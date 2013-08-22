<?php

if ( !defined('ABSPATH') )
	die('-1');

/* =Map
use [map width='' height='350' zoom='14' long='' lat='' address='New York, USA'] xxx [/map]
----------------------------------------------- */
function cyon_map( $atts, $content = null, $id ) {
	$atts = shortcode_atts(
		array(
			width		=> '',
			height		=> '350',
			zoom		=> '14',
			lat			=> '',
			long		=> '',
			address		=> 'New York, USA'
		), $atts);
	ob_start();
		wp_enqueue_script('gmap_api');
		wp_enqueue_script('gmap');
	ob_get_clean();
	return '<div class="gmap" data-address="'.$atts['address'].'" data-lat="'.$atts['lat'].'" data-long="'.$atts['long'].'" data-zoom="'.$atts['zoom'].'" style="max-width: '.$atts['width'].'; height: '.$atts['height'].'px;">'. $content .'</div>';
}
add_shortcode('map','cyon_map'); 

/* =IFrame
use [iframe width='500' height='350' scroll='yes' url='http://localhost/']
----------------------------------------------- */
function cyon_iframe( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			width		=> '500',
			height		=> '350',
			scroll		=> 'yes',
			url			=> 'http://lipsum.com/'
		), $atts);
	return '<div style="width:'.$atts['width'].'px; max-width:100%; height:'.$atts['height'].'px; overflow:visible;"><iframe style="max-width:100%;" width="'.$atts['width'].'" height="'.$atts['height'].'" frameborder="0" scrolling="'.$atts['scroll'].'" marginheight="0" marginwidth="0" src="'.$atts['url'].'"></iframe></div>';
}
add_shortcode('iframe','cyon_iframe'); 


/* =Video
use [video width='480' height='270' src='' poster='' subtitles='' chapters='']
----------------------------------------------- */
function cyon_video( $atts, $content = null ) {
	global $data;
	$atts = shortcode_atts(
		array(
			width		=> '480',
			height		=> '270',
			poster		=> '',
			src			=> '',
			subtitles	=> '',
			chapters	=> ''
		), $atts);
	$html = '';
	if($atts['src']!=''){
		if($data['responsive']==1 && $atts['width']=='100%'){ $style=' style="width:100%; height:100%;"'; }
		$domain = parse_url(strtolower($atts['src']));
		if($domain['host']=='www.youtube.com' || $domain['host']=='youtube.com' || $domain['host']=='youtu.be'){
			//$html .= '<video width="'.$atts['width'].'" height="'.$atts['height'].'" type="video/youtube" src="'.$atts['src'].'" preload="none"'.$style.' />';
			if($data['responsive']==1){ $html .= '<div class="flex-video">'; }
			$html .= '<iframe width="'.$atts['width'].'" height="'.$atts['height'].'" src="http://www.youtube.com/embed/'.get_youtube_id($atts['src']).'?showinfo=0" frameborder="0" allowfullscreen></iframe>';
			if($data['responsive']==1){ $html .= '</div>'; }
		}elseif($domain['host']=='www.vimeo.com' || $domain['host']=='vimeo.com'){
			//$html .= '<video width="'.$atts['width'].'" height="'.$atts['height'].'" type="video/vimeo" src="'.$atts['src'].'" preload="none"'.$style.' />';
			if($data['responsive']==1){ $html .= '<div class="flex-video flex-video-vimeo">'; }
			$html .= '<iframe src="http://player.vimeo.com/video/'.get_vimeo_id($atts['src']).'" width="'.$atts['width'].'" height="'.$atts['height'].'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
			if($data['responsive']==1){ $html .= '</div>'; }
		}elseif($domain['scheme']=='rtmp'){
			$html .= '<video width="'.$atts['width'].'" height="'.$atts['height'].'" type="video/flv" src="'.$atts['src'].'" autoplay'.$style.' /></video>';
			ob_start();
				wp_enqueue_script('mediaelement');
				wp_enqueue_style('mediaelement_style');
			ob_get_clean();
		}else{
			$type = '';
			$sources = explode(",", $atts['src']);
			$html .= '<video width="'.$atts['width'].'" height="'.$atts['height'].'" poster="'.$atts['poster'].'" controls="controls" preload="none"'.$style.'>';
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
					$html .= '<source type="video/'.$type.'" src="'.$sources[$i].'" />';
				}
				if($type=='mp4' || $type=='m4v' || $type=='mov' || $type=='flv'){
					$html .= '<object width="'.$atts['width'].'" height="'.$atts['height'].'" type="application/x-shockwave-flash" data="'.get_template_directory_uri().'/assets/js/jquery.flashmediaelement.swf"><param name="movie" value="'.get_template_directory_uri().'/assets/js/jquery.flashmediaelement.swf" /><param name="flashvars" value="controls=true&poster='.$atts['poster'].'&file='.$sources[$i].'" /><img src="'.$atts['poster'].'" width="'.$atts['width'].'" height="'.$atts['height'].'" title="'.__('No video playback capabilities').'" /></object>';
				}
				if($atts['subtitles']!=''){
					$html .= '<track kind="subtitles" src="'.$atts['subtitles'].'" srclang="en" />';
				}
				if($atts['chapters']!=''){
					$html .= '<track kind="chapters" src="'.$atts['chapters'].'" srclang="en" />';
				}
			}
			$html .= '</video>';
			ob_start();
				wp_enqueue_script('mediaelement');
				wp_enqueue_style('mediaelement_style');
			ob_get_clean();
		}
	}else{
		$html = __('No video source specified.');
	}
	return $html;
}
add_shortcode('video','cyon_video'); 

