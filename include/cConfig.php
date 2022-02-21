<?php
require_once(INCLUDEDIR."/cUserStates.php");
require_once(INCLUDEDIR."/cSession.php");
require_once(INCLUDEDIR."/cSkin.php");
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
 * configuration handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.14 $
 */
class cConfig{

	var $m_arrAvailableTemplateEngines;			// available template engines
	var $m_sActiveTemplateEngine;				// active template engine, depending on installed engines and skin configuration
	var $m_objActiveBoard;						// active board
	var $m_objActiveUser;						// active user
	var $m_objActiveSkin;						// active skin
	var $m_objSession;							// session

	var $m_iAccessTimestamp;					// current timestamp

	var $m_iDefaultSkinId;						// default skin id
	var $m_sSkinDir;							// skin directory
	var $m_bUseBanners;							// use banners?
	var $m_bUseQuickPost;						// activate quickpost?
	var $m_bUseGuestPost;						// are guests allowed to post?
	var $m_bUseDirectRegistration;				// activate direct registratiom?
	var $m_bUniqueRegistrationMails;			// unique registration mail?
	var $m_bParseUrl;							// parse urls
	var $m_bParseStyle;							// parse style tags
	var $m_bUseSignatures;						// use usersignatures?
	var $m_bCountViews;							// count thread views
	var $m_sDateFormat;							// string for php date function
	var $m_iTimeOffset;							// date & time offset in hours
	var $m_iOnlineTime;							// time that a user will be visible in onlinelist in seconds

	var	$m_iThreadSizeLimit;					// close threads with at least x messages
	var $m_iUserPerPage;						// display x user per page
	var $m_iMessageHeaderPerPage;				// display x messages per page (search)
	var $m_iMessagesPerPage;					// display x messages per page (flat mode)
	var $m_iPrivateMessagesPerPage;				// display x private messages per page
	var $m_iThreadsPerPage;						// display x threads per page

	var $m_sQuoteChar;							// character in front of quoted text
	var $m_sQuoteSubject;						// prefix for quoted subjects

	var $m_sMailWebmaster;						// mail of webmaster

	var $m_iMaxProfileImgSize;					// size of profile images in bytes
	var $m_iMaxProfileImgWidth;					// width of profile images
	var $m_iMaxProfileImgHeight;				// height of profile images
	var $m_sProfileImgDir;						// profile images directory
	var $m_iProfileImgSplitDir;					// one directory for x profile images
	var $m_arrProfileImgTypes;					// accepted filetypes for profile images

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param object $objActiveBoard active board
	 * @param string $arrTemplateEngines available template engine ordered by priority
	 * @return void
	 */
	function __construct(&$objActiveBoard, &$arrTemplateEngines){

		$this->m_objActiveBoard = &$objActiveBoard;

		$this->m_arrAvailableTemplateEngines = &$arrTemplateEngines;
		$this->m_sActiveTemplateEngine = "";

		$this->m_objActiveUser = NULL;

		$this->m_objActiveSkin = NULL;

		// initialize general configuration

		// defaults
		$this->m_iAccessTimestamp = time();

		$this->m_iDefaultSkinId = 0;
		$this->m_sSkinDir = "skins/";
		$this->m_bUseBanners = FALSE;
		$this->m_bUseQuickPost = FALSE;
		$this->m_bUseGuestPost = FALSE;
		$this->m_bUseDirectRegistration = FALSE;
		$this->m_bUniqueRegistrationMails = FALSE;
		$this->m_bParseUrl = FALSE;
		$this->m_bParseStyle = FALSE;
		$this->m_bUseSignatures =FALSE;
		$this->m_bCountViews = FALSE;
		$this->m_sDateFormat = "j.m.Y H:i";
		$this->m_iTimeOffset = 0;
		$this->m_iOnlineTime = 300;

		$this->m_iThreadSizeLimit = 500;
		$this->m_iUserPerPage = 20;
		$this->m_iMessageHeaderPerPage = 20;
		$this->m_iMessagesPerPage = 20;
		$this->m_iPrivateMessagesPerPage = 20;
		$this->m_iThreadsPerPage = 50;

		$this->m_sQuoteChar = ">";
		$this->m_sQuoteSubject = "Re:";

		$this->m_sMailWebmaster	= "";

		$this->m_iMaxProfileImgSize = 0;
		$this->m_iMaxProfileImgWidth = 100;
		$this->m_iMaxProfileImgHeight = 150;
		$this->m_sProfileImgDir = "";
		$this->m_iProfileImgSplitDir = 100;
		$this->m_arrProfileImgTypes = array("image/jpeg" => "jpg","image/pjpeg" => "jpg","image/gif" => "gif","image/png" => "png");

		// get general configuration from database
		$this->_loadData();

		// initialize session
		$this->m_objSession = new cSession("brdsid");

		// initialize active user
		if($this->m_objSession->sessionDataAvailable()){
			include_once(INCLUDEDIR."/cUserConfig.php");
			$this->m_objSession->startSession();
			$objActiveUser = &$this->m_objSession->getSessionVar("activeuser");
			if(is_object($objActiveUser)){
				if(intval($this->m_objSession->getSessionVar("rightstimestamp"))<$this->m_iAccessTimestamp-600){
					$objActiveUser->refreshRights();
					$this->m_objSession->setSessionVar("rightstimestamp",$this->m_iAccessTimestamp);
				}
				if($objActiveUser->getStatus()==cUserStates::userActive()){
					$this->m_objActiveUser = &$objActiveUser;
					if($this->m_iOnlineTime>0){
						$this->m_objActiveUser->updateLastOnlineTimestamp($this->m_iAccessTimestamp);
					}
				}
				else{
					$this->m_objSession->destroySession();
				}
			}
			else{
				$this->m_objSession->destroySession();
			}
		}
	}

