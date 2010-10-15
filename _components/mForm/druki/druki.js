_mFormTypes.register('druki', Class.create(mFormField, {
  initialize: function($super, params){
    $super(params);
    this.validate();
  },
  getValueDiv: function(){
    this.valueDiv = new Element('p', {className: 'druki empty border'});
    
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
    if( value ) { for( var i=0; i<value.length; i++ ) this.addDruk(value[i]); }
  },
  addDruk: function(id){
    this.getDrukData(id, function(data){
	    this.valueDiv.removeClassName('empty');

      if( data.id.length!=5 ) { alert('Nieprawidłowy id druku'); }

      var li = new Element('li', {className: 'druk', druk_id: data.id}).update( docthumbs_create(data.dokument_id, 3) ).insert('<div class="content"><h4>'+data.numer+'</h4><p class="data">'+data.data+'</p><p>'+data.typ+' - '+data.autor+'</p><p class="tytul">'+data.tytul_oryginalny+'</p></div>');
      this.ul.insert(li);
      if( Object.isFunction(this.onDrukRender) ) this.onDrukRender(data);
      this.validate();
    }.bind(this));
  },
  getDrukData: function(id, callback){
    if( _druki_data ) {
      for( var i=0; i<_druki_data.length; i++ ) {
        if( _druki_data[i]['id']==id ) return callback(_druki_data[i]);
      }
    }
    $S('druki/mform_info', id, callback.bind(this));
  },
  add: function(){
    var suggestion;
    if( Object.isArray(this.suggestion) ) { suggestion = this.suggestion.shift(); }
    if( Object.isString(this.suggestion) ) { suggestion = this.suggestion; }
    this.lightpicker = new Lightpicker('druki', {title: 'Wybierz druk', afterPick: function(params){
      this.addDruk(params);
      if( Object.isFunction(this.onChange) ) this.onChange();
    }.bind(this), suggestion: suggestion});
  },
  remove: function(){
  
  },
  getValue: function(){
    var druki = this.ul.select('.druk');
    var result = $A();
    for( var i=0; i<druki.length; i++ ) {
      result.push( druki[i].readAttribute('druk_id') );
    }
    return result;
  },
  _validate: function(){
    if( this.notEmpty && this.getValue().length==0 ) { return false; } else { return true; }
  } 
}));