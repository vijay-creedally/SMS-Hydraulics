document.addEventListener("DOMContentLoaded", function () {

    function applyMobileBreak() {
        const heading = document.querySelector(".benefits__heading");
        if (!heading) return;

        let html = heading.innerHTML;

        if (window.innerWidth <= 767) {
            html = html.replace(/<br\s*\/?>/gi, '<span class="mobile-line-break"></span>');
        } else {
            html = html.replace(/<span class="mobile-line-break"><\/span>/gi, '<br>');
        }

        heading.innerHTML = html;
    }

    applyMobileBreak();

    let resizeTimer;
    window.addEventListener("resize", function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(applyMobileBreak, 150);
    });
});
