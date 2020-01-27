/* global $ */
import { AbstractPage } from 'starting-blocks';


export default class DefaultPage extends AbstractPage {
	constructor(container) {
		super(container, 'DefaultPage');
	}

	init() {
		super.init();
	}

	initEvents() {
		super.initEvents();

		const $mc4wp = $('.mc4wp-form');

		if ($mc4wp.hasClass('mc4wp-form-submitted')) {
			$('html, body').animate({
				scrollTop: document.body.scrollHeight,
			}, 200);
		}
	}
}
