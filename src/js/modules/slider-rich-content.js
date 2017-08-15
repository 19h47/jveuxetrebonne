var $ = require('jquery');


require('slick-carousel');
require('sticky-kit/dist/sticky-kit.js');

/**
 * Slider rich content
 */
function SliderRichContent() {
    if (!(this instanceof SliderRichContent)) {
        return new SliderRichContent();
    }

    this.initPlugins();
}


SliderRichContent.prototype = {

    /**
     * SliderRichContent.initPlugins
     */
    initPlugins: function() {
         
        $('.js-slider-rich-content').stick_in_parent({
            parent: '.js-sticky-container',
            bottoming: true
        });

        // Select all sticky sliders on page
        var $sliders = $('.js-slider-rich-content');
        var $content = $('.js-single-content');
        var $links = $content.find('.js-rich-content-link');

        $sliders.each($.proxy(function(i, e){
            var $slider = $(e);

            var slider = {
                progressbar: $slider.find('.js-slider-rich-content-progressbar'),
                count: $slider.find('.js-slider-rich-content-count'),
                next: $slider.find('.js-slider-rich-content-next'),
                previous: $slider.find('.js-slider-rich-content-previous')
            }


            $slider
                .find('.js-slider-rich-content-container')

                .on('init', function(event, slick) {
                    
                    $links.each(function(i, link) {
                        var $link = $(link);
                        var id = $link[0].dataset.id;

                        $link[0].addEventListener('mouseenter', function() {

                            var index = $slider.find('[data-id=' + id + ']').index();

                            slick.goTo(parseInt(index));
                        });

                    });
                })

                .on('init afterChange', function(event, slick) {   
                    var count = slick.slideCount;
                    var current = slick.currentSlide;
                    
                    var calc = ( current / ( count - 1 ) ) * 100;

                    slider.progressbar
                        .css('background-size', calc + '% 100%')
                        .attr('aria-valuenow', calc );

                    slider.count[0].dataset.count = current + 1;
                })


                // .on('init', function() {})

                .slick({
                    // arrows: false,
                    useCSS: false,
                    adaptiveHeight: false,
                    fade: true,
                    nextArrow: slider.next,
                    prevArrow: slider.previous,
                    accessibility: false
                });


        }, this));

    }
}

module.exports = SliderRichContent;