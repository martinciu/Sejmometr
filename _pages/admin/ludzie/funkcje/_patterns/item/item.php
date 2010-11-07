<?
  $id = $_PARAMS['id'];
  $result = array();
  
  $item = $this->DB->selectAssoc("SELECT id, autor FROM wypowiedzi_nierozpoznani_autorzy WHERE id='$id'");
  $item['wyp_count'] = $this->DB->selectCount("SELECT COUNT(*) FROM `wypowiedzi_id-nierozpoznani_autorzy` WHERE autor_id='$id'");
  
  $result['item'] = $item;
  return $result;
?>