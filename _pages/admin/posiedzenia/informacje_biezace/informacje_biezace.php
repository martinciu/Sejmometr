<?
  $_kluby = $this->DB->selectRows("SELECT kluby.id, druki_autorzy.autor FROM kluby JOIN druki_autorzy ON kluby.id=druki_autorzy.id WHERE kluby.id!='NIEZ' ORDER BY autor ASC");
  $this->SMARTY->Assign('_kluby', $_kluby);
?>