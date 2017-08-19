<?php
//-------------------------------------------------------
// Purpose: To Hold/Unhold Student Marks
// functionality 
//
// Author : Jaineesh
// Created on : (14.05.2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HoldUnholdStudentResult');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Hold/Unhold Student Result</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(new Array('checkAll','<input type="checkbox" name="checkbox2" value="checkbox" onClick="doAll()">','width="3%"','',false),new Array('srNo','#','width="3%"','',false), new Array('className','Class Name','width="94%"','',true));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/TimeTable/scAjaxInitList.php';
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
       messageBox("<?php echo 'Please select time table label';?>");
	   //document.getElementById('saveDiv').style.display='none';
	   document.getElementById('saveDiv1').style.display='none';	
	   document.getElementById('showTitle').style.display='none';	 	
	   document.getElementById('showData').style.display='none';	 
	   document.getElementById('results').innerHTML=" ";
	   document.listForm.labelId.focus();
	   return false;
   }
   else{
	  // document.getElementById('saveDiv').style.display='';
	   document.getElementById('saveDiv1').style.display='';
	   document.getElementById('showTitle').style.display='';	 	
	   document.getElementById('showData').style.display='';	 
       sendReq(listURL,divResultName,'listForm',''); 
   }      
		 
}


function clearText(){

  //  document.getElementById('saveDiv').style.display='none';
    document.getElementById('saveDiv1').style.display='none';	 	
	document.getElementById('showTitle').style.display='none';	 	
	document.getElementById('showData').style.display='none';
	document.getElementById('results').innerHTML="";
}

function holdResult(val) {
	if(document.listForm.rollNos.value == '') {
		messageBox("<?php echo ENTER_ROLLNO;?>");
		document.listForm.rollNos.focus();
		return false;
	}
	 url = '<?php echo HTTP_LIB_PATH;?>/Student/initHoldResult.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters:{	rollNos : document.listForm.rollNos.value,
						hold : val
					},
		 onCreate: function() {
			 showWaitDialog(true); 
		 },
		 onSuccess: function(transport){

			 hideWaitDialog(true);
			 if("<?php echo STUDENT_HOLD_RESULT;?>" == trim(transport.responseText)) {  
				 document.getElementById('invalidData').innerHTML='';
				 document.getElementById('invalidData').style.height='0px';
				 document.getElementById('invalidData').style.width='0px';
				 flag = true;
				 alert(trim(transport.responseText));
				 
				 return false;
			 }
			 else if ("<?php echo ROLL_NO_CANNOT_BLANK;?>" == trim(transport.responseText)) {
				document.getElementById('invalidData').innerHTML='';
				document.getElementById('invalidData').style.height='0px';
				document.getElementById('invalidData').style.width='0px';
				alert(trim(transport.responseText));
				return false;
			 }
			 else {
					var j = eval('('+transport.responseText+')');
					var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
					var invalidRollNo = j.split(',');
					var f=0;
					var invalidStr='<tr class="rowheading"><td class="searchhead_text reportBorder" colspan="10" align="left">Invalid Roll Nos.</td></tr><tr '+bg+'>';
					if(invalidRollNo.length > 0 ) {
						for(var i=0; i <invalidRollNo.length;i++){
							if(i%10==0 && i!=0){
								bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
								invalidStr +='</tr><tr '+bg+'>';
								f=0;
							}
							bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
							invalidStr +='<td class="padding_top reportBorder" >'+invalidRollNo[i]+'</td>';
							f++;
						}
						if(i % 10 !=0){
                        var m= i % 10;
                        for(;m<10;m++){
                            invalidStr +='<td class="reportBorder" align="left">&nbsp;</td>';
                        }
							invalidStr +='</tr>';
						}
					document.getElementById('invalidData').innerHTML = '<table style="border-collapse:collapse;" border="0" cellpadding="2" cellspacing="0" width="100%">'+invalidStr+'</table>';
					}
			 }
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function unHoldResult(val) {
	if(document.listForm.rollNos.value == '') {
		messageBox("<?php echo ENTER_ROLLNO;?>");
		document.listForm.rollNos.focus();
		return false;
	}
	 url = '<?php echo HTTP_LIB_PATH;?>/Student/initHoldResult.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters:{	rollNos : document.listForm.rollNos.value,
						hold : val
					},
		 onCreate: function() {
			 showWaitDialog(true); 
		 },
		 onSuccess: function(transport){

			 hideWaitDialog(true);
			 if("<?php echo STUDENT_UNHOLD_RESULT;?>" == trim(transport.responseText)) {  
				 document.getElementById('invalidData').innerHTML='';
				 document.getElementById('invalidData').style.height='0px';
				 document.getElementById('invalidData').style.width='0px';
				 flag = true;
				 alert(trim(transport.responseText));
				 
				 return false;
			 }
			 else if ("<?php echo ROLL_NO_CANNOT_BLANK;?>" == trim(transport.responseText)) {
				document.getElementById('invalidData').innerHTML='';
				document.getElementById('invalidData').style.height='0px';
				document.getElementById('invalidData').style.width='0px';
				alert(trim(transport.responseText));
				return false;
			 }
			 else {
					var j = eval('('+transport.responseText+')');
					var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
					var invalidRollNo = j.split(',');
					var f=0;
					var invalidStr='<tr class="rowheading"><td class="searchhead_text reportBorder" colspan="10" align="left">Invalid Roll Nos.</td></tr><tr '+bg+'>';
					if(invalidRollNo.length > 0 ) {
						for(var i=0; i <invalidRollNo.length;i++){
							if(i%10==0 && i!=0){
								bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
								invalidStr +='</tr><tr '+bg+'>';
								f=0;
							}
							bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
							invalidStr +='<td class="padding_top reportBorder" >'+invalidRollNo[i]+'</td>';
							f++;
						}
						if(i % 10 !=0){
                        var m= i % 10;
                        for(;m<10;m++){
                            invalidStr +='<td class="reportBorder" align="left">&nbsp;</td>';
                        }
							invalidStr +='</tr>';
						}
					document.getElementById('invalidData').innerHTML = '<table style="border-collapse:collapse;" border="0" cellpadding="2" cellspacing="0" width="100%">'+invalidStr+'</table>';
					}
			 }
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

window.onload=function() {
	document.listForm.rollNos.focus();
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/holdUnholdStudentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: $
//
?>