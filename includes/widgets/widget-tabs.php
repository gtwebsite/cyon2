<?php

class CyonTabsWidget extends WP_Widget {

	// Creating your widget
	function CyonTabsWidget(){
		$widget_ops = array('classname' => 'cyon-tabs tabs', 'description' => __('Displays Widgets in Tabs', 'cyon') );
		$this->WP_Widget('CyonTabsWidget', __('Cyon Tabs', 'cyon'), $widget_ops);
	}
 
 	// Widget form in WP Admin
	function form($instance){
		// Start adding your fields here
		$instance = wp_parse_args( (array) $instance, array(
			'widgetarea'		=> ''
		) );
		$widgetarea = $instance['widgetarea'];

		?>
		  <p>
		  	<label for="<?php echo $this->get_field_id('widgetarea'); ?>"><?php _e('Widget Area', 'cyon') ?></label>
			<select id="<?php echo $this->get_field_id('widgetarea'); ?>" name="<?php echo $this->get_field_name('widgetarea'); ?>">
				<option value="">- Please select -</option>
				<?php foreach ($GLOBALS['wp_registered_sidebars'] as $val) { ?>
				<option value="<?php echo $val['id']; ?>"<?php selected($widgetarea, $val['id']); ?>><?php echo $val['name']; ?></option>
				<?php }	?>
			</select>
		  </p>

		<?php
	}

	// Saving your widget form
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		// Override new values of each fields
		$instance['widgetarea'] = $new_instance['widgetarea'];

		return $instance;
	}

	// Displays your widget on the front-end
	function widget($args, $instance){
		extract($args, EXTR_SKIP);
		global $wp_registered_widgets;

    	// Widget code here
		echo $before_widget;
		echo '<ul class="tab_nav">';
		echo '';
		$widgets = wp_get_sidebars_widgets();
		$widgetarea = $widgets[$instance['widgetarea']];
		foreach($widgetarea as $widget){
			$title = $this->get_widget_titles($wp_registered_widgets[$widget]);
			echo '<li><a href="#'.$widget.'">'.$title['given_title'].'</a></li>';
			//echo $widget;
		}
		echo '</ul><div class="panel">';
		//print_r($wp_registered_widgets['cyonsocialwidget-3']);
		//print_r(wp_get_sidebars_widgets());
		dynamic_sidebar( $instance['widgetarea'] );
		echo '</div>'.$after_widget;
		// End widget
	}
	
	function get_widget_titles($widget_data) {
		$widget_name = $widget_data['name'];
		$widget_params = $widget_data['params'];
		$widget_callback = $widget_data['callback'];
		
		// if parameter is a string
		if (isset($widget_params[0]) && !is_array($widget_params[0]))
			$widget_params = $widget_params[0];
		
		$sidebar_params['before_title'] = '[%';
		$sidebar_params['after_title'] = '%]';
		$all_params = array_merge(array($sidebar_params), (array)$widget_params);					
			
		if (is_callable($widget_callback)) {
			// Call widget to see its title
			ob_start();
				call_user_func_array($widget_callback, $all_params);
				$widget_title = ob_get_contents();
			ob_end_clean();
			
			// Extract only title of the widget
			$find_fn_pattern = '/\[\%(.*?)\%\]/';
			preg_match_all($find_fn_pattern, $widget_title, $result);
			$given_title = strip_tags(trim((string)$result[1][0]));
		} else {
			$widget_title = $widget_name;
			$given_title = '';
		}
		
		return array('original_title' => $widget_name, 'given_title' => $given_title);
	}		
}

// Adding your widget to WordPress
add_action( 'widgets_init', create_function('', 'return register_widget("CyonTabsWidget");') );

