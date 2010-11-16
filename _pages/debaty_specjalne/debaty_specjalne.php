<?
  $debaty = $this->DB->selectAssocs("SELECT debaty_specjalne.id, debaty_specjalne.tytul, posiedzenia_dni.data, punkty_wypowiedzi.ilosc_wypowiedzi, punkty_wypowiedzi.opis FROM debaty_specjalne JOIN punkty_wypowiedzi ON debaty_specjalne.id=punkty_wypowiedzi.id JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id ORDER BY posiedzenia_dni.data DESC");
        
  $this->SMARTY->assign('debaty', $debaty);
?>