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
 * scrolllist handling
 * 
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/01/02 19:14:14 $
 * @version $Revision: 1.8 $
 */
class cScrollList{

	var $m_arrResultList;			// array containing listelements
	var	$m_iPrevPageId;				// id of previous index page
	var	$m_iCurPageId;				// id of current index page
	var	$m_iNextPageId;				// id of next index page

	var $m_iItemsPerPage;			// items visible on one page
	var	$m_iItemCount;				// item count

	/**
	 * Constructor
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function cScrollList(){

		$this->m_arrResultList = array();
		$this->m_iPrevPageId = 0;
		$this->m_iCurPageId = 0;
		$this->m_iNextPageId = 0;

		$this->m_iItemsPerPage = 0;
		$this->m_iItemCount = 0;
	}

	/**
	 * get data from database
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iCurPageId page offset
	 * @param integer $iResultRowLimit quantity of entries that should be loaded
	 * @return boolean success / failure
	 */
	function loadData($iCurPageId,$iResultRowLimit){

		global $objDb;

		$iCurPageId = intval($iCurPageId);
		$iResultRowLimit = intval($iResultRowLimit);

		$this->m_iItemsPerPage = $iResultRowLimit;
		$this->m_iCurPageId = $iCurPageId;
		if($iCurPageId>0){
			--$iCurPageId;
		}
		else{
			++$this->m_iCurPageId;
		}

		$this->_doPreQuery();

		$sQuery = $this->_getQuery();
		
		if(!empty($sQuery) && $objResultSet = &$objDb->executeQuery($sQuery,$iResultRowLimit+1,$iCurPageId*$iResultRowLimit)){

			$this->m_arrResultList = array();
			for($x = 0; $x<$iResultRowLimit; $x++){
				if(!($objResultRow = $objResultSet->getNextResultRowObject())){
					break;
				}
				else{
					$this->_setDataFromDb($objResultRow);
				}
			}

			$this->m_iPrevPageId = $iCurPageId;
			$this->m_iNextPageId = (($iResultRowLimit<$objResultSet->getAffectedRows())?($iCurPageId+2):0);

			$objResultSet->freeResult();
			unset($objResultSet);

			$this->_doPostQuery();

			return TRUE;
		}
		$this->_doPostQuery();

		return FALSE;
	}

	/**
	 * do the query initializaton stuff here
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return void
	 */
	function _doPreQuery(){
	}

	/**
	 * get the query
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return string query
	 */
	function _getQuery(){
		return "";
	}

	/**
	 * do the query shutdown stuff here
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return void
	 */
	function _doPostQuery(){
	}

	/**
	 * initalize the member variables with the resultrow from the db
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param object $objResultRow resultrow from db query
	 * @return boolean success / failure
	 */
	function _setDataFromDb(&$objResultRow){
		return TRUE;
	}

	/**
	 * get the item count
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer item count
	 */
	function getItemCount(){
		return $this->m_iItemCount;
	}

	/**
	 * get the page count
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer page count
	 */
	function getPageCount(){
		if($this->m_iItemsPerPage>0){
			return ceil($this->m_iItemCount / $this->m_iItemsPerPage);
		}
		return 0;	
	}

	/**
	 * get previous page id
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer previous page id
	 */
	function getPrevPageId(){
		return $this->m_iPrevPageId;
	}

	/**
	 * get next page id
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer next page id
	 */
	function getNextPageId(){
		return $this->m_iNextPageId;
	}

	/**
	 * get current page id
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer current page id
	 */
	function getCurPageId(){
		return $this->m_iCurPageId;
	}

	/**
	 * get membervariables as array
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array member variables
	 */
	function &getDataArray(){
		return $this->m_arrResultList;
	}
}
?>