<?php

class AcceptStripePaymentsShortcode {

    var $AcceptStripePayments	 = null;
    var $StripeCSSInserted	 = false;
    var $ProductCSSInserted	 = false;
    var $ButtonCSSInserted	 = false;

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance		 = null;
    protected static $payment_buttons	 = array();

    function __construct() {
	$this->AcceptStripePayments = AcceptStripePayments::get_instance();

	add_action( 'wp_enqueue_scripts', array( $this, 'register_stripe_script' ) );

	add_shortcode( 'asp_show_all_products', array( &$this, 'shortcode_show_all_products' ) );
	add_shortcode( 'asp_product', array( &$this, 'shortcode_asp_product' ) );
	add_shortcode( 'accept_stripe_payment', array( &$this, 'shortcode_accept_stripe_payment' ) );
	add_shortcode( 'accept_stripe_payment_checkout', array( &$this, 'shortcode_accept_stripe_payment_checkout' ) );
	add_shortcode( 'accept_stripe_payment_checkout_error', array( &$this, 'shortcode_accept_stripe_payment_checkout_error' ) );
	if ( ! is_admin() ) {
	    add_filter( 'widget_text', 'do_shortcode' );
	}
    }

    public function interfer_for_redirect() {
	global $post;
	if ( ! is_admin() ) {
	    if ( has_shortcode( $post->post_content, 'accept_stripe_payment_checkout' ) ) {
		$this->shortcode_accept_stripe_payment_checkout();
		exit;
	    }
	}
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

	// If the single instance hasn't been set, set it now.
	if ( null == self::$instance ) {
	    self::$instance = new self;
	}

	return self::$instance;
    }

    function register_stripe_script() {
	wp_register_script( 'stripe-script', 'https://checkout.stripe.com/checkout.js', array(), null, true );
	wp_register_script( 'stripe-handler', WP_ASP_PLUGIN_URL . '/public/assets/js/stripe-handler.js', array( 'jquery' ), WP_ASP_PLUGIN_VERSION, true );
	//localization data and Stripe API key
	if ( $this->AcceptStripePayments->get_setting( 'is_live' ) == 0 ) {
	    //use test keys
	    $key = $this->AcceptStripePayments->get_setting( 'api_publishable_key_test' );
	} else {
	    //use live keys
	    $key = $this->AcceptStripePayments->get_setting( 'api_publishable_key' );
	}
	$loc_data = array(
	    'strEnterValidAmount'	 => __( 'Please enter a valid amount', 'stripe-payments' ),
	    'strMinAmount'		 => __( 'Minimum amount is 0.5', 'stripe-payments' ),
	    'key'			 => $key,
	    'strEnterQuantity'	 => __( 'Please enter quantity.', 'stripe-payments' ),
	    'strQuantityIsZero'	 => __( 'Quantity can\'t be zero.', 'stripe-payments' ),
	    'strQuantityIsFloat'	 => __( 'Quantity should be integer value.', 'stripe-payments' ),
	);
	wp_localize_script( 'stripe-handler', 'stripehandler', $loc_data );
	// addons can register their scripts if needed
	do_action( 'asp-button-output-register-script' );
    }

