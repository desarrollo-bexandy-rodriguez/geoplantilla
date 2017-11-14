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


/*
===========================================================================
 * Customizar Wyz-Toolkit Plugin
===========================================================================
*/

/**
 * Global map search handler.
 */
function custom_get_businesses_js_data() {
	$nonce = filter_input( INPUT_POST, 'nonce' );
	if ( ! wp_verify_nonce( $nonce, 'wyz_ajax_custom_nonce' ) ) {
		wp_die( 'busted' );
	}

	//$coor = get_post_meta( $l_id, 'wyz_location_coordinates', true );

	$bus_names = filter_input( INPUT_POST, 'bus-name' );
	$cat_id = filter_input( INPUT_POST, 'cat-id' );
	$loc_id = filter_input( INPUT_POST, 'loc-id' );
	$rad = filter_input( INPUT_POST, 'rad' );
	$lat = filter_input( INPUT_POST, 'lat' );
	$lon = filter_input( INPUT_POST, 'lon' );
	$is_listing_page = filter_input( INPUT_POST, 'is-listing' );
	$is_grid_view = filter_input( INPUT_POST, 'is-grid' );
	$posts_per_page = filter_input( INPUT_POST, 'posts-per-page' );
	$page = filter_input( INPUT_POST, 'page' );

	if ( $posts_per_page < 0 || is_nan( $posts_per_page ) )
		$posts_per_page = 10;


	$template_type = '';
	if ( function_exists( 'wyz_get_theme_template' ) )
			$template_type = wyz_get_theme_template();

	if ( $template_type == 1 )
		$template_type = '';

	//$loc_radius_search = false;


	if ( ! $rad || '' == $rad || ! is_numeric( $rad ) ) {
		$rad = 0;
		$lat = $lon = 0;
	}

	$results = WyzHelpers::wyz_handle_business_search( $bus_names, $cat_id, $loc_id, $rad, $lat, $lon, $page );
	$args =  $results['query'] ;
	$lat = $results['lat'];
	$lon = $results['lon'];


 	$featured_posts_per_page = get_option( 'wyz_featured_posts_perpage', 2 );


 	if ($is_listing_page && empty($bus_names)) {



		$sticky_posts = get_option( 'sticky_posts' );

		$cat_feat = array(
			'post_type' => 'wyz_business',
			'post__in' => $sticky_posts,
			'fields' => 'ids',
		);


		$featured_businesses_args = array(
			'post_type' => 'wyz_business',
			//'posts_per_page' => $featured_posts_per_page,
			'post__in' => $sticky_posts,
			'fields' => 'ids',
			'offset' => $page,
		);

		if ( isset( $args['tax_query'] ) ) {
			$featured_businesses_args['tax_query'] = $args['tax_query'];
		}


		$featured_businesses_args = apply_filters( 'wyz_query_featured_businesses_args_search', $featured_businesses_args, $args );


		$query1 = new WP_Query( $featured_businesses_args );

		$sticky_posts = $query1->posts;

		if ( count( $sticky_posts ) > $featured_posts_per_page ) {

			Wyzhelpers::fisherYatesShuffle( $sticky_posts, rand(10,100) );
			$sticky_posts = array_slice( $sticky_posts, 0, $featured_posts_per_page );
		}

		$args['fields'] = 'ids';
		//$args['post__not_in'] = get_option( 'sticky_posts' );
		$args['post_type'] = 'wyz_business';

		$query2 = new WP_Query( $args );

		$all_the_ids = array_merge( $sticky_posts, $query2->posts  );

		if ( empty( $all_the_ids ) ) $all_the_ids = array( 0 );

		$final_query_args = array(
			'post_type' => 'wyz_business',
			'post__in' => $all_the_ids,
			'orderby' => 'post__in',
			'offset' => $page,
			'posts_per_page' => -1,
		);


		 $query =  new WP_Query( $final_query_args );

 	}else {

 		$query = new WP_Query($args);

 	}




	// $query = new WP_Query($args);



	// This query will get all businesses that match the search in their title, slogan, category and excerpt.

	//$query = new WP_Query( $args );

	$posts_for_nxt_loop = array();

	$user_favorites = WyzHelpers::get_user_favorites();

	$favorites = array();

	$locations = array();
	$marker_icons = array();
	$business_names = array();
	$business_after_names = array();
	$business_logoes = array();
	$business_permalinks = array();
	$business_cat_ids = array();
	$business_cat_colors = array();
	$business_list = '';
	$current_b_ids = array();

	$i = 0;
	$posts_count = 0;


	while ( $query->have_posts() ) {

		$query->the_post();
		$b_id = get_the_ID();
		$temp_loc = get_post_meta( $b_id, 'wyz_business_location', true );

		$def_marker_coor = array('latitude' => get_option( 'wyz_businesses_default_lat', 0 ), 'longitude' => get_option( 'wyz_businesses_default_lon', 0 ) );

		if ( empty( $temp_loc ) ) {
			$temp_loc = array(
				'latitude' => $def_marker_coor['latitude'],
				'longitude' => $def_marker_coor['longitude'],
			);
		}

		$posts_count++;

		// If the business has map coordinates and is within range (in case search radius was provided),
		// add its id to $posts_for_nxt_loop
		if ( 0 != $lat && 0 != $lon && 0 != $rad ) {
			$pos = array( 'lat' => $temp_loc['latitude'], 'lon' => $temp_loc['longitude'] );
			$my_pos = array( 'lat' => $lat, 'lon' => $lon );
			if ( $rad < wyz_get_distance( $pos, $my_pos ) ) {
				continue;
			}
		}

		array_push( $favorites, in_array( $b_id, $user_favorites ) );
		array_push( $locations, $temp_loc );
		array_push( $business_names, get_the_title() );
		array_push( $business_after_names, apply_filters( 'wyzi_after_business_name_info_bubble', '', $b_id ) );
		array_push( $business_permalinks, esc_url( get_the_permalink() ) );
		array_push( $posts_for_nxt_loop, $b_id );


		if ( $is_listing_page && $i++ < ( $posts_per_page + $featured_posts_per_page ) ) {

			array_push( $current_b_ids, $b_id );
			if ( $is_grid_view ) {
				$business_list .= custom_create_business_grid_look();

			} else {
				$business_list .= custom_create_business();

			}
		}

		if ( has_post_thumbnail() ) {
			array_push( $business_logoes, get_the_post_thumbnail( $b_id, 'medium', array( 'class' => 'business-logo-marker' ) ) );
		} else {
			array_push( $business_logoes, '' );
		}

		$temp_term = WyzHelpers::wyz_get_representative_business_category_id( $b_id );

		if ( '' != $temp_term ) {

			$col = get_term_meta( $temp_term, 'wyz_business_cat_bg_color', true );

			if ($temp_term == 17) {
				$sticky = is_sticky($b_id);
				if ($sticky) {
					$holder = wp_get_attachment_url( get_term_meta( $temp_term, "map_icon4", true ) );
				} else {
					$holder = wp_get_attachment_url( get_term_meta( $temp_term, "map_icon3", true ) );
				}

			} else {
				$holder = wp_get_attachment_url( get_term_meta( $temp_term, "map_icon$template_type", true ) );
			}


		} else {
			$col = '';
		}
		if ( ! isset( $holder ) || false == $holder ) {
			$marker = '';
		} else {
			$marker = $holder;
		}

		array_push( $business_cat_ids, intval( $temp_term ) );
		array_push( $business_cat_colors, $col );


		if ( false == $marker ) {
			array_push( $marker_icons, '' );
			array_push( $business_cat_ids, -1 );
		} else {
			array_push( $marker_icons, $marker );
		}
	}
	wp_reset_postdata();


	if ( empty( $posts_for_nxt_loop ) ) {
		$posts_for_nxt_loop[] = -1;
	}

	if ( $is_listing_page ) {
		$remaining_pages = ceil( ( sizeof( $posts_for_nxt_loop ) / ( float ) $posts_per_page ) -1 );
	} else {
		$remaining_pages = 0;
	}

	wp_reset_postdata();

	if ( ! isset( $locations ) || ! isset( $marker_icons ) ) {
		$locations = array();
		$marker_icons = array();
	}
// Lets pass Essential Grid Shortcode in case needed
	$ess_grid_shortcode ='';

	if ( function_exists( 'wyz_get_theme_template' ) ) {
		$template_type = wyz_get_theme_template();

		if ( $template_type == 2 ) {
			$grid_alias = wyz_get_option( 'listing_archives_ess_grid' );
			$ess_grid_shortcode = do_shortcode( '[ess_grid alias="' . $grid_alias .'" posts='.implode(',',$current_b_ids).']' );
		}
	}



	$global_map_java_data = array(
		'defCoor' => array(),
		'radiusUnit' => '',
		'GPSLocations' => $locations,
		'markersWithIcons' => $marker_icons,
		'businessNames' => $business_names,
		'afterBusinessNames' => $business_after_names,
		'businessLogoes' => $business_logoes,
		'businessPermalinks' => $business_permalinks,
		'businessCategories' => $business_cat_ids,
		'businessCategoriesColors' => $business_cat_colors,
		'isListingPage' => $is_listing_page,
		'postsPerPage' =>$posts_per_page,
		'businessIds' => $posts_for_nxt_loop,
		'businessList' => $business_list,
		'hasAfter' => $remaining_pages > 0,
		'favorites' => $favorites,
		'hasBefore' => false,
		'postsCount' => $posts_count,
		'ess_grid_shortcode' => $ess_grid_shortcode,
	);

	wp_die( wp_json_encode( $global_map_java_data ) );
}
add_action( 'wp_ajax_global_map_search', 'custom_get_businesses_js_data' );
add_action( 'wp_ajax_nopriv_global_map_search', 'custom_get_businesses_js_data' );


function custom_create_business_grid_look() {

		if ( method_exists( 'WyzBusinessPostOverride', 'wyz_create_business_grid_look') ) {
			return WyzBusinessPostOverride::wyz_create_business_grid_look();
		}

		$business_data = WyzBusinessPost::wyz_get_business_data( get_the_ID() );
		$sticky = is_sticky();
		if ($sticky) {
			return 'Hola Patricia';
		} else {
			return false;
		}

	}

function custom_create_business() {

		if ( method_exists( 'WyzBusinessPostOverride', 'wyz_create_business') ) {
			return WyzBusinessPostOverride::wyz_create_business();
		}

		$business_data = WyzBusinessPost::wyz_get_business_data( get_the_ID() );
		return 'Hola Pepito';
	}

?>
