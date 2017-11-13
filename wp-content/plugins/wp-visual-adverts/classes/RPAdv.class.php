<?php
use Webcodin\WPVisualAdverts\Core\Agp_Module;

class RPAdv extends Agp_Module {

    private $version = '2.3.0';
    
    /**
     * Plugin settings
     * 
     * @var RPAdv_Settings
     */
    private $settings;    
    
    /**
     * Ajax Object
     * 
     * @var RPAdv_Ajax
     */
    private $ajax;
    
    
    /**
     * Advert Repository
     * 
     * @var RPAdv_AdvertRepository
     */
    private $advertRepository;        
    
    
    private $tmpViewParams;
    
    
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
    
    public function __construct() {
        parent::__construct(dirname(dirname(__FILE__)));
        
        include_once ( $this->getBaseDir() . '/types/adverts-post-type.php' );                
        
        $this->ajax = RPAdv_Ajax::instance();
        $this->advertRepository = new RPAdv_AdvertRepository();
        $this->settings = RPAdv_Settings::instance( $this );
        
        add_action( 'init', array($this, 'init' ), 999 );        
        add_action( 'wp_enqueue_scripts', array($this, 'enqueueScripts' ) );    
        add_action( 'admin_enqueue_scripts', array($this, 'enqueueAdminScripts' ));                    
        add_action( 'widgets_init', array($this, 'initWidgets' ) );
        add_action( 'after_setup_theme', array($this, 'setupTheme' ) );
        add_action( 'add_meta_boxes', array($this, 'addMetaboxes' ) );
        add_action( 'save_post', array($this, 'saveMetaboxes' ), 1, 2);
        add_action( 'load-widgets.php', array($this, 'loadWidget' ) );
    }
    
    public function init () {
        $this->advertRepository->refreshRepository();
    }
    
    public function setupTheme() {
        add_theme_support( 'post-thumbnails' );
        add_image_size( 'adverts-thumb', $this->settings->getImageWidth(), $this->settings->getImageHeight(), $this->settings->getImageCrop() ); 
    }    
    
    public function addMetaboxes () {
        add_meta_box('rpadv_link', 'Link', array($this, 'renderLinkMetabox'), 'adverts', 'normal', 'default');
    }
    
    public function saveMetaboxes($post_id, $post) {
        if ( empty($_POST['rpadv_link_noncename'])
            || !wp_verify_nonce( $_POST['rpadv_link_noncename'], $this->getBaseDir() )
            || !current_user_can( 'edit_post', $post->ID )
        ) {
            return $post->ID;
        }            
        
        if( $post->post_type == 'revision' ) return;
        
        $params = array( 'rpadv_link', 'rpadv_link_target' );
        
        foreach ($params as $key) {
            $value = $_POST[$key];

            if ( !$value ) {
                delete_post_meta($post->ID, $key);
            } elseif ( get_post_meta($post->ID, $key, FALSE) ) {
                update_post_meta($post->ID, $key, $value);
            } else {
                add_post_meta($post->ID, $key, $value);
            }            
        }
    }
    
    public function renderLinkMetabox() {
        echo $this->getTemplate('admin/link-metabox');
    }
    
    public function enqueueScripts () {
        wp_register_script( 'rpadv', $this->getAssetUrl('js/main.js'), array('jquery') );                                                         
        wp_localize_script( 'rpadv', 'ajax_rpadv', array( 
            'base_url' => site_url(),         
            'ajax_url' => admin_url( 'admin-ajax.php' ), 
            'ajax_nonce' => wp_create_nonce('ajax_atf_nonce'),        
        ));  

        wp_register_style('rpadv-css', $this->getAssetUrl('css/style.css'));                    
        
        $InFooterScript = $this->settings->getInFooterScript();
        if (empty($InFooterScript)) {
            wp_enqueue_script( 'rpadv' );         
            wp_enqueue_style( 'rpadv-css' );         
        }        
    }        
    
    
    public function enqueueAdminScripts () {
        wp_enqueue_script( 'rpadv', $this->getAssetUrl('js/admin.js'), array('jquery', 'wp-color-picker') );                                                         
        wp_enqueue_style( 'rpadv-css', $this->getAssetUrl('css/admin.css'), array('wp-color-picker') );                    
    }    
    
