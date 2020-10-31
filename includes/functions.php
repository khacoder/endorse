<?php
/**
 * Endorse Functions
 * This file contains general functions used by Endorse..
 *
 * @package   Endorse WordPress Plugin
 * @copyright Copyright (C) 2019, Kevin Archibald
 * @license   GPLv2 or later http://www.gnu.org/licenses/quick-guide-gplv2.html
 * @author    kevinhaig <kevinsspace.ca/contact/>
 * Endorse is distributed under the terms of the GNU GPL.
 */

namespace EndorseByKHA;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
global $options;
// List Custom post id's in the admin post listing.
/**
 * Add post id to default array.
 *
 * @param array $columns the array of custom post elements.
 */
function posts_columns_id( $columns ) {
	$columns['kha-post-id'] = esc_html__( 'Post ID', 'endorse' );
	$columns['kha-content'] = esc_html__( 'Content', 'endorse' );
	$columns['kha-author']  = esc_html__( 'Author', 'endorse' );
	unset( $columns['comments'] );
	return $columns;
}
add_filter( 'manage_edit-khatestimonial_columns', 'EndorseByKHA\posts_columns_id', 5 );
/**
 * Print post id numbers in admin panel.
 *
 * @param string $column_name is the column to print in.
 * @param string $id is the custom post id.
 */
function posts_custom_id_columns( $column_name, $id ) {
	$meta = get_post_meta( get_the_ID(), 'endorse_meta', true );
	if ( 'kha-post-id' === $column_name ) {
			echo intval( $id );
	} elseif ( 'kha-content' === $column_name ) {
		$content = get_the_excerpt();
		echo wp_kses_post( $content );
	} elseif ( 'kha-author' === $column_name ) {
		echo esc_html( $meta['author'] );
	} elseif ( 'kha-email' === $column_name ) {
		echo esc_html( $meta['email'] );
	}
}
add_action( 'manage_posts_custom_column', 'EndorseByKHA\posts_custom_id_columns', 5, 2 );
/**
 * Get Endorse Plugin Option Defaults
 *
 * Array that holds all of the deault values
 * for Endorse Plugin Options.
 *
 * @return  array current default values for all Endorse options
 */
