<?
  list($id, $licznik) = $_PARAMS;  
  return $this->DB->q("INSERT IGNORE INTO liczniki (`id`, `licznik`) VALUES ('$id', '$licznik') ON DUPLICATE KEY UPDATE `licznik`='$licznik'");
?>