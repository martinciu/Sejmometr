{literal}
	$M.addInitCallback(function(){
	  mBrowser = new MBrowser('browser', 'Wyroki', {loader: 'wyroki', categories: [{label: 'Zaakceptowane', id: 'zaakceptowane'}, {id: 'doakceptu', label: 'Do akceptu', where: "`akcept`='0'"}], 'url_controll': true, 'item_pattern':'wyrok', 'default_category': 1});
	});
{/literal}
	var _wyroki_typy = {$_wyroki_typy|@json_encode};
