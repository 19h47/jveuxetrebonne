// Modules
var Modules = require('./modules/index');


// create App
window.app = new Modules.App();

// Instanciate Recipe
new Modules.Recipe();

// instanciate Sliders
new Modules.SliderIntroduction();
new Modules.SliderPost();
new Modules.SliderRichContent();
new Modules.SliderCategoryVideos();
new Modules.SliderRelationshipPosts();

new Modules.CategoryVideos();

new Modules.ImageContent();

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