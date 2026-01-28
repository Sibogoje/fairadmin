// Vanilla JS for login form submission
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
        credentials: 'same-origin'
      }).then(function(res){
        return res.text();
      }).then(function(txt){
        try{
          var data = JSON.parse(txt);
        }catch(err){
          showAlert('Unexpected server response.', 'danger');
          btn.disabled = false; btn.innerText = 'Login';
          return;
        }

        if(data.statusCode === 200){
          window.location.href = 'dash.php';
        } else if(data.statusCode === 201){
          showAlert(data.exception || 'Authentication failed.', 'warning');
          btn.disabled = false; btn.innerText = 'Login';
        } else {
          showAlert(data.error || 'Login error.', 'danger');
          btn.disabled = false; btn.innerText = 'Login';
        }
      }).catch(function(){
        showAlert('Network error. Try again.', 'danger');
        btn.disabled = false; btn.innerText = 'Login';
      });
    });
  });
})();
