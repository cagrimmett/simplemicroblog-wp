<?php
/**
 * micro functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package micro
 */

if ( ! function_exists( 'micro_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function micro_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on micro, use a find and replace
	 * to change 'micro' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'micro', get_template_directory() . '/languages' );

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
		'menu-1' => esc_html__( 'Primary', 'micro' ),
	) );

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
	add_theme_support( 'custom-background', apply_filters( 'micro_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;
add_action( 'after_setup_theme', 'micro_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function micro_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'micro_content_width', 640 );
}
add_action( 'after_setup_theme', 'micro_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function micro_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'micro' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'micro' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name' => 'Footer widgets',
		'id' => 'footer-widgets',
		'description' => 'Appears in the footer',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget' => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
		) );
	}
add_action( 'widgets_init', 'micro_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function micro_scripts() {
	wp_enqueue_style( 'micro-style', get_stylesheet_uri() );

	wp_enqueue_script( 'micro-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'micro-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'micro_scripts' );

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
 * Add microblog verification setting page
 */
require get_template_directory() . '/microblog_verification.php';

/*--------------------------------------------------------------
Remove comments
Functions from https://gist.github.com/mattclements/eab5ef656b2f946c4bfb
--------------------------------------------------------------*/
// Disable support for comments and trackbacks in post types
function df_disable_comments_post_types_support() {
	$post_types = get_post_types();
	foreach ($post_types as $post_type) {
		if(post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}
add_action('admin_init', 'df_disable_comments_post_types_support');
// Close comments on the front-end
function df_disable_comments_status() {
	return false;
}
add_filter('comments_open', 'df_disable_comments_status', 20, 2);
add_filter('pings_open', 'df_disable_comments_status', 20, 2);
// Hide existing comments
function df_disable_comments_hide_existing_comments($comments) {
	$comments = array();
	return $comments;
}
add_filter('comments_array', 'df_disable_comments_hide_existing_comments', 10, 2);
// Remove comments page in menu
function df_disable_comments_admin_menu() {
	remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'df_disable_comments_admin_menu');
// Redirect any user trying to access comments page
function df_disable_comments_admin_menu_redirect() {
	global $pagenow;
	if ($pagenow === 'edit-comments.php') {
		wp_redirect(admin_url()); exit;
	}
}
add_action('admin_init', 'df_disable_comments_admin_menu_redirect');
// Remove comments metabox from dashboard
function df_disable_comments_dashboard() {
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'df_disable_comments_dashboard');
// Remove comments links from admin bar
function df_disable_comments_admin_bar() {
	if (is_admin_bar_showing()) {
		remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
	}
}
add_action('init', 'df_disable_comments_admin_bar');

/*--------------------------------------------------
	Hide Title from Title Remover plugin by http://www.brittanyinternetservices.com/
----------------------------------------------------*/

function wptr_supress_title($title, $post_id) {
	global $id;
	$hide_title = get_post_meta( $post_id, 'wptr_hide_title', true );
    if (!is_admin() && intval($hide_title) && in_the_loop())
        return '';
    return $title;
}
add_filter('the_title', 'wptr_supress_title', 10, 2);

/*--------------------------------------------------
	MetaBox
----------------------------------------------------*/

add_action( 'load-post.php', 'wptr_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'wptr_post_meta_boxes_setup' );

function wptr_post_meta_boxes_setup() {
	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes', 'wptr_add_post_meta_boxes' );

	/* Save post meta on the 'save_post' hook. */
	add_action( 'save_post', 'wptr_save_meta', 10, 2 );
}

function wptr_add_post_meta_boxes() {
	add_meta_box(
		'wptr-hide-title',		// Unique ID
		'Hide Title?',			// Title
		'wptr_render_metabox',	// Callback function
		null,					// Admin page
		'side',					// Context
		'core'					// Priority
	);
}

function wptr_render_metabox( $object, $box ) {
	$curr_value = get_post_meta( $object->ID, 'wptr_hide_title', true );
	wp_nonce_field( basename( __FILE__ ), 'wptr_meta_nonce' );
?>
	<input type="hidden" name="wptr-hide-title-checkbox" value="0" />
	<input type="checkbox" name="wptr-hide-title-checkbox" id="wptr-hide-title-checkbox" value="1" <?php checked($curr_value, '1'); ?> />
	<label for="wptr-hide-title-checkbox">Hide the title for this item</label>
<?php
}

function wptr_save_meta( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['wptr_meta_nonce'] ) || !wp_verify_nonce( $_POST['wptr_meta_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	$form_data = ( isset( $_POST['wptr-hide-title-checkbox'] ) ?  $_POST['wptr-hide-title-checkbox'] : '0' );
	update_post_meta( $post_id, 'wptr_hide_title', $form_data );
}

/*--------------------------------------------------
	Theme Options panel - https://codex.wordpress.org/Creating_Options_Pages
----------------------------------------------------*/
