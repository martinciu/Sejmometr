<?
  $projekt_id = $_PARAMS['id'];
  if( strlen($projekt_id)!=5 ) return false;
  $stanowisko_senatu = (string) $_PARAMS['data']['stanowisko_senatu'];
  $html_status = (int) $_PARAMS['data']['status'];
  $poprawki_senatu = (int) $_PARAMS['data']['poprawki_senatu'];
  $data_podpisania = $_PARAMS['data']['data_podpisania'];
  $data_wplynal = $_PARAMS['data']['data_wplynal'];
  $data_wycofania = $_PARAMS['data']['data_wycofania'];
  $isap_id = $_PARAMS['data']['isap_id'];
  $etapy = $_PARAMS['etapy'];
  $_typ = $_PARAMS['typ'];
  $_chains = array('druk', 'czytanie_komisje', 'wypowiedzi', 'glosowania', 'aklamacja', 'dodaj_wypowiedzi_bw');
  
  $_typy_schematy = array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'1', '5'=>'3', '7'=>'2', '8'=>'3', '10'=>'3', '11'=>'3', '12'=>'2');
  $_schemat = $_typy_schematy[$_typ];
  
  $model = array();
  $sequence = array();
  
  $ostatnie_glosowanie_trzecie_czytanie = false;
  $ostatnie_wypowiedzi_iterator = false;
  $ostatnie_glosowania_iterator = false;
  $iterator = 0;
  
  $etapy_count = count($etapy);
  for( $i=0; $i<$etapy_count; $i++ ) {
    $etap = $etapy[$i];
    $typ = $etap['typ'];
    $item_id = $etap['id'];
    $data = '';
    $subtyp = '0';
    $chain = '';
    
    if( $typ=='skierowanie' && $item_id ) {
      $etap = array_merge($etap, $this->DB->selectAssoc("SELECT data FROM skierowania WHERE id='".$item_id."'"));
      $etap['adresat'] = implode(',', $this->DB->selectValues("SELECT autor_id FROM skierowania_adresaci WHERE skierowanie_id='".$item_id."'"));
      $etapy[$i] = $etap;
    }
    
    if( $typ=='aklamacja' && $item_id ) {
      $etap = array_merge($etap, $this->DB->selectAssoc("SELECT data FROM aklamacje WHERE id='".$item_id."'"));
      $etapy[$i] = $etap;
    }
    
    if( $typ=='wypowiedzi_bw' && $item_id ) {
      $etap = array_merge($etap, $this->DB->selectAssoc("SELECT data FROM punkty_wypowiedzi_bz WHERE id='".$item_id."'"));
      $etapy[$i] = $etap;
    }
    
    if( $typ=='czytanie_komisje' && $item_id ) {
      $etap = array_merge($etap, $this->DB->selectAssoc("SELECT data FROM czytania_komisje WHERE id='".$item_id."'"));
      $etap['komisje'] = implode(',', $this->DB->selectValues("SELECT komisja_id FROM czytania_komisje_komisje WHERE czytanie_id='".$item_id."'"));
      $etapy[$i] = $etap;
    }
    
    if( $typ=='wyrok' && $item_id ) {
      $etap = array_merge($etap, $this->DB->selectAssoc("SELECT data, wynik FROM wyroki_tk WHERE id='".$item_id."'"));
      $etapy[$i] = $etap;
    }
    
    if( in_array($typ, $_chains) ) {
      $item = array('typ'=>$typ);
      switch( $typ ) {
        case 'druk': {
          list($data, $subtyp) = $this->DB->selectRow("SELECT data, typ_id FROM druki WHERE id='$item_id'");
          $etap['data'] = $data;
          $etap['subtyp'] = $subtyp;
          $etapy[$i] = $etap;
          break;
        }
        case 'czytanie_komisje': {
          $data = $etap['data'];
          break;
        }
        case 'wypowiedzi': {
          $data = $this->DB->selectValue("SELECT posiedzenia_dni.data FROM punkty_wypowiedzi LEFT JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE punkty_wypowiedzi.id='$item_id'");
          $etap['data'] = $data;
          $etapy[$i] = $etap;
          $subtyp = $etap['debata_typ'];
          $ostatnie_wypowiedzi_iterator = $iterator;
          break;
        }
        case 'glosowania': {
          list($data, $pp, $data_start, $data_koniec) = $this->DB->selectRow("SELECT posiedzenia_dni.data, punkty_glosowania.pp, DATE(punkty_glosowania.czas_start), DATE(punkty_glosowania.czas_koniec) FROM punkty_glosowania LEFT JOIN posiedzenia_dni ON punkty_glosowania.dzien_id=posiedzenia_dni.id WHERE punkty_glosowania.id='$item_id'");
          $etap['data'] = $data;
          $etapy[$i] = $etap;
          if( $data_start!=$data_koniec ) return 'Czas_start i czas_koniec nie są takie same';
          if( $pp=='1' ) $data = $data_koniec;
          $subtyp = $etap['debata_typ'];
          if( $subtyp==3 ) $ostatnie_glosowanie_trzecie_czytanie = $iterator;
          if( $subtyp!=5 ) $ostatnie_glosowania_iterator = $iterator;
          break;
        }
        case 'aklamacja': {
          $ostatnie_glosowanie_trzecie_czytanie = $iterator;
          $ostatnie_glosowania_iterator = $iterator;
          break;
        }
      }
      $sequence[] = array_search($typ, $_chains).':'.$subtyp;
	    $item['data'] = $data;
      $item['subtyp'] = $subtyp;
      $model[] = $item;
      $iterator++;
    }    
  }
    
  $ostatni_etap = $etapy[$etapy_count-1];
  $modele_count = count($model);
  $last_item = $model[$modele_count-1]; 
  
  $result = array(
    'error' => '1'
  );
  
  $sequence = implode(',', $sequence);
  
  
  if( $_schemat=='1' ) {
      
	  if( $isap_id ) $isap = $this->DB->selectAssoc("SELECT data_obowiazywania, data_ogloszenia, data_uchylenia, data_wejscia_w_zycie, data_wydania FROM isap WHERE id='$isap_id'");
	  $data_przyjecia_iterator = $ostatnie_glosowania_iterator;
	  if( $poprawki_senatu==2 ) $data_przyjecia_iterator = $ostatnie_glosowanie_trzecie_czytanie;
	  $data_przyjecia = $model[$data_przyjecia_iterator]['data'];
	    
	  
	  if( $data_podpisania!='0000-00-00' ) {
	    
	    if( $isap['data_ogloszenia'] ) {
	      $substatus = 1;
	      $data_ostatniego_procedowania = $isap['data_ogloszenia'];
	    } else {
	      $substatus = 2;
	      $data_ostatniego_procedowania = $data_podpisania;
	    }
	    
	    $result = array(
		    'status' => 5,
		    'substatus' => $substatus,
		    'data_ostatniego_procedowania' => $data_ostatniego_procedowania,
		    'data_przyjecia' => $data_przyjecia,
		  );
		  
	  
	  } elseif( $data_wycofania!='0000-00-00' ) {
	    $result = array(
		    'status' => 6,
		    'substatus' => 10,
		    'data_ostatniego_procedowania' => $data_wycofania,
		  );
	  } elseif ($html_status==1) {
	    
	    $result = array(
		    'status' => 6,
		    'substatus' => $model[$ostatnie_glosowania_iterator]['subtyp'],
		    'data_ostatniego_procedowania' => $model[$ostatnie_glosowania_iterator]['data'],
		  );
		  
		} elseif ($html_status==2) {
	    
	    $result = array(
		    'status' => 6,
		    'substatus' => 9,
		    'data_ostatniego_procedowania' => $model[$ostatnie_glosowania_iterator]['data'],
		  );
		 
		} elseif ($html_status==3) {
	    
	    $result = array(
		    'status' => 6,
		    'substatus' => 11,
		    'data_ostatniego_procedowania' => $model[$ostatnie_glosowania_iterator]['data'],
		  );
		  
		} elseif ($html_status==4) {
	    
	    $result = array(
		    'status' => 6,
		    'substatus' => 12,
		    'data_ostatniego_procedowania' => $ostatni_etap['data'],
		  );
		  
		} elseif ($html_status==5) {
	    
	    $result = array(
		    'status' => 6,
		    'substatus' => 13,
		    'data_ostatniego_procedowania' => $ostatni_etap['data'],
		  );
		  
	  } else {
	    
	    if( $etapy_count==1 && $ostatni_etap['typ']=='druk' ) {
	    
	      $result = array('status' => 1, 'substatus' => 3, 'data_ostatniego_procedowania' => $ostatni_etap['data']);
	    
	    } elseif( $etapy_count==2 && $ostatni_etap['typ']=='skierowanie' ) {
	
	      $result = array('status' => 1, 'data_ostatniego_procedowania' => $ostatni_etap['data']);
		    if( $ostatni_etap['adresat']=='Poslowie' ) {
		      $result['substatus'] = 1;
		    } else {
	        $result['substatus'] = 2;
		    }
	    
	    } else {
	      
	      
	      $result = array('status' => 2, 'data_ostatniego_procedowania' => $ostatni_etap['data']);
		    if( $ostatni_etap['typ']=='skierowanie' ) {
		      
		      if( $ostatni_etap['adresat']=='TK' ) {
		        $result['status'] = 7;
		        $result['substatus'] = 4;
		        $result['data_przyjecia'] = $data_przyjecia;
		      } elseif($ostatni_etap['adresat']=='Prezydent') {
		        $result['status'] = 4;
		        $result['substatus'] = 2;
		        $result['data_przyjecia'] = $data_przyjecia;
		      } elseif( $ostatni_etap['adresat']=='Marszalek_Senatu,Prezydent' ) {
		        $result['status'] = 3;
		        $result['substatus'] = 1;
		        $result['data_przyjecia'] = $data_przyjecia;
		      } else {
		        $result['substatus'] = 1;
		      }
		    
		    } elseif( $ostatni_etap['typ']=='druk' && ($ostatni_etap['subtyp']=='1' || $ostatni_etap['subtyp']=='9') ) {
		      $result['substatus'] = 2;
		    } elseif( $ostatni_etap['typ']=='czytanie_komisje') {
		      $result['substatus'] = 3;
		    } elseif( $ostatni_etap['typ']=='druk' && $ostatni_etap['subtyp']=='16' ) {
		      $result['substatus'] = 5;
		    } elseif( $ostatni_etap['typ']=='wypowiedzi' && $ostatni_etap['debata_typ']=='2') {
		      $result['substatus'] = 6;
		    } elseif( $ostatni_etap['typ']=='wypowiedzi' && $ostatni_etap['debata_typ']=='1') {
		      $result['substatus'] = 9;
		    } elseif( $ostatni_etap['typ']=='glosowania' && $ostatni_etap['debata_typ']=='1') {
		      $result['substatus'] = 9;
		    } elseif( $ostatni_etap['typ']=='wyrok' || $ostatni_etap['wynik']=='2' ) {
		      $result['status'] = 4;
		      $result['substatus'] = 1;
		    } elseif( $ostatni_etap['typ']=='glosowania' && $ostatni_etap['debata_typ']=='4' ) {
		      $result['data_przyjecia'] = $model[$ostatnie_glosowanie_trzecie_czytanie]['data'];
		      $result['status'] = 2;
		      $result['substatus'] = 7;
		    } elseif( $ostatni_etap['typ']=='glosowania' && $ostatni_etap['debata_typ']=='3' ) {
		      $result['data_przyjecia'] = $model[$ostatnie_glosowanie_trzecie_czytanie]['data'];
		      $result['status'] = 2;
		      $result['substatus'] = 8;
		    } else { $result = array( 'error' => '1', 'ostatni_etap' => $ostatni_etap); }
		    
		    
	    }
	    
	    
	  }
	

	  $result['stanowisko_senatu'] = $stanowisko_senatu;
	  $result['data_podpisania'] = $data_podpisania;	
    $result['isap_id'] = $isap_id;

	} elseif($_schemat=='2') {
	  
	  if( $html_status==9 || ($isap_id && $html_status!=8) ) {
	    $isap = $this->DB->selectAssoc("SELECT data_obowiazywania, data_ogloszenia, data_uchylenia, data_wejscia_w_zycie, data_wydania FROM isap WHERE id='$isap_id'");
	    $result = array(
	      'status' => 1,
	      'substatus' => 1,
	      'data_przyjecia' => $ostatni_etap['data'],
	      'data_ostatniego_procedowania' => $ostatni_etap['data'],
	    );
	  } elseif( $html_status==8 ) {
	    $result = array(
	      'status' => 1,
	      'substatus' => 2,
	      'data_przyjecia' => $ostatni_etap['data'],
	      'data_ostatniego_procedowania' => $ostatni_etap['data'],
	    );
	  } elseif ($html_status==1) {
	    
	    $subtyp = (int) $model[$ostatnie_glosowania_iterator]['subtyp'];
	    $result = array(
		    'status' => 3,
		    'substatus' => $subtyp+2,
		    'data_ostatniego_procedowania' => $model[$ostatnie_glosowania_iterator]['data'],
		  );
		  
	  } elseif( $html_status==7 ) {
	    $result = array(
	      'status' => 3,
	      'substatus' => 1,
	      'data_ostatniego_procedowania' => $ostatni_etap['data'],
	    );
	  } elseif ( $ostatni_etap['typ']=='druk' && $ostatni_etap['subtyp']=='1' ) {
	    $result = array(
	      'status' => 2,
	      'substatus' => 2,
	      'data_ostatniego_procedowania' => $ostatni_etap['data'],
	    );
	  } elseif( $data_wycofania!='0000-00-00' ) {
	    $result = array(
	      'status' => 3,
	      'substatus' => 2,
	      'data_ostatniego_procedowania' => $data_wycofania,
	    );
	  } elseif( $etapy_count==2 && $ostatni_etap['typ']=='skierowanie' ) {
	    $result = array('status' => 2, 'data_ostatniego_procedowania' => $ostatni_etap['data']);
	    if( $ostatni_etap['adresat']=='Poslowie' ) {
	      $result['substatus'] = 3;
	    } else {
        $result['substatus'] = 4;
	    }
	  } elseif( $etapy_count==1 && $ostatni_etap['typ']=='druk' ) {
	    $result = array('status' => 2, 'substatus'=>5, 'data_ostatniego_procedowania' => $ostatni_etap['data']);
	  } else {
	  
	    $result = array('status' => 2, 'data_ostatniego_procedowania' => $ostatni_etap['data']);
	    if( $ostatni_etap['typ']=='czytanie_komisje') {
		    $result['substatus'] = 1;
		  }
	  }
	
	} elseif($_schemat=='3') {
	  
	  if( $data_wycofania!='0000-00-00' ) {
	    $result = array(
	      'status' => 3,
	      'substatus' => 2,
	      'data_ostatniego_procedowania' => $data_wycofania,
	    );
	  } elseif( $html_status==11 ) {
	    $result = array('status' => 3, 'substatus'=>'3', 'data_ostatniego_procedowania' => $ostatni_etap['data']);
	  } elseif( $html_status==12 ) {
	    $result = array('status' => 1, 'substatus'=>'4', 'data_przyjecia'=>$ostatni_etap['data'], 'data_ostatniego_procedowania' => $ostatni_etap['data']);
	  } elseif( $html_status==13 ) {
	    $result = array('status' => 1, 'substatus'=>'5', 'data_przyjecia'=>$ostatni_etap['data'], 'data_ostatniego_procedowania' => $ostatni_etap['data']);
	  } elseif( $html_status==14 ) {
	    $result = array('status' => 1, 'substatus'=>'6', 'data_przyjecia'=>$ostatni_etap['data'], 'data_ostatniego_procedowania' => $ostatni_etap['data']);
	  } elseif( $html_status==6 || $html_status==9 ) {
	    $result = array('status' => 1, 'data_przyjecia'=>$ostatni_etap['data'], 'data_ostatniego_procedowania' => $ostatni_etap['data']);
	   	
	    if( $ostatni_etap['typ']=='wypowiedzi_bw' || ($ostatni_etap['typ']=='wypowiedzi' && $ostatni_etap['debata_typ']=='1')) {
	      $result['substatus'] = 1;
	    } elseif( $ostatni_etap['typ']=='wypowiedzi' && $ostatni_etap['debata_typ']=='3') {
	      $result['substatus'] = 2;
	    } elseif( $ostatni_etap['typ']=='glosowania' && $ostatni_etap['debata_typ']=='0') {
	      $result['substatus'] = 3;
	    }
	    
	  } elseif( $html_status==10 ) {
	    $result = array('status' => 1, 'data_przyjecia'=>$ostatni_etap['data'], 'data_ostatniego_procedowania' => $ostatni_etap['data'], 'substatus'=>'2');
	  } elseif( $html_status==1 ) {
	    $result = array('status' => 3, 'data_ostatniego_procedowania' => $ostatni_etap['data'], 'substatus'=>'3');
	  
	  } elseif( $etapy_count==2 && $ostatni_etap['typ']=='skierowanie' ) {
	    $result = array('status' => 2, 'data_ostatniego_procedowania' => $ostatni_etap['data']);
	    if( $ostatni_etap['adresat']=='Poslowie' ) {
	      $result['substatus'] = 3;
	    } else {
        $result['substatus'] = 4;
	    }
	  } elseif( $etapy_count==1 && $ostatni_etap['typ']=='druk' ) {
	    $result = array('status' => 2, 'substatus'=>5, 'data_ostatniego_procedowania' => $ostatni_etap['data']);
	  } elseif ( $ostatni_etap['typ']=='druk' && $ostatni_etap['subtyp']=='1' ) {
	    $result = array(
	      'status' => 2,
	      'substatus' => 2,
	      'data_ostatniego_procedowania' => $ostatni_etap['data'],
	    );
	  }
	}
	
	$result['typ'] = $_typ;
  $result['status_slowny'] = $this->S('projekty/status_slowny', $result);
  $result['sequence'] = $sequence;
  $result['data_wplynal'] = $data_wplynal;
    
  return $result;
?>