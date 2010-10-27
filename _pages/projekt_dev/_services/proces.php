<?
  $_etapy_typy = array('druk', 'czytanie_komisje', 'wypowiedzi', 'glosowania', 'skierowanie', 'wyrok', 'aklamacja', 'wypowiedzi_bw', 'wycofanie', 'anulowanie');
  $_typy_schematy = array(
    '1' => '1',
    '2' => '2',
    '3' => '3',
    '4' => '1',
    '5' => '3',
    '7' => '2',
    '8' => '3',
    '10' => '3',
    '11' => '3',
    '12' => '2',
  );
  $_czytania_typy = array(
    '1' => array(
      '1' => 'Pierwsze czytanie',
      '2' => 'Drugie czytanie',
      '3' => 'Trzecie czytanie',
      '4' => 'Rozpatrywanie stanowiska Senatu',
      '5' => 'Rozpatrywanie niezgodności z Konstytucją',
      '6' => 'Rozpatrywanie wniosku Prezydenta'
    ),
    '2' => array(
      '1' => 'Pierwsze czytanie',
      '2' => 'Drugie czytanie',
      '3' => 'Trzecie czytanie',
    ),
    '3' => array(
      '0' => 'Rozpatrywanie',
      '1' => 'Przyjęcie bez zastrzeżeń',
      '3' => 'Wysłuchanie',
    ),
  );
  
  $projekt_id = $_PARAMS;
  
  $projekt_data = $this->DB->selectAssoc("SELECT projekty.data_wplynal, projekty.typ_id, projekty.status, projekty.substatus, projekty.data_podpisania, projekty.data_ostatniego_procedowania, isap.data_wejscia_w_zycie, isap.data_ogloszenia, isap.dokument_ogloszony as dokument_id, dokumenty.scribd_doc_id, dokumenty.scribd_access_key, dokumenty.ilosc_stron FROM projekty LEFT JOIN isap ON projekty.isap_id=isap.id LEFT JOIN dokumenty ON isap.dokument_ogloszony=dokumenty.id WHERE projekty.id='$projekt_id'");
  $typ_id = $projekt_data['typ_id'];
  $data_podpisania = $projekt_data['data_podpisania'];
  $data_ostatniego_procedowania = $projekt_data['data_ostatniego_procedowania'];
  $status = $projekt_data['status'];
  $substatus = $projekt_data['substatus'];
  $data_wplynal = $projekt_data['data_wplynal'];
  
  $proces = $this->DB->selectAssocs("SELECT typ_id, subtyp, etap_id FROM projekty_etapy WHERE projekt_id='$projekt_id' ORDER BY ord ASC");
  $ostatni_etap = &$proces[ count($proces)-1 ];
  $_proces = array();
  
  $schemat = $_typy_schematy[$typ_id];
  $czytania_typy = $_czytania_typy[ $schemat ]; 
    
  switch( $schemat ) {
    case '1': { // schemat
      
      switch( $status ) {
        case '4': { // status
          
          switch( $substatus ) {
            case '1': { // substatus
              $proces[] = array('typ_id'=>'4', 'data'=>'2012-01-02', 'data_zalecenie'=>'0000-00-00', 'adresaci'=>array(array('Prezydent', 'Prezydenta')));
              break;
            }
          }
          break;
        
        }
        case '6': { // status
          
          switch( $substatus ) {
            case '1': {
              $ostatni_etap['info'] = 'odrzucono projekt';
              break;
            }
            case '2': {
              $ostatni_etap['info'] = 'odrzucono projekt';
              break;
            }
            case '3': {
              $ostatni_etap['info'] = 'odrzucono projekt';
              break;
            }
            case '9': {
              $ostatni_etap['info'] = 'odrzucono projekt';
              break;
            }
            case '10': {
              $proces[] = array('typ_id'=>'8', 'data'=>$data_ostatniego_procedowania);
              break;
            }
            case '11': {
              $ostatni_etap['info'] = 'nie uchwalono ponownie po wecie Prezydenta';
              break;
            }
            case '13': {
              $proces[] = array('typ_id'=>'text', 'text'=>'Prezydent zgłosił weto, ale Marszałek Sejmu zdecydował o jego nierozpatrywaniu. Projekt został odrzucony.');
              break;
            }
          }
          break;
          
        }
      }
      
      break;
    }
    
    case '2': { // schemat
      
      switch( $status ){ 
        
        case '1': { // status
          
          switch( $substatus ) {
          
            case '1': { // substatus
              $ostatni_etap['info'] = 'przyjęto';
              break;
            }
          
          }
          
          break;
        }
        
        case '3': { // status
          
          switch( $substatus ) {
          
            case '1': { // substatus
              $proces[] = array('typ_id'=>'9', 'data'=>$data_ostatniego_procedowania);
              break;
            }
            case '2': { // substatus
              $proces[] = array('typ_id'=>'8', 'data'=>$data_ostatniego_procedowania);
              break;
            }
            case '3': { // substatus
              $ostatni_etap['info'] = 'odrzucono projekt';
              break;
            }
            case '4': { // substatus
              $ostatni_etap['info'] = 'odrzucono projekt';
              break;
            }
            case '5': { // substatus
              $ostatni_etap['info'] = 'odrzucono projekt';
              break;
            }
          
          }
          
          break;
        }
        
      }
      break;
      
    }
    
    case '3': { // schemat
      
      switch( $status ) {
      
        case '1': { // status
          
          switch( $substatus ) {
            case '3': { // substatus
              $ostatni_etap['info'] = 'przyjęto';
              break;
            }
            case '4': { // substatus
              $proces[] = array('typ_id'=>'text', 'text'=>'Projekt został wykorzystany w pracach nad ustawą budżetową.');
              break;
            }
            case '5': { // substatus
              $proces[] = array('typ_id'=>'text', 'text'=>'Projekt został wykorzystany w pracach nad sprawozdaniem z wykonania budżetu.');
              break;
            }
            case '6': { // substatus
              $proces[] = array('typ_id'=>'text', 'text'=>'Zakończono rozpatrywanie.');
              break;
            }
          }
          
          break;
        }
        
        case '3': { // status
          
          switch( $substatus ) {
            case '1': { // substatus
              $proces[] = array('typ_id'=>'9', 'data'=>$data_ostatniego_procedowania);
              break;
            }
            case '2': { // substatus
              $proces[] = array('typ_id'=>'8', 'data'=>$data_ostatniego_procedowania);
              break;
            }
            case '3': { // substatus
              $ostatni_etap['info'] = 'odrzucono projekt';
              break;
            }
          }
          
          break;
        }
      
      }
      break;
    
    }
    
  }
  
  
  
  if( $data_wplynal!='0000-00-00' ) {
    $_proces[] = array(
      'data' => $data_wplynal,
      'typ' => 'wplynal',
      'nowa_data' => true,
    );
    $last_date = $data_wplynal;
  }
  
  if( $proces[0]['typ_id']==0 ) array_shift($proces);
    
  foreach( $proces as $etap ) {
    
    $etap['typ'] = $_etapy_typy[ $etap['typ_id'] ];
    $etap_id = $etap['etap_id'];
    $data = array();
    
    switch( $etap['typ_id'] ) {
      case 0: {
        $data = $this->DB->selectAssoc("SELECT druki.data, druki.typ_id as 'etap_typ_id', druki_typy.label, druki.dokument_id, druki.autorA_id, druki.autorB_id, druki.autorC_id, druki.numer as 'title', dokumenty.scribd_doc_id, dokumenty.scribd_access_key, dokumenty.ilosc_stron FROM druki LEFT JOIN druki_typy ON druki.typ_id=druki_typy.id LEFT JOIN dokumenty ON druki.dokument_id=dokumenty.id WHERE druki.id='$etap_id'");
        $data['title'] = 'Druk nr '.$data['title'];
        $data['autorzy'] = array();
        foreach( array('A','B','C') as $l ) {
          $autor_id = $data['autor'.$l.'_id'];
          if( $autor_id ) {
            $data['autorzy'][] = array( $autor_id, $this->DB->selectValue("SELECT autor FROM druki_autorzy WHERE id='$autor_id'") );
          }
        }
        break;
      }
      case 1: {
        $data = $this->DB->selectAssoc("SELECT data FROM czytania_komisje WHERE id='$etap_id'");
        break;
      }
      case 2: {
        $data = $this->DB->selectAssoc("SELECT posiedzenia_dni.data, punkty_wypowiedzi.ilosc_wypowiedzi FROM punkty_wypowiedzi LEFT JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE punkty_wypowiedzi.id='$etap_id'");
        $data['tytul'] = $czytania_typy[ $etap['subtyp'] ];
        break;
      }
      case 3: {
        $data = $this->DB->selectAssoc("SELECT posiedzenia_dni.data, punkty_glosowania.ilosc_glosowan FROM punkty_glosowania LEFT JOIN posiedzenia_dni ON punkty_glosowania.dzien_id=posiedzenia_dni.id WHERE punkty_glosowania.id='$etap_id'");
        $data['tytul'] = $czytania_typy[ $etap['subtyp'] ];
        break;
      }
      case 4: {
        if($etap_id){
	        $data = $this->DB->selectAssoc("SELECT data, data_zalecenie FROM skierowania WHERE id='$etap_id'");
	        $data['adresaci'] = $this->DB->selectRows("SELECT skierowania_adresaci.autor_id, druki_autorzy.dopelniacz FROM skierowania_adresaci LEFT JOIN druki_autorzy ON skierowania_adresaci.autor_id=druki_autorzy.id WHERE skierowania_adresaci.skierowanie_id='$etap_id' ORDER BY druki_autorzy.autor ASC");
	        if( count($data['adresaci'])==1 && $data['adresaci'][0][0]=='Poslowie' ) $data['FORUM_SEJMU'] = true;
        }
        break;
      }
      case 5: {        
        $data = $this->DB->selectAssoc("SELECT wyroki_tk.data, wyroki_typy.label, wyroki_tk.dokument_ogloszony as dokument_id, dokumenty.scribd_doc_id, dokumenty.scribd_access_key, dokumenty.ilosc_stron FROM wyroki_tk LEFT JOIN dokumenty ON wyroki_tk.dokument_ogloszony=dokumenty.id LEFT JOIN wyroki_typy ON wyroki_tk.wynik=wyroki_typy.id WHERE wyroki_tk.id='$etap_id'");
        break;
      }
      case 6: {
        $data = $this->DB->selectAssoc("SELECT data FROM aklamacje WHERE id='$etap_id'");
        break;
      }
      case 7: {
        $data = $this->DB->selectAssoc("SELECT data FROM punkty_wypowiedzi_bz WHERE id='$etap_id'");
        break;
      }
      default: {
        $data['data'] = $etap['data'];
      }
    }
    
    if( $data ) {
      $etap = array_merge($etap, $data);
      if( $etap['data']!=$last_date ) $etap['nowa_data'] = true;
      $last_date = $etap['data'];
    }
    
    $_proces[] = $etap;
    
  }
  
  
  
      
  if( $data_podpisania!='' && $data_podpisania!='0000-00-00' ) $_proces[] = array(
    'data' => $data_podpisania,
    'typ' => 'podpisanie',
    'nowa_data' => true,
    'dokument_id' => $projekt_data['dokument_id'],
    'data_obowiazywania' => $projekt_data['data_obowiazywania'],
    'data_ogloszenia' => $projekt_data['data_ogloszenia'],
    'data_wejscia_w_zycie' => $projekt_data['data_wejscia_w_zycie'],
    'data_wydania' => $projekt_data['data_wydania'],
    'scribd_doc_id' => $projekt_data['scribd_doc_id'],
    'scribd_access_key' => $projekt_data['scribd_access_key'],
    'ilosc_stron' => $projekt_data['ilosc_stron'],
  );
  
  return $_proces;
?>