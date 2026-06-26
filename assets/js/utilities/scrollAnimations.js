import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
gsap.registerPlugin(ScrollTrigger);

// Animation configurations
const ANIMATIONS = {
	'ani-fade': {
		initial: { opacity: 0 },
		animate: { opacity: 1 }
	},
	'ani-left': {
		initial: { x: -100 },
		animate: { x: 0 }
	},
	'ani-right': {
		initial: { x: 100 },
		animate: { x: 0 }
	},
	'ani-top': {
		initial: { y: -100 },
		animate: { y: 0 }
	},
	'ani-bottom': {
		initial: { y: 100 },
		animate: { y: 0 }
	}
};

// Get all animation classes applied to an element
const getAnimationClasses = (element) => {
	return Object.keys(ANIMATIONS).filter(className =>
		element.classList.contains(className)
	);
};

// Combine multiple animation properties
const combineAnimations = (classes) => {
	return classes.reduce((combined, className) => {
		const animation = ANIMATIONS[className];
		return {
			initial: { ...combined.initial, ...animation.initial },
			animate: { ...combined.animate, ...animation.animate }
		};
	}, { initial: {}, animate: {} });
};

const createElementAnimation = (element) => {
	const animationClasses = getAnimationClasses(element);
	if (animationClasses.length === 0) return;

	const combined = combineAnimations(animationClasses);

	// Set initial state
	gsap.set(element, {
		...combined.initial,
		willChange: 'transform, opacity'
	});

	// Create animation
	gsap.to(element, {
		...combined.animate,
		duration: 4,
		ease: 'power2.out',
		scrollTrigger: {
			trigger: element,
			start: 'top 80%',
			end: 'top 60%',
			scrub: 0.5,
			once: true,
		}
	});
};

const createStaggeredAnimations = (parent, elements) => {
	const staggerOffset = 50;

	elements.forEach((element, index) => {
		const i = (index + 1);
		const animationClasses = getAnimationClasses(element);
		const combined = combineAnimations(animationClasses);

		// Add stagger offset to initial position
		const initialState = {
			...combined.initial,
			// y: (combined.initial.y || 0) + (staggerOffset * i),
			willChange: 'transform, opacity'
		};

		// Set initial state
		gsap.set(element, initialState);

		// Create animation
		gsap.to(element, {
			...combined.animate,
			// y: 0, // Always animate to y: 0 for staggered elements
			ease: 'power2.out',
			scrollTrigger: {
				trigger: element,
				start: 'top 80%',
				end: 'top 60%',
				scrub: 0.5,
				once: true,
			}
		});
	});
};

const initializeAnimations = () => {
	// Find all elements with any animation class
	const animatedElements = document.querySelectorAll(
		Object.keys(ANIMATIONS).map(className => '.' + className).join(',')
	);

	animatedElements.forEach((element) => {
		const isNested = element.closest('[class*="ani-"]') !== element;

		if (!isNested) {
			// Handle parent blocks
			createElementAnimation(element);

			// Find and handle nested elements
			const nestedElements = element.querySelectorAll('[class*="ani-"]');
			if (nestedElements.length > 0) {
				createStaggeredAnimations(element, nestedElements);
			}
		}
	});
};

// Initialize animations when DOM is ready
const init = () => {
	document.addEventListener('DOMContentLoaded', initializeAnimations);

	// Handle ACF block renders
	if (window.acf) {
		acf.addAction('render_block_preview', initializeAnimations);
	}
};

// Run initialization
init();
