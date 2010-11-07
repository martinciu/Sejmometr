var Projekt = Class.create({
  _czytania_typy: {'1': {'undefined': '<span>Wybierz typ</span>', '1': 'Pierwsze czytanie', '2': 'Drugie czytanie', '3': 'Trzecie czytanie', '4': 'Rozpatrywanie stanowiska Senatu', '5': 'Rozpatrywanie niezgodności z Konstytucją', '6': 'Rozpatrywanie wniosku Prezydenta'}, '2': {'undefined': '<span>Wybierz typ</span>', '1': 'Pierwsze czytanie', '2': 'Drugie czytanie', '3': 'Trzecie czytanie'}, '3': {'0': 'Rozpatrywanie', '1': 'Przyjęcie bez zastrzeżeń', '3': 'Wysłuchanie'}},
  _statusy: {'1': {'1': 'Przed pierwszym czytaniem', '2': 'Rozpatrywane w Sejmie', '3': 'Rozpatrywane w Senacie', '4': 'Rozpatrywane przez Prezydenta', '5': 'Przyjęty', '6': 'Odrzucony', '7': 'Rozpatrywane w Trybunale'}, '2': {'1': 'Przyjęto', '2': 'Rozpatrywane', '3': 'Odrzucono'}, '3': {'1': 'Przyjęto', '2': 'Rozpatrywane', '3': 'Odrzucono'}},
  _typy_schematy: {'1':'1', '2':'2', '3':'3', '4':'1', '5':'3', '7':'2', '8':'3', '10':'3', '11':'3', '12':'2'},
  initialize: function(data, druki, wypowiedzi, glosowania, isap, wyroki, etapy){
    this.id = data.id;
    this.data = data;
    this.druki = druki;
    this.wypowiedzi = wypowiedzi;
    this.glosowania = glosowania;
    this.isap = isap;
    this.wyroki = wyroki;
    this.etapy = etapy;
    this.html_data = {status: 0, stanowisko_senatu: '1', data_podpisania: '0000-00-00', data_wycofania: '0000-00-00'};
    
    this._schemat = this._typy_schematy[this.data.typ_id];
    this._czytania_typy = this._czytania_typy[this._schemat];
    this._statusy = this._statusy[this._schemat];
    this._czytania_typy_ids = Object.keys(this._czytania_typy).without('undefined');    
    
    mBrowser.itemTitleUpdate(data.numer ? data.numer : '<i>Bez tytułu</i>');
    this.btnSave = mBrowser.addItemButton('save', 'Zapisz', this.save.bind(this));
    this.btnNochanges = mBrowser.addItemButton('nochanges', 'Brak zmian', this.nochanges.bind(this));
    this.btnRegrab = mBrowser.addItemButton('regrab', 'Pobierz', this.regrab.bind(this));

    
    $('autor_a').update( _autor_label(this.data.autor_id) );
    $('typ_span').update( _projekt_typ_label(this.data.typ_id) );
    
    
    // SIDE_DIV
    $('druki_ul').update('').scrollTop = 0;
    $('wypowiedzi_ul').update('').scrollTop = 0;
    $('glosowania_ul').update('').scrollTop = 0;
    if( !this.data.html ) {
      $('side_div').update('<p class="msg">Brak HTML</p>');
      return false;
    }
    
    $('side_div').update(this.data.html).scrollTop = 0;
    if($('side_div').down('table')){
      $('side_div').down('table').writeAttribute('width', '100%');
      for( var i=0; i<3; i++ ) $('side_div').down('table tr').remove();
    }
    
    
    $('side_div').select('font').each( function(font){
      var match = font.innerHTML.match(/Dnia ([0-9]{2})\-([0-9]{2})\-([0-9]{4}) skierowano do (.*?) na posiedzeniu Sejmu/i);
      if( match ) {
        var data = match[3]+'-'+match[2]+'-'+match[1];
        return font.writeAttribute({data: data, adresat: 'Poslowie', element_typ: 'skierowanie'}).addClassName('link').observe('click', this.elementClick.bind(this, 'side_div'));
      }
      
      var match = font.innerHTML.match(/^odrzucony na pos/i);
      if( match ) {
        this.html_data['status'] = 1;
        return font.addClassName('link x y');
      }
      
      var match = font.innerHTML.match(/^wycofany dnia ([0-9]{2})\-([0-9]{2})\-([0-9]{4})/i);
      if( match ) {
        this.html_data['data_wycofania'] = match[3]+'-'+match[2]+'-'+match[1];;
        return font.addClassName('link x y');
      }
      
      var match = font.innerHTML.match(/^anulowany dnia ([0-9]{2})\-([0-9]{2})\-([0-9]{4})/i);
      if( match ) {
        this.html_data['data_wycofania'] = match[3]+'-'+match[2]+'-'+match[1];;
        return font.addClassName('link x y');
      }
      
      var match = font.innerHTML.match(/^nieaktualny dnia ([0-9]{2})\-([0-9]{2})\-([0-9]{4})/i);
      if( match ) {
        this.html_data['data_wycofania'] = match[3]+'-'+match[2]+'-'+match[1];;
        return font.addClassName('link x y');
      }
      
      var match = font.innerHTML.match(/^odrzucono na wniosek Senatu/i);
      if( match ) {
        this.html_data['status'] = 2;
        return font.addClassName('link x y');
      }
      
      var match = font.innerHTML.match(/^nieuchwalona ponownie po wecie Prezydenta/i);
      if( match ) {
        this.html_data['status'] = 3;
        return font.addClassName('link x y');
      }
      
      var match = font.innerHTML.match(/Trybunał Konstytucyjny orzekł przed podpisaniem ustawy przez Prezydenta jej niezgodność z Konstytucją$/i);
      if( match ) {
        this.html_data['status'] = 4;
        return font.addClassName('link x y');
      }
      
      var match = font.innerHTML.match(/Marszałek Sejmu - po opinii Prezydium Sejmu - nie nadał biegu wnioskowi Prezydenta RP o ponowne rozpatrzenie ustawy$/i);
      if( match ) {
        this.html_data['status'] = 5;
        return font.addClassName('link x y');
      }
      
      var match = font.innerHTML.match(/^przyjęto na pos\./i);
      if( match ) {
        this.html_data['status'] = 6;
        return font.addClassName('link x y');
      }
      
      var match = font.innerHTML.match(/nieaktualny na pos\./i);
      if( match ) {
        this.html_data['status'] = 7;
        return font.addClassName('link x y');
      }
            
      var match = font.innerHTML.match(/przez aklamację/i);
      if( match ) {
        this.aklamacja = true;
        return font.addClassName('_aklamacja');
      }
      
      var match = font.innerHTML.match(/podjęto uchwałę na pos\./i) || font.innerHTML.match(/^przyjęto bez zastrzeżeń na pos\./i);
      if( match ) {
        this.html_data['status'] = 9;
        return font.addClassName('link x y');
      }
      
      var match = font.innerHTML.match(/Sejm wysłuchał informacji na pos\./i);
      if( match ) {
        this.html_data['status'] = 6;
        return font.addClassName('link x y');
      }
      
      var match = font.innerHTML.match(/Sejm zapoznał się na pos\./i);
      if( match ) {
        this.html_data['status'] = 10;
        return font.addClassName('link x y');
      }
      
      var match = font.innerHTML.match(/^nie przyjęto na pos\./i);
      if( match ) {
        this.html_data['status'] = 11;
        return font.addClassName('link x y');
      }
      
      var match = font.innerHTML.match(/^wykorzystano w pracach nad projektem ustawy budżetowej/i);
      if( match ) {
        this.html_data['status'] = 12;
        return font.addClassName('link x y');
      }
      
      var match = font.innerHTML.match(/^wykorzystano w pracach nad sprawozdaniem z wykonania budżetu na pos\./i);
      if( match ) {
        this.html_data['status'] = 13;
        return font.addClassName('link x y');
      }
      
      var match = font.innerHTML.match(/^zakończono rozpatrywanie na pos\./i);
      if( match ) {
        this.html_data['status'] = 14;
        return font.addClassName('link x y');
      }
                 
                        
      if( font.innerHTML.match(/^skierowano$/i) || font.innerHTML.match(/^skierowano do$/i) ) {
        var text = font.up('tr').select('font').last().innerHTML.strip();
        var data;
        var data_zalecenie = '';
        var datematch = text.match(/dnia ([0-9]{2})\-([0-9]{2})\-([0-9]{4}) do (.*?)$/i);
        // alert(datematch);
        if( datematch ) {
          text = datematch[4];
        } else {
          for( var _i=0; _i<6; _i++ ) {
            var datematch = font.up('tr').previous('tr', _i).down('font').innerHTML.match(/([0-9]{2})\-([0-9]{2})\-([0-9]{4})/i);
            if(datematch) break;
          }
        }
        data = datematch[3]+'-'+datematch[2]+'-'+datematch[1];
        
        var match = text.match(/^(.*?) z zaleceniem przedstawienia sprawozdania do dnia ([0-9]{2})\-([0-9]{2})\-([0-9]{4})/i);
        if( match ) {
          text = match[1];
          data_zalecenie = match[4]+'-'+match[3]+'-'+match[2];
        }
        var adresat = _autorzy_to_id(text);
        
        return font.writeAttribute({data: data, data_zalecenie: data_zalecenie, adresat: adresat, element_typ: 'skierowanie'}).addClassName('link').observe('click', this.elementClick.bind(this, 'side_div'));
      }
            
    }.bind(this) );
    
    if( this.aklamacja ) this.html_data['status'] = 8;
    
    // data wpłynął
    var b = $('side_div').down('tr').select('b').last();
    var match = b.innerHTML.match(/wpłynął ([0-9]{2})\-([0-9]{2})\-([0-9]{4})/i);
    if( match ) {
      this.html_data['data_wplynal'] = match[3]+'-'+match[2]+'-'+match[1];
      b.addClassName('link x y');
    }
        
    // czytania
    $('side_div').select('b').each( function(b){
		  
		  
		  // stanowisko senatu
		  if( b.innerHTML=='STANOWISKO SENATU' ) {
		    if( b.up('tr').next('tr').innerHTML.stripTags().match(/nie wniósł poprawek/i) ) {
		      this.html_data['stanowisko_senatu'] = '0';
		      b.up('tr').next('tr').select('font').last().addClassName('link x y');
		    }
		    return true;
		  }
		  
		  // czytanie w komisjach
		  if( b.innerHTML=='I CZYTANIE W KOMISJACH' ) {

		    
		    var match = b.up('tr').next('tr', 1).innerHTML.stripTags().match(/I czytanie odbyło się: ([0-9]{2})\-([0-9]{2})\-([0-9]{4})/i);
		    if( match ) {
		      var adresat = b.up('tr').next('tr').down('.link[element_typ=skierowanie]').readAttribute('adresat');
		      var data = match[3]+'-'+match[2]+'-'+match[1];
		      b.up('tr').next('tr', 1).down('font').addClassName('link').writeAttribute({data: data, komisje: adresat, element_typ: 'czytanie_komisje'}).observe('click', this.elementClick.bind(this, 'side_div'));
		    }
		    
		    
		    return true;
		  }
		  
		  
		  // czytania na posiedzeniach sejmu
	    var match = b.innerHTML.match(/([I]+) CZYTANIE NA POSIEDZENIU SEJMU/i);
	    if( match ) {
	      var typ = match[1];
	      if( typ=='I' || typ=='II' || typ=='III' ) {	        
		      var datematch = b.up('tr').next('tr').down('font').innerHTML.match(/([0-9]{2})\-([0-9]{2})\-([0-9]{4})/i);
		      if( datematch ) {
		        var data = datematch[3]+'-'+datematch[2]+'-'+datematch[1];
		        b.writeAttribute({element_typ: 'czytanie', czytanie_typ: typ.length, data: data}).addClassName('link').observe('click', this.elementClick.bind(this, 'side_div'));
		      } else b.addClassName('invalidlink');
	      
	      } else b.addClassName('invalidlink'); 
	      return true;
	    }
	    
	    
	    // rozpatrywania na posiedzeniach sejmu
	    var match = b.innerHTML.match(/^ROZPATRYWANIE NA FORUM SEJMU STANOWISKA SENATU/i);
	    if( match ) {		       
	      var datematch = b.up('tr').next('tr').down('font').innerHTML.match(/([0-9]{2})\-([0-9]{2})\-([0-9]{4})/i);
	      if( datematch ) {
	        var data = datematch[3]+'-'+datematch[2]+'-'+datematch[1];
	        var element_typ = 'czytanie';
	        var wynik = '0';
	        try {
	          for(var j=1; j<6; j++){
		          var text = b.up('tr').next(j).innerHTML;
		          if( text.match(/przyjęto/i) ) {
		            element_typ = 'glosowanie';
		            wynik = '1';
		            break;
		          }
		          if( text.match(/odrzucono/i) ) {
		            element_typ = 'glosowanie';
		            wynik = '2';
		            break;
		          }
	          }        
	        }catch(e){}
	        b.writeAttribute({element_typ: element_typ, czytanie_typ: '4', data: data, wynik: wynik}).addClassName('link').observe('click', this.elementClick.bind(this, 'side_div'));
	      } else b.addClassName('invalidlink');
	      
	      return true;
	    }
	    
	    // rozpatrywanie
	    var match = b.innerHTML.match(/^ROZPATRYWANIE NA FORUM SEJMU/i);
	    if( match ) {		       
	      var datematch = b.up('tr').next('tr').down('font').innerHTML.match(/([0-9]{2})\-([0-9]{2})\-([0-9]{4})/i);
	      if( datematch ) {
	        var data = datematch[3]+'-'+datematch[2]+'-'+datematch[1];
	        var element_typ = 'czytanie';
	        var wynik = '0';
	        try {
	          for(var j=1; j<6; j++){
		          var text = b.up('tr').next(j).innerHTML;
		          if( text.match(/przyjęto/i) ) {
		            wynik = '1';
		            break;
		          }
		          if( text.match(/odrzucono/i) ) {
		            element_typ = 'glosowanie';
		            wynik = '2';
		            break;
		          }
		          if( text.match(/Sejm wysłuchał informacji/i) ) {
		            wynik = '3';
		            break;
		          }
		           	
	          }        
	        }catch(e){}
	        b.writeAttribute({element_typ: element_typ, czytanie_typ: wynik, data: data}).addClassName('link').observe('click', this.elementClick.bind(this, 'side_div'));
	      } else b.addClassName('invalidlink');
	      
	      return true;
	    }
	    
	    
	    // rozpatrywania niezgodności z Konstytuncją 
	    var match = b.innerHTML.match(/^ROZPATRYWANIE NA FORUM SEJMU SPRAWOZDANIA KOMISJI W SPRAWIE USUNIĘCIA NIEZGODNOŚCI Z KONSTYTUCJĄ/i);
	    if( match ) {		       
	      var datematch = b.up('tr').next('tr').down('font').innerHTML.match(/([0-9]{2})\-([0-9]{2})\-([0-9]{4})/i);
	      if( datematch ) {
	        var data = datematch[3]+'-'+datematch[2]+'-'+datematch[1];
	        var element_typ = 'czytanie';
	        var wynik = '0';
	        try {
	          for(var j=1; j<6; j++){
		          var text = b.up('tr').next(j).innerHTML;
		          if( text.match(/przyjęto/i) ) {
		            element_typ = 'glosowanie';
		            wynik = '1';
		            break;
		          }
		          if( text.match(/odrzucono/i) ) {
		            element_typ = 'glosowanie';
		            wynik = '2';
		            break;
		          }
	          }        
	        }catch(e){}
	        b.writeAttribute({element_typ: element_typ, czytanie_typ: '5', data: data, wynik: wynik}).addClassName('link').observe('click', this.elementClick.bind(this, 'side_div'));
	      } else b.addClassName('invalidlink');
	      
	      return true;
	    }
	    
	    // ROZPATRYWANIE NA FORUM SEJMU WNIOSKU PREZYDENTA
	    var match = b.innerHTML.match(/^ROZPATRYWANIE NA FORUM SEJMU WNIOSKU PREZYDENTA/i);
	    if( match ) {		       
	      var datematch = b.up('tr').next('tr').down('font').innerHTML.match(/([0-9]{2})\-([0-9]{2})\-([0-9]{4})/i);
	      if( datematch ) {
	        var data = datematch[3]+'-'+datematch[2]+'-'+datematch[1];
	        var element_typ = 'czytanie';
	        var wynik = '0';
	        try {
	          for(var j=1; j<6; j++){
		          var text = b.up('tr').next(j).innerHTML;
		          if( text.match(/przyjęto/i) ) {
		            element_typ = 'glosowanie';
		            wynik = '1';
		            break;
		          }
		          if( text.match(/odrzucono/i) ) {
		            element_typ = 'glosowanie';
		            wynik = '2';
		            break;
		          }
	          }        
	        }catch(e){}
	        b.writeAttribute({element_typ: element_typ, czytanie_typ: '6', data: data, wynik: wynik}).addClassName('link').observe('click', this.elementClick.bind(this, 'side_div'));
	      } else b.addClassName('invalidlink');
	      
	      return true;
	    }
	    
	    
	    
	    
	    
	    
	    
	    
	    // przekazano Prezydentowi i Marszałkowi Senatu
	    var match = b.innerHTML.match(/^Ustawę przekazano Prezydentowi i Marszałkowi Senatu dnia ([0-9]{2})\-([0-9]{2})\-([0-9]{4})/i);
	    if( match ) {		       
        var data = match[3]+'-'+match[2]+'-'+match[1];
        b.writeAttribute({data: data, adresat: 'Marszalek_Senatu,Prezydent', element_typ: 'skierowanie'}).addClassName('link').observe('click', this.elementClick.bind(this, 'side_div'));
	      return true;
	    }
	    		
	    		    
	    // przekazano TK
	    var match = b.innerHTML.match(/^Prezydent skierował ustawę do Trybunału Konstytucyjnego dnia ([0-9]{2})\-([0-9]{2})\-([0-9]{4})/i);
	    if( match ) {		       
        var data = match[3]+'-'+match[2]+'-'+match[1];
        b.writeAttribute({data: data, adresat: 'TK', element_typ: 'skierowanie'}).addClassName('link').observe('click', this.elementClick.bind(this, 'side_div'));
	      return true;
	    }
	    
	    // przekazano TK
	    var match = b.innerHTML.match(/^Ustawę przekazano Prezydentowi do podpisu dnia ([0-9]{2})\-([0-9]{2})\-([0-9]{4})/i);
	    if( match ) {		       
        var data = match[3]+'-'+match[2]+'-'+match[1];
        b.writeAttribute({data: data, adresat: 'Prezydent', element_typ: 'skierowanie'}).addClassName('link').observe('click', this.elementClick.bind(this, 'side_div'));
	      return true;
	    }
	    
	    
	    // Prezydent podpisał 
	    var match = b.innerHTML.match(/^Dnia ([0-9]{2})\-([0-9]{2})\-([0-9]{4}) Prezydent podpisał ustawę/i);
	    if( match ) {	       
        this.html_data['data_podpisania'] = match[3]+'-'+match[2]+'-'+match[1];
        b.addClassName('link x y');
	      return true;
	    }
		
		}.bind(this) );
    
    
    // druki
    $('side_div').select('a').each( function(a){
      var href = a.readAttribute('href');
      a.writeAttribute({href: '#', _href: href, onclick: 'return false;'});
      
      var match = href.match(/http:\/\/orka.sejm.gov.pl\/Druki6ka.nsf\/druk\?OpenAgent&([ A-F0-9\-]+)/i);
      if( match ) {
        a.addClassName('link').writeAttribute('element_typ', 'druk').writeAttribute('druk_nr', match[1]).observe('click', this.elementClick.bind(this, 'side_div'));
      }
      
      var match = href.match(/http:\/\/isap.sejm.gov.pl\/DetailsServlet\?id=(.*?)$/i);
      
      if( match ) {
        a.addClassName('link').writeAttribute('element_typ', 'isap').writeAttribute('sejm_id', match[1]).observe('click', this.elementClick.bind(this, 'side_div'));
      }
      
      var match = href.match(/http:\/\/orka.sejm.gov.pl\/SQL.nsf\/glosowania/i);
      if( match ) {
        try{
          for(var j=2; j<4; j++){
            var _el = a.up('tr').previous(j).down('.link');
            if(_el) {
              _el.writeAttribute('element_typ', 'glosowanie');
              if(this._schemat=='3') _el.writeAttribute('czytanie_typ', '0');
              break;
            }
          }
        }catch(e){}
      }
      
      
    }.bind(this) );
    
    
    // aklamacja
    var font = $('side_div').down('._aklamacja')
    if( font ) {
      try{
        var el = font.up('tr').previous(2).down('.link');
        if( el.readAttribute('element_typ')=='czytanie' ) {
          el.writeAttribute({element_typ: 'aklamacja', czytanie_typ: null});
        } else alert('Błąd aklamacji');
      }catch(e){}
    }
    
	  
	  try{
	    this.html_data['poprawki_senatu'] = $('side_div').select('.link[element_typ=glosowanie][czytanie_typ=4]').last().readAttribute('wynik');
	  }catch(e){}
	    
    
    // DRUKI
    for( var i=0; i<this.druki.length; i++ ){
      var li = new Element('li', {typ: 'druk', className: 'druk', element_id: druki[i]['id']}).update('<p class="t">'+this.druki[i]['numer']+'</p><p class="o">'+this.druki[i]['typ']+', <a href="#" onclick="return false;">'+this.druki[i]['autor']+'</a></p>').observe('click', this.elementClick.bind(this, 'elementy'));
      $('druki_ul').insert(li);
    }
    
    // WYPOWIEDZI
    for( var i=0; i<this.wypowiedzi.length; i++ ){
      var li = new Element('li', {className: 'punkt', typ: 'wypowiedzi', element_id: this.wypowiedzi[i]['id'], data: this.wypowiedzi[i]['data']}).update('<p class="t">'+this.wypowiedzi[i]['data']+'</p><p class="o"><span class="subt">wypowiedzi</span>: '+this.wypowiedzi[i]['ilosc_wypowiedzi']+'</p>').observe('click', this.elementClick.bind(this, 'elementy'));
      $('wypowiedzi_ul').insert(li);
    }
    
    // GŁOSOWANIA
    for( var i=0; i<this.glosowania.length; i++ ){
      var li = new Element('li', {className: 'punkt', typ: 'glosowania', element_id: this.glosowania[i]['id'], data: this.glosowania[i]['data']}).update('<p class="t">'+this.glosowania[i]['data']+'</p><p class="o"><span class="subt">głosowań</span>: '+this.glosowania[i]['ilosc_glosowan']+'</p>').observe('click', this.elementClick.bind(this, 'elementy'));
      if( this.glosowania[i]['pp']=='1' ) li.addClassName('pp');
      $('glosowania_ul').insert(li);
    }
    
    
    // ETAPY
    for( var i=0; i<this.etapy.length; i++ ){
      var etap = this.etapy[i];
      switch( etap['typ_id'] ) {
        case '0': {
          this.dodaj_druk(etap['etap_id'], false, false);
          break;
        }
        case '1': {
          this.dodaj_czytanie_komisje({id: etap['etap_id'], data: etap['data'], komisje: etap['komisje']}, false, false);
          break;
        }        
        case '2': {
          this.dodaj_wypowiedzi(etap['etap_id'], etap['subtyp'], false, false);
          break;
        }
        case '3': {
          this.dodaj_glosowania(etap['etap_id'], etap['subtyp'], false, false);
          break;
        }
        case '4': {
          this.dodaj_skierowanie({id: etap['etap_id'], data: etap['data'], data_zalecenie: etap['data_zalecenie'], adresat: etap['adresat']}, false, false);
          break;
        }
        case '5': {
          this.dodaj_wyrok(etap['etap_id'], false, false);
          break;
        }
        case '6': {
          this.dodaj_aklamacje({id: etap['etap_id'], data: etap['data']}, false, false);
          break;
        }
        case '7': {
          this.dodaj_wypowiedzi_bw({id: etap['etap_id'], data: etap['data']}, false, false);
          break;
        }
      }
      
    }
    
    if( this.isap.length>0 ) {
      if( this.isap.length>1 ) alert('Ten projekt ma więcej niż jeden ISAP!!!');
      this.html_data['isap_id'] = this.isap[0]['id'];      
      var el = $('side_div').down('.link[element_typ=isap][sejm_id='+this.isap[0]['sejm_id']+']');
      if( el ) { el.addClassName('x y'); } else { alert('W htmlu nie ma ISAP'); }
    }
    
    this.onWindowResize();
    Event.observe(window, 'resize', this.onWindowResize.bind(this));
  },
  onWindowResize: function(){
    var document_height = document.viewport.getHeight();
    var elements_height = document_height/2.5;
    var side_height = document_height-elements_height;
    elements_height -= 130;
    
    $('elementy_table').select('ul').invoke('setStyle', {height: elements_height+'px'});
    $('side_div').setStyle({height: side_height+'px'});
  },
  elementClick: function(typ, event){    
	  var args = $A(arguments);
	  var typ = args[0];
	  var event = args[1];
	  var animation = args[2]!==false;
    var guess_order = args[3]!==false;
        
    if( typ=='elementy' ) {
      
      var czytanie_typ = Object.keys(this._czytania_typy).first();
      var li = Object.isElement(event) ? event : event.findElement('li');      
	    if( !li.hasClassName('x') ){
		    var element_id = li.readAttribute('element_id');
		    switch( li.readAttribute('typ') ) {
		      case 'druk': { this.dodaj_druk(element_id); break; }
		      case 'wypowiedzi': { this.dodaj_wypowiedzi(element_id, czytanie_typ); break; }
		      case 'glosowania': { this.dodaj_glosowania(element_id, czytanie_typ); break; }
		    }
	    }
    
    
    
    } else if(typ=='side_div') {
      
      var link = Object.isElement(event) ? event : event.findElement('.link');
	    if( !link.hasClassName('x') ){
		    var element_typ = link.readAttribute('element_typ');
		    switch( element_typ ) {
		      case 'druk': {
		        var nr = link.readAttribute('druk_nr');
		        for( var i=0; i<this.druki.length; i++ ){
		          if( this.druki[i].numer==nr ) return this.dodaj_druk(this.druki[i].id, animation, guess_order);
		        }
		        link.removeClassName('link').addClassName('invalidlink');
		        break;
		      }
		      case 'isap': {
		        var sejm_id = link.readAttribute('sejm_id');
		        for( var i=0; i<this.wyroki.length; i++ ){
		          if( this.wyroki[i].sejm_id==sejm_id ) return this.dodaj_wyrok(this.wyroki[i].id, animation, guess_order);
		        }
		        link.removeClassName('link').addClassName('invalidlink');
		        break;
		      }
		      case 'czytanie': {
		        var typ = link.readAttribute('czytanie_typ');
		        var data = link.readAttribute('data');
		        		        
		        var wypowiedzi = $('wypowiedzi_ul').select('li[data='+data+']');
		        if( wypowiedzi.length>1 ) return alert('Wybór nie jest jednoznaczny');
		        if( wypowiedzi.length==1 ) { this.dodaj_wypowiedzi(wypowiedzi[0].readAttribute('element_id'), typ, animation, guess_order); } else {
		          var glosowania = $('glosowania_ul').select('li[data='+data+']');
			        if( glosowania.length==1 ) {
			          alert("Nie znalazłem wypowiedzi w tej dacie, ale znalazłem głosowania");
			        } else {
		            if( this._schemat=='3' && typ=='1' && confirm('Czy na pewno chcesz dodać rozpatrywanie bez wypowiedzi?') ){
		              this.dodaj_wypowiedzi_bw({data: data}, animation, guess_order);
		            }
		          }
		        } 
		       	break;
		      }
		      case 'glosowanie': {
		        var typ = link.readAttribute('czytanie_typ');
		        var data = link.readAttribute('data');
		        		        
		        var glosowania = $('glosowania_ul').select('li[data='+data+']');
		        if( glosowania.length>1 ) return alert('Wybór nie jest jednoznaczny');
		        if( glosowania.length==1 ) this.dodaj_glosowania(glosowania[0].readAttribute('element_id'), typ, animation, guess_order);
		       	break;
		      }
		      case 'aklamacja': {
		        this.dodaj_aklamacje({data: link.readAttribute('data')}, animation, guess_order);
		       	break;
		      }
		      case 'skierowanie': {
		        this.dodaj_skierowanie({data: link.readAttribute('data'), data_zalecenie: link.readAttribute('data_zalecenie'), adresat: link.readAttribute('adresat')}, animation, guess_order);
		        break;
		      }
		      case 'czytanie_komisje': {
		        this.dodaj_czytanie_komisje({data: link.readAttribute('data'), komisje: link.readAttribute('komisje')}, animation, guess_order);
		        break;
		      }
		    }
	    }
      
    }
  },
  dodaj_element: function(li, animation, guess_order){
    var data = li.readAttribute('data');
	  if( guess_order ) {
	    
	    var childElements = $('etapy').childElements();
	    switch( childElements.length ) {
	      case 0: { $('etapy').insert(li); break; }
	      case 1: {
	        var t = compare_dates( data, childElements[0].readAttribute('data') ) ? 'bottom' : 'top';
	        var options = {};
	        options[t] = li;
	        $('etapy').insert(options);
	        break;
	      }
	      default: {
	        for( var i=1; i<childElements.length; i++ ) {
	          a = childElements[i-1].readAttribute('data');
	          b = childElements[i].readAttribute('data');
	          
	          if( compare_dates(data, a)  ) {
	            if( compare_dates(data, b) ){
	              if(i==childElements.length-1) {
	                $('etapy').insert({bottom: li});
	                break;
	              }
	            } else {
	              childElements[i].insert({before: li});
		            break;
	            }
	          } else {
	            childElements[i-1].insert({before: li});
	            break;
	          }
	        }
	      }
	    }
	  
	  } else { $('etapy').insert(li); }
	  
	  var lis = $('etapy').select('li[data='+data+']');
	  if( lis.length>1 ) lis.invoke('addClassName', 'date_warning');
	  Sortable.create('etapy');
	  
	  var h = li.getHeight()-6;
	  li.hide().setStyle({height: '0'}).show();

	  
	  if(animation) {
		  new Effect.Tween(null, 0, h, {duration: .25}, function(p){
		    this.setStyle({height: p+'px'});
		    $('etapy').scrollTop = li.offsetTop-$('etapy').offsetTop;
		  }.bind(li));
		  
		  new Effect.Tween(null, 70, 100, {duration: 2.0, transition: Effect.Transitions.sinoidal}, function(p){
	      this.setStyle({backgroundColor: 'rgb('+p+'%, 100%, '+p+'%)'});
	    }.bind(li));
    } else {
      li.show().setStyle({height: h+'px'});
    }
  },
  dodaj_druk: function(){
    var args = $A(arguments);
    var id = args[0];
    var animation = args[1]!==false;
    var guess_order = args[2]!==false;
    
    
    
    if( !$('etapy').down('li[element_typ=druk][element_id='+id+']') ) {
	    this.get_druk_data(id, function(animation, guess_order, data){
	      
	      var li = new Element('li', {className: 'druk', element_typ: 'druk', element_id: data.id, data: data.data}).insert('<h4>'+data.typ+'</h4><p class="data">'+data.data+'</p><p class="tytul">'+data.autor+', '+data.numer+'</p>');
	      this.dodaj_element(li, animation, guess_order);
	      $('druki_ul').select('li[element_id='+id+']').invoke('addClassName', 'x');
	      $('side_div').select('.link[druk_nr='+data.numer+']').invoke('addClassName', 'x');
	      
	    }.bind(this, animation, guess_order));
    }
  },
  dodaj_wyrok: function(){
    var args = $A(arguments);
    var id = args[0];
    var animation = args[1]!==false;
    var guess_order = args[2]!==false;
    
    
    
    if( !$('etapy').down('li[element_typ=wyrok][element_id='+id+']') ) {
	    this.get_wyrok_data(id, function(animation, guess_order, data){
	      
	      var li = new Element('li', {className: 'druk', element_typ: 'wyrok', wynik: data.wynik, element_id: data.id, data: data.data}).insert('<h4>Wyrok TK</h4><p class="data">'+data.data+'</p><p class="tytul">'+data.sejm_id+'. '+_wyrok_label(data.wynik)+'</p>');
	      this.dodaj_element(li, animation, guess_order);
	      $('side_div').select('.link[sejm_id='+data.sejm_id+']').invoke('addClassName', 'x');
	      
	    }.bind(this, animation, guess_order));
    }
  },
  get_druk_data: function(id, callback){
    for( var i=0; i<this.druki.length; i++ ) {
      if( this.druki[i]['id']==id ) callback(this.druki[i]);
    }
  },
  get_wyrok_data: function(id, callback){
    for( var i=0; i<this.wyroki.length; i++ ) {
      if( this.wyroki[i]['id']==id ) callback(this.wyroki[i]);
    }
  },
  get_wypowiedzi_data: function(id, callback){
    for( var i=0; i<this.wypowiedzi.length; i++ ) {
      if( this.wypowiedzi[i]['id']==id ) callback(this.wypowiedzi[i]);
    }
  },
  get_glosowania_data: function(id, callback){
    for( var i=0; i<this.glosowania.length; i++ ) {
      if( this.glosowania[i]['id']==id ) callback(this.glosowania[i]);
    }
  },
  dodaj_wypowiedzi: function(){
	  var args = $A(arguments);
	  var id = args[0];
	  var typ = args[1];
	  var animation = args[2]!==false;
    var guess_order = args[3]!==false;

	  this.get_wypowiedzi_data(id, function(animation, data){
      var li = new Element('li', {className: 'punkt', element_typ: 'wypowiedzi', element_id: data.id, debata_typ: typ, data: data.data}).insert('<h4>'+this._czytania_typy[typ]+'</h4><p class="data">'+data.data+'</p><p class="tytul">Ilość wypowiedzi: <b>'+data.ilosc_wypowiedzi+'</b>, '+data.sejm_id+'</p>');
      li.down('h4').observe('click', this.zmien_typ_punktu.bind(this));
      this.dodaj_element(li, animation, guess_order);
      $('wypowiedzi_ul').select('li[element_id='+id+']').invoke('addClassName', 'x');
      
      $('side_div').select('.link[element_typ=czytanie][data='+data.data+']').invoke('addClassName', 'x');
      
    }.bind(this, animation));
  },
  dodaj_glosowania: function(){
	  var args = $A(arguments);
	  var id = args[0];
	  var typ = args[1];
	  var animation = args[2]!==false;
    var guess_order = args[3]!==false;

	  this.get_glosowania_data(id, function(animation, data){
      var li = new Element('li', {className: 'punkt', element_typ: 'glosowania', element_id: data.id, debata_typ: typ, data: data.data}).insert('<h4>'+this._czytania_typy[typ]+'</h4><p class="data">'+data.data+'</p><p class="tytul">Ilość głosowań: <b>'+data.ilosc_glosowan+'</b>, '+data.sejm_id+'</p>');
      if(data.pp=='1') li.addClassName('pp');
      li.down('h4').observe('click', this.zmien_typ_punktu.bind(this));
      this.dodaj_element(li, animation, guess_order);
      $('glosowania_ul').select('li[element_id='+id+']').invoke('addClassName', 'x');
      
      $('side_div').select('.link[element_typ=glosowanie][data='+data.data+']').invoke('addClassName', 'x');
      
    }.bind(this, animation));
  },
  dodaj_aklamacje: function(){
	  var args = $A(arguments);
    var data = args[0];
    if( !data.id ) data.id='';
    var animation = args[1]!==false;
    var guess_order = args[2]!==false;
        
    var li = new Element('li', {className: 'aklamacja', element_typ: 'aklamacja', element_id: data.id, data: data.data}).insert('<h4>Aklamacja</h4><p class="data">'+data.data+'</p>');
    this.dodaj_element(li, animation, guess_order);
    
    $('side_div').select('.link[element_typ=aklamacja][data='+data.data+']').invoke('addClassName', 'x');      
  },
  dodaj_skierowanie: function(){
    var args = $A(arguments);
    var data = args[0];
    if( !data.id ) data.id='';
    var animation = args[1]!==false;
    var guess_order = args[2]!==false;
    
    var parts = data.adresat.split(',');
    for( var i=0; i<parts.length; i++ ) { parts[i] = _autor_label(parts[i]); }
    
    
    var li = new Element('li', {className: 'skierowanie', element_typ: 'skierowanie', element_id: data.id, data: data.data, adresat: data.adresat, data_zalecenie: data.data_zalecenie}).insert('<h4>Skierowanie</h4><p class="data">'+data.data+'</p><p class="tytul skierowanie">'+parts.join(', ')+'</p>');
    if( data.data_zalecenie && data.data_zalecenie!='0000-00-00' ) li.insert('<p class="zalecenie">Zalecenie: '+data.data_zalecenie+'</p>');
    this.dodaj_element(li, animation, guess_order);
    
    $('side_div').select('.link[element_typ=skierowanie][data='+data.data+'][adresat='+data.adresat+']').invoke('addClassName', 'x');
  },
  dodaj_wypowiedzi_bw: function(){
    var args = $A(arguments);
    var data = args[0];
    if( !data.id ) data.id='';
    var animation = args[1]!==false;
    var guess_order = args[2]!==false;
     
    var li = new Element('li', {className: 'punkt', element_typ: 'wypowiedzi_bw', element_id: data.id, data: data.data}).insert('<h4>Rozpatrywanie bez wypowiedzi</h4><p class="data">'+data.data+'</p>');
    this.dodaj_element(li, animation, guess_order);
    
    $('side_div').select('.link[element_typ=czytanie][czytanie_typ=1][data='+data.data+']').invoke('addClassName', 'x');
  },
  dodaj_czytanie_komisje: function(){
    var args = $A(arguments);
    var data = args[0];
    if( !data.id ) data.id='';
    var animation = args[1]!==false;
    var guess_order = args[2]!==false;
    
    var parts = data.komisje.split(',');
    for( var i=0; i<parts.length; i++ ) { parts[i] = _autor_label(parts[i]); }
    
    var li = new Element('li', {className: 'czytanie_komisje', element_typ: 'czytanie_komisje', element_id: data.id, data: data.data, komisje: data.komisje}).insert('<h4>Czytanie w komisjach</h4><p class="data">'+data.data+'</p><p class="tytul skierowanie">'+parts.join(', ')+'</p>');
    this.dodaj_element(li, animation, guess_order);
    $('side_div').select('.link[element_typ=czytanie_komisje][data='+data.data+'][komisje='+data.komisje+']').invoke('addClassName', 'x');
      
  },  
  dodaj_wszystko: function(){
    $('side_div').select('.link').each( function(link){
      this.elementClick('side_div', link, false, true);
    }.bind(this) );
  },
  zmien_typ_punktu: function(event){
    var label = event.findElement('h4');
    var li = label.up(li);
    
    var index = this._czytania_typy_ids.indexOf( li.readAttribute('debata_typ') );    
    var typ = (index==-1 || index==this._czytania_typy_ids.length-1) ? this._czytania_typy_ids[0] : this._czytania_typy_ids[index+1];
    
    
    li.writeAttribute({debata_typ: typ});
    label.update( this._czytania_typy[typ] );    
  },
  nochanges: function(){
    if( mBrowser.enabled ) {
      if( !this.validate() ) return false;
      $S('projekty/zapisz_etapy_nochanges', this.id, this.onSave.bind(this));
    }
  },
  validate: function(){
	  if( $('etapy').select('li[debata_typ=undefined]').length ) {
	    alert('Niektóre punkty nie mają określonych typów');
	    return false;
	  }
	  if( $('side_div').select('.invalidlink').length ) {
	    alert('Nieprawidłowe linki w sejmowym html!');
	    return false;
	  }
	  
	  
		var error = false;
		$('side_div').select('.link').each(function(li){
		  if( !li.hasClassName('x') ) {
		    error = true;
		    return false;
		  }
		});
		if(error) {
		  if( !confirm("Nie wszystkie sejmowe linki zostały dodane.\nCzy chcesz kontynuować?") ) return false;
		}
	  
	  var error = false;
		['druki', 'wypowiedzi', 'glosowania'].each(function(list){
			$(list+'_ul').childElements().each(function(li){
			  if( !li.hasClassName('x') ) {
			    error = true;
			    return false;
			  }
			});
			if(error) return false;
		});
		if(error) {
		  alert('Nie wszystkie elementy zostały dodane');
		  return false;
		}
		return true;
  },
  save: function(){
    if( mBrowser.enabled ) {
      
      if( !this.validate() ) return false;
      
      var etapy = $('etapy').select('li'); 
      for( var i=0; i<etapy.length; i++ ) {
        var li = etapy[i];
        var typ = li.readAttribute('element_typ');
        
        var etap = {typ: typ};
        switch( typ ) {
          case 'druk': {
            etap['id'] = li.readAttribute('element_id');
            break;
          }
          case 'wyrok': {
            etap['id'] = li.readAttribute('element_id');
            break;
          }
          case 'skierowanie': {
            var id = li.readAttribute('element_id');
            if( id ) {
              etap['id'] = id;
            } else {
              etap['data'] = li.readAttribute('data');
              etap['adresat'] = li.readAttribute('adresat');
              if( li.readAttribute('data_zalecenie') ) { etap['data_zalecenie'] = li.readAttribute('data_zalecenie'); }
            }
            break;
          }
          case 'wypowiedzi_bw': {
            var id = li.readAttribute('element_id');
            if( id ) {
              etap['id'] = id;
            } else {
              etap['data'] = li.readAttribute('data');
            }
            break;
          }
          case 'aklamacja': {
            var id = li.readAttribute('element_id');
            if( id ) {
              etap['id'] = id;
            } else {
              etap['data'] = li.readAttribute('data');
            }
            break;
          }
          case 'czytanie_komisje': {
            var id = li.readAttribute('element_id');
            if( id ) {
              etap['id'] = id;
            } else {
              etap['data'] = li.readAttribute('data');
              etap['komisje'] = li.readAttribute('komisje');
            }
            break;
          }
          case 'wypowiedzi': {
            etap['id'] = li.readAttribute('element_id');
            etap['debata_typ'] = li.readAttribute('debata_typ');
            break;
          }
          case 'glosowania': {
            etap['id'] = li.readAttribute('element_id');
            etap['debata_typ'] = li.readAttribute('debata_typ');
            break;
          }
        }
        
        etapy[i] = etap;
      }
           
      var params = {id: this.id, typ: this.data.typ_id, data: this.html_data, etapy: etapy};
      
	    mBrowser.disable_loading();
	    // this.btnSave.disable();
	    	    
	    $S('projekty/pobierz_dane_do_zapisu', params, function(data){
	      var height;
	      
	      if( data['error']=='1' ) data['status_slowny'] = 'Nie zdefiniowano reguł dla tego modelu!';
	      if( data['error']=='2' ) data['status_slowny'] = 'Nie znalazłem pasującej sekwencji!';
	      
	      var innerHTML = '<p class="status_slowny">'+data['status_slowny']+'</p>';
	      
	      if( data['error'] ) {
	        height = 130;
	        innerHTML += '<p class="sequence">'+data['sequence']+'</p>';
	      } else {
	        height = 262;
	        if( !data['data_przyjecia'] ) data['data_przyjecia'] = '&nbsp;';
	        innerHTML += '<div class="data"><div class="field"><p class="label">Status<p><p class="value">'+this._statusy[data['status']]+'</p></div><div class="field"><p class="label">Data ostatniego procedowania<p><p class="value">'+data['data_ostatniego_procedowania']+'</p></div><div class="field"><p class="label">Data przyjęcia w Sejmie<p><p class="value">'+data['data_przyjecia']+'</p></div></div><div class="buttons"><a href="#" onclick="return false;">Zapisz</a></div>';
	      }
	      
	    	this.lbdiv = new Element('div', {className: 'lbdane'}).update(innerHTML);
	    	try{ this.lbdiva = this.lbdiv.down('a').observe('click', this._save.bind(this, params)); }catch(e){}
	      this.lb = $M.addLightboxShow( this.lbdiv, {title: 'Zapisywanie', width: 600, height: height, afterClose: function(){
	        mBrowser.enable_loading();
		      this.btnSave.enable();
	      }.bind(this)} );
	    }.bind(this), function(){
	      mBrowser.disable_loading();
	      this.btnSave.enable();
	    }.bind(this));
	    
    }
  },
  _save: function(params){
    this.lbdiva.remove();
    $S('projekty/zapisz_etapy', params, this.onSave.bind(this));
  },
  onSave: function(data){
    if( data.status=='4' ) {
      try{
	      var model = data.model;
	      for( var i=0; i<model.length; i++ ) {
	        $('etapy').childElements()[i].writeAttribute({element_id: model[i]['etap_id']});
	      }
	      $('status_slowny').update(data.status_slowny);
      }catch(e){}
      
      mBrowser.enable_loading();
      $M.lightboxClose();
      $LICZNIKI.update();
      
      if( mBrowser.category.id=='doakceptu' ) {
        mBrowser.markAsDeleted(this.id);
        mBrowser.loadNextItem();
      }   
         
    } else alert('Projekt nie został zapisany');
    mBrowser.enable_loading();
    this.btnSave.enable();
  },
  regrab: function(){
    mBrowser.disable_loading();
    $S('graber/projekty/pobierz', this.id, function(){location.reload();});
  }
});

