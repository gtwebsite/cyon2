<?php
if ( !defined('ABSPATH') )
	die('-1');


/* =Check for jQuery running
----------------------------------------------- */
if(!function_exists('cyon_check_jquery')) {
function cyon_check_jquery(){ ?>
	<script type="text/javascript">
		if (typeof jQuery == 'undefined') {  
			document.write('<?php __('Javascript is not running. This website requires javascript, please turn it on.', 'cyon'); ?>');
		}
	</script>
<?php } }

/* =Check if online
----------------------------------------------- */
if(!function_exists('cyon_check_offline')) {
function cyon_check_offline($template){
	global $wp_query, $data;
	if($data['site_offline_page']>0 && !is_user_logged_in()){
		include( THEME_DIR . '/offline.php');
		exit();
	}
} }

/* =Get all jQuery and CSS
----------------------------------------------- */
if(!function_exists('cyon_register_scripts_styles')) {
function cyon_register_scripts_styles(){
	global $data;
	wp_enqueue_script('cyon_jquery_all');
	wp_enqueue_script('isotope');
	wp_enqueue_script('cyon_jquery_custom');
	wp_enqueue_script('transit');
	if($data['responsive']==1){
		wp_enqueue_style('cyon_style_responsive'); 
		wp_enqueue_script('nonbounce'); 
	}
	if(((cyon_get_page_bg()!='' && $data['background_style_pattern_repeat']=='full'))){
		wp_enqueue_script('supersized');
	}
} }


/* =SEO Page Title
----------------------------------------------- */
if(!function_exists('cyon_wp_title')) {
function cyon_wp_title($title){
	global $post, $data;

	$BLOGTITLE = get_bloginfo( 'name' );
	$BLOGTAGLINE = get_bloginfo( 'description' );
	if($data['seo_activate']==1){
		if(is_page() || is_single()){
			if(get_post_meta($post->ID,'cyon_meta_title',true)!=''){
				$PAGETITLE = get_post_meta($post->ID,'cyon_meta_title',true);
			}else{
				$PAGETITLE = get_the_title($post->ID);
			}
		}else{
			$PAGETITLE = $title;
		}
		$filtered_title = preg_replace('/\{([A-Z]+)\}/e', '$$1', $data['seo_title_format']);
	}else{
		if(is_home() || is_front_page()){
			$filtered_title = $BLOGTITLE.' | '.$BLOGTAGLINE;
		}else{
			$filtered_title = $title.' | '.$BLOGTITLE;
		}
	}
	$filtered_title .= ( 2 <= $paged || 2 <= $page ) ? ' | ' . sprintf( __( 'Page %s' ), max( $paged, $page ) ) : '';
	return $filtered_title;
} }

/* =SEO Meta Info
----------------------------------------------- */
if(!function_exists('cyon_header_meta')) {
function cyon_header_meta(){
	global $post,$data;
	if($data['seo_activate']==1 && (is_page() || is_single())){
		echo get_post_meta($post->ID,'cyon_meta_desc',true)!='' ? '<meta name="description" content="'.get_post_meta($post->ID,'cyon_meta_desc',true).'" />' : '<meta name="description" content="'.get_the_excerpt().'" />';
		echo get_post_meta($post->ID,'cyon_meta_keywords',true)!='' ? '<meta name="keywords" content="'.get_post_meta($post->ID,'cyon_meta_keywords',true).'" />' : '';
		if(get_post_meta($post->ID,'cyon_robot',true)){
			add_action('wp_head','wp_no_robots');
		}
	}
} }


/* =Header Scripts
----------------------------------------------- */
if(!function_exists('cyon_header_scripts')) {
function cyon_header_scripts(){
	global $data;
	echo $data['header_scripts']."\n";
} }

/* =Load Header Styles
----------------------------------------------- */
if(!function_exists('cyon_header_styles')) {
function cyon_header_styles(){
	global $data; 
	
	echo "\n";
	
	
	/* Getting fonts we want */
	$google_fonts = array();
	
	/* Getting Primary font */
	if($data['primary_font']['face']=='google_font'){
		$primary_font_face = '\''.$data['primary_font']['google'].'\', sans-serif';
		$google_fonts[] = $data['primary_font']['google'].':'.$data['primary_font']['googlew'].$data['primary_font']['style'];
	}else{
		$primary_font_face = $data['primary_font']['face'];
	}
	
	/* Getting Secondary font */
	if($data['secondary_font']['face']=='google_font'){
		$secondary_font_face = '\''.$data['secondary_font']['google'].'\', sans-serif';
		$google_fonts[] = $data['secondary_font']['google'].':'.$data['secondary_font']['googlew'].$data['primary_font']['style'];
	}else{
		$secondary_font_face = $data['secondary_font']['face'];
	}

	/* Getting Main Navigation font */
	if($data['menu_font']['face']=='google_font'){
		$menu_font_face = '\''.$data['menu_font']['google'].'\', sans-serif';
		$google_fonts[] = $data['menu_font']['google'].':'.$data['menu_font']['googlew'].$data['primary_font']['style'];
	}else{
		$menu_font_face = $data['menu_font']['face'];
	}
	
	/* Fetching Google Fonts */
	$google_fonts = array_unique($google_fonts);
	if(count($google_fonts)>0){
		echo '<!-- Getting Google Fonts by Cyon Theme -->'."\n";
		echo '<link rel="stylesheet" type="text/css" media="all" href="http://fonts.googleapis.com/css?family=';
		foreach($google_fonts as $google_font){
			echo str_replace(' ','+',$google_font).'|';
		}
		echo '" />'."\n";
	}

	/* Getting background we want */
	$background_pattern = '';
	if(cyon_get_page_bg()!='' && $data['background_style_pattern_repeat']!='full'){
		$background_pattern = ' background-image:url('.cyon_get_page_bg().'); background-repeat:'.$data['background_style_pattern_repeat'].'; background-position:'.$data['background_style_pattern_position'].';';
	}
	if($data['background_style_pattern_repeat']=='full'){
		$background_element = 'body';
	}else{
		$background_element = '#page';
	}

	?>
	<?php if($data['iosicon']!=''){ ?><link rel="apple-touch-icon" href="<?php echo $data['iosicon']; ?>" /><?php echo "\n"; } ?>
	<?php if($data['favicon']!=''){ ?><link rel="shortcut icon" href="<?php echo $data['favicon']; ?>" /><?php } ?>

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="<?php if($data['responsive']==1){ echo apply_filters('cyon_viewport','width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0'); }else{ echo apply_filters('cyon_viewport','width=1024'); } ?>" />
	<?php if($data['responsive']==1){ ?><meta name="apple-mobile-web-app-capable" content="yes" /><meta name="apple-mobile-web-app-status-bar-style" content="black" /> <?php echo "\n"; } ?>
	<meta name="application-name" content="<?php echo get_bloginfo( 'name' ); ?>"/>
	<?php if($data['background_color']){ ?><meta name="msapplication-TileColor" content="<?php echo $data['background_color']; ?>" /><?php } ?>
	<?php if(cyon_get_page_bg()){ ?><meta name="msapplication-TileImage" content="<?php echo cyon_get_page_bg(); ?>"/><?php } ?>

	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo THEME_ASSETS_URI; ?>css/<?php echo $data['theme_color']; ?>" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_ASSETS_URI; ?>css/<?php echo $data['theme_gutter']; ?>" />

	<!--[if lte IE 8]>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_ASSETS_URI; ?>css/style-ie8.css" />
	<![endif]-->
	<!--[if lte IE 7]>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_ASSETS_URI; ?>css/style-ie7.css" />
	<![endif]-->

	<!-- Styles generated by Cyon Theme -->
	<style type="text/css">
		body {
			font-family:<?php echo $primary_font_face; ?>; font-size:<?php echo $data['primary_font']['size']; ?>; font-style:<?php echo $data['primary_font']['style']; ?>; font-weight:<?php echo $data['primary_font']['googlew']; ?>; color:<?php echo $data['primary_font']['color']; ?>;
		}
		<?php echo $background_element; ?> {
			 background-color:<?php echo $data['background_color'] ?>;<?php echo $background_pattern."\n"; ?>
		}
		h1, h2, h3 {
			font-family:<?php echo $secondary_font_face; ?>; color:<?php echo $data['secondary_font']['color']; ?>; font-style:<?php echo $data['secondary_font']['style']; ?>; font-weight:<?php echo $data['secondary_font']['googlew']; ?>;
		}
		#access ul.menu > li > a {
			font-family:<?php echo $menu_font_face; ?>; font-size:<?php echo $data['menu_font']['size']; ?>; font-style:<?php echo $data['menu_font']['style']; ?>; color:<?php echo $data['menu_font']['color']; ?>; font-weight:<?php echo $data['menu_font']['googlew']; ?>;
		}
	</style>	
	<?php echo "\n"; ?>
<?php } }


