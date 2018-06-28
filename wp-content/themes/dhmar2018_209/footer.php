<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package dhmar2018_209
 */

?>
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <?php 
        $menuLocations = get_nav_menu_locations();   
        $menuID = $menuLocations['header_menu'];
        $primaryNav = wp_get_nav_menu_items($menuID);   
        $i=1; 
        foreach ($primaryNav as $navItem) {
        ?>
        <a href="<?php echo $navItem->url; ?>" data-name="<?php echo $navItem->title; ?>"><?php echo $navItem->title; ?></a>
        <?php $i++; } ?> 
    </div>
    <!---->
    <footer >
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="list-inline">
                        <li class="list-inline-item"><a href="#a">Home</a></li>
                        <?php 
                        $menuLocations = get_nav_menu_locations();   
                        $menuID = $menuLocations['main_menu'];
                        $primaryNav = wp_get_nav_menu_items($menuID);   
                        $i=1; 
                        foreach ($primaryNav as $navItem) {
                            $class="";
                            if($i=='1')
                            {
                                $class = 'active';
                            }
                        ?>
                        <li class="list-inline-item">
                            <a class="<?php echo $class; ?>" href="<?php echo $navItem->url; ?>" ><?php echo $navItem->title; ?></a>
                        </li>
                        <?php $i++;} ?>
                        
                    </ul> 
                    <a href="<?php echo get_site_url() ?>"><img src="<?php echo get_option("theme_phototwo_about");?>" class="img-fluid mx-auto d-block" alt=".."></a>
                    <p> <?php echo get_option("theme_cpy_content");?> </p>
                </div>
            </div>
        </div>
    </footer>

    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/wow.min.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/bower_components/socket.io-client/dist/socket.io<?php echo WP_DEBUG ? '' : '.min' ?>.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/bower_components/angular/angular<?php echo WP_DEBUG ? '' : '.min' ?>.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/bower_components/angular-img-http-src/index.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/bower_components/moment<?php echo WP_DEBUG ? '' : '/min' ?>/moment<?php echo WP_DEBUG ? '' : '.min' ?>.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/bower_components/moment-timezone/builds/moment-timezone-with-data<?php echo WP_DEBUG ? '' : '.min' ?>.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/app.js"></script>
    <!--<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/counter.js"></script> -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>

    <script type="text/javascript">
        var socketTestStatusEvent = '<?= getenv('SOCKET_TEST_STATUS_EVENT') ?>';
        var socket = io('//<?= getenv('SOCKET_HOST') ?>:<?= getenv('SOCKET_PORT') ?>');
        var userAgent = navigator.userAgent||navigator.vendor||window.opera;
        var isMobilePhone = /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(userAgent)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(userAgent.substr(0,4));
        var hideFromMobile = ['Test My Site', 'Learn More', 'Help Center'];

        $(function () {

            socket.on('connect', function () {
                console.log('Connected socket');
                var $rootScope = angular.element(document.body).injector().get('$rootScope');
                $rootScope.$apply(function () { 
                    $rootScope.$broadcast('SOCKET:INITALIZED');
                });
            });

            if (isMobilePhone) {
                $('body').addClass('is_mobile_phone');
                for (var key in hideFromMobile) {
                    $('[data-name="' + hideFromMobile[key] + '"]').hide();
                }
            }
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

<script type="text/javascript">
function priceTable() {
    var whiteLeft = $(".white-left").innerWidth(),
        greenWidth = $(".green-width").innerWidth(),
        whiteHeight = $(".price").innerHeight();

    $(".bg-white").css({
        left: whiteLeft,
        bottom: whiteHeight
    });

    $(".bg-green").css({
        width: greenWidth
    });
}

$(document).ready(priceTable);
$(window).on("resize load", priceTable);
</script>
<?php wp_footer(); ?>

</body>
</html>
