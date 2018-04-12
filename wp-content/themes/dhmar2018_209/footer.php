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
        <a href="<?php echo $navItem->url; ?>" ><?php echo $navItem->title; ?></a>
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