/* =Header Layout
----------------------------------------------- */
if(!function_exists('cyon_header_hook')) {
function cyon_header_hook(){
	global $data;  ?>
	<!-- Header -->
	<header id="branding" role="banner" class="<?php echo $data['header_layout']; ?>">
		<div class="wrapper clearfix">

			<!-- Screen Readers -->
			<ul class="skip-link hide-text">
				<li><a href="#primary" title="<?php esc_attr_e( 'Skip to primary content', 'cyon' ); ?>"><?php _e( 'Skip to primary content', 'cyon' ); ?></a></li>
				<li><a href="#secondary" title="<?php esc_attr_e( 'Skip to secondary content', 'cyon' ); ?>"><?php _e( 'Skip to secondary content', 'cyon' ); ?></a></li>
			</ul>
			
			<?php cyon_header_wrapper(); ?>
			
		</div>
	</header>
<?php } }


/* =Header Columns
----------------------------------------------- */
if(!function_exists('cyon_header_columns')) {
function cyon_header_columns(){
	global $data;
	if($data['top_left_content'] || $data['top_right_content']){ ?>
	<!-- Top Contents -->
	<div id="top"<?php if($data['top_left_content'] && $data['top_right_content']){ ?> class="row-fluid"<?php } ?>>
		<?php if($data['top_left_content']){ ?>
		<?php if($data['top_left_content'] && $data['top_right_content']){ ?><div class="span6"><?php } ?>
			<p><?php echo do_shortcode($data['top_left_content']); ?></p>
		<?php if($data['top_left_content'] && $data['top_right_content']){ ?></div><?php } ?>
		<?php } ?>
		<?php if($data['top_right_content']){ ?>
		<?php if($data['top_left_content'] && $data['top_right_content']){ ?><div class="span6 right"><?php } ?>
			<p><?php echo do_shortcode($data['top_right_content']); ?></p>
		<?php if($data['top_left_content'] && $data['top_right_content']){ ?></div><?php } ?>
		<?php } ?>
	</div>
	<?php }
} }


/* =Logo / Sitename
----------------------------------------------- */
if(!function_exists('cyon_header_logo')) {
function cyon_header_logo(){
	global $data;  ?>
	<!-- Logo / Site Name -->
	<hgroup>
		<?php if($data['header_logo']!=''){ ?>
		<h1 id="site-title"><span><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo $data['header_logo']; ?>" /></a></span></h1>
		<?php }else{ ?>
		<h1 id="site-title"><span><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span></h1>
		<?php } ?>
		<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
	</hgroup> <?php
} }


/* =Main Navigation
----------------------------------------------- */
if(!function_exists('cyon_header_mainnav')) {
function cyon_header_mainnav(){ ?>
	<!-- Main Menu -->
	<nav id="access" class="clearfix" role="navigation">
		<h3 class="assistive-text hide-text"><?php _e( 'Main menu', 'cyon' ); ?></h3>
		<?php
		$locations = get_nav_menu_locations();
		$header_id = wp_get_nav_menu_object( $locations['main-menu'] );
		if($header_id->term_id!=''){
			wp_nav_menu(array('menu'=>$header_id->term_id,'container'=>'','menu_class'=>'menu clearfix'));
		}else{
			echo '<ul class="menu clearfix">'.wp_list_pages(array('title_li'=>'','echo'=>false)).'</ul>';
		}
		?>
	</nav> <?php
} }

/* =Body Layout
----------------------------------------------- */
if(!function_exists('cyon_body_hook')) {
function cyon_body_hook(){ ?>
		<!-- Body -->
		<div id="main" class="<?php echo cyon_get_page_layout(); ?>">
			<div class="wrapper clearfix">
				<?php cyon_body_wrapper(); ?>
			</div>
		</div>
<?php } }


/* =Primary Layout
----------------------------------------------- */
if(!function_exists('cyon_primary_hook')) {
function cyon_primary_hook(){ ?>
	<!-- Center -->
	<div id="primary">
		<?php cyon_primary(); ?>
	</div>
<?php } }


/* =Primary Contents
----------------------------------------------- */
if(!function_exists('cyon_primary_content')) {
function cyon_primary_content(){ ?>
	<div id="content" class="clearfix<?php if (cyon_get_list_layout()!=1 && !get_tax_meta(cyon_get_term_id(),'cyon_cat_layout_masonry') ) { echo ' row-fluid'; } ?>" role="main">
	<?php
		if ( have_posts() ) {
			while ( have_posts() ) : the_post();
				if(is_front_page() && get_option('show_on_front', true) == 'page'){
					cyon_home_content();
				}else{ ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>><div class="article-wrapper">
						<header class="page-header">
							<?php cyon_post_header(); ?>
						</header>
						<div class="page-content clearfix">
							<?php cyon_post_content(); ?>
						</div>
						<footer class="entry-meta">
							<?php cyon_post_footer(); ?>
							<?php edit_post_link( __( 'Edit', 'cyon' ), '<span class="edit-link">', '</span>' ); ?>
						</footer>
					</div></article>
				<?php }
			endwhile;
		}else{
	?>
		<article id="post-0" class="post no-results not-found"><div class="article-wrapper">
			<header class="page-header">
				<h1 class="page-title"><?php echo apply_filters('the_title',__( 'Nothing Found', 'cyon' )); ?></h1>
			</header>
			<div class="page-content">
				<?php echo apply_filters('the_content',__( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'cyon' )); ?>
			</div>
		</div></article>

	<?php } wp_reset_postdata(); ?>
	</div>
<?php  } }


/* =Primary Post Header Title
----------------------------------------------- */
if(!function_exists('cyon_post_header_title')) {
function cyon_post_header_title(){ ?>
	<?php if(is_single() || is_page()){ ?>
		<h1 class="page-title"><?php the_title(); ?></h1>
	<?php }else{ ?>
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
	<?php } ?>
<?php } }


