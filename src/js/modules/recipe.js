/* global $ */

// import config from 'js/config';
import SliderPost from 'Modules/SliderPost';
import SliderRecipe from 'Modules/SliderRecipe';

export default class Recipe {
	constructor() {
		this.$parent = $('.js-recipes');
	}

	init() {
		if (0 === this.$parent.length || 'undefined' === this.$parent) {
			return;
		}

		this.$buttons = this.$parent.find('.js-recipes-button');
		this.id = this.$buttons[0].dataset.id;
		this.$container = this.$parent.find('.js-recipes-container');


		// this.$buttons && this.$buttons.each((i, el) => {
		// });

		this.event.call(this);

		this.load()
			.then(this.append.bind(this))
			.done(this.update.bind(this));
	}


	event() {
		$('.js-recipes-buttons').on('click', '.js-recipes-button', (e) => {
			this.id = e.currentTarget.dataset.id;

			// Recipe is already load
			if ($(e.currentTarget).hasClass('is-active')) {
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
			$(e.currentTarget).addClass('is-active');

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
		};

		// lock everything before the request
		this.on.call(this);

		return $.get(window.wp.ajax_url, data);
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
		const sliderPost = new SliderPost();
		sliderPost.init();

		const sliderRecipe = new SliderRecipe();
		sliderRecipe.init();

		// ensure everything is unlocked
		this.off.call(this);
	}


	on() {
		// console.log('Recipe.lock.on');

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
