<?php
//-------------------------------------------------------
// Purpose: To generate student fee receipt functionality
// Author : Nishu Bindal
// Created on : (14.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CollectFeesNew');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
global $sessionHandler;
$studentFeeReceiptdate = $sessionHandler->getSessionVariable('FEE_RECEIPT_DATE');
if($studentFeeReceiptdate==''){
  $studentFeeReceiptdate = date('Y-m-d');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Collect Fees New</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
//echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
recordsPerPageTeacher = <?php echo RECORDS_PER_PAGE_TEACHER;?>;
var topPos = 0;
var leftPos = 0;
var negativeAmount =0;
var negativeDebitAmount =0;
var prevAllClassAmount =0;

var globalPaidAt=0;

</script>
<?php
//echo UtilityManager::javaScriptFile2();
?>
<script language="javascript">
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

var resourceAddCnt=0;

var dtArray=new Array();  


function checkValue(fee,id){
		if(!isEmpty(fee)){
			if(!isDecimal(fee)){
				form= document.feeForm;
				alert("Fee Should Be Numeric.");
				eval("document.getElementById('"+id+"').focus()");
				return false;
			}
		}
	}
//----------------------------------------------------------------------
//Author:Nishu Bindal
//Purpose:validate the to check correct value
//Date:17.7.2008
//------------------------------------------------------------------------
function isCorrectValue(val,id,msg){

	if(isInteger(val)){
		
		return 1;
	}
	else{
		alert(msg);
		eval("document.getElementById('"+id+"').focus()");
		return 0;
	}
}


//----------------------------------------------------------------------
//Author:Nishu Bindal
//Purpose:to Cash fee
//Date:17.7.2008
//------------------------------------------------------------------------
function receiveCashFee() {
  
   try {
       var cash = document.feeForm.paidAmount.value;
       if(!isDecimal(document.feeForm.paidAmount.value)) {
         cash=0;
       }
       document.feeForm.cashAmount.value = cash;
   }
   catch(e){
     document.feeForm.cashAmount.value = 0;
   }
}                   

//for help module
function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');   
      return false;
    }
     if(document.getElementById('helpChk').checked == false) {
         return false;
     }
    //document.getElementById('divHelpInfo').innerHTML=title;      
    document.getElementById('helpInfo').innerHTML= msg;   
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);
    
    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}

//----------------------------------------------------------------------
//Author:Nishu Bindal
//Purpose:hide/show elements based on cash and cheque/draft
//Date:17.7.2008
//------------------------------------------------------------------------
function formdisable(selectedValue){
	if(selectedValue!=1){
		document.feeForm.chequeNumber.value="";
		document.feeForm.issuingBank.value="";
		document.feeForm.favouringBank.value="";
		document.feeForm.issuingDate.value="";
		document.getElementById('cashpay').style.display='';
		document.getElementById('instStatus').style.display='';
		document.getElementById('reStatus').style.display='';
		document.getElementById('paymentStatus').selectedIndex=1;
		document.getElementById('receiptStatus').selectedIndex=1;
		document.feeForm.chequeNumber.focus();
	}
	else{
		document.getElementById('cashpay').style.display='none';
		document.getElementById('instStatus').style.display='none';
		document.getElementById('reStatus').style.display='none';
		document.getElementById('paymentStatus').selectedIndex=3;
		document.getElementById('receiptStatus').selectedIndex=2;
	}
}

function getPaidAtDetails() {
	var paidValue =0;
	if(document.feeForm.paid3.checked){
	  paidValue = 1;
	}
    else if(document.feeForm.paid2.checked){
	  paidValue = 2;
	}
    else {
	  paidValue = 0;
	}
    globalPaidAt = paidValue;
    
	if(paidValue==2){
	// account desk
	  document.getElementById('trPaid').style.display='none';
   	  document.getElementById('trRow2').style.display='none';	
 	  document.getElementById('paidAt').selectedIndex =1;
	  document.getElementById('paidEntry').innerHTML ='Receipt No.';
	  document.getElementById('paidStatus').innerHTML ='On Account Desk';	
	}
    else if(paidValue==1){
	  //bank
	  document.getElementById('trPaid').style.display='none';
	  document.getElementById('trRow2').style.display='';	
	  document.getElementById('paidAt').selectedIndex =2;
	  document.getElementById('paidStatus').innerHTML ='Bank';
      document.getElementById('paidEntry').innerHTML ='Bank Scroll No.'; 
	}
	else{
	  document.getElementById('trPaid').style.display='none';
	  document.getElementById('trRow2').style.display='none';
	  document.getElementById('paidStatus').innerHTML ='';	
	}
	getReceiptOn();
}


