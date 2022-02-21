<?php
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
 * skin handling
 *
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.6 $
 */
class cSkin{

	var $m_iId;							// skin id
	var $m_sName;						// name
	var $m_sDirectory;					// subdirectory of the templates
	var $m_arrSupportedTemplateEngines; // supported template engines
	var $m_iTopFrameSize;				// tope frame size
	var $m_iBottomFrameSize;			// bottom frame size
	var $m_sQuotePrefix;				// prefix for quoted text
	var $m_sQuoteSuffix;				// suffix for quoted text
	var $m_arrThreadGraphics;			// graphics for thread visualisation
	var $m_arrAdditionalSkinValues;		// additional values

	/**
	 * Constructor
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return void
	 */
	function __construct(){

		$this->m_iId = 0;
		$this->m_sName = "";
		$this->m_sDirectory = "";
		$this->m_arrSupportedTemplateEngines = array();
		$this->m_iFrameTop = 0;
		$this->m_iFrameBottom = 0;
		$this->m_arrThreadGraphics = array("empty" => "","midc" => "","lastc" => "","noc" => "");
		$this->m_arrAdditionalSkinValues = array();
	}

	/**
	 * get data from database by skin id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iSkinId skin id
	 * @return boolean success / failure
	 */
	function loadDataById($iSkinId){

		$bReturn = FALSE;
		$iSkinId = intval($iSkinId);

		if($iSkinId>0){

			global $objDb;

			if($objResultSet = &$objDb->executeQuery("SELECT s_fieldname,s_fieldvalue FROM pxm_skin WHERE s_id=".$iSkinId)){
				if($objResultSet->getNumRows()>5){
					$bReturn = TRUE;
					$this->m_iId = $iSkinId;
					while($objResultRow = $objResultSet->getNextResultRowObject()){
						switch($objResultRow->s_fieldname){
							case "name"			:	$this->m_sName = $objResultRow->s_fieldvalue;
												 	break;
							case "dir"			:	$this->m_sDirectory = $objResultRow->s_fieldvalue;
													break;
							case "type"			:	$this->m_arrSupportedTemplateEngines = explode(",",$objResultRow->s_fieldvalue);
													break;
							case "frame_top"	:	$this->m_iTopFrameSize = intval($objResultRow->s_fieldvalue);
												 	break;
							case "frame_bottom"	:	$this->m_iBottomFrameSize = intval($objResultRow->s_fieldvalue);
												 	break;
							case "quoteprefix"	:	$this->m_sQuotePrefix = $objResultRow->s_fieldvalue;
												 	break;
							case "quotesuffix"	:	$this->m_sQuoteSuffix = $objResultRow->s_fieldvalue;
													break;
							default				:	if(strncmp($objResultRow->s_fieldname,"tgfx_",5)==0){
														$this->m_arrThreadGraphics[substr($objResultRow->s_fieldname,5)] = $objResultRow->s_fieldvalue;
													}
													else{
												  		$this->m_arrAdditionalSkinValues[$objResultRow->s_fieldname] = $objResultRow->s_fieldvalue;
													}
						}
					}
				}
				$objResultSet->freeResult();
				unset($objResultSet);
			}
		}
		return $bReturn;
	}

	/**
	 * update data in database
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean success / failure
	 */
	function updateData(){

		global $objDb;
		$bReturn = FALSE;

		if($this->m_iId>0){
			$objDb->executeQuery("UPDATE pxm_skin SET s_fieldvalue='".addslashes($this->m_sName)."' ".
										   "WHERE s_id=".$this->m_iId." AND s_fieldname='name'");
			$objDb->executeQuery("UPDATE pxm_skin SET s_fieldvalue='".addslashes($this->m_sDirectory)."' ".
										   "WHERE s_id=".$this->m_iId." AND s_fieldname='dir'");
			$objDb->executeQuery("UPDATE pxm_skin SET s_fieldvalue='".addslashes($this->m_iTopFrameSize)."' ".
										   "WHERE s_id=".$this->m_iId." AND s_fieldname='frame_top'");
			$objDb->executeQuery("UPDATE pxm_skin SET s_fieldvalue='".addslashes($this->m_iBottomFrameSize)."' ".
										   "WHERE s_id=".$this->m_iId." AND s_fieldname='frame_bottom'");
			$objDb->executeQuery("UPDATE pxm_skin SET s_fieldvalue='".addslashes($this->m_sQuotePrefix)."' ".
										   "WHERE s_id=".$this->m_iId." AND s_fieldname='quoteprefix'");
			$objDb->executeQuery("UPDATE pxm_skin SET s_fieldvalue='".addslashes($this->m_sQuoteSuffix)."' ".
										   "WHERE s_id=".$this->m_iId." AND s_fieldname='quotesuffix'");

			foreach($this->m_arrThreadGraphics as $sKey=>$sValue){
				$objDb->executeQuery("UPDATE pxm_skin SET s_fieldvalue='".addslashes($sValue)."' ".
											   "WHERE s_id=".$this->m_iId." AND s_fieldname='".addslashes("tgfx_".$sKey)."'");
			}
			foreach($this->m_arrAdditionalSkinValues as $sKey=>$sValue){
				$objDb->executeQuery("UPDATE pxm_skin SET s_fieldvalue='".addslashes($sValue)."' ".
											   "WHERE s_id=".$this->m_iId." AND s_fieldname='".addslashes($sKey)."'");
			}
			$bReturn = TRUE;
		}
		return $bReturn;
	}

