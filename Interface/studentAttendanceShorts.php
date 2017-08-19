<?php
//-----------------------------------------------------------------------------
//  To generate Studnet Attendance Short functionality      
//
//
// Author :Parveen Sharma
// Created on : 06-03-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentAttendanceShortReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Attendance Short Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(
                     new Array('srNo','#','width="2%"','',false), 
                     new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="3%"','align=\"left\"',false), 
                     //new Array('checkAll','<input type="checkbox" id="checkbox2" name="checkbox2" value="checkbox" onClick="doAll()">','width="3%"','',false),
                     new Array('rollNo','Roll No.','width="9%"','align="left"',true), 
                     new Array('universityRollNo','Univ. Roll No.','width="12%"','',true), 
                     new Array('studentName','Name','width="15%"','align="left"',true), 
                     new Array('fatherName','Father`s Name','width="15%"','align="left"',true),  
                     new Array('imgSrc','Photo','width="5%" align="center"','align="center"',false),    
                     new Array('studentMobileNo','Contact No.','width=12%','align="left"',true),
                     new Array('corrAdd','Corr. Address','width="15%"','align="left"',false),
                     new Array('permAdd','Perm. Address','width="15%"','align="left"',false)
                  );

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initStudentAttendanceShortReport.php';
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
sortField = 'rollNo';
sortOrderBy    = 'Asc';
 //This function Validates Form 


var studentCheck;
var queryString;
var queryClass;

function validateAddForm() {
   
   studentCheck = ""; 
   queryString = "";
   queryClass = "";

   if(document.getElementById("labelId").value == '') {
      messageBox("<?php echo SELECT_TIME_TABLE; ?>");  
       document.getElementById('labelId').focus();         
       return false;
   }
    
   if(document.getElementById('classId').value=='') {
       messageBox("<?php echo "Select Class "; ?>");
       document.getElementById('classId').focus();    
       return false;
   }
   
   if(trim(document.getElementById('rollno').value)!='') {
     if(document.getElementById('classId').value=='') {
        messageBox("<?php echo "Select Class "; ?>");
        document.getElementById('classId').focus();    
        return false;
     }   
   } 
   
   if(trim(document.getElementById('percentage').value)==""){
     messageBox("<?php echo "Enter value for percentage" ?>");
     document.getElementById('percentage').focus();
     return false;
   }  
   
   if(!isNumericCustom(document.getElementById('percentage').value,".-")) {
        messageBox("Enter only numeric value");
        document.getElementById('percentage').focus();
        return false;
   } 
   
   if(!isDecimal(document.getElementById('percentage').value)) {
      messageBox("Enter only numeric value");
      document.getElementById('percentage').focus();
      return false;
   }
   
   if(document.getElementById('percentage').value < 0 || document.getElementById('percentage').value > 100 ){
      messageBox("Percentage can not more than 100 and less than 0");
      document.getElementById('percentage').focus();
      return false;
   }
   
   hideDetails();
   queryString = generateQueryString('allDetailsForm');
   
   page = 1;
   
   timeTable = escape(document.getElementById('labelId').value);    
   classId = escape(document.getElementById('classId').value);
   rollno = escape(document.getElementById('rollno').value);
   per = escape(document.getElementById('percentage').value);     
   
   queryClass = '&labelId='+timeTable+'&classId='+classId+'&rollno='+rollno+'&percentage='+per;    
   
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false);
   
   document.getElementById("nameRow").style.display='';
   document.getElementById("nameRow2").style.display='';
   document.getElementById("resultRow").style.display='';
   
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
            if(formx.elements[i].type=="checkbox"){
                formx.elements[i].checked=false;
            }
        }
    }
}

function hideDetails() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}


