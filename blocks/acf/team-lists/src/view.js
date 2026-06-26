/* Add block specific JS here */

// Safari-safe imports
import Masonry from "masonry-layout";
import imagesLoaded from "imagesloaded/imagesloaded";

document.addEventListener("DOMContentLoaded", () => {
    const grid = document.querySelector(".team__grid");
    let msnry = null;

    function initMasonry() {
        if (!grid) return;

        // Only activate Masonry on desktop
        if (window.innerWidth >= 992) {

            // Wait until all images are loaded
            imagesLoaded(grid, () => {

                // Safari reflow fix (forces redraw)
                grid.style.display = "block";
                grid.style.opacity = "0";
                void grid.offsetWidth; // trigger reflow
                grid.style.opacity = "1";

                if (!msnry) {
                    msnry = new Masonry(grid, {
                        itemSelector: ".team__item",
                        columnWidth: ".team__item",
                        percentPosition: true,
                        horizontalOrder: true,
                        gutter: 40,
                    });
                } else {
                    msnry.layout();
                }
            });

        } else {
            // Destroy Masonry on mobile
            if (msnry) {
                msnry.destroy();
                msnry = null;
            }
        }
    }

    // Run Masonry after page load (required for Safari)
    window.addEventListener("load", () => {
        console.log("Window loaded, initializing Masonry...");
        setTimeout(initMasonry, 500);
    });

    window.addEventListener("scroll", () => {
        const rect = grid?.getBoundingClientRect();
        console.log("Window scrolled, checking grid visibility...");
        if(rect.top < window.innerHeight && rect.bottom > 0) {
            console.log("Grid rect:", rect);
            initMasonry();
        }
    });

    let resizeTimer;
    window.addEventListener("resize", () => {
        console.log("Window resize, initializing Masonry...");
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(initMasonry, 300);
    });
});
