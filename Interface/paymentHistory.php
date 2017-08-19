<?php
//-------------------------------------------------------
// Purpose: To generate student payment history list
// functionality 
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
global $sessionHandler;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisplayFeePaymentHistory');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
$roleId = $sessionHandler->getSessionVariable('RoleId');
if($roleId == 5){
UtilityManager::ifManagementNotLoggedIn();
}
else{
	UtilityManager::ifNotLoggedIn();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Fee Payment History</title>
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
  	  	  	  	
var tableHeadArray = new Array(new Array('srNos',               '#','width="2%"','',false),
                               //new Array('checkAll',            '<input type="checkbox" name="checkbox2" value="checkbox" onClick="doAll()">','width="3%"','',false),
                               new Array('receiptDate',         'Receipt<br>Date','width="9%"','align="center"',true),  
                               new Array('receiptNo',           'Receipt','width="10%"','align="left"',true),
                               new Array('studentName',         'Name','width="12%"','',true) , 
                               new Array('rollNo',              'Roll No.','width="10%"','',true), 
                               new Array('className',           'Fee Class','width="15%"','',true),  
                               new Array('cycleName',           'Fee Cycle','width="9%"','',true),  
                               new Array('installmentCount',    'Installment','width="11%"','',true), 
                               new Array('discountedFeePayable','Payable<br>(Rs.)','width="10%"','align="right"',false), 
                               new Array('amountPaid',          'Paid<br>(Rs.)','width="8%"','align="right"',false), 
                               new Array('previousDues',        'Outstanding<br>(Rs.)','width="13%"','align="right"',false),
                               new Array('instStatus',          'Instrument','width="12%"','align="left"',false), 
                               new Array('retStatus',           'Status','width="12%"','align="left"',false),
                               new Array('printAction','Action','width="10%"','align="center" nowrap',false));                
                  	   

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

function doAll(){

	formx = document.listForm;
	if(formx.checkbox2.checked){
		for(var i=1;i<formx.length;i++){
			if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
            	formx.elements[i].checked=true;
			} 
		}
	}
	else{
		for(var i=1;i<formx.length;i++){
			
			if(formx.elements[i].type=="checkbox"){
			
				formx.elements[i].checked=false;
			}
		}
	}
}

