/*
Sticky Nav
Description: Makes the navigation sticky once the user has scrolled to it.
Usage: StickyMenu(elementClass);
*/
const StickyMenu = (elem) => {
	const stickyNav = document.getElementsByTagName(elem);
	console.log(stickyNav);
	if (stickyNav.length > 1) {
		console.warn('Multiple Sticky Navs found. Please define 1 only.'); // eslint-disable-line
	} else if (stickyNav.length === 0) {
		console.warn('Sticky Nav element not found'); // eslint-disable-line
	} else {
		const nav = stickyNav[0],
			navContainer = nav.parentElement;

		let navTop = nav.offsetTop,
			navContainerHeight = navContainer.offsetHeight + 'px';

		window.addEventListener(
			'resize',
			() => {
				navTop = nav.offsetTop;
				navContainerHeight = navContainer.offsetHeight + 'px';
			},
			false
		);

		window.addEventListener(
			'scroll',
			() => {

				const top = window.scrollY;
				const contentAreaNotFloating = document.querySelector('.entry-content');
				const contentArea = document.querySelector('.floating-nav .entry-content');

				if (top >= 40) {
					nav.classList.add('sticky');
					// navContainer.style.height = navContainerHeight;
					// get nav height and minus is from margin of .entry-content
					if (contentArea) {
						contentArea.style.marginTop = '-' + nav.offsetHeight + 'px';
					} else {
						contentAreaNotFloating.style.marginTop = nav.offsetHeight + 'px';
						contentAreaNotFloating.style.paddingTop = '0';
					}
				} else {
					nav.classList.remove('sticky');
					if (contentArea) {
						contentArea.style.marginTop = 'auto';
					} else {
						contentAreaNotFloating.style.marginTop = 'auto';
						// remove inline style for paddingTop
						contentAreaNotFloating.style.removeProperty('padding-top');
					}

					// navContainer.style.height = '';
				}
			},
			false
		);
	}
};

export default StickyMenu;


