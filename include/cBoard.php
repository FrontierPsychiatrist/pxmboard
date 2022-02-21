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
 * board handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/08/04 17:47:41 $
 * @version $Revision: 1.14 $
 */
class cBoard{

	var	$m_iId;						// board id
	var	$m_sName;					// board name
	var	$m_sDescription;			// board description
	var	$m_iPosition;				// position in boardlist
	var	$m_bIsActive;				// board status
	var	$m_iLastMessageTimestamp;	// timestamp of last message
	var	$m_iThreadListTimeSpan;		// timespan for threadlist
	var	$m_sThreadListSortMode;		// sortmode for threadlist
	var	$m_bParseStyle;				// parse style information in messages
	var	$m_bParseUrl;				// parse urls in messages
	var	$m_bParseImg;				// parse urls in messages
	var $m_bDoTextReplacements;		// do textreplacements

	var	$m_arrModerators;			// array of moderatores (id and name)

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
		$this->m_sName = "";
		$this->m_sDescription = "";
		$this->m_iPosition = 0;
		$this->m_bIsActive = FALSE;
		$this->m_iLastMessageTimestamp = 0;

		$this->m_iThreadListTimeSpan = 7;
		$this->m_sThreadListSortMode = "thread";
		$this->m_bParseStyle = FALSE;
		$this->m_bParseUrl = FALSE;
		$this->m_bParseImg = FALSE;
		$this->m_bDoTextReplacements = FALSE;

