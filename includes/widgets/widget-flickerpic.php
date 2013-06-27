<?php

class CyonFlickrWidget extends WP_Widget {

	// Declare widget-wide variable
	protected $flickr_id, $flickr_columns;

	// Creating your widget
	function CyonFlickrWidget(){
		$widget_ops = array('classname' => 'cyon-flickr-gallery', 'description' => __('Displays Latest Flickr Photos') );
		$this->WP_Widget('CyonFlickrWidget', __('Cyon Flickr Gallery'), $widget_ops);
	}
 
 	// Widget form in WP Admin
	function form($instance){
		// Start adding your fields here
		$instance = wp_parse_args( (array) $instance, array(
			'title' 		=> 'Flickr Photos',
			'id'			=> '48956289@N05',
			'count' 		=> '12',
			'columns' 		=> '3'
		) );
		$title = $instance['title'];
		$id = $instance['id'];
		$count = $instance['count'];
		$columns = $instance['columns'];

		?>
		  <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Flickr ID  (<a href="http://www.idgettr.com" target="_blank">idGettr</a>)') ?>: <input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo attribute_escape($id); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of images') ?>: <input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" size="3" value="<?php echo attribute_escape($count); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('columns'); ?>"><?php _e('Columns') ?>: <input id="<?php echo $this->get_field_id('columns'); ?>" name="<?php echo $this->get_field_name('columns'); ?>" type="text" size="3" value="<?php echo attribute_escape($columns); ?>" /></label></p>

		<?php
	}

	// Saving your widget form
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		// Override new values of each fields
		$instance['title'] = $new_instance['title'];
		$instance['id'] = $new_instance['id'];
		$instance['count'] = $new_instance['count'];
		$instance['columns'] = $new_instance['columns'];

		return $instance;
	}

	// Displays your widget on the front-end
	function widget($args, $instance){
		extract($args, EXTR_SKIP);

		$title = $instance['title'];
		$id = $instance['id'];
		$count = $instance['count'];
		$columns = $instance['columns'];

		// Start widget
		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		
		if (!empty($title)){
			echo $before_title . $title . $after_title;;
		}
		echo '<div class="widget-content">';
		
		echo '<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count='.$count.'&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user='.$id.'"></script>';

		// End widget
		echo '</div>';
		echo $after_widget;

		$this->flickr_id = $id;
		$this->flickr_columns = $columns;

		add_action('wp_footer', array(&$this, 'cyon_flickr_style'));
	}

	function cyon_flickr_style() {
	?>
		<style type="text/css">
			.flickr_badge_image { width:<?php echo 100 / $this->flickr_columns ?>%; }
		</style>
		<script type="text/javascript">
			jQuery(document).ready(function(){ 
				jQuery('.flickr_badge_image a').addClass('fancybox-group').attr('rel','flickr_<?php echo $this->flickr_id; ?>');
				jQuery('.flickr_badge_image a').each(function(index) {
					var urlsmall = jQuery(this).find('img').attr('src');
					var newurl = (urlsmall.replace('_s', '_z'));
					jQuery(this).attr('href',newurl);
				});
				var urlsmall = jQuery(this).find('img').attr('src');
				var newurl = (urlsmall.replace('_s', '_z'));
				jQuery(this).attr('href',newurl);
			});
		</script>
	<?php }

}

// Adding your widget to WordPress
add_action( 'widgets_init', create_function('', 'return register_widget("CyonFlickrWidget");') );

