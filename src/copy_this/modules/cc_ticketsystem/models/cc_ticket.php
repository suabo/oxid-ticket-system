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

class cc_ticket extends oxBase {

  // defining ticket state constants
  /**
   * Support answer required.
   * @var int
   */
  const STATE_ADMIN_ACTION = 1;

  /**
   * Awaiting customer response.
   * @var int
   */
  const STATE_USER_ACTION = 2;

  /**
   * Ticket closed.
   * @var int
   */
  const STATE_CLOSED = 3;

  // defining author state constants
  /**
   * Admin
   * @var string
   */
  const AUTHOR_ADMIN = "admin";

  /**
   * User
   * @var string
   */
  const AUTHOR_USER = "user";

  /**
   * Object core table name
   *
   * @var string
   */
  protected $_sCoreTable = 'cctickets';

  /**
   * Current class name
   *
   * @var string
   */
  protected $_sClassName = 'cc_ticket';

  /**
   * Current module name
   *
   * @var string
   */
  protected $_sModuleId = 'cc_ticketsystem';

  /**
   * Array with ticket texts.
   *
   * @var array
   */
  protected $_aTextList = null;

  /**
   * User real fullname.
   *
   * @var string
   */
  protected $_sUserFullName = '';

  /**
   * Support display name.
   *
   * @var string
   */
  protected $_sSupportDisplay = '';

  /**
   * Initialises the instance
   *
   * @return null
   */
  public function __construct() {

    parent::__construct();
    $this->init();
  }

  /**
   * Selects all texts for the requested ticket.
   *
   * @return array with ticket texts
   */
  public function getTextList() {

    if ( $this->_aTextList !== null ) {
      return $this->_aTextList;
    }

    $oUser = oxNew('oxUser');
    $oUser->load($this->cctickets__oxuserid->rawValue);
    $this->_sUserFullName = $oUser->oxuser__oxfname->rawValue . ' ' . $oUser->oxuser__oxlname->rawValue;

    $oxConfig = $this->getConfig();
    $sShopId = $oxConfig->getShopId();
    $sModule = $oxConfig::OXMODULE_MODULE_PREFIX . $this->_sModuleId;
    $this->_sSupportDisplay = $oxConfig->getShopConfVar('ccSupportname', $sShopId, $sModule);

    $sSelect = "SELECT * FROM cctickettexts WHERE TICKETID = '".$this->_sOXID."'";
    $sSelect .= " ORDER BY TIMESTAMP";

    $oTextList = oxNew('oxList');
    $oTextList->init('cc_tickettext');
    $oTextList->selectString($sSelect);
    $this->_aTextList = $this->_prepareForTemplate($oTextList->getArray());

    return $this->_aTextList;
  }

  /**
   * Prepares ticket texts for output.
   *
   * @param array $aTextList raw texts objects
   * @return type
   */
  protected function _prepareForTemplate($aTextList) {

    $aTexts = array();

    foreach($aTextList as $oText) {
      $aTexts[$oText->cctickettexts__oxid->rawValue]['text'] = nl2br($oText->cctickettexts__text->rawValue);
      $aTexts[$oText->cctickettexts__oxid->rawValue]['timestamp'] = $oText->cctickettexts__timestamp->rawValue;
      $aTexts[$oText->cctickettexts__oxid->rawValue]['author'] = $this->_getAuthorName($oText->cctickettexts__author->rawValue);
      $aTexts[$oText->cctickettexts__oxid->rawValue]['image'] = $this->_getAuthorPicture($oText->cctickettexts__author->rawValue);
    }

    return $aTexts;
  }

  /**
   * Prepares author name for template. Full name for user.
   *
   * @param string $name standard name
   * @return string
   */
  protected function _getAuthorName($name) {

    if($name == self::AUTHOR_USER) {
      return $this->_sUserFullName;
    }

    elseif($name == self::AUTHOR_ADMIN) {
      return $this->_sSupportDisplay;
    }

    return $name;
  }

  /**
   * Prepares author picture for template.
   *
   * @param string $name standard name
   * @return string
   */
  protected function _getAuthorPicture($name) {

    $baseUrl = $this->getConfig()->getShopUrl() . "modules/cc_ticketsystem/out/img/";

    if($name == self::AUTHOR_USER) {
      $oUser = oxNew('oxUser');
      $oUser->load($this->cctickets__oxuserid->rawValue);
      if($oUser->oxuser__oxsal->rawValue == "MRS") {
        return $baseUrl . "user_female.png";
      }
      else {
        return $baseUrl . "user_male.png";
      }
    }

    elseif($name == self::AUTHOR_ADMIN) {
      return $baseUrl . "admin.png";
    }

    return "";
  }
}