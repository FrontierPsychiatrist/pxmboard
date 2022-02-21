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
 * abstraction layer for DB handling (PostgreSql)
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/01/02 19:21:38 $
 * @version $Revision: 1.9 $
 */
class cDBPostgreSql extends cDB{

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
		if($this->m_resDBLink = @pg_connect("host=$sHostName dbname=$sDBName user=$sUserName password=$sPassword")){
			return TRUE;
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
			if(!empty($iLimit)){
				$sQuery .= " LIMIT $iLimit";
			}
			if(!empty($iOffset)){
				$sQuery .= " OFFSET $iOffset";
			}

 			if($mResult = @pg_query($this->m_resDBLink,$sQuery)){
				if(!is_resource($mResult)){
					$mResult = NULL;
				}
				return new cDBPostgreSqlResultSet($mResult);
			}
			else{
 				$this->m_sLastErrorMessage	= pg_last_error();
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
		if($objResultSet = &$this->executeQuery("SELECT currval('".$sTableName."_".$sColumnName."_seq') AS lastid")){
			if($objResultRow = $objResultSet->getNextResultRowObject()){
				return $objResultRow->lastid;
			}
		}
		return 0;
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
 		@pg_close($this->m_resDBLink);
	}

	/**
	 * get the type of the connection
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
 	function getDBType(){
		return "PostgreSQL";
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
		return pg_version()["client"];
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
			case "integer"	:	$sColumnType = "INT4";
								break;
			case "string"	:	$sColumnType = "VARCHAR($iSize)";
								break;
		}
		return $sColumnType;
	}
}

/**
 * database resultset (PostgreSql)
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/01/02 19:21:38 $
 * @version $Revision: 1.9 $
 */
class cDBPostgreSqlResultSet extends cDBResultSet{

	var $m_iRowPointer = 0;				// row pointer

	/**
	 * get next result row as an object
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return object result row
	 */
	function getNextResultRowObject(){
		return @pg_fetch_object($this->m_resResultSet,$this->m_iRowPointer++);
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
		return @pg_fetch_array($this->m_resResultSet,$this->m_iRowPointer++,PGSQL_ASSOC);
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
		return @pg_fetch_array($this->m_resResultSet,$this->m_iRowPointer++,PGSQL_NUM);
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
		$this->m_iRowPointer = $iRowId;
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
		return @pg_num_rows($this->m_resResultSet);
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
		return @pg_affected_rows($this->m_resResultSet);
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
		@pg_free_result($this->m_resResultSet);
	}
}
?>