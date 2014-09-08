<?php

/* =Declare Support
----------------------------------------------- */
add_theme_support( 'woocommerce' );


/* =Woocommerce initiate
----------------------------------------------- */
if(!function_exists('cyon_woo_init')) {
function cyon_woo_init(){

	/* Remove sidebar */
	remove_action('woocommerce_sidebar', 				'woocommerce_get_sidebar', 10 );

	/* Replace breadcrumb */
	remove_action('woocommerce_before_main_content', 	'woocommerce_breadcrumb',20);

	/* Add scripts */
	if( !is_admin() ) {
		add_action('wp_enqueue_scripts',					'cyon_register_scripts_styles_woocommerce',110);
		add_action('wp_footer', 							'cyon_woo_header_js_css_hook',130);
		add_filter( 'woocommerce_enqueue_styles', '__return_false' );
	}

	/* Replace main wrapper */
	remove_action('woocommerce_before_main_content', 	'woocommerce_output_content_wrapper', 10);
	remove_action('woocommerce_after_main_content', 	'woocommerce_output_content_wrapper_end', 10);
	add_action('woocommerce_before_main_content', 		'cyon_woocommerce_output_content_wrapper', 10);
	add_action('woocommerce_after_main_content', 		'cyon_woocommerce_output_content_wrapper_end', 10);
	
	/* Add product wrapper */
	add_action('woocommerce_before_shop_loop_item',		'cyon_woocommerce_before_shop_loop_item', 9 );
	add_action('woocommerce_after_shop_loop_item',		'cyon_woocommerce_after_shop_loop_item', 999 );
	
	/* Override related products */
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
	add_action( 'woocommerce_after_single_product_summary', 'cyon_woocommerce_output_related_products' , 20, 0 );
	
	/* Override upsell products */
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	add_action( 'woocommerce_after_single_product_summary', 'cyon_woocommerce_upsell_display', 15 );
	
	/* Thumbnails */
	if(get_option('woocommerce_frontend_css') == 'no'){
		add_filter( 'woocommerce_product_thumbnails_columns', 'cyon_woocommerce_product_thumbnails_columns' );
	}
	
	/* Product Columns */
	add_action('wp_head', 'cyon_woocommerce_reset_loop');
	add_filter('cyon_the_list_layout','cyon_woocommerce_product_cols');

} }

if ( ! is_admin() || defined('DOING_AJAX') ) {
	add_action( 'woocommerce_init', 'cyon_woo_init' );
}


/* =Woocommerce only CSS
----------------------------------------------- */
if(!function_exists('cyon_register_scripts_styles_woocommerce')) {
function cyon_register_scripts_styles_woocommerce(){
	global $data;
	wp_enqueue_style('cyon_style_woocommerce');
	if($data['responsive']==1){
		wp_enqueue_style('cyon_style_woocommerce_responsive'); 
	}
} }


