/*
###############################################################################
#                                                                             #
#   Author: JungHwan Lee                                                      #
#   Submission Deadline: 31th March                                           #
#   Niub: 20467554                                                            #
#                                                                             #
###############################################################################
*/

$(document).ready(function () {
  $("#login-form").on("submit", handleLoginSubmit);
});

function handleLoginSubmit(e) {
  e.preventDefault();

  const user_id = $("#username").val().trim();
  const password = $("#password").val().trim();
  const autologin = $("#autologin").is(":checked") ? 1 : 0;

  if (!user_id || !password) {
    showError("Username and password are required.");
    return;
  }

  sendLoginRequest(user_id, password, autologin);
}

function sendLoginRequest(user_id, password, autologin) {
  $.post(
    "../php/login.php",
    { user_id, password, autologin },
    function (data) {
      if (data.success) {
        window.location.href = "../html/home.html";
      } else {
        showError(data.error || "Login failed.");
      }
    },
    "json"
  ).fail(function () {
    showError("Server error. Please try again later.");
  });
}

function showError(message) {
  const $msgBox = $("#error-message");
  $msgBox.text(message).removeClass("d-none");
}
