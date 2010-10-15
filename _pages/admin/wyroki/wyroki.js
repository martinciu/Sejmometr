var Wyrok = Class.create({
  _miesiace: ['stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca', 'lipca', 'sierpnia', 'września', 'października', 'listopada', 'grudnia'],
  initialize: function(data){
    this.data = data;
    this.id = this.data.id;
    
    mBrowser.itemTitleUpdate(this.data.numer);
    this.btnSave = mBrowser.addItemButton('save', 'Zapisz', this.save.bind(this));
    
    
        
    var fields = $A();
    fields.push({name: 'data', label: 'Data', type: 'date', value: this.data.data, suggestion: _sug_data});
    fields.push({name: 'wynik', label: 'Wynik', type: 'radio', value: this.data.wynik, options: _wyroki_typy});
    this.form = new mForm('wyrok_form', fields, {activateFirstInvalid: true});



		var match = this.data.tytul.match(/([0-9]{1,2}) (.*?) ([0-9]{4})/i);
    if( match ) {
      var m = this._miesiace.indexOf( match[2] );
      if( m!=-1 ) {
        m = String(m+1);
        if( m.length==1 ) m = '0'+m;
        if( match[1].length==1 ) match[1] = '0'+match[1];
        var _sug_data = match[3]+'-'+m+'-'+match[1];
      }
    }
    
    if( this.data.data=='0000-00-00' && _sug_data ) {
      this.form.fields[0].input.value = _sug_data;
      this.form.fields[0].div.addClassName('suggestion');
      this.form.fields[0].validate();
    }



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
			  this.showDocumentMessage('<a href="/admin/dokumenty_obrabiane#id='+this.data.dokument_id+'">Dokument</a> jest w obróbce');
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
	    	    
	    $S('wyroki/zapisz', params, this.onSave.bind(this), function(){
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
    } else alert('Wyrok nie został zapisany');
    mBrowser.enable_loading();
    this.btnSave.enable();
  }
});

var MBrowser = Class.create(MBrowser, {

  getListItemInnerHTML: function(data){
    return data['numer'];
  },
  
  afterCloseItem: function(){ $('scribd').update(''); }
  
  
});
var wyrok;
var mBrowser;

$M.addInitCallback(function(){
  Event.observe(document, 'keypress', function(event){
	  if( event.ctrlKey && event.charCode==115 ) wyrok.save();
	});
});
