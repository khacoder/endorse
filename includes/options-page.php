<?php
/**
 * Endorse Options
 * This file sets up the options for the plugin.
 *
 * @package   Endorse ClassicPress Plugin
 * @copyright Copyright (C) 2020, Kevin Archibald
 * @license   GPLv2 or later http://www.gnu.org/licenses/quick-guide-gplv2.html
 * @author    kevinhaig <kevinsspace.ca/contact/>
 * Endorse is distributed under the terms of the GNU GPL.
 */

namespace EndorseByKHA;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Sets up custom admin pages under the Testimonials menu.
 *
 * @since 1.0.0
 */
function create_submenu() {
	// Documentation page.
	add_submenu_page(
		'edit.php?post_type=khatestimonial',
		esc_html__( 'Endorse Documentation', 'endorse' ),
		esc_html__( 'Documentation', 'endorse' ),
		'manage_options',
		'endorse_docs',
		'EndorseByKHA\setup_documentation',
		'',
		5
	);
	// Backup or restore page.
	add_submenu_page(
		'edit.php?post_type=khatestimonial',
		esc_html__( 'Endorse Backup/Restore', 'endorse' ),
		esc_html__( 'Backup/Restore', 'endorse' ),
		'manage_options',
		'endorse-backup-restore',
		'EndorseByKHA\setup_backup_restore_page',
		'',
		6
	);
	// Link to Panel.
	$query['autofocus[panel]'] = 'endorse';
	$panel_link                = add_query_arg( $query, admin_url( 'customize.php' ) );
	// Backup or restore page.
	add_submenu_page(
		'edit.php?post_type=khatestimonial',
		esc_html__( 'Endorse Options', 'endorse' ),
		esc_html__( 'Endorse Options', 'endorse' ),
		'manage_options',
		esc_url( $panel_link ),
		'',
		'',
		10
	);
}
add_action( 'admin_menu', 'EndorseByKHA\create_submenu' );

/**
 * Call back to set up the Documentation page.
 *
 * @since 1.0.0
 */
