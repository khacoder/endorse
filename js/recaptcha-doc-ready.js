/* eslint-disable no-undef */
/**
 * Doc ready script to render multiple reCaptchas.
 *
 * @package   Endorse WordPress Plugin 
 * @copyright Copyright (C) 2019, Kevin Archibald
 * @license   GPLv2 or later http://www.gnu.org/licenses/quick-guide-gplv2.html
 * @author	  kevinhaig <www.kevinsspace.ca/contact/>
 * 
 * Endorse is distributed under the terms of the GNU GPL
 */
var reCaptchaWidgetID1,reCaptchaContentID1;
// eslint-disable-next-line no-unused-vars
var EndorseCaptchaCallback = function() {
	if ( jQuery( '#endorse-widget-captcha-1' ).length > 0 ) {
		var widget_1_sitekey = jQuery( '#endorse-widget-captcha-1' ).data( "sitekey" );
		reCaptchaWidgetID1   = grecaptcha.render( 'endorse-widget-captcha-1', { 'sitekey' : widget_1_sitekey } );
		grecaptcha.reset(reCaptchaWidgetID1);
	}
	if ( jQuery( '#endorse-widget-captcha-2' ).length > 0 ) {
		var widget_2_sitekey = jQuery( '#endorse-widget-captcha-2' ).data( "sitekey" );
		reCaptchaWidgetID2   = grecaptcha.render( 'endorse-widget-captcha-2', { 'sitekey' : widget_2_sitekey } );
	}
	if ( jQuery( '#endorse-widget-captcha-3' ).length > 0 ) {
		var widget_3_sitekey = jQuery( '#endorse-widget-captcha-3' ).data( "sitekey" );
		reCaptchaWidgetID3   = grecaptcha.render( 'endorse-widget-captcha-3', { 'sitekey' : widget_3_sitekey } );
	}
	if ( jQuery( '#endorse-widget-captcha-4' ).length > 0 ) {
		var widget_4_sitekey = jQuery( '#endorse-widget-captcha-4' ).data( "sitekey" );
		reCaptchaWidgetID4   = grecaptcha.render( 'endorse-widget-captcha-4', { 'sitekey' : widget_4_sitekey } );
	}
	if ( jQuery( '#endorse-widget-captcha-5' ).length > 0 ) {
		var widget_5_sitekey = jQuery( '#endorse-widget-captcha-5' ).data( "sitekey" );
		reCaptchaWidgetID5   = grecaptcha.render( 'endorse-widget-captcha-5', { 'sitekey' : widget_5_sitekey } );
	}
	if ( jQuery( '#endorse-content-captcha-1' ).length > 0 ) {
		var content_1_sitekey = jQuery( '#endorse-content-captcha-1' ).data( "sitekey" );
		reCaptchaContentID1   = grecaptcha.render( 'endorse-content-captcha-1', { 'sitekey' : content_1_sitekey } );
		grecaptcha.reset(reCaptchaContentID1);
	}
	if ( jQuery( '#endorse-content-captcha-2' ).length > 0 ) {
		var content_2_sitekey = jQuery( '#endorse-content-captcha-2' ).data( "sitekey" );
		reCaptchaContentID2   = grecaptcha.render( 'endorse-content-captcha-2', { 'sitekey' : content_2_sitekey } );
	}
	if ( jQuery( '#endorse-content-captcha-3' ).length > 0 ) {
		var content_3_sitekey = jQuery( '#endorse-content-captcha-3' ).data( "sitekey" );
		reCaptchaContentID3   = grecaptcha.render( 'endorse-content-captcha-3', { 'sitekey' : content_3_sitekey } );
	}
	if ( jQuery( '#endorse-content-captcha-4' ).length > 0 ) {
		var content_4_sitekey = jQuery( '#endorse-content-captcha-4' ).data( "sitekey" );
		reCaptchaContentID4   = grecaptcha.render( 'endorse-content-captcha-4', { 'sitekey' : content_4_sitekey } );
	}
	if ( jQuery( '#endorse-content-captcha-5' ).length > 0 ) {
		var content_5_sitekey = jQuery( '#endorse-content-captcha-5' ).data( "sitekey" );
		reCaptchaContentID5   = grecaptcha.render( 'endorse-content-captcha-5', { 'sitekey' : content_5_sitekey } );
	}
};