function showStudentDetails(dv,w,h) {
	var left = 150;
	var top = 100;
	displayFloatingDiv(dv,'',w,h,left,top);
	document.getElementById(dv).style.top='40px';
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
     resourceAddCnt= resourceAddCnt -1 ; 
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
             }
          }
          resourceAddCnt=0;  
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
	  
	  cell1.setAttribute('align','left'); 
	  cell1.setAttribute('style','padding: 0px 0px 0px 0px');     
	  cell2.setAttribute('align','left'); 
	  cell2.setAttribute('style','padding: 0px 0px 0px 10px');
	  cell3.setAttribute('align','right');
	  cell3.setAttribute('style','padding: 0px 10px 0px 0px'); 
	  cell4.setAttribute('align','right'); 
	  cell4.setAttribute('style','padding: 0px 10px 0px 0px'); 
	  cell5.setAttribute('align','left');
	  cell5.setAttribute('style','padding: 0px 0px 0px 10px'); 

	cell6.setAttribute('align','center');
	cell7.setAttribute('align','center'); 
      
	  if(start==0){
		var txt0=document.createTextNode(start+i+1);
	  }
	  else{
		var txt0=document.createTextNode(start+i);
	  }
      
      var idStore=document.createElement('input');   
      
      
	  var txt1=document.createElement('select');
	  var txt2=document.createElement('input');
      	  var txt3=document.createElement('input');
	  var txt4=document.createElement('select');
	  var txt5=document.createElement('select');
	  var txt6=document.createElement('a');
      
	 
     // To store table ids 
      idStore.setAttribute('type','hidden'); 
      idStore.setAttribute('name','idNos[]'); 
      idStore.setAttribute('value',parseInt(start+i,10));
     
      txt1.setAttribute('id','paymentTypeId'+parseInt(start+i,10));
	  txt1.setAttribute('name','paymentTypeId[]');
	  thisCtr = parseInt(start+i,10);
	  txt1.className='htmlElement';

	  txt2.setAttribute('id','number'+parseInt(start+i,10));
	  txt2.setAttribute('name','number[]');
	  txt2.className='inputbox';
	  txt2.style.width='50px';

	  txt3.setAttribute('id','amount'+parseInt(start+i,10));
	  txt3.setAttribute('name','amount[]'); 
	  txt3.className='inputbox';
	  txt3.style.width='50px';
	  txt3.value='0';
	  txt4.setAttribute('id','issuingBankId'+parseInt(start+i,10));
	  txt4.setAttribute('name','issuingBankId[]');
	  txt4.className='htmlElement';
          
      
	  txt6.setAttribute('id','rd');
	  txt6.className='htmlElement';  
	  txt6.setAttribute('title','Delete');       
	  
	  txt6.innerHTML='X';
	  txt6.style.cursor='pointer';
	  

	  txt6.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff    
	  
	  cell1.appendChild(txt0);
	  cell1.appendChild(idStore);
	  cell2.appendChild(txt1);
	  cell3.appendChild(txt2);
	  cell4.appendChild(txt3);
	  cell5.appendChild(txt4);   
	  cell6.innerHTML='<input type="text" id="dated'+parseInt(start+i,10)+'" name="dated[]" class="inputBox" readonly="true" value="'+serverDate+'" size="8" />';
	  cell6.innerHTML +="<input type=\"image\" id=\"calImg\" name=\"calImg\" title=\"Select Date\" src=\""+imagePathURL+"/calendar.gif\"  onClick=\"return showCalendar('dated"+parseInt(start+i,10)+"','%Y-%m-%d', '24', true);\">";
	  cell7.appendChild(txt6);
			 
	  tr.appendChild(cell1);
	  tr.appendChild(cell2);
	  tr.appendChild(cell3);
	  tr.appendChild(cell4);
	  tr.appendChild(cell5);
	  tr.appendChild(cell6);
	  tr.appendChild(cell7); 
	  
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
  } 
  tbl.appendChild(tbody);
}



