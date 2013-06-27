<?php

class CyonContactWidget extends WP_Widget {

	// Creating your widget
	function CyonContactWidget(){
		$widget_ops = array('classname' => 'cyon-contact', 'description' => __('Displays contact information') );
		$this->WP_Widget('CyonContactWidget', __('Cyon Contact'), $widget_ops);
	}
 
 	// Widget form in WP Admin
	function form($instance){
		// Start adding your fields here
		$instance = wp_parse_args( (array) $instance, array(
			'title' 		=> 'Contact Info',
			'address'		=> '',
			'phone'			=> '',
			'fax'			=> '',
			'email'			=> '',
			'website'		=> '',
			'map'			=> 'false',
			'height'		=> '200',
			'zoom'			=> '14',
			'lat'			=> '',
			'long'			=> ''
		) );
		$title = $instance['title'];
		$address = $instance['address'];
		$phone = $instance['phone'];
		$fax = $instance['fax'];
		$email = $instance['email'];
		$website = $instance['website'];
		$map = $instance['map'];
		$height = $instance['height'];
		$zoom = $instance['zoom'];
		$lat = $instance['lat'];
		$long = $instance['long'];

		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('address'); ?>"><?php _e('Address') ?>: <input class="widefat" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>" type="text" value="<?php echo attribute_escape($address); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('phone'); ?>"><?php _e('Phone') ?>: <input class="widefat" id="<?php echo $this->get_field_id('phone'); ?>" name="<?php echo $this->get_field_name('phone'); ?>" type="text" value="<?php echo attribute_escape($phone); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fax'); ?>"><?php _e('Fax') ?>: <input class="widefat" id="<?php echo $this->get_field_id('fax'); ?>" name="<?php echo $this->get_field_name('fax'); ?>" type="text" value="<?php echo attribute_escape($fax); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Email') ?>: <input class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="text" value="<?php echo attribute_escape($email); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('website'); ?>"><?php _e('Website') ?>: <input class="widefat" id="<?php echo $this->get_field_id('website'); ?>" name="<?php echo $this->get_field_name('website'); ?>" type="text" value="<?php echo attribute_escape($website); ?>" /></label></p>
		<p><input type="checkbox" class="cyon_contact_map_check" name="<?php echo $this->get_field_name('map'); ?>" id="<?php echo $this->get_field_id('map'); ?>" value="1" <?php echo ($map == "true" ? "checked='checked'" : ""); ?> /> <label for="<?php echo $this->get_field_id('map'); ?>"><?php _e('Show Map') ?></label></p>
		<fieldset class="cyon_contact_map"<?php echo ($map == "true" ? '' : ' style="display:none;"'); ?>>
			<p><label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height') ?>: <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo attribute_escape($height); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('zoom'); ?>"><?php _e('Zoom') ?>: <input class="widefat" id="<?php echo $this->get_field_id('zoom'); ?>" name="<?php echo $this->get_field_name('zoom'); ?>" type="text" value="<?php echo attribute_escape($zoom); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('lat'); ?>"><?php _e('Latitude') ?>: <input class="widefat" id="<?php echo $this->get_field_id('lat'); ?>" name="<?php echo $this->get_field_name('lat'); ?>" type="text" value="<?php echo attribute_escape($lat); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('long'); ?>"><?php _e('Longetitude') ?>: <input class="widefat" id="<?php echo $this->get_field_id('long'); ?>" name="<?php echo $this->get_field_name('long'); ?>" type="text" value="<?php echo attribute_escape($long); ?>" /></label></p>
		</fieldset>
		<?php
	}

	// Saving your widget form
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		// Override new values of each fields
		$instance['title'] = $new_instance['title'];
		$instance['address'] = $new_instance['address'];
		$instance['phone'] = $new_instance['phone'];
		$instance['fax'] = $new_instance['fax'];
		$instance['email'] = $new_instance['email'];
		$instance['website'] = $new_instance['website'];
		$instance['map'] = (bool)$new_instance['map'];
		$instance['height'] = $new_instance['height'];
		$instance['zoom'] = $new_instance['zoom'];
		$instance['lat'] = $new_instance['lat'];
		$instance['long'] = $new_instance['long'];
		return $instance;
	}

	// Displays your widget on the front-end
	function widget($args, $instance){
		extract($args, EXTR_SKIP);
		
		// Start widget
		echo $before_widget;
		$html = ''; 
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		$html .= empty($instance['address']) ? '' : '<address class="has-icon"><span class="icon-map-marker"></span>'.$instance['address'].'</address>';
		$html .= empty($instance['phone']) ? '' : '<address class="has-icon"><span class="icon-phone"></span>'.$instance['phone'].'</address>';
		$html .= empty($instance['fax']) ? '' : '<address class="has-icon"><span class="icon-print"></span>'.$instance['fax'].'</address>';
		$html .= empty($instance['email']) ? '' : '<address class="has-icon"><a href="mailto:'.$instance['email'].'"><span class="icon-envelope"></span>'.$instance['email'].'</a></address>';
		$html .= empty($instance['website']) ? '' : '<address class="has-icon"><a href="http://'.$instance['website'].'"><span class="icon-globe"></span>'.$instance['website'].'</a></address>';
		
		if (!empty($title)){
			echo $before_title . $title . $after_title;;
		}
		echo '<div class="widget-content">';
		
    	// Widget code here
		echo $html;
 		if($instance['map']==true){
			echo '<div class="gmap" data-address="'.$instance['address'].'" data-lat="'.$instance['lat'].'" data-long="'.$instance['long'].'" data-zoom="'.$instance['zoom'].'" style="width: 100%; height: '.$instance['height'].'px;">'.$instance['address'].'</div>';
			wp_enqueue_script('gmap');
		}
		
		// End widget
		echo '</div>';
		echo $after_widget;
	}
	
}

// Adding your widget to WordPress
add_action( 'widgets_init', create_function('', 'return register_widget("CyonContactWidget");') );