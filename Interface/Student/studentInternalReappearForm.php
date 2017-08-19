<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Student Internal Reappear Form
//
//
// Author :Parveen Sharma
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentReappear');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();
include_once(BL_PATH ."/Student/initStudentInformation.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Student Internal Re-appear Form</title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');

function parseOutput($data){
  return ( (trim($data)!="" ? $data : NOT_APPLICABLE_STRING ) );  
}
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(
     new Array('srNo','#','width="3%"',' valign="middle"',false), 
     new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="4%" align=\"center\" ','align=\"center\"  valign="middle"',false),
     new Array('subjectName','Subject Name','width="30%"','align="left" valign="middle"',true),  
     new Array('subjectCode','Subject Code','width="15%"','align="left" valign="middle"',true),  
     new Array('subjectTypeName','Subject Type','width="15%"','align="left" valign="middle"',true),  
     new Array('reppearStatus','Re-appear Status','width="15%"','align="left" valign="middle"',true),
     new Array('detained','Student Detained','width="15%"','align="center" valign="middle"',true));

                               
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitReappearList.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'reppearStatus';
sortOrderBy    = 'ASC';
queryString = "";
msg = "";
// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      
//This function Displays Div Window

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
    document.getElementById('nameRow4').style.display='none';
                        
    document.getElementById('assignmentChk').checked = false; 
    document.getElementById('midSemesterChk').checked = false; 
    document.getElementById('attendanceChk').checked = false;  
}

function validateAddForm() {   
    
    queryString = "";
    if(document.getElementById('classId').value =='') {
       messageBox("<?php echo SELECT_CLASS;?>");
       document.allDetailsForm.classId.focus();    
       return false;
    }  
    
    queryString = document.getElementById('classId').value;
    sendReq(listURL,divResultName,searchFormName,' ',false);
 
    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("resultRow").style.display='';
    document.getElementById('nameRow4').style.display='';
    
    if (typeof j.assign === "undefined") {
       document.getElementById('assignmentChk').checked = false;  
    }
    else if(j.assign==1) {
      document.getElementById('assignmentChk').checked = true;  
    }
    else {
      document.getElementById('assignmentChk').checked = false; 
    }
   
    if (typeof j.midsem === "undefined") {
       document.getElementById('midSemesterChk').checked = false;  
    }
    else if(j.midsem==1) {
      document.getElementById('midSemesterChk').checked = true;  
    }
    else {
      document.getElementById('midSemesterChk').checked = false; 
    }
    
    if (typeof j.atte === "undefined") {
       document.getElementById('attendanceChk').checked = false;  
    }
    else if(j.atte==1) {
      document.getElementById('attendanceChk').checked = true;  
    }
    else {
      document.getElementById('attendanceChk').checked = false; 
    }
}

//-------------------------------------------------------
//THIS FUNCTION addRoom() IS USED TO ADD NEW GROUP TYPE
//
//Author : Parveen Sharma
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addReappearSubjects() {
   
    var formx = document.allDetailsForm;      
   
    var subjectId='';
    var reappears= '';
    var attendanceChk = 0; 
    var assignmentChk = 0; 
    var midSemesterChk =0;
    
    var chk = 0;
    if (document.getElementById('assignmentChk').checked==true) {
      assignmentChk = 1;
      chk = 1; 
    }
    
    if (document.getElementById('midSemesterChk').checked==true) {
      midSemesterChk = 1;
      chk = 1; 
    }
    
    if (document.getElementById('attendanceChk').checked==true) {
      attendanceChk = 1;
      chk = 1; 
    }
    
    msg = 0;
    for(var i=1;i<formx.length;i++){
       if(formx.elements[i].type=="hidden" && formx.elements[i].name=="reapperId[]") {
          if(formx.elements[i].value!=-1) {
             msg=1;
             break;
          }
       }  
    }
    
    chk1=0;
    // Un select all check box
    for(var i=1;i<formx.length;i++){
       if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]") {
          if((formx.elements[i].checked) && formx.elements[i].name=="chb[]") {
            chk1++;
          }
       }  
    }
    
    if(msg==0 && chk1==0) {
      messageBox("<?php echo REGISTRATION_SUBJECT; ?>");   
      return false;
    }

    if(chk==0 && chk1>0) {
      messageBox("<?php echo "Select Cause of Detention / Re-appear";?>");   
      document.allDetailsForm.assignmentChk.focus();
      return false;   
    }
    
    for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
            if((formx.elements[i].checked) && formx.elements[i].name=="chb[]"){
              if(subjectId=='') {
                   subjectId=formx.elements[i].value; 
                   reappears = eval("document.getElementById('reappearId"+(formx.elements[i].value)+"').value");
                }
                else {
                    subjectId = subjectId+','+formx.elements[i].value; 
                    reappears = reappears+','+eval("document.getElementById('reappearId"+(formx.elements[i].value)+"').value"); 
                }
            }
        }
    }
    
   pars  = queryString+"&subjectId="+subjectId+"&reappearId="+reappears+"&msg="+msg;
   pars += "&midSemesterChk="+midSemesterChk+"&assignmentChk="+assignmentChk+"&attendanceChk="+attendanceChk; 

   if(msg==1 && chk1==0) {
      if(false===confirm("<?php echo REGISTRATION_CANCEL; ?>")) {
        return false;
      }
   }

   var url = '<?php echo HTTP_LIB_PATH;?>/Student/addStudentReapper.php';
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
             if("<?php echo REGISTRATION_SUBMITTED;?>" == trim(transport.responseText)) {  
                messageBox(trim(transport.responseText));                   
                location.reload();
             }
             else if("<?php echo REGISTRATION_DELETE;?>" == trim(transport.responseText)) {  
                messageBox(trim(transport.responseText));                   
                location.reload();
             }
             else if("<?php echo REGISTRATION_UPDATED;?>" == trim(transport.responseText)) {  
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

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/listStudentInternalReappearContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
//$History: studentInternalReappearForm.php $
//
//*****************  Version 12  *****************
//User: Parveen      Date: 1/28/10    Time: 5:40p
//Updated in $/LeapCC/Interface/Student
//validation & format update (button & radio button updated)
//
//*****************  Version 11  *****************
//User: Parveen      Date: 1/19/10    Time: 6:27p
//Updated in $/LeapCC/Interface/Student
//function & validation message and format updated
//
//*****************  Version 10  *****************
//User: Parveen      Date: 1/15/10    Time: 5:35p
//Updated in $/LeapCC/Interface/Student
//format and validation updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 1/15/10    Time: 12:32p
//Updated in $/LeapCC/Interface/Student
//validation & sorting format updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 1/15/10    Time: 10:03a
//Updated in $/LeapCC/Interface/Student
//format updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 1/14/10    Time: 5:37p
//Updated in $/LeapCC/Interface/Student
//page title updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 1/14/10    Time: 2:43p
//Updated in $/LeapCC/Interface/Student
//validation format update
//
//*****************  Version 5  *****************
//User: Parveen      Date: 1/14/10    Time: 2:15p
//Updated in $/LeapCC/Interface/Student
//checks updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 1/13/10    Time: 2:12p
//Updated in $/LeapCC/Interface/Student
//subjectId base checks updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 1/12/10    Time: 5:26p
//Updated in $/LeapCC/Interface/Student
//validation message updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/09/10    Time: 5:22p
//Updated in $/LeapCC/Interface/Student
//look & feel updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/09/10    Time: 1:04p
//Created in $/LeapCC/Interface/Student
//initial checkin
//

?>
