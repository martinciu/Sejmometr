{literal}
var items;
var items_count;
var items_iterator;

var start = function(){
  $S('list', function(data){
    items = data;
    items_count = data.length;
    items_iterator = 0;
    $('btnStart').disable();
    $('results').update('');
    recurency_start();
  });
}

function recurency_start(){
  var item = items.shift();
  if( item ) {
    items_iterator++;
    var progress = 100*items_iterator/items_count;
    $$('.progress_bar').invoke('setStyle', {width: progress+'%'});
    $('results').insert('<p>('+items_iterator+'/'+items_count+') '+item);
    if( $('followInp').checked ){ $('results').scrollTop = $('results').scrollHeight-$('results').getHeight(); }
    
    $S('do', item, recurency_start)
    
  } else {
    $('btnStart').enable();
  }
}

$('btnStart').observe('click', start);
{/literal}