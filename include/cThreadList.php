<?php
require_once(INCLUDEDIR."/cScrollList.php");
require_once(INCLUDEDIR."/cThreadHeader.php");
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
 * threadlist handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.10 $
 */
class cThreadList extends cScrollList{

	var $m_iBoardId;			// board id
	var $m_sSortMode;			// sort mode
	var $m_sSortDirection;		// sort direction
	var $m_iTimeSpan;			// timespan

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iBoardId board id
	 * @param string $sSortMode sort mode
	 * @param integer $iTimeSpan timespan
	 * @return void
	 */
	function cThreadList($iBoardId,$sSortMode,$iTimeSpan){

		$this->m_sSortDirection = "DESC";

		switch($sSortMode){
			case "thread": 	$this->m_sSortMode = "m_tstmp";
							break;
			case "last": 	$this->m_sSortMode = "t_lastmsgtstmp";
							break;
			case "subject": $this->m_sSortMode = "m_subject";
							$this->m_sSortDirection = "ASC";
							break;
			case "nickname":$this->m_sSortMode = "m_usernickname";
							$this->m_sSortDirection = "ASC";
							break;
			case "views":	$this->m_sSortMode = "t_views";
							break;
			case "replies":	$this->m_sSortMode = "t_msgquantity";
							break;
			default: 		$this->m_sSortMode = "m_tstmp";
							break;
		}
		$this->m_iBoardId = intval($iBoardId);
		$this->m_iTimeSpan = intval($iTimeSpan);

		cScrollList::cScrollList();
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
		return "SELECT    m_id,"
						."m_subject,"
						."m_tstmp,"
						."m_threadid,"
						."t_active,"
						."t_lastmsgid,"
						."t_lastmsgtstmp,"
						."t_msgquantity,"
						."t_views,"
						."t_fixed,"
						."m_userid,"
						."m_usernickname,"
						."m_userhighlight"
				." FROM   pxm_thread,"
						."pxm_message"
				." WHERE  t_id=m_threadid"
				." AND 	  m_parentid=0"
				." AND 	  t_boardid=".$this->m_iBoardId
				." AND 	  (t_lastmsgtstmp>".$this->m_iTimeSpan." OR t_fixed=1)"
				." ORDER BY t_fixed DESC,".$this->m_sSortMode
				." ".$this->m_sSortDirection;
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

		$objThreadHeader = new cThreadHeader();
		$objThreadHeader->setId($objResultRow->m_id);
		$objThreadHeader->setSubject($objResultRow->m_subject);
		$objThreadHeader->setMessageTimestamp($objResultRow->m_tstmp);
		$objThreadHeader->setThreadId($objResultRow->m_threadid);
		$objThreadHeader->setThreadActive($objResultRow->t_active);
		$objThreadHeader->setLastMessageId($objResultRow->t_lastmsgid);
		$objThreadHeader->setLastMessageTimestamp($objResultRow->t_lastmsgtstmp);
		$objThreadHeader->setMessageQuantity($objResultRow->t_msgquantity);
		$objThreadHeader->setViews($objResultRow->t_views);
		$objThreadHeader->setIsThreadFixed($objResultRow->t_fixed);
		$objThreadHeader->m_objAuthor->setId($objResultRow->m_userid);
		$objThreadHeader->m_objAuthor->setNickName($objResultRow->m_usernickname);
		$objThreadHeader->m_objAuthor->setHighlightUser($objResultRow->m_userhighlight);

		$this->m_arrResultList[] = $objThreadHeader;
	}

	/**
	 * get membervariables as array
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iTimeOffset time offset in seconds
	 * @param string $sDateFormat php date format
	 * @param integer $iLastOnlineTimestamp last online timestamp for user
	 * @return array member variables
	 */
	function &getDataArray($iTimeOffset,$sDateFormat,$iLastOnlineTimestamp){

		$arrOutput = array();
		reset($this->m_arrResultList);
		while(list(,$objThreadHeader) = each($this->m_arrResultList)){
			$arrOutput[] = $objThreadHeader->getDataArray($iTimeOffset,$sDateFormat,$iLastOnlineTimestamp);
		}
		return $arrOutput;
	}
}
?>