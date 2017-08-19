<?php 
//-------------------------------------------------------------------
// This File contains the show details of Student Internal Subject Re-appear detail   
// Author :Parveen Sharma
// Created on : 19-01-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisplayStudentReappear');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Display Student Internal Re-appear </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?>

<script language="javascript">

//This function Validates Form 
var tableHeadArray = new Array(
     new Array('srNo','#','width="2%"','',false), 
     new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="3%"','align=\"left\" valign="middle" ',false),
     new Array('studentName','Student Name','width="12%"','align="left" valign="middle" ',true),  
     new Array('currentClassName','Current Class Name','width="20%"','align="left" valign="middle" ',true),  
     new Array('rollNo','Roll No.','width="8%"','align="left" valign="middle" ',true),  
     new Array('universityRollNo','Univ. Roll No.','width="12%"','align="left" valign="middle" ',true),  
     new Array('reappearClassName','Re-appear Class Name','width="20%','align="left" valign="middle" ',true),  
     new Array('subjects','Re-appear Subject Code<br><span style="color:red;font-size:10px;font-weight:bold;font-family:Arial, Helvetica, sans-serif;">Subject Status / Student Detained</span>','width="20%','align="left" valign="middle" ',false), 
     new Array('action1','Action','width="7%"','align="center" valign="middle" ',false));


var listURL='<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxStudentInternalReappear.php'; 
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'Name';
sortOrderBy    = 'Asc';

queryString = "";
studentIdds = "";
pars1 = "";

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
    document.getElementById('nameRow4').style.display='none';
}

function validateAddForm() {

   studentIdds = ""; 
   queryString = "";     
   if(trim(document.getElementById('reappearClassId').value)=="") {
      messageBox("<?php echo SELECT_CLASS ?>");
      document.getElementById('reappearClassId').focus();
      return false;
   }
   
   if(!isEmpty(document.getElementById('startDate').value)) {
       if(isEmpty(document.getElementById('endDate').value)) {
        messageBox("<?php echo EMPTY_DATE_TO;?>");  
        document.getElementById('endDate').focus();
        return false;
       }  
   }    
   
    if(!isEmpty(document.getElementById('endDate').value)) {
       if(isEmpty(document.getElementById('startDate').value)) {
        messageBox("<?php echo EMPTY_DATE_FROM;?>");  
        document.getElementById('endDate').focus();
        return false;
       }   
    }  
   
   if(!isEmpty(document.getElementById('startDate').value) && !isEmpty(document.getElementById('endDate').value)) { 
     if(!dateDifference(eval("document.getElementById('startDate').value"),eval("document.getElementById('endDate').value"),'-') ) {
        messageBox ("<?php echo DATE_CONDITION;?>");
        eval("document.getElementById('startDate').focus();");
        return false;
     } 
   }
      
   hideResults();   
   queryString  = "&classId="+document.getElementById('reappearClassId').value+"&rollNo="+document.getElementById('rollNo').value;
   queryString += "&startDate="+document.getElementById('startDate').value+"&endDate="+document.getElementById('endDate').value;
   sendReq(listURL,divResultName,searchFormName,' ',false);
   
   document.getElementById("nameRow").style.display='';
   document.getElementById("nameRow2").style.display='';
   document.getElementById("resultRow").style.display='';
   document.getElementById("nameRow4").style.display=''; 
    
   return false;
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
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
                formx.elements[i].checked=false;
            }
        }
    }
}

function approveValidation() {
   
    formx = document.allDetailsForm;
    studentId = "";
    
    for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
            if((formx.elements[i].checked) && formx.elements[i].name=="chb[]"){
              if(studentId=='') {
                   studentId='('+formx.elements[i].value+')'; 
                }
                else {
                    studentId = studentId+',('+formx.elements[i].value+')'; 
                }
            }
        }
    }
    
    // $reppearStatusArr = array("1"=>"Approved","2"=>"Not Approved","3"=>"Pending Approval");    
    reappearStatus = -1;
    len = formx.reappearStatus.length;
    
    if(len==1) {
      if(formx.reappearStatus.checked==true) {
        reappearStatus = formx.reappearStatus.value;
      }  
    }
    else 
    if(len>0) {
     for(i=0;i<len;i++){
        if(formx.reappearStatus[i].checked==true) {
          reappearStatus = formx.reappearStatus[i].value;
          break;
        }
      }
    }
   
    studentDetained = -1;
    if(formx.studentDetained.checked==true) {
      studentDetained = formx.studentDetained.value;
    }
    
    
    if(studentDetained == -1 &&  reappearStatus == -1) {
       alert("Select student status");
       return false;
    }
    
    if(studentId=='')    {
       alert("Please select atleast one student record");
       return false;
    }   
    
    pars = queryString + "&studentId="+studentId+"&reappearStatus="+reappearStatus+"&studentDetained="+studentDetained; 
    
    var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxAdminApproval.php';
    new Ajax.Request(url,
    {
       method:'post',
       asynchronous:false,
       parameters: pars,
       onCreate: function(){
         showWaitDialog(true);
       },
       onSuccess: function(transport){
             hideWaitDialog(true);
             if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {  
                messageBox(trim(transport.responseText));                   
                location.reload();
             }
             else {
                messageBox(trim(transport.responseText));
                return false;
             }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
     });
    
    window.scroll(1000,1000);
    return false; 
}


