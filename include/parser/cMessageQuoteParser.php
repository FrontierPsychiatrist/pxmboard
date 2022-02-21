<?php
require_once(INCLUDEDIR."/parser/cParser.php");
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
 * message text parsing
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:01:24 $
 * @version $Revision: 1.2 $
 */
class cMessageQuoteParser extends cParser{

	var $m_arrReplacements;			// textreplacements (array[search];array[replace])
	var	$m_cQuoteChar;				// quote char

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function cMessageQuoteParser(){
		$this->m_arrReplacements = array("search"=>array(),"replace"=>array());
		$this->m_cQuoteChar = "";
	}

	/**
	 * parse the given text
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param string $sText text to be parsed
	 * @return string parsed text
	 */
	function &parse($sText){

		$sReturnText = "";
		if (strlen($sText) > 0) {
			$sReturnText .= $this->m_cQuoteChar;
			$this->m_arrReplacements["search"][] = "\n";
			$this->m_arrReplacements["replace"][] = "\n".$this->m_cQuoteChar;
			$sReturnText .= str_replace($this->m_arrReplacements["search"],$this->m_arrReplacements["replace"], htmlspecialchars($sText));
		}
		return $sReturnText;
	}

	/**
	 * get replacements
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array replacements
	 */
	function &getReplacements(){
		return $this->m_arrReplacements;
	}

	/**
	 * set replacements
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param array $arrReplacements replacements
	 * @return void
	 */
	function setReplacements(&$arrReplacements){
		$this->m_arrReplacements = &$arrReplacements;
	}

	/**
	 * get quote char
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string quote char
	 */
	function getQuoteChar(){
		return $this->m_cQuoteChar;
	}

	/**
	 * set quote char
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param char $cQuoteChar quote char
	 * @return void
	 */
	function setQuoteChar($cQuoteChar){
		$this->m_cQuoteChar = $cQuoteChar;
	}
}
?>