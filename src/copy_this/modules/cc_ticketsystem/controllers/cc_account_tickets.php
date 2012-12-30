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
   * List with all users tickets.
   *
   * @var array
   */
  protected $_aTicketList  = null;

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

        $this->_aViewData['ticket'] = $ticket;
        $this->_aViewData['ticket_texts'] = $ticket->getTextList();

        if(isset($_GET['update']) && $_GET['update']) {
          $this->_aViewData['update'] = true;
        }

        return $this->_sThisTicketTemplate;
      }
    }

    $this->_aViewData['tickets'] = $oUser->getTickets();

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

    if(isset($this->_aViewData['ticket'])) {
      $aPath['title'] = $this->_aViewData['ticket']->cctickets__subject->rawValue;
      $aPath['link'] = $this->getLink() . "&ticket=" . $this->_aViewData['ticket']->cctickets__oxid->rawValue;
      $aPaths[] = $aPath;
    }

    return $aPaths;
  }

  /**
   * Creates a new ticket and informs the admin, if wished.
   *
   * @return string
   */
  public function newTicket() {

    $sSubject = trim((string)oxConfig::getParameter('ticketsubject', true));
    $sText = trim((string)oxConfig::getParameter('tickettext', true));
    $sTimestamp = date('Y-m-d H:i:s');
    $sShopId     = $this->getConfig()->getShopId();
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

    $oxConfig = oxConfig::getInstance();
    $sModule = oxConfig::OXMODULE_MODULE_PREFIX . $this->_sModuleId;
    if($oxConfig->getShopConfVar('sendmail', $sShopId, $sModule)) {
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

    $sText = trim((string)oxConfig::getParameter('tickettext', true));
    $sTicketId = trim((string)oxConfig::getParameter('tid', true));
    $sTimestamp = date('Y-m-d H:i:s');
    $sShopId     = $this->getConfig()->getShopId();
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

    $oxConfig = oxConfig::getInstance();
    $sModule = oxConfig::OXMODULE_MODULE_PREFIX . $this->_sModuleId;
    if($oxConfig->getShopConfVar('sendmail', $sShopId, $sModule)) {
      $oEmail = oxNew('cc_oxemail');
      $oEmail->sendTicketUpdateToAdmin($oTicket->cctickets__subject->rawValue, $sText);
    }

    return '?cl=' . __CLASS__ . '&ticket=' . $sTicketId;
  }
}