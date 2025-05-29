/*
###############################################################################
#                                                                             #
#   Author: JungHwan Lee                                                      #
#   Submission Deadline: 31th March                                           #
#   Niub: 20467554                                                            #
#                                                                             #
###############################################################################
*/

function fetchTableContent(database, table, page = 1) {
  $.get('../php/get_database_content.php', {
    database: database,
    table: table,
    page: page
  }, function (data) {
    $("#db-name").text(data.database);

    updateTableTabs(data, database);
    updateTableName(data);
    updateTableHeaders(data);
    updateTableBody(data);
    updatePaginationControls(data, database, page);
  }, "json")
  .fail(function () {
    $("#table-container").html('<p class="text-danger">Failed to load table.</p>');
  });
}

function updateTableTabs(data, database) {
  const $tabsContainer = $("#table-tabs");
  $tabsContainer.empty();

  data.tables.forEach(function (tableName) {
    const $a = $("<a>")
      .addClass("nav-link" + (tableName === data.selected_table ? " active" : ""))
      .attr("href", "#")
      .text(tableName)
      .on("click", function (e) {
        e.preventDefault();
        fetchTableContent(database, tableName, 1);
      });

    const $li = $("<li>").addClass("nav-item").append($a);
    $tabsContainer.append($li);
  });
}

function updateTableName(data) {
  $("#selected-table-name").text(data.selected_table);
}

function updateTableHeaders(data) {
  const $headersRow = $("#table-headers").empty();
  data.columns.forEach(function (col) {
    $("<th>").text(col).appendTo($headersRow);
  });
}

function updateTableBody(data) {
  const $tbody = $("#table-body").empty();
  data.rows.forEach(function (row) {
    const $tr = $("<tr>");
    data.columns.forEach(function (col) {
      $("<td>").text(row[col]).appendTo($tr);
    });
    $tbody.append($tr);
  });
}

function updatePaginationControls(data, database, page) {
  const $prevBtn = $("#prev-button");
  const $nextBtn = $("#next-button");

  $prevBtn.prop("disabled", page <= 1);
  $nextBtn.prop("disabled", !data.has_next_page);

  $prevBtn.off("click").on("click", function (e) {
    e.preventDefault();
    if (page > 1) {
      fetchTableContent(database, data.selected_table, page - 1);
    }
  });

  $nextBtn.off("click").on("click", function (e) {
    e.preventDefault();
    if (data.has_next_page) {
      fetchTableContent(database, data.selected_table, page + 1);
    }
  });
}

