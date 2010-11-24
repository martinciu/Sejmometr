var Item = Class.create({
  _pola: {data_wybrania: 'Wybrany dnia', data_wybrania: 'Wybrana dnia', lista: 'Lista', okreg_nr: 'Okręg wyborczy', liczba_glosow: 'Liczba głosów', staz: 'Staż parlamentarny', data_urodzenia: 'Data urodzenia', miejsce_urodzenia: 'Miejsce urodzenia', stan_cywilny: 'Stan cywilny', wyksztalcenie: 'Wykształcenie', szkola: 'Ukończona szkoła', zawod: 'Zawód', data_slubowania: 'Ślubował dnia', data_slubowania: 'Ślubowała dnia', tytul: 'Tytuł/stopień naukowy', data_wygasniecia: 'Wygaśnięcie mandatu', nazwisko: 'Nazwisko', klub: 'Klub', image_url: 'Image URL', image_md5: 'Image MD5'},
  initialize: function(data, zmiany){
    this.data = data;
    this.zmiany = zmiany;
    this.id = this.data.id;
   
    this.inner_bar_div = $$('#item .inner_title_bar').first();
    
    mBrowser.itemTitleUpdate(this.data.nazwa ? this.data.nazwa : '<i>Brak nazwy</i>');
    this.btnSave = mBrowser.addItemButton('save', 'Zapisz', this.save.bind(this));
    // this.btnAplikuj = mBrowser.addItemButton('aplikuj', 'Aplikuj zmiany', this.aplikuj_zmiany.bind(this));
    this.btnRegrab = mBrowser.addItemButton('regrab', 'Regrab', this.regrab.bind(this));
    this.btnAvatar = mBrowser.addItemButton('avatar', 'Aktualizuj avatar', this.aktualizuj_avatar.bind(this));
    
    this.refresh_avatars();
    
    var fields = $A();
    // 0
    fields.push({name: 'imie', label: 'Imię', type: 'varchar', value: this.data.imie});
    fields.push({name: 'drugie_imie', label: 'Drugie imię', type: 'varchar', value: this.data.drugie_imie});
    fields.push({name: 'nazwisko', label: 'Nazwisko', type: 'varchar', value: this.data.nazwisko});
    fields.push({name: 'tytul', label: 'Tytuł', type: 'varchar', value: this.data.tytul});
    fields.push({name: 'klub', label: 'Klub', type: 'radio', options: _kluby_options, value: this.data.klub});

    // 5
    fields.push({name: 'data_urodzenia', label: 'Data urodzenia', type: 'date', value: this.data.data_urodzenia});
    fields.push({name: 'data_wybrania', label: 'Data wybrania', type: 'date', value: this.data.data_wybrania});
    fields.push({name: 'data_slubowania', label: 'Data ślubowania', type: 'date', params: {canBeEmpty: true}, value: this.data.data_slubowania});
    fields.push({name: 'data_wygasniecia', label: 'Data wygaśnięcia', type: 'date', params: {canBeEmpty: true}, value: this.data.data_wygasniecia});
    
    // 9
    fields.push({name: 'miejsce_urodzenia', label: 'Miejsce urodzenia', type: 'varchar', value: this.data.miejsce_urodzenia});
    fields.push({name: 'okreg_nr', label: 'Okręg', type: 'integer', value: this.data.okreg_nr});
    fields.push({name: 'lista', label: 'Lista', type: 'varchar', value: this.data.lista});
    fields.push({name: 'liczba_glosow', label: 'Liczba głosów', type: 'integer', value: this.data.liczba_glosow});

    // 13
    fields.push({name: 'stan_cywilny', label: 'Stan cywilny', type: 'radio', options:[[1,'wolny'], [2,'małżeństwo'], [3,'wdowiec']], value: this.data.stan_cywilny});
    fields.push({name: 'wyksztalcenie', label: 'Wykształcenie', type: 'radio', options:[[1,'zawodowe'], [2,'policealne'], [3,'średnie'], [4, 'wyższe']], value: this.data.wyksztalcenie});
    fields.push({name: 'staz', label: 'Staż', type: 'text', value: this.data.staz});
    fields.push({name: 'szkola', label: 'Szkoła', type: 'text', value: this.data.szkola});
    fields.push({name: 'zawod', label: 'Zawód', type: 'varchar', value: this.data.zawod});
    
    // 18
    fields.push({name: 'image_url', label: 'Image URL', type: 'varchar', value: this.data.image_url});
    fields.push({name: 'image_md5', label: 'Image MD5', type: 'varchar', value: this.data.image_md5});
    
    
    this.form = new mForm('item_form', fields, {activateFirstInvalid: true});

    
    
    $('zmiany').update('');
    for( var i=0; i<this.zmiany.length; i++ ){
      var zmiana = this.zmiany[i];
      
      var li = new Element('li', {zmiana_id: zmiana.id}).observe('click', this.zmiana_click.bind(this)).update('<p class="nazwa">'+zmiana['typ']+'<span class="separator">·</span><span class="nazwa">'+this._pola[zmiana['nazwa']]+'</span><span class="separator">·</span>'+zmiana['data_dodania']+'</p><p class="wartosc">'+zmiana['wartosc']+'</p>');
      
      $('zmiany').insert(li);
    }
    
    $('zmiany').height_control();
  },
  regrab: function(){
    this.btnRegrab.disable();
    $S('graber/poslowie/pobieranie/pobierz', this.id, function(){
      mBrowser.refreshItem();
    });
  },
  zmiana_click: function(event){
    this.aplikuj_zmiane( event.findElement('li').readAttribute('zmiana_id') );
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
  aplikuj_zmiane: function(zmiana_id){
    var zmiana = this.get_zmiana(zmiana_id);
    var zmiana_li = $$('#zmiany li[zmiana_id='+zmiana_id+']').first();
    
    if( zmiana_li.hasClassName('done') ) return false;
    
    var success = true;
    if( zmiana['typ']=='D' ) return alert('Skasowanie');
    
    switch( zmiana['nazwa'] ) {
    
      case 'data_wybrania': {
        this.form.fields[6].setValue( fix_date(zmiana['wartosc']) ); break;
      }
      
      case 'lista': {
        this.form.fields[11].setValue( zmiana['wartosc'] ); break;
      }
      
      case 'okreg_nr': {
        this.form.fields[10].setValue( zmiana['wartosc'] ); break;
      }
      
      case 'liczba_glosow': {
        this.form.fields[12].setValue( zmiana['wartosc'] ); break;
      }
      
      case 'staz': {
        if( zmiana['wartosc']!='brak' ) this.form.fields[15].setValue( zmiana['wartosc'] ); break;
      }
      
      case 'data_urodzenia': {
        this.form.fields[5].setValue( fix_date(zmiana['wartosc']) ); break;
      }
      
      case 'data_wygasniecia': {
        this.form.fields[8].setValue( fix_date(zmiana['wartosc']) ); break;
      }
      
      case 'miejsce_urodzenia': {
        this.form.fields[9].setValue( zmiana['wartosc'] ); break;
      }
      
      case 'data_slubowania': {
        this.form.fields[7].setValue( fix_date(zmiana['wartosc']) ); break;
      }
      
      case 'tytul': {
        this.form.fields[3].setValue( zmiana['wartosc'] ); break;
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
        this.form.fields[13].setValue( v ); break;
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
        this.form.fields[14].setValue( v ); break;
      }
      
      case 'szkola': {
        this.form.fields[16].setValue( zmiana['wartosc'] ); break;
      }
      
      case 'zawod': {
        this.form.fields[17].setValue( zmiana['wartosc'] ); break;
      }
      
      case 'nazwisko': {
        if( zmiana['wartosc']=='Jan Bury s. Józefa' ) {
          var parts = ['Jan','Bury'];
        } else if( zmiana['wartosc']=='Jan Bury s. Antoniego' ) {
          var parts = ['Jan', 'Bury'];
        } else if( zmiana['wartosc']=='Piotr Van der Coghen' ) {
          var parts = ['Piotr', 'Van der Coghen'];
        } else {      
	        var parts = zmiana['wartosc'].split(' ');
	      }

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
      
      case 'klub': {
        if( zmiana['wartosc']=='' ){
          if( this.form.fields[4].getValue()!='' && this.form.fields[4].getValue()!='0000-00-00' ) return alert('klub klops')
          else break;
        }
        var v = _kluby[ zmiana['wartosc'] ];        
        if(v || v==='') this.form.fields[4].setValue( v );
        else return alert('Nie rozpoznano klubu');
        break;
      }
      
      case 'image_url': {
        this.form.fields[18].setValue( zmiana['wartosc'] ); break;
      }
      
      case 'image_md5': {
        this.form.fields[19].setValue( zmiana['wartosc'] ); break;
      }
      
      default: {
        var success = false;
      }
    }
    
    if( success ) zmiana_li.addClassName('done');
    else alert('Błąd przetwarzania - '+zmiana['nazwa']);
  },
  get_zmiana: function(id){
    for( var i=0; i<this.zmiany.length; i++ ) if( this.zmiany[i]['id']==id ) return this.zmiany[i];
  },
  aplikuj_zmiany: function(){
    this.zmiany.pluck('id').each( function(id){
      this.aplikuj_zmiane(id);
    }.bind(this) );
  },
  save: function(){
    if( mBrowser.enabled ) {
      
      var complete = true;
      var lis = $$('#zmiany li');
      for( var i=0; i<lis.length; i++ ) {
        if( !lis[i].hasClassName('done') ) {
          complete = false;
          break;
        }
      }
      if( !complete ) return alert('Nie wszystkie zmiany zostały dodane');
      
      var params = {};
      var canBeEmpty = this.form.fields[8].getValue()!='0000-00-00' && this.form.fields[8].getValue()!='';
      this.form.fields[4].canBeEmpty = canBeEmpty;
      this.form.fields[13].canBeEmpty = canBeEmpty;
      this.form.fields[14].canBeEmpty = canBeEmpty;
      params.pola = this.form.serialize();
      if( !params.pola ) return false;
      
      params.zmiany = $$('#zmiany li.done').invoke('readAttribute', 'zmiana_id');
      
      params.id = this.id;
	    mBrowser.disable_loading();
	    this.btnSave.disable();
	    	    
	    $S('zapisz', params, this.onSave.bind(this), function(){
	      mBrowser.disable_loading
	      this.btnSave.enable();
	    }.bind(this));
	    
    }
  },
  onSave: function(data){
    if( Object.isArray(data) ) {
      var new_id = data[1];
      data = data[0];
    }
    
    if( data=='4' ) {
            
      var listitem = mBrowser.listDiv.down('.listitem[mid='+this.id+']');
      if( listitem ) listitem.writeAttribute('mid', new_id);
      
      mBrowser.enable_loading();
      $LICZNIKI.update();
      if( mBrowser.category.id=='doakceptu' ) {
        mBrowser.markAsDeleted( new_id );
        mBrowser.loadItem( new_id );
      }      
    } else if( data=='3' ) {
      alert('Nie ma jednoznacznego odpowiednika w tabeli \'ludzie\'');
    } else if( data=='7' ) {
      alert('Nie mogłem utworzyć nowego człowieka');
    } else { alert('Druk nie został zapisany'); }
    mBrowser.enable_loading();
    this.btnSave.enable();
  },
  delete_p: function(){
    $$('#txt p.s').invoke('remove');
  },
  aktualizuj_avatar: function(){
    this.btnAvatar.disable();
    $S('zmien_avatar', this.id, this.refresh_avatars.bind(this));
  },
  clip_avatar: function(direction){
    var typ = Number(this.selected_avatar.readAttribute('typ'));
    if( typ!=0 ) {
      $S('clip_avatar', [this.id, typ, direction], function(result){
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
