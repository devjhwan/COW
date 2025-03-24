document.observe("dom:loaded", function () {
  transformCardContainer();
});

function transformCardContainer() {
  var toggled = false;

  $("connect-button").observe("click", function (e) {
    e.preventDefault();
    if (toggled) return;
    toggled = true;

    new Effect.Fade("page-container", {
      duration: 0.50,
      afterFinish: function () {
        setTimeout(alignCardRow, 50);
        new Effect.Appear("page-container", {
          duration: 0.4
        });
      }
    });
  });
}

function alignCardRow() {
  $("card-container").setStyle({ maxWidth: "720px" });
  $("card-container").removeClassName("card-style");
  $("card-container").removeClassName("flex-column");
  $("card-container").removeClassName("shadow-lg");
  $("card-container").removeClassName("p-4");
  $("card-container").addClassName("flex-row flex-wrap align-items-center");
  $("card-container").setStyle({
    transform: "",
    maxWidth: "720px",
    width: "100%",
    margin: "0px",
    padding: "10px 20px",
    gap: "30px",
  });

  $("card-title").removeClassName("mb-4");
  $("card-title").setStyle({
    flex: "0 0 200px",
    fontSize: "1.5rem",
    margin: "12px 6px"
  });

  $("form-wrapper").removeClassName("flex-column");
  $("form-wrapper").addClassName("flex-row flex-wrap align-items-center");
  $("form-wrapper").setStyle({
    gap: "10px",
    flex: "1 1 auto"
  });

  const formGroup = $("form-wrapper").down(".form-group");
  formGroup.addClassName("d-flex flex-row align-items-center");
  formGroup.setStyle({
    flex: "1 1 150px",
    marginBottom: "0"
  });

  const label = formGroup.down("label");
  if (label) label.remove();
  const select = formGroup.down("select");
  select.setStyle({ flex: "1 1 auto"});

  const elements = $("form-wrapper").select("button", "a");
  elements.forEach(function (el) {
    el.removeClassName("btn-block");
    el.removeClassName("mt-2");
    el.setStyle({
      flex: "0 0 100px",
      minWidth: "100px",
      marginBottom: "0"
    });
    el.addClassName("align-self-center");
  });
}