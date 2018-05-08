<?php
/*
Template Name: New Checkout
*/
get_header('checkout');

if($_GET['id']!='')
{
    $plan_id=$_GET['id'];
   
    
   
}
 if($plan_id =='1')
    {
        
        $amp_plan ='classic';
        $amp_price =get_field('amp_price' , 8);
      
    }
    else if($plan_id =='2')
    {
        $amp_plan ='Pro+';
        $amp_price =get_field('amp_pro_price', 8);
    }
?>

<!-- start chekout -->
<section class="checkout">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/page_logo.png" class="img-fluid d-block mx-auto page_logo">
                <div class="space70 hidden-xs"></div>
                <div class="space10 visible-xs"></div>
                <div class="wyt_box">
                    <div class="row bdr_btm">
                        <div class="col-lg-7  col-sm-7 col-12 ryt_bdr">
                            <div class="media">
                              <img class="mr-3 msg" src="<?php echo get_stylesheet_directory_uri(); ?>/img/order.png" alt="Sample photo">
                              <div class="media-body">
                                <h4> YOUR ORDER DETAILS</h4>
                              </div>            
                            </div>
                            <div class="row space20">
                                <div class="col-lg-3 col-sm-3 col-4">
                                    <h5> PACKAGE</h5>
                                  <h6 class="package_plan"><?php echo $amp_plan;?></h6>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-4">
                                    <h5> EXTRAS </h5>
                                    <h6>Included</h6>
                                </div>
                                <div class="col-lg-2 col-sm-2 col-4">
                                    <h5> PRICE </h5>
                                   <h6>$<span class="grand_total"><?php echo $amp_price; ?></span></h6>
                                </div>
                                <div class="col-lg-4 col-sm-4 hidden-xs">
                                    <button class="btn btn_order">complete order</button>
                                </div>
                            </div> 
                            <div class="coupn_code">
                                <input type="email" name="email" placeholder=" COUPON CODE" class="form-control">
                                <button type="button" class="btn btn_apply">Apply   </button>
                            </div>
                            <button class="btn btn_complte visible-xs">complete order</button>
                        </div>

                        <div class="col-lg-5 col-sm-5 col-12">
                            <div class="slit_lft">
                                <div class="media hidden-xs">
                                  <img class="mr-3 msg" src="<?php echo get_stylesheet_directory_uri(); ?>/img/help.png" alt="Sample photo">
                                  <div class="media-body">
                                    <h4>HELP CENTER</h4>

                                  </div>            
                                </div>
                                <p class="space30">For help with your order, open a live chat or <br><span><a href="#"> help@pageamp.com</span></a></p>
                            </div>    
                            <!-- <h4 class="helpcentr_txt">HELP CENTER</h4> -->
                            
                        </div>
                    </div>

                    <div class="your offset-lg-1 col-lg-10">
                        <div class="row">
                            <div class="col-lg-12">
                                 <?php
                                  if ( have_posts() ) :
              while ( have_posts() ) : the_post();
              $id1=get_the_ID();
              $optimize_title = get_field('optimize_title' ,$id1);
              
              $pageamp_image =get_field('pageamp_image' ,$id1);
              $pageamppro_image = get_field('pageamppro_image' ,$id1);
              
                                ?>
                                <h1> <?php echo $optimize_title;  ?></h1>
                                <div class="row main_check">
                                    <div class="col-lg-6 col-sm-6 col-6 padding_ryt ">
                                        <div class="classic">
                                            
                                            <input type="radio" data-id="<?php echo get_field('amp_price', 8);?>"   <?php echo ($plan_id=='1')?'checked':'' ?>  id="first" name="plan" value="classic" />
                                            <label for="first">
                                                <img src="<?php echo $pageamp_image['url']?>" class="img-fluid mx-auto d-block img_cursr">
                                            </label>



                                            <!-- <img src="img/p2.png" class="img-fluid mx-auto d-block"> -->
                                            <ul class="list-unstyled tik_line">
                                                <?php
                                            if( have_rows('classic_services' ,$id1) ):
          
            while( have_rows('classic_services' ,$id1) ): the_row();
            $classic_service_name = get_sub_field('classic_service_name' ,$id1);
                                            ?>
                                            <li><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/blu_tik.png" class="tik"><?php echo $classic_service_name;?></li>
                                            <?php 
                                            endwhile;
                                            endif;
                                            ?>                                                
                                            </ul>
                                        </div>

                                    </div>

                                    <div class="col-lg-6 col-sm-6 col-6 padding_lft">
                                        <a  class="upgrade_package">UPGRADE AND SAVE</a>
                                        <input type="radio" <?php echo ($plan_id=='2')?'checked':'' ?>  data-id="<?php echo get_field('amp_pro_price', 8);?>"  id="second"  name="plan" value="Pro+" />
                                        <label for="second">
                                            <img src="<?php echo $pageamppro_image['url']?>" class="img-fluid mx-auto d-block img_cursr">
                                        </label>


                                        <!-- <img src="img/p3.png" class="img-fluid mx-auto d-block"> -->
                                        <h6 class="in_txt">In Addition to ALL Standard Optimizations</h6>
                                        <ul class="list-unstyled tik_line one">
                                        <?php
                                            if( have_rows('pro_services' ,$id1) ):
          
            while( have_rows('pro_services' ,$id1) ): the_row();
            $pro_service_name = get_sub_field('pro_service_name' ,$id1);
                                            ?>
                                            <li><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/orng_tik.png" class="tik"><?php echo $pro_service_name;?></li>
                                            <?php 
                                            endwhile;
                                            endif;
                                            ?>     
                                         </ul>
                                        <!-- <button class="btn btn_downgrd">DOWNGRADE PACKAGE</button> -->

                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn_downgrd" data-toggle="modal" data-target="#myModal">
                                         DOWNGRADE PACKAGE
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                          <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                              <div class="modal-body">
                                               Are you sure you want to downgrade your package? <br>
                                               <br>The PRO+ Package is recommended for the best results and ROI of services. 24 hour turnaround and advanced optimizations will not be included free of charge.
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn_modal btn_downgrd downgrade_btn" data-dismiss="modal">DOWNGRADE</button>
                                                <button type="button" class="btn btn_modal btn_order" data-dismiss="modal">CONTINUE WITH PRO+</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                 <?php
                                endwhile;
                                endif;
                                ?>
                            </div>
                        </div>
                       <form action="<?php echo get_site_url()?>/submit" method="post" id="paymentFrm">
                        <div class="row space70">
                            <div class="col-lg-12 col-sm-9 col-12">
                                <div class="media">
                                  <img class="align-self-start mr-3 lo1 hidden-xs" src="<?php echo get_stylesheet_directory_uri(); ?>/img/l1.png" alt="...">
                                  <div class="media-body">
                                    <span><img class="align-self-start mr-3 lo1 visible-xs  l_img" src="<?php echo get_stylesheet_directory_uri(); ?>/img/l1.png" alt="..."></span><h3> CONFIRM YOUR WEBSITE URL </h3>
                                    <div class="form-group cnfrm ">
                                        <input type="text" class="form-control" id="exampleInputEmail1"  name="website_url"  aria-describedby="emailHelp" placeholder="www.yourwebsite.com">
                                    </div>                        
                                  </div>
                                </div>
                             <!--    <div class="form-group cnfrm visible-xs">
                                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="www.yourwebsite.com">
                                </div> -->
                            </div>
                        </div>


                        <div class="row space50">
                            <div class="col-lg-12 col-sm-12 col-12">
                                <div class="media">
                                  <img class="align-self-start mr-3 lo1 hidden-xs" src="<?php echo get_stylesheet_directory_uri(); ?>/img/l2.png" alt="...">
                                  <div class="media-body">
                                    <span> <img class="align-self-start mr-3 lo1 visible-xs l_img" src="<?php echo get_stylesheet_directory_uri(); ?>/img/l2.png" alt="..."></span><h3> BOOSTS </h3>
                                        <div class="row org1">
                                               <?php
                                  if ( have_posts() ) :
              while ( have_posts() ) : the_post();
              $id1=get_the_ID();
              
              $boost_image1 =get_field('boost_image1' ,$id1);
                $boost_1_price =get_field('boost_1_price' ,$id1);
                  $boost2_image =get_field('boost2_image' ,$id1);
                    $boost2_price =get_field('boost2_price' ,$id1);
              ?>
                                            <div class="col-lg-6 col-sm-6 col-6 ">

                                                <input type="checkbox"   data-id="<?php echo $boost_1_price; ?>"  name="boost1" value="24 Hours boost" id="third"  />
                                                <label for="third">
                                                    <img src="<?php echo $boost_image1['url' ]?>" class="img-fluid mx-auto d-block img_cursr">
                                                </label>

                                                <!-- <img src="img/p4.png" class="img-fluid d-block mx-auto"> -->
                                                <p class="dolar">+ $<b><?php echo $boost_1_price; ?></b><span>Included with Pro+</span></p>
                                                
                                            </div>

                                            <div class="col-lg-6 col-sm-6 col-6">
                                                <input type="checkbox" data-id="<?php echo $boost2_price; ?>" id="forth" name="boost2" value="CDN boost"  />
                                                <label for="forth">
                                                    <img src="<?php echo $boost2_image['url' ]?>" class="img-fluid mx-auto d-block img_cursr">
                                                </label>
                                                <!-- <img src="img/p5.png" class="img-fluid d-block mx-auto"> -->
                                                <p class="dolar">+ $<b><?php echo $boost2_price; ?></b><span>Included with Pro+</span></p>
                                              
                                            </div>
                                              <?php
                                    endwhile;
                                    endif;
                                    ?>  
                                        </div>                   
                                  </div>
                                </div>
    
                            </div>
                        </div>



                        <div class="row space50">
                            <div class="col-lg-12">
                                <div class="media">
                                  <img class="align-self-start mr-3 lo1 hidden-xs" src="<?php echo get_stylesheet_directory_uri(); ?>/img/l3.png" alt="...">
                                  <div class="media-body">
                                    <span> <img class="align-self-start mr-3 lo1 visible-xs l_img" src="<?php echo get_stylesheet_directory_uri(); ?>/img/l3.png" alt="..."></span>
                                    <h3>  PAYMENT DETAILS (BILLING ADDRESS) </h3>
                                     <input type="hidden" name="grand_tt" class="grand_tt" value="">
                                         <input type="hidden" name="plan_name" class="plan" value="">
                                        <div class="row">
                                        <div class="col-lg-6 col-sm-6 col-12">
                                            <div class="form-group cnfrm">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" name="username" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-sm-6 col-12">
                                            <div class="form-group cnfrm">
                                                <label for="name">Email Address</label>
                                                <input type="text"  name="useremail" class="form-control"  id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                                            </div>
                                        </div>
                                        </div>  
        
                                        
        
                                        <div class="row">
                                        <div class="col-lg-6 col-sm-6 col-12">
                                            <div class="form-group cnfrm">
                                                <label for="name">Address</label>
                                                <input type="text" name="address" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-sm-6 col-12">
                                            <div class="form-group cnfrm">
                                                <label for="name">Zip Code</label>
                                               <input type="text" name="zipcode" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                                            </div>
                                        </div>
                                        </div>  
        
        
                                        <div class="row">
                                        <div class="col-lg-6 col-sm-6 col-12">
                                            <div class="form-group cnfrm"  id="validateCard">
                                                <label for="name">Card Number</label>
                                                <input type="text" class="form-control new cardnumber" name="ccNumber" maxlength="16" id="cardnumber" aria-describedby="emailHelp" placeholder="" data-creditcard="true" autocomplete="cc-number">
                                                  <i class="icon-ok"></i>
                                            </div>
                                        </div>

                                    <div class="col-lg-2 col-sm-2 col-12">
                                            <div class="form-group cnfrm">
                                                <label for="name"> month</label>
                                                 <input type="text" name="exp_month" size="2" class="form-control card-expiry-month" placeholder="MM">
                                                 </div>
                                                   </div>
        
                                   <div class="col-lg-2 col-sm-2 col-12">
                                         <div class="form-group cnfrm">
                                   <label for="name">  year</label>
                                   <input type="text" name="exp_year" size="4" class="form-control card-expiry-year"  placeholder="YYYY">
        
                                                <!--<input type="text" class="form-control card-expiry-month"  name="exp_dt" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">-->
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-12">
                                            <div class="form-group cnfrm">
                                                <label for="name">Code</label>
                                                <input type="text" class="form-control card-cvc" name="cvv" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                                            </div>
                                        </div>
                                        </div>  
                                    <button  type="submit" id="payBtn" class="btn btn_conti buttn_odr space70">ORDER NOW</button>
                                       
                                        <div class="space60 hidden-xs"></div>
                                        <div class="space30 visible-xs"></div>
        
                                        <div class="hidden-xs">
                                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/payment1.png" class="img-fluid mx-auto d-block    hidden-xs">
                                        </div>
                                     </div>   
                                  </div>
                                </div>

                         
                            </div>
                             </form>
                        </div>
                    </div>
                    <div class="visible-xs">
                      <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/payment1.png" class="img-fluid mx-auto d-block ">
                    </div>
  
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end checkout -->

