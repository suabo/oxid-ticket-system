[{capture append="oxidBlock_content"}]

  <h1 class="pageHead">[{ oxmultilang ident="CC_TICKETSYSTEM_NEW_TICKET" }]</h1>

  [{include file="cc_form_new.tpl"}]

[{/capture}]
[{capture append="oxidBlock_sidebar"}]
    [{include file="page/account/inc/account_menu.tpl" active_link="cc_account_tickets"}]
[{/capture}]
[{include file="layout/page.tpl" sidebar="Left"}]