function setup_documentation() {
	?>
	<div class="wrap" style="max-width: 800px;">
		<h1><?php esc_html_e( 'Endorse - Documentation', 'endorse' ); ?></h1>

		<?php echo intro_html();//phpcs:ignore ?>

		<h2 style="font-size: 24px;"><?php esc_html_e( 'Introduction', 'endorse' ); ?></h2>

		<p><?php esc_html_e( 'Endorse facilitates easy management of customer testimonials. The user can set up an input form in a page or in a widget, and display all or selected testimonials in a page or a widget.', 'endorse' ); ?></p>

		<p><?php esc_html_e( 'If you like the program show your appreciation, buy me a coffee, beer, or a bottle of wine (red please!). Or just head to the website link above and put in a testimonial, or send me an e-mail, pats on the back are pretty nice too!', 'endorse' ); ?></p>

		<p><?php esc_html_e( 'I plan to do updates if required, so also contact me if you find any problems, or have suggestions for improvements.', 'endorse' ); ?></p>

		<p><?php esc_html_e( 'A word about Schema. This plugin does not contain any schema. In the past year Google has frowned on users who are using rating plugins on their site, and have developed a policy where these kind of reviews should be done independantly. So there is no schema and there are no plans to use it at this time.', 'endorse' ); ?></p>
		<p><?php esc_html_e( 'I hope you enjoy Endorse.', 'endorse' ); ?></p>

		<h2 style="font-size: 24px;"><?php esc_html_e( 'Initial Set Up', 'endorse' ); ?></h2>
		<p><?php esc_html_e( 'The Endorse Plugin options are available in the Customize Panel. You can access these options from Testimonials->Endorse Options.', 'endorse' ); ?></p>

		<h3><?php esc_html_e( 'Endorse Testimonials -> General Options', 'endorse' ); ?></h3>
		<ul>
			<li><?php esc_html_e( 'You can set up a different email address for site owners to receive the testimonial notice.', 'endorse' ); ?></li>
			<li><?php esc_html_e( 'There are two custom fields available in Endorse, You could for example set up a Company name and position as additional items for users to input.', 'endorse' ); ?></li>
			<li><?php esc_html_e( 'There is an option here to use ratings and to set the rating colors.', 'endorse' ); ?></li>
			<li><?php esc_html_e( 'You can customize the more label here.', 'endorse' ); ?></li>
			<li><?php esc_html_e( 'Custom CSS - enter any custom styles you want..', 'endorse' ); ?></li>
		<ul>

		<h3><?php esc_html_e( 'Endorse Testimonials -> Input Form General', 'endorse' ); ?></h3>
		<ul>
			<li><?php esc_html_e( 'Although not recommended, you can auto approve testimonials.', 'endorse' ); ?></li>
			<li><?php esc_html_e( 'You can set up your input form to use a honeypot to reduce BOT spam.', 'endorse' ); ?></li>
			<li><?php esc_html_e( 'You can also use reCaptcha V2, but you will need to get a Google account and sign up your site for reCaptcha at https://www.google.com/recaptcha/.', 'endorse' ); ?></li>
			<li><?php esc_html_e( 'You can optionally set up inputs for a website, location, Custom1, and Custom2, and whether or not they will be required.', 'endorse' ); ?></li>
			<li><?php esc_html_e( 'Finally, you can include a gravatar link for those folks that would like to include a photo.', 'endorse' ); ?></li>
		<ul>

		<h3><?php esc_html_e( 'Endorse Testimonials -> Input Form - Content Area (Widget)', 'endorse' ); ?></h3>
		<p><?php esc_html_e( 'These sections are all pretty explanitory. You can customize the label text, add a optional title and note, and set the font size.', 'endorse' ); ?></p>

		<h3><?php esc_html_e( 'Endorse Testimonials -> Content (Widget) Display', 'endorse' ); ?></h3>
		<p><?php esc_html_e( 'These sections are all pretty explanitory as well. You can customize how you want the testimonials to display. Note that the pagination only works in the Content area (not for widgets).', 'endorse' ); ?></p>


		<h2 style="font-size: 24px;"><?php esc_html_e( 'General Data Protection', 'endorse' ); ?></h2>

		<p>
			<?php
			esc_html_e( 'The General Data protection options allow you to set up measures to ensure users rights are protectes. Users must click a checkbox to show they understand that testimonials are saved. An additional option allows you to set up a Remove Testimonial Page. A link on every testimonial is set up that takes the user to the page and allows the user to send an email requesting removal of the testimonial selected.', 'endorse' );
			echo '<br/><br/>';
			esc_html_e( 'Create a new page titled `Request Testimonial Removal`, or some other title you wish to use and add the following shortcode:', 'endorse' );
			?>
			<br/><br/>
			<code>[endorse_remove_testimonial]</code>
			<br/><br/>
			<?php
			esc_html_e( 'In the Endorse Testimonial->Data Protection Options tab you can set up your site to facilitate GDPR compliance.', 'endorse' );
			echo ' ';
			?>
			<ol>
				<li><?php esc_html_e( 'Check the "Use General Data Protection checkbox.".', 'endorse' ); ?></li>
				<li><?php esc_html_e( 'A "Save data approval note" checkbox will be set up on your testimonial input forms that must be checked by visitors submitting a testimonial.', 'endorse' ); ?></li>
				<li><?php esc_html_e( 'In "Request testimonial remove page permalink", add the permalink to the page you just created. The pemalink is the link shown in the browser url when the page is displayed.', 'endorse' ); ?></li>
				<li><?php esc_html_e( 'You can change the default introduction text for the page.', 'endorse' ); ?></li>
				<li><?php esc_html_e( 'If the user accesses the page directly, a testimonial will not be listed. Replace this default message if you want.', 'endorse' ); ?></li>
				<li><?php esc_html_e( 'A minus icon appears on all testimonials displayed. If the user clicks it they will be taken to your Testimonial Removal page.', 'endorse' ); ?></li>
				<li><?php esc_html_e( 'They can add their email, and an optional comment.', 'endorse' ); ?></li>
				<li><?php esc_html_e( 'An email is sent to the site admin, or an optionally set email, requesting the testimonial be removed.', 'endorse' ); ?></li>
				<li><?php esc_html_e( 'If the email is the same as the testimonial or they provide additional details in the comments, you can remove the testimonial.', 'endorse' ); ?></li>
			</ol>
		</p>

		<h2 style="font-size: 24px;"><?php esc_html_e( 'Visitor Input Form', 'endorse' ); ?></h2>
		<p>
			<?php
			esc_html_e( 'You can set up a visitor input form very easily. Simply include the following shortcode in your page content:', 'endorse' );
			?>
			<br/>
			<code>[endorse_input group="" form="1"]</code>
			<br/>
			<ol>
				<li><?php esc_html_e( 'IMPORTANT : Make sure you set up the page using the "Text" editor and not the "Visual" editor.', 'endorse' ); ?></li>
				<li><?php esc_html_e( 'group - This will be the group name for the testimonial, default is "", or blank.', 'endorse' ); ?></li>
				<li><?php esc_html_e( 'form - The first form is 1 by default', 'endorse' ); ?></li>
				<li><?php esc_html_e( 'If you have more than one form in the content area number them 1,2,3, ...', 'endorse' ); ?></li>
			</ol>
		</p>

		<h2 style="font-size: 24px;"><?php esc_html_e( 'Visitor Input Widget', 'endorse' ); ?></h2>
		<p><?php esc_html_e( 'You can also use a widget as a user input form. Appearance" => "Widgets" and drag the widget to the widgetized area.', 'endorse' ); ?></p>

		<h2 style="font-size: 24px;"><?php esc_html_e( 'Displaying Testimonials', 'endorse' ); ?></h2>
		<p><?php esc_html_e( 'You can display testimonials in the content of a page using a shortcode or you can use widgets to display testimonials.', 'endorse' ); ?></p>
		<h3><?php esc_html_e( 'Using a Shortcode', 'endorse' ); ?></h3>
		<p>
			<?php
			esc_html_e( 'To display testimonials create a new page and enter the following shortcode :', 'endorse' );
			echo ' ';
			?>
			<br/>
			<code>[endorse_testimonial group="" number="all" ids="" per_page="" by="date"]</code>
		</p>
		<ol>
			<li>
				<?php
				esc_html_e( 'Options for', 'endorse' );
				echo ' "group" : "" - ';
				esc_html_e( 'ignores groups', 'endorse' );
				echo ', "group_name"- ';
				esc_html_e( 'display only this grouping', 'endorse' );
				?>
			</li>
			<li>
				<?php
				esc_html_e( 'Options for', 'endorse' );
				echo ' "number" : "all" - ';
				esc_html_e( 'displays all testimonials, or put in the number of testimonials you want to display', 'endorse' );
				?>
			</li>
			<li>
				<?php
				esc_html_e( 'Options for', 'endorse' );
				echo ' "by" : "random" - ';
				esc_html_e( 'display randomly', 'endorse' );
				echo ', "date"- ';
				esc_html_e( 'displays most recent first', 'endorse' );
				?>
			</li>
			<li>
				<?php
				esc_html_e( 'Options for', 'endorse' );
				echo ' "ids" : "" - ';
				esc_html_e( 'leave blank for multiple testimonials', 'endorse' );
				echo ', "ID" - '; // translate ok.
				esc_html_e( 'put in testimonial IDs that you want', 'endorse' );
				?>
			</li>
		</ol>
		<p><strong><?php esc_html_e( 'Tips', 'endorse' ); ?></strong></p>
		<ul>
			<li><?php esc_html_e( '* Note that if ids is not blank ( ids="" ), the "group", "by" and "number" attributes are ignored.', 'endorse' ); ?></li>
			<li><?php esc_html_e( '* Use pagination to display a lot of testimonials.', 'endorse' ); ?></li>
			<li><?php esc_html_e( '* Pagination can only be used when displaying number="all" and by="date"', 'endorse' ); ?></li>
			<li><?php esc_html_e( '* IMPORTANT : Make sure you set up the page using the "Text" editor and not the "Visual" editor.', 'endorse' ); ?></li>
		</ul>

		<h2 style="font-size: 24px;"><?php esc_html_e( 'Using a Widget', 'endorse' ); ?></h2>
		<p><?php esc_html_e( 'You can also use a widget to display a testimonial.', 'endorse' ); ?>
			<ol>
				<li><?php esc_html_e( 'Drag the widget to a widetized area and enter a title.', 'endorse' ); ?></li>
				<li><?php esc_html_e( 'Set your options.', 'endorse' ); ?></li>
				<li><?php esc_html_e( 'Save the settings.', 'endorse' ); ?></li>
			</ol>	
		<p><strong><?php esc_html_e( 'Tips', 'endorse' ); ?></strong></p>
		<ul>
			<li><?php esc_html_e( '* The fewer the testimonials the lower the load time', 'endorse' ); ?></li>
		</ul>
	</div>
	<?php
}

