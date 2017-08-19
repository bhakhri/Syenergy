<?php
//-------------------------------------------------------
// Purpose: To List Fee Collection 
// Author : Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ConsolidatedFeeDetailsReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Consolidated Fee Details Report</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?> 
<script type="text/javascript" language="javascript">

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/Fee/ConsolidatedFeeDetails/ajaxInitList.php';
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
sortField = 'instituteId';
sortOrderBy    = 'ASC';

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), 
                   new Array('className','Class Name','width="10%"','',true), 
                   new Array('totalStudents','Total Students','width="10%"','align="right"',true), 
                   new Array('totalAmount','Total Amt.','width="10%"','align="right"',true),
                   new Array('paidStudents','Paid Students','width="10%"','align="right"',true), 
                   new Array('paidFees',' Fully Paid Fees','width="10%"','align="right"',true),
                   new Array('partialPaidStduents','Partial Paid <br>Students','width="9%"','align="right"',true),
                   new Array('partialPaidFees','Partail Paid ','width="10%"','align="right"',true),
                   new Array('unPaidStudents','Not Paid <p>Students','width="9%"','align="left"',true),
                   new Array('unPaidFees','Unpaid Fees','width="10%"','align="right"',true));


function validateAddForm(frm){ 
	
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
    
	if(trim(document.getElementById('feeOf').value)==""){
		messageBox("Select Fee Of");
		document.getElementById('feeOf').focus();
		return false;
	}
    
	document.getElementById('resultsDiv').style.display='';
	document.getElementById('nameRow').style.display='';
	document.getElementById('cancelDiv1').style.display='';
	pars = generateQueryString('allDetailsForm');
	
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/ConsolidatedFeeDetails/ajaxInitList.php';
	new Ajax.Request(url,
	{
		 method:'post',
         parameters: pars,
         asynchronous:false,
		 onCreate: function(){
		   showWaitDialog(true);
		 },
		onSuccess: function(transport){ 
		  hideWaitDialog(true);
		  var j= trim(transport.responseText).evalJSON();
		  document.getElementById('resultsDiv').innerHTML = j.info;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
	return false;
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET STUDYPERIOD
//Author : Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function getStudyPeriod(){
	form = document.allDetailsForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/ConsolidatedFeeDetails/getStudyPeriod.php';
	new Ajax.Request(url,
	{
		method:'post',
		parameters: {	instituteId: document.getElementById('instituteId').value	
			},
			
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){ 
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.studyPeriodId.length = null;
			
			if(j== 0 || len == 0) {
				addOption(form.studyPeriodId,'', 'Select');
			}
			else{	
				addOption(form.studyPeriodId,'', 'Select');
				for(i=0;i<len;i++) {
					addOption(form.studyPeriodId, j[i].studyPeriodId, j[i].periodName);
				}
			}
			
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function resetResult(mode){
	if(mode == 'institute'){
		document.getElementById('feeOf').selectedIndex=0;
		document.getElementById('studyPeriodId').selectedIndex=0;
	}
	else if(mode == 'studyPeriod'){
		document.getElementById('feeOf').selectedIndex=0;
	}
	
	document.getElementById('resultsDiv').innerHTML='';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('resultsDiv').style.display='none';
	document.getElementById('cancelDiv1').style.display='none';
}

//populate list
window.onload=function(){
	 document.getElementById('instituteId').focus();
	 //getStudyPeriod();
}

/* function to print city report*/
function printReport(){

	var params = generateQueryString('allDetailsForm');
	var qstr=params+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;

    
    path='<?php echo UI_HTTP_PATH;?>/Fee/consolidatedFeeDetailsReportPrint.php?'+qstr;
    
    window.open(path,"ConsolidatedFeeDetailsReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to export to excel */
function printReportCSV() {
	var params = generateQueryString('allDetailsForm');
	var qstr=params+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	var path='<?php echo UI_HTTP_PATH;?>/Fee/consolidatedFeeDetailsReportCSV.php?'+qstr;
	window.location = path;
}

</script>
</head>
<body>
    <?php 
    	require_once(TEMPLATES_PATH . "/header.php");
    	require_once(TEMPLATES_PATH . "/Fee/ConsolidatedFeeDetailsReport/listConsolidatedFeeDetailsContents.php");
    	require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
                
