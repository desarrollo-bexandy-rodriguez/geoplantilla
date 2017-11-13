<?php
use Webcodin\WPVisualAdverts\Core\Agp_AjaxAbstract;

class RPAdv_Ajax extends Agp_AjaxAbstract {
    /**
     * The single instance of the class 
     * 
     * @var object
     */
    protected static $_instance = null;    
    
	/**
	 * Main Instance
	 *
     * @return object
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}    
    
	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() {
    }        
    
    /**
     * Refresh Adverts Action
     */
    public function advertsRefresh($data) {
        //http_response_code(503);
        //die();
        
        $id = str_replace('rpadv_widget-', '', $data['id']);
        $obj = new RPAdv_AdvertWidget();
        $settings = $obj->get_settings();
        $settings = $settings[$id];
        
        $params = array(
            'id' => $data['id'],
            'index' => $data['index'],
            'isAjax' => TRUE,
        );
        
        if (taxonomy_exists('advert-category') && !empty($settings['taxonomy_term'])) {
            $params['term'] = $settings['taxonomy_term'];
        }
        $params['color'] = !empty($settings['color']) ? $settings['color'] : '';        
        
        if (isset($settings['refreshTime'])) {
            $params['refreshTime'] = $settings['refreshTime'];    
        }        
        if (isset($settings['animationSpeed'])) {
            $params['animationSpeed'] = $settings['animationSpeed'];    
        }        
        if (isset($settings['advertCount'])) {
            $params['advertCount'] = $settings['advertCount'];    
        }                        
        
        RPAdv()->showWidget($params);
    }
}
