_mFormTypes.register('autor', Class.create(_mFormTypes.getClass('txt_local_completer'), {
  initialize: function($super, params){
    $super(params);
    this.input.observe('keypress', function(event){
      switch( event.keyCode ) {
        case 112: { // F1
          new Lightpicker('poslowie-partie', {title: 'Wybierz posła', afterPick: function(id){
            this.setValue(id);
          }.bind(this)});
          break;
        }
      }
    }.bind(this));
  },
  onInit: function(params){
    this.options = _autorzy_options;
    this.onValidate = params.onValidate;
  },
  _validate: function($super){
    var result = $super();
    if( result ) { this.input.removeClassName('validate_error'); }
    else { this.input.addClassName('validate_error'); }
    return result;
  }
}));

_mFormTypes.register('autorzy', Class.create(mFormField, {
  fieldsCounter: 0,
  initialize: function($super, params){
    $super(params);
    if( this.fields.length==0 ) this.addField();
  },
  build: function($super){
    $super();
    this.fields = $A();
  },
  getValueDiv: function(){
    this.valuesDiv = new Element('div');
    return this.valuesDiv;
  },
  addField: function(value){
    if( this.fieldsCounter<3 ) {
	    var Class = _mFormTypes.getClass('autor');
	    var field = new Class({name: this.fieldsCounter, value: value});
	    field.onValidate = this.validate.bind(this);
	    var btns = new Element('div', {className: 'buttons'});
	    var remove_btn = $ANCHOR('-', {className: 'button'}).observe('click', this.removeField.bind(this, this.fieldsCounter));
	    var add_btn = $ANCHOR('+', {className: 'button'}).observe('click', this.addField.bind(this));
	    field.input.addClassName('b').insert({after: btns.insert(remove_btn).insert(add_btn)});
	    this.fields.push( field );
	    this.valuesDiv.insert( field.valueDiv.addClassName('multi') );
	    field.input.activate();
	    this.fieldsCounter++;
	    
	    this.validate();
    }
  },
  getFieldIterator: function(name){
    for( var i=0; i<this.fields.length; i++ ) {
      if( this.fields[i]['name']==name ) return i;
    }
  },
  removeField: function(name){
    if( this.fieldsCounter>1 ) {
      var iterator = this.getFieldIterator(name);
      this.fields[iterator].valueDiv.remove();
      this.fields.splice(iterator, 1);
      this.fieldsCounter--;
    }
  },
  setValue: function(value){
    this.fields = $A();
    this.valuesDiv.update('');
    this.fieldsCounter = 0;
    
    if( Object.isArray(value) ) {
      var length = Math.min( value.length, 3 );
      for( var i=0; i<length; i++ ) {
        if( value[i] ) this.addField(value[i]);
      }
    }
  },
  validate: function(){
    var validate = this._validate();
    if( validate ) { this.labelDiv.removeClassName('validate_error'); }
    else { this.labelDiv.addClassName('validate_error'); }
    return validate;
  },
  _validate: function(){
    if( this.fields.length>0 ) {
	    var result = true;
	    for( var i=0; i<this.fields.length; i++ ) {
	      var field = this.fields[i];
	      result = result && field._validate();
	    }
	    return result;
    }
  },
  getValue: function(){
    var result = $A();
    for( var i=0; i<this.fields.length; i++ ) {
      result.push( this.fields[i].getValue() );
    }
    return result;
  }
}));