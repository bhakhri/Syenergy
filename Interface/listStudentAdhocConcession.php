<?php
//-------------------------------------------------------
// Purpose: To generate student list functionality 
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentAdhocConcession');
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
<title><?php echo SITE_NAME;?>: Student Adhoc Concession</title>
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

function getAdhocCharges() {
    
   if(trim(document.getElementById('adhocCharges').value)!='') {
     if(!isDecimal(trim(document.getElementById('adhocCharges').value))) {
       messageBox ("Enter numeric value for concession amount ");
       eval("document.getElementById('adhocCharges').className='inputboxRed'");
       eval("document.getElementById('adhocCharges').focus()");  
       return false;
     }
   }
 
   var amount = trim(document.getElementById('adhocCharges').value);  
   var formx = document.allDetailsForm;
   var obj=formx.getElementsByTagName('INPUT');
   var total=obj.length;
   for(var i=0;i<total;i++) {
     if(obj[i].type.toUpperCase()=='HIDDEN' && obj[i].name.indexOf('idNos[]')>-1) {
       id =obj[i].value;
       // Integer Value Checks updated
       var feeHeadAmount = eval("document.getElementById('feeAmount"+id+"').value");
       if(parseFloat(amount,0) > 0 ) {
          if(parseFloat(feeHeadAmount,2) > parseFloat(amount,2)) {
            eval("document.getElementById('totalAmount"+id+"').value = amount");  
            amount =0;  
          }
          else {
            var tconcession = parseFloat(amount,2) - parseFloat(feeHeadAmount,0)   
            if(parseFloat(tconcession,2)<=0) {
              tconcession = parseFloat(feeHeadAmount,0);  
            }
            else {
              feeHeadAmount = parseFloat(amount,0) - parseFloat(tconcession,0);  
              eval("document.getElementById('totalAmount"+id+"').value = feeHeadAmount");  
            }
            amount -= parseFloat(feeHeadAmount,0); 
          }
       }
       else {
         eval("document.getElementById('totalAmount"+id+"').value = 0");
       }
     }
   }
   return false;
}

function validateAddForm() {
 
	/* START: search filter */
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentAdhocConcession/ajaxInitList.php';  
	
    var queryString = '';
	var form = document.allDetailsForm;
    
    vanishData();
    
    if(document.getElementById('feeClassId').value==''){
       messageBox("<?php echo SELECT_FEE_CLASS;?>");
       document.getElementById('feeClassId').focus();
       return false;
    }
    
    if(trim(document.getElementById('studentName').value)=='' && trim(document.getElementById('rollNo').value)==''){
       messageBox("<?php echo ENTER_NAME_ROLLNO;?>");
       document.getElementById('rollNo').focus();
       return false;
    }
    
    
    queryString = generateQueryString('allDetailsForm'); 
    
    new Ajax.Request(url,
    {
        method:'post',
        parameters:{ feeClassId: (document.getElementById('feeClassId').value), 
                     studentName: trim(document.getElementById('studentName').value),
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
                   document.getElementById('studentRoll').focus();
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
                
                if("<?php echo FEE_HEAD_NOT_DEFINE;?>" == trim(transport.responseText)) {      
                  document.getElementById('resultsDiv').innerHTML="<table border='0' cellspacing='0' cellpadding='3' width='100%'><tr class='rowheading'><td valign='middle' width='3%'><B>#</B></td><td valign='middle' width='60%'><B>Fee Head</B></td><td valign='middle' width='15%'><B>Fee Head Amount</B></td><td valign='middle' width='15%'><B>Concession</B></td></tr><tr class='row0'><td valign='middle' colspan='4' align='center'>No detail found</td></tr></table>";  
                  return false; 
                }
                
                document.getElementById("resultRow2").style.display='';      
                document.getElementById("resultRow1").style.display='';      
                
                var j= trim(transport.responseText).evalJSON(); 
               
                var tbHeadArray = new Array(new Array('srNo','#','width="3%"',''), 
                                            new Array('headName','Head Name','width="57%"','') , 
                                            new Array('feeHeadAmt','Fee Head Amount','width="20%"',' align="right"'), 
                                            new Array('concession','Concession Amount','width="20%"',' align="right"'));
                printResultsNoSorting('resultsDiv', j.info, tbHeadArray);
               
                document.getElementById('comments').value=j.comments;  
                document.getElementById("sName").innerHTML=j.studentinfo[0].studentName;  
                document.getElementById("sRollNo").innerHTML=j.studentinfo[0].rollNo;  
                document.getElementById("uRollNo").innerHTML=j.studentinfo[0].universityRollNo;  
                document.getElementById("rRollNo").innerHTML=j.studentinfo[0].regNo;  
                document.getElementById("fName").innerHTML=j.studentinfo[0].fatherName;  
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
    } catch(e){ }
}

function addAdhocConcession() {
                 
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentAdhocConcession/addAdhocConcession.php';
     
    var formx = document.allDetailsForm;
    var obj=formx.getElementsByTagName('INPUT');
    var total=obj.length;
    var studentString='';
    for(var i=0;i<total;i++) {
       if(obj[i].type.toUpperCase()=='HIDDEN' && obj[i].name.indexOf('idNos[]')>-1) {
            id =obj[i].value;
            // Integer Value Checks updated
            var concessionAmount = trim(eval("document.getElementById('totalAmount"+id+"').value"));
            var feeHeadId = trim(eval("document.getElementById('feeHeadId"+id+"').value")); 
            if(concessionAmount=='') {
              concessionAmount=0; 
            }
            if(trim(eval("document.getElementById('totalAmount"+id+"').value"))!='') {  
              if(!isDecimal(trim(eval("document.getElementById('totalAmount"+id+"').value")))) {                          
                messageBox ("Enter numeric value for amount");
                eval("document.getElementById('totalAmount"+id+"').className='inputboxRed'");
                eval("document.getElementById('totalAmount"+id+"').focus()");  
                return false;
              }
            }
            if(trim(eval("document.getElementById('totalAmount"+id+"').value"))!='') { 
               if(parseFloat(trim(eval("document.getElementById('totalAmount"+id+"').value")),2) > parseFloat(trim(eval("document.getElementById('feeAmount"+id+"').value")),2)) { 
                 messageBox ("Concession Amount not greater than Fee Head Amount");
                 eval("document.getElementById('totalAmount"+id+"').className='inputboxRed'");
                 eval("document.getElementById('totalAmount"+id+"').focus()");  
                 return false; 
               }
            }
            if(studentString!='') {  
              studentString = studentString + "!~!~!";
            }
            studentString += feeHeadId+'_'+concessionAmount; 
       }
    }
   
   
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
                    studentString : studentString 
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
             validateAddForm();
             //location.reload();
             return false;
           } 
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
    require_once(TEMPLATES_PATH . "/StudentAdhocConcession/listStudentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
