<?php

class CyonBlogListWidget extends WP_Widget {

	// Creating your widget
	function CyonBlogListWidget(){
		$widget_ops = array('classname' => 'cyon-blog-list', 'description' => __('Displays Blog Posts','cyon') );
		$this->WP_Widget('CyonBlogListWidget', __('Cyon Blog List','cyon'), $widget_ops);
	}
 
 	// Widget form in WP Admin
	function form($instance){
		// Start adding your fields here
		$instance = wp_parse_args( (array) $instance, array(
			'title' 	=> __('Latest Posts'),
			'count'		=> '3',
			'params'	=> '',
			'excerpt'	=> 'true',
			'thumb'		=> 'true',
			'url'		=> ''			
		) );
		$title = $instance['title'];
		$count = $instance['count'];
		$cols = $instance['cols'];
		$params = $instance['params'];
		$excerpt = $instance['excerpt'];
		$thumb = $instance['thumb'];
		$url = $instance['url'];

		?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Count') ?>: <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo attribute_escape($count); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('params'); ?>"><?php _e('Parameters') ?>: <input class="widefat" id="<?php echo $this->get_field_id('params'); ?>" name="<?php echo $this->get_field_name('params'); ?>" type="text" placeholder="&cat=1" value="<?php echo attribute_escape($params); ?>" /></label></p>
			<p><input type="checkbox" name="<?php echo $this->get_field_name('excerpt'); ?>" id="<?php echo $this->get_field_id('excerpt'); ?>" value="1" <?php echo ($excerpt == "true" ? "checked='checked'" : ""); ?> /> <label for="<?php echo $this->get_field_id('excerpt'); ?>"><?php _e('Show excerpt') ?></label></p>
			<p><input type="checkbox" name="<?php echo $this->get_field_name('thumb'); ?>" id="<?php echo $this->get_field_id('thumb'); ?>" value="1" <?php echo ($thumb == "true" ? "checked='checked'" : ""); ?> /> <label for="<?php echo $this->get_field_id('thumb'); ?>"><?php _e('Show thumbnail') ?></label></p>
			<p><label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('View more URL') ?>: <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo attribute_escape($url); ?>" /></label></p>

		<?php
	}

	// Saving your widget form
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		// Override new values of each fields
		$instance['title'] = $new_instance['title'];
		$instance['count'] = $new_instance['count'];
		$instance['params'] = $new_instance['params'];
		$instance['excerpt'] = (bool)$new_instance['excerpt'];
		$instance['thumb'] = (bool)$new_instance['thumb'];
		$instance['url'] = $new_instance['url'];

		return $instance;
	}

	// Displays your widget on the front-end
	function widget($args, $instance){
		extract($args, EXTR_SKIP);
		
		$title = $instance['title'];
		
		$posts_array = new WP_Query( 'posts_per_page='.$instance['count'].$instance['params'] );

		// Start widget
		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		
		if (!empty($title)){
			echo $before_title . $title . $after_title;;
		}
		echo '<div class="widget-content">';
		if($posts_array->have_posts()){
			while ( $posts_array->have_posts() ) : $posts_array->the_post(); ?>
				<article class="<?php if(has_post_thumbnail() && $instance['thumb']=='true'){ echo 'has-thumbnail'; } ?>"><div class="article-wrapper">
					<header class="entry-header">
						<?php if(has_post_thumbnail() && $instance['thumb']=='true') { ?><a href="<?php the_permalink(); ?>" class="featured-thumbnail"><?php the_post_thumbnail('thumbnail'); ?></a><?php } ?>
						<h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
						<div class="meta">
							<span class="posted-day"><?php echo esc_html( get_the_time('d') ); ?></span>
							<span class="posted-month"><?php echo esc_html( get_the_time('M') ); ?></span>
							<span class="posted-year"><?php echo esc_html( get_the_time('Y') ); ?></span>
						</div>
					</header>
					<?php if($instance['excerpt']=='true') { ?>
					<div class="entry-content clearfix">
						<?php the_excerpt(); ?>
					</div>
					<?php } ?>
				</div></article>
			<?php endwhile;
			if(!empty($instance['url'])){
				echo do_shortcode('[button url="'.$instance['url'].'"]'.__('View more','cyon').'[/button]');
			}
		}
 
		// End widget
		echo '</div>';
		
		wp_reset_postdata();
		
		echo $after_widget;
	}
	
}

// Adding your widget to WordPress
add_action( 'widgets_init', create_function('', 'return register_widget("CyonBlogListWidget");') );

