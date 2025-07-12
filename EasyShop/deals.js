function startCountdown(element, hours) {
  const endTime = new Date().getTime() + hours * 60 * 60 * 1000;

  function update() {
    const now = new Date().getTime();
    const diff = endTime - now;

    if (diff <= 0) {
      element.innerHTML = "Deal expired";
      return;
    }

    const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const s = Math.floor((diff % (1000 * 60)) / 1000);
    element.innerHTML = `${h}h ${m}m ${s}s`;
  }

  update();
  setInterval(update, 1000);
}

// Run countdown for each product
document.querySelectorAll(".countdown").forEach(el => {
  const hours = parseInt(el.dataset.hours);
  startCountdown(el, hours);
});
