/*
###############################################################################
#                                                                             #
#   Author: JungHwan Lee                                                      #
#   Submission Deadline: 31th March                                           #
#   Niub: 20467554                                                            #
#                                                                             #
###############################################################################
*/

document.observe("dom:loaded", initializeValidation);

function initializeValidation() {
  $("first_name").observe("keyup", validateFirstName);
  $("last_name").observe("keyup", validateLastName);
  $("doc_id").observe("keyup", validateDocId);
  $("doc_type").observe("change", validateDocId);

  const form = $$("form")[0];
  form.observe("submit", function (event) {
    if (!validateForm()) {
      event.preventDefault();
    }
  });
}

function validateFirstName() {
  const firstName = $("first_name").value.trim();

  if (firstName.length === 0) {
    showAlert("First Name is required.", "first_name");
    return false;
  }
  if (firstName.length > 50) {
    showAlert("First Name must be between 1 and 50 characters.", "first_name");
    return false;
  }

  hideAlert();
  resetBorder("first_name");
  return true;
}

function validateLastName() {
  const lastName = $("last_name").value.trim();

  if (lastName.length === 0) {
    showAlert("Last Name is required.", "last_name");
    return false;
  }
  if (lastName.length > 50) {
    showAlert("Last Name must be between 1 and 50 characters.", "last_name");
    return false;
  }

  hideAlert();
  resetBorder("last_name");
  return true;
}

function validateDocId() {
  const docType = $("doc_type").value;
  const docId = $("doc_id").value.trim();

  if (docId.length === 0) {
    showAlert("Document ID is required.", "doc_id");
    return false;
  }

  if (!isValidDocId(docType, docId)) {
    showAlert("Invalid Document ID format.", "doc_id");
    return false;
  }

  hideAlert();
  resetBorder("doc_id");
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
const DELAY = 1000;

function showAlert(message, element) {
  if (alertTimer) {
    clearTimeout(alertTimer);
  }

  alertTimer = setTimeout(function () {
    const alertBox = $("alert-box");
    alertBox.innerHTML = message;
    alertBox.appear({ duration: 0.3 });

    if (element) {
      $(element).setStyle({ border: "2px solid red" });
      $(element).shake();
    }
  }, DELAY);
}

function hideAlert() {
  $("alert-box").fade({ duration: 0.3 });

  if (alertTimer) {
    clearTimeout(alertTimer);
    alertTimer = null;
  }
}

function resetBorder(element) {
  $(element).setStyle({ border: "" });
}
