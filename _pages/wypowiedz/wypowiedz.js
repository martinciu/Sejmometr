var Debata = Class.create({
  get_wyps: function(){
    return this.lista_div.select('a.wyp');
  },
  enabled: true,
  initialize: function(){
    this.lista_div = $('wyp_lista');
    this.wyp_div = $('wyp_div');
    this.initial_offset = this.wyp_div.positionedOffset()[1];
    this.length = this.get_wyps().length;
    this.text_shadow_top = $$('.text_shadow.top').first();
    this.text_shadow_bottom = $$('.text_shadow.bottom').first();
    
    Event.observe(window, 'scroll', this.onWindowScroll.bind(this));
    Event.observe(window, 'resize', this.onWindowResize.bind(this));
    Event.observe('wyp_text', 'DOMMouseScroll', this.onMouseWheel.bind(this));
    Event.observe('wyp_text', 'mousewheel', this.onMouseWheel.bind(this));
    // window.onmousewheel = document.onmousewheel = this.onMouseWheel.bind(this);
    
    /*
    $$('#navbar ._BOX_FOOTER a').invoke('observe', 'click', function(event){
      var action = Number( event.findElement('a').readAttribute('action') );
      switch( action ) {
        case 1: { this.wyp_poprzednia(); break; }
        case 2: { this.wyp_poprzednia(); break; }
        case 3: { this.wyp_nastepna(); break; }
        case 4: { this.wyp_nastepna(); break; }
      }
    }.bind(this));
    */
  },
  init: function(){
    Event.observe(window, 'keydown', this.onWindowKeydown.bind(this));
    Event.observe('wyp_input', 'keydown', this.onWypInputKeydown.bind(this));
    
    this.get_wyps().invoke('observe', 'click', this.wyp_a_click.bind(this));
    this.hash = location.hash.substr(1);
    this.wyp( (this.hash!='' && this.lista_div.down('a.wyp[wyp_i='+this.hash+']')) ? this.hash : 1 );
  },
  wyp_a_click: function(event){
    this.wyp( event.findElement('a.wyp').readAttribute('wyp_i') );
  },
  wyp: function(wyp_i){
    if( this.enabled ) {
      this.wyp_i = wyp_i;
      this.wyp_a = this.lista_div.down('a.wyp[wyp_i='+this.wyp_i+']');
      this.wyp_id = this.wyp_a.readAttribute('wyp_id');
      
      this.disable();
	    this.lista_div.select('a.wyp.selected').invoke('removeClassName', 'selected');
	    this.lista_div.select('a.wyp.beforeselected').invoke('removeClassName', 'beforeselected');
	    
	    $('wyp_text').update( '<p class="ladowanie">ładowanie...</p>' );
	    $('wyp_input').value = this.wyp_i;
	    
	    this.wyp_a.addClassName('selected');
	    var prev_wyp = this.wyp_a.previous('a.wyp');
	    if( prev_wyp ) prev_wyp.addClassName('beforeselected');
	    $('wyp_input').value = this.wyp_a.readAttribute('wyp_i');
	    
	    location.hash = this.wyp_i;
	    
	    var start = getScrollTop();
	    var end = this.wyp_a.positionedOffset()[1]-($$('html').first().clientHeight - this.wyp_a.getHeight(2) - 22)/2;
	    new Effect.Tween(null, start, end, { duration: .25 }, function(p) { setScrollTop(p); } );
	    
	    var src = this.wyp_a.readAttribute('avatar')=='1' ? '/l/2/'+this.wyp_a.readAttribute('autor_id') : '/g/gp_2';
	    $('wyp_info').update( '<img class="avatar c" src="'+src+'.jpg" /><div class="info">'+this.wyp_a.down('.info').innerHTML+'</div>' );
	        
	    $S('wyp', this.wyp_id, function(txt){
	      $('wyp_text').update( txt ).scrollTop = 0;
	      this.onWindowScroll();
	      this.onWindowResize();
	      this.enable();
	    }.bind(this), function(){
	      alert('fail');
	      this.enable();
	    }.bind(this));
    }
  },
  text_ratio_update: function(){
	  this.text_ratio = $('wyp_text').scrollHeight / $('wyp_text').getHeight();
	  if( this.text_ratio>1 ) { $('wyp_text').addClassName('high'); } else { $('wyp_text').removeClassName('high'); }
  },
  enable: function(){
    this.enabled = true;
  },
  disable: function(){
    this.enabled = false;
  },
  onWindowScroll: function(event){
    var t = Math.max( 7, this.initial_offset-getScrollTop() );
		$('wyp_div').setStyle({top: t+'px'});
		this.text_shadow_top.setStyle({top: t+$('wyp_info').getHeight()+'px'});
		
		this.onWindowResize();
  },
  onWindowResize: function(event){  
		var html = $$('html').first();
		var body = $$('body').first();
		
		var window_outer_height = Math.max( html.getHeight(), body.getHeight(), html.clientHeight );
	  var	window_inner_height = Math.min( html.getHeight(), body.getHeight(), html.clientHeight );
		
		var d = window_outer_height-window_inner_height-getScrollTop();

    var height = window_inner_height - 26 - $('_HEADER').getHeight() - $('_SUBMENUS').getHeight() - $$('#_CONTAINER ._BOX_HEADER').first().getHeight() - $('wyp_info').getHeight() - $('navbar').getHeight() + Math.min(this.initial_offset-7, getScrollTop()) - Math.max(0, 75-d);
    $('wyp_text').setStyle({height: height+'px'});
    this.text_ratio_update();
    
    var tsth = this.text_shadow_top.positionedOffset()[1];
    this.text_shadow_bottom.setStyle({top: tsth+height-10+'px'});
    
    $('navbar').setStyle({bottom: Math.max(0, 63-d)+'px'});  
  },
  onWindowKeydown: function(event){
    switch( event.keyCode ) {
      case 33: {
        this.wyp_poprzednia();
        event.stop();
        break;
      }
      case 34: {
        this.wyp_nastepna();
        event.stop();
        break;
      }
    }
  },
  onMouseWheel: function(event){
    if( this.text_ratio>1 ) event.stop();
    
    var delta = event.wheelDelta ? event.wheelDelta/-480 : event.detail;    
    $('wyp_text').scrollTop += delta;
  },
  wyp_nastepna: function(){
    var wyp_a = this.wyp_a.next('a.wyp');
    if( wyp_a ) this.wyp( wyp_a.readAttribute('wyp_i') );
  },
  wyp_poprzednia: function(){
    var wyp_a = this.wyp_a.previous('a.wyp');
    if( wyp_a ) this.wyp( wyp_a.readAttribute('wyp_i') );
  },
  onWypInputKeydown: function(event){
    if( event.keyCode==13 ) {
	    var n = Number( $F('wyp_input') );
	    if( n==0 ) {
	      $('wyp_input').value = this.wyp_i;
	    } else {
	      if( this.lista_div.down('a.wyp[wyp_i='+n+']') ) {
	        this.wyp( n );
	      } else { $('wyp_input').value = this.wyp_i; }
	    }
    }
  },
});