document.observe("dom:loaded", function () {
  function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
  }

  const requiredParams = ["country", "city", "start_date", "end_date"];
  const values = {};

  let missing = false;
  requiredParams.forEach(function (key) {
    const value = getQueryParam(key);
    if (!value) {
      missing = true;
      console.log(key + " is missing");
    } else {
      values[key] = value;
      console.log(value);
    }
  });

  if (missing) {
    window.location.href = "../html/home.html";
    return;
  }

  $("hidden-country").value = values["country"];
  $("hidden-city").value = values["city"];
  $("hidden-start-date").value = values["start_date"];
  $("hidden-end-date").value = values["end_date"];
});