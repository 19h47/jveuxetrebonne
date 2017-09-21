// Modules
var Modules = require('./modules/index');
var svg4everybody = require('svg4everybody');

// SVG for everybody
svg4everybody();


// create App
window.app = new Modules.App();

// Init scrollTop
window.app.scrollTo();

new Modules.Print();

// Instanciate Recipe
new Modules.Recipe();

// instanciate Sliders
new Modules.SliderFilter();
new Modules.SliderIntroduction();
new Modules.SliderPost();
new Modules.SliderRichContent();
new Modules.SliderCategoryVideos();
new Modules.SliderRecipe();
new Modules.SliderRelationshipPosts();

new Modules.Menu();

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