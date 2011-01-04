<?
  if( is_string($_PARAMS) && strlen($_PARAMS)==5 ) {
    $_MODE = 'posiedzenie';
    $id = $_PARAMS;
  } else return false;
  
  $data = $this->DB->selectAssocs("SELECT punkty_wypowiedzi.id as 'debata_id', projekty_etapy.projekt_id FROM projekty_etapy JOIN punkty_wypowiedzi ON projekty_etapy.etap_id=punkty_wypowiedzi.id JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE projekty_etapy.typ_id=2 AND posiedzenia_dni.posiedzenie_id='$id' ORDER BY posiedzenia_dni.data DESC, punkty_wypowiedzi.ord DESC");
  
  

  
  
  $projekty = array();
  $_projekty = array();
  $_debaty = array();
  
  
  if( !function_exists('znajdz_projekt_przez_projekt') ) {
	  function znajdz_projekt_przez_projekt($id, &$projekty){
	    if( is_array($projekty) ) {
	      reset( $projekty );
	      foreach( $projekty as $klucz => $p ) if( $p['projekt_id']==$id ) return $klucz;
	    }
	    return false;
	  }
  }
  
  if( !function_exists('znajdz_projekt_przez_debaty') ) {
	  function znajdz_projekt_przez_debaty( $debaty, &$data ){
	    if( is_array($data) ) {
		    reset( $data );
		    foreach( $data as $klucz => $d ) {
		      $data_debaty = $d['debaty'];
		      reset( $debaty );
		      foreach( $debaty as $debata ) if( in_array($debata, $data_debaty) ) return $klucz;
		    }
	    }
	    return false;
	  }
  }
  
  
  
  foreach( $data as $d ) {
    if( !in_array($d['projekt_id'], $_projekty) ) $_projekty[] = $d['projekt_id'];
    if( !in_array($d['debata_id'], $_debaty) ) $_debaty[] = $d['debata_id'];
  
    $klucz = znajdz_projekt_przez_projekt( $d['projekt_id'], $projekty );
        
    if( $klucz!==false ) {
      $projekty[$klucz]['debaty'][] = $d['debata_id'];
    } else {
      $projekty[] = array(
        'projekt_id' => $d['projekt_id'],
        'debaty' => array( $d['debata_id'] ),
      );
    }
  }
  
  
  
  
  $mapa = array();
  foreach( $projekty as $projekt ) {
    $projekt_id = $projekt['projekt_id'];
    $debaty = $projekt['debaty'];
    
    $klucz = znajdz_projekt_przez_debaty( $debaty, $mapa );
    if( $klucz!==false ) {
    
      if( !in_array($projekt_id, $mapa[$klucz]['projekty']) ) $mapa[$klucz]['projekty'][] = $projekt_id;
      $mapa[$klucz]['debaty'] = array_values( array_unique( array_merge( $mapa[$klucz]['debaty'], $debaty ) ) );
      
      
    } else {
      $mapa[] = array(
        'projekty' => array($projekt_id),
        'debaty' => $debaty,
      );
    }
  }
  
  
  return array(
    'mapa' => $mapa,
    'projekty' => $_projekty,
    'debaty' => $_debaty,
  );
?>