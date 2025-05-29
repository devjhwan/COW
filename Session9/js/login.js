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

  const user_id = $("#username").val();
  const password = $("#password").val();

  sendLoginRequest(user_id, password);
}

function sendLoginRequest(user_id, password) {
  $.post("../php/login.php", { user_id, password }, function (data) {
    window.location.href = "../html/home.html";
  }, "json")
  .fail(function () {
    showError("Login failed");
  });
}

function showError(message) {
  const $msgBox = $("#error-message");
  $msgBox.text(message).removeClass("d-none");
}
