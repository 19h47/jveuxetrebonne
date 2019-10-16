/* global $, wp */
const Instafeed = require('instafeed.js');

require('slick-carousel');

export default class Instagram {
	constructor() {
		this.rootElement = document.querySelector('.js-instagram');
	}

	init() {
		if (null === this.rootElement || 0 === this.rootElement.length) {
			return false;
		}

		this.feed = null;
		this.userId = '26283047';
		this.accessToken = '26283047.1677ed0.7d7129e0c46e4c60a261f0ab4a8536fd';

		this.setup();

		return this.feed.run();
	}

	setup() {
		this.feed = new Instafeed({
			get: 'user',
			target: 'js-instagram',
			userId: this.userId,
			accessToken: this.accessToken,
			sortBy: 'most-recent',
			limit: 6,
			template: wp.template_instagram,
			after: this.initPlugins,
			error: () => {
				this.rootElement.style.setProperty('display', 'none');
			},
		});
	}

	initPlugins() {
		$('.js-instagram-container', this.rootElement).slick({
			arrows: false,
			centerMode: true,
			centerPadding: `${(138 * 100) / 486}%`,
			mobileFirst: true,
			responsive: [{
				breakpoint: 992,
				settings: 'unslick',
			}],
		});
	}
}
