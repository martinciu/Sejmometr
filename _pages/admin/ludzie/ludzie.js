var Item = Class.create({
  avatar_center_delta: 2,
  avatar_zoom_delta: .1,
  _avatar : '0',
  initialize: function(data){
    this.data = data;
    this._avatar = data['avatar'];
    this.id = this.data.id;
   
    this.inner_bar_div = $$('#item .inner_title_bar').first();
    
    mBrowser.itemTitleUpdate(this.data.nazwa ? this.data.nazwa : '<i>Brak nazwy</i>');
    this.btnSave = mBrowser.addItemButton('save', 'Zapisz', this.save.bind(this));
    this.btnAvatar = mBrowser.addItemButton('avatar', 'Aktualizuj avatar', this.aktualizuj_avatar.bind(this));
    
    if(this.data.avatar=='1') {
      this.refresh_avatars();
    } else {
      this.inner_bar_div.update('<input type="text" id="inpSzukaj" name="q" /><div id="inpSzukajDiv" class="autocomplete"></div>');
      this.inpSzukaj = $('inpSzukaj').activate();
      new Ajax.Autocompleter('inpSzukaj', 'inpSzukajDiv', '/pattern/wgrane_avatary?_PID='+CURRENT_PAGE, {afterUpdateElement: this.inpSzukajAfterUpdate.bind(this)});
    }
    
    var fields = $A();
    fields.push({name: 'nazwa', label: 'Nazwa', type: 'varchar', value: this.data.nazwa});
    fields.push({name: 'fraza', label: 'Fraza', type: 'varchar', value: this.data.fraza});
    this.form = new mForm('item_form', fields, {activateFirstInvalid: true});
    
    new Event.observe(window, 'keypress', function(event){
      if( this.avatar_lb ) {
	      switch( event.keyCode ) {
	        case 40: { event.stop(); return this.place_avatar([this.avatar_center[0], this.avatar_center[1]+this.avatar_center_delta], this.avatar_zoom); }
	        case 38: { event.stop(); return this.place_avatar([this.avatar_center[0], this.avatar_center[1]-this.avatar_center_delta], this.avatar_zoom); }
	        case 39: { event.stop(); return this.place_avatar([this.avatar_center[0]+this.avatar_center_delta, this.avatar_center[1]], this.avatar_zoom); }
	        case 37: { event.stop(); return this.place_avatar([this.avatar_center[0]-this.avatar_center_delta, this.avatar_center[1]], this.avatar_zoom); }
	        case 13: { 
	          if(this.img_loaded) {
	            event.stop();
	            $M.lightboxClose();
	            this._avatar = '1';
	            this.inner_bar_div.update('Zapisuje avatar...');
	            $S('ludzie/przypisz_avatar', [this.id, this.avatar_id, this.avatar_center[0], this.avatar_center[1], this.avatar_zoom], this.refresh_avatars.bind(this));
	            return true;
	          }
	        }
	      }
      }
    }.bind(this));
    
    new Event.observe(window, 'keydown', function(event){
      if( this.avatar_lb ) {
	      switch( event.keyCode ) {
	        case 107: { event.stop(); return this.place_avatar([this.avatar_center[0], this.avatar_center[1]], this.avatar_zoom+this.avatar_zoom_delta); }
	        case 109: { event.stop(); return this.place_avatar([this.avatar_center[0], this.avatar_center[1]], this.avatar_zoom-this.avatar_zoom_delta); }
	      }
      }
    }.bind(this));
  },
  inpSzukajAfterUpdate: function(inp, li){
    this.avatar_id = li.readAttribute('value');
    this.img_loaded = false;
    this.lbdiv = new Element('div', {id: 'avatar_img_cont'}).update('<img id="avatar_img" style="display: none;" src="/resources/ludzie/avatary/'+this.avatar_id+'.jpg" />');
    this.avatar_lb = true;
    this.lb = $M.addLightboxShow( this.lbdiv, {title: 'Avatar', width: 112, height: 142, afterClose: function(){
      this.avatar_lb = false;
      $M.getLightboxDiv(this.lb).remove();
    }.bind(this)} );
    this.avatar_img = $('avatar_img').observe('load', this.avatar_img_load.bind(this));
  },
  avatar_img_load: function( event ){
    this.img_loaded = true;
    this.avatar_img.focus();
    this.avatar_width = this.avatar_img.getWidth();
    this.avatar_height = this.avatar_img.getHeight();
        
    var zoom = Math.max( 110/this.avatar_width, 140/this.avatar_height );
    this.place_avatar([55,70], zoom);
    
    this.avatar_img.show();
  },
  place_avatar: function(center, zoom){
    
    var w = this.avatar_width * zoom;
    var h = this.avatar_height * zoom;
    var l = center[0] - w/2;
    var t = center[1] - h/2;
    
    if( zoom>0 && zoom<=1 ) { this.avatar_zoom = zoom; } else return false;
    if( l<=0 && (w+l>=110) && t<=0 && (h+t>=140) ) { this.avatar_center = center; } else return false;
    
    this.avatar_img.setStyle({width: w+'px', height: h+'px', top: t+'px', left: l+'px'});
    this.avatar_img.writeAttribute('offset', w+l-110);
  },
  x: function(){
    $S('ludzie/stworz_avatar', this.lastAvatar, this.refresh_avatars.bind(this));
  },
  refresh_avatars: function(){
    var bar = this.inner_bar_div.update('');
    var t = new Date();
    t = t.getTime();
    for( var i=0; i<=3; i++ ) {
      var img = new Element('img', {typ: i, src: '/l/'+i+'/'+this.id+'.jpg?t='+t}).observe('click', this.avatar_click.bind(this));
      bar.insert( img );
    }
    this.btnAvatar.enable();
  },
  avatar_click: function(event){
    var img = event.findElement(event);
    var typ = Number(img.readAttribute('typ'));
    var selected = img.hasClassName('selected');
    this.inner_bar_div.select('img').invoke('removeClassName', 'selected');
    if( typ!=0 && !selected ) {
	    this.selected_avatar = img.addClassName('selected');
    }
  },
  save: function(){
    if( mBrowser.enabled ) {
      
      var params = this.form.serialize();  
      params.id = this.id;
      params.avatar = this._avatar;
	    mBrowser.disable_loading();
	    this.btnSave.disable();
	    
	    $S('zapisz', params, this.onSave.bind(this), function(){
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
        mBrowser.markAsDeleted( this.id );
        mBrowser.loadItem( this.id );
      }      
    } else { alert('Element nie został zapisany'); }
    mBrowser.enable_loading();
    this.btnSave.enable();
  },
  aktualizuj_avatar: function(){
    this.btnAvatar.disable();
    $S('zmien_avatar', this.id, this.refresh_avatars.bind(this));
  },
  clip_avatar: function(direction){
    var typ = Number(this.selected_avatar.readAttribute('typ'));
    if( typ!=0 ) {
      $S('ludzie/clip_avatar', [this.id, typ, direction], function(result){
        var typ = this.selected_avatar.readAttribute('typ');
        var t = new Date();
        t = t.getTime();
        this.selected_avatar.writeAttribute('src', '/l/'+typ+'/'+this.id+'.jpg?t='+t);
      }.bind(this));
    }
  }
});



var MBrowser = Class.create(MBrowser, {
  getListItemInnerHTML: function(data){
    return data['nazwa'] ? data['nazwa'] : '<i>Brak nazwy</i>';
  },
  
  afterCloseItem: function(){ $('zmiany').update(''); },
   
});
var item;
var mBrowser;

function fix_date(d){
  var parts = d.substr(0, 10).split('-');
  return parts[2]+'-'+parts[1]+'-'+parts[0];
}

$M.addInitCallback(function(){
  Event.observe(document, 'keypress', function(event){
	  if( event.ctrlKey && event.charCode==115 ) { item.save(); }
	  else if( event.ctrlKey && event.charCode==100 ) { item.aplikuj_zmiany(); }
	});
	Event.observe(document, 'keydown', function(event){
	  if( item.selected_avatar ) {
		  if( event.keyCode==38 ) { item.clip_avatar('up'); event.stop(); }
		  else if( event.keyCode==40 ) { item.clip_avatar('down'); event.stop(); }
		  else if( event.keyCode==37 ) { item.clip_avatar('left'); event.stop(); }
		  else if( event.keyCode==39 ) { item.clip_avatar('right'); event.stop(); }
	  }
	});
	
});
