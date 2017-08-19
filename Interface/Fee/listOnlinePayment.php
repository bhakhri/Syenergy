<?php
//used for showing student dashboard
global $FE;
$FE = substr($FE,0,strlen(str_replace("Interface","",$FE))-1); 
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','OnlineFeePayment');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();
// var_dump($_SESSION['RollNo']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Online Fee Payment </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">   
    function validateAddForm() {   
        document.getElementById("nameRow").style.display='';
        document.getElementById("nameRow2").style.display='';
        document.getElementById("resultRow").style.display='';
        
       populateStudentFeeDetails(); 
    }
    function getPaybleFee() {

        document.getElementById("onlineTotalFee").innerHTML = "0";        
        
        var formx = document.frmOnlineFeeForm;  
        var    totalFee = 0;
        for(var i=0;i<formx.length;i++){    
          if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chbCheckFee[]" && formx.elements[i].checked==true){
             var value = formx.elements[i].value; 
             var rval=value.split('~'); 
             totalFee += parseFloat(rval[2],2);  
          }
        }                   
        document.getElementById("onlineTotalFee").innerHTML = totalFee;  
        return false;      
    }


    function addOnlineFeeStudentDetails() {
        
        
        	if(document.getElementById("acceptPayment").checked == false) {  
                messageBox("Please accept all term and conditions to proceed further");
                document.getElementById("acceptPayment").focus();
                return false;  
           	} 
           	
            if(false===confirm("Are you sure you want to pay the fee?")) {
              return false;
            }
            
           	 document.getElementById("ttPaymentDiv").innerHTML="<center><br><br><img src='<?php echo STORAGE_HTTP_PATH; ?>/Images/loader.gif' ></center>";
       
            url = '<?php echo HTTP_LIB_PATH;?>/Fee/OnlineFee/ajaxGetPaymentFee.php';
            new Ajax.Request(url,
            {
              method:'post', 
              asynchronous:false, 
              parameters:{ids:1},           
              onCreate: function() {
                showWaitDialog();
             },
             onSuccess: function(transport){
                hideWaitDialog();
                result = trim(transport.responseText);
                if(result.search("<?php echo TECHNICAL_PROBLEM; ?>")>0) {
                  messageBox("<?php echo TECHNICAL_PROBLEM; ?>");
                  return false;
                }
                else {
                  window.location = result;  
                  return false; 
                }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
    }

    

function getShowClassesDetail(){
      txt="<center><br><br><img style='height:65px;' src='<?php echo STORAGE_HTTP_PATH; ?>/Images/loader.gif'></center>";
      document.getElementById("divFeeClassResult").innerHTML=txt;
      
      var url = '<?php echo HTTP_LIB_PATH;?>/Fee/OnlineFee/ajaxGetAllClasses.php';

      new Ajax.Request(url,
       {
         method:'post',
         asynchronous: false,
         onCreate: function() {
             showWaitDialog(true); 
         },
         onSuccess: function(transport){
               
         hideWaitDialog(true);
		 // alert(transport.responseText);die;
           var value = trim(transport.responseText);
        
           if(value=='<table width="100%" border="0" cellspacing="0px" cellpadding="0px" class="border"></table>') {      
            	// document.getElementById("divFeeClassResult").innerHTML = "";  
            	 document.getElementById("divFeeClassResult").innerHTML = "<br><Br><h2><center>No Fee Due</center></h2><br><br>";            	 
           }
           else {
             document.getElementById("divFeeClassResult").innerHTML = value;
             //document.getElementById("divFeeAmountResult").innerHTML = "";
           }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       }); 
}
    
// window.onload=function(){
  // valShow=1;           
  // getShowClassesDetail();
// }


	function printout() {	
	 window.print();
	
	}
	function getHome() {	
	 
	window.location.replace('<?php echo HTTP_PATH;?>/Interface/studentOnlineFeePayment.php');

	}
    
    
    
    
  function printFeeReceipt(payFee,feeClassId){
        if("0"=='1') {
          path="<?php echo HTTP_PATH;?>/Interface/Fee/studentFeesPrint.php?fee="+payFee+"&feeClassId="+feeClassId;
          window.location = path;  
        }
        else {
          window.open("<?php echo HTTP_PATH;?>/Interface/Fee/studentFeesPrint.php?fee="+payFee+"&feeClassId="+feeClassId,"StuidentFeeReceiptPrint","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
        }
        return false;
}

function printOnlineSlip(receiptId){  
   path="<?php echo HTTP_PATH;?>/Interface/Fee/printSlip.php?receiptId="+receiptId;
   
   // window.open(url,"StudentOnlineSlip");
   window.open(path,"StudentOnlineSlip","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");     
 } 



///=====================================================new function=========================
window.onload=function(){
  valShow=1;           
  getFeeCylceClasses();
}

function getFeeCylceClasses() {
     
     var url = '<?php echo HTTP_LIB_PATH;?>/CollectFees/ajaxGetFeeCycleClasses.php';   
     
     document.feeForm.feeClassId.length = null;
     // addOption(document.feeForm.feeClassId, '', 'Select');
     document.getElementById('trFacilityIds').style.display='none';
     
     /* var feeCycleId = document.feeForm.feeCycle.value;
        if(feeCycleId=='') {
          return false;  
        }
     */
     var ttSelected=0;
     
     var rollNo = trim('<?php echo $sessionHandler->getSessionVariable('RollNo'); ?>');
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
		   // alert(transport.responseText);
           j = eval('('+ transport.responseText+')');
           len = j.length;
           document.feeForm.feeClassId.length = null;
           // addOption(document.feeForm.feeClassId, '', 'Select');
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
	  var classId = trim('<?php echo $sessionHandler->getSessionVariable('ClassId'); ?>');
	  // alert(classId);
	  document.getElementById('feeClassId').value = classId;
	  populateValues(-1);
	 // document.getElementById('feeClassId').value = classId;​​​​​​​​​​
	 }

function populateValues(id) {
    // return false;
    var frm = document.feeForm;
    document.getElementById("feeDetail").style.display='';
    document.getElementById("divFeeAmountResult").style.display='none';
    document.getElementById("tblPaymentDetail1").style.display='none';
    document.getElementById("tblPaymentDetail2").style.display='none';
    document.getElementById("tblPaymentDetail3").style.display='none';
	//var includePreviousDues = frm.includePreviousDues[0].checked==true?'0':'1'; 
    document.getElementById("lblAmountPaid").innerHTML = '0';
	document.getElementById("feeAmtPaid").value='0';
	document.getElementById("transportAmtPaid").value='0';
	document.getElementById("hostelAmtPaid").value='0';
    var includePreviousDues =0;
    if(includePreviousDues==0) {
      document.getElementById('chkPrevClassDues1').style.display='';
      document.getElementById('chkPrevClassDues2').style.display='';
      document.getElementById('chkPrevClassDues3').style.display='';
      document.getElementById('chkPrevClassDues4').style.display='';
    }
    

    
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
    
    
    for(i=1;i<=3;i++) {
      document.getElementById('tdFeeAmtPaid'+i).style.display='none'; 
      document.getElementById('tdHostelAmtPaid'+i).style.display='none'; 
      document.getElementById('tdTransportAmtPaid'+i).style.display='none'; 
    }
     
    // var url = '<?php echo HTTP_LIB_PATH;?>/CollectFees/ajaxStudentFeeDetailValue.php';
     url = '<?php echo HTTP_LIB_PATH;?>/Fee/OnlineFee/ajaxGetStudentFeeDetails.php'; 
    
    
        new Ajax.Request(url,
        {
		    method:'post',
		    parameters: {feeTypeId: (document.getElementById('feeType').value), 
						 feeClassId: (document.getElementById('feeClassId').value), 
                         rollNo: trim('<?php echo $sessionHandler->getSessionVariable('RollNo'); ?>'),
                         includePreviousDues: includePreviousDues },
		    onCreate:function(transport){ showWaitDialog(true);},
		    onSuccess: function(transport) {
		        hideWaitDialog(true);
				 // messageBox(trim(transport.responseText));  
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
                                            new Array('headName','Head Name','width="30%"','') , 
                                            new Array('feeHeadAmt','Amount<br>Due','width="10%"',' align="center"'), 
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
		  
                document.feeForm.quotaId.value = j.studentinfo[0].quotaId;
                document.feeForm.isLeet.value = j.studentinfo[0].isLeet;
                document.feeForm.hostelFacility.value =  j.studentinfo[0].hostelFacility; 
                document.feeForm.transportFacility.value =  j.studentinfo[0].transportFacility; 
		       
                
                
                // document.getElementById('installmentCount').value = j.studentInstallmentCount;
                
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
                    
                    // document.getElementById('showBlueIndication').style.display=''; 
                    
                    document.feeForm.feeAmtPaid.value = j.showTFeeAmtPaid;   
                    document.getElementById('tdFeeBalanceDetail').style.display='';  
                     
                    document.feeForm.previousFees.value = parseFloat(j.previousFees,2).toFixed(2);
                    document.feeForm.previousPayment.value = parseFloat(j.previousPayment,2).toFixed(2);
                    document.feeForm.totalFees.value = parseFloat(j.totalFees,2).toFixed(2);
                    
					
					
                    document.feeForm.totalConcession.value = parseFloat(j.totalConcession,2).toFixed(2);
		            document.feeForm.previousPaymentCurr.value = parseFloat(j.previousPaymentCurr,2).toFixed(2);
                    document.getElementById('lblPreviousFineCurr').innerHTML=j.previousFineCurr;
					 
                    // document.feeForm.receivedFrom.value = j.studentinfo[0].studentName;
                    document.getElementById('lblPreviousFine').innerHTML=parseFloat(j.previousFine,2).toFixed(2);
                    document.getElementById('lblPreviousFees').innerHTML=parseFloat(j.previousFees,2).toFixed(2);
                    document.getElementById('lblPreviousPayment').innerHTML=parseFloat(j.previousPayment,2).toFixed(2);
                  
                    var prevDues = ( (parseFloat(j.previousFees,2)+parseFloat(j.previousFine,2)) - parseFloat(j.previousPayment,2)).toFixed(2);
                    document.getElementById('lblPreviousDues').innerHTML=prevDues; 
                    
                    document.getElementById('lblTotalFees').innerHTML=parseFloat(j.totalFees,2).toFixed(2);
                    document.getElementById('lblTotalConcession').innerHTML=parseFloat(j.totalConcession,2).toFixed(2);
                   
                    document.getElementById('lblFeeDues').innerHTML=parseFloat(j.feeAmtPaidTotal,2).toFixed(2);
                    document.getElementById('currFeeDues').value=parseFloat(j.feeAmtPaidTotal,2).toFixed(2); 
                    
                    document.getElementById('lblPreviousPaymentCurr').innerHTML=parseFloat(j.previousPaymentCurr,2).toFixed(2);
                    document.getElementById('lblBalanceAmount').innerHTML= parseFloat((j.netAmount-(j.previousPaymentCurr-j.previousFineCurr)),2).toFixed(2);  
                }
                document.getElementById('lblNetAmount').innerHTML= document.feeForm.netAmount.value; 
              
			
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
                    
                    // document.getElementById('transportDue').value = j.prevTransportDues;   
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
                   
                    // document.getElementById('hostelDue').value = j.prevHostelDues;   
                    document.getElementById('lblNetAmount').innerHTML = document.feeForm.netAmount.value;
                }
                var netBalance = 0;
                 if(document.feeForm.feeType.value==4 || document.feeForm.feeType.value == 1) {    // Only Academic
                   document.feeForm.feeAmtPaid.value = j.showTFeeAmtPaid; 
                   netBalance = parseFloat(netBalance) + parseFloat(j.showTFeeAmtPaid);
                }
                  if(document.feeForm.feeType.value==4 || document.feeForm.feeType.value == 2) {// Only Transport
                    document.feeForm.transportAmtPaid.value = j.showTTransportAmtPaid;    
                    netBalance = parseFloat(netBalance) + parseFloat(j.showTTransportAmtPaid);
                 }
                 if(document.feeForm.feeType.value==4 || document.feeForm.feeType.value == 3) {// Only Hostel
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
				 calculateFeePaid();
		     },
		     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	       });
	
}
function sendKeys(eleName, e,ctr) {
	var ev = e||window.event;
	
	thisKeyCode = ev.keyCode;
	if (thisKeyCode == '13') {
		// alert("hello");
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
function getFacilityList() {
  // try {
       // var tFeeClassId = document.getElementById('feeClassId').value;
       // var tFeeType = document.getElementById('feeType').value;
       
       // document.getElementById('trFacilityIds').style.display='none';
       // document.getElementById('tdFacilityTransport').style.display='none';
       // document.getElementById('tdFacilityHostel').style.display='none';
       
       // if(tFeeClassId!='' && (tFeeType==2 || tFeeType==4)) {         // Only Transport
         // document.getElementById('trFacilityIds').style.display='';
         // document.getElementById('tdFacilityTransport').style.display='';
       // }
       
       // if(tFeeClassId!='' && (tFeeType==3 || tFeeType==4)) {        // Only Hostel 
         // document.getElementById('trFacilityIds').style.display='';
         // document.getElementById('tdFacilityHostel').style.display='';
       // }
       
       // document.getElementById('trShowPrevDues').style.display='none'; 
       // if(tFeeClassId!=='') {
         // document.getElementById('trShowPrevDues').style.display=''; 
       // }
  // } catch(e){ }
}

function calculateFeePaid(){

	var fee = parseInt(document.getElementById("feeAmtPaid").value);
	var transportF = parseInt(document.getElementById("transportAmtPaid").value);
	var hostalF = parseInt(document.getElementById("hostelAmtPaid").value);
	
	var total = fee+transportF+hostalF;
	document.getElementById("lblAmountPaid").innerHTML = total;
}

function getConfirm() {
       // document.getElementById("lblFeePaid1").style.display='none';
       // document.getElementById("lblFeePaid2").style.display='none';
       // document.getElementById("lblFeePaid3").style.display='none';
       // document.getElementById("lblFeePaid5").style.display='none';
       // document.getElementById("lblFeePaid4").style.display='';
       // document.getElementById("tblPaymentDetail1").style.display='none';
      // document.getElementById("tblPaymentDetail2").style.display='none';
       // document.getElementById("tblPaymentDetail3").style.display='none';
       if(false===confirm("<?php echo ONLINE_CONFIRM; ?>")) {
         return false;
       }
       // document.getElementById("lblFeePaid1").style.display='';
       // document.getElementById("lblFeePaid2").style.display='';
       // document.getElementById("lblFeePaid3").style.display='';
       // document.getElementById("lblFeePaid5").style.display='';
       // document.getElementById("lblFeePaid4").style.display='none';
		var totalAmt=parseInt(document.getElementById("lblAmountPaid").innerHTML);
		var totalfee=parseInt(document.getElementById("lblNetAmount").innerHTML);
		var feeTypeId=document.getElementById('feeType').value;

		var feeClassId=document.getElementById('feeClassId').value;
      window.location=window.location+'?amt='+feeClassId+'~'+feeTypeId+'~'+totalAmt+'~'+totalfee;  	
 }
 function getPaymentTerms(mode){
    document.getElementById("feeDetail").style.display='none';
    document.getElementById("divFeeAmountResult").style.display='none';
    document.getElementById("tblPaymentDetail1").style.display='none';
    document.getElementById("tblPaymentDetail2").style.display='none';
    document.getElementById("tblPaymentDetail3").style.display='none';
  	if(mode=='1'){
  	  document.getElementById("tblPaymentDetail1").style.display='';
      document.getElementById("tblPaymentDetail2").style.display='none';
      document.getElementById("tblPaymentDetail3").style.display='none';
  	}
    if(mode=='2'){
      document.getElementById("tblPaymentDetail1").style.display='none';
      document.getElementById("tblPaymentDetail2").style.display='';
      document.getElementById("tblPaymentDetail3").style.display='none';
    }
    if(mode=='3'){
      document.getElementById("tblPaymentDetail1").style.display='none';
      document.getElementById("tblPaymentDetail2").style.display='none';
      document.getElementById("tblPaymentDetail3").style.display='';
    }  
  	return false;
  }
</script>
</head>
<body>
<?php
    require_once(TEMPLATES_PATH . "/header.php");
	if(isset($_SESSION['onlineTransaction']) && $_SESSION['onlineTransaction']!= ""){
		?>
		
		
				<table width="1000px" border="0" cellspacing="0" cellpadding="0" align="center">
					<tr>
						<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									
									<td></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
							<td height="5" bgcolor="#73AACC"></td>
					</tr>         
					<tr>
						<td align="center" background="<?php echo IMG_HTTP_PATH;?>/bg.gif" style="background-repeat:repeat-x; background-position:top; padding-top:40px;">&nbsp;</td>
					</tr> 
					<tr>
						<td align="left" valign="top" class="text_class">
						<font size="5">
						<b>
						<?php 
							echo "".$_SESSION['onlineTransaction']."";
						?>
						</b>
						</font>
						</td>
					</tr>
					
				</table>
			</body>
		</html>
		
		<?php
						
		
		$_SESSION['onlineTransaction']="";
		
	}
	else	
    require_once(TEMPLATES_PATH . "/Fee/OnlineFeeCollection/listStudentOnlinePayemntContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
