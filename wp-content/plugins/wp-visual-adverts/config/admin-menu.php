<?php

return array(
    'adverts' => array(
        'page_title' => 'WCP Visual Ads', 
        'menu_title' => 'WCP Visual Ads', 
        'capability' => 'manage_options',
        'function' => '',
        'position' => null, 
        'hideInSubMenu' => TRUE,
        'icon_url'   => 'dashicons-images-alt2',
        'submenu' => array(
            'edit-tags.php?taxonomy=advert-category&post_type=adverts' => array(
                'page_title' => 'Categories', 
                'menu_title' => 'Categories', 
                'capability' => 'manage_options',
                'function' => '',                                         
            ),
            'adverts-settings' => array(
                'page_title' => 'Settings', 
                'menu_title' => 'Settings', 
                'capability' => 'manage_options',
                'function' => array('RPAdv_Settings', 'renderSettingsPage'),                         
            ),            
        ),
    ),
);
    