<?php
//-------------------------------------------------------
// Purpose: To generate student fee receipt
// functionality 
//
// Author : Rajeev Aggarwal
// Created on : (17.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CollectFees');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
global $sessionHandler;
$studentFeeReceiptdate = $sessionHandler->getSessionVariable('FEE_RECEIPT_DATE');
if($studentFeeReceiptdate==''){

	$studentFeeReceiptdate = date('Y-m-d');
}

//global $sessionHandler;
//$studentFeeReceiptNo = $sessionHandler->getSessionVariable('FEE_RECEIPT_PREFIX');
//require_once(BL_PATH . "/Student/initList.php");
//require_once(BL_PATH . "/Student/getFeeReceiptNumber.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Collect Fees</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
recordsPerPageTeacher = <?php echo RECORDS_PER_PAGE_TEACHER;?>;
</script>
<?php
echo UtilityManager::javaScriptFile2();
?>


<script type="text/javascript">
window.onload = function () {
    var roll = document.getElementById("studentRoll");
    autoSuggest(roll);
    document.feeForm.receiptNumber.focus();
}
</script>
<script language="javascript">
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//----------------------------------------------------------------------
//Author:Rajeev Aggarwal
//Purpose:validate the data before insertion
//Date:17.7.2008
//------------------------------------------------------------------------
var resourceAddCnt=0;

