<?php
//-------------------------------------------------------
// Purpose: To generate student list functionality 
//
// Author : Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentAdhocConcessionNew');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
$showTitle = "none";
$showData  = "none";
$showPrint = "none";
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Student Adhoc Concession New</title>
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
var topPos = 0;
var leftPos = 0;

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
//addFormName    = 'AddState';   
//editFormName   = 'EditState';
//winLayerWidth  = 315; //  add/edit form width
//winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteState';
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'studentName';
sortOrderBy    = 'ASC';
var queryString='';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
        displayWindow(dv,w,h);
        populateValues(id);
}




//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET Classes 
//Author : Nishu Bindal
//Created on : (6.Feb.2012)
// Copyright 2012-2013 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getClass() { 
	form = document.allDetailsForm;
	if(trim(document.getElementById('rollNo').value)==''){
		form.classId.length = null;
		addOption(form.classId, '', 'Select');
		messageBox("<?php echo ENTER_NAME_ROLLNO;?>");
		document.getElementById('rollNo').focus();
		return false;
	}
	var url = '<?php echo HTTP_LIB_PATH;?>/Fee/StudentAdhocConcession/getClasses.php';
	new Ajax.Request(url,
	{
		method:'post',
		parameters: {	rollNo: trim(document.getElementById('rollNo').value)
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

function resetResult(){
	document.getElementById('comments').value=''; 
	document.getElementById('resultsDiv').innerHTML=''; 
	document.getElementById('totalFee').innerHTML = '';
	document.getElementById('feeValueHidden').value = '';
	document.getElementById('discountAmount').innerHTML = '';
	document.getElementById('discountValueHidden').value = '';
	document.getElementById('adhocCharges').value = '';
	document.getElementById('payableAmount').innerHTML = '';
	document.getElementById("sName").innerHTML='';  
	document.getElementById("sRollNo").innerHTML='';  
	document.getElementById("uRollNo").innerHTML='';  
	document.getElementById("rRollNo").innerHTML='';  
	document.getElementById("fName").innerHTML=''; 
	document.getElementById("studentId").value = '';
	
	 document.getElementById("nameRow").style.display='none';
        document.getElementById("nameRow2").style.display='none';
        document.getElementById("resultRow").style.display='none';
        document.getElementById("resultRow1").style.display='none'; 
        document.getElementById("resultRow2").style.display='none'; 
        
        document.getElementById("adhocChargesHidden").style.display='none';
        document.getElementById("adhocCharges").value='';
        document.getElementById("comments").value=''; 
        
        document.getElementById("sName").innerHTML=''; 
        document.getElementById("sName").innerHTML=''; 
        document.getElementById("sRollNo").innerHTML=''; 
        document.getElementById("uRollNo").innerHTML=''; 
        document.getElementById("rRollNo").innerHTML=''; 
        document.getElementById("fName").innerHTML=''; 
        document.getElementById("comments").value='';
       
}

function getAdhocCharges() { 
	var discountAmount = '';
	var amount = '';
	if(trim(document.getElementById('adhocCharges').value)!='') {
		if(!isDecimal(document.getElementById('adhocCharges').value)) {
			messageBox ("Enter numeric value for concession amount ");
			eval("document.getElementById('adhocCharges').className='inputboxRed'");
			eval("document.getElementById('adhocCharges').focus()");  
			return false;
		}
		fee = trim(document.getElementById('feeValueHidden').value);
		fee = parseFloat(fee,2);
		if(!document.getElementById('fixedRadio').checked){
			percentAmount = trim(document.getElementById('adhocCharges').value);
			if(percentAmount <= 100){ 
				discountAmount = ((percentAmount/100) * fee);
			}
			else{
				messageBox("Concession Can't be Greater than 100 %");
				eval("document.getElementById('adhocCharges').className='inputboxRed'");
				eval("document.getElementById('adhocCharges').focus()"); 
				
			}
		}
		else{
			amount = trim(document.getElementById('adhocCharges').value);
			amount = parseFloat(amount,2);
			if(fee >= amount){
				discountAmount = amount;
			}
			else{
				messageBox("Concession Can't be Greater than Total Fees");
				eval("document.getElementById('adhocCharges').className='inputboxRed'");
				eval("document.getElementById('adhocCharges').focus()"); 
			}
		}
		document.getElementById('discountAmount').innerHTML = (parseFloat(discountAmount,2)).toFixed(2);
		document.getElementById('discountValueHidden').value = (parseFloat(discountAmount,2)).toFixed(2);
		document.getElementById('payableAmount').innerHTML = (fee - discountAmount).toFixed(2);
	}
	else{
		document.getElementById('discountAmount').innerHTML = '0.00';
		document.getElementById('discountValueHidden').value = '0.00';
		document.getElementById('payableAmount').innerHTML = fee.toFixed(2);
	}
	return false;
}



function validateAddForm() {

	/* START: search filter */
    var url = '<?php echo HTTP_LIB_PATH;?>/Fee/StudentAdhocConcession/ajaxInitList.php';  
	
    var queryString = '';
	var form = document.allDetailsForm;
    
  if(trim(document.getElementById('rollNo').value)==''){
       messageBox("<?php echo ENTER_NAME_ROLLNO;?>");
       document.getElementById('rollNo').focus();
       return false;
    }
    
    if(trim(document.getElementById('classId').value)==""){
      messageBox("<?php echo SELECT_CLASS; ?>");
      document.getElementById('classId').focus();
      return false;
    } 
    
    queryString = generateQueryString('allDetailsForm'); 
    
    new Ajax.Request(url,
    {
        method:'post',
        parameters:{ classId: (document.getElementById('classId').value), 
                     rollNo: trim(document.getElementById('rollNo').value)
                   },
            onCreate:function(transport){ showWaitDialog(true);},
            onSuccess: function(transport) {
                hideWaitDialog(true);
                if("<?php echo SELECT_FEE_CLASS;?>" == trim(transport.responseText)) { 
                   messageBox(trim(transport.responseText));  
                   document.getElementById('feeClassId').focus();
                   return false;
                }
                if("<?php echo ENTER_NAME_ROLLNO;?>" == trim(transport.responseText)) { 
                   messageBox(trim(transport.responseText));  
                   document.getElementById('studentRoll').focus();
                   return false;
                }
                
                if("<?php echo FEE_HEAD_NOT_DEFINE;?>" == trim(transport.responseText)) { 
                   messageBox(trim(transport.responseText));  
                   return false;
                }
                
                if("<?php echo STUDENT_NOT_EXIST;?>" == trim(transport.responseText)) { 
                   messageBox(trim(transport.responseText));  
                   document.getElementById('rollNo').focus();
                   return false;
                }
                
                if("<?php echo STUDENT_CONCESSION_CATEGORY;?>" == trim(transport.responseText)) { 
                   messageBox(trim(transport.responseText));  
                   return false;
                }
                
                document.getElementById("adhocChargesHidden").style.display='';
                document.getElementById("adhocCharges").value='';
                
                document.getElementById("nameRow").style.display='';
                document.getElementById("nameRow2").style.display='';
                document.getElementById("resultRow").style.display='';
                document.getElementById("resultRow2").style.display='';      
                document.getElementById("resultRow1").style.display='';  
                
                if("<?php echo FEE_HEAD_NOT_DEFINE;?>" == trim(transport.responseText)) {      
                  document.getElementById('resultsDiv').innerHTML="<table border='0' cellspacing='0' cellpadding='3' width='100%'><tr class='rowheading'><td valign='middle' width='3%'><B>#</B></td><td valign='middle' width='60%'><B>Fee Head</B></td><td valign='middle' width='15%'><B>Fee Head Amount</B></td><td valign='middle' width='15%'><B>Concession</B></td></tr><tr class='row0'><td valign='middle' colspan='4' align='center'>No detail found</td></tr></table>";  
                  return false; 
                }
                         
                var j= trim(transport.responseText).evalJSON(); 
                document.getElementById("sName").innerHTML=j.studentinfo[0].studentName;  
                document.getElementById("sRollNo").innerHTML=j.studentinfo[0].rollNo;  
                document.getElementById("uRollNo").innerHTML=j.studentinfo[0].universityRollNo;  
                document.getElementById("rRollNo").innerHTML=j.studentinfo[0].regNo;  
                document.getElementById("fName").innerHTML=j.studentinfo[0].fatherName; 
                document.getElementById("studentId").value = j.studentinfo[0].studentId;
                document.getElementById("currentClassId").value = j.currentClassId;  
                
                var tbHeadArray = new Array(new Array('srNo','#','width="3%"',''), 
                                            new Array('headName','Head Name','width="57%"','') , 
                                            new Array('feeHeadAmt','Fee Head Amount','width="20%"',' align="right"') 
                                           );
                printResultsNoSorting('resultsDiv', j.info, tbHeadArray);
                document.getElementById('totalFee').innerHTML = j.totalFee; 
                document.getElementById('feeValueHidden').value = j.totalFee;
                document.getElementById('discountAmount').innerHTML = j.concessionAmount;
                document.getElementById('discountValueHidden').value = j.concessionAmount;
                if(j.concessionAmount == 0){
                	document.getElementById('adhocCharges').value = '';
                	document.getElementById('comments').value=''; 
                	document.getElementById('deleteConcession').style.display ='none';
                	
                }
                else{
                	document.getElementById('adhocCharges').value = j.concessionAmount;
                	document.getElementById('comments').value=j.comments;
                	document.getElementById('deleteConcession').style.display ='';
                	
                }
                document.getElementById('payableAmount').innerHTML = (j.totalFee - j.concessionAmount).toFixed(2);
                document.getElementById('fixedRadio').checked=true;
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
           
    return false;       
}

/* function to print all student report*/
function printReport() {
	 
	queryString += '&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	 
    qtr = "<?php echo $queryString?>";
	//if(qtr!='')
		//queryString = qtr;

//alert(queryString);
	form = document.allDetailsForm;
    
    var alumniStudent=1;
    if(form.alumniStudent[0].checked==true){
       alumniStudent=1; 
    }
    else if(form.alumniStudent[1].checked==true){
       alumniStudent=2; 
    }
    else{
       alumniStudent=3; 
    }
    
    queryString += '&alumniStudent='+alumniStudent;
    
	path='<?php echo UI_HTTP_PATH;?>/searchStudentReportPrint.php?listStudent=1&'+queryString;
	//alert(path);
	window.open(path,"MissedAttendanceReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to print student profile report*/
function printStudentReport(studentId,classId) {
	
	var form = document.allDetailsForm;
	path='<?php echo UI_HTTP_PATH;?>/studentPrint.php?studentId='+studentId+'&classId='+classId;
	window.open(path,"MissedAttendanceReport","status=1,menubar=1,scrollbars=1, width=700, height=500, top=100,left=50");
}

/* function to print all student report*/
function printStudentCSV() {

	queryString += '&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    qtr = "<?php echo $queryString?>";
	if(qtr!='')
		queryString = qtr;
	
    var alumniStudent=1;
    form = document.allDetailsForm;
    if(form.alumniStudent[0].checked==true){
       alumniStudent=1; 
    }
    else if(form.alumniStudent[1].checked==true){
       alumniStudent=2; 
    }
    else{
       alumniStudent=3; 
    }
    
    queryString += '&alumniStudent='+alumniStudent;
    
	window.location='searchStudentReportCSV.php?'+queryString;

	/*queryString += '&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    qtr = "<?php echo $queryString?>";
	if(qtr!='')
		queryString = qtr;

	document.getElementById('generateCSV').href='searchStudentReportCSV.php?queryString='+queryString;
	document.getElementById('generateCSV1').href='searchStudentReportCSV.php?queryString='+queryString;
	*/
}
//populate list
window.onload=function(){

	//alert("<?php echo $queryString?>");
   if("<?php echo $queryString?>"!=''){
       sendReq(listURL,divResultName,searchFormName,"<?php echo $queryString?>");
       document.getElementById("nameRow").style.display='';
       document.getElementById("nameRow2").style.display='';
       document.getElementById("resultRow").style.display='';
       
   }
   var roll = document.getElementById("rollNo");
   autoSuggest(roll);
}

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

function vanishData(){ 
    try {
   
	
       
        document.getElementById("nameRow").style.display='none';
        document.getElementById("nameRow2").style.display='none';

        document.getElementById("resultRow").style.display='none';
        document.getElementById("resultRow1").style.display='none'; 
        document.getElementById("resultRow2").style.display='none'; 
        
        document.getElementById("adhocChargesHidden").style.display='none';
        document.getElementById("adhocCharges").value='';
        document.getElementById("comments").value=''; 
        
        document.getElementById("sName").innerHTML=''; 
        document.getElementById("sName").innerHTML=''; 
        document.getElementById("sRollNo").innerHTML=''; 
        document.getElementById("uRollNo").innerHTML=''; 
        document.getElementById("rRollNo").innerHTML=''; 
        document.getElementById("fName").innerHTML=''; 
        document.getElementById("comments").value='';
        document.getElementById("rollNo").value=''; 
        
        
	form = document.allDetailsForm; 
	form.classId.length = null;
	addOption(form.classId, '', 'Select');
	/*
	document.getElementById('degreeId').selectedIndex = 0;
	 
        form.batchId.length = null;
	addOption(form.batchId, '', 'Select');
	form.branchId.length = null;
	addOption(form.branchId, '', 'Select');
	*/
    } catch(e){ }
}

function addAdhocConcession() {
                 
    var url = '<?php echo HTTP_LIB_PATH;?>/Fee/StudentAdhocConcession/addAdhocConcession.php';
   
     if(trim(document.getElementById("adhocCharges").value) =='') { 
       messageBox ("Enter Concession Amount");
       document.getElementById("adhocCharges").className='inputboxRed';
       document.getElementById("adhocCharges").focus();  
       return false;
    }
    /*
    if(trim(document.getElementById("adhocCharges").value) == 0) { 
       messageBox ("Concession Amount Should Be Greater Than 0");
       document.getElementById("adhocCharges").className='inputboxRed';
       document.getElementById("adhocCharges").focus();  
       return false;
    }
     */
    if(trim(document.getElementById("comments").value)=='') { 
       messageBox ("Enter reason");
       document.getElementById("comments").className='inputboxRed';
       document.getElementById("comments").focus();  
       return false;
    }
    
    //params = generateQueryString('allDetailsForm');  
    new Ajax.Request(url,
    {
       method:'post',
       parameters:{ comments : trim(document.getElementById("comments").value),
       		    concession : trim(document.getElementById('discountValueHidden').value),
       		    classId : trim(document.getElementById('classId').value),
       		    studentId : trim(document.getElementById('studentId').value),
       		    currentClassId : trim( document.getElementById("currentClassId").value) 
                    
                  },
       asynchronous:false,                                        
       onSuccess: function(transport){
         if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
           showWaitDialog(true);
         }
         else {
           hideWaitDialog(true);
           messageBox(trim(transport.responseText)); 
           if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {   
            	vanishData();
           } 
         }
       },
       onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}
function deleteConcession(){
	
 if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {
         	url = '<?php echo HTTP_LIB_PATH;?>/Fee/StudentAdhocConcession/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
              asynchronous :false,
             parameters: {
                     classId: (document.getElementById('classId').value), 
                     rollNo: trim(document.getElementById('rollNo').value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true); 
                    if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                    	messageBox(trim(transport.responseText));
                        validateAddForm();
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

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fee/StudentAdhocConcession/listStudentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
