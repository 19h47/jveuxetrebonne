/* global $, jveb */

import { AbstractBlock } from 'starting-blocks';

const Instafeed = require('instafeed.js');

require('slick-carousel');

export default class InstagramPosts extends AbstractBlock {
	constructor(container) {
		super(container, 'InstagramPosts');
	}

	async init() {
		super.init();

		this.feed = null;
		this.userId = '26283047';
		this.accessToken = '26283047.1677ed0.7d7129e0c46e4c60a261f0ab4a8536fd';
	}

	initEvents() {
		super.initEvents();

		this.feed = new Instafeed({
			get: 'user',
			target: 'js-instagram',
			userId: this.userId,
			accessToken: this.accessToken,
			sortBy: 'most-recent',
			limit: 6,
			template: jveb.template_instagram,
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

	onPageReady() {
		super.onPageReady();

		return this.feed.run();
	}
}
