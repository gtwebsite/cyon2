<?php

if ( !defined('ABSPATH') )
	die('-1');

/* =Buttons
use [button color='' size='' icon='' url='' title='' classname=''] xxx [/button]
----------------------------------------------- */
function cyon_button( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			color		=> '',
			size		=> '',
			icon		=> '',
			url			=> '#',
			classname	=> '',
			title		=> ''
		), $atts);
	$classname = '';
	if($atts['classname']){
		$classname .= ' '.$atts['classname'];
	}
	if($atts['color']){
		$classname .= ' btn-'.$atts['color'];
	}
	if($atts['size']){
		$classname .= ' btn-'.$atts['size'];
	}
	$icon = '';
	if($atts['icon']){
		if($atts['size']=='large'){
			$classname .= ' has-icon2x';
			$icon = '<span class="icon2x-'.$atts['icon'].'"></span>';
		}else{
			$classname .= ' has-icon';
			$icon = '<span class="icon-'.$atts['icon'].'"></span>';
		}
	}
	$title = '';
	if($atts['title']){
		$classname .= ' hastip';
		$title = ' title="'. $atts['title'] . '"';
	}
	$html = '<a href="'. $atts['url'] . '" class="btn'.$classname.'"'.$title.'>'. $icon . $content . '</a>';
	return $html;
}
add_shortcode('button','cyon_button'); 

/* =Header Icons
use [header size='' icon='' classname=''] xxx [/header]
----------------------------------------------- */
function cyon_header_style( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			size		=> '2',
			classname	=> '',
			icon		=> 'right_arrow'
		), $atts);
	if($atts['size']==''){
		$atts['size'] = '2';
	}
	if($atts['icon']==''){
		$atts['icon'] = 'right_arrow';
	}
	$size = 'h'.$atts['size'];
	$classname = '';
	if($atts['classname']){
		$classname .= ' '.$atts['classname'];
	}
	$classname .= 'has-icon2x';
	$icon = '<span class="icon2x-'.$atts['icon'].'"></span>';
	$icon_content = array('<'.$size.' class="'.$classname.'">'. $icon . $content . '</'.$size.'>');
	foreach ($icon_content as $value){
		return $value ;
	}
}
add_shortcode('header','cyon_header_style'); 

/* =Inline Icons
use [icon element='' icon='' classname='' title='' url='' size=''] xxx [/icon]
----------------------------------------------- */
function cyon_inline_icon( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			element		=> 'span',
			classname	=> '',
			url			=> '',
			title		=> '',
			icon		=> '',
			size		=> ''
		), $atts);
	$classname = '';
	if($atts['size']=='large'){
		$classname .= 'has-icon2x';
		$iconsize = '2x';
	}else{
		$classname .= 'has-icon';
		$iconsize = '';
	}
	$element = $atts['element'];
	if($atts['classname']){
		$classname .= ' '.$atts['classname'];
	}
	$title = '';
	if($atts['title']){
		$classname .= ' hastip';
		$title = ' title="'. $atts['title'] . '"';
	}
	$url = '';
	if($atts['url'] || $atts['element']=='a'){
		$url = ' href="'. $atts['url'] . '"';
		$element = 'a';
	}
	if($atts['icon']=='' && ($atts['url'] || $atts['element']=='a')){
		$icon = 'icon'.$iconsize.'-share';
		$element = 'a';
	}elseif($atts['icon']==''){
		$icon = 'icon'.$iconsize.'-question-sign';
	}else{
		$icon = 'icon'.$iconsize.'-'.$atts['icon'];
	}
	$html = '<'.$element.' class="'.$classname.'"'.$title.$url.'><span class="'.$icon.'"></span> ' . $content . '</'.$element.'>';
	return $html;
}
add_shortcode('icon','cyon_inline_icon'); 

/* =Bulleted List
use [lists icon='' size="large" classname='' cols=''] [list icon=''] xxx [/list] [/lists]
----------------------------------------------- */
function cyon_bulleted_lists( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			icon		=> '',
			classname	=> '',
			size		=> '',
			cols		=> ''
		), $atts);
	$classname = '';
	if($atts['cols']){
		$classname .= ' cols'.$atts['cols'];
	}
	if($atts['classname']){
		$classname .= ' '.$atts['classname'];
	}
	if($atts['size']=='large'){
		$classname .= ' has-icon2x-list';
		$iconsize = '2x';
	}else{
		$classname .= ' has-icon-list';
		$iconsize = '';
	}
	if($atts['icon']==''){
		$icon = 'icon'.$iconsize.'-ok';
	}else{
		$icon = 'icon'.$iconsize.'-'.$atts['icon'];
	}
	$GLOBALS['iconlist'] = $icon;
	$GLOBALS['iconlist-size'] = $iconsize;
	$list_content = array('<ul class="clearfix'.$classname.'">'. do_shortcode($content) .'</ul>');
	foreach ($list_content as $value){
		return $value ;
	}
}
add_shortcode('lists','cyon_bulleted_lists'); 
function cyon_bulleted_list( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			icon		=> '',
			classname	=> ''
		), $atts);
	if($atts['icon']==''){
		$icon = $GLOBALS['iconlist'];
	}else{
		$icon = 'icon'.$GLOBALS['iconlist-size'].'-'.$atts['icon'];
	}
	$classname = '';
	if($atts['classname']){
		$classname = ' '.$atts['classname'];
	}
	$list_content = array('<li class="has-icon'.$GLOBALS['iconlist-size'].$classname.'"><span class="'.$icon.'"></span>'. do_shortcode($content) .'</li>');
	foreach ($list_content as $value){
		return $value ;
	}
}
add_shortcode('list','cyon_bulleted_list'); 

