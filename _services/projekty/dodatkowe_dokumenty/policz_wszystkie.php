<?
  $ids = $this->DB->selectValues("(SELECT projekt_id FROM `akty_wykonawcze`) UNION (SELECT projekt_id FROM `projekty_dokumenty`)");
  foreach( $ids as $id ) $this->S('projekty/dodatkowe_dokumenty/policz', $id);
?>