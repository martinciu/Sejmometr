function build_page(){
  $S('mPortal/pages/build', CURRENT_PAGE);
}

function build_engines(){
  $S('mPortal/pages/build_engines');
}

function build_all(){
  $S('mPortal/pages/build_all');
}

function new_service(){
  var args = $A(arguments);
  var service = args[0];
  var page = '';
  var access = '';
  
  if ( args.length==2 ) {
    if( Object.isString(args[1]) ) page = args[1];
    if( Object.isNumber(args[1]) ) access = args[1];
  } else if ( args.length==3 ) {
    page = args[1];
    access = args[2];
  }
  
  if( service ) {
    $S('mPortal/services/new', {id: service, page: page, access: access});
  }
  
}

var LICZNIKI = Class.create({
  initialize: function(data){
    this.data = data['data'];
    this.count = data['count'];
    this.toggleDiv = $('_LICZNIKI_TOGGLE').observe('click', this.toggle.bind(this));
    this.div = $('_LICZNIKI_DIV');
    this.ul = $('_LICZNIKI_UL');
    this.state = false;
    
    this.render();
    new PeriodicalExecuter(this.update.bind(this), 180);
  },
  toggle: function(){
    if( this.state ) { this.hide(); } else { if(this.count>0) { this.show(); } }
  },
  show: function(){
    this.update();
    this.toggleDiv.addClassName('selected');
    this.div.show();
    this.state = true;
    
    Event.observe(document, 'click', this.documentClick.bind(this));
  },
  documentClick: function(event){
    if( event.findElement('div')!=this.div ) { event.stop(); }
    if( event.findElement('a')==this.toggleDiv ) return false;
    this.hide();
    Event.stopObserving(document, 'click');
  },
  hide: function(){
    this.toggleDiv.removeClassName('selected');
    this.div.hide();
    this.state = false;
  },
  render: function(){
    $$('._licznik[licznik=wszystko]').invoke('update', this.count);
    this.ul.update('');
    for( var i=0; i<this.data.length; i++ ){
      var v = Number(this.data[i]['licznik']);
      $$('._licznik[licznik='+this.data[i]['id']+']').invoke('update', v);
      if(v>0) {
        this.ul.insert('<li><a href="/'+this.data[i]['href']+'"><span class="label">'+this.data[i]['nazwa']+'</span><span class="_licznik" licznik="'+this.data[i]['id']+'">'+this.data[i]['licznik']+'</span></a></li>');
      }
    }
  },
  update: function(){
    $S('liczniki/pobierz', function(data){
	    this.data = data['data'];
	    this.count = data['count'];
	    this.render();    
    }.bind(this));
  }
});
var $LICZNIKI;

$M.addInitCallback(function(){
  try{
    $LICZNIKI = new LICZNIKI($LICZNIKI_INITIAL_DATA); 
  }catch(e){}; 
});