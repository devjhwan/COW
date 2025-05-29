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
  const $startInput = $("#start-date");
  const $endInput = $("#end-date");

  $startInput.on("change", handleStartDateChange);

  initializeStartDate();

  function getTodayDateString() {
    return new Date().toISOString().split("T")[0];
  }

  function initializeStartDate() {
    const today = getTodayDateString();
    $startInput.attr("min", today);
    $endInput.attr("min", today);
  }

  function handleStartDateChange() {
    const startValue = $startInput.val();
    if (!startValue) return;

    $endInput.attr("min", startValue);

    const endValue = $endInput.val();
    if (endValue && endValue < startValue) {
      $endInput.val("");
    }
  }
});
