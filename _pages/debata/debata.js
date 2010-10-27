var Debata = Class.create({
  initialize: function(lista_div){
    this.lista_div = $(lista_div);
    
    this.lista_div.select('a.wyp').invoke('observe', 'click', this.wyp_a_click.bind(this));
  },
  wyp_a_click: function(event){
    this.wypowiedz( event.findElement('a.wyp').readAttribute('name') );
  },
  wypowiedz: function(wyp_id){
    this.lista_div.select('a.wyp.selected').invoke('removeClassName', 'selected');
    var wyp_a = this.lista_div.down('a.wyp[name='+wyp_id+']').addClassName('selected');
    
  }
});