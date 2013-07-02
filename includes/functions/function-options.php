<?php
if ( !defined('ABSPATH') )
	die('-1');

/* =Start building theme options here
----------------------------------------------- */
remove_action( 'init', 'of_options' );
add_action( 'init', 'cyon_of_options' );


if ( !function_exists( 'cyon_of_options' ) ){
	function cyon_of_options() {

		/* Stylsheet reader for color/gutter */
		$alt_stylesheet_path = THEME_ASSETS_DIR . '/css/';
		$alt_stylesheets_color = array();
		$alt_stylesheets_gutter = array();
		
		if ( is_dir($alt_stylesheet_path) ) 
		{
		    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) 
		    { 
		        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) 
		        {
		            if(strstr($alt_stylesheet_file,'color-') !== false)
		            {
		                $alt_stylesheets_color[$alt_stylesheet_file] = $alt_stylesheet_file;
		            }
		            if(strstr($alt_stylesheet_file,'grid-') !== false)
		            {
		                $alt_stylesheets_gutter[$alt_stylesheet_file] = $alt_stylesheet_file;
		            }
		        }    
		    }
		}

		/* = Homepage blocks for the layout manager (sorter) */
		$of_options_homepage_blocks = array
		( 
			'disabled' => array (
				'placebo' 			=> 'placebo', 
				'home_block_static' => 'Static content',
				'home_block_blog' 	=> 'Latest blog'
			), 
			'enabled' => array (
				'placebo' 			=> 'placebo', 
				'home_block_slider' => 'Slider',
				'home_block_page' 	=> 'Page content',
				'home_block_bucket' => 'Bucket widgets'
			),
		);

		/* =The options array
		----------------------------------------------- */
		/* Set the option array */
		global $of_options;
		$of_options = array();
		
		/* =Styling
		----------------------------------------------- */
		$of_options[] = array( 'name' => __( 'Styling', 'cyon' ),
							'type' => "heading");
							
		/* Icon Begin ------- */
		$of_options[] = array( 'name' => __( 'Icons Upload', 'cyon' ),
							'type' => 'group_begin');

		/* Favicon PC */
		$of_options[] = array( 'name' => __( 'Favicon file', 'cyon' ),
							'desc' => __( 'Upload a 16px x 16px icon/png/gif image that will represent your website\'s favicon.', 'cyon' ),
							'id' => 'favicon',
							'placeholder' => __( 'No file selected', 'cyon' ),
							'std' => '',
							'type' => 'media');  

		/* Favicon iOS */
		$of_options[] = array( 'name' => __( 'iOS icon file', 'cyon' ),
							'desc' => __( 'Minimum is 57px x 57px at 72dpi of png image. For retina display, maximum is 114px x 114px.', 'cyon' ),
							'id' => 'iosicon',
							'placeholder' => __( 'No file selected', 'cyon' ),
							'std' => '',
							'type' => 'media');  

		/* Icon End ------- */
		$of_options[] = array( 'type' => 'group_end');

		/* Fonts Begin ------- */
		$of_options[] = array( 'name' => __( 'Fonts Selection', 'cyon' ),
							'type' => 'group_begin');
		/* Primary Font */
		$of_options[] = array( 'name' => __( 'Primary', 'cyon' ),
							'desc' => __( 'General font usage', 'cyon' ),
							'id' => 'primary_font',
							'std' => array('size' => '13px','face' => 'segoe ui','style' => 'normal','color' => '#333333'),
							'type' => 'typography');

		/* Secondary Font */
		$of_options[] = array( 'name' => __( 'Secondary', 'cyon' ),
							'desc' => __( 'Font use for headers like H1, H2 and H3', 'cyon' ),
							'id' => 'secondary_font',
							'std' => array('face' => 'segoe ui light','color' => '#666666'),
							'type' => 'typography');

		/* Main Navigation Font */
		$of_options[] = array( 'name' => __( 'Main navigation', 'cyon' ),
							'desc' => __( 'Font use for main navigation', 'cyon' ),
							'id' => 'menu_font',
							'std' => array('size' => '13px','face' => 'segoe ui','style' => 'normal','color' => '#333333'),
							'type' => 'typography');

		/* Fonts End ------- */
		$of_options[] = array( 'type' => 'group_end');

		/* Background Begin ------- */
		$of_options[] = array( 'name' => __( 'Background Options', 'cyon' ),
							'type' => 'group_begin');

		/* Background Image */
		$of_options[] = array( 'name' => __( 'Image', 'cyon' ),
							'placeholder' => __( 'No file selected', 'cyon' ),
							'id' => 'background_style_image',
							'std' => '',
							'type' => 'media');  

		/* Background color */
		$of_options[] = array( 'name' => __( 'Color', 'cyon' ),
							'id' => 'background_color',
							'std' => '#ffffff',
							'type' => 'color');  

		/* Background repeat */
		$of_options[] = array( 'name' => __( 'Repeat', 'cyon' ),
							'id' => 'background_style_pattern_repeat',
							'std' => 'repeat',
							'type' => 'select',
		          			'folds' => 1,
							'options' => array('full'=>__('Full screen','cyon'),'repeat'=>__('Repeat','cyon'), 'no-repeat'=>__('No repeat','cyon'), 'repeat-x'=>__('Repeat horizontally','cyon'), 'repeat-y'=>__('Repeat vertically','cyon')));  

		/* Background position */
		$of_options[] = array( 'name' => __( 'Position', 'cyon' ),
							'placeholder' => '50% 0',
							'id' => 'background_style_pattern_position',
							'std' => '50% 0',
							'type' => 'text',
		          			'fold' => array('background_style_pattern_repeat_repeat','background_style_pattern_repeat_no-repeat','background_style_pattern_repeat_repeat-y','background_style_pattern_repeat_repeat-x'));

		/* Background End ------- */
		$of_options[] = array( 'type' => 'group_end');


		/* Stylesheets Begin ------- */
		$of_options[] = array( 'name' => __( 'Stylesheets Selection', 'cyon' ),
							'type' => 'group_begin');

		/* Color */
		$of_options[] = array( 'name' => __( 'Color', 'cyon' ),
							'id' => 'theme_color',
							'type' => 'select',
							'std' => 'color-light.css',
							'options' => $alt_stylesheets_color);  

		/* Gutter */
		$of_options[] = array( 'name' => __( 'Grid gutter', 'cyon' ),
							'id' => 'theme_gutter',
							'type' => 'select',
							'std' => 'grid-20px-gutter.css',
							'options' => $alt_stylesheets_gutter);  

		/* Stylesheets End ------- */
		$of_options[] = array( 'type' => 'group_end' );

		/* =General Settings
		----------------------------------------------- */
		$of_options[] = array( 'name' => __( 'General', 'cyon' ),
							'type' => 'heading');

		/* Layout Begin ------- */
		$of_options[] = array( 'name' => __( 'Site-wide Layout', 'cyon' ),
							'type' => 'group_begin');

		/* Layout */
		$of_options[] = array( 'name' => __( 'Default layout of the website', 'cyon' ),
							'id' => 'general_layout',
							'std' => 'general-2right',
							'type' => 'images',
							'options' => array(
								'general-1column' => THEME_FRAMEWORK_URI . 'assets/images/1col.png',
								'general-2left' => THEME_FRAMEWORK_URI . 'assets/images/2cl.png',
								'general-2right' => THEME_FRAMEWORK_URI . 'assets/images/2cr.png'
							));  

		/* Width */
		$of_options[] = array( 'name' => __( 'Page width', 'cyon' ),
							'id' => 'page_width',
							'std' => 'wide',
							'type' => 'radio',
							'options' => array( 'wide' => 'Wide', 'centered' => 'Centered' ));


		/* Layout End ------- */
		$of_options[] = array( 'type' => 'group_end');

		/* Content Begin ------- */
		$of_options[] = array( 'name' => __( 'Content', 'cyon' ),
							'type' => 'group_begin');

		/* Author */
		$of_options[] = array( 'name' => __( 'Display author', 'cyon' ),
							'desc' => __( 'Yes, display author info after the content', 'cyon' ),
							'std' => 0,
							'id' => 'content_author',
							'type' => 'checkbox');

		/* Comment */
		$of_options[] = array( 'name' => __( 'Display comment', 'cyon' ),
							'id' => 'content_comment',
							'std' => array( 'posts' ),
							'type' => 'multicheck',
							'options' => array( 'posts' => __( 'Posts' ), 'pages' => __( 'Pages' ) ));

		/* Featured image */
		$of_options[] = array( 'name' => __( 'Display featured image', 'cyon' ),
							'id' => 'content_featured_image',
							'std' => array( 'posts', 'listing' ),
							'type' => 'multicheck',
							'options' => array( 'posts' => __( 'Posts' ), 'listing' => __( 'Blog/taxonomy listing' ), 'pages' => __( 'Pages' ) ));

		/* Content End ------- */
		$of_options[] = array( 'type' => 'group_end');

		/* Blog/taxonomy Begin ------- */
		$of_options[] = array( 'name' => __( 'Blog/Taxonomy List', 'cyon' ),
							'type' => 'group_begin');

		/* Style */
		$of_options[] = array( 'name' => __( 'Content display', 'cyon' ),
							'id' => 'content_blog_post',
							'std' => 'excerpt',
							'type' => 'radio',
							'options' => array( 'excerpt' => 'Excerpt', 'full' => 'Full content' ));

		/* Layout */
		$of_options[] = array( 'name' => __( 'Default layout', 'cyon' ),
							'id' => 'blog_list_layout_list',
							'std' => '1column',
							'type' => 'images',
							'options' => array(
								'1column' => THEME_FRAMEWORK_URI . 'assets/images/1-col-portfolio.png',
								'2columns' => THEME_FRAMEWORK_URI . 'assets/images/2-col-portfolio.png',
								'3columns' => THEME_FRAMEWORK_URI . 'assets/images/3-col-portfolio.png',
								'4columns' => THEME_FRAMEWORK_URI . 'assets/images/4-col-portfolio.png'
							));  

		/* Featured image size */
		$of_options[] = array( 'name' => __( 'Featured image size', 'cyon' ),
							'id' => 'content_thumbnail_size',
							'std' => 'large',
							'type' => 'radio',
							'options' => array( 'thumbnail' => __( 'Thumbnail' ), 'medium' => __( 'Medium' ), 'large' => __( 'Large' ), 'full' => __( 'Full' ) ));

		/* Blog/taxonomy End ------- */
		$of_options[] = array( 'type' => 'group_end');

		/* Woocommerce Begin ------- */
		if ( class_exists( 'Woocommerce' ) ) {
			$of_options[] = array( 'name' => __( 'Woocommerce', 'cyon' ),
								'type' => 'group_begin');
		
			/* Listing per page */
			$of_options[] = array( 'name' => __( 'Products per page', 'cyon' ),
								'desc' => '',
								'mod' => 'mini',
								'placeholder' => 20,
								'std' => 20,
								'id' => 'woocommerce_product_per_page',
								'type' => 'text');

			/* Listing columns */
			$of_options[] = array( 'name' => __( 'Product list layout', 'cyon' ),
								'desc' => '',
								'id' => 'woocommerce_product_cols',
								'std' => '3',
								'type' => 'images',
								'options' => array(
									'1' => THEME_FRAMEWORK_URI . 'assets/images/1-col-portfolio.png',
									'2' => THEME_FRAMEWORK_URI . 'assets/images/2-col-portfolio.png',
									'3' => THEME_FRAMEWORK_URI . 'assets/images/3-col-portfolio.png',
									'4' => THEME_FRAMEWORK_URI . 'assets/images/4-col-portfolio.png'
								));  
								
			/* Woocommerce End ------- */
			$of_options[] = array( 'type' => 'group_end');
		}

		/* =Header
		----------------------------------------------- */
		$of_options[] = array( 'name' => __( 'Header', 'cyon' ),
							'type' => 'heading');

		/* Options Begin ------- */
		$of_options[] = array( 'name' => __( 'Header Options', 'cyon' ),
							'type' => 'group_begin');
		/* Logo */
		$of_options[] = array( 'name' => __( 'Logo file', 'cyon' ),
							'desc' => __( 'This will replace the website name text and use the image instead. Also replace the admin logo.', 'cyon' ),
							'placeholder' => __( 'No file selected', 'cyon' ),
							'id' => 'header_logo',
							'std' => '',
							'type' => 'media');  

		/* Layout */
		$of_options[] = array( 'name' => __( 'Logo/menu default layout', 'cyon' ),
							'id' => 'header_layout',
							'std' => 'logo-left',
							'type' => 'images',
							'options' => array(
								'logo-left' => THEME_FRAMEWORK_URI . 'assets/images/header-logo-left.png',
								'logo-center' => THEME_FRAMEWORK_URI . 'assets/images/header-logo-center.png',
								'logo-right' => THEME_FRAMEWORK_URI . 'assets/images/header-logo-right.png',
								'logo-left-menu' => THEME_FRAMEWORK_URI . 'assets/images/header-logo-left-menu.png',
								'logo-right-menu' => THEME_FRAMEWORK_URI . 'assets/images/header-logo-right-menu.png'
							));  

		/* Breadcrumbs */
		$of_options[] = array( 'name' => __( 'Breadcrumbs', 'cyon' ),
							'std' => 1,
							'desc' => __( 'Yes, use breadcrumbs on inner pages. If you are running Woocommerce, this will be ignored.', 'cyon' ),
							'id' => 'breadcrumbs',
							'type' => 'checkbox');

		/* Top Left */
		$of_options[] = array( 'name' => __( 'Top left content', 'cyon' ),
							'desc' => __( 'This will show at the very top left.' ),
							'id' => 'top_left_content',
							'type' => 'textarea');

		/* Top Right */
		$of_options[] = array( 'name' => __( 'Top right content', 'cyon' ),
							'desc' => __( 'This will show at the very top right.' ),
							'id' => 'top_right_content',
							'type' => 'textarea');

		/* Options End ------- */
		$of_options[] = array( 'type' => 'group_end' );

		/* =Footer
		----------------------------------------------- */
		$of_options[] = array( 'name' => __( 'Footer', 'cyon' ),
							'type' => 'heading');

		/* Options Begin ------- */
		$of_options[] = array( 'name' => __( 'Footer Options', 'cyon' ),
							'type' => 'group_begin');

		/* Layout */
		$of_options[] = array( 'name' => __( 'Bucket layout', 'cyon' ),
							'id' => 'footer_bucket_layout',
							'desc' => 'Shows number of footer columns to be used.',
							'std' => 'bucket-4columns',
							'type' => 'images',
							'options' => array(
								'bucket-1column' => THEME_FRAMEWORK_URI . 'assets/images/1-col-widget.png',
								'bucket-2columns' => THEME_FRAMEWORK_URI . 'assets/images/2-col-widget.png',
								'bucket-3columns' => THEME_FRAMEWORK_URI . 'assets/images/3-col-widget.png',
								'bucket-4columns' => THEME_FRAMEWORK_URI . 'assets/images/4-col-widget.png'
							));  

		/* Copyright */
		$of_options[] = array( 'name' => __( 'Copyright', 'cyon' ),
							'std' => __( '&copy; 2013 MyCompany.com. All Rights Reserved.' ),
							'id' => 'footer_copyright',
							'type' => 'text');

		/* Sub Footer */
		$of_options[] = array( 'name' => __( 'Sub footer', 'cyon' ),
							'desc' => 'This will show at the very bottom of the page.',
							'id' => 'footer_sub',
							'type' => 'textarea');

		/* Back to Top Button */
		$of_options[] = array( 'name' => __( 'Back to top button', 'cyon' ),
							'desc' => __( 'Yes, show back to top button', 'cyon' ),
							'id' => 'footer_backtotop',
							'std' => 0,
							'type' => 'checkbox');

		/* Options End ------- */
		$of_options[] = array( 'type' => 'group_end' );

		/* =Homepage
		----------------------------------------------- */
		$of_options[] = array( 'name' => __( 'Homepage', 'cyon' ),
							'type' => 'heading');

		/* Info */
		if ( 0 == get_option('page_on_front') ) { // Check if a page is set to front page
			$of_options[] = array( 'std' => __( '<h3 style="margin: 0 0 10px">Oppps! Something\'s wrong.</h3><div>You should create a blank page for your homepage. Go to Settings > Reading > Front page displays, then select "A static page". Set "Front page" to the new page you just created.</div>' ),
								'icon' => true,
								'type' => 'info');
		}
		
		/* Options Begin ------- */
		$of_options[] = array( 'name' => __( 'Homepage Options', 'cyon' ),
							'type' => 'group_begin');

		/* Layout */
		$of_options[] = array( 'name' => __( 'Default layout of the homepage', 'cyon' ),
							'desc' => __( 'This will override the default layout.' ),
							'id' => 'homepage_layout',
							'std' => 'general-1column',
							'type' => 'images',
							'options' => array(
								'general-1column' => THEME_FRAMEWORK_URI . 'assets/images/1col.png',
								'general-2left' => THEME_FRAMEWORK_URI . 'assets/images/2cl.png',
								'general-2right' => THEME_FRAMEWORK_URI . 'assets/images/2cr.png'
							));  

		/* Sorter */
		$of_options[] = array( 'name' => __( 'Block sorter', 'cyon' ),
							'id' => 'homepage_blocks',
							'desc' => 'Organize how you want the layout to appear on the homepage',
							'std' => $of_options_homepage_blocks,
							'type' => 'sorter');  

		/* Options End ------- */
		$of_options[] = array( 'type' => 'group_end' );

		/* Blocks Begin ------- */
		$of_options[] = array( 'name' => __( 'Slider', 'cyon' ),
							'type' => 'group_begin');

		/* Slider */
		$of_options[] = array( 'name' => __( 'Images', 'cyon' ),
							'desc' => __( 'Unlimited slider with drag and drop sortings.' ),
							'id' => 'homepage_slider',
							'type' => 'slider');

		/* Options End ------- */
		$of_options[] = array( 'type' => 'group_end' );

		/* Blocks Begin ------- */
		$of_options[] = array( 'name' => __( 'Static content', 'cyon' ),
							'type' => 'group_begin');

		/* Static Block */
		$of_options[] = array( 'name' => __( 'Text', 'cyon' ),
							'desc' => __( 'Can accept HTML tags and shortcodes.' ),
							'id' => 'homepage_middle_block',
							'type' => 'textarea');

		/* Options End ------- */
		$of_options[] = array( 'type' => 'group_end' );

		/* Blocks Begin ------- */
		$of_options[] = array( 'name' => __( 'Blog', 'cyon' ),
							'type' => 'group_begin');

		/* Blog */
		$of_options[] = array( 'name' => __( 'Number of posts', 'cyon' ),
							'desc' => '',
							'mod' => 'mini',
							'placeholder' => 3,
							'std' => 3,
							'id' => 'homepage_blog',
							'type' => 'text');

		/* Blog thumbnail */
		$of_options[] = array( 'name' => __( 'Show thumbnail', 'cyon' ),
							'desc' => __( 'Yes, show featured thumbnail', 'cyon' ),
							'id' => 'homepage_blog_thumbnail',
							'std' => 1,
							'type' => 'checkbox');

		/* Blog columns */
		$of_options[] = array( 'name' => __( 'Layout', 'cyon' ),
							'id' => 'homepage_blog_layout',
							'desc' => '',
							'std' => '3',
							'type' => 'images',
							'options' => array(
								'1' => THEME_FRAMEWORK_URI . 'assets/images/1-col-portfolio.png',
								'2' => THEME_FRAMEWORK_URI . 'assets/images/2-col-portfolio.png',
								'3' => THEME_FRAMEWORK_URI . 'assets/images/3-col-portfolio.png',
								'4' => THEME_FRAMEWORK_URI . 'assets/images/4-col-portfolio.png'
							));  

		/* Options End ------- */
		$of_options[] = array( 'type' => 'group_end' );

		/* Blocks Begin ------- */
		$of_options[] = array( 'name' => __( 'Widgets', 'cyon' ),
							'type' => 'group_begin');

		/* Layout */
		$of_options[] = array( 'name' => __( 'Bucket layout', 'cyon' ),
							'id' => 'homepage_bucket_layout',
							'desc' => 'Shows number of bucket columns to be used.',
							'std' => 'bucket-3columns',
							'type' => 'images',
							'options' => array(
								'bucket-1column' => THEME_FRAMEWORK_URI . 'assets/images/1-col-widget.png',
								'bucket-2columns' => THEME_FRAMEWORK_URI . 'assets/images/2-col-widget.png',
								'bucket-3columns' => THEME_FRAMEWORK_URI . 'assets/images/3-col-widget.png',
								'bucket-4columns' => THEME_FRAMEWORK_URI . 'assets/images/4-col-widget.png'
							));  

		/* Blocks End ------- */
		$of_options[] = array( 'type' => 'group_end' );

		/* =Advanced Settings
		----------------------------------------------- */
		$of_options[] = array( 'name' => __( 'Advanced', 'cyon' ),
							'type' => 'heading');

		/* Social Begin ------- */
		$of_options[] = array( 'name' => __( 'Social', 'cyon' ),
							'type' => 'group_begin');

		/* Social boxes */
		$of_options[] = array( 'name' => __( 'Social boxes', 'cyon' ),
							'id' => 'socialshareboxes',
							'std' => array('facebook','twitter','plus'),
							'type' => 'multicheck',
							'options' => array('facebook' => __( 'Facebook','cyon' ), 'twitter' => __( 'Twitter','cyon' ), 'plus' => __( 'Google+','cyon' ), 'pinterest' => __( 'Pinterest','cyon' ), 'mail' => __( 'Email','cyon' ), 'sharethis' => __( 'ShareThis','cyon' )));

		/* Share pages */
		$of_options[] = array( 'name' => __( 'Pages to appear the social boxes', 'cyon' ),
							'id' => 'socialshare',
							'std' => array('posts'),
							'type' => 'multicheck',
							'options' => array('posts' => __( 'Blog Posts','cyon' ), 'listings' => __( 'Blog Listings','cyon' ), 'pages' => __( 'Pages','cyon' )));

		/* Social End ------- */
		$of_options[] = array( 'type' => 'group_end');

		/* SEO Begin ------- */
		$of_options[] = array( 'name' => __( 'SEO', 'cyon' ),
							'type' => 'group_begin');

		/* Activate */
		$of_options[] = array( 'name' => __( 'Activate SEO', 'cyon' ),
							'desc' => __( 'Activates SEO options to all pages and posts in the admin.' ),
							'id' => 'seo_activate',
							'std' => 0,
							'folds' => 1,
							'type' => 'checkbox');

		/* Page title format */
		$of_options[] = array( 'name' => __( 'Page title format', 'cyon' ),
							'desc' => __( 'Accepts:' ).' {PAGETITLE}, {BLOGTITLE}, {BLOGTAGLINE}',
							'id' => 'seo_title_format',
							'std' => '{PAGETITLE} | {BLOGTITLE}',
							'fold' => 'seo_activate',
							'type' => 'text');

		/* SEO End ------- */
		$of_options[] = array( 'type' => 'group_end' );

		/* Scripts Begin ------- */
		$of_options[] = array( 'name' => __( 'Scripts Support', 'cyon' ),
							'type' => 'group_begin');

		/* Lightbox */
		$of_options[] = array( 'name' => __( 'Activate Fancybox', 'cyon' ),
							'desc' => __( 'Activates lightbox in all linked to images, this includes WP Gallery. Supports jpg, png, gif, and bmp' ),
							'std' => 1,
							'id' => 'lightbox_activate',
							'type' => 'checkbox');

		/* Responsive */
		$of_options[] = array( 'name' => __( 'Responsiveness', 'cyon' ),
							'desc' => __( 'Allow special styles for mobile devices' ),
							'std' => 0,
							'id' => 'responsive',
							'type' => 'checkbox');

		/* LazyLoad */
		$of_options[] = array( 'name' => __( 'LazyLoad', 'cyon' ),
							'desc' => __( 'Activates LazyLoad in all images' ),
							'std' => 1,
							'id' => 'lazyload',
							'type' => 'checkbox');

		/* Scripts End ------- */
		$of_options[] = array( 'type' => 'group_end' );

		/* Custom Scripts Begin ------- */
		$of_options[] = array( 'name' => __( 'Custom Scripts', 'cyon' ),
							'type' => 'group_begin');

		/* Header */
		$of_options[] = array( 'name' => __( 'Header', 'cyon' ),
							'desc' => __( 'Scripts and Links placed inside the head tag. Can have Google Analytics here.' ),
							'id' => 'header_scripts',
							'type' => 'textarea');

		/* Footer */
		$of_options[] = array( 'name' => __( 'Footer', 'cyon' ),
							'desc' => __( 'Scripts placed below the footer just before the end body tag.' ),
							'id' => 'footer_scripts',
							'type' => 'textarea');

		/* Custom Scripts End ------- */
		$of_options[] = array( 'type' => 'group_end' );

		/* =Testimonials
		----------------------------------------------- */
		$of_options[] = array( 'name' => __( 'Testimonials', 'cyon' ),
							'type' => 'heading');

		/* Create Begin ------- */
		$of_options[] = array( 'name' => __( 'Testimonial Manager', 'cyon' ),
							'type' => 'group_begin');

		/* Slider */
		$of_options[] = array( 'desc' => __( 'Unlimited testimonial with drag and drop sortings.' ),
							'id' => 'testimonials',
							'type' => 'testimonial');

		/* Create End ------- */
		$of_options[] = array( 'type' => 'group_end');


		/* =Admin
		----------------------------------------------- */
		$of_options[] = array( 'name' => __( 'Admin', 'cyon' ),
							'type' => 'heading');

		/* WP-admin Begin ------- */
		$of_options[] = array( 'name' => __( 'WP Admin', 'cyon' ),
							'type' => 'group_begin');

		/* Background login */
		$of_options[] = array( 'name' => __( 'Login background color', 'cyon' ),
							'id' => 'admin_login_bgcolor',
							'std' => '#fbfbfb',
							'type' => 'color');

		/* Updates */
		$of_options[] = array( 'name' => __( 'Updates', 'cyon' ),
							'id' => 'admin_updates',
							'std' => array('admin_plugin_updates'),
							'type' => 'multicheck',
							'options' => array('admin_core_updates'=>'Remove WP core updates', 'admin_plugin_updates'=>'Remove plugin updates') );

		/* WP-admin End ------- */
		$of_options[] = array( 'type' => 'group_end');

		/* Maintenance Begin ------- */
		$of_options[] = array( 'name' => __( 'Maintenance', 'cyon' ),
							'type' => 'group_begin');
		/* Backup */
		$of_options[] = array( 'name' => __( 'Backup and Restore Options', 'cyon' ),
							'desc' => __( 'You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.', 'cyon' ),
							'id' => 'of_backup',
							'type' => 'backup');

		/* Transfer */
		$of_options[] = array( 'name' => __( 'Transfer Theme Options Data', 'cyon' ),
							'desc' => __( 'You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options".', 'cyon' ),
							'id' => 'of_transfer',
							'type' => 'transfer');

		/* Maintenance End ------- */
		$of_options[] = array( 'type' => 'group_end');
	}
}
