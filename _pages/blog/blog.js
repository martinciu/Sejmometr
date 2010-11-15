var Wykres = Class.create({
  google_loaded: false,
  data_loaded: false,
  initialize: function(div, dataset){
    this.div = div;
    this.dataset = dataset;
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback( function(){
      this.google_loaded = true;
      this.drawChart();
    }.bind(this) );
    $M.addInitCallback( this.init.bind(this) );
  },
  init: function(){
    this.div = $(this.div);
    $S('blog/wykresy/dane', this.dataset, function(data){
      if( data ) {
        this.data = data;
        this.data_loaded = true;
        this.drawChart();
      }
    }.bind(this));
  },
  drawChart: function(){
    if( !this.google_loaded || !this.data_loaded ) return false;
    
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Miesiąc');
    data.addColumn('number', 'Ilość projektów');
    data.addRows( this.data.length );
    
    for( var i=0; i<this.data.length; i++ ) {
      data.setValue(i, 0, this.data[i][0]);
      data.setValue(i, 1, this.data[i][1]);
    }
    
    this.chart = new google.visualization.ColumnChart( this.div );
    this.chart.draw(data, {height: 400, width: 550, title: 'Ilość projektów ustaw zgłaszanych przez rząd:', fontSize: '12px', hAxis: {slantedText: true}, legend: 'none', colors: ['#418DC3'], chartArea: {width: 550, left: 29}, titleTextStyle: {color: '#58595B'},  tooltipTextStyle: {color: '#58595B'}});
    google.visualization.events.addListener(this.chart, 'select', function(){
      var m = data.getValue( this.getSelection()[0]['row'], 0 );
      location = '/ustawy?autor=Rzad&miesiac='+m;
    }.bind(this.chart));
  
  }
});