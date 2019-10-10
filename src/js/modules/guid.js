
/**
 * Guid
 */
export default class Guid {
	constructor() {
		this.$guid = document.querySelector('.js-guid');
	}

	init() {
		if (null === this.$guid) {
			return false;
		}

		document.addEventListener('keydown', (e) => {
			if ((e.metaKey || e.ctrlKey) && 186 === e.keyCode) {
				this.$guid.classList.toggle('d-none');
			}
		});

		return true;
	}
}
