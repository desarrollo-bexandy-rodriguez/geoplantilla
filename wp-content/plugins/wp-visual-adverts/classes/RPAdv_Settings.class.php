<?php
use Webcodin\WPVisualAdverts\Core\Agp_SettingsAbstract;

class RPAdv_Settings extends Agp_SettingsAbstract {
    
    /**
     * The single instance of the class 
     * 
     * @var object 
     */
    protected static $_instance = null;    

    /**
     * Parent Module
     * 
     * @var Agp_Module
     */
    protected static $_parentModule;
    
	/**
	 * Main Instance
	 *
     * @return object
	 */
	public static function instance($parentModule = NULL) {
		if ( is_null( self::$_instance ) ) {
            self::$_parentModule = $parentModule;            
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
     * Constructor 
     * 
     * @param Agp_Module $parentModule
     */
    public function __construct() {
        //delete_option('rpadv_settings');
        
        $config = include ($this->getParentModule()->getBaseDir() . '/config/config.php');        
        
        parent::__construct($config);

        add_action('admin_menu', array($this, 'adminMenu'));                        
    }
    
    public static function getParentModule() {
       
        return self::$_parentModule;
    }

    public static function renderSettingsPage() {
        echo self::getParentModule()->getTemplate('admin/options/layout', self::instance());
    }    
    
    public function adminMenu () {
        parent::adminMenu();
        add_filter( 'parent_file', array($this, 'currentMenu') );        
    }
    
    function currentMenu($parent_file){
        global $submenu_file, $current_screen, $pagenow;

        if($current_screen->post_type == 'adverts') {

            if($pagenow == 'edit-tags.php'){
                $submenu_file = 'edit-tags.php?taxonomy=advert-category&post_type='.$current_screen->post_type;
            }

            $parent_file = 'adverts';

        }

        return $parent_file;

    }     
    
    public function getImageWidth() {
        $settings = $this->getSettings();
        return $settings['rpadv_settings']['rpadv-image']['width'];
    }

    public function getImageHeight() {
        $settings = $this->getSettings();
        return $settings['rpadv_settings']['rpadv-image']['height'];
    }

    public function getImageCrop() {
        $settings = $this->getSettings();
        return !empty($settings['rpadv_settings']['rpadv-image']['crop']) ? 1 : 0;
    }

    public function getRefreshTime() {
        $settings = $this->getSettings();
        return $settings['rpadv_settings']['rpadv-refresh-time'];
    }
    
    public function getAdvertCount() {
        $settings = $this->getSettings();
        return $settings['rpadv_settings']['rpadv-advert-count'];
    }

    public function getAnimationSpeed() {
        $settings = $this->getSettings();
        return $settings['rpadv_settings']['rpadv-animation-speed'];
    }    

    public function getInFooterScript() {
        $settings = $this->getSettings();
        return $settings['rpadv_settings']['scripts_in_footer'];
    }        
    
    public function getOptions() {
        $fields = $this->getFields();        
        
        $result = array();
        if ($this->getTabs()) {        
            foreach ($this->getTabs() as $k => $v) {
                if (!empty($fields[$k])) {
                    foreach ($fields[$k]['fields'] as $dk => $dv) {
                        $options = get_option( $k );
                        if (!empty($options)) {
                            if ( isset( $options[$dk] ) ) {
                                $result[$k][$dk] = $options[$dk];  
                            } elseif ($dv['type'] !== 'checkbox' && isset ( $dv['default'])) {
                                $result[$k][$dk] = $dv['default'];
                            } elseif ($dk == 'rpadv-image') {    
                                if (isset($options['rpadv-image-width'])) {
                                    $result[$k][$dk]['width'] = $options['rpadv-image-width'];            
                                } else {
                                    $result[$k][$dk]['width'] = $dv['default']['width'];            
                                }
                                if (isset($options['rpadv-image-height'])) {
                                    $result[$k][$dk]['height'] = $options['rpadv-image-height'];            
                                } else {
                                    $result[$k][$dk]['height'] = $dv['default']['height'];            
                                }
                                if (!empty($options['rpadv-image-crop'])) {
                                    $result[$k][$dk]['crop'] = 1;            
                                }
                            } elseif ( isset ( $dv['default'] ) ) {
                                $result[$k][$dk] = $dv['default'];
                            }   
                        } else {
                            if ( isset ( $dv['default'] ) ) {
                                $result[$k][$dk] = $dv['default'];
                            }                               
                        }
                    }                    
                }
            }    
        } 
        return $result;
    }    
}

