<?php

/* =Price Grid
use [pricegrid labels='label1,label2' columuns='2'] xxx [/pricegrid]
----------------------------------------------- */
function cyon_pricegrid( $atts, $content = null ) {
	global $gridcol;
	$atts = shortcode_atts(
		array(
			labels => '',
			columns => 4,
			currency => '$',
			bgcolor => '#03BCEE'
		), $atts);
		$labels = explode(',',$atts['labels']);
	$CyonCurrency = $atts['currency'];
	$html ='
		<style media="all" type="text/css">
			.grid article.selected { background-color: '.$atts['bgcolor'].'!important; }
		</style>
		<div class="grid row-fluid">
			<aside class="span2">
				<ul>';
	foreach($labels as $key => $value){
		$html .= '<li>'.$value.'</li>';
	}
	$html .='	</ul>
			</aside>
			<div class="span10 cols'.$atts['columns'].'">'.do_shortcode($content).'</div>
		</div>
	';
	return $html;
}
add_shortcode('pricegrid','cyon_pricegrid');

function cyon_pricegrid_gridcolumn( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			title => '',
			price => '',
			period => '',
			link_url => '',
			highlighted => 'no',
			best_value => 'no',
			button => __('See details')
		), $atts);
	if ($atts['highlighted'] == 'yes'){
		$class = ' class="selected"';
		$btn = ' btn-green';
	}
	if ($atts['best_value']!='no'){
		$bestvalue = '<h4 class="label">'.$atts['highlighted'].'</h4>';
	}
	$html = '<article'.$class.'>
				<header>
					<hgroup class="plan"><h1>'.$atts['title'].'</h1></hgroup>
					<hgroup class="price">
						<h2>'.$atts['price'].'<em>'.$atts['period'].'</em></h2>'.$bestvalue.'
					</hgroup>
				</header>
				<section>
					<ul>
						'.do_shortcode($content).'
					</ul>
				</section>
				<footer><a href="'.$atts['link_url'].'" class="btn btn-large'.$btn.'">'.$atts['button'].'</a></footer>		
	';
	$html .= '</article>'; 
	return $html;
}

add_shortcode('gridcolumn','cyon_pricegrid_gridcolumn');

function cyon_pricegrid_gridoption( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			text => '',
			tooltip_text => '',
			checked => ''
		), $atts);
	if($atts['tooltip_text']){
		$tooltip_text =$atts['tooltip_text'];
		$tip = ' title="'.$tooltip_text.'"';
		$tipclass = ' class="hastip"';
	}
	if($atts['checked'] == 'yes'){
		$content = '<span class="check">Yes</span>';
	}elseif($atts['checked'] == 'no'){
		$content = '<span class="no">No</span>';
	}elseif($atts['text']){
		$content = '<span>'.$atts['text'].'</span>';
	}else{
		$content = '<span>&nbsp;</span>';
	}
	$html = '
		<li>
			<span'.$tipclass.$tip.'>'.$content.'</span>
		</li>
	';
	return $html;
}
add_shortcode('gridoption','cyon_pricegrid_gridoption');