<?php
/**
 * Plugin Name: Endorse
 * Plugin URI: https://kevinsspace.ca/endorse-classicpress-plugin/
 * Description: This plugin provides complete comprehensive management of customer testimonials. The user can set up an input form in a page or in a widget, and display all or selected testimonials in a page or a widget. The plug in is very easy to use and modify.
 * Version: 1.0.2
 * Author: Kevin Archibald
 * Author URI: https://kevinsspace.ca
 * License: GPLv2 or later
 * Text Domain: endorse

 * Endorse is a ClassicPress Plugin
 * Copyright (C) 2020 Kevin Archibald
 * Endorse is distributed under the terms of the GNU GPL
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

 * @package   Endorse
 * @version   1.0.0
 * @author    Kevin Archibald <kevin@kevinsspace.ca>
 * @copyright Copyright (C) 2020, Kevin Archibald
 * @link      https://kevinsspace.ca/endorse
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace EndorseByKHA;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
// constants.
define( 'ENDORSE_VERSION', '1.0.0' );

/**
 * Gets this plugin's absolute directory path.
 *
 * @return string
 */
function _get_plugin_directory() {
	return __DIR__;
}

/**
 * Gets this plugin's URL.
 *
 * @return string
 */
function _get_plugin_url() {
	static $plugin_url;
	if ( empty( $plugin_url ) ) {
		$plugin_url = plugins_url( null, __FILE__ );
	}
	return $plugin_url;
}

/**
 * Activate the plugin.
 */
function activate() {
	// Check version compatibilities.
	if ( version_compare( get_bloginfo( 'version' ), '1.0', '<' ) ) {
		deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin.
		die( esc_html__( 'Please Upgrade your ClassicPress to use this plugin.', 'endorse' ) );
	}
	// Clear the permalinks after the post type has been registered.
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'EndorseByKHA\activate' );

/**
 * Add the testimonial custom post.
 */
function setup_custom_post() {
	$labels = array(
		'name'               => esc_html__( 'Testimonials', 'endorse' ),
		'singular_name'      => esc_html__( 'Testimonial', 'endorse' ),
		'add_new'            => esc_html__( 'Add New Testimonial', 'endorse' ),
		'add_new_item'       => esc_html__( 'Add New Testimonial', 'endorse' ),
		'edit_item'          => esc_html__( 'Edit Testimonial', 'endorse' ),
		'new_item'           => esc_html__( 'New Testimonial', 'endorse' ),
		'all_items'          => esc_html__( 'All Testimonials', 'endorse' ),
		'view_item'          => esc_html__( 'View Testimonial', 'endorse' ),
		'search_items'       => esc_html__( 'Search Testimonials', 'endorse' ),
		'not_found'          => esc_html__( 'No testimonials found', 'endorse' ),
		'not_found_in_trash' => esc_html__( 'No testimonials found in the Trash', 'endorse' ),
		'parent_item_colon'  => '',
		'menu_name'          => esc_html__( 'Testimonials', 'endorse' ),
	);
	$args   = array(
		'labels'       => $labels,
		'description'  => esc_html__( 'Custom post type for testimonials.', 'endorse' ),
		'public'       => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-awards',
		'rewrite'      => array( 'slug' => 'khatestimonial' ),
		'supports'     => array( 'title', 'editor' ),
	);
	register_post_type( 'khatestimonial', $args );
}
add_action( 'init', 'EndorseByKHA\setup_custom_post' );

/**
 * Add the testimonial custom taxonomy group.
 */
function setup_custom_taxonomy() {
	$labels = array(
		'name'              => esc_html__( 'Groups', 'endorse' ),
		'singular_name'     => esc_html__( 'Group', 'endorse' ),
		'search_items'      => esc_html__( 'Search Groups', 'endorse' ),
		'all_items'         => esc_html__( 'All Groups', 'endorse' ),
		'parent_item'       => esc_html__( 'Parent Group', 'endorse' ),
		'parent_item_colon' => esc_html__( 'Parent Group:', 'endorse' ),
		'edit_item'         => esc_html__( 'Edit Group', 'endorse' ),
		'update_item'       => esc_html__( 'Update Group', 'endorse' ),
		'add_new_item'      => esc_html__( 'Add New Group', 'endorse' ),
		'new_item_name'     => esc_html__( 'New Group Name', 'endorse' ),
		'menu_name'         => esc_html__( 'Testimonial Groups', 'endorse' ),
	);
	$args   = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'groups' ),
	);
	register_taxonomy( 'khagroups', array( 'khatestimonial' ), $args );
}
add_action( 'init', 'EndorseByKHA\setup_custom_taxonomy' );

