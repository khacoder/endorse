<?php
/**
 * Plugin Name: Endorse Display Widget
 * Plugin URI: https://kevinsspace.ca/endorse-classicpress-plugin/
 * Description: A plugin to display testimonials in a widget.
 * Version: 1.0.4
 * Author: Kevin Archibald
 * Author URI: http://kevinsspace.ca/
 * License: GPLv2 or later
 *
 * @package   Endorse ClassicPress Plugin
 * @copyright Copyright (C) 2020 Kevin Archibald
 * @license   GPLv2 or later http://www.gnu.org/licenses/quick-guide-gplv2.html
 * @author    Kevin Archibald <https://kevinsspace.ca/contact/>
 * Testimonial Basics is distributed under the terms of the GNU GPL
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
function display_register_widget() {
	register_widget( 'EndorseByKHA\EndorseDisplayWidget' );
}
add_action( 'widgets_init', 'EndorseByKHA\display_register_widget' );

/**
 * Widget Class
 */
class EndorseDisplayWidget extends \WP_Widget {
	/** The first function is required to process the widget
	 * It sets up an array to store widget options
	 * 'classname' - added to <li class="classnamne"> of the widget html
	 * 'description' - displays under Appearance => Widgets ...your widget
	 * WP_Widget(widget list item ID,Widget Name to be shown on grag bar, options array)
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'display_widget_class',
			'description' => esc_html__( 'Display Testimonials', 'endorse' ),
		);
		parent::__construct( 'endorse_display_widget', esc_html__( 'Endorse Display Widget', 'endorse' ), $widget_ops );
	}
	/**
	 * Form for widget setup.
	 *
	 * @param array $instance Previously saved values from the database.
	 */
	public function form( $instance ) {
		$display_defaults = array(
			'display_widget_title'  => esc_html__( 'Testimonials', 'endorse' ),
			'display_widget_group'  => '',
			'display_widget_number' => 'all',
			'display_widget_by'     => 'date',
			'display_widget_ids'    => '',
		);
		$instance         = wp_parse_args( (array) $instance, $display_defaults );
		$title            = $instance['display_widget_title'];
		$group            = $instance['display_widget_group'];
		$number           = $instance['display_widget_number'];
		$by               = $instance['display_widget_by'];
		$ids              = $instance['display_widget_ids'];
		?>
		<p><?php esc_html_e( 'Title : ', 'endorse' ); ?><input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'display_widget_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_widget_title' ) ); ?>" type="text" value="<?php echo esc_html( $title ); ?>" /></p>
		<p><?php esc_html_e( 'Group : ', 'endorse' ); ?><input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'display_widget_group' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_widget_group' ) ); ?>" type="text" value="<?php echo esc_html( $group ); ?>" /></p>
		<p><?php esc_html_e( 'Number : ', 'endorse' ); ?><input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'display_widget_number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_widget_number' ) ); ?>" type="text" value="<?php echo esc_html( $number ); ?>" /></p>
		<p><?php esc_html_e( 'By : ', 'endorse' ); ?>
			<select name="<?php echo esc_attr( $this->get_field_name( 'display_widget_by' ) ); ?>">
				<option value="date" <?php selected( $by, 'date' ); ?>><?php esc_html_e( 'date', 'endorse' ); ?></option>
				<option value="random" <?php selected( $by, 'random' ); ?>><?php esc_html_e( 'random', 'endorse' ); ?></option>
			</select> 
		</p>
		<p><?php esc_html_e( 'IDs : ', 'endorse' ); ?><input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'display_widget_ids' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_widget_ids' ) ); ?>" type="text" value="<?php echo esc_html( $ids ); ?>" /></p>
		<?php
	}
	/**
	 * Save the widget settings.
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from the database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                          = $old_instance;
		$instance['display_widget_title']  = esc_html( $new_instance['display_widget_title'] );
		$instance['display_widget_group']  = esc_html( $new_instance['display_widget_group'] );
		$instance['display_widget_number'] = esc_html( $new_instance['display_widget_number'] );
		$instance['display_widget_by']     = strtolower( esc_html( $new_instance['display_widget_by'] ) );
		$instance['display_widget_ids']    = esc_html( $new_instance['display_widget_ids'] );
		// number validation/whitelist.
		if ( '' !== $instance['display_widget_number'] ) {
			if ( intval( $instance['display_widget_number'] ) < 1 ) {
				$instance['display_widget_number'] = 1;
			} else {
				$instance['display_widget_number'] = intval( $instance['display_widget_number'] );
			}
		}
		// by whitelist.
		if ( 'date' !== $instance['display_widget_by'] ) {
			$instance['display_widget_by'] = 'rand';
		}
		return $instance;
	}

	/**
	 * Display the widget.
	 *
	 * @param array $args Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {
		// get user options.
		global $options;
		// Initialize main testimonial arrays.
		$use_excerpts          = $options['widget_use_excerpts'];
		$widget_excerpt_length = $options['widget_excerpt_length'];
		$use_ratings           = $options['use_ratings'];
		$widget_use_gravatars  = $options['widget_use_gravatars'];
		$widget_show_website   = $options['widget_show_website'];
		$widget_show_date      = $options['widget_show_date'];
		$widget_show_location  = $options['widget_show_location'];
		$widget_show_custom1   = $options['widget_show_custom1'];
		$widget_show_custom2   = $options['widget_show_custom2'];
		$use_data_protection   = $options['use_data_protection'];
		$dp_remove_permalink   = $options['dp_remove_permalink'];
		if ( 0 === intval( $widget_excerpt_length ) ) {
			$widget_excerpt_length = 20;
		}
		// get $args.
		$before_widget = $args['before_widget'];
		$after_widget  = $args['after_widget'];
		$before_title  = $args['before_title'];
		$after_title   = $args['after_title'];
		// before_widget is from register sidebar and is typically hardcoded html, no escaping required.
		echo $before_widget;//phpcs:ignore
		// Get the settings for this instance of the widget.
		$title  = apply_filters( 'widget_title', empty( $instance['display_widget_title'] ) ? '' : $instance['display_widget_title'], $instance, $this->id_base );
		$group  = $instance['display_widget_group'];
		$number = $instance['display_widget_number'];
		$by     = $instance['display_widget_by'];
		$ids    = $instance['display_widget_ids'];
		// Validate/whitelist group.
		if ( isset( $group ) ) {
			$group = sanitize_text_field( $group );
		} else {
			$group = '';
		}
		// Validate/whitelist Number.
		if ( isset( $number ) ) {
			$number = strtolower( sanitize_text_field( $number ) );
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
		if ( isset( $ids ) && '' !== $ids ) {
			$ids           = sanitize_text_field( $ids );
			$ids_validated = validate_ids( $ids );
		} else {
			$ids = '';
		}
		// White list by.
		if ( isset( $by ) ) {
			$by = strtolower( sanitize_text_field( $by ) );
			if ( 'date' !== $by ) {
				$by = 'rand';
			}
		} else {
			$by = 'date';
		}
		// display the title.
		if ( ! empty( $title ) ) {
			// before_title and after_title are from register sidebar, typically hardcoded html, no escaping required.
			echo $before_title . esc_html( $title ) . $after_title;//phpcs:ignore
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
				'posts_per_page' => $number,
			);
		} else {
			$args = array(
				'post_type'      => 'khatestimonial',
				'orderby'        => $by,
				'tax_query'      => array(//phpcs:ignore
					array(
						'taxonomy' => 'khagroups',
						'field'    => 'slug',
						'terms'    => $group,
					),
				),
				'posts_per_page' => $number,
			);
		}
		$loop         = new \WP_Query( $args );
		$widget_error = '';
		if ( '' !== $widget_error ) {
			?>
			<div class="endorse-display-widget-error"><?php echo esc_attr( $widget_error ); ?></div>
			<?php
		} else {
			// Display these testimonials.
			?>
			<div class="endorse-widget-wrap-top">
				<?php
				while ( $loop->have_posts() ) {
					$loop->the_post();
					$id                = get_the_id();
					$meta              = get_post_meta( $id, 'endorse_meta' );
					$gravatar_or_photo = insert_gravatar( $meta[0]['image'], '120', $widget_use_gravatars, $meta[0]['email'] );
					?>
					<div class="endorse-widget-box-top">
						<div class="endorse-widget-meta-top-wrap">
							<?php if ( '' !== $gravatar_or_photo ) { ?>
								<div class="endorse-widget-gravatar-top">
									<?php echo $gravatar_or_photo;//phpcs:ignore ?>
								</div>
							<?php } ?>
							<div class="endorse-widget-meta-top">
								<?php
									echo get_author_html( $meta[0]['author'], $divider = '' );// phpcs:ignore
									echo get_date_html( $widget_show_date, get_the_date(), $divider = ', ' );// phpcs:ignore
									echo get_location_html( $widget_show_location, $meta[0]['location'], $divider = ' <br/>' );// phpcs:ignore
									echo get_custom1_html( $widget_show_custom1, $meta[0]['custom1'], $divider = ' ' );// phpcs:ignore
									echo get_custom2_html( $widget_show_custom2, $meta[0]['custom2'], $divider = ' <br/>' );// phpcs:ignore
									echo get_website_html( $widget_show_website, $meta[0]['website'], $divider = '' );// phpcs:ignore
								?>
							</div>
						</div>
						<div class="endorse-widget-title-rating-wrap">
							<?php
								echo get_title_html( get_the_title() );// phpcs:ignore
								echo get_rating_html( $use_ratings, $meta[0]['rating'] );// phpcs:ignore
							?>
						</div>
						<div class="endorse-widget-testimonial-wrap-top">
							<?php
							if ( true === $use_excerpts ) {
								$args = array(
									'length'          => intval( $widget_excerpt_length ),
									'readmore_text'   => esc_html__( 'read more', 'endorse' ),
									'custom_excerpts' => true,
								);
								echo endorse_get_excerpt( $args );//phpcs:ignore
							} else {
								the_content( esc_html__( 'read more', 'endorse' ) );
							}
							if ( true === $use_data_protection ) {
								echo get_gdpr_link( $dp_remove_permalink, $id, $divider, 'remove-link-widget' );//phpcs:ignore
							}
							?>
						</div>
					</div>
				<?php } ?>
			</div>
			<?php
			wp_reset_postdata();
		}
		?>
		<br style="clear:both;" />
		<?php
		// after_widget is from register sidebar and is typicallt hardcoded html, no escaping required.
		echo $after_widget;//phpcs:ignore

	}

}
