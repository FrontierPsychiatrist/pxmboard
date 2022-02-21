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
 * error handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.7 $
 */
class cError{

	var $m_iErrorId = 0;										// error id

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iErrorId error id
	 * @return void
	 */
	function cError($iErrorId){
		$this->m_iErrorId = intval($iErrorId);
	}

	/**
	 * get the id of this error
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer error id
	 */
	function getId(){
		return $this->m_iErrorId;
	}

	/**
	 * get the message for this error
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string error message
	 */
	function getMessage(){
		global $objDb;

		$sError = "";
		if ($objResultSet = &$objDb->executeQuery("SELECT e_message FROM pxm_error WHERE e_id=$this->m_iErrorId")){
			if ($objResultRow = $objResultSet->getNextResultRowObject()){
				$sError = $objResultRow->e_message;
			}
			$objResultSet->freeResult();
		}
		return $sError;
	}

	/**
	 * get membervariables as array
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array error information
	 */
	function getDataArray(){
		return array("id" 	=> $this->m_iErrorId,
					 "text"	=> $this->getMessage());
	}
}
?>