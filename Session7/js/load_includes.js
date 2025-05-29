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
  $('#header-container').load('../includes/header.html', function () {
    check_session();
    highlightActiveMenu();
  });

  $('#footer-container').load('../includes/footer.html');
});

function check_session() {
  $.get("../php/check_session.php", function (data) {
    if (data && data.logged_in) {
      const link = $("#login-link");
      const icon = $("#login-icon");
      const text = $("#login-text");

      if (icon.length) icon.remove();
      if (text.length) text.text(data.user_id);

      link.attr("href", "../php/logout.php");
    }
  }).fail(function () {
    console.warn("Failed to check session.");
  });
}

function highlightActiveMenu() {
  const path = window.location.pathname;
  const filename = path.substring(path.lastIndexOf('/') + 1);

  let targetLink = null;

  if (filename !== 'reserve.html') {
    targetLink = filename;
  }

  if (targetLink) {
    $('#navbarNav .nav-link').each(function () {
      const link = $(this);

      if (link.attr('href') === targetLink) {
        link.closest('.nav-item').addClass('active');
      } else {
        link.closest('.nav-item').removeClass('active');
      }
    });
  }
}