/* =Table
use [table caption='' headers='' footers=''] [/table]
----------------------------------------------- */
function cyon_table( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			classname	=> '',
			caption		=> '',
			headers		=> '',
			footers		=> ''
		), $atts);
	$classname = '';
	if($atts['classname']){
		$classname .= ' '.$atts['classname'];
	}
	$html = '<table class="table'.$classname.'">';
	if($atts['caption']){
		$html .= '<caption>'.$atts['caption'].'</caption>';
	}
	if($atts['headers']){
		$headers = explode('|',$atts['headers']);
		$html .= '<thead><tr>';
		for($i=0;$i<count($headers);$i++){
			$width = explode('%%',$headers[$i]);
			if(count($width)>0){
				$setwidth = ' style="width:'.$width[1].'%"';
				$html .= '<th'.$setwidth.'>'.$width[0].'</th>';
			}else{
				$html .= '<th'.$setwidth.'>'.$headers[$i].'</th>';
			}
		}
		$html .= '</tr></thead>';
	}
	if($atts['footers']){
		$footers = explode('|',$atts['footers']);
		$html .= '<tfoot><tr>';
		for($i=0;$i<count($footers);$i++){
			$html .= '<td>'.$footers[$i].'</td>';
		}
		$html .= '</tr></tfoot>';
	}
	$html .= '<tbody>'. do_shortcode($content).'</tbody>';
	$html .= '</table>';
	return $html;
}
add_shortcode('table','cyon_table'); 

function cyon_table_row( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			classname	=> '',
			color		=> ''
		), $atts);
	$classname = '';
	if($atts['color']!=''){
		$classname .= 'row-'.$atts['color'];
	}
	if($atts['classname']){
		$classname .= ' '.$atts['classname'];
	}
	if($classname!=''){
		$class = ' class="'.$classname.'"';
	}
	$row_content = array('<tr'.$class.'>'. do_shortcode($content) .'</tr>');
	foreach ($row_content as $value){
		return $value ;
	}
}
add_shortcode('row','cyon_table_row'); 

function cyon_table_data( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			classname	=> '',
			rows		=> '',
			cols		=> '',
			color		=> ''
		), $atts);
	$classname = '';
	$attributes = '';
	if($atts['color']!=''){
		$classname .= 'data-'.$atts['color'];
	}
	if($atts['classname']){
		$classname .= ' '.$atts['classname'];
	}
	if($atts['rows']){
		$attributes .= ' colspan="'.$atts['rows'].'"';
	}
	if($atts['cols']){
		$attributes .= ' colspan="'.$atts['cols'].'"';
	}
	if($classname!=''){
		$class = ' class="'.$classname.'"';
	}
	$data_content = array('<td'.$class.$attributes.'>'. do_shortcode($content) .'</td>');
	foreach ($data_content as $value){
		return $value ;
	}
}
add_shortcode('data','cyon_table_data'); 

/* =Box
use [box icon='' color='' close='no' classname='' title='' width='' align='' quote='no'] xxx [/box]
----------------------------------------------- */
function cyon_box( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			color		=> '',
			width		=> '',
			align		=> '',
			close		=> 'no',
			quote		=> 'no',
			classname	=> '',
			title		=> '',
			icon		=> ''
		), $atts);
	$style = '';
	if($atts['width']!=''){
		$width = (int)$atts['width'] - 60;
		$style .= ' style="width:'. $width .'px;  max-width:90%;"';
	}
	$classname = 'box';
	if($atts['color']!=''){
		$classname .= ' box-'.$atts['color'];
	}
	if($atts['align']){
		$classname .= ' align'.$atts['align'];
	}
	if($atts['classname']){
		$classname .= ' '.$atts['classname'];
	}
	if($atts['icon']!=''){
		$icon = '<span class="icon-box icon2x-'.$atts['icon'].'"></span>';
		$classname .= ' has-icon-box';
	}
	$close = '';
	if($atts['close']=='yes'){
		$close = '<a class="btn btn-close"><span class="icon-remove"></span></a>';
	}
	$quote = '';
	if($atts['quote']=='yes'){
		$quote = 'blockquote';
	}else{
		$quote = 'div';
	}
	$title = '';
	if($atts['title']!=''){
		$title = '<h3>'.$atts['title'].'</h3>';
	}
	if($classname!=''){
		$class = ' class="'.$classname.'"';
	}
	$html = '<'.$quote.$class.$style.'>'. $icon . $title . do_shortcode($content) . $close .'</'.$quote.'>';
	return $html;
}
add_shortcode('box','cyon_box'); 

/* =Code
use [code inline="yes"] xxx [/code]
----------------------------------------------- */
function cyon_code( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			inline		=> 'yes'
		), $atts);
	if($atts['inline']=='yes'){
		$code = 'code';
	}else{
		$code = 'pre';
	}
	$html = '<'.$code.'>'. html_entity_decode($content) .'</'.$code.'>';
	return $html;
}
add_shortcode('code','cyon_code'); 

/* =Horizontal Line
use [line style=""]
----------------------------------------------- */
function cyon_line( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			style	=> ''
		), $atts);
	$style = '';
	if($atts['style']){
		$style = ' class="'.$atts['style'].'"';
	}
	$html = '<hr'.$style.' />';
	return $html;
}
add_shortcode('line','cyon_line'); 

/* =Back to Top
use [backtotop style='' classname='']
----------------------------------------------- */
function cyon_backtotop( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			style	=> '',
			classname	=> ''
		), $atts);
	$style = '';
	if($atts['style']){
		$style = ' class="'.$atts['style'].' '.$atts['classname'].'"';
	}
	$html = '<div class="backtotop-line"><hr'.$style.' /><a href="#page" class="backtotop">'.__('Back to top','cyon').'</a></div>';
	return $html;
}
add_shortcode('backtotop','cyon_backtotop'); 
