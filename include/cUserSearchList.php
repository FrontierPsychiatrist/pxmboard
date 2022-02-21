<?php
require_once(INCLUDEDIR."/cScrollList.php");
/**
 * PXMBoard Forum software
 * Copyright (C) 2001 by Torsten Rentsch <forum@torsten-rentsch.de>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307
 * USA
 */

/**
 * user search list handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:48 $
 * @version $Revision: 1.5 $
 */
class cUserSearchList extends cScrollList{

	var $m_sNickName;			// nickname

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sNickName nickname
	 * @return void
	 */
	function __construct($sNickName){

		$this->m_sNickName = $sNickName;

		parent::__construct();
	}

	/**
	 * get the query
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return string query
	 */
	function _getQuery(){
		return "SELECT u_id,u_nickname,u_highlight,u_status FROM pxm_user WHERE u_nickname LIKE'".addslashes($this->m_sNickName)."%' ORDER BY u_nickname ASC";
	}

	/**
	 * initalize the member variables with the resultrow from the db
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param object $objResultRow resultrow from db query
	 * @return boolean success / failure
	 */
	function _setDataFromDb(&$objResultRow){

		$this->m_arrResultList[] = array("id"		=>$objResultRow->u_id,
										 "nickname"	=>$objResultRow->u_nickname,
										 "highlight"=>$objResultRow->u_highlight,
										 "status"	=>$objResultRow->u_status);
	}
}
?>