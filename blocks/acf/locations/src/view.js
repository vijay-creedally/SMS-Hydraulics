document.addEventListener("DOMContentLoaded", function () {

    const allBlocks = document.querySelectorAll(".locations");

    if(allBlocks) {
        allBlocks.forEach((block) => {

            const tabs = block.querySelectorAll(".locations__tab");
            const panels = block.querySelectorAll(".locations__panel");
            const mapImage = block.querySelector(".locations__map-image");
            
            if(tabs) {
                tabs.forEach((tab) => {
                    tab.addEventListener("click", function () {
                    
                        const target = this.getAttribute("data-tab");
                    
                        tabs.forEach(t => t.classList.remove("locations__tab--active"));
                        this.classList.add("locations__tab--active");
                    
                        if(panels) {
                            panels.forEach(panel => {
                                if (panel.id === target) {
                                    panel.classList.add("locations__panel--active");
                                
                                    const newMap = panel.dataset.image;
                                    if (newMap && mapImage) {
                                        mapImage.src = newMap;
                                        mapImage.setAttribute("src", newMap);
                                    }
                                
                                } else {
                                    panel.classList.remove("locations__panel--active");
                                }
                            });
                        }
                    });
                });
            }
        });
    }
});
