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
 * message search list handling
 * TODO: make this work for PostgreSQL!
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/04/08 21:23:21 $
 * @version $Revision: 1.15 $
 */
class cMessageSearchList extends cScrollList{

	var $m_arrBoardIds;			// board ids
	var $m_sNickName;			// nickname
	var $m_sSearchString;		// search string
	var $m_iSearchDays;			// timespan of the search (last x days)
	var $m_iSearchTimestamp;	// timestamp of this search
	var $m_iTimeOffset;			// time offset
	var $m_sDateFormat;			// date format

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param object $objSearch search config
	 * @param integer $iTimeOffset time offset
	 * @param string $sDateFormat date format
	 * @return void
	 */
	function cMessageSearchList(&$objSearch,$iTimeOffset,$sDateFormat){

		$this->m_arrBoardIds = array();
		foreach($objSearch->getBoardIds() as $iBoardId){
			$iBoardId = intval($iBoardId);
			if($iBoardId>0){
				$this->m_arrBoardIds[] = $iBoardId;
			}
		}
		$this->m_sNickName = $objSearch->getSearchUser();
		$this->m_sSearchString = $objSearch->getSearchMessage();
		$this->m_iSearchDays = intval($objSearch->getSearchDays());
		$this->m_iSearchTimestamp = intval($objSearch->getTimestamp());
		$this->m_iTimeOffset = intval($iTimeOffset);
		$this->m_sDateFormat = $sDateFormat;

		cScrollList::cScrollList();
	}

	/**
	 * do the query initializaton stuff here
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return void
	 */
	function _doPreQuery(){

		global $objDb;

		$sMatchQuery = "";
		if(!empty($this->m_sSearchString)){
			$sMatchQuery .= "MATCH (m_subject,m_body) AGAINST(".$objDb->quote($this->m_sSearchString);
			if(strnatcmp($objDb->getDBVersion(),"4.0.1")>=0){
				$sMatchQuery .= " IN BOOLEAN MODE";
			}
			$sMatchQuery .= ")";
		}
		else {
			$sMatchQuery .= "0";
		}

		$sQuery =  "SELECT m_id AS tmp_id, m_tstmp AS tmp_tstmp, ROUND($sMatchQuery,2) AS tmp_score FROM pxm_board,pxm_thread,pxm_message WHERE b_id=t_boardid AND t_id=m_threadid AND b_active=1";
		if($this->m_iSearchDays>0) {
		    $sQuery .= " AND m_tstmp>".($this->m_iSearchTimestamp - $this->m_iSearchDays*86400 + $this->m_iTimeOffset);
		}
		if(!empty($this->m_sNickName)){
			$sQuery .= " AND m_usernickname LIKE ".$objDb->quote($this->m_sNickName."%");
		}
		if(!empty($this->m_arrBoardIds)){
			$sQuery .= " AND t_boardid IN (".implode(",",$this->m_arrBoardIds).")";
		}
		if(!empty($this->m_sSearchString)){
			$sQuery .= " AND ".$sMatchQuery;
		}
		$sQuery .= " LIMIT 501";	// only 500 hits allowed, if there are 501 a error message will be displayed
		$objDb->executeQuery("CREATE TEMPORARY TABLE pxm_tmp_search (PRIMARY KEY(tmp_id), KEY(tmp_score,tmp_tstmp)) ENGINE=HEAP ".$sQuery);
	}

	/**
	 * get the query.
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return string query
	 */
	function _getQuery(){
		return "SELECT m_id,".
					"m_threadid,".
					"t_boardid,".
					"m_subject,".
					"m_userid,".
					"m_usernickname,".
					"m_userhighlight,".
					"tmp_score,".
					"tmp_tstmp ".
					"FROM pxm_tmp_search tmp,pxm_message,pxm_thread ".
					"WHERE m_id=tmp_id AND t_id=m_threadid ".
					"ORDER BY tmp_score DESC,tmp_tstmp DESC";
	}

	/**
	 * do the query shutdown stuff here
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return void
	 */
	function _doPostQuery(){

		global $objDb;

		if($objResultSet = &$objDb->executeQuery("SELECT count(*) AS cou FROM pxm_tmp_search")){
			if($objResultRow = $objResultSet->getNextResultRowObject()){
				$this->m_iItemCount = $objResultRow->cou;
			}
		}
		$objDb->executeQuery("DROP TABLE pxm_tmp_search");
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

		$this->m_arrResultList[] = array("id"		=>$objResultRow->m_id,
										 "threadid"	=>$objResultRow->m_threadid,
										 "boardid"	=>$objResultRow->t_boardid,
										 "subject"	=>$objResultRow->m_subject,
										 "score"	=>$objResultRow->tmp_score,
										 "date"		=>(($objResultRow->tmp_tstmp>0)?date($this->m_sDateFormat,($objResultRow->tmp_tstmp + $this->m_iTimeOffset)):0),
										 "user"		=>array("id"		=>$objResultRow->m_userid,
								  							"nickname"	=>$objResultRow->m_usernickname,
								  							"highlight"	=>$objResultRow->m_userhighlight));
	}
}
?>