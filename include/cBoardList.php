<?php
require_once(INCLUDEDIR."/cBoard.php");
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
 * boardlist handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.8 $
 */
class cBoardList{

	var	$m_arrBoards;			// boards

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function __construct(){
		$this->m_arrBoards = array();
	}

	/**
	 * get data from database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean success / failure
	 */
	function loadData(){

		global $objDb;

		if($objResultSet = &$objDb->executeQuery("SELECT b_id,b_name,b_description,b_position,b_lastmsgtstmp,b_active FROM pxm_board ORDER BY b_position ASC")){

			while($objResultRow = $objResultSet->getNextResultRowObject()){

				$objBoard = new cBoard();

				$objBoard->setId($objResultRow->b_id);
				$objBoard->setName($objResultRow->b_name);
				$objBoard->setDescription($objResultRow->b_description);
				$objBoard->setPosition($objResultRow->b_position);
				$objBoard->setLastMessageTimestamp($objResultRow->b_lastmsgtstmp);
				$objBoard->setIsActive($objResultRow->b_active);

				$objBoard->loadModData();

				$this->m_arrBoards[] = $objBoard;
			}
			$objResultSet->freeResult();
		}
		else{
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * get basic data from database (without description, last message date and moderators)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean success / failure
	 */
	function loadBasicData(){

		global $objDb;

		if($objResultSet = &$objDb->executeQuery("SELECT b_id,b_name,b_position,b_active FROM pxm_board ORDER BY b_position ASC")){

			while($objResultRow = $objResultSet->getNextResultRowObject()){

				$objBoard = new cBoard();

				$objBoard->setId($objResultRow->b_id);
				$objBoard->setName($objResultRow->b_name);
				$objBoard->setPosition($objResultRow->b_position);
				$objBoard->setIsActive($objResultRow->b_active);

				$this->m_arrBoards[] = $objBoard;
			}
			$objResultSet->freeResult();
		}
		else{
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * open boards
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param array $arrBoardIds board ids
	 * @return boolean success / failure
	 */
	function openBoards($arrBoardIds){

		global $objDb;

		if(sizeof($arrBoardIds)>0){
			if(!$objDb->executeQuery("UPDATE pxm_board SET b_active=1 WHERE b_id IN (".implode(",",$arrBoardIds).")")){
				return FALSE;
			}
		}
		return TRUE;
	}

	/**
	 * close all boards
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array closed boards
	 */
	function closeAllBoards(){

		global $objDb;
		$arrClosedBoards = array();

		if($objResultSet = &$objDb->executeQuery("SELECT b_id FROM pxm_board WHERE b_active=1")){
			while($objResultRow = $objResultSet->getNextResultRowObject()){
				$arrClosedBoards[] = $objResultRow->b_id;
			}
			$objDb->executeQuery("UPDATE pxm_board SET b_active=0");
		}
		return $arrClosedBoards;
	}

	/**
	 * get membervariables as array
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iTimeOffset time offset in seconds
	 * @param string $sDateFormat php date format
	 * @param integer $iLastOnlineTimestamp last online timestamp for user
	 * @param object $objParser message parser (for signature)
	 * @return array member variables
	 */
	function &getDataArray($iTimeOffset,$sDateFormat,$iLastOnlineTimestamp,&$objParser){

		$arrOutput = array();
		reset($this->m_arrBoards);
		foreach($this->m_arrBoards as $objBoard) {
			$arrOutput[] = $objBoard->getDataArray($iTimeOffset,$sDateFormat,$iLastOnlineTimestamp,$objParser);
		}
		return $arrOutput;
	}
}
?>