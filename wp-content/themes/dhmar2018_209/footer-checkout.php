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
    
    <!---->
    <footer >
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                     
                    <a href="<?php echo get_site_url() ?>"><img src="<?php echo get_option("theme_phototwo_about");?>" class="img-fluid mx-auto d-block" alt=".."></a>
                    <p> <?php echo get_option("theme_cpy_content");?> </p>
                </div>
            </div>
        </div>
    </footer>
    
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/wow.min.js"></script>
    <!--<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/counter.js"></script> -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
    



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
