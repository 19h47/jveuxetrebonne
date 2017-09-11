var $ = require('jquery');


var select = require('dom-select');
var classes = require('dom-classes');


/**
 * LoadMore
 */
function LoadMore(element) {
	if (!(this instanceof LoadMore)) {
        	return new LoadMore();
	}
	
	this.element = element;

	if (!this.element) {
		return;
	}
	
	this.container = this.element.querySelector('.js-load-more-container');
	this.button = this.element.querySelector('.js-load-more-button');
	this.filters = document.querySelectorAll('.js-filters-button');
	this.heading = document.querySelector('.js-heading') || null;

	this.tag = this.button.dataset.tag || false;
	this.count = this.button.dataset.count;
	this.posts_per_page = this.button.dataset.postsPerPage || 3;
	this.post_template = this.button.dataset.template || 'tease';
	this.offset = this.button.dataset.offset || 9;
	this.exclude = this.button.dataset.exclude;

	this.update();
	this.setup.events.call(this);

	this.filters && this.filters.forEach(function(el) {

		this.setup.filters.call(this, el);

	}.bind(this));
}


LoadMore.prototype = {

	/**
	 * LoadMore.setupEvents
	 */
	setup: {

		/**
		 * LoadMore.setup.events
		 */
		events: function() {

			this.button.addEventListener('click', function(e) {

				// load more LoadMore with AJAX
		    	this.load()
		        	// then append result to the container
		        	.then(this.append.bind(this))
		        	// finally update things
		        	.done(this.update.bind(this));
	            
	 		}.bind(this));
			
		},


		/**
		 * LoadMore.setup.filters
		 */
		filters: function(el) {
			// console.info('LoadMore.setup.filters');

			el.addEventListener('click', function() {
				
				if (this.tag === el.dataset.tag) {
					return;
				}

				// Remove all `is-active` classes
				this.filters.forEach(function(filter) {
					classes.remove(filter, 'is-active');
				});

				classes.add(el, 'is-active');
				
				this.offset = 0;
				this.tag = el.dataset.tag;
				this.count = el.dataset.count;
				this.posts_per_page = el.dataset.postPerPage;
				this.description = el.dataset.description;

				$(this.heading).find('span').html('Loading');

				this.load()
			    	// then replace result to the container
			    	.then(this.replace.bind(this))
			    	// finally update things
			    	.done(this.update.bind(this));

			}.bind(this));
		}
	},


	/**
	 * LoadMore.load
	 */
	load: function() {

		var data = {
        	action: 'ajax_load_posts',
	        offset: this.offset,
	        exclude: this.exclude
		};

		if (this.tag) {
			data.tag = this.tag;
		}

		if (this.posts_per_page) {
	        data.posts_per_page = this.posts_per_page;
	    }

	    if (this.post_template) {
	    	data.post_template = this.post_template;
	    }

		// lock everything before the request
		this.lock.on.call(this);

		return $.get(window.wp.ajax_url, data);
	},


	/**
	 * LoadMore.replace
	 */
	replace: function(html) {
		if (!html) {
        	return;
    	}
		
    	$(this.heading).find('span').html(this.description);
    	
    	this.container.innerHTML = html;
	},


	/**
	 * LoadMore.append
	 */
	append: function(html) {
		if (!html) {
        	return;
		}

		$(this.container).append(html);
	},


	/**
	 * LoadMore.update
	 */
	update: function() {

		this.offset = this.container.querySelectorAll('.js-load-more-post').length;
				
		this.button.dataset.offset = this.offset;
		this.button.dataset.tag = this.tag;
		this.button.dataset.count = this.count;

		this.button && $(this.button).toggle(this.offset < this.count);
		
		// ensure everything is unlocked
		this.lock.off.call(this);
	},


	/**
	 * LoadMore.lock
	 */
	lock: {

		/**
		 * LoadMore.lock.on
		 */
		on: function() {
			// console.log('LoadMore.lock.on');
			
			// add loading state to ajax container if exists
		    this.container && $(this.container).addClass('is-loading');
			
		},


		/**
		 * LoadMore.lock.off
		 */
		off: function() {
			// console.log('LoadMore.lock.off');
			
			// remove loading state of ajax container if exists
		    this.container && $(this.container).removeClass('is-loading');
		},
	}
};


module.exports = LoadMore;