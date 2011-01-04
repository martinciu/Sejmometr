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
    
    Event.observe(window, 'scroll', this.onWindowScroll.bind(this));
    Event.observe(window, 'resize', this.onWindowResize.bind(this));
    
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
    this.set_txt_height();
    
    
    var wyp_id = 1;
    if( this.hash!='' ) { 
	    if( String(this.hash).length==5 ) {
	      var a = this.lista_div.down('a.wyp[wyp_id='+this.hash+']');
	      if( a ) wyp_id = a.readAttribute('wyp_i');
	    } else if( this.lista_div.down('a.wyp[wyp_i='+this.hash+']') ) {
	      wyp_id = this.hash;
	    }
    }
        
    
    this.wyp( wyp_id );
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
	    
	    $('wyp_text').update( '<p class="ladowanie">ładowanie...</p>' );
	    $('wyp_input').value = this.wyp_i;
	    
	    this.wyp_a.addClassName('selected');
	    $('wyp_input').value = this.wyp_a.readAttribute('wyp_i');
	    
	    location.hash = this.wyp_i;
	    
	    var start = getScrollTop();
	    var end = this.wyp_a.positionedOffset()[1]-($$('html').first().clientHeight - this.wyp_a.getHeight(2) - 22)/2;
	    new Effect.Tween(null, start, end, { duration: .25 }, function(p) { setScrollTop(p); } );
	    
	    var src = this.wyp_a.readAttribute('avatar')=='1' ? '/l/2/'+this.wyp_a.readAttribute('autor_id') : '/g/gp_2';
	    $('wyp_info').update( '<img class="avatar c" src="'+src+'.jpg" /><div class="info">'+this.wyp_a.down('.info').innerHTML+'</div><div class="link"><a href="/wypowiedz/'+this.wyp_id+'">Strona tej wypowiedzi &raquo;</a></div>' );
	        
	    $S('wyp', [this.wyp_id, this.txt_height], function(data){
	      var txt = data[0];
	      var ratio = data[1];
	      
	      $('wyp_text').update( txt ).scrollTop = 0;
	      
	      if( ratio>0 && ratio<100 ) $('wyp_text').insert('<p class="ratio">Powyższy tekst stanowi '+ratio+'% wypowiedzi.<br/><a href="/wypowiedz/'+this.wyp_id+'">Przeczytaj całą wypowiedź &raquo;</a></p>');
	      
	      this.onWindowScroll();
	      this.onWindowResize();
	      this.enable();
	    }.bind(this), function(){
	      alert('fail');
	      this.enable();
	    }.bind(this));
    }
  },
  enable: function(){
    this.enabled = true;
  },
  disable: function(){
    this.enabled = false;
  },
  onWindowScroll: function(event){
    var t = Math.max( 7, this.initial_offset-getScrollTop() );
		$('wyp_div').setStyle({top: t+10+'px'});
		
		this.onWindowResize();
  },
  set_txt_height: function(){
    var html = $$('html').first();
		var body = $$('body').first();
		
		var window_outer_height = Math.max( html.getHeight(), body.getHeight(), html.clientHeight );
	  var	window_inner_height = Math.min( html.getHeight(), body.getHeight(), html.clientHeight );
		
		var d = window_outer_height-window_inner_height-getScrollTop();

    this.txt_height = window_inner_height - 26 - $('_HEADER').getHeight() - $('_SUBMENUS').getHeight() - $$('#_CONTAINER ._BOX_HEADER').first().getHeight() - $('wyp_info').getHeight() - $('navbar').getHeight() + Math.min(this.initial_offset-7, getScrollTop()) - Math.max(0, 75-d);
    return d;
  },
  onWindowResize: function(event){  
		var d =this.set_txt_height();
    $('wyp_text').setStyle({height: this.txt_height+'px'});
      
    var delta = document.viewport.getHeight() - $('_MAIN_CONTAINER').getHeight()+63;    
    var v = (delta>0) ? delta : 0;    
    if(delta<63) v = Math.max(0, 63-d);
    $('navbar').setStyle({bottom: v+'px'}).show();  
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