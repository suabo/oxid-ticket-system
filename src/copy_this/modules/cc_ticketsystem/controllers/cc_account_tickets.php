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

class cc_account_tickets extends Account {

  /**
   * Template for creating new tickets.
   *
   * @var string
   */
  protected $_sThisNewTemplate = 'cc_account_ticket_new.tpl';

  /**
   * Template for detailed ticket view.
   *
   * @var string
   */
  protected $_sThisTicketTemplate = 'cc_account_ticket.tpl';

  /**
   * Template for overview of all tickets.
   *
   * @var string
   */
  protected $_sThisOverviewTemplate = 'cc_account_overview.tpl';

  /**
   * Current module name
   *
   * @var string
   */
  protected $_sModuleId = 'cc_ticketsystem';

  /**
   * Active user ticket.
   *
   * @var cc_ticket
   */
  protected $_oTicket = null;

  /**
   * List with all users tickets.
   *
   * @var array
   */
  protected $_aTicketList = null;

  /**
   * Returns the right template and prepares the view data.
   *
   * @return string current template file name
   */
  public function render() {

    parent::render();

    // is logged in ?
    if(!$oUser = $this->getUser()) {
      return $this->_sThisLoginTemplate;
    }

    // ticket id set ?
    if(isset($_GET['ticket'])) {

      // user created ticket ?
      if($_GET['ticket'] == 'new') {
        return $this->_sThisNewTemplate;
      }

      $ticket = oxNew('cc_ticket');
      $ticket->load($_GET['ticket']);

      // user created ticket ?
      if($ticket->cctickets__oxuserid->rawValue == $oUser->getId()) {

        $this->_oTicket = $ticket;

        return $this->_sThisTicketTemplate;
      }
    }

    $this->_aTicketList = $oUser->getTickets();

    return $this->_sThisOverviewTemplate;
  }

  /**
   * Returns Bread Crumb - you are here page1/page2/page3...
   *
   * @return array
   */
  public function getBreadCrumb() {

    $aPaths = array();
    $aPath  = array();

    $aPath['title'] = oxRegistry::getLang()->translateString( 'MY_ACCOUNT', oxRegistry::getLang()->getBaseLanguage(), false );
    $aPath['link']  =  oxRegistry::get("oxSeoEncoder")->getStaticUrl( $this->getViewConfig()->getSelfLink() . "cl=account" );
    $aPaths[] = $aPath;

    $aPath['title'] = oxRegistry::getLang()->translateString( 'CC_TICKETSYSTEM_MY_TICKETS', oxRegistry::getLang()->getBaseLanguage(), false );
    $aPath['link']  = $this->getLink();
    $aPaths[] = $aPath;

    if($this->_oTicket != null) {
      $aPath['title'] = $this->_oTicket->cctickets__subject->rawValue;
      $aPath['link'] = $this->getLink() . "&ticket=" . $this->_oTicket->cctickets__oxid->rawValue;
      $aPaths[] = $aPath;
    }

    return $aPaths;
  }

  /**
   * Returns ticket ID for template output.
   *
   * @return string
   */
  public function getTicketOxid() {
    return $this->_oTicket->cctickets__oxid->rawValue;
  }

  /**
   * Returns ticket subject for template output.
   *
   * @return string
   */
  public function getTicketSubject() {
    return $this->_oTicket->cctickets__subject->rawValue;
  }

  /**
   * Returns ticket state for template output.
   *
   * @return string
   */
  public function getTicketState() {
    return $this->_oTicket->cctickets__state->rawValue;
  }

  /**
   * Returns ticket texts for template output.
   *
   * @return array
   */
  public function getTicketTexts() {
    return $this->_oTicket->getTextList();
  }

  /**
   * Returns an array with all ticket of active user for template output.
   *
   * @return array
   */
  public function getTicketList() {
    return $this->_aTicketList;
  }

