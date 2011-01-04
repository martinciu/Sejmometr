<?
  $file = ROOT.'/data/news.json';
  $limit = 1;
  
  $qs = array(
    "SELECT projekty_etapy.etap_id FROM projekty_etapy JOIN druki ON projekty_etapy.etap_id=druki.id WHERE projekty_etapy.typ_id=0 AND DATEDIFF(NOW(), druki.data)<=7", "SELECT projekty_etapy.etap_id FROM projekty_etapy JOIN druki ON projekty_etapy.etap_id=druki.id WHERE projekty_etapy.typ_id=0 AND DATEDIFF(NOW(), druki.data)<=".$limit,
    "SELECT projekty_etapy.etap_id FROM projekty_etapy JOIN czytania_komisje ON projekty_etapy.etap_id=czytania_komisje.id WHERE projekty_etapy.typ_id=1 AND DATEDIFF(NOW(), czytania_komisje.data)<=".$limit,
    "SELECT projekty_etapy.etap_id FROM projekty_etapy JOIN punkty_wypowiedzi ON projekty_etapy.etap_id=punkty_wypowiedzi.id JOIN posiedzenia_dni ON punkty_wypowiedzi.dzien_id=posiedzenia_dni.id WHERE projekty_etapy.typ_id=2 AND DATEDIFF(NOW(), posiedzenia_dni.data)<=".$limit,
    "SELECT projekty_etapy.etap_id FROM projekty_etapy JOIN punkty_glosowania ON projekty_etapy.etap_id=punkty_glosowania.id JOIN posiedzenia_dni ON punkty_glosowania.dzien_id=posiedzenia_dni.id WHERE projekty_etapy.typ_id=3 AND DATEDIFF(NOW(), posiedzenia_dni.data)<=".$limit,
    "SELECT projekty_etapy.etap_id FROM projekty_etapy JOIN skierowania ON projekty_etapy.etap_id=skierowania.id WHERE projekty_etapy.typ_id=4 AND DATEDIFF(NOW(), skierowania.data)<=$limit",
    "SELECT projekty_etapy.etap_id FROM projekty_etapy JOIN wyroki_tk ON projekty_etapy.etap_id=wyroki_tk.id WHERE projekty_etapy.typ_id=5 AND DATEDIFF(NOW(), wyroki_tk.data)<=".$limit,
    "SELECT projekty_etapy.etap_id FROM projekty_etapy JOIN aklamacje ON projekty_etapy.etap_id=aklamacje.id WHERE projekty_etapy.typ_id=6 AND DATEDIFF(NOW(), aklamacje.data)<=".$limit,
    "SELECT projekty_etapy.etap_id FROM projekty_etapy JOIN punkty_wypowiedzi_bz ON projekty_etapy.etap_id=punkty_wypowiedzi_bz.id WHERE projekty_etapy.typ_id=7 AND DATEDIFF(NOW(), punkty_wypowiedzi_bz.data)<=".$limit,
  );
  $q = "(".implode(") UNION (", $qs).")";

  
  $projekty_ids = $this->DB->selectValues($q);
  
  
  var_export($projekty_ids);
  die();
  
  @unlink($file);
  return file_put_contents($file, json_encode($data));
?>