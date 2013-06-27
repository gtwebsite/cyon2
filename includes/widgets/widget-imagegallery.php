<?php

class CyonImageGalleryWidget extends WP_Widget {
	
	protected $style;
	
	// Creating your widget
	function CyonImageGalleryWidget(){
		$widget_ops = array('classname' => 'cyon-image-gallery', 'description' => __('Displays Image Gallery from Post') );
		$this->WP_Widget('CyonImageGalleryWidget', __('Cyon Image Gallery Widget'), $widget_ops);
	}
 
 	// Widget form in WP Admin
	function form($instance){
		global $post;
		// Start adding your fields here
		$instance = wp_parse_args( (array) $instance, array(
			'title' 		=> 'Image Gallery',
			'style'			=> 'Lightbox',
			'size'			=> 'medium',
			'postid'		=> '',
			'columns' 		=> '3'
		) );
		$title = $instance['title'];
		$style = $instance['style'];
		$size = $instance['size'];
		$postid = $instance['postid'];
		$columns = $instance['columns'];
		$posts = new WP_Query(array('orderby' => 'title', 'order' => 'ASC', 'tax_query' => array(array('taxonomy' => 'post_format','field' => 'slug','terms' => 'post-format-gallery'))));
		?>
		  <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
  		  <p><label for="<?php echo $this->get_field_id('postid'); ?>"><?php _e('Gallery Post Format') ?>: <select class="widefat" id="<?php echo $this->get_field_id('postid'); ?>" name="<?php echo $this->get_field_name('postid'); ?>">
				<?php while ($posts->have_posts()) { $posts->the_post(); ?>
					<option value="<?php the_ID(); ?>" <?php echo get_the_ID() == $postid ? 'selected' : ''?>><?php echo the_title(); ?></option>
				<?php } ?>
		  </select></label></p>		  
			<?php $options_size = array( 'thumbnail'=>'Thumnail', 'medium'=>'Medium', 'large'=>'Large', 'full'=>'Full'); ?>
  		  <p><label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Size') ?>: <select class="widefat" id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>">
				<?php foreach ( $options_size as $key=>$value ) : ?>
					<option value="<?php echo $key ?>" <?php echo $key== $instance['size'] ? 'selected' : ''?>><?php echo $value ?></option>
				<?php endforeach; ?>
		  </select></label></p>		  
			<?php $options = array( 1=>'Lightbox', 'Animation'); ?>
  		  <p><label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('View Style') ?>: <select class="widefat" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>">
				<?php foreach ( $options as $i=>$opt ) : ?>
					<option value="<?php echo $i?>" <?php echo $i == $instance['style'] ? 'selected' : ''?>><?php echo $opt ?></option>
				<?php endforeach; ?>
		  </select></label></p>		  
		  <p><label for="<?php echo $this->get_field_id('columns'); ?>"><?php _e('Columns') ?>: <input id="<?php echo $this->get_field_id('columns'); ?>" name="<?php echo $this->get_field_name('columns'); ?>" type="text" size="3" value="<?php echo attribute_escape($columns); ?>" /></label></p>
		<?php
	}
	// Saving your widget form
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		// Override new values of each fields
		$instance['title'] = $new_instance['title'];
		$instance['text'] = $new_instance['text'];
		$instance['style'] = $new_instance['style'];
		$instance['size'] = $new_instance['size'];
		$instance['postid'] = $new_instance['postid'];
		$instance['columns'] = $new_instance['columns'];
		return $instance;
	}
		
	// Displays your widget on the front-end
	function widget($args, $instance){
		extract($args, EXTR_SKIP);
		$this->style = $instance['style'];

		// Start widget
		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
			if (!empty($title)){
			echo $before_title . $title . $after_title;
		}
		$post = new WP_Query( 'p='.$instance['postid'] );
		$classes = '';
    	// Widget code here
		if($instance['style']==1){
			if($instance['columns']>1){
				$classes .= ' columns columns'.$instance['columns'];
			}
			echo '<div class="widget-content'.$classes.'">';
		}else{
			$classes .= 'slides';
			echo '<div class="widget-content"><div class="flexslider"><ul class="'.$classes.'">';
		}
		while ($post->have_posts()) {
			$post->the_post();
			$images = rwmb_meta('cyon_gallery_images', 'type=image&size='.$instance['size']);
			$i = 0;
			foreach ( $images as $image ){
				if($instance['style']==1){
					if($i==$instance['columns']){
						$i=0;
						$first_class = ' first';
					}else{
						$i++;
						$first_class = '';
					}
					echo '<div class="thumbnail'.$first_class.'"><a href="'.$image['full_url'].'" class="fancybox" rel="widget_gallery_'.$this->id.'" title="'.$image['name'].'"><img src="'.$image['url'].'" alt="'.$image['name'].'" /></a></div>';
				}else{
					echo '<li><a href="'.$image['full_url'].'" class="fancybox" rel="widget_gallery_'.$this->id.'" title="'.$image['name'].'"><img src="'.$image['url'].'" alt="'.$image['name'].'" /></a></li>';
					add_action ( 'wp_footer', 'cyon_header_banner_js_css');
					add_action ( 'wp_footer', 'cyon_footer_banner_common_hook');
				}
			}
		}
		wp_reset_postdata();
		if($instance['style']==1){
			echo '</div>';
		}else{
			echo '</ul></div></div>';
		}
		// End widget
		echo $after_widget;
	}
}

// Adding your widget to WordPress
add_action( 'widgets_init', create_function('', 'return register_widget("CyonImageGalleryWidget");') );
