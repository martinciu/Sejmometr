var Calendar = Class.create({
  initialize: function(div, cal_data){
    this.div = $(div);
    this.table = this.div.down('table');
    this.data = cal_data;
    this.header = this.div.down('h1');
    
    this.render_month();
    
    $('cal_link_prev').observe('click', this.prev_month.bind(this));
    $('cal_link_next').observe('click', this.next_month.bind(this));
  },
  render_month: function(){
    this.header.update( _miesiace[this.data.month]+' '+this.data.year );
  
    var dim = this.days_in_month(this.data.year, this.data.month);
        
    var d = new Date(this.data.year, this.data.month-1, 1);
    var db = d.getDay();
    
    
    this.table.update('<tr class="dni"><td>pon</td><td>wto</td><td>śr</td><td>czw</td><td>pią</td><td class="s">so</td><td class="s">nie</td></tr>');
    for( var d=0; d<dim+db-1; d++ ) {
      var mod = d % 7;
      if( mod==0 ) {
        var tr = new Element('tr', {className: 'd'});
        var tri = 0;
      }
      
      var dn = d-db+2;
      
      if( dn<=0 ) dn = '';
      
      var td = new Element('td').update('<p class="nr"></p><div class="adevents"></div>');
      if( dn>0 && dn<=dim ) {
        var _dn = dn<10 ? '0'+String(dn) : String(dn);
        var _month = this.data.month<10 ? '0'+String(this.data.month) : String(this.data.month);
        var date = this.data.year+'-'+_month+'-'+_dn;
        td.addClassName('i').writeAttribute({date: date}).down('p.nr').update('<span>'+dn+'</span>');
      }
      
      if( mod==5 || mod==6 ) td.addClassName('s');
      
      tr.insert(td);
      tri++;
            
      if( mod==6 ) this.table.insert(tr);
    
    }
    
    if( mod!=6 ) {
	    for( var i=0; i<6-mod; i++ ) {
	      var td = new Element('td').update('<p class="nr"></p><div class="events"></div>');
	      tr.insert(td);
	    }
	    this.table.insert(tr);
    }
    
    for( var i=0; i<this.data.dni.length; i++ ){    
      var td = this.table.down('td[date='+this.data.dni[i]['data']+']');
      if( td ) {
        var a = new Element('a', {href: '/posiedzenia/'+this.data.dni[i]['posiedzenie_id'], posiedzenie_id: this.data.dni[i]['posiedzenie_id']}).update( '&nbsp;<span style="display: none;"><b>'+this.data.dni[i]['numer']+'</b></span>' );
        td.down('.adevents').insert(a);
      }
    }
    
    for( var i=0; i<this.data.posiedzenia.length; i++ ){
      var id = this.data.posiedzenia[i];
      var es = this.table.select('.adevents a[posiedzenie_id='+id+']');
      if( posiedzenie_id==id ) es.invoke('addClassName', 'selected');
      es.first().addClassName('first').down('span').show();
      es.last().addClassName('last');
    }
       
    
    var dni = this.table.select();
    
  },
  load_month: function(d){
    $S('cal_data', [d.getFullYear(), d.getMonth()+1], function(data){
	    this.data = data;
	    this.render_month();
    }.bind(this));
  },
  prev_month: function(){
    var d = new Date( this.data.year, this.data.month-2, 1 );
    this.load_month(d);
  },
  next_month: function(){
    var d = new Date( this.data.year, this.data.month, 1 );
    this.load_month(d);
  },
  days_in_month: function(m, y){
	  return 32 - new Date(y, m, 32).getDate();
  }

});

var cal;
$M.addInitCallback(function(){
  cal = new Calendar('cal', cal_data);
});