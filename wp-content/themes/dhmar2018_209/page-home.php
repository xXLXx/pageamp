<?php 
/*
Template Name: Home
*/
get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post(); 
$point_background = get_field('internet_moves_background');
?>
<style>
.hero_sec {
    background: url(<?php echo wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()));  ?>) no-repeat;
    background-size: cover;
    background-position: 50% 100%;
}
.point_background {
    background: url(<?php echo $point_background['url']?>) no-repeat;
    background-size: cover;
    background-position: 50% 0;
}
</style>
<section class="hero_sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="animated fadeInUp wow"><?php echo ucwords(get_field('first_title'));?></h1>
                <p class="animated fadeInUp wow"><?php echo get_field('second_title');?></p>
                <div class="space50"></div>
                <h6 class="animated fadeInUp wow"><?php echo get_field('third_title');?></h6>
                <form action="javascript:void(0);" onsubmit="sendTest(this)"> 
                    <div class="input_grp">
                        <input type="name" name="test" placeholder="www.mywebsite.com" class="form-control">
                        <button type="submit" class="btn btn_search">Analyze My Website</button>
                    </div>
                </form>
                <h5 class="animated fadeInUp wow"><?php echo get_field('fourth_title');?></h5>
            </div>
        </div>
    </div>
</section>
<?php endwhile; endif; ?>
<!---->
<section class="testinomial_sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $args = array( 'post_type' => 'testimonial', 'order' => 'DESC' );
                        $loop = new WP_Query( $args );
                        $i=0; 
                        while ( $loop->have_posts() ) : $loop->the_post();
                            $class="";
                            if($i=='0') { $class="active";}
                        ?>
                        <div class="carousel-item <?php echo $class ?>">
                            <div>
                                <h4 class="animated fadeIn wow"><?php echo the_content() ?></h4>
                                <img src="<?php echo wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()));  ?>" class="img-fluid mx-auto d-block" alt="..">
                                <h5> <?php echo get_field('author') ?> <br><span><?php echo get_field('brand') ?></span></h5>
                            </div>                          
                        </div>
                        <?php  $i++; endwhile; ?>
                       
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                        <i class="fa fa-chevron-circle-left" aria-hidden="true"></i>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                        <i class="fa fa-chevron-circle-right" aria-hidden="true"></i>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!---->
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<section class="internet_fast padbtm0">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h6 class="animated fadeInUp wow"><?php echo get_field('did_you_konw');?></h6>  
                <h4 class="animated fadeInUp wow"><?php echo get_field('main_title');?></h4>
                <p class="animated fadeInUp wow"> <i>- <?php echo get_field('sub_title');?></i></p>
                <div class="space120"></div>                    
            </div>
        </div>
    </div>
</section>
<!---->
<section class="internet_fast point_background padtop0">
    <div class="container">             
        <div class="">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="animated fadeInUp wow"> <?php echo get_field('internet_moves_title')?></h2>
                    <?php echo get_field('internet_moves_content')?>
                </div>
            </div>
            <div class="row counter_row">
                <!-- Temporary disable -->
                <?php if(false && get_field('counter')): while(the_repeater_field('counter')): ?>
                <div class="col-lg-4 col-md-4 col-4">
                    <div class="icon">
                        <span class="counter" data-count="<?php the_sub_field('value'); ?>"><?php the_sub_field('value'); ?></span><?php the_sub_field('sign'); ?>
                    </div>
                    <h6 class="ornge_clr"><?php the_sub_field('title'); ?></h6>
                </div>
                <?php endwhile; endif; ?>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <a href="<?php echo get_field('internet_moves_button_href')?>" target="_blank" class="btn_see"><?php echo get_field('internet_moves_button_title')?></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!---->
<section class="liftoff_sec">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <h1><?php echo get_field('ready_for_liftoff_title')?></h1>
                <ul class="list-inline">
                    <?php if(get_field('ready_for_liftoff_technology')): while(the_repeater_field('ready_for_liftoff_technology')): 
                        $image = get_sub_field('image');
                    ?>
                    <li class="list-inline-item"><a href="#a"><img src="<?php echo $image['url'];?>" class="img-fluid mx-auto d-block" alt=".."></a></li>
                    <?php endwhile; endif; ?>
                </ul>
            </div>
        </div>
    </div>
