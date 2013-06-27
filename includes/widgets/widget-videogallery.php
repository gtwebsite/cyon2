<?php

class CyonVideoGalleryWidget extends WP_Widget {

	// Creating your widget
	function CyonVideoGalleryWidget(){
		$widget_ops = array('classname' => 'cyon-video-gallery', 'description' => __('Displays Youtube videos in thumbnails') );
		$this->WP_Widget('CyonVideoGalleryWidget', __('Cyon Video Gallery'), $widget_ops);
	}
 
 	// Widget form in WP Admin
	function form($instance){
		// Start adding your fields here
		$instance = wp_parse_args( (array) $instance, array(
			'title' 		=> 'Video Gallery',
			'text'			=> 'UwbOr_7qtG4,9lejDw3dtsw,RHPMocTmC08',
		) );
		$title = $instance['title'];
		$text = $instance['text'];
		?>
		  <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Youtube IDs separated by commma') ?>: <textarea class="widefat" rows="10" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" type="text"><?php echo attribute_escape($text); ?></textarea></label></p>
		<?php
	}

	// Saving your widget form
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		// Override new values of each fields
		$instance['title'] = $new_instance['title'];
		$instance['text'] = $new_instance['text'];
		return $instance;
	}

	// Displays your widget on the front-end
	function widget($args, $instance){
		extract($args, EXTR_SKIP);
		
		// Start widget
		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		
		if (!empty($title)){
			echo $before_title . $title . $after_title;;
		}
		echo '<ul class="widget-content">';
    	// Widget code here
		$videoids = explode(',',$instance['text']);
		for( $i=0; $i<count($videoids); $i++){
			$video = 'http://i4.ytimg.com/vi/'.$videoids[$i].'/hqdefault.jpg';
			echo '<li>';
			echo '<a href="http://www.youtube.com/embed/'.$videoids[$i].'/?autoplay=1" class="iframe"><img src="'.$video.'" /></a>';
			echo '</li>';
		}
		
		// End widget
		echo '</ul>';
		echo $after_widget;
	}
}

// Adding your widget to WordPress
add_action( 'widgets_init', create_function('', 'return register_widget("CyonVideoGalleryWidget");') );