<?
  $kluby_options = $this->DB->selectRows("SELECT kluby.id, druki_autorzy.autor FROM kluby LEFT JOIN druki_autorzy ON kluby.id=druki_autorzy.id ORDER BY druki_autorzy.autor ASC");
  
  $kluby = $this->DB->selectPairs("SELECT sejm_id, id FROM kluby");
    
  $this->SMARTY->assign('_kluby_options', $kluby_options );
  $this->SMARTY->assign('_kluby',  $kluby);
?>