import { breakpoints } from 'js/config';
import getViewportSize from 'Utils/getViewportSize';

/**
 * Media breakpoint up
 *
 * @param  str breakpoint
 * @return bool
 */
export default function mediaBreakpointUp(breakpoint) {
	if (!breakpoints[breakpoint]) {
		const errorMessage = `Breakpoint '${breakpoint}' do not exist`;
		console.error(errorMessage);
		throw new Error(errorMessage);
	}

	return getViewportSize().width > breakpoints[breakpoint];
}