    function shortcode_asp_product( $atts ) {
	if ( ! isset( $atts[ 'id' ] ) || ! is_numeric( $atts[ 'id' ] ) ) {
	    $error_msg	 = '<div class="stripe_payments_error_msg" style="color: red;">';
	    $error_msg	 .= "Error: product ID is invalid.";
	    $error_msg	 .= '</div>';
	    return $error_msg;
	}
	$id	 = $atts[ 'id' ];
	$post	 = get_post( $id );
	if ( ! $post || get_post_type( $id ) != ASPMain::$products_slug ) {
	    $error_msg	 = '<div class="stripe_payments_error_msg" style="color: red;">';
	    $error_msg	 .= "Can't find product with ID " . $id;
	    $error_msg	 .= '</div>';
	    return $error_msg;
	}

	$currency = get_post_meta( $id, 'asp_product_currency', true );

	if ( ! $currency ) {
	    $currency = $this->AcceptStripePayments->get_setting( 'currency_code' );
	}

	$button_text = get_post_meta( $id, 'asp_product_button_text', true );
	if ( ! $button_text ) {
	    $button_text = $this->AcceptStripePayments->get_setting( 'button_text' );
	}

	$thumb_img	 = '';
	$thumb_url	 = get_post_meta( $id, 'asp_product_thumbnail', true );

	if ( $thumb_url ) {
	    $thumb_img = '<img src="' . $thumb_url . '">';
	}

	$url = get_post_meta( $id, 'asp_product_upload', true );

	if ( ! $url ) {
	    $url = '';
	}

	$template_name	 = 'default'; //this could be made configurable
	$button_color	 = 'blue'; //this could be made configurable

	$price	 = get_post_meta( $id, 'asp_product_price', true );
	$buy_btn = '';

	$button_class = get_post_meta( $id, 'asp_product_button_class', true );

	$class = ! empty( $button_class ) ? $button_class : 'asp_product_buy_btn ' . $button_color;

	$class = isset( $atts[ 'class' ] ) ? $atts[ 'class' ] : $class;

	$custom_field = get_post_meta( $id, 'asp_product_custom_field', true );

	if ( ( $custom_field === "" ) || $custom_field === "2" ) {
	    $custom_field = $this->AcceptStripePayments->get_setting( 'custom_field_enabled' );
	} else {
	    $custom_field = intval( $custom_field );
	}

	$thankyou_page = get_post_meta( $id, 'asp_product_thankyou_page', true );

	//Let's only output buy button if we're in the loop. Since the_content hook could be called several times (for example, by a plugin like Yoast SEO for its purposes), we should only output the button only when it's actually needed.
	if ( ! isset( $atts[ 'in_the_loop' ] ) || $atts[ 'in_the_loop' ] === "1" ) {
	    $sc_params	 = array(
		'product_id'		 => $id,
		'name'			 => $post->post_title,
		'price'			 => $price,
		'currency'		 => $currency,
		'class'			 => $class,
		'quantity'		 => get_post_meta( $id, 'asp_product_quantity', true ),
		'custom_quantity'	 => get_post_meta( $id, 'asp_product_custom_quantity', true ),
		'button_text'		 => $button_text,
		'description'		 => get_post_meta( $id, 'asp_product_description', true ),
		'url'			 => $url,
		'thankyou_page_url'	 => $thankyou_page,
		'billing_address'	 => get_post_meta( $id, 'asp_product_collect_billing_addr', true ),
		'shipping_address'	 => get_post_meta( $id, 'asp_product_collect_shipping_addr', true ),
		'custom_field'		 => $custom_field,
	    );
	    //this would pass additional shortcode parameters from asp_product shortcode
	    $sc_params	 = array_merge( $atts, $sc_params );
	    $buy_btn	 = $this->shortcode_accept_stripe_payment( $sc_params );
	}

	$button_only = get_post_meta( $id, 'asp_product_button_only', true );

	if ( (isset( $atts[ "fancy" ] ) && $atts[ "fancy" ] == '0') || $button_only == 1 ) {
	    //Just show the stripe payment button (no fancy template)
	    $tpl				 = '<div class="asp_product_buy_button">' . $buy_btn . '</div>';
	    $tpl				 = "<link rel='stylesheet' href='" . WP_ASP_PLUGIN_URL . '/public/views/templates/default/style.css' . "' type='text/css' media='all' />" . $tpl;
	    $this->productCSSInserted	 = true;
	    return $tpl;
	}

	//Show the stripe payment button with fancy style template.
	require_once(WP_ASP_PLUGIN_PATH . 'public/views/templates/' . $template_name . '/template.php');
	if ( isset( $atts[ "is_post_tpl" ] ) ) {
	    $tpl = asp_get_post_template( $this->ProductCSSInserted );
	} else {
	    $tpl = asp_get_template( $this->ProductCSSInserted );
	}
	$this->productCSSInserted	 = true;
	$tpl				 = str_replace( array( '%_thumb_img_%', '%_name_%', '%_description_%', '%_price_%', '%_buy_btn_%' ), array( $thumb_img, $post->post_title, do_shortcode( wpautop( $post->post_content ) ), AcceptStripePayments::formatted_price( $price, $currency ), $buy_btn ), $tpl );
	return $tpl;
    }

