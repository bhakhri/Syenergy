<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in Fine Collection Report
//
//
// Author :Jaineesh
// Created on : 06.07.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentDetailFineCollectionReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Detail Fine Collection Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?> 
<script language="javascript">

var tableHeadArray = new Array(	new Array('srNo','#','width="2%"','',false),
								new Array('rollNo','Roll No.','width="10%"','align=left',true),
								new Array('studentName','Name','width="10%"','align=left',true),
								new Array('fineCategoryName','Fine Category','width="10%"','align=left',true),
								new Array('amount','Amount','width="8%"','align=right',true),
								new Array('fineDate','Fine Date','width="10%"','align=center',true),
								new Array('receiptDate','Fine Collect Date','width="15%"','align=center',true),
								new Array('employeeName','Fined By','width="15%"','align=left',true)
							);

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/Fine/initStudentDetailFineCollectionReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'allDetailsForm'; // name of the form which will be used for search
//addFormName    = 'AddState';   
//editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'ASC';
query = '';

function validateAddForm(frm) {
   /*  var fieldsArray = new Array(new Array("startDate","<?php echo SELECT_DATE;?>"),
								new Array("toDate","<?php echo SELECT_TODATE;?>")
								);

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
		else if (!dateDifference(document.FineCollectionForm.startDate.value,document.FineCollectionForm.toDate.value,'-')) {
			messageBox("<?php echo DATE_VALIDATION ?>");
			document.getElementById("toDate").focus();
			return false;
		}
    }*/

	if(document.getElementById('fromDate').value=='' && document.getElementById('toDate').value!='') {
       messageBox("Select From Date");
       //eval("frm.fromDate.focus();");
       return false;
    }
    
    
    if(document.getElementById('fromDate').value!='' && document.getElementById('toDate').value=='') {
       messageBox("Select To Date");
       //eval("frm.fromDate.focus();");
       return false;
    }
    
    if(document.getElementById('fromDate').value!='' && document.getElementById('toDate').value!='') {
	  if(!dateDifference(document.getElementById('fromDate').value,document.getElementById('toDate').value,'-') ) {
	     messageBox("<?php echo DATE_VALIDATION;?>");
		 //eval("frm.fromDate.focus();");
		 return false;
	  }
    }

	//openStudentLists(frm.name,'rollNo','Asc');    
		document.getElementById("nameRow").style.display='';
		document.getElementById("nameRow2").style.display='';
		document.getElementById("resultRow").style.display='';
		query = generateQueryString('allDetailsForm');
		sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
		
}


function printReport() {
	var params = generateQueryString('allDetailsForm');
	var qstr=params+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/studentDetailFineCollectionReportPrint.php?'+qstr;
    window.open(path,"StudentDetailFineReport","status=1,menubar=1,scrollbars=1, width=900");
}

function sendKeys(mode,eleName, e) {
 var ev = e||window.event;
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13') {
 if(mode==1){    
  var form = document.FineCollectionForm;
 }
  eval('form.'+eleName+'.focus()');
  return false;
 }
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}
window.onload=function(){
 var roll = document.getElementById("rollNo");
 roll.focus();
 autoSuggest(roll);
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fine/listStudentDetailCollectionFineContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
