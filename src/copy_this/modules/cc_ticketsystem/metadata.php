<?php
/**
 * CommerceCoding Ticketsystem for OXID eShop
 *
 * NOTICE OF LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 3 of the License
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see http://www.gnu.org/licenses/
 *
 * @copyright   Copyright (c) 2012 CommerceCoding (http://www.commerce-coding.de)
 * @author      Alexander Diebler
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

/**
 * Metadata version
 */
$sMetadataVersion = '1.0';

/**
 * Module information
 */
$aModule = array(
  'id'           => 'cc_ticketsystem',
  'title'        => 'Ticketsystem',
  'description'  => array(
    'de' => 'Ein Ticketsystem zum Empfang und zur Bearbeitung von Kundenanfragen.',
    'en' => 'A ticket system for receiving and handling customer inquiries.'
  ),
  'lang' => 'de',
  'thumbnail'    => 'CommerceCoding.png',
  'version'      => '0.1.0',
  'author'       => 'Commerce Coding',
  'url'          => 'http://www.commerce-coding.de',
  'email'        => 'info@commerce-coding.de',
  'extend' => array(
    'oxuser'  => 'cc_ticketsystem/models/cc_oxuser',
    'oxemail' => 'cc_ticketsystem/models/cc_oxemail'
  ),
  'files' => array(
    'cc_ticket'               => 'cc_ticketsystem/models/cc_ticket.php',
    'cc_tickettext'           => 'cc_ticketsystem/models/cc_tickettext.php',
    'cc_account_tickets'      => 'cc_ticketsystem/controllers/cc_account_tickets.php',
    'cc_ticketsystem_tickets' => 'cc_ticketsystem/controllers/cc_ticketsystem_tickets.php'
  ),
  'blocks' => array(
    array('template' => 'page/account/dashboard.tpl',        'block' => 'account_dashboard_col2',         'file' => 'out/blocks/page/account/dashboard'),
    array('template' => 'page/account/inc/account_menu.tpl', 'block' => 'account_menu',                   'file' => 'out/blocks/page/account/inc/account_menu'),
    array('template' => 'widget/header/servicebox.tpl',      'block' => 'widget_header_servicebox_items', 'file' => 'out/blocks/widget/header/servicebox')
  ),
  'settings' => array(
    array('group' => 'main', 'name' => 'sendmail',    'type' => 'bool', 'value' => 'true'),
    array('group' => 'main', 'name' => 'supportname', 'type' => 'str',  'value' => 'Support')
  ),
  'templates' => array(
    'cc_ticketsystem_overview.tpl'     => 'cc_ticketsystem/out/admin/tpl/cc_ticketsystem_overview.tpl',
    'cc_ticketsystem_table.tpl'        => 'cc_ticketsystem/out/admin/tpl/cc_ticketsystem_table.tpl',
    'cc_ticketsystem_ticket.tpl'       => 'cc_ticketsystem/out/admin/tpl/cc_ticketsystem_ticket.tpl',
    'cc_account_overview.tpl'          => 'cc_ticketsystem/out/tpl/page/cc_account_overview.tpl',
    'cc_account_ticket.tpl'            => 'cc_ticketsystem/out/tpl/page/cc_account_ticket.tpl',
    'cc_account_ticket_new.tpl'        => 'cc_ticketsystem/out/tpl/page/cc_account_ticket_new.tpl',
    'cc_form_new.tpl'                  => 'cc_ticketsystem/out/tpl/form/cc_form_new.tpl',
    'cc_form_update.tpl'               => 'cc_ticketsystem/out/tpl/form/cc_form_update.tpl',
    'cc_email_ticket_new_admin.tpl'    => 'cc_ticketsystem/out/tpl/email/cc_email_ticket_new_admin.tpl',
    'cc_email_ticket_update_admin.tpl' => 'cc_ticketsystem/out/tpl/email/cc_email_ticket_update_admin.tpl',
    'cc_email_ticket_update_user.tpl'  => 'cc_ticketsystem/out/tpl/email/cc_email_ticket_update_user.tpl'
  )
);