<?php

class CyonInstaSliderWidget extends WP_Widget {

	// Creating your widget
	function CyonInstaSliderWidget(){
		$widget_ops = array('classname' => 'cyon-insta-slider', 'description' => __('Slides Instagram photos','cyon') );
		$this->WP_Widget('CyonInstaSliderWidget', __('Cyon Instagram Slider','cyon'), $widget_ops);
	}
 
 	// Widget form in WP Admin
	function form($instance){
		// Start adding your fields here
		$instance = wp_parse_args( (array) $instance, array(
			'title' 	=> __('Instagram'),
			'count'		=> '5',
			'url'		=> '',
			'user'		=> '',
			'clientid'	=> ''			
		) );
		$title = $instance['title'];
		$count = $instance['count'];
		$url = $instance['url'];
		$user = $instance['user'];
		$clientid = $instance['clientid'];

		?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Count') ?>: <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo attribute_escape($count); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('user'); ?>"><?php _e('User ID') ?> (<a href="http://jelled.com/instagram/lookup-user-id" target="_blank"><?php _e( 'check ID here', 'cyon' ); ?></a>): <input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo attribute_escape($user); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('clientid'); ?>"><?php _e('Client ID') ?> (<a href="http://instagram.com/developer/" target="_blank"><?php _e( 'apply one here', 'cyon' ); ?></a>): <input class="widefat" id="<?php echo $this->get_field_id('clientid'); ?>" name="<?php echo $this->get_field_name('clientid'); ?>" type="text" value="<?php echo attribute_escape($clientid); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('URL') ?>: <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo attribute_escape($url); ?>" /></label></p>

		<?php
	}

	// Saving your widget form
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		// Override new values of each fields
		$instance['title'] = $new_instance['title'];
		$instance['count'] = $new_instance['count'];
		$instance['user'] = $new_instance['user'];
		$instance['clientid'] = $new_instance['clientid'];
		$instance['url'] = $new_instance['url'];

		return $instance;
	}

	// Displays your widget on the front-end
	function widget($args, $instance){
		extract($args, EXTR_SKIP);
		
		$title = $instance['title'];
		$count = $instance['count'];
		$user = $instance['user'];
		$clientid = $instance['clientid'];

		ob_start();
			wp_enqueue_script('fotorama');
			wp_enqueue_style('fotorama');
		ob_get_clean();

		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		
		if (!empty($title)){
			echo $before_title . $title . $after_title;;
		}
		echo '<div class="widget-content">';
		
		$target = 'https://api.instagram.com/v1/users/' . $user . '/media/recent/?client_id=' . $clientid . '&count=' . $count;
		
		$ch = curl_init();
		
		curl_setopt ($ch, CURLOPT_HTTPGET,        TRUE);
		curl_setopt ($ch, CURLOPT_POST,           FALSE);
		curl_setopt ($ch, CURLOPT_COOKIEJAR,      COOKIE_FILE);   // Defined Constant
		curl_setopt ($ch, CURLOPT_COOKIEFILE,     COOKIE_FILE);
		curl_setopt ($ch, CURLOPT_TIMEOUT,        CURL_TIMEOUT);  // Defined Constant
		curl_setopt ($ch, CURLOPT_USERAGENT,      WEBBOT_NAME);   // Defined Constant
		curl_setopt ($ch, CURLOPT_URL,            $target);       // Target site
		curl_setopt ($ch, CURLOPT_REFERER,        '');            // Referer value
		curl_setopt ($ch, CURLOPT_VERBOSE,        FALSE);         // Minimize logs
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);         // No certificate
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, TRUE);          // Follow redirects
		curl_setopt ($ch, CURLOPT_MAXREDIRS,      4);             // Limit redirections to four
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);          // Return in string
		
		$json = json_decode( curl_exec($ch) , true);
		
		# Close PHP/CURL handle
		curl_close($ch);
		
		echo '<a href="' . $instance['url'] . '" target="_blank"><div class="fotorama" data-width="100%" data-arrows="false" data-nav="false" data-autoplay="true" data-loop="true" data-transition="crossfade">';
		
		foreach( $json['data'] as $image ) {
			echo '<img src="' . $image['images']['low_resolution']['url'] . '" alt="" />';
		}
		
		echo '</div><i class="icon-instagram"></i></a>';
		
		// End widget
		echo '</div>';

		echo $after_widget;
	}

}

// Adding your widget to WordPress
add_action( 'widgets_init', create_function('', 'return register_widget("CyonInstaSliderWidget");') );
