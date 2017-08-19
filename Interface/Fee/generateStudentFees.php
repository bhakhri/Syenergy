<?php

//-------------------------------------------------------
// Purpose: To generate Quota Seat Intake functionality
// Author : Nishu Bindal
// Created on : 6-Feb-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GenerateStudentFees');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Generate Student Fees</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script type="text/javascript" language="javascript">
var resourceAddCnt=0;
var showDelete='';
var valShow=0;
// check browser
var isMozilla = (document.all) ? 0 : 1;

var dtArray=new Array();  


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
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/GenerateFee/getBranches.php';
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
				addOption(form.branchId,'all', 'All');
				for(i=0;i<len;i++) {
					addOption(form.branchId, j[i].branchId, j[i].branchCode);
				}
			}
			getBatch();
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
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/GenerateFee/getBatches.php';
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
				addOption(form.batchId, 'all', 'All');
				for(i=0;i<len;i++) {
					addOption(form.batchId, j[i].batchId, j[i].batchName);
				}
			}
			getClass();
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
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/GenerateFee/getClases.php';
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
    if(trim(document.getElementById('degreeId').value)==""){
      messageBox("<?php echo SELECT_DEGREE; ?>");
      document.getElementById('degreeId').focus();
      return false;
    }
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
    
     if(trim(document.getElementById('feeCycleId').value)==""){
      messageBox("<?php echo SELECT_FEECYCLE; ?>");
      document.getElementById('feeCycleId').focus();
      return false;
    }
     
    addStudentFees();
    return false;
}




function resetValues() {
	form = document.allDetailsForm;	
	document.getElementById('allDetailsForm').reset();
	document.getElementById('classId').selectedIndex=0;  
	document.getElementById('classId').focus();
	document.getElementById('trAttendance').style.display='none';
	document.getElementById('results').style.display='none';
	document.getElementById('results11').style.display='none';
	form.classId.length = null;
	addOption(form.classId, '', 'Select');
	form.branchId.length = null;
	addOption(form.branchId, '', 'Select');
	form.batchId.length = null;
	addOption(form.batchId, '', 'Select');
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
		document.getElementById('feeCycleId').selectedIndex=0;
		document.getElementById('degreeId').focus();
	}
		
	document.getElementById('results').innerHTML='';
	document.getElementById('results').style.display='none';
	document.getElementById('cancelDiv').style.display='none';
	document.getElementById('cancelDiv1').style.display='none';
}

function hideValue() {
    document.getElementById("totAmount").innerHTML = "<?php echo NOT_APPLICABLE_STRING; ?>"; 
    document.getElementById('trAttendance').style.display='none';
    document.getElementById('results').style.display='none';
    document.getElementById('results11').style.display='none';
    cleanUpTable();   
    
}

function addStudentFees() {   
   var url = '<?php echo HTTP_LIB_PATH;?>/Fee/GenerateFee/generateStudentFees.php';
   document.getElementById('results').style.display='';
   document.getElementById('cancelDiv').style.display='';
   document.getElementById('cancelDiv1').style.display='';
   params = generateQueryString('allDetailsForm');
   new Ajax.Request(url,
   {
     method:'post',
     parameters: params ,
     onCreate: function () {
        showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true);    
        if((trim("<?php echo FEE_HEAD_NOT_DEFINE;?>") == trim(transport.responseText) || trim("<?php echo 'Students Not Found';?>") == trim(transport.responseText)) || ("Class Not Found" == trim(transport.responseText))) {
            messageBox(trim(transport.responseText));  
            return false;
        }
        else if("Fee is Already Generated For This Class!!!" == trim(transport.responseText)){
		if(false==confirm("Fee is Already Generated For This Class.\n Do you want to Delete this record?")) {
			resetResult('other');
			return false;
		}
		else { 
			deleteClassFee();
		}
        }
        else {
		var j= trim(transport.responseText).evalJSON(); 
		var feeHeadArray = new Array(new Array('srNo','#','width="3%"',''),
			new Array('studentName','Student Name','width="18%"',''), 
			new Array('regNo','Reg No.','width="20%"',''),
			new Array('academicFee','Academic Fee','width="14%"',' align="right"'),
			new Array('concession','Concession','width="10%"',' align="right"'),
			new Array('hostelSecurity','Hostel Security','width="14%"',' align="right"'),
			new Array('hostelFee','Hostel Fee','width="10%"',' align="right"'),
			new Array('transportFee','Transport Fee','width="14%"',' align="right"') 
			);
		printResultsNoSorting('results', j.feeInfo, feeHeadArray);
       		
        }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

function deleteClassFee(){
   var url = '<?php echo HTTP_LIB_PATH;?>/Fee/GenerateFee/ajaxInitDelete.php';
   params = generateQueryString('allDetailsForm');
   new Ajax.Request(url,
   {
     method:'post',
     parameters: params ,
     onCreate: function () {
        showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true);    
        if(trim("<?php echo DELETE;?>") == trim(transport.responseText)) {
            messageBox(trim(transport.responseText));
            resetResult();  
            return false;
        }
        else{
        	resetResult('other');
        	messageBox(trim(transport.responseText));
        	 return false;
        }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

//populate list
window.onload=function(){
	 document.getElementById('degreeId').focus();
}


</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fee/GenerateFee/generateStudentFeeContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
                
