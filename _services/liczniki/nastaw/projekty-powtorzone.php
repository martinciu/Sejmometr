<?
  $druki_ids = $this->DB->selectValues("SELECT druk_id FROM `projekty` WHERE druk_id!='' GROUP BY druk_id HAVING COUNT(*)>1");
  $this->S('liczniki/nastaw', array('projekty-powtorzone', count($druki_ids)));
?>