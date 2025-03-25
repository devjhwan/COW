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
  var startInput = $("start-date");
  var endInput = $("end-date");

  startInput.observe("change", handleStartDateChange);

  initializeStartDate();

  function initializeStartDate() {
    var today = new Date().toISOString().split("T")[0];
    startInput.setAttribute("min", today);
    endInput.setAttribute("min", today);
  }

  function handleStartDateChange() {
    var startValue = startInput.value;

    if (startValue) {
      endInput.setAttribute("min", startValue);

      if (endInput.value && endInput.value < startValue) {
      endInput.value = "";
      }
    }
  }
});
