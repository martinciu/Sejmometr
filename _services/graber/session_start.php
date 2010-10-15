<?
  $this->DB->q("INSERT INTO graber_sessions (`action`) VALUES ('$_PARAMS')");
  return array($this->DB->insert_id, microtime(true));
?>