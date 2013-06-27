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

