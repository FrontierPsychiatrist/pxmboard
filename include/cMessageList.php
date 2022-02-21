<?php
require_once(INCLUDEDIR."/cScrollList.php");
require_once(INCLUDEDIR."/cBoardMessage.php");
require_once(INCLUDEDIR."/cUser.php");
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
 * message list handling for flat view
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/01/02 19:14:14 $
 * @version $Revision: 1.11 $
 */
class cMessageList extends cScrollList{

	var $m_iBoardId;			// board id
	var $m_iThreadId;			// thread id

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iBoardId board id
	 * @param integer $iThreadId thread id
	 * @return void
	 */
	function cMessageList($iBoardId,$iThreadId){

		$this->m_iBoardId = intval($iBoardId);
		$this->m_iThreadId = intval($iThreadId);;

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
		return "SELECT t_boardid,".
						"t_id,".
						"t_active,".
						"m_id,".
						"m_parentid,".
						"m_subject,".
						"m_body,".
						"m_tstmp,".
						"m_userid,".
						"m_usernickname,".
						"m_userhighlight,".
						"m_usermail,".
						"u_firstname,".
						"u_lastname,".
						"u_city,".
						"u_signature,".
						"u_imgfile,".
						"u_registrationtstmp,".
						"u_lastonlinetstmp,".
						"u_msgquantity,".
						"m_ip,".
						"m_notification".
				" FROM pxm_thread,pxm_message". 
				" LEFT OUTER JOIN pxm_user ON (m_userid=u_id)".
				" WHERE t_id=m_threadid".
				" AND t_boardid=$this->m_iBoardId".
				" AND m_threadid=$this->m_iThreadId".
				" ORDER BY m_tstmp ASC";
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

		if($objResultSet = &$objDb->executeQuery("SELECT count(*) AS cou".
													" FROM pxm_thread,pxm_message".
													" WHERE t_id=m_threadid".
													" AND t_boardid=$this->m_iBoardId".
													" AND m_threadid=$this->m_iThreadId")){
			if($objResultRow = $objResultSet->getNextResultRowObject()){
				$this->m_iItemCount = $objResultRow->cou;
			}
		}
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

		$objBoardMessage = new cBoardMessage();
		$objBoardMessage->setId($objResultRow->m_id);
		$objBoardMessage->setParentId($objResultRow->m_parentid);
		$objBoardMessage->setBoardId($objResultRow->t_boardid);
		$objBoardMessage->setThreadId($objResultRow->t_id);
		$objBoardMessage->setIsThreadActive($objResultRow->t_active);
		$objBoardMessage->setSubject($objResultRow->m_subject);
		$objBoardMessage->setBody($objResultRow->m_body);
		$objBoardMessage->setMessageTimestamp($objResultRow->m_tstmp);
		$objBoardMessage->setIp($objResultRow->m_ip);
		$objBoardMessage->setSendNotification($objResultRow->m_notification);

		$objUser = new cUser();
		$objUser->setId($objResultRow->m_userid);
		$objUser->setNickName($objResultRow->m_usernickname);
		$objUser->setPublicMail($objResultRow->m_usermail);
		$objUser->setHighlightUser($objResultRow->m_userhighlight);
		$objUser->setFirstName($objResultRow->u_firstname);
		$objUser->setLastName($objResultRow->u_lastname);
		$objUser->setCity($objResultRow->u_city);
		$objUser->setImageFileName($objResultRow->u_imgfile);
		$objUser->setRegistrationTimestamp($objResultRow->u_registrationtstmp);
		$objUser->setLastOnlineTimestamp($objResultRow->u_lastonlinetstmp);
		$objUser->setMessageQuantity($objResultRow->u_msgquantity);
		$objUser->setSignature($objResultRow->u_signature);
		$objBoardMessage->setAuthor($objUser);

		$this->m_arrResultList[] = $objBoardMessage;
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
	function &getDataArray($iTimeOffset,$sDateFormat,$iLastOnlineTimestamp,$sSubjectQuotePrefix,&$objParser){
		$arrReturn = array();

		reset($this->m_arrResultList);
		while(list(,$objBoardMessage) = each($this->m_arrResultList)){
			$arrReturn[] = $objBoardMessage->getDataArray($iTimeOffset,$sDateFormat,$iLastOnlineTimestamp,$sSubjectQuotePrefix,$objParser);
		}
		return $arrReturn;
	}
}
?>