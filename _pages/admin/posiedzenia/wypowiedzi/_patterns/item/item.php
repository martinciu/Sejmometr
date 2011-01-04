<?
  $id = $_PARAMS['id'];
  $result = array();
  
  $_funkcje_klubowe = array('1','2','3','4','5');
  
  
  $item = $this->DB->selectAssoc("SELECT wypowiedzi.id, wypowiedzi.autor_id, wypowiedzi.funkcja_id, wypowiedzi_funkcje.nazwa as 'funkcja_label', wypowiedzi.skrot, ludzie.nazwa, ludzie.avatar, wypowiedzi.text, posiedzenia_dni.data, wypowiedzi.punkt_id, punkty_wypowiedzi.sejm_id as 'punkt_tytul', posiedzenia_dni.data FROM wypowiedzi LEFT JOIN ludzie ON wypowiedzi.autor_id=ludzie.id LEFT JOIN wypowiedzi_funkcje ON wypowiedzi.funkcja_id=wypowiedzi_funkcje.id LEFT JOIN posiedzenia_dni ON posiedzenia_dni.id=wypowiedzi.dzien_id LEFT JOIN punkty_wypowiedzi ON wypowiedzi.punkt_id=punkty_wypowiedzi.id WHERE wypowiedzi.id='$id'");
  
  if( in_array($item['funkcja_id'], $_funkcje_klubowe) ) {
    $item = array_merge($item, $this->DB->selectAssoc("SELECT poslowie.klub, druki_autorzy.autor as 'klub_label' FROM poslowie LEFT JOIN druki_autorzy ON poslowie.klub=druki_autorzy.id WHERE poslowie.id='".$item['autor_id']."'"));
  }
  
  $item['sejm_ids'] = $this->DB->selectValues("SELECT sejm_id FROM wypowiedzi_sejm_id WHERE wypowiedz_id='$id'");
  
  
     
  $result['item'] = $item;
  return $result;
?>