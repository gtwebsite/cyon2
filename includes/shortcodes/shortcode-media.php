<?php

if ( !defined('ABSPATH') )
	die('-1');

/* =Map
use [map width='' height='350' zoom='14' long='' lat='' address='New York, USA'] xxx [/map]
----------------------------------------------- */
if(!function_exists('cyon_map')){
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
} }
add_shortcode('map','cyon_map'); 

/* =IFrame
use [iframe width='500' height='350' scroll='yes' url='http://localhost/']
----------------------------------------------- */
if(!function_exists('cyon_iframe')){
function cyon_iframe( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			width		=> '500',
			height		=> '350',
			scroll		=> 'yes',
			url			=> 'http://lipsum.com/'
		), $atts);
	return '<div style="width:'.$atts['width'].'px; max-width:100%; height:'.$atts['height'].'px; overflow:visible;" class="iframe"><iframe style="max-width:100%;" width="'.$atts['width'].'" height="'.$atts['height'].'" frameborder="0" scrolling="'.$atts['scroll'].'" marginheight="0" marginwidth="0" src="'.$atts['url'].'"></iframe></div>';
} }
add_shortcode('iframe','cyon_iframe'); 


if (!function_exists('cyon_get_youtube_id')){
	function cyon_get_youtube_id($url) {
	
		// find the youtube-based URL in the post
		if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
			return $match[1];
		}	
	
	} // end cyon_get_youtube_id
}
if (!function_exists('cyon_get_vimeo_id')){
	function cyon_get_vimeo_id($content) {
	
		return (int) substr(parse_url($content, PHP_URL_PATH), 1);; 
	
	} 
}


/* =Gallery
Override default gallery code
----------------------------------------------- */
if(!function_exists('cyon_post_gallery')){
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
	}elseif($attr['style']=='slide'){
		ob_start();
			wp_enqueue_script('fotorama');
			wp_enqueue_style('fotorama');
		ob_get_clean();
	}
	
	$size_class = sanitize_html_class( $size );
	if($attr['style']=='slide'){
		$gallery_div = $gallery_script. "\n\t\t" . "<div id='$selector' class='fotorama' data-nav='thumbs' data-width='100%' data-autoplay='true'>";
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

		if($attr['style']=='slide'){
			$large_image_url = wp_get_attachment_image_src( $id, $attr['size']);
			$thumbnail_image_url = wp_get_attachment_image_src( $id, 'thumbnail');
			$output .= '<a href="'.$large_image_url[0].'"><img src="'.$thumbnail_image_url[0].'" alt="" data-caption="'. wptexturize($attachment->post_excerpt) .'" /></a>';
		}else{
			$output .= "<{$itemtag} class='gallery-item'>";
			$output .= "
				<{$icontag} class='gallery-icon {$orientation}'>
					$image_output
				</{$icontag}>";
		}
		if ( $captiontag && trim($attachment->post_excerpt) && $attr['style']!='slide' ) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
		if($attr['style']!='slide'){
			$output .= "</{$itemtag}>";
			if ( $columns > 0 && ++$i % $columns == 0 )
				$output .= '<br style="clear: both" />';
		}
	}

	if($attr['style']=='slide'){
		$output .= "</div>\n";
	}else{
		$output .= "
				<br style='clear: both;' />
			</div>\n";
	}

	return $output;
} }
add_filter( 'post_gallery', 'cyon_post_gallery', 10, 2);