/**
 * This function contains the html for the intro section to the admin pages.
 *
 * @since 1.0.0
 */
function intro_html() {
	?>
	<div class="endorse_paypal"><?php esc_html_e( 'Show your appreciation!', 'endorse' ); ?>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="PP4GPMXBUVPY4">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
	</div>
	<p>
		<?php
			$endorse_options;
		if ( true === is_rtl() ) {
			echo '<a href="//www.kevinsspace.ca/testimonial-basics-wordpress-plugin/" target="_blank" >kevinsspace.ca/endorse-classicpress-plugin/</a>' . esc_html__( ' : Plugin Site', 'endorse' );
			echo '&nbsp;&nbsp;&nbsp;<a href="//www.kevinsspace.ca" target="_blank" >www.kevinsspace.ca</a>' . esc_html__( ' : Author Site', 'endorse' );
		} else {
			echo esc_html__( 'Author Site : ', 'endorse' ) . '<a href="//www.kevinsspace.ca" target="_blank" >kevinsspace.ca</a>&nbsp;&nbsp;&nbsp;';
			echo esc_html__( 'Plugin Site : ', 'endorse' ) . '<a href="//www.kevinsspace.ca/endorse-classicpress-plugin/" target="_blank" >kevinsspace.ca/endorse-classicpress-plugin/</a>';
		}
		?>
	</p>
	<?php
}

