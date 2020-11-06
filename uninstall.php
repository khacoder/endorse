<?php
/**
 * This is uninstall.php for the plugin Endorse
 *
 * @package   Endorse ClassicPress Plugin
 * @copyright Copyright (C) 2020 Kevin Archibald
 * @license   http://www.gnu.org/licenses/quick-guide-gplv3.html  GNU Public License
 * @author    Kevin Archibald <https://kevinsspace.ca/contact/>
 * Testimonial Basics is distributed under the terms of the GNU GPL
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
// If uninstall not called from ClassicPress exit this function.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die();
}
// Delete Options.
delete_option( 'endorse_customize_options' );
delete_option( 'widget_endorse_display_widget' );
delete_option( 'widget_endorse_input_widget' );
delete_option( 'widget_endorse_slider_widget' );
delete_option( 'khagroups_children' );
// delete custom post type posts.
$endorse_cpt_args  = array(
	'post_type'      => 'khatestimonial',
	'posts_per_page' => -1,
);
$endorse_cpt_posts = get_posts( $endorse_cpt_args );
foreach ( $endorse_cpt_posts as $cptpost ) {
	wp_delete_post( $cptpost->ID, true );
}
// Remove meta.
$meta_type  = 'user';
$user_id    = 0; // This will be ignored, since we are deleting for all users.
$meta_key   = 'endorse_meta';
$meta_value = ''; // Also ignored. The meta will be deleted regardless of value.
$delete_all = true;
delete_metadata( $meta_type, $user_id, $meta_key, $meta_value, $delete_all );