    function shortcode_accept_stripe_payment( $atts ) {

	extract( shortcode_atts( array(
	    'name'			 => '',
	    'class'			 => 'stripe-button-el', //default Stripe button class
	    'price'			 => '0',
	    'quantity'		 => '',
	    'custom_quantity'	 => false,
	    'description'		 => '',
	    'url'			 => '',
	    'thankyou_page_url'	 => '',
	    'item_logo'		 => '',
	    'billing_address'	 => '',
	    'shipping_address'	 => '',
	    'currency'		 => $this->AcceptStripePayments->get_setting( 'currency_code' ),
	    'button_text'		 => $this->AcceptStripePayments->get_setting( 'button_text' ),
	), $atts ) );

	if ( empty( $name ) ) {
	    $error_msg	 = '<div class="stripe_payments_error_msg" style="color: red;">';
	    $error_msg	 .= 'There is an error in your Stripe Payments shortcode. It is missing the "name" field. ';
	    $error_msg	 .= 'You must specify an item name value using the "name" parameter. This value should be unique so this item can be identified uniquely on the page.';
	    $error_msg	 .= '</div>';
	    return $error_msg;
	}

	if ( ! empty( $url ) ) {
	    $url = base64_encode( $url );
	} else {
	    $url = '';
	}

	if ( ! empty( $thankyou_page_url ) ) {
	    $thankyou_page_url = base64_encode( $thankyou_page_url );
	} else {
	    $thankyou_page_url = '';
	}

	if ( empty( $quantity ) && $custom_quantity !== "1" ) {
	    $quantity = 1;
	}

	if ( ! is_numeric( $quantity ) ) {
	    $quantity = strtoupper( $quantity );
	}
	if ( $quantity == "N/A" ) {
	    $quantity = "NA";
	}
	$uniq_id		 = count( self::$payment_buttons );
	$button_id		 = 'stripe_button_' . $uniq_id;
	self::$payment_buttons[] = $button_id;
	$paymentAmount		 = ($custom_quantity == "1" ? $price : (floatval( $price ) * $quantity));
	if ( in_array( $currency, $this->AcceptStripePayments->zeroCents ) ) {
	    //this is zero-cents currency, amount shouldn't be multiplied by 100
	    $priceInCents = $paymentAmount;
	} else {
	    $priceInCents = $paymentAmount * 100;
	}

	$button_key = md5( htmlspecialchars_decode( $name ) . $priceInCents );

	//Charge description
	//We only generate it if it's empty and if custom qunatity and price is not used
	//If custom quantity and\or price are used, description will be generated by javascript
	if ( empty( $description ) && $custom_quantity !== '1' && ( ! empty( $price ) && $price !== 0) ) {
	    //Create a description using quantity, payment amount and currency
	    $description = "{$quantity} X " . AcceptStripePayments::formatted_price( $paymentAmount, $currency );
	}
	//This is public.css stylesheet
	//wp_enqueue_style('stripe-button-public');
	//$button = "<button id = '{$button_id}' type = 'submit' class = '{$class}'><span>{$button_text}</span></button>";
	$button = sprintf( '<button id="%s" type="submit" class="%s"><span>%s</span></button>', esc_attr( $button_id ), esc_attr( $class ), sanitize_text_field( $button_text ) );

	$checkout_lang = $this->AcceptStripePayments->get_setting( 'checkout_lang' );

	$allowRememberMe = $this->AcceptStripePayments->get_setting( 'disable_remember_me' );

	$allowRememberMe = ($allowRememberMe === 1) ? false : true;

	$custom_field = $this->AcceptStripePayments->get_setting( 'custom_field_enabled' );
	if ( isset( $atts[ 'custom_field' ] ) ) {
	    $custom_field = $atts[ 'custom_field' ];
	}

	$data = array(
	    'button_key'		 => $button_key,
	    'allowRememberMe'	 => $allowRememberMe,
	    'quantity'		 => $quantity,
	    'custom_quantity'	 => $custom_quantity,
	    'description'		 => $description,
	    'image'			 => $item_logo,
	    'currency'		 => $currency,
	    'locale'		 => (empty( $checkout_lang ) ? 'auto' : $checkout_lang),
	    'name'			 => htmlspecialchars_decode( $name ),
	    'url'			 => $url,
	    'amount'		 => $priceInCents,
	    'billingAddress'	 => (empty( $billing_address ) ? false : true),
	    'shippingAddress'	 => (empty( $shipping_address ) ? false : true),
	    'uniq_id'		 => $uniq_id,
	    'variable'		 => ($price == 0 ? true : false),
	    'zeroCents'		 => $this->AcceptStripePayments->zeroCents,
	    'addonHooks'		 => array(),
	    'custom_field'		 => $custom_field,
	);

	$data = apply_filters( 'asp-button-output-data-ready', $data, $atts );

	$output = '';

	//Let's insert Stripe default stylesheet only when it's needed
	if ( $class == 'stripe-button-el' && ! ($this->StripeCSSInserted) ) {
	    $output			 = "<link rel = 'stylesheet' href = 'https://checkout.stripe.com/v3/checkout/button.css' type = 'text/css' media = 'all' />";
	    $this->StripeCSSInserted = true;
	}

	$output .= "<form id = 'stripe_form_{$uniq_id}' class='asp-stripe-form' action = '' METHOD = 'POST'> ";

//	if ( $price == 0 || $custom_quantity !== false || $this->AcceptStripePayments->get_setting( 'use_new_button_method' ) ) {
	// variable amount or new method option is set in settings
	$output	 .= $this->get_button_code_new_method( $data );
//	} else {
	// use old method instead
//	    $output .= $this->get_button_code_old_method( $data, $price, $button_text );
//	}
	$output	 .= '<input type="hidden" name="asp_action" value="process_ipn" />';
	$output	 .= "<input type = 'hidden' value = '{$data[ 'name' ]}' name = 'item_name' />";
	$output	 .= "<input type = 'hidden' value = '{$data[ 'quantity' ]}' name = 'item_quantity' />";
	$output	 .= "<input type = 'hidden' value = '{$data[ 'currency' ]}' name = 'currency_code' />";
	$output	 .= "<input type = 'hidden' value = '{$data[ 'url' ]}' name = 'item_url' />";
	$output	 .= "<input type = 'hidden' value = '{$thankyou_page_url}' name = 'thankyou_page_url' />";
	$output	 .= "<input type = 'hidden' value = '{$data[ 'description' ]}' name = 'charge_description' />"; //

	$trans_name	 = 'stripe-payments-' . $button_key; //Create key using the item name.
	set_transient( $trans_name, $price, 2 * 3600 ); //Save the price for this item for 2 hours.
	$output		 .= wp_nonce_field( 'stripe_payments', '_wpnonce', true, false );
	$output		 .= $button;
	//after button filter
	$output		 = apply_filters( 'asp-button-output-after-button', $output, $data, $class );
	$output		 .= "</form>";
	return $output;
    }

