<?php

class CyonTestimonialWidget extends WP_Widget {

	// Creating your widget
	function CyonTestimonialWidget(){
		$widget_ops = array('classname' => 'cyon-testimonial', 'description' => __('Displays on testimonials','cyon') );
		$this->WP_Widget('CyonTestimonialWidget', __('Cyon Testimonial','cyon'), $widget_ops);
	}
 
 	// Widget form in WP Admin
	function form($instance){
		// Start adding your fields here
		$instance = wp_parse_args( (array) $instance, array(
			'title' 		=> __('Testimonials'),
			'style'		=> 'true'
		) );
		$title = $instance['title'];
		$style = $instance['style'];

		?>
		  <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
		  <p><input type="checkbox" name="<?php echo $this->get_field_name('style'); ?>" id="<?php echo $this->get_field_id('style'); ?>" value="1" <?php echo ($style == "true" ? "checked='checked'" : ""); ?> /> <label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Enable slide') ?></label></p>

		<?php
	}

	// Saving your widget form
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		// Override new values of each fields
		$instance['title'] = $new_instance['title'];
		$instance['style'] = (bool)$new_instance['style'];

		return $instance;
	}

	// Displays your widget on the front-end
	function widget($args, $instance){
		extract($args, EXTR_SKIP);
		global $data;
		$testimonials = $data['testimonials'];
		
		$title = $instance['title'];
		if($instance['style']=='true'){ $slide='yes'; }else{ $slide='no'; }


		// Start widget
		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		
		if (!empty($title)){
			echo $before_title . $title . $after_title;;
		}
		echo '<div class="widget-content">';
		if(count($testimonials)>0){
			if($slide=='yes'){
				echo '<div class="swiper"><a class="swiper-left" href="#"><span class="icon-chevron-left"></span></a><a class="swiper-right" href="#"><span class="icon-chevron-right"></span></a><div class="swiper-pager"></div><div class="swiper-container"><div class="swiper-wrapper">';
			}else{
				echo '<ul>';
			}
			foreach ($testimonials as $testimonial) {
				if($slide=='yes'){
					echo '<div class="swiper-slide">';
				}else{
					echo '<li>';
				}
				echo '<blockquote class="clearfix"><div class="icon-quote-left"></div>'.$testimonial['description'].'<div class="bubble"></div></blockquote><div class="name clearfix">';
				echo $testimonial['url']!='' ? '<img src="'.$testimonial['url'].'" alt="'.$testimonial['title'].'" />' : '';
				echo '<h4>'.$testimonial['title'].'</h4>';
				echo $testimonial['company']!='' ? '<p>'.$testimonial['company'].'</p>' : '';
				echo '</div>';
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
add_action( 'widgets_init', create_function('', 'return register_widget("CyonTestimonialWidget");') );

