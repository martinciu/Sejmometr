<?
  $id = $_PARAMS;
  $this->S('projekty/dodatkowe_dokumenty/policz', $id);
  $this->DB->q("UPDATE projekty SET _temp='1' WHERE id='$id'");
  
  /*
  $punkt_id = $_PARAMS;
  $this->DB->update_assoc('punkty_wypowiedzi', array(
    'przeliczono' => '1',
  ), $punkt_id);
  
  
  
  $count = $this->DB->selectCount("SELECT COUNT(*) FROM wypowiedzi WHERE punkt_id='$punkt_id'");
  
  
  $this->DB->update_assoc('punkty_wypowiedzi', array(
    'ilosc_wypowiedzi' => $count,
    'przeliczono' => '2',
  ), $punkt_id);

  die();

  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
    
  // $this->S('druki/przypisz_zalacznik', $id);
  
  $this->S('graber/projekty/pobierz', $id);
  */
?>