    function get_button_code_old_method( $data, $price, $button_text ) {
	if ( $this->AcceptStripePayments->get_setting( 'is_live' ) == 0 ) {
	    //use test keys
	    $key = $this->AcceptStripePayments->get_setting( 'api_publishable_key_test' );
	} else {
	    //use live keys
	    $key = $this->AcceptStripePayments->get_setting( 'api_publishable_key' );
	}
	$output	 = "<input type = 'hidden' value = '{$data[ 'amount' ]}' name = 'stripeItemPrice' />";
	$output	 .= "<input type = 'hidden' value = '{$data[ 'button_key' ]}' name='stripeButtonKey'>";
	//Lets hide default Stripe button. We'll be using our own instead for styling purposes
	$output	 .= "<div style = 'display: none !important'>";
	$output	 .= "<script src = 'https://checkout.stripe.com/checkout.js' class = 'stripe-button'
	data-key = '" . $key . "'
	data-panel-label = 'Pay'
	data-amount = '{$data[ 'amount' ]}'
	data-name = '{$data[ 'name' ]}'
	data-allow-remember-me = '{$data[ 'allowRememberMe' ]}'
	data-description = '{$data[ 'description' ]}'
	data-label = '{$button_text}'
	data-currency = '{$data[ 'currency' ]}'";
	$output	 .= "data-locale = '{$data[ 'locale' ]}'";
	if ( ! empty( $data[ 'image' ] ) ) {//Show item logo/thumbnail in the stripe payment window
	    $output .= "data-image = '{$data[ 'image' ]}'";
	}

	if ( $data[ 'billingAddress' ] ) {
	    $output .= "data-billing-address = '{$data[ 'billingAddress' ]}'";
	}
	if ( $data[ 'shippingAddress' ] ) {
	    $output .= "data-shipping-address = '{$data[ 'shippingAddress' ]}'";
	}
	$output	 .= apply_filters( 'asp_additional_stripe_checkout_data_parameters', '' ); //Filter to allow the addition of extra data parameters for stripe checkout.
	$output	 .= "></script>";
	$output	 .= '</div>';
	return $output;
    }

