<?php
/**
 * This file the additional meta (data) Endorse testimonials
 *
 * @package     Endorse WordPress Plugin
 * @copyright   Copyright (C) 2017 Kevin Archibald, GPLv2 or later
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @author      Kevin Archibald <www.kevinsspace.ca/contact/>
 * Testimonial Basics is distributed under the terms of the GNU GPL
 */

namespace EndorseByKHA;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Add meta box to pages.
 *
 * The format for the add_meta_box() is as follows:
 * add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
 * $id : (string) (required) HTML 'id' attribute of the edit screen section Default: None
 * $title : (string) (required) Title of the edit screen section, visible to user Default: None
 * $callback : (callback) (required) Function that prints out the HTML for the edit screen section.
 *             Pass function name as a string
 * $post_type : (string) (required) The type of Write screen on which to show the edit screen section
 *              ('post', 'page', 'link', or 'custom_post_type' where custom_post_type is the custom post type slug)
 *              Default: None
 * $context : (string) (optional) The part of the page where the edit screen section should be shown
 *            ('normal', 'advanced', or 'side'). (Note that 'side' doesn't exist before 2.7)Default: 'advanced'
 * $priority : (string) (optional) The priority within the context where the boxes should show
 *             ('high', 'core', 'default' or 'low') Default: 'default'
 * $callback_args : (array) (optional) Arguments to pass into your callback function. The callback will receive
 *                  the $post object and whatever parameters are passed through this variable. Default: null
 */
/**
 * =============================================================================================
 *                     Custom Post Meta Box
 * =============================================================================================
 */

/**
 * Add meta box to posts.
 *
 * The format for the add_meta_box() is as follows:
 * add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
 * $id : (string) (required) HTML 'id' attribute of the edit screen section Default: None
 * $title : (string) (required) Title of the edit screen section, visible to user Default: None
 * $callback : (callback) (required) Function that prints out the HTML for the edit screen section.
 *             Pass function name as a string
 * $post_type : (string) (required) The type of Write screen on which to show the edit screen section
 *              ('post', 'page', 'link', or 'custom_post_type' where custom_post_type is the custom post type slug)
 *              Default: None
 * $context : (string) (optional) The part of the page where the edit screen section should be shown
 *            ('normal', 'advanced', or 'side'). (Note that 'side' doesn't exist before 2.7)Default: 'advanced'
 * $priority : (string) (optional) The priority within the context where the boxes should show
 *             ('high', 'core', 'default' or 'low') Default: 'default'
 * $callback_args : (array) (optional) Arguments to pass into your callback function. The callback will receive
 *                  the $post object and whatever parameters are passed through this variable. Default: null
 *
 * @since 0.0.1
 */
function add_post_custom_meta_box() {
	add_meta_box(
		'endorse-custom-meta-box',
		'Endorse Testimonial - Additional Data',
		'EndorseByKHA\show_post_custom_meta_box',
		'khatestimonial',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'EndorseByKHA\add_post_custom_meta_box' );

/**
 * The Callback.
 *
 * The callback is used to display the options on the post.
 *
 * @since 0.0.1
 */
function show_post_custom_meta_box() {
	global $post, $options;
	// Use nonce for verification.
	wp_nonce_field( basename( __FILE__ ), 'post_meta_box_nonce' );
	?>
	<?php
	if ( ! get_post_meta( $post->ID, 'endorse_meta' ) ) {
		$meta[0] = array(
			'rating'   => 0,
			'author'   => '',
			'email'    => '',
			'website'  => '',
			'location' => '',
			'image'    => '',
			'custom1'  => '',
			'custom2'  => '',
		);
	} else {
		$meta = get_post_meta( $post->ID, 'endorse_meta' );
	}
	if ( isset( $options['custom1'] ) && '' !== $options['custom1'] ) {
		$custom1_label = $options['custom1'];
	} else {
		$custom1_label = __( 'Custom 1 entry', 'endorse' );
	}
	if ( isset( $options['custom2'] ) && '' !== $options['custom2'] ) {
		$custom2_label = $options['custom2'];
	} else {
		$custom2_label = __( 'Custom 2 entry', 'endorse' );
	}
	?>
	<!-- **************** Author ************************************************************** -->
	<span class="endorse-author-meta">
		<label class="endorse-author-label">
			<?php esc_html_e( 'Author: ', 'endorse' ); ?>
		</label>
		<input class="endorse-author-input" type="text" name="author-name" id="author-id" value="<?php echo esc_html( stripcslashes( ( $meta[0]['author'] ) ) ); ?>" />
	</span>
	<!-- **************** Email ************************************************************** -->
	<span class="endorse-email-meta">
		<label class="endorse-email-label">
			<?php esc_html_e( 'Email: ', 'endorse' ); ?>
		</label>
		<input class="endorse-email-input" type="text" name="email-name" id="email-id" value="<?php echo sanitize_email( $meta[0]['email'] );//phpcs:ignore ?>" />
	</span>
	<!-- **************** Location ************************************************************** -->
	<span class="endorse-location-meta">
		<label class="endorse-location-label">
			<?php esc_html_e( 'Location: ', 'endorse' ); ?>
		</label>
		<input class="endorse-location-input" type="text" name="location-name" id="location-id" value="<?php echo esc_html( stripslashes( $meta[0]['location'] ) ); ?>" />
	</span>
	<!-- **************** Website ************************************************************** -->
	<span class="endorse-website-meta">
		<label class="endorse-website-label">
			<?php esc_html_e( 'Website: ', 'endorse' ); ?>
		</label>
		<input class="endorse-website-input" type="text" name="website-name" id="website-id" value="<?php echo esc_url( $meta[0]['website'] ); ?>" />
	</span>
	<!-- **************** Rating ************************************************************** -->
	<span class="endorse-rating-meta">
		<label class="endorse-rating-label"><?php esc_html_e( 'Review rating: ', 'endorse' ); ?></label>
		<select class="endorse-rating-input" name="rating-name">
			<option <?php selected( $meta[0]['rating'] ); ?> value="<?php echo esc_html( $meta[0]['rating'] ); ?>"><?php echo esc_html( $meta[0]['rating'] ); ?></option>
			<option value="0.0">0.0</option>
			<option value="0.5">0.5</option>
			<option value="1.0">1.0</option>
			<option value="1.5">1.5</option>
			<option value="2.0">2.0</option>
			<option value="2.5">2.5</option>
			<option value="3.0">3.0</option>
			<option value="3.5">3.5</option>
			<option value="4.0">4.0</option>
			<option value="4.5">4.5</option>
			<option value="5.0">5.0</option>
		</select>
	</span>
	<!-- **************** Custom 1 ************************************************************** -->
	<span class="endorse-custom1-meta">
		<label class="endorse-custom1-label">
			<?php echo esc_html( $custom1_label ); ?>
		</label>
		<input class="endorse-custom1-input" type="text" name="custom1-name" id="custom1-id" value="<?php echo esc_html( stripslashes( $meta[0]['custom1'] ) ); ?>" />
	</span>
	<!-- **************** Custom 2 ************************************************************** -->
	<span class="endorse-custom2-meta">
		<label class="endorse-custom2-label">
			<?php echo esc_html( $custom2_label ); ?>
		</label>
		<input class="endorse-custom2-input" type="text" name="custom2-name" id="custom2-id" value="<?php echo esc_html( stripslashes( $meta[0]['custom2'] ) ); ?>" />
	</span>
	<br style="line-height: 0;" />
	<!-- **************** Gravatar ************************************************************** -->
	<span class="endorse-avatar-meta">
		<label class="endorse-avatar-label"><?php esc_html_e( 'Gravatar/Image: ', 'endorse' ); ?></label>
		<span class="endorse-avatar"><?php echo get_avatar( $meta[0]['email'], $size = '60' ); ?></span>
	</span>
	<!-- **************** Image ************************************************************** -->
	<span class="endorse-pic-meta">
		<label class="endorse-edit-pic-label"><?php esc_html_e( 'Custom Image: ', 'endorse' ); ?></label>
		<input id="endorse-upload-image" class="endorse-picture-url" type="text" name="upload_image" maxlength="100" value="<?php echo isset( $meta[0]['image'] ) ? esc_url( $meta[0]['image'] ) : ''; ?>" />
		<input id="endorse-upload-button" class="endorse-upload-button" type="button" name="endorse_photo_add" value="<?php esc_attr_e( 'Select Image', 'endorse' ); ?>" />
		<?php if ( ! isset( $meta[0]['image'] ) || '' === $meta[0]['image'] ) { ?>
			<span class="endorse-edit-pic">
				<img src="<?php echo esc_url( plugins_url() ) . '/endorse/images/mystery_man_200.jpg'; ?>" title="<?php esc_attr_e( 'Uploaded Author Image', 'endorse' ); ?>" alt="<?php esc_attr_e( 'Uploaded Author Image', 'endorse' ); ?>" />
			</span>
		<?php } else { ?>
			<span class="endorse-edit-pic">
				<img src="<?php echo esc_url( $meta[0]['image'] ); ?>" title="<?php esc_attr_e( 'Uploaded Author Image', 'endorse' ); ?>" alt="<?php esc_attr_e( 'Uploaded Author Image', 'endorse' ); ?>" />
			</span>
		<?php } ?>
	</span>
	<?php
}

/**
 * Save the data.
 *
 * This functon takes the meta data and saves it to the WordPress Database.
 *
 * @since 0.0.1
 * @param string $post_id id the post id.
 */
function save_post_custom_meta( $post_id ) {
	global $post;
	// Verify nonce.
	if ( false === isset( $_POST['post_meta_box_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['post_meta_box_nonce'] ) ), basename( __FILE__ ) ) ) {
		return $post_id;
	}
	// Check autosave.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}
	// Check permissions.
	if ( isset( $_POST['post_type'] ) && 'khatestimonial' === $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		}
	} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}
	// Save the meta data.
	$meta['rating']   = ( isset( $_POST['rating-name'] ) ? sanitize_text_field( wp_unslash( $_POST['rating-name'] ) ) : 0 );
	$meta['custom1']  = ( isset( $_POST['custom1-name'] ) ? sanitize_text_field( wp_unslash( $_POST['custom1-name'] ) ) : '' );
	$meta['custom2']  = ( isset( $_POST['custom2-name'] ) ? sanitize_text_field( wp_unslash( $_POST['custom2-name'] ) ) : '' );
	$meta['author']   = ( isset( $_POST['author-name'] ) ? sanitize_text_field( wp_unslash( $_POST['author-name'] ) ) : '' );
	$meta['email']    = ( isset( $_POST['email-name'] ) ? sanitize_email( wp_unslash( $_POST['email-name'] ) ) : '' );
	$meta['website']  = ( isset( $_POST['website-name'] ) ? esc_url_raw( $_POST['website-name'] ) : '' );//phpcs:ignore
	$meta['location'] = ( isset( $_POST['location-name'] ) ? sanitize_text_field( wp_unslash( $_POST['location-name'] ) ) : '' );
	$meta['image']    = ( isset( $_POST['upload_image'] ) ? esc_url_raw( $_POST['upload_image'] ) : '' );//phpcs:ignore
	// Add the meta.
	if ( ! get_post_meta( $post->ID, 'endorse_meta' ) ) {
		add_post_meta( $post_id, 'endorse_meta', $meta, true );
	} else {
		update_post_meta( $post_id, 'endorse_meta', $meta );
	}
}
add_action( 'save_post', 'EndorseByKHA\save_post_custom_meta' );

