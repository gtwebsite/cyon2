<?php
if ( !defined('ABSPATH') )
	die('-1');

/* =Custom Hooks
----------------------------------------------- */
if(!function_exists('cyon_header')) {
function cyon_header() {
    do_action('cyon_header');
} }

if(!function_exists('cyon_header_wrapper')) {
function cyon_header_wrapper() {
    do_action('cyon_header_wrapper');
} }

if(!function_exists('cyon_body')) {
function cyon_body() {
    do_action('cyon_body');
} }

if(!function_exists('cyon_body_wrapper')) {
function cyon_body_wrapper() {
    do_action('cyon_body_wrapper');
} }

if(!function_exists('cyon_primary')) {
function cyon_primary() {
    do_action('cyon_primary');
} }

if(!function_exists('cyon_secondary')) {
function cyon_secondary() {
    do_action('cyon_secondary');
} }

if(!function_exists('cyon_footer')) {
function cyon_footer() {
    do_action('cyon_footer');
} }

if(!function_exists('cyon_footer_wrapper')) {
function cyon_footer_wrapper() {
    do_action('cyon_footer_wrapper');
} }

if(!function_exists('cyon_home_content')) {
function cyon_home_content() {
    do_action('cyon_home_content');
} }

if(!function_exists('cyon_post_header')) {
function cyon_post_header() {
    do_action('cyon_post_header');
} }

if(!function_exists('cyon_post_content')) {
function cyon_post_content() {
    do_action('cyon_post_content');
} }

if(!function_exists('cyon_post_footer')) {
function cyon_post_footer() {
    do_action('cyon_post_footer');
} }

if(!function_exists('cyon_offline_header')) {
function cyon_offline_header() {
    do_action('cyon_offline_header');
} }

if(!function_exists('cyon_offline_footer')) {
function cyon_offline_footer() {
    do_action('cyon_offline_footer');
} }


/* =Include all shortcodes
----------------------------------------------- */
$shortcode_path = THEME_DIR . '/includes/shortcodes/';

if ( is_dir($shortcode_path) ) {
	if ($shortcode_path = opendir($shortcode_path) ) { 
		while ( ($shortcode_path_file = readdir($shortcode_path)) !== false ) {
			if(strstr($shortcode_path_file,'shortcode-') !== false)	{
				include_once(THEME_DIR . '/includes/shortcodes/'.$shortcode_path_file);
			}
		}    
	}
}


/* =Include all widgets
----------------------------------------------- */
$widget_path = THEME_DIR . '/includes/widgets/';

if ( is_dir($widget_path) ) {
	if ($widget_path = opendir($widget_path) ) { 
		while ( ($widget_path_file = readdir($widget_path)) !== false ) {
			if(strstr($widget_path_file,'widget-') !== false)	{
				include_once(THEME_DIR . '/includes/widgets/'.$widget_path_file);
			}
		}    
	}
}