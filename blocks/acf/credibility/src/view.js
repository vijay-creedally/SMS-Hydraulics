document.addEventListener("DOMContentLoaded", () => {
  const counters = document.querySelectorAll(".credibility__number");
  const options = { threshold: 0.5 };

  const startCounting = (entry) => {
    const counter = entry.target;
    const target = parseFloat(counter.getAttribute("data-target"));
	const unit	= counter.dataset.unit || '';
    let count = 0;
    const increment = target / 100;

    const updateCounter = () => {
      count += increment;
      if (count < target) {
        counter.textContent = `${Math.floor(count)}${unit}`;
        requestAnimationFrame(updateCounter);
      } else {
        counter.textContent = `${target}${unit}`;
      }
    };

    updateCounter();
  };

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        startCounting(entry);
        observer.unobserve(entry.target);
      }
    });
  }, options);

  counters.forEach((counter) => observer.observe(counter));
});