<?
  $id = $_PARAMS; 
  if( empty($id) ) return false;
  
  
  
  $ctrlFile = ROOT.'/dokumenty_podajnik/'.$id;
  $pdfFile = ROOT.'/dokumenty_podajnik/'.$id.'.pdf';
  $gifFile = ROOT.'/dokumenty_podajnik/'.$id.'.gif';
  $txtFile = ROOT.'/dokumenty_podajnik/'.$id.'.txt';
  
  $targetPdfFile = ROOT.'/dokumenty_backup/'.$id.'.pdf';
  $targetGifFile = ROOT.'/d/0/'.$id.'.gif';
  $targetTxtFile = ROOT.'/dokumenty_txt/'.$id.'.txt';
    
  if( file_exists($ctrlFile) ) {
    
    // OBRABIAMY PDF
    @rename(ROOT.'/dokumenty/'.$id.'.pdf', $targetPdfFile);
    @rename($pdfFile, ROOT.'/dokumenty/'.$id.'.pdf');
    
    // OBRABIAMY GIF
    @rename($gifFile, $targetGifFile);
    $size = getimagesize($targetGifFile);
	  $width = $size[0];
	  $height = $size[1];
		$this->S( 'graber/dokumenty/obrazy/zbuduj_miniature', array($targetGifFile, 170, 220, '1') );
		$this->S( 'graber/dokumenty/obrazy/zbuduj_miniature', array($targetGifFile, 100, 124, '2') );
		$this->S( 'graber/dokumenty/obrazy/zbuduj_miniature', array($targetGifFile, 75, 93, '3') );
		$this->S( 'graber/dokumenty/obrazy/zbuduj_miniature', array($targetGifFile, 48, 48, '4') );
		$this->S( 'graber/dokumenty/obrazy/zbuduj_miniature', array($targetGifFile, 265, 343, '5') );
		
		
    // OBRABIAMY TXT    
    if( file_exists($txtFile) ) {
      @file_put_contents($targetTxtFile, iconv('UTF-16', 'UTF-8', file_get_contents($txtFile)));
    }
    
    $this->DB->q("UPDATE `dokumenty` SET `obraz`='3', `data_obraz`=NOW(), `obraz_szerokosc`='$width', `obraz_wysokosc`='$height' WHERE id='$id'");
    $this->DB->q("UPDATE `dokumenty` SET `scribd`='1' WHERE `id`='$id' AND `scribd`='0'");
    $this->S('liczniki/nastaw/dokumenty_obrabiane');
    
  } else {
    $this->DB->q("UPDATE `dokumenty` SET `obraz`='1', `akcept`='0' WHERE `id`='$id'");
  }

  @unlink($ctrlFile);
  @unlink($pdfFile);
  @unlink($gifFile);
  @unlink($txtFile);
?>