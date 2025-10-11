// Minimal front init (bez jQuery)
document.addEventListener("DOMContentLoaded", () => {
  const els = document.querySelectorAll(".counter");
  if (!("IntersectionObserver" in window)) {
    els.forEach((el) => (el.textContent = el.getAttribute("data-target")));
    return;
  }
  const ease = (t) => 1 - Math.pow(1 - t, 3);
  const animate = (el) => {
    const target = parseFloat(el.getAttribute("data-target"));
    const isFloat = !Number.isInteger(target);
    const start = performance.now();
    const dur = 1200;
    const step = (now) => {
      const p = Math.min(1, (now - start) / dur);
      const val = target * ease(p);
      el.textContent = isFloat ? val.toFixed(1) : Math.round(val);
      if (p < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  };
  const io = new IntersectionObserver(
    (entries) => {
      entries.forEach((e) => {
        if (e.isIntersecting) {
          animate(e.target);
          io.unobserve(e.target);
        }
      });
    },
    { threshold: 0.6 }
  );
  els.forEach((el) => io.observe(el));
});
