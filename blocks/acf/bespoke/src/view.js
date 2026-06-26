
	/* Add block specific JS here */

function updateBespokeVisualHeight() {

	const wrapBlocks = document.querySelectorAll('.bespoke__wrap');
	if(wrapBlocks) {
		wrapBlocks.forEach(wrap => {
			const content = wrap.querySelector('.bespoke__content');
			const visualsImage = wrap.querySelector('.bespoke__visuals .bespoke__images');

			if( window.innerWidth >= 1025 ) {

				const contentHeight = content.offsetHeight;
				const finalHeight = contentHeight + 50;
				visualsImage.style.height = `${finalHeight}px`;
			} else {
				visualsImage.style.removeProperty('height');
			}
		});
	}
}

window.addEventListener('load', updateBespokeVisualHeight);
window.addEventListener('resize', updateBespokeVisualHeight);
