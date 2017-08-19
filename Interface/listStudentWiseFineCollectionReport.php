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
define('MODULE','StudentWiseFineCollectionReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Wise Fine Collection Report </title>
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

var tableHeadArray = new Array(	new Array('srNo','#','width="3%"','',false),
								new Array('rollNo','Roll No.','width="8%"','align=left',true),
								new Array('studentName','Name','width="8%"','align=left',true),
								new Array('className','Fine Class','width="15%"','',true), 
								new Array('totalFineAmount','Total Fine Amount','width="12%"','align=right',true),
								new Array('totalFinePaid','Total Fine Paid','width="12%"','align=right',true),
								new Array('balance','Balance','width="12%"','align=right',true)
								//new Array('viewDetail','Action','width="10%"','align=center',false)
							);

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/Fine/initStudentFineCollectionReport.php';
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

function validateAddForm(frm) {	
	/*if(trim(document.getElementById('degreeId').value)!=""){
		//if(trim(document.getElementById('degreeId').value)==""){
			//messageBox("<?php echo SELECT_DEGREE; ?>");
			//document.getElementById('degreeId').focus();
			//return false;
		//}
		
		if(trim(document.getElementById('branchId').value)==""){
			messageBox("<?php echo SELECT_BRANCH; ?>");
			document.getElementById('branchId').focus();
			return false;
		}
		
		if(trim(document.getElementById('batchId').value)==""){
			messageBox("<?php echo SELECT_BATCH; ?>");
			document.getElementById('batchId').focus();
			return false;
		}
	
		if(trim(document.getElementById('classId').value)==""){
			messageBox("<?php echo SELECT_CLASS; ?>");
			document.getElementById('classId').focus();
			return false;
		}
	}
	
    var fieldsArray = new Array(new Array("startDate","<?php echo SELECT_DATE;?>"),
								new Array("toDate","<?php echo SELECT_TODATE;?>")
								);

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if (!dateDifference(document.FineCollectionForm.startDate.value,document.FineCollectionForm.toDate.value,'-')) {
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
		sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
}


function printReport() {
	var params = generateQueryString('allDetailsForm');
	var qstr=params+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/studentFineCollectionReportPrint.php?'+qstr;
	window.open(path,"FineCollectionReport","status=1,menubar=1,scrollbars=1, width=900");
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


function getShowSearch(val) {
   if((document.getElementById('searchDate').value)==1){
     document.getElementById('searchDt').style.display='';
   }
   else{
   	 document.getElementById('searchDt').style.display='none';
   	 document.getElementById('startDate').value='';
   	 document.getElementById('toDate').value='';
   }   
}

function getBranches() {
	form = document.FineCollectionForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeCollectionReport/getBranches.php';
	new Ajax.Request(url,
	{
		method:'post',
		parameters: {	degreeId: document.getElementById('degreeId').value	
			},
			
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){ 
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.branchId.length = null; 
			if(j== 0 || len == 0) {
				addOption(form.branchId,'', 'Select');
			}
			else{	
				addOption(form.branchId,'', 'Select');
				for(i=0;i<len;i++) {
					addOption(form.branchId, j[i].branchId, j[i].branchCode);
				}
			}
			resetResult('branch');
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function getBatch() { 
	form = document.FineCollectionForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeCollectionReport/getBatches.php';
	new Ajax.Request(url,
	{
		method:'post',
		parameters: {	branchId: document.getElementById('branchId').value,
				degreeId: document.getElementById('degreeId').value
			},
			
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){ 
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.batchId.length = null;
			if(j==0 || len == 0) {
				addOption(form.batchId, '', 'Select');
			}
			else{
				addOption(form.batchId, '', 'Select');
				for(i=0;i<len;i++) {
					addOption(form.batchId, j[i].batchId, j[i].batchName);
				}
			}
			resetResult('batch');
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function getClass() {
	form = document.FineCollectionForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeCollectionReport/getClases.php';
	new Ajax.Request(url,
	{
		method:'post',
		parameters: {	branchId: document.getElementById('branchId').value,
				degreeId: document.getElementById('degreeId').value,
				batchId: document.getElementById('batchId').value
			},
			
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){ 
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.classId.length = null;
			if(j==0 || len == 0) {
				addOption(form.classId, '', 'Select');
				return false;
			}
			else{
				for(i=0;i<len;i++) {
					addOption(form.classId, j[i].classId, j[i].className);
				}
			}
			resetResult('other');
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function resetResult(mode){
	if(mode == 'all'){
		form = document.FineCollectionForm;
		form.batchId.length = null;
		addOption(form.batchId, '', 'Select');
		form.classId.length = null;
		addOption(form.classId, '', 'Select');
		form.branchId.length = null; 
		addOption(form.branchId,'', 'Select');
		document.getElementById('degreeId').selectedIndex=0;
		document.getElementById('degreeId').focus();
	}
	else if(mode == 'branch'){
		form = document.FineCollectionForm;
		form.batchId.length = null;
		addOption(form.batchId, '', 'Select');
		form.classId.length = null;
		addOption(form.classId, '', 'Select');
	}
	else if(mode == 'batch'){
		form = document.FineCollectionForm;
		form.classId.length = null;
		addOption(form.classId, '', 'Select');
	}
	document.getElementById('resultsDiv').innerHTML='';
	//document.getElementById('nameRow').style.display='none';
	//document.getElementById('resultsDiv').style.display='none';
	//document.getElementById('cancelDiv1').style.display='none';
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fine/listStudentWiseCollectionFineContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
