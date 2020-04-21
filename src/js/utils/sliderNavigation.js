export default function(options) {
	const calc = ((options.current + 1) / options.count) * 100;

	// console.log({ calc, options });

	options.slider.progressbar.css('background-size', `${calc}% 100%`).attr('aria-valuenow', calc);

	if (options.slider.count.length) {
		options.slider.count[0].dataset.count = options.current + 1; // eslint-disable-line
	}
}
