_mFormTypes.register('_druk', Class.create(_mFormTypes.getClass('druki'), {
  setValue: function($super, value){
    if(value) $super([value]);
  },
  onDrukRender: function(data){
    if( projekt.data.autor_id=='' ) projekt.form.fields[1].setValue( data.autorA_id );
  },
  getValue: function($super){
    return $super()[0];
  }
}));

var Projekt = Class.create({
  initialize: function(data){
    this.id = data.id;
    this.data = data;
    
    mBrowser.itemTitleUpdate(data.numer ? data.numer : '<i>Bez tytułu</i>');
    this.btnSave = mBrowser.addItemButton('save', 'Zapisz', this.save.bind(this));
    this.btnSave = mBrowser.addItemButton('regrab', 'Pobierz', this.regrab.bind(this));
    
    $$('#duplikaty_table .buttons input').invoke('observe', 'click', this.pobierz_projekt.bind(this));
    
    var suggestions = {};
    
    if( this.data.html===false ) {
    
      $('side_div').update("Projekt nie został jeszcze pobrany");
    
    } else {
    
	    $('side_div').update(this.data.html).height_control();
	    if($('side_div').down('table')){
	      $('side_div').down('table').writeAttribute('width', '100%');
	      for( var i=0; i<3; i++ ) $('side_div').down('table tr').remove();
	    }
	    
	    $('side_div').select('a').each( function(a){
	      var match = a.readAttribute('href').match(/http:\/\/orka.sejm.gov.pl\/Druki6ka.nsf\/druk\?OpenAgent&([A-F0-9\-]+)/);
	      a.writeAttribute('href', '#').writeAttribute('onclick', 'return false;');
	      if( match ) a.addClassName('_druk').writeAttribute('numer', match[1]).observe('click', this.onDrukCick.bind(this));
	    }.bind(this) );
	    
	    
	    
	    try{
	
		    suggestions['tytul'] = $('side_div').down('b').innerHTML;
		
		    var match = suggestions['tytul'].match(/^(.*?) projekt ustawy o zmianie(.*?)$/);
		    if( match ) { suggestions['tytul'] = 'Zmiana'+match[2]; } else {
		      var match = suggestions['tytul'].match(/^(.*?) projekt ustawy o(.*?)$/);
		      if( match ) suggestions['tytul'] = 'Projekt ustawy o'+match[2];
		    }
		    
		    var match = suggestions['tytul'].match(/^(.*?) projekt ustawy zmieniającej ustawę o zmianie(.*?)$/);
		    if( match ) suggestions['tytul'] = 'Zmiana'+match[2];
		    
		    suggestions['tytul'] = suggestions['tytul'].replace(' oraz o zmianie niektórych innych ustaw', '');
		    suggestions['tytul'] = suggestions['tytul'].replace(' oraz niektórych innych ustaw', '');
		             
		    var fonts = $('side_div').select('font');
		    for( var i=0; i<fonts.length; i++ ) {
		      var text = fonts[i].innerHTML;
		      if( text.match(/opis projektu/i) ) {
		        var opis = fonts[i].up('tr').next('tr').down('font').innerHTML;
		        if( !opis.match('/\- wyrażenie przez Sejm RP zgody na dokonanie przez Prezydenta RP ratyfikacji ww\. Umowy\./i') ) {
		          suggestions['opis'] = opis;
		        }
		      }
		    }
		    
		    if(suggestions['opis']) {
			    var match = suggestions['opis'].match(/^- p(.*?)$/);
			    if( match ) suggestions['opis'] = 'P'+match[1];
			    
			    var match = suggestions['opis'].match(/^(.*?)\.$/);
			    if( !match ) suggestions['opis'] = suggestions['opis']+'.';
			    suggestions['opis'] = suggestions['opis'][0].toUpperCase()+suggestions['opis'].substr(1);
		    }
	    
	    } catch(e){
	      $('side_div').insert({top: '<p>Błąd parsowania</p>'});
	    }
    
    }
    
    // FORM
    
    var fields = $A();
    fields.push({name: 'druk_id', label: 'Druk', type: '_druk', value: this.data.druk_id, params: {notEmpty: true}});
    fields.push({name: 'autor_id', label: 'Autor', type: 'autor', value: this.data.autor_id});
    fields.push({name: 'typ_id', label: 'Typ', type: 'radio', options: _projekty_typy_options, value: this.data.typ_id});
    fields.push({name: 'tytul', label: 'Tytuł', type: 'text', value: this.data.tytul, suggestion: suggestions['tytul']});
    fields.push({name: 'opis', label: 'Opis', type: 'text', params: {rows: 6}, value: this.data.opis, suggestion: suggestions['opis']});
      
    this.form = new mForm('projekt_form', fields, {activateFirstInvalid: true});
    
    if( mBrowser.category.id=='doakceptu' && ( this.form.fields[3].getValue().match(/ratyfikacji/i) || this.form.fields[4].getValue().match(/ratyfikacji/i) ) ) {
	    this.form.fields[2].setValue(4);
    } 
  },
  pobierz_projekt: function(event){
       
    var inp = event.findElement('input');
    var projekt_id = inp.readAttribute('projekt_id');
    inp.disable();
    $S('graber/projekty/pobierz', projekt_id, function(result){
      mBrowser.refreshItem();
    });  
  },
  onDrukCick: function(obj){
    
    var el;
    if( Object.isElement(obj) ) { el = obj; } else { el = obj.findElement('._druk'); }
  
    var numer = el.readAttribute('numer');
    this.lightpicker = new Lightpicker('druki', {title: 'Wybierz druk', afterPick: function(params){
      this.form.fields[0].setValue(params);
    }.bind(this), suggestion: numer});
  },
  save: function(){
    if( mBrowser.enabled ) {
      var params = this.form.serialize();
      if( !params ) return false;
      
      params.id = this.id;
	    mBrowser.disable_loading();
	    this.btnSave.disable();
	    	    
	    $S('projekty/zapisz_wlasciwosci', params, this.onSave.bind(this), function(){
	      mBrowser.disable_loading
	      this.btnSave.enable();
	    }.bind(this));
	    
    }
  },
  onSave: function(data){
    if( data=='4' ) {
      mBrowser.enable_loading();
      $LICZNIKI.update();
      if( mBrowser.category.id=='doakceptu' ) {
        mBrowser.markAsDeleted(this.id);
        mBrowser.loadNextItem();
      }      
    } else alert('Projekt nie został zapisany');
    mBrowser.enable_loading();
    this.btnSave.enable();
  },
  regrab: function(){
    mBrowser.disable_loading();
    $S('graber/projekty/pobierz', this.id, function(result){
      alert('onRegrab');
      mBrowser.refreshItem();
    }.bind(this));
  }
});

var _druki_data = [];
var projekt;

var MBrowser = Class.create(MBrowser, {
  getListItemInnerHTML: function(data){
    return data['numer'] ? data['numer'] : '<i>Bez tytułu</i>';
  },
  afterCloseItem: function(){ $('side_div').update(''); }
});
var mBrowser;

$M.addInitCallback(function(){
  Event.observe(document, 'keypress', function(event){
	  if( event.ctrlKey && event.charCode==115 ) { projekt.save(); }
	  else if( event.ctrlKey && event.charCode==100 ) { projekt.onDrukCick( $('side_div').down('a._druk') ); }
	});
});
