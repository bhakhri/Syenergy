<?php 
//-------------------------------------------------------
//  This File contains Attendance Register 
//
// Author :Parveen Sharma
// Created on : 28-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TeacherAttendanceRegister');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Attendance Register Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
 
//This function Validates Form 
//var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initStudentList.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window
recordsPerPage = 10000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'attendanceForm'; // name of the form which will be used for search
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'ASC';
queryString = '';
incDate=0;



function refreshAttendanceRegisterData1() {
    
     url='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxStudentList.php';        
     hideResults();
     page=1;
     queryString = '';
     form = document.attendanceForm; 
     
     timeTable = form.timeTable.value;  
     
     if(form.timeTable.value=='') {
       return false;  
     }
     
     var rval=timeTable.split('~');

     timeTable=rval[0];     
     
     if(document.attendanceForm.consolidatedId.checked) {
       consolidatedId =1;
     }
     else {
       consolidatedId =0;  
     }  
     var reportType=document.attendanceForm.reportType[0].checked==true?1:0;    
     
     dutyLeave =0;
     if(document.attendanceForm.dutyLeave.checked) {
       dutyLeave =1;
     }
     else {
       dutyLeave =0;  
     }  
     
     sortOrderBy1='ASC';
     if(document.attendanceForm.sortOrderBy1[1].checked==true) {
       sortOrderBy1='DESC';
     }
     sortOrderBy = sortOrderBy1;
     
     queryString = 'dutyLeave='+dutyLeave+'&timeTable='+timeTable+'&fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&degree='+form.degree.value;  
     queryString = queryString+'&subjectId='+form.subjectId.value+'&groupId='+form.groupId.value+'&nosCol='+form.nosCol.value; 
     queryString = queryString+'&sortOrderBy='+sortOrderBy+'&sortField='+form.sortField1.value;
     queryString = queryString+'&page='+page+'&consolidatedId='+consolidatedId+'&reportType='+reportType;
           
     new Ajax.Request(url,
     {
          method:'post',
          asynchronous:false,
          parameters: queryString, 
          onCreate: function() {
              showWaitDialog(true);
          },
          onSuccess: function(transport){
             hideWaitDialog(true);
             if(trim(transport.responseText)==false) {
                messageBox("<?php echo INCORRECT_FORMAT?>");  
             }
             else {
               document.getElementById('resultsDiv').innerHTML=trim(transport.responseText);
               document.getElementById("nameRow").style.display='';
               document.getElementById("nameRow2").style.display='';
               document.getElementById("resultRow").style.display='';
             }
     },
     onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
     });
}

function validateAddForm(frm) {
    
    if(!isEmpty(document.getElementById('fromDate').value)) {
       if(isEmpty(document.getElementById('toDate').value)) {
        messageBox("<?php echo EMPTY_DATE_TO;?>");  
        document.getElementById('toDate').focus();
        return false;
       }  
    }    
   
    if(!isEmpty(document.getElementById('toDate').value)) {
       if(isEmpty(document.getElementById('fromDate').value)) {
        messageBox("<?php echo EMPTY_DATE_FROM;?>");  
        document.getElementById('toDate').focus();
        return false;
       }   
    }  
   
    if(!isEmpty(document.getElementById('fromDate').value) && !isEmpty(document.getElementById('toDate').value)) { 
        if(!dateDifference(eval("document.getElementById('fromDate').value"),eval("document.getElementById('toDate').value"),'-') ) {
            messageBox ("<?php echo DATE_CONDITION;?>");
            eval("document.getElementById('fromDate').focus();");
            return false;
        } 
    }
    
    if(document.getElementById("timeTable").value == '') {
       messageBox("<?php echo SELECT_TIME_TABLE; ?>");  
       document.getElementById('timeTable').focus();         
       return false;
    }
    
    if(document.getElementById("degree").value == '') {
       messageBox("<?php echo SELECT_DEGREE; ?>");  
       document.getElementById('degree').focus(); 
       return false;
    }
    
    if(document.getElementById("subjectId").value == '') {
       messageBox("<?php echo SELECT_SUBJECT; ?>");  
       document.getElementById('subjectId').focus();    
       return false;
    }
    
    if(document.getElementById("groupId").value == '') {
       messageBox("<?php echo SELECT_GROUP; ?>");
       document.getElementById('groupId').focus();  
       return false;
    }
    
    if(trim(document.getElementById("nosCol").value) == '') {
       messageBox("<?php echo "Enter view of lectures value"; ?>");
       document.getElementById('nosCol').focus();  
       return false;
    }
    
     if(false == isNumeric(document.getElementById('nosCol').value) ) {
       messageBox("<?php echo ENTER_NUMERIC_VALUE ?>");
       document.getElementById('nosCol').focus();   
       return false;  
    }
    
    if(parseInt(document.getElementById('nosCol').value,10) <= 0 || parseInt(document.getElementById('nosCol').value,10) >= 150 ) {
       messageBox("<?php echo "View of Lectures value between 1 to 150 "; ?>");
       document.getElementById('nosCol').focus();
       return false;  
    }
    
    
    //refreshAttendanceRegisterData();
    refreshAttendanceRegisterData1();
    return false;
}