<script src="https://js.stripe.com/v2/"></script>

<!--<script type="text/javascript" src="https://js.stripe.com/v2/"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!--<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery-1.11.2.min.js"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.1/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/wow.min.js"></script>
   <!--  <script type="text/javascript" <?php echo get_stylesheet_directory_uri(); ?>/src="js/counter.js"></script> -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
    
 <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/creditcard.js"></script>
  <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.creditCardValidator.js"></script>

     <script type="text/javascript">

        var getCardType = function (number) {
        var cards = {
            visa: /^4[0-9]{12}(?:[0-9]{3})?$/,
            mastercard: /^5[1-5][0-9]{14}$/,
            amex: /^3[47][0-9]{13}$/,
            diners: /^3(?:0[0-5]|[68][0-9])[0-9]{11}$/,
            discover: /^6(?:011|5[0-9]{2})[0-9]{12}$/,
            jcb: /^(?:2131|1800|35\d{3})\d{11}$/
        };
        for (var card in cards) {
            if (cards[card].test(number)) {
                return card;
            }
        }
    };

</script>

   <script type="text/javascript">

    $('.downgrade_btn').click(function(){
        $(this).parents('body').find('.classic input').trigger('click')
        $(this).parents('body').removeClass('paid').addClass('free')
    })


        $('.counter').each(function() {
          var $this = $(this),
              countTo = $this.attr('data-count');
          
          $({ countNum: $this.text()}).animate({
            countNum: countTo
          },

          {

            duration: 3000,
            easing:'linear',
            step: function() {
              $this.text(Math.floor(this.countNum));
            },
            complete: function() {
              $this.text(this.countNum);
              //alert('finished');
            }

          });  
          
          

        });

