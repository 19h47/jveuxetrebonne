import StartingBlocks, { polyfills } from 'starting-blocks';
import WebpackAsyncBlockBuilder from 'Services/WebpackAsyncBlockBuilder';

import Guid from 'Common/Guid';
import Menu from 'Common/Menu';

import DefaultPage from 'Pages/DefaultPage';

import objectFitImages from 'object-fit-images';

const production = 'production' === process.env.NODE_ENV;

(() => {
	window.MAIN_EXECUTED = true;

	// Declare polyfills.
	polyfills();
	objectFitImages(null, { watchMQ: true });

	const startingBlocks = new StartingBlocks({
		debug: production ? 0 : 1,
	});

	if (!production) {
		const guid = new Guid();

		guid.init();
	}

	const menu = new Menu();
	menu.init();

	startingBlocks.provider('BlockBuilder', WebpackAsyncBlockBuilder);
	startingBlocks.instanceFactory('default-page', c => new DefaultPage(c));

	startingBlocks.boot();
})();