window.onload = function () {
  resetForm('all');
  document.getElementById('paidAt').value='';
  getLastEntry();
  return false;
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO Search Student
//Author : Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

function getData(){

	if((document.listForm.studentClass.value)==''){
		messageBox("Please select class");
        	document.listForm.studentClass.focus();
		return false;
	}
	 
	if(!isAlphaNumericCustom(trim(document.listForm.studentName.value),".,& ()") ){
		messageBox("Please correct value");
        	document.listForm.studentName.focus();
		return false;
	}
	
	url1 = '<?php echo HTTP_LIB_PATH;?>/Fee/CollectFees/getStudentList.php';
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
	sendRequest(url1, listObj1, '',false);
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO Fill student roll no
//Author : Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function fillStudent(rollNoRegNo){ 
	hiddenFloatingDiv('getStudentDetail');
	document.getElementById('rollNoRegNo').value=rollNoRegNo;
	resetForm('receipt');
	getStudentClasses();
    	return false;
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET Classes 
//
//Author : Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getStudentClasses() { 
	form = document.feeForm;
	 if(trim(document.getElementById('rollNoRegNo').value)==''){
	       form.classId.length = null;
	       addOption(form.classId, '', 'Select');
	       messageBox("<?php echo ENTER_NAME_ROLLNO;?>");
	       document.getElementById('rollNoRegNo').focus();
	       return false;
	  }
	  
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/CollectFees/getClasses.php';
	new Ajax.Request(url,
	{
		method:'post',
		parameters: {	rollNoRegNo: trim(document.getElementById('rollNoRegNo').value)
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
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}
 //-------------------------------------------------------
//THIS FUNCTION IS USED TO GET Last Receipt No
//Author : Nishu Bindal
// Created on : (10.May.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getLastEntry() {
    var url = '<?php echo HTTP_LIB_PATH;?>/Fee/CollectFees/ajaxGetLastEntry.php';  
    //cument.getElementById('trLastEntry').style.display='none';    

    document.getElementById('lastEntry').innerHTML='';     
  
    new Ajax.Request(url,
    {
         method:'post',
         asynchronous:false,
         onCreate: function() {
             showWaitDialog(true); 
         },
         onSuccess: function(transport){
           hideWaitDialog(true);
           if(trim(transport.responseText)==false) {
              document.getElementById('trLastEntry').style.display='none';    
              messageBox("<?php echo "No Data Found"; ?>");  
           }
           else {
               	var ret=trim(transport.responseText).split('!~~!');
             	document.getElementById('lastEntry').innerHTML  = ret[0];
              	document.getElementById('receiptDate').value = ret[1];
              	//setSelectedIndex(document.getElementById('paidAt'), ret[2]); 
                printReport(ret[3]);  
           }
         },
         onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
    }); 
}
 //-------------------------------------------------------
//THIS FUNCTION IS USED TO Calculate Payable Amount
//Author : Nishu Bindal
// Created on : (10.May.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function claculatePayableAmount(){
	var payableFees = 0;
	 if(((document.getElementById('hostelId').value != 0) && (document.getElementById('roomId').value != 0)) && (document.getElementById('feePaymentMode').value == 4 || document.getElementById('feePaymentMode').value == 3)){
			    if(document.getElementById('appliedHostelFine').value != ''){
			    	if(isDecimal(trim(document.getElementById('appliedHostelFine').value))) {
			 		 payableFees = parseFloat(eval("document.getElementById('appliedHostelFine').value"),2); 
				 }
				 else{
				 	messageBox ("Enter numeric value for Hostel Fine");
		 			document.getElementById('appliedHostelFine').className='inputboxRed';   
		 			document.getElementById('appliedHostelFine').focus();
		 			return false;
				 }
			    }
		    }
		
		   if(((document.getElementById('busStopId').value != 0) && (document.getElementById('busRouteId').value != 0)) && (document.getElementById('feePaymentMode').value == 4 || document.getElementById('feePaymentMode').value == 2)){
		     if(document.getElementById('appliedTransportFine').value != ''){
			    	if(isDecimal(trim(document.getElementById('appliedTransportFine').value))) { 
			 		 payableFees = payableFees + parseFloat(eval("document.getElementById('appliedTransportFine').value"),2); 
				 }
				 else{
				 	messageBox ("Enter numeric value for Transport Fine");
		 			document.getElementById('appliedTransportFine').className='inputboxRed';   
		 			document.getElementById('appliedTransportFine').focus();
		 			return false;
				 }
		    } 
		}
		
		if(document.getElementById('feePaymentMode').value == 4 || document.getElementById('feePaymentMode').value == 1){
		     if(document.getElementById('appliedFine').value != ''){
			    	if(isDecimal(trim(document.getElementById('appliedFine').value))) {
			 		 payableFees = payableFees + parseFloat(eval("document.getElementById('appliedFine').value"),2); 
				 }
				 else{
				 	messageBox ("Enter numeric value for Fine");
		 			document.getElementById('appliedFine').className='inputboxRed';   
		 			document.getElementById('appliedFine').focus();
		 			return false;
				 }
		    }
		}
		var obj=form.getElementsByTagName('INPUT');
		var total=obj.length;
		for(var i=0;i<total;i++) {
			if(obj[i].type.toUpperCase()=='TEXT' && obj[i].name.indexOf('miscHeadAmt[]')>-1){
				// Blank value of misc charges 
			   	if(trim(obj[i].value)!='') {                          
			       	// Integer Value Checks 
			       		if(!isDecimal(trim(obj[i].value))) {                          
				 		messageBox ("Enter numeric value of Amount");
				 		obj[i].className='inputboxRed';   
				 		obj[i].focus();
				 		return false;
			       		}
			       payableFees=payableFees+parseFloat(obj[i].value,2); 
			   }
			}
		}
		
		document.getElementById('payableSpan').innerHTML=  parseFloat(payableFees) + parseFloat(trim(document.getElementById('netPayable').value));
		

}

// function to select value from dropdown
function setSelectedIndex(s, valsearch)
{ 
	// Loop through all the items in drop down list
	for (i = 0; i< s.options.length; i++){
		if(s.options[i].value == valsearch){
			// Item is found. Set its property and exit
			s.options[i].selected = true;
			break;
		}
	}
}

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to getStudent details & Fee Details
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------- 
		function getStudentDetails(){
            
            negativeAmount=0;
		negativeDebitAmount =0;
			prevAllClassAmount =0;
			
            document.getElementById('addMore').style.display='';
			if(trim(document.getElementById('rollNoRegNo').value)=='') {
				messageBox("<?php echo "Enter Roll No./ Reg No."; ?>"); 
				eval("document.getElementById('rollNoRegNo').className='inputboxRed'"); 
				document.getElementById('rollNoRegNo').focus();
				return false; 
			}
			if(trim(document.getElementById('classId').value)=='') {
				messageBox("<?php echo "Select Class Name"; ?>"); 
				eval("document.getElementById('classId').className='inputboxRed'"); 
				document.getElementById('classId').focus();
				return false; 
			}
			if(trim(document.getElementById('feePaymentMode').value)=='') {
				messageBox("<?php echo "Select Pay Fee Of"; ?>"); 
				eval("document.getElementById('feePaymentMode').className='inputboxRed'"); 
				document.getElementById('feePaymentMode').focus();
				return false; 
			}


			/*if(trim(document.getElementById('paidAt').value)=='') {
				messageBox("<?php echo "Select Paid At"; ?>"); 
				eval("document.getElementById('paidAt').className='inputboxRed'"); 
				document.getElementById('paidAt').focus();
				return false; 
			}*/	
			document.getElementById('feeDiv1').style.display='';
			//document.getElementById('paymentData').style.display='';
			var frm = document.feeForm;
			var url = '<?php echo HTTP_LIB_PATH;?>/Fee/CollectFees/ajaxStudentFeeValue.php';

			//if(document.getElementById('receiptNumber').value!=''){
				new Ajax.Request(url,
				{
					method:'post',
					parameters: {
					classId: trim(document.getElementById('classId').value),
					rollNoRegNo : trim(document.getElementById('rollNoRegNo').value),
					feePaymentMode : trim(document.getElementById('feePaymentMode').value)
							
				},
				onCreate:function(transport){ showWaitDialog(true);},
				onSuccess: function(transport) {
					if(trim(transport.responseText) == "Please Insert Receipt Number."){
						messageBox(transport.responseText);
					}
					if(trim(transport.responseText) == "Fees Not Found for this class."){
						messageBox(transport.responseText);
					}
					hideWaitDialog(true);
					document.getElementById('feeDiv').style.display='none';
					var j= trim(transport.responseText).evalJSON(); 
					
					var feeHeadArray = 	new Array(new Array('srNo','#','width="3%"',''), 
								new Array('headName','Head Name','width="20%"',''),
								new Array('amount','Amount','width="20%"',' align="right"'),
								new Array('applAmt','Amount Paid','width="20%"',' align="right"') 
								);

					printResultsNoSorting('feeDiv1', j.feeInfo, feeHeadArray);
					
					document.getElementById('paidFeeData').innerHTML = trim(j.payFeeTotalData);
					var previousAmount = trim(j.payFeeTotalData);
                    negativeAmount = trim(j.negativeLedgerAmount);    
                    negativeDebitAmount = trim(j.negativeDebitLedgerAmount);
			         prevAllClassAmount =trim(j.prevAllClassAmount);
			         
			         
					document.getElementById('installmentNo').value = trim(j.installmentNo);
					document.getElementById('hiddenFeeCycleId').value = trim(j.feeCycleId);
					document.getElementById('studentName').innerHTML = trim(j.studentinfo[0].studentName);
					document.getElementById('fatherName').innerHTML = trim(j.studentinfo[0].fatherName);
					document.getElementById('regNo').innerHTML = trim(j.studentinfo[0].regNo);
					document.getElementById('className').innerHTML = trim(j.studentinfo[0].className);
				    	document.getElementById('studentId').value = trim(j.studentinfo[0].studentId);
				     	document.getElementById('receiptId').value = trim(j.studentinfo[0].feeReceiptId);
				     	document.getElementById('bankAccNo').innerHTML= trim(j.studentinfo[0].instituteBankAccountNo);
				     	document.getElementById('bankName').innerHTML= trim(j.studentinfo[0].bankName);
				     	document.getElementById('feeCycle').innerHTML = trim(j.feeCycleName);
				     	document.getElementById('rollNo').innerHTML = trim(j.rollNo);
				     	document.getElementById('hostelSecurityStatus').value = j.hostelSecurityStatus;
				 	document.getElementById('busStopId').value = trim(j.busStopId);
					document.getElementById('busRouteId').value = trim(j.busRouteId);
					document.getElementById('payableAmount').value = trim(j.payableAmount); 
					document.getElementById('payableSpan').innerHTML = trim(j.payableAmount);
					document.getElementById('netPayable').value = trim(j.payableAmount);
					 
				   
				     	if(!isEmpty(j.studentinfo[0].hostelId) && j.studentinfo[0].hostelId != 0){
				     		document.getElementById('hostelId').value = trim(j.studentinfo[0].hostelId);
				     		document.getElementById('roomId').value = trim(j.studentinfo[0].hostelRoomId);
				     	}
				     	else{
				     		document.getElementById('hostelId').value = 0;
				     		document.getElementById('roomId').value = 0;
				     	}
				     	
				},
				onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
				});
			/*}
			else
			{
			document.getElementById('resultsDiv').innerHTML="<table border='0' cellspacing='0' cellpadding='3' width='100%'><tr class='rowheading'><td valign='middle' width='3%'><B>#</B></td><td valign='middle' width='60%'><B>Fee Head</B></td><td valign='middle' width='15%'><B>Amount</B></td></tr><tr class='row0'><td valign='middle' colspan='4' align='center'>No detail found</td></tr></table>";
			}*/
		}
		
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to validate form before saving
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------- 
	function validateForm(frm, act) {
	
		if(act == 'OldPrint'){
			if(document.getElementById('bankScrollNo').value=='') {
				messageBox("<?php echo "Insert Bank Scroll No"; ?>"); 
				eval("document.getElementById('bankScrollNo').className='inputboxRed'");  
				document.getElementById('bankScrollNo').focus();
				return false;
			}
			printReport(document.getElementById('bankScrollNo').value);  
       			return false; 
		}
	
		if(trim(document.getElementById('rollNoRegNo').value)=='') {
				messageBox("<?php echo "Enter Roll No./ Reg No."; ?>"); 
				eval("document.getElementById('rollNoRegNo').className='inputboxRed'"); 
				document.getElementById('rollNoRegNo').focus();
				return false; 
		}
		if(trim(document.getElementById('classId').value)=='') {
			messageBox("<?php echo "Select Class Name"; ?>"); 
			eval("document.getElementById('classId').className='inputboxRed'"); 
			document.getElementById('classId').focus();
			return false; 
		}
		if(trim(document.getElementById('feePaymentMode').value)=='') {
			messageBox("<?php echo "Select Pay Fee Of"; ?>"); 
			eval("document.getElementById('feePaymentMode').className='inputboxRed'"); 
			document.getElementById('feePaymentMode').focus();
			return false; 
		}
		
		if(trim(document.getElementById('paidAt').value)=='') {
			messageBox("<?php echo "Select Paid At"; ?>"); 
			eval("document.getElementById('paidAt').className='inputboxRed'"); 
			document.getElementById('paidAt').focus();
			return false; 
		}
        if(trim(document.getElementById('paidAt').value)=='1') {
		    if(document.getElementById('bankScrollNo').value=='') {
			    messageBox("<?php echo "Insert Bank Scroll No"; ?>"); 
			    eval("document.getElementById('bankScrollNo').className='inputboxRed'");  
			    document.getElementById('bankScrollNo').focus();
			    return false;
		    }
        }
		if(!isEmpty(document.getElementById('installmentNo').value)){ 
		     if(!isDecimal(trim(document.getElementById('installmentNo').value))) {
		       messageBox ("Enter numeric value for Installment No.");
		       eval("document.getElementById('installmentNo').className='inputboxRed'"); 
		       eval("document.getElementById('installmentNo').focus()");
		       return false;
		     }
		     if(parseInt(document.getElementById('installmentNo').value) <= 0){
		     	messageBox ("Installment No. Should be Greater than 0.");
		       	eval("document.getElementById('installmentNo').className='inputboxRed'"); 
		       	eval("document.getElementById('installmentNo').focus()");
		       	return false;
		     }
		}
		if(!isEmpty(document.getElementById('cashAmount').value)){ 
		     if(!isDecimal(trim(document.getElementById('cashAmount').value))) {
		       messageBox ("Enter numeric value for Cash Amount");
		       eval("document.getElementById('cashAmount').className='inputboxRed'"); 
		       eval("document.getElementById('cashAmount').focus()");
		       return false;
		     }
		     if(parseInt(document.getElementById('cashAmount').value) <= 0){
		     	messageBox ("Cash Amount Should be Greater than 0.");
		       	eval("document.getElementById('cashAmount').className='inputboxRed'"); 
		       	eval("document.getElementById('cashAmount').focus()");
		       	return false;
		     }
		}
		if(document.getElementById('receiptDate').value == ''){
			messageBox("<?php echo "Select 	Receipt Date"; ?>"); 
			eval("document.getElementById('receiptDate').className='inputboxRed'");  
			eval("document.getElementById('receiptDate').focus()");
			return false; 
		}
		date = "<?php echo date('Y-m-d')?>"; 
		if(!dateDifference(eval("frm.receiptDate.value"),date,'-') ) {
			messageBox ("Receipt Date Can't be greater than current date.");
			eval("frm.visibleFromDate1.focus();");
			return false;
		}
		
		   var headTotal=0;
		   var cashTotal =0
		    var obj=form.getElementsByTagName('INPUT');
		    var total=obj.length;
		    for(var i=0;i<total;i++) {
			if(obj[i].type.toUpperCase()=='TEXT' && obj[i].name.indexOf('academicFee[]')>-1) {
			    // blank value check 
			   if(trim(obj[i].value)!='') {                          
			       // Integer Value Checks updated
			       if(!isDecimal(trim(obj[i].value))) {                          
				 messageBox ("Enter numeric value for Amount Paid");
				 obj[i].className='inputboxRed';   
				 obj[i].focus();
				 return false;
			       }
			       headTotal=headTotal+parseFloat(obj[i].value,2); 
			   }
			}
			else if(obj[i].type.toUpperCase()=='TEXT' && obj[i].name.indexOf('amount[]')>-1){
			   	// blank value check 
			   	if(trim(obj[i].value)!='') {                          
			       	// Integer Value Checks updated
			       		if(!isDecimal(trim(obj[i].value))) {                          
				 		messageBox ("Enter numeric value for Payment Details1");
				 		obj[i].className='inputboxRed';   
				 		obj[i].focus();
				 		return false;
			       		}
			       cashTotal=cashTotal+parseFloat(obj[i].value,2); 
			   }
			}
		    }
		   // Add Hostel security if exists
		   if(eval("document.getElementById('hostelSecurityStatus').value") == 1){
			    if(eval("document.getElementById('hostelSecurity').value") != ''){
			    	if(!isDecimal(eval("document.getElementById('hostelSecurity').value"))) {                          
			 		messageBox ("Enter numeric value for Amount Paid");
			 		document.getElementById('hostelSecurity').className='inputboxRed';   
			 		document.getElementById('hostelSecurity').focus();
			 		return false;
				 }
				 headTotal=headTotal + parseFloat(eval("document.getElementById('hostelSecurity').value"),2); 
			    }
		  }
		  if(((document.getElementById('hostelId').value != 0) && (document.getElementById('roomId').value != 0)) && (document.getElementById('feePaymentMode').value == 4 || document.getElementById('feePaymentMode').value == 3)){
			    
			    if(document.getElementById('hostelFees').value != ''){
			    	if(!isDecimal(trim(document.getElementById('hostelFees').value))) {                          
			 		messageBox ("Enter numeric value for Amount Paid");
			 		document.getElementById('hostelFees').className='inputboxRed';   
			 		document.getElementById('hostelFees').focus();
			 		return false;
				 }
				 headTotal=headTotal + parseFloat(eval("document.getElementById('hostelFees').value"),2); 
			    }
			    // for hostel fine
			    if(document.getElementById('hostelFinePaid').value != ''){
			    	if(isDecimal(trim(document.getElementById('hostelFinePaid').value))) {
			 		 headTotal=headTotal + parseFloat(eval("document.getElementById('hostelFinePaid').value"),2); 
				 }
				 else{

				 	messageBox ("Enter numeric value for Hostel Fine");
		 			document.getElementById('hostelFinePaid').className='inputboxRed';   
		 			document.getElementById('hostelFinePaid').focus();
		 			return false;
				 }
			    }
			 	if(document.getElementById('appliedHostelFine').value != '' && document.getElementById('hostelFinePaid').value != ''){
				      if(parseFloat(document.getElementById('appliedHostelFine').value) != parseFloat(document.getElementById('hostelFinePaid').value)){
			    			messageBox ("Hostel Fine Amount & Hostel Fine Paid can't be different.");
			 			document.getElementById('hostelFinePaid').className='inputboxRed';   
			 			document.getElementById('hostelFinePaid').focus();
			 			return false;
			    		}
			    	}
		    }
		    
		   if(((document.getElementById('busStopId').value != 0) && (document.getElementById('busRouteId').value != 0)) && (document.getElementById('feePaymentMode').value == 4 || document.getElementById('feePaymentMode').value == 2)){
		     if(document.getElementById('transportFees').value != ''){ 
		    	if(!isDecimal(trim(document.getElementById('transportFees').value))) {                          
		 		messageBox ("Enter numeric value for Amount Paid");
		 		document.getElementById('transportFees').className='inputboxRed';   
		 		document.getElementById('transportFees').focus();
		 		return false;
			 }
			 headTotal=headTotal + parseFloat(trim(eval("document.getElementById('transportFees').value")),2);
		    }
		    // transport fine
		      if(document.getElementById('transportFinePaid').value != ''){
			    	if(isDecimal(trim(document.getElementById('transportFinePaid').value))) {
			 		 headTotal=headTotal +  parseFloat(eval("document.getElementById('transportFinePaid').value"),2); 
				 }
				 else{
				 	messageBox ("Enter numeric value for Transport Fine");
		 			document.getElementById('transportFinePaid').className='inputboxRed';   
		 			document.getElementById('transportFinePaid').focus();
		 			return false;
				 }
		    }
		    if(document.getElementById('appliedTransportFine').value != '' && document.getElementById('transportFinePaid').value != ''){
		      	if(parseFloat(document.getElementById('appliedTransportFine').value) != parseFloat(document.getElementById('transportFinePaid').value)){
		    			messageBox ("Transport Fine Amount & Transport Fine Paid can't be different.");
		 			document.getElementById('transportFinePaid').className='inputboxRed';   
		 			document.getElementById('transportFinePaid').focus();
		 			return false;
		    	}
		    }
		    
		   }
		   
		   if(document.getElementById('feePaymentMode').value == 4 || document.getElementById('feePaymentMode').value == 1){
		     if(document.getElementById('finePaid').value != ''){
			    	if(isDecimal(trim(document.getElementById('finePaid').value))) {
			 		 headTotal=headTotal + parseFloat(eval("document.getElementById('finePaid').value"),2); 
				 }
				 else{
				 	messageBox ("Enter numeric value for Fine");
		 			document.getElementById('finePaid').className='inputboxRed';   
		 			document.getElementById('finePaid').focus();
		 			return false;
				 }
		    }
		    if(document.getElementById('appliedFine').value != '' && document.getElementById('finePaid').value != ''){
		     	if(parseFloat(document.getElementById('appliedFine').value) != parseFloat(document.getElementById('finePaid').value)){
		    		messageBox ("Fine Amount & Fine Amount Paid can't be Different.");
		 		document.getElementById('finePaid').className='inputboxRed';   
		 		document.getElementById('finePaid').focus();
		 		return false;
		    	}
		    }
		}
		
       
         
	 headTotal = parseInt(headTotal) + parseInt(negativeDebitAmount);
	 

	 headTotal = headTotal - negativeAmount;
		if(trim(document.getElementById('feePaymentMode').value)=='1' || trim(document.getElementById('feePaymentMode').value)=='4' ){
         headTotal = parseInt(headTotal) + parseInt(prevAllClassAmount);
        }
       
       
           
		if(document.getElementById('cashAmount').value != ''){
		      if(!isDecimal(eval("document.getElementById('cashAmount').value"))) {                          
		 		 messageBox ("Enter numeric value for Payment Details");
		 		 document.getElementById('cashAmount').className='inputboxRed';   
		 		 document.getElementById('cashAmount').focus();
		 		 return false;
			   }
			   cashTotal=cashTotal + parseFloat(eval("document.getElementById('cashAmount').value"),2);
		    }
		    
		   
		    if(headTotal != cashTotal){
		    	messageBox ("Fee Head Wise Amount ("+headTotal+") and Payment Detail ("+ parseFloat(cashTotal)+") mismatch.\n Difference (Head Wise Amount - Payment Detail) is of "+(headTotal - cashTotal ));
			 return false;
		   }
		  
		   if(cashTotal == 0){
		   	messageBox("Please Enter Playment Details");
		   	return false;
		   }  
		
		addStudentFees(act); 
		return false;
	}
		
		
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS used to add student fees
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------- 
function addStudentFees(act){
		var url = '<?php echo HTTP_LIB_PATH;?>/Fee/CollectFees/initAddFee.php';
		new Ajax.Request(url,
		{
			method:'post',
			parameters: $('feeForm').serialize(true),
			onCreate: function(){
		      showWaitDialog(true);
		},
		onSuccess: function(transport){
		  hideWaitDialog(true);
		  if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
		     flag = true;
		      messageBox(trim(transport.responseText));
		     if(act=="Print"){
               			printReport(document.getElementById('bankScrollNo').value);
             	      }
		     resetForm('all');
		     getLastEntry();          
		     return false;
		  }
		  else {
		     messageBox(trim(transport.responseText));              
		  }
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

//----------------------------------------------------------------------
//Author:Nishu Bindal
//Purpose:to print fees receipt
//Date:10.5.2012
//------------------------------------------------------------------------
function printReport(receiptNo) {
	path='<?php echo UI_HTTP_PATH;?>/Fee/paymentReceiptDetailPrint.php?receiptNo='+receiptNo;
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=750, height=600, top=100,left=150");
}


//---------------------------------------------------------------------------------------
// THIS FUNCTION IS TO RESET FORM 
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------- 
	function resetForm(mode){ 
		if(mode == 'all'){ 
			document.getElementById('rollNoRegNo').value='';
		}
		
		cleanUpTable();
		document.getElementById('rollNoRegNo').focus();
		document.getElementById('addRowDiv').innerHTML = '';
		document.getElementById('addMore').style.display='';
		document.getElementById('addRowDiv').style.display='';
		document.getElementById('studentName').innerHTML='---';
		document.getElementById('fatherName').innerHTML='---';
		document.getElementById('regNo').innerHTML='---';
		document.getElementById('className').innerHTML='---';
		document.getElementById('feeCycle').innerHTML='---';
		document.getElementById('feeClassId').value='';
		document.getElementById('studentId').value='';
		document.getElementById('receiptId').value='';
		document.getElementById('hostelId').value='';
		document.getElementById('roomId').value='';
		document.getElementById('busRouteId').value='';
		document.getElementById('busStopId').value='';
		document.getElementById('feeConcession').value='';
		document.getElementById('bankScrollNo').value='';
		document.getElementById('feeDiv1').innerHTML= "";
		document.getElementById('feeDiv1').style.display='none';
		document.getElementById('feeDiv').style.display='';
		
		document.getElementById('hideSave').style.display = '';
		document.getElementById('bankAccNo').innerHTML= "---";
		document.getElementById('rollNo').innerHTML= "---";
		document.getElementById('bankName').innerHTML= "---";
		document.getElementById('cashAmount').value='';
		document.getElementById('hiddenFeeCycleId').value='';
		document.getElementById('hostelSecurityStatus').value ='';
		document.getElementById('installmentNo').value ='';
		document.getElementById('paidFeeData').innerHTML ='';
		document.getElementById('feePaymentMode').selectedIndex =0;
        document.getElementById('paidAt').selectedIndex =0;
		document.getElementById('studentResult').innerHTML='';	
		document.getElementById('studentResult').style.display='none';
		document.getElementById('studentClass').selectedIndex= 0;
		document.getElementById('studentName').value ='';
		document.getElementById('deletedStudent').checked= false;	
        document.getElementById('payableSpan').innerHTML='';    
        
        if(globalPaidAt=='0') {
         document.getElementById('paid1').checked = true;
        }
        else if(globalPaidAt=='2') {
          document.getElementById('paid2').checked = true;
        }
        if(globalPaidAt=='1') {
          document.getElementById('paid3').checked = true;
        }
        getPaidAtDetails();
	}
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS TO Get the reason of delete 
// Author :Nishu Bindal
// Created on : (27.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------- 
	
	function getDeleteReason(){
		reason = prompt("Please Enter Reason For Delete", ""); 
		if(isEmpty(reason)){
	 		alert('Please Enter Reason for Delete.');
	 		getDeleteReason();
		 }
		 else{
		 	return reason;
		 }
	}

    function getReceiptOn() {
        
       if(document.getElementById('paidAt').value==2) {
         document.getElementById('trRow1').style.display='';       
         document.getElementById('trRow3').style.display='none';
         document.getElementById('receiptDate').value="<?php echo date('Y-m-d'); ?>";
       }
       else {
         document.getElementById('trRow1').style.display='none';
         document.getElementById('trRow3').style.display='';
       }
    }
    
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fee/CollectFees/studentFeesContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
