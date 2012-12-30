[{capture append="oxidBlock_content"}]

  <h1 class="pageHead">[{ oxmultilang ident="CC_TICKETSYSTEM_MY_TICKETS" }]</h1>

  <table cellspacing="0" cellpadding="0" style="border: 1px solid #000; margin-bottom: 20px;" width="100%">
  <tr>
    <th style="width:60%; background-color: #CCC;">Ticket</th>
    <th style="width:20%; background-color: #CCC;">Status</th>
    <th style="width:20%; background-color: #CCC;">Update</th>
  </tr>
  [{foreach from=$tickets item=ticket}]
  <tr style="background-color: [{cycle values="#EEE,#DDD"}];">
    <td style="padding: 2px;"><a class="readMore" href="[{oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=cc_account_tickets" params="ticket="|cat:$ticket.ticketid}]" rel="nofollow">[{$ticket.subject}]</a></td>
    <td style="padding: 2px; text-align: center;">[{oxmultilang ident=$ticket.state}]</td>
    <td style="padding: 2px; text-align: center;">[{$ticket.updated|date_format:"%d.%m.%Y, %H:%M"}]</td>
  </tr>
  [{/foreach}]
  </table>

  <a class="readMore" href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=cc_account_tickets" params="ticket=new"}]" rel="nofollow">+ Neues Ticket</a>

[{/capture}]
[{capture append="oxidBlock_sidebar"}]
    [{include file="page/account/inc/account_menu.tpl" active_link="cc_account_tickets"}]
[{/capture}]
[{include file="layout/page.tpl" sidebar="Left"}]