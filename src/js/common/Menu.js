/* global $ */
import { elements } from 'js/config';
import { enableScroll, disableScroll } from 'Utils/scroll';

export default class Menu {
	constructor() {
		this.is_open = elements.body.$.hasClass('menu--is-open');
	}

	init() {
		this.setupEvents();
	}

	setupEvents() {
		$(document)
			.on('click.menu', '.js-toggle-menu-button', this.toggle.bind(this))
			.on('keydown.menu', event => {
				if (27 === event.which) {
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

		elements.body.$.stop().animate({ scrollTop: 0 }, 300, 'linear', () => {
			elements.body.$
				.addClass('menu--is-open')
				.trigger('open.menu');

			// When menu is open, disable scroll
			disableScroll();
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

		elements.body.$
			.removeClass('menu--is-open')
			.trigger('close.menu');

		// When menu is closed, enable scroll
		enableScroll();
	}
}