    function get_button_code_new_method( $data ) {
	$output = '';
	if ( ! $this->ButtonCSSInserted ) {
	    $this->ButtonCSSInserted = true;
	    // we need to style custom inputs
	    ob_start();
	    ?>

	    <style>
	        .asp_product_buy_button input {
	    	display: inline-block;
	    	line-height: 1;
	    	padding: 8px 10px;
	        }

	        .asp_product_buy_button input::placeholder {
	    	font-style: italic;
	    	color: #bbb;
	        }
	        @keyframes blink {
	    	0% {
	    	    opacity: .2;
	    	}
	    	20% {
	    	    opacity: 1;
	    	}
	    	100% {
	    	    opacity: 0;
	    	}
	        }
	        .asp-processing-cont {
	    	display: none;
	        }
	        .asp-processing i {
	    	animation-name: blink;
	    	animation-duration: 1s;
	    	animation-iteration-count: infinite;
	    	animation-fill-mode: both;
	        }
	        .asp-processing i:nth-child(2) {
	    	animation-delay: .1s;
	        }
	        .asp-processing i:nth-child(3) {
	    	animation-delay: .2s;
	        }
	    </style>
	    <?php
	    $output			 .= ob_get_clean();
	    //addons can output their styles if needed
	    $output			 = apply_filters( 'asp-button-output-additional-styles', $output );
	    ob_start();
	    ?>
	    <div class="asp-processing-cont"><span class="asp-processing">Processing <i>.</i><i>.</i><i>.</i></span></div>
	    <?php
	    $output			 .= ob_get_clean();
	}
	if ( $data[ 'amount' ] == 0 ) { //price not specified, let's add an input box for user to specify the amount
	    $output .= "<div class='asp_product_item_amount_input_container'>"
	    . "<input type='text' size='10' class='asp_product_item_amount_input' id='stripeAmount_{$data[ 'uniq_id' ]}' value='' name='stripeAmount' placeholder='" . __( 'Enter amount', 'stripe-payments' ) . "' required/>"
	    . "<span class='asp_product_item_amount_currency_label' style='margin-left: 5px; display: inline-block'> {$data[ 'currency' ]}</span>"
	    . "<span style='display: block;' id='error_explanation_{$data[ 'uniq_id' ]}'></span>"
	    . "</div>";
	}
	if ( $data[ 'custom_quantity' ] === "1" ) { //we should output input for customer to input custom quantity
	    if ( empty( $data[ 'quantity' ] ) ) {
		//If quantity option is enabled and the value is empty then set default quantity to 1 so the number field type can handle it better.
		$data[ 'quantity' ] = 1;
	    }
	    $output .= "<div class='asp_product_item_qty_input_container'>"
	    . "<input type='number' min='1' size='6' class='asp_product_item_qty_input' id='stripeCustomQuantity_{$data[ 'uniq_id' ]}' value='{$data[ 'quantity' ]}' name='stripeCustomQuantity' placeholder='" . __( 'Enter quantity', 'stripe-payments' ) . "' value='{$data[ 'quantity' ]}' required/>"
	    . "<span class='asp_product_item_qty_label' style='margin-left: 5px; display: inline-block'> " . __( 'X item(s)', 'stripe-payments' ) . "</span>"
	    . "<span style='display: block;' id='error_explanation_quantity_{$data[ 'uniq_id' ]}'></span>"
	    . "</div>";
	}
	if ( $data[ 'custom_field' ] == 1 ) {
	    $field_type	 = $this->AcceptStripePayments->get_setting( 'custom_field_type' );
	    $field_name	 = $this->AcceptStripePayments->get_setting( 'custom_field_name' );
	    $field_descr	 = $this->AcceptStripePayments->get_setting( 'custom_field_descr' );
	    $mandatory	 = $this->AcceptStripePayments->get_setting( 'custom_field_mandatory' );
	    $output		 .= "<div class='asp_product_custom_field_input_container'>";
	    $output		 .= '<input type="hidden" name="stripeCustomFieldName" value="' . esc_attr( $field_name ) . '">';
	    switch ( $field_type ) {
		case 'text':
		    $output	 .= '<label class="asp_product_custom_field_label">' . $field_name . ' ' . '</label><input id="asp-custom-field-' . $data[ 'uniq_id' ] . '" class="asp_product_custom_field_input" type="text"' . ($mandatory ? ' data-asp-custom-mandatory' : '') . ' name="stripeCustomField" placeholder="' . $field_descr . '"' . ($mandatory ? ' required' : '' ) . '>';
		    break;
		case 'checkbox':
		    $output	 .= '<label class="asp_product_custom_field_label"><input id="asp-custom-field-' . $data[ 'uniq_id' ] . '" class="asp_product_custom_field_input" type="checkbox"' . ($mandatory ? ' data-asp-custom-mandatory' : '') . ' name="stripeCustomField"' . ($mandatory ? ' required' : '' ) . '>' . $field_descr . '</label>';
		    break;
	    }
	    $output .= "<span style='display: block;' id='custom_field_error_explanation_{$data[ 'uniq_id' ]}'></span>" .
	    "</div>";
	}
	if ( $data ) {
	    $output .= "<input type='hidden' id='stripeToken_{$data[ 'uniq_id' ]}' name='stripeToken' />"
	    . "<input type='hidden' id='stripeTokenType_{$data[ 'uniq_id' ]}' name='stripeTokenType' />"
	    . "<input type='hidden' id='stripeEmail_{$data[ 'uniq_id' ]}' name='stripeEmail' />"
	    . "<input type='hidden' name='stripeButtonKey' value='{$data[ 'button_key' ]}' />"
	    . "<input type='hidden' name='stripeItemPrice' value='{$data[ 'amount' ]}' />"
	    . "<input type='hidden' data-stripe-button-uid='{$data[ 'uniq_id' ]}' />";
	}
	//Let's enqueue Stripe js
	wp_enqueue_script( 'stripe-script' );
	//using nested array in order to ensure boolean values are not converted to strings by wp_localize_script function
	wp_localize_script( 'stripe-handler', 'stripehandler' . $data[ 'uniq_id' ], array( 'data' => $data ) );
	//enqueue our script that handles the stuff
	wp_enqueue_script( 'stripe-handler' );
	//addons can enqueue their scripts if needed
	do_action( 'asp-button-output-enqueue-script' );
	return $output;
    }