var projekt;

var MBrowser = Class.create(MBrowser, {
  getListItemInnerHTML: function(data){
    return data['numer'] ? data['numer'] : '<i>Bez tytułu</i>';
  },
  afterCloseItem: function(){ $('side_div').update(''); }
});
var mBrowser;

$M.addInitCallback(function(){
  Event.observe(document, 'keypress', function(event){
	  if( event.ctrlKey && event.charCode==115 ) { projekt.save(); }
	  else if( event.ctrlKey && event.charCode==97 ) { projekt.dodaj_wszystko(); }
	  else if( event.ctrlKey && event.charCode==49 ) {
	    $('etapy').setStyle({height: 'inherit'});
	    $('projekt').setStyle({height: 'inherit'});
	  }
	  else if( event.ctrlKey && event.charCode==50 ) { $M.heightController.resizeArea('projekt'); }
	});
});

function _projekt_typ_label(id){
  for( var i=0; i<_projekty_typy_options.length; i++ ){
    if( _projekty_typy_options[i][0]==id ) return _projekty_typy_options[i][1];
  }
}

function _autor_label(id){
  for( var i=0; i<_autorzy_options.length; i++ ){
    if( _autorzy_options[i][0]==id ) return _autorzy_options[i][1];
  }
}

function compare_dates(a, b){
  a = string_to_date(a);
  b = string_to_date(b);
  return a>=b;
}

