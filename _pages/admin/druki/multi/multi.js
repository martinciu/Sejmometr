var Druk = Class.create({
  initialize: function(data){
    this.data = data;
    this.id = this.data.id;
    this.selectedId = this.data.dokument_id;
    this.docthumbsDiv = mBrowser.itemDiv.down('.docthumbs_div');
    
    mBrowser.item_standard_title.update(this.data.numer);
    this.btnSave = mBrowser.addItemButton('save', 'Zapisz', this.save.bind(this));
        
    for( var i=0; i<this.data.dokumenty.length; i++ ) {
      var dok = this.data.dokumenty[i];
      this.docthumbsDiv.insert( docthumbs_create(dok.id, 5, {lfloat: true}).observe('click', this.docClick.bind(this)) );
    }
    this.getDocThumb(this.selectedId).addClassName('selected');
  },
  getDocThumb: function(id){
    return this.docthumbsDiv.down('.docthumbs_a[docid='+id+']');
  },
  docClick: function(event){
    var el = event.findElement('.docthumbs_a');
    var id = el.readAttribute('docid');
    this.select(id);
  },
  select: function(id){
    if( !id ) return false;
    this.selectedId = id;
    this.docthumbsDiv.select('.docthumbs_a.selected').invoke('removeClassName', 'selected');
    this.getDocThumb(id).addClassName('selected');
  },
  save: function(){
    if( mBrowser.enabled ) {
	    mBrowser.disable_loading();
	    this.btnSave.disable();
	    $S('druki/zapisz_dokument_glowny', [this.id, this.selectedId], this.onSave.bind(this), function(){
	      mBrowser.disable_loading
	      this.btnSave.enable();
	    }.bind(this));
    }
  },
  onSave: function(data){
    if( data=='3' ) {
      mBrowser.enable_loading();
      $LICZNIKI.update();

      if( mBrowser.category.id=='doakceptu' ) {
        mBrowser.markAsDeleted(this.id);
        mBrowser.loadNextItem();
      }      
    } else alert('Druk nie został zapisany');
    mBrowser.enable_loading();
    this.btnSave.enable();
  }
});

var MBrowser = Class.create(MBrowser, {

  getListItemInnerHTML: function(data){
    return data['numer'];
  },
  
  
});
var druk;
var mBrowser;