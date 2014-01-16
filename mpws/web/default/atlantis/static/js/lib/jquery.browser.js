define("default/js/lib/jquery.browser", [
    'cmn_jquery'
    /* component implementation */
], function (jQuery) {


	(function( jQuery, window, undefined ) {
	"use strict";
	 
	var matched, browser;
	 
	jQuery.uaMatch = function( ua ) {
	  ua = ua.toLowerCase();
	 
		var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
			/(webkit)[ \/]([\w.]+)/.exec( ua ) ||
			/(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
			/(msie) ([\w.]+)/.exec( ua ) ||
			ua.indexOf("trident") && /(rv) ([\w.]+)/.exec( ua ) ||
			ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
			[];

		var platform_match = /(ipad)/.exec( ua ) ||
			/(iphone)/.exec( ua ) ||
			/(android)/.exec( ua ) ||
			[];
	 
		return {
			browser: match[ 1 ] || "",
			version: match[ 2 ] || "0",
			platform: platform_match[0] || ""
		};
	};
	 
	matched = jQuery.uaMatch( window.navigator.userAgent );
	browser = {};
	 
	if ( matched.browser ) {
		browser[ matched.browser ] = true;
		browser.version = matched.version;
	}

	if ( matched.platform) {
		browser[ matched.platform ] = true
	}
	 
	// Chrome is Webkit, but Webkit is also Safari.
	if ( browser.chrome ) {
		browser.webkit = true;
	} else if ( browser.webkit ) {
		browser.safari = true;
	}

	// IE11 has a new token so we will assign it msie to avoid breaking changes
	if (browser.rv)
	{
		browser.msie = true;
	}
	 
	jQuery.browser = browser;
	 
	})( jQuery, window );

});