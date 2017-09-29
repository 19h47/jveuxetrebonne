var $ = require('jquery');
var plyr = require('plyr');

require('slick-carousel');


/**
 * Slider category videos
 */
function SliderCategoryVideos() {
    if (!(this instanceof SliderCategoryVideos)) {
        return new SliderCategoryVideos();
    }

    this.$slider = null;
    this.players = null;

    this.initSlick();
    this.initPlyr();
}


SliderCategoryVideos.prototype = {

    /**
     * SliderCategoryVideos.initPlyr
     */
    initPlyr: function() {

        this.players = plyr.setup({
            'iconUrl': wp.current_url
        });

        var i = 1;
        this.players && this.players.forEach(function (instance) {
            instance.on('ready', function (event) {
                instance.getContainer().setAttribute('id', 'plyId-' + i);
                instance.plyId = 'plyr-' + i;
                // console.log(instance.pid);
                i++;
            });
            instance.on('play', function (event) {
                var currentPid = instance.plyId;
                players.forEach(function (instance) {
                    if (currentPid != instance.plyId) {
                        instance.pause();
                    }
                });
            });
        });
    },


    /**
     * SliderCategoryVideos.initPlyr
     */
    initSlick: function() {

        this.$slider = $('.js-slider-category-videos');
        
        this.$slider.find('.js-slider-category-videos-container')

            .on('beforeChange', $.proxy(function(event, slick, currentSlide, nextSlide){
    
                this.players.forEach(function(player) { 
                    player.pause();
                });

            }, this))

            .slick({
                // arrows: false,
                nextArrow: this.$slider.find('.js-slider-category-videos-next'),
                prevArrow: this.$slider.find('.js-slider-category-videos-previous'),
                mobileFirst: true,
                accessibility: true,
                // slidesToShow: 1,
                // infinite: true,
                // initialSlide: 1,
                
                responsive: [{
                    breakpoint: 992,
                    settings: {
                        centerMode: true,
                        centerPadding: ( 297 * 100 ) / 1440 + '%',
                    }
                }]
            });
    }
}

module.exports = SliderCategoryVideos;