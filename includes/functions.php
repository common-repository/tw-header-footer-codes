<?php
/**
 * Function to add meta box
 *
 * @package WordPress
 * @subpackage TW Header Footer Codes
 * @since TW Header Footer Codes 1.0.0
 */
if ( ! function_exists( 'twhfc_add_meta_box' ) ) {
	function twhfc_add_meta_box() {
		$opt = get_option('twhfc_options');
		
		if( isset($opt['post_types']) ){
			add_meta_box(
				'twhfc-box',
				__( 'Custom Header Footer Codes', 'tw-header-footer-codes' ),
				'twhfc_render_meta_box',
				$opt['post_types']
			);
		}
	}
	add_action( 'add_meta_boxes', 'twhfc_add_meta_box' );
}

/**
 * Function to render meta box
 *
 * @package WordPress
 * @subpackage TW Header Footer Codes
 * @since TW Header Footer Codes 1.0.0
 */
if ( ! function_exists( 'twhfc_render_meta_box' ) ) {
	function twhfc_render_meta_box() {
		global $post;
		wp_nonce_field( 'twhfc_meta_box_nonce', 'twhfc_meta_box_nonce' ); ?>
		<div class="twhfc-meta-form">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><?php esc_html_e('Enable Header & Footer Codes', 'tw-header-footer-codes'); ?></th>
						<td>
							<label for="twhfc-enable">
								<input type="checkbox" id="twhfc-enable" name="twhfc_enable" value="1" <?php echo (get_post_meta($post->ID, 'twhfc_enable', true) ) ? 'checked' : '' ?> />
								<?php esc_html_e('Yes', 'tw-header-footer-codes'); ?>
							</label>
							<p class="description"><?php _e( 'Your header & footer codes will be displayed in the frontend for this post/page.', 'tw-header-footer-codes' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e('Header Codes', 'tw-header-footer-codes'); ?></th>
						<td>
							<textarea class="large-text" cols="50" rows="10" id="header-codes" name="twhfc_header_codes"><?php echo (get_post_meta($post->ID, 'twhfc_header_codes', true) ) ? get_post_meta($post->ID, 'twhfc_header_codes', true) : '' ?></textarea>
							<p class="description"><?php _e( 'Codes that will be loaded inside head tag.', 'tw-header-footer-codes' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e('Footer Codes', 'tw-header-footer-codes'); ?></th>
						<td>
							<textarea class="large-text" cols="50" rows="10" id="footer-codes" name="twhfc_footer_codes"><?php echo (get_post_meta($post->ID, 'twhfc_footer_codes', true) ) ? get_post_meta($post->ID, 'twhfc_footer_codes', true) : ''; ?></textarea>
							<p class="description"><?php _e( 'Codes that will be loaded before closing body tag.', 'tw-header-footer-codes' ); ?></p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	<?php
	}
}

/**
 * Function to save meta
 *
 * @package WordPress
 * @subpackage TW Header Footer Codes
 * @since TW Header Footer Codes 1.0.0
 */
if ( ! function_exists( 'twhfc_save_meta' ) ) {
	function twhfc_save_meta($post_id) {
		
		// Bail if we're doing an auto save
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		
		// if our nonce isn't there, or we can't verify it, bail
		if ( !isset( $_POST['twhfc_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['twhfc_meta_box_nonce'], 'twhfc_meta_box_nonce' ) ) return;

		// if our current user can't edit this post, bail
		if ( !current_user_can( 'edit_post' ) ) return;

		if ( array_key_exists( 'twhfc_enable', $_POST ) && !empty($_POST['twhfc_enable']) ) {
			update_post_meta($post_id, 'twhfc_enable', $_POST['twhfc_enable']);
		} else {
			delete_post_meta($post_id, 'twhfc_enable', $_POST['twhfc_enable']);
		}

		if ( array_key_exists( 'twhfc_header_codes', $_POST ) && !empty($_POST['twhfc_header_codes']) ) {
			update_post_meta($post_id, 'twhfc_header_codes', $_POST['twhfc_header_codes']);
		}
		
		if ( array_key_exists( 'twhfc_header_codes', $_POST ) && !empty($_POST['twhfc_footer_codes']) ) {
			update_post_meta($post_id, 'twhfc_footer_codes', $_POST['twhfc_footer_codes']);
		}
	}
	add_action( 'save_post', 'twhfc_save_meta' );
}

/**
 * Function to show header codes
 *
 * @package WordPress
 * @subpackage TW Header Footer Codes
 * @since TW Header Footer Codes 1.0.0
 */
if ( ! function_exists( 'twhfc_show_header_codes' ) ) {
	function twhfc_show_header_codes() {
		global $post;
		$opt = get_option('twhfc_options');
		$post_type = get_post_type();

		// Load global header codes
		if ( !empty($opt['header_codes']) ) {
			echo get_option('twhfc_options')['header_codes'];
		}

		// Load post specific header codes
		if ( $post_type != false && get_post_meta($post->ID, 'twhfc_enable', true) == '1' && in_array($post_type, $opt['post_types']) ) {
			echo (get_post_meta($post->ID, 'twhfc_header_codes', true) ) ? get_post_meta($post->ID, 'twhfc_header_codes', true) : '';
		}
	}
	add_action( 'wp_head', 'twhfc_show_header_codes', 99 );
}

/**
 * Function to show footer codes
 *
 * @package WordPress
 * @subpackage TW Header Footer Codes
 * @since TW Header Footer Codes 1.0.0
 */
if ( ! function_exists( 'twhfc_show_footer_codes' ) ) {
	function twhfc_show_footer_codes() {
		global $post;
		$opt = get_option('twhfc_options');
		$post_type = get_post_type();

		// Load global footer codes
		if ( !empty($opt['footer_codes']) ) {
			echo get_option('twhfc_options')['footer_codes'];
		}

		// Load post specific footer codes
		if ( get_post_meta($post->ID, 'twhfc_enable', true) == '1' && in_array($post_type, $opt['post_types']) ) {
			echo (get_post_meta($post->ID, 'twhfc_footer_codes', true) ) ? get_post_meta($post->ID, 'twhfc_footer_codes', true) : get_option('twhfc_options')['footer_codes'];
		}
	}
	add_action( 'wp_footer', 'twhfc_show_footer_codes', 99 );
}