	/**
	 * get id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer id
	 */
	function getId(){
		return $this->m_iId;
	}

	/**
	 * set id
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iId id
	 * @return void
	 */
	function setId($iId){
		$this->m_iId = intval($iId);
	}

	/**
	 * get name
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string name
	 */
	function getName(){
		return $this->m_sName;
	}

	/**
	 * set name
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sName name
	 * @return void
	 */
	function setName($sName){
		if(!empty($sName)){
			$this->m_sName = $sName;
		}
	}

	/** get top frame size
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer top frame size
	 */
	function getTopFrameSize(){
		return $this->m_iTopFrameSize;
	}

	/** get bottom frame size
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return integer bottom frame size
	 */
	function getBottomFrameSize(){
		return $this->m_iBottomFrameSize;
	}

	/**
	 * set frame size
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param integer $iTopFrameSize top frame size
	 * @param integer $iBottomFrameSize bottom frame size
	 * @return void
	 */
	function setFrameSize($iTopFrameSize,$iBottomFrameSize){
		$iTopFrameSize = intval($iTopFrameSize);
		$iBottomFrameSize = intval($iBottomFrameSize);
		if(($iTopFrameSize+$iBottomFrameSize)<=100){
			$this->m_iTopFrameSize = $iTopFrameSize;
			$this->m_iBottomFrameSize = $iBottomFrameSize;
		}
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
	 * get directory
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string directory
	 */
	function getDirectory(){
		return $this->m_sDirectory;
	}

	/**
	 * set directory
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sDirectory directory
	 * @return void
	 */
	function setDirectory($sDirectory){
		if(!empty($sDirectory)){
			$this->m_sDirectory = $sDirectory;
		}
	}

	/**
	 * get thread graphics
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array thread graphics
	 */
	function &getThreadGraphics(){
		return $this->m_arrThreadGraphics;
	}

	/**
	 * set thread graphics
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param array $arrThreadGraphics thread graphics
	 * @return void
	 */
	function setThreadGraphics(&$arrThreadGraphics){
		$this->m_arrThreadGraphics = &$arrThreadGraphics;
	}

	/**
	 * get supported template engines
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array supported template engines
	 */
	function &getSupportedTemplateEngines(){
		return $this->m_arrSupportedTemplateEngines;
	}

	/**
	 * set supported template engines
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param array $arrSupportedTemplateEngines supported template engines
	 * @return void
	 */
	function setSupportedTemplateEngines(&$arrSupportedTemplateEngines){
		$this->m_arrSupportedTemplateEngines = &$arrSupportedTemplateEngines;
	}

	/**
	 * get additional skin values
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return array additional skin values
	 */
	function &getAdditionalSkinValues(){
		return $this->m_arrAdditionalSkinValues;
	}

	/**
	 * set additional skin values
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param array $arrAdditionalSkinValues additional skin values
	 * @return void
	 */
	function setAdditionalSkinValues(&$arrAdditionalSkinValues){
		$this->m_arrAdditionalSkinValues = &$arrAdditionalSkinValues;
	}

	/**
	 * get membervariables as array
	 *
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param array  $arrAdditionalConfig additional configuration
	 * @return array member variables
	 */
	function getDataArray($arrAdditionalConfig = array()){
		return array_merge(array("id"			=>	$this->m_iId,
								 "name"		 	=>	$this->m_sName,
								 "frame_top"	=>	$this->m_iTopFrameSize,
								 "frame_bottom"	=>	$this->m_iBottomFrameSize),
						   $this->m_arrAdditionalSkinValues);
	}
}
?>