function get_option_defaults() {
	$defaults = array(
		// Endorse Plugin General.
		'edit_role'                       => 'Administrator',
		'email'                           => '',
		'custom1'                         => '',
		'custom2'                         => '',
		'remove_rtl_support'              => false,
		'use_ratings'                     => false,
		'star_color'                      => '#EACB1E',
		'star_shadow_color'               => '#000000',
		'disable_rotator_script'          => false,
		'more_label'                      => esc_html__( 'more', 'endorse' ),
		'custom_css'                      => '',
		// Data Protection option Defaults.
		'use_data_protection'             => false,
		'dp_save_data_note'               => esc_html__( 'data will be saved', 'endorse' ),
		'dp_remove_permalink'             => '',
		'remove_page_intro'               => esc_html__( 'Data Protection requires the author of this testimonial be allowed to have it removed if requested. Please provide your email and an optional comment reason for removal, in the form below. The administrator of this site will be contacted and the testimonial will be requested for removal. Note that the email you provide below and the original author email must match or the testimonial may not be removed.', 'endorse' ),
		'remove_page_direct_access'       => esc_html__( 'You have not selected a testimonial to remove. Please provide additional information below so we may identify which testimonial to remove.', 'endorse' ),
		// Input form general.
		'auto_approve'                    => false,
		'use_honeypot'                    => false,
		'use_recaptcha'                   => false,
		'recaptcha_site_key'              => '',
		'recaptcha_secret_key'            => '',
		'include_website'                 => false,
		'require_website'                 => false,
		'include_location'                => false,
		'require_location'                => false,
		'include_custom1'                 => false,
		'require_custom1'                 => false,
		'include_custom2'                 => false,
		'require_custom2'                 => false,
		'include_gravatar_link'           => false,
		// Content area specific input form options.
		'input_content_font_size'         => '1em',
		'input_content_disable_popup'     => false,
		'input_content_show_html_strip'   => false,
		'input_content_include_title'     => false,
		'input_content_title_text'        => esc_html__( 'Add a Testimonial', 'endorse' ),
		'input_content_include_note'      => false,
		'input_content_note_text'         => esc_html__( 'Email is not published', 'endorse' ),
		'input_content_labels_inside'     => false,
		'input_content_author_label'      => esc_html__( 'Author*', 'endorse' ),
		'input_content_email_label'       => esc_html__( 'Email*', 'endorse' ),
		'input_content_website_label'     => esc_html__( 'Website', 'endorse' ),
		'input_content_location_label'    => esc_html__( 'Location', 'endorse' ),
		'input_content_custom1_label'     => esc_html__( 'Custom 1', 'endorse' ),
		'input_content_custom2_label'     => esc_html__( 'Custom 2', 'endorse' ),
		'input_content_rating_label'      => esc_html__( 'Rating', 'endorse' ),
		'input_content_title_label'       => esc_html__( 'Title*', 'endorse' ),
		'input_content_testimonial_label' => esc_html__( 'Testimonial*', 'endorse' ),
		'input_content_submit_label'      => esc_html__( 'Submit', 'endorse' ),
		'input_content_required_label'    => esc_html__( '*Required', 'endorse' ),
		// Widget specific input form options.
		'input_widget_font_size'          => '1em',
		'input_widget_disable_popup'      => false,
		'input_widget_show_html_strip'    => false,
		'input_widget_include_note'       => false,
		'input_widget_note_text'          => esc_html__( 'Email is not published', 'endorse' ),
		'input_widget_labels_above'       => false,
		'input_widget_author_label'       => esc_html__( 'Author*', 'endorse' ),
		'input_widget_email_label'        => esc_html__( 'Email*', 'endorse' ),
		'input_widget_website_label'      => esc_html__( 'Website', 'endorse' ),
		'input_widget_location_label'     => esc_html__( 'Location', 'endorse' ),
		'input_widget_custom1_label'      => esc_html__( 'Custom 1', 'endorse' ),
		'input_widget_custom2_label'      => esc_html__( 'Custom 2', 'endorse' ),
		'input_widget_rating_label'       => esc_html__( 'Rating', 'endorse' ),
		'input_widget_title_label'        => esc_html__( 'Title*', 'endorse' ),
		'input_widget_testimonial_label'  => esc_html__( 'Testimonial*', 'endorse' ),
		'input_widget_submit_label'       => esc_html__( 'Submit', 'endorse' ),
		'input_widget_required_label'     => esc_html__( '*Required', 'endorse' ),
		// Content display options.
		'content_base_font_size'          => '1em',
		'content_use_excerpts'            => false,
		'content_excerpt_length'          => '30',
		'content_testimonials_per_page'   => '3',
		'content_show_website'            => false,
		'content_show_date'               => false,
		'content_show_location'           => false,
		'content_show_custom1'            => false,
		'content_show_custom2'            => false,
		'content_use_gravatars'           => false,
		// Widget display options.
		'widget_base_font_size'           => '1em',
		'widget_separation_line_color'    => '#000000',
		'widget_use_excerpts'             => false,
		'widget_excerpt_length'           => '20',
		'widget_show_website'             => false,
		'widget_show_date'                => false,
		'widget_show_location'            => false,
		'widget_show_custom1'             => false,
		'widget_show_custom2'             => false,
		'widget_use_gravatars'            => false,
	);
	return $defaults;
}

/**
 * Get Options
 *
 * Array that holds all of the defined values
 * for Endorse Plugin Options. If the user
 * has not specified a value for a given plugin
 * option, then the option's default value is
 * used instead.
 *
 * @uses   get_option_defaults() in this file
 * @return array $options current values for all plugin options
 */
function get_options() {
	// Globalize the variable that holds the Theme options.
	global $options;
	// Get the option defaults.
	$option_defaults = get_option_defaults();
	// Parse the stored options with the defaults.
	$options = wp_parse_args( get_option( 'endorse_customize_options', array() ), $option_defaults );
	return $options;
}

// Call options and set up for files below.
global $options;
$options = get_options();

/**
 * Supplies array of filter parameters for wp_kses($text,$allowed_html)
 * Only this html will be allowed in testimonials submitted by visitors
 * used in check_for_submitted_testimonial()
 * and in input_testimonial_widget.php function widget
 *
 * @return  array   $allowed_html
 */
function allowed_html() {

	$allowed_html = array(
		'p'      => array(),
		'br'     => array(),
		'i'      => array(),
		'h1'     => array(),
		'h2'     => array(),
		'h3'     => array(),
		'h4'     => array(),
		'h5'     => array(),
		'h6'     => array(),
		'em'     => array(),
		'strong' => array(),
		'q'      => array(),
		'a'      => array(
			'href'   => array(),
			'title'  => array(),
			'target' => array(),
		),
	);

	return apply_filters( 'endorse_allowed_html', $allowed_html );
}

