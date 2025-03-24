function fetchTableContent(database, table, page = 1) {
  new Ajax.Request('../php/get_database_content.php', {
    method: 'get',
    parameters: {
      database: database,
      table: table,
      page: page
    },
    onSuccess: function(response) {
      const data = response.responseJSON;
    
      $('db-name').textContent = data.database;
    
      const tabsContainer = $('table-tabs');
      tabsContainer.innerHTML = '';
      data.tables.forEach(function(tableName) {
        const li = document.createElement('li');
        li.className = 'nav-item';
    
        const a = document.createElement('a');
        a.className = 'nav-link' + (tableName === data.selected_table ? ' active' : '');
        a.href = '#';
        a.textContent = tableName;
        a.observe('click', function(e) {
          e.preventDefault();
          fetchTableContent(database, tableName, 1);
        });
    
        li.appendChild(a);
        tabsContainer.appendChild(li);
      });
    
      $('selected-table-name').textContent = data.selected_table;
    
      const headersRow = $('table-headers');
      headersRow.innerHTML = '';
      data.columns.forEach(function(col) {
        const th = document.createElement('th');
        th.textContent = col;
        headersRow.appendChild(th);
      });
    
      const tbody = $('table-body');
      tbody.innerHTML = '';
      data.rows.forEach(function(row) {
        const tr = document.createElement('tr');
        data.columns.forEach(function(col) {
          const td = document.createElement('td');
          td.textContent = row[col];
          tr.appendChild(td);
        });
        tbody.appendChild(tr);
      });
    
      const prevBtn = $('prev-button');
      const nextBtn = $('next-button');

      prevBtn.disabled = page <= 1;
      nextBtn.disabled = !data.has_next_page;
    
      prevBtn.stopObserving('click');
      nextBtn.stopObserving('click');
    
      prevBtn.observe('click', function(e) {
        e.preventDefault();
        if (page > 1) {
          fetchTableContent(database, data.selected_table, page - 1);
        }
      });
    
      nextBtn.observe('click', function(e) {
        e.preventDefault();
        if (data.has_next_page) {
          fetchTableContent(database, data.selected_table, page + 1);
        }
      });
    },
    onFailure: function() {
      $('table-container').innerHTML = '<p class="text-danger">Failed to load table.</p>';
    }
  });
}
  