		$this->m_arrModerators = array();
	}

	/**
	 * get data from database by board id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iBoardId board id
	 * @return boolean success / failure
	 */
	function loadDataById($iBoardId){

		$bReturn = FALSE;
		$iBoardId = intval($iBoardId);

		if($iBoardId>0){

			global $objDb;

			if($objResultSet = &$objDb->executeQuery("SELECT b_id,".
																	  "b_name,".
																	  "b_description,".
																	  "b_position,".
																	  "b_active,".
																	  "b_lastmsgtstmp,".
																	  "b_timespan,".
																	  "b_threadlistsort,".
																	  "b_parsestyle,".
																	  "b_parseurl,".
																	  "b_parseimg,".
																	  "b_replacetext ".
																"FROM pxm_board WHERE b_id=$iBoardId")){
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

		$this->m_iId = intval($objResultRow->b_id);
		$this->m_sName = $objResultRow->b_name;
		$this->m_sDescription = $objResultRow->b_description;
		$this->m_iPosition = intval($objResultRow->b_position);
		$this->m_bIsActive = $objResultRow->b_active?TRUE:FALSE;
		$this->m_iLastMessageTimestamp = intval($objResultRow->b_lastmsgtstmp);
		$this->m_iThreadListTimeSpan = intval($objResultRow->b_timespan);
		$this->m_sThreadListSortMode = $objResultRow->b_threadlistsort;
		$this->m_bParseStyle = $objResultRow->b_parsestyle?TRUE:FALSE;
		$this->m_bParseUrl = $objResultRow->b_parseurl?TRUE:FALSE;
		$this->m_bParseImg = $objResultRow->b_parseimg?TRUE:FALSE;
		$this->m_bDoTextReplacements = $objResultRow->b_replacetext?TRUE:FALSE;

		return TRUE;
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

		$this->m_arrModerators = array();

		if($objResultSet = &$objDb->executeQuery("SELECT u_id,u_nickname,u_publicmail,u_highlight FROM pxm_moderator,pxm_user WHERE mod_userid=u_id AND mod_boardid=$this->m_iId")){
			while($objResultRow = $objResultSet->getNextResultRowObject()){

				$objUser = new cUser();
				$objUser->setId($objResultRow->u_id);
				$objUser->setNickName($objResultRow->u_nickname);
				$objUser->setPublicMail($objResultRow->u_publicmail);
				$objUser->setHighlightUser($objResultRow->u_highlight);

				$this->m_arrModerators[] = $objUser;
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

		if($objDb->executeQuery("DELETE FROM pxm_moderator WHERE mod_boardid=$this->m_iId")){
			reset($this->m_arrModerators);
			foreach($this->m_arrModerators as $objUser) {
				$objDb->executeQuery("INSERT INTO pxm_moderator (mod_userid,mod_boardid) VALUES (".$objUser->getId().",$this->m_iId)");
			}
		}
		else return FALSE;

		return TRUE;
	}

	/**
	 * insert new data into database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean success / failure
	 */
	function insertData(){

		global $objDb;

		if($objDb->executeQuery("INSERT INTO pxm_board (b_name,b_description,b_active,b_timespan,b_threadlistsort,b_parsestyle,b_parseurl,b_parseimg,b_replacetext) "
										 ."VALUES ('".addslashes($this->m_sName)."','".addslashes($this->m_sDescription)."',".intval($this->m_bIsActive).",$this->m_iThreadListTimeSpan,"
												 ."'".addslashes($this->m_sThreadListSortMode)."',".intval($this->m_bParseStyle).",".intval($this->m_bParseUrl).",".intval($this->m_bParseImg).",".intval($this->m_bDoTextReplacements).")")){
			$this->m_iId = $objDb->getInsertId("pxm_board","b_id");
			$objDb->executeQuery("UPDATE pxm_board SET b_position=b_id WHERE b_id=".$this->m_iId);
		}
		else{
			return FALSE;
		}
		return TRUE;
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
			if($objDb->executeQuery("UPDATE pxm_board SET b_name='".addslashes($this->m_sName)."',"
																."b_description='".addslashes($this->m_sDescription)."',"
																."b_position=$this->m_iPosition,"
																."b_active=".intval($this->m_bIsActive).","
																."b_timespan=$this->m_iThreadListTimeSpan,"
																."b_threadlistsort='".addslashes($this->m_sThreadListSortMode)."',"
																."b_parsestyle=".intval($this->m_bParseStyle).","
																."b_parseurl=".intval($this->m_bParseUrl).","
																."b_parseimg=".intval($this->m_bParseImg).","
																."b_replacetext=".intval($this->m_bDoTextReplacements)." WHERE b_id=$this->m_iId")){
				$bReturn = TRUE;
			}
		}
		return $bReturn;
	}

	/**
	 * delete data from database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @return boolean success / failure
	 */
	function deleteData(){

		global $objDb;

		if($this->m_iId>0){
			if($objResultSet = &$objDb->executeQuery("SELECT t_id FROM pxm_thread WHERE t_boardid=$this->m_iId")){
				while($objResultRow = $objResultSet->getNextResultRowObject()){
					$objDb->executeQuery("DELETE FROM pxm_message WHERE m_threadid=$objResultRow->t_id");
				}
				$objDb->executeQuery("DELETE FROM pxm_thread WHERE t_boardid=$this->m_iId");
				$objDb->executeQuery("DELETE FROM pxm_moderator WHERE mod_boardid=$this->m_iId");
				$objDb->executeQuery("DELETE FROM pxm_board WHERE b_id=$this->m_iId");
			}
		}
		else{
			return FALSE;
		}

		return TRUE;
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
	 * get name
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string name
	 */
	function getName(){
		return $this->m_sName;
	}

	/**
	 * set name
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sName name
	 * @return void
	 */
	function setName($sName){
		$this->m_sName = $sName;
	}

	/**
	 * get description
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string description
	 */
	function getDescription(){
		return $this->m_sDescription;
	}

	/**
	 * set description
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sDescription description
	 * @return void
	 */
	function setDescription($sDescription){
		$this->m_sDescription = $sDescription;
	}

	/**
	 * get position
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer position
	 */
	function getPosition(){
		return $this->m_iPosition;
	}

	/**
	 * set position
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iPosition position
	 * @return void
	 */
	function setPosition($iPosition){
		$this->m_iPosition = intval($iPosition);
	}

	/**
	 * update position
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iPosition position
	 * @return void
	 */
	function updatePosition($iPosition){

		global $objDb;

		$iPosition = intval($iPosition);

		if($iPosition>0 && $this->m_iPosition!=$iPosition){
			if($this->m_iPosition>$iPosition){
				$objDb->executeQuery("UPDATE pxm_board SET b_position = b_position+1 WHERE b_position >= $iPosition AND b_position < $this->m_iPosition");
			}
			else{
				$objDb->executeQuery("UPDATE pxm_board SET b_position = b_position-1 WHERE b_position <= $iPosition AND b_position > $this->m_iPosition");
			}
			$this->m_iPosition = $iPosition;
			$objDb->executeQuery("UPDATE pxm_board SET b_position = $this->m_iPosition WHERE b_id = $this->m_iId");
		}
	}

	/**
	 *  ist the board active (open)?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean ist the board active?
	 */
	function isActive(){
		return $this->m_bIsActive;
	}

	/**
	 *  set status of the board (open / closed)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bIsActive should the board be activated?
	 * @return void
	 */
	function setIsActive($bIsActive){
		$this->m_bIsActive = $bIsActive?TRUE:FALSE;
	}

	/**
	 *  update status of the board (open / closed)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bIsActive should the board be activated?
	 * @return boolean success / failure
	 */
	function updateIsActive($bIsActive){

		global $objDb;

		if(!$objDb->executeQuery("UPDATE pxm_board SET b_active=".intval($bIsActive)." WHERE b_id=$this->m_iId")){
			return FALSE;
		}
		$this->m_bIsActive = $bIsActive?TRUE:FALSE;
		return TRUE;
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
	 * get threads per page
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer threads per page
	 */
	function getThreadsPerPage(){
		return $this->m_iThreadsPerPage;
	}

	/**
	 * set threads per page
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iThreadsPerPage threads per page
	 * @return void
	 */
	function setThreadsPerPage($iThreadsPerPage){
		$this->m_iThreadsPerPage = intval($iThreadsPerPage);
	}

	/**
	 * get threadlist timespan
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer threadlist timespan
	 */
	function getThreadListTimeSpan(){
		return $this->m_iThreadListTimeSpan;
	}

	/**
	 * set threadlist timespan
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iThreadListTimeSpan threadlist timespan
	 * @return void
	 */
	function setThreadListTimeSpan($iThreadListTimeSpan){
		$this->m_iThreadListTimeSpan = intval($iThreadListTimeSpan);
	}

	/**
	 * get threadlist sort mode
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string threadlist sort mode
	 */
	function getThreadListSortMode(){
		return $this->m_sThreadListSortMode;
	}

	/**
	 * set threadlist sort mode
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sThreadListSortMode threadlist sort mode
	 * @return void
	 */
	function setThreadListSortMode($sThreadListSortMode){
		$this->m_sThreadListSortMode = $sThreadListSortMode;
	}

	/**
	 * parse style tags?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean parse style tags?
	 */
	function parseStyle(){
		return $this->m_bParseStyle;
	}

	/**
	 * set parse style tags
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bParseStyle parse style tags?
	 * @return void
	 */
	function setParseStyle($bParseStyle){
		$this->m_bParseStyle = $bParseStyle?TRUE:FALSE;
	}

	/**
	 * parse url tags?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean parse url tags?
	 */
	function parseUrl(){
		return $this->m_bParseUrl;
	}

	/**
	 * set parse url tags
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bParseUrl parse url tags?
	 * @return void
	 */
	function setParseUrl($bParseUrl){
		$this->m_bParseUrl = $bParseUrl?TRUE:FALSE;
	}

	/**
	 * parse img tags?
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
	 * set parse img tags
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
	 * get moderators
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array moderators
	 */
	function &getModerators(){
		return $this->m_arrModerators;
	}

	/**
	 * set moderators
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param array $arrModeratorNickNames nicknames of moderators
	 * @return void
	 */
	function setModeratorsByNickName($arrModeratorNickNames){

		$this->m_arrModerators = array();

		foreach($arrModeratorNickNames as $sNickName){
			$sNickName = trim($sNickName);
			if(!empty($sNickName)){
				$objUser = new cUser();
				if($objUser->loadDataByNickName($sNickName)){
					$this->m_arrModerators[] = $objUser;
				}
			}
		}
	}

	/**
	 * get membervariables as array
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iTimeOffset time offset in seconds
	 * @param string $sDateFormat php date format
	 * @param integer $iLastOnlineTimestamp last online timestamp for user
	 * @param object $objParser message parser (for signature)
	 * @return array member variables
	 */
	function getDataArray($iTimeOffset,$sDateFormat,$iLastOnlineTimestamp,&$objParser){
		$arrModerators = array();
		reset($this->m_arrModerators);
		foreach($this->m_arrModerators as $objUser) {
			$arrModerators[] = $objUser->getDataArray($iTimeOffset,$sDateFormat,$objParser);
		}

		return array("id"		=>	$this->m_iId,
					 "name"		=>	$this->m_sName,
					 "desc"		=>	$this->m_sDescription,
					 "position"	=>	$this->m_iPosition,
					 "lastmsg"	=>	(($this->m_iLastMessageTimestamp>0)?date($sDateFormat,($this->m_iLastMessageTimestamp+$iTimeOffset)):0),
					 "new"		=>	(($iLastOnlineTimestamp>$this->m_iLastMessageTimestamp)?0:1),
					 "active"	=>	$this->m_bIsActive?"1":"0",
					 "moderator"=>	$arrModerators);
	}
}
?>