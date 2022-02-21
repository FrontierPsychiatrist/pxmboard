<?php
require_once(INCLUDEDIR."/cUserProfile.php");
require_once(INCLUDEDIR."/cBoard.php");
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
 * admin user handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:48 $
 * @version $Revision: 1.11 $
 */
class cUserAdmin extends cUserProfile{

	var $m_bPost;						// post allowed ?
	var $m_bEdit;						// edit allowed ?
	var $m_bIsAdmin;					// is administrator ?
	var $m_arrModeratedBoards;			// boards moderated by current user

	var $m_bIsVisible;					// user visible? (online list)
	var $m_iSkinId;						// skin id
	var $m_iTopFrameSize;				// size of top frame
	var $m_iBottomFrameSize;			// size of bottom frame
	var $m_sThreadListSortMode;			// sort mode for threadlist
	var $m_iTimeOffset;					// timeoffset
	var	$m_bParseImg;					// parse images
	var $m_bDoTextReplacements;			// do textreplacements
	var $m_bPrivateMessageNotification;	// send private message notification
	var $m_bShowSignatures;				// show signatures

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param array $arrAddFields additional profile fields
	 * @return void
	 */
	function cUserAdmin($arrAddFields = array()){

		cUserProfile::cUserProfile($arrAddFields);

		$this->m_bPost = FALSE;
		$this->m_bEdit = FALSE;

		$this->m_bIsAdmin = FALSE;
		
		$this->m_arrModeratedBoards = array();

		$this->m_bIsVisible	= TRUE;
		$this->m_iSkinId = 0;
		$this->m_iTopFrameSize = 0;
		$this->m_iBottomFrameSize = 0;
		$this->m_sThreadListSortMode = "";
		$this->m_iTimeOffset = 0;
		$this->m_bParseImg = FALSE;
		$this->m_bDoTextReplacements = FALSE;
		$this->m_bPrivateMessageNotification = FALSE;
		$this->m_bShowSignatures = FALSE;
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

		cUserProfile::_setDataFromDb($objResultRow);

		$this->m_bPost = $objResultRow->u_post?TRUE:FALSE;
		$this->m_bEdit = $objResultRow->u_edit?TRUE:FALSE;
		$this->m_bIsAdmin = $objResultRow->u_admin?TRUE:FALSE;

		$this->m_bIsVisible	= $objResultRow->u_visible?TRUE:FALSE;
		$this->m_iSkinId = intval($objResultRow->u_skinid);
		$this->m_iTopFrameSize = intval($objResultRow->u_frame_top);
		$this->m_iBottomFrameSize = intval($objResultRow->u_frame_bottom);
		$this->m_sThreadListSortMode = $objResultRow->u_threadlistsort;
		$this->m_iTimeOffset = intval($objResultRow->u_timeoffset);
		$this->m_bParseImg = $objResultRow->u_parseimg?TRUE:FALSE;
		$this->m_bDoTextReplacements = $objResultRow->u_replacetext?TRUE:FALSE;
		$this->m_bPrivateMessageNotification = $objResultRow->u_privatenotification?TRUE:FALSE;
		$this->m_bShowSignatures = $objResultRow->u_showsignatures?TRUE:FALSE;

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
	 	return cUserProfile::_getDbAttributes()
				.",u_post,u_edit,u_admin,u_visible,u_skinid,u_frame_top,u_frame_bottom,u_threadlistsort,"
				."u_timeoffset,u_parseimg,u_replacetext,u_privatenotification,u_showsignatures";
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
		$sAddUpdateQuery = "";

		foreach($this->m_arrAddData as $sFieldName => $mData){
			if(is_integer($mData)){
				$sAddUpdateQuery .= "u_profile_".$sFieldName."=".$this->m_arrAddData[$sFieldName].",";
			}
			else{
				$sAddUpdateQuery .= "u_profile_".$sFieldName."='".addslashes($this->m_arrAddData[$sFieldName])."',";
			}
		}

		if($objResultSet = &$objDb->executeQuery("UPDATE pxm_user SET u_nickname='".addslashes($this->m_sNickName)."',".
														 					  "u_firstname='".addslashes($this->m_sFirstName)."',".
																		 	  "u_lastname='".addslashes($this->m_sLastName)."',".
																		 	  "u_city='".addslashes($this->m_sCity)."',".
																		 	  "u_publicmail='".addslashes($this->m_sPublicMail)."',".
																		 	  "u_privatemail='".addslashes($this->m_sPrivateMail)."',".
																		 	  "u_signature='".addslashes($this->m_sSignature)."',".
																		 	  $sAddUpdateQuery.
																		 	  "u_highlight=".intval($this->m_bHighlight).",".
																		 	  "u_status=".$this->m_iStatus.",".
																		 	  "u_post=".intval($this->m_bPost).",".
																		 	  "u_edit=".intval($this->m_bEdit).",".
																		 	  "u_admin=".intval($this->m_bIsAdmin).",".
																			  "u_visible=".intval($this->m_bIsVisible).",".
																		 	  "u_skinid=".$this->m_iSkinId.",".
																		 	  "u_frame_top=".$this->m_iTopFrameSize.",".
																		 	  "u_frame_bottom=".$this->m_iBottomFrameSize.",".
																		 	  "u_threadlistsort='".addslashes($this->m_sThreadListSortMode)."',".
																		 	  "u_timeoffset=".$this->m_iTimeOffset.",".
																		 	  "u_parseimg=".intval($this->m_bParseImg).",".
																		 	  "u_replacetext=".intval($this->m_bDoTextReplacements).",".
																			  "u_privatenotification=".intval($this->m_bPrivateMessageNotification).",".
																			  "u_showsignatures=".intval($this->m_bShowSignatures).
																	 " WHERE u_id=".$this->m_iId)){
#			if($objResultSet->getAffectedRows()>0){
				$bReturn = TRUE;
#			}
		}
		return $bReturn;
	}

