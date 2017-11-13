<?php
/**
 * Theme Functions
 *
 * @package wyz
 * @author WzTechno
 * @link http://www.wztechno.com
 */
define( 'WYZ_THEME_DIR', get_template_directory() );
define( 'WYZ_THEME_URI', get_template_directory_uri() );
define( 'WYZ_CSS_DIR', WYZ_THEME_DIR . '/css' );
define( 'WYZ_CSS_URI', WYZ_THEME_URI . '/css' );
define( 'WYZ_IMPORT_DIR', WYZ_THEME_DIR . '/auto-import' );
define( 'WYZ_IMPORT_URI', WYZ_THEME_DIR . '/auto-import' );
define( 'WYZ_TEMPLATES_DIR', WYZ_THEME_DIR . '/templates' );
define('ULTIMATE_NO_EDIT_PAGE_NOTICE', true);
define('ULTIMATE_NO_PLUGIN_PAGE_NOTICE', true);

/*
===========================================================================
 * Loads theme options files
=========================================================================
*/

require_once( WYZ_THEME_DIR . '/includes/theme-options.php' );

/*
===========================================================================
 * Add theme supports
===========================================================================
*/

add_action( 'after_setup_theme', 'wyz_after_theme_setup' );

/**
 * Add theme supports and includes one-click importer.
 */
function wyz_after_theme_setup() {
	global $wp_version, $content_width;
	if ( version_compare( $wp_version, '3.0', '>=' ) ) {
		add_theme_support( 'automatic-feed-links' );
	}
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce' );
	if ( ! isset( $content_width ) ) {
		$set_width = wyz_get_option( 'content-width' );
		if ( ! isset( $set_width ) || empty( $set_width ) ) {
			$content_width = 1140;
		} else {
			$content_width = esc_html( $set_width );
		}
	}

	if ( ! function_exists( '_wp_render_title_tag' ) ) {
		/**
		 * Displays title in page header.
		 */
		function wyz_render_title() {?>
			<title><?php wp_title();?></title>
		<?php }
		add_action( 'wp_head', 'wyz_render_title' );
	} else {
		add_theme_support( 'title-tag' );
	}
	add_theme_support( 'custom-background' );
	add_theme_support( 'custom-logo' );

    load_theme_textdomain( 'wyzi-business-finder', WYZ_THEME_DIR . '/languages' );

	require WYZ_IMPORT_URI . '/wyz-importer.php';
}

require_once( WYZ_THEME_DIR . '/wyz-core/wyz-hooks.php' );
require_once( WYZ_THEME_DIR . '/wyz-core/server-status.php' );

// Register Custom Navigation Walker.
require_once( WYZ_THEME_DIR . '/includes/wp_bootstrap_navwalker.php' );


if ( function_exists( 'register_nav_menus' ) ) {
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'wyzi-business-finder' ),
		'footer' => esc_html__( 'Footer Menu', 'wyzi-business-finder' ),
		'login' => esc_html__( 'Login Menu', 'wyzi-business-finder' ),
	) );
}

// Get headers.
require_once( WYZ_TEMPLATES_DIR . '/headers/header-factory.php' );

// Get footers.
require_once( WYZ_TEMPLATES_DIR . '/footers/footer-factory.php' );

// Get sidebars.
require_once( WYZ_THEME_DIR . '/sidebar/register-sidebars.php' );

// Filter for theme options.
require_once( WYZ_THEME_DIR . '/wyz-core/theme-options-filters.php' );

// Get wizy core functions.
require_once( WYZ_THEME_DIR . '/wyz-core/wyz-core-functions.php' );

// Register required plugins.
require_once( WYZ_THEME_DIR . '/TGMPA/setup.php' );

// Specify the number of Items per shop page in Woocommerce
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 8;' ), 20 );

function lorem_function() {
  return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec nec nulla vitae lacus mattis volutpat eu at sapien. Nunc interdum congue libero, quis laoreet elit sagittis ut. Pellentesque lacus erat, dictum condimentum pharetra vel, malesuada volutpat risus. Nunc sit amet risus dolor. Etiam posuere tellus nisl. Integer lorem ligula, tempor eu laoreet ac, eleifend quis diam. Proin cursus, nibh eu vehicula varius, lacus elit eleifend elit, eget commodo ante felis at neque. Integer sit amet justo sed elit porta convallis a at metus. Suspendisse molestie turpis pulvinar nisl tincidunt quis fringilla enim lobortis. Curabitur placerat quam ac sem venenatis blandit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nullam sed ligula nisl. Nam ullamcorper elit id magna hendrerit sit amet dignissim elit sodales. Aenean accumsan consectetur rutrum.';
}

add_shortcode('lorem', 'lorem_function');

function wmpudev_enqueue_icon_stylesheet() {
	wp_register_style( 'material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons' );
	wp_enqueue_style( 'material-icons');
}
add_action( 'wp_enqueue_scripts', 'wmpudev_enqueue_icon_stylesheet' );

?>
