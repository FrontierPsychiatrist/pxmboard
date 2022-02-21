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
 * user online list handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:48 $
 * @version $Revision: 1.6 $
 */
class cUserOnlineList extends cScrollList{

	var $m_bAdminMode;			// query in adminmode?
	var $m_iOnlineTimestamp;	// online timestamp

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bAdminMode query in adminmode?
	 * @param integer $iOnlineTimestamp online timestamp
	 * @return void
	 */
	function __construct($bAdminMode, $iOnlineTimestamp){

		$this->m_bAdminMode = $bAdminMode?TRUE:FALSE;
		$this->m_iOnlineTimestamp = intval($iOnlineTimestamp);

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
		return "SELECT u_id,u_nickname,u_highlight,u_status FROM pxm_user WHERE ".($this->m_bAdminMode?"":"u_visible=1 AND ")."u_lastonlinetstmp>$this->m_iOnlineTimestamp ORDER BY u_nickname ASC";
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

	/**
	 * count visible / invisible
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array data
	 */
	function getVisibilityDataArray(){

		global $objDb;

		$arrTmp = array("all"=>"0","visible"=>"0","invisible"=>"0");

		if($objResultSet = &$objDb->executeQuery("SELECT u_visible,count(*) AS anz  FROM pxm_user WHERE u_lastonlinetstmp>$this->m_iOnlineTimestamp GROUP BY u_visible")){
			$iAll = 0;
			while($objResultRow = $objResultSet->getNextResultRowObject()){
				$iAll += intval($objResultRow->anz);
				if($objResultRow->u_visible){
					$arrTmp["visible"] = strval($objResultRow->anz);
				}
				else{
					$arrTmp["invisible"] = strval($objResultRow->anz);
				}
			}
			$arrTmp["all"] = strval($iAll);
		}
		return $arrTmp;
	}
}
?>