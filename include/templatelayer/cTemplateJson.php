<?php
require_once(INCLUDEDIR . "/templatelayer/cTemplate.php");

class cTemplateJson extends cTemplate
{

	private array $m_aMergedData = array();

	function __construct($sSkinDir){
		parent::__construct($sSkinDir);
	}

	function _addDataRecursive(&$arrData, $sSubst = ""){
		// This will be called potentially multiple times. Everytime add all data
		$this->m_aMergedData = array_merge($this->m_aMergedData, $arrData);
		return TRUE;
	}

	function getOutput(){
		return json_encode($this->m_aMergedData);
	}

	function getContentType(): string{
		return "application/json";
	}
}
?>