/* global $ */
import config from 'js/config';

export default class Menu {
	constructor() {
		this.is_open = config.body.$.hasClass('menu--is-open');

		this.setupEvents();
	}

	setupEvents() {
		$(document)
			.on('click.menu', '.js-toggle-menu-button', this.toggle.bind(this))
			.on('keydown.menu', (e) => {
				if (27 === e.which) {
					this.close();
				}
			});
	}


	/**
	 * Menu.toggle
	 */
	toggle() {
		if (this.is_open) {
			return this.close();
		}

		return this.open();
	}


	/**
	 * Menu.open
	 */
	open() {
		if (this.is_open) {
			return;
		}

		this.is_open = true;

		config.body.$.stop().animate({ scrollTop: 0 }, 300, 'linear', () => {
			config.body.$
				.addClass('menu--is-open')
				.trigger('open.menu');

			// When menu is open, disable scroll
			window.app.constructor.disableScroll();
		});
	}


	/**
	 * Menu.close
	 */
	close() {
		if (!this.is_open) {
			return;
		}

		this.is_open = false;

		config.body.$
			.removeClass('menu--is-open')
			.trigger('close.menu');

		// When menu is closed, enable scroll
		window.app.constructor.enableScroll();
	}
}
