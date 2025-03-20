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
    $("first_name").observe("keyup", validateFirstName);
    $("last_name").observe("keyup", validateLastName);
    $("doc_id").observe("keyup", validateDocId);
    $("doc_type").observe("change", validateDocId);

    var form = $$("form")[0];
    form.observe("submit", function (event) {
        if (!validateForm()) {
            event.preventDefault();
        }
    });
});

function showAlert(message, element) {
    var alertBox = $("alert-box");
    alertBox.innerHTML = message;
    alertBox.appear({ duration: 0.3 });

    if (element) {
        $(element).setStyle({ border: "2px solid red" });
        $(element).shake();
    }
}

function hideAlert() {
    $("alert-box").fade({ duration: 0.3 });
}

function resetBorder(element) {
    $(element).setStyle({ border: "" });
}

function validateFirstName() {
    var firstName = $("first_name").value.trim();
    if (firstName.length === 0) {
        showAlert("First Name is required.", "first_name");
        return false;
    } else if (firstName.length > 50) {
        showAlert("First Name must be between 1 and 50 characters.", "first_name");
        return false;
    }

    hideAlert();
    resetBorder("first_name");
    return true;
}

function validateLastName() {
    var lastName = $("last_name").value.trim();
    if (lastName.length === 0) {
        showAlert("Last Name is required.", "last_name");
        return false;
    } else if (lastName.length > 50) {
        showAlert("Last Name must be between 1 and 50 characters.", "last_name");
        return false;
    }

    hideAlert();
    resetBorder("last_name");
    return true;
}

function validateDocId() {
    var docType = $("doc_type").value;
    var docId = $("doc_id").value.trim();
    var dniPattern = /^[0-9]{8}[A-Z]$/;
    var niePattern = /^[XYZ][0-9]{7}[A-Z]$/;
    var passportPattern = /^[A-Z0-9]{6,12}$/;

    var isValid = false;

    if (docId.length === 0) {
        showAlert("Document ID is required.", "doc_id");
        return false;
    }

    if (docType === "DNI" && dniPattern.test(docId)) {
        isValid = true;
    } else if (docType === "NIE" && niePattern.test(docId)) {
        isValid = true;
    } else if (docType === "PASSPORT" && passportPattern.test(docId)) {
        isValid = true;
    }

    if (!isValid) {
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
