{literal}
  google.load('visualization', '1', {packages:['table']});
  google.setOnLoadCallback(drawTable);
  function drawTable() {
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Klub');
    data.addColumn('number', 'Liczba posłów');
    data.addColumn('number', 'Średnia wieku');
    data.addColumn('number', 'Udział kobiet [%]');
    data.addColumn('number', 'Udział "singli" [%]');
{/literal}

    data.addRows({$data|@count});
    {section name="rows" loop=$data}{assign var="row" value=$data[rows]}
      {section name="columns" loop=$row}{assign var="col" value=$row[columns]}
        data.setCell({$smarty.section.rows.iteration-1}, {$smarty.section.columns.iteration-1}, {$col});
      {/section}
    {/section}

{literal}
    var table = new google.visualization.Table($('tabela'));
    table.draw(data, {sortColumn: 1, sortAscending: false, allowHtml: true, cssClassNames: {tableCell: 'tableCell'}});
  }
{/literal}