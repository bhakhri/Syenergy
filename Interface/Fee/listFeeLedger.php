<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in Fee Ledger
// Author :Nishu Bindal
// Created on : 21-Mar-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeLedger');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fee Ledger </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

 //This function Validates Form
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'studentAttendanceForm'; // name of the form which will be used for search
addFormName    = 'AddDebitCreditForm';   
editFormName   = 'EditDebitCreditForm';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'ASC';




function openDebitCreditWindow(){
	displayWindow('AddDebitCredit',300,200);
	blankValues();
	//document.getElementById('currentClass').innerHTML= document.getElementById('hiddenClassName').value;
    document.getElementById('feeCycleNameSpan').innerHTML= document.getElementById('hiddenFeeCycleName').value;
	return false;
}


function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

function validateAddForm(form){
	if(document.getElementById('regRollNo').value == ''){
		messageBox('Please Enter Roll No/Reg No.');
		document.getElementById('regRollNo').focus();
		return false;
	}
	showFeeLedger();
}

function showFeeLedger(){ 
	
    form = document.allDetailsForm;
    document.AddDebitCreditForm.currentClassId.length = null;  
    addOption(document.AddDebitCreditForm.currentClassId, '', 'Select'); 
	
    document.EditDebitCreditForm.currentClassId.length = null;  
    addOption(document.EditDebitCreditForm.currentClassId, '', 'Select');    
	
    includeFine =0;
    if(document.studentAttendanceForm.includeFine.checked) {
      includeFine =1;
    }
    
    var url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeLedger/ajaxInitList.php';
	new Ajax.Request(url,
	{
		method:'post',
		asynchronous:false,
		parameters: {regRollNo: document.getElementById('regRollNo').value,
                     includeFine: includeFine   	
			        },
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){ 
			 hideWaitDialog(true);
			 if(trim(transport.responseText) == 'Invalid Reg/Noll No.'){
			 	messageBox(trim(transport.responseText));
			 	resetForm('result');
			 	return false;
			 }
			document.getElementById('studentDetail').style.display='';
			document.getElementById('resultRow').style.display='';
			var j= trim(transport.responseText).evalJSON(); 
			var feeHeadArray = 	new Array(new Array('srNo','#','width="3"',''), 
						new Array('date','Date','width="12%"',''),
						new Array('feeCycle','Fee Cycle','width="10%"',''), 
						new Array('className','Class Name','width="25%"',''),
						new Array('particulars','Particulars','width="22%"',''), 
						new Array('debit','Debit','width="8%"',' align="right"'), 
						new Array('credit','Credit','width="8%"',' align="right"'),
						new Array('balance','Balance','width="8%"',' align="right"'),
						new Array('action','Action','width="8%"',' align="right"')
			);
			printResultsNoSorting('resultsDiv', j.feeInfo, feeHeadArray);
            
            j0 = j.feeClassInfo;
            for(i=0;i<j0.length;i++) {
              addOption(document.AddDebitCreditForm.currentClassId, j0[i].classId, j0[i].className);
		addOption(document.EditDebitCreditForm.currentClassId, j0[i].classId, j0[i].className);      
            }
            
			document.getElementById('studentId').value = trim(j.studentId);
			document.getElementById('hiddenFeeCycleName').value = trim(j.feeCycleName);
			document.getElementById('hiddenFeeCycleId').value = trim(j.feeCycleId);
			//document.getElementById('hiddenClassId').value = trim(j.classId);
			document.getElementById('studentName').innerHTML = trim(j.studentName);
			document.getElementById('fatherName').innerHTML = trim(j.fatherName);
			document.getElementById('rollNo').innerHTML = trim(j.rollNo);
			//document.getElementById('hiddenClassName').value = trim(j.className);
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
	return false;

}

function addDebitCredit(){
    
    if(document.getElementById('currentClassId').value == ''){
        messageBox('Please select class');
        document.getElementById('currentClassId').focus();
        return false;
    }
    
	if(document.getElementById('particulars').value == ''){
		messageBox('Please Enter Particulars.');
		document.getElementById('particulars').focus();
		return false;
	}
	if(document.getElementById('debit').value == '' && document.getElementById('credit').value == ''){
		messageBox('Please Enter Either Debit Or Credit');
		document.getElementById('debit').focus();
		return false;
	}
	else if(isEmpty(document.getElementById('debit').value) && isEmpty(document.getElementById('credit').value)){
		messageBox('Please Enter Either Debit Or Credit');
		document.getElementById('debit').focus();
		return false;
	}
	else if(!isEmpty(document.getElementById('debit').value) && !isEmpty(document.getElementById('credit').value)){
		messageBox("Debit And Credit Can't be filed in same row");
		document.getElementById('debit').focus();
		return false;
	}
	else{
		if(!isEmpty(document.getElementById('debit').value) && !isDecimal(document.getElementById('debit').value)){
			messageBox("Please Enter Numeric Value In Debit");
			document.getElementById('debit').focus();
			return false;
		}
		else if(!isEmpty(document.getElementById('debit').value) && document.getElementById('debit').value <= 0 ){
			messageBox("Debit Should be Greater than 0.");
			document.getElementById('debit').focus();
			return false;
		}
		else if(!isEmpty(document.getElementById('credit').value) && document.getElementById('credit').value <=0){
			messageBox("Credit Should be Greater than 0.");
			document.getElementById('credit').focus();
			return false;
		}
		else if(!isEmpty(document.getElementById('credit').value) && !isDecimal(document.getElementById('credit').value)){
			messageBox("Please Enter Numeric Value In Debit");
			document.getElementById('debit').focus();
			return false;
		}
	}
	
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeLedger/ajaxInitAdd.php';
	new Ajax.Request(url,
	{
		method:'post',
		asynchronous:false,
		parameters: {	particulars: document.getElementById('particulars').value,
                                ledgerTypeId: document.getElementById('ledgerTypeId').value,
				debit: document.getElementById('debit').value,	
				credit: document.getElementById('credit').value,
				studentId:document.getElementById('studentId').value,
				classId:document.getElementById('currentClassId').value,
				feeCycleId:document.getElementById('hiddenFeeCycleId').value
			},
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){ 
			 hideWaitDialog(true);
			 if(trim(transport.responseText) == "<?php echo SUCCESS ;?>"){
			 	if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             		blankValues();
                             		return false;
                         	}
                         	else{
			 		hiddenFloatingDiv('AddDebitCredit');
			 		showFeeLedger();
			 		return false;
			 	}
			 	
			 }
			 else{
				messageBox(trim(transport.responseText));
				return false;
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

   function editWindow(id) {
 	displayWindow('EditDebitCredit',300,200);   	
    	populateValues(id);   
 }
  
  function populateValues(id) {
       var  url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeLedger/ajaxGetValues.php';
          new Ajax.Request(url,
           {
             method:'post',
             parameters: {feeLedgerDebitCreditId: id},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
             onSuccess: function(transport){
              
                     hideWaitDialog(true);
                     hideWaitDialog(true);  
                
                    	j = eval('('+trim(transport.responseText)+')');			
			
			 document.EditDebitCreditForm.particulars.value = j.comments;
			 document.EditDebitCreditForm.debit.value = j.debit;	
			 document.EditDebitCreditForm.credit.value = j.credit;
			document.getElementById('studentId').value = j.studentId;
			document.EditDebitCreditForm.currentClassId.value = j.classId;
                        document.EditDebitCreditForm.ledgerTypeId.value = j.ledgerTypeId;
			document.getElementById('feeLedgerDebitCreditId').value = j.feeLedgerDebitCreditId;
			document.getElementById('hiddenFeeCycleId').value = j.feeCycleId;
			document.getElementById('isFine').value = j.isFine;
                 	  document.EditDebitCreditForm.particulars.focus();
               
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


   function editLedger(){	
	
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeLedger/ajaxInitEdit.php';
	
	new Ajax.Request(url,
	{
		method:'post',
		asynchronous:false,
		parameters: {	particulars:  document.EditDebitCreditForm.particulars.value,
  				ledgerTypeId: document.EditDebitCreditForm.ledgerTypeId.value,
				debit: document.EditDebitCreditForm.debit.value,	
				credit: document.EditDebitCreditForm.credit.value,
				studentId:document.getElementById('studentId').value,
				classId:document.EditDebitCreditForm.currentClassId.value,
				feeCycleId:document.getElementById('hiddenFeeCycleId').value,
				isFine:document.getElementById('isFine').value,
				feeLedgerDebitCreditId:document.getElementById('feeLedgerDebitCreditId').value
			},
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){ 
			 hideWaitDialog(true);

		  if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                 	alert(trim(transport.responseText));
			
                	 hiddenFloatingDiv('EditDebitCredit');
                 	showFeeLedger();
                 return false;
            	 }
			 
			 else{
				
				messageBox(trim(transport.responseText));
				return false;
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
	
	
}

function deleteLedger(id){
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else{
         url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeLedger/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feeLedgerDebitCreditId: id},
             onCreate: function() {
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
	showFeeLedger();
}


function blankValues(){
	document.getElementById('particulars').value='';
	document.getElementById('credit').value='';
	document.getElementById('debit').value='';
	document.getElementById('particulars').focus();
}

function resetForm(mode){
	if(mode== 'all'){
		document.getElementById('regRollNo').value = '';
	}
	document.getElementById('studentDetail').style.display='none';
	document.getElementById('resultRow').style.display='none';
}



window.onload=function(){
	document.getElementById('regRollNo').focus();
	
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fee/FeeLedger/feeLedgerContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>

</html>


