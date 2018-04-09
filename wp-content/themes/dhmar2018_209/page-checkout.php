<?php 
/*
Template Name: Checkout
*/
get_header('checkout');
if ( have_posts() ) : while ( have_posts() ) : the_post();
$lock = get_field('lock_image');
$stripe = get_field('stripe_image');
$btm = get_field('bottom_image');
?>
<style>
.checkout_page {
    background: url(<?php echo wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()));  ?>) no-repeat;
    background-size: cover;
    padding: 225px 0 0;
    background-position: 50% 130%;
    margin-bottom: 8%;
}
</style>
<section class="checkout_header">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <a href="<?php echo get_site_url() ?>"><img src="<?php echo get_option("theme_photoone_about");?>" class="img-fluid" alt="..."></a>
            </div>
        </div>
    </div>
</section>
<!---->
<section class="checkout_page">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="checkout_box" id="msform">
                   <ul id="progressbar">
                        <li class="active"> <span>Complete Test</span></li>
                        <li> <span>Confirm URL</span></li>
                        <li> <span id="payment">Payment</span></li>
                    </ul>
                    <fieldset>
                        <h4> Confirm your website URL</h4>
                        <input type="text" name="email" placeholder="www.example.com" class="form-control">
                        <button type="button" class="btn btn_cntnue next"> CONTINUE</button>

                    <ul class="list-inline text-center">
                        <li class="list-inline-item"><a href="#a">
                            <img src="<?php echo $lock['url']?>" class="img-fluid mx-auto lock_img d-block" alt="..">
                        </a></li>
                        <li class="list-inline-item">
                            <p><?php echo get_field('title');?></p>
                            <a href="#a"><img src="<?php echo $stripe['url']?>" class="img-fluid strip_img mx-auto d-block" alt="..">
                        </a></li>
                        <li class="list-inline-item">
                            <p><?php echo get_field('sub_title');?></p>
                            <ul class="list-inline card_ul">
                                <?php if(get_field('payment_image')):while(the_repeater_field('payment_image')): 
                                    $image = get_sub_field('image');
                                ?>
                                <li class="list-inline-item">
                                    <a href="#a"><img src="<?php echo $image['url']?>" class="img-fluid mx-auto d-block" alt=".."></a>
                                </li>
                            	<?php endwhile; endif; ?>
                            </ul>
                        </li>
                    </ul>
                </fieldset>
                <fieldset>
                    <h4> Confirm your website URL</h4>
                    <input type="text" name="email" placeholder="example@gmail.com" class="form-control">
                    <button type="button" class="btn btn_cntnue next"> CONTINUE</button>

                    <ul class="list-inline text-center">
                        <li class="list-inline-item"><a href="#a">
                            <img src="<?php echo $lock['url']?>" class="img-fluid mx-auto d-block" alt="..">
                        </a></li>
                        <li class="list-inline-item">
                            <p><?php echo get_field('title');?></p>
                            <a href="#a"><img src="<?php echo $stripe['url']?>" class="img-fluid strip_img mx-auto d-block" alt="..">
                        </a></li>
                        <li class="list-inline-item">
                            <p><?php echo get_field('sub_title');?></p>
                            <ul class="list-inline card_ul">
                                <?php if(get_field('payment_image')):while(the_repeater_field('payment_image')): 
                                    $image = get_sub_field('image');
                                ?>
                                <li class="list-inline-item">
                                    <a href="#a"><img src="<?php echo $image['url']?>" class="img-fluid mx-auto d-block" alt=".."></a>
                                </li>
                            	<?php endwhile; endif; ?>
                            </ul>
                        </li>
                    </ul>
                </fieldset>
                <fieldset>
                    <h4> Confirm your website URL</h4>
                    <input type="text" name="email" placeholder="example@gmail.com" class="form-control">
                    <button type="button" class="btn btn_cntnue"> CONTINUE</button>

                    <ul class="list-inline text-center">
                        <li class="list-inline-item"><a href="#a">
                            <img src="<?php echo $lock['url']?>" class="img-fluid mx-auto d-block" alt="..">
                        </a></li>
                        <li class="list-inline-item">
                            <p><?php echo get_field('title');?></p>
                            <a href="#a"><img src="<?php echo $stripe['url']?>" class="img-fluid strip_img mx-auto d-block" alt="..">
                        </a></li>
                        <li class="list-inline-item">
                            <p><?php echo get_field('sub_title');?></p>
                            <ul class="list-inline card_ul">
                                <?php if(get_field('payment_image')):while(the_repeater_field('payment_image')): 
                                    $image = get_sub_field('image');
                                ?>
                                <li class="list-inline-item">
                                    <a href="#a"><img src="<?php echo $image['url']?>" class="img-fluid mx-auto d-block" alt=".."></a>
                                </li>
                            	<?php endwhile; endif; ?>
                            </ul>
                        </li>
                    </ul>
                </fieldset>
                </div>
            </div>
        </div>
        <div class="space50"></div>
        <div class="row order_media">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                <div class="media">
                  <img class="align-self-start mr-3" src="<?php echo $btm['url'];?>" alt="Generic placeholder image">
                  <div class="media-body">
                    <h5 class="mt-0"><?php echo get_field('bottom_title');?></h5>
                    <p><?php echo get_field('enter_sub_title');?></p>                        
                  </div>
                </div>
            </div>
        </div>
        <div class="space20 d-block d-md-none"></div>
    </div>
</section>
<!---->
<?php 
endwhile; endif; 
get_footer('checkout');
?>