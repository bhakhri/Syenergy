<?php
//-------------------------------------------------------
// Purpose: To generate student fee receipt
// functionality 
//
// Author : Rajeev Aggarwal
// Created on : (17.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CalculateFees');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
require_once(BL_PATH . "/Student/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Detail</title>
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
   document.feeForm.studentRoll.focus();
}
</script>
<script language="javascript">
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
//document.getElementById('receiptDate').onchange = function populateValues();

//obj = document.getElementById("datePicker");
//obj.onclick = function(){alert('hi');};
//obj.onclick();
 
 
function editWindow(id,dv,w,h) {
        displayWindow(dv,w,h);
        populateValues(id);
}
function checkLectureDelivered(value){
   
  s = value.toString();
  for (var i = 0; i < s.length; i++){
    var c = s.charAt(i); 
    if(!isInteger(c))  {
     
     document.getElementById('chb').value="0.00";  
     messageBox("Enter Numeric Value");   
     //document.getElementById('lectureDelivered').focus();
     return false;
   } 
  } 
  return true;
}


//----------------------------------------------------------------------
//Author:Rajeev Aggarwal
//Purpose:Populates "student data" before edit
//Date:17.7.2008
//------------------------------------------------------------------------
function populateValues(id) {
	//alert("studyperiod"+document.getElementById('feeStudyPeriod').value);
	//alert(document.getElementById('feeStudyPeriod').value);
         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentBasicValues.php';
		  if(document.getElementById('receiptDate').value!='' && document.getElementById('studentRoll').value!='' && document.getElementById('feeCycle').value!='' && document.getElementById('feeStudyPeriod').value!='')
		 {
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {receiptDate: (document.getElementById('receiptDate').value),rollNo: (document.getElementById('studentRoll').value),feeCycleId: (document.getElementById('feeCycle').value), studyPeriodId: (document.getElementById('feeStudyPeriod').value)},
             onCreate:function(transport){ showWaitDialog(true);},
             onSuccess: function(transport){
                   
                 hideWaitDialog(true);
				
				 j= trim(transport.responseText).evalJSON();
				 var tbHeadArray = new Array(new Array('srNo','#','width="3%"',''), new Array('headName','Head Name','width="89%"','') , new Array('feeHeadAmt','Amount','width="4%"',' align="right"'), new Array('concession','Concession','width="4%"',' align="right"'));
				 
				 printResultsNoSorting('results', j.info, tbHeadArray);
				  
				 //alert(j.discountedFeePayable);
				 document.feeForm.studentId.value = j.studentinfo[0].studentId;
                 document.feeForm.studentName.value = j.studentinfo[0].firstName;
				 document.feeForm.studentLName.value = j.studentinfo[0].lastName;
				 document.getElementById('myClass').innerHTML=j.studentinfo[0].className;
				 //document.feeForm.currStudyPeriod1.value = j.studentinfo[0].studyPeriodId;
				 document.feeForm.currStudyPeriod.value = j.studentinfo[0].studyPeriodId;
				 document.getElementById('mySerial').innerHTML=j.serialNo;
				 document.getElementById('myReceipt').innerHTML="<?php echo FEE_RECEIPT_PREFIX ?>"+''+j.serialNo;
				 document.getElementById('myFirst').innerHTML=j.studentinfo[0].firstName;
				 document.getElementById('myLast').innerHTML=j.studentinfo[0].lastName;
				 document.getElementById('myStudyPeriod').innerHTML=j.studentinfo[0].periodName;


				 document.feeForm.studentClass.value = j.studentinfo[0].classId;
				 
				 document.feeForm.totalFees.value = j.totalAmount;

				 document.feeForm.totalConcession.value = j.discountAmt;
				 document.getElementById('myInstallment').innerHTML=j.studentInstallmentCount;
				 document.feeForm.totalFeesHidden.value = j.totalAmount;
				 document.feeForm.paidAmount.value = j.totalAmount;
				 document.feeForm.serialNumber.value = j.serialNo;
				 document.feeForm.receiptNumber.value = "<?php echo FEE_RECEIPT_PREFIX ?>"+''+j.serialNo;
				 document.feeForm.studentFine.value = j.studentFeeCycleFine;
				 document.feeForm.previousPayment.value = j.previousPaid;
				 document.feeForm.previousDues.value = j.previousDues;
				 
				 document.feeForm.amountPayable.value = (parseFloat(document.feeForm.totalFees.value)-parseFloat(document.feeForm.previousPayment.value)).toFixed(2);
				
				 document.feeForm.netAmount.value = (parseFloat(document.feeForm.amountPayable.value)+ parseFloat(document.feeForm.studentFine.value)- parseFloat(document.feeForm.totalConcession.value)).toFixed(2);  
				
				 document.feeForm.netAmount1.value = (parseFloat(j.totalAmount)+ parseFloat(document.feeForm.studentFine.value)- parseFloat(document.feeForm.totalConcession.value)).toFixed(2);  

				 document.feeForm.paidAmount.value = document.feeForm.netAmount.value;
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

 

function calculateConcession()
{
 obj = document.feeForm.elements['chb[]'];
 obj1 = document.feeForm.elements['feeHeadAmt[]'];
 
 len = obj.length;
 if(len>0 && obj!='undefined')
 {
  sumConcession = 0;
  for(i=0;i<len;i++)
  {
   reg = new RegExp("^[-+]{0,1}[0-9]*[.]{0,1}[0-9]*$");
   if ( !reg.test(obj[i].value) )
   {
      alert("Please enter correct concession value");
   }
   else
   {
    //alert(obj[i].value);
    //alert(obj1[i].value);
    if(parseFloat(obj[i].value)>=0)
    {
     if(parseFloat(obj[i].value)>parseFloat(obj1[i].value))
     {
      
      obj[i].value="0.00";
     }
     sumConcession = parseFloat(parseFloat(obj[i].value)+ parseFloat(sumConcession));
    }
    
    else
    {
     obj[i].value="0.00";
    }
   }
  }
 }
 else
 {
  if(obj.value>0 && obj!='undefined')
  {
   sumConcession = obj.value;
  }
 }
 
 document.feeForm.totalFees.value       = parseFloat(document.feeForm.totalFeesHidden.value).toFixed(2)
 document.feeForm.totalConcession.value = parseFloat(sumConcession).toFixed(2);
 if(parseInt(document.feeForm.studentFine.value)!=0)
 {
  if(parseInt(document.feeForm.studentFine.value)>0)
  {
   document.feeForm.netAmount.value       = parseFloat(parseFloat(document.feeForm.totalFees.value) - parseFloat(document.feeForm.previousPayment.value)- parseFloat(sumConcession) + parseFloat(document.feeForm.studentFine.value)).toFixed(2);
   document.feeForm.netAmount1.value       = parseFloat(parseFloat(document.feeForm.totalFees.value) - parseFloat(document.feeForm.previousPayment.value)- parseFloat(sumConcession) + parseFloat(document.feeForm.studentFine.value)).toFixed(2);
  }
  else
  document.feeForm.studentFine.value = "0.00";
 }
 else
 {
  
  document.feeForm.netAmount.value       = parseFloat(parseFloat(document.feeForm.totalFees.value) - parseFloat(document.feeForm.previousPayment.value)- parseFloat(sumConcession) + parseFloat(document.feeForm.studentFine.value)).toFixed(2);
 }
 document.feeForm.paidAmount.value      = document.feeForm.netAmount.value;
}

function formdisable(selectedValue)
{
	if(selectedValue!=1)
	{
		document.feeForm.chequeNumber.value="";
		document.feeForm.issuingBank.value="";
		document.feeForm.favouringBank.value="";
		document.feeForm.issuingDate.value="";
		
		document.getElementById('checkno').style.display='';
		document.getElementById('issuebank').style.display='';
		document.getElementById('favourbank').style.display='';
		document.getElementById('cashpay').style.display='';
		document.getElementById('instStatus').style.display='';
		document.getElementById('reStatus').style.display='';
		document.getElementById('paymentStatus').selectedIndex=1;
		document.getElementById('receiptStatus').selectedIndex=1;
		
	}
	else
	{
		document.getElementById('checkno').style.display='none';
		document.getElementById('issuebank').style.display='none';
		document.getElementById('favourbank').style.display='none';
		document.getElementById('cashpay').style.display='none';
		document.getElementById('instStatus').style.display='none';
		document.getElementById('reStatus').style.display='none';
		document.getElementById('paymentStatus').selectedIndex=3;
		document.getElementById('receiptStatus').selectedIndex=2;
	}
}
 
</script>
</head>
<body >
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/calculateFeesContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: calculateFees.php $
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface
//added code for autosuggest functionality
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 10/27/09   Time: 3:17p
//Updated in $/LeapCC/Interface
//added java script for auto suggest
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/07/09    Time: 5:25p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
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
//*****************  Version 2  *****************
//User: Rajeev       Date: 9/01/08    Time: 4:01p
//Updated in $/Leap/Source/Interface
//updated as per new comments
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/07/08    Time: 3:02p
//Created in $/Leap/Source/Interface
//intial checkin
?>
