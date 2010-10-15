{literal}
  var data;
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
    data = new google.visualization.DataTable();
    data.addColumn('string', 'Miesiąc');
    data.addColumn('number', 'Ilość projektów');
{/literal}

    data.addRows({$data|@count});
    {section name="data" loop=$data}
      data.setValue({$smarty.section.data.iteration-1}, 0, '{$data[data][0]}');
      data.setValue({$smarty.section.data.iteration-1}, 1, {$data[data][1]});
    {/section}
    
{literal}
    var chart = new google.visualization.ColumnChart($('wykres'));
    chart.draw(data, {height: 400, title: 'Ilość projektów ustaw zgłaszanych przez Rząd:', fontSize: '12px', hAxis: {slantedText: true}, legend: 'none', colors: ['#418DC3'], titleTextStyle: {color: '#58595B'},  tooltipTextStyle: {color: '#58595B'}});
    google.visualization.events.addListener(chart, 'select', function(){
      var m = data.getValue( chart.getSelection()[0]['row'], 0 );
      location = '/ustawy?autor=Rzad&miesiac='+m;
    }.bind(chart));
  }
{/literal}