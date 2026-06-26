document.addEventListener("DOMContentLoaded", () => {
    const storedHash = window.location.hash;
    if (storedHash) {
        const target = document.querySelector(storedHash);
        if (target) {
            setTimeout(() => {
                target.scrollIntoView({ behavior: "auto", block: "start" });
            }, 500);
        }
    }
});