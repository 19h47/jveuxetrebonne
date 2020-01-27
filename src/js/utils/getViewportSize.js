/**
 * Match CSS media queries and JavaScript window width.
 *
 * @see http://stackoverflow.com/a/11310353
 * @return {Object}
 */
export default function getViewportSize() {
	let e = window;
	let a = 'inner';

	if (!('innerWidth' in window)) {
		a = 'client';
		e = document.documentElement || document.body;
	}

	return { width: e[`${a}Width`], height: e[`${a}Height`] };
}
