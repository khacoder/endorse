<?php
/**
 * Endorse Shortcodes
 * This file contains the shortcodes for the plugin.
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
 * Displays the testimonial input form.
 *
 * Useage : [endorse_input group="" form="1"].
 * group : Set the group if you want the testimonial to go to a specified group, leave blank or use "All" to not use a group.
 * form : if more then one form on a page ensure each form has a unique number.
 *
 * @since 1.0.0
 * @param array $atts array of shortcode parameters, in this case only the group and form number.
 * @uses get_options() array of plugin user options.
 * @return string $input_html which is the html string for the form.
 * --------------------------------------------------------------------------------------------- */
function input_content_form( $atts ) {
	// Get options.
	global $options;
	// General input options.
	$use_data_protection = $options['use_data_protection'];
	$dp_note             = $options['dp_save_data_note'];
	$include_website     = $options['include_website'];
	$require_website     = $options['require_website'];
	$include_location    = $options['include_location'];
	$require_location    = $options['require_location'];
	$include_custom1     = $options['include_custom1'];
	$require_custom1     = $options['require_custom1'];
	$include_custom2     = $options['include_custom2'];
	$require_custom2     = $options['require_custom2'];
	$use_ratings         = $options['use_ratings'];
	$auto_approve        = $options['auto_approve'];
	$use_captcha         = $options['use_recaptcha'];
	$use_honeypot        = $options['use_honeypot'];
	$disallow_html       = $options['disallow_html'];
	// Content input form specific.
	$author_label          = $options['input_content_author_label'];
	$email_label           = $options['input_content_email_label'];
	$website_label         = $options['input_content_website_label'];
	$location_label        = $options['input_content_location_label'];
	$custom1_label         = $options['input_content_custom1_label'];
	$custom2_label         = $options['input_content_custom2_label'];
	$rating_label          = $options['input_content_rating_label'];
	$title_label           = $options['input_content_title_label'];
	$testimonial_label     = $options['input_content_testimonial_label'];
	$submit_label          = $options['input_content_submit_label'];
	$required_label        = $options['input_content_required_label'];
	$disable_popup         = $options['input_content_disable_popup'];
	$labels_inside         = $options['input_content_labels_inside'];
	$show_html_allowed     = $options['input_content_show_html_strip'];
	$include_gravatar_link = $options['include_gravatar_link'];
	$allowed_html          = allowed_html();
	// check for blanks.
	if ( '' === trim( $author_label ) ) {
		$author_label = 'Author*'; }
	if ( '' === trim( $email_label ) ) {
		$email_label = 'Email*'; }
	if ( '' === trim( $website_label ) ) {
		$website_label = 'Website'; }
	if ( '' === trim( $location_label ) ) {
		$location_label = 'Location'; }
	if ( '' === trim( $custom1_label ) ) {
		$custom1_label = 'Custom 1'; }
	if ( '' === trim( $custom2_label ) ) {
		$custom2_label = 'Custom 2'; }
	if ( '' === trim( $rating_label ) ) {
		$rating_label = 'Rating'; }
	if ( '' === trim( $title_label ) ) {
		$title_label = 'Title*'; }
	if ( '' === trim( $testimonial_label ) ) {
		$testimonial_label = 'Testimonial*'; }
	if ( '' === trim( $submit_label ) ) {
		$submit_label = 'Submit*'; }
	if ( '' === trim( $required_label ) ) {
		$author_label = '*Required'; }
	// Initialize Variables.
	if ( true === $labels_inside ) {
		$author      = $author_label;
		$email       = $email_label;
		$website     = $website_label;
		$location    = $location_label;
		$custom1     = $custom1_label;
		$custom2     = $custom2_label;
		$title       = $title_label;
		$testimonial = $testimonial_label;
		$rating      = 0.0;
	} else {
		$author      = '';
		$email       = '';
		$website     = '';
		$location    = '';
		$custom1     = '';
		$custom2     = '';
		$title       = '';
		$testimonial = '';
		$rating      = 0.0;
	}
	// Initialize GDPR switch.
	$use_dp_checked = false;
	// Initiate return string.
	$input_html = '';
	// Get shortcode variables.
	$group = esc_html( $atts['group'] );
	$form  = intval( $atts['form'] );
	if ( '' === $group || 'All' === $group || 'all' === $group ) {
		$group = '';
	}
	if ( '' === $form || 0 === $form ) {
		$form = 1;
	}
	$content_input_group   = $group;
	$content_input_form_no = $form;
	// Check for post and run with it.
	if ( isset( $_POST[ 'content_submitted' . $content_input_form_no ], $_POST[ 'content_main_form_nonce' . $content_input_form_no ] ) &&
		wp_verify_nonce( sanitize_key( $_POST[ 'content_main_form_nonce' . $content_input_form_no ] ), 'content_nonce_1' ) ) {
		// Check for valid submission.
		$bot_submission = false;
		if ( true === $options['use_honeypot'] ) {
			if ( ! empty( $_POST['custom3'] ) ) {
				$bot_submission = true;
			}
		}
		if ( false === $bot_submission ) {
			// Initialize error message.
			$content_input_error = '';
			// Order is set in admin.
			$order = '';
			// Auto approve.
			if ( true === $auto_approve ) {
				$status = 'publish';
			} else {
				$status = 'draft';
			}
			// Image url, not allowed at this time.
			$image = '';
			// GDPR approval.
			if ( true === $use_data_protection ) {
				if ( isset( $_POST['gdpr'] ) ) {
					$use_dp_checked = true;
				} else {
					$use_dp_checked = false;
					if ( false === $disable_popup ) {
						$content_input_error .= '\n - ' . esc_html__( 'You must check the box to allow us to save the testimonial data', 'endorse' );
					} else {
						$content_input_error .= '<br/> - ' . esc_html__( 'You must check the box to allow us to save the testimonial data', 'endorse' );
					}
				}
			}
			// Validate-Sanitize author.
			if ( ! empty( $_POST['content_author'] ) ) {
				$author = sanitize_text_field( wp_unslash( $_POST['content_author'] ) );
			} else {
				$author = '';
			}
			if ( '' === $author || $author === $author_label ) {
				if ( false === $disable_popup ) {
					$content_input_error .= '\n - ' . esc_html( $author_label ) . ' ' . esc_html__( 'required', 'endorse' );
				} else {
					$content_input_error .= '<br/> - ' . esc_html( $author_label ) . ' ' . esc_html__( 'required', 'endorse' );
				}
				if ( true === $labels_inside ) {
					$author = $author_label;
				} else {
					$author = '';
				}
			}
			// Validate-Sanitize E-mail, note: label will not be an email.
			if ( ! empty( $_POST['content_email'] ) ) {
				$email = sanitize_email( wp_unslash( $_POST['content_email'] ) );
			} else {
				$email = '';
			}
			if ( ! is_email( $email ) ) {
				if ( false === $disable_popup ) {
					$content_input_error .= '\n - ' . esc_html__( 'Valid email is required', 'endorse' );
				} else {
					$content_input_error .= '<br/> - ' . esc_html__( 'Valid email is required', 'endorse' );
				}
				if ( true === $labels_inside ) {
					$email = $email_label;
				} else {
					$email = '';
				}
				if ( true === $labels_inside && '' === $email ) {
					$email = $email_label;
				}
			}
			// Validate-Sanitize Website.
			if ( true === $include_website ) {
				if ( ! empty( $_POST['content_website'] ) ) {
					$website = esc_url_raw( wp_unslash( $_POST['content_website'] ) );
				} else {
					$website = '';
				}
				if ( '' === $website || $website === $website_label ) {
					if ( true === $require_website ) {
						if ( false === $disable_popup ) {
							$content_input_error .= '\n - ' . esc_html( $website_label ) . ' ' . esc_html__( 'required', 'endorse' );
						} else {
							$content_input_error .= '<br/> - ' . esc_html( $website_label ) . ' ' . esc_html__( 'required', 'endorse' );
						}
					}
					if ( true === $labels_inside ) {
						$website = $website_label;
					} else {
						$website = '';
					}
				}
			} else {
				$website = '';
			}
			// Validate Location.
			if ( true === $include_location ) {
				if ( ! empty( $_POST['content_location'] ) ) {
					$location = sanitize_text_field( wp_unslash( $_POST['content_location'] ) );
				} else {
					$location = '';
				}
				if ( '' === $location || $location === $location_label ) {
					if ( true === $require_location ) {
						if ( false === $disable_popup ) {
							$content_input_error .= '\n - ' . esc_html( $location_label ) . ' ' . esc_html__( 'required', 'endorse' );
						} else {
							$content_input_error .= '<br/> - ' . esc_html( $location_label ) . ' ' . esc_html__( 'required', 'endorse' );
						}
					}
					if ( true === $labels_inside ) {
						$location = $location_label;
					} else {
						$location = '';
					}
				}
			} else {
				$location = '';
			}
			// Validate custom1.
			if ( true === $include_custom1 ) {
				if ( ! empty( $_POST['content_custom1'] ) ) {
					$custom1 = sanitize_text_field( wp_unslash( $_POST['content_custom1'] ) );
				} else {
					$custom1 = '';
				}
				if ( '' === $custom1 || $custom1 === $custom1_label ) {
					if ( true === $require_custom1 ) {
						if ( false === $disable_popup ) {
							$content_input_error .= '\n - ' . esc_html( $custom1_label ) . ' ' . esc_html__( 'required', 'endorse' );
						} else {
							$content_input_error .= '<br/> - ' . esc_html( $custom1_label ) . ' ' . esc_html__( 'required', 'endorse' );
						}
					}
					if ( true === $labels_inside ) {
						$custom1 = $custom1_label;
					} else {
						$custom1 = '';
					}
				}
			} else {
				$custom1 = '';
			}
			// Validate custom2.
			if ( true === $include_custom2 ) {
				if ( ! empty( $_POST['content_custom2'] ) ) {
					$custom2 = sanitize_text_field( wp_unslash( $_POST['content_custom2'] ) );
				} else {
					$custom2 = '';
				}
				if ( '' === $custom2 || $custom2 === $custom2_label ) {
					if ( true === $require_custom2 ) {
						if ( false === $disable_popup ) {
							$content_input_error .= '\n - ' . esc_html( $custom2_label ) . ' ' . esc_html__( 'required', 'endorse' );
						} else {
							$content_input_error .= '<br/> - ' . esc_html( $custom2_label ) . ' ' . esc_html__( 'required', 'endorse' );
						}
					}
					if ( true === $labels_inside ) {
						$custom2 = $custom2_label;
					} else {
						$custom2 = '';
					}
				}
			} else {
				$custom2 = '';
			}
			// Validate rating.
			if ( true === $use_ratings ) {
				if ( ! empty( $_POST['content_rating'] ) ) {
					$rating = sanitize_text_field( wp_unslash( $_POST['content_rating'] ) );
				} else {
					$rating = '0.0';
				}
			} else {
				$rating = '0.0';
			}
			// Captcha Check.
			if ( true === $use_captcha ) {
				if ( ! empty( $_POST['g-recaptcha-response'] ) ) {
					$captcha_response = sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ) );
				} else {
					$captcha_response = false;
				}
				if ( ! $captcha_response ) {
					if ( false === $disable_popup ) {
						$content_input_error .= '\n - ' . esc_html__( 'Please show you are a human and check the captcha box', 'endorse' );
					} else {
						$content_input_error .= '<br/> - ' . esc_html__( 'Please show you are a human and check the captcha box', 'endorse' );
					}
				} else {
					$secret_key      = esc_html( $options['recaptcha_secret_key'] );
					$request         = wp_safe_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $captcha_response );
					$verify_response = wp_remote_retrieve_body( $request );
					$response_data   = json_decode( $verify_response );
					if ( false === $response_data->success ) {
						if ( false === $disable_popup ) {
							$content_input_error .= '\n - ' . esc_html__( 'Captcha failed - please try again', 'endorse' );
						} else {
							$content_input_error .= '<br/> - ' . esc_html__( 'Captcha failed - please try again', 'endorse' );
						}
					}
				}
			}
			// Validate testimonial_title.
			if ( ! empty( $_POST['content_title'] ) ) {
				$title = sanitize_text_field( wp_unslash( $_POST['content_title'] ) );
			} else {
				$title = '';
			}
			if ( '' === $title || $title === $title_label ) {
				if ( false === $disable_popup ) {
					$content_input_error .= '\n - ' . esc_html( $title_label ) . ' ' . esc_html__( 'required', 'endorse' );
				} else {
					$content_input_error .= '<br/> - ' . esc_html( $title_label ) . ' ' . esc_html__( 'required', 'endorse' );
				}
				if ( true === $labels_inside ) {
					$title = $title_label;
				} else {
					$title = '';
				}
			}
			// Validate Testimonial.
			// Disallowed HTML check.
			if ( false !== $disallow_html ) {
				if ( ! empty( $_POST['content_testimonial'] ) ) {
					$testimonial = wp_kses( wp_unslash( $_POST['content_testimonial'] ), $allowed_html );
				} else {
					$testimonial = '';
				}
				if ( false !== strpos( $testimonial, '<a' ) ||
					false !== stripos( $testimonial, '&lt;a' ) ||
					false !== strpos( $testimonial, '<img' ) ||
					false !== stripos( $testimonial, '&lt;img' ) ||
					false !== strpos( $testimonial, 'href=' ) ||
					false !== strpos( $testimonial, 'src=' ) ||
					false !== strpos( $testimonial, 'http' ) ||
					false !== strpos( $testimonial, '//' )
				) {
					$found_html = true;
				} else {
					$found_html = false;
				}
				if ( true === $found_html ) {// phpcs:ignore
					// string contains html.
					if ( false === $disable_popup ) {
						$content_input_error .= '\n - ' . esc_html__( 'html is not allowed in content', 'endorse' );
					} else {
						$content_input_error .= '<br/> - ' . esc_html__( 'html is not allowed in content', 'endorse' );
					}
				}
			}
			// Check for error before processing to avoid html encoding until all is good.
			// Premature encoding causes wp_kses to remove smiley images on second pass.
			if ( '' === $content_input_error ) {
				// Sanitize first.
				if ( ! empty( $_POST['content_testimonial'] ) ) {
					$sanitize_testimonial = wp_kses( wp_unslash( $_POST['content_testimonial'] ), $allowed_html );
					// Add ClassicPress Smiley support.
					$fix_emoticons = convert_smilies( $sanitize_testimonial );
					// If emoji present convert to html entities.
					$testimonial = wp_encode_emoji( $fix_emoticons );
				} else {
					$testimonial = '';
				}
			} else {
				$testimonial = wp_kses( wp_unslash( $_POST['content_testimonial'] ), $allowed_html );
			}
			if ( '' === $testimonial || $testimonial === $testimonial_label ) {
				if ( false === $disable_popup ) {
					$content_input_error .= '\n - ' . esc_html( $testimonial_label ) . ' ' . esc_html__( 'required', 'endorse' );
				} else {
					$content_input_error .= '<br/> - ' . esc_html( $testimonial_label ) . ' ' . esc_html__( 'required', 'endorse' );
				}
				if ( true === $labels_inside ) {
					$testimonial = $testimonial_label;
				} else {
					$testimonial = '';
				}
			}
			// Validation complete.
			if ( '' === $content_input_error ) {
				// OK $content_input_error is empty so let's insert the testimonial.
				// First remove label entries if they exist.
				if ( $location === $location_label ) {
					$location = '';
				}
				if ( $website === $website_label ) {
					$website = '';
				}
				if ( $custom1 === $custom1_label ) {
					$custom1 = '';
				}
				if ( $custom2 === $custom2_label ) {
					$custom2 = '';
				}
				// Inset the custom post.
				// Set up the meta.
				$meta                = array();
				$meta['order']       = $order;
				$meta['rating']      = $rating;
				$meta['author']      = $author;
				$meta['email']       = $email;
				$meta['website']     = $website;
				$meta['location']    = $location;
				$meta['image']       = $image;
				$meta['custom1']     = $custom1;
				$meta['custom2']     = $custom2;
				$post_arr            = array(
					'post_type'    => 'khatestimonial',
					'post_title'   => $title,
					'post_content' => $testimonial,
					'post_status'  => $status,
					'meta_input'   => array(
						'endorse_meta' => $meta,
					),
				);
				$testimonial_post_id = wp_insert_post( $post_arr, true );
				if ( ! is_wp_error( $testimonial_post_id ) ) {
					wp_set_object_terms( $testimonial_post_id, $content_input_group, 'khagroups', true );
				}
				// Send notification email.
				email_notification( $author, $email, $testimonial );
				// Success message.
				if ( false === $disable_popup ) {
					?>
					<script>
						<?php
						echo 'alert("' . esc_html__( 'Testimonial Submitted - Thank You!', 'endorse' ) . '")';
						?>
					</script>
					<?php
				} else {
					$input_html .= '<span class="endorse-test-sent">' . esc_html__( 'Testimonial Submitted - Thank You!', 'endorse' ) . '</span>';
				}
				// Reset the variables.
				if ( true === $labels_inside ) {
					$author      = $author_label;
					$email       = $email_label;
					$website     = $website_label;
					$location    = $location_label;
					$custom1     = $custom1_label;
					$custom2     = $custom2_label;
					$title       = $title_label;
					$testimonial = $testimonial_label;
				} else {
					$author      = '';
					$email       = '';
					$website     = '';
					$location    = '';
					$custom1     = '';
					$custom2     = '';
					$title       = '';
					$testimonial = '';
				}
			} else {
				// There is an error somewhere.
				if ( false === $disable_popup ) {
					$error_message = esc_html__( 'There were errors so the testimonial was not added: ', 'endorse' ) . $content_input_error;
					?>
					<script>alert("<?php echo esc_html( $error_message ); ?>")</script>
					<?php
				} else {
					$input_html .= '<span class="endorse-error">' . esc_html__( 'There were errors so the testimonial was not added: ', 'endorse' ) . $content_input_error . '</span>';
				}
			}
		}
	}
	// Start of display input form.
	$input_html .= '<div class="endorse-content-input-wrap">';
	// Should I include title?
	if ( true === $options['input_content_include_title'] ) {
		$input_html .= '<h2 class="endorse-content-input-title">' . esc_html( stripcslashes( $options['input_content_title_text'] ) ) . '</h2>';
	}
	// Should I include note?
	if ( true === $options['input_content_include_note'] ) {
		$input_html .= '<span class="endorse-content-note">' . esc_html( stripcslashes( $options['input_content_note_text'] ) ) . '</span>';
	}
	// Start form.
	$input_html .= '<form class="endorse-content-form" method="POST">';
	// GDPR approval.
	if ( true === $use_data_protection ) {
		$input_html .= '<span class="endorse-content-gdpr-approve">';
		$input_html .= '<input class="endorse-content-gdpr-checkbox" type="checkbox" name="gdpr" ' . checked( $use_dp_checked, true, false ) . '>';
		$input_html .= '<span class="endorse-content-gdpr-label">';
		$input_html .= ' ' . esc_html( $dp_note );
		$input_html .= '</span>';
		$input_html .= '</span>';
	}
	// Author.
	if ( false === $labels_inside ) {
		$input_html .= '<label>' . esc_html( $author_label ) . '</label>';
	}
	if ( $author === $author_label || '' === trim( $author ) ) {
		$input_html .= '<input class="endorse-content-input" type="text" maxlength="100" name="content_author" placeholder="' . esc_attr( stripcslashes( $author ) ) . '" /><br/>';
	} else {
		$input_html .= '<input class="endorse-content-input" type="text" maxlength="100" name="content_author" value="' . esc_attr( stripcslashes( $author ) ) . '" /><br/>';
	}
	// Email.
	if ( false === $labels_inside ) {
		$input_html .= '<label>' . esc_html( $email_label ) . '</label>';
	}
	if ( $email === $email_label || '' === trim( $email ) ) {
		$input_html .= '<input class="endorse-content-input" type="text" maxlength="100" name="content_email" placeholder="' . esc_attr( stripcslashes( $email ) ) . ' " /><br/>';
	} else {
		$input_html .= '<input class="endorse-content-input" type="text" maxlength="100" name="content_email" value="' . esc_attr( stripcslashes( $email ) ) . ' " /><br/>';
	}
	// Website.
	if ( true === $include_website ) {
		if ( false === $labels_inside ) {
			$input_html .= '<label>' . esc_html( $website_label ) . '</label>';
			if ( '' === trim( $website ) || trim( $website ) === $website_label || 'http://' . $website_label === trim( $website ) ) {// phpcs:ignore
				$input_html .= '<input class="endorse-content-input" type="text" maxlength="100" name="content_website" placeholder="' . esc_url( stripcslashes( $website ) ) . '" /><br/>';
			} else {
				$input_html .= '<input class="endorse-content-input" type="text" maxlength="100" name="content_website" value="' . esc_url( stripcslashes( $website ) ) . '" /><br/>';
			}
		} else {
			if ( '' === trim( $website ) || trim( $website ) === $website_label || 'http://' . $website_label === trim( $website ) ) {// phpcs:ignore
				$input_html .= '<input class="endorse-content-input" type="text" maxlength="100" name="content_website" placeholder="' . $website_label . '" /><br/>';
			} else {
				$input_html .= '<input class="endorse-content-input" type="text" maxlength="100" name="content_website" value="' . esc_url( stripcslashes( $website ) ) . '" /><br/>';
			}
		}
	}
	// Location.
	if ( true === $include_location ) {
		if ( false === $labels_inside ) {
			$input_html .= '<label>' . esc_html( $location_label ) . '</label>';
		}
		if ( $location === $location_label || '' === trim( $location ) ) {
			$input_html .= '<input class="endorse-content-input" type="text"  maxlength="100" name="content_location" placeholder="' . esc_attr( stripcslashes( $location ) ) . '" /><br/>';
		} else {
			$input_html .= '<input class="endorse-content-input" type="text"  maxlength="100" name="content_location" value="' . esc_attr( stripcslashes( $location ) ) . '" /><br/>';
		}
	}
	// Custom 1.
	if ( true === $include_custom1 ) {
		if ( false === $labels_inside ) {
			$input_html .= '<label>' . esc_html( $custom1_label ) . '</label>';
		}
		if ( $custom1 === $custom1_label || '' === trim( $custom1 ) ) {
			$input_html .= '<input class="endorse-content-input" type="text"  maxlength="100" name="content_custom1" placeholder="' . esc_attr( stripcslashes( $custom1 ) ) . '" /><br/>';
		} else {
			$input_html .= '<input class="endorse-content-input" type="text"  maxlength="100" name="content_custom1" value="' . esc_attr( stripcslashes( $custom1 ) ) . '" /><br/>';
		}
	}
	// Custom 2.
	if ( true === $include_custom2 ) {
		if ( false === $labels_inside ) {
			$input_html .= '<label>' . esc_html( $custom2_label ) . '</label>';
		}
		if ( $custom2 === $custom2_label || '' === trim( $custom2 ) ) {
			$input_html .= '<input class="endorse-content-input" type="text"  maxlength="100" name="content_custom2" placeholder="' . esc_attr( stripcslashes( $custom2 ) ) . '" /><br/>';
		} else {
			$input_html .= '<input class="endorse-content-input" type="text"  maxlength="100" name="content_custom2" value="' . esc_attr( stripcslashes( $custom2 ) ) . '" /><br/>';
		}
	}
	// Rating input.
	if ( true === $use_ratings ) {
		if ( '' === trim( $rating ) ) {
			$rating = '0.0';
		}
		$input_html .= '<span class="endorse-input-rating-wrap">';
		$input_html .= '<label>' . esc_attr( $rating_label ) . '</label>';
		$input_html .= '<select name="content_rating">';
		$input_html .= '<option value="0.0" ' . selected( esc_attr( $rating ), '0.0', false ) . '>0.0</option>';
		$input_html .= '<option value="0.5" ' . selected( esc_attr( $rating ), '0.5', false ) . '>0.5</option>';
		$input_html .= '<option value="1.0" ' . selected( esc_attr( $rating ), '1.0', false ) . '>1.0</option>';
		$input_html .= '<option value="1.5" ' . selected( esc_attr( $rating ), '1.5', false ) . '>1.5</option>';
		$input_html .= '<option value="2.0" ' . selected( esc_attr( $rating ), '2.0', false ) . '>2.0</option>';
		$input_html .= '<option value="2.5" ' . selected( esc_attr( $rating ), '2.5', false ) . '>2.5</option>';
		$input_html .= '<option value="3.0" ' . selected( esc_attr( $rating ), '3.0', false ) . '>3.0</option>';
		$input_html .= '<option value="3.5" ' . selected( esc_attr( $rating ), '3.5', false ) . '>3.5</option>';
		$input_html .= '<option value="4.0" ' . selected( esc_attr( $rating ), '4.0', false ) . '>4.0</option>';
		$input_html .= '<option value="4.5" ' . selected( esc_attr( $rating ), '4.5', false ) . '>4.5</option>';
		$input_html .= '<option value="5.0" ' . selected( esc_attr( $rating ), '5.0', false ) . '>5.0</option>';
		$input_html .= '</select>';
		$input_html .= '</span>';
	}
	// Title input.
	if ( false === $labels_inside ) {
		$input_html .= '<label>' . esc_html( $title_label ) . '</label>';
	}
	if ( $title === $title_label || '' === trim( $title ) ) {
		$input_html .= '<input class="endorse-content-input" type="text"  name="content_title" placeholder="' . esc_attr( stripcslashes( $title ) ) . '" /><br/>';
	} else {
		$input_html .= '<input class="endorse-content-input" type="text"  name="content_title" value="' . esc_attr( stripcslashes( $title ) ) . '" /><br/>';
	}
	// Testimonial.
	if ( false === $labels_inside ) {
		$input_html .= '<label>' . esc_html( $testimonial_label ) . '</label><br/>';
	}
	if ( $testimonial === $testimonial_label || '' === trim( $testimonial ) ) {
		$input_html .= '<textarea class="endorse-content-textarea" rows="5" name="content_testimonial" placeholder="' . wp_kses_post( stripcslashes( $testimonial ) ) . '"></textarea>';
	} else {
		$input_html .= '<textarea class="endorse-content-textarea" rows="5" name="content_testimonial" >' . wp_kses_post( stripcslashes( $testimonial ) ) . '</textarea>';
	}
	// Captcha input.
	if ( true === $use_captcha ) {
		$site_key        = $options['recaptcha_site_key'];
		$input_html     .= '<div class="endorse-content-captcha-wrap">';
			$input_html .= '<div id="endorse-content-captcha-' . $content_input_form_no . '" class="g-recaptcha" data-sitekey="' . esc_html( $site_key ) . '"></div>';
		$input_html     .= '</div>';
	}
	$input_html .= '<input type="hidden" name="content_form_no" value="' . $content_input_form_no . '">';
	$input_html .= '<input class="endorse-content-submit endorse-button" type="submit" name="content_submitted' . $content_input_form_no . '" value="' . esc_attr( $submit_label ) . '" />';
	$input_html .= wp_nonce_field( 'content_nonce_1', 'content_main_form_nonce' . $content_input_form_no, false, false );
	if ( true === $use_honeypot ) {
		$input_html .= '<span class="endorse_span_custom3">';
		$input_html .= '<input class="custom_3" type="text" maxlength="100" name="custom3" value="" autocomplete="off" />';
		$input_html .= '</span>';
	}
	$input_html .= '</form>';
	// Required text.
	$input_html .= '<span class="endorse-required-label">' . esc_attr( $required_label ) . '</span>';
	// HTML allowed.
	if ( false !== $disallow_html ) {
		$input_html .= '<span class="endorse-content-html-allowed">' . esc_html__( 'HTML Not Allowed', 'endorse' );
	} elseif ( true === $show_html_allowed ) {
		$input_html .= '<span class="endorse-content-html-allowed">' . esc_html__( 'HTML Allowed', 'endorse' ) . ': <i>a p br i em strong q h1-h6</i></span>';
	}
	// Gravatar link.
	if ( false !== $include_gravatar_link ) {
		$input_html         .= '<div class="endorse-content-add-gravatar">';
			$input_html     .= '<span class="endorse-gravatar-label">' . esc_html__( 'Add a gravatar photo? ', 'endorse' ) . '</span>';
			$input_html     .= '<a href="https://en.gravatar.com/" title="Gravatar Site" target="_blank" >';
				$input_html .= '<img class="endorse-content-gravatar-logo" src="' . plugins_url() . '/endorse/images/Gravatar80x16.jpg" alt="' . esc_attr__( 'Gravatar Website', 'endorse' ) . '" title="' . esc_attr__( 'Gravatar Website', 'endorse' ) . '" />';
			$input_html     .= '</a>';
		$input_html         .= '</div>';
	}
	$input_html .= '</div>';

	return $input_html;
}
add_shortcode( 'endorse_input', 'EndorseByKHA\input_content_form' );