    function shortcode_accept_stripe_payment_checkout( $atts, $content = '' ) {
	if ( ! defined( 'DONOTCACHEPAGE' ) ) {
	    define( 'DONOTCACHEPAGE', true );
	}
	$aspData = array();
	if ( isset( $_SESSION[ 'asp_data' ] ) ) {
	    $aspData = $_SESSION[ 'asp_data' ];
	} else {
	    // no session data, let's display nothing for now
	    return;
	}
	if ( empty( $content ) ) {
	    //this is old shortcode. Let's display the default output for backward compatability
	    if ( isset( $aspData[ 'error_msg' ] ) && ! empty( $aspData[ 'error_msg' ] ) ) {
		//some error occured, let's display it
		return __( "System was not able to complete the payment.", "stripe-payments" ) . ' ' . $aspData[ 'error_msg' ];
	    }
	    $output	 = '';
	    $output	 .= '<p class="asp-thank-you-page-msg1">' . __( "Thank you for your payment.", "stripe-payments" ) . '</p>';
	    $output	 .= '<p class="asp-thank-you-page-msg2">' . __( "Here's what you purchased: ", "stripe-payments" ) . '</p>';
	    $output	 .= '<div class="asp-thank-you-page-product-name">' . __( "Product Name: ", "stripe-payments" ) . $aspData[ 'item_name' ] . '</div>';
	    $output	 .= '<div class="asp-thank-you-page-qty">' . __( "Quantity: ", "stripe-payments" ) . $aspData[ 'item_quantity' ] . '</div>';
	    $output	 .= '<div class="asp-thank-you-page-qty">' . __( "Item Price: ", "stripe-payments" ) . AcceptStripePayments::formatted_price( $aspData[ 'item_price' ], $aspData[ 'currency_code' ] ) . '</div>';
	    $output	 .= '<div class="asp-thank-you-page-qty">' . __( "Total Amount: ", "stripe-payments" ) . AcceptStripePayments::formatted_price( $aspData[ 'paid_amount' ], $aspData[ 'currency_code' ] ) . '</div>';
	    $output	 .= '<div class="asp-thank-you-page-txn-id">' . __( "Transaction ID: ", "stripe-payments" ) . $aspData[ 'txn_id' ] . '</div>';

	    if ( ! empty( $aspData[ 'item_url' ] ) ) {
		$output	 .= "<div class='asp-thank-you-page-download-link'>";
		$output	 .= __( "Please ", "stripe-payments" ) . "<a href='" . $aspData[ 'item_url' ] . "'>" . __( "click here", "stripe-payments" ) . "</a>" . __( " to download.", "stripe-payments" );
		$output	 .= "</div>";
	    }

	    $output = apply_filters( 'asp_stripe_payments_checkout_page_result', $output, $aspData ); //Filter that allows you to modify the output data on the checkout result page

	    $wrap	 = "<div class='asp-thank-you-page-wrap'>";
	    $wrap	 .= "<div class='asp-thank-you-page-msg-wrap' style='background: #dff0d8; border: 1px solid #C9DEC1; margin: 10px 0px; padding: 15px;'>";
	    $output	 = $wrap . $output;
	    $output	 .= "</div>"; //end of .asp-thank-you-page-msg-wrap
	    $output	 .= "</div>"; //end of .asp-thank-you-page-wrap

	    return $output;
	}
	if ( isset( $aspData[ 'error_msg' ] ) && ! empty( $aspData[ 'error_msg' ] ) ) {
	    //some error occured. We don't display any content to let the error shortcode handle it
	    return;
	}
	$content = $this->apply_content_tags( do_shortcode( $content ), $aspData );
	return $content;
    }

