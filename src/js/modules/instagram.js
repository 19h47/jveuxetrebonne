
module.exports = Instagram;

var Instafeed = require('instafeed.js');
require('slick-carousel');

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
    this.accessToken = '26283047.1677ed0.eefc02ff179746bcb76f99cc08554827';
    
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
            after: this.initPlugins
        });

    },

    initPlugins: function() {
        console.info('Instagram.initPlugins');

        $('.js-slider-instagram').find('#js-instagram').slick({
            arrows: false,
            centerMode: true,
            centerPadding: ( 138 * 100 ) / 486 + '%',
            mobileFirst: true,
            
            responsive: [{
                breakpoint: 992,
                settings: "unslick"
            }]
        });
    }
}