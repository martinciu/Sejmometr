var Calendar = Class.create({
  initialize: function(div, cal_data){
    this.div = $(div);
    this.table = this.div.down('table');
    this.data = cal_data;
    
    this.render_month();
  },
  render_month: function(){
    var dim = this.days_in_month(this.data.year, this.data.month);
    
    var d = new Date(this.data.year, this.data.month-1, 1, 0, 0, 0);
    var db = d.getDay();
    
    for( var d=0; d<dim+db-1; d++ ) {
      var mod = d % 7;
      if( mod==0 ) {
        var tr = new Element('tr', {className: 'd'});
        var tri = 0;
      }
      
      var dn = d-db+2;
      
      if( dn<=0 ) dn = '';
      
      var td = new Element('td').update('<p class="nr"></p><div class="adevents"></div><div class="sevents"></div>');
      if( dn==this.day ) td.addClassName('today');
      if( dn>0 && dn<=dim ) {
        var _dn = dn<10 ? '0'+String(dn) : String(dn);
        var date = this.data.year+'-'+this.data.month+'-'+_dn;
        td.addClassName('i').writeAttribute({date: date}).down('p.nr').update('<span>'+dn+'</span>');
      }
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
        var a = new Element('a', {href: '#', onclick: 'return false;', posiedzenie_id: this.data.dni[i]['posiedzenie_id']}).update( '&nbsp;<span style="display: none;">Posiedzenie <b>'+this.data.dni[i]['numer']+'</b></span>' );
        td.down('.adevents').insert(a);
      }
    }
    
    for( var i=0; i<this.data.posiedzenia.length; i++ ){
      var es = this.table.select('.adevents a[posiedzenie_id='+this.data.posiedzenia[i]+']');
      es.first().addClassName('first').down('span').show();
      es.last().addClassName('last');
    }
       
    
    
    
    for( var i=0; i<this.data.projekty.length; i++ ){   
      var projekt = this.data.projekty[i];
      var td = this.table.down('td[date='+projekt['data']+']');
      if( td ) {
        var a = new Element('a', {href: '#', onclick: 'return false;'}).update( projekt.tytul );
        td.down('.sevents').insert(a);
      }
    }
    
    
    
    /*
    for( day in this.days ) {
      var div = this.table.down('td.i[date='+day+'] .sevents');
      if( div ) {
        
        
        for( var i=0; i<this.typy.length; i++ ) {
          var typ = this.typy[i];
          if( Object.isArray(this.days[day][typ]) ) {
	          var count = this.days[day][typ].length;
	          var a = new Element('a', {className: typ, href: '#'}).update( this.sm_dopelniacz(count, typ) );
		        div.insert(a);
	        }
        }
        
	      
      }
     
    }
    */
    
      
      
    
    
    
  },
  days_in_month: function(m, y){
	  return 32 - new Date(y, m, 32).getDate();
  }

});

var cal;
$M.addInitCallback(function(){
  cal = new Calendar('cal', cal_data);
});