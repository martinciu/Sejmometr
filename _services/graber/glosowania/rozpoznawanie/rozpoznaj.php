<?
  $id = $_PARAMS;
  if( !is_numeric($id) ) return false;
  $this->DB->q("UPDATE glosowania_glosy JOIN poslowie ON glosowania_glosy.czlowiek_id=poslowie.glosowania_fraza SET glosowania_glosy.nid=poslowie.nid, glosowania_glosy.status='2' WHERE glosowania_glosy.id=$id");
?>