<?php 
/*
Template Name: submit
*/
get_header();

?>
<?php
// Turn off error reporting
error_reporting(1);

// Report runtime errors
//error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Report all errors
//error_reporting(E_ALL);

// Same as error_reporting(E_ALL);
//ini_set("error_reporting", E_ALL);

// Report all errors except E_NOTICE
//error_reporting(E_ALL & ~E_NOTICE);
?>

<?php
//check whether stripe token is not empty
if(!empty($_POST['stripeToken'])){
    
    // print_r($_POST);
    //get token, card and user info from the form
    $token  = $_POST['stripeToken'];
    $website_url=isset($_POST['website_url'])?$_POST['website_url']:'';
    $boost1=isset($_POST['boost1'])?$_POST['boost1']:'';
   
    $boost2=isset($_POST['boost2'])?$_POST['boost2']:'';
   
    $grand_tt =isset($_POST['grand_tt'])?$_POST['grand_tt']:'';
    $plan_name=isset($_POST['plan_name'])?$_POST['plan_name']:'';
    $username =isset($_POST['username'])?$_POST['username']:'';
    $user_email =isset($_POST['useremail'])?$_POST['useremail']:'';
    $address =isset($_POST['address'])?$_POST['address']:'';
    $zipcode = isset($_POST['zipcode'])?$_POST['zipcode']:'';
    $ccNumber = isset($_POST['ccNumber'])?$_POST['ccNumber']:'';
    $exp_month = isset($_POST['exp_month'])?$_POST['exp_month']:'';
    $exp_year = isset($_POST['exp_year'])?$_POST['exp_year']:'';
    $cvv  = isset($_POST['cvv'])?$_POST['cvv']:'';
   
    require_once(dirname(__FILE__) .'/stripe/vendor/autoload.php');
    //include Stripe PHP library
    require_once(dirname(__FILE__) .'/stripe/vendor/stripe/stripe-php/init.php');
    
     $stripe = array(
      "secret_key"      => "sk_live_FMKGOOcHkvTzNHhkyHvxfE8I",
      "publishable_key" => "pk_live_knmuxeSNsl5OpXZ6PkIDD8eb"
    );
    
    \Stripe\Stripe::setApiKey($stripe['secret_key']);
    
    //add customer to stripe
    $customer = \Stripe\Customer::create(array(
        'email' => $user_email,
        'source'  => $token
    ));
    
    // print_r($customer);
    //item information
    $itemName = $plan_name;
   // $itemNumber = "PS123456";
    $itemPrice = $grand_tt;
    $currency = "usd";
    $orderID = "SKA92712382139";
    
    //charge a credit or a debit card
    $charge = \Stripe\Charge::create(array(
        'customer' => $customer->id,
        'amount'   => $itemPrice,
        'currency' => $currency,
        'description' => $itemName,
        'metadata' => array(
            'order_id' => $orderID
        )
    ));
  
    //retrieve charge details
    $chargeJson = $charge->jsonSerialize();
    
    //check whether the charge is successful
    if($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1){
        //order details 
        $amount = $chargeJson['amount'];
        $balance_transaction = $chargeJson['balance_transaction'];
        $currency = $chargeJson['currency'];
        $status = $chargeJson['status'];
        $date = date("Y-m-d H:i:s");
        
        //include database config file
        	$wpdb->insert( 'wp_order',
  array('user_name' => $username,
        'user_email' =>$user_email,
        'website_url'=>$website_url,
        'boost1'=>$boost1,
        'boost2'=>$boost2,
        'address' =>$address,
        'zipcode'=>$zipcode,
         'card_num' =>$ccNumber, 
         'card_cvc'=>$cvv,
        'card_exp_month' =>$exp_month,
          'card_exp_year' =>$exp_year,
        'item_name' =>$itemName,
         'item_price' =>$itemPrice,
         'item_price_currency'=>$currency,
         'paid_amount'=>$amount,
         'txn_id'=>$balance_transaction,
         'status'=>$status,
         'date'=>$date
          
        ), //data
                array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s') //data format     
        );

        
        $lastid = $wpdb->insert_id;
        
        
        //if order inserted successfully
        if($lastid && $status == 'succeeded'){
            ?>
            <?php
            if ( have_posts() ) : while ( have_posts() ) : the_post();
            ?>
            <section class="hero_sec" id="small_orng">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="wyt_box">
                    <img src="<?php echo wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()));  ?>" class="img-fluid mx-auto d-block" alt="..">
                    <?php the_content(); ?>
                    <button class="btn btn_conti"> CONTINUE</button>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
endwhile; endif; 
?>
<?php
        }else{
            ?>
             <section class="hero_sec" id="small_orng">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="wyt_box">
            <p class="your_txt">"Transaction has been failed"</p>
            </div>
            </div>
        </div>
    </div>
</section>
<?php
        }
    }else{
        $statusMsg = "Transaction has been failed";
    }
}else{
    $statusMsg = "Form submission error.......";
}
echo $statusMsg;
get_footer();
//show success or error message
?>


