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

    $$("div.card").each(function (card) {
        var originalWidth = card.getWidth();
        var originalHeight = card.getHeight();

        card.effect = null;

        card.observe("mouseover", function () {
            if (card.effect) {
                card.effect.cancel();
            }

            card.effect = new Effect.Morph(card, {
                style: {
                    width: (originalWidth * 1.1) + "px",
                    height: (originalHeight * 1.1) + "px",
                    boxShadow: "0 4px 8px rgba(0, 0, 0, 0.2)"
                },
                duration: 0.3
            });
        });

        card.observe("mouseout", function () {
            if (card.effect) {
                card.effect.cancel();
            }

            card.effect = new Effect.Morph(card, {
                style: {
                    width: originalWidth + "px",
                    height: originalHeight + "px",
                    boxShadow: "none"
                },
                duration: 0.3
            });
        });
    });
});
