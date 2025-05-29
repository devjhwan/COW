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
  let toggled = false;

  $("#connect-button").on("click", function (e) {
    e.preventDefault();
    if (!toggled) {
      transformCardContainer();
      toggled = true;
    }
    const selectedDatabase = $("#database").val();
    fetchTableContent(selectedDatabase);
  });

  function transformCardContainer() {
    $("#page-container").fadeOut(500, function () {
      setTimeout(alignCardRow, 50);
      $("#page-container").fadeIn(400);
      $("#table-container").css("display", "block");
    });

    $("#footer-container").fadeOut(500, function () {
      $("#footer-container").fadeIn(400);
    });
  }

  function alignCardRow() {
    resetCardLayout();
    styleCardContainer();
    styleCardTitle();
    styleFormWrapper();
    adjustFormGroup();
    adjustFormButtons();
  }

  function resetCardLayout() {
    removeClassesById("card-layout", "d-flex justify-content-center align-items-center vh-100");
    removeClassesById("card-container", "shadow-lg p-4");
    removeClassesById("card-title", "mb-4");
    removeClassesById("form-wrapper", "flex-column");
  }

  function styleCardContainer() {
    $("#card-container")
      .addClass("d-flex flex-row flex-wrap align-items-center")
      .css({
        maxWidth: "700px",
        width: "100%",
        margin: "0px",
        padding: "10px 20px",
        gap: "30px"
      });
  }

  function styleCardTitle() {
    $("#card-title").css({
      flex: "0 0 200px",
      fontSize: "1.5rem",
      margin: "12px 6px"
    });
  }

  function styleFormWrapper() {
    $("#form-wrapper")
      .addClass("flex-row flex-wrap align-items-center")
      .css({
        gap: "10px",
        flex: "1 1 auto"
      });
  }

  function adjustFormGroup() {
    const $formGroup = $("#form-wrapper .form-group").first();
    $formGroup
      .addClass("d-flex flex-row align-items-center")
      .css({
        flex: "1 1 120px",
        marginBottom: "0"
      });

    $formGroup.find("label").remove();
    $formGroup.find("select").css({ flex: "1 1 auto" });
  }

  function adjustFormButtons() {
    $("#form-wrapper").find("button, a").each(function () {
      $(this)
        .removeClass("btn-block mt-2")
        .css({
          flex: "0 0 100px",
          minWidth: "100px",
          marginBottom: "0"
        })
        .addClass("align-self-center");
    });
  }

  function removeClassesById(id, classListString) {
    const $el = $("#" + id);
    removeClassesByElement($el, classListString);
  }

  function removeClassesByElement($el, classListString) {
    const classes = classListString.split(/\s+/);
    classes.forEach((cls) => {
      $el.removeClass(cls);
    });
  }
});
