var mFormLocalCompleter = Class.create(Autocompleter.Local, {
  setOptions: function(options) {
    this.options = Object.extend({
      choices: 10,
      partialSearch: true,
      partialChars: 2,
      ignoreCase: true,
      fullSearch: false,
      selector: function(instance) {
        var ret       = []; // Beginning matches
        var partial   = []; // Inside matches
        var entry     = instance.getToken();
        var count     = 0;

        for (var i = 0; i < instance.options.array.length &&  
          ret.length < instance.options.choices ; i++) { 
          
          var elem_id = instance.options.array[i][0];
          var elem = instance.options.array[i][1];
          var foundPos = instance.options.ignoreCase ? 
            elem.toLowerCase().indexOf(entry.toLowerCase()) : 
            elem.indexOf(entry);

          while (foundPos != -1) {
            if (foundPos == 0 && elem.length != entry.length) { 
              ret.push("<li value=\""+elem_id+"\"><strong>" + elem.substr(0, entry.length) + "</strong>" + 
                elem.substr(entry.length) + "</li>");
              break;
            } else if (entry.length >= instance.options.partialChars && 
              instance.options.partialSearch && foundPos != -1) {
              if (instance.options.fullSearch || /\s/.test(elem.substr(foundPos-1,1))) {
                partial.push("<li value=\""+elem_id+"\">" + elem.substr(0, foundPos) + "<strong>" +
                  elem.substr(foundPos, entry.length) + "</strong>" + elem.substr(
                  foundPos + entry.length) + "</li>");
                break;
              }
            }

            foundPos = instance.options.ignoreCase ? 
              elem.toLowerCase().indexOf(entry.toLowerCase(), foundPos + 1) : 
              elem.indexOf(entry, foundPos + 1);

          }
        }
        if (partial.length)
          ret = ret.concat(partial.slice(0, instance.options.choices - ret.length))
        return "<ul>" + ret.join('') + "</ul>";
      }
    }, options || { });
  }
});


var mFormTypes = Class.create({
  initialize: function(params){
    this.types = $A();
  },
  register: function(type, class){
    this.types.push([type, class]);
  },
  getClass: function(type){
    for( var i=0; i<this.types.length; i++ ) {
      if( this.types[i][0]==type ) return this.types[i][1];
    }
  }
});
var _mFormTypes = new mFormTypes();











var mFormField = Class.create({
  initialize: function(params){
    this.name = params.name;
    this.params = params.params;
    this.suggestion = params.suggestion;
    this.div = new Element('div', {className: 'field'});
    
    if( Object.isFunction(this.onInit) ) this.onInit(params);
    this.build();
    
    this.setLabel( params.label );
    if( params.value ) { this.setValue( params.value ); } else {
      if( params.suggestion ) {
        this.div.addClassName('suggestion');
        this.setValue(params.suggestion);
      }
    }
  }, 
  setValue: function(value){
    this.value = value;
    if(this.input) this.input.value = value;
    this.validate();
  },
  setLabel: function(label){
    this.label = label;
    if(this.labelDiv) this.labelDiv.update(label);
  },
  build: function(){
    this.div.insert( this.getLabelDiv().addClassName('label') );
    this.div.insert( this.getValueDiv().addClassName('value') );
  },
  getLabelDiv: function(){
    var p = new Element('p');
    this.labelDiv = new Element('label', {className: 'label'});
    return p.insert(this.labelDiv);
  },
  getValueDiv: function(){
    return new Element('p');
  },
  getValue: function(){
    return this.value;
  },
  validate: function(){
    if( Object.isFunction(this.onValidate) ) {
      return this.onValidate();
    } else {
	    var validate = this._validate();
	    if( validate ) { this.div.removeClassName('validate_error'); }
	    else { this.div.addClassName('validate_error'); }
	    return validate;
    }
  },
  _validate: function(){ return true; }
});

_mFormTypes.register('varchar', Class.create(mFormField, {
  default_maxlength: 1000,
  getValueDiv: function(){
    var p = new Element('p');
    this.input = new Element('input', {name: this.name, type: 'text', className: 'value'}).observe('blur', this.validate.bind(this));
    return p.insert(this.input);
  },
  getValue: function(){
    return $F(this.input);
  }
}));

_mFormTypes.register('text', Class.create(mFormField, {
  default_maxlength: 1000,
  getValueDiv: function(){
    var p = new Element('p');
    this.input = new Element('textarea', {name: this.name, type: 'text', className: 'value', rows: this.rows}).observe('blur', this.validate.bind(this));
    return p.insert(this.input);
  },
  getValue: function(){
    return $F(this.input);
  },
  initialize: function($super, params){
    this.rows = 3;
    if( params.params && params.params.rows ) this.rows = params.params.rows;
    $super(params);
  }
}));

_mFormTypes.register('integer', Class.create(mFormField, {
  default_maxlength: 1000,
  getValueDiv: function(){
    var p = new Element('p');
    this.input = new Element('input', {name: this.name, type: 'text', className: 'value'}).observe('blur', this.validate.bind(this));
    return p.insert(this.input);
  },
  getValue: function(){
    return $F(this.input);
  }
}));

