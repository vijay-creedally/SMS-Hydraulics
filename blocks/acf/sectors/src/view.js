document.addEventListener("DOMContentLoaded", () => {

    const panels = document.querySelectorAll(".sectors-panel");
    const desktopBreakpoint = 1024;
    if(panels) {
        panels.forEach(panel => {

            const grid = panel.querySelector(".sectors-panel__grid");
            const cards = panel.querySelectorAll(".sectors-panel__card");
            
            if (!grid || cards.length === 0) return;
            
            cards.forEach(card => {
            
                card.addEventListener("mouseenter", () => {
                    if (window.innerWidth >= desktopBreakpoint) {
                        grid.classList.add("hovered");
                        cards.forEach(c => c.classList.remove("active"));
                        card.classList.add("active");
                    }
                });
            
                card.addEventListener("mouseleave", () => {
                    if (window.innerWidth >= desktopBreakpoint) {
                    
                        card.classList.remove("active");
                    
                        const anyHovered = Array.from(cards).some(c => c.matches(":hover"));
                    
                        if (!anyHovered) {
                            grid.classList.remove("hovered");
                        }
                    }
                });
            });
        
            window.addEventListener("resize", () => {
                if (window.innerWidth < desktopBreakpoint) {
                    grid.classList.remove("hovered");
                    cards.forEach(c => c.classList.remove("active"));
                }
            });
        
        });
    }
});