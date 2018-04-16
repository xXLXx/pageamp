<?php
/**
 * dhmar2018_209 functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package dhmar2018_209
 */

if ( ! function_exists( 'dhmar2018_209_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function dhmar2018_209_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on dhmar2018_209, use a find and replace
		 * to change 'dhmar2018_209' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'dhmar2018_209', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'main_menu' => esc_html__( 'Primary', 'dhmar2018_209' ),
		) );
	 
    
        register_nav_menus( array(
            'header_menu' => 'Header Top Menu',
        ));
        
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'dhmar2018_209_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'dhmar2018_209_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function dhmar2018_209_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'dhmar2018_209_content_width', 640 );
}
add_action( 'after_setup_theme', 'dhmar2018_209_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function dhmar2018_209_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'dhmar2018_209' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'dhmar2018_209' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'dhmar2018_209_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function dhmar2018_209_scripts() {
	wp_enqueue_style( 'dhmar2018_209-style', get_stylesheet_uri() );

	wp_enqueue_script( 'dhmar2018_209-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'dhmar2018_209-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'dhmar2018_209_scripts' );

/**
 * Hosted API implementation
 */
require get_template_directory() . '/api.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Custom Function file include
 */
 require get_template_directory() . '/inc/custom-function.php';


/*
Custom Post
*/
register_post_type(
	'testimonial',
	array(
		'labels' => array(
			'name' => 'Testimonial',
			'singular_name' => 'testimonial'
		),
		'menu_icon' => 'dashicons-align-left',
		'public' => true,
		'has_archive' => false,
		'rewrite' => array('slug' => 'testimonial'),
		'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', 'page-attributes', 'post-formats'),
		'can_export' => true,
	)
);
register_taxonomy('testimonial', 'testimonial', array('hierarchical' => true,'label' => 'Categories', 'query_var' => true, 'rewrite' => true));

register_post_type(
	'faq',
	array(
		'labels' => array(
			'name' => 'FAQ',
			'singular_name' => 'faq'
		),
		'menu_icon' => 'dashicons-align-left',
		'public' => true,
		'has_archive' => false,
		'rewrite' => array('slug' => 'faq'),
		'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', 'page-attributes', 'post-formats'),
		'can_export' => true,
	)
);
register_taxonomy('faq', 'faq', array('hierarchical' => true,'label' => 'Categories', 'query_var' => true, 'rewrite' => true));