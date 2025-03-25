document.observe("dom:loaded", function () {
  new Ajax.Request('../includes/header.html', {
    method: 'get',
    onSuccess: function(response) {
      $('header-container').innerHTML = response.responseText;
      check_session();
    }
  });
  new Ajax.Request('../includes/footer.html', {
    method: 'get',
    onSuccess: function(response) {
      $('footer-container').innerHTML = response.responseText;
    }
  });

  function check_session() {
    new Ajax.Request("../php/check_session.php", {
      method: "get",
      onSuccess: function (response) {
        const data = response.responseJSON;
  
        if (data && data.logged_in) {
          const link = $("login-link");
          const icon = $("login-icon");
          const text = $("login-text");
  
          if (icon) icon.remove();
          if (text) text.textContent = data.user_id;
  
          link.href = "../php/logout.php";
        }
      },
      onFailure: function () {
        console.warn("Failed to check session.");
      }
    });
  }
});