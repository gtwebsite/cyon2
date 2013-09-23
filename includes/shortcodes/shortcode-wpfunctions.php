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
			id 			=> get_the_ID(),
			style		=> 'list'
		), $atts);
	static $instance = 0;
	$instance++;
	$args = array(
		'sort_order' 	=> 'ASC',
		'sort_column' 	=> 'menu_order',
		'child_of' 		=> $atts['id'],
		'parent'		=>  $atts['id']
	);
	$subpages = get_pages($args);
	$result = '';
	$class = 'subpages';
	if($atts['classname']){
		$class .= ' '.$atts['classname'];
	}
	if($atts['cols']>1 && $atts['style']=='list'){
		global $subpage_cols;
		$subpage_cols = $atts['cols'];
		$cols = 12 / $atts['cols'];
		$classli = ' class="span'.$cols.'"';
		$wrapper = '<li class="span'.$cols.'">';
		$wrapper_end = '</li>';
		$class .= ' row-fluid';
		$result .= '<ul class="'.$class.'">';
	}elseif($atts['style']=='slide'){
		$result .= '<div class="carousel '.$class.'" id="subpages-'.$instance.'"><a class="swiper-left" href="#"><span class="icon-chevron-left"></span></a><a class="swiper-right" href="#"><span class="icon-chevron-right"></span></a><div class="swiper-container"><div class="swiper-wrapper">';
		$wrapper = '<div class="swiper-slide"><div class="swiper-slide-wrapper">';
		$wrapper_end = '</div></div>';
	}
	foreach ( $subpages as $page ) {
		$result .= $wrapper;
		if($atts['thumbnail']=='yes'){
			$result .= '<div class="page-thumb"><a href="' . get_page_link( $page->ID ) . '">'.get_the_post_thumbnail( $page->ID, $data['content_thumbnail_size'] ).'</a></div>';
		}
		$result .= '<h4><a href="' . get_page_link( $page->ID ) . '">' . $page->post_title . '</a></h4>';
		if($atts['excerpt']=='yes'){
			$result .= do_shortcode($page->post_excerpt);
		}
		$result .= $wrapper_end;
	}
	if($atts['cols']>1 && $atts['style']=='list'){
		$result .= '</ul>';
		ob_start();
			add_action('wp_footer','cyon_subpages_js_css',10);
		ob_get_clean();
	}elseif($atts['style']=='slide'){
		$result .= '</div></div></div>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				var cyonSubpages'.$instance.' = jQuery(\'#subpages-'.$instance.' .swiper-container\').swiper({
					loop: true,
					slidesPerGroup: '.$atts['cols'].',
					slidesPerView: '.$atts['cols'].',
					calculateHeight: true,
					onSlideChangeEnd: function(){
						jQuery(window).trigger(\'scroll\');
					}
				});
				jQuery(\'#subpages-'.$instance.' .swiper-left\').on(\'click\', function(e){
					e.preventDefault();
					cyonSubpages'.$instance.'.swipePrev();
				})
				jQuery(\'#subpages-'.$instance.' .swiper-right\').on(\'click\', function(e){
					e.preventDefault();
					cyonSubpages'.$instance.'.swipeNext();
				})
			});
		</script>';
	}
	return $result;
}
add_shortcode('subpages','cyon_subpages'); 

function cyon_subpages_js_css(){
		global $subpage_cols;
?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('.subpages > li:nth-of-type(<?php echo $subpage_cols; ?>n+1)').addClass('first-child');
			});
		</script>
<?php }