function string_to_date(s){
  var t = s.split('-');
  return new Date(t[0], t[1]-1, t[2]);
}

function _autorzy_to_id(_adresaci){
  var match = _adresaci.match(/(.*?), z zaleceniem zasięgnięcia opinii (.*?)/i);
  if( match ) _adresaci = match[1];
  
  _adresaci = _adresaci.replace(' , ', ' oraz ').replace(', Komisji', ' oraz Komisji').replace(' oraz projektów ustaw z nimi związanych', '').split(' oraz ');
  
  for( var j=0; j<_adresaci.length; j++ ) {
	  var found = false;
	  for( var i=0; i<_autorzy_dopelniacze.length; i++ ){
	    if( _autorzy_dopelniacze[i][1]==_adresaci[j].strip() ) {
	      found = true;
	      _adresaci[j]=_autorzy_dopelniacze[i][0];
	      break;
	    }
	  }
	  if( !found ) {
		  alert(_adresaci[j]);
		  return '';
	  }
  } 
  return _adresaci.sort().join(',');
}

function _wyrok_label(typ){
  switch( typ ) {
    case '1': return 'Zakwestionowe przepisy są zgodne z Konstytucją';
    case '2': return 'Zakwestionowe przepisy są <b>niezgodne</b> z Konstytucją i nie są nierozerwalnie związane z całością projektu.';
    case '3': return 'Zakwestionowe przepisy są <b>niezgodne</b> z Konstytucją i są nierozerwalnie związane z całością projektu.';
  }
}     