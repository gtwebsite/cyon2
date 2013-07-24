<?php
if ( !defined('ABSPATH') )
	die('-1');

/* =Get Script and Styles
----------------------------------------------- */
if ( !function_exists( 'cyon_admin_scripts_styles' ) ){
function cyon_admin_scripts_styles() {
	wp_enqueue_script( 'cyon_custom_admin_script' );
	wp_enqueue_style( 'cyon_custom_admin_style' );
} }
add_action( 'admin_enqueue_scripts', 'cyon_admin_scripts_styles' );


/* =Adding MCE button
----------------------------------------------- */
if ( !function_exists( 'cyon_add_mce_button' ) ){
function cyon_add_mce_button() {
	/* Don't bother doing this stuff if the current user lacks permissions */
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
	 return;
 
   /* Add only in Rich Editor mode */
   if ( get_user_option('rich_editing') == 'true') {
	 add_filter("mce_external_plugins", "cyon_add_mce_button_plugin");
	 add_filter('mce_buttons', 'cyon_add_mce_button_register');
   }

	function cyon_add_mce_button_register($buttons) {
	   array_push($buttons, "separator", "cyon_plugin");
	   return $buttons;
	}
	 
	/* Load the TinyMCE plugin */
	function cyon_add_mce_button_plugin($plugin_array) {
	   $plugin_array['cyon_plugin'] = THEME_ASSETS_URI .'js/mce/editor_plugin.js';
	   return $plugin_array;
	}
} }
add_action('init', 'cyon_add_mce_button');


/* =Adding Meta Boxes
----------------------------------------------- */
global $cyon_meta_boxes, $data;

$prefix = 'cyon_';
$cyon_meta_boxes = array();

/* Page Settings */
$cyon_meta_boxes[] = array(
	'id' => 'settings',
	'title' => __('Page Settings', 'cyon'),
	'pages' => array('post','page'), // multiple post types, accept custom post types
	'context' => 'normal', // normal, advanced, side (optional)
	'fields' => array(
		array(
			'name' => __('Layout', 'cyon'),
			'id' => $prefix .'layout',
			'type' => 'radio',
			'std' => 'default', 
			'options' => array( // array of name, value pairs for radio options
				'default' => __('Default', 'cyon'),
				'general-1column' => __('1 Column', 'cyon'),
				'general-2left' => __('2 Columns Left', 'cyon'),
				'general-2right' => __('2 Columns Right', 'cyon')
			)
		),
		array(
			'name' => __('Background Image', 'cyon'),
			'id' => $prefix .'background',
			'type' => 'thickbox_image',
			'std' => ''
		)
	)
);

/* Post Format - Gallery */
$cyon_meta_boxes[] = array(
	'id' => 'gallery-settings',
	'title' => __('Gallery Settings', 'cyon'),
	'pages' => array('post'), // multiple post types, accept custom post types
	'context' => 'normal', // normal, advanced, side (optional)
	'fields' => array(
		array(
			'name' => __('Images Excerpt', 'cyon'),
			'id' => $prefix .'gallery_images',
			'type' => 'thickbox_image',
			'std' => ''
		)
	)
);

/* Post Format - Link */
$cyon_meta_boxes[] = array(
	'id' => 'link-settings',
	'title' => __('Link Settings', 'cyon'),
	'pages' => array('post'), // multiple post types, accept custom post types
	'context' => 'normal', // normal, advanced, side (optional)
	'fields' => array(
		array(
			'name' => __('URL', 'cyon'),
			'id' => $prefix .'link_url',
			'type' => 'text',
			'std' => ''
		)
	)
);

/* Post Format - Quote */
$cyon_meta_boxes[] = array(
	'id' => 'quote-settings',
	'title' => __('Quote Settings', 'cyon'),
	'pages' => array('post'), // multiple post types, accept custom post types
	'context' => 'normal', // normal, advanced, side (optional)
	'fields' => array(
		array(
			'name' => __('Author', 'cyon'),
			'id' => $prefix .'quote_author',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Position / Company', 'cyon'),
			'id' => $prefix .'quote_title',
			'type' => 'text',
			'std' => ''
		)
	)
);

/* Post Format - Audio */
$cyon_meta_boxes[] = array(
	'id' => 'audio-settings',
	'title' => __('Audio Settings', 'cyon'),
	'pages' => array('post'), // multiple post types, accept custom post types
	'context' => 'normal', // normal, advanced, side (optional)
	'fields' => array(
		array(
			'name' => __('URL', 'cyon'),
			'id' => $prefix .'audio_url',
			'type' => 'text',
			'std' => ''
		)
	)
);

/* Post Format - Video */
$cyon_meta_boxes[] = array(
	'id' => 'video-settings',
	'title' => __('Video Settings', 'cyon'),
	'pages' => array('post'), // multiple post types, accept custom post types
	'context' => 'normal', // normal, advanced, side (optional)
	'fields' => array(
		array(
			'name' => __('URL', 'cyon'),
			'id' => $prefix .'video_url',
			'type' => 'text',
			'desc' => __('Supports Youtube, Vimeo, Metacafe, Dailymotion, Twitvid and local video files such as mp4, m4v, mov, wmv, flv, webm, ogv', 'cyon'),
			'std' => ''
			)
	)
);