/* =Show Blog
use [blog excerpt="yes" thumbnail="yes" cols="" items="4" cat_id="1" classname="" style="slide"]
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
			cat_id 		=> 1,
			style		=> 'list' // list, slide, masonry
		), $atts);
	static $instance = 0;
	$instance++;
	$args = array(
		'posts_per_page' 	=> $atts['items'],
		'category' 		=> $atts['cat_id']
	);
	$posts = get_posts($args);
	$result = '';
	$class = 'postlist';
	if($atts['classname']){
		$class .= ' '.$atts['classname'];
	}
	if($atts['cols']>1 && $atts['style']=='list'){
		global $blog_cols;
		$blog_cols = $atts['cols'];
		$cols = 12 / $atts['cols'];
		$wrapper = '<li class="span'.$cols.'">';
		$wrapper_end = '</li>';
		$class .= ' row-fluid';
		$result .= '<ul class="'.$class.'" id="bloglist-'.$instance.'">';
	}elseif($atts['style']=='slide'){
		$result .= '<div class="carousel '.$class.'" id="bloglist-'.$instance.'"><a class="swiper-left" href="#"><span class="icon-chevron-left"></span></a><a class="swiper-right" href="#"><span class="icon-chevron-right"></span></a><div class="swiper-container"><div class="swiper-wrapper">';
		$wrapper = '<div class="swiper-slide"><div class="swiper-slide-wrapper">';
		$wrapper_end = '</div></div>';
	}elseif($atts['style']=='masonry'){
		$wrapper = '<li class="article"><div class="article-wrapper">';
		$wrapper_end = '</div></li>';
		$result .= '<ul class="'.$class.'" id="bloglist-'.$instance.'">';
	}
	foreach ( $posts as $post ) {
		setup_postdata($post);
		$result .= $wrapper;
		if($atts['thumbnail']=='yes'){
			$result .= '<div class="entry-featured-image"><a href="' . get_page_link( $post->ID ) . '">'.get_the_post_thumbnail( $post->ID, $data['content_thumbnail_size'] ).'</a></div>';
		}
		$result .= '<h4><a href="' . get_page_link( $post->ID ) . '">' . $post->post_title . '</a></h4>
		<p class="meta"><span class="posted-date"><span class="posted-day">'.esc_html( get_the_time('d') ).'</span> <span class="posted-month">'.esc_html( get_the_time('M') ).'</span> <span class="posted-year">'.esc_html( get_the_time('Y') ).'</span></span></p>';
		
		if($atts['excerpt']=='yes'){
			if($post->post_excerpt){
				$result .= $post->post_excerpt;
			}else{
				$result .= get_the_excerpt();
			}
		}
		$result .= $wrapper_end;
	}
	if($atts['cols']>1 && $atts['style']=='list'){
		$result .= '</ul>';
		ob_start();
			add_action('wp_footer','cyon_blog_js_css',10);
		ob_get_clean();
	}elseif($atts['style']=='slide'){
		$result .= '</div></div></div>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				var cyonBloglist'.$instance.' = jQuery(\'#bloglist-'.$instance.' .swiper-container\').swiper({
					loop: true,
					slidesPerGroup: '.$atts['cols'].',
					slidesPerView: '.$atts['cols'].',
					calculateHeight: true,
					onSlideChangeEnd: function(){
						jQuery(window).trigger(\'scroll\');
					}
				});
				jQuery(\'#bloglist-'.$instance.' .swiper-left\').on(\'click\', function(e){
					e.preventDefault();
					cyonBloglist'.$instance.'.swipePrev();
				});
				jQuery(\'#bloglist-'.$instance.' .swiper-right\').on(\'click\', function(e){
					e.preventDefault();
					cyonBloglist'.$instance.'.swipeNext();
				});
			});
		</script>';
	}elseif($atts['style']=='masonry'){
		$result .= "</ul>
		<script type=\"text/javascript\">
			jQuery(window).load(function(){
				jQuery('#bloglist-".$instance."').imagesLoaded(function(){
					jQuery('#bloglist-".$instance."').isotope({
						itemSelector: 'li.article',
						animationOptions: {
							duration: 750,
							easing: 'linear',
							queue: false
						}
					});
					checkMasonryBloglist".$instance."();
					jQuery(window).trigger('scroll');
				});
			});
			function checkMasonryBloglist".$instance."() {
				var pagesize = jQuery('.page_wrapper').width();
				if (pagesize <= 480) {
					jQuery('#bloglist-".$instance." li.article').width(jQuery('#content').width());
				}else if (pagesize <= 974) {
					jQuery('#bloglist-".$instance." li.article').width((jQuery('#content').width() / 2)-2);
				}else{
					jQuery('#bloglist-".$instance." li.article').width((jQuery('#content').width() / ".$atts['cols'].")-3);
				}
				jQuery(window).trigger('scroll');
			}
			jQuery(window).resize(checkMasonryBloglist".$instance.");
			jQuery(window).scroll(function(){
				jQuery('#bloglist-".$instance."').isotope('reLayout');
			});
		</script>
		";
	}
	wp_reset_query();
	return $result;
}
add_shortcode('blog','cyon_blog'); 

function cyon_blog_js_css(){
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
use [testimonials style='' cols='' classname='']
----------------------------------------------- */
function cyon_testimonial( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			id			=> '',
			classname 	=> '',
			cols 		=> '1',
			style		=> 'list' // list, slide, masonry
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
		}elseif($atts['style']=='masonry'){
			$html .= '<ul id="testimonial-'.$instance.'">';
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
			}elseif($atts['style']=='masonry'){
				$html .= '<li class="article"><div class="article-wrapper">';
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
			}elseif($atts['style']=='masonry'){
				$html .= '</div></li>';
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
		}elseif($atts['style']=='masonry'){
			$html .= "</ul>
			<script type=\"text/javascript\">
				jQuery(window).load(function(){
					jQuery('#testimonial-".$instance."').imagesLoaded(function(){
						jQuery('#testimonial-".$instance."').isotope({
							itemSelector: 'li.article',
							animationOptions: {
								duration: 750,
								easing: 'linear',
								queue: false
							}
						});
						checkMasonryTestimonial".$instance."();
						jQuery(window).trigger('scroll');
					});
				});
				function checkMasonryTestimonial".$instance."() {
					var pagesize = jQuery('.page_wrapper').width();
					if (pagesize <= 480) {
						jQuery('#testimonial-".$instance." li.article').width(jQuery('#content').width());
					}else if (pagesize <= 974) {
						jQuery('#testimonial-".$instance." li.article').width((jQuery('#content').width() / 2)-2);
					}else{
						jQuery('#testimonial-".$instance." li.article').width((jQuery('#content').width() / ".$atts['cols'].")-3);
					}
					jQuery(window).trigger('scroll');
				}
				jQuery(window).resize(checkMasonryTestimonial".$instance.");
				jQuery(window).scroll(function(){
					jQuery('#testimonial-".$instance."').isotope('reLayout');
				});
			</script>";
		}else{
			$html .= '</ul>';
		}
		$html .= '</div>';
	}

	return $html;
}
add_shortcode('testimonials','cyon_testimonial'); 