_mFormTypes.register('date', Class.create(_mFormTypes.getClass('varchar'), {
  _miesiace: ['stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca', 'lipca', 'sierpnia', 'września', 'października', 'listopada', 'grudnia'],
  initialize: function(params){
    this.name = params.name;
    this.params = params.params;
    this.suggestion = params.suggestion;
    this.div = new Element('div', {className: 'field'});
    
    if( Object.isFunction(this.onInit) ) this.onInit(params);
    this.build();
    
    this.setLabel( params.label );
    if( params.value!='0000-00-00' && params.value!='' ) { this.setValue( params.value ); } else {
      if( params.suggestion ) {
        this.div.addClassName('suggestion');
        this.setValue(params.suggestion);
      }
    }
  },
  getValueDiv: function(){
    var p = new Element('p');
    this.input = new Element('input', {name: this.name, type: 'text', className: 'value date', MAXLENGTH: '10'}).observe('blur', this.validate.bind(this)).observe('keyup', this.update_display.bind(this)).observe('change', this.update_display.bind(this));
    this.display = new Element('span');
    return p.insert(this.input).insert(this.display);
  },
  update_display: function(){
    parts = $F(this.input).strip().split('-');
    if( parts.length==3 ) {
      var m = Number(parts[1])-1;
      if( m>=0 && m<=12 ) { this.display.update( Number(parts[2])+' '+this._miesiace[m]+' '+Number(parts[0]) ); }
      else { this.display.update(''); }
    }
  },
  _validate: function(){
    return Boolean( $F(this.input).match(/^(19|20)\d\d([- /.])(0[1-9]|1[012])\2(0[1-9]|[12][0-9]|3[01])$/) )
  },
  setValue: function($super, value){
    $super(value);
    this.update_display();
  }
}));

_mFormTypes.register('select', Class.create(mFormField, {
  getValueDiv: function(){
    var p = new Element('p');
    
    this.input = new Element('select', {name: this.name, className: 'value'});
    for( var i=0; i<this.options.length; i++ ) {
      var option = new Element('option', {value: this.options[0]}).update( this.options[i][1] );
      this.input.insert(option);
    } 
    
    return p.insert(this.input);
  },
  onInit: function(params){
    this.options = params.options;
  }
}));

_mFormTypes.register('radio', Class.create(mFormField, {
  getValueDiv: function(){
    this.inputs = $A();
    var p = new Element('p', {className: 'radios border'});
    for( var i=0; i<this.options.length; i++ ) {
      var label = new Element('label', {className: 'radio'});
      if( this.params && this.params.option_width ) label.setStyle({width: this.params.option_width});
      var input = new Element('input', {name: this.name, type: 'radio', value: this.options[i][0]}).observe('change', this.validate.bind(this));
      var span = new Element('span').update( this.options[i][1] );
      
      this.inputs.push(input);
      label.insert(input);
      label.insert(span);
      
      p.insert(label);
    }    
    return p;
  },
  onInit: function(params){
    this.options = params.options;
  },
  setValue: function(value){
    this.value = value;
    for( var i=0; i<this.inputs.length; i++ ) {
      if( this.inputs[i].value==value ) { this.inputs[i].checked = true; }
    }
    this.validate();
  },
  getValue: function(){
    for( var i=0; i<this.inputs.length; i++ ) {
      if( this.inputs[i].checked ) { return this.inputs[i].value; }
    }
    return false;
  },
  _validate: function(){
    return this.getValue()!==false;
  }
}));

_mFormTypes.register('txt_local_completer', Class.create(_mFormTypes.getClass('varchar'), {
  getValueDiv: function($super){
    this.valueDiv = $super();
        
    var autocompleterDiv = new Element('div', {className: 'autocomplete'});
    this.input.insert({after: autocompleterDiv});
    this.autocompleter = new mFormLocalCompleter(this.input, autocompleterDiv, this.options, {updateElement: function(li){
      this.setValue( li.readAttribute('value') );
    }.bind(this)});
    
    return this.valueDiv;
  },
  onInit: function(params){
    this.options = params.options;
  },
  setValue: function(value){
    this.value = value;
    this.input.value = this.getOptionLabel(value);
    this.validate();
  },
  getOptionLabel: function(id){
    for( var i=0; i<this.options.length; i++ ) if( this.options[i][0]==id ) return this.options[i][1];
    return '';
  },
  getOptionId: function(label){
    for( var i=0; i<this.options.length; i++ ) if( this.options[i][1]==label ) return this.options[i][0];
    return false;
  },
  _validate: function(){
    this.id = this.getOptionId( $F(this.input) );
    return Boolean(this.id);
  },
  getValue: function(){
    return this.value;
  }
}));











var mForm = Class.create({  
  initialize: function(div, fields_data, params){
    this.form = new Element('form');
    this.form.onSubmit = function(){return false};
    this.div = $(div).addClassName('mForm').update( this.form );
    this.fields = $A();
    
    this._activateFirstInvalid = params.activateFirstInvalid;
    
    this.addFields( fields_data );
  },
  addFields: function(fields_data){
	  for(var i=0; i<fields_data.length; i++) { this.addField(fields_data[i]); }
	  this.activateFirstInvalid();
	  
  },
  activateFirstInvalid: function(){
    if( this._activateFirstInvalid ) {
	    var el = this.div.down('.validate_error input.value');
	    if( el ) { el.activate(); } else {
	      var el = this.div.down('input.value.validate_error');
	      if( el ) { el.activate(); }
	    }
	  }
  },
  
  addField: function(field_data){
    
    var field;
    var Class = _mFormTypes.getClass(field_data['type']);
    if( Object.isFunction(Class) ) {
    
      field = new Class(field_data);
      this.fields.push( field );
      this.form.insert( field.div );
    } 
  },
  validate: function(){
    var result = true;
    for( var i=0; i<this.fields.length; i++ ){
      result = result && this.fields[i].validate();
    }
    return result;
  },
  serialize: function(){
    if( !this.validate() ) return false;
    var result = {};
    for( var i=0; i<this.fields.length; i++ ){
      result[this.fields[i].name] = this.fields[i].getValue();
    }
    return result;
  }
});