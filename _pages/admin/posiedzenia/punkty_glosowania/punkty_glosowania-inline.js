{literal}
	$M.addInitCallback(function(){
	  mBrowser = new MBrowser('browser', 'Punkty głosowania', {loader: 'punkty_glosowania', categories: [{label: 'Zaakceptowane', id: "zaakceptowane"}, {id: 'doakceptu', label: 'Do akceptu'}], 'url_controll': true, 'item_pattern':'punkt', 'default_category': 1});
	});
{/literal}
	var _druki_typy = {$_druki_typy|@json_encode};
	var _punkty_typy = {$_punkty_typy|@json_encode};
	var _autorzy_options = {$_autorzy_options|@json_encode};
