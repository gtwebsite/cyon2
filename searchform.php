<?php
/**
 * The template for displaying search forms in Cyon Theme
 *
 * @package WordPress
 * @subpackage Cyon Theme
 */
?>
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="cyonform">
		<fieldset>
			<label for="s" class="assistive-text"><?php _e( 'Search', 'cyon' ); ?></label>
			<input type="text" class="field" name="s" id="s" autocomplete="off" x-webkit-speech x-webkit-gramar="builtin:search" placeholder="<?php esc_attr_e( 'Search entire website...', 'cyon' ); ?>" />
			<button type="submit" class="submit" name="submit" id="searchsubmit"><?php esc_attr_e( 'Go', 'cyon' ); ?></button>
		</fieldset>
	</form>
