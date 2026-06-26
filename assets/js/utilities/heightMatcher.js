/**
 * heightMatcher - Matches the height of elements based on the tallest one
 * 
 * @param {string} elem - CSS selector for target elements
 * @param {number} breakpoint - Minimum window width to apply height matching (default: 0)
 * @param {number} debounceDelay - Delay for debouncing resize event (default: 250ms)
 * @return {function} - Function to manually trigger recalculation
 */
const heightMatcher = (elem, breakpoint, debounceDelay = 250) => {
	breakpoint = typeof breakpoint === 'undefined' ? 0 : breakpoint;
	let resizeTimer;

	// The main calculation function
	const calculateHeights = () => {
		const elements = document.querySelectorAll(elem);
		if (!elements.length) return;

		const windowWidth = document.body.clientWidth;
		let height = 0;

		// Reset heights first
		elements.forEach(el => {
			el.style.height = '';
		});

		// Only apply if window width is greater than breakpoint
		if (windowWidth > breakpoint) {
			// Find the tallest element
			elements.forEach(el => {
				const thisHeight = el.clientHeight;
				if (thisHeight > height) {
					height = thisHeight;
				}
			});

			// Apply height to all elements
			elements.forEach(el => {
				el.style.height = height + 'px';
			});
		}
	};

	// Debounced resize handler
	const handleResize = () => {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(calculateHeights, debounceDelay);
	};

	// Initialize when DOM is ready
	const initialize = () => {
		// Initial calculation
		calculateHeights();

		// Add resize event listener
		window.addEventListener('resize', handleResize);

		// Also recalculate when images load as they may affect layout
		window.addEventListener('load', calculateHeights);
	};

	// If DOM is already ready, initialize immediately
	if (document.readyState === 'complete' || document.readyState === 'interactive') {
		setTimeout(initialize, 1);
	} else {
		// Otherwise wait for DOMContentLoaded
		document.addEventListener('DOMContentLoaded', initialize);
	}

	// Return the calculate function for manual triggering
	return calculateHeights;
};

export default heightMatcher;
