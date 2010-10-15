var MBrowser = Class.create(MBrowser, {

  getListItemInnerHTML: function(data){
    return data['plik'];
  },
  
  arm: function(){
    this.itemDiv.select('.btn.sprawdz_status_scribd').invoke('observe', 'click', this.sprawdz_status_scribd.bind(this));
    this.itemDiv.select('.btn.pobierz_plik').invoke('observe', 'click', this.pobierz_plik.bind(this));
    this.itemDiv.select('.btn.usun_scribd').invoke('observe', 'click', this.usun_scribd.bind(this));
    this.itemDiv.select('.btn.automator').invoke('observe', 'click', this.automator.bind(this));
    this.itemDiv.select('.btn.pobierz_automator').invoke('observe', 'click', this.pobierz_automator.bind(this));
    this.itemDiv.select('.btn.ustaw_reczny_scribd').invoke('observe', 'click', this.ustaw_reczny_scribd.bind(this));
    
  },

  pobierz_plik: function(){
    if( this.enabled ) {
      this.disable();
      $S('graber/dokumenty/pobieranie/pobierz', this.mid, function(data){
        this.enable();
        if( data===true ) {
          this.clear();
          this.load();
          $LICZNIKI.update();         
        } else if(data===false) {
          this.loadItem(this.mid);
          alert('Nie udało się pobrać pliku');
        } else {
          alert('Brak odpowiedzi.');
          this.clear();
          this.load();
        }
      }.bind(this), this.onLoadFail.bind(this));
    }
  },
  
  sprawdz_status_scribd: function(){
    if( this.enabled ) {
      this.disable();
      $S('graber/dokumenty/scribd/status', this.mid, function(data){
        this.enabled = true;
        alert(data);
      }.bind(this), this.onLoadFail.bind(this));
    }
  },
  
  ustaw_reczny_scribd: function(){
    if( this.enabled ) {
      this.disable();
      
      var sid = prompt('Podaj ScribdId:');   
      $S('graber/dokumenty/scribd/ustaw_recznie_id', [sid, this.mid], function(data){
        this.enabled = true;
        alert(data);
      }.bind(this), this.onLoadFail.bind(this));
      
    }
  },
  
  
  usun_scribd: function(){
    if( this.enabled ) {
      if( confirm('Czy na pewno chcesz usunąć ten dokument z bazy Scribd?') ) {
	      this.disable();
	      $S('graber/dokumenty/scribd/usun', this.mid, function(data){
	        this.enable();
	        this.clear();
          this.load();  
	      }.bind(this), this.onLoadFail.bind(this));
      }
    }
  },
  
  automator: function(){
    if( this.enabled ) {
      this.disable();
      $S('graber/dokumenty/przeslij_do_automatora', this.mid, function(data){
        this.enable();
        $LICZNIKI.update(); 
      }.bind(this), this.onLoadFail.bind(this));
    }
  },
  
  pobierz_automator: function(){
    if( this.enabled ) {
      this.disable();
      $S('graber/dokumenty/obrazy/pobierz', this.mid, function(data){
        this.enable();
        $LICZNIKI.update(); 
      }.bind(this), this.onLoadFail.bind(this));
    }
  },
  
  
  
});
var mBrowser;