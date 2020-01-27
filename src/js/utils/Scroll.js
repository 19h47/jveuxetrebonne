import { elements, scroll } from 'js/config';


/**
 * reset
 *
 * @param  positionX
 * @param  positionY
 * @public
 */
const reset = (positionX, positionY) => {
	// console.info('reset');

	if ('undefined' !== typeof positionX) {
		scroll.left = parseInt(positionX, 10);
	}

	if ('undefined' !== typeof positionY) {
		scroll.top = parseInt(positionY, 10);
	}

	window.scrollTo(
		scroll.left,
		scroll.top,
	);
};


/**
 * disableScroll
 *
 * Lock scroll position, but retain settings for later
 *
 * @see  http://stackoverflow.com/a/3656618
 */
export function disableScroll() {
	// console.info('disableScroll');

	const documentElementScrollLeft = elements.html.scrollLeft;
	const documentElementScrollTop = elements.html.scrollTop;

	const bodyScrollLeft = elements.body.scrollLeft;
	const bodyScrollTop = elements.body.scrollTop;

	// eslint-disable-next-line no-restricted-globals
	scroll.left = self.pageXOffset || documentElementScrollLeft || bodyScrollLeft;
	// eslint-disable-next-line no-restricted-globals
	scroll.top = self.pageYOffset || documentElementScrollTop || bodyScrollTop;

	elements.html.style.setProperty('overflow', 'hidden');
	elements.html.style.setProperty('height', '100%');

	// document.ontouchmove = (event) => event.preventDefault();

	// eslint-disable-next-line
	reset(scroll.left, scroll.top);
}


/**
 * enableScroll
 *
 * @param  position
 */
export function enableScroll(position) {
	// console.info('enableScroll');

	let resumeScroll = true;
	let currentPosition = position;

	if ('undefined' === typeof currentPosition) {
		currentPosition = scroll.top;
	}

	if ('boolean' === typeof currentPosition && false === currentPosition) {
		resumeScroll = false;
	}

	// Enable scrolling.
	document.ontouchmove = () => true;

	// unlock scroll position
	// http://stackoverflow.com/a/3656618
	elements.html.style.setProperty('overflow', 'visible');
	elements.html.style.setProperty('height', 'auto');

	// resume scroll position if possible
	if (resumeScroll) {
		// eslint-disable-next-line
		reset(
			scroll.left,
			currentPosition,
		);
	}
}
