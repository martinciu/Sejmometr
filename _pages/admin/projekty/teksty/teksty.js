var Item = Class.create({
  initialize: function(data){
    this.data = data;
    this.id = this.data.id;
   
    mBrowser.itemTitleUpdate(this.data.numer);
    this.btnSave = mBrowser.addItemButton('save', 'Zapisz', this.save.bind(this));

    if( this.data.txt===false ) {
      $('txt').update( "Brak tekstu" );
    } else {
	    $('txt').update( this.data.txt ).height_control();
	    
	    
	    $$('#txt p').each( function(p){
	      if( p.innerHTML.match(/^([0-9]+)$/i) ) { p.remove(); } else {
	        
	        p.observe('click', this.p_click.bind(this));
			    new Draggable(p, {revert: true, ghosting: true});

	      }
	    }.bind(this) );
    }
    
    this.tabs = new Tabs('tabs', {onDrop: this.onDrop.bind(this)});
    this.tabs.add_tab('autorzy', 'Autorzy', this.data.autorzy);
    this.tabs.add_tab('tresc', 'Treść', this.data.tresc);
    this.tabs.add_tab('uzasadnienie', 'Uzasadnienie', this.data.uzasadnienie);
    this.tabs.add_tab('osr', 'OSR', this.data.osr);
    this.tabs.select_first_tab();
  },
  onDrop: function(p, div, event){
    p.addClassName('s _ignore');
    $$('#txt p.s').each( function(p){
      
      this.insert('<p>'+p.innerHTML);
      p.remove();
      
    }.bind(div) );
  },
  p_click: function(event){
    var p = event.findElement('p');
    
    if( p.hasClassName('_ignore') ) return p.removeClassName('_ignore');
    
    if( event.shiftKey ) {
      
      var p_selected = $$('#txt p.s').first();
      if( p_selected ) {
        var start = $$('#txt p').indexOf( p_selected );
        var end = $$('#txt p').indexOf( p );
        $$('#txt p.s').invoke('removeClassName', 's');
        
        if( start<end ) {
          for( var i=start; i<=end; i++ ) $('txt').childElements()[i].addClassName('s');
        } else {
          $$('#txt p')[end].addClassName('s');
        }
      }
      
    } else {
      var selected = p.hasClassName('s');
      $$('#txt p.s').invoke('removeClassName', 's');
      if( !selected ) p.addClassName('s');
    }
    
  },
  showDocumentMessage: function(msg){
    $('scribd').update('<p class="msg">'+msg+'</p>').height_control();
  },
  save: function(){
    if( mBrowser.enabled ) {
      params = {};
      var divs = this.tabs.get_tab_divs();
      for( var i=0; i<divs.length; i++ ){
        var div = divs[i];
        params[ div.readAttribute('tab') ] = div.innerHTML;
      }
      
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
    } else alert('Druk nie został zapisany');
    mBrowser.enable_loading();
    this.btnSave.enable();
  },
  delete_p: function(){
    $$('#txt p.s').invoke('remove');
  }
});

var Tabs = Class.create({
  initialize: function(div, params){
    this.div = $(div);
    this.header_div = this.div.down('.header');
    this.content_div = this.div.down('.content').height_control();
    
    if( params ) {
      this.onDrop = params.onDrop;
    }
  },
  get_tab_divs: function(){
    return this.content_div.select('.tab_div');
  },
  get_btns: function(){
    return this.header_div.select('a');
  },
  add_tab: function(name, label, content){
    var a = new Element('a', {href: '#', onclick: 'return false;', tab: name}).observe('click', this.label_click.bind(this)).update( label );
    this.header_div.insert( a );
    var div = new Element('div', {className: 'tab_div _height_controll', tab: name, height_offset: '-2'}).hide().update(content);
    
	  Droppables.add(div, { 
		  hoverclass: 'hover',
		  containment: 'txt',
		  onDrop: this.onDrop.bind(this),
		});
    
    this.content_div.insert(div).height_control();
  },
  select_first_tab: function(){
    this.select_tab( this.get_tab_divs().first().readAttribute('tab') );
  },
  select_tab: function(name){
    this.get_btns().invoke( 'removeClassName', 'selected' );
    this.get_tab_divs().invoke( 'hide' );
    
    this.header_div.down('a[tab='+name+']').addClassName('selected');
    this.content_div.down('.tab_div[tab='+name+']').show().height_control();
  },
  label_click: function(event){
    this.select_tab( event.findElement('a').readAttribute('tab') );
  },
});

var MBrowser = Class.create(MBrowser, {

  getListItemInnerHTML: function(data){
    return data['numer'];
  },
  
  afterCloseItem: function(){ $('scribd').update(''); }
  
  
});
var item;
var mBrowser;

$M.addInitCallback(function(){
  Event.observe(document, 'keypress', function(event){
	  if( event.ctrlKey && event.charCode==115 ) { item.save(); }
	  else if( event.ctrlKey && event.charCode==100 ) { item.delete_p(); }
	});
});