/**
 * Add custom styles.
 *
 * @since 1.0.0
 */
function custom_css() {
	global $options;
	$endorse_dynamic_css = '';
	$linecolor           = hex_to_rgba( esc_html( $options['widget_separation_line_color'] ) );
	$linergba            = 'rgba( ' . $linecolor[0] . ',' . $linecolor[1] . ',' . $linecolor[2] . ',0.25 )';
	// GDPR remove icon.
	$gdprrgba             = 'rgba( ' . $linecolor[0] . ',' . $linecolor[1] . ',' . $linecolor[2] . ',0.4 )';
	$endorse_dynamic_css .= '.endorse-remove-link a svg path { fill: ' . $gdprrgba . '; }';
	// content input form dynamic styles.
	$endorse_dynamic_css .= '.endorse-content-input-wrap { font-size: ' . esc_html( $options['input_content_font_size'] ) . '; } ';
	// Widget Input Form.
	$endorse_dynamic_css .= '.endorse-widget-form { font-size: ' . esc_html( $options['input_widget_font_size'] ) . '; }';
	// Content Display.
	$endorse_dynamic_css .= '.endorse-content-wrap-side { font-size: ' . esc_html( $options['content_base_font_size'] ) . '; }';
	// Widget Display.
	$endorse_dynamic_css .= '.endorse-widget-wrap-top { font-size: ' . esc_html( $options['widget_base_font_size'] ) . '; }';
	$endorse_dynamic_css .= '.endorse-widget-box-top { border-bottom: 2px groove ' . esc_html( $options['widget_separation_line_color'] ) . '; }';
	$endorse_dynamic_css .= '.endorse-widget-title-rating-wrap { border-bottom: 1px solid ' . $linergba . '; border-top: 1px solid  ' . $linergba . '; }';
	// Other Custom Styles.
	$shadow_color         = hex_to_rgba( esc_html( $options['star_shadow_color'] ) );
	$endorse_dynamic_css .= '.endorse-css-rating { ';
	$endorse_dynamic_css .= 'color: ' . esc_html( $options['star_color'] ) . '!important;';
	$endorse_dynamic_css .= 'text-shadow: 2px 2px 2px rgba( ' . $shadow_color[0] . ',' . $shadow_color[1] . ',' . $shadow_color[2] . ',0.5 )!important; }';
	$endorse_dynamic_css .= wp_filter_nohtml_kses( $options['custom_css'] );
	return $endorse_dynamic_css;
}

/*
 * =================================================================================================
 *              Style Functions
 * =================================================================================================
 */
/**
 * This function provides the html for the css rating system.
 *
 * @param string $rating is the rating.
 * @return $rating html string
 */