	/**
	 * initialize the skin for the output
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return boolean success / failure
	 */
	function initSkin(){

		$bReturn = TRUE;

		if(is_object($this->m_objActiveUser) && $this->m_objActiveUser->getSkinId()>0){
			$iSkinId = $this->m_objActiveUser->getSkinId();
		}
		else{
			$iSkinId = $this->m_iDefaultSkinId;
		}

		$this->m_objActiveSkin = new cSkin();
		if(!$this->m_objActiveSkin->loadDataById($iSkinId)
		|| !($arrValidTemplateEngines = array_intersect($this->m_arrAvailableTemplateEngines,$this->m_objActiveSkin->getSupportedTemplateEngines()))){
			if($iSkinId == $this->m_iDefaultSkinId || !$this->m_objActiveSkin->loadDataById($this->m_iDefaultSkinId)
			|| !($arrValidTemplateEngines = array_intersect($this->m_arrAvailableTemplateEngines,$this->m_objActiveSkin->getSupportedTemplateEngines()))){
				$bReturn = FALSE;
			}
		}

		if($bReturn){
			reset($arrValidTemplateEngines);
			$this->m_sActiveTemplateEngine = current($arrValidTemplateEngines);

			if(is_object($this->m_objActiveUser)){
				if(($this->m_objActiveUser->getTopFrameSize()>0) && ($this->m_objActiveUser->getBottomFrameSize()>0)){
					$this->m_objActiveSkin->setFrameSize($this->m_objActiveUser->getTopFrameSize(),$this->m_objActiveUser->getBottomFrameSize());
				}
			}
		}
		return $bReturn;
	}

