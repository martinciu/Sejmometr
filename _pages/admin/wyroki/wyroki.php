<?
  $this->SMARTY->assign('_wyroki_typy', $this->DB->selectRows('SELECT id, label FROM wyroki_typy ORDER BY id ASC'))
  // $this->assignService('_autorzy_options', 'autorzy/lista_mform');
?>