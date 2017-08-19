<?php
//-------------------------------------------------------
//  This file shows the Admission status of All Programs and Details of Candidate 
//
// Author : Vimal Sharma
// Created on : (14.04.2009 )
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentFee');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
//require_once(BL_PATH . "/Admission/initProgramWiseStatus.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Program Wise Admission Status </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
 
 var tableHeadArray = 
        new Array(new Array('srNo','#','width="2%"','',false), 
        new Array('programName','Program','width="40%"','',false) ,
		new Array('totalSeats','Seats Occupied','width="15%" align="right"','align="right"',false));
        
recordsPerPage  = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage    = <?php echo LINKS_PER_PAGE;?>;                                                  
listURL         = '<?php echo HTTP_LIB_PATH;?>/StudentEnquiry/ajaxInitProgramStatusList.php';
searchFormName  = 'studentFeeForm'; // name of the form which will be used for search
divResultName   = 'results';
page            = 1; //default page
sortField       = 'programId';
sortOrderBy     = 'ASC';



function validateForm() {

    frm = document.studentFeeForm;
    examRollNo  = trim(document.getElementById('examRollNo').value);      
    examRollNo2  = trim(document.getElementById('examRollNo2').value);      
    
    if(examRollNo=='' && examRollNo2=='' ) {
       messageBox("Please enter Competion Exam Roll No. / Application Form No.");   
       frm.examRollNo.focus();
       return false; 
    } 
    
    populateValues();
    return false;
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "editCandidate" DIV
//--------------------------------------------------------
function populateValues() {
         
         hideResults();
         blankValue();
    
         frm = document.studentFeeForm;
         examRollNo1  = trim(document.getElementById('examRollNo').value);      
         examRollNo2  = trim(document.getElementById('examRollNo2').value);      
        
         
         url = '<?php echo HTTP_LIB_PATH;?>/StudentEnquiry/ajaxGetCandidateDetails.php';
         new Ajax.Request(url,
         {
             method:'post',
             parameters: { examRollNo1: examRollNo1,
                           examRollNo2: examRollNo2
                         },
             asynchronous:false,     
             onCreate: function() {
                 showWaitDialog(true);
          },
          onSuccess: function(transport){
                hideWaitDialog(true);
                if(trim(transport.responseText)==0) {
                    messageBox("<?php echo "Competion Exam Roll No. / Application Form No. not exist"; ?>");
                    frm.examRollNo.focus(); 
                    return false;
                }
                showDivs();
                var j = eval('('+transport.responseText+')');
                
                frm.degreeId.value = j.classId;  
                frm.candidateName.value = j.studentName; 
                frm.compExamBy.value = j.compExamBy; 
                frm.compExamRollNo.value = j.compExamRollNo; 
                frm.compExamRank.value = j.compExamRank; 
                frm.applicationDate.value = j.enquiryDate;
                frm.candidateEmail.value = j.studentEmail;
                frm.contactNo.value = j.contactNo;  
                frm.fatherName.value = j.fatherName;  
                frm.applicationNo.value = j.applicationNo;  
                frm.categoryId.value = j.categoryId;  
                frm.studentId.value = j.studentId;  

                if(j.feeReceiptId!='') {  
                   showDivs1();
                   frm.degreeId.value = j.classId1;    
                   frm.cashFee.value = j.cashAmount;
                   frm.feeReceiptNo.value = j.receiptNo;
                   
                   var name = document.getElementById('degreeId');  
                   frm.tdegreeId.value = name.options[name.selectedIndex].text;
                    
                   document.getElementById('tReceiptNos1').innerHTML = j.receiptNo;
                   document.getElementById('tFeeDoe1').innerHTML = customParseDate(j.receiptDate,"-");
                   
                   if(j.ddAmount==0) {
                     frm.feeDD.value = "<?php echo NOT_APPLICABLE_STRING; ?>";
                     frm.feeDDNo.value = "<?php echo NOT_APPLICABLE_STRING; ?>";
                     frm.feeDDDate.value = "<?php echo NOT_APPLICABLE_STRING; ?>";
                     frm.bankName.value  = "<?php echo NOT_APPLICABLE_STRING; ?>";
                   }
                   else {
                     frm.feeDD.value = j.ddAmount;           
                     frm.feeDDNo.value = j.ddNo;    
                     frm.feeDDDate.value = j.ddDate;    
                     frm.bankName.value = j.ddBankName;  
                   }
                   frm.remarks.value = (j.remarks);  
                   frm.feeTotAmt.value = (j.totalAmountPaid);
                   frm.feeReceiptId.value = j.feeReceiptId; 
                   frm.feeDoe.value = j.receiptDate;
                   frm.tfeeDDDate.value = frm.feeDDDate.value;  
                }
                return false;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function showDivs1() {
   frm = document.studentFeeForm;   
    
   document.getElementById("degreeId").className = "noBorder";    
   document.getElementById("cashFee").className = "noBorder";      
   document.getElementById("feeDD").className = "noBorder";  
   document.getElementById("feeDDNo").className = "noBorder";   
   document.getElementById("bankName").className = "noBorder";   
   document.getElementById("remarks").className = "noBorder";    
   
   
   document.getElementById("degreeId").readOnly = true;   
   document.getElementById("cashFee").readOnly = true;      
   document.getElementById("feeDD").readOnly = true;  
   document.getElementById("feeDDNo").readOnly = true;  
   document.getElementById("bankName").readOnly = true;   
   document.getElementById("remarks").readOnly = true;     
   
   document.getElementById("resultRow").style.display='';
   document.getElementById('nameRow').style.display='';
   document.getElementById('nameRow2').style.display='';
   
   document.getElementById('printRow1').style.display='none';
   document.getElementById('printRow2').style.display='';
   document.getElementById('degreeId1').style.display='none';
   document.getElementById('degreeId2').style.display='';
   document.getElementById('feeDate1').style.display='none';
   document.getElementById('feeDate2').style.display='';
   document.getElementById('feeRow2').style.display='';    
}

function showDivs() {
    
    document.getElementById("degreeId").className = "inputbox";    
    document.getElementById("cashFee").className = "inputbox";      
    document.getElementById("feeDD").className = "inputbox";  
    document.getElementById("feeDDNo").className = "inputbox";   
    document.getElementById("bankName").className = "inputbox";   
    document.getElementById("remarks").className = "inputbox";    
   
    document.getElementById("resultRow").style.display='';
    document.getElementById('nameRow').style.display='';
    document.getElementById('nameRow2').style.display='';
    
    document.getElementById("degreeId").readOnly = false;   
    document.getElementById("cashFee").readOnly = false;      
    document.getElementById("feeDD").readOnly = false;  
    document.getElementById("feeDDNo").readOnly = false;  
    document.getElementById("bankName").readOnly = false;   
    document.getElementById("remarks").readOnly = false;     

    document.getElementById('printRow1').style.display='';
    document.getElementById('printRow2').style.display='none';
    document.getElementById('degreeId1').style.display='';
    document.getElementById('degreeId2').style.display='none';
    document.getElementById('feeDate1').style.display='';
    document.getElementById('feeDate2').style.display='none';
    document.getElementById('feeRow2').style.display='none';   
}

function hideResults() {
   document.getElementById("resultRow").style.display='none';
   document.getElementById('nameRow').style.display='none';
   document.getElementById('nameRow2').style.display='none';
   
   document.getElementById('printRow1').style.display='none';
   document.getElementById('printRow2').style.display='none';
}

function blankValue() {
    
    frm = document.studentFeeForm;      
    
    frm.candidateName.value = ""; 
    frm.compExamBy.value = ""; 
    frm.compExamRollNo.value = ""; 
    frm.compExamRank.value = ""; 
    //frm.applicationDate.value = ;
    frm.candidateEmail.value = "";
    frm.contactNo.value = "";  
    frm.fatherName.value = "";  
    frm.applicationNo.value = "";  
    frm.categoryId.value = "";  
    frm.studentId.value = "";   
    
    frm.cashFee.value = "";   
    frm.feeDD.value = "";
    frm.feeDDNo.value = "";
    frm.feeDDDate.value = "<?php echo date('Y-m-d'); ?>";
    frm.bankName.value  = "";
    frm.remarks.value = "";
    frm.feeReceiptId.value = ""; 
    document.getElementById('tReceiptNos1').innerHTML = "";
    document.getElementById('tFeeDoe1').innerHTML = "";
}


window.onload=function(){
  // var t=setInterval("getData()",10000);
   
}

function getData(){
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
}

//  Fee Receipt
function validateAddForm1(frm,act) {
   
    if(true == isEmpty((document.getElementById('degreeId').value))){
       messageBox("<?php echo "Select degree"; ?>");
       frm.degreeId.focus();
       return false;
    }
    
    tcashFee = (frm.cashFee.value);
    tfeeDD = (frm.feeDD.value);
    tfeeDDNo = (frm.feeDDNo.value);
    tfeeDDDate = (frm.feeDDDate.value);
    tbankName = (frm.bankName.value);
    tremarks = (frm.remarks.value);
    
    if(true == isEmpty((document.getElementById('degreeId').value))){
       messageBox("<?php echo "Select degree"; ?>");
       frm.degreeId.focus();
       return false;
    }
     if (tfeeDD=="" && tcashFee=="")  {
    messageBox("<?php echo "Enter Cash Amount or DD Amount"; ?>");
       frm.cashFee.focus();
       return false;
       }
    
 //   temp=0;  
     if (tfeeDD!="")  {
    temp=1;
    }
    else if (tcashFee!="")  {
    temp=0;
    }
   if (tfeeDD=="" && tcashFee=="")     {
    messageBox("<?php echo "Please select one: Cash amount or DD Amount"; ?>");
    return false;
    }    
  /* if(tfeeDD=="" && tfeeDDNo=="" &&   tfeeDDDate=="" &&  tbankName=="") {
      temp=0;
   }
  else {
      temp=1;
    }       */
    
    if(temp==1) {
       if(tfeeDD=="") {
         messageBox("<?php echo "Please enter DD Amount"; ?>");
         frm.feeDD.focus();
         return false; 
       }
       
       if(!isInteger(tfeeDD) && tfeeDD!="") {     
          messageBox ("Please enter numeric value in DD Amount"); 
          frm.feeDD.focus();
          return false;
       }
      
       if(parseFloat(tfeeDD) < 0 ) {   
          messageBox ("Please enter numeric value in DD Amount"); 
          frm.feeDD.focus();  
          return false;
       }
       
       if(tfeeDDNo=="") {
         messageBox("<?php echo "Please Enter DD No."; ?>");
         frm.feeDDNo.focus();
         return false; 
       }
       if(tfeeDDDate=="") {
         messageBox("<?php echo "Please select DD Date"; ?>");
         frm.feeDDDate.focus();
         return false; 
       }
       if(tbankName=="") {
         messageBox("<?php echo "Please enter bank name"; ?>");
         frm.bankName.focus();
         return false; 
       }
       
       if(tcashFee!="") {
         if(!isInteger(tcashFee)) {     
           messageBox ("Please enter numeric value in cash amount"); 
           frm.cashFee.focus();
           return false;
         }
       }
    }    

    if(temp==0) { 
    
     if(tcashFee=="") {
         messageBox("<?php echo "Please Enter Cash Amount"; ?>");
         frm.remarks.focus();
         return false; 
       } 
       if(!isInteger(tcashFee)) {     
          messageBox ("Please enter numeric value in cash amount"); 
          frm.cashFee.focus();
          return false;
       }
    }
    
    if(act=='1' || act=='2') {
       addFeeReceipt(act);
       return false;
    }
    else {
       printReport(act);
       return false; 
    }
}


function addFeeReceipt(act) {
        
    url = '<?php echo HTTP_LIB_PATH;?>/StudentEnquiry/ajaxAddFeeReceipt.php';
    
    //var pars = generateQueryString('studentFeeForm');
    
    new Ajax.Request(url,
    {
     method:'post',
     parameters: { degreeId: (document.getElementById('degreeId').value),   
                   studentId: trim(document.getElementById('studentId').value), 
                   cashFee: trim(document.getElementById('cashFee').value),
                   feeDD: trim(document.getElementById('feeDD').value), 
                   feeDDNo: trim(document.getElementById('feeDDNo').value),    
                   feeDDDate:   (document.getElementById('feeDDDate').value), 
                   bankName:  trim(document.getElementById('bankName').value), 
                   remarks: trim(document.getElementById('remarks').value)
                 },
     asynchronous:false,     
     onCreate: function(){
         showWaitDialog(true);
     },
     onSuccess: function(transport){
         hideWaitDialog(true);
         if("<?php echo "Select Degree";?>" == trim(transport.responseText)) {                     
            messageBox(trim(transport.responseText));  
            return false;
         }
         else if("<?php echo "Please correct competition exam roll no. / application form no."; ?>" == trim(transport.responseText)) {                     
            messageBox(trim(transport.responseText));  
            return false;
         }
         else if("<?php echo FAILURE;?>" == trim(transport.responseText)) {                     
            messageBox(trim(transport.responseText));  
            return false;
         }
         else if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
             flag = true;
             if(act=='2') {
               printReport();
               populateValues();  
               return false;
             }   
             else {
                 messageBox(trim(transport.responseText));     
                 populateValues();  
                 return false;
             }
             return false;
         } 
         else {
             messageBox(trim(transport.responseText));     
             return false;
         }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}


function printReport() {
    
    studentId = document.getElementById('studentId').value;  
    if(studentId=='') {
       messageBox("Please select search");   
       document.getElementById('examRollNo').focus();
       return false; 
    }
    
    path='<?php echo UI_HTTP_PATH;?>/displayFeeReceipt.php?studentId='+studentId;
    window.open(path,"CandidateFeeReceiptReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
}

function editCancelForm(frm,dv,w,h) {
   
    cancelBlankValue();
    if(false===confirm("Are you sure cancel this receipt?")) {
       return false;
    }
    document.getElementById('cancelStudentId').value  = document.getElementById('studentId').value; 
    document.getElementById('cancelFeeReceiptId').value = document.getElementById('feeReceiptId').value; 
    
    document.getElementById('cname').innerHTML = document.getElementById('candidateName').value; 
    
    var name = document.getElementById('degreeId');  
    document.getElementById('degreeName').innerHTML = name.options[name.selectedIndex].text;
    
    document.getElementById('tReceiptNos').innerHTML = document.getElementById('feeReceiptNo').value;
    document.getElementById('tTotalPaidAmount').innerHTML = document.getElementById('feeTotAmt').value+"/-";
    document.getElementById('tFeeDoe').innerHTML = customParseDate(document.getElementById('feeDoe').value,"-");
    
    getPercentageValue();
    
    displayWindow(dv,w,h);
    //alert(document.getElementById('cancelStudentId').value);
    //alert(document.getElementById('cancelFeeReceiptId').value);
    //alert(document.getElementById('feeReceiptNo').value);
}

function getPercentageValue() {
   
     if(document.getElementById('cancelPaymentMode').value==1)  {
       document.getElementById('paymentValue').value =  20;
       net = document.getElementById('feeTotAmt').value - (Math.round(document.getElementById('feeTotAmt').value*20/100.0));
       document.getElementById('tPayValue').innerHTML = "Rs. "+net+"/-";
       //document.getElementById('tPayValue').innerHTML = "Rs. "+(Math.round(document.getElementById('feeTotAmt').value*val/100.0))+"/-";
       document.getElementById('tFormat').innerHTML = "%&nbsp;";
    }
    else if(document.getElementById('cancelPaymentMode').value==2)  {
       document.getElementById('paymentValue').value =  1000;   
       net = document.getElementById('feeTotAmt').value - 1000;
       document.getElementById('tPayValue').innerHTML = "Rs. "+net+"/-" ; 
       document.getElementById('tFormat').innerHTML = "Rs.";
    }
    
}

function getPercentageValue1() {
   
    val1 = (document.getElementById('feeTotAmt').value);
    val  = (document.getElementById('paymentValue').value);
    document.getElementById('tPayValue').innerHTML = "";
    
    if(val=='') {
       return false; 
    }
    
    if(!isDecimal(val)) {
      return false; 
    }   
    
    if(document.getElementById('cancelPaymentMode').value==1)  {  
       if(val=='') {
         messageBox ("Please enter the refundable percentage value");
         document.getElementById('paymentValue').focus();
         return false; 
       }
       
       // Decimal Value Checks updated
       if(!isDecimal(val)) {
          messageBox ("Please enter numeric value");
          document.getElementById('paymentValue').focus();
          return false; 
       }
       
       if(parseFloat(val) < 0 || parseFloat(val) >100 ) {
          messageBox ("Enter percentage value between (1 to 100)");
          document.getElementById('paymentValue').focus();
          return false;
       }
    }
    else if(document.getElementById('cancelPaymentMode').value==2)  {    
       if(val=='') {
         messageBox ("Please enter the fixed refundable amount");
         document.getElementById('paymentValue').focus();
         return false; 
       }
       
       if(!isDecimal(val)) {
          messageBox ("Please enter numeric value");
          document.getElementById('paymentValue').focus();
          return false; 
       }
       
       if(parseFloat(val) < 0 ) {
          messageBox ("Fixed refundable amount not less than zero ");
          document.getElementById('paymentValue').focus();
          return false;
       }
       if(parseFloat(val) > parseFloat(val1)) {
          messageBox ("Fixed refundable amount not greater than paid amount ");
          document.getElementById('paymentValue').focus();
          return false;
       }
    }
    
    if(document.getElementById('cancelPaymentMode').value==1)  {
       net = document.getElementById('feeTotAmt').value - (Math.round(document.getElementById('feeTotAmt').value*val/100.0));
       document.getElementById('tPayValue').innerHTML = "Rs. "+net+"/-";
       //document.getElementById('tPayValue').innerHTML = "Rs. "+(Math.round(document.getElementById('feeTotAmt').value*val/100.0))+"/-";
       document.getElementById('tFormat').innerHTML = "%&nbsp;";
    }
    else if(document.getElementById('cancelPaymentMode').value==2)  {
       net = document.getElementById('feeTotAmt').value - val; 
       document.getElementById('tPayValue').innerHTML = "Rs. "+net+"/-" ; 
       document.getElementById('tFormat').innerHTML = "Rs.";
    }
    
    if(document.getElementById('cancelRemarks').value=='') {
       messageBox("Enter remarks");
       document.getElementById('cancelRemarks').focus(); 
    }
}


function validateCancelForm(frm) {
    
     var url = '<?php echo HTTP_LIB_PATH;?>/StudentEnquiry/ajaxCancelFeeReceipt.php';
     
     val1 = (document.getElementById('feeTotAmt').value);
     val  = (document.getElementById('paymentValue').value);
     
     refundAmount = 0;    
     
     if(!isInteger(val1)) {
       messageBox ("Please enter integer value");
       document.getElementById('feeTotAmt').focus();
       return false; 
     }
       
     if(parseFloat(val) < 0 ) {
       messageBox ("Fixed refundable amount not less than zero ");
       document.getElementById('paymentValue').focus();
       return false;
     }
     
     if(document.getElementById('cancelRemarks').value=='') {
       messageBox ("Enter remarks");
       document.getElementById('cancelRemarks').focus();
       return false;
     }
       
     
     if((document.getElementById('cancelPaymentMode').value)==1) {
        cancelPaymentMode = "Percentage";  
        refundAmount = document.getElementById('feeTotAmt').value - (Math.round(document.getElementById('feeTotAmt').value*val/100.0));   
     }
     else if((document.getElementById('cancelPaymentMode').value)==2) {
        cancelPaymentMode = "Fixed"; 
        refundAmount = document.getElementById('feeTotAmt').value - val;  
     }
     
     new Ajax.Request(url,
     {
         method:'post',
         parameters:{ studentId: (document.getElementById('cancelStudentId').value),
                      feeReceiptId: (document.getElementById('cancelFeeReceiptId').value), 
                      refundMode:   cancelPaymentMode, 
                      refundValue:  document.getElementById('paymentValue').value, 
                      refundAmount: refundAmount, 
                      remarks: (document.getElementById('cancelRemarks').value)
                    },
         onSuccess: function(transport){
           if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
              showWaitDialog(true);
           }
           else {
              //alert(1); 
              hideWaitDialog(true);
                // messageBox(trim(transport.responseText));
              if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                 hiddenFloatingDiv('CancelReceipt');
                 populateValues();  
                 //location.reload(); 
                 return false;
                 //location.reload();
              }
              else {
                messageBox(trim(transport.responseText));
                populateValues();  
              }
           }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
     });
}

function cancelBlankValue() {
    
    frm = document.cancelReceipt;
    
    document.getElementById('tFeeDoe').innerHTML = "";
    document.getElementById('cname').innerHTML = "";
    document.getElementById('degreeName').innerHTML = "";
    document.getElementById('tReceiptNos').innerHTML = "";
    document.getElementById('tTotalPaidAmount').innerHTML = "";
    document.getElementById('tPayValue').innerHTML = "";
    //document.getElementById('tFormat').innerHTML ="";
    
    frm.cancelStudentId.value = "";   
    frm.cancelFeeReceiptId.value = ""; 
 /* frm.cancelCashFee.value = "";   
    frm.cancelFeeDD.value = "";
    frm.cancelFeeDD.value = "";
    frm.cancelFeeDDNo.value = "";
    frm.cancelFeeDDDate.value = "";
    frm.cancelBankName.value  = "";
 */    
    frm.cancelRemarks.value = "";
}

</script>

</head>
<body>
  <?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/StudentEnquiry/listStudentFeeContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
  ?>
  <SCRIPT LANGUAGE="JavaScript">
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //-->
  </SCRIPT>
</body>
</html>
 
<?php 
// $History: candidateFee.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 4/15/10    Time: 2:36p
//Updated in $/LeapCC/Interface
//validation format updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/14/10    Time: 11:23a
//Updated in $/LeapCC/Interface
//validation and format updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 3/23/10    Time: 6:34p
//Updated in $/LeapCC/Interface
//query & condition format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/18/10    Time: 12:45p
//Updated in $/LeapCC/Interface
//validation & condition updated
//

?>