/* =Primary Post Header Meta
----------------------------------------------- */
if(!function_exists('cyon_post_header_meta')) {
function cyon_post_header_meta(){
	global $data;
	if(is_single() || is_home() || is_archive() ){
		echo '<p class="meta">';
			echo '<span class="posted-date">';
				echo '<span class="posted-day">'.esc_html( get_the_time('d') ).'</span> ';
				echo '<span class="posted-month">'.esc_html( get_the_time('M') ).'</span> ';
				echo '<span class="posted-year">'.esc_html( get_the_time('Y') ).'</span>';
			echo '</span> ';
			echo '<span class="posted-by">'.__('by','cyon').' <a href="'.esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ).'">'.get_the_author().'</a></span> ';
			if(count(get_the_category())>1){
				$catps = 'ies';
			}else{
				$catps = 'y';
			}
			echo '<span class="categories-links">'.__('in categor'.$catps).' '.get_the_category_list( __( ', ', 'cyon' ) ).'</span> ';
			if(get_the_tag_list()){
				echo '<span class="tag-links">'.__('and tagged','cyon').' '.get_the_tag_list( '', __( ', ', 'cyon' ) ).'</span> ';
			}
			if($data['content_comment']['posts']==1) {
				$comments = wp_count_comments(get_the_ID());
				if($comments->approved==0){
					echo '<span class="comments"> | '.__('Be the first to','cyon').' <a href="'.get_permalink().'#respond">'.__('comment here','cyon').'</a>.</span>';
				}elseif($comments->approved==1){
					echo '<span class="comments">'.__('with','cyon').' <a href="'.get_permalink().'#comments">'.$comments->approved.' '.__('comment','cyon').'</a></span>';
				}else{
					echo '<span class="comments">'.__('with','cyon').' <a href="'.get_permalink().'#comments">'.$comments->approved.' '.__('comments)','cyon').'</a></span>';
				}
			}
		echo '</p>';
	}
} }


/* =Primary Post Content
----------------------------------------------- */
if(!function_exists('cyon_post_content_main')) {
function cyon_post_content_main(){
	global $data;
	if(is_page() || is_single()){
		the_content();
	}else{
		if($data['content_blog_post']=='full'){
			the_content();
		}else{
			the_excerpt();
		}
	}
} }


if(!function_exists('cyon_related_posts')) {
function cyon_related_posts() {
	global $data;
	$posttags = get_the_tags();
	
	if ($posttags && is_single()) {
		$tags = '';
		$count=1;
		foreach($posttags as $tag) {
			if (1 == $count) {
				$plus = '';
			}else{
				$plus = ',';
			}
			$tags .= $plus . $tag->name ; 
			$count++;
		}
		$query  = new WP_Query( 'posts_per_page=5&tag='.$tags );
		if ( $query->have_posts() && $data['content_related_posts']==1 ) {
			echo '<div class="related-posts"><h3>'.__('Related Posts','cyon').'</h3><ul class="list">';
			while ( $query->have_posts() ) {
				$query->the_post();
				echo '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
			}
			echo '</ul></div>';
		}
	}
} }


