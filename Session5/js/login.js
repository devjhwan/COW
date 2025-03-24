document.observe('dom:loaded', function () {
  $('login-form').observe('submit', function (e) {
    e.preventDefault();

    const user_id = $F('username');
    const password = $F('password');

    new Ajax.Request('../php/login.php', {
      method: 'post',
      parameters: {
        user_id: user_id,
        password: password
      },
      onSuccess: function (response) {
        const data = response.responseJSON;
        if (data.success) {
          window.location.href = '../html/home.html';
        } else {
          showError(data.error || 'Login failed');
        }
      },
      onFailure: function () {
        showError('Server error. Please try again.');
      }
    });

    function showError(message) {
      const msgBox = $('error-message');
      msgBox.textContent = message;
      msgBox.classList.remove('d-none');
    }
  });
});