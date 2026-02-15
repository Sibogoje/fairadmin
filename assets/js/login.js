// Login form submits normally via POST to parse.php, which handles server-side redirect
// No additional JavaScript handling needed - browser will follow Location header
(function(){
  document.addEventListener('DOMContentLoaded', function(){
    var btn = document.getElementById('loginBtn');
    var form = document.getElementById('loginform');
    
    form.addEventListener('submit', function(){
      btn.disabled = true;
      btn.innerText = 'Signing in...';
      // Form will submit via standard POST to parse.php
    });
  });
})();