/* =Primary Archive Header
----------------------------------------------- */
if(!function_exists('cyon_primary_archive_header')) {
function cyon_primary_archive_header(){ ?>
	<?php if ( have_posts() ) { ?>
		<?php if ( is_archive() ) { ?>
			<header class="category-header">
				<h1 class="category-title">
					<?php if ( is_day() ) : ?>
						<?php printf( __( 'Daily Archives: %s', 'cyon' ), '<span>' . get_the_date() . '</span>' ); ?>
					<?php elseif ( is_month() ) : ?>
						<?php printf( __( 'Monthly Archives: %s', 'cyon' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'cyon' ) ) . '</span>' ); ?>
					<?php elseif ( is_year() ) : ?>
						<?php printf( __( 'Yearly Archives: %s', 'cyon' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'cyon' ) ) . '</span>' ); ?>
					<?php elseif ( is_author() ) : ?>
						<?php if ( have_posts() ) : ?>
							<?php the_post(); ?>
						<?php endif; ?>
						<?php printf( __( 'Author Archives: %s', 'cyon' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' ); ?>
						<?php rewind_posts(); ?>
					<?php elseif ( is_tag() ) : ?>
						<?php printf( __( 'Posts Tagged: %s', 'cyon' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'cyon' ) ) . '</span>' ); ?>
					<?php else : ?>
						<?php printf( __( 'Category Archives: %s', 'cyon' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?>
					<?php endif; ?>
				</h1>
				<?php
					$category_meta = '';
					$category_image = get_tax_meta(cyon_get_term_id(),'cyon_cat_image');
					if ( ! empty( $category_image['id'] ) )
						$category_meta .= apply_filters( 'cyon_category_archive_image', wp_get_attachment_image( $category_image['id'], 'large' ) );
					$category_description = category_description();
					if ( ! empty( $category_description ) )
						$category_meta .= apply_filters( 'cyon_category_archive_meta', $category_description );
					if( !empty($category_meta))
						echo '<div class="category-archive-meta">'.$category_meta.'</div>';
				?>
			</header>
		<?php }elseif(is_search()){ ?>
			<header class="category-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'cyon' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header>
		<?php }elseif(is_home() && get_option('page_for_posts', true)){ ?>
			<header class="category-header">
				<?php $post = get_page(get_option('page_for_posts', true));	?>
				<h1 class="page-title"><?php echo apply_filters('the_title', $post->post_title); ?></h1>
				<div class="category-archive-meta"><?php echo apply_filters( 'cyon_category_archive_meta', apply_filters('the_content', $post->post_content) ); ?></div>
			</header>
		<?php } ?>
	<?php } ?>
<?php } }


/* =Secondary Layout
----------------------------------------------- */
if(!function_exists('cyon_secondary_hook')) {
function cyon_secondary_hook(){ ?>
	<?php if(cyon_get_page_layout()!='general-1column'){ ?>
	<!-- Secondary -->
	<div id="secondary" class="widget-area" role="complementary">
		<?php cyon_secondary(); ?>
	</div>
	<?php } ?>
<?php } }

/* =Secondary Contents
----------------------------------------------- */
if(!function_exists('cyon_secondary_content')) {
function cyon_secondary_content(){
	if(cyon_get_page_layout()=='general-2right'){
		dynamic_sidebar( 'right-sidebar' );
	}elseif(cyon_get_page_layout()=='general-2left'){
		dynamic_sidebar( 'left-sidebar' );
	}
} }


/* =Breadcrumbs
----------------------------------------------- */
if(!function_exists('cyon_breadcrumb')) {
function cyon_breadcrumb() {
	global $data;
	if (!is_front_page() && $data['breadcrumbs']==1) {
		if(function_exists('bcn_display')){
			// Support for Breacrumb NavXT
			?> <div id="breadcrumb" class="clearfix"> <?php
			if(function_exists('bcn_display')){
				bcn_display();
			}
			?> </div> <?php
		}else{
			$pretext = '<span class="pretext">&raquo;</span>';
			echo '<dl id="breadcrumb" class="clearfix" itemprop="breadcrumb">';
			echo '<dt>'.__('You are here').':</dt>';
			echo '<dd><a href="'.get_option('home').'">'.__('Home').'</a></dd>';
			if (is_category() || is_single()) {
				$cat_title = get_the_category();
				$post_type = get_post_type_object(get_post_type());
				if(is_category()){
					echo '<dd>'.$pretext.' '.$cat_title[0]->cat_name.'</dd>';
				}elseif($cat_title[0]->cat_name=='') {
					echo '<dd>'.$pretext.' <a href="'.get_post_type_archive_link($post_type->name).'">'.$post_type->labels->name.'</a></dd>';
				}else{
					echo '<dd>'.$pretext.' <a href="'.get_category_link( $cat_title[0]->cat_ID).'">'.$cat_title[0]->cat_name.'</a></dd>';
				}
				if (is_single()) {
					echo '<dd>'.$pretext.' '.get_the_title().'</dd>';
				}
			} elseif (is_post_type_archive()) {
					echo '<dd>'.$pretext.' '.post_type_archive_title('', false).'</dd>';
			} elseif (is_page()) {
				$page=get_page_by_title(get_the_title());
				if($page->post_parent!=0){
					$parent_id  = $page->post_parent;
					$breadcrumbs = array();
					while ($parent_id) {
						$spage = get_page($parent_id);
						$breadcrumbs[] = '<dd>'.$pretext.' <a href="' . get_permalink($spage->ID) . '">' . get_the_title($spage->ID) . '</a></dd>';
						$parent_id  = $spage->post_parent;
					}
					$breadcrumbs = array_reverse($breadcrumbs);
					for ($i = 0; $i < count($breadcrumbs); $i++) {
						echo $breadcrumbs[$i];
					}
					//echo '<dd>'.$pretext.' <a href="'.get_permalink($page->post_parent).'">'.get_the_title($page->post_parent).'</a></dd>';
				}
				echo '<dd>'.$pretext.' '.get_the_title().'</dd>';
			}elseif (is_home()){
				echo '<dd>'.$pretext.' '.get_the_title(get_option('page_for_posts', true)).'</dd>';
			}elseif (is_front_page()){
				echo '<dd>'.$pretext.' '.get_the_title(get_option('page_for_posts', true)).'</dd>';
			}elseif (is_search()){
				echo '<dd>'.$pretext.' '.__('Search results for','cyon').': '.get_search_query().'</dd>';
			}elseif (is_day()){
				echo '<dd>'.$pretext.' <a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></dd>';
				echo '<dd>'.$pretext.' <a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a></dd>';
				echo '<dd>'.$pretext.' '.get_the_time('d').'</dd>';
			}elseif (is_month()){
				echo '<dd>'.$pretext.' <a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></dd>';
				echo '<dd>'.$pretext.' '.get_the_time('F').'</dd>';
			}elseif (is_year()){
				echo '<dd>'.$pretext.' '.get_the_time('Y').'</dd>';
			}elseif (is_author()){
				global $author;
				$userdata = get_userdata($author);
				echo '<dd>'.$pretext.' '.__('Articles posted by','cyon').': '.$userdata->display_name.'</dd>';
			}elseif (is_tag()){
				echo '<dd>'.$pretext.' '.__('Posts tagged','cyon').': '.single_tag_title('', false).'</dd>';
			}elseif (is_tax()){
				$current_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				$ancestors = array_reverse( get_ancestors( $current_term->term_id, get_query_var( 'taxonomy' ) ) );
				foreach ( $ancestors as $ancestor ) {
					$ancestor = get_term( $ancestor, get_query_var( 'taxonomy' ) );
					echo '<dd>'.$pretext.' <a href="'.get_term_link( $ancestor->slug, get_query_var( 'taxonomy' ) ).'">'.esc_html( $ancestor->name ).'</a></dd>';
				}
				echo '<dd>'.$pretext.' '.esc_html( $current_term->name ).'</dd>';
			}elseif (is_404()){
				echo '<dd>'.$pretext.' '.__('Error 404','cyon').'</dd>';
			}
			echo '</dl>';
		}
	}
} }


/* =Comments
----------------------------------------------- */
if(!function_exists('cyon_post_comments')) {
function cyon_post_comments(){
	global $data;
	if($data['content_comment']['posts']==1 && is_single()){
		comments_template( '', true );
	}
	if($data['content_comment']['pages']==1 && is_page()){
		comments_template( '', true );
	}
} }


/* =Social sharing
----------------------------------------------- */
if(!function_exists('cyon_socialshare')) {
function cyon_socialshare() {
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
	if(($social['posts']==1 && (is_single())) || ($social['listings']==1 && (is_category() || is_home() || is_archive()))){
		echo '<p class="share">'.$socialboxes.'</p>';
		add_action ('wp_footer','cyon_social_js',10);
	}
	if($social['pages']==1 && is_page()){
		echo '<p class="share">'.$socialboxes.'</p>';
		add_action ('wp_footer','cyon_social_js',10);
	}
} }

if(!function_exists('cyon_social_js')) {
function cyon_social_js(){ ?>
	<script type="text/javascript">var switchTo5x=true;</script>
	<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
	<script type="text/javascript">stLight.options({publisher: "32ae9ff6-93e1-4428-9772-72735858587d"});</script>
<?php } }


/* =Read more
----------------------------------------------- */
if(!function_exists('cyon_readmore')) {
function cyon_readmore() {
	global $data;
	if((is_archive() || is_home()) && $data['content_blog_post']=='excerpt'){
	 ?>
		<p class="readmore"><a href="<?php the_permalink(); ?>"><?php _e('Read more','cyon'); ?></a></p>
<?php	}
} }


/* =Author
----------------------------------------------- */
if(!function_exists('cyon_author')) {
function cyon_author(){
	global $data;
	if($data['content_author']==1 && is_single()) { 
		if ( get_the_author_meta( 'description' ) && ( ! function_exists( 'is_multi_author' ) || is_multi_author() ) ) { // If a user has filled out their description and this is a multi-author blog, show a bio on their entries ?>
		<div id="author-info">
			<div id="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), '68' ); ?>
			</div>
			<div id="author-description">
				<h2><?php printf( __( 'About %s', 'cion' ), get_the_author() ); ?></h2>
				<?php the_author_meta( 'description' ); ?>
				<div id="author-link">
					<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author" class="button">
						<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'cion' ), get_the_author() ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php }
	}
} }


/* =Homepage Block
----------------------------------------------- */
if(!function_exists('cyon_homepage_blocks')) {
function cyon_homepage_blocks(){
	global $data;
	$layout = $data['homepage_blocks']['enabled'];
	if ($layout){
		foreach ($layout as $key=>$value) {
			switch($key) {
				case 'home_block_slider':
					cyon_home_block_slider();
					break;
				case 'home_block_page':
					cyon_home_block_page();
					break;
				case 'home_block_bucket':
					cyon_home_block_bucket();
					break;
				case 'home_block_static':
					cyon_home_block_static();
					break;
				case 'home_block_blog':
					cyon_home_block_blog();
					break;
			}
		}
	}
} }


/* =Homepage Block Slider
----------------------------------------------- */
if(!function_exists('cyon_home_block_slider')) {
function cyon_home_block_slider(){
	global $data;
	$slides = $data['homepage_slider'];
	if(count($slides)>1){
		echo '<div class="block swiper" id="slider-block"><a class="swiper-left" href="#"><span class="icon-chevron-left"></span></a><a class="swiper-right" href="#"><span class="icon-chevron-right"></span></a><div class="swiper-pager"></div><div class="swiper-container"><div class="swiper-wrapper">';
		foreach ($slides as $slide) {
			if($slide['url']!=''){
				echo '<div class="swiper-slide">';
				echo $slide['link']!='' ? '<a href="'.$slide['link'].'">' : '';
				echo '<img src="'.$slide['url'].'" alt="'.$slide['title'].'" />';
				echo $slide['link']!='' ? '</a>' : '';
				if($slide['description']!=''){
					echo '<div class="swiper-caption">';
					echo '<h3 class="swiper-title">'.$slide['title'].'</h3><div class="swiper-content">'.apply_filters('the_content',$slide['description']).'</div>';
					echo $slide['link']!='' ? '<p class="readmore"><a href="'.$slide['link'].'">'.__('Read more').'</a></p>' : '';
					echo '</div>';
				}
				echo '</div>';
			}
		}
		echo '</div></div></div>';
	}else{
		if($slides[1]['url']!=''){
			echo '<div class="block" id="slider-block">';
			echo $slide['link']!='' ? '</a>' : '';
			echo '<img src="'.$slide[1]['url'].'" alt="'.$slide[1]['title'].'" />';
			echo $slide['link']!='' ? '</a>' : '';
			echo '</div>';
		}
	}
} }

