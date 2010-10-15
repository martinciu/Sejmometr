function click_checkbox(event){
  var el = event.findElement('.mCheckbox');  
  el.toggleClassName('checked');
}

function arm_checkboxes(){
  $$('.mCheckbox').each(function(checkbox){
    if( checkbox.up('li').hasClassName('selected') ) {
      checkbox.addClassName('superchecked');
    } else {
      checkbox.observe('click', click_checkbox);
    }
  });
}

function filtruj(filtr, btn){
  var vars = get_ul(btn).select('.mCheckbox.checked').invoke('readAttribute', 'o');
  if( vars.length ) location = '?'+filtr+'='+vars.join(',');
}

function filtr_wiecej(a){
  var ul = get_ul(a);
  ul.select('li').each(function(li){
    if( li.hasClassName('_') ) li.show();
  });
  
  var wm = get_wm(ul);
  wm.down('.wiecej').hide();
  wm.down('.mniej').show();
}

function filtr_mniej(a){
  var ul = get_ul(a);
  ul.select('li').each(function(li){
    if( li.hasClassName('_') ) li.hide();
  });

  var wm = get_wm(ul);
  wm.down('.wiecej').show();
  wm.down('.mniej').hide();
}

function get_ul(el){
  return $(el).up('.filtr').down('ul');
}

function get_wm(el){
  return $(el).up('.filtr').down('.wiecejmniej');
}

function arm_sorting(){
  $('sortowanie_select').observe('change', function(){
    var l =  String( location );
    var params = (l.indexOf('?')==-1) ? {} : l.toQueryParams();
    params['sort'] = $('sortowanie_select').selectedIndex;
    location = '?'+Object.toQueryString(params);
  });
}