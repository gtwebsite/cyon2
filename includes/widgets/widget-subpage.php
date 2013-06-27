<?php

class CyonSupageWidget extends WP_Widget {

	// Creating your widget
	function CyonSupageWidget(){
		$widget_ops = array('classname' => 'cyon-subpages', 'description' => __('Displays supages') );
		$this->WP_Widget('CyonSupageWidget', __('Cyon Supage'), $widget_ops);
	}
 
 	// Widget form in WP Admin
	function form($instance){
		// Start adding your fields here
		$instance = wp_parse_args( (array) $instance, array(
			'title' 		=> 'Subpages',
			'depths' 		=> '0'
		) );
		$title = $instance['title'];
		$depths = $instance['depths'];

		?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('depths'); ?>"><?php _e('Depths') ?>:</label>
				<?php $options = array( '0'=>__('Nested'), '-1'=>__('Flat'), '1'=>__('Top-level pages'), '2'=>__('Up to 2 depths'), '3'=>__('Up to 3 depths')); ?>
				<select id="<?php echo $this->get_field_id('depths'); ?>" name="<?php echo $this->get_field_name('depths'); ?>" class="widefat">
				<?php foreach ( $options as $i=>$opt ) : ?>
					<option value="<?php echo $i?>" <?php echo $i == $instance['depths'] ? 'selected' : ''?>><?php echo $opt ?></option>
				<?php endforeach; ?>
				</select>
			</p>

		<?php
	}

	// Saving your widget form
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		// Override new values of each fields
		$instance['title'] = $new_instance['title'];
		$instance['depths'] = $new_instance['depths'];

		return $instance;
	}

	// Displays your widget on the front-end
	function widget($args, $instance){
		extract($args, EXTR_SKIP);
		global $wp_query;
		$post_obj = $wp_query->get_queried_object();
		if ($post_obj->post_parent)	{
			$ancestors=get_post_ancestors($post_obj->ID);
			$root=count($ancestors)-1;
			$parent = $ancestors[$root];
		} else {
			$parent = $post_obj->ID;
		}		
		$title = $instance['title'];


		// Start widget
		if(is_page() && get_pages(array('child_of'=>$parent) && !is_home() && !is_front_page())){
			echo $before_widget;
			$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
			
			if (!empty($title)){
				echo $before_title . $title . $after_title;;
			}
			echo '<ul class="widget-content">';
			echo wp_list_pages(array('child_of'=>$parent,'title_li'=>'','depth'=>$instance['depths']));
			// Widget code here
	 
			// End widget
			echo '</ul>';
			echo $after_widget;
		}
	}
	
}

// Adding your widget to WordPress
add_action( 'widgets_init', create_function('', 'return register_widget("CyonSupageWidget");') );

