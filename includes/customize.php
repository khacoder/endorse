<?php
/**
 * Endorse Customizer.
 * This file sets up the options for the plugin.
 *
 * @package   Endorse ClassicPress Plugin
 * @copyright Copyright (C) 2020, Kevin Archibald
 * @license   GPLv2 or later http://www.gnu.org/licenses/quick-guide-gplv2.html
 * @author    kevinhaig <kevinsspace.ca/contact/>
 *
 * Endorse is distributed under the terms of the GNU GPL.
 */

namespace EndorseByKHA;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
/**
 * PANEL and SECTION ARRAY
 *
 * This function contains the array that is used to set up the panels and sub panels.
 */
function get_customizer_groups() {
	$groups = array(
		'endorse' => array(
			'name'        => 'endorse',
			'title'       => esc_html__( 'Endorse Testimonials', 'endorse' ),
			'description' => esc_html__( 'Options for the Endorse Plugin', 'endorse' ),
			'priority'    => 1000,
			'sections'    => array(
				'general_options'         => array(
					'name'        => 'general_options',
					'title'       => esc_html__( 'General Options', 'endorse' ),
					'description' => esc_html__( 'General settings for the Endorse plugin.', 'endorse' ),
					'priority'    => 1,
				),
				'data_protection_options' => array(
					'name'        => 'data_protection_options',
					'title'       => esc_html__( 'Data Protection Options', 'endorse' ),
					'description' => esc_html__( 'This section deals with Data Protection. If you choose to use this feature, users must agree to you saving testimonial data. An additional option allows you to set up a page where the user can send a request to have a testimonial removed.', 'endorse' ),
					'priority'    => 2,
				),
				'input_options_general'   => array(
					'name'        => 'input_options_general',
					'title'       => esc_html__( 'Input Form - General', 'endorse' ),
					'description' => esc_html__( 'General settings for input forms.', 'endorse' ),
					'priority'    => 3,
				),
				'input_options_content'   => array(
					'name'        => 'input_options_content',
					'title'       => esc_html__( 'Input Form - Content Area', 'endorse' ),
					'description' => esc_html__( 'Input settings for content area forms.', 'endorse' ),
					'priority'    => 4,
				),
				'input_options_widget'    => array(
					'name'        => 'input_options_widget',
					'title'       => esc_html__( 'Input Form - Widget', 'endorse' ),
					'description' => esc_html__( 'Input settings for widget forms.', 'endorse' ),
					'priority'    => 5,
				),
				'content_display_options' => array(
					'name'        => 'content_display_options',
					'title'       => esc_html__( 'Content Display Options', 'endorse' ),
					'description' => esc_html__( 'Content area display options for the Endorse plugin.', 'endorse' ),
					'priority'    => 6,
				),
				'widget_display_options'  => array(
					'name'        => 'widget_display_options',
					'title'       => esc_html__( 'Widget Display Options', 'endorse' ),
					'description' => esc_html__( 'Widget display options for the Endorse plugin.', 'endorse' ),
					'priority'    => 7,
				),
			),
		),
	);
	return apply_filters( 'get_customizer_groups', $groups );
}
/**
 * This function sets up the array with the option parameters.
 *
 * Format.
 *
 * 'id' => array(
 *       'name' => 'id',//keep the name the same as the option key. ALL OPTION NAMES MUST BE UNIQUE.
 *       'title' => esc_html__('Title of Option','endorse'),//title to show in option.
 *       'option_type' => 'text',//text, textarea, checkbox, radio, select, range, image, color, upload, scat, stag.
 *       'setting_type' => 'option', //option->saves to separate table called endorse_customize_options[].
 *                                   //   ->will not be changed in child theme.
 *                                   //theme_mod->saves to theme options table called theme_mods_endorse[].
 *                                   //   ->will have to be re-entered for child theme.
 *      'description' => esc_html__("Description of option",'endorse'), //additional documentation for option.
 *      'section' => 'panel1_section1', //panel you want the option to appear under.
 *      'priority' => 1, // order within the section that the option is displayed.
 *      'default' => '', // sane default, what default do you want to use if the user does not update this option.
 *      'transport' => 'refresh', // refresh-> page must be reloaded to use the option.
 *                                // postMessage-> you have custom Javascript to instantly update the preview window.
 *      'sanitize' => 'is_email'// sanitize callback function you want to use, see recommended below.
 *                              // checkbox=>wp_validate_boolean,
 *                              // text(email) => is_email,text(nohtml)=>sanitize_text_field,
 *                              // text(html allowed)=>wp_kses_post,text(link) => esc_url_raw,
 *                              // textarea(no html allowed)=>esc_html,textarea(html allowed)=> wp_kses_post,
 *                              // radio=>sanitize_text_field,select=>sanitize_text_field,
 *                              // image=>esc_url_raw,upload=>esc_url_raw,
 *                              // range=>sanitize_text_field,color=>sanitize_hex_color,
 *                              // scat=>sanitize_text_field,stag=>sanitize_text_field.
 * ),
 *
 * for select and radio lists and the range slider there is a choices array to also set up, see the defaults below.
 *
 * NOTE: Panel 1 Section 1 contains a comlete set of option examples available for this script.
 * Simply cut and paste to add the option of your choice.
 */
