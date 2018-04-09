<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package dhmar2018_209
 */

get_header();
?>
<section class="hero_sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="animated fadeInUp wow"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'dhmar2018_209' ); ?></h1>
                <div class="space50"></div>
                <h5 class="animated fadeInUp wow"><p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'dhmar2018_209' ); ?></p></h5>
            </div>
        </div>
    </div>
</section>
<div class="space70"></div>
<?php
get_footer();
