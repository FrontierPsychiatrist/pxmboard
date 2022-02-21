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
 * banner handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2007/06/02 22:23:46 $
 * @version $Revision: 1.8 $
 */
class cBanner{

	var $m_iId;							// banner id
	var $m_iBoardId;					// board id
	var $m_sBoardName;					// board name
	var $m_sBannerCode;					// banner code
	var $m_iStartTimestamp;				// banner starttimestamp
	var $m_iEndTimestamp;				// banner endtimestamp
	var $m_iViews;						// views
	var $m_iMaxViews;					// max views

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function cBanner(){

		$this->m_iId = 0;
		$this->m_iBoardId = 0;
		$this->m_sBannerCode = "";
		$this->m_sBoardName = "";
		$this->m_iStartTimestamp = 0;
		$this->m_iEndTimestamp = 0;
		$this->m_iViews = 0;
		$this->m_iMaxViews = 0;
	}

	/**
	 * get data from database by banner id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iBannerId banner id
	 * @return boolean success / failure
	 */
	function loadDataById($iBannerId){

		$bReturn = FALSE;
		$iBannerId = intval($iBannerId);

		if($iBannerId>0){

			global $objDb;

			if($objResultSet = &$objDb->executeQuery("SELECT ba_id,".
																	  "ba_boardid,".
																	  "ba_code,".
																	  "ba_start,".
																	  "ba_expiration,".
																	  "ba_views,".
																	  "ba_maxviews".
																	  " FROM pxm_banner".
																	  " WHERE ba_id=".$iBannerId)){
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
	 * get random data from database by board id and timestamp
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iTimestamp timestamp
	 * @param integer $iBoardId board id
	 * @return boolean success / failure
	 */
	function loadRandomData($iTimestamp,$iBoardId = 0){

		global $objDb;
		$bReturn = FALSE;
		$iBoardId = intval($iBoardId);
		$iTimestamp = intval($iTimestamp);

		if($objResultSet = &$objDb->executeQuery("SELECT ba_id,".
																  "ba_boardid,".
																  "ba_code,".
																  "ba_start,".
																  "ba_expiration,".
																  "ba_views,".
																  "ba_maxviews".
																  " FROM pxm_banner".
																  " WHERE ba_boardid IN (".($iBoardId>0?$iBoardId.",-1,-2":"0,-1").")".
																  " AND ba_start<$iTimestamp AND (ba_expiration<1 OR $iTimestamp<ba_expiration)".
																  " AND (ba_maxviews<1 OR ba_views<ba_maxviews)")){
			$iNumRows = $objResultSet->getNumRows();
			if($iNumRows>0){
				if($iNumRows>1){
					$objResultSet->setResultPointer(mt_rand(0,$iNumRows-1));
				}
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

		$this->m_iId = intval($objResultRow->ba_id);
		$this->m_iBoardId = intval($objResultRow->ba_boardid);
		$this->m_sBannerCode = $objResultRow->ba_code;
		$this->m_iStartTimestamp = intval($objResultRow->ba_start);
		$this->m_iEndTimestamp = intval($objResultRow->ba_expiration);
		$this->m_iViews = intval($objResultRow->ba_views);
		$this->m_iMaxViews = intval($objResultRow->ba_maxviews);

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

		if($this->m_iBoardId>-3){
			if(!empty($this->m_sBannerCode)){

				global $objDb;

				if($objResultset = &$objDb->executeQuery("INSERT INTO pxm_banner (ba_boardid,ba_code,ba_start,ba_expiration,ba_maxviews)".
																   " VALUES ($this->m_iBoardId,'".addslashes($this->m_sBannerCode)."',$this->m_iStartTimestamp,$this->m_iEndTimestamp,$this->m_iMaxViews)")){
					if($objResultset->getAffectedRows()>0){
						return TRUE;
					}
				}
			}
		}
		return FALSE;
	}


	/**
	 * update data in database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean success / failure
	 */
	function updateData(){

		if($this->m_iId>0){
			if(!empty($this->m_sBannerCode)){

				global $objDb;

				if($objResultset = &$objDb->executeQuery("UPDATE pxm_banner SET ba_boardid=$this->m_iBoardId,".
																						"ba_code='".addslashes($this->m_sBannerCode)."',".
																						"ba_start=$this->m_iStartTimestamp,".
																						"ba_expiration=$this->m_iEndTimestamp,".
																						"ba_maxviews=$this->m_iMaxViews".
																						" WHERE ba_id=".$this->m_iId)){
					if($objResultset->getAffectedRows()>0){
						return TRUE;
					}
				}
			}
		}
		return FALSE;
	}

	/**
	 * delete data from database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean success / failure
	 */
	function deleteData(){

		if($this->m_iId>0){

			global $objDb;

			if($objResultset = &$objDb->executeQuery("DELETE FROM pxm_banner WHERE ba_id=".$this->m_iId)){
				if($objResultset->getAffectedRows()>0){
					return TRUE;
				}
			}
		}
		return FALSE;
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
	 * get board id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer board id
	 */
	function getBoardId(){
		return $this->m_iBoardId;
	}

	/**
	 * set board id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iBoardId board id
	 * @return void
	 */
	function setBoardId($iBoardId){
		$this->m_iBoardId = intval($iBoardId);
	}

	/**
	 * get board name
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string board name
	 */
	function getBoardName(){
		return $this->m_sBoardName;
	}

	/**
	 * set board name
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sBoardName board name
	 * @return void
	 */
	function setBoardName($sBoardName){
		$this->m_sBoardName = $sBoardName;
	}

	/**
	 * get bannercode
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string bannercode
	 */
	function getBannerCode(){
		return $this->m_sBannerCode;
	}

	/**
	 * set bannercode
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sBannerCode bannercode
	 * @return void
	 */
	function setBannerCode($sBannerCode){
		$this->m_sBannerCode = $sBannerCode;
	}

	/**
	 * get starttimestamp
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer starttimestamp
	 */
	function getStartTimestamp(){
		return $this->m_iStartTimestamp;
	}

	/**
	 * set starttimestamp
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iStartTimestamp starttimestamp
	 * @return void
	 */
	function setStartTimestamp($iStartTimestamp){
		$this->m_iStartTimestamp = intval($iStartTimestamp);
	}

	/**
	 * get endtimestamp
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer endtimestamp
	 */
	function getEndTimestamp(){
		return $this->m_iEndTimestamp;
	}

	/**
	 * set endtimestamp
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iEndTimestamp endtimestamp
	 * @return void
	 */
	function setEndTimestamp($iEndTimestamp){
		$this->m_iEndTimestamp = intval($iEndTimestamp);
	}

	/**
	 * get views
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer views
	 */
	function getViews(){
		return $this->m_iViews;
	}

	/**
	 * set views
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iViews views
	 * @return void
	 */
	function setViews($iViews){
		$this->m_iViews = intval($iViews);
	}

	/**
	 * get max views
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer max views
	 */
	function getMaxViews(){
		return $this->m_iMaxViews;
	}

	/**
	 * set max views
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iMaxViews max views
	 * @return void
	 */
	function setMaxViews($iMaxViews){
		$this->m_iMaxViews = intval($iMaxViews);
	}

	/**
	 * get membervariables as array
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array member variables
	 */
	function getDataArray(){

		if($this->m_iMaxViews>0){
			global $objDb;
			$objDb->executeQuery("UPDATE pxm_banner SET ba_views=ba_views+1 WHERE ba_id=".$this->m_iId);
		}

		return array("_code" => $this->m_sBannerCode);
	}
}
?>