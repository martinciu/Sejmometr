<?
  $id = $_PARAMS['id'];
  $druk = $this->DB->selectAssoc("SELECT druki.id, druki.numer, druki.data, druki.typ_id, druki.autorA_id, druki.autorB_id, druki.autorC_id, druki.zalacznik, druki.tytul_oryginalny, dokumenty.akcept as dokument_akcept, dokumenty.scribd_doc_id, dokumenty.scribd_access_key, druki.dokument_id FROM druki LEFT JOIN dokumenty ON druki.dokument_id=dokumenty.id WHERE druki.id='$id'"); 
  
  $druk['autorzy'] = array();
  $_autorzy_names = array('A', 'B', 'C');
  foreach( $_autorzy_names as $_autor_name ) {
    $value = $druk['autor'.$_autor_name.'_id'];
    if($value) $druk['autorzy'][] = $value;
  }  
  
  $result = array();
  
  
  
  $result['projekty'] = $this->DB->selectAssocs("SELECT id, sejm_id, tytul, autor_id, druk_id, response_status FROM projekty WHERE druk_id='$id'"); 
  foreach( $result['projekty'] as &$_projekt ) $_projekt['etapy_count'] = $this->DB->selectCount("SELECT COUNT(*) FROM projekty_etapy WHERE projekt_id='".$_projekt['id']."'");
  
  
  
  
  if( $druk['zalacznik']=='1' ) {
  
    $druki_data = $this->DB->selectAssocs("SELECT druki.id, druki.numer, druki.data, druki.dokument_id, druki.tytul_oryginalny, druki_autorzy.autor, druki_typy.label as 'typ' FROM druki_zalaczniki LEFT JOIN druki ON druki_zalaczniki.druk=druki.id LEFT JOIN druki_autorzy ON druki.autorA_id=druki_autorzy.id LEFT JOIN druki_typy ON druki_typy.id=druki.typ_id WHERE druki_zalaczniki.zalacznik='$id'");
    $druk['druki_glowne'] = array();
    if( is_array($druki_data) ) foreach( $druki_data as $druk_glowny ) $druk['druki_glowne'][] = $druk_glowny['id'];
    $result['druki_data'] = $druki_data;
    
  } elseif( $druk['zalacznik']=='0' ) {
  
    $druki_data = $this->DB->selectAssocs("SELECT druki.id, druki.numer, druki.data, druki.dokument_id, druki.tytul_oryginalny, druki_autorzy.autor, druki_typy.label as 'typ' FROM druki_zalaczniki LEFT JOIN druki ON druki_zalaczniki.zalacznik=druki.id LEFT JOIN druki_autorzy ON druki.autorA_id=druki_autorzy.id LEFT JOIN druki_typy ON druki_typy.id=druki.typ_id WHERE druki_zalaczniki.druk='$id'");
    $druk['zalaczniki'] = array();
    if( is_array($druki_data) ) foreach( $druki_data as $druk_glowny ) $druk['zalaczniki'][] = $druk_glowny['id'];
    $result['druki_data'] = $druki_data;
    
    $projekty_data = $this->DB->selectAssocs("SELECT projekty.id, projekty.tytul, projekty.autor_id, druki_autorzy.autor, druki.numer FROM projekty_druki LEFT JOIN projekty ON projekty_druki.projekt_id=projekty.id LEFT JOIN druki ON projekty.druk_id=druki.id LEFT JOIN druki_autorzy ON projekty.autor_id=druki_autorzy.id WHERE projekty_druki.druk_id='$id'");
    $druk['projekty'] = array();
    foreach( $projekty_data as $projekt ) $druk['projekty'][] = $projekt['id'];
    $result['projekty_data'] = $projekty_data;
  
  }
  
  
  
  $result['druk'] = $druk;
  return $result;
?>