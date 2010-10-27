var Druk = Class.create({
  initialize: function(data){
    this.data = data;
    this.id = this.data.id;
    this._miesiace = ['stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca', 'lipca', 'sierpnia', 'września', 'października', 'listopada', 'grudni'];
    
    mBrowser.itemTitleUpdate(this.data.id);
    
    $$('#druk .tytul').first().update( this.data.tytul );
    this.btnSave = mBrowser.addItemButton('save', 'Zapisz', this.save.bind(this));
    
    if( data.txt ) {
      var regexp = new RegExp('([0-9]+) ('+this._miesiace.join('|')+') ([0-9]{4})', 'i');
      var data_suggestion = this.data.txt.match(regexp);
      if(data_suggestion) {
        var m = String( this._miesiace.indexOf(data_suggestion[2])+1 );
        if( m.length==1 ) m = '0'+m;
        data_suggestion = data_suggestion[3]+'-'+m+'-'+data_suggestion[1];
      }
    }
    
    
    
    var fields = $A();
    fields.push({name: 'data', label: 'Data', type: 'date', value: this.data.data, suggestion: data_suggestion});
    fields.push({name: '', label: 'Projekty (Sejm)', type: 'projekty', value: this.data.projekty});
  
    
    this.form = new mForm('druk_form', fields, {activateFirstInvalid: true});    
    this.form.activateFirstInvalid();

    
    this.form.fields[1].div.addClassName('disabled').down('.buttons').hide();
    this.form.fields[0].input.activate();
    
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
	    	    
	    $S('bas/zapisz', params, this.onSave.bind(this), function(){
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
    } else alert('Element nie został zapisany');
    mBrowser.enable_loading();
    this.btnSave.enable();
  }
});

var MBrowser = Class.create(MBrowser, {

  getListItemInnerHTML: function(data){
    return data['id'];
  },
  
  afterCloseItem: function(){ $('scribd').update(''); }
  
  
});
var _projekty_data = [];
var druk;
var mBrowser;

$M.addInitCallback(function(){
  Event.observe(document, 'keypress', function(event){
	  if( event.ctrlKey && event.charCode==115 ) { druk.save(); }
	  else if( event.ctrlKey && event.charCode==100 ) { druk.form.fields[1].add(); }
	});
});
