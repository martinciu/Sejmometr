var Druk = Class.create({
  initialize: function(data){
    this.data = data;
    this.id = this.data.id;
   
    mBrowser.itemTitleUpdate(this.data.numer);
    this.btnSave = mBrowser.addItemButton('save', 'Zapisz', this.save.bind(this));
    
    
    $$('#duplikaty_table .buttons input').invoke('observe', 'click', this.usun_projekt.bind(this));

    
    
    var fields = $A();
    fields.push({name: 'zalacznik', label: 'Załącznik', type: 'radio', options: [[0, 'Druk główny'], [1, 'Załącznik']], value: this.data.zalacznik});
    fields.push({name: 'data', label: 'Data', type: 'date', value: this.data.data});
    fields.push({name: 'typ_id', label: 'Typ', type: 'txt_local_completer', options: _druki_typy, value: this.data.typ_id});
    fields.push({name: 'autorzy', label: 'Autor', type: 'autorzy', value: this.data.autorzy});
    
    if( this.data.zalacznik=='0' ) {
      fields.push({name: 'zalaczniki', label: 'Załączniki', type: 'druki', value: this.data.zalaczniki});
      fields.push({name: 'projekty', label: 'Projekty (ręcznie)', type: 'projekty', value: this.data.projekty});
    }
    if( this.data.zalacznik=='1' ) {
      
      var guess = this.data.numer.match(/^do druku (.*?)$/i);
			if( guess ) guess = guess[1].replace('nr ', '');
      fields.push({name: 'druki_glowne', label: 'Druki główne', type: 'druki', value: this.data.druki_glowne, suggestion: guess, notEmpty: true});
      
    }
    
    this.form = new mForm('druk_form', fields, {activateFirstInvalid: true});
    
    
    
    // GUESSING
    
    if( this.data.zalacznik=='1' && this.data.typ_id=='0' ) {
      this.form.fields[2].input.addClassName('guess');
      this.form.fields[2].setValue(4);
    }
    
    var guess = this.data.tytul_oryginalny.match(/^stanowisko rządu/i);
    if( guess ) {
      if( this.data.typ_id!='7' ){
	      this.form.fields[2].setValue(7);
	      this.form.fields[2].input.addClassName('guess');
      }
      
      if( this.data.autorzy.length!=1 || this.data.autorzy[0]!='Rzad' ){
	      this.form.fields[3].setValue(['Rzad']);
	      this.form.fields[3].fields[0].input.addClassName('guess');
      }
    }
    
    var guess = this.data.tytul_oryginalny.match(/^rządowy projekt ustawy/i);
    if( guess ) {
      if( this.data.typ_id!='2' ){
	      this.form.fields[2].setValue(2);
	      this.form.fields[2].input.addClassName('guess');
      }
      
      if( this.data.autorzy.length!=1 || this.data.autorzy[0]!='Rzad' ){
	      this.form.fields[3].setValue(['Rzad']);
	      this.form.fields[3].fields[0].input.addClassName('guess');
      }
    }
    
    var guess = this.data.tytul_oryginalny.match(/^senacki projekt ustawy/i);
    if( guess ) {
      if( this.data.typ_id!='2' ){
	      this.form.fields[2].setValue(2);
	      this.form.fields[2].input.addClassName('guess');
      }
      
      if( this.data.autorzy.length!=1 || this.data.autorzy[0]!='Senat' ){
	      this.form.fields[3].setValue(['Senat']);
	      this.form.fields[3].fields[0].input.addClassName('guess');
      }
    }
    
    var guess = this.data.tytul_oryginalny.match(/^uchwała senatu/i);
    if( guess ) {
      if( this.data.typ_id!='8' ){
	      this.form.fields[2].setValue(8);
	      this.form.fields[2].input.addClassName('guess');
      }
      
      if( this.data.autorzy.length!=1 || this.data.autorzy[0]!='Senat' ){
	      this.form.fields[3].setValue(['Senat']);
	      this.form.fields[3].fields[0].input.addClassName('guess');
      }
    }
    
    var guess = this.data.tytul_oryginalny.match(/^przedstawiony przez prezydium sejmu wniosek/i);
    if( guess ) {
      if( this.data.typ_id!='12' ){
	      this.form.fields[2].setValue(12);
	      this.form.fields[2].input.addClassName('guess');
      }
      
      if( this.data.autorzy.length!=1 || this.data.autorzy[0]!='Prezydium' ){
	      this.form.fields[3].setValue(['Prezydium']);
	      this.form.fields[3].fields[0].input.addClassName('guess');
      }
    }
    
    var guess = this.data.tytul_oryginalny.match(/^sprawozdanie/i);
    if( guess ) {
      if( this.data.typ_id!='1' ){
	      this.form.fields[2].setValue(1);
	      this.form.fields[2].input.addClassName('guess');
      }
    }
    
    var guess = this.data.tytul_oryginalny.match(/^Dodatkowe sprawozdanie/i);
    if( guess ) {
      if( this.data.typ_id!='9' ){
	      this.form.fields[2].setValue(9);
	      this.form.fields[2].input.addClassName('guess');
      }
    }
    
    var guess = ( this.data.tytul_oryginalny.match(/^komisyjny projekt ustawy/i) || this.data.tytul_oryginalny.match(/^poselski projekt ustawy/i) );
    if( guess ) {
      if( this.data.typ_id!='2' ){
	      this.form.fields[2].setValue(2);
	      this.form.fields[2].input.addClassName('guess');
      }
    }
    
    
    var guess = this.data.tytul_oryginalny.match(/^Przedstawiony przez Prezydium Sejmu projekt uchwały/i);
    if( guess ) {
      if( this.data.typ_id!='11' ){
	      this.form.fields[2].setValue(11);
	      this.form.fields[2].input.addClassName('guess');
      }
      
      if( this.data.autorzy.length!=1 || this.data.autorzy[0]!='Prezydium' ){
	      this.form.fields[3].setValue(['Prezydium']);
	      this.form.fields[3].fields[0].input.addClassName('guess');
      }
    }
    
    var guess = this.data.tytul_oryginalny.match(/^Lista kandydatów/i);
    if( guess ) {
      if( this.data.typ_id!='32' ){
	      this.form.fields[2].setValue(32);
	      this.form.fields[2].input.addClassName('guess');
      }
      
      if( this.data.autorzy.length!=1 || this.data.autorzy[0]!='Poslowie' ){
	      this.form.fields[3].setValue(['Poslowie']);
	      this.form.fields[3].fields[0].input.addClassName('guess');
      }
    }
    
    
    
    
    
    //
    
    this.form.activateFirstInvalid();
    
    $('scribd').height_control(); 
    if( this.data.dokument_id ) {
	    if( this.data.dokument_akcept ) {
		    
		    this.doc_img = new Element('img', {src: '/d/0/'+this.data.dokument_id+'.gif', className: '_height_controll'}).hide().observe('load', function(){
		      this.doc_img.height_control().show();
		    }.bind(this)).observe('click', function(){
		      this.scribd_doc = scribd.Document.getDoc(this.data.scribd_doc_id, this.data.scribd_access_key);
					this.scribd_doc.addParam('jsapi_version', 1);
					this.scribd_doc.addParam('height', mBrowser.div.getHeight());
					this.scribd_doc.addEventListener( 'iPaperReady', function(){
					  this.scribd_doc.api.setZoom(.85);
					}.bind(this) );
					this.scribd_doc.write('scribd');
		    }.bind(this));
		    
		    $('scribd').height_control().update(this.doc_img);
				
			} else {
			  this.showDocumentMessage('<a href="/admin/dokumenty_obrabiane#id='+this.data.dokument_id+'">Dokument</a> jest obrabiany');
			}
		} else {
		  this.showDocumentMessage('Oczekiwanie na <a href="/admin/druki/oczekujace#id='+this.id+'">dokument</a>');
		}

  },
  showDocumentMessage: function(msg){
    $('scribd').update('<p class="msg">'+msg+'</p>').height_control();
  },
  save: function(){
    if( mBrowser.enabled ) {
      var params = this.form.serialize();
      if( !params ) return false;
      
      params.id = this.id;
	    mBrowser.disable_loading();
	    this.btnSave.disable();
	    	    
	    $S('druki/zapisz', params, this.onSave.bind(this), function(){
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
    } else alert('Druk nie został zapisany');
    mBrowser.enable_loading();
    this.btnSave.enable();
  },
  usun_projekt: function(event){
    if( confirm('Czy na pewno chcesz usunąć ten projekt?') ) {
	    var inp = event.findElement('input');
	    var projekt_id = inp.readAttribute('projekt_id');
	    inp.disable();
	    $S('projekty/usun', projekt_id, function(result){
	      location.reload();
	    });  
    }
  },
});

var MBrowser = Class.create(MBrowser, {

  getListItemInnerHTML: function(data){
    return data['numer'];
  },
  
  afterCloseItem: function(){ $('scribd').update(''); }
  
  
});
var _projekty_data = [];
var _druki_data = [];
var druk;
var mBrowser;

$M.addInitCallback(function(){
  Event.observe(document, 'keypress', function(event){
	  if( event.ctrlKey && event.charCode==115 ) druk.save();
	});
});
