<?php

if ( !defined('ABSPATH') )
	die('-1');


/* =Newsletter
use [newsletter email="" name="no"] xxx [/newsletter]
----------------------------------------------- */
function cyon_newsletter( $atts, $content = null ) {
	$nonce = wp_create_nonce('cyon_newsletter_nonce');
	$atts = shortcode_atts(
		array(
			email	=> get_bloginfo('admin_email'),
			name	=> 'no',
			classname => ''
		), $atts);
	$html = '<div class="cyon-newsletter newsletter-shortcode '.$atts['classname'].'"><form action="" method="post" class="cyonform">';
	$html .= '<fieldset>';
	if($content!=''){
		$html .= '<legend>'.$content.'</legend>';
	}
	$html .= '<div class="box hide-text"></div><input type="hidden" class="nonce" name="nonce" value="'.$nonce.'" /><input type="hidden" class="emailto" name="emailto" value="'.$atts['email'].'" />';
	if($atts['name']=='yes'){
		$html .= '<p><label for="newsletter_name">'.__('Name','cyon').':</label> <input type="text" id="newsletter_name" name="name" placeholder="'.__('Name','cyon').'" /></p>';
	}
	$html .= '<p><label for="newsletter_email">'.__('Email','cyon').':</label> <input type="email" id="newsletter_email" name="email" placeholder="'.__('Email','cyon').'" /></p>';
	$html .= '<button type="submit" name="newsletter_submit">'.__('Submit','cyon').'</button>';
	$html .= '</fieldset>';
	$html .= '</form></div>';
	ob_start();
		add_action('wp_footer','cyon_newsletter_ajax');
		add_action('wp_ajax_cyon_newsletter_action', 'cyon_newsletter_email');
		add_action('wp_ajax_nopriv_cyon_newsletter_action', 'cyon_newsletter_email');
	ob_get_clean();
	return $html;
}
add_shortcode('newsletter','cyon_newsletter'); 

if(!function_exists('cyon_newsletter_ajax')) {
function cyon_newsletter_ajax(){ ?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('.cyon-newsletter form').each(function(){
					jQuery(this).submit(function(){
						if(jQuery(this).find('input[type=email]').val()=='') {
							jQuery(this).find('.box').addClass('box-red').removeClass('box-green').text('<?php _e('Please enter your email address.','cyon'); ?>').removeClass('hide-text');
							jQuery(this).find('input[type=email]').addClass('error');
							return false;
						} else {
							var emailto = jQuery(this).find('input.emailto').val();
							var name = jQuery(this).find('input[type=text]').val();
							var email = jQuery(this).find('input[type=email]').val();
							var nonce = jQuery(this).find('input.nonce').val();
							if(email.indexOf("@") == -1 || email.indexOf(".") == -1) {
								jQuery(this).find('.box').addClass('box-red').removeClass('box-green').text('<?php _e('Please enter a valid email address.','cyon'); ?>').removeClass('hide-text');
								jQuery(this).find('input[type=email]').addClass('error');
								return false;
							} else {
								var data = {
									action: 'cyon_newsletter_action',
									emailto: emailto,
									nonce: nonce,
									name: name,
									email: email
								};
								jQuery(this).find('button').hide();
								jQuery(this).addClass('form-sending');
								jQuery.ajax({
									url		: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
									type	: 'POST',
									data	: data,
									success	: function( results ) {
										jQuery('.cyon-newsletter form').removeClass('form-sending');
										jQuery('.cyon-newsletter button').show();
										if(results==1){
											jQuery('.cyon-newsletter .box').removeClass('box-red').addClass('box-green').text('<?php _e('Your email has been subscribed to our mailing list.'); ?>').removeClass('hide-text');
										}else{
											jQuery('.cyon-newsletter .box').addClass('box-red').text('<?php _e('There was a problem in the server. Please try again later.'); ?>').removeClass('hide-text');
										}
										jQuery('.cyon-newsletter input[type=email]').removeClass('error');
										jQuery('.cyon-newsletter input[type=email]').val('');
										jQuery('.cyon-newsletter input[type=text]').val('');
									}
			
								});
								return false;
							}
						} 
					});
				});
			});
		</script>
<?php } }

