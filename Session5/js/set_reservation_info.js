document.observe("dom:loaded", function () {
  let card_moved = false;
  let info_appeared = false;

  $('check-button').observe('click', function () {
    if (!card_moved) {
      playCardTranslation();
      card_moved = true;
    }
    fetchReservationInfo();
  });

  function fetchReservationInfo() {
    const params = {
      reservation_code: $('reservation_code').value,
      last_name: $('last_name').value
    };

    new Ajax.Request("../php/get_reservation_info.php", {
      method: "post",
      parameters: params,
      onSuccess: function (response) {
        if (response.status === 200) {
          const json = response.responseText.evalJSON();
          fillResultCard(json.data);
        } else {
          showError("Reservation not found.");
        }
      },
      onFailure: function () {
        showError("Server error. Please try again later.");
      },
      onComplete: function () {
        if (!info_appeared) {
          new Effect.Appear('result-card', { duration: 0.5 });
          info_appeared = true;
        }
      }
    });
  }

  function fillResultCard(data) {
    $('result-card').innerHTML = `
      <h4 class="mb-3">Reservation Info</h4>
      <p><strong>Name:</strong> ${data.first_name} ${data.last_name}</p>
      <p><strong>Reservation Code:</strong> ${data.reservation_code}</p>
      <p><strong>Location:</strong> ${data.city}, ${data.country}</p>
      <p><strong>Stay:</strong> ${data.start_date} to ${data.end_date}</p>
      <p><strong>Document:</strong> ${data.doc_type} - ${data.doc_id}</p>
    `;
  }

  function showError(message) {
    $('result-card').innerHTML = `
      <h4 class="mb-3 text-danger">Error</h4>
      <p>${message}</p>
    `;
  }

  function playCardTranslation() {
    new Effect.Move('check-reservation', {
      x: -300,
      y: 0,
      mode: 'relative',
      duration: 0.7
    });
  }
});