//----------------------------------------------------------------------
//Author:Rajeev Aggarwal
//Purpose:To insert data
//Date:17.7.2008
//------------------------------------------------------------------------
function addStudentFees(act){
 
	 
	url = '<?php echo HTTP_LIB_PATH;?>/Student/initAddFee.php';
	new Ajax.Request(url,
	{
		method:'post',
		parameters: $('feeForm').serialize(true),
		onCreate:function(transport){ showWaitDialog(true);},
		onSuccess: function(transport){
			   
		hideWaitDialog(true);
		//alert(transport.responseText);
		returnArr = trim(transport.responseText).split('~'); 
		if(returnArr[1]!=0) {
			 messageBox(returnArr[0]);
			 flag = true;
			 if(returnArr[0]=="<?php echo SUCCESS;?>") {
				 
				document.getElementById('results').innerHTML="<table border='0' cellspacing='0' cellpadding='3' width='100%'><tr class='rowheading'><td valign='middle' width='3%'><B>#</B></td><td valign='middle' width='60%'><B>Fee Head</B></td><td valign='middle' width='15%'><B>Amount</B></td><td valign='middle' width='15%'><B>Concession</B></td></tr><tr class='row0'><td valign='middle' colspan='4' align='center'>No detail found</td></tr></table>";
				document.getElementById('myClass').innerHTML="--";
				document.getElementById('myFirst').innerHTML="--";
				document.getElementById('myLast').innerHTML="";
				
				document.getElementById('myFather').innerHTML="--";
				document.getElementById('myInstallment').innerHTML="Installment 1";
				cleanUpTable();
				//document.getElementById('anyidT').innerHTML='';
				//document.getElementById('anyidT').innerHTML='<table width="100%" border="0" cellspacing="2" cellpadding="0" id="anyid"><tbody id="anyidBody"><tr class="rowheading"><td width="2%" class="searchhead_text"><b>Sr.</b></td><td class="searchhead_text"><b>Type</b></td><td  class="searchhead_text"><b>Number</b></td><td  class="searchhead_text"><b>Amount</b></td><td  class="searchhead_text"><b>Bank</b></td><td class="searchhead_text"><b>Date</b></td><td class="searchhead_text"><b>Inst. Status</b></td><td class="searchhead_text"><b>Receipt Status</b></td><td class="searchhead_text"><b>Delete</b></td></tr></tbody></table>';
				//var resourceAddCnt=0;
				//cleanUpTable();
				if(act=="Print"){

					printReport(returnArr[1],document.getElementById('feeType').value);
				}
				document.getElementById('feeForm').reset();
				document.getElementById('receiptNumber').focus();
				//location.reload();
				document.feeForm.receiptNumber.focus();
			 }
			 else {

				 //location.reload();
				 return false;
			 }
		 }
		 else if("<?php echo RECEIPT_ALREADY_EXIST;?>" == trim(returnArr[0])){
			 
			 messageBox("<?php echo RECEIPT_ALREADY_EXIST ;?>"); 
			 document.feeForm.receiptNumber.focus();
			  return false;
		 }
		 else {
			
			 messageBox(trim(transport.responseText));
			 document.feeForm.receiptNumber.focus(); 
			 document.getElementById('feeForm').reset(); 
		 }
	 },
	 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}
//----------------------------------------------------------------------
//Author:Rajeev Aggarwal
//Purpose:Populates "student data" before edit
//Date:17.7.2008
//------------------------------------------------------------------------
function populateValues(id) {

url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentBasicValues.php';
if(document.getElementById('receiptDate').value!='' && document.getElementById('studentRoll').value!='' && document.getElementById('feeCycle').value!='' && document.getElementById('feeStudyPeriod').value!='' && document.getElementById('feeType').value!='')
{
new Ajax.Request(url,
   {
		method:'post',
		parameters: {receiptDate: (document.getElementById('receiptDate').value),rollNo: (document.getElementById('studentRoll').value),feeCycleId: (document.getElementById('feeCycle').value), studyPeriodId: (document.getElementById('feeStudyPeriod').value), feeTypeId: (document.getElementById('feeType').value), studentId: (document.getElementById('studentId').value), deleteStudent: (document.getElementById('deleteStudent').value)},
		onCreate:function(transport){ showWaitDialog(true);},
		onSuccess: function(transport){
		hideWaitDialog(true);
		j= trim(transport.responseText).evalJSON();
		var tbHeadArray = new Array(new Array('srNo','#','width="3%"',''), new Array('headName','Head Name','width="83%"','') , new Array('feeHeadAmt','Amount','width="10%"',' align="right"'), new Array('concession','Concession','width="4%"',' align="right"'));
		 
		printResultsNoSorting('results', j.info, tbHeadArray);
		 
		document.feeForm.studentId.value = j.studentinfo[0].studentId;
		document.feeForm.studentName.value = j.studentinfo[0].firstName;
		document.feeForm.studentLName.value = j.studentinfo[0].lastName;
		//document.feeForm.fatherName.value = j.studentinfo[0].fatherName;
		document.getElementById('myClass').innerHTML=j.studentinfo[0].className;
		//document.feeForm.currStudyPeriod.value = j.studentinfo[0].studyPeriodId;
		//document.getElementById('mySerial').innerHTML=j.serialNo;
		//document.getElementById('myReceipt').innerHTML="<?php echo $studentFeeReceiptNo ?>";
		 //alert("----"+j.studentinfo[0].fatherName);
		document.getElementById('myFirst').innerHTML=j.studentinfo[0].firstName;
		document.getElementById('myLast').innerHTML=j.studentinfo[0].lastName;
		document.getElementById('myFather').innerHTML=j.studentinfo[0].fatherName;
		
		//document.getElementById('myStudyPeriod').innerHTML=j.studentinfo[0].periodName;
		document.feeForm.receivedFrom.value =j.studentinfo[0].firstName+' '+j.studentinfo[0].lastName;
		document.feeForm.studentClass.value = j.studentinfo[0].classId;
		document.feeForm.totalFees.value = j.totalAmount;
		//alert(j.totalAmount-j.discAmount);
		document.feeForm.totalConcession.value = (j.totalAmount-j.discAmount).toFixed(2);
		document.getElementById('myInstallment').innerHTML=j.studentInstallmentCount;
		document.feeForm.totalFeesHidden.value = j.totalAmount;
		//document.feeForm.paidAmount.value = j.totalAmount;
		//document.feeForm.cashAmount.value = j.totalAmount;
		//document.feeForm.serialNumber.value = document.getElementById('receiptNumber').value;
		//document.feeForm.receiptNumber.value = "<?php echo $studentFeeReceiptNo ?>"+''+j.serialNo;
		document.feeForm.studentFine.value = j.studentFeeCycleFine;
		document.feeForm.previousPayment.value = j.previousPaid;
		document.feeForm.previousDues.value = j.previousDues;
		 
		document.feeForm.amountPayable.value = (parseFloat(document.feeForm.totalFees.value)-parseFloat(document.feeForm.previousPayment.value)).toFixed(2);
		
		 
		document.feeForm.netAmount.value = (parseFloat(document.feeForm.amountPayable.value)+ parseFloat(document.feeForm.studentFine.value)- parseFloat(document.feeForm.totalConcession.value)).toFixed(2);  
		
		document.feeForm.netAmount1.value = (parseFloat(j.totalAmount)+ parseFloat(document.feeForm.studentFine.value)- parseFloat(document.feeForm.totalConcession.value)).toFixed(2);  

		//document.feeForm.paidAmount.value = document.feeForm.netAmount.value;
		if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
			 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
			 return false;
		   }
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
	}
	else
	{
		//printResultsNoSorting('results', j.info, tbHeadArray);
		document.getElementById('results').innerHTML="<table border='0' cellspacing='0' cellpadding='3' width='100%'><tr class='rowheading'><td valign='middle' width='3%'><B>#</B></td><td valign='middle' width='60%'><B>Fee Head</B></td><td valign='middle' width='15%'><B>Amount</B></td><td valign='middle' width='15%'><B>Concession</B></td></tr><tr class='row0'><td valign='middle' colspan='4' align='center'>No detail found</td></tr></table>";
	}
}

//----------------------------------------------------------------------
//Author:Rajeev Aggarwal
//Purpose:validate the to check correct value
//Date:17.7.2008
//------------------------------------------------------------------------
function isCorrectValue(val){

	if(isInteger(val)){
		
		return 1;
	}
	else{

		alert("not integer");
		return 0;
	}
}

//----------------------------------------------------------------------
//Author:Rajeev Aggarwal
//Purpose:to calculate concession
//Date:17.7.2008
//------------------------------------------------------------------------
	function calculateConcession(){

		obj = document.feeForm.elements['chb[]'];
		obj1 = document.feeForm.elements['feeHeadAmt[]'];
	 
		len = obj.length;
		if(len>0 && obj!='undefined'){

			sumConcession = 0;
			for(i=0;i<len;i++){
				
				reg = new RegExp("^[-+]{0,1}[0-9]*[.]{0,1}[0-9]*$");
				if (!reg.test(obj[i].value)){

					alert("Please enter correct concession value");
					obj[i].value="0.00";
				}
				else{
					if(parseFloat(obj[i].value)>=0){
					
						if(parseFloat(obj[i].value)>parseFloat(obj1[i].value)){
			  
							obj[i].value="0.00";
						}
						//alert(obj[i].value);
						sumConcession = parseFloat(parseFloat(obj[i].value)+ parseFloat(sumConcession));
				}
				else{
					obj[i].value="0.00";
				}
			}
		}
	 }
	 else{

		if(obj.value>0 && obj!='undefined'){
			
			sumConcession = obj.value;
		}
	 }
	document.feeForm.totalFees.value       = parseFloat(document.feeForm.totalFeesHidden.value).toFixed(2)
	document.feeForm.totalConcession.value = parseFloat(sumConcession).toFixed(2);
	if(parseInt(document.feeForm.studentFine.value)!=0){

		if(parseInt(document.feeForm.studentFine.value)>0){

			document.feeForm.netAmount.value       = parseFloat(parseFloat(document.feeForm.totalFees.value) - parseFloat(document.feeForm.previousPayment.value)- parseFloat(sumConcession) + parseFloat(document.feeForm.studentFine.value)).toFixed(2);
			
			document.feeForm.netAmount1.value       = parseFloat(parseFloat(document.feeForm.totalFees.value) -  parseFloat(document.feeForm.totalConcession.value) + parseFloat(document.feeForm.studentFine.value)).toFixed(2);
		}
		else
			document.feeForm.studentFine.value = "0.00";
	}
	else{
		 //alert(document.feeForm.totalFees.value);
		document.feeForm.netAmount.value       = parseFloat(parseFloat(document.feeForm.totalFees.value) - parseFloat(document.feeForm.previousPayment.value)- parseFloat(sumConcession) + parseFloat(document.feeForm.studentFine.value)).toFixed(2);

		document.feeForm.netAmount1.value       = parseFloat(parseFloat(document.feeForm.totalFees.value) -  parseFloat(document.feeForm.totalConcession.value) + parseFloat(document.feeForm.studentFine.value)).toFixed(2);
	}
	//document.feeForm.paidAmount.value      = document.feeForm.netAmount.value;
	//document.feeForm.cashAmount.value      = document.feeForm.netAmount.value;
	 
}

//----------------------------------------------------------------------
//Author:Rajeev Aggarwal
//Purpose:to calculate concession
//Date:17.7.2008
//------------------------------------------------------------------------
	function calculateTotal(){

		obj = document.feeForm.elements['feeHeadAmt[]'];
		//obj1 = document.feeForm.elements['feeHeadAmt[]'];
	 
		len = obj.length;
		
		if(len>0 && obj!='undefined'){
			
			sumAmount = 0;
			for(i=0;i<len;i++){
				
				reg = new RegExp("^[-+]{0,1}[0-9]*[.]{0,1}[0-9]*$");
				if (!reg.test(obj[i].value)){

					alert("Please enter correct value");
					obj[i].value="0.00";
				}
				else{
					if(parseFloat(obj[i].value)>=0){
					
					 
						sumAmount = parseFloat(parseFloat(obj[i].value)+ parseFloat(sumAmount));
					}
					else{
						obj[i].value="0.00";
					}
				}
			}
	 
		//alert(sumAmount);
	 }
	 else{

		if(obj.value>0 && obj!='undefined'){
			
			sumAmount = obj.value;
		}
	 }
	//document.feeForm.totalFees.value       = parseFloat(parseFloat(document.feeForm.totalFeesHidden.value)+parseFloat(sumAmount)).toFixed(2);
	document.feeForm.totalFees.value       = parseFloat(sumAmount).toFixed(2);
	document.feeForm.totalFeesHidden.value= parseFloat(sumAmount).toFixed(2);
	//document.feeForm.totalConcession.value = parseFloat(sumAmount).toFixed(2);
	if(parseInt(document.feeForm.studentFine.value)!=0){

		if(parseInt(document.feeForm.studentFine.value)>0){

			document.feeForm.netAmount.value       = parseFloat(parseFloat(document.feeForm.totalFees.value) - parseFloat(document.feeForm.previousPayment.value) + parseFloat(document.feeForm.studentFine.value)).toFixed(2);
			
			document.feeForm.netAmount1.value       = parseFloat(parseFloat(document.feeForm.totalFees.value) -  parseFloat(document.feeForm.totalConcession.value) + parseFloat(document.feeForm.studentFine.value)).toFixed(2);
		}
		else
			document.feeForm.studentFine.value = "0.00";
	}
	else{
  
		document.feeForm.netAmount.value       = parseFloat(parseFloat(document.feeForm.totalFees.value) - parseFloat(document.feeForm.previousPayment.value) + parseFloat(document.feeForm.studentFine.value)).toFixed(2);

		document.feeForm.netAmount1.value       = parseFloat(parseFloat(document.feeForm.totalFees.value) -  parseFloat(document.feeForm.totalConcession.value) + parseFloat(document.feeForm.studentFine.value)).toFixed(2);
	}
	//document.feeForm.paidAmount.value      = document.feeForm.netAmount.value;
	//document.feeForm.cashAmount.value      = document.feeForm.netAmount.value;
}

//----------------------------------------------------------------------
//Author:Rajeev Aggarwal
//Purpose:hide/show elements based on cash and cheque/draft
//Date:17.7.2008
//------------------------------------------------------------------------
function formdisable(selectedValue){
	
	if(selectedValue!=1){

		document.feeForm.chequeNumber.value="";
		document.feeForm.issuingBank.value="";
		//document.feeForm.payablebank.value="";
		document.feeForm.favouringBank.value="";
		document.feeForm.issuingDate.value="";
		//document.getElementById('checkno').style.display='';
		//document.getElementById('issuebank').style.display='';
		//document.getElementById('payablebank').style.display='';
		//document.getElementById('favourbank').style.display='';
		document.getElementById('cashpay').style.display='';
		document.getElementById('instStatus').style.display='';
		document.getElementById('reStatus').style.display='';
		document.getElementById('paymentStatus').selectedIndex=1;
		document.getElementById('receiptStatus').selectedIndex=1;
		document.feeForm.chequeNumber.focus();
	}
	else{

		//document.getElementById('checkno').style.display='none';
		//document.getElementById('issuebank').style.display='none';
		//document.getElementById('payablebank').style.display='none';
		//document.getElementById('favourbank').style.display='none';
		document.getElementById('cashpay').style.display='none';
		document.getElementById('instStatus').style.display='none';
		document.getElementById('reStatus').style.display='none';
		document.getElementById('paymentStatus').selectedIndex=3;
		document.getElementById('receiptStatus').selectedIndex=2;
	}
}

//----------------------------------------------------------------------
//Author:Rajeev Aggarwal
//Purpose:to print fees receipt
//Date:17.7.2008
//------------------------------------------------------------------------
function printReport(receiptId,feeType) {

	
	form = document.feeForm;
	path='<?php echo UI_HTTP_PATH;?>/paymentReceiptDetailPrint.php?receiptId='+receiptId+'&feeType='+feeType;
	//alert(path);
	window.open(path,"Missed Attendance Report","status=1,menubar=1,scrollbars=1, width=800, height=600, top=150,left=150");
}

function showStudentDetails(dv,w,h,left,top) {
    
	if(typeof left === 'undefined') {
		left = 150;
		top = 0;
	}
    displayFloatingDiv(dv,'',w,h,left,top);
	document.getElementById(dv).style.top='40px';
	//populateStudent();
}

function blankValues(){

}

function getData(){

	if((document.listForm.studentClass.value)==''){
	
		messageBox("Please select class");
        document.listForm.studentClass.focus();
		return false;
	}
	 
	//if(isCharsInBag(document.listForm.studentName.value)){
	if(!isAlphaNumericCustom(trim(document.listForm.studentName.value),".,& ()") ){
	
		messageBox("Please correct value");
        document.listForm.studentName.focus();
		return false;
	}
	
	url1 = '<?php echo HTTP_LIB_PATH;?>/Student/getStudentList.php';
	if(document.listForm.deletedStudent.checked){
	
		deletedStudent=1;
	}
	else{
	
		deletedStudent=0;
	}
	
	var tableHeadArray = new Array(
		new Array('srNo','#','width="2%" align="left"',false), 
		new Array('students','Select','width="4%" align=\"center\"',false), 
		new Array('studentName','Name','width="30%" align="left"',true),
		new Array('rollNo','R. No.','width="12%"',true) ,
		new Array('fatherName','Father Name','width="25%"',true) ,
		new Array('universityRollNo','Univ. R. No.','width="18%"',true)
    );

	listObj1 = new initPage(url1,recordsPerPageTeacher,linksPerPage,1,'','studentName','ASC','studentResult','','',true,'listObj1',tableHeadArray,'','','&studentClass='+document.listForm.studentClass.value+'&studentName='+document.listForm.studentName.value+'&deletedStudent='+deletedStudent);
	//alert(document.listForm.studentName.value);
	sendRequest(url1, listObj1, '',false);
  /*
	if(listObj1.totalRecords==0){
	
		document.getElementById('showSubmit').style.display='none';
	}
	 */
}
function fillStudent(studentValue){

	//alert(studentValue);
	hiddenFloatingDiv('getStudentDetail');
	studentValueArr = studentValue.split('~');
	document.feeForm.studentRoll.value=studentValueArr[1];
	document.feeForm.studentId.value=studentValueArr[0];
	//alert(studentValueArr[5]);
	document.feeForm.deleteStudent.value=studentValueArr[5];

	document.getElementById('myClass').innerHTML=studentValueArr[2];
	document.getElementById('myFirst').innerHTML=studentValueArr[3];
	document.getElementById('myFather').innerHTML=studentValueArr[4];
	populateValues();
 
}

function clearData(){

	document.getElementById('studentResult').style.display='none';	
}
function sendKeys(eleName, e,ctr) {
	var ev = e||window.event;
	thisKeyCode = ev.keyCode;
	if (thisKeyCode == '13') {
		var form = document.feeForm;

		if (eleName != 'imageFieldSave') {
			if (eval('form.'+eleName)) {
				eval('form.'+eleName+'.focus()');
			}
		}
		else {
			validateAddForm(document.feeForm);	
		}
		return false;
	}
}

// check browser
 var isMozilla = (document.all) ? 0 : 1;

function addDetailRows(value){
	 
	 var tbl=document.getElementById('anyid');
	 var tbody = document.getElementById('anyidBody');
	 //var tblB    = document.createElement("tbody");
	 if(!isInteger(value)){
		return false;
	 }
	 
	 if(resourceAddCnt>0){     //if user reenter no of rows
	  //if(confirm('Previous Data Will Be Erased.\n Are You Sure ?')){
		   cleanUpTable();
	  //}
	  //else{
		//  return false;
	  //}
	} 
	resourceAddCnt=parseInt(value); 
	createRows(0,resourceAddCnt,0);
}


    //for deleting a row from the table 
    function deleteRow(value){
      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody');
      
      var tr=document.getElementById('row'+rval[0]);
      tbody1.removeChild(tr);
     
      if(isMozilla){
          if((tbody1.childNodes.length-2)==0){
              resourceAddCnt=0;
          }
      }
      else{
          if((tbody1.childNodes.length-1)==0){
              resourceAddCnt=0;
          }
      }
	  //document.feeForm.rowCnt.value=parseInt(document.feeForm.rowCnt.value)-1;
    } 


    //to add one row at the end of the list
    function addOneRow(cnt) {

        //set value true to check that the records were retrieved but not posted bcos user marked them deleted
        document.getElementById('deleteFlag').value=true;
        if(cnt=='')
			cnt=1;  
        if(isMozilla){
             
			 if(document.getElementById('anyidBody').childNodes.length <= 3){
                resourceAddCnt=0; 
             }       
        }
        else{
             
			 if(document.getElementById('anyidBody').childNodes.length <= 1){
               resourceAddCnt=0;  
             }       
        }  
        resourceAddCnt++; 
        createRows(resourceAddCnt,cnt);
    }

    //to clean up table rows
    function cleanUpTable(){
       var tbody = document.getElementById('anyidBody');
       for(var k=0;k<=resourceAddCnt;k++){
             try{
              tbody.removeChild(document.getElementById('row'+k));
             }
             catch(e){
                 //alert(k);  // to take care of deletion problem
             }
          }  
    }

    var bgclass='';

    //create dynamic rows 
    
    //function createRows(start,rowCnt,optionData,sectionData,roomData){
var serverDate="<?php echo date('Y-m-d');?>";
function createRows(start,rowCnt){
	   
	 var tbl=document.getElementById('anyid');
	 var tbody = document.getElementById('anyidBody');
						 
	 for(var i=0;i<rowCnt;i++){

	  var tr=document.createElement('tr');
	  tr.setAttribute('id','row'+parseInt(start+i,10));
	  
	  var cell1=document.createElement('td');
	  var cell2=document.createElement('td'); 
	  var cell3=document.createElement('td'); 
	  var cell4=document.createElement('td');
	  var cell5=document.createElement('td');
	  var cell6=document.createElement('td');
	  var cell7=document.createElement('td');
	  var cell8=document.createElement('td');
	  var cell8=document.createElement('td');
	  var cell9=document.createElement('td');
	  
	  cell1.setAttribute('align','left');      
	  cell2.setAttribute('align','left'); 
	  cell3.setAttribute('align','left'); 
	  cell4.setAttribute('align','left'); 
	  cell5.setAttribute('align','right');
	  cell6.setAttribute('align','center');
	  cell7.setAttribute('align','center');
	  cell8.setAttribute('align','center');
	  if(start==0){

		var txt0=document.createTextNode(start+i+1);
	  }
	  else{

		var txt0=document.createTextNode(start+i);
	  }
	  var txt1=document.createElement('select');
	  var txt2=document.createElement('input');
	  var txt3=document.createElement('input');
	  var txt4=document.createElement('select');
	  var txt5=document.createElement('select');
	  var txt6=document.createElement('select');
	  var txt7=document.createElement('select');
	  
	  var txt8=document.createElement('a');
	   

	  txt1.setAttribute('id','paymentTypeId'+parseInt(start+i,10));
	  txt1.setAttribute('name','paymentTypeId[]');
	  thisCtr = parseInt(start+i,10);
	  txt1.className='htmlElement';

	  txt2.setAttribute('id','instId'+parseInt(start+i,10));
	  txt2.setAttribute('name','instId[]');
	  txt2.className='inputbox';
	  txt2.style.width='50px';

	  txt3.setAttribute('id','amtId'+parseInt(start+i,10));
	  txt3.setAttribute('name','amtId[]'); 
	  txt3.className='inputbox';
	  txt3.style.width='50px';
	  txt3.value='0';
	  txt3.onblur = new Function("getAmountPaid('"+thisCtr+"')");
	  
	  txt4.setAttribute('id','issuingBankId'+parseInt(start+i,10));
	  txt4.setAttribute('name','issuingBankId[]');
	  txt4.className='htmlElement';

      
	  txt6.setAttribute('id','paymentStatusId'+parseInt(start+i,10));
	  txt6.setAttribute('name','paymentStatusId[]');
	  txt6.className='htmlElement';
      
	  
	  txt7.setAttribute('id','receiptStatusId'+parseInt(start+i,10));
	  txt7.setAttribute('name','receiptStatusId[]');
	  txt7.className='htmlElement';
	 
	  txt8.setAttribute('id','rd');
	  txt8.className='htmlElement';  
	  txt8.setAttribute('title','Delete');       
	  
	  txt8.innerHTML='X';
	  txt8.style.cursor='pointer';
	  

	  txt8.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff    
	  
	  cell1.appendChild(txt0);
	  cell2.appendChild(txt1);
	  cell3.appendChild(txt2);
	  cell4.appendChild(txt3);
	  cell5.appendChild(txt4);
	  cell6.innerHTML='<input type="text" id="leaveDate'+parseInt(start+i,10)+'" name="leaveDate'+parseInt(start+i,10)+'" class="inputBox" readonly="true" value="'+serverDate+'" size="8" />';
	  cell6.innerHTML +="<input type=\"image\" id=\"calImg\" name=\"calImg\" title=\"Select Date\" src=\""+imagePathURL+"/calendar.gif\"  onClick=\"return showCalendar('leaveDate"+parseInt(start+i,10)+"','%Y-%m-%d', '24', true);\">";
	  //cell6.appendChild(txt6);
	   
	  cell7.appendChild(txt6);
	  cell8.appendChild(txt7);
	  cell9.appendChild(txt8);
			 
	  tr.appendChild(cell1);
	  tr.appendChild(cell2);
	  tr.appendChild(cell3);
	  tr.appendChild(cell4);
	  tr.appendChild(cell5);
	  tr.appendChild(cell6);
	   
	  tr.appendChild(cell7); 
	  tr.appendChild(cell8); 
	  tr.appendChild(cell9); 
	  
	  bgclass=(bgclass=='row0'? 'row1' : 'row0');
	  tr.className=bgclass;
	  
	  tbody.appendChild(tr); 
	  var len= document.getElementById('paymentType').options.length;
	  var t=document.getElementById('paymentType');
	  if(len>0) {
		var tt='paymentTypeId'+parseInt(start+i,10) ;
		for(k=0;k<len;k++) { 
		  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
		 }
	  }

	  var len= document.getElementById('issuingBank').options.length;
	  var t=document.getElementById('issuingBank');
	  if(len>0) {
		var tt='issuingBankId'+parseInt(start+i,10) ;
		for(k=0;k<len;k++) { 
		  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
		 }
	  }

	  var len= document.getElementById('paymentStatus').options.length;
	  var t=document.getElementById('paymentStatus');
	  if(len>0) {
		var tt='paymentStatusId'+parseInt(start+i,10) ;
		for(k=0;k<len;k++) { 
		  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
		 }
	  }

	  var len= document.getElementById('receiptStatus').options.length;
	  var t=document.getElementById('receiptStatus');
	  if(len>0) {
		var tt='receiptStatusId'+parseInt(start+i,10) ;
		for(k=0;k<len;k++) { 
		  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
		 }
	  }
	  
  } 
  tbl.appendChild(tbody);   
  //document.feeForm.rowCnt.value=parseInt(document.feeForm.rowCnt.value)+parseInt(rowCnt);
}

function getAmountPaid(ctr){

	var instTotal=0;
	for(var i=0;i<resourceAddCnt;i++){

		try{  

			instTotal += parseFloat(document.getElementById('amtId'+(i+1)).value); 
		}
		catch(e){

		}
	}
	//alert(instTotal);

	if((document.feeForm.cashAmount.value)==''){
		
		totalAmountPaid = (parseFloat(instTotal));
	}
	else{
	
		totalAmountPaid = (parseFloat(document.feeForm.cashAmount.value)+parseFloat(instTotal));
	}
	if(totalAmountPaid>0){
	
		document.feeForm.paidAmount.value= (parseFloat(totalAmountPaid)).toFixed(2);
	}
}
function validateForm(frm, act) {
	
    var instTotal=0;
    for(var i=0;i<resourceAddCnt;i++){
      
	  try{  
         
		 if(document.getElementById('instId'+(i+1)).value==''){
			
			messageBox("<?php echo INSTRUMENT_NUMBER_MISSING; ?>");
			//eval("document.getElementById('marks"+cnt+"').className = 'inputboxRed'");
            document.getElementById('instId'+(i+1)).className= 'inputboxRed';
			document.getElementById('instId'+(i+1)).focus();
            return false;
         }
		 if(document.getElementById('amtId'+(i+1)).value==''){
			
			messageBox("<?php echo AMOUNT_MISSING; ?>");
			document.getElementById('amtId'+(i+1)).className= 'inputboxRed';
            document.getElementById('amtId'+(i+1)).focus();
            return false;
         }
		 if(document.getElementById('issuingBankId'+(i+1)).value==''){
			
			messageBox("<?php echo ISSUING_BANK_MISSING; ?>");
            document.getElementById('issuingBankId'+(i+1)).className= 'inputboxRed';
			document.getElementById('issuingBankId'+(i+1)).focus();
            return false;
         }
         instTotal += parseFloat(document.getElementById('amtId'+(i+1)).value); 

      }
      catch(e){}
    }
	var cashAmt;
	cashAmt=document.feeForm.cashAmount.value;
	if(cashAmt==''){
	
		cashAmt=0;
	}
	instTotal += parseFloat(cashAmt);
	//alert(instTotal);
	//return false;
	if(instTotal==0){
			
		messageBox("<?php echo AMOUNT_PAID_MISSING; ?>");
		document.getElementById('cashAmount').focus();
		return false;
	 }
	
	var fieldsArray = new Array(new Array("receiptNumber","<?php echo STUDENT_RECEIPT_NUMBER?>"),new Array("feeType","<?php echo STUDENT_FEE_TYPE?>"),new Array("studentRoll","<?php echo STUDENT_FEES_ROLL?>"),new Array("feeCycle","<?php echo STUDENT_FEES_CYCLE?>"),new Array("feeStudyPeriod","<?php echo STUDENT_FEES_PERIOD?>"),new Array("paidAmount","<?php echo STUDENT_FEES_PAID?>") );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {

		if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
		}
		if(fieldsArray[i][0]=="paidAmount"){
			
			if(isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value"))){  //if city is not selected

                 messageBox("<?php echo STUDENT_AMOUNT_NUMERIC?>");
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
            }
		}
	}
		 
	/*if((document.feeForm.paymentMode[1].checked) || (document.feeForm.paymentMode[2].checked)){

		if(isEmpty(document.feeForm.chequeNumber.value)){

			messageBox("<?php echo STUDENT_FEES_CHEQUE?>");
			document.feeForm.chequeNumber.focus();
			return false;
		}
		 
		
		if(isEmpty(document.feeForm.issuingBank.value)){

			messageBox("<?php echo STUDENT_FEES_BANK?>");
			document.feeForm.issuingBank.focus();
			return false;
		}
		if(isEmpty(document.feeForm.payableBank.value)){

			messageBox("<?php echo STUDENT_FEES_PAYABLE_BANK?>");
			document.feeForm.payableBank.focus();
			return false;
		}
		if(isEmpty(document.feeForm.favouringBank.value)){

			messageBox("<?php echo STUDENT_FEES_FAVOUR?>");
			document.feeForm.favouringBank.focus();
			return false;
		}
		if(isEmpty(document.feeForm.issuingDate.value)){

			messageBox("<?php echo STUDENT_FEES_ISSUE_DATE?>");
			document.feeForm.issuingDate.focus();
			return false;
		}
	}else{
	
		if(isEmpty(document.feeForm.payableBank.value)){

			messageBox("<?php echo STUDENT_FEES_PAYABLE_BANK?>");
			document.feeForm.payableBank.focus();
			return false;
		}
		if(isEmpty(document.feeForm.favouringBank.value)){

			messageBox("<?php echo STUDENT_FEES_FAVOUR?>");
			document.feeForm.favouringBank.focus();
			return false;
		}
	}*/
	if(!isEmpty(document.feeForm.studentFine.value)){

		studentFine = document.feeForm.studentFine.value;
		len1 = studentFine.length;
		var count1 = 0;
		for(i=0;i<len1;i++){

		   reg = new RegExp("^[-+]{0,1}[0-9]*[.]{0,1}[0-9]*$");
		   count1 = 0;
		   if (!reg.test(studentFine)){
			  
			  count1 ++;
		   }
		}
		if(count1>0){

			messageBox("<?php echo STUDENT_CORRECT_FINE?>");
			count1 = 0;
			document.feeForm.studentFine.value="0.00";
			document.feeForm.studentFine.focus();
			return false;
		}
	}
	if(isEmpty(document.feeForm.paidAmount.value)){

		messageBox("<?php echo STUDENT_FEES_PAID?>");
		document.feeForm.paidAmount.focus();
		return false;
	}
	else if((trim(document.feeForm.paidAmount.value)=="0.00") || (trim(document.feeForm.paidAmount.value)=="0")){

		messageBox("<?php echo STUDENT_FEES_PAID?>");
		document.feeForm.paidAmount.focus();
		return false;
	}
	else{

		payAmount = document.feeForm.paidAmount.value;
		len = payAmount.length;
		for(i=0;i<len;i++){

		   reg = new RegExp("^[-+]{0,1}[0-9]*[.]{0,1}[0-9]*$");
		   count = 0;
		   if (!reg.test(payAmount)){

			  count ++;
		   }
		}
		 if(count>0){

			  messageBox("<?php echo STUDENT_CORRECT_PAID?>");
			  document.feeForm.paidAmount.value="0.00";
			  document.feeForm.paidAmount.focus();
			  return false;
		 }
	}
	  
	if(parseInt(document.feeForm.paidAmount.value)>parseInt(document.feeForm.netAmount.value)){
 
		var agree=confirm("<?php echo STUDENT_FEES_CONFIRM?>");
		if (agree){
			
			addStudentFees(act);
			return false;
		}
		else
			return false ;
	}
	else{
		
		if(document.feeForm.studentId.value!=''){

			addStudentFees(act);
		}
		else{

			 messageBox("<?php echo STUDENT_CORRECT_ROLL?>");
			 document.feeForm.studentRoll.focus();
			 return false;
		}
		/*if(act=="Print"){

			addStudentFees();
			printReport(abc);
			 
		} */
		return false;
	}
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/studentFeesContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: feeReceipt.php $
//
//*****************  Version 18  *****************
//User: Rajeev       Date: 10-03-29   Time: 10:33a
//Updated in $/LeapCC/Interface
//removed reload of page after submit 
//
//*****************  Version 17  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Interface
//updated with all the fees enhancements
//
//*****************  Version 16  *****************
//User: Rajeev       Date: 10-03-08   Time: 11:20a
//Updated in $/LeapCC/Interface
//updated issue received during CIET
//
//*****************  Version 15  *****************
//User: Rajeev       Date: 10-02-15   Time: 4:02p
//Updated in $/LeapCC/Interface
//resolved bug no 2874. Collect Fees 
//
//*****************  Version 14  *****************
//User: Rajeev       Date: 10-02-11   Time: 6:10p
//Updated in $/LeapCC/Interface
//update bug no 0002150
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 09-11-25   Time: 4:17p
//Updated in $/LeapCC/Interface
//Fixed 2126,2127,2128,2129,2130,
//2131,2132,2133,2134,2135
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 09-11-25   Time: 1:43p
//Updated in $/LeapCC/Interface
//fixed bug no 2126,2127,2128,2129,2130,2131,2132,2133,2134,2135
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 09-11-21   Time: 3:52p
//Updated in $/LeapCC/Interface
//Added Student search,receipt no manual and fee type functionality in
//collect fees
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 09-11-11   Time: 6:27p
//Updated in $/LeapCC/Interface
//Added issue and payable bank id as per new requirement
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface
//added code for autosuggest functionality
//
//*****************  Version 8  *****************
//User: Gurkeerat    Date: 10/28/09   Time: 1:09p
//Updated in $/LeapCC/Interface
//added script for auto suggest
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/04/09    Time: 12:46p
//Updated in $/LeapCC/Interface
//Gurkeerat: corrected title of page
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 09-09-02   Time: 3:03p
//Updated in $/LeapCC/Interface
//Updated with config parameter which has been removed from
//common.inc.php
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/07/09    Time: 6:27p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/11/09    Time: 11:17a
//Updated in $/LeapCC/Interface
//updated fee receipt error for variable mismatch
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 1/12/09    Time: 5:30p
//Updated in $/LeapCC/Interface
//Updated with Required field, centralized message, left align
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 17  *****************
//User: Rajeev       Date: 9/10/08    Time: 2:05p
//Updated in $/Leap/Source/Interface
//updated tab order
//
//*****************  Version 14  *****************
//User: Rajeev       Date: 9/08/08    Time: 3:36p
//Updated in $/Leap/Source/Interface
//updated formatting
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 9/04/08    Time: 6:30p
//Updated in $/Leap/Source/Interface
//updated fixes for student fees receipt
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 9/01/08    Time: 4:04p
//Updated in $/Leap/Source/Interface
//updated fees concept by making use of previousDues and
//previousOverPayment 
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 8/27/08    Time: 2:13p
//Updated in $/Leap/Source/Interface
//updated fee module
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 8/23/08    Time: 12:00p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 8/18/08    Time: 7:35p
//Updated in $/Leap/Source/Interface
//updated formatting
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 8/13/08    Time: 6:35p
//Updated in $/Leap/Source/Interface
//updated fees receipt issues
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 8/05/08    Time: 6:30p
//Updated in $/Leap/Source/Interface
//remove all the demo issues
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 7/31/08    Time: 4:31p
//Updated in $/Leap/Source/Interface
//optimized the queries
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 7/24/08    Time: 6:36p
//Updated in $/Leap/Source/Interface
//updated the validations
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/24/08    Time: 2:42p
//Updated in $/Leap/Source/Interface
//updated receipt number field
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/24/08    Time: 12:36p
//Updated in $/Leap/Source/Interface
//completed the fee receipt functionality
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/17/08    Time: 6:53p
//Created in $/Leap/Source/Interface
//intial checkin
?>