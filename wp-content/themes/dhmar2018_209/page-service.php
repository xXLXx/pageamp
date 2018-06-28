<?php 
/*
Template Name: Service
*/
get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post(); 
$ampImage = get_field('page_amp_image');
$ampProImage = get_field('page_amp_pro_image');
$bottom_image = get_field('bottom_image');
?>
<style>
.service_page .hero_sec {
    background: url(<?php echo wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()));  ?>) no-repeat;
    background-size: cover;
    background-position: 50% 100%;
}
</style>
<!---->
<section class="hero_sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="animated fadeInUp wow"><?php echo get_field('title');?></h1>
                <p class="animated fadeInUp wow"><?php echo get_field('enter_sub_title');?></p>
                
            </div>
        </div>
    </div>
</section>
<!---->
<section class="table_sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pos_tablecntnt">
                <div class="main table-responsive">
                    <span class="bg-white"></span>
                    <span class="bg-green"></span>
                    <table class="table price-table">
                        <thead>
                            <tr>
                                <td class="white-left" colspan="7">&nbsp;</td>
                                <td><img src="<?php echo $ampImage['url'] ?>" class="img_width" alt=".."></td>
                                <td class="green-width"><img src="<?php echo $ampProImage['url'] ?>" class="img_width" alt=".."></td>
                            </tr>
                        </thead>
                        <tbody>                                
                            <?php if(get_field('service_list')):while(the_repeater_field('service_list')): ?>                                
                            <tr>
                                <td colspan="7"><?php the_sub_field('service_name'); ?> <span></span></td>
                                <td><i class="fa fa-<?php echo(get_sub_field('amp') == "YES")?'check':'times'?>"></i></td>
                                <td><i class="fa fa-<?php echo(get_sub_field('amp_pro') == "YES")?'check':'times'?>"></i></td>
                            </tr>
                            <?php endwhile; endif; ?>
                        </tbody>
                        <tfoot class="mobile_hide">
                            <tr>
                                <td class="white-left" colspan="7">&nbsp;</td>
                               <td class="opt_txt"> <a href="<?php echo get_site_url()?>/checkout?id=1"><?php echo get_field('amp_button_text');?></a> </td>
                                <td class="opt_pro">  <a href="<?php echo get_site_url()?>/checkout?id=2"><?php echo get_field('amp_pro_button_text');?></a></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="media pos_media mobile_hide" id="media_price">
                      <div class="media-left mr-3">
                          <h1>$<?php echo get_field('amp_price');?></h1>
                      </div>
                      <div class="media-body">
                          <h6>One-time <br> Fee</h6>
                      </div>
                </div>

                <div class="media pos_media1 mobile_hide" id="media_price">
                      <div class="media-left mr-3">
                          <h1>$<?php echo get_field('amp_pro_price');?></h1>
                      </div>
                      <div class="media-body">
                          <h6>One-time <br> Fee</h6>
                      </div>
                </div>
                </div>
                

                <div class="row">
                    <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                        <div class="media">
                          <img class="align-self-start mr-3" src="<?php echo $bottom_image['url']?>" alt="Generic placeholder image">
                          <div class="media-body">
                            <h5 class="mt-0"><?php echo get_field('bottom_title');?></h5>
                            <p><?php echo get_field('bottom_sub_title');?></p>                        
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!---->
<section class="faq_sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1>
                    <button class="opt_txt">
                        <a href="<?php echo get_site_url()?>/checkout?id=1"><?php echo ucwords(get_field('faq_title'));?></a>
                    </button>
                </h1>
                <div id="accordion">
                    <?php
                    $args = array( 'post_type' => 'faq', 'order' => 'DESC' );
                    $loop = new WP_Query( $args );
                    $i=1; 
                    while ( $loop->have_posts() ) : $loop->the_post();
                        $class=''; $cl ='';
                        if($i==1){$class='show';}
                        if($cl!=1){ $cl = 'collapsed';}
                    ?>
                    <div class="card">
                        <div class="card-header" id="heading<?php echo $i ?>">
                            <h5 class="mb-0">
                                <button class="btn btn-link <?php echo $cl; ?>" data-toggle="collapse" data-target="#collapse<?php echo $i ?>" aria-expanded="true" aria-controls="collapse<?php echo $i ?>">
                                    <?php echo the_title(); ?>
                                </button>
                            </h5>
                        </div>

                        <div id="collapse<?php echo $i ?>" class="collapse <?php echo $class; ?>" aria-labelledby="heading<?php echo $i ?>" data-parent="#accordion">
                            <div class="card-body">
                                <?php echo the_content(); ?>
                            </div>
                        </div>
                    </div>
                    <?php  $i++; endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!---->
<?php 
endwhile; endif; 
get_footer();
?>