	/**
	 * load moderator data from database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean success / failure
	 */
	function loadModData(){

		global $objDb;

		$this->m_arrModeratedBoards = array();

		if($objResultSet = &$objDb->executeQuery("SELECT b_id,b_name FROM pxm_moderator,pxm_board WHERE mod_boardid=b_id AND mod_userid=$this->m_iId")){
			while($objResultRow = $objResultSet->getNextResultRowObject()){

				$objBoard = new cBoard();
				$objBoard->setId($objResultRow->b_id);
				$objBoard->setName($objResultRow->b_name);

				$this->m_arrModeratedBoards[] = $objBoard;
			}
			$objResultSet->freeResult();
		}
		else return FALSE;

		return TRUE;
	}

	/**
	 * save moderator data to database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return boolean success / failure
	 */
	function updateModData(){

		global $objDb;

		if($objDb->executeQuery("DELETE FROM pxm_moderator WHERE mod_userid=$this->m_iId")){
			reset($this->m_arrModeratedBoards);
			while(list(,$objBoard) = each($this->m_arrModeratedBoards)){
				$objDb->executeQuery("INSERT INTO pxm_moderator (mod_boardid,mod_userid) VALUES (".$objBoard->getId().",$this->m_iId)");
			}
		}
		else return FALSE;

		return TRUE;
	}

	/**
	 * allowed to post messages?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean posting new messages allowed?
	 */
	function isPostAllowed(){
		return $this->m_bPost;
	}

	/**
	 * set allowed to post messages?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bPost posting new messages allowed?
	 * @return void
	 */
	function setPostAllowed($bPost){
		$this->m_bPost = $bPost?TRUE:FALSE;
	}

	/**
	 * allowed to edit messages?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean edit messages allowed?
	 */
	function isEditAllowed(){
		return $this->m_bEdit;
	}

	/**
	 * set allowed to edit messages?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bEdit edit messages allowed?
	 * @return void
	 */
	function setEditAllowed($bEdit){
		$this->m_bEdit = $bEdit?TRUE:FALSE;
	}

	/**
	 * is an admin?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean is admin?
	 */
	function isAdmin(){
		return $this->m_bIsAdmin;
	}

	/**
	 * set admin flag
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bIsAdmin is admin?
	 * @return void
	 */
	function setAdmin($bIsAdmin){
		$this->m_bIsAdmin = $bIsAdmin?TRUE:FALSE;
	}

	/**
	 * is visible in online list?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean visible / invisible
	 */
	function isVisible(){
		return $this->m_bIsVisible;
	}

	/**
	 * set the visibility of the user in the onlinelist
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bIsVisible visible / invisible
	 * @return void
	 */
	function setIsVisible($bIsVisible){
		$this->m_bIsVisible = $bIsVisible?TRUE:FALSE;
	}

	/**
	 * get skin id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer skin id
	 */
	function getSkinId(){
		return $this->m_iSkinId;
	}

