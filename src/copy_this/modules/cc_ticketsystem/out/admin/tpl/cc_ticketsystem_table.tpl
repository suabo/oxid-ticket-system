<table cellspacing="0" cellpadding="0" style="border: 1px solid #000; margin-bottom: 20px;" width="50%">
<tr>
  <th style="width:70%; background-color: #CCC;">Ticket</th>
  <th style="width:25%; background-color: #CCC;">Update</th>
  <th style="width:5%; background-color: #CCC;"></th>
</tr>
[{foreach from=$aTickets key=ticketid item=ticket}]
<tr style="background-color: [{cycle values="#EEE,#DDD"}];">
  <td><a href="[{$oViewConf->getSelfLink()}]cl=cc_ticketsystem_tickets&ticket=[{$ticketid}]">[{$ticket.subject}]</a></td>
  <td style="text-align: center;">[{$ticket.updated|date_format:"%d.%m.%Y, %H:%M"}]</td>
  <td>
    <a href="[{$oViewConf->getSelfLink()}]cl=cc_ticketsystem_tickets&ticket=[{$ticketid}]&fnc=[{$action}]">
      <img src="[{$oViewConf->getBaseDir()}]modules/cc_ticketsystem/out/img/[{$action}].png" title="[{oxmultilang ident="CC_TICKETSYSTEM_"|cat:$action}]">
    </a>
  </td>
</tr>
[{/foreach}]
</table>