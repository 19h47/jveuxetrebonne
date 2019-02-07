var $ = require('jquery');

/**
 * Print
 */
function Print() {
	if (!(this instanceof Print)) {
        	return new Print();
	}

	this.$element = $('.js-print');

	if (this.$element.length === 0) {
		return;
	}

	this.initEvents();
}

Print.prototype = {
	initEvents: function() {
		this.$element.on('click', function() {
			 window.print();
		});
	}
};


module.exports = Print;
