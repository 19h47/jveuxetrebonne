/* global $, jveb */

import { AbstractBlock } from 'starting-blocks';


export default class Recipes extends AbstractBlock {
	constructor(container) {
		super(container, 'Recipes');
	}

	async init() {
		super.init();

		this.$buttons = $(this.rootElement).find('.js-button');
		this.$container = $(this.rootElement).find('.js-container');

		this.id = this.$buttons[0].dataset.id;
	}


	initEvents() {
		super.initEvents();

		this.$buttons.on('click', event => {
			const $currentTarget = $(event.currentTarget);
			this.id = event.currentTarget.dataset.id;

			// Recipe is already load
			if ($currentTarget.hasClass('is-active')) {
				return;
			}

			// Recipe is currently loading
			if (this.$container.hasClass('is-loading')) {
				return;
			}

			// Remove `is-active` class from buttons collection
			this.$buttons.each((i, el) => {
				$(el).removeClass('is-active');
			});

			// Add `is-active` on current element
			$currentTarget.addClass('is-active');

			// load more LoadMore with AJAX
			this.load()
				// then append result to the container
				.then(this.replace.bind(this))
				// finally update things
				.done(this.update.bind(this));
		});
	}

	load() {
		const data = {
			action: 'ajax_load_recipes',
			id: this.id,
			nonce: jveb.nonce,
		};

		// lock everything before the request
		this.on.call(this);

		return $.get(jveb.ajax_url, data);
	}


	/**
	 * Recipe.replace
	 */
	replace(html) {
		if (!html) {
			return;
		}

		$(this.$container).html(html);
	}

	append(html) {
		if (!html) {
			return;
		}

		$(this.$container).append(html);
	}


	update() {
		// ensure everything is unlocked
		this.off.call(this);
	}


	on() {
		// add loading state to ajax container if exists
		if (this.$container) {
			$(this.$container).addClass('is-loading');
		}
	}


	off() {
		// Remove loading state of ajax container if exists
		if (this.$container) {
			$(this.$container).removeClass('is-loading');
		}
	}
}
