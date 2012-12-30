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

class cc_oxuser extends cc_oxuser_parent {

  /**
   * Selects all tickets for the active user and prepares them for template output.
   *
   * @return array with user tickets
   */
  public function getTickets() {

    $sSelect = "SELECT * FROM cctickets WHERE OXUSERID = '".$this->_sOXID."'";
    $sSelect .= " ORDER BY UPDATED DESC";

    $oTicketList = oxNew('oxList');
    $oTicketList->init('cc_ticket');
    $oTicketList->selectString($sSelect);

    $aTicketList = $oTicketList->getArray();

    $aTickets = array();

    foreach($aTicketList as $oTicket) {
      $sTicketId = $oTicket->cctickets__oxid->rawValue;
      $aTickets[$sTicketId]['ticketid'] = $sTicketId;
      $aTickets[$sTicketId]['subject'] = $oTicket->cctickets__subject->rawValue;
      $aTickets[$sTicketId]['state'] = $this->_getStateText($oTicket->cctickets__state->rawValue);
      $aTickets[$sTicketId]['updated'] = $oTicket->cctickets__updated->rawValue;
    }

    return $aTickets;
  }

  /**
   * Selects the right output text depending on ticket state.
   *
   * @param integer $iState ticket state
   * @return string
   */
  protected function _getStateText($iState) {

    $oTicket = oxNew('cc_ticket');

    switch($iState) {
      case $oTicket::STATE_USER_ACTION: return "CC_TICKETSYSTEM_STATE_USER";
      case $oTicket::STATE_ADMIN_ACTION: return "CC_TICKETSYSTEM_STATE_ADMIN";
      case $oTicket::STATE_CLOSED: return "CC_TICKETSYSTEM_STATE_CLOSED";
    }
  }
}