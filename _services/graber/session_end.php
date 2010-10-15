<?
  list($session_id, $session_time_start, $result) = $_PARAMS;
  $length = microtime(true) - $session_time_start;
    
  $this->DB->q("UPDATE graber_sessions SET `status`='1', `length`='$length', `result`='".json_encode($result)."' WHERE `id`='$session_id'");
?>