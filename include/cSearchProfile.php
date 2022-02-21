<?php
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
 * searchprofile handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/04/09 09:14:05 $
 * @version $Revision: 1.7 $
 */
class cSearchProfile{

	var $m_iId;							// search id
	var $m_iIdUser;						// who started the search?
	var $m_sSearchMessage;				// message search string
	var $m_sSearchUser;					// user search string
	var $m_arrBoardIds;					// search in this boards
	var $m_iSearchDays;					// timespan of the search (last x days)
	var $m_iSearchTimestamp;			// date of the search

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function cSearchProfile(){
		$this->m_iId = 0;
		$this->m_iIdUser = 0;
		$this->m_sSearchMessage = "";
		$this->m_sSearchUser = "";
		$this->m_arrBoardIds = array();
		$this->m_iSearchDays = 0;
		$this->m_iSearchTimestamp = 0;
	}

	/**
	 * get data from database by search id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iSearchId search id
	 * @return boolean success / failure
	 */
	function loadDataById($iSearchId){

		$bReturn = FALSE;
		$iSearchId = intval($iSearchId);

		if($iSearchId>0){

			global $objDb;

			if($objResultSet = &$objDb->executeQuery("SELECT se_id,se_userid,se_message,se_nickname,se_boardids,se_days,se_tstmp FROM pxm_search WHERE se_id=".$iSearchId)){
				if($objResultRow = $objResultSet->getNextResultRowObject()){
					$bReturn = $this->_setDataFromDb($objResultRow);
				}
				$objResultSet->freeResult();
				unset($objResultSet);
			}
		}
		return $bReturn;
	}

	/**
	 * initalize the member variables with the resultset from the db
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param object $objResultRow resultrow from db query
	 * @return boolean success / failure
	 */
	function _setDataFromDb(&$objResultRow){

		$this->m_iId = intval($objResultRow->se_id);
		$this->m_iIdUser = intval($objResultRow->se_userid);
		$this->m_sSearchMessage = $objResultRow->se_message;
		$this->m_sSearchUser = $objResultRow->se_nickname;
		$this->m_arrBoardIds = explode(",",$objResultRow->se_boardids);
		$this->m_iSearchDays = intval($objResultRow->se_days);
		$this->m_iSearchTimestamp = intval($objResultRow->se_tstmp);

		return TRUE;
	}

	/**
	 * insert new data into database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean success / failure
	 */
	function insertData(){

		global $objDb;

		if($objResultSet = &$objDb->executeQuery("INSERT INTO pxm_search (se_userid,se_message,se_nickname,se_boardids,se_days,se_tstmp)".
													  " VALUES ($this->m_iIdUser,".
													  			$objDb->quote($this->m_sSearchMessage).",".
																$objDb->quote($this->m_sSearchUser).",".
																$objDb->quote(implode(",",$this->m_arrBoardIds)).",".
																$this->m_iSearchDays.",".
																$this->m_iSearchTimestamp.")")){
			if($objResultSet->getAffectedRows()>0){
				$this->m_iId = intval($objDb->getInsertId("pxm_search","se_id"));
			}
		}

		// delete searchqueries older than 30 days
		if(mt_rand(1,10) == 5){
			$objDb->executeQuery("DELETE FROM pxm_search WHERE se_tstmp<".($this->m_iSearchTimestamp-86400*30));
		}
		return TRUE;
	}

	/**
	 * delete a user from the database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean success / failure
	 */
	function deleteData(){

		global $objDb;
		$bReturn = FALSE;

		if($objResultSet = &$objDb->executeQuery("DELETE FROM pxm_search WHERE se_id=".$this->m_iId)){
			if($objResultSet->getAffectedRows()>0){
				$bReturn = TRUE;
			}
		}
		return $bReturn;
	}

	/**
	 * get id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer id
	 */
	function getId(){
		return $this->m_iId;
	}

	/**
	 * set id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iId id
	 * @return void
	 */
	function setId($iId){
		$this->m_iId = intval($iId);
	}

	/**
	 * get user id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer user id
	 */
	function getIdUser(){
		return $this->m_iIdUser;
	}

	/**
	 * set user id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iIdUser user id
	 * @return void
	 */
	function setIdUser($iIdUser){
		$this->m_iIdUser = intval($iIdUser);
	}

	/**
	 * get message search string
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string message search string
	 */
	function getSearchMessage(){
		return $this->m_sSearchMessage;
	}

	/**
	 * set message search string
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sSearchMessage message search string
	 * @return void
	 */
	function setSearchMessage($sSearchMessage){
		$this->m_sSearchMessage = $sSearchMessage;
	}

	/**
	 * get user search string
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string user search string
	 */
	function getSearchUser(){
		return $this->m_sSearchUser;
	}

	/**
	 * set user search string
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sSearchUser user search string
	 * @return void
	 */
	function setSearchUser($sSearchUser){
		$this->m_sSearchUser = $sSearchUser;
	}

	/**
	 * get the boards to be searched
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array the boards to be searched
	 */
	function getBoardIds(){
		return $this->m_arrBoardIds;
	}

	/**
	 * set the boards to be searched
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param array $arrBoardIds the boards to be searched
	 * @return void
	 */
	function setBoardIds($arrBoardIds){
		$this->m_arrBoardIds = $arrBoardIds;
	}

	/**
	 * get the timespan of the search (last x days)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer timespan of the search (last x days)
	 */
	function getSearchDays(){
		return $this->m_iSearchDays;
	}

	/**
	 * set the timespan of the search (last x days)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iSearchDays timespan of the search (last x days)
	 * @return void
	 */
	function setSearchDays($iSearchDays){
		$this->m_iSearchDays = intval($iSearchDays);
	}

	/**
	 * get the date of the search
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer the date of the search
	 */
	function getTimestamp(){
		return $this->m_iSearchTimestamp;
	}

	/**
	 * set the date of the search
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iSearchTimestamp the date of the search
	 * @return void
	 */
	function setTimestamp($iSearchTimestamp){
		$this->m_iSearchTimestamp = intval($iSearchTimestamp);
	}

	/**
	 * get membervariables as array
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iTimeOffset time offset in seconds
	 * @param string $sDateFormat php date format
	 * @return array member variables
	 */
	function getDataArray($iTimeOffset,$sDateFormat){
		return array("id"			=>	$this->m_iId,
					 "userid"		=>	$this->m_iIdUser,
					 "searchstring"	=>	$this->m_sSearchMessage,
					 "nickname"		=>	$this->m_sSearchUser,
					 "days"			=>	$this->m_iSearchDays,
					 "date"			=>	(($this->m_iSearchTimestamp>0)?date($sDateFormat,($this->m_iSearchTimestamp+$iTimeOffset)):0));
	}
}
?>