    function shortcode_accept_stripe_payment_checkout_error( $atts, $content = '' ) {
	$aspData = array();
	if ( isset( $_SESSION[ 'asp_data' ] ) ) {
	    $aspData = $_SESSION[ 'asp_data' ];
	} else {
	    // no session data, let's display nothing for now
	    return;
	}
	if ( isset( $aspData[ 'error_msg' ] ) && ! empty( $aspData[ 'error_msg' ] ) ) {
	    //some error occured. Let's display error message
	    $content = $this->apply_content_tags( do_shortcode( $content ), $aspData );
	    return $content;
	}
	// no error occured - we don't display anything
	return;
    }

    function shortcode_show_all_products( $params ) {

	include_once(WP_ASP_PLUGIN_PATH . 'public/views/all-products/default/template.php');

	$page = isset( $_GET[ 'asp_page' ] ) && ! empty( $_GET[ 'asp_page' ] ) ? intval( $_GET[ 'asp_page' ] ) : 1;

	$q = array(
	    'post_type'	 => ASPMain::$products_slug,
	    'post_status'	 => 'publish',
	    'posts_per_page' => $params[ 'items_per_page' ],
	    'paged'		 => $page,
	    'orderby'	 => $params[ 'sort_by' ],
	    'order'		 => strtoupper( $params[ 'sort_order' ] ),
	);

	//handle search

	$search = isset( $_GET[ 'asp_search' ] ) && ! empty( $_GET[ 'asp_search' ] ) ? sanitize_text_field( $_GET[ 'asp_search' ] ) : false;

	if ( $search !== false ) {
	    $q[ 's' ] = $search;
	}

	$products = new WP_Query( $q );

	if ( ! $products->have_posts() ) {
	    //query returned no results. Let's see if that was a search query
	    if ( $search === false ) {
		//that wasn't search query. That means there is no products configured
		return 'No products have been configured yet';
	    }
	}

	if ( $params[ 'search_box' ] !== '1' ) {
	    $tpl[ 'search_box' ] = '';
	} else {
	    if ( $search !== false ) {
		$tpl[ 'clear_search_url' ]	 = esc_url( remove_query_arg( array( 'asp_search', 'asp_page' ) ) );
		$tpl[ 'search_result_text' ]	 = $products->found_posts === 0 ? 'Nothing found for "%s".' : 'Search results for "%s".';
		$tpl[ 'search_result_text' ]	 = sprintf( $tpl[ 'search_result_text' ], htmlentities( $search ) );
		$tpl[ 'search_term' ]		 = htmlentities( $search );
	    } else {
		$tpl[ 'search_result_text' ]	 = '';
		$tpl[ 'clear_search_button' ]	 = '';
		$tpl[ 'search_term' ]		 = '';
	    }
	}

	$tpl[ 'products_list' ]	 .= $tpl[ 'products_row_start' ];
	$i			 = $tpl[ 'products_per_row' ]; //items per row

	while ( $products->have_posts() ) {
	    $products->the_post();
	    $i --;
	    if ( $i < 0 ) { //new row
		$tpl[ 'products_list' ]	 .= $tpl[ 'products_row_end' ];
		$tpl[ 'products_list' ]	 .= $tpl[ 'products_row_start' ];
		$i			 = $tpl[ 'products_per_row' ];
	    }

	    $id = get_the_ID();

	    $thumb_url = get_post_meta( $id, 'asp_product_thumbnail', true );
	    if ( ! $thumb_url ) {
		$thumb_url = WP_ASP_PLUGIN_URL . '/assets/product-thumb-placeholder.png';
	    }

	    $view_btn = str_replace( '%[product_url]%', get_permalink(), $tpl[ 'view_product_btn' ] );

	    $price	 = get_post_meta( $id, 'asp_product_price', true );
	    $curr	 = get_post_meta( $id, 'asp_product_currency', true );
	    $price	 = AcceptStripePayments::formatted_price( $price, $curr );
	    if ( empty( $price ) ) {
		$price = '&nbsp';
	    }

	    $item			 = str_replace(
	    array(
		'%[product_id]%', '%[product_name]%', '%[product_thumb]%', '%[view_product_btn]%', '%[product_price]%'
	    ), array(
		$id, get_the_title(), $thumb_url, $view_btn, $price
	    ), $tpl[ 'products_item' ] );
	    $tpl[ 'products_list' ]	 .= $item;
	}

	$tpl[ 'products_list' ] .= $tpl[ 'products_row_end' ];

	//pagination

	$tpl[ 'pagination_items' ] = '';

	$pages = $products->max_num_pages;

	if ( $pages > 1 ) {
	    $i = 1;

	    while ( $i <= $pages ) {
		if ( $i != $page ) {
		    $url	 = esc_url( add_query_arg( 'asp_page', $i ) );
		    $str	 = str_replace( array( '%[url]%', '%[page_num]%' ), array( $url, $i ), $tpl[ 'pagination_item' ] );
		} else
		    $str				 = str_replace( '%[page_num]%', $i, $tpl[ 'pagination_item_current' ] );
		$tpl[ 'pagination_items' ]	 .= $str;
		$i ++;
	    }
	}

	if ( empty( $tpl[ 'pagination_items' ] ) ) {
	    $tpl[ 'pagination' ] = '';
	}

	wp_reset_postdata();

	//Build template
	foreach ( $tpl as $key => $value ) {
	    $tpl[ 'page' ] = str_replace( '_%' . $key . '%_', $value, $tpl[ 'page' ] );
	}

	return $tpl[ 'page' ];
    }

    function apply_content_tags( $content, $data ) {
	$tags	 = array();
	$vals	 = array();

	foreach ( $data as $key => $value ) {
	    if ( $key == 'stripeEmail' ) {
		$key = 'payer_email';
	    }
	    if ( $key == 'txn_id' ) {
		$key = 'transaction_id';
	    }
	    $tags[]	 = '{' . $key . '}';
	    $vals[]	 = $value;
	}

	$content = str_replace( $tags, $vals, $content );
	return $content;
    }

}
