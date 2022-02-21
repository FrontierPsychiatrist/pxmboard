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
 * handles the textreplacements (smilies etc)
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.5 $
 */
class cTextreplacementList{

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
	 * get all textreplacements
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array textreplacements
	 */
	function &getList(){

		global $objDb;

		$arrReplacements = array("search"=>array(),"replace"=>array());

		if($objResultSet = &$objDb->executeQuery("SELECT tr_name,tr_replacement FROM pxm_textreplacement")){
			while($objResultRow = $objResultSet->getNextResultRowObject()){
				if(strlen($objResultRow->tr_name)>0){
					$arrReplacements["search"][] = $objResultRow->tr_name;
					$arrReplacements["replace"][] = $objResultRow->tr_replacement;
				}
			}
			$objResultSet->freeResult();
		}
		return $arrReplacements;
	}

	/**
	 * update all textreplacements
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param array $arrReplacements textreplacements
	 * @return boolean success / failure
	 */
	function updateList(&$arrReplacements){
		global $objDb;

		if(is_array($arrReplacements)&&isset($arrReplacements["search"])&&isset($arrReplacements["replace"])){
			if($objDb->executeQuery("DELETE FROM pxm_textreplacement")){
				foreach($arrReplacements["search"] as $iKey=>$sReplacementSearch){
					if(strlen($sReplacementSearch)>0){
						if(isset($arrReplacements["replace"][$iKey])){
							$sReplacementReplace = $arrReplacements["replace"][$iKey];
						}
						else{
							$sReplacementReplace = "";
						}
						$objDb->executeQuery("INSERT INTO pxm_textreplacement (tr_name,tr_replacement) VALUES ('".addslashes($sReplacementSearch)."','".addslashes($sReplacementReplace)."')");
					}
				}
			}
		}
		return TRUE;
	}
}
?>