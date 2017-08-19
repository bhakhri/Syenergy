<?php
//-------------------------------------------------------
// Purpose: To generate student fee status list
// functionality 
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeReceiptStatus');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fee Receipt Status</title>
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
// receiptNo,receiptDate
  	  	  	  	  	   
var tableHeadArray = new Array(new Array('srNos',               '#','width="2%"','',false),
                               new Array('receiptDate',         'Receipt<br>Date','width="9%"','align="center"',true),  
                               new Array('receiptNo',           'Receipt','width="12%"','align="left"',true),
                               new Array('studentName',         'Name','width="12%"','',true) , 
                               new Array('rollNo',              'Roll No.','width="12%"','',true), 
                               new Array('className',           'Fee Class','width="20%"','',true),  
                               new Array('cycleName',           'Fee Cycle','width="9%"','',true),  
                               new Array('installmentCount',    'Installment','width="15%"','',true), 
                               new Array('discountedFeePayable','Payable<br>(Rs.)','width="10%"','align="right"',false), 
                               new Array('amountPaid',          'Paid<br>(Rs.)','width="8%"','align="right"',false), 
                               new Array('previousDues',        'Outstanding<br>(Rs.)','width="13%"','align="right"',false), 
                               new Array('instStatus',          'Instrument','width="12%"','align="center"',false), 
                               new Array('retStatus',           'Status','width="12%"','align="center"',false),
                               new Array('deleteAction',        'Action','width="5%"','align="center"',false));   

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxReceiptStatus.php';
searchFormName = 'listForm'; // name of the form which will be used for search
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
function validateStatus() {

	fromDate = document.listForm.fromDate.value;
	toDate = document.listForm.toDate.value;
	 
	if(dateCompare(fromDate,toDate)==1){

		 messageBox("<?php echo FR_CORRECT_DATE?>");
		 document.listForm.toDate.select();
		 return false;
	}
	
    /*
    if(document.listForm.fromAmount.value){
		reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
		if (!reg.test(document.getElementById('fromAmount').value)){
    		messageBox("<?php echo FR_CORRECT_PAID?>");
			document.listForm.fromAmount.focus();
			return false;
		}
	}

	if(document.listForm.toAmount.value){
		reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
		if (!reg.test(document.getElementById('toAmount').value)){
			messageBox("<?php echo FR_CORRECT_PAID_TO?>");
			document.listForm.toAmount.focus();
			return false;
		}
	}
	
	if((document.listForm.fromAmount.value!='') && (document.listForm.toAmount.value!='')){
		if (parseFloat(document.getElementById('fromAmount').value)>parseFloat(document.getElementById('toAmount').value)){
			messageBox("<?php echo FR_CORRECT_PAID_TO_FROM?>");
			document.listForm.fromAmount.focus();
			return false;
		}
	}
    */
    
	sendReq(listURL,divResultName,'listForm','page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false); 	
    document.getElementById('saveDiv').style.display='';
    document.getElementById('showTitle').style.display='';
    document.getElementById('showData').style.display='';
    document.getElementById('nameRow2').style.display='';
	return false;
}

function validatetStatus() {
 
	 updateStatus();
	 return false;
}


function deleteReceipt(id) {  
     if(false===confirm("Do you want to delete this record?")) {
         return false;
     }
     else {   

     url = '<?php echo HTTP_LIB_PATH;?>/Student/paymentDeleteReceipt.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {receiptId: id},
         onSuccess: function(transport){
           if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
              showWaitDialog(true);
           }
            else {
                 hideWaitDialog(true);
              //   messageBox(trim(transport.responseText));
                 if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                     sendReq(listURL,divResultName,'listForm','page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false);     
                     return false;
                 }
                  else {
                     messageBox(trim(transport.responseText));
                 }
           }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
     }    
}

function updateStatus() {
   
   var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxUpdateStatus.php';
   
   var receiptStatus ='';
   var instrumentStatus ='';
   var id=0;
   var formx=document.listForm;
   var objSelect=formx.getElementsByTagName('INPUT');  
   var totalSelect=objSelect.length;
   var recieptId = "";
   for(var j=0;j<totalSelect;j++) {
      if(objSelect[j].name=='feeRecId') { 
         id="rStatus"+objSelect[j].value; 
         receiptStatus  = eval("document.getElementById('"+id+"').value");
         id="iStatus"+objSelect[j].value; 
         instrumentStatus  = eval("document.getElementById('"+id+"').value"); 
         if(recieptId=='') {
           recieptId = objSelect[j].value+"~"+receiptStatus+"~"+instrumentStatus;  
         }
         else {
           recieptId = recieptId+","+objSelect[j].value+"~"+receiptStatus+"~"+instrumentStatus;
         }
      }
   }
   
   new Ajax.Request(url,
   {
	 method:'post',
	 parameters:{ recieptId: recieptId },
     asynchronous:false,
	 onSuccess: function(transport){
	   if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
		  showWaitDialog(true);
	   }
	   else {
			 
			hideWaitDialog(true);
			 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {  
				 
				alert(trim(transport.responseText));
			 } 
			 else {
				messageBox(trim(transport.responseText)); 
				 
				document.getElementById('listForm').reset(); 
			 }
	   }
	 },
	 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
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
    
    var str = 'degree='+form.degree.value+'&degreeName='+degree.options[degree.selectedIndex].text+'&batch='+form.batch.value;
    str = str + '&batchName='+batch.options[batch.selectedIndex].text+'&studyperiod='+form.studyperiod.value+'&studyperiodName='+studyperiod.options[studyperiod.selectedIndex].text;
    str = str + '&studentName='+form.studentName.value+'&studentRoll='+form.studentRoll.value+'&feeCycle='+form.feeCycle.value;
    str = str + '&receiptNo='+form.receiptNo.value;
    str = str + '&feecycleName='+feeCycle.options[feeCycle.selectedIndex].text+'&paymentStatus='+form.paymentStatus.value+'&paymentStatusName='+paymentStatus.options[paymentStatus.selectedIndex].text;
    str = str + '&fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&receiptStatus='+form.receiptStatus.value+'&receiptStatusName='+receiptStatus.options[receiptStatus.selectedIndex].text;
    //str = str + '&fromAmount='+form.fromAmount.value+'&toAmount='+form.toAmount.value;
    str = str + '&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    path='<?php echo UI_HTTP_PATH;?>/feeStatusReportPrint.php?'+str;
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=900, height=500, top=100,left=50");
}
 
/* function to output all fee receipt to csv*/
function printFeeStatusCSV() {

	form = document.listForm;
    var degree = document.getElementById('degree');
    var batch = document.getElementById('batch');
    var studyperiod = document.getElementById('studyperiod');
    var feeCycle = document.getElementById('feeCycle');
    var paymentStatus = document.getElementById('paymentStatus');
    var receiptStatus = document.getElementById('receiptStatus');
    var paymentType = document.getElementById('paymentType');
    
    var str = 'degree='+form.degree.value+'&degreeName='+degree.options[degree.selectedIndex].text+'&batch='+form.batch.value;
    str = str + '&batchName='+batch.options[batch.selectedIndex].text+'&studyperiod='+form.studyperiod.value+'&studyperiodName='+studyperiod.options[studyperiod.selectedIndex].text;
    str = str + '&studentName='+form.studentName.value+'&studentRoll='+form.studentRoll.value+'&feeCycle='+form.feeCycle.value;
    str = str + '&receiptNo='+form.receiptNo.value;  
    str = str + '&feecycleName='+feeCycle.options[feeCycle.selectedIndex].text+'&paymentStatus='+form.paymentStatus.value+'&paymentStatusName='+paymentStatus.options[paymentStatus.selectedIndex].text;
    str = str + '&fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&receiptStatus='+form.receiptStatus.value+'&receiptStatusName='+receiptStatus.options[receiptStatus.selectedIndex].text;
    //str = str + '&fromAmount='+form.fromAmount.value+'&toAmount='+form.toAmount.value;
    str = str + '&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    
	path='<?php echo UI_HTTP_PATH;?>/feeStatusReportCSV.php?'+str; 
	window.location=path;
	//document.getElementById('generateCSV').href=path;
	//document.getElementById('generateCSV1').href=path;
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    //require_once(TEMPLATES_PATH . "/Student/paymentReceiptStatus.php");
    require_once(TEMPLATES_PATH . "/Student/paymentReceiptStatusContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: feeReceiptStatus.php $
//
//*****************  Version 8  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface
//added code for autosuggest functionality
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 10/28/09   Time: 6:42p
//Updated in $/LeapCC/Interface
//added script for autosuggest
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 09-09-03   Time: 12:40p
//Updated in $/LeapCC/Interface
//fixed 0001421,0001422,0001428,0001430,0001434,0001435
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/07/09    Time: 6:27p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
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
//User: Rajeev       Date: 9/05/08    Time: 6:03p
//Created in $/Leap/Source/Interface
//initial checkin
?>