if($data['seo_activate']==1){
	$cyon_meta_boxes[] = array(
		// SEO
		'id' => 'seo',
		'title' => __('SEO Options'),
		'pages' => array('post','page'), // multiple post types, accept custom post types
		'context' => 'normal', // normal, advanced, side (optional)
		'fields' => array(
			array(
				'name' => __('Page Title'),
				'id' => $prefix .'meta_title',
				'type' => 'text',
				'std' => ''
			),
			array(
				'name' => __('Description'),
				'id' => $prefix .'meta_desc',
				'type' => 'textarea',
				'std' => ''
			),
			array(
				'name' => __('Keywords'),
				'id' => $prefix .'meta_keywords',
				'type' => 'text',
				'std' => ''
			),
			array(
				'name' => __('Hide from search engines'),
				'id' => $prefix .'robot',
				'type' => 'checkbox'
			)
		)
	);
}

/* Register Post/Page metaboxes */
if( !function_exists( 'cyon_register_meta_boxes' ) ){
function cyon_register_meta_boxes(){
	global $cyon_meta_boxes;
	if ( class_exists( 'RW_Meta_Box' ) ){
		foreach ( $cyon_meta_boxes as $cyon_meta_box ){
			new RW_Meta_Box( $cyon_meta_box );
		}
	}
} }
add_action( 'admin_init', 'cyon_register_meta_boxes' );

/* =Adding Taxonomy Meta boxes on Post Categories
----------------------------------------------- */
if ( class_exists( 'Tax_Meta_Class' ) ){
	$prefix = 'cyon_';
	$config = array(
		'id' => 'tax_meta_category',         
		'title' => __('Category Meta Box', 'cyon'),       
		'pages' => array('category'), 
		'context' => 'normal',        
		'fields' => array(),          
		'local_images' => false,    
		'use_with_theme' => false 
	);
	$new_cat_meta = new Tax_Meta_Class($config);
	
	$new_cat_meta->addSelect( $prefix.'cat_layout',
					array(
							'default' 			=> __('Default', 'cyon'),
							'general-1column'	=> __('1 Column', 'cyon'),
							'general-2left'		=> __('2 Columns Left', 'cyon'),
							'general-2right'	=> __('2 Columns Right', 'cyon')
					),
					array(
							'name' => __('Page Layout', 'cyon'),
							'std'=> array('default')
					));

	$new_cat_meta->addSelect( $prefix.'cat_layout_listing',
					array(
							'default' 			=> __('Default', 'cyon'),
							'list-1column'		=> __('1 Column', 'cyon'),
							'list-2columns'		=> __('2 Columns', 'cyon'),
							'list-3columns'		=> __('3 Columns', 'cyon'),
							'list-4columns'		=> __('4 Columns', 'cyon')
					),
					array(
							'name' => __('Listing Layout', 'cyon'),
							'std'=> array('default')
					));
				
	$new_cat_meta->addImage( $prefix.'cat_image', 
					array('name'=> __('Image Banner'), 'cyon' ));

	$new_cat_meta->addImage( $prefix.'cat_background', 
					array('name'=> __('Background Image', 'cyon') ));
				
	$new_cat_meta->Finish();
}


/* =Register new widgets
----------------------------------------------- */
if(!function_exists('cyon_custom_widgets_init')) {
function cyon_custom_widgets_init(){
	global $data;
	$widgets = $data['widgets'];
	if(count($widgets)>1){
		foreach ($widgets as $widget) {
			if(strlen($widget['title'])>0){
				register_sidebar( array(
					'name' => __( $widget['title'] ),
					'id' => 'cyon-'.str_replace(' ', '-', strtolower($widget['title'])),
					'before_widget' => '<aside id="%1$s" class="widget %2$s">',
					'after_widget' => '</aside>',
					'before_title' => '<h3 class="widget-title">',
					'after_title' => '</h3>',
				) );
			}
		}
	}
} }
add_action( 'widgets_init', 'cyon_custom_widgets_init' );


/* =Remove core and plugin updates
----------------------------------------------- */
if($data['admin_updates']['admin_core_updates']==1){
	/* 2.3 to 2.7 */
	add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 );
	add_filter( 'pre_option_update_core', create_function( '$a', "return null;" ) );
	
	/* 2.8 to 3.0 */
	remove_action( 'wp_version_check', 'wp_version_check' );
	remove_action( 'admin_init', '_maybe_update_core' );
	add_filter( 'pre_transient_update_core', create_function( '$a', "return null;" ) );
	
	/* 3.0 */
	add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );
}
if($data['admin_updates']['admin_plugin_updates']==1){
	/* 2.3 to 2.7 */
	add_action( 'admin_menu', create_function( '$a', "remove_action( 'load-plugins.php', 'wp_update_plugins' );") );
	add_action( 'admin_init', create_function( '$a', "remove_action( 'admin_init', 'wp_update_plugins' );"), 2 );
	add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_update_plugins' );"), 2 );
	add_filter( 'pre_option_update_plugins', create_function( '$a', "return null;" ) );
	
	/* 2.8 to 3.0 */
	remove_action( 'load-plugins.php', 'wp_update_plugins' );
	remove_action( 'load-update.php', 'wp_update_plugins' );
	remove_action( 'admin_init', '_maybe_update_plugins' );
	remove_action( 'wp_update_plugins', 'wp_update_plugins' );
	add_filter( 'pre_transient_update_plugins', create_function( '$a', "return null;" ) );
	
	/* 3.0 */
	remove_action( 'load-update-core.php', 'wp_update_plugins' );
	add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );
}

