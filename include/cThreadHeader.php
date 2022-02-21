<?php
require_once(INCLUDEDIR."/cMessageHeader.php");
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
 * threadheader handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.7 $
 */
class cThreadHeader extends cMessageHeader{

	var $m_iBoardId;					// board id
	var $m_iThreadId;					// thread id
	var $m_bIsActive;					// thread status
	var $m_bIsFixed;					// is the thread fixed on top of the threadlist?
	var $m_iLastMessageId;				// last message id
	var $m_iLastMessageTimestamp;		// last message timestamp
	var $m_iMessageQuantity;			// quantity of messages in this thread
	var $m_iViews;						// views for this thread

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function __construct(){

		parent::__construct();

		$this->m_iBoardId = 0;
		$this->m_iThreadId = 0;
		$this->m_bIsActive = FALSE;
		$this->m_bIsFixed = FALSE;
		$this->m_iLastMessageId = 0;
		$this->m_iLastMessageTimestamp = 0;
		$this->m_iMessageQuantity = 0;
		$this->m_iViews = 0;
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

		cMessageHeader::_setDataFromDb($objResultRow);

		$this->m_iThreadId = intval($objResultRow->t_id);
		$this->m_bIsActive = $objResultRow->t_active?TRUE:FALSE;
		$this->m_iLastMessageId = intval($objResultRow->t_lastmsgid);
		$this->m_iLastMessageTimestamp = intval($objResultRow->t_lastmsgtstmp);
		$this->m_iMessageQuantity = intval($objResultRow->t_msgquantity);
		$this->m_iViews = intval($objResultRow->t_views);
		$this->m_bIsFixed = $objResultRow->t_fixed?TRUE:FALSE;

		return TRUE;
	}

	/**
	 * get additional database attributes for this object (template method)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return string additional database attributes for this object
	 */
	 function _getDbAttributes(){
	 	return cMessageHeader::_getDbAttributes().",t_id,t_active,t_boardid,t_lastmsgid,t_lastmsgtstmp,t_msgquantity,t_views,t_fixed";
	 }

	/**
	 * get additional database tables for this object (template method)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return string additional database tables for this object
	 */
	 function _getDbTables(){
	 	return cMessageHeader::_getDbTables().",pxm_thread";
	 }

	/**
	 * get additional database tables for this object (template method)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return string additional database join for this object
	 */
	 function _getDbJoin(){
	 	return cMessageHeader::_getDbJoin()." AND t_id=m_threadid";
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
	 * @param integer $iThreadId board id
	 * @return void
	 */
	function setBoardId($iBoardId){
		$this->m_iBoardId = intval($iBoardId);
	}

	/**
	 * get thread id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer thread id
	 */
	function getThreadId(){
		return $this->m_iThreadId;
	}

	/**
	 * set thread id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iThreadId thread id
	 * @return void
	 */
	function setThreadId($iThreadId){
		$this->m_iThreadId = intval($iThreadId);
	}

	/**
	 * is the thread active?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean is active / is not active
	 */
	function isThreadActive(){
		return $this->m_bIsActive;
	}

	/**
	 * set thread is active
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bIsActive is the thread active
	 * @return void
	 */
	function setThreadActive($bIsActive){
		$this->m_bIsActive = $bIsActive?TRUE:FALSE;
	}

	/**
	 * is the thread fixed on top of the threadlist?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean is the thread fixed on top of the threadlist?
	 */
	function isThreadFixed(){
		return $this->m_bIsFixed;
	}

	/**
	 * set whether the thread is fixed on top of the threadlist or not?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bIsFixed is the thread fixed on top of the threadlist?
	 * @return void
	 */
	function setIsThreadFixed($bbIsFixed){
		$this->m_bIsFixed = $bbIsFixed?TRUE:FALSE;
	}

	/**
	 * get last message id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer last message id
	 */
	function getLastMessageId(){
		return $this->m_iLastMessageId;
	}

	/**
	 * set last message id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iLastMessageId last message id
	 * @return void
	 */
	function setLastMessageId($iLastMessageId){
		$this->m_iLastMessageId = intval($iLastMessageId);
	}

	/**
	 * get last message timestamp
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer last message timestamp
	 */
	function getLastMessageTimestamp(){
		return $this->m_iLastMessageTimestamp;
	}

	/**
	 * set last message timestamp
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iLastMessageTimestamp last message timestamp
	 * @return void
	 */
	function setLastMessageTimestamp($iLastMessageTimestamp){
		$this->m_iLastMessageTimestamp = intval($iLastMessageTimestamp);
	}

	/**
	 * get message quantity
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer message quantity
	 */
	function getMessageQuantity(){
		return $this->m_iMessageQuantity;
	}

	/**
	 * set message quantity
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iMessageQuantity message quantity
	 * @return void
	 */
	function setMessageQuantity($iMessageQuantity){
		$this->m_iMessageQuantity = intval($iMessageQuantity);
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
	 * set message quantity
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
	 * get membervariables as array
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iTimeOffset time offset in seconds
	 * @param string $sDateFormat php date format
	 * @param integer $iLastOnlineTimestamp last online timestamp for user
	 * @return array member variables
	 */
	function getDataArray($iTimeOffset,$sDateFormat,$iLastOnlineTimestamp, $sSubjectQuotePrefix,$objParser){
		$objParser = null;
		return array_merge(cMessageHeader::getDataArray($iTimeOffset,$sDateFormat,$iLastOnlineTimestamp,"",$objParser),
						   array("threadid"	=>	$this->m_iThreadId,
						   		 "active"	=>	intval($this->m_bIsActive),
						   		 "views"	=>	strval($this->m_iViews),
								 "fixed"	=>	intval($this->m_bIsFixed),
						   		 "lastid"	=>	$this->m_iLastMessageId,
						   		 "lastdate"	=>	(($this->m_iLastMessageTimestamp>$this->m_iMessageTimestamp)?date($sDateFormat,($this->m_iLastMessageTimestamp+$iTimeOffset)):0),
								 "lastnew"	=>	(($iLastOnlineTimestamp>$this->m_iLastMessageTimestamp)?0:1),
						   		 "msgquan"	=>	strval($this->m_iMessageQuantity)));
	}
}
?>