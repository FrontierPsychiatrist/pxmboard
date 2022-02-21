<?php
require_once(INCLUDEDIR."/cMessage.php");
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
 * boardmessage handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.14 $
 */
class cBoardMessage extends cMessage{

	var $m_iBoardId;				// board id
	var $m_iThreadId;				// thread id
	var $m_bThreadIsActive;			// thread status
	var $m_objReplyMsg;				// reply to message
	var $m_bSendNotification;		// send reply notification

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function cBoardMessage(){

		cMessage::cMessage();

		$this->m_iBoardId = 0;
		$this->m_iThreadId = 0;
		$this->m_bThreadIsActive = FALSE;
		$this->m_objReplyMsg = new cMessageHeader();
		$this->m_bSendNotification = FALSE;
	}

	/**
	 * get data from database by message id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iMessageId message id
	 * @param integer $iBoardId board id (will be checked for more security)
	 * @return boolean success / failure
	 */
	function loadDataById($iMessageId,$iBoardId){
		return (cMessage::loadDataById($iMessageId) && $this->m_iBoardId == $iBoardId);
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

		cMessage::_setDataFromDb($objResultRow);

		$this->m_iBoardId = intval($objResultRow->t_boardid);
		$this->m_iThreadId = intval($objResultRow->t_id);
		$this->m_bThreadIsActive = $objResultRow->t_active?TRUE:FALSE;

		// author data
		$this->m_objAuthor->setFirstName($objResultRow->u_firstname);
		$this->m_objAuthor->setLastName($objResultRow->u_lastname);
		$this->m_objAuthor->setCity($objResultRow->u_city);
		$this->m_objAuthor->setImageFileName($objResultRow->u_imgfile);
		$this->m_objAuthor->setRegistrationTimestamp($objResultRow->u_registrationtstmp);
		$this->m_objAuthor->setLastOnlineTimestamp($objResultRow->u_lastonlinetstmp);
		$this->m_objAuthor->setMessageQuantity($objResultRow->u_msgquantity);
		$this->m_objAuthor->setSignature($objResultRow->u_signature);

		$this->m_objReplyMsg = new cMessageHeader();
		$this->m_objReplyMsg->loadDataById($objResultRow->m_parentid);
		$this->m_bSendNotification = $objResultRow->m_notification?TRUE:FALSE;

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
	 	return cMessage::_getDbAttributes()
				.",t_id,t_active,t_boardid,m_parentid,m_notification"
				.",u_firstname,u_lastname,u_city,u_imgfile,u_registrationtstmp"
				.",u_lastonlinetstmp,u_msgquantity,u_signature";
	 }

	/**
	 * get additional database tables for this object (template method).
	 * will perform a left outer join and needs pxm_message as last table from parent class!
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return string additional database tables for this object
	 */
	 function _getDbTables(){
	 	return "pxm_thread,".cMessage::_getDbTables()." LEFT OUTER JOIN pxm_user ON (m_userid=u_id)";
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
	 	return cMessage::_getDbJoin()." AND t_id=m_threadid";
	 }