</script>

<script>
function openNav() {
    document.getElementById("mySidenav").style.width = "100%";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}
</script>


<script type="text/javascript">
 $(function(){
      new WOW({
        animateClass: 'animated',
        offset: 100
      }).init();
    });
</script>

<script>    
    $('.nav_open').click(function(){
    $('#mySidenav').addClass('open');
    var lastScrollTop = 0;
    $(window).scroll(function(event){
       var st = $(this).scrollTop();
       if (st > lastScrollTop){
          $('#mySidenav.open').css({'width':'0'}).addClass('open');
       } else {
           $('#mySidenav.open').css({'width':'100%'});
       }
       lastScrollTop = st;
    });
})
$('.closebtn').click(function(){
    $('#mySidenav').removeClass('open');
})

$('.your input[type="radio"]').click(function(){
    
    
    var id_name = $(this).attr('id')
    
    if(id_name == "second"){
        $('body').addClass('paid')
        $('body').removeClass('free')
    }
    else{
        $('body').removeClass('paid')
        $('body').addClass('free')
    }
})

$('.org1 input[type="checkbox"]').click(function(){
        var text = parseInt($(this).siblings('.dolar').find('b').text());
        var price = parseInt($(this).parents().find('.grand_total').text())
    if($(this).is(':checked')){
        
        $(this).parents().find('.grand_total').text(price + text)
        $('input[name=grand_tt]').val($('.grand_total').text());
    }else{
        $(this).parents().find('.grand_total').text(price - text)
        $('input[name=grand_tt]').val($('.grand_total').text());
    }
    
})

