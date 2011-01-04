var Item = Class.create({
  initialize: function(data, _typy_options){    
    this.data = data;
    this.id = this.data.id;
    
    this.btnSave = mBrowser.addItemButton('save', 'Zapisz', this.save.bind(this));
    
    
    var fields = $A();
    fields.push({name: 'tytul', label: 'Tytuł', type: 'text', options: {rows: 5}, value: this.data.tytul, suggestion: this.data['tytul_suggestion']});
    
    this.form = new mForm('item_form', fields, {activateFirstInvalid: true});
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
  ustaw_tytul: function(){
    this.form.fields[0].setValue( $$('#projekty .projekt .t a').first().innerHTML )
  }
});

var MBrowser = Class.create(MBrowser, {
  getListItemInnerHTML: function(data){
    return data['id'];
  },
});

var item;
var mBrowser;

$M.addInitCallback(function(){
  Event.observe(document, 'keypress', function(event){
	  if( event.ctrlKey && event.charCode==115 ) { item.save(); }
	  else if( event.ctrlKey && event.charCode==100 ) { item.ustaw_tytul(); }
	});
});
