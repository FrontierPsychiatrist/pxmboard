<?php
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
 * notification handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.5 $
 */
class cNotification{

	var $m_iId;									// notification id
	var $m_sMessage;							// notification message
	var $m_sName;								// name of the notification
	var $m_sDescription;						// description of the notification

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function cNotification(){
		$this->m_iId = 0;
		$this->m_sMessage = "";
		$this->m_sName = "";
		$this->m_sDescription = "";
	}

	/**
	 * get data from database by message id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iNotificationId notification id
	 * @return boolean success / failure
	 */
	function loadDataById($iNotificationId){

		$bReturn = FALSE;
		$iNotificationId = intval($iNotificationId);

		if($iNotificationId>0){

			global $objDb;

			if($objResultSet = &$objDb->executeQuery("SELECT n_id,".
															"n_message,".
															"n_name,".
															"n_description".
															" FROM pxm_notification".
															" WHERE n_id=".$iNotificationId)){
				if($objResultRow = $objResultSet->getNextResultRowObject()){
					$this->m_iId = intval($objResultRow->n_id);
					$this->m_sMessage = $objResultRow->n_message;
					$this->m_sName = $objResultRow->n_name;
					$this->m_sDescription = $objResultRow->n_description;

					$bReturn = TRUE;
				}
				$objResultSet->freeResult();
				unset($objResultSet);
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
			if($objDb->executeQuery("UPDATE pxm_notification SET n_message='".addslashes($this->m_sMessage)."' WHERE n_id=$this->m_iId")){
				$bReturn = TRUE;
			}
		}
		return $bReturn;
	}

	/**
	 * get the id of this notification
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer notification id
	 */
	function getId(){
		return $this->m_iId;
	}

	/**
	 * set the id of this notification
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iNotificationId notification id
	 * @return void
	 */
	function setId($iNotificationId){
		$this->m_iId = intval($iNotificationId);;
	}

	/**
	 * get the message for this notification
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string notification message
	 */
	function getMessage(){
		return $this->m_sMessage;
	}

	/**
	 * set the message for this notification
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sMessage notification message
	 * @return void
	 */
	function setMessage($sMessage){
		$this->m_sMessage = $sMessage;
	}

	/**
	 * get the name of this notification
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string notification name
	 */
	function getName(){
		return $this->m_sName;
	}

	/**
	 * set the name of this notification
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sName notification name
	 * @return void
	 */
	function setName($sName){
		$this->m_sName = $sName;
	}

	/**
	 * get the description of this notification
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string notification description
	 */
	function getDescription(){
		return $this->m_sDescription;
	}

	/**
	 * set the description of this notification
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sDescription notification description
	 * @return void
	 */
	function setDescription($sDescription){
		$this->m_sDescription = $sDescription;
	}
}
?>