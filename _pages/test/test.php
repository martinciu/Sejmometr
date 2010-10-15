<?
  $this->addLib('google');
  $data = json_decode(file_get_contents(ROOT.'/data/ofensywa.json'));
  $this->SMARTY->assign('data', $data);
?>