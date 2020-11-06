/* eslint-disable no-undef */
/**
 * Admin doc ready script
 *
 * @since 1.0.0
 * @package   Endorse ClassicPress Plugin 
 * @copyright Copyright (C) 2020, kevinhaig
 * @license   GPLv2 or later http://www.gnu.org/licenses/quick-guide-gplv2.html
 * @author	  kevinhaig <https://kevinsspace.ca/contact/>
 * Endorse is distributed under the terms of the GNU GPL.
 */
jQuery(document).ready(function(){
	// Upload a photo within testimonial basic admin.
	var custom_uploader;
	jQuery('#endorse-upload-button').click(function(e) {
		e.preventDefault();
		// If the uploader object has already been created, reopen the dialog.
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}
		// Extend the wp.media object.
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false
		});
		// When a file is selected, grab the URL and set it as the text field's value.
		custom_uploader.on('select', function() {
			attachment = custom_uploader.state().get('selection').first().toJSON();
			jQuery('#endorse-upload-image').val(attachment.url);
		});
		// Open the uploader dialog.
		custom_uploader.open();
	});
});