$( document ).ready(function() {
   if ($("#first").is(':checked')){
     $('body').removeClass('paid')
        $('body').addClass('free')
   }
   else if($("#second").is(':checked')){
        $('body').addClass('paid')
        $('body').removeClass('free')
    }
 
   
});

$('#first').click(function(){
    var price =$(this).attr('data-id');

    var plan =$(this).attr('value');
     $(this).parents().find('.package_plan').text(plan);
   $(this).parents().find('.grand_total').text(price);
   $('input[name=grand_tt]').val($('.grand_total').text());
    $('input[name=plan_name]').val($('.package_plan').text());
});

$('#second').click(function(){
    var price1 =$(this).attr('data-id');
    var plan =$(this).attr('value');
   
   $(this).parents().find('.grand_total').text(price1);
   $(this).parents().find('.package_plan').text(plan);
   $('input[name=grand_tt]').val($('.grand_total').text());
     $('input[name=plan_name]').val($('.package_plan').text());
});


  $('input[name=plan_name]').val($('.package_plan').text());
$('input[name=grand_tt]').val($('.grand_total').text());
</script>


<script type="text/javascript">
//set your publishable key
Stripe.setPublishableKey('pk_live_knmuxeSNsl5OpXZ6PkIDD8eb');

//callback to handle the response from stripe
function stripeResponseHandler(status, response) {
    if (response.error) {
        //enable the submit button
        $('#payBtn').removeAttr("disabled");
        //display the errors on the form
        $(".payment-errors").html(response.error.message);
    } else {
        var form$ = $("#paymentFrm");
        //get token id
        var token = response['id'];
        //insert the token into the form
        form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
        //submit form to the server
        form$.get(0).submit();
    }
}
$(document).ready(function() {
    //on form submit
    $("#payBtn").click(function(event) {
        //disable the submit button to prevent repeated clicks
        $('#payBtn').attr("disabled", "disabled");
        
        //create single-use token to charge the user
        Stripe.createToken({
            number: $('.cardnumber').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
        }, stripeResponseHandler);
        
        //submit from callback
        return false;
    });
});
</script>


