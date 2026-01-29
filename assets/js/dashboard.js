// Simple dashboard UI helpers
// Shows skeleton placeholders briefly while the page loads
(function () {
  function removeLoading() {
    document.body.classList.remove('loading-dashboard');
  }

  // Remove loading state after window load or fallback timeout
  if (document.readyState === 'complete') {
    removeLoading();
  } else {
    window.addEventListener('load', removeLoading);
    // fallback if load takes too long
    setTimeout(removeLoading, 800);
  }
})();
