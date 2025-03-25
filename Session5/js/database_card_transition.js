/*
###############################################################################
#                                                                             #
#   Author: JungHwan Lee                                                      #
#   Submission Deadline: 31th March                                           #
#   Niub: 20467554                                                            #
#                                                                             #
###############################################################################
*/

document.observe("dom:loaded", function () {
  var toggled = false;

  $("connect-button").observe("click", function (e) {
    e.preventDefault();
    if (!toggled) {
      transformCardContainer();
      toggled = true;
    }
    const selectedDatabase = $('database').getValue();
    fetchTableContent(selectedDatabase);
  });
});

function transformCardContainer() {
  new Effect.Fade("page-container", {
    duration: 0.50,
    afterFinish: function () {
      setTimeout(alignCardRow, 50);
      new Effect.Appear("page-container", {
        duration: 0.4
      });
		  $('table-container').setStyle({ display: 'block' })
    }
  });
  new Effect.Fade("footer-container", {
    duration: 0.50,
    afterFinish: function () {
      new Effect.Appear("footer-container", {
        duration: 0.4
      });
    }
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
  $("card-container").addClassName("d-flex flex-row flex-wrap align-items-center");
  $("card-container").setStyle({
    maxWidth: "700px",
    width: "100%",
    margin: "0px",
    padding: "10px 20px",
    gap: "30px"
  });
}

function styleCardTitle() {
  $("card-title").setStyle({
    flex: "0 0 200px",
    fontSize: "1.5rem",
    margin: "12px 6px"
  });
}

function styleFormWrapper() {
  $("form-wrapper").addClassName("flex-row flex-wrap align-items-center");
  $("form-wrapper").setStyle({
    gap: "10px",
    flex: "1 1 auto"
  });
}

function adjustFormGroup() {
  const formGroup = $("form-wrapper").down(".form-group");
  formGroup.addClassName("d-flex flex-row align-items-center");
  formGroup.setStyle({
    flex: "1 1 120px",
    marginBottom: "0"
  });

  const label = formGroup.down("label");
  if (label) label.remove();

  const select = formGroup.down("select");
  select.setStyle({ flex: "1 1 auto" });
}

function adjustFormButtons() {
  const elements = $("form-wrapper").select("button", "a");
  elements.forEach(function (el) {
    removeClassesByElement(el, "btn-block mt-2");
    el.setStyle({
      flex: "0 0 100px",
      minWidth: "100px",
      marginBottom: "0"
    });
    el.addClassName("align-self-center");
  });
}

function removeClassesById(id, classListString) {
  const element = $(id);
  if (!element) return;
  removeClassesByElement(element, classListString);
}

function removeClassesByElement(el, classListString) {
  if (!el) return;
  const classes = classListString.split(/\s+/);
  classes.forEach(function (className) {
    el.removeClassName(className);
  });
}