/**
 * Display testimonials in content area shortcode
 *
 * Format for setting up shortcode.
 * useage : [endorse_testimonial group="" number="all" ids="" per_page="" by="date"]
 * group  : Input the group name if you only want testimonials of that group, leave blank or "All" for all testimonials.
 * number : "all" or input the number you want to display.
 * ids    : put in post id's if you only want to show certain testimonials.
 * by     : "date" or "random".
 * per_page : testimonials to show per page, blank for default of 10.
 *
 * @param array $atts contains the shortcode parameters.
 *
 * @return string $endorse_html containing the html of the testimonial display request
 * ------------------------------------------------------------------------- */
function list_testimonials_in_content( $atts ) {
	// Get options.
	global $options;
	// Initialize main testimonial arrays.
	$use_excerpts           = $options['content_use_excerpts'];
	$content_excerpt_length = $options['content_excerpt_length'];
	$use_ratings            = $options['use_ratings'];
	$content_use_gravatars  = $options['content_use_gravatars'];
	$content_show_website   = $options['content_show_website'];
	$content_show_date      = $options['content_show_date'];
	$content_show_location  = $options['content_show_location'];
	$content_show_custom1   = $options['content_show_custom1'];
	$content_show_custom2   = $options['content_show_custom2'];
	$use_data_protection    = $options['use_data_protection'];
	$dp_remove_permalink    = $options['dp_remove_permalink'];
	if ( 0 === intval( $content_excerpt_length ) ) {
		$content_excerpt_length = 30;
	}
	// Validate/whitelist group.
	if ( isset( $atts['group'] ) ) {
		$group = sanitize_text_field( $atts['group'] );
		if ( 'all' === strtolower( $group ) ) {
			$group = '';
		}
	} else {
		$group = '';
	}
	// Validate/whitelist Number.
	if ( isset( $atts['number'] ) ) {
		$number = strtolower( sanitize_text_field( $atts['number'] ) );
		if ( '' === $number || 'All' === $number || 'all' === $number ) {
			$number = '';
		} else {
			if ( 1 > intval( $number ) ) {
				$number = 1;
			} else {
				$number = intval( $number );
			}
		}
	} else {
		$number = '';
	}
	// Validate ids.
	if ( isset( $atts['ids'] ) && '' !== $atts['ids'] ) {
		$ids           = sanitize_text_field( $atts['ids'] );
		$ids_validated = validate_ids( $ids );
	} else {
		$ids = '';
	}
	// Validate per_page.
	if ( isset( $atts['per_page'] ) ) {
		$per_page = strtolower( sanitize_text_field( $atts['per_page'] ) );
		if ( '' === $per_page ) {
			$per_page = 10;
		} else {
			if ( 1 > intval( $per_page ) ) {
				$per_page = 1;
			} else {
				$per_page = intval( $per_page );
			}
		}
	} else {
		$per_page = 10;
	}
	// White list by.
	if ( isset( $atts['by'] ) ) {
		$by = strtolower( sanitize_text_field( $atts['by'] ) );
		if ( 'date' !== $by ) {
			$by = 'rand';
		}
	} else {
		$by = 'date';
	}
	if ( '' !== $number ) {
		$per_page  = $number;
		$print_nav = false;
	} else {
		$print_nav = true;
	}

	// get the posts.
	if ( '' !== $ids ) {
		$print_nav = false;
		$args      = array(
			'post_type'      => 'khatestimonial',
			'post__in'       => $ids_validated,
			'orderby'        => 'none',
			'posts_per_page' => -1,
		);
	} elseif ( '' === $group ) {
		$args = array(
			'post_type'      => 'khatestimonial',
			'orderby'        => $by,
			'posts_per_page' => $per_page,
			'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
		);
	} else {
		$args = array(
			'post_type'      => 'khatestimonial',
			'orderby'        => $by,
			'tax_query'      => array(// phpcs:ignore
				array(
					'taxonomy' => 'khagroups',
					'field'    => 'slug',
					'terms'    => $group,
				),
			),
			'posts_per_page' => $per_page,
			'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
		);
	}
	$loop = new \WP_Query( $args );
	ob_start();
	?>
	<div class="endorse-content-wrap-side">
		<?php
		// present the posts.
		while ( $loop->have_posts() ) {
			$loop->the_post();
			$id                = get_the_id();
			$meta              = get_post_meta( $id, 'endorse_meta' );
			$gravatar_or_photo = insert_gravatar( $meta[0]['image'], '120', $content_use_gravatars, $meta[0]['email'] );
			?>
			<div class="endorse-content-box-side">
				<div class="endorse-content-side-left">
					<?php if ( '' !== $gravatar_or_photo ) { ?>
						<div class="endorse-content-side-gravatar">
							<?php echo $gravatar_or_photo;// phpcs:ignore ?>
						</div>
					<?php } ?>
					<div class="endorse-content-side-meta">
						<?php
							echo get_author_html( $meta[0]['author'], $divider = '' );// phpcs:ignore
							echo get_date_html( $content_show_date, get_the_date(), $divider = '' );// phpcs:ignore
							echo get_location_html( $content_show_location, $meta[0]['location'], $divider = '' );// phpcs:ignore
							echo get_custom1_html( $content_show_custom1, $meta[0]['custom1'], $divider = '' );// phpcs:ignore
							echo get_custom2_html( $content_show_custom2, $meta[0]['custom2'], $divider = '' );// phpcs:ignore
							echo get_website_html( $content_show_website, $meta[0]['website'], $divider = '' );// phpcs:ignore
						?>
					</div>
				</div>
				<div class="endorse-content-side-right">
					<div class="endorse-title-rating-wrap-side">
						<?php
							echo get_title_html( get_the_title() );// phpcs:ignore
							echo get_rating_html( $use_ratings, $meta[0]['rating'] );// phpcs:ignore
						?>
					</div>
					<div class="endorse-testimonial-wrap-side">
						<?php
						if ( true === $use_excerpts ) {
							$args = array(
								'length'          => intval( $content_excerpt_length ),
								'readmore_text'   => esc_html__( 'read more', 'endorse' ),
								'custom_excerpts' => true,
							);
							echo endorse_get_excerpt( $args );// phpcs:ignore
						} else {
							the_content( '...more' );
						}
						if ( true === $use_data_protection ) {
							echo get_gdpr_link( $dp_remove_permalink, $id, $divider, 'remove-link-content' );// phpcs:ignore
						}
						?>
					</div>
				</div>
			</div>
			<?php
		}
		if ( 'rand' !== $by && true === $print_nav ) {
			$big = 999999999; // need an unlikely integer.
			echo paginate_links(// phpcs:ignore
				array(
					'base'    => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
					'format'  => '?paged=%#%',
					'current' => max( 1, get_query_var( 'paged' ) ),
					'total'   => $loop->max_num_pages,
				)
			);
		}
		wp_reset_postdata();
		?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'endorse_testimonial', 'EndorseByKHA\list_testimonials_in_content' );