function validateStatus() {

	fromDate = document.listForm.fromDate.value;
	toDate = document.listForm.toDate.value;
	
    if(document.listForm.feeCycle.value==''){
      messageBox("Select Fee Cycle");
      document.listForm.feeCycle.focus();
      return false;
    }
     
	if(dateCompare(fromDate,toDate)==1){
  	  messageBox("<?php echo PH_CORRECT_DATE?>");
	  document.listForm.toDate.select();
	  return false;
	}
	
	if(true == isEmpty(document.getElementById('startingRecord').value)){
        messageBox("<?php echo START_RECORD ?>");
        document.listForm.startingRecord.focus();
        return false;
    }
    
    if(false == isNumeric(document.getElementById('startingRecord').value) ) {
       messageBox("<?php echo ENTER_NUMERIC_VALUE ?>");
       document.listForm.startingRecord.focus(); 
       return false;  
    }
    
    if(document.getElementById('startingRecord').value <= 0 ) {
       messageBox("<?php echo "Starting value for 'Starting Record No.' field should be 1 "; ?>");
       document.listForm.startingRecord.focus(); 
       return false;  
    }

     if(true == isEmpty(document.getElementById('totalRecords').value)){
        messageBox("<?php echo "ENTER No. Of Records In Report" ?>");
        document.listForm.totalRecords.focus();
        return false;
    }

    if(false == isNumeric(document.getElementById('totalRecords').value) ) {
       messageBox("<?php echo ENTER_NUMERIC_VALUE ?>");
       document.listForm.totalRecords.focus(); 
       return false;  
    }
    
    if(document.getElementById('totalRecords').value <= 0 ) { 
       messageBox("<?php echo "No. of Records Field Should Be 1 or Greater "; ?>");
       document.listForm.totalRecords.focus(); 
       return false;  
    }
    
    if(document.getElementById('totalRecords').value > 100 ) { 
       messageBox("<?php echo "No. of Records Field Should Be 100 or Lesser "; ?>");
       document.listForm.totalRecords.focus(); 
       return false;  
    }
	
    /*
	if(document.listForm.fromAmount.value){
		reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
		if (!reg.test(document.getElementById('fromAmount').value)){
			messageBox("<?php echo PH_CORRECT_PAID?>");
			document.listForm.fromAmount.focus();
			return false;
		}
	}
	if(document.listForm.toAmount.value){
		reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
		if (!reg.test(document.getElementById('toAmount').value)){
			messageBox("<?php echo PH_CORRECT_PAID_TO?>");
			document.listForm.toAmount.focus();
			return false;
		}
	}
	if((document.listForm.fromAmount.value!='') && (document.listForm.toAmount.value!='')){
		if (parseFloat(document.getElementById('fromAmount').value)>parseFloat(document.getElementById('toAmount').value)){
			messageBox("<?php echo PH_CORRECT_PAID_TO_FROM?>");
			document.listForm.fromAmount.focus();
			return false;
		}
	}
    */
    
    sendReq(listURL,divResultName,'listForm','&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false);     
    document.getElementById('saveDiv').style.display='';
    document.getElementById('showTitle').style.display='';
    document.getElementById('showData').style.display='';
    document.getElementById('nameRow2').style.display='';
    
	return false;
}

function printReport() {
	
	if(true == isEmpty(document.getElementById('startingRecord').value)){
        messageBox("<?php echo START_RECORD ?>");
        document.listForm.startingRecord.focus();
        return false;
    }
    
    if(false == isNumeric(document.getElementById('startingRecord').value) ) {
       messageBox("<?php echo ENTER_NUMERIC_VALUE ?>");
       document.listForm.startingRecord.focus(); 
       return false;  
    }
    
    if(document.getElementById('startingRecord').value <= 0 ) {
       messageBox("<?php echo "Starting value for 'Starting Record No.' field should be 1 "; ?>");
       document.listForm.startingRecord.focus(); 
       return false;  
    }

     if(true == isEmpty(document.getElementById('totalRecords').value)){
        messageBox("<?php echo "ENTER No. Of Records In Report" ?>");
        document.listForm.totalRecords.focus();
        return false;
    }

    if(false == isNumeric(document.getElementById('totalRecords').value) ) {
       messageBox("<?php echo ENTER_NUMERIC_VALUE ?>");
       document.listForm.totalRecords.focus(); 
       return false;  
    }
    
    if(document.getElementById('totalRecords').value <= 0 ) { 
       messageBox("<?php echo "No. of Records Field Should Be 1 or Greater "; ?>");
       document.listForm.totalRecords.focus(); 
       return false;  
    }
    
    if(document.getElementById('totalRecords').value > 100 ) { 
       messageBox("<?php echo "No. of Records Field Should Be 100 or Lesser "; ?>");
       document.listForm.totalRecords.focus(); 
       return false;  
    }
	
	form = document.listForm;
    var degree = document.getElementById('degree');
    var batch = document.getElementById('batch');
    var studyperiod = document.getElementById('studyperiod');
    var feeCycle = document.getElementById('feeCycle');
    var paymentStatus = document.getElementById('paymentStatus');
    var receiptStatus = document.getElementById('receiptStatus');
    var paymentType = document.getElementById('paymentType');
    var start = document.getElementById('startingRecord').value;
    var end = document.getElementById('totalRecords').value;
    
    if(document.listForm.isSort.checked==true) {
       isSort =1;
    }
    else {
       isSort =0;  
    }
    
    var str = 'degree='+form.degree.value+'&degreeName='+degree.options[degree.selectedIndex].text+'&batch='+form.batch.value;
    str = str + '&batchName='+batch.options[batch.selectedIndex].text+'&studyperiod='+form.studyperiod.value+'&studyperiodName='+studyperiod.options[studyperiod.selectedIndex].text;
    str = str + '&studentName='+form.studentName.value+'&studentRoll='+form.studentRoll.value+'&feeCycle='+form.feeCycle.value;
    str = str + '&receiptNo='+form.receiptNo.value;  
    str = str + '&feecycleName='+feeCycle.options[feeCycle.selectedIndex].text+'&paymentStatus='+form.paymentStatus.value+'&paymentStatusName='+paymentStatus.options[paymentStatus.selectedIndex].text;
    str = str + '&fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&receiptStatus='+form.receiptStatus.value+'&receiptStatusName='+receiptStatus.options[receiptStatus.selectedIndex].text;
    //str = str + '&fromAmount='+form.fromAmount.value+'&toAmount='+form.toAmount.value;
    str = str + '&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&start='+start+'&end='+end+'&isSort='+isSort;
    
	path='<?php echo UI_HTTP_PATH;?>/paymentHistoryReportPrint.php?'+str;

	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=900, height=500, top=100,left=50");
}

/* function to print all payment history to csv*/
function printCSV() {

	form = document.listForm;
    var degree = document.getElementById('degree');
    var batch = document.getElementById('batch');
    var studyperiod = document.getElementById('studyperiod');
    var feeCycle = document.getElementById('feeCycle');
    var paymentStatus = document.getElementById('paymentStatus');
    var receiptStatus = document.getElementById('receiptStatus');
    var paymentType = document.getElementById('paymentType');
    
    if(document.listForm.isSort.checked==true) {
       isSort =1;
    }
    else {
       isSort =0;  
    }
       
    var str = 'degree='+form.degree.value+'&degreeName='+degree.options[degree.selectedIndex].text+'&batch='+form.batch.value;
    str = str + '&batchName='+batch.options[batch.selectedIndex].text+'&studyperiod='+form.studyperiod.value+'&studyperiodName='+studyperiod.options[studyperiod.selectedIndex].text;
    str = str + '&studentName='+form.studentName.value+'&studentRoll='+form.studentRoll.value+'&feeCycle='+form.feeCycle.value;
    str = str + '&receiptNo='+form.receiptNo.value;  
    str = str + '&feecycleName='+feeCycle.options[feeCycle.selectedIndex].text+'&paymentStatus='+form.paymentStatus.value+'&paymentStatusName='+paymentStatus.options[paymentStatus.selectedIndex].text;
    str = str + '&fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&receiptStatus='+form.receiptStatus.value+'&receiptStatusName='+receiptStatus.options[receiptStatus.selectedIndex].text;
    //str = str + '&fromAmount='+form.fromAmount.value+'&toAmount='+form.toAmount.value;
    str = str + '&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&isSort='+isSort;
    
	path='<?php echo UI_HTTP_PATH;?>/paymentHistoryReportCSV.php?'+str; 

	window.location=path;
}
 
function printReceipt(id,feeType){
	 
	path='<?php echo UI_HTTP_PATH;?>/paymentReceiptPrint.php?receiptId='+id+'&feeType='+feeType;
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=700, height=400, top=100,left=50");
}

function printDetailReceipt(id,feeType){
	 
	path='<?php echo UI_HTTP_PATH;?>/paymentReceiptDetailPrint.php?receiptId='+id+'&feeType='+feeType;
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=700, height=600, top=100,left=150");
}

function deleteReceipt(id,feeType){
	
	//allReceiptId = id.split(',');
	//alert(allReceiptId[0]);
	//alert(id);
	if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
		 return false;
	 }
	 else {   
	
		 url = '<?php echo HTTP_LIB_PATH;?>/Student/paymentDeleteReceipt.php';
		 new Ajax.Request(url,
		 {
			 method:'post',
			 parameters: {receiptId: (id),receiptType: (feeType)},
			 onCreate: function(){
				 showWaitDialog(true);
			 },
			 onSuccess: function(transport){
				 hideWaitDialog(true);
				 if("<?php echo DELETE;?>"==trim(transport.responseText)) {
					 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
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
function showListForm(frm, act) {
    
	var selected=0;
	var selected1=0;
	formx = document.listForm;
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
</script>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/paymentHistoryContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>