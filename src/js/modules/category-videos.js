var $ = require('jquery');
var plyr = require('plyr');


/**
 * CategoryVideos
 */
function CategoryVideos() {
	if (!(this instanceof CategoryVideos)) {
        	return new CategoryVideos();
	}


	plyr.setup();

}


module.exports = CategoryVideos;