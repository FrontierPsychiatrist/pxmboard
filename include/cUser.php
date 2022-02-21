<?php
require_once(INCLUDEDIR."/cUserStates.php");
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
 * user handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/03/28 21:35:59 $
 * @version $Revision: 1.22 $
 */
class cUser{

	var $m_iId;						// user id
	var $m_sNickName;				// user nickname
	var $m_sPassword;				// user password
	var $m_sPublicMail;				// public mailadress
	var $m_sPrivateMail;			// mailadress for internal use only
	var $m_sRegistrationMail;		// registration mailadress
	var $m_sFirstName;				// first name
	var $m_sLastName;				// last name
	var $m_sCity;					// user city
	var	$m_sSignature;				// signature (will not be loaded in this class)
	var	$m_sImgFileName;			// filename of profile picture
	var $m_iMessageQuantity;		// number of messages
	var $m_iRegistrationTimestamp;	// date of registration
	var $m_iLastOnlineTimestamp;	// last online timestamp
	var	$m_bHighlight;				// highlight user ?
	var	$m_iStatus;					// status of the user ?

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
		$this->m_sNickName = "";
		$this->m_sPassword = "";
		$this->m_sPublicMail = "";
		$this->m_sPrivateMail = "";
		$this->m_sRegistrationMail = "";
		$this->m_sFirstName = "";
		$this->m_sLastName = "";
		$this->m_sCity = "";
		$this->m_sSignature = "";
		$this->m_sImgFileName = "";
		$this->m_iMessageQuantity = 0;
		$this->m_iRegistrationTimestamp = 0;
		$this->m_iLastOnlineTimestamp = 0;
		$this->m_bHighlight = FALSE;
		$this->m_iStatus = cUserStates::userNotActivated();
	}

	/**
	 * get data from database by user id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iUserId user id
	 * @return boolean success / failure
	 */
	function loadDataById($iUserId){

		$bReturn = FALSE;
		$iUserId = intval($iUserId);

		if($iUserId>0){

			global $objDb;

			if($objResultSet = &$objDb->executeQuery("SELECT ".$this->_getDbAttributes()." FROM pxm_user WHERE u_id=".$iUserId)){
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
	 * get data from database by user nickname
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sNickName nickname
	 * @return boolean success / failure
	 */
	function loadDataByNickName($sNickName){

		$bReturn = FALSE;

		if(!empty($sNickName)){

			global $objDb;

			if($objResultSet = &$objDb->executeQuery("SELECT ".$this->_getDbAttributes()." FROM pxm_user WHERE u_nickname=".$objDb->quote($sNickName))){
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
	 * get data from database by user ticket
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sTicket ticket
	 * @return boolean success / failure
	 */
	function loadDataByTicket($sTicket){

		$bReturn = FALSE;

		if(!empty($sTicket)){

			global $objDb;

			if($objResultSet = &$objDb->executeQuery("SELECT ".$this->_getDbAttributes()." FROM pxm_user WHERE u_ticket=".$objDb->quote($sTicket))){
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
	 * get data from database by password key
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sPasswordKey password key
	 * @return boolean success / failure
	 */
	function loadDataByPasswordKey($sPasswordKey){

		$bReturn = FALSE;

		if(!empty($sPasswordKey)){

			global $objDb;

			if($objResultSet = &$objDb->executeQuery("SELECT ".$this->_getDbAttributes()." FROM pxm_user WHERE u_passwordkey=".$objDb->quote($sPasswordKey))){
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

		$this->m_iId = intval($objResultRow->u_id);
		$this->m_sNickName = $objResultRow->u_nickname;
		$this->m_sPassword = $objResultRow->u_password;
		$this->m_sFirstName = $objResultRow->u_firstname;
		$this->m_sLastName = $objResultRow->u_lastname;
		$this->m_sCity = $objResultRow->u_city;
		$this->m_sImgFileName = $objResultRow->u_imgfile;
		$this->m_sPublicMail = $objResultRow->u_publicmail;
		$this->m_sPrivateMail = $objResultRow->u_privatemail;
		$this->m_sRegistrationMail = $objResultRow->u_registrationmail;
		$this->m_iRegistrationTimestamp = intval($objResultRow->u_registrationtstmp);
		$this->m_iLastOnlineTimestamp = intval($objResultRow->u_lastonlinetstmp);
		$this->m_iMessageQuantity = intval($objResultRow->u_msgquantity);
		$this->m_bHighlight = $objResultRow->u_highlight?TRUE:FALSE;
		$this->m_iStatus = intval($objResultRow->u_status);

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
	 	return "u_id,u_nickname,u_password,u_firstname,u_lastname,"
				."u_city,u_imgfile,u_publicmail,u_privatemail,u_registrationmail,"
				."u_registrationtstmp,u_lastonlinetstmp,u_msgquantity,u_highlight,u_status";
	 }

	/**
	 * insert a new user into the database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bUniqueRegistrationMail should the registration email attribute be unique?
	 * @return boolean success / failure
	 */
	function insertData($bUniqueRegistrationMail){

		global $objDb;
		$bReturn = FALSE;

		if($objResultSet = &$objDb->executeQuery("SELECT u_id FROM pxm_user WHERE u_nickname=".$objDb->quote($this->m_sNickName).
														  ($bUniqueRegistrationMail?" OR u_registrationmail=".$objDb->quote($this->m_sRegistrationMail):""))){
			if($objResultSet->getNumRows()<1){
				if($objDb->executeQuery("INSERT INTO pxm_user (u_nickname,u_password,u_privatemail,u_registrationmail,u_registrationtstmp,u_status) ".
												  "VALUES (".$objDb->quote($this->m_sNickName).",".
												  			 $objDb->quote($this->m_sPassword).",".
															 $objDb->quote($this->m_sPrivateMail).",".
															 $objDb->quote($this->m_sRegistrationMail).",".
															 $this->m_iRegistrationTimestamp.",".
															 $this->m_iStatus.")")){
					$this->m_iId = $objDb->getInsertId("pxm_user","u_id");
					$bReturn = TRUE;
				}
			}
		}
		return $bReturn;
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

		global $objDb;
		$bReturn = FALSE;

		if($this->m_iId>0){
			if($objResultSet = &$objDb->executeQuery("UPDATE pxm_user SET u_password=".$objDb->quote($this->m_sPassword).",u_status=".$this->m_iStatus.",u_passwordkey='' WHERE u_id=".$this->m_iId)){
				if($objResultSet->getAffectedRows()>0){
					$bReturn = TRUE;
				}
			}
		}
		return $bReturn;
	}

	/**
	 * delete a user from the database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean success / failure
	 */
	function deleteData(){

		global $objDb;
		$bReturn = FALSE;

		if($objResultSet = &$objDb->executeQuery("DELETE FROM pxm_user WHERE u_id=".$this->m_iId)){
			if($objResultSet->getAffectedRows()>0){
				$bReturn = TRUE;
			}
		}
		return $bReturn;
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
	 * get nickname
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string nickname
	 */
	function getNickName(){
		return $this->m_sNickName;
	}

	/**
	 * set nickname
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sNickName nickname
	 * @return void
	 */
	function setNickName($sNickName){
		$this->m_sNickName = $sNickName;
	}

	/**
	 * get firstname
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string firstname
	 */
	function getFirstName(){
		return $this->m_sFirstName;
	}

	/**
	 * set firstname
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sFirstName firstname
	 * @return void
	 */
	function setFirstName($sFirstName){
		$this->m_sFirstName = $sFirstName;
	}

	/**
	 * get lastname
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string lastname
	 */
	function getLastName(){
		return $this->m_sLastName;
	}

	/**
	 * set firstname
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sLastName lastname
	 * @return void
	 */
	function setLastName($sLastName){
		$this->m_sLastName = $sLastName;
	}

	/**
	 * get city
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string city
	 */
	function getCity(){
		return $this->m_sCity;
	}

	/**
	 * set city
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sCity city
	 * @return void
	 */
	function setCity($sCity){
		$this->m_sCity = $sCity;
	}

	/**
	 * get signature
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string signature
	 */
	function getSignature(){
		return $this->m_sSignature;
	}

	/**
	 * set signature
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sSignature signature
	 * @return void
	 */
	function setSignature($sSignature){
		$this->m_sSignature = $sSignature;
	}

	/**
	 * get profile imagefilename
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string imagefilename
	 */
	function getImageFileName(){
		return $this->m_sImgFileName;
	}

	/**
	 * set profile imagefilename
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sImgFileName imagefilename
	 * @return void
	 */
	function setImageFileName($sImgFileName){
		$this->m_sImgFileName = $sImgFileName;
	}

	/**
	 * add profile image
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sImageDir profile image directory
	 * @param integer $iSplitImageDir split profile image directory after x entries
	 * @param string $sSrcFileName profile image sourcefile
	 * @param string $sImageType filetype (jpg, gif, png)
	 * @return boolean success / failure
	 */
	function addImage($sImageDir,$iSplitImageDir,$sSrcFileName,$sImageType){

		global $objDb;

		$this->deleteImage($sImageDir);

		$sImageDir .= (floor($this->m_iId/$iSplitImageDir)*$iSplitImageDir)."/";

		if(!@is_dir($sImageDir)){
			if(!mkdir($sImageDir,0755)){
				return FALSE;
			}
		}

		if(@move_uploaded_file($sSrcFileName,$sImageDir.$this->m_iId.".".$sImageType)){
			$this->m_sImgFileName = (floor($this->m_iId/$iSplitImageDir)*$iSplitImageDir)."/".$this->m_iId.".".$sImageType;
			if(!$objDb->executeQuery("UPDATE pxm_user SET u_imgfile=".$objDb->quote($this->m_sImgFileName)." WHERE u_id=".$this->m_iId)){
				return FALSE;
			}
		}
		else{
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * add delete profile image
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sImageDir profile image directory
	 * @return boolean success / failure
	 */
	function deleteImage($sImageDir){

		global $objDb;

		if(!empty($this->m_sImgFileName)){
			if(!file_exists($sImageDir.$this->m_sImgFileName) || @unlink($sImageDir.$this->m_sImgFileName)){
				$this->m_sImgFileName = "";
				if($objDb->executeQuery("UPDATE pxm_user SET u_imgfile='' WHERE u_id=".$this->m_iId)){
					return TRUE;
				}
			}
		}
		return FALSE;
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
	 * increment the msgquantity and save it to the database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function incrementMessageQuantity(){

		global $objDb;

		$objDb->executeQuery("UPDATE pxm_user SET u_msgquantity=u_msgquantity+1 WHERE u_id=".$this->m_iId);
		++$this->m_iMessageQuantity;
	}

	/**
	 * get registration timestamp
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer registration timestamp
	 */
	function getRegistrationTimestamp(){
		return $this->m_iRegistrationTimestamp;
	}

	/**
	 * set registration timestamp
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iRegistrationTimestamp registration timestamp
	 * @return void
	 */
	function setRegistrationTimestamp($iRegistrationTimestamp){
		return $this->m_iRegistrationTimestamp = intval($iRegistrationTimestamp);
	}

	/**
	 * get the password
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string password (md5 encrypted)
	 */
	function getPassword(){
		return $this->m_sPassword;
	}

	/**
	 * set password
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sPassword password (md5 encrypted)
	 * @return void
	 */
	function setPassword($sPassword){
		$this->m_sPassword = $sPassword;
	}

	/**
	 * change the password and update the database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sNewPassword new password (not encrypted)
	 * @param string $sNewPasswordConfirm new password confirm (not encrypted)
	 * @return boolean success / failure
	 */
	function changePassword($sNewPassword,$sNewPasswordConfirm){

		$bReturn = FALSE;

		if((strlen($sNewPassword)>2) && (strcmp($sNewPassword,$sNewPasswordConfirm)==0)){

			global $objDb;

			$sNewPassword = md5($sNewPassword);

			if($objDb->executeQuery("UPDATE pxm_user SET u_password=".$objDb->quote($sNewPassword).",u_passwordkey='',u_ticket='' WHERE u_password=".$objDb->quote($this->m_sPassword)." AND u_id=".$this->m_iId)){
				$this->m_sPassword = $sNewPassword;
				$bReturn = TRUE;
			}
		}
		return $bReturn;
	}

	/**
	 * get the user status
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer user status
	 */
	function getStatus(){
		return $this->m_iStatus;
	}

	/**
	 * set the user status
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iStatus user status
	 * @return void
	 */
	function setStatus($iStatus){
		$this->m_iStatus = intval($iStatus);
	}

	/**
	 * update the status of an user
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean success / failure
	 */
	function updateStatus(){

		global $objDb;

		if(!$objDb->executeQuery("UPDATE pxm_user SET u_status=".$this->m_iStatus." WHERE u_id=".$this->m_iId)){
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * get registration mail
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string registration mail
	 */
	function getRegistrationMail(){
		return $this->m_sRegistrationMail;
	}

	/**
	 * validate and set registration mail
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sRegistrationMail registration mail address
	 * @param array $arrForbiddenMails forbidden mail address parts
	 * @return boolean success / failure
	 */
	function setRegistrationMail($sRegistrationMail,$arrForbiddenMails = array()){
		$bReturn = TRUE;

		if($this->_isValidEmail($sRegistrationMail)){
			reset($arrForbiddenMails);
 			while($bReturn && (list(,$sMailPart)=each($arrForbiddenMails))){
				if(preg_match("/".$sMailPart."$/",$sRegistrationMail)){
					$bReturn = FALSE;
				}
			}
			if($bReturn){
				$this->m_sRegistrationMail = $sRegistrationMail;
			}
		}
		else{
			$bReturn = FALSE;
		}
		return $bReturn;
	}

	/**
	 * get private mail
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string private mail
	 */
	function getPrivateMail(){
		return $this->m_sPrivateMail;
	}

	/**
	 * validate and set private mail
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sPrivateMail private mail address
	 * @return boolean success / failure
	 */
	function setPrivateMail($sPrivateMail){
		if($this->_isValidEmail($sPrivateMail)){
			$this->m_sPrivateMail = $sPrivateMail;
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * get public mail
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string public mail
	 */
	function getPublicMail(){
		return $this->m_sPublicMail;
	}

	/**
	 * validate and set public mail
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sPublicMail public mail address
	 * @return boolean success / failure
	 */
	function setPublicMail($sPublicMail){
		if(empty($sPublicMail) || $this->_isValidEmail($sPublicMail)){
			$this->m_sPublicMail = $sPublicMail;
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * get last online timestamp
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer last online timestamp
	 */
	function getLastOnlineTimestamp(){
		return $this->m_iLastOnlineTimestamp;
	}

	/**
	 * set last online timestamp
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iLastOnlineTimestamp last online timestamp
	 * @return void
	 */
	function setLastOnlineTimestamp($iLastOnlineTimestamp){
		return $this->m_iLastOnlineTimestamp = intval($iLastOnlineTimestamp);
	}

	/**
	 * update last online timestamp
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iLastOnlineTimestamp last online timestamp
	 * @return boolean success / failure
	 */
	function updateLastOnlineTimestamp($iLastOnlineTimestamp){

		global $objDb;

		$iLastOnlineTimestamp = intval($iLastOnlineTimestamp);

		if($objDb->executeQuery("UPDATE pxm_user SET u_lastonlinetstmp=".$iLastOnlineTimestamp." WHERE u_id=".$this->m_iId)){
#			$this->m_iLastOnlineTimestamp = $iLastOnlineTimestamp;
		}
		else{
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * should the user be highlighted?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean highlight / don't highlight
	 */
	function highlightUser(){
		return $this->m_bHighlight;
	}

	/**
	 * should the user be highlighted?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param  boolean $bHighlight highlight / don't highlight
	 * @return void
	 */
	function setHighlightUser($bHighlight){
		$this->m_bHighlight = $bHighlight?TRUE:FALSE;
	}

	/**
	 * validate email
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param string $sEmail email address
	 * @return boolean is valid / is not valid
	 */
	function _isValidEmail($sEmail){
		if(!preg_match("/^[0-9a-zA-Z_-]+(\.[0-9a-zA-Z_-]+)*@[0-9a-zA-Z_-]+(\.[0-9a-zA-Z_-]+)*\.[a-zA-Z]{2,4}$/",$sEmail)){
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * generate a new password
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return string password (not encrypted)
	 */
	function generatePassword(){
		$sPassword = "";
  		for($iCharCounter = 1;$iCharCounter<9;$iCharCounter++){
			$sPassword .= chr(mt_rand(97,122));
		}
		$this->m_sPassword = md5($sPassword);
		return $sPassword;
	}

	/**
	 * checks if the password is valid
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param string $sPassword password (not encrypted)
	 * @return boolean valid / invalid
	 */
	function validatePassword($sPassword){
		$bReturn = FALSE;
		if((strlen($sPassword)>0) && (strcmp($this->m_sPassword,md5($sPassword))==0)){
			$bReturn = TRUE;
		}
		return $bReturn;
	}

	/**
	 * create a login ticket and store it in the database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return string ticket
	 */
	function createNewTicket(){

		global $objDb;

		$sTicket = md5(uniqid($this->m_sNickName.mt_rand(),1));
		if($objDb->executeQuery("UPDATE pxm_user SET u_ticket=".$objDb->quote($sTicket)." WHERE u_id=".$this->m_iId)){
			return $sTicket;
		}
		return "";
	}

	/**
	 * create a password key for password retrival and store it in the database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return string password key
	 */
	function createNewPasswordKey(){

		global $objDb;

		$sPasswordKey = md5(uniqid($this->m_sNickName.mt_rand(),1));
		if($objDb->executeQuery("UPDATE pxm_user SET u_passwordkey=".$objDb->quote($sPasswordKey)." WHERE u_id=".$this->m_iId)){
			return $sPasswordKey;
		}
		return "";
	}

	/**
	 * get membervariables as array
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iTimeOffset time offset in seconds
	 * @param string $sDateFormat php date format
	 * @param object $objParser message parser (for signature)
	 * @return array member variables
	 */
	function getDataArray($iTimeOffset,$sDateFormat,&$objParser){
		return array(	"id"		=>	$this->m_iId,
						"nickname"	=>	$this->m_sNickName,
						"email"		=>	$this->m_sPublicMail,
						"fname"		=>	$this->m_sFirstName,
						"lname"		=>	$this->m_sLastName,
						"city"		=>	$this->m_sCity,
						"_signature"=>	$objParser?$objParser->parse($this->m_sSignature):$this->m_sSignature,
						"pic"		=>	$this->m_sImgFileName,
						"msgquan"	=>	$this->m_iMessageQuantity,
						"regdate"	=>	(($this->m_iRegistrationTimestamp>0)?date($sDateFormat,($this->m_iRegistrationTimestamp+$iTimeOffset)):0),
						"highlight"	=>	$this->m_bHighlight,
						"status"	=>	$this->m_iStatus);
	}
}
?>