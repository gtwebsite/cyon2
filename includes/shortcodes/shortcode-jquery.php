<?php

if ( !defined('ABSPATH') )
	die('-1');

/* =Toggle
use [toggle title='title' icon=''] xxx [/accordion]
----------------------------------------------- */
function cyon_toggle( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			title 		=> __( 'Title Here' , 'cyon' ),
			icon		=> '',
		), $atts);
		
	$icon = '';
	if($atts['icon']){
		$icon = '<span class="icon-'.$atts['icon'].'"></span>';
	}
	$toggle_content .= '<div class="toggle"><h3 class="toggle-title">' . $icon . $atts['title'] . '</h3><div class="toggle-wrapper"><div class="toggle-content clearfix">'. $content . '</div></div></div>';
	return $toggle_content;
}
add_shortcode('toggle','cyon_toggle');

/* =Accordion
use [accordion title='title' icon=''] xxx [/accordion]
----------------------------------------------- */
function cyon_accordion( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			title 		=> __( 'Title Here' , 'cyon' ),
			icon		=> '',
		), $atts);
	$icon = '';
	if($atts['icon']){
		$icon = '<span class="icon-'.$atts['icon'].'"></span>';
	}
	$accordion_content = array('<div class="accordion"> <h3 class="accordion-title">' . $icon . $atts['title'] . '</h3><div class="accordion-wrapper"><div class="accordion-content clearfix">'. $content . '</div></div></div>');
	foreach ($accordion_content as $value){
		return $value ;
	}
}
add_shortcode('accordion','cyon_accordion'); 

/* =Tabs
use [tabs] [tab title='' icon=''] xxx [/tab] [/tabs]
----------------------------------------------- */
$tab_nav = array();
function cyon_tabs( $atts, $content = null ) {
	$GLOBALS['tab_count'] = 0;
	do_shortcode($content);
	$html = '<div class="tabs"><ul class="tab_nav clearfix">';
	foreach( $GLOBALS['tabs'] as $tab ){
		$icon = '';
		if($tab['icon']){
			$icon = '<span class="icon-'.$tab['icon'].'"></span>';
		}
		$html .= '<li><a href="#tab_'.$tab['index'].'">'.$icon.$tab['title'].'</a></li>';
	}
	$html .= '</ul><div class="panel">';
	foreach( $GLOBALS['tabs'] as $tab ){
		$html .= '<div id="tab_'.$tab['index'].'" class="tab-content clearfix"><h3>'.$tab['title'].'</h3>'.$tab['content'].'</div>';
	}
	$html .= '</div></div>';
	return $html;
}
add_shortcode('tabs','cyon_tabs'); 

function cyon_tab( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			title 		=> __( 'Title Here' , 'cyon' ),
			icon		=> '',
			active 		=> false
		), $atts);
	$x = $GLOBALS['tab_count'];
	$GLOBALS['tabs'][$x] = array( 'title'=> $atts['title'], 'icon'=>$atts['icon'], 'content' =>  do_shortcode($content), 'index' => $x );
	$GLOBALS['tab_count']++;
}
add_shortcode('tab','cyon_tab'); 


