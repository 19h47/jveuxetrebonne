/* global $ */

import config from 'js/config';

export default class SingleMenu {
	constructor() {
		this.element = document.querySelector('.js-single-menu');
	}

	init() {
		if (null === this.element || 0 === this.element.length) {
			return;
		}

		this.is_open = config.body.$.hasClass('single-menu--is-open');
		this.items = this.element.querySelectorAll('.js-single-menu-item');
		this.thumbnails = this.element.querySelectorAll('.js-single-menu-thumbnail');

		this.setupEvents();
	}


	setupEvents() {
		$(document)
			.on('click.single-menu', '.js-single-menu-button', this.toggle.bind(this))
			.on('keydown.single-menu', (e) => {
				if (27 === e.which) {
					this.close();
				}
			});

		this.items.forEach((el) => {
			const target = el.children[0];
			const { id } = target.dataset;

			$(el).on({
				mouseenter: $.proxy(() => {
					// console.log('mouseenter');

					this.element.classList.add('is-hover');

					this.thumbnails.forEach((thumbnail) => {
						if (thumbnail.dataset.id === id) {
							thumbnail.classList.add('is-active');
						}
					});
				}, this),
				mouseleave: $.proxy(() => {
					// console.log('mouseout');

					this.element.classList.remove('is-hover');

					this.thumbnails.forEach((thumbnail) => {
						thumbnail.classList.remove('is-active');
					});
				}, this),
			});
		});
	}


	toggle() {
		if (this.is_open) {
			return this.close();
		}

		return this.open();
	}


	close() {
		if (!this.is_open) {
			return;
		}

		this.is_open = false;

		config.body.$
			.removeClass('single-menu--is-open')
			.trigger('close.single-menu');

		window.app.constructor.enableScroll();
	}


	open() {
		if (this.is_open) {
			return;
		}

		this.is_open = true;

		window.app.constructor.disableScroll();

		config.body.$
			.addClass('single-menu--is-open')
			.trigger('open.single-menu');
	}
}
