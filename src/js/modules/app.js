module.exports = App;

var $ = require('jquery');
var config = require('../config');
var css = require('dom-css');
var classes = require('dom-classes');


/**
 * App
 */
function App() {
	if (!(this instanceof App)) {
		return new App();
	}

	console.log('%c🔥 Les Indiens x 19h47 🔥', 'background-color:#000;color:#fff;padding:0.5em 1em;' );

}


App.prototype = {
	
	/**
	 * App.disableScroll
	 */
	disableScroll: function() {
		// console.log('App.disableScroll');
		
		// lock scroll position, but retain settings for later
		// http://stackoverflow.com/a/3656618
		config.body.scroll.left = self.pageXOffset || document.documentElement.scrollLeft  || document.body.scrollLeft;
		config.body.scroll.top = self.pageYOffset || document.documentElement.scrollTop  || document.body.scrollTop;
		
		css(config.html, 'overflow', 'hidden');

		this.resetScroll(config.body.scroll.left, config.body.scroll.top);

		// disable scroll on touch devices as well
		if (config.is.touch) {
			
			document.addEventListener('touchmove.app', function(e) {
				e.preventDefault();
			});
		}
	},


	/**
	 * App.enableScroll
	 * 
	 * @param  position
	 */
	enableScroll: function(position) {
		// console.log('App.enableScroll');

		if (typeof position === 'undefined') {
			position = config.body.scroll.top;
		}

		var resume_scroll = true;
		if (typeof position === 'boolean' && position === false) {
			resume_scroll = false;
		}

		// unlock scroll position
		// http://stackoverflow.com/a/3656618
		css(config.html, 'overflow', 'visible');

		// resume scroll position if possible
		if (resume_scroll) {
			this.resetScroll(config.body.scroll.left, position);
		}

		// enable scroll on touch devices as well
		if (config.is.touch) {
			$(document).off('touchmove.app');
		}
	},


	/**
	 * App.resetScroll
	 * 
	 * @param  position_x
	 * @param  position_y
	 */
	resetScroll: function(position_x, position_y) {

		if (typeof position_x !== 'undefined') {
			config.body.scroll.left = parseInt(position_x);
		}

		if (typeof position_y !== 'undefined') {
			config.body.scroll.top = parseInt(position_y);
		}

		window.scrollTo(config.body.scroll.left, config.body.scroll.top);
	},


	/**
	 * App.addState
	 *
	 * @param 	state
	 * @author 	Julien Vasseur julien@poigneedemainvirile.com
	 */
	addState: function(state) {

		config.body.$.addClass(state);
	},


	/**
	 * App.removeState
	 * 
	 * @param 	state
	 * @author 	Julien Vasseur julien@poigneedemainvirile.com
	 */
	removeState: function(state) {
		var deferred = new $.Deferred();

		config.body.$.removeClass(state);

		deferred.promise();

		return deferred.resolve();	
	},


	/**
	 * App.scrollTo
	 */
	scrollTo: function() {
		var elements = document.querySelectorAll('.js-scroll-to');


		elements.forEach(function(element) {
			// console.dir(element);
			var offset = element.dataset.offset;

			switch (offset) {
				case 'bottom':
					offset = document.body.scrollHeight + element.scrollHeight;

				break;
			}

			element.addEventListener('click', function(){
				// console.log(offset);
				// window.scrollTo(0, offset);

				config.body.$.animate({
				        scrollTop: $(document).height() - $(window).height()
			    }, 200);
			});
			
		});
	}
};