/* =Testimonials
use [testimonials id='' style='' classname='']
----------------------------------------------- */
function cyon_testimonial( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			id			=> '',
			classname 	=> '',
			cols 		=> '1',
			style		=> 'list' // list, slide
		), $atts);
	global $data;
	static $instance = 0;
	$instance++;
	$testimonials = $data['testimonials'];
	$html = '';

	if(count($testimonials)>0){
		$html .= '<div class="cyon-testimonial '.$atts['classname'].'">';
		if($atts['style']=='slide'){
			$html .= '<div id="testimonial-'.$instance.'"><a class="swiper-left" href="#"><span class="icon-chevron-left"></span></a><a class="swiper-right" href="#"><span class="icon-chevron-right"></span></a><div class="swiper-pager"></div><div class="swiper-container"><div class="swiper-wrapper">';
		}else{
			if($atts['cols']>1){
				$html .= '<ul class="row-fluid">';
			}else{
				$html .= '<ul>';
			}
		}
		$count = 0;
		foreach ($testimonials as $testimonial) {
			if($atts['style']=='slide'){
				$html .= '<div class="swiper-slide"><div class="swiper-slide-wrapper">';
			}else{
				$count++;
				if($atts['cols']==2){
					$span = 'span6';
				}elseif($atts['cols']==3){
					$span = 'span4';
				}elseif($atts['cols']==4){
					$span = 'span3';
				}elseif($atts['cols']==6){
					$span = 'span2';
				}else{
					$span = '';
				}
				if($count==$atts['cols']){
					$count = 0;
				}elseif($count==1){
					$span .= ' first-child';
				}
				$html .= '<li class="'.$span.'">';
			}
			$html .= '<blockquote class="clearfix"><div class="icon-quote-left"></div>'.$testimonial['description'].'<div class="bubble"></div></blockquote><div class="name clearfix">';
			$html .= $testimonial['url']!='' ? '<img src="'.$testimonial['url'].'" alt="'.$testimonial['title'].'" />' : '';
			$html .= '<h4>'.$testimonial['title'].'</h4>';
			$html .= $testimonial['company']!='' ? '<p>'.$testimonial['company'].'</p>' : '';
			$html .= '</div>';
			if($atts['style']=='slide'){
				$html .= '</div></div>';
			}else{
				$html .= '</li>';
			}
		}
		if($atts['style']=='slide'){
			$html .= '</div></div></div>
				<script type="text/javascript">
					jQuery(document).ready(function(){
						var cyonTestimonial'.$instance.' = jQuery(\'#testimonial-'.$instance.' .swiper-container\').swiper({
							autoplay:Math.floor(Math.random() * 6000) + 4000,
							loop: true,
							calculateHeight: true,
							slidesPerGroup: '.$atts['cols'].',
							slidesPerView: '.$atts['cols'].'
						});
					});
				</script>';
		}else{
			$html .= '</ul>';
		}
		$html .= '</div>';
	}

	return $html;
}
add_shortcode('testimonials','cyon_testimonial'); 



/* =Carousel
use [carousel slidesperview='3' width='92%' height='100' loop='no'] [slide title=''] xxx [/slide] [/carousel]
----------------------------------------------- */
function cyon_carousel( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			slidesperview => '1',
			width => '92%',
			height => '100',
			loop => ''
		), $atts);

	static $instance = 0;
	$instance++;
	$loop = '';
	if($atts['loop']=='yes'){
		$loop = "loop:true,";
	}
	
	$carousel_content = array('
		<div class="carousel" id="carousel-'.$instance.'" style="width:'.$atts['width'].';"><a class="swiper-left" href="#"><span class="icon-chevron-left"></span></a><a class="swiper-right" href="#"><span class="icon-chevron-right"></span></a><div class="swiper-container" style="height:'.$atts['height'].'px"><div class="swiper-wrapper">'.do_shortcode($content).'</div></div></div>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				var cyonCarousel'.$instance.' = jQuery(\'#carousel-'.$instance.' .swiper-container\').swiper({'.$loop.'
					slidesPerGroup: '.$atts['slidesperview'].',
					slidesPerView: '.$atts['slidesperview'].'
				});
				jQuery(\'#carousel-'.$instance.' .swiper-left\').on(\'click\', function(e){
					e.preventDefault();
					cyonCarousel'.$instance.'.swipePrev();
				});
				jQuery(\'#carousel-'.$instance.' .swiper-right\').on(\'click\', function(e){
					e.preventDefault();
					cyonCarousel'.$instance.'.swipeNext();
				});
			});
		</script>
	');
	foreach ($carousel_content as $value){
		return $value ;
	}
}
add_shortcode('carousel','cyon_carousel'); 

function cyon_slide( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			title => ''
		), $atts);
	$slide_content = array('<div class="swiper-slide"><div class="swiper-slide-wrapper"><h3 class="swiper-title">' . $atts['title'] . '</h3><div class="swiper-content clearfix">'. do_shortcode($content) . '</div></div></div>');
	foreach ($slide_content as $value){
		return $value ;
	}
}
add_shortcode('slide','cyon_slide'); 
