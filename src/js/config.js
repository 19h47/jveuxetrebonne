/* global $, feature, jveb */

/**
 * Config object
 *
 * global shared variables
 */
export default {
	body: {
		el: document.body,
		$: $('body'),
		scroll: {
			left: 0,
			top: 0,
		},
	},
	scroll: {
		left: 0,
		top: 0,
	},
	html: document.documentElement,
	is: {
		touch: feature.touch,
		first_load: true,
	},
	api: `${jveb.api_url}wp/v2/`,
	nonce: jveb.api_nonce,
	// Ease
	// easeInOutQuart: CustomEase.create('easeInOutQuart', '0.77, 0, 0.175, 1'),
	// easeOutSine: CustomEase.create('easeOutSine', '0.39, 0.575, 0.565, 1'),
	// easeOutCirc: CustomEase.create('easeOutCirc', '0.075, 0.82, 0.165, 1')
};
