var Item = Class.create({
  initialize: function(data){
    this.data = data;
    this.id = this.data.id;
    
    this.btnSave = mBrowser.addItemButton('save', 'Zapisz', this.save.bind(this));
    
    var parts = this.data.autor.split(' ');
    var split = parts.length-2;
    var _funkcja = $A();
    var _nazwa = $A();
    for( var i=0; i<split; i++ ) _funkcja.push( parts[i] ); 
    for( var i=split; i<parts.length; i++ ) _nazwa.push( parts[i] ); 
     
    var fields = $A();
    fields.push({name: 'funkcja', label: 'Funkcja', type: 'varchar', value: _funkcja.join(' ')});  
    fields.push({name: 'nazwa', label: 'Nazwa', type: 'varchar', value: _nazwa.join(' ')});
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
    if( data=='2' ) {
      alert('Nazwa funkcji już istnieje');
    } else if( data=='3' ) {
      alert('Nazwa człowieka już istnieje');
    } else if( data=='4' ) {
      alert('ID człowieka już istnieje');
    } else if( data=='5' ) {
      mBrowser.enable_loading();
      $LICZNIKI.update();
      if( mBrowser.category.id=='nowe' ) {
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
    return data['autor'];
  },
  afterCloseItem: function(){ $('side_div').update(''); }
});

var item;
var mBrowser;

$M.addInitCallback(function(){
  Event.observe(document, 'keypress', function(event){
	  if( event.ctrlKey && event.charCode==115 ) { item.save(); }
	});
});
