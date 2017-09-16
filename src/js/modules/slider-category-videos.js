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

    this.initPlugins();
}


SliderCategoryVideos.prototype = {

    /**
     * SliderCategoryVideos.initPlugins
     */
    initPlugins: function() {

        var players = plyr.setup({
            'iconUrl': wp.current_url
        });


        var $slider = $('.js-slider-category-videos');
        
        $slider.find('.js-slider-category-videos-container')

            .on('beforeChange', function(event, slick, currentSlide, nextSlide){
    
                // If the current video is played
                if (players[currentSlide].play) {

                    // Stop it
                    players[currentSlide].pause();
                };
            })

            .slick({
                // arrows: false,
                nextArrow: $slider.find('.js-slider-category-videos-next'),
                prevArrow: $slider.find('.js-slider-category-videos-previous'),
                mobileFirst: true,
                
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