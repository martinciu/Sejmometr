<?
  header("Content-Type: text/xml");
  function sm_cdata( $s ){
    return '<![CDATA['.$s.']]>';
  }

  $items = $this->DB->selectAssocs("SELECT id, url_title, tytul, opis, DATE(data_zapisania) as 'data' FROM blog WHERE robocza='0' ORDER BY data_zapisania DESC");
  $this->SMARTY->assign('items', $items);
  $this->SMARTY->register_modifier('cdata', 'sm_cdata');
?>