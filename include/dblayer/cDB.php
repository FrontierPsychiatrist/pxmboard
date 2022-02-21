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
 * abstraction layer for DB handling (interface)
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/01/02 19:21:00 $
 * @version $Revision: 1.8 $
 */
class cDB{

	var $m_resDBLink;					//link id returned by connect
	var $m_iLastErrorId;				//last error id
	var $m_sLastErrorMessage;			//last error message

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function cDB(){
		$this->m_resDBLink			= NULL;
		$this->m_iLastErrorId		= 0;
		$this->m_sLastErrorMessage	= "";
	}

	/**
	 * open a connection to a DB Server
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sHostName hostname
	 * @param string $sUserName username
	 * @param string $sPassword password
	 * @param string $sDBName db name
	 * @return boolean success / failure
	 */
	function connectDBServer($sHostName = "localhost",$sUserName = "defaultuser",$sPassword = "",$sDBName = ""){
	}

	/**
	 * execute a query
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sQuery query string
	 * @param integer $iLimit row limit
	 * @param integer $iOffset row offset
	 * @return object query result set
	 */
	function &executeQuery($sQuery,$iLimit = 0,$iOffset = 0){
	}

	/**
	 * get the id generated from the previous insert operation
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sTableName table name
	 * @param string $sColumnName column name
	 * @return integer insert id
	 */
	function getInsertId($sTableName,$sColumnName){
	}

	/**
	 * escape special chars in the string for use in a db query
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sString string to quote
	 * @return string quoted string
	 */
	function quote($sString){
		return "'".addslashes($sString)."'";
	}

	/**
	 * close a connection to a DB Server
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
 	function disconnectDBServer(){
	}

	/**
	 * get the type of the connection
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string db type
	 */
 	function getDBType(){
	}

	/**
	 * get the version of the db
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string db version
	 */
 	function getDBVersion(){
	}

	/**
	 * get the column metatype (integer, string, text)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sMetaType meta name of the column type (integer, string)
	 * @param integer $iSize size of the requested field
	 * @return string db dependent column type
	 */
 	function getMetaType($sMetaType,$iSize = -1){
	}

	/**
	 * handle errors (and stop the script)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sErrorMessage additional error message
	 * @return void
	 */
 	function _handleError($sErrorMessage = ""){
		$sErrorMessage = "</td></tr></table><b>Error:</b> ".$sErrorMessage;
		if(!empty($this->m_iLastErrorId)){
			$sErrorMessage .= "<br><i><b>DBErrorID:</b> ".$this->m_iLastErrorId."</i>";
		}
		if(!empty($this->m_sLastErrorMessage)){
			$sErrorMessage .= "<br><i><b>DBError:</b> ".$this->m_sLastErrorMessage."</i>";
		}
		die("$sErrorMessage<br>\n");
	}
}

/**
 * database resultset (interface)
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/01/02 19:21:00 $
 * @version $Revision: 1.8 $
 */
class cDBResultSet{

	var $m_resResultSet;

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param object $objConfig configuration data of the board
	 * @return void
	 */
	function cDBResultSet($resResultSet){
		$this->m_resResultSet = $resResultSet;
	}

	/**
	 * get next result row as an object
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return object result row
	 */
	function getNextResultRowObject(){
	}

	/**
	 * get next result row as an associative array
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array result row
	 */
	function getNextResultRowAssociative(){
	}

	/**
	 * get next result row as an numeric array
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array result row
	 */
	function getNextResultRowNumeric(){
	}

	/**
	 * set result pointer to ...
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iRowId id of the row
	 * @return boolean success / failure
	 */
	function setResultPointer($iRowId = 0){
	}

	/**
	 * get number of rows in result (for select)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer number of rows
	 */
	function getNumRows(){
	}

	/**
	 * get number of affected rows (for insert, update and delete)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer number of rows
	 */
	function getAffectedRows(){
	}

	/**
	 * free result memory
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function freeResult(){
	}
}
?>