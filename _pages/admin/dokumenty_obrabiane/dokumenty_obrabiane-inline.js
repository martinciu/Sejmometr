{literal}
	$M.addInitCallback(function(){
	  mBrowser = new MBrowser('browser', 'Dokumenty obrabiane', {table: 'dokumenty', fields: ['plik'], categories: [{label: 'Wszystkie', where: "`akcept`='0'"}, {label: 'Niepobrane', where: "`pobrano`='1' OR `pobrano`='3'"}, {label: 'Automator', where: "`obraz`='1'"}, {label: 'Błędy Scribd', where: "`scribd`='2' OR `scribd`='4'"}], 'url_controll': true, 'item_pattern':'dokument'});
	});
{/literal}