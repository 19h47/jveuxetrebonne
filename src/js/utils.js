/**
 * utils
 * @type {obj}
 */
var utils = {

	/**
	 * Get right transitionend event name regarding browser support.
	 * From Modernizr.
	 */
	whichTransitionEvent: function() {
		var transition;
		var el = document.createElement('fakeelement');
		var transitions = {
			'transition': 'transitionend',
			'OTransition': 'oTransitionEnd',
			'MozTransition': 'transitionend',
			'WebkitTransition': 'webkitTransitionEnd'
		};

		for (transition in transitions) {
			if (el.style[transition] !== undefined) {
				return transitions[transition];
			}
		}
	},

	/**
	 * Remove class by prefixof a given DOM element
	 *
	 * @param 	{obj} el 		DOM element
	 * @param 	{str} prefix 	prefix to find and remove in class
	 * @return 	{el}
	 * @see  http://stackoverflow.com/a/28608829/5091221
	 */
	removeClassByPrefix: function(el, prefix) {
	    var regx = new RegExp('(?:^|\\s)' + prefix + '(?!\\S)');
	    el.className = el.className.replace(regx, '');

	    return el;
	},

	/**
	 * ForEach method
	 *
	 * @see  https://toddmotto.com/ditch-the-array-foreach-call-nodelist-hack/
	 */
	forEach: function (array, callback, scope) {
	  	for (var i = 0; i < array.length; i++) {
	    	callback.call(scope, i, array[i]); // passes back stuff we need
	  	}
	}
}

module.exports = utils;
