function var_export(v){
  var r = '';
  for(k in v){
    r += k+': '+v[k]+"\n";
  }
  alert(r);
}

function cssToNumber(css){
  return Number( css.replace('px', '') ); 
}

function getScrollTop(){
  if(typeof pageYOffset!= 'undefined'){
	  //most browsers
	  return pageYOffset;
  }
  else {
	  var B= document.body; //IE 'quirks'
	  var D= document.documentElement; //IE with doctype
	  D= (D.clientHeight)? D: B;
	  return D.scrollTop;
  }
}

function setScrollTop(val){
  var t = getScrollTop();
  scrollBy(0, val-t);
}

function isIE(){
  return /msie/i.test(navigator.userAgent) && !/opera/i.test(navigator.userAgent);
}

Element.addMethods({
  makeVisible: function(element){
    return element.setStyle({visibility:'visible'});
  },
  makeHidden: function(element){
    return element.setStyle({visibility:'hidden'});
  },
  height_control: function(element){
    if( $M.heightController ) {
      return $M.heightController.resizeItem(element);
    }
  }
});

function isObject(obj){
  if( obj==null || Object.isUndefined(obj) ) { return false; }
  return (String(typeof(obj)).toUpperCase()=='OBJECT');
}

var Height_Controller = Class.create({
  initialize: function(){
    this.resize();
    this.footer_height = $('_FOOTER').getHeight();    
    Event.observe(window, 'resize', this.resize.bind(this));
  },
  resize: function(){
    this.viewport_height = document.viewport.getHeight();
    $$('._height_controll').each( function(item){ this.resizeItem(item); }.bind(this) );
  },
  resizeArea: function(area){
    $(area).select('._height_controll').each( function(item){ this.resizeItem(item); }.bind(this) );
  },
  resizeItem: function(item){
    var height_offset = Number( item.readAttribute('height_offset') );
    var height = this.viewport_height - item.cumulativeOffset()[1] - this.footer_height + height_offset - 20;
    return item.setStyle({height: height+'px'});
  }
});

