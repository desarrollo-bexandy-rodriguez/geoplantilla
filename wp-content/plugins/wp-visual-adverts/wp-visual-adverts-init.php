<?php
use Webcodin\WPVisualAdverts\Core\Agp_Autoloader;

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', 'rpadv_output_buffer');
function rpadv_output_buffer() {
    ob_start();
}

if (file_exists(dirname(__FILE__) . '/agp-core/agp-core.php' )) {
    include_once (dirname(__FILE__) . '/agp-core/agp-core.php' );
} 

add_action( 'plugins_loaded', 'rpadv_activate_plugin' );
function rpadv_activate_plugin() {
    if (class_exists('Webcodin\WPVisualAdverts\Core\Agp_Autoloader') && !function_exists('RPAdv')) {
        $autoloader = Agp_Autoloader::instance();
        $autoloader->setClassMap(array(
            'paths' => array(
                __DIR__ => array('classes'),
            ),
            'namespaces' => array(
                'Webcodin\WPVisualAdverts\Core' => array(
                    __DIR__ => array('agp-core'),
                ),
            ),
            'classmaps' => array (
                __DIR__ => 'classmap.json',
            ),
        ));
        //$autoloader->generateClassMap(__DIR__);

        function RPAdv() {
            return RPAdv::instance();
        }    

        RPAdv();                
    }
}

rpadv_activate_plugin();
