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
 * @version $Date: 2006/01/12 22:46:48 $
 * @version $Revision: 1.3 $
 */
class cMessageHtmlParser extends cParser{

	var $m_arrReplacements;			// textreplacements (array[search];array[replace])
	var	$m_cQuoteChar;				// quote char
	var $m_sQuotePrefix;			// quote prefix
	var $m_sQuoteSuffix;			// quote suffix
	var $m_bParseUrl;				// parse urls?
	var $m_bParseImg;				// parse img tags?
	var $m_bParseStyle;				// parse style tags?

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function __construct(){
        parent::__construct();
		$this->m_arrReplacements = array("search"=>array(),"replace"=>array());
		$this->m_cQuoteChar = "";
		$this->m_sQuotePrefix = "";
		$this->m_sQuoteSuffix = "";
		$this->m_bParseUrl = FALSE;
		$this->m_bParseImg = FALSE;
		$this->m_bParseStyle = FALSE;
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
		if (($iBodyLength = strlen($sText)) > 0) {
			$iCharPointer = 0;
			$arrStyleStack = array();

			if($sText[0] == $this->m_cQuoteChar){
				$sReturnText .= $this->m_sQuotePrefix;
				$bIsInQuote = TRUE;
			}
			else{
				$bIsInQuote = FALSE;
			}
			while($iCharPointer<$iBodyLength){
				$iCharPointerTextLength = strcspn(substr($sText,$iCharPointer),"[]\n");

				if($iCharPointerTextLength>0){		// process normal text
					$sReturnText .= str_replace($this->m_arrReplacements["search"],$this->m_arrReplacements["replace"],htmlspecialchars(substr($sText,$iCharPointer,$iCharPointerTextLength)));
					$iCharPointer += $iCharPointerTextLength;
				}
				if($iCharPointer<$iBodyLength){
					switch($sText[$iCharPointer]){		// special char
						case	"\n":	if(strcmp(substr($sText,$iCharPointer,2),"\n".$this->m_cQuoteChar) == 0){
											if($bIsInQuote){
												$sReturnText .= "<br />\n>";
											}
											else{
												while(sizeof($arrStyleStack)>0){
													$sReturnText .= "</".array_pop($arrStyleStack).">";
												}
												$sReturnText .= "<br />\n".$this->m_sQuotePrefix.$this->m_cQuoteChar;
												$bIsInQuote = TRUE;
											}
											++$iCharPointer;
										}
										else{
											if($bIsInQuote){
												while(sizeof($arrStyleStack)>0){
													$sReturnText .= "</".array_pop($arrStyleStack).">";
												}
												$sReturnText .= $this->m_sQuoteSuffix."<br />\n";
												$bIsInQuote = FALSE;
											}
											else{
												$sReturnText .= "<br />\n";
											}
										}
										break;
						case	"["	:	if( $this->m_bParseStyle && preg_match("/^\[([bBiIuUsS]):/",substr($sText,$iCharPointer,3),$arrStyleMatch) ){
											$sStyle = strtolower($arrStyleMatch[1]);
											if(!in_array($sStyle,$arrStyleStack)){
												$sReturnText .= "<".$sStyle.">";
												array_push($arrStyleStack,$sStyle);
											}
											else{
												$sReturnText .= "[".$sStyle.":";
											}
											$iCharPointer += 2;
										}
										else if($this->m_bParseUrl && preg_match("/^\[((http|ftp|www|mailto:)([^\] ]+))/i",substr($sText,$iCharPointer),$arrLinkMatch) ){

											if(strcasecmp($arrLinkMatch[2],"www") == 0 ){
												$sReturnText .= "[<a href=\"http://".htmlspecialchars($arrLinkMatch[1])."\" target=\"_blank\">".htmlspecialchars($arrLinkMatch[1])."</a>]";
											}
											else if(strcasecmp($arrLinkMatch[2],"mailto:") == 0 ){
												$sReturnText .= "[<a href=\"".htmlspecialchars($arrLinkMatch[1])."\">".htmlspecialchars($arrLinkMatch[3])."</a>]";
											}
											else{
												$sReturnText .= "[<a href=\"".htmlspecialchars($arrLinkMatch[1])."\" target=\"_blank\">".htmlspecialchars($arrLinkMatch[1])."</a>]";
											}
											$iCharPointer += strlen($arrLinkMatch[1])+1;
										}
										else if(($this->m_bParseImg || $this->m_bParseUrl) && preg_match("/^\[img:((http[^\] ?]+)\.(?:jpg|gif|png))/i",substr($sText,$iCharPointer),$arrImgMatch) ){
											if($this->m_bParseImg && !$bIsInQuote ){
												$sReturnText .= "<img src=\"".htmlspecialchars($arrImgMatch[1])."\" />";
											}
											else if($this->m_bParseUrl){
												$sReturnText .= "[<a href=\"".htmlspecialchars($arrImgMatch[1])."\" target=\"_blank\">".htmlspecialchars($arrImgMatch[1])."</a>]";
											}
											else{
												$sReturnText .= "[img:".htmlspecialchars($arrImgMatch[1])."]";
											}
											$iCharPointer += strlen($arrImgMatch[1])+5;
										}
										else{
											$sReturnText .= $sText[$iCharPointer];
										}
										break;
						case	"]"	:	if(sizeof($arrStyleStack)>0){
											$sReturnText .= "</".array_pop($arrStyleStack).">";
										}
										else{
											$sReturnText .= "]";
										}
										break;
					}
				}
				++$iCharPointer;
			}
			while(sizeof($arrStyleStack)>0){
				$sReturnText .= "</".array_pop($arrStyleStack).">";
			}
			if($bIsInQuote){
				$sReturnText .= $this->m_sQuoteSuffix;
			}
			$sReturnText = str_replace("  "," &nbsp;",$sReturnText);
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

	/**
	 * get quote prefix
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string quote prefix
	 */
	function getQuotePrefix(){
		return $this->m_sQuotePrefix;
	}

	/**
	 * set quote prefix
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sQuotePrefix quote prefix
	 * @return void
	 */
	function setQuotePrefix($sQuotePrefix){
		$this->m_sQuotePrefix = $sQuotePrefix;
	}

	/**
	 * get quote suffix
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string quote suffix
	 */
	function getQuoteSuffix(){
		return $this->m_sQuoteSuffix;
	}

	/**
	 * set quote suffix
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sQuoteSuffix quote suffix
	 * @return void
	 */
	function setQuoteSuffix($sQuoteSuffix){
		$this->m_sQuoteSuffix = $sQuoteSuffix;
	}

	/**
	 * parse style tags?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean parse style tags?
	 */
	function parseStyle(){
		return $this->m_bParseStyle;
	}

	/**
	 * set parse style tags
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bParseStyle parse style tags?
	 * @return void
	 */
	function setParseStyle($bParseStyle){
		$this->m_bParseStyle = $bParseStyle?TRUE:FALSE;
	}

	/**
	 * parse url tags?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean parse url tags?
	 */
	function parseUrl(){
		return $this->m_bParseUrl;
	}

	/**
	 * set parse url tags
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bParseUrl parse url tags?
	 * @return void
	 */
	function setParseUrl($bParseUrl){
		$this->m_bParseUrl = $bParseUrl?TRUE:FALSE;
	}

	/**
	 * parse img tags?
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean parse / don't parse image tags
	 */
	function parseImages(){
		return $this->m_bParseImg;
	}

	/**
	 * set parse img tags
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param boolean $bParseImages parse / don't parse image tags
	 * @return void
	 */
	function setParseImages($bParseImages){
		$this->m_bParseImg = $bParseImages?TRUE:FALSE;
	}
}
?>