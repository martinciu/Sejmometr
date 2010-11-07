var Punkt = Class.create({
  initialize: function(data){
    this.data = data;
    this.id = this.data.id;
    
    this.btnSave = mBrowser.addItemButton('save', 'Zapisz', this.save.bind(this));
    
    var suggestions = this.data.sejm_id.substr(this.data.sejm_id.lastIndexOf('druk')).match(/([0-9]+)/gi);
    
    var fields = $A();
    fields.push({name: 'typ_id', label: 'Typ', type: 'radio', options: _punkty_typy, value: this.data.typ_id});
    fields.push({name: 'druki', label: 'Druki', type: 'druki', value: this.data.druki, suggestion:suggestions, onChange: function(){
      this.form.fields[0].setValue('1');
    }.bind(this)});
      
    this.form = new mForm('punkt_form', fields, {activateFirstInvalid: true});
    
    this.form.activateFirstInvalid();
    
    this.ostatnie_punkty_pobrane = false;
    $('side_div').update('<a id="ostatnie_punkty_a" href="#" onclick="return false;">Ostatnie punkty</a><ul id="ostatnie_punkty_ul" style="display: none;"></ul>');
    $('ostatnie_punkty_a').observe('click', this.ostatniePunktyClick.bind(this));
    // this.ostatniePunktyClick();
    
    
	  
	  new Effect.Tween(null, $('side_div').scrollTop, 0, {duration: .1}, function(p){
	    $('side_div').scrollTop = p;
	  });
		  
    for( var i=0; i<_wypowiedzi.length; i++ ) {
      var s = _wypowiedzi[i]['typ']=='3' ? '<b>Marszałek</b>' : '<a href="#" onclick="return false;">'+String(_wypowiedzi[i]['nazwa'])+'</a>';
      $('side_div').insert('<hr/>'+s+'<br/><div wypowiedz_id="'+_wypowiedzi[i]['id']+'">'+_wypowiedzi[i]['text']+'</div>');
    }    
    $('side_div').height_control().select('._druk').invoke('observe', 'click', function(event){
      var numer = event.findElement('._druk').readAttribute('numer');
      this.lightpicker = new Lightpicker('druki', {title: 'Wybierz druk', afterPick: function(params){
	      this.form.fields[0].setValue('1');
	      this.form.fields[1].addDruk(params);
	    }.bind(this), suggestion: numer});
    }.bind(this));
    
    this.btnSzukajNazwa = $('punkt_buttons').down('.szukaj_nazwa');
    if( this.btnSzukajNazwa ) {
      this.btnSzukajNazwa.observe('click', function(){
        this.lightpicker = new Lightpicker('druki-tytul', {className: 'druki', title: 'Wybierz druk (po tytule)', afterPick: function(params){
		      this.form.fields[0].setValue('1');
		      this.form.fields[1].addDruk(params);
		    }.bind(this), loader_params: {date_gt: this.data.data}});
      }.bind(this));
    }
  },
  
  ostatniePunktyClick: function(){
    $('ostatnie_punkty_ul').toggle();
    
    if(!this.ostatnie_punkty_pobrane) {
      $S('ostatnie_punkty', [this.data.posiedzenie_id, this.data.data], function(data){
		    for( var i=0; i<data.length; i++ ) {
		      var li = new Element('li').update( '<b>'+data[i][0]+'</b> '+data[i][1] );
		      $('ostatnie_punkty_ul').insert( li );
		    }
		    $('ostatnie_punkty_ul').select('._druk').invoke('observe', 'click', function(event){
		      var numer = event.findElement('._druk').readAttribute('numer');
		      this.lightpicker = new Lightpicker('druki', {title: 'Wybierz druk', afterPick: function(params){
			      this.form.fields[0].setValue('1');
			      this.form.fields[1].addDruk(params);
			    }.bind(this), suggestion: numer});
		    }.bind(this));
		    this.ostatnie_punkty_pobrane = true;
      }.bind(this));
    }
  },
  
  save: function(){
    if( mBrowser.enabled ) {
      var params = this.form.serialize();
      if( !params ) return false;
      
      params.id = this.id;
	    mBrowser.disable_loading();
	    this.btnSave.disable();
	    	    
	    $S('punkty_glosowania/zapisz', params, this.onSave.bind(this), function(){
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
    } else alert('Punkt nie został zapisany');
    mBrowser.enable_loading();
    this.btnSave.enable();
  }
});

var MBrowser = Class.create(MBrowser, {
  getListItemInnerHTML: function(data){
    return data['sejm_id'].substr(0, 125);
  },
  afterCloseItem: function(){ $('side_div').update(''); }
});

var _druki_data = [];
var _wypowiedzi = [];
var punkt;
var mBrowser;

$M.addInitCallback(function(){
  Event.observe(document, 'keypress', function(event){
	  if( event.ctrlKey && event.charCode==115 ) { punkt.save(); }
	  else if( event.ctrlKey && event.charCode==100 ) { punkt.form.fields[1].add(); }
	});
});
