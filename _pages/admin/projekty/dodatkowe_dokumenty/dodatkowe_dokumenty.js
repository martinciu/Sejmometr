var Item = Class.create({
  initialize: function(data){
    this.data = data;
    this.id = this.data.id;
   
    mBrowser.itemTitleUpdate(this.data.id);
    this.btnSave = mBrowser.addItemButton('save', 'Zapisz', this.save.bind(this));
    
    
    
    var fields = $A();
    fields.push({name: 'tytul', label: 'Tytuł', type: 'text', options: {rows: 3}, value: this.data.tytul});
    this.form = new mForm('item_form', fields, {activateFirstInvalid: true});
    
    
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
	    	    
	    $S('zapisz', params, this.onSave.bind(this), function(){
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
  },
});

var MBrowser = Class.create(MBrowser, {

  getListItemInnerHTML: function(data){
    return data['id'];
  },
  
  afterCloseItem: function(){ $('scribd').update(''); }
  
  
});
var item;
var mBrowser;

$M.addInitCallback(function(){
  Event.observe(document, 'keypress', function(event){
	  if( event.ctrlKey && event.charCode==115 ) druk.save();
	});
});
