var Item = Class.create({
  initialize: function(data, nazwa){
    
    this.data = data;
    this.nazwa = nazwa;
    this.id = this.data.id;
    
    this.btnSave = mBrowser.addItemButton('save', 'Zapisz', this.save.bind(this));
    
    
    var fields = $A();
    fields.push({name: 'nazwa', label: 'Nazwa', type: 'text', options: {rows: 1}, value: this.nazwa[0]});
    fields.push({name: 'dopelniacz', label: 'Dopełniacz', type: 'text', options: {rows: 1}, value: this.nazwa[1]});
    
    this.form = new mForm('item_form', fields, {});
    this.form.fields[0].input.readOnly = true;
    this.form.fields[1].input.readOnly = true;
  },
  save: function(){
    if( mBrowser.enabled ) {
      if( this.form.fields[0].input.value=='' || this.form.fields[1].input.value=='' ) return alert('Brak opisu');
      
	    mBrowser.disable_loading();
	    this.btnSave.disable();
	    	    
	    $S('zapisz', this.id, this.onSave.bind(this), function(){
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
