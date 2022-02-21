<?php
require_once(INCLUDEDIR."/dblayer/cDB.php");
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
 * abstraction layer for DB handling (MySql)
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/01/02 19:22:20 $
 * @version $Revision: 1.8 $
 */
class cDBMySql extends cDB{

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
		if($this->m_resDBLink = @mysqli_connect($sHostName,$sUserName,$sPassword)){
			if(@mysqli_select_db($this->m_resDBLink, $sDBName)){
				return TRUE;
			}
		}
		$this->_handleError("couldn't connect to server");
		return FALSE;
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
		if(empty($sQuery)){
			$this->_handleError("invalid querystring");
			return FALSE;
		}
		else{
			if(!empty($iLimit) || !empty($iOffset)){
				if(!empty($iOffset)){
					$sQuery .= " LIMIT $iOffset";
					if(!empty($iLimit)){
						$sQuery .= ",$iLimit";
					}
				}
				else{
					$sQuery .= " LIMIT $iLimit";
				}
			}

 			if($mResult = @mysqli_query($this->m_resDBLink, $sQuery)){
				if(!($mResult instanceof mysqli_result)){
					$mResult = NULL;
				}
				$objResultSet = new cDBMySqlResultSet($mResult);
				$objResultSet->setAffectedRows(@mysqli_affected_rows($this->m_resDBLink));
				return $objResultSet;
			}
			else{
				$this->m_iLastErrorId		= mysqli_errno($this->m_resDBLink);
				$this->m_sLastErrorMessage	= mysqli_error($this->m_resDBLink);
				$this->_handleError("couldn't execute query");
				return NULL;
			}
 		}
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
	function getInsertID($sTableName,$sColumnName){
		return mysqli_insert_id($this->m_resDBLink);
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
 		@mysqli_close($this->m_resDBLink);
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
		return "MySQL";
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
		return mysqli_get_server_info($this->m_resDBLink);
	}

	/**
	 * get the column metatype (integer, string)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sMetaType meta name of the column type (integer, string)
	 * @param integer $iSize size of the requested field
	 * @return string db dependent column type
	 */
 	function getMetaType($sMetaType,$iSize = -1){
		$sColumnType = "";
		switch($sMetaType){
			case "integer"	:	$sColumnType = "INT";
								break;
			case "string"	:	if($iSize<=255){
									$sColumnType = "VARCHAR($iSize)";
								}
								else{
									$sColumnType = "TEXT";
								}
								break;
		}
		return $sColumnType;
	}
}

/**
 * database resultset (MySql)
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/01/02 19:22:20 $
 * @version $Revision: 1.8 $
 */
class cDBMySqlResultSet extends cDBResultSet{

	var $m_iAffectedRows = 0;

	/**
	 * get next result row as an object
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return object result row
	 */
	function getNextResultRowObject(){
		return mysqli_fetch_object($this->m_resResultSet);
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
		return mysqli_fetch_array($this->m_resResultSet,MYSQLI_ASSOC);
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
		return mysqli_fetch_array($this->m_resResultSet,MYSQLI_NUM);
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
		if (@mysqli_data_seek($this->m_resResultSet,$iRowId)){
			return TRUE;
		}
		else{
			return FALSE;
		}
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
		return mysqli_num_rows($this->m_resResultSet);
	}

	/**
	 * set number of affected rows (for insert, update and delete)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iAffectedRows number of rows
	 * @return void
	 */
	function setAffectedRows($iAffectedRows){
		$this->m_iAffectedRows = $iAffectedRows;
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
		return $this->m_iAffectedRows;
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
		mysqli_free_result($this->m_resResultSet);
	}
}
?>