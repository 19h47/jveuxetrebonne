/* global $ */


import Mustache from 'mustache';
import config from 'js/config';
import { sprintf } from 'sprintf-js';

export default class Search {
	constructor() {
		this.$element = $('.js-search');
	}

	init() {
		if (this.$element && 0 === this.$element) {
			return false;
		}

		this.is_open = config.body.$.hasClass('search--is-open');

		this.$input = this.$element.find('.js-search-input');
		this.$suggest = this.$element.find('.js-search-suggest');
		this.$informations = this.$element.find('.js-search-informations');
		this.$button = $('.js-search-button');
		this.total = 0;

		this.success = this.$informations[0].dataset.success;
		this.error = this.$informations[0].dataset.error;

		// Add error message
		this.$informations.find('span:eq( 2 )').html(this.error);

		this.response = null;

		this.update();
		this.initEvents();
		this.initPlugins();

		return true;
	}


	initEvents() {
		this.$input.on('change keyup paste', () => {
			this.update();
		});

		config.body.$.on('open.search', () => {
			// Weird behavior on Chrome
			setTimeout(() => {
				this.$input.focus();
			}, 600);
		});


		$(document)
			.on('click.search', '.js-search-button', this.toggle.bind(this))

			.on('keydown.search', (e) => {
				if (27 === e.which) {
					this.close();
				}
			});
	}


	initPlugins() {
		this.$input.autocomplete({
			messages: {
				noResults: '',
				// results: function() {}
			},
			minLength: 1,
			delay: 600,
			source: $.proxy((request, response) => {
				this.lock();

				// Easter egg
				if ('Les Indiens' === request.term) {
					console.log('%cLes Indiens c\'est le studio qui a réalisé le design du site ✌️', 'background-color:#000;color:#fff;padding:0.5em 1em;');
				}

				if ('19h47' === request.term) {
					console.log('%c19h47 c\'est le developer front end du site ✌️', 'background-color:#000;color:#fff;padding:0.5em 1em;');
				}

				$.ajax({
					url: window.wp.search_api,
					data: {
						term: request.term,
					},
					dataType: 'json',
					success(data) {
						const transformed = $.map(
							data,
							(element) => ({
								title: element.post_title,
								date: element.post_date_format,
								categories: element.post_categories.map((el) => el.name).join(', '),
								content: element.post_content,
							}),
						);

						if (0 === transformed.length) {
							return;
						}

						response(transformed);
					},
				})
					.then(this.construct.bind(this))
					.then(this.append.bind(this))
					.done(this.update.bind(this));
			}, this),
		});
	}


	/**
	 * Search.toggle
	 */
	toggle() {
		if (this.is_open) {
			return this.close();
		}

		return this.open();
	}


	/**
	 * Search.close
	 */
	close() {
		if (!this.is_open) {
			return;
		}

		this.is_open = false;

		this.total = 0;
		this.$input.val('');
		this.update();

		config.body.$
			.removeClass('search--is-open')
			.trigger('close.search');

		window.app.constructor.enableScroll();
	}


	open() {
		if (this.is_open) {
			return;
		}

		this.is_open = true;

		this.update();

		window.app.constructor.disableScroll();

		config.body.$
			.addClass('search--is-open')
			.trigger('open.search');
	}


	/**
	 * Search.lock
	 */
	lock() {
		// console.info('Search.lock');

		this.$element.addClass('is-loading');

		// Just in case
		this.$element.removeClass('not-found');
	}


	/**
	 * Search.unlock
	 */
	unlock() {
		// console.info('Search.unlock');

		this.$element.removeClass('is-loading');
	}


	/**
	 * Search.error
	 */
	// error: function(data) {
	//	console.info('Search.error');
	// },


	/**
	 * Search.append
	 *
	 * @param object suggest
	 */
	construct(suggests) {
		if (null === suggests || 0 === suggests.length) {
			this.$element.addClass('not-found');
			this.reset();

			return false;
		}

		// Update total
		this.total = suggests.length;

		const template = $('#suggest').html();
		let output = '';
		let i = 0;

		Mustache.parse(template);

		for (i; i < this.total; i += 1) {
			output += Mustache.render(
				template, {
					// Stock each value to render in template
					title: suggests[i].post_title,
					date: suggests[i].post_date_format,
					link: suggests[i].link,
					categories: suggests[i].post_categories.map((el) => el.name).join(', '),
					thumbnail: suggests[i].post_thumbnail_url || '',
				},
			);
		}

		return output;
	}


	/**
	 * Search.append
	 *
	 * @param string html
	 */
	append(html) {
		// console.info('Search.append');

		if (!html) {
			return;
		}

		this.$suggest.html(html);
	}


	reset() {
		// console.info('Search.reset');

		this.$suggest.html('');
		this.total = 0;

		// Update attributes for CSS
		$(this.$informations)
			.attr('data-total', this.total)
			.data('total', this.total);


		this.unlock();
	}


	/**
	 * Search.update
	 */
	update() {
		// console.info('Search.update');

		// this.$informations && $(this.$informations).toggle(this.$input.val().length > 0);

		// Update attributes for CSS
		$(this.$informations)
			.attr('data-total', this.total)
			.data('total', this.total);

		if (1 === this.total) {
			// console.log(this.totalString);
			this.$informations.find('span:eq( 1 )').html(this.success.replace(/s/g, ''));
		}

		if (1 < this.total) {
			// console.log(this.totalString);
			this.$informations.find('span:eq( 1 )').html(sprintf(this.success, 's', 'ent'));
		}

		this.unlock();

		// console.log(this.$input.val().length);
		if (0 === this.$input.val().length) {
			this.$element.removeClass('not-found');
			this.reset();
		}
	}
}
