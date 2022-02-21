<?php
require_once(INCLUDEDIR."/actions/admin/cAdminAction.php");
require_once(INCLUDEDIR."/cSkin.php");
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
 * displays the skin edit form
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/30 17:57:48 $
 * @version $Revision: 1.6 $
 */
class cAdminActionSkinform extends cAdminAction{

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

			$objSkin = new cSkin();

			if($objSkin->loadDataById($this->m_objInputHandler->getIntFormVar("id",TRUE,TRUE,TRUE))){
				$this->m_sOutput .= "<h4>edit skin configuration</h4>\n";

				$this->m_sOutput .= "<form action=\"pxmboard.php\" method=\"post\">".$this->_getHiddenField("mode","admskinsave").$this->_getHiddenField("id",$objSkin->getId());
				$this->m_sOutput .= "\n<table border=\"0\">\n<tr><td><table border=\"1\" id=\"c\" width=\"100%\">\n<tr><td colspan=\"2\" id=\"h\">general configuration</td></tr>\n";

				$this->m_sOutput .= "<tr><td>supported template engines</td><td>".htmlspecialchars(implode(",",$objSkin->getSupportedTemplateEngines()))."</td></tr>\n";
				$this->m_sOutput .= $this->_getTextField("name",255,$objSkin->getName(),"name");
				$this->m_sOutput .= $this->_getTextField("dir",255,$objSkin->getDirectory(),"directory");
				$this->m_sOutput .= $this->_getTextField("ftop",2,$objSkin->getTopFrameSize(),"size of the top frame");
				$this->m_sOutput .= $this->_getTextField("fbottom",2,$objSkin->getBottomFrameSize(),"size of the bottom frame");
				$this->m_sOutput .= $this->_getTextField("quoteprefix",255,$objSkin->getQuotePrefix(),"prefix for quoted text");
				$this->m_sOutput .= $this->_getTextField("quotesuffix",255,$objSkin->getQuoteSuffix(),"suffix for quoted text");
				foreach($objSkin->getThreadGraphics() as $sKey=>$sValue){
					$this->m_sOutput .= $this->_getTextField("threadgraphic[$sKey]",255,$sValue,"threadgraphic ".$sKey);
				}
				$this->m_sOutput .= "</table></td></tr>\n";
				$this->m_sOutput .= "<tr><td><br><table border=\"1\" id=\"c\" width=\"100%\">\n<tr><td colspan=\"2\" id=\"h\">additional configuration</td></tr>\n";
				foreach($objSkin->getAdditionalSkinValues() as $sKey=>$sValue){
					$this->m_sOutput .= $this->_getTextField("additionalvalues[".$sKey."]",255,$sValue,$sKey);
				}
				$this->m_sOutput .= "</table></td></tr>\n";
				$this->m_sOutput .= "<tr><td align=\"center\"><input type=\"submit\" value=\"update data\">&nbsp;<input type=\"reset\" value=\"reset data\"></td></tr></table></form>";
			}
			else $this->m_sOutput .= "<h3 id=\"e\">couldn't find skin</h3>";
		}
		else $this->m_sOutput .= "<h3 id=\"e\">forbidden</h3>";

		$this->m_sOutput .= $this->_getFooter();
	}
}
?>