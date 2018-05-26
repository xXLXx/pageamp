<?php
/**
 * @link              http://nilaypatel.info
 * @since             1.0.0
 * @package           Easy_Coupon_Code_WP
 *
 * @wordpress-plugin
 * Plugin Name:       Easy Coupon Code WP
 * Plugin URI:        http://nilaypatel.info
 * Description:       This plugin create a custom post type for coupon code that includes Coupon Start / End date, Discount in Percentage / Cash. The coupons are listed with all details so you can easily manage it from listing page. You can easily use this plugin by customization by your own to enable coupon feature to your site. 
 * Version:           2.0
 * Author:            Nilay Patel
 * Author URI:        http://nilaypatel.info
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       easy-coupon-code-wp
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * The code that runs during plugin activation.
 */
register_activation_hook( __FILE__, 'activate_np_eccw' );

function activate_np_eccw() {
		update_option('npcc_activated_on',@date('d-m-Y h:i:s'));
}

/**
 * The code that runs during plugin deactivation.
 */
register_deactivation_hook( __FILE__, 'deactivate_np_eccw' );

function deactivate_np_eccw() {
	update_option('npcc_deactivated_on',@date('d-m-Y h:i:s'));
}




$plgprefix = 'np_ccode_';

/* Register Post type */
add_action( 'init', 'create_cc_post_type' );
function create_cc_post_type() {

	register_post_type( 'np_coupon_code',
		array(
			'labels' => array(
				'name' => __( 'Coupon Code' ),
				'singular_name' => __( 'Coupon Code' ),
				'edit_item'	=>	__( 'Edit Coupon Code'),
				'add_new_item'	=>	__( 'Add Coupon Code')
			),
			'public' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'rewrite' => array( 'slug' => 'np_coupon_code'),
			'taxonomies' => array( 'Coupon Code '),
			'supports' => array('title'),
			'menu_icon'	=> plugins_url('npcc-icon.png',__FILE__ )
		)
	);
}	


function np_eccw_css_and_js() {
		wp_register_style('your_css_and_js', plugins_url('datepicker/jquery-ui.css',__FILE__ ));
		wp_enqueue_style('your_css_and_js');
		wp_register_script( 'your_css_and_js', plugins_url('datepicker/jquery-ui.js',__FILE__ ));
		wp_enqueue_script('your_css_and_js');
		wp_register_script( 'mycustomjs', plugins_url('np-coupon-code.js',__FILE__ ));
		wp_enqueue_script('mycustomjs');
}
add_action( 'admin_init','np_eccw_css_and_js');


