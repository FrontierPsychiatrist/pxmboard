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
 * user permission handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:48 $
 * @version $Revision: 1.8 $
 */
class cUserPermissions extends cUser{

	var	$m_bPost;					// post allowed ?
	var	$m_bEdit;					// edit allowed ?

	var	$m_bIsAdmin;				// is administrator ?
	var	$m_arrModBoards;			// is moderator for

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

		$this->m_bPost = FALSE;
		$this->m_bEdit = FALSE;

		$this->m_bIsAdmin = FALSE;
		$this->m_arrModBoards = NULL;
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

		cUser::_setDataFromDb($objResultRow);

		$this->m_bPost = $objResultRow->u_post?TRUE:FALSE;
		$this->m_bEdit = $objResultRow->u_edit?TRUE:FALSE;
		$this->m_bIsAdmin = $objResultRow->u_admin?TRUE:FALSE;

		return TRUE;
	}

	/**
	 * initalize an array with board ids where current user is moderator
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return boolean success / failure
	 */
	function _loadModBoards(){

		global $objDb;
		$this->m_arrModBoards = array();

		if($objResultSet = &$objDb->executeQuery("SELECT mod_boardid FROM pxm_moderator WHERE mod_userid=".$this->m_iId)){
			while($objResultRow = $objResultSet->getNextResultRowObject()){
				$this->m_arrModBoards[] = intval($objResultRow->mod_boardid);
			}
			$objResultSet->freeResult();
		}
		else{
			return FALSE;
		}
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
	 	return cUser::_getDbAttributes().",u_post,u_edit,u_admin";
	 }

	/**
	 * refresh the member rights and status variables from database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function refreshRights(){

		global $objDb;

		if($objResultSet = &$objDb->executeQuery("SELECT u_status,u_post,u_edit,u_admin FROM pxm_user WHERE u_id=".$this->m_iId)){
			if($objResultRow = $objResultSet->getNextResultRowObject()){
				$this->m_iStatus = intval($objResultRow->u_status);
				$this->m_bPost = $objResultRow->u_post?TRUE:FALSE;
				$this->m_bEdit = $objResultRow->u_edit?TRUE:FALSE;
				$this->m_bIsAdmin = $objResultRow->u_admin?TRUE:FALSE;
			}
			$objResultSet->freeResult();
		}
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
	 * is an moderator for the given board?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iBoardId board id
	 * @return boolean is moderator for the given board?
	 */
	function isModerator($iBoardId){
		if(!is_array($this->m_arrModBoards)){
			$this->_loadModBoards();
		}
		return in_array(intval($iBoardId),$this->m_arrModBoards);
	}

	/**
	 * get the board ids of where this user is moderator
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array board ids
	 */
	function &getModeratorBoardIds(){
		if(!is_array($this->m_arrModBoards)){
			$this->_loadModBoards();
		}
		return $this->m_arrModBoards;
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

		if(!$objDb->executeQuery("UPDATE pxm_user SET u_status=".$this->m_iStatus.
															 ",u_post=".intval($this->m_bPost).
															 ",u_edit=".intval($this->m_bEdit).
															 ",u_admin=".intval($this->m_bIsAdmin).
															 " WHERE u_id=".$this->m_iId)){
			return FALSE;
		}
		return TRUE;
	}
}
?>