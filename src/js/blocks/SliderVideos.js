/* global $ */

import Plyr from 'plyr';
import { AbstractBlock } from 'starting-blocks';

import 'slick-carousel';

export default class SliderVideos extends AbstractBlock {
	constructor(container) {
		super(container, 'SliderVideos');

		this.players = null;
	}

	async init() {
		super.init();

		// this.initPlyr();

		// if (this.players) {
		this.initSlick();
		// }
	}


	initPlyr() {
		const $players = this.rootElement.querySelectorAll('.js-player');

		if (0 === $players.length) {
			return false;
		}

		this.players = new Plyr.setup($players); // eslint-disable-line new-cap

		let i = 1;

		this.players.forEach((instance) => {
			instance.on('ready', () => {
				const current = instance;

				current.elements.container.setAttribute('id', `plyId-${i}`);
				current.plyId = `plyr-${i}`;

				i += 1;
			});
			instance.on('play', () => {
				const currentPid = instance.plyId;
				this.players.forEach((player) => {
					if (currentPid !== player.plyId) {
						player.pause();
					}
				});
			});
		});

		return true;
	}


	initSlick() {
		const $container = $(this.rootElement);

		$container.find('.js-container')
			// .on('beforeChange', () => {
			// 	this.players.forEach((player) => {
			// 		player.pause();
			// 	});
			// })
			.slick({
				nextArrow: $container.find('.js-next'),
				prevArrow: $container.find('.js-previous'),
				mobileFirst: true,
				accessibility: true,
				centerMode: true,
				centerPadding: 0,
				responsive: [{
					breakpoint: 992,
					settings: {
						variableWidth: true,
						// centerPadding: `${(285 * 100) / 1440}%`,
					},
				}],
			});
	}
}
