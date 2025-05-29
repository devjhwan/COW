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
  const $countryCodeInput = $("#country-code");
  const $countryInput = $("#country-input");
  const $cityInput = $("#city-input");

  initializeForm($cityInput);

  $.get("../php/get_country_list.php", function (data, status) {
    if (status === "success")
      handleCountryListSuccess(data, $countryCodeInput, $cityInput);
    else
      handleCountryListFailure();
  });

  $countryInput.on("input", function () {
    $countryCodeInput.val("");
    $cityInput.val("").prop("disabled", true);
  });
});

function initializeForm($cityInput) {
  $cityInput.prop("disabled", true);
  $cityInput.autocomplete({ source: [] });
}

function handleCountryListSuccess(data, $countryCodeInput, $cityInput) {
  const countryMap = {};

  const countryNames = data.map(function (country) {
    countryMap[country.name] = country.code;
    return country.name;
  }).sort();

  $("#country-input").autocomplete({
    source: countryNames,
    minLength: 0,
    select: function (event, ui) {
      const selectedName = ui.item.value;
      const code = countryMap[selectedName] || "";

      $countryCodeInput.val(code);
      $cityInput.text("");
      $cityInput.prop("disabled", false);
      fetchCityListAndActivate(code);
    }
  }).on("focus", function () {
    $(this).autocomplete("search", "");
  });
}

function handleCountryListFailure() {
  $("#country-input").autocomplete({ source: [] });
}

function fetchCityListAndActivate(countryCode) {
  $.get("../php/get_city_list.php", { country_code: countryCode }, function (data, status) {
    if (status === "success")
      handleCityListSuccess(data);
    else
      handleCityListFailure();
  });
}

function handleCityListSuccess(data) {
  const cityList = data.sort();

  $("#city-input").autocomplete({
    source: cityList,
    minLength: 0,
  }).on("focus", function () {
    $(this).autocomplete("search", "");
  });
}

function handleCityListFailure() {
  $("#city-input").autocomplete({
    source: []
  });
}
