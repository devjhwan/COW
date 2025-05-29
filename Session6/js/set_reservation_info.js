$(document).ready(function () {
  let card_moved = false;
  let info_appeared = false;

  $("#check-button").on("click", function () {
    if (!card_moved) {
      playCardTranslation();
      card_moved = true;
    }
    fetchReservationInfo();
  });

  function fetchReservationInfo() {
    const params = {
      reservation_code: $("#reservation_code").val(),
      last_name: $("#last_name").val()
    };

    $.post("../php/get_reservation_info.php", params, function (data, textStatus, jqXHR) {
      if (jqXHR.status === 204) {
        showError("Reservation not found.");
        return;
      }
    
      fillResultCard(data.data);
    }, "json")
    .fail(function () {
      showError("Server error. Please try again later.");
    })
    .always(function () {
      if (!info_appeared) {
        $("#result-card").fadeIn(500);
        info_appeared = true;
      }
    });
  }

  function fillResultCard(data) {
    $("#result-card").html(`
      <h4 class="mb-3">Reservation Info</h4>
      <p><strong>Name:</strong> ${data.first_name} ${data.last_name}</p>
      <p><strong>Reservation Code:</strong> ${data.reservation_code}</p>
      <p><strong>Location:</strong> ${data.city}, ${data.country}</p>
      <p><strong>Stay:</strong> ${data.start_date} to ${data.end_date}</p>
      <p><strong>Document:</strong> ${data.doc_type} - ${data.doc_id}</p>
    `);
  }

  function showError(message) {
    $("#result-card").html(`
      <h4 class="mb-3 text-danger">Error</h4>
      <p>${message}</p>
    `);
  }

  function playCardTranslation() {
    $("#check-reservation").animate({ left: "-=300px" }, 700);
  }
});