function get_customizer_option_partameters() {
	$defaults = get_option_defaults();
	$options  = array(
		// General Options.
		'email'                           => array(
			'name'         => 'email',
			'title'        => esc_html__( 'Email address to use', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'If empty : Settings->General->E-mail Address is used.', 'endorse' ),
			'section'      => 'general_options',
			'priority'     => 2,
			'default'      => $defaults['email'],
			'transport'    => 'refresh',
			'sanitize'     => 'is_email',
		),
		'custom1'                         => array(
			'name'         => 'custom1',
			'title'        => esc_html__( 'Custom 1 Field Name', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for the Post Meta. Should be the same as the label for Content and Widget Input forms.', 'endorse' ),
			'section'      => 'general_options',
			'priority'     => 3,
			'default'      => $defaults['custom1'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'custom2'                         => array(
			'name'         => 'custom2',
			'title'        => esc_html__( 'Custom 2 Field Name', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for the post meta Should be the same as the label for Content and Widget Input forms.', 'endorse' ),
			'section'      => 'general_options',
			'priority'     => 4,
			'default'      => $defaults['custom2'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'use_ratings'                     => array(
			'name'         => 'use_ratings',
			'title'        => esc_html__( 'Use ratings', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Check to use 5 star rating system.', 'endorse' ),
			'section'      => 'general_options',
			'priority'     => 6,
			'default'      => $defaults['use_ratings'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'star_color'                      => array(
			'name'         => 'star_color',
			'title'        => esc_html__( 'Star color for css stars', 'endorse' ),
			'option_type'  => 'color',
			'setting_type' => 'option',
			'description'  => esc_html__( 'default: #EACB1E, only used for css stars.', 'endorse' ),
			'section'      => 'general_options',
			'priority'     => 7,
			'default'      => $defaults['star_color'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_hex_color',
		),
		'star_shadow_color'               => array(
			'name'         => 'star_shadow_color',
			'title'        => esc_html__( 'Shadow color for the css stars', 'endorse' ),
			'option_type'  => 'color',
			'setting_type' => 'option',
			'description'  => esc_html__( 'default: #000000, only used for css stars.', 'endorse' ),
			'section'      => 'general_options',
			'priority'     => 8,
			'default'      => $defaults['star_shadow_color'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_hex_color',
		),
		'more_label'                      => array(
			'name'         => 'more_label',
			'title'        => esc_html__( 'Excerpt more label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Provide a custom label for the excerpt more link.', 'endorse' ),
			'section'      => 'general_options',
			'priority'     => 10,
			'default'      => $defaults['more_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'custom_css'                      => array(
			'name'         => 'custom_css',
			'title'        => esc_html__( 'Custom CSS', 'endorse' ),
			'option_type'  => 'textarea',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Replace default css styles or add new ones.', 'endorse' ),
			'section'      => 'general_options',
			'priority'     => 11,
			'default'      => '',
			'transport'    => 'refresh',
			'sanitize'     => 'wp_strip_all_tags',
		),
		// Data Protection Options.
		'use_data_protection'             => array(
			'name'         => 'use_data_protection',
			'title'        => esc_html__( 'Use Data Protection', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Data Protection is set up on input forms and testimonials.', 'endorse' ),
			'section'      => 'data_protection_options',
			'priority'     => 1,
			'default'      => $defaults['use_data_protection'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'dp_save_data_note'               => array(
			'name'         => 'dp_save_data_note',
			'title'        => esc_html__( 'Save data approval note', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Note to be added to the check box on testimonial input forms.', 'endorse' ),
			'section'      => 'data_protection_options',
			'priority'     => 2,
			'default'      => $defaults['dp_save_data_note'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'dp_remove_permalink'             => array(
			'name'         => 'dp_remove_permalink',
			'title'        => esc_html__( 'Request testimonial remove page permalink', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Include your page address set up for GDPR testimonial removal requests. Leave blank if you are not using this feature.', 'endorse' ),
			'section'      => 'data_protection_options',
			'priority'     => 3,
			'default'      => $defaults['dp_remove_permalink'],
			'transport'    => 'refresh',
			'sanitize'     => 'esc_url_raw',
		),
		'remove_page_intro'               => array(
			'name'         => 'remove_page_intro',
			'title'        => esc_html__( 'Request for Testimonial Removal Introduction', 'endorse' ),
			'option_type'  => 'textarea',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Add your introduction text for the request testimonial removal page, html not allowed. If deleted and left blank you can leave instructions in the page content. Example: Data Protection options allow the author of this testimonial to have it removed if requested. Please provide your email and an optional comment reason for removal, in the form below. The administrator of this site will be contacted and the testimonial will be requested for removal. Note that the email you provide below and the original author email must match or the testimonial may not be removed.', 'endorse' ),
			'section'      => 'data_protection_options',
			'priority'     => 4,
			'default'      => esc_html__( 'Data Protection options allow the author of this testimonial to have it removed if requested. Please provide your email and an optional comment reason for removal, in the form below. The administrator of this site will be contacted and the testimonial will be requested for removal. Note that the email you provide below and the original author email must match or the testimonial may not be removed.', 'endorse' ),
			'transport'    => 'refresh',
			'sanitize'     => 'wp_strip_all_tags',
		),
		'remove_page_direct_access'       => array(
			'name'         => 'remove_page_direct_access',
			'title'        => esc_html__( 'Request for Testimonial Removal No Testimonial Selected', 'endorse' ),
			'option_type'  => 'textarea',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Add a message if the testimonial removal page is directly accessed, no html allowed. If deleted and left blank you can leave instructions in the page content. Example: You have not selected a testimonial to remove. Please provide additional information below so we may identify which testimonial to remove. ', 'endorse' ),
			'section'      => 'data_protection_options',
			'priority'     => 5,
			'default'      => esc_html__( 'You have not selected a testimonial to remove. Please provide additional information below so we may identify which testimonial to remove.', 'endorse' ),
			'transport'    => 'refresh',
			'sanitize'     => 'wp_strip_all_tags',
		),
		// General input options.
		'auto_approve'                    => array(
			'name'         => 'auto_approve',
			'title'        => esc_html__( 'Auto approve testimonials', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'CAUTION: Use at your own risk.', 'endorse' ),
			'section'      => 'input_options_general',
			'priority'     => 1,
			'default'      => $defaults['auto_approve'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'use_honeypot'                    => array(
			'name'         => 'use_honeypot',
			'title'        => esc_html__( 'Use honyepot to reduce spam', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Should remove most of the spam without the need of a Captcha.', 'endorse' ),
			'section'      => 'input_options_general',
			'priority'     => 2,
			'default'      => $defaults['use_honeypot'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'use_recaptcha'                   => array(
			'name'         => 'use_recaptcha',
			'title'        => esc_html__( 'Use Google reCaptcha V2', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Sign up at https://www.google.com/recaptcha/', 'endorse' ),
			'section'      => 'input_options_general',
			'priority'     => 3,
			'default'      => $defaults['use_recaptcha'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'recaptcha_site_key'              => array(
			'name'         => 'recaptcha_site_key',
			'title'        => esc_html__( 'reCaptcha V2 Site Key', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Required to use Google reCaptcha.', 'endorse' ),
			'section'      => 'input_options_general',
			'priority'     => 4,
			'default'      => $defaults['recaptcha_site_key'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'recaptcha_secret_key'            => array(
			'name'         => 'recaptcha_secret_key',
			'title'        => esc_html__( 'reCaptcha Secret Key', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Required to use Google reCaptcha.', 'endorse' ),
			'section'      => 'input_options_general',
			'priority'     => 5,
			'default'      => $defaults['recaptcha_secret_key'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'include_website'                 => array(
			'name'         => 'include_website',
			'title'        => esc_html__( 'Include Website Address', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Include website address on input forms.', 'endorse' ),
			'section'      => 'input_options_general',
			'priority'     => 6,
			'default'      => $defaults['include_website'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'require_website'                 => array(
			'name'         => 'require_website',
			'title'        => esc_html__( 'Require Website Address', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Require website address on input forms.', 'endorse' ),
			'section'      => 'input_options_general',
			'priority'     => 7,
			'default'      => $defaults['require_website'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'include_location'                => array(
			'name'         => 'include_location',
			'title'        => esc_html__( 'Include Location', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Include location on input forms.', 'endorse' ),
			'section'      => 'input_options_general',
			'priority'     => 8,
			'default'      => $defaults['include_location'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'require_location'                => array(
			'name'         => 'require_location',
			'title'        => esc_html__( 'Require Location', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Require location on input forms.', 'endorse' ),
			'section'      => 'input_options_general',
			'priority'     => 9,
			'default'      => $defaults['require_location'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'include_custom1'                 => array(
			'name'         => 'include_custom1',
			'title'        => esc_html__( 'Include Custom 1', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Include custom 1 on input forms.', 'endorse' ),
			'section'      => 'input_options_general',
			'priority'     => 10,
			'default'      => $defaults['include_custom1'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'require_custom1'                 => array(
			'name'         => 'require_custom1',
			'title'        => esc_html__( 'Require Custom 1', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Require custom 1 on input forms.', 'endorse' ),
			'section'      => 'input_options_general',
			'priority'     => 11,
			'default'      => $defaults['require_custom1'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'include_custom2'                 => array(
			'name'         => 'include_custom2',
			'title'        => esc_html__( 'Include Custom 2', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Include custom 2 on input forms.', 'endorse' ),
			'section'      => 'input_options_general',
			'priority'     => 12,
			'default'      => $defaults['include_custom2'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'require_custom2'                 => array(
			'name'         => 'require_custom2',
			'title'        => esc_html__( 'Require Custom 2', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Require custom 2 on input forms.', 'endorse' ),
			'section'      => 'input_options_general',
			'priority'     => 13,
			'default'      => $defaults['require_custom2'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'include_gravatar_link'           => array(
			'name'         => 'include_gravatar_link',
			'title'        => esc_html__( 'Show Gravatar Link', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Allows users to set up their gravatar.', 'endorse' ),
			'section'      => 'input_options_general',
			'priority'     => 14,
			'default'      => $defaults['include_gravatar_link'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		// Content area Input form options.
		'input_content_font_size'         => array(
			'name'         => 'input_content_font_size',
			'title'        => esc_html__( 'Base Font Size', 'endorse' ),
			'option_type'  => 'select',
			'setting_type' => 'option',
			'choices'      => array(
				'0.875em' => esc_html__( '14px', 'endorse' ),
				'1em'     => esc_html__( '16px', 'endorse' ),
				'1.125em' => esc_html__( '18px', 'endorse' ),
				'1.25em'  => esc_html__( '20px', 'endorse' ),
			),
			'description'  => esc_html__( 'Select the font size for the input form.', 'endorse' ),
			'section'      => 'input_options_content',
			'priority'     => 1,
			'default'      => $defaults['input_content_font_size'],
			'transport'    => 'refresh',
			'sanitize'     => 'EndorseByKHA\endorse_sanitize_font_size',
		),
		'input_content_disable_popup'     => array(
			'name'         => 'input_content_disable_popup',
			'title'        => esc_html__( 'Disable Popup Messages', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Messages for errors and thankyou.', 'endorse' ),
			'section'      => 'input_options_content',
			'priority'     => 2,
			'default'      => $defaults['input_content_disable_popup'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'input_content_show_html_strip'   => array(
			'name'         => 'input_content_show_html_strip',
			'title'        => esc_html__( 'Show HTML Message', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Display html allowed message.', 'endorse' ),
			'section'      => 'input_options_content',
			'priority'     => 3,
			'default'      => $defaults['input_content_show_html_strip'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'input_content_include_title'     => array(
			'name'         => 'input_content_include_title',
			'title'        => esc_html__( 'Include Title', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'You can add a title to the top of the form.', 'endorse' ),
			'section'      => 'input_options_content',
			'priority'     => 4,
			'default'      => $defaults['input_content_include_title'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'input_content_title_text'        => array(
			'name'         => 'input_content_title_text',
			'title'        => esc_html__( 'Title Text', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Provide the text for your title.', 'endorse' ),
			'section'      => 'input_options_content',
			'priority'     => 5,
			'default'      => $defaults['input_content_title_text'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_content_include_note'      => array(
			'name'         => 'input_content_include_note',
			'title'        => esc_html__( 'Include Note.', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'You can add a note to the top of the form.', 'endorse' ),
			'section'      => 'input_options_content',
			'priority'     => 6,
			'default'      => $defaults['input_content_include_note'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'input_content_note_text'         => array(
			'name'         => 'input_content_note_text',
			'title'        => esc_html__( 'Note Text', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Provide the text for your note.', 'endorse' ),
			'section'      => 'input_options_content',
			'priority'     => 7,
			'default'      => $defaults['input_content_note_text'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_content_labels_inside'     => array(
			'name'         => 'input_content_labels_inside',
			'title'        => esc_html__( 'Display Labels Inside', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Check to display labels inside input boxes.', 'endorse' ),
			'section'      => 'input_options_content',
			'priority'     => 8,
			'default'      => $defaults['input_content_labels_inside'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'input_content_author_label'      => array(
			'name'         => 'input_content_author_label',
			'title'        => esc_html__( 'Author Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for author input box.', 'endorse' ),
			'section'      => 'input_options_content',
			'priority'     => 9,
			'default'      => $defaults['input_content_author_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_content_email_label'       => array(
			'name'         => 'input_content_email_label',
			'title'        => esc_html__( 'Email Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for email input box.', 'endorse' ),
			'section'      => 'input_options_content',
			'priority'     => 10,
			'default'      => $defaults['input_content_email_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_content_website_label'     => array(
			'name'         => 'input_content_website_label',
			'title'        => esc_html__( 'Website Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for website input box.', 'endorse' ),
			'section'      => 'input_options_content',
			'priority'     => 11,
			'default'      => $defaults['input_content_website_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_content_location_label'    => array(
			'name'         => 'input_content_location_label',
			'title'        => esc_html__( 'Location Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for location input box.', 'endorse' ),
			'section'      => 'input_options_content',
			'priority'     => 12,
			'default'      => $defaults['input_content_location_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_content_custom1_label'     => array(
			'name'         => 'input_content_custom1_label',
			'title'        => esc_html__( 'Custom 1 Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for custom 1 input box.', 'endorse' ),
			'section'      => 'input_options_content',
			'priority'     => 13,
			'default'      => $defaults['input_content_custom1_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_content_custom2_label'     => array(
			'name'         => 'input_content_custom2_label',
			'title'        => esc_html__( 'Custom 2 Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for custom 2 input box.', 'endorse' ),
			'section'      => 'input_options_content',
			'priority'     => 14,
			'default'      => $defaults['input_content_custom2_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_content_rating_label'      => array(
			'name'         => 'input_content_rating_label',
			'title'        => esc_html__( 'Rating Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for rating select box.', 'endorse' ),
			'section'      => 'input_options_content',
			'priority'     => 15,
			'default'      => $defaults['input_content_rating_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_content_title_label'       => array(
			'name'         => 'input_content_title_label',
			'title'        => esc_html__( 'Title label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for title input box.', 'endorse' ),
			'section'      => 'input_options_content',
			'priority'     => 16,
			'default'      => $defaults['input_content_title_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_content_testimonial_label' => array(
			'name'         => 'input_content_testimonial_label',
			'title'        => esc_html__( 'Testimonial Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for testimonial input box.', 'endorse' ),
			'section'      => 'input_options_content',
			'priority'     => 17,
			'default'      => $defaults['input_content_testimonial_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_content_submit_label'      => array(
			'name'         => 'input_content_submit_label',
			'title'        => esc_html__( 'Submit Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for submit button.', 'endorse' ),
			'section'      => 'input_options_content',
			'priority'     => 18,
			'default'      => $defaults['input_content_submit_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_content_required_label'    => array(
			'name'         => 'input_content_required_label',
			'title'        => esc_html__( 'Required Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for required note.', 'endorse' ),
			'section'      => 'input_options_content',
			'priority'     => 19,
			'default'      => $defaults['input_content_required_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		// Widget input form options.
		'input_widget_font_size'          => array(
			'name'         => 'input_widget_font_size',
			'title'        => esc_html__( 'Base Font Size', 'endorse' ),
			'option_type'  => 'select',
			'setting_type' => 'option',
			'choices'      => array(
				'0.875em' => esc_html__( '14px', 'endorse' ),
				'1em'     => esc_html__( '16px', 'endorse' ),
				'1.125em' => esc_html__( '18px', 'endorse' ),
				'1.25em'  => esc_html__( '20px', 'endorse' ),
			),
			'description'  => esc_html__( 'Select the font size for the input form.', 'endorse' ),
			'section'      => 'input_options_widget',
			'priority'     => 1,
			'default'      => $defaults['input_widget_font_size'],
			'transport'    => 'refresh',
			'sanitize'     => 'EndorseByKHA\endorse_sanitize_font_size',
		),
		'input_widget_disable_popup'      => array(
			'name'         => 'input_widget_disable_popup',
			'title'        => esc_html__( 'Disable Popup Messages', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Messages for errors and thankyou.', 'endorse' ),
			'section'      => 'input_options_widget',
			'priority'     => 2,
			'default'      => $defaults['input_widget_disable_popup'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'input_widget_show_html_strip'    => array(
			'name'         => 'input_widget_show_html_strip',
			'title'        => esc_html__( 'Show HTML Message', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Display html allowed message.', 'endorse' ),
			'section'      => 'input_options_widget',
			'priority'     => 3,
			'default'      => $defaults['input_widget_show_html_strip'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'input_widget_include_note'       => array(
			'name'         => 'input_widget_include_note',
			'title'        => esc_html__( 'Include Note.', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'You can add a note to the top of the form.', 'endorse' ),
			'section'      => 'input_options_widget',
			'priority'     => 4,
			'default'      => $defaults['input_widget_include_note'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'input_widget_note_text'          => array(
			'name'         => 'input_widget_note_text',
			'title'        => esc_html__( 'Note Text', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Provide the text for your note.', 'endorse' ),
			'section'      => 'input_options_widget',
			'priority'     => 5,
			'default'      => $defaults['input_widget_note_text'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_widget_labels_above'       => array(
			'name'         => 'input_widget_labels_above',
			'title'        => esc_html__( 'Display Labels Above', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Check to display labels above input boxes.', 'endorse' ),
			'section'      => 'input_options_widget',
			'priority'     => 6,
			'default'      => $defaults['input_widget_labels_above'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'input_widget_author_label'       => array(
			'name'         => 'input_widget_author_label',
			'title'        => esc_html__( 'Author Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for author input box.', 'endorse' ),
			'section'      => 'input_options_widget',
			'priority'     => 7,
			'default'      => $defaults['input_widget_author_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_widget_email_label'        => array(
			'name'         => 'input_widget_email_label',
			'title'        => esc_html__( 'Email Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for email input box.', 'endorse' ),
			'section'      => 'input_options_widget',
			'priority'     => 8,
			'default'      => $defaults['input_widget_email_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_widget_website_label'      => array(
			'name'         => 'input_widget_website_label',
			'title'        => esc_html__( 'Website Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for website input box.', 'endorse' ),
			'section'      => 'input_options_widget',
			'priority'     => 9,
			'default'      => $defaults['input_widget_website_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_widget_location_label'     => array(
			'name'         => 'input_widget_location_label',
			'title'        => esc_html__( 'Location Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for location input box.', 'endorse' ),
			'section'      => 'input_options_widget',
			'priority'     => 10,
			'default'      => $defaults['input_widget_location_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_widget_custom1_label'      => array(
			'name'         => 'input_widget_custom1_label',
			'title'        => esc_html__( 'Custom 1 Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for custom 1 input box.', 'endorse' ),
			'section'      => 'input_options_widget',
			'priority'     => 11,
			'default'      => $defaults['input_widget_custom1_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_widget_custom2_label'      => array(
			'name'         => 'input_widget_custom2_label',
			'title'        => esc_html__( 'Custom 2 Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for custom 2 input box.', 'endorse' ),
			'section'      => 'input_options_widget',
			'priority'     => 12,
			'default'      => $defaults['input_widget_custom2_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_widget_rating_label'       => array(
			'name'         => 'input_widget_rating_label',
			'title'        => esc_html__( 'Rating Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for rating select box.', 'endorse' ),
			'section'      => 'input_options_widget',
			'priority'     => 13,
			'default'      => $defaults['input_widget_rating_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_widget_title_label'        => array(
			'name'         => 'input_widget_title_label',
			'title'        => esc_html__( 'Title label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for title input box.', 'endorse' ),
			'section'      => 'input_options_widget',
			'priority'     => 14,
			'default'      => $defaults['input_widget_title_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_widget_testimonial_label'  => array(
			'name'         => 'input_widget_testimonial_label',
			'title'        => esc_html__( 'Testimonial Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for testimonial input box.', 'endorse' ),
			'section'      => 'input_options_widget',
			'priority'     => 15,
			'default'      => $defaults['input_widget_testimonial_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_widget_submit_label'       => array(
			'name'         => 'input_widget_submit_label',
			'title'        => esc_html__( 'Submit Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for submit button.', 'endorse' ),
			'section'      => 'input_options_widget',
			'priority'     => 16,
			'default'      => $defaults['input_widget_submit_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'input_widget_required_label'     => array(
			'name'         => 'input_widget_required_label',
			'title'        => esc_html__( 'Required Label', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Label for required note.', 'endorse' ),
			'section'      => 'input_options_widget',
			'priority'     => 17,
			'default'      => $defaults['input_widget_required_label'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		// Content Display Options.
		'content_base_font_size'          => array(
			'name'         => 'content_base_font_size',
			'title'        => esc_html__( 'Base Font Size', 'endorse' ),
			'option_type'  => 'select',
			'setting_type' => 'option',
			'choices'      => array(
				'0.875em' => esc_html__( '14px', 'endorse' ),
				'1em'     => esc_html__( '16px', 'endorse' ),
				'1.125em' => esc_html__( '18px', 'endorse' ),
				'1.25em'  => esc_html__( '20px', 'endorse' ),
			),
			'description'  => esc_html__( 'Select the font size for the input form.', 'endorse' ),
			'section'      => 'content_display_options',
			'priority'     => 1,
			'default'      => $defaults['content_base_font_size'],
			'transport'    => 'refresh',
			'sanitize'     => 'EndorseByKHA\endorse_sanitize_font_size',
		),
		'content_use_excerpts'            => array(
			'name'         => 'content_use_excerpts',
			'title'        => esc_html__( 'Use excerpts', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Only applies to content area testimonials.', 'endorse' ),
			'section'      => 'content_display_options',
			'priority'     => 2,
			'default'      => $defaults['content_use_excerpts'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'content_excerpt_length'          => array(
			'name'         => 'content_excerpt_length',
			'title'        => esc_html__( 'Excerpt words', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Number or words before excerpt is used.', 'endorse' ),
			'section'      => 'content_display_options',
			'priority'     => 3,
			'default'      => $defaults['content_excerpt_length'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'content_show_website'            => array(
			'name'         => 'content_show_website',
			'title'        => esc_html__( 'Show website in testimonial', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Check to include website', 'endorse' ),
			'section'      => 'content_display_options',
			'priority'     => 4,
			'default'      => $defaults['content_show_website'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'content_show_date'               => array(
			'name'         => 'content_show_date',
			'title'        => esc_html__( 'Show date in testimonial', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Check to include date', 'endorse' ),
			'section'      => 'content_display_options',
			'priority'     => 5,
			'default'      => $defaults['content_show_date'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'content_show_location'           => array(
			'name'         => 'content_show_location',
			'title'        => esc_html__( 'Show location in testimonial', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Check to include location', 'endorse' ),
			'section'      => 'content_display_options',
			'priority'     => 6,
			'default'      => $defaults['content_show_location'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'content_show_custom1'            => array(
			'name'         => 'content_show_custom1',
			'title'        => esc_html__( 'Show Custom 1 in testimonial', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Check to include custom 1', 'endorse' ),
			'section'      => 'content_display_options',
			'priority'     => 7,
			'default'      => $defaults['content_show_custom1'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'content_show_custom2'            => array(
			'name'         => 'content_show_custom2',
			'title'        => esc_html__( 'Show Custom 2 in testimonial', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Check to include custom 2', 'endorse' ),
			'section'      => 'content_display_options',
			'priority'     => 8,
			'default'      => $defaults['content_show_custom2'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'content_use_gravatars'           => array(
			'name'         => 'content_use_gravatars',
			'title'        => esc_html__( 'Use gravatars in testimonial', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Check to include gravatars', 'endorse' ),
			'section'      => 'content_display_options',
			'priority'     => 9,
			'default'      => $defaults['content_use_gravatars'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		// Widget Display Options.
		'widget_base_font_size'           => array(
			'name'         => 'widget_base_font_size',
			'title'        => esc_html__( 'Base Font Size', 'endorse' ),
			'option_type'  => 'select',
			'setting_type' => 'option',
			'choices'      => array(
				'0.875em' => esc_html__( '14px', 'endorse' ),
				'1em'     => esc_html__( '16px', 'endorse' ),
				'1.125em' => esc_html__( '18px', 'endorse' ),
				'1.25em'  => esc_html__( '20px', 'endorse' ),
			),
			'description'  => esc_html__( 'Select the font size for the input form.', 'endorse' ),
			'section'      => 'widget_display_options',
			'priority'     => 2,
			'default'      => $defaults['widget_base_font_size'],
			'transport'    => 'refresh',
			'sanitize'     => 'EndorseByKHA\endorse_sanitize_font_size',
		),
		'widget_separation_line_color'    => array(
			'name'         => 'widget_separation_line_color',
			'title'        => esc_html__( 'Used to separate the reviews and title.', 'endorse' ),
			'option_type'  => 'color',
			'setting_type' => 'option',
			'description'  => esc_html__( 'default: #000000.', 'endorse' ),
			'section'      => 'widget_display_options',
			'priority'     => 3,
			'default'      => $defaults['widget_separation_line_color'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_hex_color',
		),
		'widget_use_excerpts'             => array(
			'name'         => 'widget_use_excerpts',
			'title'        => esc_html__( 'Use excerpts', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Only applies to widget testimonials.', 'endorse' ),
			'section'      => 'widget_display_options',
			'priority'     => 3,
			'default'      => $defaults['widget_use_excerpts'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'widget_excerpt_length'           => array(
			'name'         => 'widget_excerpt_length',
			'title'        => esc_html__( 'Excerpt words', 'endorse' ),
			'option_type'  => 'text',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Number or words before excerpt is used.', 'endorse' ),
			'section'      => 'widget_display_options',
			'priority'     => 4,
			'default'      => $defaults['widget_excerpt_length'],
			'transport'    => 'refresh',
			'sanitize'     => 'sanitize_text_field',
		),
		'widget_show_website'             => array(
			'name'         => 'widget_show_website',
			'title'        => esc_html__( 'Show website in testimonial', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Check to include website', 'endorse' ),
			'section'      => 'widget_display_options',
			'priority'     => 5,
			'default'      => $defaults['widget_show_website'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'widget_show_date'                => array(
			'name'         => 'widget_show_date',
			'title'        => esc_html__( 'Show date in testimonial', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Check to include date', 'endorse' ),
			'section'      => 'widget_display_options',
			'priority'     => 6,
			'default'      => $defaults['widget_show_date'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'widget_show_location'            => array(
			'name'         => 'widget_show_location',
			'title'        => esc_html__( 'Show location in testimonial', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Check to include location', 'endorse' ),
			'section'      => 'widget_display_options',
			'priority'     => 7,
			'default'      => $defaults['widget_show_location'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'widget_show_custom1'             => array(
			'name'         => 'widget_show_custom1',
			'title'        => esc_html__( 'Show Custom 1 in testimonial', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Check to include custom 1', 'endorse' ),
			'section'      => 'widget_display_options',
			'priority'     => 8,
			'default'      => $defaults['widget_show_custom1'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'widget_show_custom2'             => array(
			'name'         => 'widget_show_custom2',
			'title'        => esc_html__( 'Show Custom 2 in testimonial', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Check to include custom 2', 'endorse' ),
			'section'      => 'widget_display_options',
			'priority'     => 9,
			'default'      => $defaults['widget_show_custom2'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
		'widget_use_gravatars'            => array(
			'name'         => 'widget_use_gravatars',
			'title'        => esc_html__( 'Use gravatars in testimonial', 'endorse' ),
			'option_type'  => 'checkbox',
			'setting_type' => 'option',
			'description'  => esc_html__( 'Check to include gravatars', 'endorse' ),
			'section'      => 'widget_display_options',
			'priority'     => 10,
			'default'      => $defaults['widget_use_gravatars'],
			'transport'    => 'refresh',
			'sanitize'     => 'wp_validate_boolean',
		),
	);
	return apply_filters( 'get_customizer_option_parameters', $options );
}
/**
 * Register set up the options.
 *
 * @param array $wp_customize object.
 */
function customize_register( $wp_customize ) {
	global $wp_customize;
	/**
	 * Start by adding custom controls.
	 * Set up a function to add the custom controls you want.
	 * such as add_custom_controls().
	 */
	// Set up Customizer Panels and Sections.
	setup_panels_sections();
	// set up the options.
	$customize_options = get_customizer_option_partameters();
	foreach ( $customize_options as $option ) {
		// Add option setting.
		add_setting_option( $option );
		// Add controls.
		add_control_option( $option );
	}
}
add_action( 'customize_register', 'EndorseByKHA\customize_register' );
/** ===================== SETUP PANELS AND SECTIONS =====================
 *
 * This helper function sets up panels and sections for Theme Customizer
 */
function setup_panels_sections() {
	global $wp_customize;
	$groups = array();
	$group  = array();
	$groups = get_customizer_groups();
	foreach ( $groups as $group ) {
		// Add panel.
		$wp_customize->add_panel(
			$group['name'],
			array(
				'priority'       => $group['priority'],
				'capability'     => 'edit_theme_options',
				'theme_supports' => '',
				'title'          => $group['title'],
				'description'    => $group['description'],
			)
		);
		// Add sections in panel.
		foreach ( $group['sections'] as $section ) {
			$wp_customize->add_section(
				$section['name'],
				array(
					'priority'       => $section['priority'],
					'capability'     => 'edit_theme_options',
					'theme_supports' => '',
					'title'          => $section['title'],
					'description'    => $section['description'],
					'panel'          => $group['name'],
				)
			);
		}
	}
}
/** ========================== ADD SETTING OPTION TABLE ==============================
 *
 * This helper function loads adds a setting in Theme Customizer
 * This setting function applies to options with 'setting_type'=>'option'
 *
 * -------- capability --------------------------------------------------------------
 * Note that capability is set to 'edit_theme_options' and will apply to all settings.
 * If you want to add different capabilities to each setting then change it to
 * $option['capability'] and add 'capability' => 'capability you want' to the
 * options array below
 * -------- theme_supports ----------------------------------------------------------
 * Note that theme_supports is set to '' and will apply to all settings.
 * If you want to add theme_cupports to each setting then change it to
 * $option['supports'] and add 'supports' => 'support you want' to the
 * options array below
 * -------- sanitize_js_callback ----------------------------------------------------------
 * Note that sanitize_js_callback is commented out. I initially set to '', but themecheck
 * was giving errors, and I was informed to just comment it out.
 * If you want to add sanitize_js_callback to each setting then change it to
 * $option['sanitize_js_callback'] and add 'sanitize_js_callback' => 'your js callback'
 * to the options array below.
 * ----------------------------------------------------------------------------------
 * ref: https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting
 *
 * @param array $option is the array of options.
 */
function add_setting_option( $option ) {
	global $wp_customize;
	// Add_setting for option.
	$wp_customize->add_setting(
		'endorse_customize_options[' . $option['name'] . ']',
		array(
			'default'           => $option['default'],
			'type'              => $option['setting_type'],
			'capability'        => 'edit_theme_options',
			'theme_supports'    => '',
			'transport'         => $option['transport'],
			'sanitize_callback' => $option['sanitize'],
		)
	);
}
/** ========================== ADD CONTROL OPTION TABLE ==============================
 * This helper function adds a control for Theme Customizer.
 * This function applies to options with 'setting_type'=>'option'.
 * ref: https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
 *
 * @param array $option is the array of options.
 */
function add_control_option( $option ) {
	global $wp_customize;
	if ( 'text' === $option['option_type'] ) {
		$wp_customize->add_control(
			$option['name'],
			array(
				'label'       => $option['title'],
				'section'     => $option['section'],
				'settings'    => 'endorse_customize_options[' . $option['name'] . ']',
				'type'        => $option['option_type'],
				'description' => $option['description'],
				'priority'    => $option['priority'],
			)
		);
	} elseif ( 'textarea' === $option['option_type'] ) {
		$wp_customize->add_control(
			$option['name'],
			array(
				'label'       => $option['title'],
				'section'     => $option['section'],
				'settings'    => 'endorse_customize_options[' . $option['name'] . ']',
				'type'        => $option['option_type'],
				'description' => $option['description'],
				'priority'    => $option['priority'],
			)
		);
	} elseif ( 'checkbox' === $option['option_type'] ) {
		$wp_customize->add_control(
			$option['name'],
			array(
				'label'       => $option['title'],
				'section'     => $option['section'],
				'settings'    => 'endorse_customize_options[' . $option['name'] . ']',
				'type'        => $option['option_type'],
				'description' => $option['description'],
				'priority'    => $option['priority'],
			)
		);
	} elseif ( 'radio' === $option['option_type'] ) {
		$wp_customize->add_control(
			$option['name'],
			array(
				'label'       => $option['title'],
				'section'     => $option['section'],
				'settings'    => 'endorse_customize_options[' . $option['name'] . ']',
				'type'        => $option['option_type'],
				'description' => $option['description'],
				'priority'    => $option['priority'],
				'choices'     => $option['choices'],
			)
		);
	} elseif ( 'select' === $option['option_type'] ) {
		$wp_customize->add_control(
			$option['name'],
			array(
				'label'       => $option['title'],
				'section'     => $option['section'],
				'settings'    => 'endorse_customize_options[' . $option['name'] . ']',
				'type'        => $option['option_type'],
				'description' => $option['description'],
				'priority'    => $option['priority'],
				'choices'     => $option['choices'],
			)
		);
	} elseif ( 'range' === $option['option_type'] ) {
		$wp_customize->add_control(
			$option['name'],
			array(
				'label'       => $option['title'],
				'section'     => $option['section'],
				'settings'    => 'endorse_customize_options[' . $option['name'] . ']',
				'type'        => $option['option_type'],
				'description' => $option['description'],
				'priority'    => $option['priority'],
				'input_attrs' => $option['choices'],
			)
		);
	} elseif ( 'color' === $option['option_type'] ) {
		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				$option['name'],
				array(
					'label'       => $option['title'],
					'section'     => $option['section'],
					'settings'    => 'endorse_customize_options[' . $option['name'] . ']',
					'type'        => $option['option_type'],
					'description' => $option['description'],
					'priority'    => $option['priority'],
				)
			)
		);
	} elseif ( 'image' === $option['option_type'] ) {
		$wp_customize->add_control(
			new \WP_Customize_Image_Control(
				$wp_customize,
				$option['name'],
				array(
					'label'       => $option['title'],
					'section'     => $option['section'],
					'settings'    => 'endorse_customize_options[' . $option['name'] . ']',
					'type'        => $option['option_type'],
					'description' => $option['description'],
					'priority'    => $option['priority'],
				)
			)
		);
	} elseif ( 'upload' === $option['option_type'] ) {
		$wp_customize->add_control(
			new \WP_Customize_Upload_Control(
				$wp_customize,
				$option['name'],
				array(
					'label'       => $option['title'],
					'section'     => $option['section'],
					'settings'    => 'endorse_customize_options[' . $option['name'] . ']',
					'type'        => $option['option_type'],
					'description' => $option['description'],
					'priority'    => $option['priority'],
				)
			)
		);
	}
}
/**
 * Whitelist font size choices.
 *
 * @param string $input key to whitelist.
 */
function endorse_sanitize_font_size( $input ) {
	$choices = array(
		'0.875em',
		'1em',
		'1.125em',
		'1.25em',
	);
	if ( in_array( $input, $choices, true ) ) {
		return $input;
	} else {
		return '1em';
	}
}
/**
 * Image sanitization callback example.
 *
 * Checks the image's file extension and mime type against a whitelist. If they're allowed,
 * send back the filename, otherwise, return the setting default.
 *
 * - Sanitization: image file extension
 * - Control: text, WP_Customize_Image_Control
 *
 * @see wp_check_filetype() https://developer.wordpress.org/reference/functions/wp_check_filetype/
 *
 * @param string               $image   Image filename.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string The image filename if the extension is allowed; otherwise, the setting default.
 */
function sanitize_image( $image, $setting ) {
	/*
	 * Array of valid image file types.
	 *
	 * The array includes image mime types that are included in wp_get_mime_types()
	 */
	$mimes = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif'          => 'image/gif',
		'png'          => 'image/png',
		'bmp'          => 'image/bmp',
		'tif|tiff'     => 'image/tiff',
		'ico'          => 'image/x-icon',
	);
	// Return an array with file extension and mime_type.
	$file = wp_check_filetype( $image, $mimes );
	// If $image has a valid mime_type, return it; otherwise, return the default.
	return ( $file['ext'] ? $image : $setting->default );
}
