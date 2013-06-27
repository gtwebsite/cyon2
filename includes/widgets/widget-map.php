<?php

class CyonMapWidget extends WP_Widget {

	// Creating your widget
	function CyonMapWidget(){
		$widget_ops = array('classname' => 'cyon-map', 'description' => __('Displays Google Map') );
		$this->WP_Widget('CyonMapWidget', __('Cyon Google Map'), $widget_ops);
	}
 
 	// Widget form in WP Admin
	function form($instance){
		// Start adding your fields here
		$instance = wp_parse_args( (array) $instance, array(
			'title' 		=> 'Our Location',
			'height'		=> '200',
			'zoom'			=> '14',
			'lat'			=> '',
			'long'			=> '',
			'address'		=> 'New York, USA',
			'text' 		=> ''
		) );
		$title = $instance['title'];
		$height = $instance['height'];
		$zoom = $instance['zoom'];
		$lat = $instance['lat'];
		$long = $instance['long'];
		$address = $instance['address'];
		$text = $instance['text'];

		?>
		  <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height') ?>: <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo attribute_escape($height); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('zoom'); ?>"><?php _e('Zoom') ?>: <input class="widefat" id="<?php echo $this->get_field_id('zoom'); ?>" name="<?php echo $this->get_field_name('zoom'); ?>" type="text" value="<?php echo attribute_escape($zoom); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('lat'); ?>"><?php _e('Latitude') ?>: <input class="widefat" id="<?php echo $this->get_field_id('lat'); ?>" name="<?php echo $this->get_field_name('lat'); ?>" type="text" value="<?php echo attribute_escape($lat); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('long'); ?>"><?php _e('Longetitude') ?>: <input class="widefat" id="<?php echo $this->get_field_id('long'); ?>" name="<?php echo $this->get_field_name('long'); ?>" type="text" value="<?php echo attribute_escape($long); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('address'); ?>"><?php _e('Address') ?>: <input class="widefat" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>" type="text" value="<?php echo attribute_escape($address); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text') ?>: <textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" type="text"><?php echo attribute_escape($text); ?></textarea></label></p>

		<?php
	}

	// Saving your widget form
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		// Override new values of each fields
		$instance['title'] = $new_instance['title'];
		$instance['height'] = $new_instance['height'];
		$instance['zoom'] = $new_instance['zoom'];
		$instance['lat'] = $new_instance['lat'];
		$instance['long'] = $new_instance['long'];
		$instance['address'] = $new_instance['address'];
		$instance['text'] = $new_instance['text'];

		return $instance;
	}

	// Displays your widget on the front-end
	function widget($args, $instance){
		extract($args, EXTR_SKIP);
		
		$title = $instance['title'];
		$height = $instance['height'];
		$zoom = $instance['zoom'];
		$lat = $instance['lat'];
		$long = $instance['long'];
		$address = $instance['address'];
		$text = $instance['text'];

		// Start widget
		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		
		if (!empty($title)){
			echo $before_title . $title . $after_title;;
		}
		echo '<div class="widget-content">';
		echo '<div class="gmap" data-address="'.$address.'" data-lat="'.$lat.'" data-long="'.$long.'" data-zoom="'.$zoom.'" style="width: 100%; height: '.$height.'px;">'.$text.'</div>';
    	// Widget code here
 		if($text){
			echo '<div class="map-text">'.$text.'</div>';
		}
 
		// End widget
		echo '</div>';
		echo $after_widget;
		ob_start();
			wp_enqueue_script('gmap_api');
			wp_enqueue_script('gmap');
		ob_get_clean();
	}
}

// Adding your widget to WordPress
add_action( 'widgets_init', create_function('', 'return register_widget("CyonMapWidget");') );

