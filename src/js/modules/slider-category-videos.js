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

        var i = 1;
        players && players.forEach(function (instance) {
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


        var $slider = $('.js-slider-category-videos');
        
        $slider.find('.js-slider-category-videos-container')

            .on('beforeChange', function(event, slick, currentSlide, nextSlide){
    
                players.forEach(function(player) { 
                    player.pause();
                });
            })

            .slick({
                // arrows: false,
                nextArrow: $slider.find('.js-slider-category-videos-next'),
                prevArrow: $slider.find('.js-slider-category-videos-previous'),
                mobileFirst: true,
                slidesToShow: 1,
                infinite: false,
                initialSlide: 1,
                
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