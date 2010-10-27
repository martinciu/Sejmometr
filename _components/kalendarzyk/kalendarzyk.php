<?
  function sm_kalendarzyk($data){
    $_miesiace = array('STY', 'LUTY', 'MAR', 'KWIE', 'MAJ', 'CZE', 'LIP', 'SIE', 'WRZ', 'PAŹ', 'LIS', 'GRU');
    $data = explode('-', $data);
    
    $rok = (int) $data[0];
    $miesiac = (int) $data[1];
    $miesiac = $_miesiace[$miesiac-1];
    $dzien = (int) $data[2];
    
    return '<div class="kalendarzyk"><p class="miesiac"><span>'.$miesiac.'</span></p><p class="dzien">'.$dzien.'</p><p class="rok"><span>'.$rok.'</span></p></div>';
  }
?>