/**
 * Display slider of testimonials in content area shortcode.
 *
 * Format for setting up shortcode.
 * useage : [endorse_slider group="" number="" ids="" by="date" height=""]
 * group : "all" or "group" where group is the identifier in the testimonial
 * number : "all" or input the number you want to display
 * ids : put in post id's to get very specific about selections
 * by : "date" or "random"
 * height : minimum height of slider in pixels
 *
 * @param array $atts contains the shortcode parameters.
 * @uses
 *
 * @return string $endorse_html containing the html of the testimonial display request
 * ------------------------------------------------------------------------- */
function slider_testimonials_in_content( $atts ) {
	// Get options.
	global $options;
	// Initialize main testimonial arrays.
	$use_excerpts           = $options['content_use_excerpts'];
	$content_excerpt_length = $options['content_excerpt_length'];
	$use_ratings            = $options['use_ratings'];
	$content_use_gravatars  = $options['content_use_gravatars'];
	$content_show_website   = $options['content_show_website'];
	$content_show_date      = $options['content_show_date'];
	$content_show_location  = $options['content_show_location'];
	$content_show_custom1   = $options['content_show_custom1'];
	$content_show_custom2   = $options['content_show_custom2'];
	$use_data_protection    = $options['use_data_protection'];
	$dp_remove_permalink    = $options['dp_remove_permalink'];
	if ( 0 === intval( $content_excerpt_length ) ) {
		$content_excerpt_length = 30;
	}
	// Validate/whitelist group.
	if ( isset( $atts['group'] ) ) {
		$group = sanitize_text_field( $atts['group'] );
	} else {
		$group = '';
	}
	// Validate/whitelist Number.
	if ( isset( $atts['number'] ) ) {
		$number = strtolower( sanitize_text_field( $atts['number'] ) );
		if ( '' === $number || 'All' === $number || 'all' === $number ) {
			$number = '';
		} else {
			if ( 1 > intval( $number ) ) {
				$number = 1;
			} else {
				$number = intval( $number );
			}
			if ( 10 < $number ) {
				$number = 10;
			}
		}
	} else {
		$number = '';
	}
	// Validate ids.
	if ( isset( $atts['ids'] ) && '' !== $atts['ids'] ) {
		$ids           = sanitize_text_field( $atts['ids'] );
		$ids_validated = validate_ids( $ids );
	} else {
		$ids = '';
	}
	// White list by.
	if ( isset( $atts['by'] ) ) {
		$by = strtolower( sanitize_text_field( $atts['by'] ) );
		if ( 'date' !== $by ) {
			$by = 'rand';
		}
	} else {
		$by = 'date';
	}
	// Validate height.
	if ( isset( $atts['height'] ) ) {
		$height = strtolower( sanitize_text_field( $atts['height'] ) );
		$height = intval( $height );
		if ( 1 > intval( $height ) ) {
			$height = 100;
		}
	} else {
		$height = 100;
	}
	// get the posts.
	if ( '' !== $ids ) {
		$args = array(
			'post_type'      => 'khatestimonial',
			'post__in'       => $ids_validated,
			'orderby'        => 'none',
			'posts_per_page' => -1,
		);
	} elseif ( '' === $group ) {
		$args = array(
			'post_type'      => 'khatestimonial',
			'orderby'        => $by,
			'posts_per_page' => $number,
			'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
		);
	} else {
		$args = array(
			'post_type'      => 'khatestimonial',
			'orderby'        => $by,
			'tax_query'      => array(// phpcs:ignore
				array(
					'taxonomy' => 'khagroups',
					'field'    => 'slug',
					'terms'    => $group,
				),
			),
			'posts_per_page' => $number,
			'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
		);
	}
	$loop        = new \WP_Query( $args );
	$postcount   = $loop->post_count;
	$inner_width = $postcount * 100 . '%';
	$post_width  = 100 / $postcount . '%';
	ob_start();
	?>
		<div class="endorse-content-slider" style="min-height:<?php $height;// phpcs:ignore ?>px;">
			<?php
			for ( $i = 1; $i < $postcount + 1; $i++ ) {
				if ( 1 === $i ) {
					echo '<input id="content-slides-1" type="radio" name="content-slides" title="slide1" checked="checked"/>';
				} else {
					echo '<input id="content-slides-' . $i . '" type="radio" name="content-slides" title="slide' . $i . '"/>';// phpcs:ignore
				}
			}
			?>
			<!--<ul style="width:<?php echo $inner_width;// phpcs:ignore ?>;min-height:<?php echo $height; ?>px;">-->
			<ul style="min-height:<?php echo $height;// phpcs:ignore ?>px;">
				<?php
				// present the posts.
				while ( $loop->have_posts() ) {
					$loop->the_post();
					$id                = get_the_id();
					$meta              = get_post_meta( $id, 'endorse_meta' );
					$gravatar_or_photo = insert_gravatar( $meta[0]['image'], '120', $content_use_gravatars, $meta[0]['email'] );
					?>
					<li>
						<div class="endorse-content-side-left">
							<?php if ( '' !== $gravatar_or_photo ) { ?>
								<div class="endorse-content-side-gravatar">
									<?php echo $gravatar_or_photo;// phpcs:ignore ?>
								</div>
							<?php } ?>
							<div class="endorse-content-side-meta">
								<?php
									echo get_author_html( $meta[0]['author'], $divider = '' );// phpcs:ignore
									echo get_date_html( $content_show_date, get_the_date(), $divider = '' );// phpcs:ignore
									echo get_location_html( $content_show_location, $meta[0]['location'], $divider = '' );// phpcs:ignore
									echo get_custom1_html( $content_show_custom1, $meta[0]['custom1'], $divider = '' );// phpcs:ignore
									echo get_custom2_html( $content_show_custom2, $meta[0]['custom2'], $divider = '' );// phpcs:ignore
									echo get_website_html( $content_show_website, $meta[0]['website'], $divider = '' );// phpcs:ignore
								?>
							</div>
						</div>
						<div class="endorse-content-side-right">
							<div class="endorse-title-rating-wrap-side">
								<?php
									echo get_title_html( get_the_title() );// phpcs:ignore
									echo get_rating_html( $use_ratings, $meta[0]['rating'] );// phpcs:ignore
								?>
							</div>
							<div class="endorse-testimonial-wrap-side">
								<?php
								if ( true === $use_excerpts ) {
									$args = array(
										'length'          => intval( $content_excerpt_length ),
										'readmore_text'   => esc_html__( 'read more', 'endorse' ),
										'custom_excerpts' => true,
									);
									echo endorse_get_excerpt( $args );// phpcs:ignore
								} else {
									the_content( '...more' );
								}
								if ( true === $use_data_protection ) {
									echo get_gdpr_link( $dp_remove_permalink, $id, $divider, 'remove-link-content' );// phpcs:ignore
								}
								?>
							</div>
						</div>
					</li>
					<?php
				}
				wp_reset_postdata();
				?>
			</ul>
			<?php
			echo '<div class="arrows">';
			for ( $i = 1; $i < $postcount + 1; $i++ ) {
				echo '<label for="content-slides-' . $i . '"></label>';// phpcs:ignore
			}
				echo '<label class="goto-first" for="content-slides-1"></label>';
				echo '<label class="goto-last" for="content-slides-' . intval( $postcount ) . '"></label>';
			echo '</div>';
			?>
		</div>
		<?php

		return ob_get_clean();
}
add_shortcode( 'endorse_slider', 'EndorseByKHA\slider_testimonials_in_content' );

