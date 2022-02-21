<?php
require_once(INCLUDEDIR."/cBoardMessage.php");
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
 * message statistics
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.5 $
 */
class cMessageStatistics{

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function __construct(){
	}

	/**
	 * get the amount of messages
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer amount of messages
	 */
	function &getMessageCount(){

		global $objDb;

		if($objResultSet = &$objDb->executeQuery("SELECT count(*) AS messages FROM pxm_message")){
			if($objResultRow = $objResultSet->getNextResultRowObject()){
				return $objResultRow->messages;
			}
		}
		return 0;
	}

	/**
	 * get the amount of private messages
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer amount of private messages
	 */
	function &getPrivateMessageCount(){

		global $objDb;

		if($objResultSet = &$objDb->executeQuery("SELECT count(*) AS messages FROM pxm_priv_message")){
			if($objResultRow = $objResultSet->getNextResultRowObject()){
				return $objResultRow->messages;
			}
		}
		return 0;
	}

	/**
	 * get the newest messages
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iTimeSpan timespan
	 * @return array newest messages
	 */
	function getNewestMessages($iTimeSpan){
		return $this->_getMessagesByAttribute("m_tstmp","DESC",10,$iTimeSpan);
	}

	/**
	 * get the oldest messages
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array oldest messages
	 */
	function getOldestMessages(){
		return $this->_getMessagesByAttribute("m_tstmp","ASC",10);
	}

	/**
	 * get board messages selected by a passed attribute
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param string $sAttribute db attribute
	 * @param string $sOrder order by (asc|desc)
	 * @param integer $iLimit limit the result to x rows
	 * @param integer $iTimeSpan timespan
	 * @return array boardmessage objects
	 */
	function &_getMessagesByAttribute($sAttribute,$sOrder = "ASC",$iLimit = 1,$iTimeSpan = 0){

		global $objDb;
		$arrBoardMessages = array();

		if($objResultSet = $objDb->executeQuery("SELECT m_id,m_parentid,t_boardid,t_id,t_active,m_subject,m_tstmp,m_userid,m_usernickname,m_usermail,m_userhighlight FROM pxm_board,pxm_thread,pxm_message WHERE b_id=t_boardid AND t_id=m_threadid AND b_active='1' AND m_tstmp>".intval($iTimeSpan)." ORDER BY $sAttribute $sOrder",$iLimit)){
			while($objResultRow = $objResultSet->getNextResultRowObject()){

				$objBoardMessage = new cBoardMessage();

				$objBoardMessage->setId($objResultRow->m_id);
				$objBoardMessage->setParentId($objResultRow->m_parentid);
				$objBoardMessage->setBoardId($objResultRow->t_boardid);
				$objBoardMessage->setThreadId($objResultRow->t_id);
				$objBoardMessage->setIsThreadActive($objResultRow->t_active);
				$objBoardMessage->setSubject($objResultRow->m_subject);
				$objBoardMessage->setMessageTimestamp($objResultRow->m_tstmp);
				$objBoardMessage->setAuthorId($objResultRow->m_userid);
				$objBoardMessage->setAuthorNickName($objResultRow->m_usernickname);
				$objBoardMessage->setAuthorPublicMail($objResultRow->m_usermail);
				$objBoardMessage->setAuthorHighlightUser($objResultRow->m_userhighlight);

				$arrBoardMessages[] = $objBoardMessage;
			}
			$objResultSet->freeResult();
		}
		return $arrBoardMessages;
	}
}
?>