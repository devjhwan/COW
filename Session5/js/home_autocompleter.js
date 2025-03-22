document.observe("dom:loaded", function () {
  var countryCodeInput = $("country-code");
  var countryInput = $("country-input");
  var cityInput = $("city-input");

  cityInput.disabled = true;
  new Autocompleter.Local("city-input", "city-autocompleter", []);

  new Ajax.Request("../php/get_country_list.php", {
    method: "get",
    onSuccess: handleCountryListSuccess,
    onFailure: handleCountryListFailure
  });

  countryInput.observe("input", function () {
    countryCodeInput.value = "";
    cityInput.value = "";
    cityInput.disabled = true;
  });

  function handleCountryListSuccess(response) {
    var data = JSON.parse(response.responseText);
    var countryMap = {};

    var countryNames = data.map(function (country) {
      countryMap[country.name] = country.code;
      return country.name;
    });

    new Autocompleter.Local("country-input", "country-autocompleter", countryNames, {
      afterUpdateElement: function (inputEl, selectedLi) {
        var selectedName = inputEl.value;
        var code = countryMap[selectedName] || "";
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
    var cityList = JSON.parse(response.responseText);
    new Autocompleter.Local("city-input", "city-autocompleter", cityList);
  }

  function handleCityListFailure() {
    new Autocompleter.Local("city-input", "city-autocompleter", []);
  }
});
