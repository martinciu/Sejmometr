var MBrowser = Class.create({
  initialize: function(){
    var args = $A(arguments);
    this.div = $(args[0]).addClassName('mBrowser');
    this.controll_data = [0, 100, 0];
    this._max_pages_range = 3;
    this.deletedIds = $A();
    
    
    if( Object.isString(args[1]) ) {
      this.title = args[1];
      this.params = args[2];
      this.itemParams = args[3];
    } else {
      this.title = '';
      this.params = args[1];
      this.itemParams = args[2];
    }
    this.url_vars = {};

    if( !this.params['categories'] ) { this.params['categories']=['','']; }       
    if( !this.params['default_category'] ) { this.params['default_category']=0; }
    if( !this.params['standard_title_bar'] ) { this.params['standard_title_bar']=true; }
        
    // build
    with( this ) {
      div.update('<div class="mbrowser_header"><div class="topbar"><h2 class="title">'+title+'</h2></div><div class="categories"></div><div class="pagination">&nbsp;</div></div><div class="list _height_controll"></div><div class="item"></div></div>');
      this.categoriesDiv = div.down('.categories');
      this.paginationDiv = div.down('.pagination');
      this.titleDiv = div.down('.title');
      this.listDiv = div.down('.list');
      this.itemDiv = div.down('.item');
    }
            
    // events
    Event.observe(window, 'keypress', this.onWindowKeypress.bind(this));
    
    this.titleDiv.observe('click', this.load.bind(this));
    this.params.categories.each(function(category, iterator){
      var a = new Element('a', {href: '#', onclick: 'return false;'}).update( category.label ).observe('click', this.onCategoryClick.bind(this, iterator, true));
      this.categoriesDiv.insert(a);
    }.bind(this));
    
    this.enabled = true;

    if( this.params.url_controll ) {
      this.url_vars = location.hash.substr(1).toQueryParams();
    }
    
    $M.heightController.resizeArea(this.div);
    
    this.onCategoryClick(this.url_vars.c ? this.url_vars.c : this.params['default_category'], false, null);
    
  },
  
  clear: function(){
    this.listDiv.update('');
    this.clearItem();
  },
  
  clearItem: function(){
    this.mid = null;
    this.itemDiv.update('');
    this.listDiv.select('.selected').invoke('removeClassName', 'selected');
    if( this.params.url_controll ) {
      this.url_vars['id'] = this.mid;
      this.update_url();
    }
  },
    
  enable: function(){
    this.enabled = true;
  },
  
  enable_loading: function(){
    this.enable();
    this.titleDiv.removeClassName('loading');
  },
  
  disable: function(){
    this.enabled = false;
  },
  
  disable_loading: function(){
    this.disable();
    this.titleDiv.addClassName('loading');
  },
  
  update_url: function(){
    var _temp = {};
    for( key in this.url_vars ) {
      if( !Object.isUndefined(this.url_vars[key]) ) _temp[key] = this.url_vars[key];
    }
    location.hash = Object.toQueryString(_temp);
  },
  
  onCategoryClick: function(iterator, clear, event){
    if(this.enabled) {
      
      if( this.params.url_controll ) {
        this.url_vars['c'] = iterator;
        this.update_url();
      }
      
      if(clear) this.clearItem();	    
	    
	    this.category = this.params.categories[iterator];
	    this.categoriesDiv.select('.selected').invoke('removeClassName', 'selected');
	    this.categoriesDiv.select('a')[iterator].addClassName('selected');
	    this.load();
    }
  },
  
  load: function(){
    if( Object.isNumber(arguments[0]) ) { this.controll_data[0] = arguments[0]; }
    if(this.enabled) {
      this.disable_loading();
      
      if( this.params.loader ) {
        $S('mBrowser/load', {loader: this.params.loader, category: this.category.id, controll_data: this.controll_data}, this.onLoad.bind(this), this.onLoadFail.bind(this));
      } else if ( this.params.table ) {
        $S('mBrowser/load', {table: this.params.table, where: this.category.where, fields: this.params.fields, controll_data: this.controll_data}, this.onLoad.bind(this), this.onLoadFail.bind(this));      
      } else {
        this.onLoadFail();
      }      
    }
  },
  
  updateTotalCount: function(){
    this.totalCountSpan.update(this.controll_data[2]);
  },
  
  onLoad: function(data){
    this.controll_data = data[0];
    
    if( this.controll_data[2]>0 ) {
	    var start = this.controll_data[0]*this.controll_data[1]+1;
	    var end = Math.min( start+this.controll_data[1], this.controll_data[2] );
	    this.paginationDiv.update('<p class="pages">&nbsp;</p><p class="pages_info"><b>'+start+'</b>-<b>'+end+'</b> z <b><span class="totalCountSpan">&nbsp;</b></span></p>');
	    this.totalCountSpan = this.paginationDiv.down('.totalCountSpan.');
	    this.updateTotalCount();
	    var pagesDiv = this.paginationDiv.down('.pages');
    }
    
    var pages = Math.ceil(this.controll_data[2]/this.controll_data[1]);
    if( pages>1 ) {
	    var pages_range = [ Math.max(this.controll_data[0]-this._max_pages_range, 0), Math.min( this.controll_data[0]+this._max_pages_range, pages-1) ];
	    
	    if( pages_range[0]>0 ) {
	      var a = $ANCHOR(1, {className: 'first_page'}).observe('click', this.load.bind(this, 0));
	      pagesDiv.insert(a);
	    }
	    
	    for( var i=pages_range[0]; i<=pages_range[1]; i++ ){
	      var a = $ANCHOR(i+1).observe('click', this.load.bind(this, i));
	      if( i==this.controll_data[0] ) { a.addClassName('selected'); }
	      pagesDiv.insert(a);
	      if(i!=pages_range[1]) { pagesDiv.insert('·'); }
	    }
	    
	    if( pages_range[1]<pages-1 ) {
	      var a = $ANCHOR(pages, {className: 'last_page'}).observe('click', this.load.bind(this, pages-1));
	      pagesDiv.insert(a);
	    }
    }
   
    
    data = data[1];
    this.listDiv.update('');
    for( var i=0; i<data.length; i++ ) {
      var div = Element('div').update( this.getListItemInnerHTML(data[i]) );
      div.addClassName('listitem');
      div.writeAttribute('mid', data[i]['id']);
      div.observe('click', this.onListitemClick.bind(this));
      if( data[i]['id']==this.mid ) { div.addClassName('selected'); }
      this.listDiv.insert(div);
    }
    this.enable_loading();
        
    if( this.params.url_controll && this.url_vars.id && !this.mid ) {
      this.loadItem(this.url_vars.id);
    } else {
      this.loadNextItem();
    }
  },
  
  onLoadFail: function(){
    alert('onLoadFail');
    this.enabled = true;
    this.div.select('.loading').invoke('removeClassName', 'loading');
  },
  
  getListItemInnerHTML: function(data){
    for( key in data ) {break;}
    return data[key]; 
  },
  
  onListitemClick: function(event){
    this.loadItem( event.findElement('.listitem').readAttribute('mid') );
  },
  
  getItemDiv: function(mid){
    return this.listDiv.down('.listitem[mid='+mid+']');
  },
  
  getSelectedItemDiv: function(){
    return this.getItemDiv(this.mid);
  },
  
  selectItem: function(){
    var args = $A(arguments);
    var mid = args[0];
    var loading = (args[1]==true);
  
    this.listDiv.select('.listitem.selected').invoke('removeClassName', 'selected');
    
    var item = this.getItemDiv(mid);
    if( item ) {    
	    item.addClassName('selected');
	    if( loading  ) item.addClassName('loading');
	    
	    var start = this.listDiv.scrollTop;
	    var end = this.getSelectedItemDiv().offsetTop - this.getSelectedItemDiv().up().offsetTop + 13 - this.listDiv.getHeight()/2;
		  
		  this.selectItemTween = new Effect.Tween(null, start, end, {duration: .1}, function(p){
		    this.listDiv.scrollTop = p;
		  }.bind(this));
	  }
  },
  
  loadItem: function(mid){
    if( this.enabled ) {
      this.disable();
      
      this.mid = mid;
      if( this.params.url_controll ) {
        this.url_vars['id'] = this.mid;
        this.update_url();
      }
      
      this.selectItem(mid, true);
      
      $P(this.params['item_pattern'], {id: mid, c: this.category.id}, this.onLoadItem.bind(this), this.onLoadFail.bind(this));        
    }
  },
  
  loadPrevItem: function(){
    if( this.controll_data[2]>0 ) {
	    var selectedDiv = this.getSelectedItemDiv();
	    if( selectedDiv ) {
	      if( selectedDiv.previous('.listitem') ) { this.loadItem( selectedDiv.previous('.listitem').readAttribute('mid') ); }
	    } else {
	      this.loadItem( this.listDiv.select('.listitem').last().readAttribute('mid') );
	    }
    }
  },
  
  loadNextItem: function(){
    if( this.controll_data[2]>0 ) {
	    var selectedDiv = this.getSelectedItemDiv();
	    if( selectedDiv ) {
	      if( selectedDiv.next('.listitem') ) { this.loadItem( selectedDiv.next('.listitem').readAttribute('mid') ); }
	    } else {
	      this.loadItem( this.listDiv.select('.listitem').first().readAttribute('mid') );
	    }
    }
  },
  
  addItemButton: function(class, label, onClick){
    if( this.itemButtonsDiv ) {
      var btn = new Element('input', {type: 'button', className: 'btn '+class, value: label});
      if( Object.isFunction(onClick) ) btn.observe('click', onClick.bind(this));
      this.itemButtonsDiv.insert({top: btn});
      return btn;
    }
  },
  
  closeItem: function(){
    if( this.enabled ) {
      if( this.params.url_controll ) {
	      this.url_vars['id'] = null;
	      this.update_url();
	    }
      this.clearItem();
      if( Object.isFunction(this.afterCloseItem) ) this.afterCloseItem();
    }
  },
  
  onLoadItem: function(html){
    this.selectItem(this.mid);
    this.itemDiv.update('');
    
    if( this.params['standard_title_bar'] ) {
      this.itemDiv.insert('<div class="title_bar"><div class="info"><p class="mid">'+this.mid+'</p><h3>&nbsp;</h3></div><div class="buttons"></div></div>'); 
      this.item_standard_title = this.itemDiv.down('.title_bar .info h3');
      this.itemButtonsDiv = this.itemDiv.down('.title_bar .buttons');
      this.addItemButton('close', 'X', this.closeItem.bind(this));  
    }
    
    this.itemDiv.insert(html);
    
    $M.heightController.resizeArea(this.itemDiv);

    this.enable();
    this.getItemDiv(this.mid).removeClassName('loading');
    if( Object.isFunction(this.afterLoadItem) ) { this.afterLoadItem(); }
  },
  
  itemTitleUpdate: function(title){
    if( this.item_standard_title ) this.item_standard_title.update(title);
  },
  
  onWindowKeypress: function(event){
    switch( event.keyCode ) {
      case 33: { this.loadPrevItem(); break; }
      case 34: { this.loadNextItem(); break; }
    }
  },
  
  markAsDeleted: function(id){
    if( this.deletedIds.indexOf(id)==-1 ) {
      this.deletedIds.push(id);
	    var item = this.getItemDiv(id);
	    if( item ) {
	      item.addClassName('deleted');
	      this.controll_data[2]--;
	      this.updateTotalCount();
	    }
    }
  }
});