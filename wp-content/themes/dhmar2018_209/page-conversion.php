<?php 
/*
Template Name: Conversion
*/
get_header('conversion');
?>
<section ng-controller="resultsCtrl">
    <section class="summary_sec analyzing" ng-hide="nextAnalysisPage">
        <div class="pos_header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <a href="<?php echo get_site_url() ?>"><img src="<?php echo get_option("theme_photofour_about");?>" class="img-fluid logo3" alt=".."></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-6 load-container desktop">
                    <div class="gradient"></div>
                    <div class="gradient"></div>
                    <div class="gradient"></div>
                    <div class="gradient"></div>
                </div>
                <div class="col-sm-1"></div>
                <div class="col-sm-2 load-container mobile">
                    <div class="gradient"></div>
                    <div class="gradient"></div>
                    <div class="gradient"></div>
                    <div class="gradient"></div>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="row">
                <div class="loading-line desktop"></div>
                <div class="loading-line mobile"></div>
            </div>
            <div class="space40"></div>
            <div class="row">
                <div class="col-lg-12">
                    <h3 ng-bind="url"></h3>
                    <p>Your website is being analzyed.</p>
                    <div class="space20"></div>
                    <ul class="list-inline">
                        <li class="list-inline-item" ng-class="{'current': testStatus == 'Starting' }">
                            <h1>Starting</h1>
                        </li>
                        <li class="list-inline-item" ng-class="{'current': isDesktopTesting && isMobileTesting }">
                            <h1>Testing</h1>
                        </li>
                        <li class="list-inline-item" ng-class="{'current': !isDesktopTesting && isMobileTesting }">
                            <h1>Compiling</h1>
                        </li>
                        <li class="list-inline-item" ng-class="{'current': testStatus == 'Completed' }">
                            <h1>Finished</h1>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row list-inline-item-text">
                <div class="col-lg-12">
                    <h1 ng-class="{'current': testStatus == 'Starting' }">Starting</h1>
                    <h1 ng-class="{'current': isDesktopTesting && isMobileTesting }">Testing</h1>
                    <h1 ng-class="{'current': !isDesktopTesting && isMobileTesting }">Compiling</h1>
                    <h1 ng-class="{'current': testStatus == 'Completed' }">Finished</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="website_sec ng-hide" ng-show="nextAnalysisPage">
        <div class="pos_header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <a href="<?php echo get_site_url() ?>"><img src="<?php echo get_option("theme_photofour_about");?>" class="img-fluid logo3" alt=".."></a>
                    </div>
                </div>
            </div>
        </div>
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post();  ?>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="d-none d-md-block"><?php echo get_field('section_1_title');?> </h3>
                    <h3 class="d-block d-md-none"> <?php echo get_field('section_1_title');?></h3>
                </div>
            </div>
            <div class="row">
                <div id="screenshots-container" class="col-lg-6 col-md-6">
                    <img ng-if="desktopScreenshot" ng-src="{{ desktopScreenshot }}" alt="desktop-screenshot" id="desktop-screenshot" />
                    <img src="<?php echo get_template_directory_uri() . '/img/conversion-laptopimg.png'  ?>"
                        class="screenshot-base img-fluid mx-auto d-block" alt="laptop base image">
                    <img ng-if="mobileScreenshot" ng-src="{{ mobileScreenshot }}" alt="mobile-screenshot" id="mobile-screenshot" />
                    <div id="screenshot-base-placeholder" ng-class="{'hidden': desktopScreenshot, 'all': mobileScreenshot}"></div>
                    <img src="<?php echo get_template_directory_uri() . '/img/conversion-phoneimg.png'  ?>"
                        class="screenshot-base img-fluid mx-auto d-block" alt="phone base image">
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="space20 d-block d-md-none"></div>
                    <h2 ng-bind="url"></h2>
                    <p>Performance Testing:</p>
                    <h6 class="btn_status btn_{{ testStatus.toLowerCase() }}" ng-bind="testStatus"></h6>
                    <!-- <button type="button" class="btn "></button> -->
                    <ul class="list-unstyled">
                        <li><span>Date:</span><span ng-bind="testDate"></span></li>
                        <li><span>Desktop Test:</span><?php echo get_field('desktop_test');?> </li>
                        <li><span>Mobile Test:</span><?php echo get_field('mobile_test');?> </li>
                    </ul>
                    <h6>Performance Report</h6>
                    <h6 class="btn_status btn_{{ testStatus.toLowerCase() }}" ng-bind="testStatus"></h6>
                </div>
            </div>
        </div>
    </section>
    <!---->
    <section class="score_sec ng-hide" ng-show="nextAnalysisPage">
        <div class="container">
            <div class="row">
               <div class="col-lg-6 col-md-6 offset-lg-3 offset-md-3" id="combined-scores-table">
                   <div class="row">
                       <h6 class="col-lg-6 col-md-6 text-center">Desktop Score</h6>
                       <h6 class="col-lg-6 col-md-6 text-center">Mobile Score</h6>
                   </div>
                   <div class="bg_mb">
                       <div class="row">
                           <div class="col-lg-6 col-md-6">
                                <h1 class="{{ pagespeedScore | score:true | lowercase }}" ng-bind="pagespeedScore | score"></h1>
                           </div>
                           <div class="col-lg-6 col-md-6">
                                <h1 class="{{ pagespeedMobileScore | score:true | lowercase }}" ng-bind="pagespeedMobileScore | score"></h1>
                           </div>
                       </div>
                       <hr>
                       <ul class="list-inline">
                            <li class="list-inline-item">
                               <p>Load</p>
                               <h2 ng-bind="pageLoadTime | loadtime"></h2>
                            </li>
                            <li class="list-inline-item">
                               <p>Size</p>
                               <h2 ng-bind="pageBytes | size"></h2>
                            </li>
                            <li class="list-inline-item">
                               <p>Requests</p>
                               <h2 ng-bind="pageRequests"></h2>
                            </li>
                       </ul>
                   </div>
               </div> 
              <!--  <div class="col-lg-6 col-md-6">
                <div class="space20 d-block d-md-none"></div>
                   <h6>Mobile Score</h6>
                   <div class="bg_mb">
                       <h1 class="{{ pagespeedMobileScore | score:true | lowercase }}" ng-bind="pagespeedMobileScore | score"></h1>
                       <hr>
                       <ul class="list-inline">
                            <li class="list-inline-item">
                               <p>Load</p>
                               <h2 ng-bind="pageMobileLoadTime | loadtime"></h2>
                            </li>
                            <li class="list-inline-item">
                               <p>Size</p>
                               <h2 ng-bind="pageMobileBytes | size"></h2>
                            </li>
                            <li class="list-inline-item">
                               <p>Requests</p>
                               <h2 ng-bind="pageMobileRequests"></h2>
                            </li>
                       </ul>
                   </div>
               </div>  -->
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <h3><?php echo get_field('what_do_these_mean');?><a href="<?php echo get_field('learn_more_url');?>">Learn More</a></h3>

                    <div class="table-responsive" ng-if="pagespeedResults.length">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>RECOMMENDATION</td>
                                    <td>GRADE</td>
                                    <td>PRIORITY</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="result in pagespeedResults | limitTo: 10">
                                    <td ng-bind="result.name"></td>
                                    <td>
                                        <span ng-bind="result.score" class="{{ result.score | score:true | lowercase }}"
                                            style="border-right-width: {{ 94 - (94 * result.score / 100) }}px"
                                            ng-class="{'text-black': result.score < 15}"></span>
                                    </td>
                                    <td ng-bind="result.priority"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <?php echo do_shortcode('[contact-form-7 id="390" title="Email Results Form"]')?>
                </div>
            </div>
        </div> 
    </section>
    <!---->
    <section class="summary_sec ng-hide" ng-show="nextAnalysisPage">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3>  <?php echo get_field('analysis_summary');?> </h3>
                    <p><?php echo get_field('analysis_summary_sub_title');?></p>
                    <div class="space20"></div>
                    <ul class="list-inline">
                        <?php if(get_field('analysis_summary_content')): $i=1;while(the_repeater_field('analysis_summary_content')): $image = get_sub_field('image');
                            $class = ""; if($i=="1"){ $class="lightgreen";}
                        ?>
                        <li class="list-inline-item">
                            <h1><?php the_sub_field('first'); ?></h1>
                            <hr>
                            <?php if($i!='3') { ?>
                                <h5 class="<?php echo $class; ?>"><?php the_sub_field('second'); ?></h5>
                            <?php } else { ?> 
                                <div class="space40"></div> 
                            <?php } ?>
                            <h6><?php the_sub_field('third'); ?></h6>
                            <?php if($image !=""):?>
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo4.png" class="img-fluid mx-auto d-block" alt="..">
                            <?php endif; ?>
                            <div class="space60 d-lg-block d-none"></div>
                            <div class="space40 d-md-block d-lg-none"></div>
                        </li>
                        <?php $i++; endwhile; endif; ?> 
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!---->
    <section class="speed_up"> 
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3><?php echo get_field('speed_title')?></h3>
                    <p><?php echo get_field('speed_sub_title')?></p>
                </div>
            </div>
            <div class="row test_row">
                <?php if(get_field('speed_type')): while(the_repeater_field('speed_type')): ?>
                <div class="col-lg-4 col-md-4 right_arrow">
                    <div class="text-center">
                        <h1><?php the_sub_field('sr'); ?></h1>
                    </div>
                    <h4><?php the_sub_field('title'); ?></h4>
                    <?php the_sub_field('content'); ?>
                </div>
                <?php endwhile; endif; ?>
            </div>
        </div>
    </section>
    <!---->
    <section class="priority">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="white_box">
                            <div class="space50"></div>
                            <?php $ampImg = get_field('amp_image');?>
                            <img src="<?php echo $ampImg['url']; ?>" class="img-fluid d-block mx-auto" alt="...">
                            <div class="row">
                                <div class="col-lg-8 offset-lg-2">
                                    <div class="media ">
                                        <div class="media-left mr-3">
                                            <h2 class="align-self-center">$<?php echo get_field('amp_price');?></h2>
                                        </div>
                                        <div class="media-body">
                                            <p class="pad0">One time<br> payment</p>
                                        </div>
                                    </div>
                                    <ul class="list-unstyled ampe_ul1 d-none d-md-block">
                                        <?php if(get_field('amp_service')): while(the_repeater_field('amp_service')): ?>
                                        <li><?php the_sub_field('title'); ?> </li>
                                        <?php endwhile; endif; ?>
                                    </ul>
                                    <ul class="list-unstyled padleftul d-block d-md-none">
                                        <?php if(get_field('amp_service')): while(the_repeater_field('amp_service')): ?>
                                        <li><?php the_sub_field('title'); ?> </li>
                                        <?php endwhile; endif; ?>
                                    </ul>
                                    <button class="btn btn_now" ng-click="checkout('<?php echo get_site_url() . '/checkout?id=1' ?>')"> <?php echo get_field('amp_button_title');?></button>
                                    <p class="guarantee_txt"> <?php echo get_field('amp_money_back_title');?></p>
                                    <div class="space60"></div>
                                </div>
                            </div>
                            <h5> <?php echo get_field('amp_testimonial');?></h5>
                            <h3>- <?php echo get_field('amp_author');?> <i><?php echo get_field('amp_designation');?></i></h3>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="space120 d-block d-md-none"></div>
                        <div class="white_box" id="sale_box">
                            <div class="sale_boxcntnt">
                            <div class="row">
                                <div class="col-lg-10 offset-lg-1 col-md-12">
                                    <h1><?php echo get_field('pro_sale_title');?></h1>

                                    <div class="countdown">
                                        <p>OFFER ENDS</p>
                                        <div id="clockdiv">
                                              <div>
                                                <span class="hours"></span>
                                              </div>
                                              <div>
                                                <span class="minutes"></span>
                                              </div>
                                              <div>
                                                <span class="seconds"></span>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="space10"></div>
                            <h6 class="upgrade"><?php echo get_field('pro_upgrade_title');?> </h6>
                            <div class="space20"></div>
                            <?php $ampProImg = get_field('pro_image');?>
                            <img src="<?php echo $ampProImg['url']; ?>" class="img-fluid d-block mx-auto" alt="...">
                            <ul class="list-inline ampe_ul">
                                <li class="list-inline-item">
                                    <h1 class="del"><del>$<?php echo get_field('pro_price');?></del></h1>
                                </li>
                                <li class="list-inline-item">
                                    <div class="media">
                                        <div class="media-left mr-3">
                                            <h2 class="align-self-center">$<?php echo get_field('pro_price_new');?></h2>
                                        </div>
                                        <div class="media-body">
                                            <p class="pad0">One time<br> payment</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>                                                        
                                <ul class="list-unstyled padleftul  d-none d-md-block">
                                    <?php if(get_field('pro_service')): while(the_repeater_field('pro_service')): ?>
                                        <li><?php the_sub_field('title'); ?> </li>
                                        <?php endwhile; endif; ?>
                                </ul>
                                <ul class="list-unstyled padleftul d-block d-md-none">
                                    <?php if(get_field('pro_service')): while(the_repeater_field('pro_service')): ?>
                                        <li><?php the_sub_field('title'); ?> </li>
                                        <?php endwhile; endif; ?>
                                </ul>
                                <button type="button" class="btn btn_now ornge" ng-click="checkout('<?php echo get_site_url() . '/checkout?id=2' ?>')">  <?php echo get_field('pro_btn_title');?></button>
                                    <div class="space40"></div>
                                    <h6 class="trget">Target Results</h6>
                                    <ul class="list-inline number_ul">
                                        <li class="list-inline-item brdrright">
                                            <h2>90+</h2>
                                            <p> <?php echo get_field('target_results_title_1');?> </p>
                                        </li>
                                        <li class="list-inline-item">
                                            <h2>
                                                <div class="media">
                                                <div class="media-body">
                                                    <p>LESS <br> THAN</p>
                                                </div>
                                                <div class="media-right mr-3">
                                                    <h1 class="align-self-center mrbtm0">3</h1>
                                                </div>                                            
                                                </div>
                                            </h2>
                                            <p><?php echo get_field('target_results_title_2');?></p>
                                        </li>
                                    </ul>
                                    <div class="space30"></div>
                            <h5> <?php echo get_field('pro_testimonial');?></h5>
                            <h3>- <?php echo get_field('pro_author');?> <i><?php echo get_field('pro_designation');?></i></h3>
                        </div>

                    </div>
                </div>
                <div class="space50"></div>
                <div class="row order_media">
                            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                                <div class="media">
                                    <?php $btm_image = get_field('btm_image');?>
                                  <img class="align-self-start mr-3" src="<?php echo $btm_image['url']; ?>" alt="Generic placeholder image">
                                  <div class="media-body">
                                    <h5 class="mt-0"><?php echo get_field('btm_title');?></h5>
                                    <p><?php echo get_field('btm_sub_title');?></p>                        
                                  </div>
                                </div>
                            </div>
                        </div>
            </div>
        </section>
    <!---->
    <section class="speed_up">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3><?php echo get_field('all_page_amp_title')?> </h3>
                </div>
            </div>
            <div class="row test_row space30">
                <?php if(get_field('all_page_amp_content')): while(the_repeater_field('all_page_amp_content')):
                $image = get_sub_field('image');
                ?>
                <div class="col-lg-4 col-md-4">
                    <img src="<?php echo $image['url'];?>" class="img-fluid mx-auto d-block" alt="..">
                    <h4><?php the_sub_field('title'); ?></h4>
                    <?php the_sub_field('content'); ?>
                </div>
                <?php endwhile; endif; ?>
            </div>
            <!---->
            <?php echo get_field('all_page_amp_services');?>
            <!---->
        </div>
    </section>

    <!---->
    <section class="speed_up" id="volume_">
        <div class="container">
            <div class="row">
                <div class="col-lg-12" id="c7form">
                    <?php echo the_content() ?>
                    <?php endwhile; endif; ?>
                    <?php echo do_shortcode('[contact-form-7 id="266" title="New Contact"]')?>
                </div>
            </div>
        </div>
    </section>
