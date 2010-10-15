<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;

  $glowny_druk = $this->DB->selectValue("SELECT druk_id FROM projekty_druki WHERE projekt_id='$id' ORDER BY id ASC");
  $nowy_sejm_id = $this->DB->selectValue("SELECT sejm_id FROM projekty WHERE id='$id'");
  
  if( !empty($glowny_druk) ) {
    
    $projekty = $this->DB->selectAssocs("SELECT projekty.id, projekty.sejm_id, projekty.tytul, projekty.autor_id, projekty.druk_id FROM projekty_druki LEFT JOIN projekty ON projekty_druki.projekt_id=projekty.id WHERE projekty_druki.druk_id='$glowny_druk' AND projekty.id!='$id'");
    
    $_projekty = array();
    foreach( $projekty as $i=>$projekt ) {      
      if( $projekt['druk_id']==$glowny_druk ) $_projekty[] = $projekt;
    }
    $projekty = $_projekty;
    
    
    if( count($projekty)==1 ) {
      $projekt = $projekty[0];
      
      $this->DB->q("DELETE FROM projekty WHERE id='$id'");
      $this->DB->update_assoc('projekty', array('sejm_id'=>$nowy_sejm_id), $projekt['id']);
      $this->DB->q("DELETE FROM projekty_druki WHERE projekt_id='$id'");
      $this->DB->q("DELETE FROM projekty_bas WHERE projekt_id='$id'");
      $this->DB->q("DELETE FROM projekty_isap WHERE projekt_id='$id'");
      $this->DB->q("DELETE FROM projekty_punkty_glosowania WHERE projekt_id='$id'");
      $this->DB->q("DELETE FROM projekty_punkty_wypowiedzi WHERE projekt_id='$id'");
      
      $this->S('liczniki/nastaw/projekty-wlasciwosci');
		  $this->S('liczniki/nastaw/projekty-etapy');
    }
    
  }
?>