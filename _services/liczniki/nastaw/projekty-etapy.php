<?
  $ids = $this->DB->selectValues("(SELECT id FROM projekty WHERE html_zmiana='1') UNION (SELECT projekt_id FROM projekty_druki WHERE przypisany='0') UNION (SELECT projekt_id FROM projekty_punkty_wypowiedzi WHERE przypisany='0') UNION (SELECT projekt_id FROM projekty_punkty_glosowania WHERE przypisany='0') UNION (SELECT projekt_id FROM projekty_wyroki WHERE przypisany='0')");  
  $this->S('liczniki/nastaw', array('projekty-etapy', $this->DB->selectCount("SELECT COUNT(*) FROM projekty WHERE (id='".implode("' OR id='", $ids)."') AND akcept='1'")));
?>