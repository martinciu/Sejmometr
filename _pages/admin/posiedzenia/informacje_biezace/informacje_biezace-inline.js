var _kluby = {$_kluby|@json_encode};
{literal}
	$M.addInitCallback(function(){
	  mBrowser = new MBrowser('browser', 'Informacje bieżące', {loader: 'informacje_biezace', categories: [{label: 'Zaakceptowane', id: "zaakceptowane"}, {id: 'doakceptu', label: 'Do akceptu'}], 'url_controll': true, 'item_pattern':'item', 'default_category': 1});
	});
{/literal}
