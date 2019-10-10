/* global $, wp */
const Instafeed = require('instafeed.js');

require('slick-carousel');

export default class Instagram {
	constructor() {
		this.target = document.querySelector('#js-instagram');
	}

	init() {
		if (null === this.target || 0 === this.target.length) {
			return false;
		}

		this.feed = null;
		this.userId = '26283047';
		this.accessToken = '26283047.1677ed0.eefc02ff179746bcb76f99cc08554827';

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
		});
	}

	initPlugins() {
		$(this.target).slick({
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
