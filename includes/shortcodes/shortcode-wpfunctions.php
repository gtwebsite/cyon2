<?php

if ( !defined('ABSPATH') )
	die('-1');

/* =Show Subpages
use [subpages excerpt='yes' thumbnail='no' id='' cols='' classname='']
----------------------------------------------- */
function cyon_subpages( $atts, $content = null ) {
	global $data;
	$atts = shortcode_atts(
		array(
			excerpt 	=> 'yes',
			thumbnail 	=> 'no',
			classname 	=> '',
			cols	 	=> '',
			id 			=> get_the_ID()
		), $atts);
	$args = array(
		'sort_order' 	=> 'ASC',
		'sort_column' 	=> 'menu_order',
		'child_of' 		=> $atts['id'],
		'parent'		=>  $atts['id']
	);
	$subpages = get_pages($args);
	$result = '';
	if($atts['cols']>1){
		global $subpage_cols;
		$subpage_cols = $atts['cols'];
		$cols = 12 / $atts['cols'];
		$classli = ' class="span'.$cols.'"';
	}
	foreach ( $subpages as $page ) {
		$result .= '<li'.$classli.'>';
		if($atts['thumbnail']=='yes'){
			$result .= '<div class="page-thumb"><a href="' . get_page_link( $page->ID ) . '">'.get_the_post_thumbnail( $page->ID, $data['content_thumbnail_size'] ).'</a></div>';
		}
		$result .= '<h4><a href="' . get_page_link( $page->ID ) . '">' . $page->post_title . '</a></h4>';
		if($atts['excerpt']=='yes'){
			$result .= do_shortcode($page->post_excerpt);
		}
		$result .= '</li>';
		echo $option;
	}
	$class = 'subpages';
	if($atts['classname']){
		$class .= ' '.$atts['classname'];
	}
	if($atts['cols']>1){
		$class .= ' row-fluid';
		ob_start();
			add_action('wp_footer','cyon_cyon_subpages_js_css',10);
		ob_get_clean();
	}
	return '<ul class="'.$class.'">'.$result.'</ul>';
}
add_shortcode('subpages','cyon_subpages'); 

function cyon_cyon_subpages_js_css(){
		global $subpage_cols;
?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('.subpages > li:nth-of-type(<?php echo $subpage_cols; ?>n+1)').addClass('first-child');
			});
		</script>
<?php }

/* =Show Blog
use [blog excerpt="yes" thumbnail="yes" cols="" items="4" cat_id="1" classname=""]
----------------------------------------------- */
function cyon_blog( $atts, $content = null ) {
	global $data;
	$atts = shortcode_atts(
		array(
			excerpt 	=> 'yes',
			thumbnail 	=> 'yes',
			items 		=> 4,
			classname 	=> '',
			cols	 	=> '',
			cat_id 		=> 1
		), $atts);
	$args = array(
		'numberposts' 	=> $atts['items'],
		'category' 		=> $atts['cat_id']
	);
	$posts = get_posts($args);
	$result = '';
	if($atts['cols']>1){
		global $blog_cols;
		$blog_cols = $atts['cols'];
		$cols = 12 / $atts['cols'];
		$classli = ' class="span'.$cols.'"';
	}
	foreach ( $posts as $post ) {
		setup_postdata($post);
		$result .= '<li'.$classli.'>';
		if($atts['thumbnail']=='yes'){
			$result .= '<div class="entry-featured-image"><a href="' . get_page_link( $post->ID ) . '">'.get_the_post_thumbnail( $post->ID, $data['content_thumbnail_size'] ).'</a></div>';
		}
		$result .= '<h4><a href="' . get_page_link( $post->ID ) . '">' . $post->post_title . '</a></h4>';
		if($atts['excerpt']=='yes'){
			if($post->post_excerpt){
				$result .= $post->post_excerpt;
			}else{
				$result .= get_the_excerpt();
			}
		}
		$result .= '</li>';
		echo $option;
	}
	$class = 'postlist';
	if($atts['classname']){
		$class .= ' '.$atts['classname'];
	}
	if($atts['cols']>1){
		$class .= ' row-fluid';
		ob_start();
			add_action('wp_footer','cyon_cyon_blog_js_css',10);
		ob_get_clean();
	}
	wp_reset_query();
	return '<ul class="'.$class.'">'.$result.'</ul>';
}
add_shortcode('blog','cyon_blog'); 

function cyon_cyon_blog_js_css(){
		global $blog_cols;
?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('.postlist > li:nth-of-type(<?php echo $blog_cols; ?>n+1)').addClass('first-child');
			});
		</script>
<?php }

