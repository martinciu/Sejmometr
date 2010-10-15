<?
	$action = $this->DB->selectValue("SELECT action FROM `graber_stack` ORDER BY `data` ASC, `action` ASC LIMIT 1");
	if( empty($action) ) { return false; }
	$this->DB->q("DELETE FROM `graber_stack` ORDER BY `data` ASC, `action` ASC LIMIT 1");
	return $action;
?>