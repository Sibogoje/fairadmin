// Simple login - form submits normally via POST, browser handles redirect naturally
(function(){
  document.addEventListener('DOMContentLoaded', function(){
    var form = document.getElementById('loginform');
    var btn = document.getElementById('loginBtn');

    form.addEventListener('submit', function(){
      btn.disabled = true;
      btn.innerText = 'Signing in...';
      // Form submits normally - browser will handle Location redirect
      // and preserve session cookies automatically
    });
  });
})();
