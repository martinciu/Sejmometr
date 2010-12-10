<?
  $klub_id = $_PARAMS;
  if( !$klub_id ) return false;
  
  $ilosc_poslow = $this->DB->selectCount("SELECT COUNT(*) FROM poslowie WHERE aktywny='1' AND klub='$klub_id'");
  $srednia_wieku = $this->DB->selectValue("SELECT AVG(TIMESTAMPDIFF(YEAR, data_urodzenia, NOW())) FROM poslowie WHERE aktywny='1' AND klub='$klub_id'");
  $ilosc_kobiet = $this->DB->selectValue("SELECT COUNT(*) FROM poslowie WHERE plec='K' AND aktywny='1' AND klub='$klub_id'");
  $ilosc_singli = $this->DB->selectValue("SELECT COUNT(*) FROM poslowie WHERE stan_cywilny=1 AND aktywny='1' AND klub='$klub_id'");
  $ilosc_ww = $this->DB->selectValue("SELECT COUNT(*) FROM poslowie WHERE wyksztalcenie=4 AND aktywny='1' AND klub='$klub_id'");
  
  $udzial_kobiet = $ilosc_poslow ? round( 100*$ilosc_kobiet/$ilosc_poslow ) : 0;
  $udzial_singli = $ilosc_poslow ? round( 100*$ilosc_singli/$ilosc_poslow ) : 0;
  $udzial_ww = $ilosc_poslow ? round( 100*$ilosc_ww/$ilosc_poslow ) : 0;

  $this->DB->update_assoc('kluby', array(
    'ilosc_poslow' => $ilosc_poslow,
    'srednia_wieku' => $srednia_wieku,
    'udzial_kobiet' => $udzial_kobiet,
    'udzial_singli' => $udzial_singli,
    'udzial_ww' => $udzial_ww,
  ), $klub_id);
?>