function deleteApproval(id) {
   
    pars = "studentId="+id; 
    if(false===confirm("Do you want to delete selected student?")) {
       return false;
    }
    else {   
        var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxAdminDeleteApproval.php';
        new Ajax.Request(url,
        {
           method:'post',
           asynchronous:false,
           parameters: pars,
           onCreate: function(){
             showWaitDialog(true);
           },
           onSuccess: function(transport){
                hideWaitDialog(true);
                if("<?php echo DELETE;?>" == trim(transport.responseText)) {  
                  messageBox(trim(transport.responseText));                   
                  location.reload();
                }
                else {
                  messageBox(trim(transport.responseText));
                  return false;
                }
            },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
         });
    }
}


//-------------------------------------------------------
function refreshStudentData(id,studentName,rollNo,repClassName) {
  
  var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxAdminStudentApproval.php';
  document.getElementById("divStudentName").innerHTML='';
  document.getElementById('divRollNo').innerHTML='';
  var tableColumns = new Array(
         new Array('srNo','#','width="3%" valign="middle"',false), 
         new Array('subjectName','Subject Name','width="24%" align="left" valign="middle"',true),  
         new Array('subjectCode','Subject Code','width="16%" align="left" valign="middle"',true),  
         new Array('subjectTypeName','Subject Type','width="16%" align="left" valign="middle"',true),  
         new Array('reapStatus','Re-appear Status','width="22%" align="left" valign="middle"',false),  
         new Array('studentDetained','Student Detained <input type=\"checkbox\" id=\"checkbox4\" name=\"checkbox4\" onclick=\"doAll2();\">',' width="20%" align="center"  valign="middle"',false));
 
    //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
    showWaitDialog(true); 
    listObj2 = new initPage(url,recordsPerPage,linksPerPage,1,'','subjectName','ASC','showResultDiv','','',true,'listObj2',tableColumns,'','','&studentId='+id);
    sendRequest(url, listObj2, '',false);
    document.getElementById("divStudentName").innerHTML=studentName;
    document.getElementById('divRollNo').innerHTML=rollNo;
    document.getElementById('divRepClassName').innerHTML=repClassName;
    hideWaitDialog(true); 
    pars1 = generateQueryString('showDetailsForm');
}  


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "Publisher Details" DIV
function refreshShowStudentDetails(id,studentName,rollNo,repClassName,dv,w,h) {
    //displayWindow('divMessage',600,600);
    refreshStudentData(id,studentName,rollNo,repClassName);
    displayFloatingDiv(dv,'', 650,350, 605, 350)
    studentIdds = id;
}


function doAll2(){
    formx = document.showDetailsForm;
    if(formx.checkbox4.checked){
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="studentDetained[]"){
                formx.elements[i].checked=true;
            }
        }
    }
    else{
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="studentDetained[]"){
                formx.elements[i].checked=false;
            }
        }
    }
}

function updateApproval() {
    
    pars = generateQueryString('showDetailsForm');  

    if(pars1==pars) {
       messageBox("<?php echo "Please select alteast one option Re-appear Status/Student Detained " ?>"); 
       return false;  
    }    
    
    var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/addStudentReapper.php';
       new Ajax.Request(url,
       {
           method:'post',
           asynchronous:false,
           parameters: pars,
           onCreate: function(){
             showWaitDialog(true);
           },
           onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {  
                    messageBox(trim(transport.responseText));                   
                    //location.reload();
                    //sendRequest(url, listObj1, '',true);
                    hiddenFloatingDiv('divInfo');
                    validateAddForm();
                 }
                 else {
                    messageBox(trim(transport.responseText));
                    return false;
                 }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
    return false;
}


function hiddenFloatingDiv(divId) 
{
    //document.getElementById(divId).innerHTML = originalDivHTML;
    document.getElementById(divId).style.visibility='hidden';
    //document.getElementById('dimmer').style.visibility = 'hidden';
    document.getElementById('modalPage').style.display = "none";
    makeMenuDisable('qm0',false);
    over=false;      
    DivID = "";
    if(document.getElementById('containfooter'))
    {
        document.getElementById('containfooter').style.display='';
    }
}

function sendKeys(e) {
   var ev = e||window.event;
   thisKeyCode = ev.keyCode;
   if (thisKeyCode == '13') {
      document.getElementById('sSubmit').focus();
      //window.event.sSubmit.focus()
      return false;
   }
}

</script> 
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/AdminTasks/listStudentInternalReappearContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php
//History: displayStudentInternalReappear.php $
//
//

?>