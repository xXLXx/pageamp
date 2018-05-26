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
    $itemPrice = $grand_tt * 100;
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
        $amount = $chargeJson['amount']/100;
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
            
            $to2='success@page-amp.com';


$subject2 = 'Order Mail from PAGE-AMP';

$messages2 = '
   <html>
  <head><meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
  <title>Order Mail from PAGE-AMP</title>
  </head>
  <body>
 <div style="">
<table width="503" style="background-color:#89cbe3;border: 1px dashed #00adef;font-size: 21px;    border-radius: 1px;">
<tbody>
<tr style="background:#89cbe3;color:#ffffff;border-bottom:5px solid #00adef;word-break:break-word;border-collapse:collapse!important;vertical-align:top;padding:0;padding-top:10px;text-align:center;margin-bottom:0px" valign="top">
  
</tr>
<tr style="color: white;">

<td align="right" colspan="2">Order Mail from PAGE-AMP </td>
</tr>
</tbody>
</table>
<table width="503" style="background-color: white;    border: 1px dashed;    font-size: 21px;    border-radius: 1px;">
<tbody> 
    <tr>
      <td><b>User Name :</b></td>
      <td>'. $username.'</td>
    </tr>
 <tr>
      <td><b>Email:</b></td>
      <td>'.$user_email.'</td>
    </tr> <tr>
      <td><b>Website Url:</b></td>
      <td>'.$website_url.'</td>
    </tr> <tr>
      <td><b>Address:</b></td>
      <td>'.$address.'</td>
    </tr> 
     <tr>
      <td><b>Package Name :</b></td>
      <td>'.$itemName.'</td>
    </tr> 
     <tr>
      <td><b>Paid Amount :</b></td>
      <td>'.$amount.'</td>
    </tr>
   
    <tr>
      <td><b> Transaction ID:</b></td>
      <td>'.$balance_transaction.'</td>
    </tr>
    
      <tr>
      <td><b>Payment Status :</b></td>
      <td>'.$status.'</td>
    </tr>
   <tr>
      <td><b>Payment Date :</b></td>
      <td>'.$date.'</td>
    </tr>
     
 </table>
 </div>
  </body>
  </html>
';
$headers3 = "MIME-Version: 1.0" . "\r\n";
$headers3 .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers3 .= 'From: <'.$to2.'>' . "\r\n";



if(mail($to2,$subject2,$messages2,$headers3))
{
//$msg2 = '<span style="color:#32CD32; font-size:16px;margin-left: 14px;">Thank you...Your message has been submitted to us. We will be in touch shortly.</span>';
  // send mail end ======================================================
}
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


