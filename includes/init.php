<?php
if ( !defined('ABSPATH') )
	die('-1');

/* =Cyon - A WordPress theme development framework.
----------------------------------------------- */
class Cyon {

	/**
	* Constructor method for the Cyon class. 
	*/
	function __construct() {
		global $cyon;

		/* Set up an empty class for the global $cyon object. */
		$cyon = new stdClass;
		
		/* Define constants */
		add_action( 'after_setup_theme', array( &$this, 'constants' ), 1 );

		/* Load the WP theme setup */
		add_action( 'after_setup_theme', array( &$this, 'theme_support' ), 2 );

		/* Load framework functions */
		add_action( 'after_setup_theme', array( &$this, 'functions' ), 3 );

		/* Registers new scripts */
		add_action( 'init', array( &$this, 'registers' ), 1 );
		
	}

	/**
	* Define contants
	*/
	function constants() {

		/* Sets the framework version number. */
		define( 'CYON_VERSION', '2.0.0' );

		/* Sets the path to the parent theme directory. */
		define( 'THEME_DIR', get_template_directory() );

		/* Sets the path to the parent theme directory URI. */
		define( 'THEME_URI', get_template_directory_uri() );

		/* Sets the path to the functions. */
		define( 'THEME_FUNC', trailingslashit( THEME_DIR . '/includes/functions' ) );

		/* Sets the path to the framework directory. */
		define( 'THEME_FRAMEWORK_DIR', trailingslashit( THEME_DIR . '/includes/framework' ) );

		/* Sets the path to the framework URI. */
		define( 'THEME_FRAMEWORK_URI', trailingslashit( THEME_URI . '/includes/framework' ) );

		/* Sets the path to the assets directory. */
		define( 'THEME_ASSETS_DIR', trailingslashit( THEME_DIR . '/assets' ) );

		/* Sets the path to the assets URI. */
		define( 'THEME_ASSETS_URI', trailingslashit( THEME_URI . '/assets' ) );

		/* Sets the path to the metabox directory. */
		define( 'RWMB_DIR', trailingslashit( THEME_FUNC . '/meta-box' ) );

		/* Sets the path to the metabox directory URI. */
		define( 'RWMB_URL', trailingslashit( THEME_URI . '/includes/functions/meta-box' ) );

		/* Sets the path to the taxmeta directory. */
		define( 'TAXMB_DIR', trailingslashit( THEME_FUNC . '/tax-meta-class' ) );

		/* Sets the path to the taxmeta directory URI. */
		define( 'TAXMB_URL', trailingslashit( THEME_URI . '/includes/functions/tax-meta-class' ) );

	}
	
	/**
	* WP theme setup
	*/
	function theme_support() {
		
		/* Languages */
		load_theme_textdomain( 'cyon', THEME_DIR . '/languages' );

		/* This theme styles the visual editor to match the theme style. */ 
		add_editor_style();

		/* Allow Text Widget do shortcode */ 
		add_filter( 'widget_text', 'shortcode_unautop' );
		add_filter( 'widget_text', 'do_shortcode' );
		
		/* Add default posts and comments RSS feed links to <head>. */
		add_theme_support( 'automatic-feed-links' );

		/* This theme supports a variety of post formats. */
		add_theme_support( 'post-formats', array( 'image', 'link', 'quote', 'video', 'audio', 'gallery' ) );

		/* This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images */
		add_theme_support( 'post-thumbnails' );
		
		/* This theme uses wp_nav_menu() in one location. */
		register_nav_menus( array(
			'main-menu' => __( 'Main Menu', 'cyon' ),
			'footer-menu' => __( 'Footer Menu', 'cyon' ),
		) );

		/* Add Excerpt on Pages. */
		add_post_type_support( 'page', 'excerpt' );

	}

	/**
	* Core functions
	*/
	function functions() {
		global $data;
		
		/* Load Slightly Modded Options Framework - http://aquagraphite.com/2011/09/29/slightly-modded-options-framework/ */
		require_once( THEME_FRAMEWORK_DIR. '/index.php' );
		
		/* Remove default theme options and replace with new Cyon */
		require_once( THEME_FUNC. '/function-options.php' );

		/* Meta Box for post/page custom fields - http://www.deluxeblogtips.com/ */
		require_once( THEME_FUNC. '/meta-box/meta-box.php' );
		
		/* Tax Meta Class for category custom fields - http://en.bainternet.info/2012/wordpress-taxonomies-extra-fields-the-easy-way/ */
		require_once( THEME_FUNC. '/tax-meta-class/Tax-meta-class.php' );

		/* Register Styles and Scripts */
		require_once( THEME_FUNC. '/function-hooks.php' );

		/* Admin */
		require_once( THEME_FUNC. '/function-admin.php' );

		/* Core Functions */
		require_once( THEME_FUNC. '/function-core.php' );

		/* Attached actions */
		require_once( THEME_FUNC. '/function-actions.php' );
	
		/* Woocommerce */
		if ( class_exists( 'Woocommerce' ) ) {
			require_once (THEME_DIR . '/includes/functions/function-woocommerce.php');	
		}
	}
	
	function registers(){
		/* Javascripts */
		wp_register_script('cyon_jquery_all',THEME_ASSETS_URI . 'js/jquery.all.js',array('jquery'),'1.0.0');
		wp_register_script('cyon_jquery_custom',THEME_ASSETS_URI . 'js/jquery.custom.js',array('cyon_jquery_all'),'1.0.0');
		wp_register_script('gmap_api','http://maps.google.com/maps/api/js?sensor=false',array('jquery'),'1.0.0',false);
		wp_register_script('gmap',THEME_ASSETS_URI . 'js/jquery.gmap.min.js',array('jquery','gmap_api'),'3.3.3');
		wp_register_script('mediaelement',THEME_ASSETS_URI . 'js/jquery.mediaelement.min.js',array('jquery'),'2.12.0');
		wp_register_script('transit',THEME_ASSETS_URI . 'js/jquery.transit.js',array('jquery'),'0.9.9');
		wp_register_script('supersized',THEME_ASSETS_URI . 'js/jquery.supersized.js',array('jquery'),'3.2.7');
		
		/* Styles */
		wp_register_style('cyon_style_responsive', THEME_ASSETS_URI . 'css/style-responsive.css',array(),'1.0.0',false);
		wp_register_style('cyon_style_woocommerce', THEME_ASSETS_URI . 'css/style-woocommerce.css',array(),'1.0.0',false);
		wp_register_style('cyon_style_woocommerce_responsive', THEME_ASSETS_URI . 'css/style-woocommerce-responsive.css',array(),'1.0.0',false);
		wp_register_style('mediaelement_style',THEME_ASSETS_URI . 'css/mediaelementplayer.min.css',array(),'2.12.0');
		
		/* Admin */
		wp_register_script('cyon_custom_admin_script', THEME_ASSETS_URI . 'js/jquery.admin.js', array('jquery'),'1.0.0');
		wp_register_style('cyon_custom_admin_style', THEME_ASSETS_URI . 'css/style-admin.css' );
	}
	
}
