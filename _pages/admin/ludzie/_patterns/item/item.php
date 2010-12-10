<?
  $id = $_PARAMS['id'];
  $result = array();
    
  $item = $this->DB->selectAssoc("SELECT id, nazwa, fraza, avatar FROM ludzie WHERE id='$id'");
  $funkcje = $this->DB->selectValues("SELECT DISTINCT(wypowiedzi_funkcje.nazwa) FROM wypowiedzi JOIN wypowiedzi_funkcje ON wypowiedzi.funkcja_id=wypowiedzi_funkcje.id WHERE wypowiedzi.autor_id='$id'");
  
  $result['item'] = $item;
  $result['funkcje'] = $funkcje;
  return $result;
?>