// Simple login handler with AJAX to prevent full page reload during auth check
(function(){
  function showAlert(message, type){
    var html = '<div class="alert alert-' + type + ' alert-dismissible" role="alert">' +
      message +
      '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
      '</div>';
    document.getElementById('alert-placeholder').innerHTML = html;
  }

  document.addEventListener('DOMContentLoaded', function(){
    var form = document.getElementById('loginform');
    var btn = document.getElementById('loginBtn');

    form.addEventListener('submit', function(e){
      e.preventDefault();
      btn.disabled = true;
      btn.innerText = 'Signing in...';

      var fd = new FormData(form);

      fetch('parse.php', {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
        redirect: 'follow'
      }).then(function(res){
        if(res.ok){
          // On successful auth, parse.php redirects to dash.php
          window.location.href = 'dash.php';
        } else {
          showAlert('Authentication failed. Please try again.', 'warning');
          btn.disabled = false;
          btn.innerText = 'Login';
        }
      }).catch(function(){
        showAlert('Network error. Try again.', 'danger');
        btn.disabled = false;
        btn.innerText = 'Login';
      });
    });
  });
})();
