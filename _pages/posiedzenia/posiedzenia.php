<?
  $year = (int) date('Y');
  $month = (int) date('m');
    
    
  
  $id = $_GET['_ID'];
  if( strlen($id)!=5 ) $id = 'PKRhj';
  
  $posiedzenie = $this->DB->selectAssoc("SELECT id, numer FROM posiedzenia WHERE id='$id'");
  $dni = $this->DB->selectAssocs("SELECT id, data FROM posiedzenia_dni WHERE posiedzenie_id='$id'");
  $plan = $this->S('posiedzenia/projekty', $id);
  

  $cal_data = $this->S('cal_data', explode('-', $dni[0]['data']));

    
  $this->SMARTY->assign('posiedzenie', $posiedzenie);
  $this->SMARTY->assign('dni', $dni);
  $this->SMARTY->assign('plan', $plan);
  $this->SMARTY->assign('cal_data', $cal_data);
  $this->SMARTY->assign('_miesiace', array('', 'Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'));
  
  $this->TITLE = 'Posiedzenie '.$posiedzenie['numer'];
?>