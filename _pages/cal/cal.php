<?
  $year = (int) date('Y');
  $month = (int) date('m');
  $day = (int) date('d');
  
  $cal_data = $this->S('cal_data', array($year, $month, $day));
 
  $this->SMARTY->assign('cal_data', $cal_data);
  $this->SMARTY->assign('_miesiace', array('', 'Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'));
?>