function css_rating( $rating ) {
	$fullstar   = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 16 16">
<path d="M16 6.204l-5.528-0.803-2.472-5.009-2.472 5.009-5.528 0.803 4 3.899-0.944 5.505 4.944-2.599 4.944 2.599-0.944-5.505 4-3.899z"></path>
</svg>';
	$emptystar  = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 16 16">
<path d="M16 6.204l-5.528-0.803-2.472-5.009-2.472 5.009-5.528 0.803 4 3.899-0.944 5.505 4.944-2.599 4.944 2.599-0.944-5.505 4-3.899zM8 11.773l-3.492 1.836 0.667-3.888-2.825-2.753 3.904-0.567 1.746-3.537 1.746 3.537 3.904 0.567-2.825 2.753 0.667 3.888-3.492-1.836z"></path>
</svg>';
	$halfstar   = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 16 16">
<path d="M16 6.204l-5.528-0.803-2.472-5.009-2.472 5.009-5.528 0.803 4 3.899-0.944 5.505 4.944-2.599 4.944 2.599-0.944-5.505 4-3.899zM8 11.773l-0.015 0.008 0.015-8.918 1.746 3.537 3.904 0.567-2.825 2.753 0.667 3.888-3.492-1.836z"></path>
</svg>';
	$css_rating = '';
	switch ( $rating ) {
		case 0.0:
			$css_rating .= '<span class="katb-star 1">' . $emptystar . '</span>';
			$css_rating .= '<span class="katb-star 2">' . $emptystar . '</span>';
			$css_rating .= '<span class="katb-star 3">' . $emptystar . '</span>';
			$css_rating .= '<span class="katb-star 4">' . $emptystar . '</span>';
			$css_rating .= '<span class="katb-star 5">' . $emptystar . '</span>';
			break;
		case 0.5:
			$css_rating .= '<span class="katb-star 1">' . $halfstar . '</span>';
			$css_rating .= '<span class="katb-star 2">' . $emptystar . '</span>';
			$css_rating .= '<span class="katb-star 3">' . $emptystar . '</span>';
			$css_rating .= '<span class="katb-star 4">' . $emptystar . '</span>';
			$css_rating .= '<span class="katb-star 5">' . $emptystar . '</span>';
			break;
		case 1.0:
			$css_rating .= '<span class="katb-star 1">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 2">' . $emptystar . '</span>';
			$css_rating .= '<span class="katb-star 3">' . $emptystar . '</span>';
			$css_rating .= '<span class="katb-star 4">' . $emptystar . '</span>';
			$css_rating .= '<span class="katb-star 5">' . $emptystar . '</span>';
			break;
		case 1.5:
			$css_rating .= '<span class="katb-star 1">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 2">' . $halfstar . '</span>';
			$css_rating .= '<span class="katb-star 3">' . $emptystar . '</span>';
			$css_rating .= '<span class="katb-star 4">' . $emptystar . '</span>';
			$css_rating .= '<span class="katb-star 5">' . $emptystar . '</span>';
			break;
		case 2.0:
			$css_rating .= '<span class="katb-star 1">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 2">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 3">' . $emptystar . '</span>';
			$css_rating .= '<span class="katb-star 4">' . $emptystar . '</span>';
			$css_rating .= '<span class="katb-star 5">' . $emptystar . '</span>';
			break;
		case 2.5:
			$css_rating .= '<span class="katb-star 1">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 2">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 3">' . $halfstar . '</span>';
			$css_rating .= '<span class="katb-star 4">' . $emptystar . '</span>';
			$css_rating .= '<span class="katb-star 5">' . $emptystar . '</span>';
			break;
		case 3.0:
			$css_rating .= '<span class="katb-star 1">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 2">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 3">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 4">' . $emptystar . '</span>';
			$css_rating .= '<span class="katb-star 5">' . $emptystar . '</span>';
			break;
		case 3.5:
			$css_rating .= '<span class="katb-star 1">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 2">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 3">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 4">' . $halfstar . '</span>';
			$css_rating .= '<span class="katb-star 5">' . $emptystar . '</span>';
			break;
		case 4.0:
			$css_rating .= '<span class="katb-star 1">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 2">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 3">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 4">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 5">' . $emptystar . '</span>';
			break;
		case 4.5:
			$css_rating .= '<span class="katb-star 1">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 2">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 3">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 4">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 5">' . $halfstar . '</span>';
			break;
		case 5.0:
			$css_rating .= '<span class="katb-star 1">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 2">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 3">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 4">' . $fullstar . '</span>';
			$css_rating .= '<span class="katb-star 5">' . $fullstar . '</span>';
			break;
	}
	return $css_rating;
}

/**
 * This function is a helper function to inset a gravatar/image if one exists.
 *
 * @param string  $image_url if uploaded image, this is the url.
 * @param string  $gravatar_size user option.
 * @param boolean $use_gravatars user option.
 * @param string  $email address of author.
 *
 * @return $html gravatar insert html
 */
function insert_gravatar( $image_url, $gravatar_size, $use_gravatars, $email ) {
	// If uploaded photo use that, else use gravatar if selected and available.
	// If gravatars are enabled, check for valid avatar.
	if ( true === $use_gravatars ) {
		$has_valid_avatar = validate_gravatar( $email );
	} else {
		$has_valid_avatar = false;
	}
	$html = '';
	if ( '' !== $image_url ) {
		$html .= '<span class="endorse-avatar" style="width:' . esc_attr( $gravatar_size ) . 'px; height:auto;" ><img class="avatar" src="' . esc_url( $image_url ) . '" alt="Author Picture" /></span>';
	} elseif ( true === $use_gravatars && true === $has_valid_avatar ) {
		$size        = $gravatar_size;
		$avatar_html = get_avatar( $email, $size );
		$html       .= '<span class="endorse-avatar" style="width:' . esc_attr( $gravatar_size ) . 'px; height:auto;" >' . $avatar_html . '</span>';
	}
	return $html;
}

/**
 * Test if gravatar exists for a given email
 *
 * Source: http://codex.wordpress.org/Using_Gravatars.
 *
 * @param string $email is the email to use for the gravatar check.
 *
 * @return boolean $has_valid_avatar
 */
