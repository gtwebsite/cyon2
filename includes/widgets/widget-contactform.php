<?php

class CyonContactFormWidget extends WP_Widget {

	protected $nonce;
	protected $emailto;

	// Creating your widget
	function CyonContactFormWidget(){
		$widget_ops = array('classname' => 'cyon-contact-form', 'description' => __('Displays Contact Form') );
		$this->WP_Widget('CyonContactFormWidget', __('Cyon Contact Form'), $widget_ops);
	}
 
 	// Widget form in WP Admin
	function form($instance){
		// Start adding your fields here
		$instance = wp_parse_args( (array) $instance, array(
			'title' 		=> __('Contact Form'),
			'text'			=> __('Get the latest tips, news, and special offers delivered to your inbox.'),
			'email'			=> get_bloginfo('admin_email'),
			'dropdown_label'=> __('Dropdown label here'),
			'dropdown_values'=> ''
		) );
		$title = $instance['title'];
		$email = $instance['email'];
		$text = $instance['text'];
		$dropdown_label = $instance['dropdown_label'];
		$dropdown_values = $instance['dropdown_values'];

		?>
		  <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Email') ?>: <input class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="text" value="<?php echo attribute_escape($email); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text') ?>: <textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" type="text"><?php echo attribute_escape($text); ?></textarea></label></p>
		  <p><label for="<?php echo $this->get_field_id('dropdown_label'); ?>"><?php _e('Dropdown Label') ?>: <input class="widefat" id="<?php echo $this->get_field_id('dropdown_label'); ?>" name="<?php echo $this->get_field_name('dropdown_label'); ?>" type="text" value="<?php echo attribute_escape($dropdown_label); ?>" /></label></p>
		  <p><label for="<?php echo $this->get_field_id('dropdown_values'); ?>"><?php _e('Dropdown Values') ?>: <textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('dropdown_values'); ?>" name="<?php echo $this->get_field_name('dropdown_values'); ?>" type="text"><?php echo attribute_escape($dropdown_values); ?></textarea></label></p>
		<?php
	}

	// Saving your widget form
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		// Override new values of each fields
		$instance['title'] = $new_instance['title'];
		$instance['email'] = $new_instance['email'];
		$instance['text'] = $new_instance['text'];
		$instance['dropdown_label'] = $new_instance['dropdown_label'];
		$instance['dropdown_values'] = $new_instance['dropdown_values'];
		return $instance;
	}

	// Displays your widget on the front-end
	function widget($args, $instance){
		extract($args, EXTR_SKIP);
		
		// Start widget
		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		$this->emailto = $instance['email'];
		$showname = $instance['showname'];
		$dropdown = $instance['dropdown_label'];
		$dropdown_values = $instance['dropdown_values'];
		$this->nonce = wp_create_nonce('cyon_contact_nonce');
		
		if (!empty($title)){
			echo $before_title . $title . $after_title;;
		}
		echo '<form action="" method="post" class="widget-content cyonform">';
		
    	// Widget code here
		echo '<fieldset>';
		if($instance['text']!=''){
			echo '<legend>'.$instance['text'].'</legend>';
		}
		echo '<div class="box"></div><input type="hidden" class="nonce" name="nonce" value="'.$this->nonce.'" /><input type="hidden" class="emailto" name="emailto" value="'.$this->emailto.'" />';
		echo '<p><label for="contact_name">'.__('Name').':</label> <input type="text" id="contact_name" name="name" /></p>';
		echo '<p><label for="contact_email">'.__('Email').':</label> <input type="email" id="contact_email" name="email" /></p>';
		echo '<p><label for="contact_phone">'.__('Phone').':</label> <input type="phone" id="contact_phone" name="phone" /></p>';
		if($dropdown!='' && $dropdown_values!=''){
			$dd = '';
			$dd_values = explode("\r\n",$dropdown_values);
			for($i=0;$i<count($dd_values);$i++) {
				$dd .= '<option>'.$dd_values[$i].'</option>';
			}
			echo '<p><label for="contact_dropdown">'.$dropdown.':</label> <select id="contact_dropdown" name="dropdown"><option value="">- '.__('Please select').' -</option>'.$dd.'</select></p>';
		}
		echo '<p><label for="contact_message">'.__('Messsage').':</label> <textarea id="contact_message" name="message"></textarea></p>';
		echo '<button type="submit" name="contact_submit">'.__('Submit').'</button>';
		echo '</fieldset>';
 
		// End widget
		echo '</form>';
		echo $after_widget;
		add_action('wp_footer', array(&$this, 'cyon_contact_ajax'));
	}
	
	/* Ajax */
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
							var dropdown = jQuery(this).find('select[name=dropdown] :selected').val();
							var message = jQuery(this).find('textarea').val();
							var email = jQuery(this).find('input[type=email]').val();
							var nonce = jQuery(this).find('input.nonce').val();
							if(email.indexOf("@") == -1 || email.indexOf(".") == -1) {
								jQuery(this).find('.box').addClass('box-red').removeClass('box-green').text('<?php _e('Please enter a valid email address.'); ?>');
								jQuery(this).find('input[type=email]').addClass('error');
								return false;
							} else {
								var data = {
									action: 'cyon_contact_action',
									emailto: emailto,
									nonce: nonce,
									name: name,
									phone: phone,
									dropdown: dropdown,
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
											jQuery('.cyon-contact-form .box').removeClass('box-red').addClass('box-green').text('<?php _e('Your inquiry has been sent. We will get back to you shortly'); ?>');
										}else{
											jQuery('.cyon-contact-form .box').addClass('box-red').text('<?php _e('There was a problem in the server. Please try again later.'); ?>');
										}
										jQuery('.cyon-contact-form input[type=email]').removeClass('error');
										jQuery('.cyon-contact-form input[type=text]').val('');
										jQuery('.cyon-contact-form input[type=email]').val('');
										jQuery('.cyon-contact-form select[name=dropdown]').prop('selectedIndex',0);
										jQuery('.cyon-contact-form input[type=phone]').val('');
										jQuery('.cyon-contact-form textarea').val('');
										jQuery('.cyon-contact-form select[name=dropdown]').uniform();
									}
			
								});
								return false;
							}
						} else {
							jQuery(this).find('.box').addClass('box-red').removeClass('box-green').text('<?php _e('Empty field(s) required.'); ?>');
							return false;
						} 
					});
				});
			});
		</script>
	<?php }
}