if (!function_exists('get_youtube_id')){
	function get_youtube_id($url) {
	
		// find the youtube-based URL in the post
		if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
			return $match[1];
		}	
	
	} // end get_youtube_id
}
if (!function_exists('get_vimeo_id')){
	function get_vimeo_id($content) {
	
		return (int) substr(parse_url($content, PHP_URL_PATH), 1);; 
	
	} 
}

/* =Audio
use [audio width='480' src='']
----------------------------------------------- */
function cyon_audio( $atts, $content = null ) {
	global $data;
	$atts = shortcode_atts(
		array(
			width		=> '',
			height		=> '30',
			src			=> ''
		), $atts);
	$html = '';
	if($atts['src']!=''){
		if($data['responsive']==1 && $atts['width']=='100%'){ $style=' style="width:100%;"'; }
		$file = pathinfo($atts['src']);
		$html .= '<audio width="'.$atts['width'].'" src="'.$atts['src'].'" type="audio/'.$file['extension'].'" controls="controls" preload="none"'.$style.'>';
		if($file['extension']=='mp4' || $file['extension']=='mpeg' || $file['extension']=='m4a' || $file['extension']=='flv'){
			$html .= '<object width="'.$atts['width'].'" height="'.$atts['height'].'" type="application/x-shockwave-flash" data="'.get_template_directory_uri().'/assets/js/jquery.flashmediaelement.swf"><param name="movie" value="'.get_template_directory_uri().'/assets/js/jquery.flashmediaelement.swf" /><param name="flashvars" value="controls=true&file='.$atts['src'].'" />'.__('No video playback capabilities').'" /></object>';
		}
		$html .= '</audio>';
		ob_start();
			wp_enqueue_script('mediaelement');
			wp_enqueue_style('mediaelement_style');
		ob_get_clean();
	}else{
		$html = __('No audio source specified.');
	}
	return $html;
}
add_shortcode('audio','cyon_audio'); 



