var Dzien = Class.create({
  initialize: function(data, wypowiedzi){
    this.id = data.id;
    this.data = data;
    this.wypowiedzi = wypowiedzi;
    mBrowser.itemTitleUpdate(data.data);
    
    if( $('usun_btn') ) $('usun_btn').observe('click', this.usun.bind(this));
    if( $('analizuj_btn') ) $('analizuj_btn').observe('click', this.analizuj.bind(this));
    
    for( var i=0; i<wypowiedzi.length; i++ ) {
      var wyp = wypowiedzi[i];
      
      var autor = wyp['autor'] ? wyp['autor'] : '<i>Brak autora</i>';
      var funkcja = wyp['funkcja'] ? wyp['funkcja'] : '<i>Brak funkcji</i>';
      
      var div = new Element('div', {className: 'wyp '+wyp['class']}).update('<div class="bar">'+autor+' - '+funkcja+'</div>');
      if( wyp['typ']!='1' ) div.insert('<div class="text">'+wyp['text']+'</div>');
      
      $('side_div').insert( div );
    }
    
  },
  usun: function(){
    if( confirm('Na pewno?') ) {
	    $('usun_btn').disable();
	    $S('usun', this.id, function(result){
	      $('usun_btn').enable();
	    });
    }
  },
  analizuj: function(){
    $('analizuj_btn').disable();
    $S('graber/posiedzenia/analizator/analizuj_wypowiedz', this.id, function(result){
      alert(result);
      $('analizuj_btn').enable();
    });
  }
});

var MBrowser = Class.create(MBrowser, {
  getListItemInnerHTML: function(data){
    return data['data'];
  },
});

var mBrowser;