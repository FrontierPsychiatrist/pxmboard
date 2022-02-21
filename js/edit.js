<!--
// edit script for pxmboard
// copyright (c) 2001 - 2006 by Torsten Rentsch <forum@torsten-rentsch.de>
// see http://www.pxmboard.de for details

function formatText(tag){
	var selectedText = getSelectedText();
	if(selectedText.length<1){
		alert("Bitte erst Text markieren");
	}
	else{
		updateSelectedText("["+tag+":"+selectedText+"]");
	}
}

function createLink(){
	var selectedText = trimText(getSelectedText());

	while( (selectedText != null) && ( (selectedText.length < 8) || ( (selectedText.substr(0,4).toLowerCase() != "http") && (selectedText.substr(0,3).toLowerCase() != "ftp") && (selectedText.substr(0,3).toLowerCase() != "www") && (selectedText.substr(0,7).toLowerCase() != "mailto:") ) ) ){
		selectedText = trimText(prompt("Bitte geben Sie den Link ein.\nDieser muss mit \"http\", \"ftp\", \"www\" oder \"mailto:\" beginnen!",selectedText));
	}
	if(selectedText != null){
		updateSelectedText("["+encodeText(selectedText)+"]");
	}
}

function createImgLink(){
	var selectedText = trimText(getSelectedText());

	while ( (selectedText != null) && ( (selectedText.length < 17) || (selectedText.substr(0,4).toLowerCase() != "http") || ( (selectedText.substr((selectedText.length-4)).toLowerCase() != ".jpg") && (selectedText.substr((selectedText.length-4),4).toLowerCase() != ".gif") && (selectedText.substr((selectedText.length-4)).toLowerCase() != ".png") ) ) ){
		selectedText = trimText(prompt("Bitte geben Sie die URL des Bildes ein.\nDiese muss mit \"http\" beginnen und mit \".jpg\", \".gif\" oder \".png\" enden!",selectedText));
	}
	if(selectedText != null){
		updateSelectedText("[img:"+encodeText(selectedText)+"]");
	}
}

function trimText(text){
	if(text != null){
		text = text.replace(/^ +/,"");
		text = text.replace(/ +$/,"");
	}
	return text;
}

function encodeText(text){
	if(text != null){
		text = text.replace(/ /g,"%20");
		text = text.replace(/\[/,"%5B");
		text = text.replace(/\]/,"%5D");
	}
	return text;
}

function updateSelectedText(text){
	if(text != ""){
		document.forms[0].body.focus();
		if(document.selection && document.selection.createRange){
			var ziel = document.selection.createRange();
			ziel.text = text;
		}
		else if(!isNaN(document.forms[0].body.selectionEnd)){
			document.forms[0].body.value = document.forms[0].body.value.substring(0,document.forms[0].body.selectionStart)+text+document.forms[0].body.value.substring(document.forms[0].body.selectionEnd,document.forms[0].body.value.length);
		}
	}
}

function getSelectedText(){
	var selectedText = "";
	if(document.selection && document.selection.createRange){
		selectedText = document.selection.createRange().text;
	}
	else if(!isNaN(document.forms[0].body.selectionEnd)){
		selectedText = document.forms[0].body.value.substring(document.forms[0].body.selectionStart,document.forms[0].body.selectionEnd);
	}
	return selectedText;
}

function isSelectionSupported(){
	return (!isNaN(document.forms[0].body.selectionEnd)||(document.selection && document.selection.createRange));
}
//-->