function validate_gravatar( $email ) {
	// Craft a potential url and test its headers.
	$hash    = md5( strtolower( trim( $email ) ) );
	$uri     = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
	$headers = @get_headers( $uri ); // phpcs:ignore
	if ( ! preg_match( "|200|", $headers[0] ) ) { // phpcs:ignore
		$has_valid_avatar = false;
	} else {
		$has_valid_avatar = true;
	}
	return $has_valid_avatar;
}

/**
 * Helper function to display the title of the testimonial.
 * Used by content and widget display templates.
 *
 * @param string $testimonial_title is the title entered.
 */
function get_title_html( $testimonial_title ) {
	if ( '' === $testimonial_title ) {
		$testimonial_title = esc_html__( 'Testimonial', 'endorse' );
	}
	return '<span class="endorse-testimonial-title">' . esc_html( wp_unslash( $testimonial_title ) ) . '&nbsp;</span>';
}

/**
 * Helper function to display the rating of the testimonial.
 * Used by content and widget display templates.
 *
 * @param boolean $use_ratings switch for using ratings.
 * @param string  $rating is the rating.
 */
function get_rating_html( $use_ratings, $rating ) {
	if ( true === $use_ratings ) {
		if ( '' === $rating ) {
			$rating = 0;
		}
		if ( 0 < $rating ) {
			return '<span class="endorse-css-rating">' . css_rating( $rating ) . '</span>';
		}
	}
}

/**
 * Helper function to display the author of the testimonial.
 * Used by content and widget display templates.
 *
 * @param string $author_name is the name of the testimonial author.
 * @param string $divider is the html for a divider.
 */
function get_author_html( $author_name, $divider ) {
	return '<span class="endorse-testimonial-author">' . esc_html( wp_unslash( $author_name ) ) . $divider . '</span>';
}

/**
 * Helper function to display the location of the testimonial.
 * Used by content and widget display templates.
 *
 * @param boolean $show_location switch to display the location.
 * @param string  $location is the location.
 * @param string  $divider is the html for a divider.
 */
function get_location_html( $show_location, $location, $divider ) {
	if ( true === $show_location && '' !== $location ) {
		return '<span class="endorse-location">' . esc_html( wp_unslash( $location ) ) . $divider . '</span>';
	}
}

/**
 * Helper function to display the date of the testimonial.
 * Used by content and widget display templates.
 *
 * @param boolean $show_date switch to display the date.
 * @param string  $date is the date of the testimonial.
 * @param string  $divider is the html for a divider.
 */
function get_date_html( $show_date, $date, $divider ) {
	if ( true === $show_date ) {
		return '<span class="endorse-date">' . esc_html( date_i18n( get_option( 'date_format' ), strtotime( $date ) ) ) . $divider . '</span>';
	}
}

/**
 * Helper function to display the custom1 of the testimonial.
 * Used by content and widget display templates.
 *
 * @param boolean $show_custom1 switch to display custom1 content.
 * @param string  $custom1 is the custom1 content.
 * @param string  $divider is the html for a divider.
 */
function get_custom1_html( $show_custom1, $custom1, $divider ) {
	if ( true === $show_custom1 && '' !== $custom1 ) {
		return '<span class="endorse-custom1">' . esc_html( wp_unslash( $custom1 ) ) . $divider . '</span>';
	}
}

/**
 * Helper function to display the custom2 of the testimonial.
 * Used by content and widget display templates.
 *
 * @param boolean $show_custom2 switch to display custom2 content.
 * @param string  $custom2 is the custom2 content.
 * @param string  $divider is the html for a divider.
 */
function get_custom2_html( $show_custom2, $custom2, $divider ) {
	if ( true === $show_custom2 && '' !== $custom2 ) {
		return '<span class="endorse-custom2">' . esc_html( wp_unslash( $custom2 ) ) . $divider . '</span>';
	}
}

/**
 * Helper function to display the website of the testimonial.
 * Used by content and widget display templates.
 *
 * @param boolean $show_website switch to display the website link.
 * @param string  $website is the website url.
 * @param string  $divider is the html for a divider.
 */
function get_website_html( $show_website, $website, $divider ) {
	if ( true === $show_website && '' !== $website ) {
		return '<span class="endorse-website"><a href="' . esc_url( $website ) . '" title="' . esc_url( $website ) . '" target="_blank" rel="nofollow" >' . esc_html__( 'Website', 'endorse' ) . '</a>' . $divider . '</span>';
	}
}

