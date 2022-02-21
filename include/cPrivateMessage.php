<?php
require_once(INCLUDEDIR."/cMessage.php");
require_once(INCLUDEDIR."/cMessageStates.php");
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
 * private message handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.9 $
 */
class cPrivateMessage extends cMessage{

	var $m_iToUserId;					// destination user id
	var $m_iToState;					// state for the recipient
	var $m_iFromState;					// state for the sender

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

		$this->m_iToUserId = 0;
		$this->m_iToState = cMessageStates::messageNew();

		$this->m_iFromState = cMessageStates::messageRead();
	}

	/**
	 * get data from database by message id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iMessageId message id
	 * @return boolean success / failure
	 */
	function loadDataById($iMessageId){

		$bReturn = FALSE;
		$iMessageId = intval($iMessageId);

		if($iMessageId>0){

			global $objDb;

			if($objResultSet = &$objDb->executeQuery("SELECT p_id,".
															"p_subject,".
															"p_body,".
															"p_tstmp,".
															"p_touserid,".
															"p_tostate,".
															"u_id,".
															"u_nickname,".
															"u_publicmail,".
															"u_highlight,".
															"u_firstname,".
															"u_lastname,".
															"u_city,".
															"u_signature,".
															"u_imgfile,".
															"u_registrationtstmp,".
															"u_lastonlinetstmp,".
															"u_msgquantity,".
															"p_fromstate,".
															"p_ip".
															$this->_getDbAttributes().
															" FROM pxm_priv_message,pxm_user".
															$this->_getDbTables().
															" WHERE p_fromuserid=u_id".
															" AND (".
															"(p_touserid=".$this->m_iToUserId." AND p_tostate!=".cMessageStates::messageDeleted().")".
															" OR ".
															"(p_fromuserid=".$this->m_objAuthor->getId()." AND p_fromstate!=".cMessageStates::messageDeleted().")".
															") AND p_id=".$iMessageId.
															$this->_getDbJoin())){
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

		$this->m_iId = intval($objResultRow->p_id);
		$this->m_sSubject = $objResultRow->p_subject;
		$this->m_sBody = $objResultRow->p_body;
		$this->m_iMessageTimestamp = intval($objResultRow->p_tstmp);
		$this->m_sIp = $objResultRow->p_ip;

		// recipient data
		$this->m_iToState = intval($objResultRow->p_tostate);
		$this->m_iToUserId = intval($objResultRow->p_touserid);

		// author data
		$this->m_objAuthor->setId($objResultRow->u_id);
		$this->m_objAuthor->setNickName($objResultRow->u_nickname);
		$this->m_objAuthor->setPublicMail($objResultRow->u_publicmail);
		$this->m_objAuthor->setHighlightUser($objResultRow->u_highlight);
		$this->m_objAuthor->setFirstName($objResultRow->u_firstname);
		$this->m_objAuthor->setLastName($objResultRow->u_lastname);
		$this->m_objAuthor->setCity($objResultRow->u_city);
		$this->m_objAuthor->setImageFileName($objResultRow->u_imgfile);
		$this->m_objAuthor->setRegistrationTimestamp($objResultRow->u_registrationtstmp);
		$this->m_objAuthor->setLastOnlineTimestamp($objResultRow->u_lastonlinetstmp);
		$this->m_objAuthor->setMessageQuantity($objResultRow->u_msgquantity);
		$this->m_objAuthor->setSignature($objResultRow->u_signature);

		$this->m_iFromState = intval($objResultRow->p_fromstate);

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
	 	return "";
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
	 	return "";
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
	 	return "";
	 }

	/**
	 * insert new data into database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer error id
	 */
	function insertData(){

		global $objDb;

		$iErrorId = 8;												// could not insert data

		if($this->m_iToUserId>0 && $this->m_objAuthor->getId()>0){
			if(!empty($this->m_sSubject)){
				if($objResultSet = &$objDb->executeQuery("INSERT INTO pxm_priv_message (p_touserid,p_fromuserid,p_subject,p_body,p_tstmp,p_ip)".
																   " values ($this->m_iToUserId,".
																			 $this->m_objAuthor->getId().",".
																			 "'".addslashes($this->m_sSubject)."',".
																			 "'".addslashes($this->m_sBody)."',".
																			 $this->m_iMessageTimestamp.",".
																			 "'".addslashes($this->m_sIp)."')")){
					if($objResultSet->getAffectedRows()>0){
						$iErrorId = 0;
					}
				}
			}
			else $iErrorId = 7;										// missing subject
		}
		else $iErrorId = 20;										// invalid user id

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
		
		$bReturn = FALSE;

		// set the message to deleted if we are the recipient
		if($objResultSet = &$objDb->executeQuery("UPDATE pxm_priv_message SET p_tostate=".cMessageStates::messageDeleted().
														   " WHERE p_touserid=$this->m_iToUserId AND p_id=$this->m_iId")){
			if($objResultSet->getAffectedRows()>0){
				$bReturn = TRUE;
			}
		}

		// set the message to deleted if we are the author
		if(!$bReturn && $objResultSet = &$objDb->executeQuery("UPDATE pxm_priv_message SET p_fromstate=".cMessageStates::messageDeleted().
																		" WHERE p_fromuserid=".$this->m_objAuthor->getId()." AND p_id=$this->m_iId")){
			if($objResultSet->getAffectedRows()>0){
				$bReturn = TRUE;
			}
		}

		// remove all deleted messages from db
		$objDb->executeQuery("DELETE FROM pxm_priv_message WHERE p_tostate=".cMessageStates::messageDeleted()." AND p_fromstate=".cMessageStates::messageDeleted());

		return $bReturn;
	}

	/**
	 * get the id of the destination user
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer id of the destination user
	 */
	function getDestinationUserId(){
		return $this->m_iToUserId;
	}

	/**
	 * set the id of the destination user
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iToUserId id of the destination user
	 * @return void
	 */
	function setDestinationUserId($iToUserId){
		$this->m_iToUserId = intval($iToUserId);
	}

	/**
	 * get the message state for the destination user
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer message state for the destination user
	 */
	function getDestinationState(){
		return $this->m_iToState;
	}

	/**
	 * set the message state for the destination user
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iToState message state for the destination user
	 * @return void
	 */
	function setDestinationState($iToState){
		$this->m_iToState = intval($iToState);
	}

	/**
	 * set the message state to read for an unread message of the recipient
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function setMessageRead(){
		if($this->m_iToState==cMessageStates::messageNew()) {

			global $objDb;

			$objDb->executeQuery("UPDATE pxm_priv_message SET p_tostate=".cMessageStates::messageRead()." WHERE p_id=$this->m_iId");
		}
	}

	/**
	 * get the message state for the author
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer message state for the author
	 */
	function getAuthorState(){
		return $this->m_iFromState;
	}

	/**
	 * set the message state for the author
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iFromState message state for the author
	 * @return void
	 */
	function setAuthorState($iFromState){
		$this->m_iFromState = intval($iFromState);
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
						   array("read"=>($this->m_iToState==cMessageStates::messageRead()?"1":"0")));
	}
}
?>