/* =Homepage Block Page
----------------------------------------------- */
if(!function_exists('cyon_home_block_page')) {
function cyon_home_block_page(){
	$page = get_post(get_option('page_on_front', true)); ?>
	<article class="block page type-page" id="page-block"><div class="block-wrapper article-wrapper">
		<header class="page-header">
			<h1 class="paget-title"><?php echo apply_filters('the_title',$page->post_title); ?></h1>
		</header>
		<div class="page-content clearfix">
			<?php echo apply_filters('the_content',$page->post_content); ?>
		</div>
	</div></article>
<?php } }

/* =Homepage Block Bucket
----------------------------------------------- */
if(!function_exists('cyon_home_block_bucket')) {
function cyon_home_block_bucket(){
	global $data;
	if (is_active_sidebar('home-columns')){
		$class='';
		if($data['homepage_bucket_layout']!='bucket-1column'){
			$class=' row-fluid';
		} ?>
		<div class="block" id="static-block">
			<?php echo $data['homepage_bucket_title'] ? '<h2 class="block-title">'.$data['homepage_bucket_title'].'</h2>' : ''; ?>
			<div class="block-wrapper<?php echo $class; ?>">
				<?php dynamic_sidebar( 'home-columns' ); ?>
			</div>
		</div>
	<?php }
} }

/* =Homepage Block Static
----------------------------------------------- */
if(!function_exists('cyon_home_block_static')) {
function cyon_home_block_static(){
	global $data; ?>
	<?php if($data['homepage_middle_block']!=''){ ?>
	<div class="block" id="static-block">
		<?php echo $data['homepage_middle_block_title'] ? '<h2 class="block-title">'.$data['homepage_middle_block_title'].'</h2>' : ''; ?>
		<div class="block-wrapper">
			<?php echo do_shortcode($data['homepage_middle_block']); ?>
		</div>
	</div>
<?php }
} }

/* =Homepage Block Blog
----------------------------------------------- */
if(!function_exists('cyon_home_block_blog')) {
function cyon_home_block_blog(){
	global $data;
	$post_per_page = $data['homepage_blog']!='' ? $data['homepage_blog'] : 3;
	$posts_array = new WP_Query( 'posts_per_page='.$post_per_page.$data['homepage_blog_query'] );
	$count = 1; $classfirst='';
	if($data['homepage_blog_layout']!=1){
		$spanclass= 'span'. 12/$data['homepage_blog_layout'];
	}else{
		$spanclass='';
	}
	if ( $posts_array->have_posts() ) : ?>
		<div class="block" id="blog-block">
			<?php echo $data['homepage_blog_title'] ? '<h2 class="block-title">'.$data['homepage_blog_title'].'</h2>' : ''; ?>
			<div class="block-wrapper<?php echo $data['homepage_blog_layout']>1 ? ' row-fluid' : ''; ?>">
			<?php while ( $posts_array->have_posts() ) : $posts_array->the_post(); ?>
				<?php
				if($count==1){
					$classfirst = ' first-child';
				}elseif($count>=$data['homepage_blog_layout']){
					$count = 0;
				}else{
					$classfirst='';
				}
				?>
				<article id="post-<?php the_ID(); ?>" class="<?php echo $spanclass.$classfirst; ?>"><div class="article-wrapper">
					<header class="page-header">
						<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
						<p class="meta">
							<span class="posted-day"><?php echo esc_html( get_the_time('d') ); ?></span>
							<span class="posted-month"><?php echo esc_html( get_the_time('M') ); ?></span>
							<span class="posted-year"><?php echo esc_html( get_the_time('Y') ); ?></span>
						</p>
						<?php cyon_post_content_featured(); ?>
					</header>
					<div class="page-content clearfix">
						<?php the_excerpt(); ?>
					</div>
					<footer class="entry-meta">
						<p class="readmore"><a href="<?php the_permalink(); ?>"><?php _e('Read more','cyon'); ?></a></p>
					</footer>
				</div></article>
				<?php $count++; ?>
			<?php endwhile; ?>
			</div>
		</div>
	<?php endif;
	wp_reset_postdata();
} }



/* =Homepage Widgets
----------------------------------------------- */
if(!function_exists('cyon_homepage_columns')) {
function cyon_homepage_columns(){
	if ( is_active_sidebar( 'home-columns' ) && is_front_page() ){ ?>
	<?php
		$class='';
		if($data['homepage_bucket_layout']!='bucket-1column'){
			$class=' class="row-fluid"';
		}
	?>
		<!-- Homepage Buckets -->
		<div id="home-buckets"<?php echo $class; ?>>
			<?php dynamic_sidebar( 'home-columns' ); ?>
		</div>
	<?php }
} }


/* =Footer Layout
----------------------------------------------- */
if(!function_exists('cyon_footer_hook')) {
function cyon_footer_hook(){
	global $data;  ?>
	<!-- Footer -->
	<footer id="colophon" role="contentinfo">
		<div class="wrapper clearfix">
			<?php cyon_footer_wrapper(); ?>
		</div>
	</footer>
<?php } }

/* =Footer Widgets Columns
----------------------------------------------- */
if(!function_exists('cyon_footer_columns')) {
function cyon_footer_columns(){
	global $data; 
	if ( ! is_404() && is_active_sidebar( 'footer-columns' ) ){ ?>
	<!-- Footer Columns -->
	<?php
		$class='';
		if($data['footer_bucket_layout']!='bucket-1column'){
			$class=' class="row-fluid"';
		}
	?>
	<div id="footer-buckets" role="complementary"<?php echo $class; ?>>
		<?php dynamic_sidebar( 'footer-columns' ); ?>
	</div>
	<?php }
} }

/* =Copyright
----------------------------------------------- */
if(!function_exists('cyon_footer_copyright')) {
function cyon_footer_copyright(){
	global $data;  ?>
	<!-- Copyright -->
	<?php
		$class='';
		$span='';
		if($data['footer_copyright'] !='' && has_nav_menu( 'footer-menu' ) ){
			$class=' class="row-fluid"';
			$span='span6';
		} ?>
	<div id="bottom"<?php echo $class; ?>>
		<?php echo $data['footer_copyright'] != '' ? '<div class="copyright '.$span.'">'.do_shortcode($data['footer_copyright']).'</div>' : ''; ?>
		<?php wp_nav_menu( array( 'theme_location' => 'footer-menu', 'depth' => '1', 'container_id' => 'access2', 'container_class' => $span, 'fallback_cb' => false ) ); ?>
	</div><?php
} }

/* =Subfooter
----------------------------------------------- */
if(!function_exists('cyon_footer_subfooter')) {
function cyon_footer_subfooter(){
	global $data;
	if ( $data['footer_sub'] ){ ?>
	<!-- Sub Footer -->
	<div id="subfooter">
		<?php echo do_shortcode($data['footer_sub']); ?>
	</div>
	<?php }
} }

/* =Back to top
----------------------------------------------- */
if(!function_exists('cyon_footer_backtotop')) {
function cyon_footer_backtotop(){
	global $data;
	if ( $data['footer_backtotop'] ){ ?>
	<!-- Back to Top -->
	<div id="backtotop">
		<a href="#topmost" class="backtotop"><?php _e('Back to Top','cyon'); ?> </a>
	</div>
	<?php }
} }

