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
 * handles the error messages of the system
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.7 $
 */
class cErrorList{

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
	 * get all error ids and messages
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array error ids and messages
	 */
	function &getList(){

		global $objDb;

		$arrErrors = array();

		if($objResultSet = &$objDb->executeQuery("SELECT e_id,e_message FROM pxm_error ORDER BY e_id ASC")){
			while($objResultRow = $objResultSet->getNextResultRowObject()){
				$arrErrors[intval($objResultRow->e_id)] = $objResultRow->e_message;
			}
			$objResultSet->freeResult();
		}
		return $arrErrors;
	}

	/**
	 * update all error ids and messages
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param array $arrErrors error ids and messages
	 * @return boolean success / failure
	 */
	function updateList(&$arrErrors){

		global $objDb;

		foreach($arrErrors as $iErrorId => $sErrorMessage){
			$objDb->executeQuery("UPDATE pxm_error SET e_message='".addslashes($sErrorMessage)."' WHERE e_id=".intval($iErrorId));
		}
		return TRUE;
	}
}
?>