/** ------------- Plugin Deactivation ---------------------------------------
 *
 * When the plugin is deactivated this function is executed
 *
 * Nothing is required to be done with this plugin but I left the function here
 * as a reminder
 * --------------------------------------------------------------------------- */
function deactivate() {
	// Custom post type is automaticall removed on deactivation.
	// Clear the permalinks after the post type has been removed.
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'EndorseByKHA\deactivate' );

/** ------------- Plugin Setup ---------------------------------------
 * When the plugin is loaded this function is executed.
 *
 * Loads these files on setup.
 * Activates the translation on setup.
 * ---------------------------------------------------------------------- */
function plugin_setup() {
	global $options;
	require_once dirname( __FILE__ ) . '/includes/UpdateClient.class.php';
	require_once dirname( __FILE__ ) . '/includes/functions.php';
	require_once dirname( __FILE__ ) . '/includes/customize.php';
	require_once dirname( __FILE__ ) . '/includes/custom-post-meta.php';
	require_once dirname( __FILE__ ) . '/includes/shortcodes.php';
	require_once dirname( __FILE__ ) . '/widgets/class-endorseinputwidget.php';
	require_once dirname( __FILE__ ) . '/widgets/class-endorsedisplaywidget.php';
	require_once dirname( __FILE__ ) . '/widgets/class-endorsesliderwidget.php';
	// load plugin options.
	$options = get_options();
	// enable translation.
	load_plugin_textdomain( 'endorse', false, 'endorse/languages' );
}
add_action( 'plugins_loaded', 'EndorseByKHA\plugin_setup' );

/**
 * Load options.php if in admin.
 */
if ( is_admin() ) {
	require_once dirname( __FILE__ ) . '/includes/options-page.php';
}

/**
 * Admin styles and scripts.
 */
function admin_setup() {
	global $options;
	if ( true === is_rtl() ) {
		wp_enqueue_style( 'endorse_styles', plugin_dir_url( __FILE__ ) . 'css/admin-styles-rtl.css', array(), ENDORSE_VERSION );
	} else {
		wp_enqueue_style( 'endorse_styles', plugin_dir_url( __FILE__ ) . 'css/admin-styles.css', array(), ENDORSE_VERSION );
	}
	wp_enqueue_script( 'endorse_admin_js', plugins_url() . '/endorse/js/admin-doc-ready.js', array( 'jquery' ), ENDORSE_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'EndorseByKHA\admin_setup' );

/** ------------- Enqueue Styles ---------------------------------------
 *
 * When the plugin is activated this function is executed
 *
 * Loads these files on setup.
 * Activates the translation on setup.
 * ----------------------------------------------------------------------- */
function add_styles() {
	global $options;
	if ( true === is_rtl() ) {
		wp_enqueue_style( 'endorse_styles', plugin_dir_url( __FILE__ ) . 'css/endorse-styles-rtl.css', array(), ENDORSE_VERSION );
	} else {
		wp_enqueue_style( 'endorse_styles', plugin_dir_url( __FILE__ ) . 'css/endorse-styles.css', array(), ENDORSE_VERSION );
	}
	$custom_css = custom_css();
	wp_add_inline_style( 'endorse_styles', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'EndorseByKHA\add_styles' );

/**
 * Enqueue Scripts.
 *
 * Loads the excerpt, rotator, ratings, and reCaptcha scripts if required.
 *
 * @uses katb_get_options() from katb_functions.php
 */
function load_scripts() {
	global $options;
	if ( true === $options['use_recaptcha'] ) {
		// CaptchaCallback is in the doc ready so it is loaded first.
		wp_enqueue_script( 'endorse-recaptcha-doc-ready', plugins_url() . '/endorse/js/recaptcha-doc-ready.js', array( 'jquery' ), ENDORSE_VERSION, true );
		wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js?onload=EndorseCaptchaCallback&render=explicit', array( 'endorse-recaptcha-doc-ready' ), ENDORSE_VERSION, true );
	}
}
add_action( 'wp_enqueue_scripts', 'EndorseByKHA\load_scripts' );
