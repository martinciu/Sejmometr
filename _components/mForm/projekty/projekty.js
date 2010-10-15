_mFormTypes.register('projekty', Class.create(mFormField, {
  initialize: function($super, params){
    $super(params);
    this.validate();
  },
  getValueDiv: function(){
    this.valueDiv = new Element('p', {className: 'items empty border'});
    
    var btns = new Element('div', {className: 'buttons'}).insert( $ANCHOR('-', {className: 'button'}).observe('click', this.remove.bind(this, this.fieldsCounter)) ).insert( $ANCHOR('+', {className: 'button'}).observe('click', this.add.bind(this)) );
    	  
    this.ul = new Element('ul');
    return this.valueDiv.insert(btns).insert(this.ul);
  },
  onInit: function(params){
    this.suggestion = params.suggestion;
    this.notEmpty = params.notEmpty;
    this.onChange = params.onChange;
  },
  setValue: function(value){
    this.ul.update('');
    if( value ) { for( var i=0; i<value.length; i++ ) this.addItem(value[i]); }
  },
  addItem: function(id){
    this.getItemData(id, function(data){
	    this.valueDiv.removeClassName('empty');

      if( data.id.length!=5 ) { alert('Nieprawidłowy id projektu'); }

      var li = new Element('li', {className: 'item', projekt_id: data.id}).insert('<h4>'+data.numer+'</h4><p>'+data.autor+' - '+data.tytul+'</p>');
      this.ul.insert(li);
      if( Object.isFunction(this.onItemRender) ) this.onItemRender(data);
      this.validate();
    }.bind(this));
  },
  getItemData: function(id, callback){
    if( _projekty_data ) {
      for( var i=0; i<_projekty_data.length; i++ ) {
        if( _projekty_data[i]['id']==id ) return callback(_projekty_data[i]);
      }
    }
    $S('projekty/mform_info', id, callback.bind(this));
  },
  add: function(){
    var suggestion;
    if( Object.isArray(this.suggestion) ) { suggestion = this.suggestion.shift(); }
    if( Object.isString(this.suggestion) ) { suggestion = this.suggestion; }
    this.lightpicker = new Lightpicker('projekty-druk_numer', {title: 'Wybierz numer druku', afterPick: function(params){
      this.addItem(params);
      if( Object.isFunction(this.onChange) ) this.onChange();
    }.bind(this), suggestion: suggestion});
  },
  remove: function(){
  
  },
  getValue: function(){
    var items = this.ul.select('.item');
    var result = $A();
    for( var i=0; i<items.length; i++ ) {
      result.push( items[i].readAttribute('projekt_id') );
    }
    return result;
  },
  _validate: function(){
    if( this.notEmpty && this.getValue().length==0 ) { return false; } else { return true; }
  } 
}));