var Item = Class.create({
  _pola: {data_wybrania: 'Wybrany dnia', data_wybrania: 'Wybrana dnia', lista: 'Lista', okreg_nr: 'Okręg wyborczy', liczba_glosow: 'Liczba głosów', staz: 'Staż parlamentarny', data_urodzenia: 'Data urodzenia', miejsce_urodzenia: 'Miejsce urodzenia', stan_cywilny: 'Stan cywilny', wyksztalcenie: 'Wykształcenie', szkola: 'Ukończona szkoła', zawod: 'Zawód', data_slubowania: 'Ślubował dnia', data_slubowania: 'Ślubowała dnia', tytul: 'Tytuł/stopień naukowy', data_wygasniecia: 'Wygaśnięcie mandatu', nazwisko: 'Nazwisko', klub: 'Klub', image_url: 'Image URL', image_md5: 'Image MD5'},
  initialize: function(data, zmiany){
    this.data = data;
    this.zmiany = zmiany;
    this.id = this.data.id;
   
    mBrowser.itemTitleUpdate(this.data.nazwa ? this.data.nazwa : '<i>Brak nazwy</i>');
    this.btnSave = mBrowser.addItemButton('save', 'Zapisz', this.save.bind(this));
    
    
    var fields = $A();
    // 0
    fields.push({name: 'imie', label: 'Imię', type: 'varchar', value: this.data.imie});
    fields.push({name: 'drugie_imie', label: 'Drugie imię', type: 'varchar', value: this.data.drugie_imie});
    fields.push({name: 'nazwisko', label: 'Nazwisko', type: 'varchar', value: this.data.nazwisko});
    fields.push({name: 'tytul', label: 'Tytuł', type: 'varchar', value: this.data.tytul});

    // 4
    fields.push({name: 'data_urodzenia', label: 'Data urodzenia', type: 'date', value: this.data.data_urodzenia});
    fields.push({name: 'data_wybrania', label: 'Data wybrania', type: 'date', value: this.data.data_wybrania});
    fields.push({name: 'data_slubowania', label: 'Data ślubowania', type: 'date', value: this.data.data_slubowania});
    fields.push({name: 'data_wygasniecia', label: 'Data wygaśnięcia', type: 'date', value: this.data.data_wygasniecia});
    
    // 8
    fields.push({name: 'miejsce_urodzenia', label: 'Miejsce urodzenia', type: 'varchar', value: this.data.miejsce_urodzenia});
    fields.push({name: 'okreg_nr', label: 'Okręg', type: 'integer', value: this.data.okreg_nr});
    fields.push({name: 'lista', label: 'Lista', type: 'varchar', value: this.data.lista});
    fields.push({name: 'liczba_glosow', label: 'Liczba głosów', type: 'integer', value: this.data.liczba_glosow});

    // 12
    fields.push({name: 'stan_cywilny', label: 'Stan cywilny', type: 'radio', options:[[1,'wolny'], [2,'małżeństwo'], [3,'wdowiec']], value: this.data.stan_cywilny});
    fields.push({name: 'wyksztalcenie', label: 'Wykształcenie', type: 'radio', options:[[1,'zawodowe'], [2,'policealne'], [3,'średnie'], [4, 'wyższe']], value: this.data.wyksztalcenie});
    fields.push({name: 'staz', label: 'Staż', type: 'text', value: this.data.staz});
    fields.push({name: 'szkola', label: 'Szkoła', type: 'text', value: this.data.szkola});
    fields.push({name: 'zawod', label: 'Zawód', type: 'varchar', value: this.data.zawod});
    
    this.form = new mForm('item_form', fields, {activateFirstInvalid: true});

    
    
    $('zmiany').update('');
    for( var i=0; i<this.zmiany.length; i++ ){
      var zmiana = this.zmiany[i];
      
      var li = new Element('li', {zmiana_id: zmiana.id}).observe('click', this.zmiana_click.bind(this)).update('<p class="nazwa">'+zmiana['typ']+'<span class="separator">·</span><span class="nazwa">'+this._pola[zmiana['nazwa']]+'</span><span class="separator">·</span>'+zmiana['data_dodania']+'</p><p class="wartosc">'+zmiana['wartosc']+'</p>');
      
      $('zmiany').insert(li);
    }
    
    $('zmiany').height_control();
  },
  zmiana_click: function(event){
    this.aplikuj_zmiane( event.findElement('li').readAttribute('zmiana_id') );
  },
  aplikuj_zmiane: function(zmiana_id){
    var zmiana = this.get_zmiana(zmiana_id);
    var success = true;
    if( zmiana['typ']=='D' ) return alert('Skasowanie');
    
    switch( zmiana['nazwa'] ) {
    
      case 'data_wybrania': {
        this.form.fields[5].setValue( fix_date(zmiana['wartosc']) ); break;
      }
      
      case 'lista': {
        this.form.fields[10].setValue( zmiana['wartosc'] ); break;
      }
      
      case 'okreg_nr': {
        this.form.fields[9].setValue( zmiana['wartosc'] ); break;
      }
      
      case 'liczba_glosow': {
        this.form.fields[11].setValue( zmiana['wartosc'] ); break;
      }
      
      case 'staz': {
        if( zmiana['wartosc']!='brak' ) this.form.fields[14].setValue( zmiana['wartosc'] ); break;
      }
      
      case 'data_urodzenia': {
        this.form.fields[4].setValue( fix_date(zmiana['wartosc']) ); break;
      }
      
      case 'miejsce_urodzenia': {
        this.form.fields[8].setValue( zmiana['wartosc'] ); break;
      }
      
      case 'stan_cywilny': {
        var v;
        switch( zmiana['wartosc'] ) {
          case 'wolny': { v=1; break; }
          case 'żonaty': { v=2; break; }
          case 'mężatka': { v=2; break; }
          case 'wdowiec': { v=3; break; }
          default: { return alert('Nie rozpoznano stanu cywilnego'); }
        }
        this.form.fields[12].setValue( v ); break;
      }
      
      case 'wyksztalcenie': {
        var v;
        switch( zmiana['wartosc'] ) {
          case 'średnie zawodowe': { v=1; break; }
          case 'średnie policealne': { v=2; break; }
          case 'średnie ogólne': { v=3; break; }
          case 'wyższe': { v=4; break; }
          default: { return alert('Nie rozpoznano wykształcenia'); }
        }
        this.form.fields[13].setValue( v ); break;
      }
      
      case 'szkola': {
        this.form.fields[15].setValue( zmiana['wartosc'] ); break;
      }
      
      case 'zawod': {
        this.form.fields[16].setValue( zmiana['wartosc'] ); break;
      }
      
      case 'nazwisko': {
        var parts = zmiana['wartosc'].split(' ');
        if( parts.length==2 ) {
          this.form.fields[0].setValue( parts[0] );
          this.form.fields[1].setValue( '' );
          this.form.fields[2].setValue( parts[1] );
        } else if( parts.length==3 ) {
          this.form.fields[0].setValue( parts[0] );
          this.form.fields[1].setValue( parts[1] );
          this.form.fields[2].setValue( parts[2] );
        } else return alert('Błąd rozpoznawania nazwiska');
        
        break;
      }

      default: {
        var success = false;
      }
    }
    
    if( success ) $$('#zmiany li[zmiana_id='+zmiana_id+']').first().addClassName('done');
    else alert('Błąd przetwarzania - '+zmiana['nazwa']);
  },
  get_zmiana: function(id){
    for( var i=0; i<this.zmiany.length; i++ ) if( this.zmiany[i]['id']==id ) return this.zmiany[i];
  },
  save: function(){
    if( mBrowser.enabled ) {
      params = {};
      var divs = this.tabs.get_tab_divs();
      for( var i=0; i<divs.length; i++ ){
        var div = divs[i];
        params[ div.readAttribute('tab') ] = div.innerHTML;
      }
      
      params.id = this.id;
	    mBrowser.disable_loading();
	    this.btnSave.disable();
	    	    
	    $POST_SERVICE('zapisz', params, this.onSave.bind(this), function(){
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
        mBrowser.markAsDeleted(this.id);
        mBrowser.loadNextItem();
      }      
    } else alert('Druk nie został zapisany');
    mBrowser.enable_loading();
    this.btnSave.enable();
  },
  delete_p: function(){
    $$('#txt p.s').invoke('remove');
  }
});



var MBrowser = Class.create(MBrowser, {

  getListItemInnerHTML: function(data){
    return data['nazwa'] ? $data['nazwa'] : '<i>Brak nazwy</i>';
  },
  
  afterCloseItem: function(){ $('zmiany').update(''); }
  
  
});
var item;
var mBrowser;

function fix_date(d){
  var parts = d.split('-');
  return parts[2]+'-'+parts[1]+'-'+parts[0];
}

$M.addInitCallback(function(){
  Event.observe(document, 'keypress', function(event){
	  if( event.ctrlKey && event.charCode==115 ) { item.save(); }
	});
});
