var $ = require('jquery');


var select = require('dom-select');
var Mustache = require('mustache');
var config = require('../config');
var sprintf = require('sprintf-js').sprintf;
var transitionEvent = require('../utils').whichTransitionEvent;


/**
 * Search
 */
function Search(element) {
	if (!(this instanceof Search)) {
        	return new Search();
	}

	if (!element && element != 0) {
		return;
	}

	this.is_open = config.body.$.hasClass('search--is-open');

	this.$element = $(element);
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
	this.init.events.call(this);
	this.init.plugins.call(this);
}


Search.prototype = {

	/**
	 * Search.init
	 */
	init: {

		/**
		 * Search.init.events
		 */
		events: function() {
			

			this.$input.on('change keyup paste', (function (event) {
							
				this.update();

			}).bind(this));


			config.body.$.on('open.search', $.proxy(function() {
				
				// Weird behavior on Chrome
				setTimeout($.proxy(function(){
					this.$input.focus();
				},this), 600);
			
			}, this));


			$(document)
				.on('click.search', '.js-search-button', this.toggle.bind(this))

				.on('keydown.search', $.proxy(function(e) {

	                e.which === 27 && this.close();

	            }, this));
		},


		/**
		 * Search.init.plugins
		 */
		plugins: function() {

			this.$input.autocomplete({
				
				minLength: 1,
				delay: 600,
				source: $.proxy(function(request, response) {
					
					this.lock();

					// Easter egg
					if (request.term === 'Les Indiens') {
						console.log('%cLes Indiens c\'est le studio qui a réalisé le design du site ✌️', 'background-color:#000;color:#fff;padding:0.5em 1em;');
					}

					if (request.term === '19h47') {
						console.log('%c19h47 c\'est le developer front end du site ✌️', 'background-color:#000;color:#fff;padding:0.5em 1em;');
					}

					$.ajax({
				        url: window.wp.search_api,
				        data: {
				        	term: request.term
				       	},
				        dataType: 'json',
				        success: $.proxy(function( data ) {

				        	// if (data === null || data.length === 0) {
				        	// 	return;
				        	// }


				        	var transformed = $.map( 
				        		data, 
				            	function(el) {
				                	return {
				                	    title: el.post_title,
				                	    date: el.post_date_format,
				                	    categories: el.post_categories.map(function(el){ return el.name; }).join(', '),
				                	    content: el.post_content
				                	};
			            		}
			            	);


				            if (transformed.length === 0) {
				            	return;
				            } 
			            	
			            	response(transformed);

				        },this)

				        // error: function(jqXHR){ 
				        // 	console.log(jqXHR);
				       	// }

				    })
				    .then(this.construct.bind(this))
        			.then(this.append.bind(this))
        			.done(this.update.bind(this));

				}, this),
			});
		}
	},


	/**
	 * Search.toggle
	 */
	toggle: function() {
	    if (this.is_open) {
	        return this.close();
	    }

	    return this.open();
	},


	/**
	 * Search.close
	 */
	close: function() {
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

		window.app.enableScroll();
	},

	/**
	 * Search.open
	 */
	open: function() {
		if (this.is_open) {
		    return;
		}

		this.is_open = true;

		this.update();

		window.app.disableScroll();
		
		config.body.$
            .addClass('search--is-open')
            .trigger('open.search');		
	},


	/**
	 * Search.lock
	 */
	lock: function() {
		// console.info('Search.lock');

		this.$element.addClass('is-loading');

		// Just in case
		this.$element.removeClass('not-found');
	},


	/**
	 * Search.unlock
	 */
	unlock: function() {
		// console.info('Search.unlock');

		this.$element.removeClass('is-loading');
	},


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
	construct: function(suggests) {
		// console.info('Search.construct');

		if (suggests == null || suggests.length === 0) {
			
			this.$element.addClass('not-found');
			this.reset()

			return;
		}


		// Update total
		this.total = suggests.length;
		
		var template = $('#suggest').html();
		var output = '';
		var i = 0;

		Mustache.parse(template);

		for (i; i < this.total; i++) {

			output += Mustache.render(
				template, {
					// Stock each value to render in template
					title: suggests[i].post_title,
					date: suggests[i].post_date_format,
					link: suggests[i].link,
					categories: suggests[i].post_categories.map(function(el){ return el.name; }).join(', '),
					thumbnail: suggests[i].post_thumbnail_url || ''
				}
			);
		}

		return output;
	},


	/**
	 * Search.append
	 *
	 * @param string html
	 */
	append: function(html) {
		// console.info('Search.append');

		if (!html) {
			return;
		}

		this.$suggest.html(html);
	},



	/**
	 * Search.reset
	 */
	reset: function() {
		// console.info('Search.reset');

		this.$suggest.html('');
		this.total = 0;

		// Update attributes for CSS
		$(this.$informations)
			.attr('data-total', this.total)
			.data('total', this.total);


		this.unlock();
	},


	/**
	 * Search.update
	 */
	update: function() {
		// console.info('Search.update');

		// this.$informations && $(this.$informations).toggle(this.$input.val().length > 0);

		// Update attributes for CSS
		$(this.$informations)
			.attr('data-total', this.total)
			.data('total', this.total);

		if (this.total == 1) {
			// console.log(this.totalString);
			this.$informations.find('span:eq( 1 )').html(this.success.replace(/\%s/g, ''));
		}

		if (this.total > 1) {
			// console.log(this.totalString);
			this.$informations.find('span:eq( 1 )').html(sprintf(this.success, 's', 'ent'));
		}

		this.unlock();

		// console.log(this.$input.val().length);
		if(this.$input.val().length === 0) {
			
			this.$element.removeClass('not-found');	
			this.reset();
		}

	}
};


module.exports = Search;