</section>
<?php 
get_footer();
?>
<script>
function getTimeRemaining(endtime) {   
  var t = Date.parse(endtime) - Date.parse(new Date());   
  var seconds = Math.floor((t / 1000) % 60); 
  var minutes = Math.floor((t / 1000 / 60) % 60);
  var hours = Math.floor((t / (1000 * 60 * 60)) % 20);

  return {
    'total': t,
    'hours': hours,
    'minutes': minutes,
    'seconds': seconds
  };
}

function initializeClock(id, endtime) {
  var clock = document.getElementById(id);
  var hoursSpan = clock.querySelector('.hours');
  var minutesSpan = clock.querySelector('.minutes');
  var secondsSpan = clock.querySelector('.seconds');

  function updateClock() {
    var t = getTimeRemaining(endtime);
    hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
    minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
    secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

    if (t.total <= 0) {
      clearInterval(timeinterval);
    }
  }

  updateClock();
  var timeinterval = setInterval(updateClock, 1000);
}

var deadline = new Date(Date.parse(new Date()) + 15 * 24 * 60 * 60 * 1000);
initializeClock('clockdiv', deadline);

$(function () {
    window.animateAnalyze = true;
    var animationDuration = 5000;
    var loadingLineStart = {
        'desktop': 10,
        'mobile': 10,
    };
    var loadingLineEnd = {
        'desktop': 0,
        'mobile': 0
    };
    var animation = {};

    var setupLoading = function (type) {
        loadingLineEnd[type] += $('.load-container.' + type).width();
        if (screen.width < 768) {
            $('.loading-line.mobile').css('margin-top', $('.loading-line.desktop').css('height'));
        } else {
            $('.load-container.' + type)
                .prevAll().each(function () {
                    var thisWidth = $(this).outerWidth();
                    loadingLineStart[type] += thisWidth;
                    loadingLineEnd[type] += thisWidth;
                })
        }
        $('.loading-line.' + type).css('left', loadingLineStart[type]);

        var animateEle = function () {
            $('.loading-line.' + type).animate({
                left: loadingLineEnd[type]
            }, animationDuration, 'swing', function () {
                var tmp = loadingLineStart[type];
                loadingLineStart[type] = loadingLineEnd[type];
                loadingLineEnd[type] = tmp;
            });
        }   
        animation[type] = setInterval(function () {
            if (window.animateAnalyze) {
                animateEle();
            } else {
                clearInterval(animation[type]);
                $('.load-container.' + type).addClass('loaded');
            }
        }, animationDuration);
    }

    setupLoading('desktop');
    setupLoading('mobile');


});

</script>