// This function tells WP to add a new "meta box"
function add_some_box() {
    add_meta_box('np_coupon_codes', ' Add Coupon Details', 'coupon_details_box', 'np_coupon_code', 'normal', 'high');
}
// This function echoes the content of our meta box
function coupon_details_box() {
	global $post;
	global $plgprefix;
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="social_noncename" id="social_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    // Get the location data if its already been entered
    $discount_in = get_post_meta($post->ID, $plgprefix.'_discount_in', true);
	$amt_per = get_post_meta($post->ID, $plgprefix.'_amt_per', true);
	$ccstart_d = get_post_meta($post->ID, $plgprefix.'_ccstart_date', true);
	$ccstart_e = get_post_meta($post->ID, $plgprefix.'_ccend_date', true);
	$ccode = get_post_meta($post->ID, $plgprefix.'_ccode', true);
	
	
    // Echo out the field
	if($discount_in == "$"){
		$dslt = 'selected="selected"';	
	}else{
		$dslt = '';	
		}
		
	if($discount_in == "%"){
		$dslt1 = 'selected="selected"';	
	}else{
		$dslt1 = '';	
		}	
	
	echo '<table class="form-table">';
	echo '<tr><td  width="20%" class="rw-label" ><label >Coupon Code: </label></td><td><input   type="text"  name="'.$plgprefix.'_ccode" value="' . $ccode  . '" class="widefat" /></td></tr>';
	
    echo '<tr><td  width="20%" class="rw-label" ><label >Discount In:</label></td><td>
		<select name="'.$plgprefix.'_discount_in"><option '.$dslt1.'   value="%">Percent</option><option '.$dslt.' value="$">Cash Discount</option></select>
			</td></tr>';
	
	 echo '<tr><td  width="20%" class="rw-label" ><label >Discount AMT / Percent: </label></td><td><input   type="text"  name="'.$plgprefix.'_amt_per" value="' . $amt_per  . '" class="widefat" /></td></tr>';
	
	  echo '<tr><td  width="20%" class="rw-label" ><label >Coupon Code Start On: </label></td><td> <input type="text"  name="'.$plgprefix.'_ccstart_date" id="'.$plgprefix.'_ccstart_date" value="' . $ccstart_d  . '" class="widefat" /></td></tr>';
	  
	  echo '<tr><td  width="20%" class="rw-label" ><label >Coupon Code End On: </label></td><td> <input type="text"  name="'.$plgprefix.'_ccend_date" id="'.$plgprefix.'_ccend_date" value="' . $ccstart_e  . '" class="widefat" /></td></tr>';
	  echo "</table>";
}
// Hook things in, late enough so that add_meta_box() is defined

	add_action('admin_menu', 'add_some_box');
	
	
	function wpt_save_ccode_meta($post_id, $post) {
		global $plgprefix;
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( !wp_verify_nonce( @$_POST['social_noncename'], plugin_basename(__FILE__) )) {
    return $post->ID;
    }
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.
    $events_meta[$plgprefix.'_discount_in'] = $_POST[$plgprefix.'_discount_in'];
	$events_meta[$plgprefix.'_amt_per'] = $_POST[$plgprefix.'_amt_per'];
	$events_meta[$plgprefix.'_ccstart_date'] = $_POST[$plgprefix.'_ccstart_date'];
	 $events_meta[$plgprefix.'_ccend_date'] = $_POST[$plgprefix.'_ccend_date'];
	 $events_meta[$plgprefix.'_ccode'] = $_POST[$plgprefix.'_ccode'];
	 
	 
    // Add values of $events_meta as custom fields
    foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }
}
add_action('save_post', 'wpt_save_ccode_meta', 1, 2); // save the custom fields

function add_np_coupon_code_columns($columns) {

    return array_merge($columns, 
              array('np_coupon_code' => __('Coupon Code'),
			  'cccode_discount_in' => __('Discount In')));
}
add_filter('manage_np_coupon_code_posts_columns' , 'add_np_coupon_code_columns');


add_filter( 'manage_edit-np_coupon_code_columns', 'set_custom_edit_np_coupon_code_columns' );
add_action( 'manage_np_coupon_code_posts_custom_column' , 'custom_np_coupon_code_column', 10, 2 );

function set_custom_edit_np_coupon_code_columns($columns) {

    $columns['np_coupon_code'] = __( 'Coupon Code', 'nilaypatel' );
    $columns['cccode_discount_in'] = __( 'Discount In', 'nilaypatel' );
	$columns['amt_percent'] = __( 'Amt / Percent', 'nilaypatel' );
	$columns['ccstart_date'] = __( 'Start Date', 'nilaypatel' );
	$columns['ccend_date'] = __( 'End Date', 'nilaypatel' );

    return $columns;
}

function custom_np_coupon_code_column( $column, $post_id ) {
    switch ( $column ) {

        case 'np_coupon_code' :
            echo get_post_meta($post_id, 'np_ccode__ccode',true);
		break;
			
        case 'cccode_discount_in' :
          echo get_post_meta($post_id, 'np_ccode__discount_in',true);
        break;
		
		case 'amt_percent' :
          echo get_post_meta($post_id, 'np_ccode__amt_per',true);
        break;
		
		case 'ccstart_date' :
          echo get_post_meta($post_id, 'np_ccode__ccstart_date',true);
        break;
		
			case 'ccend_date' :
          echo get_post_meta($post_id, 'np_ccode__ccend_date',true);
        break;

    }
}