/**
 * Helper function to display the GDPR remove link.
 * Used by content and widget display templates.
 *
 * @param string $dp_remove_permalink remove page permalink.
 * @param string $id is the id of the testimonial to be removed.
 * @param string $divider is the html for a divider.
 * @param string $widget_or_content indicates the source of the request, 'widget' or 'content'.
 */
function get_gdpr_link( $dp_remove_permalink, $id, $divider, $widget_or_content ) {
	global $options;
	$use_data_protection = $options['use_data_protection'];
	if ( true === $use_data_protection && '' !== trim( $dp_remove_permalink ) ) {
		return '<span class="endorse-remove-link ' . esc_attr( $widget_or_content ) . '"><a href="' . esc_url( $dp_remove_permalink ) . '?id=' . esc_attr( $id ) . '" title="' . esc_attr__( 'Request Removal', 'endorse' ) . '" rel="nofollow" ><svg xmlns="http://www.w3.org/2000/svg" width="10px" viewBox="0 0 448 512"><path d="M400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zM92 296c-6.6 0-12-5.4-12-12v-56c0-6.6 5.4-12 12-12h264c6.6 0 12 5.4 12 12v56c0 6.6-5.4 12-12 12H92z"/></svg></a>' . $divider . '</span>';
	}
}

/**
 * This function hex colors to rgba colors.
 *
 * @param string $hex is the hex color string.
 *
 * @return $rgb is the array with trhe rgb values.
 */
function hex_to_rgba( $hex ) {
	$hex = str_replace( '#', '', $hex );
	if ( 3 === strlen( $hex ) ) {
		$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
		$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
		$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
	} else {
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
	}
	$rgb = array( $r, $g, $b );
	return $rgb;
}

/**
 * This function takes a string of id's and validates them.
 *
 * @param string $ids is string of id's.
 *
 * @return array $ids whitelisted/validated.
 */
function validate_ids( $ids ) {
	$id_picks           = array();
	$id_picks_processed = array();
	$id_picks           = explode( ',', $ids );
	$counter            = 0;
	foreach ( $id_picks as $pick ) {
		$id_picks_processed[ $counter ] = intval( $id_picks[ $counter ] );
		if ( 1 > $id_picks_processed[ $counter ] ) {
			$id_picks_processed[ $counter ] = 1;
		}
		$counter++;
	}
	$id_picks_processed = array_unique( $id_picks_processed );
	return $id_picks_processed;
}

/**
 * This function allows custom excerpt control.
 *
 * @param string $args for function.
 *
 * @return html excerpt content
 */
function endorse_get_excerpt( $args = array() ) {
	// Defaults.
	$defaults = array(
		'post'            => '',
		'length'          => 40,
		'readmore_text'   => esc_html__( 'read more', 'endorse' ),
		'readmore_after'  => '',
		'custom_excerpts' => true,
		'disable_more'    => false,
	);
	// Apply filters.
	$defaults = apply_filters( 'endorse_get_excerpt_defaults', $defaults );
	// Parse args.
	$args = wp_parse_args( $args, $defaults );
	// Apply filters to args.
	$args = apply_filters( 'endorse_get_excerpt_args', $args );
	// Extract.
	extract( $args );//phpcs:ignore
	// Get global post data.
	if ( ! $post ) {
		global $post;
	}
	// Get post ID.
	$post_id = $post->ID;
	// Check for custom excerpt.
	if ( $custom_excerpts && has_excerpt( $post_id ) ) {
		$output = $post->post_excerpt;
	} else {
		// No custom excerpt...so lets generate one.
		// Readmore link.
		$readmore_link = '...<br/><a href="' . esc_url( get_permalink( $post_id ) ) . '" class="readmore">' . $readmore_text . $readmore_after . '</a>';
		// Check for more tag and return content if it exists.
		if ( ! $disable_more && strpos( $post->post_content, '<!--more-->' ) ) {
			$output = apply_filters( 'the_content', get_the_content( $readmore_text . $readmore_after ) );
		} else {
			// No more tag defined so generate excerpt using wp_trim_words.
			// Generate excerpt.
			$output = wp_trim_words( strip_shortcodes( $post->post_content ), $length, $readmore_link );
		}
	}
	// Apply filters and echo.
	return apply_filters( 'endorse_get_excerpt', $output );
}

/**
 * This function sends the email notification after the testimonial has been input.
 *
 * @param string $author name of author.
 * @param string $author_email author's email address.
 * @param string $testimonial html testimonial string.
 */
