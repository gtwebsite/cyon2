<?php

class CyonSocialWidget extends WP_Widget {

	// Creating your widget
	function CyonSocialWidget(){
		$widget_ops = array('classname' => 'cyon-social', 'description' => __('Displays social icons and links') );
		$this->WP_Widget('CyonSocialWidget', __('Cyon Social Icons'), $widget_ops);
	}
 
 	// Widget form in WP Admin
	function form($instance){
		// Start adding your fields here
		$instance = wp_parse_args( (array) $instance, array(
			'title' 		=> __('Socialize'),
			'facebook'		=> '',
			'google_plus'	=> '',
			'twitter'		=> '',
			'linkedin'		=> '',
			'pinterest'		=> '',
			'skype'			=> '',
			'youtube'		=> '',
			'vimeo'			=> '',
			'flickr'		=> '',
			'email'			=> '',
			'rss'			=> get_bloginfo('rss2_url')
		) );
		$title = $instance['title'];
		$facebook = $instance['facebook'];
		$google_plus = $instance['google_plus'];
		$twitter = $instance['twitter'];
		$linkedin = $instance['linkedin'];
		$pinterest = $instance['pinterest'];
		$skype = $instance['skype'];
		$youtube = $instance['youtube'];
		$vimeo = $instance['vimeo'];
		$flickr = $instance['flickr'];
		$email = $instance['email'];
		$rss = $instance['rss'];

		?>
		  <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook') ?>: <input class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" type="text" value="<?php echo attribute_escape($facebook); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('google_plus'); ?>"><?php _e('Google+') ?>: <input class="widefat" id="<?php echo $this->get_field_id('google_plus'); ?>" name="<?php echo $this->get_field_name('google_plus'); ?>" type="text" value="<?php echo attribute_escape($google_plus); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter') ?>: <input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo attribute_escape($twitter); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('linkedin'); ?>"><?php _e('Linkedin') ?>: <input class="widefat" id="<?php echo $this->get_field_id('linkedin'); ?>" name="<?php echo $this->get_field_name('linkedin'); ?>" type="text" value="<?php echo attribute_escape($linkedin); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('pinterest'); ?>"><?php _e('Pinterest') ?>: <input class="widefat" id="<?php echo $this->get_field_id('pinterest'); ?>" name="<?php echo $this->get_field_name('pinterest'); ?>" type="text" value="<?php echo attribute_escape($pinterest); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('youtube'); ?>"><?php _e('Youtube') ?>: <input class="widefat" id="<?php echo $this->get_field_id('youtube'); ?>" name="<?php echo $this->get_field_name('youtube'); ?>" type="text" value="<?php echo attribute_escape($youtube); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('vimeo'); ?>"><?php _e('Vimeo') ?>: <input class="widefat" id="<?php echo $this->get_field_id('vimeo'); ?>" name="<?php echo $this->get_field_name('vimeo'); ?>" type="text" value="<?php echo attribute_escape($vimeo); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('flickr'); ?>"><?php _e('Flickr') ?>: <input class="widefat" id="<?php echo $this->get_field_id('flickr'); ?>" name="<?php echo $this->get_field_name('flickr'); ?>" type="text" value="<?php echo attribute_escape($flickr); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Email') ?>: <input class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="text" value="<?php echo attribute_escape($email); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('rss'); ?>"><?php _e('RSS') ?>: <input class="widefat" id="<?php echo $this->get_field_id('rss'); ?>" name="<?php echo $this->get_field_name('rss'); ?>" type="text" value="<?php echo attribute_escape($rss); ?>" /></label></p>
		<?php
	}

	// Saving your widget form
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		// Override new values of each fields
		$instance['title'] = $new_instance['title'];
		$instance['facebook'] = $new_instance['facebook'];
		$instance['google_plus'] = $new_instance['google_plus'];
		$instance['twitter'] = $new_instance['twitter'];
		$instance['linkedin'] = $new_instance['linkedin'];
		$instance['pinterest'] = $new_instance['pinterest'];
		$instance['youtube'] = $new_instance['youtube'];
		$instance['vimeo'] = $new_instance['vimeo'];
		$instance['flickr'] = $new_instance['flickr'];
		$instance['email'] = $new_instance['email'];
		$instance['rss'] = $new_instance['rss'];
		return $instance;
	}

	// Displays your widget on the front-end
	function widget($args, $instance){
		extract($args, EXTR_SKIP);
		
		// Start widget
		echo $before_widget;
		$html = ''; 
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		$html .= empty($instance['facebook']) ? '' : '<a href="'.$instance['facebook'].'" class="hastip has-icon2x" title="Facebook"><span class="icon2x-facebook"></span></a>';
		$html .= empty($instance['google_plus']) ? '' : '<a href="'.$instance['google_plus'].'" class="hastip has-icon2x" title="Google+"><span class="icon2x-google_plus"></span></a>';
		$html .= empty($instance['twitter']) ? '' : '<a href="'.$instance['twitter'].'" class="hastip has-icon2x" title="Twitter"><span class="icon2x-twitter"></span></a>';
		$html .= empty($instance['linkedin']) ? '' : '<a href="'.$instance['linkedin'].'" class="hastip has-icon2x" title="Linkedin"><span class="icon2x-linked_in"></span></a>';
		$html .= empty($instance['pinterest']) ? '' : '<a href="'.$instance['pinterest'].'" class="hastip has-icon2x" title="Pinterest"><span class="icon2x-pinterest"></span></a>';
		$html .= empty($instance['youtube']) ? '' : '<a href="'.$instance['youtube'].'" class="hastip has-icon2x" title="Youtube"><span class="icon2x-youtube"></span></a>';
		$html .= empty($instance['vimeo']) ? '' : '<a href="'.$instance['vimeo'].'" class="hastip has-icon2x" title="Vimeo"><span class="icon2x-vimeo"></span></a>';
		$html .= empty($instance['flickr']) ? '' : '<a href="'.$instance['flickr'].'" class="hastip has-icon2x" title="Flickr"><span class="icon2x-flickr"></span></a>';
		$html .= empty($instance['email']) ? '' : '<a href="mailto:'.$instance['email'].'" class="hastip has-icon2x" title="Email"><span class="icon2x-e-mail"></span></a>';
		$html .= empty($instance['rss']) ? '' : '<a href="'.$instance['rss'].'" class="hastip has-icon2x" title="RSS"><span class="icon2x-rss"></span></a>';
		
		if (!empty($title)){
			echo $before_title . $title . $after_title;;
		}
		echo '<div class="widget-content">';
		
    	// Widget code here
		echo $html;
 
		// End widget
		echo '</div>';
		echo $after_widget;
	}

}

// Adding your widget to WordPress
add_action( 'widgets_init', create_function('', 'return register_widget("CyonSocialWidget");') );