<?php

class CyonFbWidget extends WP_Widget {

	// Creating your widget
	function CyonFbWidget(){
		$widget_ops = array('classname' => 'cyon-facebook-box', 'description' => __('Displays FB Likes') );
		$this->WP_Widget('CyonFbWidget', __('Cyon FB Like Box'), $widget_ops);
	}
 
 	// Widget form in WP Admin
	function form($instance){
		// Start adding your fields here
		$instance = wp_parse_args( (array) $instance, array(
			'title' 			=> 'Facebook Page',
			'url_page'			=> 'https://www.facebook.com/platform',
			'border_color'		=> 'fff',
			'height'			=> '558',
			'faces'				=> 'true',
			'stream'			=> 'true'
		) );
		$title = $instance['title'];
		$url_page = $instance['url_page'];
		$border_color = $instance['border_color'];
		$height = $instance['height'];
		$faces = $instance['faces'];
		$stream = $instance['stream'];

		?>
		  <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('url_page'); ?>"><?php _e('URL page') ?>: <input class="widefat" id="<?php echo $this->get_field_id('url_page'); ?>" name="<?php echo $this->get_field_name('url_page'); ?>" type="text" value="<?php echo attribute_escape($url_page); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height') ?>: <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo attribute_escape($height); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('border_color'); ?>"><?php _e('Border Color') ?>: <input class="widefat" id="<?php echo $this->get_field_id('border_color'); ?>" name="<?php echo $this->get_field_name('border_color'); ?>" type="text" value="<?php echo attribute_escape($border_color); ?>" /></label></p>
		  <p><input type="checkbox" name="<?php echo $this->get_field_name('faces'); ?>" id="<?php echo $this->get_field_id('faces'); ?>" value="1" <?php echo ($faces == "true" ? "checked='checked'" : ""); ?> /> <label for="<?php echo $this->get_field_id('faces'); ?>"><?php _e('Show Faces') ?></label></p>
		  <p><input type="checkbox" name="<?php echo $this->get_field_name('stream'); ?>" id="<?php echo $this->get_field_id('stream'); ?>" value="1" <?php echo ($stream == "true" ? "checked='checked'" : ""); ?> /> <label for="<?php echo $this->get_field_id('stream'); ?>"><?php _e('Show Stream') ?></label></p>

		<?php
	}

	// Saving your widget form
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		// Override new values of each fields
		$instance['title'] = $new_instance['title'];
		$instance['url_page'] = $new_instance['url_page'];
		$instance['height'] = $new_instance['height'];
		$instance['border_color'] = $new_instance['border_color'];
		$instance['faces'] = (bool)$new_instance['faces'];
		$instance['stream'] = (bool)$new_instance['stream'];

		return $instance;
	}

	// Displays your widget on the front-end
	function widget($args, $instance){
		extract($args, EXTR_SKIP);
		
		$title = $instance['title'];
		$url_page = $instance['url_page'];
		$height = $instance['height'];
		$border_color = $instance['border_color'];
		$faces = $instance['faces'];
		$stream = $instance['stream'];


		// Start widget
		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		
		if (!empty($title)){
			echo $before_title . $title . $after_title;;
		}
		echo '<div class="widget-content">';
		echo '<div style="width:100%; height:'.$height.'px; overflow:visible"><iframe src="//www.facebook.com/plugins/likebox.php?href='.$url_page.'&amp;height='.$height.'&amp;width=200&amp;colorscheme=light&amp;show_faces='.$faces.'&amp;show_border=false&amp;stream='.$stream.'&amp;header=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100%; height:'.$height.'px;" allowTransparency="true"></iframe></div>';
    	// Widget code here
 
		// End widget
		echo '</div>';
		echo $after_widget;
	}
	
}

// Adding your widget to WordPress
add_action( 'widgets_init', create_function('', 'return register_widget("CyonFbWidget");') );

