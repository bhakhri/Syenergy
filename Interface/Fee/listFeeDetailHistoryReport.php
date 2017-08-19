<?php
//-------------------------------------------------------
// Purpose: To List Fee Detail payemnt
//Author : Harpreet
// Created on : 16-April-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeDetailHistoryReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Fee Detail History Report</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?> 
<script type="text/javascript" language="javascript">

 //This function Validates Form 
 

var listURL='<?php echo HTTP_LIB_PATH;?>/Fee/FeeDetailHistory/ajaxInitPendingList.php';
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
                     new Array('feeTypeOf','Pay Fee Of','width="7%"','align="right"',false),                     
                    new Array('totalFees','Total Fees','width="7%"','align="right"',false), 
                    new Array('ledgerDebit','Debit','width="5%"','align="right"',false),
                    new Array('ledgerCredit','Credit','width="5%"','align="right"',false),                    
                    new Array('concession','Concession','width="5%"','align="right"',false),
                     new Array('fine','Fine','width="5%"','align="right"',false),                    
                    new Array('paidAmount','Paid Fee','width="7%"','align="right"',false),
                    new Array('unPaidFees','Balance','width="7%"','align="right"',false));
	 

	

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
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeDetailHistory/getBranches.php';
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
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeDetailHistory/getBatches.php';
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
//Author : Harpreet
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getClass() {
	form = document.allDetailsForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeDetailHistory/getClases.php';
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

    url='<?php echo HTTP_LIB_PATH;?>/Fee/FeeDetailHistory/ajaxInitPendingList.php';   
    
    document.getElementById('resultsDiv').style.display='';
	document.getElementById('nameRow').style.display='';
	document.getElementById('cancelDiv1').style.display='';
	//params = generateQueryString('allDetailsForm');
    
    classId = document.allDetailsForm.classId.value;
    formx = document.allDetailsForm.classId;
    var classLen= document.getElementById('classId').options.length;
    var t=document.getElementById('classId');
    if(trim(classId)=='') {
       for(k=0;k<classLen;k++) {
         if(t.options[k].value!='') {
           if(classId!='') {
             classId +=",";  
           }  
           classId += trim(t.options[k].value);
         }
       }
    }
    
    isPaid=0;  // Paid Fee      
    if(document.getElementById('paid1').checked==false) {
      isPaid =1;   // Un Paid Fee
    }
    
    isFeeFor='0';
    if(document.getElementById('fee1').checked==true) {      // All(Hostel+Transport+Academic)
       isFeeFor='0'; 
    }
    else if(document.getElementById('fee2').checked==true) { // Academic
       isFeeFor='1'; 
    } 
    else if(document.getElementById('fee3').checked==true) {  // Transport
       isFeeFor='2'; 
    }
    else if(document.getElementById('fee4').checked==true) { // Hostel
       isFeeFor='3'; 
    }
     
    queryString = "&classId="+classId+"&studentName="+trim(document.getElementById('studentName').value);
    queryString += "&rollNo="+trim(document.getElementById('rollNo').value)+"&isFeeFor="+isFeeFor;
    queryString += "&fatherName="+trim(document.getElementById('fatherName').value)+"&isPaid="+isPaid;
    
	new Ajax.Request(url,
    {
          method:'post',
          asynchronous:false,
          parameters: queryString, 
          onCreate: function() {
              showWaitDialog(true);
          },
          onSuccess: function(transport){
             hideWaitDialog(true);
             if(trim(transport.responseText)==false) {
                messageBox("<?php echo INCORRECT_FORMAT?>");  
             }
             else {
               document.getElementById('resultsDiv').innerHTML=trim(transport.responseText);
               document.getElementById("nameRow").style.display='';
             }
     },
     onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
     });
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
    	path='<?php echo UI_HTTP_PATH;?>/Fee/listFeeDetailReportPrint.php?'+qstr;
    	window.open(path,"FeeCollectionReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to export to excel */
function printReportCSV() {
	var params = generateQueryString('allDetailsForm');
	var qstr=params+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	var path='<?php echo UI_HTTP_PATH;?>/Fee/listFeeDetailReportCSV.php?'+qstr;
	window.location = path;
}

//populate list
window.onload=function(){
	 //document.getElementById('degreeId').focus();
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
function getPaidAtDetails() {
	var paidValue =0;
	if(document.allDetailsForm.paid2.checked){
	  paidValue = 1;
	}
     else {
	  paidValue = 0;
	}
  
}

function getFeeDetails() {
	var feeValue =0;
	if(document.allDetailsForm.fee2.checked){
	  feeValue = 1;
	}
    else if(document.allDetailsForm.fee3.checked){
	  feeValue = 2;
	}else if(document.allDetailsForm.fee4.checked){
	  feeValue = 3;
	}
    else {
	  feeValue = 0;
	}
  
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fee/FeeDetailHistory/listFeeDetailHistoryReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
                