/* =Load Footer jQuery
----------------------------------------------- */
if(!function_exists('cyon_footer_jquery')) {
function cyon_footer_jquery(){
	global $data; ?>
	<script type="text/javascript">
		jQuery(document).ready(function(){

			<?php if($data['lazyload']==1){ ?>
			// Lazy Load Support
			jQuery('img.lazyload').show().lazyload({ 
				effect : 'fadeIn',
				skip_invisible : false,
				failure_limit : 100
			});
			<?php } ?>

			<?php if($data['responsive']==1){ ?>
			// Responsive Menu Support
			function checkWidth() {
				var pagesize = jQuery('body').width();
				if (pagesize <= 974) {
					if(jQuery('#access_r').length == 0){
						jQuery('#access').hide().clone().prependTo('body').attr('id','access_r').show();
						jQuery('#access_r h3').css('textIndent','0').prepend('<span class="icon-reorder"></span>');
					}
				}else{
					jQuery('#access_r').hide().remove();
					jQuery('#access').show();
				}
			}
			checkWidth();
			jQuery(window).resize(checkWidth);
			jQuery('#access_r h3').live( 'click', function(){
				if(jQuery('.open_menu').length == 0){
					jQuery('#access_r').addClass('open_menu').transition({ height:jQuery('#access_r ul.menu').height() + 40 + 'px' });; 
				}else{
					jQuery('#access_r').removeClass('open_menu').transition({ height:'40px' });; 
				}
			});

			<?php } ?>

			<?php if(((cyon_get_page_bg()!='' && $data['background_style_pattern_repeat']=='full'))){ ?>
			// Supersized Support
			jQuery.supersized({ 
				slides  :  	[ {image : '<?php echo cyon_get_page_bg() ?>', title : ''} ],
				vertical_center: 0
			});
			<?php } ?>

			<?php if($data['lightbox_activate']==1){ ?>
			// Fancy Box Support
			jQuery('a img.size-medium, a img.size-thumbnail, a img.size-large').parent().addClass("fancybox");
			jQuery('.gallery a').attr('rel', 'group');
			jQuery('.fancybox-media').fancybox({
				openEffect	: 'none',
				closeEffect	: 'none',
				helpers : {
					media : { }
				}
			});
			jQuery('.fancybox').fancybox({
				openEffect	: 'elastic',
				closeEffect	: 'elastic',
				helpers : {
					title : {
						type : 'over'
					}
				}
			});
			jQuery('.iframe').fancybox({
				type		: 'iframe',
				maxWidth	: 800,
				maxHeight	: 600,
				fitToView	: false,
				width		: '70%',
				height		: '70%',
				autoSize	: false,
				closeClick	: false,
				openEffect	: 'none',
				closeEffect	: 'none'
			});
			jQuery('.gallery a').filter(function() {
				return jQuery(this).attr('href').match(/\.(jpg|jpeg|png|gif|bmp|JPG|JPEG|PNG|GIF|BMP)/);
			}).addClass("fancybox-group");
			jQuery(".fancybox-group").fancybox({
				openEffect	: 'elastic',
				closeEffect	: 'elastic',
				helpers : {
					title : {
						type : 'over'
					}
				}
			});
			<?php } ?>
			
			<?php if(cyon_get_list_layout()!=1 && !get_tax_meta(cyon_get_term_id(),'cyon_cat_layout_masonry')){ ?>
			// Post list columns
			jQuery('#primary .row-fluid article:nth-of-type(<?php echo cyon_get_list_layout(); ?>n+1)').addClass('first-child');
			<?php } ?>

			<?php if(cyon_get_list_layout()!=1 && get_tax_meta(cyon_get_term_id(),'cyon_cat_layout_masonry')){ ?>
			jQuery('#primary article').width((jQuery('#content').width() / <?php echo cyon_get_list_layout(); ?>)-3);
			<?php } ?>
			
		});

		<?php if(cyon_get_list_layout()!=1 && get_tax_meta(cyon_get_term_id(),'cyon_cat_layout_masonry')){ ?>
		// Isotope Support
		jQuery(window).load(function(){
			jQuery('.blog-list-masonry #content').imagesLoaded(function(){
				jQuery('.blog-list-masonry #content').isotope({
					itemSelector: 'article.post',
					animationOptions: {
						duration: 750,
						easing: 'linear',
						queue: false
					}
				});
				checkMasonry();
				jQuery(window).trigger('scroll');
			});
		});
		function checkMasonry() {
			var pagesize = jQuery('.page_wrapper').width();
			if (pagesize <= 480) {
				jQuery('#primary article').width(jQuery('#content').width());
			}else if (pagesize <= 974) {
				jQuery('#primary article').width((jQuery('#content').width() / 2)-2);
			}else{
				jQuery('#primary article').width((jQuery('#content').width() / <?php echo cyon_get_list_layout(); ?>)-3);
			}
			jQuery(window).trigger('scroll');
		}
		jQuery(window).resize(checkMasonry);
		jQuery(window).scroll(function(){
			jQuery('.blog-list-masonry #content').isotope('reLayout');
		});
		<?php } ?>

	</script> 
<?php } }

/* =Footer Scripts
----------------------------------------------- */
if(!function_exists('cyon_footer_scripts')) {
function cyon_footer_scripts(){
	global $data;
	echo $data['footer_scripts']."\n";
} }


/* =Get Page Layout
----------------------------------------------- */
if(!function_exists('cyon_get_page_layout')) {
function cyon_get_page_layout(){
	global $post, $data;
	if(is_front_page()) {
		$page_layout = $data['homepage_layout'];
	}elseif(is_home() && get_post_meta(get_option('page_for_posts', true),'cyon_layout',true)!='default'){
		$page_layout = get_post_meta(get_option('page_for_posts', true),'cyon_layout',true);
	}elseif(is_archive() && get_tax_meta(cyon_get_term_id(),'cyon_cat_layout')!='default' && get_tax_meta(cyon_get_term_id(),'cyon_cat_layout')!=''){
		$page_layout = get_tax_meta(cyon_get_term_id(),'cyon_cat_layout');
	}elseif( get_post_meta($post->ID,'cyon_layout',true)=='default' || !get_post_meta($post->ID,'cyon_layout',true) ){
		$page_layout = $data['general_layout'];
	}else{
		$page_layout = get_post_meta($post->ID,'cyon_layout',true);
	}
	return apply_filters('cyon_the_page_layout', $page_layout );
} }


/* =Get Term ID
----------------------------------------------- */
if(!function_exists('cyon_get_term_id')) {
function cyon_get_term_id(){
	$current_cat = get_query_var('cat');
	$term_slug = get_category ($current_cat);
	$current_term = get_term_by( 'slug', $term_slug->slug, 'category' );
	return $current_term->term_id;
} }


/* =Get Post Listing Layout
----------------------------------------------- */
if(!function_exists('cyon_get_list_layout')) {
function cyon_get_list_layout(){
	global $post, $data;
	$cols = 1;
	if(is_category()){
		if(get_tax_meta(cyon_get_term_id(),'cyon_cat_layout_listing')=='list-2columns'){
			$cols = '2';
		}elseif(get_tax_meta(cyon_get_term_id(),'cyon_cat_layout_listing')=='list-3columns'){
			$cols = '3';
		}elseif(get_tax_meta(cyon_get_term_id(),'cyon_cat_layout_listing')=='list-4columns'){
			$cols = '4';
		}else{
			if($data['blog_list_layout_list']=='2columns'){
				$cols = '2';
			}elseif($data['blog_list_layout_list']=='3columns'){
				$cols = '3';
			}elseif($data['blog_list_layout_list']=='4columns'){
				$cols = '4';
			}
		}
	}elseif((is_front_page() && get_option('page_on_front')!='') || is_home() || is_archive() || is_category() && $data['blog_list_layout_list']!='1column'){
		if($data['blog_list_layout_list']=='2columns'){
			$cols = '2';
		}elseif($data['blog_list_layout_list']=='3columns'){
			$cols = '3';
		}elseif($data['blog_list_layout_list']=='4columns'){
			$cols = '4';
		}
	}
	return apply_filters('cyon_the_list_layout', $cols );
} }

