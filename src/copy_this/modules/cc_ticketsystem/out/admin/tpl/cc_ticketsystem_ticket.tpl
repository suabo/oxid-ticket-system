[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]

<h1>[{oxmultilang ident="CC_TICKETSYSTEM_TICKET"}]: [{$ticket->cctickets__subject->rawValue}]</h1>

  [{foreach from=$ticket_texts item=text}]
    <table cellspacing="0" cellpadding="0" style="border: 1px solid #000; margin-bottom: 20px;" width="50%">
      <tr>
        <td style="width:20%; background-color: #DDD;"><strong><img src="[{$text.image}]"> [{$text.author}]</strong></td>
        <td style="width:80%; background-color: #DDD;">[{$text.timestamp}]</td>
      </tr>
      <tr>
        <td colspan=2>[{$text.text}]</td>
      </tr>
    </table>
  [{/foreach}]

  [{if $ticket->cctickets__state->rawValue != 3}]
  <form action="[{ $oViewConf->getSelfLink() }]" name="ticket_update" method="post">
      [{ $oViewConf->getHiddenSid() }]
      <input type="hidden" name="fnc" value="updateTicket">
      <input type="hidden" name="cl" value="cc_ticketsystem_tickets">
      <input type="hidden" name="oxid" value="[{$ticket->getId()}]">
      <label>[{ oxmultilang ident="CC_TICKETSYSTEM_MESSAGE" }]</label><br>
      <textarea name="tickettext" style="width:50%" rows="6"></textarea><br>
      <button type="submit" class="submitButton">[{ oxmultilang ident="CC_TICKETSYSTEM_SEND" }]</button>
  </form>
  [{/if}]