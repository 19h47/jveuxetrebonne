import StartingBlocks, {
	polyfills,
} from 'starting-blocks';
import Guid from 'Common/Guid';
import WebpackAsyncBlockBuilder from 'Services/WebpackAsyncBlockBuilder';
import Menu from 'Common/Menu';

import DefaultPage from 'Pages/DefaultPage';

const production = 'production' === process.env.NODE_ENV;

(() => {
	// Declare polyfills.
	polyfills();

	const startingBlocks = new StartingBlocks({ debug: production ? 0 : 1 });

	if (!production) {
		const guid = new Guid();

		guid.init();
	}

	const menu = new Menu();
	menu.init();

	startingBlocks.provider('BlockBuilder', WebpackAsyncBlockBuilder);
	startingBlocks.instanceFactory('default-page', (c) => new DefaultPage(c));

	// Boot, boot, boot!
	startingBlocks.boot();
})();