/* =Sitemap
use [sitemap]
----------------------------------------------- */
function cyon_sitemap( $atts, $content = null ) {
	$html = '<div class="cyon-sitemap row-fluid">';
	$locations = get_nav_menu_locations();
	$footer_id = wp_get_nav_menu_object( $locations['footer-menu'] );
	$footer_items = wp_get_nav_menu_items( $footer_id->term_id );
	$header_id = wp_get_nav_menu_object( $locations['main-menu'] );
	$header_items = wp_get_nav_menu_items( $header_id->term_id );
	$html .= '<div class="span4">';
			if($header_id->term_id!=''){
				$html .= '<h3>'.__('Main Menu','cyon').':</h3>'.wp_nav_menu(array('menu'=>$header_id->term_id,'container'=>'','echo'=>false));
			}else{
				$html .= '<h3>'.__('Pages','cyon').':</h3><ul class="menu">'.wp_list_pages(array('title_li'=>'','echo'=>false)).'</ul>';
			}
			if($footer_id->term_id!='' && $header_id->term_id!=''){
				$html .= '<h3>'.__('Footer Menu','cyon').':</h3><ul class="menu">';
				foreach ( (array) $footer_items as $key => $footer_item ) {
					$html .= '<li><a href="'.$footer_item->url.'" title="'.$footer_item->title.'">'.$footer_item->title.'</a></li>';
				}
				$html .= '</ul>';
			}
	$html .= '</div>';
	$html .= '<div class="span4">
				<h3>'.__('Blog Categories','cyon').':</h3><ul class="menu">'.wp_list_categories(array('show_count'=>1,'echo'=>false,'title_li'=>'','feed'=>_('feed'))).'</ul>
				<h3>'.__('Blog Archives','cyon').':</h3><ul class="menu">'.wp_get_archives(array('show_post_count'=>true,'echo'=>false)).'</ul>';
			if(class_exists( 'Woocommerce' )) {
				$html .= '<h3>'.__('Product Categories','cyon').':</h3><ul class="menu">'.wp_list_categories(array('show_count'=>1,'echo'=>false,'taxonomy'=>'product_cat','title_li'=>'','feed'=>_('feed'))).'</ul>';
			}
	$recent_posts = wp_get_recent_posts(array('numberposts'=>50));
	$html .= '</div><div class="span4">
				<h3>'.__('Blog Posts').':</h3><ul class="menu">';
				foreach( $recent_posts as $recent ){
					$html .= '<li><a href="'.get_permalink($recent['ID']).'" title="'.esc_attr($recent['post_title']).'">'.$recent['post_title'].'</a></li>';
				}
	$html .= '</ul></div>';
	$html .= '</div>';
	return $html;
}
add_shortcode('sitemap','cyon_sitemap'); 


/* =Testimonials
use [testimonials id='' style='' classname='']
----------------------------------------------- */
function cyon_testimonial( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			id		=> '',
			classname => '',
			style	=> 'list' // list, slide
		), $atts);
	global $data;
	$testimonials = $data['testimonials'];
	$html = '';

	if(count($testimonials)>0){
		$html .= '<div class="cyon-testimonial '.$atts['classname'].'">';
		if($atts['style']=='slide'){
			$html .= '<div class="swiper-container"><a class="swiper-left" href="#"><span class="icon-chevron-left"></span></a><a class="swiper-right" href="#"><span class="icon-chevron-right"></span></a><div class="swiper-pager"></div><div class="swiper-wrapper">';
		}else{
			$html .= '<ul>';
		}
		foreach ($testimonials as $testimonial) {
			if($atts['style']=='slide'){
				$html .= '<div class="swiper-slide">';
			}else{
				$html .= '<li>';
			}
			$html .= '<blockquote class="clearfix"><div class="icon-quote-left"></div>'.$testimonial['description'].'<div class="bubble"></div></blockquote><div class="name clearfix">';
			$html .= $testimonial['url']!='' ? '<img src="'.$testimonial['url'].'" alt="'.$testimonial['title'].'" />' : '';
			$html .= '<h4>'.$testimonial['title'].'</h4>';
			$html .= $testimonial['company']!='' ? '<p>'.$testimonial['company'].'</p>' : '';
			$html .= '</div>';
			if($atts['style']=='slide'){
				$html .= '</div>';
			}else{
				$html .= '</li>';
			}
		}
		$html .= '</ul>';
		if($atts['style']=='slide'){
			$html .= '</div></div>';
		}else{
			$html .= '</ul>';
		}
		$html .= '</div>';
	}

	return $html;
}
add_shortcode('testimonials','cyon_testimonial'); 
