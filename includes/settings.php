<?php
/**
 * Function to setup plugin settings
 *
 * @package WordPress
 * @subpackage TW Header & Footer Codes
 * @since TW Header & Footer Codes 1.0.0
 */

// Register plugin settings
function twhfc_register_settings() {
	register_setting( 'twhfc_options', 'twhfc_options' );
}

function twhfc_settings_page() {
?>
	<div class="wrap">
		<h1><?php esc_html_e('Header & Footer Codes', 'tw-header-footer-codes'); ?></h1>

		<form id="twhfc-settings" method="post" action="options.php">
			<table class="form-table">
				<?php
				settings_fields( 'twhfc_options' );
				// wp_nonce_field( 'twhfc_nonce', 'twhfc_nonce' );
				do_settings_sections( 'twhfc_options' );

				// Get list of all custom post types
				$args = array(
				   'public'   => true,
				   '_builtin' => false
				);

				$output = 'names';
				$operator = 'or';

				$post_types = get_post_types( $args, $output, $operator );
				$settings = get_option('twhfc_options');
				$enable_post_type = isset($settings['post_types']) ? $settings['post_types'] : array();
				?>

				<tr>
					<th scope="row"><?php esc_html_e('Supported Post Type(s)', 'tw-header-footer-codes'); ?></th>
					<td>
						<?php foreach ( $post_types  as $post_type ) : ?>
							<input id="pt-<?php echo $post_type; ?>" type="checkbox" name="twhfc_options[post_types][]" value="<?php echo $post_type; ?>" <?php echo (in_array($post_type, $enable_post_type)) ? 'checked' : ''; ?>/>
							<label for="pt-<?php echo $post_type; ?>"><?php echo $post_type; ?></label> <br />
						<?php endforeach; ?>
						<p class="description"><?php esc_html_e( 'Show TW Header & Footer Codes metabox on selected post type add/edit screen.', 'tw-header-footer-codes' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row"><?php esc_html_e('Header Codes', 'tw-header-footer-codes'); ?></th>
					<td>
						<textarea class="large-text" cols="50" rows="10" id="header-codes" name="twhfc_options[header_codes]"><?php echo isset($settings['header_codes']) ? $settings['header_codes'] : ''; ?></textarea>
						<p class="description"><?php esc_html_e( 'Global header codes for all pages.', 'tw-header-footer-codes' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row"><?php esc_html_e('Footer Codes', 'tw-header-footer-codes'); ?></th>
					<td>
						<textarea class="large-text" cols="50" rows="10" id="footer-codes" name="twhfc_options[footer_codes]"><?php echo isset($settings['footer_codes']) ? $settings['footer_codes'] : ''; ?></textarea>
						<p class="description"><?php esc_html_e( 'Global footer codes for all pages.', 'tw-header-footer-codes' ); ?></p>
					</td>
				</tr>

				<tr>
					 <td></td>
					 <td><input type="submit" name="warrior_sales_notif_submit" class="button-primary warrior-sales-submit" value="<?php esc_html_e( 'Save Changes', 'tw-header-footer-codes' ); ?>" /></td>
				</tr>
			</table>
		</form>

		<div id="poststuff" class="twhfc-infos postbox-container">
			<div class="postbox">
				<h3 class="hndle"><span><?php esc_html_e('WordPress Themes & Plugins', 'tw-header-footer-codes'); ?></span></h3>
				<div class="inside">
					<p><a href="https://www.themewarrior.com" target="_blank"><img src="<?php echo plugins_url('assets/images/logo-themewarrior.png', dirname(__FILE__)); ?>" /></a></p>

					<?php esc_html_e('Checkout the free and premium WordPress themes and plugins on our website', 'tw-header-footer-codes'); ?> <a href="https://www.themewarrior.com" target="_blank"><?php esc_html_e('ThemeWarrior', 'tw-header-footer-codes'); ?></a>.
				</div>
			</div>

			<div class="postbox">
				<h3 class="hndle"><span><?php esc_html_e('Need custom WordPress or PHP project done?', 'tw-header-footer-codes'); ?></span></h3>
				<div class="inside">
					<p><?php esc_html_e('If you need help in developing something in WordPress or other PHP project, don\'t hesitate to reach out to us.', 'tw-header-footer-codes'); ?></p>

					<p><?php esc_html_e('We have a very deep understanding of WordPress theme & plugin develppment and we\'re also loves working with Laravel, MySQL, Mongodb and Angular (for mobile app development).', 'tw-header-footer-codes'); ?></p>

					<a href="https://www.themewarrior.com/custom-wordpress-work/" target="_blank"><?php esc_html_e('Contact us now', 'tw-header-footer-codes'); ?> </a>
				</div>
			</div>
		</div>
	</div>
<?php
}
