<?php
//-------------------------------------------------------
// Purpose: To generate student installment details
// functionality 
//
// Author : Rajeev Aggarwal
// Created on : (08.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InstallmentDetailOfStudents');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Installment Details of Students</title>
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
// receiptNo,receiptDate
  	  	  	  	  	   
var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false),new Array('receiptNo','Receipt','width="12%"','align="left"',true),new Array('receiptDate','Date','width="8%"','align="left"',true), new Array('fullName','Name','width="12%"','',true) , new Array('rollNo','Roll No','width="7%"','',true), new Array('cycleName','Fee Cycle','width="9%"','',true), new Array('installmentCount','Installment','width="11%"','',true),  new Array('discountedFeePayable','Payable(Rs)','width="10%"','align="right"',true), new Array('totalAmountPaid','Paid(Rs)','width="8%"','align="right"',true), new Array('outstanding','Outstanding(Rs)','width="13%"','align="right"',true), new Array('retStatus','Status','width="6%"','align="center"',false));

  	  	
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxFeesList.php';
searchFormName = 'listForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
divResultName  = 'results';
page=1; //default page
sortField = 'firstName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
        displayWindow(dv,w,h);
        populateValues(id);
}

function validatetStatus() {

	
	fromDate = document.listForm.fromDate.value;
	toDate = document.listForm.toDate.value;
	 
	if(dateCompare(fromDate,toDate)==1){

		 messageBox("<?php echo FI_CORRECT_DATE?>");
		 document.listForm.toDate.select();
		 return false;
	}
	if(document.listForm.fromAmount.value){
	
		reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
		if (!reg.test(document.getElementById('fromAmount').value)){

			messageBox("<?php echo FI_CORRECT_PAID?>");
			document.listForm.fromAmount.focus();
			return false;
		}
	}

	if(document.listForm.toAmount.value){
	
		reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
		if (!reg.test(document.getElementById('toAmount').value)){

			messageBox("<?php echo FI_CORRECT_PAID_TO?>");
			document.listForm.toAmount.focus();
			return false;
		}
	}
	
	if((document.listForm.fromAmount.value!='') && (document.listForm.toAmount.value!='')){
		 
		if (parseFloat(document.getElementById('fromAmount').value)>parseFloat(document.getElementById('toAmount').value)){
			
			messageBox("<?php echo FI_CORRECT_PAID_TO_FROM?>");
			document.listForm.fromAmount.focus();
			return false;
		}
	}
	document.getElementById('saveDiv').style.display='';
	document.getElementById('showTitle').style.display='';
	document.getElementById('showData').style.display='';
	sendReq(listURL,divResultName,'listForm',''); 	
	 //updateStatus();
	
	return false;
}

function printReport() {

	form = document.listForm;
	var degree = document.getElementById('degree');
	var batch = document.getElementById('batch');
	var studyperiod = document.getElementById('studyperiod');
	var feeCycle = document.getElementById('feeCycle');
	var paymentStatus = document.getElementById('paymentStatus');
	var receiptStatus = document.getElementById('receiptStatus');
	var paymentType = document.getElementById('paymentType');

	path='<?php echo UI_HTTP_PATH;?>/installmentReportPrint.php?degree='+form.degree.value+'&degreeName='+degree.options[degree.selectedIndex].text+'&batch='+form.batch.value+'&batchName='+batch.options[batch.selectedIndex].text+'	&studyperiod='+form.studyperiod.value+'&studyperiodName='+studyperiod.options[studyperiod.selectedIndex].text+'&studentName='+form.studentName.value+'&studentRoll='+form.studentRoll.value+'&feeCycle='+form.feeCycle.value+'&feecycleName='+feeCycle.options[feeCycle.selectedIndex].text+'&paymentStatus='+form.paymentStatus.value+'&paymentStatusName='+paymentStatus.options[paymentStatus.selectedIndex].text+'&fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&receiptStatus='+form.receiptStatus.value+'&receiptStatusName='+receiptStatus.options[receiptStatus.selectedIndex].text+'&fromAmount='+form.fromAmount.value+'&toAmount='+form.toAmount.value+'&paymentType='+form.paymentType.value+'&paymentTypeName='+paymentType.options[paymentType.selectedIndex].text+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=900, height=500, top=100,left=50");
}

/* function to print all installment to csv*/
function printInstallmentCSV() {

	form = document.listForm;
	path='<?php echo UI_HTTP_PATH;?>/installmentReportCSV.php?degree='+form.degree.value+'&batch='+form.batch.value+'	&studyperiod='+form.studyperiod.value+'&studentName='+form.studentName.value+'&studentRoll='+form.studentRoll.value+'&feeCycle='+form.feeCycle.value+'&paymentStatus='+form.paymentStatus.value+'&fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&receiptStatus='+form.receiptStatus.value+'&fromAmount='+form.fromAmount.value+'&toAmount='+form.toAmount.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;

	window.location=path;
	//document.getElementById('generateCSV').href=path;
	//document.getElementById('generateCSV1').href=path;
}

window.onload=function(){
 var roll = document.getElementById("studentRoll");
 autoSuggest(roll);
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/installmentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: installmentDetail.php $
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface
//added code for autosuggest functionality
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 09-09-03   Time: 12:40p
//Updated in $/LeapCC/Interface
//fixed 0001421,0001422,0001428,0001430,0001434,0001435
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/08/09    Time: 10:50a
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 1/12/09    Time: 5:30p
//Updated in $/LeapCC/Interface
//Updated with Required field, centralized message, left align
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/23/08   Time: 12:57p
//Updated in $/LeapCC/Interface
//updated as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/08/08    Time: 3:37p
//Created in $/Leap/Source/Interface
//intial checkin
?>