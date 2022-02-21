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
 * thread handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.9 $
 */
class cThread{

	var $m_iBoardId;					// board id
	var	$m_iId;							// thread id
	var $m_bIsActive;					// thread status
	var $m_bIsFixed;					// is the thread fixed on top of the threadlist?
	var $m_iLastMessageId;				// last message id
	var $m_iLastMessageTimestamp;		// last message timestamp
	var $m_iMessageQuantity;			// quantity of messages in this thread
	var $m_iViews;						// views for this thread
	var $m_arrThreadMessages;			// message headers of the thread
	var	$m_arrThreadGraphics;			// graphics for threads

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function __construct(){

		$this->m_iBoardId = 0;
		$this->m_iId = 0;
		$this->m_iThreadId = 0;
		$this->m_bIsActive = FALSE;
		$this->m_bIsFixed = FALSE;
		$this->m_iLastMessageId = 0;
		$this->m_iLastMessageTimestamp = 0;
		$this->m_iMessageQuantity = 0;
		$this->m_iViews = 0;
		$this->m_arrThreadGraphics = array("lastc"=>"","empty"=>"","noc"=>"","midc"=>"");
	}

	/**
	 * get data from database by thread and board id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iThreadId thread id
	 * @param integer $iBoardId board id (will be checked for more security)
	 * @return boolean success / failure
	 */
	function loadDataById($iThreadId,$iBoardId){

		$bReturn = FALSE;
		$iThreadId = intval($iThreadId);
		$iBoardId = intval($iBoardId);

		if($iThreadId>0){

			global $objDb;

			if($objResultSet = &$objDb->executeQuery("SELECT t_boardid,".
															"t_id,".
															"t_active,".
															"t_fixed,".
															"t_lastmsgid,".
															"t_lastmsgtstmp,".
															"t_msgquantity,".
															"t_views".
															" FROM pxm_thread".
															" WHERE t_id=".$iThreadId." AND t_boardid=".$iBoardId)){
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

		$this->m_iBoardId = intval($objResultRow->t_boardid);
		$this->m_iId = intval($objResultRow->t_id);
		$this->m_bIsActive = $objResultRow->t_active?TRUE:FALSE;
		$this->m_iLastMessageId = intval($objResultRow->t_lastmsgid);
		$this->m_iLastMessageTimestamp = intval($objResultRow->t_lastmsgtstmp);
		$this->m_iMessageQuantity = intval($objResultRow->t_msgquantity);
		$this->m_iViews = intval($objResultRow->t_views);
		$this->m_bIsFixed = $objResultRow->t_fixed?TRUE:FALSE;

		return TRUE;
	}

	/**
	 * is the thread active?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean is the thread active?
	 */
	function isActive(){
		return $this->m_bIsActive;
	}

	/**
	 *  change status of the thread (open / closed)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bIsActive should the thread be activated?
	 * @return boolean success / failure
	 */
	function updateIsActive($bIsActive){

		global $objDb;

		if(!$objDb->executeQuery("UPDATE pxm_thread SET t_active=".intval($bIsActive)." WHERE t_id=$this->m_iId")){
			return FALSE;
		}
		$this->m_bIsActive = $bIsActive?TRUE:FALSE;
		return TRUE;
	}

	/**
	 * is the thread fixed on top of the threadlist?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean is the thread fixed on top of the threadlist?
	 */
	function isFixed(){
		return $this->m_bIsFixed;
	}

	/**
	 * set whether the thread is fixed on top of the threadlist or not?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bIsFixed is the thread fixed on top of the threadlist?
	 * @return boolean success / failure
	 */
	function updateIsFixed($bIsFixed){

		global $objDb;

		if(!$objDb->executeQuery("UPDATE pxm_thread SET t_fixed=".intval($bIsFixed)." WHERE t_id=$this->m_iId")){
			return FALSE;
		}
		$this->m_bIsFixed = $bIsFixed?TRUE:FALSE;
		return TRUE;
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

		global $objDb;

		if($objDb->executeQuery("DELETE FROM pxm_message WHERE m_threadid=$this->m_iId")
			&& $objDb->executeQuery("DELETE FROM pxm_thread WHERE t_id=$this->m_iId")){

			return TRUE;
		}
		return FALSE;
	}

	/**
	 * delete a subthread
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iMessageId id of the start message
	 * @return boolean success / failure
	 */
	function deleteSubThread($iMessageId){

		$iMessageId = intval($iMessageId);

		$bReturn = FALSE;
		$bClosed = FALSE;
		if($this->m_bIsActive){
			$bClosed = TRUE;
			$this->updateIsActive(FALSE);
		}

		$this->m_arrThreadMessages = &$this->getThreadMessageIdArray();

		if(isset($this->m_arrThreadMessages[0]) && !in_array($iMessageId,$this->m_arrThreadMessages[0])){// root message not allowed

			global $objDb;

			$this->_deleteSubThreadRecursive($iMessageId);

			$objDb->executeQuery("DELETE FROM pxm_message WHERE m_id=".$iMessageId);

			$this->_updateThreadInformation($this->m_iId);
			$bReturn = TRUE;
		}
		if($bClosed){
			$this->updateIsActive(TRUE);
		}		
		return $bReturn;
	}

	/**
	 * extract a subthread
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iMessageId id of the start message
	 * @return boolean success / failure
	 */
	function extractSubThread($iMessageId){

		$iMessageId = intval($iMessageId);

		$bReturn = FALSE;
		$bClosed = FALSE;
		if($this->m_bIsActive){
			$bClosed = TRUE;
			$this->updateIsActive(FALSE);
		}

		$this->m_arrThreadMessages = &$this->getThreadMessageIdArray();

		if(isset($this->m_arrThreadMessages[0]) && !in_array($iMessageId,$this->m_arrThreadMessages[0])){// root message not allowed

			global $objDb;

			if($objDb->executeQuery("INSERT INTO pxm_thread (t_boardid,t_active,t_lastmsgtstmp) VALUES ($this->m_iBoardId,1,0)")){
				if(($iNewThreadId = $objDb->getInsertId("pxm_thread","t_id"))>0){

					$objDb->executeQuery("UPDATE pxm_message SET m_threadid=".intval($iNewThreadId).",m_parentid=0 WHERE m_id=".$iMessageId);

					$this->_moveSubThreadRecursive($iMessageId,$iNewThreadId);

					$this->_updateThreadInformation($iNewThreadId);
					$this->_updateThreadInformation($this->m_iId);
					$bReturn = TRUE;
				}
			}
		}
		if($bClosed){
			$this->updateIsActive(TRUE);
		}	
		return $bReturn;
	}

	/**
	 * get the ids of the messages in this thread
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array ids of the messages in this thread
	 */
	function &getThreadMessageIdArray(){

		global $objDb;

		$arrThreadMessageIds = array();
		if($objResultSet = &$objDb->executeQuery("SELECT m_id,m_parentid FROM pxm_message WHERE m_threadid=$this->m_iId")){
			while($objResultRow = $objResultSet->getNextResultRowObject()){
				$arrThreadMessageIds[intval($objResultRow->m_parentid)][] = intval($objResultRow->m_id);
			}
		}
		return $arrThreadMessageIds;
	}

	/**
	 * delete a subthread (internal helper method)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param integer $iMessageId id of the start message
	 * @return boolean success / failure
	 */
	function _deleteSubThreadRecursive($iMessageId){
		if(isset($this->m_arrThreadMessages[$iMessageId])){
			foreach($this->m_arrThreadMessages[$iMessageId] as $iSubMessageId){
				$this->_deleteSubThreadRecursive($iSubMessageId);
			}
			global $objDb;
			$objDb->executeQuery("DELETE FROM pxm_message WHERE m_threadid=$this->m_iId AND m_parentid=".$iMessageId);
		}
	}

	/**
	 * move a subthread (internal helper method)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param integer $iMessageId id of the start message
	 * @param integer $iNewThreadId id of the new thread
	 * @return boolean success / failure
	 */
	function _moveSubThreadRecursive($iMessageId,$iNewThreadId){
		if(isset($this->m_arrThreadMessages[$iMessageId])){
			foreach($this->m_arrThreadMessages[$iMessageId] as $iSubMessageId){
				$this->_moveSubThreadRecursive($iSubMessageId,$iNewThreadId);
			}
			global $objDb;
			$objDb->executeQuery("UPDATE pxm_message SET m_threadid=$iNewThreadId WHERE m_threadid=$this->m_iId AND m_parentid=".$iMessageId);
		}
	}

	/**
	 * recalculate the thread information (last message, views etc.) and update the database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param integer $iThreadId id of the thread
	 * @return boolean success / failure
	 */
	function _updateThreadInformation($iThreadId){
		global $objDb;
		if($objResultSet = &$objDb->executeQuery("SELECT count(*) AS count,MAX(m_tstmp) AS maxd,MAX(m_id) AS maxid FROM pxm_message WHERE m_threadid=$iThreadId")){
			if($objResultRow = $objResultSet->getNextResultRowObject()){
				$objDb->executeQuery("UPDATE pxm_thread SET t_msgquantity=$objResultRow->count-1,t_lastmsgid=$objResultRow->maxid,t_lastmsgtstmp=$objResultRow->maxd WHERE t_id=$iThreadId");
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * delete data from database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iDestinationBoardId destination board id
	 * @return boolean success / failure
	 */
	function moveThread($iDestinationBoardId){

		global $objDb;
		$bReturn = FALSE;

		$iDestinationBoardId = intval($iDestinationBoardId);
		if($iDestinationBoardId>0){
			if($objResultSet = &$objDb->executeQuery("UPDATE pxm_thread SET t_boardid=$iDestinationBoardId WHERE t_id=$this->m_iId")){
				if($objResultSet->getAffectedRows()>0) $bReturn = TRUE;
			}
		}
		return $bReturn;
	}

	/**
	 * set thread graphics
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param array $arrThreadGraphics thread graphics
	 * @return void
	 */
	function setThreadGraphics(&$arrThreadGraphics){
		$this->m_arrThreadGraphics = &$arrThreadGraphics;
	}

	/**
	 * get membervariables as array
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iTimeOffset time offset in seconds
	 * @param string $sDateFormat php date format
	 * @param integer $iLastOnlineTimestamp last online timestamp for user
	 * @param boolean $bCountViews count views?
	 * @return array member variables
	 */
	function getDataArray($iTimeOffset,$sDateFormat,$iLastOnlineTimestamp,$bCountViews){

		if ($this->m_iId>0){

			global $objDb;

			if($objResultSet = &$objDb->executeQuery("SELECT m_id,m_parentid,m_subject,m_tstmp,m_userid,m_usernickname,m_userhighlight FROM pxm_message WHERE m_threadid=$this->m_iId ORDER BY m_tstmp DESC")){

				$objParser = null;	// message parser not needed

				$objMessageHeader = new cMessageHeader();
				$this->m_arrThreadMessages = array();
				while($objResultRow = $objResultSet->getNextResultRowObject()){

					$objMessageHeader->setId($objResultRow->m_id);
					$objMessageHeader->setSubject($objResultRow->m_subject);
					$objMessageHeader->setMessageTimestamp($objResultRow->m_tstmp);
					$objMessageHeader->setAuthorId($objResultRow->m_userid);
					$objMessageHeader->setAuthorNickName($objResultRow->m_usernickname);
					$objMessageHeader->setAuthorHighlightUser($objResultRow->m_userhighlight);

					$this->m_arrThreadMessages[$objResultRow->m_parentid][]	= $objMessageHeader->getDataArray($iTimeOffset,$sDateFormat,$iLastOnlineTimestamp,"",$objParser);
				}

				if(sizeof($this->m_arrThreadMessages)>0){

					$objResultSet->freeResult();
					unset($objResultSet);

					if($bCountViews){
						$objDb->executeQuery("UPDATE pxm_thread SET t_views=t_views+1 WHERE t_id=$this->m_iId");
						++$this->m_iViews;
					}
					return array("id"		=>	$this->m_iId,
								 "active"	=>	$this->m_bIsActive,
								 "fixed"	=>	$this->m_bIsFixed,
								 "views"	=>	$this->m_iViews,
								 "msg"		=>	$this->_getMessageTreeArray(0));
				}
			}
		}
		return array("id"		=>	$this->m_iId,
					 "active"	=>	$this->m_bIsActive,
					 "fixed"	=>	$this->m_bIsFixed,
					 "views"	=>	$this->m_iViews);
	}

	/**
	 * build the message header tree
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param integer $iParentId parent id
	 * @param string $sImages image tags
	 * @return array message header tree
	 */
	function &_getMessageTreeArray($iParentId,$sImages = ""){
		$arrReturn = array();
		if(isset($this->m_arrThreadMessages[$iParentId]) && is_array($this->m_arrThreadMessages[$iParentId]) && ($iLevelArraySize = sizeof($this->m_arrThreadMessages[$iParentId]))>0){		//if there is at least one answer to parent message
			for($iMessagePointer = 0;$iMessagePointer<$iLevelArraySize;$iMessagePointer++){//recursive call to getMsgTreeArray for every answer
				if($iMessagePointer<$iLevelArraySize-1){	//if it is not the last answer...
					$this->m_arrThreadMessages[$iParentId][$iMessagePointer] = array_merge($this->m_arrThreadMessages[$iParentId][$iMessagePointer],array("_img" => $sImages.$this->m_arrThreadGraphics["midc"]));
					if($arrChildren = &$this->_getMessageTreeArray($this->m_arrThreadMessages[$iParentId][$iMessagePointer]["id"],$sImages.$this->m_arrThreadGraphics["noc"])){
						$this->m_arrThreadMessages[$iParentId][$iMessagePointer] = array_merge($this->m_arrThreadMessages[$iParentId][$iMessagePointer],array("msg" => $arrChildren));
					}
				}
				else{										//...else draw gif for endpart
					if($iParentId>0){
						$this->m_arrThreadMessages[$iParentId][$iMessagePointer] = array_merge($this->m_arrThreadMessages[$iParentId][$iMessagePointer],array("_img" => $sImages.$this->m_arrThreadGraphics["lastc"]));
					}
					if($arrChildren = &$this->_getMessageTreeArray($this->m_arrThreadMessages[$iParentId][$iMessagePointer]["id"],$sImages.$this->m_arrThreadGraphics["empty"])){
						$this->m_arrThreadMessages[$iParentId][$iMessagePointer] = array_merge($this->m_arrThreadMessages[$iParentId][$iMessagePointer],array("msg" => $arrChildren));
					}
				}
			}
			$arrReturn = &$this->m_arrThreadMessages[$iParentId];
		}
		return $arrReturn;
	}
}
?>