/* =Contact Form
use [contact email=""] xxx [/contact]
----------------------------------------------- */
function cyon_contact_form( $atts, $content = null ) {
	$nonce = wp_create_nonce('cyon_contact_nonce');
	$atts = shortcode_atts(
		array(
			email	=> get_bloginfo('admin_email'),
			classname => ''
		), $atts);
	$html = '<div class="cyon-contact-form contact-form-shortcode '.$atts['classname'].'"><form action="" method="post" class="cyonform">';
	$html .= '<input type="hidden" class="nonce" name="nonce" value="'.$nonce.'" /><input type="hidden" class="emailto" name="emailto" value="'.$atts['email'].'" />';
	$html .= '<fieldset>';
	if($content!=''){
		$html .= '<legend>'.$content.'</legend>';
	}
	$html .= '<div class="box hide-text"></div>';
	$html .= '<dl class="field"><dt class="label"><label for="contact_name">'.__('Name','cyon').':</label></dt><dd class="inputs"><input type="text" id="contact_name" name="name" class="large" /></dd></dl>';
	$html .= '<dl class="field"><dt class="label"><label for="contact_email">'.__('Email','cyon').':</label></dt><dd class="inputs"><input type="email" id="contact_email" name="email" class="large" /></dd></dl>';
	$html .= '<dl class="field"><dt class="label"><label for="contact_phone">'.__('Phone','cyon').':</label></dt><dd class="inputs"><input type="phone" id="contact_phone" name="phone" class="large" /></dd></dl>';
	$html .= '<dl class="field"><dt class="label"><label for="contact_message">'.__('Message','cyon').':</label></dt><dd class="inputs"><textarea id="contact_message" name="message" class="large"></textarea></dd></dl>';
	$html .= '<p class="submit"><button type="submit" name="contact_submit">'.__('Submit','cyon').'</button></p>';
	$html .= '</fieldset>';
	$html .= '</form></div>';
	ob_start();
		add_action('wp_footer','cyon_contact_ajax');
		add_action('wp_ajax_cyon_contact_action', 'cyon_contact_email');
		add_action('wp_ajax_nopriv_cyon_contact_action', 'cyon_contact_email');
	ob_get_clean();
	return $html;
}
add_shortcode('contact','cyon_contact_form'); 

if(!function_exists('cyon_contact_ajax')) {
function cyon_contact_ajax(){ ?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('.cyon-contact-form form').each(function(){
					jQuery(this).submit(function(){
						var success = true;
						if(jQuery(this).find('input[type=email]').val()=='') {
							jQuery(this).find('input[type=email]').addClass('error');
							success = false;
						}else{
							jQuery(this).find('input[type=email]').removeClass('error');
						}
						if(jQuery(this).find('textarea').val()=='') {
							jQuery(this).find('textarea').addClass('error');
							success = false;
						}else{
							jQuery(this).find('textarea').removeClass('error');
						}
						if(success){
							var emailto = jQuery(this).find('input.emailto').val();
							var name = jQuery(this).find('#contact_name').val();
							var phone = jQuery(this).find('input[type=phone]').val();
							var message = jQuery(this).find('textarea').val();
							var email = jQuery(this).find('input[type=email]').val();
							var nonce = jQuery(this).find('input.nonce').val();
							if(email.indexOf("@") == -1 || email.indexOf(".") == -1) {
								jQuery(this).find('.box').addClass('box-red').removeClass('box-green').text('<?php _e('Please enter a valid email address.','cyon'); ?>').removeClass('hide-text');
								jQuery(this).find('input[type=email]').addClass('error');
								return false;
							} else {
								var data = {
									action: 'cyon_contact_action',
									emailto: emailto,
									nonce: nonce,
									name: name,
									phone: phone,
									message: message,
									email: email
								};
								jQuery(this).find('button').hide();
								jQuery(this).addClass('form-sending');
								jQuery.ajax({
									url		: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
									type	: 'POST',
									data	: data,
									success	: function( results ) {
										jQuery('.cyon-contact-form form').removeClass('form-sending');
										jQuery('.cyon-contact-form button').show();
										if(results==1){
											jQuery('.cyon-contact-form .box').removeClass('box-red').addClass('box-green').text('<?php _e('Your inquiry has been sent. We will get back to you shortly','cyon'); ?>').removeClass('hide-text');
										}else{
											jQuery('.cyon-contact-form .box').addClass('box-red').text('<?php _e('There was a problem in the server. Please try again later.','cyon'); ?>').removeClass('hide-text');
										}
										jQuery('.cyon-contact-form input[type=email]').removeClass('error');
										jQuery('.cyon-contact-form input[type=text]').val('');
										jQuery('.cyon-contact-form input[type=email]').val('');
										jQuery('.cyon-contact-form input[type=phone]').val('');
										jQuery('.cyon-contact-form textarea').val('');
									}
			
								});
								return false;
							}
						} else {
							jQuery(this).find('.box').addClass('box-red').removeClass('box-green').text('<?php _e('Empty field(s) required.','cyon'); ?>').removeClass('hide-text');
							return false;
						} 
					});
				});
			});
		</script>