	/**
	 * insert new data into database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iParentId parent id
	 * @param integer $iAutoClose message limit per thread (thread will be closed when reached)
	 * @return integer error id
	 */
	function insertData($iParentId,$iAutoClose){

		global $objDb;

		$iErrorId = 8;												// could not insert data
		$iParentId = intval($iParentId);
		$iAutoClose = intval($iAutoClose);

		if(!empty($this->m_sSubject)){
			// dupcheck
			if($objResultSet = &$objDb->executeQuery("SELECT COUNT(*) AS msgcount FROM pxm_message".
																" WHERE   m_parentid=$iParentId".
																	" AND m_userid=".$this->m_objAuthor->getId().
																	" AND m_tstmp>".($this->m_iMessageTimestamp - 259200).
																	" AND m_subject=".$objDb->quote($this->m_sSubject))
								&& $objResultRow = $objResultSet->getNextResultRowObject()){
				if(intval($objResultRow->msgcount)<1){
					if($iParentId<1){							// new thread
						if($objDb->executeQuery("INSERT INTO pxm_thread (t_boardid,t_active,t_lastmsgtstmp) VALUES ($this->m_iBoardId,1,$this->m_iMessageTimestamp)")){
							if(($this->m_iThreadId = $objDb->getInsertId("pxm_thread","t_id"))>0){
								if($objResultSet = &$objDb->executeQuery("INSERT INTO pxm_message (m_threadid,m_parentid,m_userid,m_usernickname,m_usermail,m_userhighlight,m_subject,m_body,m_tstmp,m_ip,m_notification)".
																				   " VALUES ($this->m_iThreadId,".
																				   			 "0,".
																							 $this->m_objAuthor->getId().",".
																							 $objDb->quote($this->m_objAuthor->getNickName()).",".
																							 $objDb->quote($this->m_objAuthor->getPublicMail()).",".
																							 intval($this->m_objAuthor->highlightUser()).",".
																							 $objDb->quote($this->m_sSubject).",".
																							 $objDb->quote($this->m_sBody).",".
																							 $this->m_iMessageTimestamp.",".
																							 $objDb->quote($this->m_sIp).",".
																							 intval($this->m_bSendNotification).")")){
									if($objResultSet->getAffectedRows()>0){

										$this->m_iId = intval($objDb->getInsertId("pxm_message","m_id"));

										// update board list
										$objDb->executeQuery("UPDATE pxm_board SET b_lastmsgtstmp=$this->m_iMessageTimestamp WHERE b_id=$this->m_iBoardId");

										// no error occured
										$iErrorId = 0;
									}
									else{
										$objDb->executeQuery("DELETE FROM pxm_thread WHERE t_id=$this->m_iThreadId");
									}
								}
								else{
									$objDb->executeQuery("DELETE FROM pxm_thread WHERE t_id=$this->m_iThreadId");
								}
							}
							else $iErrorId = 8;						// could not insert data
						}
						else $iErrorId = 8;							// could not insert data
					}
					else{											// reply
						if($objResultSet = &$objDb->executeQuery("SELECT m_threadid,t_active FROM pxm_thread,pxm_message WHERE t_id=m_threadid AND t_boardid=$this->m_iBoardId AND m_id=$iParentId")){
							if($objResultRow = $objResultSet->getNextResultRowObject()){
								$objResultSet->freeResult();
								if($objResultRow->t_active == 1){

									$this->m_iThreadId = intval($objResultRow->m_threadid);

									if($objResultSet = &$objDb->executeQuery("INSERT INTO pxm_message (m_threadid,m_parentid,m_userid,m_usernickname,m_usermail,m_userhighlight,m_subject,m_body,m_tstmp,m_ip,m_notification)".
																					   " VALUES ($this->m_iThreadId,".
																					   			 $iParentId.",".
																								 $this->m_objAuthor->getId().",".
																								 $objDb->quote($this->m_objAuthor->getNickName()).",".
																								 $objDb->quote($this->m_objAuthor->getPublicMail()).",".
																								 intval($this->m_objAuthor->highlightUser()).",".
																								 $objDb->quote($this->m_sSubject).",".
																								 $objDb->quote($this->m_sBody).",".
																								 $this->m_iMessageTimestamp.",".
																								 $objDb->quote($this->m_sIp).",".
																								 intval($this->m_bSendNotification).")")){
										if($objResultSet->getAffectedRows()>0){

											$this->m_iId = intval($objDb->getInsertId("pxm_message","m_id"));

											// update thread list
											$objDb->executeQuery("UPDATE pxm_thread SET t_lastmsgtstmp=$this->m_iMessageTimestamp,t_lastmsgid=$this->m_iId,t_msgquantity=t_msgquantity+1 WHERE t_id=$this->m_iThreadId");

											// update board list
											$objDb->executeQuery("UPDATE pxm_board SET b_lastmsgtstmp=$this->m_iMessageTimestamp WHERE b_id=$this->m_iBoardId");

											// close the thread when the messagelimit is reached
											if($iAutoClose>0){
												$objDb->executeQuery("UPDATE pxm_thread SET t_active=0 WHERE t_id=$this->m_iThreadId AND t_msgquantity>=$iAutoClose");
											}

											// no error occured
											$iErrorId = 0;
										}
										else $iErrorId = 8;			// could not insert data
									}
									else $iErrorId = 8;				// could not insert data
								}
								else $iErrorId = 9;					// thread closed
							}
							else $iErrorId = 6;						// invalid msg id
						}
						else $iErrorId = 8;							// could not insert data
					}
				}
				else $iErrorId = 14;								// message already exists
			}
			else $iErrorId = 8;										// could not insert data
		}
		else $iErrorId = 7;											// missing subject

		return $iErrorId;
	}

