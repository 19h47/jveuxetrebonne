var select = require('dom-select');
// var TweenMax = require('TweenMax');
// var CustomEase = require('gsap.easing.CustomEase');


/**
 * Config object
 * 
 * global shared variables
 */
var config = {

	body: {
		el: select('body'),
		$: $('body'),
		scroll: {
			left: 0,
			top: 0
		}
	},
	html: select('html'),

	// is
	is: {
		touch: feature.touch,
		first_load: true
	},

	api: wp.api_url + 'wp/v2/',
	nonce: wp.api_nonce,

	// Ease
	// easeInOutQuart: CustomEase.create('easeInOutQuart', '0.77, 0, 0.175, 1'),
	// easeOutSine: CustomEase.create('easeOutSine', '0.39, 0.575, 0.565, 1'),
	// easeOutCirc: CustomEase.create('easeOutCirc', '0.075, 0.82, 0.165, 1')
}

module.exports = config;