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
  $("#first_name").on("keyup", validateFirstName);
  $("#last_name").on("keyup", validateLastName);
  $("#doc_id").on("keyup", validateDocId);
  $("#doc_type").on("change", validateDocId);

  $("form").first().on("submit", function (event) {
    if (!validateForm()) {
      event.preventDefault();
    }
  });
});

function validateFirstName() {
  const firstName = $("#first_name").val().trim();

  if (firstName.length === 0) {
    showAlert("First Name is required.", "#first_name");
    return false;
  }
  if (firstName.length > 50) {
    showAlert("First Name must be between 1 and 50 characters.", "#first_name");
    return false;
  }

  hideAlert();
  resetBorder("#first_name");
  return true;
}

function validateLastName() {
  const lastName = $("#last_name").val().trim();

  if (lastName.length === 0) {
    showAlert("Last Name is required.", "#last_name");
    return false;
  }
  if (lastName.length > 50) {
    showAlert("Last Name must be between 1 and 50 characters.", "#last_name");
    return false;
  }

  hideAlert();
  resetBorder("#last_name");
  return true;
}

function validateDocId() {
  const docType = $("#doc_type").val();
  const docId = $("#doc_id").val().trim();

  if (docId.length === 0) {
    showAlert("Document ID is required.", "#doc_id");
    return false;
  }

  if (!isValidDocId(docType, docId)) {
    showAlert("Invalid Document ID format.", "#doc_id");
    return false;
  }

  hideAlert();
  resetBorder("#doc_id");
  return true;
}

function validateForm() {
  return validateFirstName() && validateLastName() && validateDocId();
}

function isValidDocId(type, value) {
  const patterns = {
    DNI: /^[0-9]{8}[A-Z]$/,
    NIE: /^[XYZ][0-9]{7}[A-Z]$/,
    PASSPORT: /^[A-Z0-9]{6,12}$/
  };
  return patterns[type] ? patterns[type].test(value) : false;
}

let alertTimer = null;
const DELAY = 700;

function showAlert(message, selector) {
  if (alertTimer) clearTimeout(alertTimer);

  alertTimer = setTimeout(function () {
    const $alertBox = $("#alert-box");
    $alertBox.html(message).fadeIn(300);

    if (selector) {
      $(selector).css("border", "2px solid red");
      $(selector).effect("shake", { distance: 4, times: 2 }, 300);
    }
  }, DELAY);
}

function hideAlert() {
  $("#alert-box").fadeOut(300);

  if (alertTimer) {
    clearTimeout(alertTimer);
    alertTimer = null;
  }
}

function resetBorder(selector) {
  $(selector).css("border", "");
}
