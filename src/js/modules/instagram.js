
module.exports = Instagram;

var Instafeed = require('instafeed.js');

function Instagram() {
    if (!(this instanceof Instagram)) {
        return new Instagram();
    }

    var target = document.querySelector('#js-instagram');

    if (target === null || target.length === 0) {
        return;
    }

    this.feed = null;
    this.userId = '26283047';
    this.accessToken = '26283047.1677ed0.98b4ff5d9dd149a9b6d395127fbbd241';
    
    this.setup();
    
    this.feed.run();
}

/**
 * Instagram
 */
Instagram.prototype = {

    setup: function() {

         this.feed = new Instafeed({
            get: 'user',
            target: 'js-instagram',
            userId: this.userId,
            accessToken: this.accessToken,
            sortBy: 'most-recent',
            limit: 6,
            template: wp.template_instagram,
        });
    }
}