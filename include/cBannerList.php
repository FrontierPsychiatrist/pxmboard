<?php
require_once(INCLUDEDIR."/cBanner.php");
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
 * banner list handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.7 $
 */
class cBannerList{

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
	 * get all banners
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array banners
	 */
	function &getList(){

		$arrBanners = array();

		global $objDb;

		if($objResultSet = &$objDb->executeQuery("SELECT ba_boardid,b_name,ba_id,ba_code,ba_start,ba_expiration,ba_views,ba_maxviews FROM pxm_banner LEFT JOIN pxm_board ON ba_boardid=b_id")){
			while($objResultRow = $objResultSet->getNextResultRowObject()){

				$objBanner = new cBanner();
				$objBanner->setId($objResultRow->ba_id);
				$objBanner->setBoardId($objResultRow->ba_boardid);

				switch($objBanner->getBoardId()){
					case	0	:	$objBanner->setBoardName("boardindex");
									break;
					case	-1	:	$objBanner->setBoardName("boardindex & all boards");
									break;
					case	-2	:	$objBanner->setBoardName("all boards");
									break;
					default		:	if(isset($objResultRow->b_name)){
										$objBanner->setBoardName($objResultRow->b_name);
									}
									else{
										$objBanner->setBoardName("???");
									}
				}

				$objBanner->setBannerCode($objResultRow->ba_code);
				$objBanner->setStartTimestamp($objResultRow->ba_start);
				$objBanner->setEndTimestamp($objResultRow->ba_expiration);
				$objBanner->setViews($objResultRow->ba_views);
				$objBanner->setMaxViews($objResultRow->ba_maxviews);

				$arrBanners[] = $objBanner;
			}
			$objResultSet->freeResult();
		}
		return $arrBanners;
	}

	/**
	 * delete data from database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access array $arrBannerIds banner ids
	 * @return boolean success / failure
	 */
	function deleteFromList($arrBannerIds){

		if(sizeof($arrBannerIds)>0){

			global $objDb;

			if($objResultset = &$objDb->executeQuery("DELETE FROM pxm_banner WHERE ba_id IN (".implode(",",$arrBannerIds).")")){
				if($objResultset->getAffectedRows()==sizeof($arrBannerIds)){
					return TRUE;
				}
			}
		}
		return FALSE;
	}
}
?>