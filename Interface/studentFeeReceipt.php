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
var topPos = 0;
var leftPos = 0;

</script>
<?php
 //echo UtilityManager::javaScriptFile2();
?>
<script language="javascript">
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//----------------------------------------------------------------------
//Author:Rajeev Aggarwal
//Purpose:validate the data before insertion
//Date:17.7.2008
//------------------------------------------------------------------------
var resourceAddCnt=0;

var dtArray=new Array();  

//----------------------------------------------------------------------
//Author:Rajeev Aggarwal
//Purpose:To insert data
//Date:17.7.2008
//------------------------------------------------------------------------
function addStudentFees(act){
 
	var url = '<?php echo HTTP_LIB_PATH;?>/CollectFees/initAddFee.php';
    
	new Ajax.Request(url,
	{
		method:'post',
		parameters: $('#feeForm').serialize(true),
		onCreate: function(){
              showWaitDialog(true);
        },
        onSuccess: function(transport){
          hideWaitDialog(true);
          if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
             flag = true;
             messageBox(trim(transport.responseText));
             if(act=="Print"){
               printReport(document.getElementById('receiptNumber').value);
             }
             document.getElementById('feeForm').reset(); 
             resetFeeClass();  
             cleanUpTable();   
             populateValues(-1);
             getLastEntry();
             //document.getElementById('resultsDiv').innerHTML="";
             document.feeForm.receiptNumber.focus();
             return false;
          } 
          else if("<?php echo RECEIPT_ALREADY_EXIST;?>" == trim(transport.responseText)) {
             eval("document.getElementById('receiptNumber').className='inputboxRed'"); 
             messageBox(trim(transport.responseText));
             document.feeForm.receiptNumber.focus(); 
          }
          else if("<?php echo PAYMENT_DETAIL;?>" == trim(transport.responseText)) {
             eval("document.getElementById('cashAmount').className='inputboxRed'"); 
             messageBox(trim(transport.responseText));
             document.feeForm.cashAmount.focus(); 
          }
          else {
             messageBox(trim(transport.responseText));              
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

function resetFeeClass() {
    
    document.feeForm.studentId.value = "";
    document.feeForm.studentName.value = "";
    document.feeForm.studentLName.value = "";
    document.feeForm.hostelFacility.value =  ""; 
    document.feeForm.transportFacility.value =  ""; 
    document.feeForm.quotaId.value = "";
    document.feeForm.isLeet.value = ""; 
    document.feeForm.isConessionFormatId.value = ""; 
    
    document.getElementById('showBlueIndication').style.display='none'; 
    
    document.getElementById('resultsDiv').innerHTML="";
    document.getElementById('myClass').innerHTML="";
    document.getElementById('myFirst').innerHTML="";
    document.getElementById('myLast').innerHTML="";
    document.getElementById('myFather').innerHTML=""; 
    
    //document.getElementById('feeCycle').selectedIndex=0;
    document.getElementById('feeClassId').selectedIndex=0;
    
    document.getElementById('lblPreviousFees').innerHTML="";
    document.getElementById('lblPreviousPayment').innerHTML="";
    document.getElementById('lblTotalFees').innerHTML="";
    document.getElementById('lblTotalConcession').innerHTML="";
    document.getElementById('lblNetAmount').innerHTML="";
    document.getElementById('lblPreviousPaymentCurr').innerHTML="";
    document.getElementById('lblBalanceAmount').innerHTML="";
    document.getElementById('lblPreviousDues').innerHTML="";
    document.getElementById('lblFeeDues').innerHTML='';
    document.getElementById('currFeeDues').value='';
    
    document.getElementById('lblHostelCharges').innerHTML="";
    document.getElementById('lblHostelFine').innerHTML="";
    document.getElementById('lblHostelPaid').innerHTML="";
    document.getElementById('lblHostelDues').innerHTML="";
    
    document.getElementById('lblTransportCharges').innerHTML="";
    document.getElementById('lblTransportFine').innerHTML="";
    document.getElementById('lblTransportPaid').innerHTML="";
    document.getElementById('lblTransportDues').innerHTML="";
    document.getElementById('lblRegNo').innerHTML="";
    
    document.getElementById('myInstallment').innerHTML="";
    
    document.getElementById('tdFeeAmtPaid1').style.display="none";
    document.getElementById('tdFeeAmtPaid2').style.display="none";
    document.getElementById('tdFeeAmtPaid3').style.display="none";
    
    document.getElementById('tdTransportAmtPaid1').style.display="none";
    document.getElementById('tdTransportAmtPaid2').style.display="none";
    document.getElementById('tdTransportAmtPaid3').style.display="none";
    
    document.getElementById('tdHostelAmtPaid1').style.display="none";
    document.getElementById('tdHostelAmtPaid2').style.display="none";
    document.getElementById('tdHostelAmtPaid3').style.display="none";

   //document.getElementById('studentCurrentStatus').innerHTML='';
}

function populateValues(id) {
    
    var frm = document.feeForm;
    //var includePreviousDues = frm.includePreviousDues[0].checked==true?'0':'1'; 
    
    var includePreviousDues =0;
    if(includePreviousDues==0) {
      document.getElementById('chkPrevClassDues1').style.display='none';
      document.getElementById('chkPrevClassDues2').style.display='none';
      document.getElementById('chkPrevClassDues3').style.display='none';
      document.getElementById('chkPrevClassDues4').style.display='none';
    }
    
    document.getElementById('myClass').innerHTML="";
    document.getElementById('myFirst').innerHTML="";
    document.getElementById('myLast').innerHTML="";
    document.getElementById('myFather').innerHTML=""
    
    document.getElementById('lblPreviousFees').innerHTML="";
    document.getElementById('lblPreviousPayment').innerHTML="";
    document.getElementById('lblTotalFees').innerHTML="";
    document.getElementById('lblTotalConcession').innerHTML="";
    document.getElementById('lblNetAmount').innerHTML="";
    document.getElementById('lblPreviousPaymentCurr').innerHTML="";
    
    document.getElementById('lblFeeDues').innerHTML='';
    document.getElementById('currFeeDues').value='';

    document.getElementById('lblBalanceAmount').innerHTML="";
    document.getElementById('lblPreviousDues').innerHTML="";
    
    document.getElementById('tdFeeBalanceDetail').style.display='none'; 
    document.getElementById('tdHostelBalanceDetail').style.display='none'; 
    document.getElementById('tdTransportBalanceDetail').style.display='none'; 
    
    document.getElementById('lblRegNo').innerHTML="";
    
    for(i=1;i<=3;i++) {
      document.getElementById('tdFeeAmtPaid'+i).style.display='none'; 
      document.getElementById('tdHostelAmtPaid'+i).style.display='none'; 
      document.getElementById('tdTransportAmtPaid'+i).style.display='none'; 
    }
     
    //var url = '<?php echo HTTP_LIB_PATH;?>/CollectFees/ajaxStudentFeeDetailValue.php';
    var url = '<?php echo HTTP_LIB_PATH;?>/CollectFees/ajaxStudentFeeValue.php';
    
    if(document.getElementById('receiptDate').value!='' && document.getElementById('studentRoll').value!='' && document.getElementById('feeClassId').value!='' && document.getElementById('feeType').value!='')
    {
        new Ajax.Request(url,
        {
		    method:'post',
		    parameters: {receiptDate: (document.getElementById('receiptDate').value),
                         receiptNumber: (document.getElementById('receiptNumber').value),
                         feeTypeId: (document.getElementById('feeType').value), 
                         feeCycleId: (document.getElementById('feeCycle').value), 
                         feeClassId: (document.getElementById('feeClassId').value), 
                         rollNo: (document.getElementById('studentRoll').value),
                         includePreviousDues: includePreviousDues },
		    onCreate:function(transport){ showWaitDialog(true);},
		    onSuccess: function(transport) {
		        hideWaitDialog(true);
                if("<?php echo COLLECT_FEE_ID_NOT_EXIST;?>" == trim(transport.responseText)) { 
                   messageBox(trim(transport.responseText));  
                   document.getElementById('studentRoll').focus();
                   return false;
                }
                
                if("<?php echo COLLECT_FEE_CLASS;?>" == trim(transport.responseText)) { 
                   messageBox(trim(transport.responseText));  
                   document.getElementById('feeClassId').focus();
                   return false;
                }
                
                var j= trim(transport.responseText).evalJSON(); 
                
                var tbHeadArray = new Array(new Array('srNo','#','width="3%"',''), 
                                            new Array('headName','Head Name','width="83%"','') , 
                                            new Array('feeHeadAmt','Amount<br>Due','width="10%"',' align="right"'), 
                                            new Array('concession','Concession','width="4%"',' align="right"'),
                                            new Array('applAmt','Amount<br>Paid', 'width="4%"',' align="right"'));
                                            
		        printResultsNoSorting('resultsDiv', j.info, tbHeadArray);
                
                span1="<font color='red'>";
                span2="";
                ttStr = j.studentCurrentStatus;
                if(ttStr.search('(Active)')>0) {
                  span1="";
                  span2="";  
                }
                
                document.feeForm.isConessionFormatId.value = j.conessionFormatId; 
                document.feeForm.studentClass.value = j.studentinfo[0].classId;  
		        document.feeForm.studentId.value = j.studentinfo[0].studentId;
		        document.feeForm.studentName.value = j.studentinfo[0].studentName;
                document.feeForm.quotaId.value = j.studentinfo[0].quotaId;
                document.feeForm.isLeet.value = j.studentinfo[0].isLeet;
                document.feeForm.hostelFacility.value =  j.studentinfo[0].hostelFacility; 
                document.feeForm.transportFacility.value =  j.studentinfo[0].transportFacility; 
		        document.getElementById('myClass').innerHTML=span1+j.studentinfo[0].className+span2;;
		        document.getElementById('myFirst').innerHTML=span1+j.studentinfo[0].studentName+span2;;
		        document.getElementById('myFather').innerHTML=span1+j.studentinfo[0].fatherName+span2;;
                document.getElementById('lblRegNo').innerHTML=span1+j.studentinfo[0].regNo+span2;;   
                document.getElementById('studentCurrentStatus').innerHTML= span1+j.studentCurrentStatus+span2;
                
                document.getElementById('myInstallment').innerHTML=j.studentInstallmentCount;
                document.getElementById('installmentCount').value = j.studentInstallmentCount;
                
                document.feeForm.netAmount1.value = 0;
                document.feeForm.netAmount.value = 0;
                
                if(document.feeForm.hostelFacility.value==1) {  
                   document.getElementById('chkPrevClassHostelDues1').style.display='';
                   document.getElementById('chkPrevClassHostelDues2').style.display='';
                   document.getElementById('chkPrevClassHostelDues3').style.display='';
                   document.getElementById('chkPrevClassHostelDues4').style.display='';
                   document.getElementById('lblHostelCharges').innerHTML="";
                   document.getElementById('lblHostelFine').innerHTML="";
                   document.getElementById('lblHostelPaid').innerHTML="";
                   document.getElementById('lblHostelDues').innerHTML="";
                }
                if(document.feeForm.transportFacility.value==1) { 
                   document.getElementById('chkPrevClassTransportDues1').style.display='';
                   document.getElementById('chkPrevClassTransportDues2').style.display='';
                   document.getElementById('chkPrevClassTransportDues3').style.display='';  
                   document.getElementById('chkPrevClassTransportDues4').style.display='';
                   document.getElementById('lblTransportCharges').innerHTML="";
                   document.getElementById('lblTransportFine').innerHTML="";
                   document.getElementById('lblTransportPaid').innerHTML="";
                   document.getElementById('lblTransportDues').innerHTML="";
                }  
               
                document.feeForm.netAmount1.value = parseFloat(j.netAmount,2).toFixed(2);
                document.feeForm.netAmount.value = parseFloat(j.netAmount,2).toFixed(2);
                
                if(document.feeForm.feeType.value==4 || document.feeForm.feeType.value==1) {
                    for(i=1;i<=3;i++) {
                     document.getElementById('tdFeeAmtPaid'+i).style.display=''; 
                    }
                    document.feeForm.feeAmtPaid.value = '';   
                    
                    document.getElementById('showBlueIndication').style.display=''; 
                    
                    document.feeForm.feeAmtPaid.value = j.showTFeeAmtPaid;   
                    document.getElementById('tdFeeBalanceDetail').style.display='';  
                    document.feeForm.feeHeadDetailFind.value = j.feeHeadDetailFind;  
                    document.feeForm.previousFees.value = parseFloat(j.previousFees,2).toFixed(2);
                    document.feeForm.previousPayment.value = parseFloat(j.previousPayment,2).toFixed(2);
                    document.feeForm.totalFees.value = parseFloat(j.totalFees,2).toFixed(2);
                    //document.feeForm.studentFine.value = parseFloat(j.studentFine,2);
                    document.feeForm.totalConcession.value = parseFloat(j.totalConcession,2).toFixed(2);
		            document.feeForm.previousPaymentCurr.value = parseFloat(j.previousPaymentCurr,2).toFixed(2);
                    document.getElementById('lblPreviousFineCurr').innerHTML=j.previousFineCurr;
                    //document.feeForm.balanceAmount.value = j.netAmount-j.previousPaymentCurr;
                    document.feeForm.receivedFrom.value = j.studentinfo[0].studentName;
                    document.getElementById('lblPreviousFine').innerHTML=parseFloat(j.previousFine,2).toFixed(2);
                    document.getElementById('lblPreviousFees').innerHTML=parseFloat(j.previousFees,2).toFixed(2);
                    document.getElementById('lblPreviousPayment').innerHTML=parseFloat(j.previousPayment,2).toFixed(2);
                    
                    var prevDues = ( (parseFloat(j.previousFees,2)+parseFloat(j.previousFine,2)) - parseFloat(j.previousPayment,2)).toFixed(2);
                    document.getElementById('lblPreviousDues').innerHTML=prevDues; 
                    
                    document.getElementById('lblTotalFees').innerHTML=parseFloat(j.totalFees,2).toFixed(2);
                    document.getElementById('lblTotalConcession').innerHTML=parseFloat(j.totalConcession,2).toFixed(2);
                    //document.getElementById('lblNetAmount').innerHTML= parseFloat((j.netAmount-(j.previousPaymentCurr-j.previousFineCurr)),2).toFixed(2);  
                    document.getElementById('lblFeeDues').innerHTML=parseFloat(j.feeAmtPaidTotal,2).toFixed(2);
                    document.getElementById('currFeeDues').value=parseFloat(j.feeAmtPaidTotal,2).toFixed(2); 
                    
                    document.getElementById('lblPreviousPaymentCurr').innerHTML=parseFloat(j.previousPaymentCurr,2).toFixed(2);
                    document.getElementById('lblBalanceAmount').innerHTML= parseFloat((j.netAmount-(j.previousPaymentCurr-j.previousFineCurr)),2).toFixed(2);  
                }
                document.getElementById('lblNetAmount').innerHTML= document.feeForm.netAmount.value; 
                //alert("Net Amount: "+document.feeForm.netAmount.value);
                //return false;
            
                document.getElementById('tdTransportAmtPaid1').style.display="none";
                document.getElementById('tdTransportAmtPaid2').style.display="none";
                document.getElementById('tdTransportAmtPaid3').style.display="none";
                document.getElementById('transportAmtPaid').style.display="none";
                if((document.feeForm.feeType.value==4 || document.feeForm.feeType.value==2) && document.feeForm.transportFacility.value == 1) {
                   
                    document.getElementById('tdTransportAmtPaid1').style.display="";
                    document.getElementById('tdTransportAmtPaid2').style.display="";
                    document.getElementById('tdTransportAmtPaid3').style.display="";
                    document.getElementById('transportAmtPaid').style.display="";
                    
                    document.getElementById('tdTransportBalanceDetail').style.display='';  
                    document.getElementById('lblTransportCharges').innerHTML=parseFloat(j.prevTransportCharges,2).toFixed(2); 
                    document.getElementById('lblTransportFine').innerHTML=parseFloat(j.prevTransportFine,2).toFixed(2); 
                    document.getElementById('lblTransportPaid').innerHTML=parseFloat(j.prevTransportPaid,2).toFixed(2); 
                    document.getElementById('lblTransportDues').innerHTML=parseFloat(j.prevTransportDues,2).toFixed(2); 
                    //document.getElementById('lblNetAmount').innerHTML = parseFloat(j.netAmount,2).toFixed(2); 
                    document.getElementById('transportDue').value = j.prevTransportDues;   
                    document.getElementById('lblNetAmount').innerHTML= document.feeForm.netAmount.value;
                }
                
                document.getElementById('tdHostelAmtPaid1').style.display="none";
                document.getElementById('tdHostelAmtPaid2').style.display="none";
                document.getElementById('tdHostelAmtPaid3').style.display="none";
                document.getElementById('hostelAmtPaid').style.display="none";
                if((document.feeForm.feeType.value==4 || document.feeForm.feeType.value==3) && document.feeForm.hostelFacility.value == 1) {
                    
                    document.getElementById('tdHostelAmtPaid1').style.display="";
                    document.getElementById('tdHostelAmtPaid2').style.display="";
                    document.getElementById('tdHostelAmtPaid3').style.display="";
                    document.getElementById('hostelAmtPaid').style.display="";
                    
                    document.getElementById('tdHostelBalanceDetail').style.display='';  
                    document.getElementById('lblHostelCharges').innerHTML=parseFloat(j.prevHostelCharges,2).toFixed(2); 
                    document.getElementById('lblHostelFine').innerHTML=parseFloat(j.prevHostelFine,2).toFixed(2); 
                    document.getElementById('lblHostelPaid').innerHTML=parseFloat(j.prevHostelPaid,2).toFixed(2); 
                    document.getElementById('lblHostelDues').innerHTML=parseFloat(j.prevHostelDues,2).toFixed(2); 
                    //document.getElementById('lblNetAmount').innerHTML=parseFloat(j.netAmount,2).toFixed(2); 
                    document.getElementById('hostelDue').value = j.prevHostelDues;   
                    document.getElementById('lblNetAmount').innerHTML = document.feeForm.netAmount.value;
                }
                var netBalance = 0;
                if(document.feeForm.feeType.value==4 || document.feeForm.feeType.value == 1) {    // Only Academic
                   document.feeForm.feeAmtPaid.value = j.showTFeeAmtPaid; 
                   netBalance = parseFloat(netBalance) + parseFloat(j.showTFeeAmtPaid);
                }
                if(document.feeForm.feeType.value==4 || document.feeForm.feeType.value == 2) {   // Only Transport
                    document.feeForm.transportAmtPaid.value = j.showTTransportAmtPaid;    
                    netBalance = parseFloat(netBalance) + parseFloat(j.showTTransportAmtPaid);
                }
                if(document.feeForm.feeType.value==4 || document.feeForm.feeType.value == 3) {   // Only Hostel
                    document.feeForm.hostelAmtPaid.value = j.showTHostelAmtPaid;    
                    netBalance = parseFloat(netBalance) + parseFloat(j.showTHostelAmtPaid);
                }
                
                document.getElementById('tdPrevDuesPaid1').style.display="none";
                document.getElementById('tdPrevDuesPaid2').style.display="none";
                document.getElementById('tdPrevDuesPaid3').style.display="none";
                
                if(j.showTDuesAmtPaid != '0' && j.showTDuesAmtPaid!='') {
                  document.getElementById('tdPrevDuesPaid1').style.display="";
                  document.getElementById('tdPrevDuesPaid2').style.display="";
                  document.getElementById('tdPrevDuesPaid3').style.display="";
                  document.getElementById('duesAmtPaid').value= parseFloat(j.showTDuesAmtPaid); 
                  document.getElementById('lblDuesAmtPaid').innerHTML =  parseFloat(j.showTDuesAmtPaid);  
                }
                netBalance = parseFloat(netBalance) + parseFloat(j.showTDuesAmtPaid);  
                document.feeForm.netAmount.value = netBalance;  
                document.feeForm.netAmount1.value = netBalance;  
                document.getElementById('lblNetAmount').innerHTML= netBalance;  
		     },
		     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	       });
	}
	else
	{
		//printResultsNoSorting('results', j.info, tbHeadArray);
		document.getElementById('resultsDiv').innerHTML="<table border='0' cellspacing='0' cellpadding='3' width='100%'><tr class='rowheading'><td valign='middle' width='3%'><B>#</B></td><td valign='middle' width='60%'><B>Fee Head</B></td><td valign='middle' width='15%'><B>Amount</B></td><td valign='middle' width='15%'><B>Concession</B></td></tr><tr class='row0'><td valign='middle' colspan='4' align='center'>No detail found</td></tr></table>";
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
    try {
       var fine=0;
       var transportfine=0;
       var hostelfine =0;  
        
       var miscTotal=0; 
       formx = document.feeForm;
       var obj=formx.getElementsByTagName('INPUT');
       var total=obj.length;
       for(var i=0;i<total;i++) {
          if(obj[i].name=="studentMisc[]" && obj[i].type.toUpperCase()=='TEXT') {   
             if(!isDecimal(trim(obj[i].value))) {  
               miscTotal = miscTotal+0;
             }
             else {
               miscTotal = miscTotal+ parseFloat(trim(obj[i].value),2) 
             }
          }
       }
        
       if((document.feeForm.feeType.value==4 || document.feeForm.feeType.value==1))  {
           if(typeof (document.feeForm.studentFine) === "undefined") {   
              fine=0;
           }
           else {
              fine=trim(document.feeForm.studentFine.value);   
              if(!isDecimal(trim(document.feeForm.studentFine.value))) {
                fine=0;
              }
           }
       }
        
       if(document.feeForm.transportFacility.value == "1") { 
            if((document.feeForm.feeType.value==4 || document.feeForm.feeType.value==2))  {
               if(typeof (document.feeForm.transportFine) === "undefined") {   
                  transportfine=0;
               }
               else {
                  transportfine=trim(document.feeForm.transportFine.value);   
                  if(!isDecimal(trim(document.feeForm.transportFine.value))) {
                    transportfine=0;
                  }
               }
            }
       }
        
       if(document.feeForm.hostelFacility.value == "1") { 
            if((document.feeForm.feeType.value==4 || document.feeForm.feeType.value==2))  {
               if(typeof (document.feeForm.hostelfine) === "undefined") {   
                  hostelfine=0;
               }
               else {
                  hostelfine=trim(document.feeForm.hostelfine.value);   
                  if(!isDecimal(trim(document.feeForm.hostelfine.value))) {
                    hostelfine=0;
                  }
               }
            }
       }
        
        document.feeForm.netAmount.value = (parseFloat(document.feeForm.netAmount1.value,2)+parseFloat(fine,2)+parseFloat(hostelfine,2)+parseFloat(transportfine,2)+parseFloat(miscTotal,2)).toFixed(2);  
        document.getElementById('lblNetAmount').innerHTML=document.feeForm.netAmount.value
    }
    catch(e){
       document.feeForm.netAmount.value = (parseFloat(document.feeForm.netAmount1.value,2)).toFixed(2); 
       alert(e);
    }
}   

//----------------------------------------------------------------------
//Author:Rajeev Aggarwal
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
//Purpose:to calculate concession
//Date:17.7.2008
//------------------------------------------------------------------------
function calculateFeePaid(){
    
    return false;
    
 /* var feeAmt=0;
    var transportAmt=0;
    var hostelAmt =0;  
    var paidTot=0;
    
    try {
        if((document.feeForm.feeType.value==4 || document.feeForm.feeType.value==1) && trim(document.feeForm.feeAmtPaid.value)!='') {
          feeAmt=trim(document.feeForm.feeAmtPaid.value);
          if(!isDecimal(feeAmt)) {
            feeAmt=0;
          }
        }
        
        if((document.feeForm.feeType.value==4 || document.feeForm.feeType.value==2) && trim(document.feeForm.transportAmtPaid.value)!='') {
          transportAmt=trim(document.feeForm.transportAmtPaid.value);
          if(!isDecimal(transportAmt)) {
            transportAmt=0;
          }
        }
        
        if((document.feeForm.feeType.value==4 || document.feeForm.feeType.value==3) && trim(document.feeForm.hostelAmtPaid.value)!='') {
          hostelAmt=trim(document.feeForm.hostelAmtPaid.value);
          if(!isDecimal(hostelAmt)) {
            hostelAmt=0;
          }
        }  
        paidTot = (parseFloat(feeAmt,2)+parseFloat(transportAmt,2)+parseFloat(hostelAmt,2)).toFixed(2);
       
        document.getElementById('paidAmount').value=paidTot;
        document.getElementById('lblAmountPaid').innerHTML= paidTot;

        // Headwise Fee Division 
        //if(feeAmt!=0) {
          var dif=feeAmt;
          var formx = document.feeForm; 
          var feeHeadLen = formx.elements['applAmtD[]'].length; 
          var id;
          
          if(dif>0) {
            for(var i=0;i<feeHeadLen;i++){
                 if(parseFloat(dif,2) <= 0 ) {
                   formx.elements['applAmt[]'][i].value = 0;  
                 }
                 else if(parseFloat(dif,2) <= parseFloat(formx.elements['applAmtD[]'][i].value,2)) {
                   formx.elements['applAmt[]'][i].value = dif;  
                   dif = 0; 
                 }
                 else if(parseFloat(dif,2) > parseFloat(formx.elements['applAmtD[]'][i].value,2)) {
                   formx.elements['applAmt[]'][i].value = formx.elements['applAmtD[]'][i].value;  
                   dif = dif - formx.elements['applAmtD[]'][i].value;   
                 }
            }
            if(parseFloat(dif,2)>0) {
              if(parseFloat(trim(document.getElementById('studentFine').value),2)!='') {
                 if(parseFloat(trim(document.getElementById('studentFine').value),2) > 0 ) {
                    if(parseFloat(trim(document.getElementById('studentFine').value),2) >= parseFloat(dif,2)) {
                      document.getElementById('studentFineApplAmt').value=dif;
                    }
                    else {
                      document.getElementById('studentFineApplAmt').value=parseFloat(trim(document.getElementById('studentFine').value));
                    } 
                 }
              }
           }
           else {
             document.getElementById('studentFineApplAmt').value=dif;  
           }
        }
    }
    catch(e){
        document.getElementById('paidAmount').value=0;
        document.getElementById('lblAmountPaid').innerHTML="0.00";
    }
   */
}   


//----------------------------------------------------------------------
//Author:Rajeev Aggarwal
//Purpose:to print fees receipt
//Date:17.7.2008
//------------------------------------------------------------------------
function printReport(receiptId) {

    var feeHeadChk =0;
    var paymentChk=0;
    
    if(document.feeForm.feeHeadChk.checked==true) {
      feeHeadChk = 1;
    }
    
    if(document.feeForm.paymentChk.checked==true) {
      paymentChk = 1;
    }
    
	path='<?php echo UI_HTTP_PATH;?>/paymentReceiptDetailPrint.php?id='+receiptId+"&feeHeadChk="+feeHeadChk+"&paymentChk="+paymentChk;
	//alert(path);
	window.open(path,"PaymentReceiptPrint","status=1,menubar=1,scrollbars=1, width=800, height=600, top=150,left=150");
}

function showStudentDetails(dv,w,h) {
    
	var left = 150;
	var top = 100;
    
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
	
	url1 = '<?php echo HTTP_LIB_PATH;?>/CollectFees/getStudentList.php';
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
	resetFeeClass(); 
    populateValues(-1); 
    getFeeCylceClasses(); 
    return false;
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
	  
	  cell1.setAttribute('align','left');      
	  cell2.setAttribute('align','left'); 
	  cell3.setAttribute('align','left'); 
	  cell4.setAttribute('align','left'); 
	  cell5.setAttribute('align','left');
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

	  txt2.setAttribute('id','instId'+parseInt(start+i,10));
	  txt2.setAttribute('name','instId[]');
	  txt2.className='inputbox';
	  txt2.style.width='50px';

	  txt3.setAttribute('id','amtId'+parseInt(start+i,10));
	  txt3.setAttribute('name','amtId[]'); 
	  txt3.className='inputbox';
	  txt3.style.width='50px';
	  txt3.value='0';
	  //txt3.onblur = new Function("getAmountPaid('"+thisCtr+"')");
	  
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
	  cell6.innerHTML='<input type="text" id="leaveDate'+parseInt(start+i,10)+'" name="leaveDate'+parseInt(start+i,10)+'" class="inputBox" readonly="true" value="'+serverDate+'" size="8" />';
	  cell6.innerHTML +="<input type=\"image\" id=\"calImg\" name=\"calImg\" title=\"Select Date\" src=\""+imagePathURL+"/calendar.gif\"  onClick=\"return showCalendar('leaveDate"+parseInt(start+i,10)+"','%Y-%m-%d', '24', true);\">";
	  //cell6.appendChild(txt6);
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
    document.getElementById('lblAmountPaid').innerHTML = (parseFloat(totalAmountPaid)).toFixed(2); 
}
function validateForm(frm, act) {
	
    if(trim(document.getElementById('receiptNumber').value)=='') {
       messageBox("<?php echo "Enter Receipt No."; ?>"); 
       eval("document.getElementById('receiptNumber').className='inputboxRed'"); 
       document.getElementById('receiptNumber').focus();
       return false; 
    }
   
    if(act=='OldPrint') {
       printReport(document.getElementById('receiptNumber').value);  
       return false;  
    }
   
    if(trim(document.getElementById('receiptNumber').value)=='<?php echo $sessionHandler->getSessionVariable('FEE_RECEIPT_PREFIX'); ?>') {
       messageBox("<?php echo "Enter Receipt No."; ?>"); 
       eval("document.getElementById('receiptNumber').className='inputboxRed'");  
       document.getElementById('receiptNumber').focus();
       return false; 
    }
   
   
    var str = trim(document.getElementById('receiptNumber').value);   
    var recNo = '<?php echo FEE_RECEIPT_PREFIX; ?>';
    var findRecNo = str.substring(0,recNo.length);
  /*if(findRecNo!=recNo) {
       messageBox("<?php echo "Invalid format of Receipt No."; ?>"); 
       document.getElementById('receiptNumber').focus();
       return false; 
    }
  */
    
    if(act=='OldPrint') {
       printReport(document.getElementById('receiptNumber').value);  
       return false;  
    }
    
    if(trim(document.getElementById('studentRoll').value)=='') {
       messageBox("<?php echo "Enter Student Roll No."; ?>"); 
       eval("document.getElementById('studentRoll').className='inputboxRed'");  
       document.getElementById('studentRoll').focus();
       return false; 
    }
  
    if(document.getElementById('feeCycle').value=='') {
       messageBox("<?php echo "Select Fee Cycle"; ?>"); 
       eval("document.getElementById('feeCycle').className='inputboxRed'");  
       document.getElementById('feeCycle').focus();
       return false; 
    }
    
    if(document.getElementById('feeClassId').value=='') {
       messageBox("<?php echo "Select Fee Class"; ?>"); 
       eval("document.getElementById('feeClassId').className='inputboxRed'");    
       document.getElementById('feeClassId').focus();
       return false; 
    }
    
    /*
    if(document.getElementById('favouringBank').value=='') {
       messageBox("<?php echo "Select Payable Fav Branch"; ?>"); 
       eval("document.getElementById('favouringBank').className='inputboxRed'");  
       document.getElementById('favouringBank').focus();
       return false; 
    }*/
    
  /* 
   if(trim(document.getElementById('paidAmount').value)=='') {
       messageBox("<?php echo "Enter Total Amount Paid"; ?>"); 
      document.getElementById('paidAmount').focus();
      return false; 
   }
  
   
    // Integer Value Checks updated
   if(!isDecimal(trim(eval("document.getElementById('paidAmount').value")))) {                          
     messageBox ("Enter numeric value for Total Amount Paid");
     eval("document.getElementById('paidAmount').focus()");  
     return false;
   }
  */ 
    var transportDue=0;
    var transportConcession =0;
        
    var hostelDue=0;
    var hostelConcession =0;
        
    var applAmtTransportPaid=0;
    var applAmtTransportFine=0;
    var transportApplCharges=0;   
        
    var applAmtHostelPaid=0;
    var applAmtHostelFine=0;
    var hostelApplCharges=0;
    
    var transportDue =0;
    var transportConcession =0;
    var transportCharges =0;
    var transportFine=0;
    var applAmtTransportPaid=0;
    var applAmtTransportFine=0;

    var hostelDue =0;
    var hostelConcession =0;
    var hostelCharges =0;
    var hostelfine=0;
    var appHostelPaid=0;
    var applAmtHostelFine=0;
    
    formx = document.feeForm;
    var obj=formx.getElementsByTagName('INPUT');
    var total=obj.length;
    
    var miscAmount=0;      
    var miscApplAmount=0;
    
    for(var i=0;i<total;i++) {
       if(obj[i].name=="studentMisc[]" && obj[i].type.toUpperCase()=='TEXT') {   
         if(trim(obj[i].value)!='') {  
             if(!isDecimal(trim(obj[i].value))) {                          
                messageBox ("Enter numeric value");
                obj[i].className='inputboxRed';   
                obj[i].focus();  
                return false;
             }
             if(!isDecimal(trim(obj[i].value))) {  
               miscAmount = miscAmount+0;
             }
             else {
               miscAmount = miscAmount+ parseFloat(trim(obj[i].value),2) 
             }
         }
       }
      
       if(obj[i].name=="studentMiscApplAmt[]" && obj[i].type.toUpperCase()=='TEXT') {   
         if(trim(obj[i].value)!='') {    
             if(!isDecimal(trim(obj[i].value))) {                          
                messageBox ("Enter numeric value");
                obj[i].className='inputboxRed';   
                obj[i].focus();  
                return false;
             }  
             if(!isDecimal(trim(obj[i].value))) {  
               miscApplAmount = miscApplAmount+0;
             }
             else {
               miscApplAmount = miscApplAmount+ parseFloat(trim(obj[i].value),2) 
             }
         }
       }
    }
  
    
    if(document.feeForm.feeType.value==4 || document.feeForm.feeType.value==2) {
        if(typeof (document.feeForm.transportFine) === "undefined") { 
            transportDue =0;
            transportConcession =0;
            transportCharges =0;
            transportFine=0;
            applAmtTransportPaid=0;
            applAmtTransportFine=0;
        } 
        else {
            if(trim(document.feeForm.transportFine.value)!='') {
               transportFine=trim(document.feeForm.transportFine.value);   
               if(!isDecimal(trim(document.feeForm.transportFine.value))) {
                  messageBox ("Enter numeric value for Transport Fine");
                  eval("document.getElementById('transportFine').className='inputboxRed'"); 
                  eval("document.getElementById('transportFine').focus()");  
                  return false;
               }
            }
    
            if(trim(document.feeForm.transportDue.value)!='') {
              transportDue = trim(document.feeForm.transportDue.value);  
              if(!isDecimal(trim(document.feeForm.transportDue.value))) {
                  messageBox ("Enter numeric value for Transport Due");
                  eval("document.getElementById('transportDue').className='inputboxRed'");   
                  eval("document.getElementById('transportDue').focus()");  
                  return false;
              }
            }
        
            if(trim(document.feeForm.transportConcession.value)!='') {
              transportConcession = trim(document.feeForm.transportConcession.value); 
              if(!isDecimal(trim(document.feeForm.transportConcession.value))) {
                 messageBox ("Enter numeric value for Transport Concession");
                 eval("document.getElementById('transportConcession').className='inputboxRed'");  
                 eval("document.getElementById('transportConcession').focus()");  
                 return false;
              }
            }
            
            transportCharges =  transportDue -  transportConcession;
            if(transportCharges < 0) {
               messageBox("Concession amount cannot be greater than Transport Charges");                   
               eval("document.getElementById('transportConcession').className='inputboxRed'");    
               document.feeForm.transportConcession.focus();
               return false;  
            }
        
            
            if(trim(document.feeForm.applAmtTransportPaid.value)!='') {  
                 applAmtTransportPaid=trim(document.feeForm.applAmtTransportPaid.value);   
                 if(!isDecimal(trim(document.feeForm.applAmtTransportPaid.value))) {
                   messageBox ("Enter numeric value for Appl. Amt. Transport");
                   eval("document.getElementById('applAmtTransportPaid').className='inputboxRed'"); 
                   eval("document.getElementById('applAmtTransportPaid').focus()");  
                   return false;
                 }
            }
              
            if(trim(document.feeForm.applAmtTransportFine.value)!='') {  
                 applAmtTransportFine=trim(document.feeForm.applAmtTransportFine.value);   
                 if(!isDecimal(trim(document.feeForm.applAmtTransportFine.value))) {
                   messageBox ("Enter numeric value for Appl. Amt. Transport Fine");
                   eval("document.getElementById('applAmtTransportFine').className='inputboxRed'"); 
                   eval("document.getElementById('applAmtTransportFine').focus()");  
                   return false;
                 }
            }
            
            transportApplCharges =  parseFloat(applAmtTransportFine,0) + parseFloat(applAmtTransportPaid,0);
        
            var appTransportPaid = 0;  
            if(trim(document.feeForm.transportAmtPaid.value)!='') {
               appTransportPaid = trim(document.feeForm.transportAmtPaid.value); 
            }
            
            if(parseFloat(transportApplCharges,2) > parseFloat(appTransportPaid,2) ) {
              messageBox("Transport Amount cannot be greater than Transport Amount Paid");                   
              eval("document.getElementById('transportAmtPaid').className='inputboxRed'");    
              document.feeForm.transportAmtPaid.focus();
              return false;  
            }
        }
    }
    

    
    if(document.feeForm.feeType.value==4 || document.feeForm.feeType.value==3)  {
        if(typeof (document.feeForm.hostelFine) === "undefined") { 
            hostelDue =0;
            hostelConcession =0;
            hostelCharges =0;
            hostelfine=0;
            appHostelPaid=0;
            applAmtHostelFine=0;
        } 
        else {
          if(trim(document.feeForm.hostelFine.value)!='') {  
             hostelfine=trim(document.feeForm.hostelFine.value);   
             if(!isDecimal(trim(document.feeForm.hostelFine.value))) {
               messageBox ("Enter numeric value for Hostel Fine");
               eval("document.getElementById('hostelFine').className='inputboxRed'"); 
               eval("document.getElementById('hostelFine').focus()");  
               return false;
             }
          }
     
          if(trim(document.feeForm.hostelDue.value)!='') {
             hostelDue=trim(document.feeForm.hostelDue.value);   
             if(!isDecimal(trim(document.feeForm.hostelDue.value))) {
                messageBox ("Enter numeric value for Hostel Due");
                eval("document.getElementById('hostelDue').className='inputboxRed'");  
                eval("document.getElementById('hostelDue').focus()");  
                return false;
             }
          }
            
          if(trim(document.feeForm.hostelConcession.value)!='') {
             hostelConcession=trim(document.feeForm.hostelConcession.value);   
             if(!isDecimal(trim(document.feeForm.hostelConcession.value))) {
                messageBox ("Enter numeric value for Hostel Concession");
                eval("document.getElementById('hostelConcession').className='inputboxRed'"); 
                eval("document.getElementById('hostelConcession').focus()");  
                return false;
             }
          }
           
          hostelCharges =  hostelDue -  hostelConcession;
          if(hostelCharges < 0) {
             messageBox("Concession amount cannot be greater than Hostel Charges"); 
             eval("document.getElementById('hostelConcession').className='inputboxRed'");                    
             document.feeForm.hostelConcession.focus();
             return false;  
          }
          
          if(trim(document.feeForm.applAmtHostelPaid.value)!='') {  
             applAmtHostelPaid=trim(document.feeForm.applAmtHostelPaid.value);   
             if(!isDecimal(trim(document.feeForm.applAmtHostelPaid.value))) {
               messageBox ("Enter numeric value for Appl. Amt. Hostel");
               eval("document.getElementById('applAmtHostelPaid').className='inputboxRed'"); 
               eval("document.getElementById('applAmtHostelPaid').focus()");  
               return false;
             }
          }
          
          if(trim(document.feeForm.applAmtHostelFine.value)!='') {  
             applAmtHostelFine=trim(document.feeForm.applAmtHostelFine.value);   
             if(!isDecimal(trim(document.feeForm.applAmtHostelFine.value))) {
               messageBox ("Enter numeric value for Appl. Amt. Hostel Fine");
               eval("document.getElementById('applAmtHostelFine').className='inputboxRed'"); 
               eval("document.getElementById('applAmtHostelFine').focus()");  
               return false;
             }
          }
          
          var appHostelPaid = 0;  
          if(trim(document.feeForm.hostelAmtPaid.value)!='') {
            appHostelPaid = trim(document.feeForm.hostelAmtPaid.value); 
          }          
          
          hostelApplCharges =  parseFloat(applAmtHostelFine,0) + parseFloat(applAmtHostelPaid,0);
          if(parseFloat(hostelApplCharges,2) > parseFloat(appHostelPaid,2) ) {
            messageBox("Hostel Amount cannot be greater than Hostel Amount Paid");                   
            eval("document.getElementById('transportAmtPaid').className='inputboxRed'");    
            document.feeForm.hostelAmtPaid.focus();
            return false;  
          }
       }
    }

    studentFine=0;  
    if(trim(document.getElementById('feeType').value)==4 || trim(document.getElementById('feeType').value)==1) {
       if(typeof (document.feeForm.studentFine) === "undefined") { 
            studentFine=0;
       } 
       else { 
           if(trim(document.getElementById('studentFine').value)!='') {
               // Integer Value Checks updated
               if(!isDecimal(trim(eval("document.getElementById('studentFine').value")))) {                          
                 messageBox ("Enter numeric value for Fine");
                 eval("document.getElementById('studentFine').className='inputboxRed'");
                 eval("document.getElementById('studentFine').focus()");  
                 return false;
               }
           }
       }
   }     
   
   var paymentAmount=0;
   if(trim(document.getElementById('cashAmount').value)!='') {
       // Integer Value Checks updated
       if(!isDecimal(trim(eval("document.getElementById('cashAmount').value")))) {                          
         messageBox ("Enter numeric value for Cash Amount");
         eval("document.getElementById('cashAmount').className='inputboxRed'");
         eval("document.getElementById('cashAmount').focus()");  
         return false;
       }
       paymentAmount=trim(document.getElementById('cashAmount').value);
   }
   
    dtArray.splice(0,dtArray.length); //empty the array  
    posArray=new Array();
    
    formx = document.feeForm;
    var obj=formx.getElementsByTagName('INPUT');
    var total=obj.length;
    for(var i=0;i<total;i++) {
        if(obj[i].type.toUpperCase()=='HIDDEN' && obj[i].name.indexOf('idNos[]')>-1) {
           // blank value check 
           id =obj[i].value;
           if(eval("document.getElementById('paymentTypeId"+id+"').value")=='') {
             messageBox ("Select Type");  
             eval("document.getElementById('paymentTypeId"+id+"').className='inputboxRed'");   
             eval("document.getElementById('paymentTypeId"+id+"').focus()");
             return false;             
           }
           
           if(trim(eval("document.getElementById('instId"+id+"').value"))=='') {
             messageBox ("Enter Number");  
             eval("document.getElementById('instId"+id+"').className='inputboxRed'");   
             eval("document.getElementById('instId"+id+"').focus()");
             return false;             
           }
          
           if(trim(eval("document.getElementById('amtId"+id+"').value"))=='') {                          
             messageBox ("Enter numeric value for Amount");
             eval("document.getElementById('amtId"+id+"').className='inputboxRed'");   
             eval("document.getElementById('amtId"+id+"').focus()");  
             return false;
           }
           
           // Integer Value Checks updated
           if(!isDecimal(trim(eval("document.getElementById('amtId"+id+"').value")))) {                          
             messageBox ("Enter numeric value for amount");
             eval("document.getElementById('totalAmount"+id+"').className='inputboxRed'");   
             eval("document.getElementById('totalAmount"+id+"').focus()");  
             return false;
           }
           
           if(eval("document.getElementById('issuingBankId"+id+"').value")=='') {
             messageBox ("Select Bank");  
             eval("document.getElementById('issuingBankId"+id+"').className='inputboxRed'");   
             eval("document.getElementById('issuingBankId"+id+"').focus()");
             return false;             
           }
           
           if(checkDuplicate(trim(eval("document.getElementById('instId"+id+"').value")))==0){
             messageBox ("This column value already assign");  
             eval("document.getElementById('instId"+id+"').className='inputboxRed'");   
             eval("document.getElementById('instId"+id+"').focus()");   
             return false;
           }
           paymentAmount = parseFloat(paymentAmount,2)+parseFloat(trim(eval("document.getElementById('amtId"+id+"').value")),2);
        }
    }
       
    var headTotal=0;
    var obj=formx.getElementsByTagName('INPUT');
    var total=obj.length;
    for(var i=0;i<total;i++) {
        if(obj[i].type.toUpperCase()=='TEXT' && obj[i].name.indexOf('applAmt[]')>-1) {
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
    }     
    headTotal=headTotal+parseFloat(miscApplAmount,2);  
    
 
    
    if(trim(document.getElementById('feeType').value)==4 || trim(document.getElementById('feeType').value)==1) {
        if(trim(document.getElementById('studentFineApplAmt').value)!='') {
           if(!isDecimal(trim(document.getElementById('studentFineApplAmt').value))) {                          
              messageBox ("Enter numeric value for Fine Amount Paid");
              eval("document.getElementById('studentFineApplAmt').className='inputboxRed'");   
              eval("document.getElementById('studentFineApplAmt').focus()");  
              return false;
           } 
           headTotal=headTotal+parseFloat(document.getElementById('studentFineApplAmt').value,2); 
        }                                                 
                
        if(headTotal!=0) {   
           var ttPay = 0;
           ttPay = parseFloat(ttPay,2) + parseFloat(getCheckPaid("feeAmtPaid"),2); 
           if(parseFloat(headTotal,2)>parseFloat(ttPay,2) && parseFloat(ttPay,2) >0) {
             messageBox("<?php echo "Fee Head Wise Amount ("; ?>"+headTotal+"<?php echo ") and Payment Detail("?>"+ttPay+"<?php echo ") mismatch" ?>");
             if(typeof (document.feeForm.feeAmtPaid) === "undefined") { 
               eval("document.getElementById('feeAmtPaid').className='inputboxRed'");   
               eval("document.getElementById('feeAmtPaid').focus()");  
             }
             return false;  
           }
        }
    }  
    
                                   
    var headTotal=0;
    var obj=formx.getElementsByTagName('INPUT');
    var total=obj.length;
    for(var i=0;i<total;i++) {
        if(obj[i].type.toUpperCase()=='HIDDEN' && obj[i].name.indexOf('fIds[]')>-1) {
           // blank value check 
           if(trim(obj[i].value)!='') {
               id = obj[i].value; 
               ttApp =  eval("document.getElementById('applAmt"+id+"').value");
               ttAppD = eval("document.getElementById('applAmtD"+id+"').value");
               // Integer Value Checks updated
               if(parseFloat(ttApp,2) > parseFloat(ttAppD,2) ) {                          
                 eval("document.getElementById('applAmt"+id+"').className='inputboxRed'");
                 messageBox ("Enter Amount Paid not greater than Fee Head Amount");
                 eval("document.getElementById('applAmt"+id+"').focus();")
                 return false;
               }
           }
        }
    }   
   
    var tpay =0;
    if(isNaN(getCheckPaid("duesAmtPaid"))) {
      tpay=0;
    } 
    else if(typeof (document.feeForm.duesAmtPaid) === "undefined") { 
      tpay=0;  
    } 
    else {
      tpay = parseFloat(tpay,2) + parseFloat(getCheckPaid("duesAmtPaid"),2);
    }
    if(trim(document.getElementById('feeType').value)==4 || trim(document.getElementById('feeType').value)==1) {    
      tpay = parseFloat(tpay,2) + parseFloat(getCheckPaid("feeAmtPaid"),2);
    }
    if(trim(document.getElementById('feeType').value)==4 || trim(document.getElementById('feeType').value)==2) {    
      tpay = parseFloat(tpay,2) + parseFloat(getCheckPaid("transportAmtPaid"),2);
    }
    if(trim(document.getElementById('feeType').value)==4 || trim(document.getElementById('feeType').value)==3) {    
      tpay = parseFloat(tpay,2) + parseFloat(getCheckPaid("hostelAmtPaid"),2);
    }
    
    var paid=0;
    if(trim(document.feeForm.cashAmount.value)!='') {
       paid = trim(document.feeForm.cashAmount.value);  
    }
    var obj=formx.getElementsByTagName('INPUT');
    var total=obj.length;
    for(var i=0;i<total;i++) {
        if(obj[i].type.toUpperCase()=='TEXT' && obj[i].name.indexOf('amtId[]')>-1) {
            // blank value check 
           if(trim(obj[i].value)!='') {                          
               // Integer Value Checks updated
               if(!isDecimal(trim(obj[i].value))) {                          
                 messageBox ("Enter numeric value for Cheque/Draft");
                 obj[i].className='inputboxRed';   
                 obj[i].focus();  
                 return false;
               }
               paid = parseFloat(paid,2) + parseFloat(obj[i].value,2); 
           }
        }
    } 
    
    if(tpay != paid) {
       eval("document.getElementById('cashAmount').className='inputboxRed'"); 
       messageBox ("Cash Payment("+paid+") and Amount Paid Detail("+tpay+") Mistmatch");  
       eval("document.getElementById('cashAmount').focus()"); 
       return false; 
    } 
   
    
    if(document.feeForm.studentId.value!=''){
      addStudentFees(act);
    }
    else{
      messageBox("<?php echo STUDENT_CORRECT_ROLL?>");
      document.feeForm.studentRoll.focus();
      return false;
    }
    
    return false;
}


function getCheckPaid(param) {
   var amt=0;
   
   try { 
     if(param=='hostelAmtPaid') { 
        amt = trim(eval("document.feeForm.hostelAmtPaid.value"));
     }
     else if(param=='duesAmtPaid') { 
        amt = trim(eval("document.feeForm.duesAmtPaid.value")); 
     }
     else if(param=='feeAmtPaid') { 
        amt = trim(eval("document.feeForm.feeAmtPaid.value")); 
     }
     else if(param=='transportAmtPaid') { 
       amt = trim(eval("document.feeForm.transportAmtPaid.value"));         
     }
    
     if(isNaN(parseFloat(amt,2))) {
       amt=0;  
     }
     if(amt=='undefined') {
       amt=0;  
     }
     return amt;
   }
   catch(e){ 
      return 0;
   }
}

function checkDuplicate(value) {
    var i= dtArray.length;
    var fl=1;
    for(var k=0;k<i;k++){
      if(dtArray[k]==value){
        fl=0;
        break;
      }  
    }
    if(fl==1){
      dtArray.push(value);
    } 
    
    return fl;
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
function getFeeCylceClasses() {
     
     var url = '<?php echo HTTP_LIB_PATH;?>/CollectFees/ajaxGetFeeCycleClasses.php';   
     
     document.feeForm.feeClassId.length = null;
     addOption(document.feeForm.feeClassId, '', 'Select');
     document.getElementById('trFacilityIds').style.display='none';
     
     /* var feeCycleId = document.feeForm.feeCycle.value;
        if(feeCycleId=='') {
          return false;  
        }
     */
     var ttSelected=0;
     
     var rollNo = trim(document.feeForm.studentRoll.value);
     if(rollNo=='') {
       return false;  
     }
     new Ajax.Request(url,
     {
         method:'post',
         parameters: { //feeCycleId: feeCycleId,
                       rollNo: rollNo 
                     },
         asynchronous:false,
         onCreate: function(transport){
           //showWaitDialog(true);
         },
         onSuccess: function(transport){
           //hideWaitDialog(true);
           j = eval('('+ transport.responseText+')');
           len = j.length;
           document.feeForm.feeClassId.length = null;
           addOption(document.feeForm.feeClassId, '', 'Select');
           document.getElementById('trFacilityIds').style.display='none';  
           if(len>0) {
             for(i=0;i<len;i++) {
               addOption(document.feeForm.feeClassId, j[i]['classId'], j[i]['className']);
               if(j[i]['classId']=='<?php echo $sessionHandler->getSessionVariable('IdToFindFeeClassId'); ?>') {
                 ttSelected = (i+1);  
               }                  
             }
             //document.getElementById('trFacilityIds').style.display='';
           }
         },
         onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
      });
      document.getElementById('feeClassId').selectedIndex=ttSelected; 
}

function getFacilityList() {
  try {
       var tFeeClassId = document.getElementById('feeClassId').value;
       var tFeeType = document.getElementById('feeType').value;
       
       document.getElementById('trFacilityIds').style.display='none';
       document.getElementById('tdFacilityTransport').style.display='none';
       document.getElementById('tdFacilityHostel').style.display='none';
       
       if(tFeeClassId!='' && (tFeeType==2 || tFeeType==4)) {         // Only Transport
         document.getElementById('trFacilityIds').style.display='';
         document.getElementById('tdFacilityTransport').style.display='';
       }
       
       if(tFeeClassId!='' && (tFeeType==3 || tFeeType==4)) {        // Only Hostel 
         document.getElementById('trFacilityIds').style.display='';
         document.getElementById('tdFacilityHostel').style.display='';
       }
       
       document.getElementById('trShowPrevDues').style.display='none'; 
       if(tFeeClassId!=='') {
         document.getElementById('trShowPrevDues').style.display=''; 
       }
  } catch(e){ }
}


function getFacility(id,dv,w,h) {
   
   displayFloatingDiv(dv,'',w,h,450,100);
   document.getElementById(dv).style.top='40px';
   populateFacility(id);
}


function populateFacility(id) {
    
     var url = '<?php echo HTTP_LIB_PATH;?>/CollectFees/ajaxGetFacility.php';   
     if(id==1) {  
       document.getElementById('transportFacilitySave').style.display='none';
       document.getElementById('trTransportFacilityResults').innerHTML='';
     }
     else if(id==2) { 
       document.getElementById('hostelFacilitySave').style.display='none';  
       document.getElementById('trHostelFacilityResults').innerHTML='';
     }
     else if(id==3) { 
       document.getElementById('prevDuesSave').style.display='none';  
       document.getElementById('trPrevDuesResults').innerHTML='';
     }
     
     var rollNo = trim(document.feeForm.studentRoll.value);
     var feeClassId = document.feeForm.feeClassId.value; 
     if(rollNo=='') {
        return false;  
     }
     
     new Ajax.Request(url,
     {
         method:'post',
         parameters: { rollNo: rollNo,
                       feeClassId: feeClassId, 
                       id: id 
                     },
         asynchronous:false,
         onCreate: function(transport){
           showWaitDialog(true);
         },
         onSuccess: function(transport){
            hideWaitDialog(true);                       
            if(trim(transport.responseText)==false) {
               messageBox("<?php echo "No Data Found"; ?>");  
            }
            else {        
               var j= trim(transport.responseText).evalJSON(); 
               if(id==1 || id==2) {  
                   var tblHeadArray = new Array(new Array('srNo','#','width="3%"',''), 
                                                //new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="5%"','align=\"left\"'), 
                                                new Array('className','Class','width="28%"',' align="left"'), 
                                                new Array('facilityAmount','Amount','width="15%"',' align="left"'),
                                                new Array('facilityConcession','Concession','width="15%"',' align="left"'),
                                                new Array('facilityComments','Comments','width="29%"',' align="left"'));
               }
               else {
                   var tblHeadArray = new Array(new Array('srNo','#','width="3%"',''), 
                                                //new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="5%"','align=\"left\"'), 
                                                new Array('className','Class','width="28%"',' align="left"'), 
                                                new Array('facilityAmount','Pending Dues','width="15%"',' align="left"'),
                                                new Array('facilityComments','Comments','width="29%"',' align="left"')); 
               }
               
               if(id==1) {                                                                      
                  printResultsNoSorting('trTransportFacilityResults', j.info, tblHeadArray);  
                  document.getElementById('transportFacilitySave').style.display='';   
               }
               else if(id==2) {  
                  printResultsNoSorting('trHostelFacilityResults', j.info, tblHeadArray);   
                  document.getElementById('hostelFacilitySave').style.display=''; 
               }
               else if(id==3) {  
                  printResultsNoSorting('trPrevDuesResults', j.info, tblHeadArray);    
                  document.getElementById('prevDuesSave').style.display=''; 
               }
            }
         },
         onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
      }); 
}

function doAll(){
   
   formx = document.allDetailsForm;
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

function getLastEntry() {

    var url = '<?php echo HTTP_LIB_PATH;?>/CollectFees/ajaxGetLastEntry.php';  
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
              //cument.getElementById('trLastEntry').style.display='';     
              document.getElementById('lastEntry').innerHTML = ret[0];
              document.getElementById('receiptDate').value = ret[1];
              document.getElementById('feeCycle').value = ret[2]; 
           }
         },
         onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
    }); 
}

window.onload = function () {
    //var roll = document.getElementById("studentRoll");
    //autoSuggest(roll);
    document.getElementById('studentCurrentStatus').innerHTML='';
    getLastEntry();
    if('<?php echo $sessionHandler->getSessionVariable('IdToFindFeeRollNo'); ?>'!='') {
       document.feeForm.studentRoll.value='<?php echo $sessionHandler->getSessionVariable('IdToFindFeeRollNo'); ?>'; 
       resetFeeClass(); 
       getFeeCylceClasses();
       getFacilityList();
       populateValues(-1); 
       return false; 
    }
    document.feeForm.receiptNumber.focus();
}

function validateFacilty(frm,id) {
    
    var url = '<?php echo HTTP_LIB_PATH;?>/CollectFees/initAddFacility.php';
    
    var studentId = document.getElementById('studentId').value; 
    
    if(studentId=='' || id=='') {
       messageBox("<?php echo "Parameter Missing"; ?>");      
       return false;  
    }
    
    var pars = "studentId="+studentId+"&facilityType="+id+"&"; 
    if(id==1) {
      pars += generateQueryString('transportFacilityForm');
    }
    else if(id==2) {
      pars += generateQueryString('hostelFacilityForm');   
    }
    else if(id==3) {
      pars += generateQueryString('prevDuesForm');   
    }
     
    new Ajax.Request(url,
    {
     method:'post',
     asynchronous:false,
     parameters: pars, 
     onSuccess: function(transport){
       if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
         showWaitDialog(true);
       }
       else {
         hideWaitDialog(true);
         messageBox(trim(transport.responseText)); 
         return false;                     
       }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/CollectFees/studentFeesContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>