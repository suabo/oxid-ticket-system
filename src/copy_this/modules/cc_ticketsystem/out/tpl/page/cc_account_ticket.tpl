[{capture append="oxidBlock_content"}]

  <h1 class="pageHead">[{ oxmultilang ident="CC_TICKETSYSTEM_MY_TICKETS" }]: [{$oView->getTicketSubject()}]</h1>

  [{foreach from=$oView->getTicketTexts() item=text}]
    <table cellspacing="0" cellpadding="0" style="border: 1px solid #000; margin-bottom: 20px;" width="100%">
      <tr>
        <td style="width:50%; background-color: #DDD;"><strong><img src="[{$text.image}]"> [{$text.author}]</strong></td>
        <td style="width:50%; background-color: #DDD; text-align: right;">[{$text.timestamp|date_format:"%d.%m.%Y, %H:%M"}]</td>
      </tr>
      <tr>
        <td colspan=2 style="padding: 5px;">[{$text.text}]</td>
      </tr>
    </table>
  [{/foreach}]

  [{if $oView->getTicketState() != 3 && $oView->getUpdate()}]
  [{include file="cc_form_update.tpl"}]
  [{elseif $oView->getTicketState() != 3}]
  <a class="readMore" href="[{oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=cc_account_tickets" params="update=true&ticket="|cat:$oView->getTicketOxid()}]" rel="nofollow">+ [{ oxmultilang ident="CC_TICKETSYSTEM_ADD_MESSAGE" }]</a>
  [{/if}]

[{/capture}]
[{capture append="oxidBlock_sidebar"}]
    [{include file="page/account/inc/account_menu.tpl" active_link="cc_account_tickets"}]
[{/capture}]
[{include file="layout/page.tpl" sidebar="Left"}]