/* =Woocommerce only JS
----------------------------------------------- */
if(!function_exists('cyon_woo_header_js_css_hook')) {
function cyon_woo_header_js_css_hook(){
	global $data;
?>
	<?php if(is_shop() || is_product_category() || is_product_tag() || is_product()){  ?>
	<script type="text/javascript">
			<?php if($data['woocommerce_product_cols_masonry']){ ?>
		// Isotope Support
		jQuery(document).ready(function(){
			jQuery('#primary li.product').removeClass('span<?php echo 12/cyon_get_list_layout(); ?>');
		});
		jQuery(window).load(function(){
			jQuery('.blog-list-masonry #content').imagesLoaded(function(){
				jQuery('#primary ul.products').isotope({
					itemSelector: 'li.product',
					animationOptions: {
						duration: 750,
						easing: 'linear',
						queue: false
					}
				});
				checkProductMasonry();
				jQuery(window).trigger('scroll');
			});
		});
		function checkProductMasonry() {
			var pagesize = jQuery('.page_wrapper').width();
			if (pagesize <= 480) {
				jQuery('#primary li.type-product').width(jQuery('#primary').width());
			}else if (pagesize <= 974) {
				jQuery('#primary li.type-product').width((jQuery('#primary').width() / 2)-2);
			}else{
				jQuery('#primary li.type-product').width((jQuery('#primary').width() / <?php echo cyon_get_list_layout(); ?>)-3);
			}
			jQuery(window).trigger('scroll');
		}
		jQuery(window).resize(checkProductMasonry);
		jQuery(window).scroll(function(){
			jQuery('#primary ul.products').isotope('reLayout');
		});
			<?php }else{ ?>
		jQuery(document).ready(function(){
			jQuery('#primary ul.products').addClass('row-fluid');
		});
			<?php } ?>
		<?php if(is_product()){ ?>
		jQuery(document).ready(function(){
			<?php if(get_option('woocommerce_enable_lightbox') == 'no'){ ?>
			jQuery('.zoom').fancybox({
				openEffect	: 'elastic',
				closeEffect	: 'elastic',
				helpers : {
					title : {
						type : 'over'
					}
				}
			});
			<?php } ?>
			<?php if(cyon_get_list_layout()!=1) { ?>
				jQuery('#primary div[itemscope]').removeClass('span<?php echo 12/cyon_get_list_layout(); ?>');
			<?php } ?>
		});
		<?php } ?>
	</script>
	<?php } ?>
	<style media="all" type="text/css">
		.blockOverlay {
			background:<?php echo $data['background_color']; ?>!important;
		}

	</style>
<?php } }
 

/* =Wrapper top
----------------------------------------------- */
if(!function_exists('cyon_woocommerce_output_content_wrapper')) {
function cyon_woocommerce_output_content_wrapper() { ?>
		<div id="main" class="<?php echo cyon_get_page_layout(); ?>">
			<div class="wrapper clearfix">
				<?php cyon_breadcrumb(); ?>
				<!-- Center -->
				<div id="primary">
<?php } }
 

if(!function_exists('cyon_woocommerce_before_shop_loop_item')) {
function cyon_woocommerce_before_shop_loop_item() { ?>
	<div class="product-wrapper">
<?php } }


/* =Wrapper bottom
----------------------------------------------- */
if(!function_exists('cyon_woocommerce_output_content_wrapper_end')) {
function cyon_woocommerce_output_content_wrapper_end() { ?>
				</div>
				<?php cyon_secondary_hook(); ?>
			</div>
		</div>
<?php } }

if(!function_exists('cyon_woocommerce_after_shop_loop_item')) {
function cyon_woocommerce_after_shop_loop_item() { ?>
	</div>
<?php } }


/* =Related Products
----------------------------------------------- */
if(!function_exists('cyon_woocommerce_output_related_products')) {
function cyon_woocommerce_output_related_products() { 
	global $data;
	woocommerce_related_products($data['woocommerce_product_cols'],$data['woocommerce_product_cols']);
} }


/* =Upsell Products
----------------------------------------------- */
if(!function_exists('cyon_woocommerce_upsell_display')) {
function cyon_woocommerce_upsell_display() {
	global $data;
	woocommerce_upsell_display($data['woocommerce_product_cols'],$data['woocommerce_product_cols']);
} }


/* =Display 24 products per page
----------------------------------------------- */
if(!function_exists('cyon_loop_shop_per_page')) {
function cyon_loop_shop_per_page($cols){
	global $data;
	return $data['woocommerce_product_per_page'];
} }

add_filter('loop_shop_per_page', 'cyon_loop_shop_per_page');


/* =Display rows
----------------------------------------------- */
if(!function_exists('cyon_woocommerce_product_cols')) {
function cyon_woocommerce_product_cols($layout){
	global $data;
	if(is_woocommerce()){
		return $data['woocommerce_product_cols'];
	}else{
		return $layout;
	}
} }

if(!function_exists('cyon_woocommerce_reset_loop')) {
function cyon_woocommerce_reset_loop(){
	global $woocommerce_loop, $data;
	$woocommerce_loop['columns'] = $data['woocommerce_product_cols'];
} }