function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

/* function to print */
function printReport() {
    
    path='<?php echo UI_HTTP_PATH;?>/Teacher/attendanceRegisterPrint.php?'+queryString+'&heading='+escape(trim(form.heading.value)); 
    hideUrlData(path,true);
}


/* function to output data to a CSV*/
function printCSV() {

    path='<?php echo UI_HTTP_PATH;?>/Teacher/attendanceRegisterCSV.php?'+queryString+'&heading='+escape(trim(form.heading.value)); 
    window.location = path;  
}


function getLabelClass(){
     var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetTimeTableClass.php';
    
    
     form = document.attendanceForm;
     var timeTable = form.timeTable.value;
     
     document.attendanceForm.degree.length = null; 
     addOption(document.attendanceForm.degree, '', 'Select');
    
     document.attendanceForm.subjectId.length = null; 
     addOption(document.attendanceForm.subjectId, '', 'Select');
    
     document.attendanceForm.groupId.length = null; 
     addOption(document.attendanceForm.groupId, '', 'Select');
     
     document.getElementById('deliverAttendance').innerHTML="<?php echo NOT_APPLICABLE_STRING; ?>";
     
     if(timeTable=='') {
       return false;  
     }
     
     var rval=timeTable.split('~');

     var pars = 'timeTableLabelId='+rval[0];     
     
     if(rval[1]!='0000-00-00' && rval[1]!='') {
       document.getElementById('fromDate').value=rval[1];
     }
     if(rval[2]!='0000-00-00' && rval[2]!='') {
       document.getElementById('toDate').value=rval[2];
     }
    
     new Ajax.Request(url,
     {
         method:'post',
         parameters: pars,
         asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
               hideWaitDialog(true);
                var j = eval('(' + transport.responseText + ')');
                len = j.length;

                document.attendanceForm.degree.length = null;                  
                if(len>0) { 
                  for(i=0;i<len;i++) { 
                    addOption(document.attendanceForm.degree, j[i].classId, j[i].className);
                  }
                }
                else {
                  addOption(document.attendanceForm.degree, '', 'Select'); 
                }
                // now select the value                                     
                //document.attendanceForm.degree.value = j[0].classId;
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
       
    getSubject();   
}


function getSubject(){
    
    document.getElementById('deliverAttendance').innerHTML="<?php echo NOT_APPLICABLE_STRING; ?>";
    var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetTimeTableSubjects.php';
    
    form = document.attendanceForm;
    timeTable = form.timeTable.value;
    degree = form.degree.value;
        
    document.attendanceForm.subjectId.length = null; 
    addOption(document.attendanceForm.subjectId, '', 'Select');
    
    document.attendanceForm.groupId.length = null; 
    addOption(document.attendanceForm.groupId, '', 'Select');
    
    
    if(timeTable=='') {
      return false;  
    }
    
    if(degree=='') {
      return false  
    }


    var rval=timeTable.split('~');
    
    pars = 'timeTableLabelId='+rval[0]+'&classId='+degree;
    
    new Ajax.Request(url,
    {
         method:'post',
         parameters: pars,
         asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
               hideWaitDialog(true);
                var j = eval('(' + transport.responseText + ')');
                len = j.length;

                document.attendanceForm.subjectId.length = null;                  
                addOption(document.attendanceForm.subjectId, '', 'Select');
                for(i=0;i<len;i++) { 
                  addOption(document.attendanceForm.subjectId, j[i].subjectId, j[i].subjectCode);
                }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}


function getGroup(){

    document.getElementById('deliverAttendance').innerHTML="<?php echo NOT_APPLICABLE_STRING; ?>";
    
    var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetTimeTableGroups.php';
    
    form = document.attendanceForm;
    timeTable = form.timeTable.value;
    degree = form.degree.value;
    subject = form.subjectId.value;
    
    document.attendanceForm.groupId.length = null; 
    addOption(document.attendanceForm.groupId, '', 'Select');
    
    
    if(timeTable=='') {
      return false  
    }
    
    if(degree=='') {
      return false  
    }
    
    if(subject=='') {
      return false  
    }
    
    var rval=timeTable.split('~');  
    
    pars = 'timeTableLabelId='+rval[0]+'&classId='+degree+'&subjectId='+subject;

     new Ajax.Request(url,
     {
         method:'post',
         parameters: pars,
         asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
               hideWaitDialog(true);
                var j = eval('(' + transport.responseText + ')');
                len = j.length;

                document.attendanceForm.groupId.length = null;                  
                addOption(document.attendanceForm.groupId, '', 'Select');
                for(i=0;i<len;i++) { 
                  addOption(document.attendanceForm.groupId, j[i].groupId, j[i].groupName);
                }
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

function getDeliverLecture(){

    document.getElementById('deliverAttendance').innerHTML="<?php echo NOT_APPLICABLE_STRING; ?>";
    var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetDeliverAttendance.php';
    document.getElementById("deliverAttendance11").style.display='none';
    
    form = document.attendanceForm;

    timeTable = form.timeTable.value;
    degree = form.degree.value;
    subject = form.subjectId.value;
    group = form.groupId.value;
    fromDate=form.fromDate.value;
    toDate=form.toDate.value;
    var reportType=document.attendanceForm.reportType[0].checked==true?1:0;  
    
    if(!isEmpty(document.getElementById('fromDate').value) && !isEmpty(document.getElementById('toDate').value)) { 
      if(!dateDifference(eval("document.getElementById('fromDate').value"),eval("document.getElementById('toDate').value"),'-') ) {
        messageBox ("<?php echo DATE_CONDITION;?>");
        eval("document.getElementById('fromDate').focus();");
        return false;
      } 
    }
    
    if(document.attendanceForm.consolidatedId.checked) {
       consolidatedId =1;
    }
    else {
       consolidatedId =0;  
    }     

    
    var rval=timeTable.split('~');
    timeTable=rval[0];     

    if(timeTable=='') {
      return false  
    }
    
    if(degree=='') {
      return false  
    }
    
    if(subject=='') {
      return false  
    }
    
    if(group=='') {
      return false  
    }

    pars = 'timeTableLabelId='+rval[0]+'&classId='+degree+'&subjectId='+subject+'&groupId='+group;
    pars = pars + '&fromDate='+fromDate+'&toDate='+toDate+'&consolidatedId='+consolidatedId+'&reportType='+reportType;
    
    new Ajax.Request(url,
    {
         method:'post',
         parameters: pars,
         asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
             hideWaitDialog(true);
             var ret=trim(transport.responseText).split('!~~!');
             document.getElementById("deliverAttendance11").style.display='none';
             var j0 = ret[0];     // ConslidatedCheck
             var j1 = ret[1];     // groupTypeId
             var j2 = ret[2];     // Total
             var j3 = ret[3];     // Lecture
             var j4 = ret[4];     // Tutorial
             var j5 = ret[5];     // Subject Name
             var j6 = ret[6];     // Subject Name
          
             //alert(j0+'  '+j1+'  '+j2+'  '+j3+'  '+j4);
             document.getElementById("subjectInfo").style.display='none'; 
             if(j5!='') {
               document.getElementById("subjectInfo").style.display=''; 
               document.getElementById("subjectName").innerHTML=j5;   
             }
            
             //document.getElementById("employeeInfo").style.display='none'; 
             if(j6!='') {
               //document.getElementById("employeeInfo").style.display=''; 
               document.getElementById("employeeName").innerHTML=j6;   
             }
            
             cnt=0;
             if(j0==1) {
               document.getElementById("deliverAttendance11").style.display='none';  
               document.getElementById('deliverAttendance').innerHTML=0;
               //document.getElementById('deliverAttendanceL').innerHTML=0;
               document.getElementById('deliverAttendanceL').style.display='none'; 
               document.getElementById('deliverAttendanceT').innerHTML=0;
               if(j1==1 || j1==3) {
                   document.getElementById("deliverAttendance11").style.display=''; 
                   document.getElementById('deliverAttendanceL').style.display='none'; 
                   if(j3=='' ) {
                     j3="<b>L&nbsp;:&nbsp;</b>0";  
                   }
                   if(j4=='') {
                     j4="<b>T&nbsp;:&nbsp;</b>0";  
                   }
                   document.getElementById('deliverAttendance').innerHTML=j3;
                   document.getElementById('nosCol').value=j2;
                   document.getElementById('deliverAttendanceT').innerHTML=(j4); 
               }
               else {
                 if((j2)>0) {     
                    document.getElementById('deliverAttendance').innerHTML=(j2);
                    document.getElementById('nosCol').value=(j2); 
                 }
                 else {
                    document.getElementById('nosCol').value="20";
                 }
               }
             }
             else {
                if((j3)>0) {     
                   document.getElementById('deliverAttendance').innerHTML=(j3);
                   document.getElementById('nosCol').value=(j2); 
                }
                else {
                   document.getElementById('nosCol').value="20";
                }  
             }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

window.onload=function() {
   //loads the data
   document.getElementById('deliverAttendance').innerHTML="<?php echo NOT_APPLICABLE_STRING; ?>";
   getLabelClass(); 
   document.attendanceForm.timeTable.focus();
   
}


function sendKeys(eleName, e) {
  var ev = e||window.event;
  thisKeyCode = ev.keyCode;
  if (thisKeyCode == '13') {
    var frm = document.attendanceForm;
    eval('frm.'+eleName+'.focus()');
    return false; 
  }
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/attendanceRegisterContent.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
//$History: attendanceRegister.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/12/10    Time: 11:05a
//Updated in $/LeapCC/Interface/Teacher
//validation message updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 3/26/10    Time: 7:10p
//Updated in $/LeapCC/Interface/Teacher
//keyPress check updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/17/10    Time: 12:20p
//Updated in $/LeapCC/Interface/Teacher
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/17/10    Time: 10:18a
//Created in $/LeapCC/Interface/Teacher
//initial checkin
//
//*****************  Version 7  *****************
//User: Parveen      Date: 2/22/10    Time: 5:30p
//Updated in $/LeapCC/Interface
//period slot format updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 2/19/10    Time: 1:50p
//Updated in $/LeapCC/Interface
//validation message updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 2/19/10    Time: 1:39p
//Updated in $/LeapCC/Interface
//search condition format updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/19/10    Time: 1:18p
//Updated in $/LeapCC/Interface
//format updated (no. of columns check) 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/18/10    Time: 6:19p
//Updated in $/LeapCC/Interface
//format & validation updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/16/10    Time: 4:28p
//Created in $/LeapCC/Interface
//initial checkin
//
//
//

?>
