export default function (options) {
	const calc = (options.current / (options.count - 1)) * 100;

	options.slider.progressbar
		.css('background-size', `${calc}% 100%`)
		.attr('aria-valuenow', calc);

	if (options.slider.count.length) {
		options.slider.count[0].dataset.count = options.current + 1; // eslint-disable-line
	}
}