/**
 * =========================================================================================
 *               Endorse Backup/Restore
 * =========================================================================================
 */
/**
 * This is the callback for the backup or restote page
 *
 * @since 1.0.0
 * @link http://code.tutsplus.com/tutorials/custom-database-tables-exporting-data--wp-28796
 * @link http://code.tutsplus.com/tutorials/custom-database-tables-importing-data--wp-28869
 */
function setup_backup_restore_page() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Endorse - Backup or Restore Your Testimonials', 'endorse' ); ?></h1>
		<?php echo intro_html();//phpcs:ignore ?>
		<!-- Backup Testimonials -->
		<h3><?php esc_html_e( 'Backup Testimonials', 'endorse' ); ?></h3>
		<p><?php esc_html_e( 'You can back up all your testimonials to an xml file, and it will be downloaded to your computer.', 'endorse' ); ?></p>
		<form id="endorse-backup-form" method="post" action="">
			<p>
				<label><?php esc_html_e( 'Click to backup testimonials', 'endorse' ); ?></label>
				<input type="hidden" name="backup-action" value="endorse-backup-action" />
			</p>
			<?php wp_nonce_field( 'endorse-export-testimonials', 'endorse_backup_nonce' ); ?>
			<?php submit_button( esc_html__( 'Backup Testimonials', 'endorse' ), 'button' ); ?>
		</form>
		<!-- Restore Testimonials -->
		<h3><?php esc_html_e( 'Restore Testimonials', 'endorse' ); ?></h3>
		<p>
			<?php esc_html_e( 'You can restore your testimonials from a backup xml file.', 'endorse' ); ?>
			<?php esc_html_e( 'Maximum file size is 2MB.', 'endorse' ); ?>
			<?php esc_html_e( 'If your backup file is too large it will have to be broken up into parts.', 'endorse' ); ?>
			<?php esc_html_e( 'For example to break up your file into 2 files, use a text editor such as Notepad or Notepad++.', 'endorse' ); ?>
			<?php esc_html_e( 'Make two copies of the full backup file and append _part1 and _part2 to the file names.', 'endorse' ); ?>
			<?php esc_html_e( 'Remove the bottom half of the testimonials from the first file.', 'endorse' ); ?>
			<?php esc_html_e( 'Remove the top half of the testimonials from the second file.', 'endorse' ); ?>
			<?php esc_html_e( 'Note that all testimonials are wrapped in &lt;testimonials&gt;&lt;/testimonials&gt; tags. Do not delete these tags.', 'endorse' ); ?>
			<?php esc_html_e( 'Each testimonial is wrapped in &lt;testimonial&gt;&lt;/testimonial&gt; tags.', 'endorse' ); ?>
			<?php esc_html_e( 'To remove a testimonial delete everything between the &lt;testimonial&gt;&lt;/testimonial&gt; tags, including the tags.', 'endorse' ); ?>
		</p>
		<form method="post" action="" enctype="multipart/form-data">
			<p>
				<label for="endorse_import_testimonials"><?php esc_html_e( 'Select an xml file', 'endorse' ); ?></label>
				<input type="file" id="endorse_import_testimonials" name="endorse_import_file" />
			</p>
			<input type="hidden" name="import-action" value="endorse-import-action" />
			<?php wp_nonce_field( 'endorse-import-testimonials', 'endorse_import_nonce' ); ?>
			<?php submit_button( esc_html__( 'Restore Testimonials', 'endorse' ), 'secondary' ); ?>
		</form>
		<br/>
		<h3><?php esc_html_e( 'Other Notes/Tips', 'endorse' ); ?></h3>
		<ol>
			<li><?php esc_html_e( 'New posts are made with each testimonial, so you may create duplicates.', 'endorse' ); ?></li>
			<li><?php esc_html_e( 'Any custom photos are not uploaded and you will have to set that up separately.', 'endorse' ); ?></li>
		</ol>
	</div>
	<?php
}

