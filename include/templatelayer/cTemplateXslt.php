<?php
require_once(INCLUDEDIR."/templatelayer/cTemplate.php");
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
 * abstraction layer for output (xslt)
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2006/01/16 20:58:03 $
 * @version $Revision: 1.2 $
 */
class cTemplateXslt extends cTemplate{

	var	$m_sXmlDoc;					// xml file for the data

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sSkinDir skin directory
	 * @return void
	 */
	function cTemplateXslt($sSkinDir){

		cTemplate::cTemplate($sSkinDir);
		$this->m_sTemplateExtension = ".xsl";

		$this->m_sXmlDoc = 	 "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n"
							."<!DOCTYPE xsl:stylesheet [\n<!ENTITY nbsp \"&#160;\">\n]>\n"
							."<pxmboard>\n";
	}

	/**
	 * add data to the template (internal recursive template method)
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access private
	 * @param array $arrData key - value pairs
	 * @param string $sSubst subst string for integer keys
	 * @return object domnode
	 */
	function _addDataRecursive(&$arrData,$sSubst = ""){
		reset($arrData);
		while(list($mKey,$mVal) = each($arrData)){
			if(is_integer($mKey) && !empty($sSubst)){
				$mKey = $sSubst;
			}
			if(empty($mKey)){
				return FALSE;
			}

			if(is_string($mKey) && (strncmp($mKey,"_",1)==0)){
				$mKey = substr($mKey,1);
			}
			if(is_array($mVal)){
				if(!empty($mVal)){
					if(is_integer(key($mVal))){
						$this->_addDataRecursive($mVal,$mKey);
					}
					else{
						$this->m_sXmlDoc .= "<$mKey>\n";
						$this->_addDataRecursive($mVal,$sSubst);
						$this->m_sXmlDoc .= "</$mKey>\n";
					}
				}
			}
			else{
				if( $mVal===0 || $mVal==="" ){
					$this->m_sXmlDoc .= "<$mKey/>\n";
				}
				else{
					$this->m_sXmlDoc .= "<$mKey><![CDATA[$mVal]]></$mKey>\n";
				}
			}
		}
		return TRUE;
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

		$this->m_sXmlDoc .= "</pxmboard>\n";

		if(extension_loaded("xsl")){		// PHP 5
			$objXsltProcessor = new XSLTProcessor();

			// disable output escaping does not work properly with < php5.1
			if(defined("LIBXML_NOCDATA")){
				$objXsltProcessor->importStyleSheet(DOMDocument::load($this->m_sSkinDir."/".$this->m_sTemplateName.$this->m_sTemplateExtension, LIBXML_NOCDATA));
			}
			else{
				$objXsltProcessor->importStyleSheet(DOMDocument::load($this->m_sSkinDir."/".$this->m_sTemplateName.$this->m_sTemplateExtension));
			}
			return $objXsltProcessor->transformToXML(DOMDocument::loadXML($this->m_sXmlDoc));
		}
		else if(extension_loaded("domxml")){// PHP 4
			$resXslt = domxml_xslt_stylesheet(join("",file($this->m_sSkinDir."/".$this->m_sTemplateName.$this->m_sTemplateExtension)));
			$objResult = $resXslt->process(domxml_open_mem($this->m_sXmlDoc));
			return $objResult->dump_mem();
		}
		return "xslt not supported!";
	}
}
?>