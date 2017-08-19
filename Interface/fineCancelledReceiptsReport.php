<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineCancelledReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fine Cancelled Receipts Report </title>
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
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
  	  	  	  	  	   
var tableHeadArray = new Array(
                       new Array('srNo','#','width="2%"','',false),
                       new Array('receiptDate','Receipt Date','width="12%"','align="center"',true),
                       new Array('fineReceiptNo','Receipt No.','width="10%"','align="left"',true), 
                       new Array('fullName','Name','width="12%"','',true),
                       new Array('rollNo','Roll No.','width="10%"','',true),
                       new Array('className','Fine Class','width="15%"','',true), 
                       new Array('paidAt','Paid At','width="8%"','align="center"',false),  
                       new Array('receiveCash','Cash','width="9%"','align="right"',true), 
                       new Array('receiveDD','DD','width="9%"','align="right"',true), 
                       new Array('totalAmount','Total Receipt','width="10%"','align="right"',true),
		       new Array('reasonDelete','Cancellation reason','width="10%"','align="right"',true),   
                       new Array('employeeCodeName','Deleted By','width="8%"','align="center"',false) 
                      );
      

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FineCancelledReport/ajaxFineCancelledList.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
divResultName  = 'results';
page=1; //default page
sortField = 'receiptDate';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h){
	displayWindow(dv,w,h);
    populateValues(id);
}

// function is used to get Reason for Delete
function getReason(){
	var delReason = '';
	delReason = prompt("Reason for Delete","");
	if(isEmpty(trim(delReason))){
	  alert("Please Enter Reason For Delete.");
   	  getReason();
   	}
   	else{
   	  return delReason;
  	}
}

function deleteReceipt(receiptId) {
 
	 var reason ='';
     if(false===confirm("Do you want to delete this record?")) {
         return false;
     }
     else{
    	reason = getReason();
     url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxReceiptDelete.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {receiptId: receiptId,
					  delReason:reason},
         onCreate: function(){
			 showWaitDialog(true);
		  },
         onSuccess: function(transport){
                 hideWaitDialog(true);
              //   messageBox(trim(transport.responseText));
                 if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                     sendReq(listURL,divResultName,'allDetailsForm','page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false);     
                     return false;
                 }
                  else {
                     messageBox(trim(transport.responseText));
                 }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
     }    
}

function validateAddForm() {

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
		
	document.getElementById('saveDiv').style.display='';
	document.getElementById('showTitle').style.display='';
	document.getElementById('showData').style.display='';	
	sendReq(listURL,divResultName,'allDetailsForm','page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
	return false;
}

function printReport() {
    var params = generateQueryString('allDetailsForm');
    var qstr=params+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField; 
    path='<?php echo UI_HTTP_PATH;?>/studentFineCancelledReportPrint.php?'+qstr;  
    window.open(path,"FineCancellationReport","status=1,menubar=1,scrollbars=1, width=900");


}

/* function to print all payment history to csv*/
function printPaymentHistoryCSV() {
    var params = generateQueryString('allDetailsForm');
    var qstr=params+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/studentFineCancelledReportCSV.php?'+qstr;
    window.location = path; 

}
 
function printReceipt(id){
	 
	path='<?php echo UI_HTTP_PATH;?>/studentFineReceipt.php?receiptNo='+id;
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=700, height=400, top=100,left=50");
}

function printDetailReceipt(id){
	path='<?php echo UI_HTTP_PATH;?>/studentFineReceipt.php?receiptNo='+id;
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=700, height=600, top=100,left=150");
}

window.onload=function(){	
 document.allDetailsForm.reset();     
 var roll = document.getElementById("studentRoll");
 autoSuggest(roll);
}

function gotoURL(rollNo){
    //window.location='fineReceipt.php?rollNo='+escape(rollNo);
    var path='fineReceipt.php?rollNo='+escape(rollNo);
    window.open(path,"FineReceipt","status=1,menubar=1,scrollbars=1, width=700, height=400, top=100,left=50");
}

var serverDate="<?php echo date('Y-m-d'); ?>";
var receiptFlag=1;


function sendKeys(eleName, e) {
 var ev = e||window.event;
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13') {
 var form = document.allDetailsForm;
  eval('form.'+eleName+'.focus()');
  return false;
 }
}

function getBatch() { 
	form = document.allDetailsForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/PaymentHistory/getBatches.php';
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
				addOption(form.batchId, '', 'All');
			}
			else{
				addOption(form.batchId, '', 'All');
				for(i=0;i<len;i++) {
					addOption(form.batchId, j[i].batchId, j[i].batchName);
				}
			}
			
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function getBranches() {
	form = document.allDetailsForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/PaymentHistory/getBranches.php';
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
				addOption(form.branchId,'', 'All');
			}
			else{	
				addOption(form.branchId,'', 'All');
				for(i=0;i<len;i++) {
					addOption(form.branchId, j[i].branchId, j[i].branchCode);
				}
			}
			
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function getClass() {
	form = document.allDetailsForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/PaymentHistory/getClasses.php';
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
				addOption(form.classId, '', 'All');
				return false;
			}
			else{
				addOption(form.classId, '', 'All');
				for(i=0;i<len;i++) {
					addOption(form.classId, j[i].classId, j[i].className);
				}
			}
			
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function getShowSearch(val) {
   if((document.getElementById('searchDate').value)==1){
     document.getElementById('searchDt').style.display='';
   }
   else{
   	 document.getElementById('searchDt').style.display='none';
   	 document.getElementById('fromDate').value='';
   	 document.getElementById('toDate').value='';
   }   
}
</script>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/FineCancelledReport/listFineCancelledReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
