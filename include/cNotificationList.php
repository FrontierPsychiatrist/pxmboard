<?php
require_once(INCLUDEDIR."/cNotification.php");
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
 * handles the notifications of the system
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.5 $
 */
class cNotificationList{

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
	 * get all notifications
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array notifications
	 */
	function &getList(){

		global $objDb;

		$arrNotifications = array();

		if($objResultSet = &$objDb->executeQuery("SELECT n_id,n_message,n_name,n_description FROM pxm_notification ORDER BY n_id ASC")){
			while($objResultRow = $objResultSet->getNextResultRowObject()){

				$objNotification = new cNotification();
				$objNotification->setId($objResultRow->n_id);
				$objNotification->setMessage($objResultRow->n_message);
				$objNotification->setName($objResultRow->n_name);
				$objNotification->setDescription($objResultRow->n_description);

				$arrNotifications[intval($objResultRow->n_id)] = $objNotification;
			}
			$objResultSet->freeResult();
		}
		return $arrNotifications;
	}
}
?>