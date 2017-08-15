var $ = require('jquery');
var config = require('../config');

function Menu() {
    if (!(this instanceof Menu)) {
        return new Menu();
    }

    this.is_open = config.body.$.hasClass('menu--is-open');

    this.setupEvents();
}


Menu.prototype = {

    /**
     * Menu.setupEvents
     */
    setupEvents: function() {
        $(document)
            .on('click.menu', '.js-toggle-menu-button', this.toggle.bind(this))
            .on('keydown.menu', $.proxy(function(e) {
                e.which === 27 && this.close();
            }, this));
    },


    /**
     * Menu.toggle
     */
    toggle: function() {
        if (this.is_open) {
            return this.close();
        }

        return this.open();
    },


    /**
     * Menu.open
     */
    open: function() {
        if (this.is_open) {
            return;
        }

        this.is_open = true;

        config.body.$.stop().animate({scrollTop: 0}, 300, 'linear', function() { 

            config.body.$
                .addClass('menu--is-open')
                .trigger('open.menu');

            // When menu is open, disable scroll
            window.app.disableScroll();
        });
    },


    /**
     * Menu.close
     */
    close: function() {
        if (!this.is_open) {
            return;
        }

        this.is_open = false;

        config.body.$
            .removeClass('menu--is-open')
            .trigger('close.menu');

        // When menu is closed, enable scroll
        window.app.enableScroll();
    }
}

module.exports = Menu;