    public function loadWidget () {
        wp_enqueue_style( 'wp-color-picker' );        
        wp_enqueue_script( 'wp-color-picker' );            
    }
    
    public function initWidgets() {
        register_widget('RPAdv_AdvertWidget');
    }
    
    public function getAjax() {
        return $this->ajax;
    }

    public function getAdvertRepository() {
        return $this->advertRepository;
    }

    function getSettings() {
        return $this->settings;
    }
    
    public function getAdverts() {
        if ($this->getAdvertRepository()->getCount() > 0) {
            $result = array();
            $params = $this->getTmpViewParams();            
            
            if (isset($params['advertCount'])) {
                $maxCount = $params['advertCount'];
            }

            if (!isset($maxCount)) {
                $maxCount = $this->getSettings()->getAdvertCount();    
            }
            
            $index = !empty($params['index']) ? $params['index'] : 0;

            $adverts = $this->getAdvertRepository()->getAll();
            $allCount = $this->getAdvertRepository()->getCount();
            $count = ($allCount > $maxCount) ? $maxCount : $allCount;
            
            for ( $i = 0; $i < $count; $i++) {
                if ($index >= $allCount ) {
                    $index = 0;
                }
                
                if (!empty($adverts[$index])) {
                    $result[] = $adverts[$index];
                    $index++;                    
                } else {
                    $index = 0;
                }
            }
            
            $this->getAdvertRepository()->setIndex($index);
            
            if (!empty($result)) {
                srand((float)microtime() * 1000000);
                shuffle($result);
            }
            return $result;            
        }
    }    
    
    public function showWidget ($params) {
        
        $InFooterScript = $this->settings->getInFooterScript();
        if (!empty($InFooterScript)) {
            wp_enqueue_script( 'rpadv' );         
            wp_enqueue_style( 'rpadv-css' );         
        }                
        
        $id = !empty($params['id']) ? $params['id'] : '';
        $term = !empty($params['term']) ? $params['term'] : '';
        $color = !empty($params['color']) ? $params['color'] : '';        
        $isAjax = !empty($params['isAjax']) ? 'isAjax' : '';
        $template = !empty($params['isAjax']) ? 'rpadv-widget-list' : 'rpadv-widget';
        
        $params['refreshTime'] = isset($params['refreshTime']) ? $params['refreshTime'] : $this->settings->getRefreshTime();        
        $params['animationSpeed'] = isset($params['animationSpeed']) ? $params['animationSpeed'] : $this->settings->getAnimationSpeed();        
        $params['advertCount'] = isset($params['advertCount']) ? $params['advertCount'] : $this->settings->getAdvertCount();        
        
        $params['index'] = !empty($params['index']) ? $params['index'] : 0;        
        if (!empty($id)) :
            $this->advertRepository->setIndex($params['index']);
            $this->advertRepository->setCategoryFilter($term);
            $this->advertRepository->applyColor($color);
            if (empty($isAjax)) :
        ?>
        <script type="text/javascript">
            if (typeof rpadv_settings == 'undefined') {
                var rpadv_settings = {advertCount:[],refreshTime:[],animationSpeed:[],advertCountPage:[],version:[],index:[]};
            }
            rpadv_settings.advertCount["<?php echo $id;?>"] = <?php echo $this->advertRepository->getCount(); ?>;
            rpadv_settings.refreshTime["<?php echo $id;?>"] = <?php echo $params['refreshTime']; ?>;
            rpadv_settings.animationSpeed["<?php echo $id;?>"] = <?php echo $params['animationSpeed']; ?>;
            rpadv_settings.advertCountPage["<?php echo $id;?>"] = <?php echo $params['advertCount']; ?>;
            rpadv_settings.version["<?php echo $id;?>"] = "<?php echo $this->getVersion(); ?>";
            rpadv_settings.index["<?php echo $id;?>"] = "";
        </script>
        <?php
            endif;
            
            $this->setTmpViewParams($params);
            echo $this->getTemplate($template, $params);                            
            $this->setTmpViewParams(NULL);
        endif;
        
    }
    
    public function getTmpViewParams() {
        return $this->tmpViewParams;
    }

    public function setTmpViewParams($tmpViewParams) {
        $this->tmpViewParams = $tmpViewParams;
        return $this;
    }
    
    public function getVersion() {
        return $this->version;
    }
    
}