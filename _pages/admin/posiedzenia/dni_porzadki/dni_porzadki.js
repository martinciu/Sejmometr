var Dzien = Class.create({
  initialize: function(data){
    this.id = data.id;
    mBrowser.itemTitleUpdate(data.data);
    
    if( $('usun_btn') ) $('usun_btn').observe('click', this.usun.bind(this));
    if( $('analizuj_btn') ) $('analizuj_btn').observe('click', this.analizuj.bind(this));
    
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