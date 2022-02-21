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
 * handles the forbidden mails
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.5 $
 */
class cForbiddenMailList{

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
	 * get all forbidden mails
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array forbidden mails
	 */
	function &getList(){

		global $objDb;

		$arrForbiddenMails = array();

		if($objResultSet = &$objDb->executeQuery("SELECT fm_adress FROM pxm_forbiddenmail")){
			while($objResultRow = $objResultSet->getNextResultRowObject()){
				if(strlen($objResultRow->fm_adress)>0){
					$arrForbiddenMails[] = $objResultRow->fm_adress;
				}
			}
			$objResultSet->freeResult();
		}
		return $arrForbiddenMails;
	}

	/**
	 * update all forbidden mails
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param array $arrForbiddenMails forbidden mails
	 * @return boolean success / failure
	 */
	function updateList(&$arrForbiddenMails){
		global $objDb;

		if($objDb->executeQuery("DELETE FROM pxm_forbiddenmail")){
			foreach($arrForbiddenMails as $sForbiddenMail){
				if(strlen($sForbiddenMail)>0){
					$objDb->executeQuery("INSERT INTO pxm_forbiddenmail (fm_adress) VALUES ('".addslashes($sForbiddenMail)."')");
				}
			}
		}
		return true;
	}
}
?>