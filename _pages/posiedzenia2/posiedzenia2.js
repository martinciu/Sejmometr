var Calendar = Class.create({
  initialize: function(div, cal_data){
    this.div = $(div);
    this.table = this.div.down('table');
    
    this.cache = cal_data.data;

    
    this.header = this.div.down('h1');
    this.set_month( new Date( Number(cal_data.year), Number(cal_data.month)-1, 1 ) );
    
    $('cal_link_prev').observe('click', this.prev_month.bind(this));
    $('cal_link_next').observe('click', this.next_month.bind(this));
  },
  dec_to_string: function(m){
    return Number(m)<10 ? '0'+String(m) : String(m);
  },
  get_month_string: function(){
    return this.year+'-'+this.dec_to_string( this.month );
  },
  set_month: function(d){
    var month = d.getFullYear()+'-'+this.dec_to_string( Number(d.getMonth())+1 );
    if( Object.keys( this.cache ).indexOf( month )==-1 ) {
    
      this.load_month( [d.getFullYear(), Number(d.getMonth())+1 ] );
    
    } else {
      this.year = Number( d.getFullYear() );
	    this.month = Number(d.getMonth())+1;
      this.render_month();
    }
  },
  render_month: function(){
    var _month_string = this.get_month_string();
    this.header.update( _miesiace[this.month]+' '+this.year );
    var dim = this.days_in_month(this.year, this.month);
        
    var d = new Date(this.year, this.month-1, 1);
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
        var date = _month_string+'-'+this.dec_to_string( dn );
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
    
    var _data = this.cache[ _month_string ];
    
    var posiedzenia = $A();
    for( var i=0; i<_data.length; i++ ){  
    
      var _posiedzenie_id = _data[i]['posiedzenie_id'];
      if( posiedzenia.indexOf(_posiedzenie_id)===-1 ) posiedzenia.push( _posiedzenie_id );
      
      var td = this.table.down('td[date='+_data[i]['data']+']');
      if( td ) {
        var a = new Element('a', {href: '/'+_posiedzenie_id, posiedzenie_id: _data[i]['posiedzenie_id']}).update( '&nbsp;<span style="display: none;"><b>'+_data[i]['numer']+'</b></span>' );
        td.down('.adevents').insert(a);
      }
    }
    
    for( var i=0; i<posiedzenia.length; i++ ){
      var id = posiedzenia[i];
      var es = this.table.select('.adevents a[posiedzenie_id='+id+']');
      if( es ) {
	      if( posiedzenie_id==id ) es.invoke('addClassName', 'selected');
	      es.first().addClassName('first').down('span').show();
	      es.last().addClassName('last');
      }
    }
    
  },
  load_month: function(d){
    $S('cal_data', d, function(data){
	    this.cache = data.data;
	    this.year = Number( data.year );
	    this.month = Number( data.month );
	    this.render_month();
    }.bind(this));
  },
  prev_month: function(){
    var d = new Date( this.year, this.month-2, 1 );
    this.set_month(d);
  },
  next_month: function(){
    var d = new Date( this.year, this.month, 1 );
    this.set_month(d);
  },
  days_in_month: function(m, y){
	  return 32 - new Date(y, m, 32).getDate();
  }

});

function init(){
  $$('a.dokument.reader').each(function(a){
    
    var doc_id = a.readAttribute('doc_id');
    var doc_key = a.readAttribute('doc_key');
    var title = a.readAttribute('title');
    
    a.observe('click', pokaz_dokument.bind(null, doc_id, doc_key, title));
    
    var e = a.up('.e');
    if(e){
      var a = e.down('.tytul a');
      if(a) {
        a.observe('click', pokaz_dokument.bind(null, doc_id, doc_key, title));
      }
      
      if( !e.hasClassName('podpisanie') ) {
	      var il = e.down('.ikona_legislacyjna');
	      if( il ) il.wrap($ANCHOR()).observe('click', pokaz_dokument.bind(null, doc_id, doc_key, title));
      }
    }
    
  });
}


var cal;
$M.addInitCallback(function(){
  cal = new Calendar('cal', cal_data);
  init();
});