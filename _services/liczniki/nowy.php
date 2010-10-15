<?
  $result = (int) $this->DB->insert_ignore_assoc('liczniki', array('nazwa'=>$_PARAMS));
  return $result+2;  
?>