function email_notification( $author, $author_email, $testimonial ) {
	global $options;
	// Add filter to use html in contact area.
	add_filter( 'wp_mail_content_type', 'EndorseByKHA\set_mail_content_type' );
	// Get email address.
	if ( isset( $options['email'] ) && '' !== $options['email'] && is_email( $options['email'] ) ) {
		$emailto = $options['email'];
	} else {
		$emailto = is_email( get_option( 'admin_email' ) );
	}
	// Subject.
	$subject_trans = __( 'You have received a testimonial!', 'endorse' );
	$subject       = $subject_trans;
	// Email content.
	$body_trans = __( 'Name: ', 'endorses' ) . ' ' . esc_html( $author ) . '<br/><br/>'
			. __( 'Email: ', 'endorse' ) . ' ' . is_email( $author_email ) . '<br/><br/>'
			. __( 'Comments: ', 'endorse' ) . '<br/><br/>'
			. wp_kses_post( $testimonial ) . '<br/><br/>'
			. __( 'Log in to approve or view it.', 'endorse' );
	$body       = $body_trans;

	$headers_trans = __( 'From: ', 'endorse' ) . esc_html( $author ) . ' <' . is_email( $author_email ) . '>';
	$headers       = $headers_trans;

	// send email.
	wp_mail( $emailto, $subject, $body, $headers );

	// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578.
	remove_filter( 'wp_mail_content_type', 'set_mail_content_type' );
}

/**
 * This function sets up wp_mail() to use html.
 */
function set_mail_content_type() {
	return 'text/html';
}

/**
 * This function sends the email request to remove a testimonial.
 *
 * @param string $remover_email remover's email address.
 * @param string $remover_reason reason for removing testimonial.
 * @param array  $tdata data for testimonial to be removed.
 */
function remove_testimonial_request( $remover_email, $remover_reason, $tdata ) {
	global $options;
	$author_label      = $options['input_content_author_label'];
	$email_label       = $options['input_content_email_label'];
	$website_label     = $options['input_content_website_label'];
	$location_label    = $options['input_content_location_label'];
	$custom1_label     = $options['input_content_custom1_label'];
	$custom2_label     = $options['input_content_custom2_label'];
	$rating_label      = $options['input_content_rating_label'];
	$title_label       = $options['input_content_testimonial_title_label'];
	$testimonial_label = $options['input_content_testimonial_label'];
	// Add filter to use html in contact area.
	add_filter( 'wp_mail_content_type', 'EndorseByKHA\wp_mail_content_type' );
	// Get email address.
	if ( isset( $options['contact_email'] ) && '' !== $options['contact_email'] ) {
		$emailto = is_email( $options['contact_email'] );
	} else {
		$emailto = is_email( get_option( 'admin_email' ) );
	}
	if ( '' === trim( $remover_reason ) ) {
		$remover_reason = __( 'No reason was given.', 'endorse' );
	}
	$subject_trans = esc_html__( 'Request for Testimonial Removal!', 'endorse' );
	$subject       = $subject_trans;
	if ( false !== $tdata ) {
		$body_trans = esc_html__( 'Email: ', 'endorse' ) . esc_html( $remover_email ) . '<br/><br/>'
						. esc_html__( 'Reason: ', 'endorse' ) . '<br/><br/>'
						. esc_html( $remover_reason ) . '<br/><br/>'
						. esc_html__( 'Testimonial to be removed:', 'endorse' ) . '<br/><br/>'
						. esc_html__( '=========================================', 'endorse' ) . '<br/><br/>'
						. esc_html__( 'Post ID: ', 'endorse' ) . $tdata['id'] . '<br/><br/>'
						. esc_html( $title_label ) . ': ' . esc_html( $tdata['title'] ) . '<br/><br/>'
						. esc_html( $rating_label ) . ': ' . esc_html( $tdata['rating'] ) . '<br/><br/>'
						. esc_html( $author_label ) . ': ' . esc_html( $tdata['name'] ) . '<br/><br/>'
						. esc_html__( 'Date: ', 'endorse' ) . $tdata['date'] . '<br/><br/>'
						. esc_html( $location_label ) . ': ' . esc_html( $tdata['location'] ) . '<br/><br/>'
						. esc_html( $custom1_label ) . ': ' . esc_html( $tdata['custom1'] ) . '<br/><br/>'
						. esc_html( $custom1_label ) . ': ' . esc_html( $tdata['custom2'] ) . '<br/><br/>'
						. esc_html( $website_label ) . ': ' . esc_html( $tdata['url'] ) . '<br/><br/>'
						. esc_html( $testimonial_label ) . ': ' . wp_kses_post( $tdata['content'] ) . '<br/><br/>';
		$body       = $body_trans;
	} else {
		$body_trans = esc_html__( 'Email: ', 'endorse' ) . ' ' . esc_html( $remover_email ) . '<br/><br/>'
						. esc_html__( 'Reason: ', 'endorse' ) . '<br/><br/>'
						. esc_html( $remover_reason ) . '<br/><br/>';
		$body       = $body_trans;
	}
	$headers_trans = esc_html__( 'From: ', 'endorse' ) . ' <' . esc_html( $remover_email ) . '>';
	$headers       = $headers_trans;
	// Send email.
	wp_mail( $emailto, $subject, $body, $headers );
	// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578.
	remove_filter( 'wp_mail_content_type', 'wp_mail_content_type' );
}

