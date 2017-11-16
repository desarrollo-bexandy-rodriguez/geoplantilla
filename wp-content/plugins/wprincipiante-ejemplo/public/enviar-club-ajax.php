<?php
/**
 * [Short Description]
 *
 * [Long Description.]
 *
 * @since [1.0]
 *
 * @package [package Name]
 * @subpackage [subpackage Name]
 */


/**
 * summary
 *
 * @return void
 * @author
 */

function custom_get_businesses_js_data() {


  $latitude = esc_attr( get_option( 'wyz_businesses_default_lat' ) );
  $longitude = esc_attr( get_option( 'wyz_businesses_default_lon' ) );
  $zoom = get_option( 'wyz_archives_map_zoom', 12 );

  $def_coor = array(
    'latitude' => $latitude,
    'longitude' => $longitude,
    'zoom' => $zoom,
    );

  $taxonomies = WyzHelpers::get_business_categories();

  $global_map_java_data = WyzMap::wyz_get_businesses_js_data( get_the_ID(), $def_coor, $taxonomies );

  var_dump($global_map_java_data);
  wp_die( wp_json_encode( $global_map_java_data ) );
}

function custom_create_business_grid_look() {

    $business_data = WyzBusinessPost::wyz_get_business_data( get_the_ID() );
    $sticky = is_sticky();
    if ($sticky) {
      return custom_get_business_header( true , $business_data ) . custom_get_business_content( $business_data, true ) . custom_get_business_footer( $business_data );
    } else {
      return false;
    }

  }

 function custom_get_business_header( $is_grid , $business_data = ''){

    $sticky = is_sticky();
    ob_start();?>
    <div class="sin-bordes sin-busi-post<?php echo $is_grid ? ' bus-post-grid' : '';
                     echo $sticky ? ' bus-sticky' : '';?> sin-busi-item" style="border: 0px !important;">
      <div class="head fix con-bordes" style="border: 1px solid #ececec !important; position: relative;">
      <?php if ( has_post_thumbnail() ) {?>
        <a href="<?php echo get_the_permalink();?>" class="post-logo"><?php the_post_thumbnail( 'medium' );?></a>
      <?php } ?>
        <h3 style="padding: 0 5px;"><a href="<?php echo get_the_permalink();?>">Prueba <?php the_title();?></a></h3>
        <div class="" style="position: absolute; bottom: 3px; right: 15px;">
            <?php
              $business_id = $business_data['id'];
              $mapGPS = get_post_meta($business_id,'wyz_business_location',true);
              $lat = $mapGPS['latitude'];
              $lon = $mapGPS['longitude'];
             ?>

              <a href="<?php echo get_the_permalink();?>" class="btn btn-danger btn-xs" title="Leer Más">
                <span class="dashicons dashicons-info"></span>  Leer Más
              </a>

            <a href="https://www.google.com/maps?daddr=<?php echo $lat; ?>,<?php echo $lon; ?>" class="btn btn-primary btn-xs" title="Cómo Llegar">
            <span class="dashicons dashicons-admin-site"></span> Cómo Llegar
            </a>
        </div>

      </div>
    <?php
    return ob_get_clean();
  }



function custom_get_business_content( $business_data, $is_grid ){

    ob_start();
    $excerpt_len = $is_grid ? 150 : 230;?>
    <div class="content con-bordes" style="padding: 0px !important; border: 1px solid #ececec !important; margin-top: 15px !important; height: 200px !important;" >
        Este es mi plugin personal
      <?php if ( '' != $business_data['description'] ) { ?>
        <p><?php echo WyzHelpers::substring_excerpt( $business_data['description'], $excerpt_len );//substr( $business_data['description'] , 0, $excerpt_len );?></p>
        <a class="read-more wyz-secondary-color-text" href="<?php echo esc_attr( get_permalink() );?>"><?php esc_html_e( 'read more', 'wyzi-business-finder' )?></a>
      <?php }?>

      <?php

          $meta_custom = '';
          $meta_custom = get_post_meta( $business_data['id'], 'wyzi_claim_fields_0' , true );
          $content = get_post_field('post_content', $business_data['id']);
          echo do_shortcode($meta_custom);
      ?>

      <?php if ( '' !== $business_data['category']['icon'] ) { ?>
        <a class="busi-post-label" style="display:none; background-color:<?php echo esc_attr( $business_data['category']['color'] );?>;" href="<?php echo esc_url( $business_data['category']['link'] );?>">
          <img src="<?php echo esc_url( $business_data['category']['icon'] );?>" alt="<?php echo esc_attr( $business_data['category']['name'] );?>" />
        </a>
      <?php }?>
      </div>

    <?php
    return ob_get_clean();
  }



  function custom_get_business_footer( $business_data ){

    ob_start();?>
      <div class="footer fix"></div>
    </div>
    <?php
    return ob_get_clean();
  }

  function custom_create_business() {


    $business_data = WyzBusinessPost::wyz_get_business_data( get_the_ID() );
    return custom_get_business_header( false , $business_data) . custom_get_business_content( $business_data, false ) . custom_get_business_footer( $business_data );
  }

