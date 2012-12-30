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

class cc_tickettext extends oxBase {

  /**
   * Object core table name
   *
   * @var string
   */
  protected $_sCoreTable = 'cctickettexts';

  /**
   * Current class name
   *
   * @var string
   */
  protected $_sClassName = 'cc_tickettext';

  /**
   * Initialises the instance
   *
   * @return null
   */
  public function __construct() {

    parent::__construct();
    $this->init();
  }
}
