<?php
/**
 * Plugin Name: Endorse Input Widget
 * Plugin URI: http://kevinsspace.ca/endorse-plugin/
 * Description: A plugin to input a testimonial.
 * Version: 1.0.3
 * Author: Kevin Archibald
 * Author URI: http://kevinsspace.ca/
 * License: GPLv2 or later
 *
 * @package   Endorse ClassicPress Plugin
 * @copyright Copyright (C) 2018 Kevin Archibald
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @author    Kevin Archibald <https://kevinsspace.ca/contact/>
 * Endorse is distributed under the terms of the GNU GPL
 */

namespace EndorseByKHA;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/** ------------- Register Widget ---------------------------------------
 *
 * The widget is registered using the widgets_init action hook that fires
 * after all default widgets have been registered.
 * input_testimonial_widget is the Class for the widget, all widgets
 * must be created using the WP_Widget Class
 *
 * ------------------------------------------------------------------------ */
function input_register_widget() {
	register_widget( 'EndorseByKHA\EndorseInputWidget' );
}
add_action( 'widgets_init', 'EndorseByKHA\input_register_widget' );
/**
 * Define Endorse Input Widget
 */
class EndorseInputWidget extends \WP_Widget {
	/** The first function is required to process the widget
	 * It sets up an array to store widget options
	 * 'classname' - added to <li class="classnamne"> of the widget html
	 * 'description' - displays under Appearance => Widgets ...your widget
	 * WP_Widget(widget list item ID,Widget Name to be shown on grag bar, options array)
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'input_widget_class',
			'description' => __( 'Allow a user to input a testimonial.', 'endorse' ),
		);
		parent::__construct( 'endorse_input_widget', __( 'Endorse Input Widget', 'endorse' ), $widget_ops );
	}
	/** The second function creates the widget setting form.
	 * Each widget has a table in the Options database for it's options
	 * The array of options is $instance. The first thing we do is check to see
	 * if the title instance exists, if so use it otherwise load the default.
	 * The second part displays the title in the widget.
	 *
	 * @param array $instance contains elements of widget.
	 */
	public function form( $instance ) {
		$input_defaults = array(
			'input_widget_title'   => esc_html__( 'Add a Testimonial', 'endorse' ),
			'input_widget_group'   => '',
			'input_widget_form_no' => '1',
		);
		$instance       = wp_parse_args( (array) $instance, $input_defaults );
		$title          = $instance['input_widget_title'];
		$group          = $instance['input_widget_group'];
		$form           = $instance['input_widget_form_no'];
		?>
		<p>
			<?php esc_html_e( 'Title :', 'endorse' ); ?>
			<input class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'input_widget_title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'input_widget_title' ) ); ?>"
				type="text" value="<?php echo esc_html( $title ); ?>" />
		</p>
		<p>
			<?php esc_html_e( 'Group :', 'endorse' ); ?>
			<input class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'input_widget_group' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'input_widget_group' ) ); ?>"
				type="text" value="<?php echo esc_html( $group ); ?>" />
		</p>
		<p>
			<?php esc_html_e( 'Form No :', 'endorse' ); ?>
			<select
				id="<?php echo esc_attr( $this->get_field_id( 'input_widget_form_no' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'input_widget_form_no' ) ); ?>">
					<option <?php selected( esc_attr( $form ) ); ?> value="<?php echo esc_attr( $form ); ?>"><?php echo esc_html( $form ); ?></option>
					<option value="1" <?php selected( esc_attr( $form ), '1' ); ?>>1</option>
					<option value="2" <?php selected( esc_attr( $form ), '2' ); ?>>2</option>
					<option value="3" <?php selected( esc_attr( $form ), '3' ); ?>>3</option>
					<option value="4" <?php selected( esc_attr( $form ), '4' ); ?>>4</option>
					<option value="5" <?php selected( esc_attr( $form ), '5' ); ?>>5</option>
			</select>
		</p>
		<?php
	}
	/**
	 * The third function saves the widget settings.
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from the database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                         = $old_instance;
		$instance['input_widget_title']   = sanitize_text_field( $new_instance['input_widget_title'] );
		$instance['input_widget_group']   = sanitize_text_field( $new_instance['input_widget_group'] );
		$instance['input_widget_form_no'] = sanitize_text_field( $new_instance['input_widget_form_no'] );
		// Group validation/whitelist.
		if ( '' === $instance['input_widget_group'] ) {
			$instance['input_widget_group'] = 'All';
		}
		// Form validation/whitelist.
		if ( '' === $instance['input_widget_form_no'] ) {
			$instance['input_widget_form_no'] = '1';
		}
		return $instance;
	}
	/**
	 * Display Widget
	 *
	 * The input form for the testimonial widget is loaded. The visitor inputs a testimonial
	 * and clicks the submit button and the testimonial is submitted to the database
	 * and the admin user is notified by email that they have a testimonial to review
	 * and approve. If admin user can specify if a captcha is used to help in validation.
	 *
	 * @param array $args array of global theme values.
	 * @param array $instance array of widget form values.
	 */
	public function widget( $args, $instance ) {
		// Get user options.
		global $options;
		$use_data_protection      = $options['use_data_protection'];
		$dp_note                  = $options['dp_save_data_note'];
		$widget_include_note      = $options['input_widget_include_note'];
		$widget_note              = $options['input_widget_note_text'];
		$widget_author_label      = $options['input_widget_author_label'];
		$widget_email_label       = $options['input_widget_email_label'];
		$widget_website_label     = $options['input_widget_website_label'];
		$widget_location_label    = $options['input_widget_location_label'];
		$widget_custom1_label     = $options['input_widget_custom1_label'];
		$widget_custom2_label     = $options['input_widget_custom2_label'];
		$widget_title_label       = $options['input_widget_title_label'];
		$widget_rating_label      = $options['input_widget_rating_label'];
		$widget_testimonial_label = $options['input_widget_testimonial_label'];
		$widget_submit_label      = $options['input_widget_submit_label'];
		$widget_show_html_strip   = $options['input_widget_show_html_strip'];
		$include_website          = $options['include_website'];
		$require_website          = $options['require_website'];
		$include_location         = $options['include_location'];
		$require_location         = $options['require_location'];
		$include_custom1          = $options['include_custom1'];
		$require_custom1          = $options['require_custom1'];
		$include_custom2          = $options['include_custom2'];
		$require_custom2          = $options['require_custom2'];
		$use_ratings              = $options['use_ratings'];
		$auto_approve             = $options['auto_approve'];
		$disable_widget_popup     = $options['input_widget_disable_popup'];
		$labels_above             = $options['input_widget_labels_above'];
		$widget_required_label    = $options['input_widget_required_label'];
		$widget_rating            = '0.0';
		$use_captcha              = $options['use_recaptcha'];
		$use_honeypot             = $options['use_honeypot'];
		$widget_include_gravatar  = $options['include_gravatar_link'];
		// before_widget is from register sidebar and is typically hardcoded html, no escaping required.
		echo $args['before_widget']; //phpcs:ignore
		$title = apply_filters( 'widget_title', empty( $instance['input_widget_title'] ) ? '' : $instance['input_widget_title'], $instance, $this->id_base );
		if ( ! empty( $title ) ) {
			// before_title and after_title are from register sidebar, typically hardcoded html, no escaping required.
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; //phpcs:ignore
		}
		// Initialize Variables.
		if ( false === $labels_above ) {
			$widget_author            = $widget_author_label;
			$widget_email             = $widget_email_label;
			$widget_website           = $widget_website_label;
			$widget_location          = $widget_location_label;
			$widget_custom1           = $widget_custom1_label;
			$widget_custom2           = $widget_custom2_label;
			$widget_testimonial_title = $widget_title_label;
			$widget_testimonial       = $widget_testimonial_label;
		} else {
			$widget_author            = '';
			$widget_email             = '';
			$widget_website           = '';
			$widget_location          = '';
			$widget_custom1           = '';
			$widget_custom2           = '';
			$widget_testimonial_title = '';
			$widget_testimonial       = '';
		}
		// GDPR flag.
		$widget_use_dp_checked = false;
		// Get allowed html.
		$allowed_html = allowed_html();
		// Retrieve and validate input group.
		$input_widget_group = esc_html( $instance['input_widget_group'] );
		if ( '' === $input_widget_group || 'All' === $input_widget_group || 'all' === $input_widget_group ) {
			$input_widget_group = '';
		}
		// Retrieve and validate input form no.
		$widget_input_form_no = esc_html( $instance['input_widget_form_no'] );
		if ( '' === $widget_input_form_no ) {
			$widget_input_form_no = '1';
		}
		$post_name = 'widget_submitted' . $widget_input_form_no;
		// Process input form.
		if ( isset( $_POST[ $post_name ], $_POST[ 'widget_form_nonce' . $widget_input_form_no ] )
			&& wp_verify_nonce( sanitize_key( $_POST[ 'widget_form_nonce' . $widget_input_form_no ] ), 'nonce_2' ) ) {
			// Check for valid submission.
			$bot_submission = false;
			if ( true === $options['use_honeypot'] ) {
				if ( ! empty( $_POST['widget_custom3'] ) ) {
					$bot_submission = true;
				}
			}
			if ( false === $bot_submission ) {
				// Initialize error string.
				$widget_html_error  = '';
				$widget_popup_error = '';
				// Set default variables.
				$widget_order = '';
				if ( true === $auto_approve ) {
					$status = 'publish';
				} else {
					$status = 'draft';
				}
				// validate GDPR.
				if ( true === $use_data_protection ) {
					if ( isset( $_POST['gdpr'] ) ) {
						$widget_use_dp_checked = true;
					} else {
						$widget_use_dp_checked = false;
						if ( false === $disable_widget_popup ) {
							$widget_popup_error .= '\n - ' . esc_html__( 'You must check the box to allow us to save the testimonial data', 'endorse' );
						} else {
							$widget_html_error .= '<br/> - ' . esc_html__( 'You must check the box to allow us to save the testimonial data', 'endorse' );
						}
					}
				}
				// Validate-Sanitize author.
				if ( ! empty( $_POST['widget_author'] ) ) {
					$widget_author = sanitize_text_field( wp_unslash( $_POST['widget_author'] ) );
				} else {
					$widget_author = '';
				}
				if ( $widget_author === $widget_author_label || '' === trim( $widget_author ) ) {
					if ( false === $disable_widget_popup ) {
						$widget_popup_error .= '\n - ' . esc_html( $widget_author_label ) . ' ' . esc_html__( 'required', 'endorse' );
					} else {
						$widget_html_error .= '<br/> - ' . esc_html( $widget_author_label ) . ' ' . esc_html__( 'required', 'endorse' );
					}
					if ( true === $labels_above ) {
						$widget_author = '';
					} else {
						$widget_author = $widget_author_label;
					}
				}
				// Validate email.
				if ( ! empty( $_POST['widget_email'] ) ) {
					$widget_email = sanitize_email( wp_unslash( $_POST['widget_email'] ) );
				} else {
					$widget_email = '';
				}
				if ( ! is_email( $widget_email ) ) {
					if ( false === $disable_widget_popup ) {
						$widget_popup_error .= '\n - ' . esc_html__( 'Valid email required ', 'endorse' );
					} else {
						$widget_html_error .= '<br/> - ' . esc_html__( 'Valid email required ', 'endorse' );
					}
					if ( true === $labels_above ) {
						$widget_email = '';
					} else {
						$widget_email = $widget_email_label;
					}
				}
				// validate website.
				if ( true === $include_website ) {
					if ( ! empty( $_POST['widget_website'] ) ) {
						$widget_website = esc_url_raw( wp_unslash( $_POST['widget_website'] ) );
					} else {
						$widget_website = '';
					}
					if ( '' === trim( $widget_website ) || true === strpos( $widget_website, $widget_website_label ) ) {
						if ( true === $require_website ) {
							if ( false === $disable_widget_popup ) {
								$widget_popup_error .= '\n - ' . esc_html( $widget_website_label ) . ' ' . esc_html__( 'required ', 'endorse' );
							} else {
								$widget_html_error .= '<br/> - ' . esc_html( $widget_website_label ) . ' ' . esc_html__( 'required ', 'endorse' );
							}
						}
						if ( true === $labels_above ) {
							$widget_website = '';
						} else {
							$widget_website = $widget_website_label;
						}
					}
				} else {
					$widget_website = '';
				}
				// Validate location.
				if ( true === $include_location ) {
					if ( ! empty( $_POST['widget_location'] ) ) {
						$widget_location = sanitize_text_field( wp_unslash( $_POST['widget_location'] ) );
					} else {
						$widget_location = '';
					}
					if ( '' === trim( $widget_location ) || $widget_location === $widget_location_label ) {
						if ( true === $require_location ) {
							if ( false === $disable_widget_popup ) {
								$widget_popup_error .= '\n - ' . esc_html( $widget_location_label ) . ' ' . esc_html__( 'required ', 'endorse' );
							} else {
								$widget_html_error .= '<br/> - ' . esc_html( $widget_location_label ) . ' ' . esc_html__( 'required ', 'endorse' );
							}
						}
						if ( true === $labels_above ) {
							$widget_location = '';
						} else {
							$widget_location = $widget_location_label;
						}
					}
				} else {
					$widget_location = '';
				}
				// Validate custom1.
				if ( true === $include_custom1 ) {
					if ( ! empty( $_POST['widget_custom1'] ) ) {
						$widget_custom1 = sanitize_text_field( wp_unslash( $_POST['widget_custom1'] ) );
					} else {
						$widget_custom1 = '';
					}
					if ( '' === trim( $widget_custom1 ) || $widget_custom1 === $widget_custom1_label ) {
						if ( true === $require_custom1 ) {
							if ( false === $disable_widget_popup ) {
								$widget_popup_error .= '\n - ' . esc_html( $widget_custom1_label ) . ' ' . esc_html__( 'required ', 'endorse' );
							} else {
								$widget_html_error .= '<br/> - ' . esc_html( $widget_custom1_label ) . ' ' . esc_html__( 'required ', 'endorse' );
							}
						}
						if ( true === $labels_above ) {
							$widget_custom1 = '';
						} else {
							$widget_custom1 = $widget_custom1_label;
						}
					}
				} else {
					$widget_custom1 = '';
				}
				// Validate custom2.
				if ( true === $include_custom2 ) {
					if ( ! empty( $_POST['widget_custom2'] ) ) {
						$widget_custom2 = sanitize_text_field( wp_unslash( $_POST['widget_custom2'] ) );
					} else {
						$widget_custom2 = '';
					}
					if ( '' === trim( $widget_custom2 ) || $widget_custom2 === $widget_custom2_label ) {
						if ( true === $require_custom2 ) {
							if ( false === $disable_widget_popup ) {
								$widget_popup_error .= '\n - ' . esc_html( $widget_custom2_label ) . ' ' . esc_html__( 'required ', 'endorse' );
							} else {
								$widget_html_error .= '<br/> - ' . esc_html( $widget_custom2_label ) . ' ' . esc_html__( 'required ', 'endorse' );
							}
						}
						if ( true === $labels_above ) {
							$widget_custom2 = '';
						} else {
							$widget_custom2 = $widget_custom2_label;
						}
					}
				} else {
					$widget_custom2 = '';
				}
				// Validate rating.
				if ( true === $use_ratings ) {
					if ( ! empty( $_POST['rating_widget'] ) ) {
						$widget_rating = sanitize_text_field( wp_unslash( $_POST['rating_widget'] ) );
					} else {
						$widget_rating = '0.0';
					}
				} else {
					$widget_rating = '0.0';
				}
				// Captcha Check.
				if ( true === $use_captcha ) {
					if ( ! empty( $_POST['g-recaptcha-response'] ) ) {
						$captcha_response = sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ) );
					} else {
						$captcha_response = false;
					}
					if ( ! $captcha_response ) {
						if ( false === $disable_widget_popup ) {
							$widget_popup_error .= '\n - ' . __( 'Please show you are a human and check the captcha box', 'endorse' );
						} else {
							$widget_html_error .= '<br/> - ' . __( 'Please show you are a human and check the captcha box', 'endorse' );
						}
					} else {
						$secret_key      = esc_html( $options['recaptcha_secret_key'] );
						$request         = wp_safe_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $captcha_response );
						$verify_response = wp_remote_retrieve_body( $request );
						$response_data   = json_decode( $verify_response );
						if ( false === $response_data->success ) {
							if ( false === $disable_widget_popup ) {
								$widget_popup_error .= '\n - ' . __( 'Captcha failed - please try again', 'endorse' );
							} else {
								$widget_html_error .= '<br/> - ' . __( 'Captcha failed - please try again', 'endorse' );
							}
						}
					}
				}
				if ( ! empty( $_POST['widget_title'] ) ) {
					$widget_testimonial_title = sanitize_text_field( wp_unslash( $_POST['widget_title'] ) );
				} else {
					$widget_testimonial_title = '';
				}
				if ( '' === trim( $widget_testimonial_title ) || $widget_testimonial_title === $widget_title_label ) {
					if ( false === $disable_widget_popup ) {
						$widget_popup_error .= '\n - ' . esc_html( $widget_title_label ) . ' ' . esc_html__( 'required ', 'endorse' );
					} else {
						$widget_html_error .= '<br/> - ' . esc_html( $widget_title_label ) . ' ' . esc_html__( 'required ', 'endorse' );
					}
					if ( true === $labels_above ) {
						$widget_testimonial_title = '';
					} else {
						$widget_testimonial_title = $widget_title_label;
					}
				}
				// Validate Testimonial.
				// Check for error before processing to avoid html encoding until all is good.
				// Premature encoding causes wp_kses to remove smiley images on second pass.
				if ( '' === $widget_html_error && '' === $widget_popup_error ) {
					// Sanitize first.
					if ( ! empty( $_POST['widget_testimonial'] ) ) {
						$sanitize_testimonial = wp_kses( wp_unslash( $_POST['widget_testimonial'] ), $allowed_html );
						// Add ClassicPress Smiley support.
						$fix_emoticons = convert_smilies( $sanitize_testimonial );
						// If emoji present convert to html entities.
						$widget_testimonial = wp_encode_emoji( $fix_emoticons );
					} else {
						$widget_testimonial = '';
					}
				} else {
					$widget_testimonial = wp_kses( wp_unslash( $_POST['widget_testimonial'] ), $allowed_html );
				}
				if ( $widget_testimonial === $widget_testimonial_label || '' === trim( $widget_testimonial ) ) {
					if ( false === $disable_widget_popup ) {
						$widget_popup_error .= '\n - ' . esc_html( $widget_testimonial_label ) . ' ' . esc_html__( 'required', 'endorse' );
					} else {
						$widget_html_error .= '<br/> - ' . esc_html( $widget_testimonial_label ) . ' ' . esc_html__( 'required', 'endorse' );
					}
					if ( true !== $labels_above ) {
						$widget_testimonial = $widget_testimonial_label;
					} else {
						$widget_testimonial = '';
					}
				}
				// Validation complete.
				if ( '' === $widget_html_error && '' === $widget_popup_error ) {
					// OK $widget_html_error is empty so let's add the testimonial.
					// First remove label entries if they exist.
					if ( $widget_location === $widget_location_label ) {
						$widget_location = '';
					}
					if ( $widget_website === $widget_website_label ) {
						$widget_website = '';
					}
					if ( $widget_custom1 === $widget_custom1_label ) {
						$widget_custom1 = '';
					}
					if ( $widget_custom2 === $widget_custom2_label ) {
						$widget_custom2 = '';
					}
					// Inset the custom post.
					// Set up the meta.
					$meta                = array();
					$meta['order']       = '';
					$meta['rating']      = $widget_rating;
					$meta['author']      = $widget_author;
					$meta['email']       = $widget_email;
					$meta['website']     = $widget_website;
					$meta['location']    = $widget_location;
					$meta['image']       = '';
					$meta['custom1']     = $widget_custom1;
					$meta['custom2']     = $widget_custom2;
					$post_arr            = array(
						'post_type'    => 'khatestimonial',
						'post_title'   => $widget_testimonial_title,
						'post_content' => $widget_testimonial,
						'post_status'  => $status,
						'meta_input'   => array(
							'endorse_meta' => $meta,
						),
					);
					$testimonial_post_id = wp_insert_post( $post_arr, true );
					if ( ! is_wp_error( $testimonial_post_id ) ) {
						wp_set_object_terms( $testimonial_post_id, $input_widget_group, 'khagroups', true );
					}
					// Send notification email.
					email_notification( $widget_author, $widget_email, $widget_testimonial );
					// Success message.
					if ( false === $disable_widget_popup ) {
						?>
						<script>
							<?php
							echo 'alert("' . esc_html__( 'Testimonial Submitted - Thank You!', 'endorse' ) . '")';
							?>
						</script>
						<?php
					} else {
						echo '<span class="endorse-test-sent">' . esc_html__( 'Testimonial Submitted - Thank You!', 'endorse' ) . '</span>';
					}
					// Initialize variables.
					if ( false === $labels_above ) {
						$widget_author            = $widget_author_label;
						$widget_email             = $widget_email_label;
						$widget_website           = $widget_website_label;
						$widget_location          = $widget_location_label;
						$widget_custom1           = $widget_custom1_label;
						$widget_custom2           = $widget_custom2_label;
						$widget_testimonial_title = $widget_title_label;
						$widget_testimonial       = $widget_testimonial_label;
					} else {
						$widget_author            = '';
						$widget_email             = '';
						$widget_website           = '';
						$widget_location          = '';
						$widget_custom1           = '';
						$widget_custom2           = '';
						$widget_testimonial_title = '';
						$widget_testimonial       = '';
					}
					$widget_rating = '0.0';
				} else {
					if ( false === $disable_widget_popup ) {
						$widget_error_message = __( 'There were errors so the testimonial was not added: ', 'endorse' ) . '\n' . $widget_popup_error;
						?>
						<script>alert("<?php echo $widget_error_message;//phpcs:ignore ?>")</script>
						<?php
					} else {
						?>
						<div class="widget-error">
							<?php echo esc_html__( 'Error', 'endorse' ) . $widget_html_error;//phpcs:ignore ?>
						</div>
						<?php
					}
					if ( '' === $widget_website ) {
						$widget_website = $widget_website_label;
					}
					if ( '' === $widget_location ) {
						$widget_location = $widget_location_label;
					}
				}
			}
		}
		?>
		<div class="endorse-widget-form-wrap">
			<?php
			if ( true === $widget_include_note ) {
				?>
				<p><?php echo esc_attr( stripslashes( $widget_note ) ); ?></p>
				<?php
			}
			?>
			<form method="POST">
				<?php
				wp_nonce_field( 'nonce_2', 'widget_form_nonce' . $widget_input_form_no );
				// GDPR approval.
				if ( true === $use_data_protection ) {
					?>
					<span class="widget-gdpr-approve">
						<input class="widget-gdpr-checkbox" type="checkbox" name="tb_gdpr" <?php checked( $widget_use_dp_checked, true ); ?>>
						<span class="widget-gdpr-label">
							<?php echo esc_html( $dp_note ); ?>
						</span>
					</span>
					<?php
				}
				// Author.
				if ( true === $labels_above ) {
					?>
					<label class="endorse-widget-input-label">
						<?php echo esc_html( $widget_author_label ); ?>
					</label>
					<?php
				}
				if ( $widget_author === $widget_author_label || '' === $widget_author ) {
					?>
					<input class="endorse-widget-input" type="text" name="widget_author" placeholder="<?php echo esc_attr( $widget_author ); ?>" />
					<?php
				} else {
					?>
					<input class="endorse-widget-input" type="text" name="widget_author" value="<?php echo esc_attr( $widget_author ); ?>" />
					<?php
				}
				// Email.
				if ( true === $labels_above ) {
					?>
					<label class="endorse-widget-input-label">
						<?php echo esc_html( $widget_email_label ); ?>
					</label>
					<?php
				}
				if ( $widget_email === $widget_email_label || '' === $widget_email ) {
					?>
					<input class="endorse-widget-input" type="text" name="widget_email" placeholder="<?php echo esc_attr( $widget_email ); ?>" />
					<?php
				} else {
					?>
					<input class="endorse-widget-input" type="text" name="widget_email" value="<?php echo esc_attr( $widget_email ); ?>" />
					<?php
				}
				// Website.
				if ( true === $include_website ) {
					if ( true === $labels_above ) {
						?>
						<label class="endorse-widget-input-label">
							<?php echo esc_html( $widget_website_label ); ?>
						</label>
						<?php
					}
					if ( $widget_website === $widget_website_label || '' === $widget_website ) {
						?>
						<input class="endorse-widget-input" type="text" name="widget_website" placeholder="<?php echo esc_html( $widget_website ); ?>" />
						<?php
					} else {
						?>
						<input class="endorse-widget-input" type="text" name="widget_website" value="<?php echo esc_url( $widget_website ); ?>" />
						<?php
					}
				}
				// Location.
				if ( true === $include_location ) {
					if ( true === $labels_above ) {
						?>
						<label class="endorse-widget-input-label">
							<?php echo esc_html( $widget_location_label ); ?>
						</label>
						<?php
					}
					if ( $widget_location === $widget_location_label || '' === $widget_location ) {
						?>
						<input class="endorse-widget-input" type="text" name="widget_location" placeholder="<?php echo esc_html( $widget_location ); ?>" />
						<?php
					} else {
						?>
						<input class="endorse-widget-input" type="text" name="widget_location" value="<?php echo esc_html( $widget_location ); ?>" />
						<?php
					}
				}
				// Custom 1.
				if ( true === $include_custom1 ) {
					if ( true === $labels_above ) {
						?>
						<label class="endorse-widget-input-label">
							<?php echo esc_html( $widget_custom1_label ); ?>
						</label>
						<?php
					}
					if ( $widget_custom1 === $widget_custom1_label || '' === $widget_custom1 ) {
						?>
						<input class="endorse-widget-input" type="text" name="widget_custom1" placeholder="<?php echo esc_html( $widget_custom1 ); ?>" />
						<?php
					} else {
						?>
						<input class="endorse-widget-input" type="text" name="widget_custom1" value="<?php echo esc_html( $widget_custom1 ); ?>" />
						<?php
					}
				}
				// Custom 2.
				if ( true === $include_custom2 ) {
					if ( true === $labels_above ) {
						?>
						<label class="endorse-widget-input-label">
							<?php echo esc_html( $widget_custom2_label ); ?>
						</label>
						<?php
					}
					if ( $widget_custom2 === $widget_custom2_label || '' === $widget_custom2 ) {
						?>
						<input class="endorse-widget-input" type="text" name="widget_custom2" placeholder="<?php echo esc_html( $widget_custom2 ); ?>" />
						<?php
					} else {
						?>
						<input class="endorse-widget-input" type="text" name="widget_custom2" value="<?php echo esc_html( $widget_custom2 ); ?>" />
						<?php
					}
				}
				// Rating.
				if ( true === $use_ratings ) {
					?>
					<label class="endorse-widget-rating-label">
						<?php echo esc_html( $widget_rating_label ); ?>
					</label>
					<select name="rating_widget" class="endorse-widget-rating-select">
						<option <?php selected( esc_attr( $widget_rating ) ); ?> value="<?php echo esc_attr( $widget_rating ); ?>"><?php echo esc_html( $widget_rating ); ?></option>
						<option value="0.0" <?php selected( esc_attr( $widget_rating ), '0.0' ); ?>>0.0</option>
						<option value="0.5" <?php selected( esc_attr( $widget_rating ), '0.5' ); ?>>0.5</option>
						<option value="1.0" <?php selected( esc_attr( $widget_rating ), '1.0' ); ?>>1.0</option>
						<option value="1.5" <?php selected( esc_attr( $widget_rating ), '1.5' ); ?>>1.5</option>
						<option value="2.0" <?php selected( esc_attr( $widget_rating ), '2.0' ); ?>>2.0</option>
						<option value="2.5" <?php selected( esc_attr( $widget_rating ), '2.5' ); ?>>2.5</option>
						<option value="3.0" <?php selected( esc_attr( $widget_rating ), '3.0' ); ?>>3.0</option>
						<option value="3.5" <?php selected( esc_attr( $widget_rating ), '3.5' ); ?>>3.5</option>
						<option value="4.0" <?php selected( esc_attr( $widget_rating ), '4.0' ); ?>>4.0</option>
						<option value="4.5" <?php selected( esc_attr( $widget_rating ), '4.5' ); ?>>4.5</option>
						<option value="5.0" <?php selected( esc_attr( $widget_rating ), '5.0' ); ?>>5.0</option>
					</select>
					<?php
				}
				// Title.
				if ( true === $labels_above ) {
					?>
					<label class="endorse-widget-input-label">
						<?php echo esc_html( $widget_title_label ); ?>
					</label>
					<?php
				}
				if ( $widget_testimonial_title === $widget_title_label || '' === $widget_testimonial_title ) {
					?>
					<input class="endorse-widget-input" type="text" name="widget_title" placeholder="<?php echo esc_html( $widget_testimonial_title ); ?>" />
					<?php
				} else {
					?>
					<input class="endorse-widget-input" type="text" name="widget_title" value="<?php echo esc_html( $widget_testimonial_title ); ?>" />
					<?php
				}
				// Testimonial.
				if ( true === $labels_above ) {
					?>
					<br/><label class="endorse-widget-input-label">
						<?php echo esc_html( $widget_testimonial_label ); ?>
					</label>
					<?php
				}
				if ( $widget_testimonial === $widget_testimonial_label || '' === $widget_testimonial ) {
					?>
					<textarea class="endorse-widget-input-textarea" name="widget_testimonial" rows="5" placeholder="<?php echo wp_kses_post( $widget_testimonial ); ?>"></textarea>
					<?php
				} else {
					?>
					<textarea class="endorse-widget-input-textarea" name="widget_testimonial" rows="5" ><?php echo wp_kses_post( $widget_testimonial ); ?></textarea>
					<?php
				}
				if ( true === $use_captcha ) {
					$site_key = $options['recaptcha_site_key'];
					?>
					<div class="endorse-widget-captcha">
						<div id="endorse-widget-captcha-<?php echo esc_attr( $widget_input_form_no ); ?>"
							class="g-recaptcha"
							data-captchaid="endorse-widget-captcha-<?php echo esc_attr( $widget_input_form_no ); ?>"
							data-sitekey="<?php echo esc_attr( $site_key ); ?>">
						</div>
					</div>
					<?php
				}
				?>
				<input class="endorse-widget-submit endorse-button" type="submit" name="<?php echo esc_attr( $post_name ); ?>" value="<?php echo esc_html( $widget_submit_label ); ?>" />
				<?php
				if ( true === $use_honeypot ) {
					?>
					<span class="endorse_span_custom3">
						<input class="endorse-custom_3" type="text" maxlength="100" name="widget_custom3" value="" autocomplete="off" />
					</span>
					<?php
				}
				?>
			</form>
			<?php
			if ( '' !== $widget_required_label ) {
				?>
				<span class="endorse-widget-required-text">
					<?php echo esc_html( $widget_required_label ); ?>
				</span>
				<?php
			}
			// HTML Allowed Note.
			if ( true === $widget_show_html_strip ) {
				?>
				<span class="endorse-widget-html">HTML <?php esc_html_e( 'Allowed', 'endorse' ); ?>: <i>a p br i em strong q h1-h6</i></span>
				<?php
			}
			?>
			<div class="endorse-clear-fix"></div>
			<?php
			if ( false !== $widget_include_gravatar ) {
				?>
				<div class="endorse-widget-use-gravatar">
					<span class="endorse-widget-gravatar-label"><?php esc_html_e( 'Add a Photo? ', 'endorse' ); ?></span>
					<a href="https://en.gravatar.com/" title="Gravatar Site" target="_blank" >
						<img class="endorse-widget-gravatar-logo" src="<?php echo plugins_url();//phpcs:ignore ?>/endorse/images/Gravatar80x16.jpg" alt="<?php esc_attr_e( 'Gravatar Website', 'endorse' ); ?>" title="<?php esc_attr_e( 'Gravatar Website', 'endorse' ); ?>" />
					</a>
				</div>
				<?php
			}
			?>
		</div>
		<br style="clear:both;" />
		<?php
		// after_widget is from register sidebar and is typicallt hardcoded html, no escaping required.
		echo $args['after_widget']; //phpcs:ignore
	}
}