add_action( 'wp_ajax_global_map_search', 'custom_get_businesses_js_data',5 );
add_action( 'wp_ajax_nopriv_global_map_search', 'custom_get_businesses_js_data',5 );

function custom_paginate_business_list() {
  $nonce = filter_input( INPUT_POST, 'nonce' );
  if ( ! wp_verify_nonce( $nonce, 'wyz_ajax_custom_nonce' ) ) {
    wp_die( 'busted' );
  }

  $offset = filter_input( INPUT_POST, 'offset' );
  $posts_per_page = filter_input( INPUT_POST, 'posts-per-page' );
  $business_ids = json_decode(stripslashes($_POST['business_ids']));
  $is_grid_view = filter_input( INPUT_POST, 'is-grid' );

  if ( empty( $business_ids ) || '' == $offset || 0 > $offset ) {
    wp_die( '' );
  }

  $business_list = '';

  $featured_posts_per_page = get_option( 'wyz_featured_posts_perpage', 2 );

  $args = array(
    'post_type' => 'wyz_business',
    'posts_per_page' => $posts_per_page + $featured_posts_per_page ,
    'post__in' => $business_ids,
    'paged' => $offset,
    'orderby' => 'post__in',
  );


  /* $args = array(
    'post_type' => 'wyz_business',
    'posts_per_page' => $posts_per_page ,
    //'post__in' => $business_ids,
    'paged' => $offset,
    'post_status' => 'publish',
  );



  $results = WyzHelpers::wyz_handle_business_search( $bus_names, $cat_id, $loc_id, $rad, $lat, $lon, 1 );
  $args =  $results['query'] ;



    $featured_posts_per_page = get_option( 'wyz_featured_posts_perpage', 2 );

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
    );

    if ( isset( $args['tax_query'] ) ) {
      $featured_businesses_args['tax_query'] = $args['tax_query'];
    }

    $args2 = array(
      'post_type' => 'wyz_business',
      'posts_per_page' => -1,
      'post__in' => $business_ids,
      'post_status' => 'publish',
      //'paged' => $offset,
    );
    if ( isset( $args2['paged'] ) && 1 < $args2['paged'] ) {
      $featured_businesses_args['paged'] = $args2['paged'];
    }




    $featured_businesses_args = apply_filters( 'wyz_query_featured_businesses_args_search', $featured_businesses_args, $args2 );


    $query1 = new WP_Query( $featured_businesses_args );

    $sticky_posts = $query1->posts;

    $total_number_of_sticky_posts = count($sticky_posts);

    $args['fields'] = 'ids';
    $args['post__not_in'] = get_option( 'sticky_posts' );;

    $args['post_type'] = 'wyz_business';

    $query2 = new WP_Query( $args );

    if ( count( $sticky_posts ) > $featured_posts_per_page ) {

      Wyzhelpers::fisherYatesShuffle( $sticky_posts, rand(10,100) );
      $sticky_posts = array_slice( $sticky_posts, 0, $featured_posts_per_page );
    }




    $all_the_ids = array_merge( $sticky_posts, $query2->posts );

    if ( empty( $all_the_ids ) ) $all_the_ids = array( 0 );

    $final_query_args = array(
      'post_type' => 'wyz_business',
      'post__in' => $all_the_ids,
      'orderby' => 'post__in',

      //'posts_per_page' => $posts_per_page,
      //'post__in' => $business_ids,
      //'offset' => $offset,
      //'paged' => $offset ,
    );


     $query =  new WP_Query( $final_query_args ); */


  $query = new WP_Query( $args );

  $current_b_ids = array();

  while ( $query->have_posts() ) {

    $query->the_post();
    $b_id = get_the_ID();
    array_push( $current_b_ids, $b_id );
    if ( $is_grid_view ) {
      $business_list .= custom_create_business_grid_look();
    } else {
      $business_list .= custom_create_business();
    }
  }
  $remaining_pages = ceil( ( (sizeof( $business_ids ) ) / ( float ) ($posts_per_page + + $featured_posts_per_page) )  ) - $offset;
  wp_reset_postdata();

// Let prepare Essential Grid Shortcode
  $ess_grid_shortcode ='';

  if ( function_exists( 'wyz_get_theme_template' ) ) {
    $template_type = wyz_get_theme_template();

    if ( $template_type == 2 ) {
      $grid_alias = wyz_get_option( 'listing_archives_ess_grid' );
      $ess_grid_shortcode = do_shortcode( '[ess_grid alias="' . $grid_alias .'" posts='.implode(',',$current_b_ids).']' );
    }
  }

  $data = array(
    'businessList' => $business_list,
    'hasAfter' => ( $remaining_pages > 0 ),
    'hasBefore' => ( 1 < $offset ),
    'ess_grid_shortcode' => $ess_grid_shortcode,
  );

  wp_die( wp_json_encode( $data ) );

}

add_action( 'wp_ajax_business_listing_paginate', 'custom_paginate_business_list', 5 );
add_action( 'wp_ajax_nopriv_business_listing_paginate', 'custom_paginate_business_list', 5 );