var $_MPAGE = Class.create({
  lightboxCounter: 0,
  initialize: function(){
    this._onInitCallbacks = $A();
    this.addInitCallback( function(){
      this.heightController = new Height_Controller();
    }.bind(this) );
    
    document.observe('dom:loaded', this.onInit.bind(this));
  },
  addInitCallback: function(callback){
    this._onInitCallbacks.push(callback);
  },
  onInit: function(){
    this.PAGE = _PAGEDATA;
    $('_OVERLAY').observe('click', this.onOverlayClick.bind(this));
    this._onInitCallbacks.each(function(callback){callback();});
  },	
  getServiceParameters: function(args, method){ 
    var args = $A(args);
    if( args.length==0 ) { return false; }
    
    var service = args[0];
    var params = null;
    var successCallback = null;
    var failCallback = null;
    var method = (method=='post') ? 'post' : 'get';
    
    switch( args.length ) {
      case 2: {
        if( Object.isFunction(args[1]) ) { successCallback = args[1]; } else { params=args[1] }
        break;
      }
      case 3: {
        if( Object.isFunction(args[1]) ) {
          successCallback = args[1];
          failCallback = args[2];
        } else {
          params = args[1];
          successCallback = args[2];
        }
      }
      case 4: {
        var params = args[1];
        var successCallback = args[2];
        var failCallback = args[3];
      }
    }
    if( failCallback==null ) { failCallback=this._generalFailCallback; }
    var parameters = {'_PID': this.PAGE.ID};
    if( params ) { parameters['_PARAMS'] = Object.toJSON(params); }
    return {service: service, parameters: parameters, successCallback: successCallback, failCallback: failCallback, method: method};
  },
  service: function(args, method){   
    this._service( this.getServiceParameters(args, method) );   
  },
  pattern: function(args){
    this._pattern( this.getServiceParameters(args) );
  },
  _service: function(params){
    new Ajax.Request('/service/'+params.service, {
		  method: params.method,
		  parameters: params.parameters,
		  onSuccess: function(service, successCallback, transport){
		    try{
		      var data = transport.responseText.evalJSON();
		    }catch(e){
		      // alert("service: "+service+"\n\n"+transport.responseText);
		      // return false;
		    }
		    successCallback(data);
		  }.bind(this, params.service, params.successCallback),
		  onFailure: function(failCallback){
		    failCallback();
		  }.bind(this, params.failCallback)
		});
  },
  _pattern: function(params){    
    new Ajax.Request('/pattern/'+params.service, {
		  method: params.method,
		  parameters: params.parameters,
		  onSuccess: function(service, successCallback, transport){
		    try{
		      var html = transport.responseText;
		    }catch(e){
		      alert("pattern: "+service+"\n\n"+transport.responseText);
		      return false;
		    }
		    successCallback(html);
		  }.bind(this, params.service, params.successCallback),
		  onFailure: function(failCallback){
		    failCallback();
		  }.bind(this, params.failCallback)
		});
  },
  _generalFailCallback: function(){
    alert("Wystąpił błąd.\nPrzeładuj stronę i spróbuj jeszcze raz.");
  },
  addLightbox: function(div, params){
    this.lightboxCounter++;
    var content = new Element('div', {className: 'lightbox_content'});
    var lbdiv = new Element('div', {className: 'lightbox', lbid: this.lightboxCounter}).hide().insert('<div class="lightbox_bar"><p class="tytul">&nbsp;'+params.title+'</p><p class="x"><a href="#" onclick="return false;">x</a></p></div>').insert( div.wrap(content) );
    
    if( !params.width ) params.width = 500;
    if( !params.height ) params.height = 200;
    
    lbdiv.setStyle({marginLeft: '-'+Math.round(params.width/2)+'px'}).down('.lightbox_content').setStyle({width: params.width+'px'});
    lbdiv.setStyle({width: params.width+10+'px'});
    lbdiv.setStyle({marginTop: '-'+Math.round(params.height/2)-16+'px'}).down('.lightbox_content').setStyle({height: params.height+'px'});
    
    if( isIE() ) {
      $('_LIGHTBOXES').setStyle({marginTop: Math.round(params.height*.25)+16+'px'});
    } else {
      // new Draggable(lbdiv, {handle: 'lightbox_bar', starteffect: null, endeffects: null});
    }
    
    
    $('_LIGHTBOXES').insert( lbdiv );
    lbdiv.down('.x a').observe('click', this.lightboxClose.bind(this));
    return this.lightboxCounter;
  },
  addLightboxShow: function(div, params){
    if( !params ) params = {};
    var lbid = this.addLightbox(div, params);
    this.lightbox(lbid, {beforeClose: params.beforeClose, afterClose: params.afterClose});
    return lbid;
  },
  lightbox: function(lbid, params){
    this.lbid = lbid;
    var div = this.getLightboxDiv(lbid);
    $('_OVERLAY').show();
    this.beforeLightboxClose = params.beforeClose;
    this.afterLightboxClose = params.afterClose;
    div.show();
  },
  getLightboxDiv: function(lbid){
    return $('_LIGHTBOXES').down('.lightbox[lbid='+lbid+']');
  },
  onOverlayClick: function(){
    this.lightboxClose();
  },
  lightboxClose: function(){
    if( this.lbid ) {
      if( Object.isFunction(this.beforeLightboxClose) ) this.beforeLightboxClose();
      var lbdiv = this.getLightboxDiv(this.lbid).hide();
    }
    this.beforeLightboxClose = false;
    this.lbid = false;
    $('_OVERLAY').hide();
    if( Object.isFunction(this.afterLightboxClose) ) this.afterLightboxClose(lbdiv);
    this.afterLightboxClose = false;
    if( this.lbdiv ) this.getLightboxDiv(this.lbid).remove();
  }
});
var $M = new $_MPAGE();

function $SERVICE(){ return $M.service(arguments, 'get'); }
function $POST_SERVICE(){ return $M.service(arguments, 'post'); }
function $S(){ return $M.service(arguments, 'get'); }
function $P(){ return $M.pattern(arguments); }
function $PATTERN(){ return $M.pattern(arguments); }

function $ANCHOR(){
  var params = arguments[1] ? arguments[1] : {};
  params.href = params.href ? params.href : '#';
  params.onclick = params.onclick ? params.onclick : 'return false;';
  return new Element('a', params).update(arguments[0]);
}