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
		$icon = '<span class="icon-'.$atts['icon'].'"></span>';
	}
	$title = '';
	if($atts['title']){
		$title = ' title="'. $atts['title'] . '"';
	}
	$html = '<a href="'. $atts['url'] . '" class="btn"'.$title.'>'. $icon . $content . '</a>';
	return $html;
}
add_shortcode('button','cyon_button'); 

/* =Header Icons
use [header size='' icon_color='' icon='' classname=''] xxx [/header]
----------------------------------------------- */
function cyon_header_style( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			size		=> '2',
			classname	=> '',
			icon		=> 'right_arrow'
		), $atts);
	$classname = '';
	if($atts['size']==''){
		$atts['size'] = '2';
	}
	if($atts['icon']==''){
		$atts['icon'] = 'right_arrow';
	}
	$size = 'h'.$atts['size'];
	if($atts['classname']){
		$classname .= ' class="'.$atts['classname'].'"';
	}
	$icon = '<span class="icon-'.$atts['icon'].'"></span>';
	$icon_content = array('<'.$size.$classname.'>'. $icon . $content . '</'.$size.'>');
	foreach ($icon_content as $value){
		return $value ;
	}
}
add_shortcode('header','cyon_header_style'); 

/* =Inline Icons
use [icon element='' icon='' classname='' title='' url='' size='' color='' align=''] xxx [/icon]
----------------------------------------------- */
function cyon_inline_icon( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			element		=> 'span',
			classname	=> '',
			url			=> '',
			color		=> '',
			title		=> '',
			icon		=> '',
			size		=> '',
			align		=> ''
		), $atts);
	$element = $atts['element'];
	if($atts['classname']){
		$classname .= ' '.$atts['classname'];
	}
	$size = '';
	if($atts['size']){
		$size = ' font-size:'. $atts['size'].';';
	}
	$color = '';
	if($atts['color']){
		$color = ' color:'. $atts['color'].';';
	}
	$align = '';
	if($atts['align']){
		$align = ' align:'. $atts['align'].';';
	}
	$style = ' style="'.$size.$color.$align.'"';
	$title = '';
	if($atts['title']){
		$title = ' title="'. $atts['title'] . '"';
	}
	$url = '';
	if($atts['url'] || $atts['element']=='a'){
		$url = ' href="'. $atts['url'] . '"';
		$element = 'a';
	}
	if($atts['icon']=='' && ($atts['url'] || $atts['element']=='a')){
		$icon = 'icon-share';
		$element = 'a';
	}elseif($atts['icon']==''){
		$icon = 'icon-question-sign';
	}else{
		$icon = 'icon-'.$atts['icon'];
	}
	$html = '<'.$element.' class="'.$classname.'"'.$title.$url.$style.'><span class="'.$icon.'"></span>' . $content . '</'.$element.'>';
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
			cols		=> ''
		), $atts);
	$classname = '';
	if($atts['cols']){
		$classname .= ' cols'.$atts['cols'];
	}
	if($atts['classname']){
		$classname .= ' '.$atts['classname'];
	}
	$classname .= ' has-icon-list';
	if($atts['icon']==''){
		$icon = 'icon-ok';
	}else{
		$icon = 'icon-'.$atts['icon'];
	}
	$GLOBALS['iconlist'] = $icon;
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
		$icon = 'icon-'.$atts['icon'];
	}
	$classname = '';
	if($atts['classname']){
		$classname = ' '.$atts['classname'];
	}
	$list_content = array('<li class="has-icon'.$classname.'"><span class="'.$icon.'"></span>'. do_shortcode($content) .'</li>');
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
		$icon = '<span class="icon-box icon-'.$atts['icon'].'"></span>';
		$classname .= ' has-icon-box';
	}
	$close = '';
	if($atts['close']=='yes'){
		$close = '<a class="btn btn-close"><span class="icon-remove"></span></a>';
	}
	$quote = '';
	$title = '';
	if($atts['quote']=='yes'){
		$quote = 'blockquote';
		$title .= '<div class="icon-quote-left"></div>';
	}else{
		$quote = 'div';
	}
	if($atts['title']!=''){
		$title .= '<h3>'.$icon.$atts['title'].'</h3>';
	}
	if($classname!=''){
		$class = ' class="'.$classname.'"';
	}
	$html = '<'.$quote.$class.$style.'>'. $title . do_shortcode($content) . $close .'</'.$quote.'>';
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


/* =Social Boxes
use [social]
----------------------------------------------- */
function cyon_social( $atts, $content = null ) {
	global $data;
	$social = $data['socialshare'];
	$socialb = $data['socialshareboxes'];
	$socialboxes = '';
	if($socialb['facebook_like']==1 && $data['socialshare_fbid']!=''){
		$socialboxes .= '<iframe src="//www.facebook.com/plugins/like.php?href='.get_permalink( $post->ID ).'&amp;send=false&amp;layout=standard&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=35&amp;appId='.$data['socialshare_fbid'].'" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:35px;" allowTransparency="true"></iframe><br />';
	}
	if($socialb['facebook']==1){
		$socialboxes .= '<span class="st_facebook_hcount" displayText="Facebook"></span>';
	}
	if($socialb['twitter']==1){
		$socialboxes .= '<span class="st_twitter_hcount" displayText="Tweet"></span>';
	}
	if($socialb['plus']==1){
		$socialboxes .= '<span class="st_googleplus_hcount" displayText="Google +"></span>';
	}
	if($socialb['pinterest']==1){
		$socialboxes .= '<span class="st_pinterest_hcount" displayText="Pinterest"></span>';
	}
	if($socialb['mail']==1){
		$socialboxes .= '<span class="st_email_hcount" displayText="Email"></span>';
	}
	if($socialb['sharethis']==1){
		$socialboxes .= '<span class="st_sharethis_hcount" displayText="ShareThis"></span>';
	}
	add_action ('wp_footer','cyon_social_js',10);
	return '<p class="share">'.$socialboxes.'</p>';
}
add_shortcode('social','cyon_social'); 


