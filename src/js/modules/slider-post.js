var $ = require('jquery');
require('slick-carousel');


/**
 * Slider post
 */
function SliderPost() {
    if (!(this instanceof SliderPost)) {
        return new SliderPost();
    }

    this.initPlugins();
}


SliderPost.prototype = {

    /**
     * SliderPost.initPlugins
     */
    initPlugins: function() {
        // Select all sticky sliders on page
        var $sliders = $('.js-slider-post');

        $sliders.each($.proxy(function(i, e){
            var $slider = $(e);

            var slider = {
                progressbar: $slider.find('.js-slider-post-progressbar'),
                count: $slider.find('.js-slider-post-count'),
                next: $slider.find('.js-slider-post-next'),
                previous: $slider.find('.js-slider-post-previous')
            }
            
            // console.log($slider);
            
            $slider
                .find('.js-slider-post-container')

                .on('init afterChange', function(event, slick) {   
                    var count = slick.slideCount;
                    var current = slick.currentSlide;
                    
                    var calc = ( current / ( count - 1 ) ) * 100;

                    slider.progressbar
                        .css('background-size', calc + '% 100%')
                        .attr('aria-valuenow', calc );

                    slider.count[0].dataset.count = current + 1;
                    
                })

                .slick({
                    accessibility: false,
                    // arrows: false,
                    useCSS: false,
                    fade: true,
                    nextArrow: slider.next,
                    prevArrow: slider.previous
                });


        }, this));
    }
}

module.exports = SliderPost;