<?php

/**
 *
 * BASE THEME class that every theme should extend
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 */
abstract class EPKB_Layout {

	protected $kb_config;
	protected $kb_id;
	protected $category_seq_data;
	protected $articles_seq_data;
	protected $is_builder_on = false;

	/**
	 * Show the KB Main page with list of categories and articles
	 *
	 * @param $kb_config
	 * @param bool $is_builder_on
	 * @param array $article_seq
	 * @param array $categories_seq
	 */
	public function display_kb_main_page( $kb_config, $is_builder_on=false, $article_seq=array(), $categories_seq=array() ) {

		$this->kb_config = $kb_config;
		$this->kb_id = $kb_config['id'];

		// category and article sequence
		if ( $is_builder_on && ! empty($article_seq) && ! empty($categories_seq) ) {
			$this->articles_seq_data = $article_seq;
			$this->category_seq_data = $categories_seq;
		} else {
			$this->category_seq_data = EPKB_Utilities::get_kb_option( $this->kb_id, EPKB_Categories_Admin::KB_CATEGORIES_SEQ_META, array(), true );
			$this->articles_seq_data = EPKB_Utilities::get_kb_option( $this->kb_id, EPKB_Articles_Admin::KB_ARTICLES_SEQ_META, array(), true );
		}

		// articles with no categories - temporary add one
		if ( isset($this->articles_seq_data[0]) ) {
			$this->category_seq_data[0] = array();
		}

		$this->is_builder_on = $is_builder_on;

		$this->generate_kb_main_page();
	}

	/**
	 * Generate content of the KB main page
	 */
	protected abstract function generate_kb_main_page();

	/**
	 * Display a link to a KB article.
	 *
	 * @param $title
	 * @param $article_id
	 * @param string $link_other
	 * @param string $prefix
	 */
	public function single_article_link( $title , $article_id, $link_other='', $prefix='' ) {

		if ( empty($article_id) ) {
			return;
		}

		$class1 = $this->get_css_class( 'eckb-article-title' . ( $this->kb_config['section_article_underline'] == 'on' ? ', article_underline_effect' : '' ) );
		$style1 = $this->get_inline_style( 'color:: ' . $prefix . 'article_font_color' );
		$style2 = $this->get_inline_style( 'color:: ' . $prefix . 'article_icon_color' );

		$link = get_permalink( $article_id );
		$link = empty($link) || is_wp_error( $link ) ? '' : $link;  ?>

		<a href="<?php echo esc_url( $link ); ?>" <?php echo $link_other; ?>>
			<span <?php echo $class1 . ' ' . $style1; ?> >
				<i class="ep_font_icon_document" <?php echo $style2; ?>></i>
				<span><?php echo esc_html( $title ) ?></span>
			</span>
		</a> <?php
	}

	/**
	 * Display a search form for core layouts
	 */
	public function get_search_form() {    ?>

		<script>
			var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
		</script>   <?php

		// no search box configured or required
		if ( $this->kb_config['search_layout'] == 'epkb-search-form-0' ) {
			return;
		}

		$style1 = $this->get_inline_style(
			'background-color:: search_background_color,
			 padding-top:: search_box_padding_top,
			 padding-right:: search_box_padding_right,
			 padding-bottom:: search_box_padding_bottom,
			 padding-left:: search_box_padding_left,
			 margin-top::   search_box_margin_top,
			 margin-bottom::search_box_margin_bottom,
			 ');
		$style2 = $this->get_inline_style( 'background-color:: search_btn_background_color, background:: search_btn_background_color, border-width:: search_input_border_width, border-color::search_btn_border_color' );
		$style3 = $this->get_inline_style( 'color:: search_title_font_color' );
		$style4 = $this->get_inline_style( 'border-width:: search_input_border_width, border-color:: search_text_input_border_color, background-color:: search_text_input_background_color, background:: search_text_input_background_color' );
		$class1 = $this->get_css_class( 'epkb-search, ::search_layout' );

		$search_input_width = $this->kb_config['search_box_input_width'];
		$form_style = $this->get_inline_style('width:' . $search_input_width . '%' );		?>

		<div class="epkb-doc-search-container" <?php echo $style1 ?> >

			<h2 <?php echo $style3 ?>> <?php esc_html_e( $this->kb_config['search_title'], 'echo-knowledge-base' ); ?></h2>
			<form id="epkb_search_form" <?php echo $form_style . ' ' . $class1; ?> method="get" action="">

				<div class="epkb-search-box">
					<input type="text" <?php echo $style4 ?> id="epkb_search_terms" name="epkb_search_terms" value="" placeholder="<?php  esc_attr_e( $this->kb_config['search_box_hint'], 'echo-knowledge-base' ); ?>" />
					<input type="hidden" id="epkb_kb_id" value="<?php echo $this->kb_id; ?>"/>
					<button type="submit" id="epkb-search-kb" <?php echo $style2 ?>><?php esc_html_e( $this->kb_config['search_button_name'], 'echo-knowledge-base' ); ?> </button>

					<div class="loading-spinner"></div>
				</div>
				<div id="epkb_search_results"></div>

			</form>

		</div>  <?php
	}

	/**
	 * Output inline CSS style based on configuration.
	 *
	 * @param string $styles  A list of Configuration Setting styles
	 * @return string
	 */
	public function get_inline_style( $styles ) {
		return EPKB_Utilities::get_inline_style( $styles, $this->kb_config );
	}

	/**
	 * Output CSS classes based on configuration.
	 * 
	 * @param $classes
	 * @return string
	 */
	public function get_css_class( $classes ) {
		return EPKB_Utilities::get_css_class( $classes, $this->kb_config );
	}
}