/* =Get Page Background
----------------------------------------------- */
if(!function_exists('cyon_get_page_bg')) {
function cyon_get_page_bg(){
	global $post, $data;
	if(is_home() && get_post_meta(get_option('page_for_posts', true),'cyon_background',true)!=''){
		$image_attributes = wp_get_attachment_image_src( get_post_meta(get_option('page_for_posts', true),'cyon_background',true),'large' );
		$page_bg = $image_attributes[0];
	}elseif(is_archive() && is_array(get_tax_meta(cyon_get_term_id(),'cyon_cat_background'))){
		$image_id = get_tax_meta(cyon_get_term_id(),'cyon_cat_background');
		$image_attributes = wp_get_attachment_image_src( $image_id['id'],'full' );
		$page_bg = $image_attributes[0];
	}elseif(!is_search() && get_post_meta($post->ID,'cyon_background',true)!=''){
		$image_attributes = wp_get_attachment_image_src( get_post_meta($post->ID,'cyon_background',true),'large' );
		$page_bg = $image_attributes[0];
	}else{
		$page_bg = $data['background_style_image'];
	}
	return apply_filters('cyon_the_background_image', $page_bg);
} }

/* =Replace body classes
----------------------------------------------- */
if(!function_exists('cyon_replace_body_class')) {
function cyon_replace_body_class($classes) {
	global $post, $data;
	/* Add Ancestor classes */
	if(!is_404()){
		if(!is_404() || !is_front_page()){
			$parents = array_reverse(get_post_ancestors( $post->ID ));
			if( is_page() && $parents[0]<>'' ) { 
				$classes[] = 'page-ancestor-'.$parents[0];
			}
		}
		if(is_archive() || is_home()){
			$classes[] = 'blog-list-'.cyon_get_list_layout();
		}
		if(get_tax_meta(cyon_get_term_id(),'cyon_cat_layout_masonry')){
			$classes[] = 'blog-list-masonry';
		}
	}
	
	/* Add page width */
	$classes[] = 'width-'.$data['page_width'];
	
	return $classes;
} }

/* =Add CSS for Blog listing if multiple columns
----------------------------------------------- */
if(!function_exists('cyon_post_layout_class')) {
function cyon_post_layout_class($classes) {
	global $post;
	if(cyon_get_list_layout()!=1 && !get_tax_meta(cyon_get_term_id(),'cyon_cat_layout_masonry')){
		$classes[] = 'span'. 12/cyon_get_list_layout();
	}
	return $classes;
} }


/* =Replace Login logo
----------------------------------------------- */
if(!function_exists('cyon_admin_login')) {
function cyon_admin_login() {
	global $data; ?>
	<style type="text/css">
		body.login { background-color:<?php echo $data['admin_login_bgcolor']; ?>; }
		body.login #login h1 a {
			<?php if($data['header_logo']!=''){ ?>
			background: url('<?php echo $data['header_logo']; ?>') no-repeat scroll 50% transparent;
			height:150px;
			margin-bottom:10px;
			<?php }else{ ?>
			text-indent:0;
			background:none;
			<?php } ?>
			text-align:center;
			text-decoration:none;
			line-height:30px;
			font-size:30px;
		}
		body.login #login { padding-top:50px; }
	</style>
<?php } }