	/**
	 * get data from database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return boolean success / failure
	 */
	function _loadData(){

		global $objDb;

		if($objResultSet = &$objDb->executeQuery("SELECT c_skinid,".
														"c_banner,".
														"c_quickpost,".
														"c_guestpost,".
														"c_directregistration,".
														"c_uniquemail,".
														"c_parseurl,".
														"c_parsestyle,".
														"c_usesignatures,".
														"c_countviews,".
														"c_dateformat,".
														"c_timeoffset,".
														"c_onlinetime,".
														"c_closethreads,".
														"c_usrperpage,".
														"c_msgperpage,".
														"c_msgheaderperpage,".
														"c_privatemsgperpage,".
														"c_thrdperpage,".
														"c_quotechar,".
														"c_quotesubject,".
														"c_mailwebmaster,".
														"c_skindir,".
														"c_maxprofilepicsize,".
														"c_maxprofilepicwidth,".
														"c_maxprofilepicheight,".
														"c_profileimgdir".
													" FROM pxm_configuration")){
			if($objResultRow = $objResultSet->getNextResultRowObject()){

				$objResultSet->freeResult();
				unset($objResultSet);

				$this->m_iDefaultSkinId = intval($objResultRow->c_skinid);

				$this->m_bUseBanners = $objResultRow->c_banner?TRUE:FALSE;
				$this->m_bUseQuickPost = $objResultRow->c_quickpost?TRUE:FALSE;
				$this->m_bUseGuestPost = $objResultRow->c_guestpost?TRUE:FALSE;
				$this->m_bUseDirectRegistration = $objResultRow->c_directregistration?TRUE:FALSE;
				$this->m_bUniqueRegistrationMails = $objResultRow->c_uniquemail?TRUE:FALSE;
				$this->m_bParseUrl = $objResultRow->c_parseurl?TRUE:FALSE;
				$this->m_bParseStyle = $objResultRow->c_parsestyle?TRUE:FALSE;
				$this->m_bUseSignatures = $objResultRow->c_usesignatures?TRUE:FALSE;
				$this->m_bCountViews = $objResultRow->c_countviews?TRUE:FALSE;
				$this->m_sDateFormat = $objResultRow->c_dateformat;
				$this->m_iTimeOffset = intval($objResultRow->c_timeoffset);
				$this->m_iOnlineTime = intval($objResultRow->c_onlinetime);

				$this->m_iThreadSizeLimit = intval($objResultRow->c_closethreads);
				$this->m_iUserPerPage = intval($objResultRow->c_usrperpage);
				$this->m_iMessagesPerPage = intval($objResultRow->c_msgperpage);
				$this->m_iMessageHeaderPerPage = intval($objResultRow->c_msgheaderperpage);
				$this->m_iPrivateMessagesPerPage = intval($objResultRow->c_privatemsgperpage);
				$this->m_iThreadsPerPage = intval($objResultRow->c_thrdperpage);

				$this->m_sQuoteChar = $objResultRow->c_quotechar;
				$this->m_sQuoteSubject = $objResultRow->c_quotesubject;

				$this->m_sMailWebmaster	= $objResultRow->c_mailwebmaster;

				$this->m_sSkinDir = $objResultRow->c_skindir;
				$this->m_iMaxProfileImgSize = intval($objResultRow->c_maxprofilepicsize);
				$this->m_iMaxProfileImgWidth = intval($objResultRow->c_maxprofilepicwidth);
				$this->m_iMaxProfileImgHeight = intval($objResultRow->c_maxprofilepicheight);
				$this->m_sProfileImgDir = $objResultRow->c_profileimgdir;

				unset($objResultRow);

				return TRUE;
			}
		}
		return FALSE;
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

		if($objDb->executeQuery("UPDATE pxm_configuration SET c_skinid=$this->m_iDefaultSkinId,".
																		"c_banner=".intval($this->m_bUseBanners).",".
																	    "c_quickpost=".intval($this->m_bUseQuickPost).",".
																		"c_guestpost=".intval($this->m_bUseGuestPost).",".
																	    "c_directregistration=".intval($this->m_bUseDirectRegistration).",".
																	    "c_uniquemail=".intval($this->m_bUniqueRegistrationMails).",".
																		"c_parseurl=".intval($this->m_bParseUrl).",".
																		"c_parsestyle=".intval($this->m_bParseStyle).",".
																		"c_usesignatures=".intval($this->m_bUseSignatures).",".
																	    "c_countviews=".intval($this->m_bCountViews).",".
																	    "c_dateformat='".addslashes($this->m_sDateFormat)."',".
																	    "c_timeoffset=$this->m_iTimeOffset,".
																	    "c_onlinetime=$this->m_iOnlineTime,".
																	    "c_closethreads=$this->m_iThreadSizeLimit,".
																	    "c_usrperpage=$this->m_iUserPerPage,".
																	    "c_msgperpage=$this->m_iMessagesPerPage,".
																		"c_msgheaderperpage=$this->m_iMessageHeaderPerPage,".
																		"c_privatemsgperpage=$this->m_iPrivateMessagesPerPage,".
																	    "c_thrdperpage=$this->m_iThreadsPerPage,".
																		"c_quotechar='".addslashes($this->m_sQuoteChar)."',".
																		"c_quotesubject='".addslashes($this->m_sQuoteSubject)."',".
																	    "c_mailwebmaster='".addslashes($this->m_sMailWebmaster)."',".
																		"c_skindir='".addslashes($this->m_sSkinDir)."',".
																	    "c_maxprofilepicsize=$this->m_iMaxProfileImgSize,".
																	    "c_maxprofilepicwidth=$this->m_iMaxProfileImgWidth,".
																	    "c_maxprofilepicheight=$this->m_iMaxProfileImgHeight,".
																	    "c_profileimgdir='".addslashes($this->m_sProfileImgDir)."'")){
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * get session
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return object session
	 */
	function &getSession(){
		return $this->m_objSession;
	}

	/**
	 * get available template engines
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array available template engines
	 */
	function &getAvailableTemplateEngines(){
		return $this->m_arrAvailableTemplateEngines;
	}

	/**
	 * get active template engine
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string active template engine
	 */
	function getActiveTemplateEngine(){
		return $this->m_sActiveTemplateEngine;
	}

	/**
	 * get active board
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return object active board
	 */
	function &getActiveBoard(){
		return $this->m_objActiveBoard;
	}

	/**
	 * set active board
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param object $objActiveBoard active board
	 * @return void
	 */
	function setActiveBoard(&$objActiveBoard){
		$this->m_objActiveBoard = &$objActiveBoard;
	}

	/**
	 * get active user
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return object active user
	 */
	function &getActiveUser(){
		return $this->m_objActiveUser;
	}

	/**
	 * set active user
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param object $objActiveUser active user
	 * @return void
	 */
	function setActiveUser(&$objActiveUser){
		$this->m_objActiveUser = &$objActiveUser;
	}

	function unsetActiveUser(){
		$this->m_objActiveUser = NULL;
	}

	/**
	 * get active skin
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return object active skin
	 */
	function &getActiveSkin(){
		return $this->m_objActiveSkin;
	}

	/**
	 * set active skin
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param object $objActiveSkin active skin
	 * @return void
	 */
	function setActiveSkin(&$objActiveSkin){
		$this->m_objActiveSkin = &$objActiveSkin;
	}

	/**
	 * get default skin id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer default skin id
	 */
	function getDefaultSkinId(){
		return $this->m_iDefaultSkinId;
	}

	/**
	 * set default skin id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iDefaultSkinId default skin id
	 * @return void
	 */
	function setDefaultSkinId($iDefaultSkinId){
		$this->m_iDefaultSkinId = intval($iDefaultSkinId);
	}

	/**
	 * get access timestamp
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer access timestamp
	 */
	function getAccessTimestamp(){
		return $this->m_iAccessTimestamp;
	}

	/**
	 * use banners?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean use banners?
	 */
	function useBanners(){
		return $this->m_bUseBanners;
	}

	/**
	 * set use banners
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bUseBanners use banners?
	 * @return void
	 */
	function setUseBanners($bUseBanners){
		$this->m_bUseBanners = $bUseBanners?TRUE:FALSE;
	}

	/**
	 * use quickpost?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean use quickpost?
	 */
	function useQuickPost(){
		return $this->m_bUseQuickPost;
	}

	/**
	 * set use quickpost
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bUseQuickPost use quickpost?
	 * @return void
	 */
	function setUseQuickPost($bUseQuickPost){
		$this->m_bUseQuickPost = $bUseQuickPost?TRUE:FALSE;
	}

	/**
	 * are guests allowed to post messages?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean are guests allowed to post messages?
	 */
	function useGuestPost(){
		return $this->m_bUseGuestPost;
	}

	/**
	 * set use guestpost
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bUseGuestPost are guests allowed to post messages?
	 * @return void
	 */
	function setUseGuestPost($bUseGuestPost){
		$this->m_bUseGuestPost = $bUseGuestPost?TRUE:FALSE;
	}

	/**
	 * use signatures?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bUseActiveUser use the data from the active user
	 * @return boolean use signatures?
	 */
	function useSignatures($bUseActiveUser = TRUE){
		if($bUseActiveUser && is_object($this->m_objActiveUser)){
			return ($this->m_objActiveUser->showSignatures());
		}
		return $this->m_bUseSignatures;
	}

	/**
	 * set use signatures
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bUseSignatures use signatures?
	 * @return void
	 */
	function setUseSignatures($bUseSignatures){
		$this->m_bUseSignatures = $bUseSignatures?TRUE:FALSE;
	}

	/**
	 * use direct registration?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean use direct registration?
	 */
	function useDirectRegistration(){
		return $this->m_bUseDirectRegistration;
	}

	/**
	 * set use direct registration
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bUseDirectRegistration use direct registration?
	 * @return void
	 */
	function setUseDirectRegistration($bUseDirectRegistration){
		$this->m_bUseDirectRegistration = $bUseDirectRegistration?TRUE:FALSE;
	}

	/**
	 * are the private mail adresses unique?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean registration mail adresses unique?
	 */
	function uniqueRegistrationMails(){
		return $this->m_bUniqueRegistrationMails;
	}

	/**
	 * set private mail adresses unique
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bUniqueRegistrationMails registration mail adresses unique?
	 * @return void
	 */
	function setUniqueRegistrationMails($bUniqueRegistrationMails){
		$this->m_bUniqueRegistrationMails = $bUniqueRegistrationMails?TRUE:FALSE;
	}

	/**
	 * count thread views?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean count thread views?
	 */
	function countViews(){
		return $this->m_bCountViews;
	}

	/**
	 * set count thread views
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bCountViews count thread views?
	 * @return void
	 */
	function setCountViews($bCountViews){
		$this->m_bCountViews = $bCountViews?TRUE:FALSE;
	}

	/**
	 * get date format
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string date format
	 */
	function getDateFormat(){
		return $this->m_sDateFormat;
	}

	/**
	 * set date format
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sDateFormat date format
	 * @return void
	 */
	function setDateFormat($sDateFormat){
		$this->m_sDateFormat = $sDateFormat;
	}

	/**
	 * get time offset
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bUseActiveUser use the data from the active user
	 * @return integer time offset
	 */
	function getTimeOffset($bUseActiveUser = TRUE){
		if($bUseActiveUser && is_object($this->m_objActiveUser)){
			return ($this->m_iTimeOffset+$this->m_objActiveUser->getTimeOffset());
		}
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
		if($iTimeOffset<13 && $iTimeOffset>-13){
			$this->m_iTimeOffset = $iTimeOffset;
		}
	}

	/**
	 * get online time
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer online time (seconds)
	 */
	function getOnlineTime(){
		return $this->m_iOnlineTime;
	}

	/**
	 * set online time
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iOnlineTime online time (seconds)
	 * @return void
	 */
	function setOnlineTime($iOnlineTime){
		$this->m_iOnlineTime = intval($iOnlineTime);
	}

	/**
	 * get thread size limit
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer thread size limit (0 = no limit)
	 */
	function getThreadSizeLimit(){
		return $this->m_iThreadSizeLimit;
	}

	/**
	 * set thread size limit
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iThreadSizeLimit thread size limit (0 = no limit)
	 * @return void
	 */
	function setThreadSizeLimit($iThreadSizeLimit){
		$this->m_iThreadSizeLimit = intval($iThreadSizeLimit);
	}

	/**
	 * get user per page
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer user per page
	 */
	function getUserPerPage(){
		return $this->m_iUserPerPage;
	}

	/**
	 * set user per page
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iUserPerPage user per page
	 * @return void
	 */
	function setUserPerPage($iUserPerPage){
		$this->m_iUserPerPage = intval($iUserPerPage);
	}

	/**
	 * get message header per page (search)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer message header per page
	 */
	function getMessageHeaderPerPage(){
		return $this->m_iMessageHeaderPerPage;
	}

	/**
	 * set message header per page (search)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iMessageHeaderPerPage message header per page
	 * @return void
	 */
	function setMessageHeaderPerPage($iMessageHeaderPerPage){
		$this->m_iMessageHeaderPerPage = intval($iMessageHeaderPerPage);
	}

	/**
	 * get messages per page (flat mode)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer messages per page
	 */
	function getMessagesPerPage(){
		return $this->m_iMessagesPerPage;
	}

	/**
	 * set messages per page (flat mode)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iMessagesPerPage messages per page
	 * @return void
	 */
	function setMessagesPerPage($iMessagesPerPage){
		$this->m_iMessagesPerPage = intval($iMessagesPerPage);
	}

	/**
	 * get private messages per page
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer private messages per page
	 */
	function getPrivateMessagesPerPage(){
		return $this->m_iPrivateMessagesPerPage;
	}

	/**
	 * set private messages per page
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iPrivateMessagesPerPage private messages per page
	 * @return void
	 */
	function setPrivateMessagesPerPage($iPrivateMessagesPerPage){
		$this->m_iPrivateMessagesPerPage = intval($iPrivateMessagesPerPage);
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
	 * get webmaster mail adress
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string webmaster mail adress
	 */
	function getMailWebmaster(){
		return $this->m_sMailWebmaster;
	}

	/**
	 * set webmaster mail adress
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sMailWebmaster webmaster mail adress
	 * @return void
	 */
	function setMailWebmaster($sMailWebmaster){
		$this->m_sMailWebmaster = $sMailWebmaster;
	}

	/**
	 * get quote char
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string quote char
	 */
	function getQuoteChar(){
		return $this->m_sQuoteChar;
	}

	/**
	 * set quote char
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sQuoteChar quote char
	 * @return void
	 */
	function setQuoteChar($sQuoteChar){
		$this->m_sQuoteChar = $sQuoteChar;
	}

	/**
	 * get quote subject
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string quote subject
	 */
	function getQuoteSubject(){
		return $this->m_sQuoteSubject;
	}

	/**
	 * set quote subject
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sQuoteSubject quote subject
	 * @return void
	 */
	function setQuoteSubject($sQuoteSubject){
		$this->m_sQuoteSubject = $sQuoteSubject;
	}

	/**
	 * get skin directory
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string skin directory
	 */
	function getSkinDirectory(){
		return $this->m_sSkinDir;
	}

	/**
	 * set skin directory
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sSkinDir skin directory
	 * @return void
	 */
	function setSkinDirectory($sSkinDir){
		$this->m_sSkinDir = $sSkinDir.(((strlen($sSkinDir)>0) && ($sSkinDir[strlen($sSkinDir)-1]!='/'))?"/":"");
	}

	/**
	 * get max profile img size
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer max profile img size (byte)
	 */
	function getMaxProfileImgSize(){
		return $this->m_iMaxProfileImgSize;
	}

	/**
	 * set max profile img size
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iMaxProfileImgSize max profile img size (byte)
	 * @return void
	 */
	function setMaxProfileImgSize($iMaxProfileImgSize){
		$this->m_iMaxProfileImgSize = intval($iMaxProfileImgSize);
	}

	/**
	 * get max profile img width
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer max profile img width (pixel)
	 */
	function getMaxProfileImgWidth(){
		return $this->m_iMaxProfileImgWidth;
	}

	/**
	 * set max profile img width
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iMaxProfileImgWidth max profile img width (pixel)
	 * @return void
	 */
	function setMaxProfileImgWidth($iMaxProfileImgWidth){
		$this->m_iMaxProfileImgWidth = intval($iMaxProfileImgWidth);
	}

	/**
	 * get max profile img height
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer max profile img height (pixel)
	 */
	function getMaxProfileImgHeight(){
		return $this->m_iMaxProfileImgHeight;
	}

	/**
	 * set max profile img height
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iMaxProfileImgHeight max profile img height (pixel)
	 * @return void
	 */
	function setMaxProfileImgHeight($iMaxProfileImgHeight){
		$this->m_iMaxProfileImgHeight = intval($iMaxProfileImgHeight);
	}

	/**
	 * get profile img directory
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string profile img directory
	 */
	function getProfileImgDirectory(){
		return $this->m_sProfileImgDir;
	}

	/**
	 * set profile img directory
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sProfileImgDir profile img directory
	 * @return void
	 */
	function setProfileImgDirectory($sProfileImgDir){
		$this->m_sProfileImgDir = $sProfileImgDir.(((strlen($sProfileImgDir)>0) && ($sProfileImgDir[strlen($sProfileImgDir)-1]!='/'))?"/":"");
	}

	/**
	 * get profile img types
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array profile img types
	 */
	function getProfileImgTypes(){
		return $this->m_arrProfileImgTypes;
	}

	/**
	 * get profile img directory split
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer profile img directory split
	 */
	function getProfileImgDirectorySplit(){
		return $this->m_iProfileImgSplitDir;
	}

	/**
	 * get threadlist sortmode
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string threadlist sortmode
	 */
	function getThreadListSortMode(){
		$sSortMode = "";
		if(is_object($this->m_objActiveUser)){
			$sSortMode = $this->m_objActiveUser->getThreadListSortMode();
		}
		if(is_object($this->m_objActiveBoard) && empty($sSortMode)){
			$sSortMode = $this->m_objActiveBoard->getThreadListSortMode();
		}
		return $sSortMode;
	}

	/**
	 * set threadlist sortmode
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sThreadListSortMode threadlist sortmode
	 * @return void
	 */
	function setThreadListSortMode($sThreadListSortMode){
		if(is_object($this->m_objActiveUser)){
			$this->m_objActiveUser->setThreadListSortMode($sThreadListSortMode);
		}
		else if(is_object($this->m_objActiveBoard)){
			$this->m_objActiveBoard->setThreadListSortMode($sThreadListSortMode);
		}
	}

	/**
	 * parse urls in private messages?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean parse / don't parse urls
	 */
	function parseUrl(){
		return $this->m_bParseUrl;
	}

	/**
	 * set parse urls in private messages?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bParseUrl parse / don't parse urls
	 * @return void
	 */
	function setParseUrl($bParseUrl){
		$this->m_bParseUrl = $bParseUrl?TRUE:FALSE;
	}

	/**
	 * parse style in private messages?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean parse / don't parse style
	 */
	function parseStyle(){
		return $this->m_bParseStyle;
	}

	/**
	 * set parse style in private messages?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bParseStyle parse / don't parse style
	 * @return void
	 */
	function setParseStyle($bParseStyle){
		$this->m_bParseStyle = $bParseStyle?TRUE:FALSE;
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
		if(is_object($this->m_objActiveUser)){
			return $this->m_objActiveUser->parseImages();
		}
		else if(is_object($this->m_objActiveBoard)){
			return $this->m_objActiveBoard->parseImages();
		}
		return FALSE;
	}

	/**
	 * do textreplacements (smilies etc.)?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean do / don't do textreplacements
	 */
	function doTextReplacements(){
		if(is_object($this->m_objActiveUser)){
			return $this->m_objActiveUser->doTextReplacements();
		}
		else if(is_object($this->m_objActiveBoard)){
			return $this->m_objActiveBoard->doTextReplacements();
		}
		return FALSE;
	}

	/**
	 * get membervariables as array
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param array  $arrAdditionalConfig additional configuration
	 * @return array member variables
	 */
	function getDataArray($arrAdditionalConfig = array()){

		if(is_object($this->m_objActiveBoard)){
			$iTimeSpan = $this->m_objActiveBoard->getThreadListTimeSpan();
		}
		else{
			$iTimeSpan = "0";
		}
		if(is_object($this->m_objActiveUser)){
			$sSidForm = "";
			if($sSid = $this->m_objSession->getSid()){
				$sSid ="&".$sSid;
				$sSidForm = "<input type=\"hidden\" name=\"".$this->m_objSession->getSessionName()."\" value=\"".$this->m_objSession->getSessionId()."\"/>";
			}
			$arrGeneralConfiguration = array("logedin"		=> "1",
											 "admin" 		=> $this->m_objActiveUser->isAdmin()?"1":"0",
											 "moderator"	=> (is_object($this->m_objActiveBoard) && $this->m_objActiveUser->isModerator($this->m_objActiveBoard->getId())?"1":"0"),
											 "timespan"		=> $iTimeSpan,
											 "sort"			=> $this->getThreadListSortMode(),
											 "webmaster"	=> $this->m_sMailWebmaster,
											 "usesignatures"=> intval($this->useSignatures()),
											 "sid"			=> $sSid,
											 "_sidform"		=> $sSidForm);
		}
		else{
			$arrGeneralConfiguration = array("logedin"		=> "0",
											 "admin" 		=> "0",
											 "moderator"	=> "0",
											 "timespan"		=> $iTimeSpan,
											 "sort"			=> $this->getThreadListSortMode(),
											 "webmaster"	=> $this->m_sMailWebmaster,
											 "usesignatures"=> intval($this->useSignatures()),
											 "sid"			=> "",
											 "_sidform"		=> "");
		}
		return array("config"=> array_merge_recursive($arrGeneralConfiguration,
													  $arrAdditionalConfig,
													  array("skin"=>$this->m_objActiveSkin->getDataArray()),
													  is_object($this->m_objActiveBoard)?array("board" => array("id" 	=>$this->m_objActiveBoard->getId(),
													  															"name"	=>$this->m_objActiveBoard->getName())):array(),
													  is_object($this->m_objActiveUser)?array("user" => array("id" 		=>$this->m_objActiveUser->getId(),
													  														  "nickname"=>$this->m_objActiveUser->getNickName())):array()));
	}
}
?>