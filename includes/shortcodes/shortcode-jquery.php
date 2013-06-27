<?php

if ( !defined('ABSPATH') )
	die('-1');

/* =Toggle
use [toggle title='title'] xxx [/accordion]
----------------------------------------------- */
function cyon_toggle( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			title => __( 'Title Here' , 'cyon' )
		), $atts);
		
	$toggle_content .= '<div class="toggle"><h3 class="toggle-title">' . $atts['title'] . '</h3><div class="toggle-content clearfix">'. $content . '</div></div>';
	return $toggle_content;
}
add_shortcode('toggle','cyon_toggle');

/* =Accordion
use [accordion title='title'] xxx [/accordion]
----------------------------------------------- */
function cyon_accordion( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			title => __( 'Title Here' , 'cyon' )
		), $atts);
	$accordion_content = array('<div class="accordion"> <h3 class="accordion-title">' . $atts['title'] . '</h3><div class="accordion-content clearfix">'. $content . '</div></div>');
	foreach ($accordion_content as $value){
		return $value ;
	}
}
add_shortcode('accordion','cyon_accordion'); 

/* =Tabs
use [tabs] [tab title=''] xxx [/tab] [/tabs]
----------------------------------------------- */
$tab_nav = array();
function cyon_tabs( $atts, $content = null ) {
	$GLOBALS['tab_count'] = 0;
	do_shortcode($content);
	$html = '<div class="tabs"><ul class="tab_nav clearfix">';
	foreach( $GLOBALS['tabs'] as $tab ){
		$html .= '<li><a href="#tab_'.$tab['index'].'">'.$tab['title'].'</a></li>';
	}
	$html .= '</ul>';
	foreach( $GLOBALS['tabs'] as $tab ){
		$html .= '<div id="tab_'.$tab['index'].'" class="panel clearfix"><h3>'.$tab['title'].'</h3>'.$tab['content'].'</div>';
	}
	$html .= '</div>';
	return $html;
}
add_shortcode('tabs','cyon_tabs'); 

function cyon_tab( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			title => __( 'Title Here' , 'cyon' ),
			active => false
		), $atts);
	$x = $GLOBALS['tab_count'];
	$GLOBALS['tabs'][$x] = array( 'title'=> $atts['title'], 'content' =>  do_shortcode($content), 'index' => $x );
	$GLOBALS['tab_count']++;
}
add_shortcode('tab','cyon_tab'); 