/* =Thumbnail
----------------------------------------------- */
if(!function_exists('cyon_woocommerce_product_thumbnails_columns')) {
function cyon_woocommerce_product_thumbnails_columns(){
	return 5;
} }

/* =Product Tabs
----------------------------------------------- */
if(!function_exists('cyon_woocommerce_output_product_data_tabs')) {
function cyon_woocommerce_output_product_data_tabs(){
	$tabs = apply_filters( 'woocommerce_product_tabs', array() );
	if ( ! empty( $tabs ) ) : ?>
		<div class="tabs">
			<ul class="tab_nav">
				<?php foreach ( $tabs as $key => $tab ) : ?>
	
					<li class="<?php echo $key ?>_tab">
						<a href="#tab-<?php echo $key ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?></a>
					</li>
	
				<?php endforeach; ?>
			</ul>
			<div class="panel">
			<?php foreach ( $tabs as $key => $tab ) : ?>
	
				<div class="tab-content" id="tab-<?php echo $key ?>">
					<?php call_user_func( $tab['callback'], $key, $tab ) ?>
				</div>
	
			<?php endforeach; ?>
			</div>
		</div>
	<?php
	endif;
} }

/* Total Cart Shortcode
use [woocart]
----------------------------------------------- */
if(!function_exists('cyon_woocart')) {
function cyon_woocart( $atts, $content = null ) {
	global $woocommerce;
	return __('Your total cart:','cyon').' <a class="cart-contents" href="'.$woocommerce->cart->get_cart_url().'" title="'.__('View your shopping cart', 'woothemes').'">'.sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count).' ('.$woocommerce->cart->get_cart_total().')</a>';
} }

add_shortcode('woocart','cyon_woocart');


/* =Categories widget
----------------------------------------------- */
/* Starting Class */
class CyonWoocommerceCategoriesWidget extends WP_Widget {

	var $cat_ancestors;
	var $current_cat;

	// Creating your widget
	function CyonWoocommerceCategoriesWidget(){
		$widget_ops = array('classname' => 'CyonWoocommerceCategoriesWidget', 'description' => __('Displays a categories with images/description') );
		$this->WP_Widget('CyonWoocommerceCategoriesWidget', __('Cyon WooCommerce Categories'), $widget_ops);
	}
 
