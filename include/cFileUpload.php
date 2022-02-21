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
 * handles file uploads
 * 
 * @author Torsten Rentsch <forum@torsten-rentsch.de>
 * @copyright Torsten Rentsch 2001 - 2006
 * @version $Date: 2005/12/29 15:00:47 $
 * @version $Revision: 1.4 $
 */
class cFileUpload{

	var $m_sFileVarName = "";

	/**
	 * Constructor
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param string $sFileVarName name of the file variable
	 * @return void
	 */
	function cFileUpload($sFileVarName){
		$this->m_sFileVarName = $sFileVarName;
	}

	/**
	 * was this file uploaded via HTTP POST?
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return boolean is / is not an uploaded file
	 */
	function isUploadedFile(){
		if(isset($_FILES[$this->m_sFileVarName])){
			return is_uploaded_file($_FILES[$this->m_sFileVarName]["tmp_name"]);
		}
		else return FALSE;
	}

	/**
	 * get the original name of the file
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string original name of the file
	 */
	function getFileName(){
		return $_FILES[$this->m_sFileVarName]["name"];
	}

	/**
	 * get the mime type of the file
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string mime type of the file
	 */
	function getFileType(){
		return $_FILES[$this->m_sFileVarName]["type"];
	}

	/**
	 * get the size of the file
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string size of the file
	 */
	function getFileSize(){
		return $_FILES[$this->m_sFileVarName]["size"];
	}

	/**
	 * get the temporary filename of the file
	 * 
	 * @author Torsten Rentsch <forum@torsten-rentsch.de>
	 * @access public
	 * @param void
	 * @return string temporary filename of the file
	 */
	function getFileTmpName(){
		return $_FILES[$this->m_sFileVarName]["tmp_name"];
	}
}
?>