</section>
<!---->
<section class="expert_sec">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1> <?php echo get_field('the_experts_in_page_speed')?> </h1>
                <p> <?php echo get_field('content')?></p>
            </div>
        </div>
        
        
        
        
        <?php if(get_field('block')): $i=1; while(the_repeater_field('block')): 
            $image = get_sub_field('image');
            if($i=='1')
            {
                ?>
                <div class="row space20">
                    <div class="col-lg-6 col-md-6">
                        <img src="<?php echo $image['url']?>" class="img-fluid mx-auto d-block animated zoomIn wow" alt="..">
                        <h4><?php the_sub_field('title_main'); ?> </h4>
                        <h6><?php the_sub_field('content'); ?></h6>
                    </div>
                </div>
                <?php
            } else if($i=='2') {
                ?>
                <div class="row space20">
                    <div class="col-lg-6 offset-lg-6 col-md-6 offset-md-6">
                        <img src="<?php echo $image['url']?>" class="img-fluid mx-auto d-block animated zoomIn wow" alt="..">
                        <h4><?php the_sub_field('title_main'); ?> </h4>
                        <h6><?php the_sub_field('content'); ?></h6>
                    </div>                
                </div>
                <?php
            } else if($i=='3') {
                ?>
                <div class="row space20">
                    <div class="col-lg-6 col-md-6">
                        <div class="space40 d-block d-md-none"></div>
                        <img src="<?php echo $image['url']?>" class="img-fluid mx-auto d-block animated zoomIn wow" alt="..">
                        <h4><?php the_sub_field('title_main'); ?> </h4>
                        <h6><?php the_sub_field('content'); ?></h6>
                    </div>
                </div>
                <?php
            } else if($i=='4') {
                ?>
                <div class="row space20">
                    <div class="col-lg-6 offset-lg-6 col-md-6 offset-md-6">
                        <img src="<?php echo $image['url']?>" class="img-fluid mx-auto d-block animated zoomIn wow" alt="..">
                        <h4><?php the_sub_field('title_main'); ?> </h4>
                        <h6><?php the_sub_field('content'); ?></h6>
                    </div>
                </div>
                <?php
            } else {
                
            }
        ?>
        <?php $i++; endwhile; endif; ?>
        <div class="row">
            <div class="col-lg-12">
                <button type="button" class="btn btn_service"><?php echo get_field('service_button_text')?></button>
            </div>
        </div>
        
        
        
    </div>
</section>
<!---->
<section class="magic_sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1> <?php echo get_field('title_first_magic')?></h1>
                <p> <?php echo get_field('second_title_magic')?></p>
                
                <div class="rating_gp animated zoomIn wow">                      
                    <canvas id="myCanvas" width="250" height="300"></canvas>
                </div>
                <h5 ><?php echo get_field('bottom_title')?></h5>
                <form action="javascript:void(0);" onsubmit="sendTest(this)">
                    <div class="input_grp">
                        <input type="name" name="test" placeholder="www.mywebsite.com" class="form-control">
                        <button type="submit" class="btn btn_search">RUN TEST</button>
                    </div>
                </form>
                <div class="space10"></div>
                <ul class="list-inline trust_ul">
                    <?php if(get_field('bottom_link')): $i=1; while(the_repeater_field('bottom_link')):  ?>
                    <li class="list-inline-item"><a href="<?php the_sub_field('url'); ?> "> <?php the_sub_field('title'); ?> </a></li>
                    <?php endwhile; endif; ?>
                </ul>
                <input type="hidden" value="<?php echo get_field('bottom_counter_value')?>" id="DB_COUNTER">
            </div>
        </div>
    </div>
</section>
<!---->
<?php 
endwhile; endif;
get_footer();
?>
<script type="text/javascript">
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

        function sendTest (ele) {
            var url = $(ele).find('[name="test"]').val();
            if (url) {
                window.location.href = '<?php echo get_site_url() ?>/conversion?url=' + encodeURIComponent(url);
            }
        }

var DB_COUNTER = $('#DB_COUNTER').val();  
var canvas = document.getElementById('myCanvas');
var context = canvas.getContext('2d');
var al=0;
var start=4.72;
var cw=context.canvas.width/2;
var ch=context.canvas.height/2;
var diff;
 
function progressBar(){
diff=(al/100)*Math.PI*2;
context.clearRect(0,0,500,500);
context.beginPath();
context.arc(cw,ch,100,0,2*Math.PI,false);
context.fillStyle='#f5f5f5';
context.fill();
context.strokeStyle='#fff';
context.stroke();
context.fillStyle='#000';
context.strokeStyle='#ffac25';
context.textAlign='center';
context.lineWidth=15;
context.font = '55px Verdana';
context.beginPath();
context.arc(cw,ch,100,start,diff+start,false);
context.stroke();
context.fillText(al+'+',cw+2,ch+6);
if(al>=DB_COUNTER){
clearTimeout(bar);
}
 
al++;
}
 
var bar=setInterval(progressBar,50);
</script>