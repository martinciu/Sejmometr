var _kluby_options = {$_kluby_options|@json_encode};
var _kluby = {$_kluby|@json_encode};
{literal}
	$M.addInitCallback(function(){
	  mBrowser = new MBrowser('browser', 'Posłowie', {loader: 'poslowie', categories: [{label: 'Zaakceptowani', id: 'zaakceptowane'}, {label: 'Do akceptu', id: 'doakceptu'}], 'url_controll': true, 'item_pattern':'item', 'default_category': 1});
	});
{/literal}