/**
 * Listens for download button link then executes download.
 *
 * @since 1.0.0
 */
function maybe_download() {
	// Listen for download form submission.
	if ( empty( $_POST['backup-action'] ) || 'endorse-backup-action' !== $_POST['backup-action'] ) {
		return;
	}
	// Check permissions and nonces.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( '' );
	}
	check_admin_referer( 'endorse-export-testimonials', 'endorse_backup_nonce' );
	// Trigger download.
	do_backup();
}
add_action( 'admin_init', 'EndorseByKHA\maybe_download' );

/**
 * Listens for upload button link then executes upload.
 *
 * @since 1.0.0
 */
function maybe_upload() {
	// Listen for upload form submission.
	if ( empty( $_POST['import-action'] ) || 'endorse-import-action' !== $_POST['import-action'] ) {
		return;
	}
	// Check permissions and nonces.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not authorized to restore testimonials', 'endorse' ) );
	}
	check_admin_referer( 'endorse-import-testimonials', 'endorse_import_nonce' );
	// Perform checks on file.
	// Sanity check.
	if ( empty( $_FILES['endorse_import_file'] ) ) {
		wp_die( esc_html__( 'No file found', 'endorse' ) );
	}
	$file = $_FILES['endorse_import_file'];// phpcs:ignore
	// Is it of the expected type?
	if ( 'text/xml' !== $file['type'] ) {
		// translators: variable is the type of file trying to be imported.
		wp_die( sprintf( esc_html__( "There was an error importing the testimonials. File type detected: '%s'. File type expected: 'text/xml'.", 'endorse' ), esc_html( $file['type'] ) ) );
	}
	// Impose a limit on the size of the uploaded file. Max 2097152 bytes = 2MB.
	if ( $file['size'] > 2097152 ) {
		$size = size_format( $file['size'], 2 );
		// translators: variable is the size of file trying to be imported.
		wp_die( sprintf( esc_html__( 'File size too large (%s). Maximum 2MB' ), 'endorse' ), esc_html( $size ) );
	}
	if ( $file['error'] > 0 ) {
		// translators: variable is the short description of the error encountered.
		wp_die( sprintf( esc_html__( 'Error encountered: %d', 'endorse' ), esc_html( $file['error'] ) ) );
	}
	// If we've made it this far then we can import the data.
	$imported = endorse_import( $file['tmp_name'] );

	// Everything is complete, now redirect back to the page.
	wp_safe_redirect( add_query_arg( 'imported', $imported ) );
	exit();
}
add_action( 'admin_init', 'EndorseByKHA\maybe_upload' );

/**
 * Creates the backup and downloads it.
 *
 * @param array $args array of parameters for wp_query().
 * @since 1.0.0
 */
