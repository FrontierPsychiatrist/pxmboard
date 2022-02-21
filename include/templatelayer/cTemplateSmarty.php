<?php
require_once(INCLUDEDIR."/templatelayer/cTemplate.php");
require_once(INCLUDEDIR."/lib/Smarty/libs/Smarty.class.php");
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
 * abstraction layer for output (smarty)
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:01:36 $
 * @version $Revision: 1.9 $
 */
class cTemplateSmarty extends cTemplate{

	var	$m_objSmarty;					// smarty template parser

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sSkinDir skin directory
	 * @return void
	 */
	function __construct($sSkinDir){

		parent::__construct($sSkinDir);
		$this->m_sTemplateExtension = ".tpl";

		$this->m_objSmarty = new Smarty();
		$this->m_objSmarty->compile_dir = $sSkinDir."/cache";
		$this->m_objSmarty->template_dir = $sSkinDir;
#		$this->m_objSmarty->clear_compiled_tpl();
		$this->m_objSmarty->enableSecurity();
		$this->m_objSmarty->muteUndefinedOrNullWarnings();
	}

	/**
	 * add data to the template (internal recursive template method)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param array $arrData key - value pairs
	 * @param string $sSubst subst string for integer keys
	 * @return boolean success / failure
	 */
	function _addDataRecursive(&$arrData,$sSubst = ""){

		$this->_quoteSpecialCharsRecursive($arrData);

		reset($arrData);
		while(list($mKey,$mVal) = each($arrData)){
			$this->m_objSmarty->assign($mKey,$mVal);
		}
	}

	/**
	 * quote special chars in an array recursive
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param array $arrData key - value pairs
	 * @return array array with quoted specialchars
	 */
	function _quoteSpecialCharsRecursive(&$arrData){
		foreach(array_keys($arrData) as $mKey){
			if(is_array($arrData[$mKey])){
				$this->_quoteSpecialCharsRecursive($arrData[$mKey]);
			}
			else if(is_string($mKey) && (strncmp($mKey,"_",1)!=0) && is_string($arrData[$mKey])){
				$arrData[$mKey] = htmlspecialchars($arrData[$mKey]);
			}
			else if($arrData[$mKey]===0){
				$arrData[$mKey] = "";
			}
		}
	}

	/**
	 * get the parsed template
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string parsed template
	 */
	function getOutput(){
		return $this->m_objSmarty->fetch($this->m_sTemplateName.$this->m_sTemplateExtension);
	}
}
?>