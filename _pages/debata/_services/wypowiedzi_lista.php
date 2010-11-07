<?
  $id = $_PARAMS;
  if( strlen($id)!=5 ) return false;
  
  return $this->DB->selectAssocs("SELECT wypowiedzi.id, wypowiedzi.typ, wypowiedzi.autor_id, wypowiedzi.funkcja_id, wypowiedzi_funkcje.nazwa as 'funkcja', ludzie.nazwa as 'autor', ludzie.avatar, wypowiedzi.skrot, SUBSTRING(wypowiedzi.text, 1, 100) as 'txt' FROM wypowiedzi LEFT JOIN wypowiedzi_funkcje ON wypowiedzi.funkcja_id=wypowiedzi_funkcje.id LEFT JOIN ludzie ON wypowiedzi.autor_id=ludzie.id WHERE wypowiedzi.punkt_id='$id' ORDER BY wypowiedzi.ord ASC");
?>