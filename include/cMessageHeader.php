<?php
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
 * messageheader handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.8 $
 */
class cMessageHeader{

	var $m_iId;					// message id
	var $m_objAuthor;			// author (user)
	var $m_sSubject;			// message subject
	var $m_iMessageTimestamp;	// date of the message

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function __construct(){

		$this->m_iId = 0;
		$this->m_objAuthor = new cUser();
		$this->m_sSubject = "";
		$this->m_iMessageTimestamp = 0;
	}

	/**
	 * get data from database by message id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iMessageId message id
	 * @return boolean success / failure
	 */
	function loadDataById($iMessageId, ?int $iBoardId){

		$bReturn = FALSE;
		$iMessageId = intval($iMessageId);

		if($iMessageId>0){

			global $objDb;

			if($objResultSet = &$objDb->executeQuery("SELECT m_id,".
															 "m_subject,".
															 "m_tstmp,".
															 "m_userid,".
															 "m_usernickname,".
															 "m_usermail,".
															 "m_userhighlight".
															 $this->_getDbAttributes().
															 " FROM ".
															 $this->_getDbTables().
															 " WHERE m_id=".$iMessageId.
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

		$this->m_iId = intval($objResultRow->m_id);
		$this->m_sSubject = $objResultRow->m_subject;
		$this->m_iMessageTimestamp = intval($objResultRow->m_tstmp);

		$this->m_objAuthor->setId($objResultRow->m_userid);
		$this->m_objAuthor->setNickName($objResultRow->m_usernickname);
		$this->m_objAuthor->setPublicMail($objResultRow->m_usermail);
		$this->m_objAuthor->setHighlightUser($objResultRow->m_userhighlight);

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
	 	return "pxm_message";
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
	 * get subject
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sSubjectQuotePrefix prefix for quoted subject
	 * @return string subject
	 */
	function getSubject($sSubjectQuotePrefix = ""){
		if(!empty($sSubjectQuotePrefix) && (strncasecmp($this->m_sSubject,$sSubjectQuotePrefix,strlen($sSubjectQuotePrefix))!=0)){
			return $sSubjectQuotePrefix.$this->m_sSubject;
		}
		return $this->m_sSubject;
	}

	/**
	 * set subject
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sSubject subject
	 * @return void
	 */
	function setSubject($sSubject){
		$this->m_sSubject = $sSubject;
	}

	/**
	 * get message timestamp
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer message timestamp
	 */
	function getMessageTimestamp(){
		return $this->m_iMessageTimestamp;
	}

	/**
	 * set message timestamp
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iMessageTimestamp message timestamp
	 * @return void
	 */
	function setMessageTimestamp($iMessageTimestamp){
		$this->m_iMessageTimestamp = intval($iMessageTimestamp);
	}

	/**
	 * get author (user)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return object author (user)
	 */
	function &getAuthor(){
		return $this->m_objAuthor;
	}

	/**
	 * set author (user)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param object $objAuthor author (user)
	 * @return void
	 */
	function setAuthor(&$objAuthor){
		if(strcasecmp(get_class($objAuthor),"cuser") == 0 || is_subclass_of($objAuthor,"cUser")){
			$this->m_objAuthor = &$objAuthor;
		}
	}

	/**
	 * get author id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer author id
	 */
	function getAuthorId(){
		return $this->m_objAuthor->getId();
	}

	/**
	 * set author id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iAuthorId author id
	 * @return void
	 */
	function setAuthorId($iAuthorId){
		$this->m_objAuthor->setId($iAuthorId);
	}

	/**
	 * set author nickname
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sAuthorNickName author nickname
	 * @return void
	 */
	function setAuthorNickName($sAuthorNickName){
		$this->m_objAuthor->setNickName($sAuthorNickName);
	}

	/**
	 * set author public mail
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sAuthorPublicMail author public mail
	 * @return void
	 */
	function setAuthorPublicMail($sAuthorPublicMail){
		$this->m_objAuthor->setPublicMail($sAuthorPublicMail);
	}

	/**
	 * set author highlight user
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bAuthorHighlightUsers author highlight user
	 * @return void
	 */
	function setAuthorHighlightUser($bAuthorHighlightUser){
		$this->m_objAuthor->setHighlightUser($bAuthorHighlightUser);
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
	function getDataArray($iTimeOffset,$sDateFormat,$iLastOnlineTimestamp,$sSubjectQuotePrefix,$objParser){
		return array("id"		=>	$this->m_iId,
					 "subject"	=>	$this->getSubject($sSubjectQuotePrefix),
					 "date"		=>	(($this->m_iMessageTimestamp>0)?date($sDateFormat,($this->m_iMessageTimestamp+$iTimeOffset)):0),
					 "new"		=>	(($iLastOnlineTimestamp>$this->m_iMessageTimestamp)?0:1),
					 "user"		=>	$this->m_objAuthor->getDataArray($iTimeOffset,$sDateFormat,$objParser));
	}
}
?>