<?
  $items = $this->DB->selectAssocs('SELECT id, nazwa FROM avatary_wgrane WHERE nazwa LIKE "%'.$_POST['q'].'%" LIMIT 10'); 
  $this->SMARTY->assign('items', $items);
?>