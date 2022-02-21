<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
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
 * save a banner
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.5 $
 */
class cAdminActionBannersave extends cAdminAction{

	/**
	 * perform the action
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function performAction(){

		$this->m_sOutput .= $this->_getHead();

		if($objActiveUser = &$this->m_objConfig->getActiveUser() && $objActiveUser->isAdmin()){

			$this->m_sOutput .= "<h4>save banner</h4>\n";

			$iBannerId = $this->m_objInputHandler->getIntFormVar("id",TRUE,TRUE,TRUE);
			$objBanner = new cBanner();

			if($iBannerId==0 || $objBanner->loadDataById($iBannerId)){
				$iStartTimestamp = mktime(0,0,0,$this->m_objInputHandler->getIntFormVar("month",TRUE,TRUE,TRUE),
										 		$this->m_objInputHandler->getIntFormVar("day",TRUE,TRUE,TRUE),
												$this->m_objInputHandler->getIntFormVar("year",TRUE,TRUE,TRUE));
				$iEndTimestamp = $iStartTimestamp+($this->m_objInputHandler->getIntFormVar("exp",TRUE,TRUE,TRUE)*86400);
				if($iEndTimestamp<=$iStartTimestamp){
					$iEndTimestamp = 0;
				}

				$objBanner->setBoardId($this->m_objInputHandler->getIntFormVar("board",TRUE,TRUE));
				$objBanner->setBannerCode($this->m_objInputHandler->getStringFormVar("code","banner",TRUE,TRUE,"trim"));
				$objBanner->setStartTimestamp($iStartTimestamp);
				$objBanner->setEndTimestamp($iEndTimestamp);
				$objBanner->setMaxViews($this->m_objInputHandler->getIntFormVar("maxviews",TRUE,TRUE,TRUE));

				$bSuccess = FALSE;
				if($iBannerId==0){
					if($objBanner->insertData()){
						$this->m_sOutput .= "<h3>banner saved</h3>";
						$bSuccess = TRUE;
					}
				}
				else{
					if($objBanner->updateData()){
						$this->m_sOutput .= "<h3>banner saved</h3>";
						$bSuccess = TRUE;
					}
				}
				if(!$bSuccess){
					$this->m_sOutput .= "<h3 id=\"e\">could not save banner</h3>";
				}
			}
			else $this->m_sOutput .= "<h3 id=\"e\">banner not found</h3>";
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>