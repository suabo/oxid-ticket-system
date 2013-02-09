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

class cc_oxemail extends cc_oxemail_parent {

  /**
    * New Ticket E-Mail (Admin)
    *
    * @var string
    */
  protected $_sTicketNewAdminTemplatePlain = "cc_email_ticket_new_admin.tpl";

  /**
    * Updated Ticket E-Mail (Admin)
    *
    * @var string
    */
  protected $_sTicketUpdateAdminTemplatePlain = "cc_email_ticket_update_admin.tpl";

  /**
    * Updated Ticket E-Mail (User)
    *
    * @var string
    */
  protected $_sTicketUpdateUserTemplatePlain = "cc_email_ticket_update_user.tpl";

  /**
   * Sends an email to the admin informing him about a new ticket.
   *
   * @param string $sTicketTitle
   * @param string $sTicketText
   * @return bool
   */
  public function sendNewTicketToAdmin($sTicketTitle, $sTicketText) {

    $oShop = $this->_getShop();

    $oxConfig = $this->getConfig();
    $sUrl = $oxConfig->getShopURL() . 'admin/';

    //set mail params (from, fromName, smtp... )
    $this->_setMailParams( $oShop );
    $oLang = oxRegistry::getLang();

    $oSmarty = $this->_getSmarty();
    $this->setViewData("text", nl2br($sTicketText));
    $this->setViewData("url", $sUrl);

    // Process view data array through oxoutput processor
    $this->_processViewArray();

    $this->setRecipient( $oShop->oxshops__oxowneremail->value, $oShop->oxshops__oxname->getRawValue() );
    $this->setFrom( $oShop->oxshops__oxowneremail->value, $oShop->oxshops__oxname->getRawValue() );
    $this->setBody( $oSmarty->fetch( $oxConfig->getTemplatePath( $this->_sTicketNewAdminTemplatePlain, false ) ) );
    $this->setAltBody( "" );
    $this->setSubject($oLang->translateString('CC_TICKETSYSTEM_NEW_TICKET') . ': ' . $sTicketTitle);

    $blSend = $this->send();
    return $blSend;
  }

  /**
   * Sends an email to the admin informing him about a ticket update.
   *
   * @param string $sTicketTitle
   * @param string $sTicketText
   * @return bool
   */
  public function sendTicketUpdateToAdmin($sTicketTitle, $sTicketText) {

    $oShop = $this->_getShop();

    $oxConfig = $this->getConfig();
    $sUrl = $oxConfig->getShopURL() . 'admin/';

    //set mail params (from, fromName, smtp... )
    $this->_setMailParams( $oShop );
    $oLang = oxRegistry::getLang();

    $oSmarty = $this->_getSmarty();
    $this->setViewData("text", nl2br($sTicketText));
    $this->setViewData("url", $sUrl);

    // Process view data array through oxoutput processor
    $this->_processViewArray();

    $this->setRecipient( $oShop->oxshops__oxowneremail->value, $oShop->oxshops__oxname->getRawValue() );
    $this->setFrom( $oShop->oxshops__oxowneremail->value, $oShop->oxshops__oxname->getRawValue() );
    $this->setBody( $oSmarty->fetch( $oxConfig->getTemplatePath( $this->_sTicketUpdateAdminTemplatePlain, false ) ) );
    $this->setAltBody( "" );
    $this->setSubject($oLang->translateString('CC_TICKETSYSTEM_TICKET_UPDATE') . ': ' . $sTicketTitle);

    $blSend = $this->send();
    return $blSend;
  }

  /**
   * Sends an email to the user informing him about a ticket update.
   *
   * @param string $sTicketTitle
   * @param string $sTicketText
   * @return bool
   */
  public function sendTicketUpdateToUser($oTicket, $sTicketText) {

    $oShop = $this->_getShop();

    $oxConfig = $this->getConfig();
    $sUrl = $oxConfig->getShopURL() . '?cl=cc_account_tickets&amp;ticket=' . $oTicket->getId();

    // load user
    $oUser = oxNew('oxuser');
    $oUser->load($oTicket->cctickets__oxuserid->rawValue);
    $sFullName = $oUser->oxuser__oxfname->getRawValue() . " " . $oUser->oxuser__oxlname->getRawValue();

    //set mail params (from, fromName, smtp... )
    $this->_setMailParams( $oShop );
    $oLang = oxRegistry::getLang();

    $oSmarty = $this->_getSmarty();
    $this->setViewData("text", nl2br($sTicketText));
    $this->setViewData("fullname", $sFullName);
    $this->setViewData("url", $sUrl);

    // Process view data array through oxoutput processor
    $this->_processViewArray();

    $this->setRecipient( $oUser->oxuser__oxusername->value, $sFullName );
    $this->setFrom( $oShop->oxshops__oxowneremail->value, $oShop->oxshops__oxname->getRawValue() );
    $this->setBody( $oSmarty->fetch( $oxConfig->getTemplatePath( $this->_sTicketUpdateUserTemplatePlain, false ) ) );
    $this->setAltBody( "" );
    $this->setSubject($oLang->translateString('CC_TICKETSYSTEM_TICKET_UPDATE') . ': ' . $oTicket->cctickets__subject->rawValue);

    $blSend = $this->send();
    return $blSend;
  }
}