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
  const countryCodeInput = $("country-code");
  const countryInput = $("country-input");
  const cityInput = $("city-input");

  initializeForm(cityInput);

  new Ajax.Request("../php/get_country_list.php", {
    method: "get",
    onSuccess: function (response) {
      handleCountryListSuccess(response, countryCodeInput, cityInput);
    },
    onFailure: handleCountryListFailure
  });

  countryInput.observe("input", function () {
    countryCodeInput.value = "";
    cityInput.value = "";
    cityInput.disabled = true;
  });
});

function initializeForm(cityInput) {
  cityInput.disabled = true;
  new Autocompleter.Local("city-input", "city-autocompleter", []);
}

function handleCountryListSuccess(response, countryCodeInput, cityInput) {
  const data = JSON.parse(response.responseText);
  const countryMap = {};

  const countryNames = data.map(function (country) {
    countryMap[country.name] = country.code;
    return country.name;
  });

  new Autocompleter.Local("country-input", "country-autocompleter", countryNames, {
    afterUpdateElement: function (inputEl, selectedLi) {
      const selectedName = inputEl.value;
      const code = countryMap[selectedName] || "";
      countryCodeInput.value = code;
      cityInput.disabled = false;
      fetchCityListAndActivate(code);
    }
  });
}

function handleCountryListFailure() {
  new Autocompleter.Local("country-input", "country-autocompleter", []);
}

function fetchCityListAndActivate(countryCode) {
  new Ajax.Request("../php/get_city_list.php", {
    method: "get",
    parameters: { country_code: countryCode },
    onSuccess: handleCityListSuccess,
    onFailure: handleCityListFailure
  });
}

function handleCityListSuccess(response) {
  const cityList = JSON.parse(response.responseText);
  new Autocompleter.Local("city-input", "city-autocompleter", cityList);
}

function handleCityListFailure() {
  new Autocompleter.Local("city-input", "city-autocompleter", []);
}
