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
  
    $.post("../php/get_reservation_info.php", params, function (xml) {
      if (!xml || $(xml).find("reservation").length === 0) {
        showError("Reservation not found.");
        return;
      }
  
      const data = parseXmlToObj(xml);
      fillResultCard(data);
    }, "xml")
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
  
  function parseXmlToObj(xml) {
    const obj = {};
    $(xml).find("reservation").children().each(function () {
      obj[this.nodeName] = $(this).text();
    });
    return obj;
  }  

  function fillResultCard(data) {
    const $table = $("<table>").addClass("table table-bordered");
    const rows = [
      ["Name", `${data.first_name} ${data.last_name}`],
      ["Reservation Code", data.reservation_code],
      ["Location", `${data.city}, ${data.country}`],
      ["Stay", `${data.start_date} to ${data.end_date}`],
      ["Document", `${data.doc_type} - ${data.doc_id}`],
    ];
  
    rows.forEach(([label, value]) => {
      const $tr = $("<tr>");
      $tr.append($("<th>").text(label));
      $tr.append($("<td>").text(value));
      $table.append($tr);
    });
  
    $("#result-card").html(`
      <h4 class="mb-3">Reservation Info</h4>
    `).append($table);
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
