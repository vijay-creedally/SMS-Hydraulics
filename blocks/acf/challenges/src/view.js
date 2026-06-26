document.addEventListener('DOMContentLoaded', () => {
    if (window.innerWidth >= 1024) {

        window.addEventListener('scroll', () => {

            const sections = document.querySelectorAll(".challenges");

            sections.forEach(section => {
                const intro = section.querySelector(".challenges__intro");
                const inner = section.querySelector(".challenges__inner");

                if (!intro || !inner) return;

                // parent column width
                const colParent = intro.closest(".challenges__col");
                const colWidth = colParent ? colParent.offsetWidth : intro.offsetWidth;
                intro.style.width = colWidth + "px";

                const rect = inner.getBoundingClientRect();
                // Smooth transition for top
                intro.style.transition = "top 0.3s ease";

                let bottomSize =  300;
                if(window.innerWidth < 1200 ) {
                    bottomSize =  370;
                }

                if (rect.top < 40 && rect.bottom > bottomSize) {
                    intro.classList.add("fixed");
                    intro.style.top = "40px";  // smooth sticky
                } else {
                    intro.classList.remove("fixed");
                    intro.style.top = "4px";   // smooth return
                }
            });

        });
    }
});
