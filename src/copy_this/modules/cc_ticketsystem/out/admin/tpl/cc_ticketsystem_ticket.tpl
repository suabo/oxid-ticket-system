[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]

<h1>[{oxmultilang ident="CC_TICKETSYSTEM_TICKET"}]: [{$oView->getTicketSubject()}]</h1>

  [{foreach from=$oView->getTicketTexts() item=text}]
    <table cellspacing="0" cellpadding="0" style="border: 1px solid #000; margin-bottom: 20px;" width="50%">
      <tr>
        <td style="width:80%; background-color: #DDD;"><strong><img src="[{$text.image}]"> [{$text.author}]</strong></td>
        <td style="width:20%; background-color: #DDD;">[{$text.timestamp|date_format:"%d.%m.%Y, %H:%M"}]</td>
      </tr>
      <tr>
        <td colspan=2>[{$text.text}]</td>
      </tr>
    </table>
  [{/foreach}]

  [{if $oView->getTicketState() != 3}]
  <form action="[{ $oViewConf->getSelfLink() }]" name="ticket_update" method="post">
      [{ $oViewConf->getHiddenSid() }]
      <input type="hidden" name="fnc" value="updateTicket">
      <input type="hidden" name="cl" value="cc_ticketsystem_tickets">
      <input type="hidden" name="oxid" value="[{$oView->getTicketOxid()}]">
      <label>[{ oxmultilang ident="CC_TICKETSYSTEM_MESSAGE" }]</label><br>
      <textarea name="tickettext" style="width:50%" rows="6"></textarea><br>
      <button type="submit" class="submitButton">[{ oxmultilang ident="CC_TICKETSYSTEM_SEND" }]</button>
  </form>
  [{/if}]