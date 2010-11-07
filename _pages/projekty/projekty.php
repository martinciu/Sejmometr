<?
  $miesiac = $_GET['miesiac'];
  $miesiac_aktywny = strlen($miesiac)==7;
  
  $typ_ids = $this->DB->selectValues("SELECT id FROM projekty_typy WHERE menu_id='".$_GET['_TYPE']."'");
  $w = "projekty.akcept='1' AND projekty.status!=0 AND (projekty.typ_id=".implode(" OR projekty.typ_id=", $typ_ids).")";
  
  $_labels = array(
    'autor' => 'Autor',
    'status' => 'Status',
  );
  
  $_fields = array(
    'autor' => 'autor_id',
    'status' => 'status',
  );



  // FILTRY
  
  $filtry = $this->DB->selectAssocs("SELECT filtr as 'id' FROM projekty_filtry WHERE projekt_typ='".$_GET['_TYPE']."'");
  $_filtry = array();
  
  $wf = $w;
  foreach( $filtry as &$f ) {
    $f['tytul'] = $_labels[ $f['id'] ];
    $f['aktywny'] = isset($_GET[ $f['id'] ]);
    if( $f['aktywny'] ) {
      $f['wybrane'] = explode(',', $_GET[ $f['id'] ]);
      $wf = $wf." AND (projekty.".$_fields[ $f['id'] ]."='".implode("' OR projekty.".$_fields[ $f['id'] ]."='", $f['wybrane'])."')";
      if( $miesiac_aktywny ) $wf .= " AND SUBSTRING(projekty.data_wplynal, 1, 7)='$miesiac'";
    }
  }
  
  foreach( $filtry as &$f ) {
    $f['opcje'] = $this->S('projekty/filtry_opcje', array($f['id'], $typ_ids[0], $wf));
    
    if( $f['aktywny'] ) {
      $_filtry[ $f['id'] ] = $f['wybrane'];
	    foreach( $f['opcje'] as &$o ) $o['classes'][] = in_array($o['id'], $f['wybrane']) ? 'selected' : '_';
    } else {
      for( $i=10; $i<count($f['opcje']); $i++) $f['opcje'][$i]['classes'][] = '_';
    }
    
    foreach( $f['opcje'] as &$o ) {
      if( is_array($o['classes']) ) {
	      $o['_classes'] = implode(' ', $o['classes']);
	      if( in_array('_', $o['classes']) ) $o['_hide'] = true;
      }
    }
  }



  // SORTOWANIE
  
  $sortowanie_opcje = array(
    0 => 'Data ostatnich prac',
    1 => 'Data wpłynięcia do Sejmu',
  );
  $sortowanie_wybrane = (int) $_GET['sort'];
  switch($sortowanie_wybrane) {
    case 0: { $s = 'projekty.data_ostatniego_procedowania DESC, projekty.data_wplynal DESC'; break; }
    case 1: { $s = 'projekty.data_wplynal DESC'; break; }
  }
  


  // LISTA  
  list($projekty, $paginacja) = $this->S('projekty/lista', array(
    'typ_ids' => $typ_ids,
    'filtry' => $_filtry,
    'sort' => $s,
    'miesiac' => $miesiac,
    'q' => $_GET['q'],
  ));
  
  function sm_miesiac($m){
    $_miesiace = array('', 'Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień');
    list($y, $m) = explode('-', $m);
    $y = (int) $y;
    $m = (int) $m;
    return $_miesiace[$m].' '.$y;
  }
  $this->SMARTY->register_modifier('miesiac', 'sm_miesiac');
  
  $this->SMARTY->assign('projekty', $projekty);
  $this->SMARTY->assign('paginacja', $paginacja);
  $this->SMARTY->assign('filtry', $filtry);
  $this->SMARTY->assign('sortowanie_opcje', $sortowanie_opcje);
  $this->SMARTY->assign('sortowanie_wybrane', $sortowanie_wybrane);
  
  $this->SMARTY->assign('miesiac_aktywny', $miesiac_aktywny);
  $this->SMARTY->assign('miesiac', $miesiac);
?>