function printReport() {                    
    
   if(trim(document.getElementById('percentage').value)==""){
     messageBox("<?php echo "Enter value for percentage" ?>");
     document.getElementById('percentage').focus();
     return false;
   }  
   
   if(!isNumericCustom(document.getElementById('percentage').value,".-")) {
        messageBox("Enter only numeric value");
        document.getElementById('percentage').focus();
        return false;
   } 
   
   if(!isDecimal(document.getElementById('percentage').value)) {
      messageBox("Enter only numeric value");
      document.getElementById('percentage').focus();
      return false;
   }
   
   if(document.getElementById('percentage').value < 0 || document.getElementById('percentage').value > 100 ){
      messageBox("Percentage can not more than 100 and less than 0");
      document.getElementById('percentage').focus();
      return false;
   }
    
   var selected=0;
   studentCheck='';
    
   formx = document.allDetailsForm;
   for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
            if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]")){
                if(studentCheck=='') {
                   studentCheck=formx.elements[i].value; 
                }
                else {
                    studentCheck = studentCheck + ',' +formx.elements[i].value; 
                }
                selected++;
            }
        }
   }
   
   if(selected==0)    {
     alert("Please select atleast one student record");
     return false;
   }
    
   s=0;
   a=0;
   p=0;
   if(document.allDetailsForm.signature.checked==true) {
     s = 1;
   }
   if(document.allDetailsForm.addressChk.checked==true) {
     if(document.allDetailsForm.address[0].checked==true) {
       a = 1;   // Correspondence Address
     }  
     else {
       a = 2;   // Permanent Address
     }
   }
   if(document.allDetailsForm.photo.checked==true) {
     p = 1;
   }
   
   heading = trim(document.allDetailsForm.heading.value);
   message = trim(document.allDetailsForm.message.value);
   signature = trim(document.allDetailsForm.signature.value);
   dutyLeave = 0;  
   medicalLeave = 0;  
   if(document.allDetailsForm.dutyLeave.checked==true) {
     dutyLeave = 1;
   }
   if(document.allDetailsForm.medicalLeave.checked==true) {
     medicalLeave = 1;
   }
   
   //queryString  ='&signature='+escape(s)+'&address='+escape(a)+'&percentage='+per+'&classId='+classId+'&rollno='+rollno; 
   queryString = '&dutyLeave='+dutyLeave+'&medicalLeave='+medicalLeave;
   queryString = queryString+'&signature='+escape(s)+'&address='+escape(a)+'&photo='+escape(p)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+queryClass;
   
   path='<?php echo UI_HTTP_PATH;?>/studentAttendanceShortPrint.php?studentId='+studentCheck+queryString+'&heading='+escape(heading)+'&message='+escape(message) + '&signature='+escape(signature);
   window.open(path,"StudentAttendanceShortReport","status=1,menubar=1,scrollbars=1, width=900");
}

function populateClass(){
    
    document.allDetailsForm.classId.length = null;
    addOption(document.allDetailsForm.classId, '', 'Select');
    
    if(document.getElementById('labelId').value=='') {
       document.getElementById('labelId').focus();
       return false; 
    }
    
     
    var url ='<?php echo HTTP_LIB_PATH;?>/StudentReports/ajaxTeacherGetClasses.php';
        
    new Ajax.Request(url,
    {
             method:'post',
             asynchronous:false,
             parameters: {
                 timeTabelId: document.getElementById('labelId').value
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+transport.responseText+')'); 
                    for(var c=0;c<j.length;c++){
                      var objOption = new Option(j[c].className,j[c].classId);
                      document.allDetailsForm.classId.options.add(objOption);
                    }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

window.onload=function() {
   //loads the data
   populateClass(); 
   document.getElementById('labelId').focus();
}

function setAddress() {
    
   document.getElementById('addressHide').style.display='none'; 
   if(document.allDetailsForm.addressChk.checked) {
     document.getElementById('addressHide').style.display=''; 
   } 
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/studentAttendanceShortContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>


<?php 
//$History: studentAttendanceShorts.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 3/22/10    Time: 11:33a
//Updated in $/LeapCC/Interface
//format updated (corr. address show)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/25/10    Time: 12:02p
//Updated in $/LeapCC/Interface
//format & validation updated 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/23/10    Time: 5:41p
//Created in $/LeapCC/Interface
//initial checkin
//
//*****************  Version 6  *****************
//User: Parveen      Date: 1/12/10    Time: 12:19p
//Updated in $/Leap/Source/Interface
//print button code updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 1/11/10    Time: 3:46p
//Updated in $/Leap/Source/Interface
//validation format updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 1/11/10    Time: 12:22p
//Updated in $/Leap/Source/Interface
//validation message & format updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/28/09   Time: 3:18p
//Updated in $/Leap/Source/Interface
//classId check updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/28/09   Time: 12:14p
//Updated in $/Leap/Source/Interface
//sorting order added printReport
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/18/09   Time: 2:40p
//Created in $/Leap/Source/Interface
//initial checkin
//

?>