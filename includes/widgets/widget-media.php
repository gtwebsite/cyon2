<?php

class CyonMediaWidget extends WP_Widget {

	// Creating your widget
	function CyonMediaWidget(){
		$widget_ops = array('classname' => 'cyon-media-player', 'description' => __('Displays Video/Audio player') );
		$this->WP_Widget('CyonMediaWidget', __('Cyon Media Player'), $widget_ops);
	}
 
 	// Widget form in WP Admin
	function form($instance){
		// Start adding your fields here
		$instance = wp_parse_args( (array) $instance, array(
			'title' 		=> 'Media Player',
			'text'			=> '',
			'image_url'		=> ''
		) );
		$title = $instance['title'];
		$text = $instance['text'];
		$image_url = $instance['image_url'];
		?>
		  <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Media URL (comma separated for alternative local file formats)') ?>: <textarea class="widefat" rows="10" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo attribute_escape($text); ?></textarea></label></p>
		  <p><label for="<?php echo $this->get_field_id('image_url'); ?>"><?php _e('Image URL') ?>: <input class="widefat" id="<?php echo $this->get_field_id('image_url'); ?>" name="<?php echo $this->get_field_name('image_url'); ?>" type="text" value="<?php echo attribute_escape($image_url); ?>" /></label></p>
		<?php
	}

	// Saving your widget form
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		// Override new values of each fields
		$instance['title'] = $new_instance['title'];
		$instance['text'] = $new_instance['text'];
		$instance['image_url'] = $new_instance['image_url'];
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
		echo '<div class="widget-content">';
    	// Widget code here
		$domain = parse_url(strtolower($instance['text']));
		if($domain['host']=='www.youtube.com' || $domain['host']=='youtube.com'){
			//echo '<video type="video/youtube" src="'.$instance['text'].'" preload="none" style="width:100%; height:100%;" />';
			echo '<div class="flex-video"><iframe width="480" height="270" src="http://www.youtube.com/embed/'.get_youtube_id($instance['text']).'?showinfo=0" frameborder="0" allowfullscreen></iframe></div>';
		}elseif($domain['host']=='www.vimeo.com' || $domain['host']=='vimeo.com'){
			//echo '<video type="video/vimeo" src="'.$instance['text'].'" preload="none" style="width:100%; height:100%;" />';
			echo '<div class="flex-video flex-video-vimeo"><iframe src="http://player.vimeo.com/video/'.get_vimeo_id($instance['text']).'" width="480" height="270" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
		}elseif($domain['scheme']=='rtmp'){
			echo '<video type="video/flv" src="'.$instance['text'].'" autoplay style="width:100%; height:100%;" /></video>';
			add_action('wp_footer','cyon_video_audio_js_css',20);
		}else{
			$file = pathinfo($instance['text']);
			$sources = explode(",", $instance['text']);
			if(count($sources)==1 && $file['extension'] != 'mp4'){
				echo '<audio src="'.$instance['text'].'" type="audio/'.$file['extension'].'" controls="controls" preload="none" style="width:100%;">';
				if($file['extension']=='mp4' || $file['extension']=='mpeg' || $file['extension']=='m4a' || $file['extension']=='flv'){
					echo '<object type="application/x-shockwave-flash" data="'.get_template_directory_uri().'/assets/js/jquery.flashmediaelement.swf"><param name="movie" value="'.get_template_directory_uri().'/assets/js/jquery.flashmediaelement.swf" /><param name="flashvars" value="controls=true&file='.$instance['text'].'" />'.__('No video playback capabilities').'" /></object>';
				}
				echo '</audio>';
			}else{
				$type = '';
				echo '<video controls="controls" preload="none" poster="'.$instance['image_url'].'" style="width:100%; height:100%;">';
				for($i=0; $i<count($sources); $i++){
					$file = pathinfo($sources[$i]);
					if ($file['extension'] == 'mp4'){
						$type = 'mp4';
					}elseif($file['extension'] == 'm4v'){
						$type = 'm4v';
					}elseif($file['extension'] == 'mov'){
						$type = 'mov';
					}elseif($file['extension'] == 'wmv'){
						$type = 'wmv';
					}elseif($file['extension'] == 'flv'){
						$type = 'flv';
					}elseif($file['extension'] == 'webm'){
						$type = 'webm';
					}elseif($file['extension'] == 'ogv'){
						$type = 'ogg';
					}
					if($type!=''){
						echo '<source type="video/'.$type.'" src="'.$sources[$i].'" />';
					}
					if($type=='mp4' || $type=='m4v' || $type=='mov' || $type=='flv'){
						echo '<object type="application/x-shockwave-flash" data="'.get_template_directory_uri().'/assets/js/jquery.flashmediaelement.swf"><param name="movie" value="'.get_template_directory_uri().'/assets/js/jquery.flashmediaelement.swf" /><param name="flashvars" value="controls=true&poster='.$instance['image_url'].'&file='.$sources[$i].'" />'.__('No video playback capabilities').'</object>';
					}
				}
				echo '</video>';
			}
			add_action('wp_footer','cyon_video_audio_js_css',20);
		}
		// End widget
		echo '</div>';
		echo $after_widget;
	}
}

// Adding your widget to WordPress
add_action( 'widgets_init', create_function('', 'return register_widget("CyonMediaWidget");') );

if (!function_exists('get_youtube_id')){
	function get_youtube_id($content) {
	
		// find the youtube-based URL in the post
		$urls = array();
		preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $content, $urls);
		$youtube_url = $urls[0][0];
	
		// next, locate the youtube video id
		$youtube_id = '';
		if(strlen(trim($youtube_url)) > 0) {
			parse_str( parse_url( $youtube_url, PHP_URL_QUERY ) );
			$youtube_id = $v;
		} // end if
	
		return $youtube_id; 
	
	} // end get_youtube_id
}
if (!function_exists('get_vimeo_id')){
	function get_vimeo_id($content) {
	
		return (int) substr(parse_url($content, PHP_URL_PATH), 1);; 
	
	} 
}
