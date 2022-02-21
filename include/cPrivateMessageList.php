<?php
require_once(INCLUDEDIR."/cScrollList.php");
require_once(INCLUDEDIR."/cMessageStates.php");
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
 * private message list handling (abstract class)
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.5 $
 */
class cPrivateMessageList extends cScrollList{

	var $m_iUserId;				// user id
	var $m_sDateFormat;			// date format
	var $m_iTimeOffset;			// time offset

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iUserId user id
	 * @param integer $iTimeOffset time offset
	 * @param string $sDateFormat date format
	 * @return void
	 */
	function cPrivateMessageList($iUserId,$iTimeOffset = 0,$sDateFormat = ""){

		$this->m_iUserId = intval($iUserId);
		$this->m_iTimeOffset = intval($iTimeOffset);
		$this->m_sDateFormat = $sDateFormat;

		cScrollList::cScrollList();
	}
}
?>