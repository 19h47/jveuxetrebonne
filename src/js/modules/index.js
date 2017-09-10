var Modules = {

	App: require('./app'),
	Comments: require('./comments')(),
	Guid: require('./guid')(),
	Instagram: require('./instagram'),
	ImageContent: require('./image-content'),
	Menu: require('./menu'),
	LoadMore: require('./load-more'),
	Recipe: require('./recipe'),
	Search: require('./search'),
	SliderCategoryVideos: require('./slider-category-videos'),
	SliderFilter: require('./slider-filter'),
	SliderIntroduction: require('./slider-introduction'),
	SliderPost: require('./slider-post'),
	SliderRichContent: require('./slider-rich-content'),
	SliderRelationshipPosts: require('./slider-relationship-posts'),
	SliderRecipe: require('./slider-recipe'),
	SingleMenu: require('./single-menu')
};

module.exports = Modules;