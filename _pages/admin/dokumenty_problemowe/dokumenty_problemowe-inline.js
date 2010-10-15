{literal}
	$M.addInitCallback(function(){
	  mBrowser = new MBrowser('browser', 'Dokumenty problemowe', {table: 'dokumenty_problemy', fields: ['plik'], categories: [{label: 'Wszystkie'}], 'url_controll': true, 'item_pattern':'dokument'});
	});
{/literal}