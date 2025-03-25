/*
###############################################################################
#                                                                             #
#   Author: JungHwan Lee                                                      #
#   Submission Deadline: 31th March                                           #
#   Niub: 20467554                                                            #
#                                                                             #
###############################################################################
*/

document.observe('dom:loaded', function () {
  $('login-form').observe('submit', handleLoginSubmit);
});

function handleLoginSubmit(e) {
  e.preventDefault();

  const user_id = $F('username');
  const password = $F('password');

  sendLoginRequest(user_id, password);
}

function sendLoginRequest(user_id, password) {
  new Ajax.Request('../php/login.php', {
    method: 'post',
    parameters: { user_id, password },
    onSuccess: handleLoginSuccess,
    onFailure: handleLoginFailure
  });
}

function handleLoginSuccess(response) {
  const data = response.responseJSON;

  if (data.success) {
    window.location.href = '../html/home.html';
  } else {
    showError(data.error || 'Login failed');
  }
}

function handleLoginFailure() {
  showError('Server error. Please try again.');
}

function showError(message) {
  const msgBox = $('error-message');
  msgBox.textContent = message;
  msgBox.classList.remove('d-none');
}
