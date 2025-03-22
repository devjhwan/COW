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
  Sortable.create("sortable", {
      tag: "li",
      constraint: "horizontal"
  });
});