<style>
.error
{
  color:red;
}
</style>
     

       
 <script>
     
            (function ($, W, D)
            {
                var JQUERY4U = {};
                JQUERY4U.UTIL =
                        {
                            setupFormValidation: function ()
                            {
                                //form validation rules
                                $("#paymentFrm").validate({
                                    rules: {
                                         
                                              website_url: {
                                                  required : true
                                                  
                                                        },
                                              username:{
                                                required:true
                                              },
                                              useremail:{
                                                required:true,
                                                email : true
                                               
                                              }
                                              
                                             
                                     
                                        
                                          
                                           
                                         
                                      },
 

                                            messages: {

                                               
                                         

                                          website_url: 
                                          {

                                          required : "Please enter website url"
                                         
                                          },
                                          username:{
                                            required : "Please enter user name"
                                          },
                                       useremail:{
                                        required:"Please enter user email",
                                        email : "Please enter email"
                                       
                                       }
                                     
                                      

                                       },
                  
                                    submitHandler: function (form) {
                                        form.submit();
                                    }
                                });
                            }
                        }

                //when the dom has loaded setup form validation rules
                $(D).ready(function ($) {
                    JQUERY4U.UTIL.setupFormValidation();
                });
            })(jQuery, window, document);
        </script>
 
 

</body>

</html>
    