  /**
   * Creates a new ticket and informs the admin, if wished.
   *
   * @return string
   */
  public function newTicket() {

    $oxConfig = $this->getConfig();

    $sSubject = trim((string)$oxConfig->getParameter('ticketsubject', true));
    $sText = trim((string)$oxConfig->getParameter('tickettext', true));
    $sTimestamp = date('Y-m-d H:i:s');
    $sShopId     = $oxConfig->getShopId();
    $sUserId     = oxSession::getVar( 'usr' );

    if(!$sUserId) {
      oxRegistry::get("oxUtilsView")->addErrorToDisplay('CC_TICKETSYSTEM_ERROR_USER');
      return '?cl=' . __CLASS__;
    }

    if(!$sShopId) {
      oxRegistry::get("oxUtilsView")->addErrorToDisplay('CC_TICKETSYSTEM_ERROR_SHOP');
      return '?cl=' . __CLASS__;
    }

    if($sSubject == '' || $sText == '') {
      oxRegistry::get("oxUtilsView")->addErrorToDisplay('CC_TICKETSYSTEM_ERROR_EMPTY');
      return '?cl=' . __CLASS__ . '&ticket=new';
    }

    $oTicket = oxNew('cc_ticket');
    $oTicket->cctickets__oxuserid = new oxField($sUserId);
    $oTicket->cctickets__subject = new oxField($sSubject);
    $oTicket->cctickets__state = new oxField($oTicket::STATE_ADMIN_ACTION);
    $oTicket->cctickets__created = new oxField($sTimestamp);
    $oTicket->cctickets__updated = new oxField($sTimestamp);
    $oTicket->save();

    $oTicketText = oxNew('cc_tickettext');
    $oTicketText->cctickettexts__ticketid  = new oxField($oTicket->getId());
    $oTicketText->cctickettexts__text      = new oxField($sText);
    $oTicketText->cctickettexts__timestamp = new oxField($sTimestamp);
    $oTicketText->cctickettexts__author    = new oxField($oTicket::AUTHOR_USER);
    $oTicketText->save();

    $sModule = $oxConfig::OXMODULE_MODULE_PREFIX . $this->_sModuleId;
    if($oxConfig->getShopConfVar('ccSendmail', $sShopId, $sModule)) {
      $oEmail = oxNew('cc_oxemail');
      $oEmail->sendNewTicketToAdmin($sSubject, $sText);
    }

    return '?cl=' . __CLASS__ . '&ticket=' . $oTicket->getId();
  }

  /**
   * Updates the selected ticket and informs the admin, if wished.
   *
   * @return string
   */
  public function updateTicket() {

    $oxConfig = $this->getConfig();

    $sText = trim((string)$oxConfig->getParameter('tickettext', true));
    $sTicketId = trim((string)$oxConfig->getParameter('tid', true));
    $sTimestamp = date('Y-m-d H:i:s');
    $sShopId     = $oxConfig->getShopId();
    $sUserId     = oxSession::getVar( 'usr' );

    if(!$sUserId) {
      oxRegistry::get("oxUtilsView")->addErrorToDisplay('CC_TICKETSYSTEM_ERROR_USER');
      return '?cl=' . __CLASS__;
    }

    if(!$sShopId) {
      oxRegistry::get("oxUtilsView")->addErrorToDisplay('CC_TICKETSYSTEM_ERROR_SHOP');
      return '?cl=' . __CLASS__;
    }

    if($sText == '') {
      oxRegistry::get("oxUtilsView")->addErrorToDisplay('CC_TICKETSYSTEM_ERROR_EMPTY');
      return '?cl=' . __CLASS__ . '&update=true&ticket=' . $sTicketId;
    }

    $oTicket = oxNew('cc_ticket');
    $oTicket->load($sTicketId);
    $oTicket->cctickets__state   = new oxField($oTicket::STATE_ADMIN_ACTION);
    $oTicket->cctickets__updated = new oxField($sTimestamp);
    $oTicket->save();

    $oTicketText = oxNew('cc_tickettext');
    $oTicketText->cctickettexts__ticketid  = new oxField($sTicketId);
    $oTicketText->cctickettexts__text      = new oxField($sText);
    $oTicketText->cctickettexts__timestamp = new oxField($sTimestamp);
    $oTicketText->cctickettexts__author    = new oxField($oTicket::AUTHOR_USER);
    $oTicketText->save();

    $sModule = $oxConfig::OXMODULE_MODULE_PREFIX . $this->_sModuleId;
    if($oxConfig->getShopConfVar('ccSendmail', $sShopId, $sModule)) {
      $oEmail = oxNew('cc_oxemail');
      $oEmail->sendTicketUpdateToAdmin($oTicket->cctickets__subject->rawValue, $sText);
    }

    return '?cl=' . __CLASS__ . '&ticket=' . $sTicketId;
  }
}