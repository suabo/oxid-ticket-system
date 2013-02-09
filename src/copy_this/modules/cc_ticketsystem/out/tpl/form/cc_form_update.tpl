<form action="[{ $oViewConf->getSelfActionLink() }]" name="ticket_update" method="post">
  <ul class="">
    <li>
      [{ $oViewConf->getHiddenSid() }]
      [{ $oViewConf->getNavFormParams() }]
      <input type="hidden" name="fnc" value="updateTicket">
      <input type="hidden" name="cl" value="cc_account_tickets">
      <input type="hidden" name="tid" value="[{$oView->getTicketOxid()}]">
      <label>[{ oxmultilang ident="CC_TICKETSYSTEM_MESSAGE" }]</label><br>
      <textarea name="tickettext" rows="6" style="width:100%"></textarea><br>
      <button type="submit" class="submitButton">[{oxmultilang ident="CC_TICKETSYSTEM_SEND"}]</button>
    </li>
  </ul>
</form>