<?php
if ( !defined('ABSPATH') )
	die('-1');

/* =Head
----------------------------------------------- */
add_action('wp_head',				'cyon_header_meta',5);
add_action('wp_head',				'cyon_header_styles',10);
add_action('wp_head',				'cyon_header_scripts',20);
add_action('wp_enqueue_scripts',	'cyon_register_scripts_styles',100);
add_filter('wp_title', 				'cyon_wp_title' );

/* =Header
----------------------------------------------- */
add_action('cyon_header',			'cyon_header_hook',10);
add_action('cyon_header_wrapper',	'cyon_header_columns',10);
add_action('cyon_header_wrapper',	'cyon_header_logo',20);
add_action('cyon_header_wrapper',	'cyon_header_mainnav',30);

/* =Body
----------------------------------------------- */
add_action('cyon_body',				'cyon_body_hook',10);
add_action('cyon_body_wrapper',		'cyon_breadcrumb',10);
add_action('cyon_body_wrapper',		'cyon_primary_hook',20);
add_action('cyon_body_wrapper',		'cyon_secondary_hook',30);
add_filter('body_class',			'cyon_replace_body_class');

/* =Primary
----------------------------------------------- */
add_action('cyon_primary',			'cyon_primary_archive_header',10);
add_action('cyon_primary',			'cyon_primary_content',20);
add_action('cyon_primary',			'cyon_content_nav',30);

/* =Secondary
----------------------------------------------- */
add_action('cyon_secondary',		'cyon_secondary_content',10);

/* =Article
----------------------------------------------- */
add_action('cyon_post_header',		'cyon_post_header_title',10);
add_action('cyon_post_header',		'cyon_post_header_meta',20);
add_action('cyon_post_header',		'cyon_post_content_featured',30);
add_action('cyon_post_content',		'cyon_post_content_main',10);
add_action('cyon_post_content',		'cyon_readmore',20);
add_action('cyon_post_content',		'cyon_socialshare',30);
add_action('cyon_post_footer',		'cyon_author',10);
add_action('cyon_post_footer',		'cyon_post_comments',20);
add_filter('use_default_gallery_style', '__return_false' );
add_filter('post_class',			'cyon_post_layout_class');
add_filter( 'wp_get_attachment_image_attributes', 'cyon_wp_get_attachment_image_attributes_lazyload', 10, 2 );

/* =Homepage
----------------------------------------------- */
add_action('cyon_home_content','cyon_homepage_blocks',10);

/* =Footer
----------------------------------------------- */
add_action('cyon_footer',			'cyon_footer_hook',10);
add_action('cyon_footer_wrapper',	'cyon_footer_columns',10);
add_action('cyon_footer_wrapper',	'cyon_footer_copyright',20);
add_action('cyon_footer_wrapper',	'cyon_footer_subfooter',30);
add_action('cyon_footer_wrapper',	'cyon_footer_backtotop',40);

/* =Foot
----------------------------------------------- */
add_action('wp_footer',				'cyon_footer_jquery',100);
add_action('wp_footer',				'cyon_footer_scripts',110);

/* =Admin
----------------------------------------------- */
add_action('init',					'cyon_add_mce_button');
add_action('widgets_init', 			'cyon_widgets_init');
add_action('login_head', 			'cyon_admin_login');
add_filter('login_headerurl', 		create_function(false,"return '".get_bloginfo('wpurl')."';"));
add_filter('login_headertitle', 	create_function(false,"return 'Back to ".esc_attr( get_bloginfo( 'name', 'display' ) )."';"));

