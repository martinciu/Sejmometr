<?
  $pp_dzien_id = $_PARAMS;
  if( !is_numeric($pp_dzien_id) ) return false;
  
  $this->DB->update_assoc('glosowania_pp_dni', array('status'=>'1'), $pp_dzien_id);
  
  list( $sejm_id, $posiedzenie_nr, $data ) = $this->DB->selectRow("SELECT sejm_id, posiedzenie_nr, data FROM glosowania_pp_dni WHERE id='$pp_dzien_id'");
  
  $dzien_id = $this->DB->selectValue("SELECT posiedzenia_dni.id FROM posiedzenia_dni LEFT JOIN posiedzenia ON posiedzenia_dni.posiedzenie_id=posiedzenia.id WHERE posiedzenia_dni.data='$data' AND posiedzenia.numer='$posiedzenie_nr'");
  
  require_once( ROOT.'/_lib/SejmParser.php' );
  $SP = new SejmParser();
  
  $data = $SP->glosowania_lista($sejm_id);
  if( is_array($data) ) foreach($data as $sejm_id) $this->S('graber/glosowania/dodaj', array($sejm_id, $dzien_id, '1'));
  
  $this->DB->update_assoc('glosowania_pp_dni', array('status'=>'2'), $pp_dzien_id);
?>