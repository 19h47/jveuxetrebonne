/* global $ */

import config from 'js/config';


export default class App {
	constructor() {
		console.log('%c🔥 Les Indiens x 19h47 🔥', 'background-color:#000;color:#fff;padding:0.5em 1em;');
	}


	static disableScroll() {
		// lock scroll position, but retain settings for later
		// http://stackoverflow.com/a/3656618
		config.body.scroll.left = self.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft; // eslint-disable-line no-restricted-globals, max-len
		config.body.scroll.top = self.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop; // eslint-disable-line no-restricted-globals, max-len

		config.html.style.setProperty('overflow', 'hidden');

		App.resetScroll(config.body.scroll.left, config.body.scroll.top);

		// disable scroll on touch devices as well
		if (config.is.touch) {
			document.addEventListener('touchmove.app', (e) => {
				e.preventDefault();
			});
		}
	}


	/**
	 * App.enableScroll
	 *
	 * @param  position
	 */
	static enableScroll(position) {
		let current = position;

		if ('undefined' === typeof position) {
			current = config.body.scroll.top;
		}

		let resumeScroll = true;
		if ('boolean' === typeof current && false === current) {
			resumeScroll = false;
		}

		// unlock scroll position
		// http://stackoverflow.com/a/3656618
		config.html.style.setProperty('overflow', 'visible');

		// resume scroll position if possible
		if (resumeScroll) {
			App.resetScroll(config.body.scroll.left, current);
		}

		// enable scroll on touch devices as well
		if (config.is.touch) {
			$(document).off('touchmove.app');
		}
	}


	/**
	 * App.resetScroll
	 *
	 * @param  x
	 * @param  y
	 */
	static resetScroll(x, y) {
		if ('undefined' !== typeof x) {
			config.body.scroll.left = parseInt(x, 10);
		}

		if ('undefined' !== typeof y) {
			config.body.scroll.top = parseInt(y, 10);
		}

		window.scrollTo(config.body.scroll.left, config.body.scroll.top);
	}


	static scrollTo() {
		const elements = document.querySelectorAll('.js-scroll-to');

		elements.forEach((element) => {
			let { offset } = element.dataset;

			switch (offset) {
				case 'bottom':
					offset = document.body.scrollHeight;
					break;

				case 'top':
					offset = 0;
					break;
				default:
					break;
			}

			element.addEventListener('click', () => {
				$('html, body').animate({
					scrollTop: offset,
				}, 200);
			});
		});
	}
}