	/**
	 * update data in database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer error id
	 */
	function updateData(){

		global $objDb;

		$iErrorId = 8;												// could not insert data

		if(!empty($this->m_sSubject)){
			if($this->m_iId>0){
				if($objResultSet = &$objDb->executeQuery("UPDATE pxm_message SET m_subject=".$objDb->quote($this->m_sSubject).",".
																				"m_body=".$objDb->quote($this->m_sBody).",".
																				"m_notification=".intval($this->m_bSendNotification).
																			" WHERE m_id=$this->m_iId")){
					if($objResultSet->getAffectedRows()>0){
						$iErrorId = 0;
					}
					else $iErrorId = 8;								// could not insert data
				}
				else $iErrorId = 8;									// could not insert data
			}
			else $iErrorId = 6;										// invalid msg id
		}
		else $iErrorId = 7;											// missing subject

		return $iErrorId;
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

		$iParentId = $this->m_objReplyMsg->getId();
		if($this->m_iId>0 && $iParentId>0){
			$objDb->executeQuery("DELETE FROM pxm_message WHERE m_id=$this->m_iId");
			$objDb->executeQuery("UPDATE pxm_message SET m_parentid=$iParentId WHERE m_parentid=$this->m_iId");

			if($objResultSet = &$objDb->executeQuery("SELECT count(*) AS count,MAX(m_tstmp) AS maxd,MAX(m_id) AS maxid FROM pxm_message WHERE m_threadid=$this->m_iThreadId")){
				if($objResultRow = $objResultSet->getNextResultRowObject()){
					$objDb->executeQuery("UPDATE pxm_thread SET t_msgquantity=$objResultRow->count-1,t_lastmsgid=$objResultRow->maxid,t_lastmsgtstmp=$objResultRow->maxd WHERE t_id=$this->m_iThreadId");
				}
			}
		}
		else{
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * get the number of replies to this message from database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer number of replies
	 */
	function getReplyQuantity(){

		global $objDb;
		if($objResultSet = &$objDb->executeQuery("SELECT count(*) AS count FROM pxm_message WHERE m_threadid=$this->m_iThreadId AND m_parentid=$this->m_iId")){
			if($objResultRow = $objResultSet->getNextResultRowObject()){
				return $objResultRow->count;
			}
		}
		return 0;
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
	 * is thread active?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean thread is active / inactive
	 */
	function isThreadActive(){
		return $this->m_bThreadIsActive;
	}

	/**
	 * set is thread active?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bThreadIsActive thread is active / inactive
	 * @return void
	 */
	function setIsThreadActive($bThreadIsActive){
		$this->m_bThreadIsActive = $bThreadIsActive?TRUE:FALSE;
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
	 * get the id of the parent message
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer parent message id
	 */
	function getParentId(){
		return $this->m_objReplyMsg->getId();
	}

	/**
	 * set the id of the parent message
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iParentId parent message id
	 * @return void
	 */
	function setParentId($iParentId){
		$this->m_objReplyMsg->setId($iParentId);
	}

	/**
	 * send reply notification?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean send reply notification
	 */
	function sendNotification(){
		return $this->m_bSendNotification;
	}

	/**
	 * set send reply notification?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bSendNotification send reply notification
	 * @return void
	 */
	function setSendNotification($bSendNotification){
		$this->m_bSendNotification = $bSendNotification?TRUE:FALSE;
	}

	/**
	 * update the message notification status
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bSendNotification send reply notification
	 * @return void
	 */
	function updateSendNotification($bSendNotification){

		global $objDb;

		if(!$objDb->executeQuery("UPDATE pxm_message SET m_notification=".intval($bSendNotification)." WHERE m_id=$this->m_iId")){
			return FALSE;
		}
		$this->m_bSendNotification = $bSendNotification?TRUE:FALSE;
		return TRUE;
	}

	/**
	 * get membervariables as array
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iTimeOffset time offset in seconds
	 * @param string $sDateFormat php date format
	 * @param integer $iLastOnlineTimestamp last online timestamp for user
	 * @param string $sSubjectQuotePrefix prefix for quoted subject
 	 * @param object $objParser message parser
	 * @return array member variables
	 */
	function getDataArray($iTimeOffset,$sDateFormat,$iLastOnlineTimestamp,$sSubjectQuotePrefix,&$objParser){
		return array_merge(cMessage::getDataArray($iTimeOffset,$sDateFormat,$iLastOnlineTimestamp,$sSubjectQuotePrefix,$objParser),
						   array("notification"	=>	$this->m_bSendNotification,
						   		 "thread"		=>	array("id"		=>	$this->m_iThreadId,
						   		 						  "active"	=>	intval($this->m_bThreadIsActive),
														  "brdid"	=>	$this->m_iBoardId),
						  		 "replyto"		=>	$this->m_objReplyMsg->getDataArray($iTimeOffset,$sDateFormat,$iLastOnlineTimestamp,"",$objParser)));
	}
}
?>