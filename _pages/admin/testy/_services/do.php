<?
  $id = $_PARAMS;
  $this->DB->q("UPDATE druki SET `tempA`=1 WHERE `id`='$id'");
  
  $druki_glowne = $this->DB->selectAssocs("SELECT druki_zalaczniki.druk, druki.typ_id FROM druki_zalaczniki LEFT JOIN druki ON druki_zalaczniki.druk=druki.id WHERE druki_zalaczniki.zalacznik='$id'");
  
  if( count($druki_glowne)!=1 ) return $this->DB->q("UPDATE druki SET `tempA`=100 WHERE `id`='$id'");
  $druk_glowny = $druki_glowne[0];
  if( $druk_glowny['typ_id']!=2 ) return $this->DB->q("UPDATE druki SET `tempA`=101 WHERE `id`='$id'");
  
  $druk_id = $druk_glowny['druk'];
  $projekt_id = $this->DB->selectValue("SELECT id FROM projekty WHERE druk_id='$druk_id'");
  
  if( empty($projekt_id) ) return $this->DB->q("UPDATE druki SET `tempA`=102 WHERE `id`='$id'");
  
  $this->DB->insert_ignore_assoc('stanowiska_rzadu', array(
    'projekt_id' => $projekt_id,
    'druk_id' => $id,
  ));
  
  $this->DB->q("UPDATE druki SET `tempA`=2 WHERE `id`='$id'");
?>