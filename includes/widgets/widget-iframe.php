<?php

class CyonIframeWidget extends WP_Widget {

	// Declare widget-wide variable
	protected $var1, $var2;

	// Creating your widget
	function CyonIframeWidget(){
		$widget_ops = array('classname' => 'cyon-iframe', 'description' => __('Displays Website on Iframe') );
		$this->WP_Widget('CyonIframeWidget', __('Cyon Iframe'), $widget_ops);
	}
 
 	// Widget form in WP Admin
	function form($instance){
		// Start adding your fields here
		$instance = wp_parse_args( (array) $instance, array(
			'title' 		=> 'Framed Website',
			'width'			=> '100%',
			'height'		=> '350',
			'scroll'		=> 'true',
			'url'			=> 'http://localhost/'
		) );
		$title = $instance['title'];
		$width = $instance['width'];
		$height = $instance['height'];
		$scroll = $instance['scroll'];
		$url = $instance['url'];

		?>
		  <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width') ?>: <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo attribute_escape($width); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height') ?>: <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo attribute_escape($height); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('url') ?>: <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo attribute_escape($url); ?>" /></label></p>
		  <p><input type="checkbox" name="<?php echo $this->get_field_name('scroll'); ?>" id="<?php echo $this->get_field_id('scroll'); ?>" value="1" <?php echo ($scroll == "true" ? "checked='checked'" : ""); ?> /> <label for="<?php echo $this->get_field_id('scroll'); ?>"><?php _e('Enable Scrolling') ?></label></p>

		<?php
	}

	// Saving your widget form
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		// Override new values of each fields
		$instance['title'] = $new_instance['title'];
		$instance['width'] = $new_instance['width'];
		$instance['height'] = $new_instance['height'];
		$instance['scroll'] = (bool)$new_instance['scroll'];
		$instance['url'] = $new_instance['url'];

		return $instance;
	}

	// Displays your widget on the front-end
	function widget($args, $instance){
		extract($args, EXTR_SKIP);
		
		$title = $instance['title'];
		$width = $instance['width'];
		$height = $instance['height'];
		if($instance['scroll']=='true'){ $scroll='yes'; }else{ $scroll='no'; }
		$url = $instance['url'];


		// Start widget
		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		
		if (!empty($title)){
			echo $before_title . $title . $after_title;;
		}
		echo '<div class="widget-content">';
		echo '<div style="width:100%; height:'.$height.'px; overflow:visible"><iframe width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="'.$scroll.'" marginheight="0" marginwidth="0" src="'.$url.'"></iframe></div>';
    	// Widget code here
 
		// End widget
		echo '</div>';
		echo $after_widget;
	}
	
}

// Adding your widget to WordPress
add_action( 'widgets_init', create_function('', 'return register_widget("CyonIframeWidget");') );

