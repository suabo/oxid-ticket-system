[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]

<h1>[{oxmultilang ident="CC_TICKETSYSTEM_TICKETS"}]</h1>

  <h2>[{oxmultilang ident="CC_TICKETSYSTEM_OPEN"}]</h2>
  <strong>[{oxmultilang ident="CC_TICKETSYSTEM_SUPPORT_NEEDED"}]</strong><br>
  [{include file="cc_ticketsystem_table.tpl" aTickets=$oView->getAdminTickets() action='LOCK'}]

  <strong>[{oxmultilang ident="CC_TICKETSYSTEM_CUSTOMER_RESPONSE"}]</strong><br>
  [{include file="cc_ticketsystem_table.tpl" aTickets=$oView->getUserTickets() action='LOCK'}]

  <br>
  <h2>[{oxmultilang ident="CC_TICKETSYSTEM_CLOSED"}]</h2>
  [{include file="cc_ticketsystem_table.tpl" aTickets=$oView->getClosedTickets() action='UNLOCK'}]


[{if ($info)}]
  <br><div class="[{$info.class}]">[{oxmultilang ident=$info.message}]</div><br>
[{/if}]