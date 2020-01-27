/* global $, feature, jveb */

/**
 * Config object
 *
 * global shared variables
 */
export const elements = {
	body: {
		el: document.body,
		$: $('body'),
	},
	html: document.documentElement,
};

export const scroll = {
	left: 0,
	top: 0,
};

export const states = {
	is: {
		touch: feature.touch,
		first_load: true,
	},
};

export const api = `${jveb.api_url}wp/v2/`;
export const nonce = jveb.api_nonce;

export const breakpoints = {
	xs: 0,
	sm: 754,
	md: 992,
	lg: 1200,
	hd: 1400,
};