document.addEventListener("DOMContentLoaded", () => {
  const toggleBtn = document.querySelector(".header__toggle");
  const headerMenu = document.querySelector(".header__menu");
  const headerMenuItems = headerMenu?.querySelectorAll("li") || [];
  const svg = document.getElementById('menu-icon');
  const lines = {
    l1: svg.querySelector('.line-1'),
    l2: svg.querySelector('.line-2'),
    l3: svg.querySelector('.line-3')
  };

  let isOpen = false;
  let lastWidth = window.innerWidth;
  const duration = 500;

  const hamburger = [
    { x1: 0,  y1: 5.5,  x2: 48, y2: 5.5 },
    { x1: 12, y1: 17.5, x2: 48, y2: 17.5 },
    { x1: 24, y1: 29.5, x2: 48, y2: 29.5 }
  ];

  const cross = [
    { x1: 14.0607, y1: 6.93934, x2: 33.8596, y2: 26.7383 },
    null,
    { x1: 13.9393, y1: 26.9393, x2: 33.7383, y2: 7.14035 }
  ];

  const ease = t => t < 0.5 ? 4*t*t*t : 1 - Math.pow(-2*t+2,3)/2;
  const lerp = (a, b, t) => a + (b - a) * t;

  const readLineAttrs = line => ({
    x1: parseFloat(line.getAttribute('x1')),
    y1: parseFloat(line.getAttribute('y1')),
    x2: parseFloat(line.getAttribute('x2')),
    y2: parseFloat(line.getAttribute('y2'))
  });

  const writeLineAttrs = (line, attrs) => {
    line.setAttribute('x1', attrs.x1);
    line.setAttribute('y1', attrs.y1);
    line.setAttribute('x2', attrs.x2);
    line.setAttribute('y2', attrs.y2);
  };

  let animFrame = null;
  let isAnimating = false;

  const animateSVG = (destArray) => {
    if (isAnimating) {
      cancelAnimationFrame(animFrame);
    }
    isAnimating = true;
    const startTime = performance.now();
    const startStates = [readLineAttrs(lines.l1), readLineAttrs(lines.l2), readLineAttrs(lines.l3)];
    const startOpacity2 = parseFloat(getComputedStyle(lines.l2).opacity || 1);

    function step(now) {
      const tRaw = Math.min(1, (now - startTime) / duration);
      const t = ease(tRaw);

      for (let i = 0; i < 3; i++) {
        const start = startStates[i];
        const dest = destArray[i];
        if (!dest) continue;

        writeLineAttrs([lines.l1, lines.l2, lines.l3][i], {
          x1: lerp(start.x1, dest.x1, t),
          y1: lerp(start.y1, dest.y1, t),
          x2: lerp(start.x2, dest.x2, t),
          y2: lerp(start.y2, dest.y2, t)
        });
      }

      lines.l2.style.opacity = destArray[1] === null ? lerp(startOpacity2, 0, t) : lerp(startOpacity2, 1, t);

      if (tRaw < 1) animFrame = requestAnimationFrame(step);
      else isAnimating = false;
    }

    animFrame = requestAnimationFrame(step);
  };

  const resetSVG = () => animateSVG(hamburger);

  const resetMenu = () => {
    toggleBtn.classList.remove("is-active");
    toggleBtn.setAttribute("aria-expanded", "false");
    headerMenu.classList.remove("is-active", "open");
    headerMenu.closest(".header")?.classList.remove("is-active");

    headerMenuItems.forEach(item => {
      item.style.opacity = "";
      item.style.transform = "";
      item.style.transitionDelay = "";
    });

    isOpen = false;
    resetSVG(); // Sync SVG
  };

  const debounce = (fn, delay = 150) => {
    let timer;
    return (...args) => {
      clearTimeout(timer);
      timer = setTimeout(() => fn(...args), delay);
    };
  };

  window.addEventListener("resize", debounce(() => {
    const currentWidth = window.innerWidth;
    if ((lastWidth < 1024 && currentWidth >= 1024) || (lastWidth >= 1024 && currentWidth < 1024)) resetMenu();
    lastWidth = currentWidth;
  }));

  if(toggleBtn) {
    toggleBtn.addEventListener("click", () => {
      const header = toggleBtn.closest(".header");
      if (!isOpen) {
        toggleBtn.classList.add("is-active");
        toggleBtn.setAttribute("aria-expanded", "true");
        headerMenu.classList.add("is-active", "open");
        header?.classList.add("is-active");
      
        setTimeout(() => {
          headerMenuItems.forEach((item, i) => {
            item.style.opacity = "0";
            item.style.transform = "translateX(20px)";
            void item.offsetWidth;
            item.style.transitionDelay = `${i*100}ms`;
            item.style.opacity = "1";
            item.style.transform = "translateX(0)";
          });
        }, 300);
      
        animateSVG(cross);
      } else {
        headerMenuItems.forEach((item, i) => {
          const delay = (headerMenuItems.length - 1 - i)*100;
          item.style.transitionDelay = `${delay}ms`;
          item.style.opacity = "0";
          item.style.transform = "translateX(20px)";
        });
      
        const totalDuration = headerMenuItems.length*100 + 700;
        setTimeout(() => header?.classList.remove("is-active"), totalDuration/4);
        setTimeout(() => {
          headerMenu.classList.remove("is-active", "open");
          toggleBtn.classList.remove("is-active");
          toggleBtn.setAttribute("aria-expanded", "false");
        }, totalDuration);
      
        animateSVG(hamburger);
      }
    
      isOpen = !isOpen;
    });
  }
  writeLineAttrs(lines.l1, hamburger[0]);
  writeLineAttrs(lines.l2, hamburger[1]);
  writeLineAttrs(lines.l3, hamburger[2]);
  lines.l2.style.opacity = "1";
  lines.l3.style.opacity = "1";
});