function do_backup( $args = array() ) {
	global $post;
	// Create a file name.
	$filename = 'endorse_backup_' . gmdate( 'Y-m-d' ) . '.xml';
	// Get the testimonials.
	$args      = array(
		'post_type'      => 'khatestimonial',
		'posts_per_page' => -1,
	);
	$post_data = new \WP_Query( $args );
	// Print header.
	header( 'Content-Description: File Transfer' );
	header( 'Content-Disposition: attachment; filename=' . $filename );
	header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );
	// Print comments and set up the file.
	?>
	<!-- This is a export of the testimonial-basics table -->
	<!--  Import using the Import Button in the Testimonial Basics admin page -->
	<testimonials>
		<?php
		while ( $post_data->have_posts() ) {
			$post_data->the_post()->post;
			if ( ! get_post_meta( $post->ID, 'endorse_meta' ) ) {
				$rating   = 0;
				$author   = '';
				$email    = '';
				$website  = '';
				$location = '';
				$image    = '';
				$custom1  = '';
				$custom2  = '';
			} else {
				$meta     = get_post_meta( $post->ID, 'endorse_meta' );
				$rating   = $meta[0]['rating'];
				$author   = $meta[0]['author'];
				$email    = $meta[0]['email'];
				$website  = $meta[0]['website'];
				$location = $meta[0]['location'];
				$image    = $meta[0]['image'];
				$custom1  = $meta[0]['custom1'];
				$custom2  = $meta[0]['custom2'];
			}
			$terms      = wp_get_post_terms( $post->ID, 'khagroups', array( 'fields' => 'names' ) );
			$totalcount = count( $terms );
			$groups     = '';
			$count      = 1;
			foreach ( $terms as $term ) {
				if ( $count === $totalcount ) {
					$groups .= $term;
				} else {
					$groups .= $term . ',';
				}
				$count++;
			}
			?>
			<testimonial>
				<id><?php echo wrap_cdata( intval( $post->ID ) );// phpcs:ignore ?></id>
				<date><?php echo wrap_cdata( sanitize_text_field( $post->post_date ) );// phpcs:ignore ?></date>
				<group><?php echo wrap_cdata( esc_html( $groups ) );// phpcs:ignore ?></group>
				<approved><?php echo wrap_cdata( esc_html( $post->post_status ) );// phpcs:ignore ?></approved>
				<name><?php echo wrap_cdata( sanitize_text_field( $author ) );// phpcs:ignore ?></name>
				<location><?php echo wrap_cdata( sanitize_text_field( $location ) );// phpcs:ignore ?></location>
				<email><?php echo wrap_cdata( sanitize_text_field( $email ) );// phpcs:ignore ?></email>
				<pic_url><?php echo wrap_cdata( esc_url( $image ) );// phpcs:ignore ?></pic_url>
				<web_url><?php echo wrap_cdata( esc_url( $website ) );// phpcs:ignore ?></web_url>
				<rating><?php echo wrap_cdata( floatval( $rating ) );// phpcs:ignore ?></rating>
				<custom1><?php echo wrap_cdata( esc_html( $custom1 ) );// phpcs:ignore ?></custom1>
				<custom2><?php echo wrap_cdata( esc_html( $custom2 ) );// phpcs:ignore ?></custom2>
				<title><?php echo wrap_cdata( wp_kses_post( $post->post_title ) );// phpcs:ignore ?></title>
				<content><?php echo wrap_cdata( wp_kses_post( $post->post_content ) );// phpcs:ignore ?></content>
			</testimonial>
		<?php } ?>
	</testimonials>
	<?php
	wp_reset_postdata();
	exit();
}

/**
 * Wraps the passed string in a XML CDATA tag.
 *
 * @since 1.0.0
 * @param string $string String to wrap in a XML CDATA tag.
 * @return string
 */
function wrap_cdata( $string ) {
	if ( false === seems_utf8( $string ) ) {
		$string = utf8_encode( $string );
	}
	return '<![CDATA[' . str_replace( ']]>', ']]]]><![CDATA[>', $string ) . ']]>';
}

/**
 * Import testimonials to custom post type khatestimonial.
 *
 * @since 1.0.0
 * @param file $file is the xml file to be used for the import.
 */