/* =Team
use [cyon_team style='' cols='' classname='']
----------------------------------------------- */
function cyon_team( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			id			=> '',
			classname 	=> '',
			cols 		=> '1',
			style		=> 'list' // list, slide, masonry
		), $atts);
	global $data;
	static $instance = 0;
	$instance++;
	$team = $data['team'];
	$html = '';

	if(count($team)>0){
		$html .= '<div class="cyon-team '.$atts['classname'].'">';
		if($atts['style']=='slide'){
			$html .= '<div id="team-'.$instance.'"><div class="swiper-container"><div class="swiper-wrapper">';
		}elseif($atts['style']=='masonry'){
			$html .= '<ul id="team-'.$instance.'">';
		}else{
			if($atts['cols']>1){
				$html .= '<ul class="row-fluid">';
			}else{
				$html .= '<ul>';
			}
		}
		$count = 0;
		foreach ($team as $member) {
			if($atts['style']=='slide'){
				$html .= '<div class="swiper-slide"><div class="swiper-slide-wrapper">';
			}elseif($atts['style']=='masonry'){
				$html .= '<li class="article"><div class="article-wrapper">';
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
			
			$html .= $member['url']!='' ? '<div class="photo"><img src="'.$member['url'].'" alt="'.$member['title'].'" /></div>' : '';
			$html .= '<div class="name">';
			$html .= '<h4>'.$member['title'].'</h4>';
			$html .= $member['job']!='' ? '<div class="job">'.$member['job'].'</div>' : '';
			$html .= '</div>';
			$html .= $member['description']!='' ? '<p class="description">'.$member['description'].'</p>' : '';
			if($member['email'] || $member['facebook'] || $member['twitter'] || $member['g_plus'] || $member['linkedin']){
				$html .= '<div class="cyon-social">';
				$html .= $member['phone']!='' ? '<a href="tel:'.$member['phone'].'" class="hastip" title="'.__('Phone','cyon').'"><span class="icon-phone"></span><span class="icon-phone copy"></span></a>' : '';
				$html .= $member['email']!='' ? '<a href="mailto:'.$member['email'].'" class="hastip" title="'.__('Email','cyon').'"><span class="icon-envelope"></span><span class="icon-envelope copy"></span></a>' : '';
				$html .= $member['facebook']!='' ? '<a href="'.$member['facebook'].'" class="hastip" title="Facebook" target="_blank"><span class="icon-facebook"></span><span class="icon-facebook copy"></span></a>' : '';
				$html .= $member['twitter']!='' ? '<a href="'.$member['twitter'].'" class="hastip" title="Twitter" target="_blank"><span class="icon-twitter"></span><span class="icon-twitter copy"></span></a>' : '';
				$html .= $member['g_plus']!='' ? '<a href="'.$member['g_plus'].'" class="hastip" title="Google+" target="_blank"><span class="icon-google-plus"></span><span class="icon-facebook copy"></span></a>' : '';
				$html .= $member['linkedin']!='' ? '<a href="'.$member['linkedin'].'" class="hastip" title="Linkedin" target="_blank"><span class="icon-linkedin"></span><span class="icon-linkedin copy"></span></a>' : '';
				$html .= '</div>';
			}
			
			if($atts['style']=='slide'){
				$html .= '</div></div>';
			}elseif($atts['style']=='masonry'){
				$html .= '</div></li>';
			}else{
				$html .= '</li>';
			}
		}
		if($atts['style']=='slide'){
			$html .= '</div></div></div>
				<script type="text/javascript">
					jQuery(document).ready(function(){
						var cyonTeam'.$instance.' = jQuery(\'#team-'.$instance.' .swiper-container\').swiper({
							autoplay:Math.floor(Math.random() * 6000) + 4000,
							loop: true,
							calculateHeight: true,
							slidesPerGroup: '.$atts['cols'].',
							slidesPerView: '.$atts['cols'].',
							onSlideChangeEnd: function(){
								jQuery(window).trigger(\'scroll\');
							}

						});
					});
				</script>';
		}elseif($atts['style']=='masonry'){
			$html .= "</ul>
			<script type=\"text/javascript\">
				jQuery(window).load(function(){
					jQuery('#team-".$instance."').imagesLoaded(function(){
						jQuery('#team-".$instance."').isotope({
							itemSelector: 'li.article',
							animationOptions: {
								duration: 750,
								easing: 'linear',
								queue: false
							}
						});
						checkMasonryTeam".$instance."();
						jQuery(window).trigger('scroll');
					});
				});
				function checkMasonryTeam".$instance."() {
					var pagesize = jQuery('.page_wrapper').width();
					if (pagesize <= 480) {
						jQuery('#team-".$instance." li.article').width(jQuery('#content').width());
					}else if (pagesize <= 974) {
						jQuery('#team-".$instance." li.article').width((jQuery('#content').width() / 2)-2);
					}else{
						jQuery('#team-".$instance." li.article').width((jQuery('#content').width() / ".$atts['cols'].")-3);
					}
					jQuery(window).trigger('scroll');
				}
				jQuery(window).resize(checkMasonryTeam".$instance.");
				jQuery(window).scroll(function(){
					jQuery('#team-".$instance."').isotope('reLayout');
				});
			</script>";
		}else{
			$html .= '</ul>';
		}
		$html .= '</div>';
	}

	return $html;
}
add_shortcode('team','cyon_team'); 


