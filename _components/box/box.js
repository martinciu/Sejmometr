var Box = Class.create({
  init: function(){
    this.div = $$('._BOX').first();
    if( this.div.hasClassName('_HORIZONTAL') ) this.type = 'horizontal';
    if( this.div.hasClassName('_VERTICAL') ) this.type = 'vertical';
    
    Event.observe(window, 'resize', this.resize.bind(this));
    this.resize();
  },
  resize: function(){
    var h = document.viewport.getHeight()-$('_HEADER').getHeight()-$('_FOOTER').getHeight()-$$('._BOX_HEADER').first().getHeight()-$$('._BOX_FOOTER').first().getHeight();
    if( this.type=='vertical' ) this.div.down('._LEFT_TD').setStyle({height: h-52+'px'});
    /*
    if( this.type=='horizontal' ) {
      h = h - this.div.down('._TOP').getHeight()-50;
      h = Math.max( h, this.div.down('._BOTTOM').getHeight()+30 );
      this.div.down('._BOTTOM').setStyle({height: h-50+'px'});
    }
    */
  }
});
var box = new Box();


/*
$$('a.dokument img').invoke('observe', 'mouseover', function(event){
  $$('a.dokument.lb').invoke('remove');
  var img = event.findElement('img');
  var dokument_id = img.readAttribute('dokument_id');
  var a = new Element('a', {className: 'dokument b lb', href: '#'}).update('<img src="/d/1/'+dokument_id+'.gif" dokument_id="'+dokument_id+'" />');
  a.observe('mouseout', function(event){
    var a = event.findElement('a.dokument');
    if(a) a.remove();
  });
  img.up('a.dokument').insert({before: a});
});
*/