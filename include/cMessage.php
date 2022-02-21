<?php
require_once(INCLUDEDIR."/cMessageHeader.php");
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
 * message handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.10 $
 */
class cMessage extends cMessageHeader{

	var $m_sBody;				// messagebody
	var	$m_sIp;					// ip number for this message

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function cMessage(){

		cMessageHeader::cMessageHeader();

		$this->m_sBody = "";
		$this->m_sIp = "";
	}

	/**
	 * initalize the member variables with the resultset from the db
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param object $objResultRow resultrow from db query
	 * @return boolean success / failure
	 */
	function _setDataFromDb(&$objResultRow){

		cMessageHeader::_setDataFromDb($objResultRow);

		$this->m_sBody = $objResultRow->m_body;
		$this->m_sIp = $objResultRow->m_ip;

		return TRUE;
	}

	/**
	 * get additional database attributes for this object (template method)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param void
	 * @return string additional database attributes for this object
	 */
	 function _getDbAttributes(){
	 	return cMessageHeader::_getDbAttributes().",m_body,m_ip";
	 }

	/**
	 * get message body
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string message body
	 */
	function getBody(){
		return $this->m_sBody;
	}

	/**
	 * set message body
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sBody message body
	 * @return void
	 */
	function setBody($sBody){
		$this->m_sBody = $sBody;
	}

	/**
	 * get ip
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string ip
	 */
	function getIp(){
		return $this->m_sIp;
	}

	/**
	 * set ip
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sIp ip
	 * @return void
	 */
	function setIp($sIp){
		$this->m_sIp = $sIp;
	}

	/**
	 * get membervariables as array
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iTimeOffset time offset in seconds
	 * @param string $sDateFormat php date format
	 * @param integer $iLastOnlineTimestamp last online timestamp for user
	 * @param string $sSubjectQuotePrefix prefix for quoted subject
 	 * @param object $objParser message parser
	 * @return array member variables
	 */
	function getDataArray($iTimeOffset,$sDateFormat,$iLastOnlineTimestamp,$sSubjectQuotePrefix,&$objParser){
		return array_merge(cMessageHeader::getDataArray($iTimeOffset,$sDateFormat,$iLastOnlineTimestamp,$sSubjectQuotePrefix,$objParser),
						   array("_body"=>	$objParser->parse($this->getBody()),
						   		 "ip"	=>	$this->m_sIp));
	}
}
?>