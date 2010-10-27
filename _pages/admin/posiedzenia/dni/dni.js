var Dzien = Class.create({
  initialize: function(data){
    this.id = data.id;
    mBrowser.itemTitleUpdate(data.data);
    
    if( $('usun_btn') ) $('usun_btn').observe('click', this.usun.bind(this));
  },
  usun: function(){
    $('usun_btn').disable();
    $S('usun', this.id, function(result){
      $('usun_btn').enable();
    });
  }
});

var MBrowser = Class.create(MBrowser, {
  getListItemInnerHTML: function(data){
    return data['data'];
  },
});

var mBrowser;
