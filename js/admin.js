<!--
// admin script for pxmboard
// copyright (c) 2001 - 2006 by Torsten Rentsch <forum@torsten-rentsch.de>
// see http://www.pxmboard.de for details

function adminaction(action,boardid,id){
	switch(action){
	  	case "deletemessage"	:	result = confirm("Soll Nachricht "+id+" geloescht werden?");
								  	if(result == true) location.href="pxmboard.php?brdid="+boardid+"&mode=messagedelete&id="+id;
									break;
	  	case "deletesubthread"	:	result = confirm("Soll die Nachricht "+id+" und alle Antworten darauf geloescht werden?");
								  	if(result == true) location.href="pxmboard.php?brdid="+boardid+"&mode=threadpartdelete&id="+id;
									break;
	  	case "extractsubthread"	:	result = confirm("Soll die Nachricht "+id+" und alle Antworten darauf ausgegliedert werden?");
								  	if(result == true) location.href="pxmboard.php?brdid="+boardid+"&mode=threadpartextract&id="+id;
									break;
	  	case "threadstatus"		:	result = confirm("Soll der Status dieses Threads geaendert werden?");
								  	if(result == true) location.href="pxmboard.php?brdid="+boardid+"&mode=threadchangestatus&id="+id;
									break;
	  	case "fixthread"		:	result = confirm("Soll der Thread fixiert / geloest werden?");
								  	if(result == true) location.href="pxmboard.php?brdid="+boardid+"&mode=threadchangefixed&id="+id;
									break;
	  	case "deletethread"		:	result = confirm("Soll dieser Thread geloescht werden?");
								  	if(result == true) location.href="pxmboard.php?brdid="+boardid+"&mode=threaddelete&id="+id;
									break;
	  	case "movethread"		:	window.open("pxmboard.php?mode=admthreadmove&brdid="+boardid+"&id="+id,"movethrd","width=350,height=150,scrolling=auto,scrollbars=1,resizable=1");
									break;

	}									
}
//-->