/* =Gallery
Override default gallery code
----------------------------------------------- */
function cyon_post_gallery( $blank = NULL, $attr ) {
	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) )
			$attr['orderby'] = 'post__in';
		$attr['include'] = $attr['ids'];
	}

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => '',
		'style'    => '',
		'height'    => '150'	
	), $attr, 'gallery'));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$icontag = tag_escape($icontag);
	$valid_tags = wp_kses_allowed_html( 'post' );
	if ( ! isset( $valid_tags[ $itemtag ] ) )
		$itemtag = 'dl';
	if ( ! isset( $valid_tags[ $captiontag ] ) )
		$captiontag = 'dd';
	if ( ! isset( $valid_tags[ $icontag ] ) )
		$icontag = 'dt';

	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if ( apply_filters( 'use_default_gallery_style', true ) )
		$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 10px;
				text-align: center;
				width: {$itemwidth}%;
			}
			#{$selector} img {
				border: 2px solid #cfcfcf;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
			/* see gallery_shortcode() in wp-includes/media.php */
		</style>";
	
	$gallery_script = '';
	if($attr['style']=='masonry'){
		$gallery_script = "
			<script type='text/javascript'>
				// Isotope Support
				jQuery(window).load(function(){
					jQuery('#$selector').imagesLoaded(function(){
						jQuery('#$selector').isotope({
							itemSelector: '.gallery-item',
							animationOptions: {
								duration: 750,
								easing: 'linear',
								queue: false
							},
							masonry: {
								gutterWidth: 10
							}
						});
						jQuery(window).trigger('scroll');
					});
				});
				jQuery(window).scroll(function(){
					jQuery('#$selector').isotope('reLayout');
				});
			</script> 
		";
	}elseif($attr['style']=='carousel'){
		$gallery_script = "
			<script type='text/javascript'>
				// Carousel Support
				jQuery(window).load(function(){
					jQuery('#$selector').imagesLoaded(function(){
						var cyonCarouselGallery{$id} = jQuery('#$selector .swiper-container').swiper({
							loop:true,
							slidesPerGroup: {$columns},
							slidesPerView: {$columns},
							calculateHeight: true
						});
						jQuery('#$selector .swiper-left').on('click', function(e){
							e.preventDefault();
							cyonCarouselGallery{$id}.swipePrev();
							jQuery('.carousel-large-$selector img').attr('src',jQuery('#$selector .swiper-slide-active a').attr('href'));
						})
						jQuery('#$selector .swiper-right').on('click', function(e){
							e.preventDefault();
							cyonCarouselGallery{$id}.swipeNext();
							jQuery('.carousel-large-$selector img').attr('src',jQuery('#$selector .swiper-slide-active a').attr('href'));
						})
						jQuery('.carousel-large-$selector').append('<img src=\"' + jQuery(this).find('.swiper-slide-active a').attr('href') +'\" />');
						jQuery('#$selector .swiper-slide a').click(function(e){
							jQuery('.carousel-large-$selector img').attr('src',jQuery(this).attr('href'));
							e.preventDefault();
							false;
						});
					});
				});
			</script> 
		";
	}
	
	$size_class = sanitize_html_class( $size );
	if($attr['style']=='carousel'){
		$gallery_div = $gallery_script. "\n\t\t" . "<div id='$selector' class='carousel' style='width:92%'><a class='swiper-left' href='#'><span class='icon-chevron-left'></span></a><a class='swiper-right' href='#'><span class='icon-chevron-right'></span></a><div class='swiper-container'><div class='swiper-wrapper'>";
	}else{
		$gallery_div = $gallery_script. "\n\t\t" . "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	}
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		if ( ! empty( $attr['link'] ) && 'file' === $attr['link'] )
			$image_output = wp_get_attachment_link( $id, $size, false, false );
		elseif ( ! empty( $attr['link'] ) && 'none' === $attr['link'] )
			$image_output = wp_get_attachment_image( $id, $size, false );
		else
			$image_output = wp_get_attachment_link( $id, $size, true, false );

		$image_meta  = wp_get_attachment_metadata( $id );

		$orientation = '';
		if ( isset( $image_meta['height'], $image_meta['width'] ) )
			$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

		if($attr['style']=='carousel'){
			$large_image_url = wp_get_attachment_image_src( $id, 'large');
			$thumbnail_image_url = wp_get_attachment_image_src( $id, $size);
			$output .= '<div class="swiper-slide"><div class="swiper-slide-wrapper"><a href="'.$large_image_url[0].'"><img src="'.$thumbnail_image_url[0].'" alt="" /></a>';
		}else{
			$output .= "<{$itemtag} class='gallery-item'>";
			$output .= "
				<{$icontag} class='gallery-icon {$orientation}'>
					$image_output
				</{$icontag}>";
		}
		if ( $captiontag && trim($attachment->post_excerpt) && $attr['style']!='carousel' ) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
		if($attr['style']=='carousel'){
			$output .= "</div></div>";
		}else{
			$output .= "</{$itemtag}>";
			if ( $columns > 0 && ++$i % $columns == 0 )
				$output .= '<br style="clear: both" />';
		}
	}

	if($attr['style']=='carousel'){
		$output .= "</div></div></div><div class='gallery-carousel carousel-large-$selector'></div>\n";
	}else{
		$output .= "
				<br style='clear: both;' />
			</div>\n";
	}

	return $output;
}
add_filter( 'post_gallery', 'cyon_post_gallery', 10, 2);

