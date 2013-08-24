<?php

class CyonTwitterWidget extends WP_Widget {

	// Creating your widget
	function CyonTwitterWidget(){
		$widget_ops = array('classname' => 'cyon-twitter', 'description' => __('Displays Twitter Feed') );
		$this->WP_Widget('CyonTwitterWidget', __('Cyon Twitter'), $widget_ops);
	}
 
 	// Widget form in WP Admin
	function form($instance){
		// Start adding your fields here
		$instance = wp_parse_args( (array) $instance, array(
			'title' 		=> __('Twitter Updates','cyon'),
			'username' 		=> '',
			'count'			=> '',
			'style'			=> 'true'
		) );
		$title = $instance['title'];
		$username = $instance['username'];
		$count = $instance['count'];
		$style = $instance['style'];

		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','cyon') ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Username','cyon') ?>: <input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo attribute_escape($username); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Count','cyon') ?>: <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo attribute_escape($count); ?>" /></label></p>
		<p><input type="checkbox" name="<?php echo $this->get_field_name('style'); ?>" id="<?php echo $this->get_field_id('style'); ?>" value="1" <?php echo ($style == "true" ? "checked='checked'" : ""); ?> /> <label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Enable slide','cyon') ?></label></p>
		<?php
	}

	// Saving your widget form
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		// Override new values of each fields
		$instance['title'] = $new_instance['title'];
		$instance['username'] = $new_instance['username'];
		$instance['count'] = $new_instance['count'];
		$instance['style'] = (bool)$new_instance['style'];
		return $instance;
	}

	// Displays your widget on the front-end
	function widget($args, $instance){
		extract($args, EXTR_SKIP);
		global $data;
		
		// Start widget
		echo $before_widget;
		$html = ''; 
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		if($instance['style']=='true'){ $slide='yes'; }else{ $slide='no'; }

		if (!empty($title)){
			echo $before_title . $title . $after_title;;
		}
		echo '<div class="widget-content">';
		
    	// Widget code here
		$xml = simplexml_load_file(THEME_URI.'/includes/functions/tmhOAuth/twitter_json_to_rss.php?consumer_key='.$data['twitter_consumer_key'].'&consumer_secret='.$data['twitter_consumer_secret'].'&user_token='.$data['twitter_access_token'].'&user_secret='.$data['twitter_access_token_secret'].'&screen_name='.$instance['username'].'&count='.$instance['count']) or die(__('Error: Cannot load updates','cyon'));
		$items = $xml->channel->item;
		if(count($items)>0){
			if($slide=='yes'){
				echo '<div class="swiper"><a class="swiper-left" href="#"><span class="icon-chevron-left"></span></a><a class="swiper-right" href="#"><span class="icon-chevron-right"></span></a><div class="swiper-pager"></div><div class="swiper-container"><div class="swiper-wrapper">';
			}else{
				echo '<ul>';
			}
			foreach ($items as $item) {
				if($slide=='yes'){
					echo '<div class="swiper-slide">';
				}else{
					echo '<li>';
				}
				echo make_clickable('<p>'.$item->description.'<br /><small><a href="'.$item->link.'">'.human_time_diff(strtotime($item->created), current_time('timestamp')).' '.__('ago','cyon').'</a></small></p>');
				if($slide=='yes'){
					echo '</div>';
				}else{
					echo '</li>';
				}
			}
			if($slide=='yes'){
				echo '</div></div></div>';
			}else{
				echo '</ul>';
			}
		}
		// End widget
		echo '</div>';
		echo $after_widget;
	}
	
}

// Adding your widget to WordPress
add_action( 'widgets_init', create_function('', 'return register_widget("CyonTwitterWidget");') );

