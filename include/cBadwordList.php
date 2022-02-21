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
 * handles the badwords
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.5 $
 */
class cBadwordList{

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function cBadwordList(){
	}

	/**
	 * get all badwords
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array badwords and replacements
	 */
	function &getList(){

		global $objDb;

		$arrBadwords = array("search"=>array(),"replace"=>array());

		if($objResultSet = &$objDb->executeQuery("SELECT bw_name,bw_replacement FROM pxm_badword")){
			while($objResultRow = $objResultSet->getNextResultRowObject()){
				if(strlen($objResultRow->bw_name)>0){
					$arrBadwords["search"][] = $objResultRow->bw_name;
					$arrBadwords["replace"][] = $objResultRow->bw_replacement;
				}
			}
			$objResultSet->freeResult();
		}
		return $arrBadwords;
	}

	/**
	 * update all badwords
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param array $arrBadwords badwords and replacements
	 * @return boolean success / failure
	 */
	function updateList(&$arrBadwords){
		global $objDb;

		if(is_array($arrBadwords)&&isset($arrBadwords["search"])&&isset($arrBadwords["replace"])){
			if($objDb->executeQuery("DELETE FROM pxm_badword")){
				foreach($arrBadwords["search"] as $iKey=>$sBadwordSearch){
					if(strlen($sBadwordSearch)>0){
						if(isset($arrBadwords["replace"][$iKey])){
							$sBadwordReplace = $arrBadwords["replace"][$iKey];
						}
						else{
							$sBadwordReplace = "";
						}
						$objDb->executeQuery("INSERT INTO pxm_badword (bw_name,bw_replacement) VALUES ('".addslashes($sBadwordSearch)."','".addslashes($sBadwordReplace)."')");
					}
				}
			}
		}
		return TRUE;
	}
}
?>