/**
 * This function sets up wp_mail() to use html.
 */
function wp_mail_content_type() {
	return 'text/html';
}

/**
 * Add meta to single content for ...more display.
 *
 * @param string $content is the string of content for the post.
 */
function filter_the_content_in_the_main_loop( $content ) {
	global $post, $options;
	if ( is_single() && 'khatestimonial' === $post->post_type ) {
		if ( true === $options['content_use_gravatars'] || true === $options['widget_use_gravatars'] ) {
			$use_gravatars = true;
		} else {
			$use_gravatars = false;
		}
		if ( true === $options['content_show_date'] || true === $options['widget_show_date'] ) {
			$show_date = true;
		} else {
			$show_date = false;
		}
		if ( true === $options['content_show_location'] || true === $options['widget_show_location'] ) {
			$show_location = true;
		} else {
			$show_location = false;
		}
		if ( true === $options['content_show_custom1'] || true === $options['widget_show_custom1'] ) {
			$show_custom1 = true;
		} else {
			$show_custom1 = false;
		}
		if ( true === $options['content_show_custom2'] || true === $options['widget_show_custom2'] ) {
			$show_custom2 = true;
		} else {
			$show_custom2 = false;
		}
		if ( true === $options['content_show_website'] || true === $options['widget_show_website'] ) {
			$show_website = true;
		} else {
			$show_website = false;
		}
		if ( true === $options['use_ratings'] ) {
			$use_ratings = true;
		} else {
			$use_ratings = false;
		}
		$use_data_protection = $options['use_data_protection'];
		$dp_remove_permalink = $options['dp_remove_permalink'];
		$contentadd          = '';
		$meta                = get_post_meta( $post->ID, 'endorse_meta' );
		$gravatar_or_photo   = insert_gravatar( $meta[0]['image'], '120', $use_gravatars, $meta[0]['email'] );
		// setup new content and splice.
		$contentadd .= '<div class="endorse-content-box-side">';
		$contentadd .= '<div class="endorse-content-side-left">';
		if ( '' !== $gravatar_or_photo ) {
			$contentadd .= '<div class="endorse-content-side-gravatar">';
			$contentadd .= $gravatar_or_photo;
			$contentadd .= '</div>';
		}
		$contentadd .= '<div class="endorse-content-side-meta">';
		$contentadd .= get_author_html( $meta[0]['author'], $divider = '' );
		$contentadd .= get_date_html( $show_date, get_the_date(), $divider = '' );
		$contentadd .= get_location_html( $show_location, $meta[0]['location'], $divider = '' );
		$contentadd .= get_custom1_html( $show_custom1, $meta[0]['custom1'], $divider = '' );
		$contentadd .= get_custom2_html( $show_custom2, $meta[0]['custom2'], $divider = '' );
		$contentadd .= get_website_html( $show_website, $meta[0]['website'], $divider = '' );
		$contentadd .= '</div>';
		$contentadd .= '</div>';
		$contentadd .= '<div class="endorse-content-side-right">';
		$contentadd .= '<div class="endorse-title-rating-wrap-side">';
		$contentadd .= get_title_html( get_the_title() );
		$contentadd .= get_rating_html( $use_ratings, $meta[0]['rating'] );
		$contentadd .= '</div>';
		$contentadd .= '<div class="endorse-testimonial-wrap-side">';
		$contentadd .= $content;
		if ( true === $use_data_protection ) {
			$contentadd .= get_gdpr_link( $dp_remove_permalink, $post->ID, $divider, 'remove-link-content' );
		}
		$contentadd .= '</div>';
		$contentadd .= '</div>';
		$contentadd .= '</div>';
		return $contentadd;
	}
	return $content;
}
add_filter( 'the_content', 'EndorseByKHA\filter_the_content_in_the_main_loop', -1 );
