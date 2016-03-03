<?php
/**
 * Edin functions and definitions
 *
 * @package Edin
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 648; /* pixels */
}

if ( ! function_exists( 'edin_content_width' ) ) :

	function edin_content_width() {
		global $content_width;

		if ( is_page_template( 'page-templates/front-page.php' ) || is_page_template( 'page-templates/full-width-page.php' ) || is_page_template( 'page-templates/grid-page.php' ) ) {
			$content_width = 930;
		}
	}

endif;
add_action( 'template_redirect', 'edin_content_width' );

if ( ! function_exists( 'edin_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function edin_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Edin, use a find and replace
	 * to change 'edin' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'edin', get_template_directory() . '/languages' );

	/*
	 * Add default posts and comments RSS feed links to head.
	 */
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'edin-thumbnail-landscape', 330, 240, true );
	add_image_size( 'edin-thumbnail-square', 330, 330, true );
	add_image_size( 'edin-thumbnail-avatar', 96, 96, true );
	add_image_size( 'edin-featured-image', 648, 9999 );
	add_image_size( 'edin-hero', 1230, 1230, true );

	/*
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary'   => __( 'Primary Menu', 'edin' ),
		'secondary' => __( 'Secondary Menu', 'edin' ),
		'footer'    => __( 'Footer Menu', 'edin' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'status', 'gallery',
	) );

	/*
	 * Editor styles.
	 */
	add_editor_style( array( 'editor-style.css', edin_pt_sans_font_url(), edin_pt_serif_font_url(), edin_pt_mono_font_url() ) );

	/*
	 * Enable support for Excerpt on Pages.
	 * See http://codex.wordpress.org/Excerpt
	 */
	add_post_type_support( 'page', 'excerpt' );

	/*
	 * Setup the WordPress core custom background feature.
	 */
	add_theme_support( 'custom-background', apply_filters( 'edin_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	/**
	 * Add support for Eventbrite.
	 * See: https://wordpress.org/plugins/eventbrite-api/
	 */
	add_theme_support( 'eventbrite' );
}
endif; // edin_setup
add_action( 'after_setup_theme', 'edin_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function edin_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'edin' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer One', 'edin' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Use this widget area to display widgets in the first column of the footer', 'edin' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Two', 'edin' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Use this widget area to display widgets in the second column of the footer', 'edin' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Three', 'edin' ),
		'id'            => 'sidebar-4',
		'description'   => __( 'Use this widget area to display widgets in the third column of the footer', 'edin' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'Front Page One', 'edin' ),
		'id'            => 'sidebar-5',
		'description'   => __( 'Use this widget area to display widgets in the first column of your Front Page', 'edin' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'Front Page Two', 'edin' ),
		'id'            => 'sidebar-6',
		'description'   => __( 'Use this widget area to display widgets in the second column of your Front Page', 'edin' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'Front Page Three', 'edin' ),
		'id'            => 'sidebar-7',
		'description'   => __( 'Use this widget area to display widgets in the third column of your Front Page', 'edin' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'edin_widgets_init' );

/**
 * Register PT Sans font for Edin.
 *
 * @return string
 */
function edin_pt_sans_font_url() {
	$pt_sans_font_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by PT Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'PT Sans font: on or off', 'edin' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional PT Sans character subset specific to your language, translate this to 'cyrillic'. Do not translate into your own language. */
		$subset = _x( 'no-subset', 'PT Sans font: add new subset (cyrillic)', 'edin' );

		if ( 'cyrillic' == $subset ) {
			$subsets .= ',cyrillic-ext,cyrillic';
		}

		$query_args = array(
			'family' => urlencode( 'PT Sans:400,700,400italic,700italic' ),
			'subset' => urlencode( $subsets ),
		);

		$pt_sans_font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $pt_sans_font_url;
}

/**
 * Register PT Serif font for Edin.
 *
 * @return string
 */
function edin_pt_serif_font_url() {
	$pt_serif_font_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by PT Serif, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'PT Serif font: on or off', 'edin' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional PT Serif character subset specific to your language, translate this to 'cyrillic'. Do not translate into your own language. */
		$subset = _x( 'no-subset', 'PT Serif font: add new subset (cyrillic)', 'edin' );

		if ( 'cyrillic' == $subset ) {
			$subsets .= ',cyrillic-ext,cyrillic';
		}

		$query_args = array(
			'family' => urlencode( 'PT Serif:400,700,400italic,700italic' ),
			'subset' => urlencode( $subsets ),
		);

		$pt_serif_font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $pt_serif_font_url;
}

/**
 * Register PT Mono font for Edin.
 *
 * @return string
 */
function edin_pt_mono_font_url() {
	$pt_mono_font_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by PT Mono, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'PT Mono font: on or off', 'edin' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional PT Mono character subset specific to your language, translate this to 'cyrillic'. Do not translate into your own language. */
		$subset = _x( 'no-subset', 'PT Mono font: add new subset (cyrillic)', 'edin' );

		if ( 'cyrillic' == $subset ) {
			$subsets .= ',cyrillic-ext,cyrillic';
		}

		$query_args = array(
			'family' => urlencode( 'PT Mono' ),
			'subset' => urlencode( $subsets ),
		);

		$pt_mono_font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $pt_mono_font_url;
}

/**
 * Enqueue scripts and styles.
 */
function edin_scripts() {
	wp_enqueue_style( 'edin-pt-sans', edin_pt_sans_font_url(), array(), null );

	wp_enqueue_style( 'edin-pt-serif', edin_pt_serif_font_url(), array(), null );

	wp_enqueue_style( 'edin-pt-mono', edin_pt_mono_font_url(), array(), null );

	wp_enqueue_style( 'edin-edincon', get_template_directory_uri() . '/font/edincon.css', array(), '20140606' );

	if ( wp_style_is( 'genericons', 'registered' ) ) {
		wp_enqueue_style( 'genericons' );
	} else {
		wp_enqueue_style( 'genericons', get_template_directory_uri() . '/font/genericons.css', array(), '3.4.1' );
	}

	wp_enqueue_style( 'edin-style', get_stylesheet_uri() );

	wp_enqueue_script( 'edin-navigation', get_template_directory_uri() . '/js/navigation.js', array( 'jquery' ), '20140718', true );
	wp_localize_script( 'edin-navigation', 'screen_reader_text', array(
		'expand'   => '<span class="screen-reader-text">' . __( 'expand child menu', 'edin' ) . '</span>',
		'collapse' => '<span class="screen-reader-text">' . __( 'collapse child menu', 'edin' ) . '</span>',
	) );

	if ( 1 == get_theme_mod( 'edin_search_header' ) ) {
		wp_enqueue_script( 'edin-search', get_template_directory_uri() . '/js/search.js', array( 'jquery' ), '20140707', true );
	}

	wp_enqueue_script( 'edin-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'edin-script', get_template_directory_uri() . '/js/edin.js', array( 'jquery' ), '20140606', true );
	wp_localize_script( 'edin-script', 'screen_reader_text', array(
		'expand'   => '<span class="screen-reader-text">' . __( 'expand child menu', 'edin' ) . '</span>',
		'collapse' => '<span class="screen-reader-text">' . __( 'collapse child menu', 'edin' ) . '</span>',
	) );
}
add_action( 'wp_enqueue_scripts', 'edin_scripts' );

/**
 * Enqueue Google fonts style to admin screen for custom header display.
 *
 * @return void
 */
function edin_admin_fonts() {
	wp_enqueue_style( 'edin-pt-sans', edin_pt_sans_font_url(), array(), null );

	wp_enqueue_style( 'edin-pt-serif', edin_pt_serif_font_url(), array(), null );

	wp_enqueue_style( 'edin-pt-mono', edin_pt_mono_font_url(), array(), null );
}
add_action( 'admin_print_scripts-appearance_page_custom-header', 'edin_admin_fonts' );

/**
 * Remove the separator from Eventbrite events meta.
 */
function edin_remove_meta_separator() {
	return false;
}
add_filter( 'eventbrite_meta_separator', 'edin_remove_meta_separator' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';



/**
 * Load plugin enhancement file to display admin notices.
 */
require get_template_directory() . '/inc/plugin-enhancements.php';