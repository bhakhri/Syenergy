<?php
//-------------------------------------------------------
// Purpose: To List Fee Collection 
// Created on : 16-April-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PendingFeeReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Pending Fee Report</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?> 
<script type="text/javascript" language="javascript">

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/Fee/PendingFeeReport/ajaxInitList.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'allDetailsForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'ASC';


var tableHeadArray= new Array(new Array('srNo','#','width="2%"','',false), 
                    new Array('studentName','Student Name','width="6%"','',true), 
                    new Array('rollNo','Roll No.','width="5%"','',true), 
                    new Array('className','Class','width="12%"','',true),                   
                    new Array('academicTotalFees','Academic','width="7%"','align="right"',false),
                    new Array('academicLedgerDebit','Academic Debit','width="5%"','align="right"',false),
                    new Array('academicLedgerCredit','Academic Credit','width="5%"','align="right"',false),
                    new Array('hostelTotalFees','Hostel','width="7%"','align="right"',false),
                    new Array('hostelLedgerDebit','Hostel Debit','width="5%"','align="right"',false),
                    new Array('hostelLedgerCredit','Hostel  Credit','width="5%"','align="right"',false),
                    new Array('transportTotalFees','Transport','width="7%"','align="right"',false),
                    new Array('transportLedgerDebit','Transport Debit','width="5%"','align="right"',false),
                    new Array('transportLedgerCredit','Transport Credit','width="5%"','align="right"',false),
                    new Array('concession','Concession','width="5%"','align="right"',false),
                    new Array('totalFees','Total Fees','width="7%"','align="right"',false),
                    new Array('paidAmount','Paid','width="7%"','align="right"',false),
                    new Array('unPaidFees','Unpaid','width="7%"','align="right"',false));

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET BRANCHES 
//
//Author : Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getBranches() {
	form = document.allDetailsForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/PendingFeeReport/getBranches.php';
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
//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET BRANCHES 
//
//Author : Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getBatch() { 
	form = document.allDetailsForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/PendingFeeReport/getBatches.php';
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

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET Classes 
//
//Author : Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getClass() {
	form = document.allDetailsForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/PendingFeeReport/getClases.php';
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


function validateAddForm(frm) { 

    document.getElementById('resultsDiv').style.display='';
	document.getElementById('nameRow').style.display='';
	document.getElementById('cancelDiv1').style.display='';
	params = generateQueryString('allDetailsForm');
	
	sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
	//showFeeCollection();
	return false;
}

function resetResult(mode){
	if(mode == 'all'){
		form = document.allDetailsForm;
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
		form = document.allDetailsForm;
		form.batchId.length = null;
		addOption(form.batchId, '', 'Select');
		form.classId.length = null;
		addOption(form.classId, '', 'Select');
	}
	else if(mode == 'batch'){
		form = document.allDetailsForm;
		form.classId.length = null;
		addOption(form.classId, '', 'Select');
	}
	document.getElementById('resultsDiv').innerHTML='';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('resultsDiv').style.display='none';
	document.getElementById('cancelDiv1').style.display='none';
}

/* function to print city report*/
function printReport() {
	var params = generateQueryString('allDetailsForm');
	var qstr=params+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;  
    	path='<?php echo UI_HTTP_PATH;?>/Fee/listPendingFeeReportPrint.php?'+qstr;
    	window.open(path,"FeeCollectionReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to export to excel */
function printReportCSV() {
	var params = generateQueryString('allDetailsForm');
	var qstr=params+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	var path='<?php echo UI_HTTP_PATH;?>/Fee/listPendingFeeReportCSV.php?'+qstr;
	window.location = path;
}

//populate list
window.onload=function(){
	 document.getElementById('degreeId').focus();
}


function getFee(id) {
    
     if(id=='') {
       return false;  
     }
     
     url = '<?php echo HTTP_LIB_PATH;?>/Fee/CollectFees/generateStudentAcademicFee.php';  
     new Ajax.Request(url,
     {
         method:'post',
         parameters: {id: id},
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
            hideWaitDialog(true);
            messageBox(trim(transport.responseText));
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       }); 
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fee/PendingFeeReport/listPendingFeeReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
                