/**
 * Request to remove testimonial shortcode
 *
 * Displays the form to allow users to submit a request for testimonial removal.
 * useage : [endorse_remove_testimonial]
 *
 * @uses get_options() array of plugin user options.
 *
 * @return string $input_html which is the html string for the form.
 */
function remove_testimonial_form() {
	// Get the testimonial id.
	$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : false;
	// Initiate return string.
	$html = '';
	global $options;
	$author_label       = $options['input_content_author_label'];
	$email_label        = $options['input_content_email_label'];
	$website_label      = $options['input_content_website_label'];
	$location_label     = $options['input_content_location_label'];
	$custom1_label      = $options['input_content_custom1_label'];
	$custom2_label      = $options['input_content_custom2_label'];
	$rating_label       = $options['input_content_rating_label'];
	$title_label        = $options['input_content_title_label'];
	$testimonial_label  = $options['input_content_testimonial_label'];
	$use_captcha        = $options['use_recaptcha'];
	$use_honeypot       = $options['use_honeypot'];
	$submit_label       = $options['input_content_submit_label'];
	$disable_popup      = $options['input_content_disable_popup'];
	$intro_msg          = $options['remove_page_intro'];
	$no_testimonial_msg = $options['remove_page_direct_access'];
	// Get testimonial.
	// initialize.
	$remover_email  = '';
	$remover_reason = '';
	global $options;
	// Set up table name for datatbase updates.
	if ( false === $id ) {
		$testimonial = false;
	} else {
		$testimonial = get_post( $id );
		$meta        = get_post_meta( $id, 'endorse_meta' );
	}
	if ( false !== $testimonial ) {
		$date              = esc_html( $testimonial->post_date );
		$date_string       = date_i18n( get_option( 'date_format' ), strtotime( $date ) );
		$html             .= '<div id="endorse-remove-testimonial-wrap">';
			$html         .= '<div class="endorse-remove-intro">';
				$html     .= esc_html( $intro_msg );
			$html         .= '</div>';
			$html         .= '<div class="endorse-remove-testimonial-details">';
				$html     .= '<span class="endorse-remove title">' . esc_html__( 'Title: ', 'endorse' ) . esc_html( $testimonial->post_title ) . '</span>';
				$html     .= '<span class="endorse-remove rating">' . esc_html( $rating_label ) . ': ' . esc_html( $meta[0]['rating'] ) . '</span>';
				$html     .= '<span class="endorse-remove author">' . esc_html( $author_label ) . ': ' . esc_html( $meta[0]['author'] ) . '</span>';
				$html     .= '<span class="endorse-remove date">' . esc_html__( 'Date: ', 'endorse' ) . $date_string . '</span>';
				$html     .= '<span class="endorse-remove location">' . esc_html( $location_label ) . ': ' . esc_html( $meta[0]['location'] ) . '</span>';
				$html     .= '<span class="endorse-remove custom1">' . esc_html( $custom1_label ) . ': ' . esc_html( $meta[0]['custom1'] ) . '</span>';
				$html     .= '<span class="endorse-remove custom2">' . esc_html( $custom2_label ) . ': ' . esc_html( $meta[0]['custom2'] ) . '</span>';
				$html     .= '<span class="endorse-remove website">' . esc_html( $website_label ) . ': ' . esc_html( $meta[0]['website'] ) . '</span>';
				$html     .= '<span class="endorse-remove-testimonial-label">' . esc_html( $testimonial_label ) . ': </span>';
				$html     .= '<span class="endorse-remove testimonial">' . wp_kses_post( $testimonial->post_content ) . '</span>';
			$html         .= '</div>';
		$html             .= '</div>';
		$tdata['id']       = $id;
		$tdata['title']    = esc_html( $testimonial->post_title );
		$tdata['content']  = wp_kses_post( $testimonial->post_content );
		$tdata['rating']   = esc_html( $meta[0]['rating'] );
		$tdata['author']   = esc_html( $meta[0]['author'] );
		$tdata['date']     = $date_string;
		$tdata['location'] = esc_html( $meta[0]['location'] );
		$tdata['custom1']  = esc_html( $meta[0]['custom1'] );
		$tdata['custom2']  = esc_html( $meta[0]['custom2'] );
		$tdata['website']  = esc_html( $meta[0]['website'] );
	} else {
		$html         .= '<div id="endorse-remove-testimonial-wrap">';
			$html     .= '<div class="endorse-remove-intro">';
				$html .= esc_html( $intro_msg );
			$html     .= '</div>';
			$html     .= '<div class="endorse-remove-testimonial-details">';
				$html .= esc_html( $no_testimonial_msg );
			$html     .= '</div>';
		$html         .= '</div>';
		$tdata         = false;
	}
	// Simpler to adopt as form 1, beause of using functions that need this.
	$input_form_no = 1;
	// Process the submit.
	if ( isset( $_POST['remove_submitted'], $_POST['main_form_nonce'] ) &&
		wp_verify_nonce( sanitize_key( $_POST['main_form_nonce'] ), 'nonce_1' ) ) {// Input var OK.
		// Check for valid submission.
		$bot_submission = false;
		if ( true === $options['use_honeypot'] ) {
			if ( ! empty( $_POST['tb_custom3'] ) ) {
				$bot_submission = true;
			}
		}
		// OK to proceed.
		if ( false === $bot_submission ) {
			// Initialize error message.
			$remove_error = '';
			// Validate-Sanitize E-mail, note: label will not be an email.
			if ( ! empty( $_POST['remove_request_email'] ) ) {
				$remover_email = sanitize_email( wp_unslash( $_POST['remove_request_email'] ) );
			} else {
				$remover_email = '';
			}
			if ( ! is_email( $remover_email ) ) {
				if ( true === $use_popup ) {
					$remove_error .= '\n - ' . __( 'Valid email is required', 'endorse' );
				} else {
					$remove_error .= '<br/> - ' . __( 'Valid email is required', 'endorse' );
				}
			}
			// Captcha.
			if ( true === $use_captcha ) {
				if ( true === $options['use_recaptcha'] ) {
					if ( ! empty( $_POST['g-recaptcha-response'] ) ) {
						$captcha_response = sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ) );
					} else {
						$captcha_response = false;
					}
					if ( false === $captcha_response ) {
						if ( true === $use_popup ) {
							$remove_error .= '\n - ' . __( 'Please show you are a human and check the captcha box', 'endorse' );
						} else {
							$remove_error .= '<br/> - ' . __( 'Please show you are a human and check the captcha box', 'endorse' );
						}
					} else {
						$secret_key      = esc_html( $options['recaptcha_secret_key'] );
						$request         = wp_safe_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $captcha_response );
						$verify_response = wp_remote_retrieve_body( $request );
						$response_data   = json_decode( $verify_response );
						if ( false === $response_data->success ) {
							if ( true === $use_popup ) {
								$remove_error .= '\n - ' . __( 'Captcha failed - please try again', 'endorse' );
							} else {
								$remove_error .= '<br/> - ' . __( 'Captcha failed - please try again', 'endorse' );
							}
						}
					}
				} else {
					if ( ! empty( $_POST['verify'] ) ) {
						$captcha_entered = sanitize_text_field( wp_unslash( $_POST['verify'] ) );
					} else {
						$captcha_entered = '';
					}
					if ( get_transient( 'pass_phrase_content' . $input_form_no ) !== sha1( $captcha_entered ) ) {
						if ( true === $use_popup ) {
							$remove_error .= '\n - ' . __( 'Captcha is invalid - please try again', 'endorse' );
						} else {
							$remove_error .= '<br/> - ' . __( 'Captcha is invalid - please try again', 'endorse' );
						}
					}
				}
			}
			// Validate remover reason.
			if ( ! empty( $_POST['remover_reason'] ) ) {
				$remover_reason = sanitize_text_field( wp_unslash( $_POST['remover_reason'] ) );
			} else {
				$remover_reason = '';
			}
			// Validation complete.
			if ( '' === $remove_error ) {
				// Send notification email.
				remove_testimonial_request( $remover_email, $remover_reason, $tdata );
				// Success message.
				?>
				<script>
					<?php echo 'alert( "' . esc_html__( 'Request Submitted - Thank You!', 'endorse' ) . '" )'; ?>
				</script>
				<?php
				// Reset the variables.
				$remover_email  = '';
				$remover_reason = '';
			} else {
				// There is an error somewhere.
				if ( true === $use_popup ) {
					$error_message = esc_html__( 'There were errors so the request was not sent: ', 'endorse' ) . $remove_error;
					?>
					<script>alert("<?php echo $error_message; // phpcs:ignore ?>")</script>
					<?php
				} else {
					$html .= '<span class="error">' . esc_html__( 'There were errors so the request was not sent: ', 'endorse' ) . $remove_error . '</span>'; // WPCS: XSS ok.
				}
			}
		}
	}
	// Remove Form.
	$html .= '<form class="endorse-remove-form" method="POST">';
	// Email.
	$html .= '<label class="endorse-remove-email-label">' . esc_html( $email_label ) . '</label>';
	$html .= '<input type="text" size="75" name="remove_request_email" value="' . esc_attr( stripcslashes( $remover_email ) ) . ' " /><br/>';
	// Comment or reason.
	$html .= '<label class="endorse-remove-comment-label">' . esc_html__( 'Comment or reason for removal:', 'endorse' ) . '</label><br/>';
	$html .= '<textarea class="endorse-input-textarea" rows="5" name="remover_reason" >' . esc_html( stripcslashes( $remover_reason ) ) . '</textarea>';
	// Captcha.
	// Captcha input.
	if ( true === $use_captcha ) {
		$site_key  = $options['recaptcha_site_key'];
		$html     .= '<div class="endorse-content-captcha-wrap">';
			$html .= '<div id="endorse-content-captcha-1" class="g-recaptcha" data-sitekey="' . esc_html( $site_key ) . '"></div>';
		$html     .= '</div>';
	}
	// Submit and Reset.
	$html .= '<span class="endorse-remove-submit-wrap">';
	$html .= '<input class="endorse-remove-submit" type="submit" name="remove_submitted" value="' . esc_attr( $submit_label ) . '" />';
	$html .= wp_nonce_field( 'nonce_1', 'main_form_nonce', false, false );
	$html .= '</span>';
	// honeypot.
	if ( true === $use_honeypot ) {
		$html .= '<span class="endorse_span_custom3">';
		$html .= '<input class="custom_3" type="text" maxlength="100" name="custom3" value="" autocomplete="off" />';
		$html .= '</span>';
	}
	$html .= '</form>';
	return $html;
}
add_shortcode( 'endorse_remove_testimonial', 'EndorseByKHA\remove_testimonial_form' );

