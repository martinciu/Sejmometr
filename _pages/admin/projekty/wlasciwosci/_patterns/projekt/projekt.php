<?
  $id = $_PARAMS['id'];
  
  $result = array();
  
  $glowny_druk = $this->DB->selectValue("SELECT druk_id FROM projekty_druki WHERE projekt_id='$id' ORDER BY id ASC");
  if( !empty($glowny_druk) ) {
    $projekty = $this->DB->selectAssocs("SELECT projekty.id, projekty.sejm_id, projekty.tytul, projekty.autor_id, projekty.druk_id FROM projekty_druki LEFT JOIN projekty ON projekty_druki.projekt_id=projekty.id WHERE projekty_druki.druk_id='$glowny_druk' AND projekty.id!='$id'");
    
    $_projekty = array();
    foreach( $projekty as $i=>$projekt ) {
      if( $projekt['druk_id']==$glowny_druk ) $_projekty[] = $projekt;
    }
    
    $result['projekty'] = $_projekty;
  }
  
  
  $projekt = $this->DB->selectAssoc("SELECT projekty.id, projekty.sejm_id, projekty.druk_id, druki.numer, projekty.autor_id, projekty.typ_id, projekty.tytul, projekty.opis FROM projekty LEFT JOIN druki ON projekty.druk_id=druki.id WHERE projekty.id='$id'");
  $projekt['html'] = @file_get_contents( ROOT.'/graber_cache/projekty/'.$id.'.html' );
  
  $druki_data = $this->DB->selectAssocs("SELECT druki.id, druki.numer, druki.data, druki.dokument_id, druki.tytul_oryginalny, druki_autorzy.autor, druki_typy.label as 'typ' FROM druki LEFT JOIN druki_autorzy ON druki.autorA_id=druki_autorzy.id LEFT JOIN druki_typy ON druki_typy.id=druki.typ_id WHERE druki.id='".$projekt['druk_id']."'");
   
  $result['projekt'] = $projekt;
  $result['druki_data'] = $druki_data;
  return $result;
?>