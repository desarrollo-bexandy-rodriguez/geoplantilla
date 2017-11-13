<?php

class RPAdv_AdvertWidget extends WP_Widget {
    
	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 'description' => __( "Adds visual adverts to sidebar") );
		parent::__construct('rpadv_widget', __('WP Visual Adverts'), $widget_ops);
	}
    
	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if (!empty( $instance['title'])) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
        }
      
        $atts = array();
        $atts['id'] = $this->id;
        $atts['term'] = !empty($instance['taxonomy_term']) && taxonomy_exists('advert-category') ? $instance['taxonomy_term'] : array();
        $atts['color'] = !empty($instance['color']) ? $instance['color'] : '';
        
        if (isset($instance['refreshTime'])) {
            $atts['refreshTime'] = $instance['refreshTime'];    
        }        
        if (isset($instance['animationSpeed'])) {
            $atts['animationSpeed'] = $instance['animationSpeed'];    
        }        
        if (isset($instance['advertCount'])) {
            $atts['advertCount'] = $instance['advertCount'];    
        }                
        
        RPAdv()->showWidget($atts);
        
        echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$title = !empty($instance['title']) ? $instance['title'] : '';
        $taxonomy_term = !empty($instance['taxonomy_term']) ? $instance['taxonomy_term'] : '';                
        $color = !empty($instance['color']) ? $instance['color'] : '';
        $refreshTime = isset($instance['refreshTime']) ? $instance['refreshTime'] : RPAdv()->getSettings()->getRefreshTime();        
        $animationSpeed = isset($instance['animationSpeed']) ? $instance['animationSpeed'] : RPAdv()->getSettings()->getAnimationSpeed();        
        $advertCount = isset($instance['advertCount']) ? $instance['advertCount'] : RPAdv()->getSettings()->getAdvertCount();        
    ?>
        <p><?php $this->renderTitleField($title); ?></p>
        <p><?php $this->renderTaxonomyTermField('advert-category', $taxonomy_term); ?></p>        
        <p><?php $this->renderTextColorField($color); ?></p>                
        <p><?php $this->renderRefreshTimeField($refreshTime); ?></p>                
        <p><?php $this->renderAnimationSpeedField($animationSpeed); ?></p>                
        <p><?php $this->renderAdvertCountField($advertCount); ?></p>                
    <?php    
	}
    
    
	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
        
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags( $new_instance['title'] ) : '';
        $instance['taxonomy_term'] = (!empty($new_instance['taxonomy_term'])) ? strip_tags( $new_instance['taxonomy_term'] ) : '';        
        $instance['color'] = (!empty($new_instance['color'])) ? strip_tags( $new_instance['color'] ) : '';        
        
        $instance['refreshTime'] = (!empty($new_instance['refreshTime']) || $new_instance['refreshTime'] != '') ? strip_tags( $new_instance['refreshTime'] ) : NULL;        
        $instance['animationSpeed'] = (!empty($new_instance['animationSpeed']) || $new_instance['animationSpeed'] != '') ? strip_tags( $new_instance['animationSpeed'] ) : NULL;        
        $instance['advertCount'] = (!empty($new_instance['advertCount']) || $new_instance['advertCount'] != '') ? strip_tags( $new_instance['advertCount'] ) : NULL;        
        
		return $instance;
	}    
    
    public function renderTextColorField($color) {
    ?>
        <script type='text/javascript'>
            jQuery('.widgets-sortables .rpadv-color-picker').wpColorPicker();                                        
        </script>
        <label for="<?php echo esc_attr( $this->get_field_id( 'color' ) ); ?>" style="display:block;"><?php _e( 'Font Color:' ); ?></label> 
        <input class="widefat rpadv-color-picker" id="<?php echo esc_attr( $this->get_field_id( 'color' ) ); ?>" name="<?php echo $this->get_field_name( 'color' ); ?>" type="text" value="<?php echo esc_attr( $color ); ?>" />        
    <?php 
    }
    
    public function renderTitleField ($title) {
    ?>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">    
    <?php    
    }    

    public function renderTaxonomyTermField ($taxonomy, $current_term = '') {
        if (taxonomy_exists('advert-category')) :
        $args = array(
            'orderby'       => 'name', 
            'order'         => 'ASC',
            'hide_empty'    => false, 
            'fields'        => 'all', 
            'hierarchical'  => true, 
        ); 

        $terms = get_terms( $taxonomy, $args ); 
    ?>    
        <label for="<?php echo esc_attr( $this->get_field_id( 'taxonomy_term' ) ); ?>"><?php _e( 'Category:' ); ?></label>
        <select id="<?php echo esc_attr( $this->get_field_id( 'taxonomy_term' ) ); ?>" class="taxonomy-select widefat" name="<?php echo esc_attr( $this->get_field_name( 'taxonomy_term' ) ); ?>">
        <option value=""<?php selected( $current_term, '' ); ?>>All Adverts</option>            
        <?php foreach ( $terms as $term ) : ?>
            <option value="<?php echo $term->term_id; ?>"<?php selected( $current_term, $term->term_id ); ?>><?php echo $term->name; ?></option>
        <?php endforeach; ?>
        </select>
    <?php  
        endif;
    }    
    
    public function renderRefreshTimeField ( $refreshTime ) {
    ?>
        <label for="<?php echo esc_attr( $this->get_field_id( 'refreshTime' ) ); ?>"><?php _e( 'Refresh Time (msec.):' ); ?></label> 
		<input min="0" class="widefat" id="<?php echo $this->get_field_id( 'refreshTime' ); ?>" name="<?php echo $this->get_field_name( 'refreshTime' ); ?>" type="number" value="<?php echo esc_attr( $refreshTime ); ?>">    
    <?php            
    }

    public function renderAnimationSpeedField ( $animationSpeed ) {
    ?>
        <label for="<?php echo esc_attr( $this->get_field_id( 'animationSpeed' ) ); ?>"><?php _e( 'Animation Speed (msec.):' ); ?></label> 
		<input min="0" class="widefat" id="<?php echo $this->get_field_id( 'animationSpeed' ); ?>" name="<?php echo $this->get_field_name( 'animationSpeed' ); ?>" type="number" value="<?php echo esc_attr( $animationSpeed ); ?>">    
    <?php            
    }    
    
    
    public function renderAdvertCountField ( $advertCount ) {
    ?>
        <label for="<?php echo esc_attr( $this->get_field_id( 'advertCount' ) ); ?>"><?php _e( 'Advert Count:' ); ?></label> 
		<input min="0" class="widefat" id="<?php echo $this->get_field_id( 'advertCount' ); ?>" name="<?php echo $this->get_field_name( 'advertCount' ); ?>" type="number" value="<?php echo esc_attr( $advertCount ); ?>">    
    <?php            
    }        
    
}

    