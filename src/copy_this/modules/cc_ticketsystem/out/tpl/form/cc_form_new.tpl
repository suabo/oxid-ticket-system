<form action="[{ $oViewConf->getSelfActionLink() }]" name="ticket_new" method="post">
  <ul class="">
    <li>
      [{ $oViewConf->getHiddenSid() }]
      [{ $oViewConf->getNavFormParams() }]
      <input type="hidden" name="fnc" value="newTicket">
      <input type="hidden" name="cl" value="cc_account_tickets">
      <label>[{ oxmultilang ident="CC_TICKETSYSTEM_SUBJECT" }]</label><br>
      <input type="text" name="ticketsubject" style="width:98%"><br><br>
      <label>[{ oxmultilang ident="CC_TICKETSYSTEM_MESSAGE" }]</label><br>
      <textarea name="tickettext" rows="10" style="width:99%"></textarea><br><br>
      <button type="submit" class="submitButton">[{oxmultilang ident="CC_TICKETSYSTEM_SEND"}]</button>
    </li>
  </ul>
</form>