/* =Checkspam
----------------------------------------------- */
if(!function_exists('cyon_checkspam')) {
function cyon_checkspam ($content) {
	$isSpam = FALSE;
	$content = (array) $content;
	
	if (function_exists('akismet_init')) {
		$wpcom_api_key = get_option('wordpress_api_key');
		if (!empty($wpcom_api_key)) {
			global $akismet_api_host, $akismet_api_port;
			// set remaining required values for akismet api
			$content['user_ip'] = preg_replace( '/[^0-9., ]/', '', $_SERVER['REMOTE_ADDR'] );
			$content['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
			$content['referrer'] = $_SERVER['HTTP_REFERER'];
			$content['blog'] = get_option('home');
			
			if (empty($content['referrer'])) {
				$content['referrer'] = get_permalink();
			}
			
			$queryString = '';
			
			foreach ($content as $key => $data) {
				if (!empty($data)) {
					$queryString .= $key . '=' . urlencode(stripslashes($data)) . '&';
				}
			}
			
			$response = akismet_http_post($queryString, $akismet_api_host, '/1.1/comment-check', $akismet_api_port);
			
			if ($response[1] == 'true') {
				update_option('akismet_spam_count', get_option('akismet_spam_count') + 1);
				$isSpam = TRUE;
			}
			
		}
		
	}
	return $isSpam;
} }

/* =Contact submission
----------------------------------------------- */
if(!function_exists('cyon_contact_email')) {
function cyon_contact_email() {
	if (! wp_verify_nonce($_REQUEST['nonce'], 'cyon_contact_nonce') ) die(__('Security check')); 
	if(isset($_REQUEST['nonce']) && isset($_REQUEST['email'])) {
		$subject = __('New inquiry from').' '.get_bloginfo('name');
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: '.$_REQUEST['name'].' <'.$_REQUEST['email'].'>' . "\r\n";
		$body = __('Name').': <b>'.$_REQUEST['name'].'</b><br>';
		$body .= __('Email').': <b>'.$_REQUEST['email'].'</b><br>';
		$body .= __('Phone').': <b>'.$_REQUEST['phone'].'</b><br>';
		if($_REQUEST['dropdown']){
			$body .= __('Selected').': <b>'.$_REQUEST['dropdown'].'</b><br>';
		}
		$body .= __('Message').': <b>'.$_REQUEST['message'].'</b>';

		$content['comment_author'] = $_REQUEST['name'];
		$content['comment_author_email'] = $_REQUEST['email'];
		$content['comment_author_url'] = '';
		$content['comment_content'] = $_REQUEST['message'];
	
		/* Check spam */
		if (cyon_checkspam ($content)) {
			echo 0;
			die();
		}

		/* Send mail */
		if( mail($_REQUEST['emailto'], $subject, $body, $headers) ) {
			echo 1;
		} else {
			echo 0;
		}
	}
	die();
} }

/* =Newsletter submission
----------------------------------------------- */
if(!function_exists('cyon_newsletter_email')) {
function cyon_newsletter_email() {
	if (! wp_verify_nonce($_REQUEST['nonce'], 'cyon_newsletter_nonce') ) die(__('Security check')); 
	if(isset($_REQUEST['nonce']) && isset($_REQUEST['email'])) {
		$subject = __('New subscriber from').' '.get_bloginfo('name');
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: '.$_REQUEST['name'].' <'.$_REQUEST['email'].'>' . "\r\n";
		$body = __('Name').': <b>'.$_REQUEST['name'].'</b><br>';
		$body .= __('Email').': <b>'.$_REQUEST['email'].'</b><br>';

		$content['comment_author'] = $_REQUEST['name'];
		$content['comment_author_email'] = $_REQUEST['email'];
		$content['comment_author_url'] = '';
		$content['comment_content'] = '';
	
		/* Check spam */
		if (cyon_checkspam ($content)) {
			echo 0;
			die();
		}

		/* Send mail */
		if( mail($_REQUEST['emailto'], $subject, $body, $headers) ) {
			echo 1;
		} else {
			echo 0;
		}
	}
	die();
} }


/* =Register Widgets
----------------------------------------------- */
if(!function_exists('cyon_widgets_init')) {
function cyon_widgets_init() {
	global $data;

	/* Check home bucket columns */
	if($data['homepage_bucket_layout']=='bucket-4columns'){
		$homeclass = ' span3';
	}elseif($data['homepage_bucket_layout']=='bucket-3columns'){
		$homeclass = ' span4';
	}elseif($data['homepage_bucket_layout']=='bucket-2columns'){
		$homeclass = ' span6';
	}else{
		$homeclass = '';
	}

	/* Check footer bucket columns */
	if($data['footer_bucket_layout']=='bucket-4columns'){
		$footclass = ' span3';
	}elseif($data['footer_bucket_layout']=='bucket-3columns'){
		$footclass = ' span4';
	}elseif($data['footer_bucket_layout']=='bucket-2columns'){
		$footclass = ' span6';
	}else{
		$footclass = '';
	}

	register_sidebar( array(
		'name' => __( 'Homepage Buckets', 'cyon' ),
		'id' => 'home-columns',
		'before_widget' => '<aside id="%1$s" class="widget %2$s'.$homeclass.'">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Left Sidebar', 'cyon' ),
		'id' => 'left-sidebar',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Right Sidebar', 'cyon' ),
		'id' => 'right-sidebar',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Buckets', 'cyon' ),
		'id' => 'footer-columns',
		'before_widget' => '<aside id="%1$s" class="widget %2$s'.$footclass.'">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

} }


/* =Category Pagination
----------------------------------------------- */
if(!function_exists('cyon_content_nav')) {
function cyon_content_nav() {
	global $wp_query;
	if(function_exists( 'wp_pagenavi' )){
		wp_pagenavi();
	}else{
		if ( $wp_query->max_num_pages > 1 ) : ?>
			<nav class="navigation clearfix">
				<h3 class="assistive-text"><?php _e( 'Post navigation', 'cyon' ); ?></h3>
				<div class="alignleft"><?php next_posts_link( __( '&laquo; Older Posts', 'cyon' ) ); ?></div>
				<div class="alignright"><?php previous_posts_link( __( 'Newer Posts &raquo;', 'cyon' ) ); ?></div>
			</nav>
		<?php endif;
	}
}
}


/* =Custom Comments
----------------------------------------------- */
if(!function_exists('cyon_comment')) {
function cyon_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'cyon' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'cyon' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'cyon' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'cyon' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'cyon' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'cyon' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'cyon' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
} }

/* =Featured Media
----------------------------------------------- */
if(!function_exists('cyon_post_content_featured')) {
function cyon_post_content_featured(){
	global $data;
	$pages = $data['content_featured_image']; ?>
	<?php if(has_post_thumbnail() && (is_single() && $pages['posts']==1) || (is_page() && $pages['pages']==1)){ ?>
		<?php $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large'); ?>
		<div class="entry-featured-image">
			<?php if(has_post_format('video')){ ?>
				<?php $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large'); ?>
				<?php echo do_shortcode('[video width="100%" src="'.rwmb_meta( 'cyon_video_url' ).'" poster="'.$large_image_url[0].'"]'); ?>
			<?php }elseif(has_post_format('audio')){ ?>
				<?php the_post_thumbnail( 'large' ); ?>
				<?php echo do_shortcode('[audio width="100%" src="'.rwmb_meta( 'cyon_audio_url' ).'"]'); ?>
			<?php }else{ ?>
				<?php the_post_thumbnail( 'large' ); ?>
			<?php } ?>
		</div>
	<?php }elseif(has_post_thumbnail() && (is_category() || is_archive() || is_home() || (is_front_page() && $data['homepage_blog_thumbnail']==1)) && $pages['listing']==1 ){ ?>
		<?php $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), $data['content_thumbnail_size']); ?>
		<div class="entry-featured-image">
			<?php if(has_post_format('video')){ ?>
				<?php
				$video = '';
				$file = pathinfo(rwmb_meta( 'cyon_video_url' ));
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
				?>
					<?php echo do_shortcode('[video width="100%" src="'.rwmb_meta( 'cyon_video_url' ).'" poster="'.$large_image_url[0].'"]'); ?>
				<?php }else{
				$icon = 'facetime-video';
					?>
				<a href="<?php echo rwmb_meta( 'cyon_video_url' ); ?>" <?php if($data['lightbox_activate']==1){ ?>class="fancybox-media"<?php }else{ ?>target="_blank"<?php } ?>><?php the_post_thumbnail( $data['content_thumbnail_size'] ); ?><span class="status-box"><span class="icon-box icon-<?php echo $icon; ?>"></span></span></a>
				<?php }	?>
			<?php }elseif(has_post_format('image')){
				$icon = 'zoom-in';
				?>
				<?php $image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' ); ?>
				<a href="<?php echo $image_attributes[0]; ?>" <?php if($data['lightbox_activate']==1){ ?>class="fancybox"<?php }else{ ?>target="_blank"<?php } ?>><?php the_post_thumbnail( $data['content_thumbnail_size'] ); ?><span class="status-box"><span class="icon-box icon-<?php echo $icon; ?>"></span></span></a>
			<?php }else{ ?>
				<?php 
				if(has_post_format('link')){
					$url = rwmb_meta( 'cyon_link_url' );
					$icon = 'share';
				}else{
					$url = get_permalink();
					$icon = 'info-sign';
				}
				?>
				<a href="<?php echo $url; ?>"><?php the_post_thumbnail( $data['content_thumbnail_size'] ); ?><span class="status-box"><span class="icon-box icon-<?php echo $icon; ?>"></span></span></a>
				<?php if(has_post_format('audio')){ ?>
					<?php echo do_shortcode('[audio width="100%" src="'.rwmb_meta( 'cyon_audio_url' ).'"]'); ?>
				<?php } ?>
			<?php } ?>
		</div>
	<?php }elseif(has_post_format('gallery') && (is_category() || is_archive() || is_home() || is_front_page()) && $pages['listing']==1){ ?>
		<div class="entry-featured-image swiper">
			<div class="swiper-pager"></div><div class="swiper-container"><div class="swiper-wrapper">
			<?php 
			$images = rwmb_meta( 'cyon_gallery_images', 'type=image&size='.$data['content_thumbnail_size'] );
			foreach ( $images as $image ){ ?>
				<div class="swiper-slide"><a href="<?php echo $image['full_url']; ?>" class="fancybox-group" rel="images-<?php the_ID(); ?>"><img src="<?php echo $image['url']; ?>" alt="<?php echo $image['name']; ?>" /><span class="status-box"><span class="icon-box icon-camera"></span></span></a></div>
			<?php } ?>
			</div></div>
		</div>
	<?php } ?>
<?php } }

if(!function_exists('cyon_wp_get_attachment_image_attributes_lazyload')) {
function cyon_wp_get_attachment_image_attributes_lazyload( $attr, $attachment ) {
	global $post, $data;
	if(! is_admin() && $data['lazyload']==1){
		if(get_post_type(get_the_ID())!='product' && !is_single()){
			$attr['data-original'] = $attr['src'];
			$attr['src'] = THEME_ASSETS_URI.'images/blank.png';
			$attr['class'] = 'lazyload';
		}elseif(get_post_type(get_the_ID())=='product' && !is_single()){
			$attr['data-original'] = $attr['src'];
			$attr['src'] = THEME_ASSETS_URI.'images/blank.png';
			$attr['class'] = 'lazyload';
		}
	}
    return $attr;
} }