function endorse_import( $file ) {
	global $post;
	// Parse file.
	$testimonials = parse( $file );
	// No logs found ? - then aborted.
	if ( ! $testimonials ) {
		return false;
	}
	// Initialises a variable storing the number of testimonials successfully imported.
	$imported = 0;
	// Go through each testimonial to restore.
	foreach ( $testimonials as $testimonial ) {
		if ( 0 === $testimonial['approved'] || false === $testimonial['approved'] || 'draft' === $testimonial['approved'] ) {
			$status = 'draft';
		} else {
			$status = 'publish';
		}
		// Website validation.
		$restore_website = $testimonial['web_url'];
		if ( '' !== $restore_website ) {
			$restore_website = esc_url( $restore_website );
		}
		if ( 'http://' === $restore_website ) {
			$restore_website = '';
		}
		// Image validation.
		$restore_picture_url = $testimonial['pic_url'];
		if ( '' !== $restore_picture_url ) {
			$restore_picture_url = esc_url( $restore_picture_url );
		}
		if ( 'http://' === $restore_picture_url ) {
			$restore_picture_url = '';
		}
		// Set up the meta.
		$endorse_meta             = array();
		$endorse_meta['rating']   = floatval( $testimonial['rating'] );
		$endorse_meta['author']   = sanitize_text_field( $testimonial['name'] );
		$endorse_meta['email']    = sanitize_email( $testimonial['email'] );
		$endorse_meta['website']  = $restore_website;
		$endorse_meta['location'] = sanitize_text_field( $testimonial['location'] );
		$endorse_meta['image']    = $restore_picture_url;
		$endorse_meta['custom1']  = sanitize_text_field( $testimonial['custom1'] );
		$endorse_meta['custom2']  = sanitize_text_field( $testimonial['custom2'] );
		$post_arr                 = array(
				'post_type'    => 'khatestimonial',
				'post_title'   => wp_kses_post( $testimonial['title'] ),
				'post_content' => wp_kses_post( $testimonial['content'] ),
				'post_date'    => esc_html( $testimonial['date'] ),
				'post_status'  => $status,
				'meta_input'   => array(
				'endorse_meta' => $endorse_meta,
			),
		);
		$testimonial_post_id      = wp_insert_post( $post_arr );
		if ( ! is_wp_error( $testimonial_post_id ) ) {
			$groups = explode( ',', $testimonial['group'] );
			foreach ( $groups as $group ) {
				wp_set_object_terms( $testimonial_post_id, esc_html( $group ), 'khagroups', true );
			}
		}
		$imported++;
	}
	return $imported;
}

/**
 * Parses $file into a testimonial array to be used for importing.
 *
 * @since 1.0.0
 * @param file $file is the xml file to be used for the import.
 */
function parse( $file ) {
	// Load the xml file.
	$xml = simplexml_load_file( $file, null, LIBXML_NOCDATA );
	// halt if loading produces an error.
	if ( ! $xml ) {
		return false;
	}
	// Setup counter and initialize array.
	$testimonial_counter = 0;
	$testimonials        = array();
	// Loop.
	foreach ( $xml->xpath( '/testimonials/testimonial' ) as $testimonial_obj ) {
		$testimonial                          = $testimonial_obj->children();
		$testimonials[ $testimonial_counter ] = array(
			'id'       => (int) $testimonial->id,
			'date'     => (string) $testimonial->date,
			'group'    => (string) $testimonial->group,
			'status'   => (string) $testimonial->approved,
			'name'     => (string) $testimonial->name,
			'location' => (string) $testimonial->location,
			'email'    => (string) $testimonial->email,
			'pic_url'  => (string) $testimonial->pic_url,
			'web_url'  => (string) $testimonial->web_url,
			'rating'   => (string) $testimonial->rating,
			'custom1'  => (string) $testimonial->custom1,
			'custom2'  => (string) $testimonial->custom2,
			'title'    => (string) $testimonial->title,
			'content'  => (string) $testimonial->content,
		);
		$testimonial_counter ++;
	}
	return $testimonials;
}

/**
 * Provides an admis notice about success or failure of the import.
 *
 * @since 1.0.0
 */
function admin_notices() {
	// Was an import attempted and are we on the correct admin page?
	if ( ! isset( $_GET['imported'] ) || 'khatestimonial_page_endorse-backup-restore' !== get_current_screen()->id ) {// phpcs:ignore
		return;
	}
	$imported = intval( $_GET['imported'] );// phpcs:ignore
	if ( 1 === $imported ) {
		printf( '<div class="updated"><p>%s</p></div>', esc_html__( '1 testimonial successfully imported', 'endorse' ) );
	} elseif ( intval( $_GET['imported'] ) ) {// phpcs:ignore
		// translators: variable is the number of testimonials imported.
		printf( '<div class="updated"><p>%s</p></div>', sprintf( esc_html__( '%d testimonials successfully imported', 'endorse' ), intval( $imported ) ) );
	} else {
		printf( '<div class="error"><p>%s</p></div>', esc_html__( 'No testimonials were imported - must be a problem somewhere.', 'endorse' ) );
	}
}
add_action( 'admin_notices', 'EndorseByKHA\admin_notices' );
