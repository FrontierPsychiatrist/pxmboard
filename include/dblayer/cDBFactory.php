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
 * factory class for db abstraction
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:01:11 $
 * @version $Revision: 1.6 $
 */
class cDBFactory{

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function cDBFactory(){
	}

	/**
	 * instanciates and returns the selected db object
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sDbType database type (MySql,PostgreSql,...)
	 * @return object database connection object
	 */
	function &getDBObject($sDbType){
		$objDb = NULL;
		if(preg_match("/^[a-zA-Z]+$/",$sDbType)){
			$sDbType = "cDB".$sDbType;
			include_once(INCLUDEDIR."/dblayer/".$sDbType.".php");
			$objDb = new $sDbType();
			return $objDb;
		}
		return $objDb;
	}
}
?>