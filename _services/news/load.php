<?
  $file = ROOT.'/data/news.json';
  return json_decode( @file_get_contents($file) );
?>