<?
  list($akcja, $wynik) = $_PARAMS;
  if( !empty($wynik) ) $this->DB->q("INSERT INTO graber_dokumenty_dziennik (`czas`, `akcja`, `wynik`) VALUES (NOW(), '$akcja', '$wynik')");
  return $wynik;
?>