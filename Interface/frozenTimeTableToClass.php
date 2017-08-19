<?php
//-------------------------------------------------------
// Purpose: To generate assign time table label to class from the database, and have add/edit/delete, search 
// functionality 
//
// Author : Jaineesh
// Created on : (30.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FrozenTimeTableToClass');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Freeze/Backup Data</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(	new Array('checkAll','<input type="checkbox" name="checkbox2" value="checkbox" onClick="doAll()">','width="3%"','align="center"',false),
								new Array('srNo','#','width="3%"','',false), 
								new Array('className','Class Name','width="50%"','',true),
								new Array('marksTransferred','External marks entered','width="20%"','align="center"',true),
								new Array('frozen','Is Frozen','width="20%"','align=center',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FrozenClass/ajaxInitList.php';
searchFormName = 'listForm'; // name of the form which will be used for search
divResultName  = 'results';
page=1; //default page
sortField = 'subjectName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
       displayWindow(dv,w,h);
       populateValues(id);
}

function getClasses(){

	if(isEmpty(document.getElementById('labelId').value)){
       messageBox("<?php echo 'Please Select Time Table';?>");
	   //document.getElementById('saveDiv').style.display='none';
	   document.getElementById('saveDiv1').style.display='none';
	   document.getElementById('showTitle').style.display='none';
	   document.getElementById('showData').style.display='none';
	   document.getElementById('txtReason').style.display='none';
	   document.getElementById('reason').value='';
	   document.getElementById('results').innerHTML=" ";
	   document.listForm.labelId.focus();
	   return false;
   }
   else{
	  // document.getElementById('saveDiv').style.display='';
	   document.getElementById('saveDiv1').style.display='';
	   document.getElementById('showTitle').style.display='';
	   document.getElementById('showData').style.display='';
	   document.getElementById('txtReason').style.display='';
	   document.getElementById('reason').value='';
       sendReq(listURL,divResultName,'listForm','');
   }      
		 
}

function clearText(){

    document.getElementById('saveDiv1').style.display='none';	 	
	document.getElementById('showTitle').style.display='none';	 	
	document.getElementById('showData').style.display='none';
	document.getElementById('txtReason').style.display='none';
	document.getElementById('reason').value='';
	document.getElementById('results').innerHTML="";
}

function insertForm() {
 
	 url = '<?php echo HTTP_LIB_PATH;?>/FrozenClass/initLabelAdd.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: $('listForm').serialize(true),
		 onCreate: function() {
			 showWaitDialog(true); 
		 },
		 onSuccess: function(transport){

			 hideWaitDialog(true);
			 if("<?php echo FROZEN_SUCCESS;?>" == trim(transport.responseText)) {  
				 flag = true;
					 alert(trim(transport.responseText));
					 clearText();
					 return false;
			 }
			 else if("<?php echo ERROR_CGPA_NOT_CALCULATED;?>" == trim(transport.responseText)) {  
				 //flag = true;
					 alert(trim(transport.responseText));
					 return false;
			 }
			 else {
					str = trim(transport.responseText);
					messageBox(trim(str));
					//document.getElementById('listForm').reset(); 
			 }
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function insertUnfreezeForm() {
 
	 url = '<?php echo HTTP_LIB_PATH;?>/FrozenClass/initUnfreezeAdd.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: $('listForm').serialize(true),
		 onCreate: function() {
			 showWaitDialog(true); 
		 },
		 onSuccess: function(transport){

			 hideWaitDialog(true);
			 if("<?php echo UNFROZEN_SUCCESS;?>" == trim(transport.responseText)) {  
				 flag = true;
					 alert(trim(transport.responseText));
					 clearText();
					 return false;
			 } 
			 else {
					str = trim(transport.responseText);
					messageBox(trim(str));
					//document.getElementById('listForm').reset(); 
			 }
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

 
 

function validateAddForm(frm, act) {
    
	var selected=0;
	var selected1=0;
	//var midsemValueString = [];
	//var finalExamString = [];
	formx = document.listForm;
	for(var i=1;i<formx.length;i++){

		if(formx.elements[i].disabled){

			 {selected1++;}
		} 
	}
	if(selected1>0){

		alert("<?php echo TIMETABLE_INACTIVE_CLASS?>");
		return false;
	}
	for(var i=1;i<formx.length;i++){

		if(formx.elements[i].type=="checkbox"){

			if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]"))
			{selected++;}
		
		}
	}
	if(selected==0){

		alert("<?php echo SELECT_ATLEAST_ONE_CLASS?>");
		return false;
	}

	if(formx.reason.value=="") {
		alert("<?php echo ENTER_REASON?>");
		formx.reason.focus();
		return false;
	}
	if(isEmpty(formx.reason.value)) {
		alert("<?php echo ENTER_REASON?>");
		formx.reason.focus();	
		return false;
	}

    insertForm();
	return false;
}

function validateUnfreezeForm(frm, act) {
    
	var selected=0;
	var selected1=0;
	//var midsemValueString = [];
	//var finalExamString = [];
	formx = document.listForm;
	for(var i=1;i<formx.length;i++){

		if(formx.elements[i].disabled){

			 {selected1++;}
		} 
	}
	if(selected1>0){

		alert("<?php echo TIMETABLE_INACTIVE_CLASS?>");
		return false;
	}
	for(var i=1;i<formx.length;i++){

		if(formx.elements[i].type=="checkbox"){

			if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]"))
			{selected++;}
		
		}
	}
	if(selected==0){

		alert("<?php echo SELECT_ATLEAST_ONE_CLASS?>");
		return false;
	}

	if(formx.reason.value=="") {
		alert('Enter reason');
		formx.reason.focus();
		return false;
	}
	if(isEmpty(formx.reason.value)) {
		alert("<?php echo ENTER_REASON?>");
		formx.reason.focus();	
		return false;
	}

    insertUnfreezeForm();
	return false;
}

 
function doAll(){

	formx = document.listForm;
	if(formx.checkbox2.checked){
		for(var i=1;i<formx.length;i++){

			if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
			
				formx.elements[i].checked=true;
			}
		}
	}
	else{
		for(var i=1;i<formx.length;i++){
			
			if(formx.elements[i].type=="checkbox"){
			
				formx.elements[i].checked=false;
			}
		}
	}
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/FrozenClass/frozenTimeTableToClassContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: frozenTimeTableToClass.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/23/10    Time: 12:34p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0002686, 0002685, 0002669
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/21/10    Time: 1:59p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0002672, 0002660, 0002657, 0002656, 0002658, 0002659,
//0002661, 0002662
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 8/07/09    Time: 1:47p
//Created in $/LeapCC/Interface
//add new file for frozen time table to class
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/02/09    Time: 11:49a
//Created in $/Leap/Source/Interface
//new file for frozen class
//
?>