<?php } }


/* =Custom Form
For viewing purpose only, no actual function
----------------------------------------------- */
function cyon_custom_form($atts, $content = null){
	$html = '<form action="" method="post" class="cyonform">
				<fieldset>
					<legend>Simple Fields</legend>
					<dl class="field">
						<dt class="label"><label for="formtext">Text</label></dt>
						<dd class="inputs">
							<input type="text" id="formtext" name="formtext" value="" class="medium" />
							<div class="description">This is a description</div>
						</dd>
					</dl>
					<dl class="field">
						<dt class="label"><label for="formemail">Email</label></dt>
						<dd class="inputs"><input type="email" id="formemail" name="formemail" value="" class="medium" /></dd>
					</dl>
					<dl class="field">
						<dt class="label"><label for="formphone">Phone</label></dt>
						<dd class="inputs"><input type="text" id="formphone" name="formphone" value="" class="medium" /></dd>
					</dl>
					<dl class="field">
						<dt class="label"><label for="formfile">File</label></dt>
						<dd class="inputs"><input type="file" id="formfile" name="formfile" value="" /></dd>
					</dl>
					<dl class="field">
						<dt class="label"><label for="formerror">Error <span class="required">*</span></label></dt>
						<dd class="inputs"><input type="text" id="formerror" name="formerror" class="medium error" value="" /></dd>
					</dl>
					<dl class="field">
						<dt class="label"><label>Multiple</label></dt>
						<dd class="inputs"><input type="text" name="multi1" class="small" value="" placeholder="Field 1" /> <input type="text" name="multi2" class="small" value="" placeholder="Field 2" /> <input type="text" name="multi3" class="small" value="" placeholder="Field 3" /></dd>
					</dl>
					<dl class="field">
						<dt class="label"><label for="formselect">Select</label></dt>
						<dd class="inputs">
							<select name="formselect" id="formselect">
								<option value="">- Please select -</option>
								<option>Option 1</option>
								<option>Option 2</option>
								<option>Option 3</option>
							</select>
						</dd>
					</dl>
					<dl class="field">
						<dt class="label"><label>Checkboxes</label></dt>
						<dd class="inputs">
							<ul class="selection">
								<li>
									<input type="checkbox" name="formcheckbox[]" id="formcheck1" /> <label for="formcheck1">Checkbox 1</label>
								</li>
								<li>
									<input type="checkbox" name="formcheckbox[]" id="formcheck2" /> <label for="formcheck2">Checkbox 2</label>
								</li>
								<li>
									<input type="checkbox" name="formcheckbox[]" id="formcheck3" /> <label for="formcheck3">Checkbox 3</label>
								</li>
							</ul>
						</dd>
					</dl>
					<dl class="field">
						<dt class="label"><label>Radio Buttons</label></dt>
						<dd class="inputs">
							<ul class="selection">
								<li>
									<input type="radio" name="formradio[]" id="formradio1" /> <label for="formradio1">Radio 1</label>
								</li>
								<li>
									<input type="radio" name="formradio[]" id="formradio2" /> <label for="formradio2">Radio 2</label>
								</li>
								<li>
									<input type="radio" name="formradio[]" id="formradio3" /> <label for="formradio3">Radio 3</label>
								</li>
							</ul>
						</dd>
					</dl>
					<dl class="field">
						<dt class="label"><label for="formtextarea">Textarea</label></dt>
						<dd class="inputs"><textarea id="formtextarea" name="formtextarea" rows="5" class="large"></textarea></dd>
					</dl>
				</fieldset>
				<p class="submit"><button type="submit" class="button">'.__('Submit Form','cyon').'</button></p>
			</form>';
	return $html;
}
add_shortcode('custom_form','cyon_custom_form'); 


/* =Search
use [search_form] xxx [/search_form]
----------------------------------------------- */
function cyon_search_form( $atts, $content = null ) {
	return get_search_form( false );
}
add_shortcode('search_form','cyon_search_form'); 