 	// Widget form in WP Admin
	function form($instance){
		// Start adding your fields here
		$instance = wp_parse_args( (array) $instance, array(
			'title' 		=> __('Categories'),
			'orderby' 		=> '',
			'image' 		=> true,
			'image_size'	=> 'thumbnail',
			'description'	=> true,
			'count' 		=> ''
		) );
		$title = $instance['title'];
		$orderby = isset( $instance['orderby'] ) ? $instance['orderby'] : 'order';
		$image_size = isset( $instance['image_size'] ) ? $instance['image_size'] : 'thumbnail';
		$image = isset($instance['image']) ? (bool) $instance['image'] :false;
		$description = isset($instance['description']) ? (bool) $instance['description'] :false;
		$count = isset($instance['count']) ? (bool) $instance['count'] :false;

		?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Order by:', 'woocommerce') ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id('orderby') ); ?>" name="<?php echo esc_attr( $this->get_field_name('orderby') ); ?>">
				<option value="order" <?php selected($orderby, 'order'); ?>><?php _e('Category Order', 'woocommerce'); ?></option>
				<option value="name" <?php selected($orderby, 'name'); ?>><?php _e('Name', 'woocommerce'); ?></option>
			</select></p>
			<p>
				<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('image') ); ?>" name="<?php echo esc_attr( $this->get_field_name('image') ); ?>"<?php checked( $image ); ?> /> <label for="<?php echo $this->get_field_id('image'); ?>"><?php _e( 'Show image' ); ?></label><br />
				<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('description') ); ?>" name="<?php echo esc_attr( $this->get_field_name('description') ); ?>"<?php checked( $description ); ?> /> <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e( 'Show Description' ); ?></label><br />
				<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('count') ); ?>" name="<?php echo esc_attr( $this->get_field_name('count') ); ?>"<?php checked( $count ); ?> /> <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts', 'woocommerce' ); ?></label>
			</p>
			<p><label for="<?php echo $this->get_field_id('image_size'); ?>"><?php _e('Image Size:') ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id('image_size') ); ?>" name="<?php echo esc_attr( $this->get_field_name('image_size') ); ?>">
				<option value="thumbnail" <?php selected($image_size, 'thumbnail'); ?>><?php _e('Thumbnail'); ?></option>
				<option value="medium" <?php selected($image_size, 'medium'); ?>><?php _e('Medium'); ?></option>
				<option value="large" <?php selected($image_size, 'large'); ?>><?php _e('Large'); ?></option>
			</select></p>
		<?php
	}

	// Saving your widget form
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		// Override new values of each fields
		$instance['title'] = $new_instance['title'];
		$instance['orderby'] = strip_tags($new_instance['orderby']);
		$instance['image'] = !empty($new_instance['image']) ? 1 : 0;
		$instance['image_size'] = strip_tags($new_instance['image_size']);
		$instance['description'] = !empty($new_instance['description']) ? 1 : 0;
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
		return $instance;
	}

	// Displays your widget on the front-end
	function widget($args, $instance){
		extract($args, EXTR_SKIP);
		global $wp_query, $post, $woocommerce;

		// Start widget
		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		$orderby = isset($instance['orderby']) ? $instance['orderby'] : 'order';
		$image = $instance['image'] ? '1' : '0';
		$image_size = isset($instance['image_size']) ? $instance['image_size'] : 'thumbnail';
		$description = $instance['description'] ? '1' : '0';
		$count = $instance['count'] ? '1' : '0';
	
		$this->current_cat = false;
		$this->cat_ancestors = array();
		
		$cat_args = array('show_count' => $count, 'hierarchical' => 0, 'taxonomy' => 'product_cat');
		if ( $orderby == 'order' ) {
			$cat_args['menu_order'] = 'asc';
		} else {
			$cat_args['orderby'] = 'title';
		}
	
		if (!empty($title)){
			echo $before_title . $title . $after_title;
		}

		include_once( 'class-product-cat-list-walker.php' );

		$cat_args['walker'] 			= new Cyon_WC_Product_Cat_List_Walker;
		$cat_args['title_li'] 			= '';
		$cat_args['show_children_only']	= 1;
		$cat_args['pad_counts'] 		= 1;
		$cat_args['image'] 				= $image;
		$cat_args['description'] 		= $description;
		$cat_args['image_size'] 		= $image_size;
		$cat_args['show_option_none'] 	= __('No product categories exist.', 'woocommerce');
		$cat_args['current_category']	= ( $this->current_cat ) ? $this->current_cat->term_id : '';
		$cat_args['current_category_ancestors']	= $this->cat_ancestors;

		echo '<ul class="cyon-product-categories size-'.$image_size.'">';

    	// Widget code here
		wp_list_categories( apply_filters( 'woocommerce_product_categories_widget_args', $cat_args ) );
 
		// End widget
		echo '</ul>';
		echo $after_widget;
	}
	
	
}

/* Adding your widget to WordPress */
add_action( 'widgets_init', create_function('', 'return register_widget("CyonWoocommerceCategoriesWidget");') );


/* Total Cart Widget AJAX */
if(!function_exists('cyon_woocommerce_header_add_to_cart_fragment')) {
function cyon_woocommerce_header_add_to_cart_fragment($fragments){
	global $woocommerce;
	ob_start();
	?>
		<a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>"><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?> (<?php echo $woocommerce->cart->get_cart_total(); ?>)</a>
	<?php
	$fragments['a.cart-contents'] = ob_get_clean();
	return $fragments;
} }

add_filter('add_to_cart_fragments', 'cyon_woocommerce_header_add_to_cart_fragment');

