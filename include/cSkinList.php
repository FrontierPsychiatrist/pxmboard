<?php
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
 * handles the skins
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.5 $
 */
class cSkinList{

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function cSkinList(){
	}

	/**
	 * get all skin ids and names
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array skins
	 */
	function &getList(){

		$arrSkins = array();

		global $objDb;

		if($objResultSet = &$objDb->executeQuery("SELECT s1.s_id,s1.s_fieldvalue AS s_name,s2.s_fieldvalue AS s_type FROM pxm_skin s1,pxm_skin s2 WHERE s1.s_id=s2.s_id AND s1.s_fieldname='name' AND s2.s_fieldname='type' ORDER BY s1.s_id")){
			while($objResultRow = $objResultSet->getNextResultRowObject()){

				$objSkin = new cSkin();
				$objSkin->setId($objResultRow->s_id);
				$objSkin->setName($objResultRow->s_name);
				$objSkin->setSupportedTemplateEngines(explode(",",$objResultRow->s_type));

				$arrSkins[] = $objSkin;
			}
			$objResultSet->freeResult();
		}
		return $arrSkins;
	}
}
?>