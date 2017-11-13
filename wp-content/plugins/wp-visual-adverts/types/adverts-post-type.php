<?php

function rpadv_adverts_init() {
    $labels = array(
      'name'               => __('Adverts', 'rpadv'), 
      'singular_name'      => __('Advert', 'rpadv'),
      'add_new'            => __('Add New', 'rpadv'),
      'add_new_item'       => __('Add New Advert', 'rpadv'),
      'edit_item'          => __('Edit Advert', 'rpadv'),
      'new_item'           => __('New Advert', 'rpadv'),
      'all_items'          => __('Adverts', 'rpadv'),
      'view_item'          => __('View Advert', 'rpadv'),
      'search_items'       => __('Search Advert', 'rpadv'),
      'not_found'          => __('No Adverts Found', 'rpadv'),
      'not_found_in_trash' => __('No Adverts Found in Trash', 'rpadv'),
      'parent_item_colon'  => '',
      'menu_name'          => __('WP Visual Adverts', 'rpadv')
    );

    $args = array(
      'labels'             => $labels, 
      'public'             => false,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => 'adverts',
      'show_in_nav_menus'  => true,
      'query_var'          => true,
      'rewrite'            => array( 'slug' =>  _x( 'adverts', 'URL slug'),  'with_front' => false ),
      'capability_type'    => 'post',
      'has_archive'        => false,
      'hierarchical'       => false,
      'menu_position'      => 2,
      'supports'           => array( 
          'title', 
          'editor',
          'thumbnail',
      ),
    );

    register_post_type( 'adverts', $args );

    $labels = array(
      'name'               => __('Advert Categories', 'rpadv'), 
      'singular_name'      => __('Category', 'rpadv'),
      'add_new'            => __('Add New', 'rpadv'),
      'add_new_item'       => __('Add New Category', 'rpadv'),
      'edit_item'          => __('Edit Category', 'rpadv'),
      'new_item'           => __('New Category', 'rpadv'),
      'all_items'          => __('All Categories', 'rpadv'),
      'view_item'          => __('View Category', 'rpadv'),
      'search_items'       => __('Search Category', 'rpadv'),
      'not_found'          => __('No Categories Found', 'rpadv'),
      'not_found_in_trash' => __('No Categories Found in Trash', 'rpadv'),
      'parent_item_colon'  => '',
      'menu_name'          => __('Categories', 'rpadv')
    );

    $args = array(
      'labels'             => $labels,       
      'public'             => true,      
      'show_ui'            => true,
      'show_in_menu'       => false,            
      'show_in_nav_menu'   => true,      
      'show_admin_column'  => true,
      'hierarchical'       => true,      
      'query_var'          => true,      
      'rewrite'            => array( 'slug' =>  _x( 'advert-category', 'URL slug'),  'with_front' => false ),
    );  

    register_taxonomy( 'advert-category', 'adverts', $args );     

    flush_rewrite_rules();    
}
add_action( 'init', 'rpadv_adverts_init' );
