<ul>{section name="items" loop=$items}{assign var="item" value=$items[items]}
  <li value="{$item.id}">{$item.nazwa}</li>
{/section}</ul>