// Adding your widget to WordPress
add_action( 'widgets_init', create_function('', 'return register_widget("CyonContactFormWidget");') );

// Sending email
add_action('wp_ajax_cyon_contact_action', 'cyon_contact_email');
add_action('wp_ajax_nopriv_cyon_contact_action', 'cyon_contact_email');
if(!function_exists('cyon_contact_email')) {
function cyon_contact_email() {
	if (! wp_verify_nonce($_REQUEST['nonce'], 'cyon_contact_nonce') ) die(__('Security check')); 
	if(isset($_REQUEST['nonce']) && isset($_REQUEST['email'])) {
		$subject = __('New inquiry from').' '.get_bloginfo('name');
		$body = __('Name').': <b>'.$_REQUEST['email'].'</b><br>';
		$body .= __('Email').': <b>'.$_REQUEST['email'].'</b><br>';
		$body .= __('Phone').': <b>'.$_REQUEST['phone'].'</b><br>';
		$body .= __('Selected').': <b>'.$_REQUEST['dropdown'].'</b><br>';
		$body .= __('Message').': <b>'.$_REQUEST['message'].'</b>';
		if( mail($_REQUEST['emailto'], $subject, $body) ) {
			echo 1;
		} else {
			echo 0;
		}
	}
	die();
} }