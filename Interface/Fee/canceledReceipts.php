<?php
//-------------------------------------------------------
// Purpose: To generate student payment history listfunctionality 
// Author : Nishu Bindal
// Created on : (08-may-2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
global $sessionHandler;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CanceledReceipts');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fee Payment History</title>
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


<script type="text/javascript">
window.onload = function () {
    var roll = document.getElementById("studentRoll");
    autoSuggest(roll);
}
</script>
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
  	  	  	  	
var tableHeadArray =  new Array(new Array('srNos','#','width="2%"','',false),
                                new Array('receiptDate','Receipt Date','width="12%"','align="center"',true),  
                                new Array('receiptNo','Receipt No.','width="10%"','align="left"',true),
                                new Array('studentName','Name','width="12%"','',true) , 
                                new Array('rollNo','Roll No.','width="10%"','',true), 
                                new Array('className',           'Fee Class','width="15%"','',true),  
                                new Array('cycleName',           'Fee Cycle','width="9%"','',true),  
                                new Array('installmentNo',       'Installment','width="11%"','',true),
                                new Array('receiveCash',          'Cash(Rs.)','width="10%"','align="right"',true), 
                                new Array('receiveDD',          'DD(Rs.)','width="10%"','align="right"',true), 
                                new Array('amount','Total Receipt','width="10%"','align="right"',true), 
                                new Array('reason',          'Cancellation Reason','width="10%"','align="right"',true),           
                                new Array('employeeCodeName','Deleted By','width="8%"','align="center"',false) 

);   
                               //new Array('printAction','Action','width="10%"','align="center" nowrap',false));                
                  	                 
                  	   

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Fee/cancelReceipt/ajaxFeeList.php';
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

function populateValues(id) {
   return false;  
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET BRANCHES 
//
//Author : Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getBranches() {
	form = document.allDetailsForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/cancelReceipt/getBranches.php';
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
//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET BRANCHES
//Author : Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getBatch() { 
	form = document.allDetailsForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/cancelReceipt/getBatches.php';
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

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET Classes
//Author : Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

function getClass() {
	form = document.allDetailsForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/cancelReceipt/getClasses.php';
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
function validateAddForm(frm) {
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
    
    sendReq(listURL,divResultName,'allDetailsForm','&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false);     
    //document.getElementById('saveDiv').style.display='';
    document.getElementById('showTitle').style.display='';
    document.getElementById('showData').style.display='';
     document.getElementById('saveDiv').style.display='';
    document.getElementById('nameRow2').style.display='';
    
	return false;
}

function printReport() {
	
	var params = generateQueryString('allDetailsForm');
	var qstr=params+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/Fee/cancelledReceiptsPrint.php?'+qstr;

	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=900, height=500, top=100,left=50");
}

//function to print all payment history to csv
function printCSV() {

	var params = generateQueryString('allDetailsForm');
	var qstr=params+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/Fee/cancelledReceiptsCSV.php?'+qstr; 

	window.location=path;
}


function printDetailReceipt(receiptId,receiptNo){
	path='<?php echo UI_HTTP_PATH;?>/Fee/paymentReceiptDetailPrint.php?receiptId='+receiptId+'&receiptNo='+receiptNo;
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=750, height=600, top=100,left=150");
}


function showListForm(frm, act) {
    
	var selected=0;
	var selected1=0;
	formx = document.allDetailsForm;
	var insertValue ='';
	for(var i=3;i<formx.length;i++){

		if(formx.elements[i].type=="checkbox"){

			if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]")){

				selected++;
				querySeprator = '';
				if(insertValue!=''){

					querySeprator = ",";
				}
				insertValue += querySeprator+formx.elements[i].value
			}
		
		}
	}
	
	if(selected==0){
		alert("<?php echo SELECT_ATLEAST_ONE_STUDENT?>");
		return false;
	}

    deleteReceipt(insertValue);
	return false;
} 
// function is used to get Reason for Delete
function getReason(){
	var delReason = '';
	delReason = prompt("Reason for Delete","");
	if(isEmpty(delReason)){
		alert("Please Enter Reason For Delete.");
     		getReason();
     	}
     	else{
     		return delReason;
     	}
}

function deleteReceipt(receiptId,receiptNo) {
     var reason ='';
     if(false===confirm("Do you want to delete this record?")) {
         return false;
     }
     else{
     	reason = getReason();
     url = '<?php echo HTTP_LIB_PATH;?>/Fee/cancelReceipt/ajaxInitDelete.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {receiptId: receiptId,receiptNo:receiptNo,delReason:reason},
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

</script>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fee/cancelReceipts/canceledReceiptsContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>

