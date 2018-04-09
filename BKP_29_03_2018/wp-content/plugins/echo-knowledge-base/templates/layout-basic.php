<?php
/**
 * The template for displaying Basic Main Page.
 *
 * This template can be overridden by copying it to yourtheme/kb_templates/layout-basic.php.
 *
 * HOWEVER, on occasion Echo Plugins will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		Echo Plugins
 */

global $eckb_kb_id, $epkb_password_checked;

$kb_id = $eckb_kb_id;
$kb_config = epkb_get_instance()->kb_config_obj->get_kb_config_or_default( $kb_id );

/**
 * Display ARTICLE PAGE content
 */
get_header();

// initialize Main Page title
if ( $kb_config[ 'templates_display_main_page_main_title' ] === 'off' ) {
	$kb_main_pg_title = '';
} else {
	$kb_main_pg_title = '<h1 class="eckb_main_title">' . get_the_title() . '</h1>';
}

$template_style1 = EPKB_Utilities::get_inline_style(
           'padding-top::       templates_for_kb_padding_top,
	        padding-bottom::    templates_for_kb_padding_bottom,
	        padding-left::      templates_for_kb_padding_left,
	        padding-right::     templates_for_kb_padding_right,
	        margin-top::        templates_for_kb_margin_top,
	        margin-bottom::     templates_for_kb_margin_bottom,
	        margin-left::       templates_for_kb_margin_left,
	        margin-right::      templates_for_kb_margin_right,', $kb_config );       ?>

	<div class="eckb-kb-template" <?php echo $template_style1; ?>>	        <?php

		    echo $kb_main_pg_title;

			while ( have_posts() ) {

			    the_post();

				if ( post_password_required() ) {
					echo get_the_password_form();
					return;
				}
				$epkb_password_checked = true;

				// get post content
				$post = empty($GLOBALS['post']) ? '' : $GLOBALS['post'];
				if ( empty($post) || ! $post instanceof WP_Post  ) {
					continue;
				}
				$post_content = $post->post_content;

				// output KB Main Page
				$striped_content = empty($post_content) ? '' : preg_replace('/\s+|&nbsp;/', '', $post_content);
				$generate_main_page_directly = empty($striped_content) || strlen($striped_content) < 27;

				// if the page contains only shortcode then directly generate the Main Page
				if ( $generate_main_page_directly ) {
					echo EPKB_Layouts_Setup::output_main_page( $kb_config );

				// otherwise output the full content of the page
				} else {
					$post_content = apply_filters( 'the_content', $post_content );
					echo str_replace( ']]>', ']]&gt;', $post_content );
				}
				
			}  ?>

	</div>   <?php

get_footer();