// Modules
var Modules = require('./modules/index');


// create App
window.app = new Modules.App();


// instanciate Sliders
new Modules.SliderIntroduction();
new Modules.SliderPost();
new Modules.SliderRichContent();


// instanciate Slider
new Modules.Instagram();


// instanciate Slider
var loadMoreEl = document.querySelector('.js-load-more');
new Modules.LoadMore(loadMoreEl);


// Instanciate search
new Modules.Search('.js-search');


// create Menu
window.menu = new Modules.Menu();

// create Single Menu
new Modules.SingleMenu();