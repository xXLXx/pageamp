<?php 
/*
Template Name: Contact
*/
get_header();
?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post();

$id=get_the_ID();
$contact_title =get_field('contact_title' ,$id);
$contact_description =get_field('contact_description' ,$id);
$address = get_field('address' ,$id);
$email = get_field('email' ,$id);
$phone_number = get_field('phone_number' ,$id);
?>
       <!-- start hero section -->
<style>
    #orang_map.hero_sec {
    background: url(<?php echo wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()));  ?>) no-repeat;
    background-size: cover;
    background-position: 100% 17%;
}

@media (max-width: 767px){
#orang_map.hero_sec {
    background: url(<?php echo wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()));  ?>) no-repeat;
    padding: 15% 15% 2%;
}
}
</style>
    <section class="hero_sec" id="orang_map">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-sm-8 pad0">
                  <div class="space30 visible-xs"></div>
                    <h1 class="animated fadeInUp wow get_in_txt"><?php echo $contact_title; ?></h1>
                    <p class="animated fadeInUp wow super_fast_txt"><?php echo $contact_description; ?></p>
                    <div class="space50"></div>
                    
                </div>
                <div class="col-lg-6">
                  <!-- <img src="img/map_img.png" class="img-fluid mx-auto d-block" alt="..."> -->
                </div>
            </div>
        </div>
    </section>

    <!-- End hero section -->


    <!-- start send -->
    <section class="send">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="shadow_box">
              <div class="row">
                <div class="col-lg-8 col-sm-12 col-12 padryt0">
                  <div class="white-box">
                    <div class="media">
                      <img class="mr-3 msg" src="<?php echo get_stylesheet_directory_uri(); ?>/img/msg.png" alt="Sample photo">
                      <div class="media-body">
                        <h4> Send us a Message</h4>
                      </div>            
                    </div> 
<?php echo do_shortcode('[contact-form-7 id="382" title="contact form for new page"]');?>
                   <!-- <form class="contact_form">
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="exampleInputEmail1">Full Name</label>
                            <input type="email" class="form-control" aria-describedby="emailHelp" placeholder="">  
                          </div>
                        </div>

                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="exampleInputEmail1">Email Address</label>
                            <input type="email" class="form-control" aria-describedby="emailHelp" placeholder="">  
                          </div>
                        </div>
                      </div>
                      

                      <div class="space30 hidden-xs"></div>
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="exampleInputEmail1">Phone</label>
                            <input type="email" class="form-control"  aria-describedby="emailHelp" placeholder="">  
                          </div>
                        </div>

                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="exampleInputEmail1">Company</label>
                            <input type="email" class="form-control" aria-describedby="emailHelp" placeholder="">  
                          </div>
                        </div>
                      </div>


                      <div class="space30 hidden-xs"></div>
                      <div class="row">
                        <div class="col-lg-10 col-12">
                          <div class="form-group msg_box">
                            <label for="exampleInputEmail1">Message</label>
                            <textarea type="email" class="form-control" aria-describedby="emailHelp" placeholder=" "></textarea>
                            
                          </div>
                        </div>
                        <div class="col-lg-2 col-12">
                          <a href="#"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/send.png" class="send"></a>
                        </div>
                      </div>
                    </form>       -->      
                  </div>
                </div>


                <div class="col-lg-4 col-sm-12 col-12 padlft0">
                  <div class="grey_contact">
                    <h4>Contact Information</h4>
                    <div class="media">
                      <img class="mr-3" src="<?php echo get_stylesheet_directory_uri(); ?>/img/add.png" alt="Sample photo">
                      <div class="media-body">
                        <h5> <?php echo $address; ?></h5>
                      </div>            
                    </div>

                    <div class="space30"></div>
                    <div class="media">
                      <img class="mr-3" src="<?php echo get_stylesheet_directory_uri(); ?>/img/mob.png" alt="Sample photo">
                      <div class="media-body">
                        <h5> <?php echo $phone_number; ?></h5>
                      </div>            
                    </div>

                    <div class="space30"></div>
                    <div class="media">
                      <img class="mr-3" src="<?php echo get_stylesheet_directory_uri(); ?>/img/earth.png" alt="Sample photo">
                      <div class="media-body">
                        <h5><?php echo $email; ?></h5>
                      </div>            
                    </div> 
                  </div>
                </div>
              </div>  
            </div> 
          </div>
           
        </div>
      </div>
    </section>
    <!-- end send -->

<?php
endwhile;
endif;
?>


<?php get_footer(); ?>

<script>
    $( "#getform" ).click(function() {
  $( ".wpcf7-form" ).submit();
});
</script>