	/**
	 * set skin id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iSkinId skin id
	 * @return void
	 */
	function setSkinId($iSkinId){
		$iSkinId = intval($iSkinId);
		global $objDb;
		if($objResultSet = &$objDb->executeQuery("SELECT s_id FROM pxm_skin WHERE s_id=".$iSkinId." AND s_fieldname='name'")){
			if($objResultSet->getNumRows()>0){
				$this->m_iSkinId = $iSkinId;
			}
		}
	}

	/**
	 * get top frame size
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer top frame size
	 */
	function getTopFrameSize(){
		return $this->m_iTopFrameSize;
	}

	/**
	 * get bottom frame size
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer bottom frame size
	 */
	function getBottomFrameSize(){
		return $this->m_iBottomFrameSize;
	}

	/**
	 * set frame size
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iTopFrameSize top frame size
	 * @param integer $iBottomFrameSize bottom frame size
	 * @return void
	 */
	function setFrameSize($iTopFrameSize,$iBottomFrameSize){
		$iTopFrameSize = intval($iTopFrameSize);
		$iBottomFrameSize = intval($iBottomFrameSize);
		if(($iTopFrameSize+$iBottomFrameSize)<=100){
			$this->m_iTopFrameSize = $iTopFrameSize;
			$this->m_iBottomFrameSize = $iBottomFrameSize;
		}
	}

	/**
	 * get sort mode for threadlist
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string sort mode for threadlist
	 */
	function getThreadListSortMode(){
		return $this->m_sThreadListSortMode;
	}

	/**
	 * set sort mode for threadlist
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sThreadListSortMode sort mode for threadlist
	 * @return void
	 */
	function setThreadListSortMode($sThreadListSortMode){
		$this->m_sThreadListSortMode = $sThreadListSortMode;
	}

	/**
	 * get time offset
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer time offset
	 */
	function getTimeOffset(){
		return $this->m_iTimeOffset;
	}

	/**
	 * set time offset
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iTimeOffset time offset
	 * @return void
	 */
	function setTimeOffset($iTimeOffset){
		$iTimeOffset = intval($iTimeOffset);
		if(($iTimeOffset<13) && ($iTimeOffset>-13)){
			$this->m_iTimeOffset = $iTimeOffset;
		}
	}

	/**
	 * parse image tags in messages?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean parse / don't parse image tags
	 */
	function parseImages(){
		return $this->m_bParseImg;
	}

	/**
	 * set parse images flag
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bParseImages parse / don't parse image tags
	 * @return void
	 */
	function setParseImages($bParseImages){
		$this->m_bParseImg = $bParseImages?TRUE:FALSE;
	}

	/**
	 * do textreplacements?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean do textreplacements?
	 */
	function doTextReplacements(){
		return $this->m_bDoTextReplacements;
	}

	/**
	 * set do textreplacements
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bDoTextReplacements do textreplacements?
	 * @return void
	 */
	function setDoTextReplacements($bDoTextReplacements){
		$this->m_bDoTextReplacements = $bDoTextReplacements?TRUE:FALSE;
	}

	/**
	 * send private message notification?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean send a notification?
	 */
	function sendPrivateMessageNotification(){
		return $this->m_bPrivateMessageNotification;
	}

	/**
	 * set send private message notification
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bPrivateMessageNotification send a notification?
	 * @return void
	 */
	function setSendPrivateMessageNotification($bPrivateMessageNotification){
		$this->m_bPrivateMessageNotification = $bPrivateMessageNotification?TRUE:FALSE;
	}

	/**
	 * show signatures?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean show signatures?
	 */
	function showSignatures(){
		return $this->m_bShowSignatures;
	}

	/**
	 * set show signatures
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bShowSignatures show signatures
	 * @return void
	 */
	function setShowSignatures($bShowSignatures){
		$this->m_bShowSignatures = $bShowSignatures?TRUE:FALSE;
	}

	/**
	 * get moderated boards
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array moderated boards
	 */
	function &getModeratedBoards(){
		return $this->m_arrModeratedBoards;
	}

	/**
	 * set moderated boards
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param array $arrModeratedBoards moderated boards
	 * @return void
	 */
	function setModeratedBoardsById($arrModeratedBoards){

		$this->m_arrModeratedBoards = array();

		foreach($arrModeratedBoards as $iBoardId){
			$objBoard = new cBoard();
			if($objBoard->loadDataById($iBoardId)){
				$this->m_arrModeratedBoards[] = $objBoard;
			}
		}
	}
}
?>