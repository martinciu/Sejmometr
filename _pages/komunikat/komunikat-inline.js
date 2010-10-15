{literal}
try{
  document.getElementById('inp').focus();
}catch(e){}
{/literal}
{if $smarty.get.dodano eq 1}alert('Dziękujemy - Twój adres email został zapisany.');{/if}