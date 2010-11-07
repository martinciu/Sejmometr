var Item = Class.create({
  initialize: function(data){
    this.data = data;
    this.id = this.data.id;
    
    this.btnCopy = mBrowser.addItemButton('copy', 'Kopiuj tekst', this.kopiuj_tekst.bind(this));
    this.btnSave = mBrowser.addItemButton('save', 'Zapisz', this.save.bind(this));
    
    $('side_div').update( this.data.text ).height_control();
    
    var fields = $A();
    fields.push({name: 'skrot', label: 'Skrót', type: 'text', value: this.data.skrot, params: {rows: 12}});      
    this.form = new mForm('item_form', fields, {activateFirstInvalid: true});

  },
  save: function(){
    if( mBrowser.enabled ) {
      var params = this.form.serialize();
      if( !params ) return false;
      
      params.id = this.id;
	    mBrowser.disable_loading();
	    this.btnSave.disable();
	    	    
	    $POST_SERVICE('zapisz', params, this.onSave.bind(this), function(){
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
    } else alert("Element nie został zapisany/nSpróbuj ponownie");
    mBrowser.enable_loading();
    this.btnSave.enable();
  },
  kopiuj_tekst: function(){
    var s = document.getSelection().strip();
    if( s!='' ) {
      this.form.fields[0].setValue(s);
    }
  }
});

var MBrowser = Class.create(MBrowser, {
  getListItemInnerHTML: function(data){
    return data.id;
  },
  afterCloseItem: function(){ $('side_div').update(''); }
});

var item;
var mBrowser;

$M.addInitCallback(function(){
  Event.observe(document, 'keypress', function(event){
	  if( event.ctrlKey && event.charCode==115 ) { item.save(); }
	  else if( event.ctrlKey && event.charCode==100 ) { item.kopiuj_tekst(); }
	});
});
