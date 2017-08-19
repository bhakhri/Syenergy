<?php
//-------------------------------------------------------
// Purpose: To generate topic taught list for subject centric
// functionality 
//
// Author :Parveen Sharma
// Created on : 01-06-2009
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TeacherWiseTopicTaught');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Teacher Topic Taught Report</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
                                 
var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
                               new Array('fromDate','From Date','width="10%"','align="center"',true),
                               new Array('toDate','To Date','width="10%"','align="center"',true),
                               new Array('groupName','Group','width="10%"','align="left"',true),
                               new Array('periodNumber','Period No.','width="10%"','align="center"',true),
                               new Array('topic',"Topics Covered",'align="left" width="58%"','align="left"',true));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxSubjectTopicCovered.php';
searchFormName = 'listForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
divResultName  = 'results';
page=1; //default page
sortField = 'fromDate';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h){
    displayWindow(dv,w,h);
    populateValues(id);
}

var initialTextForMultiDropDowns='Click to select multiple items';
var selectTextForMultiDropDowns='item';
window.onload=function(){
    //document.listForm.subject.value='';
    makeDDHide('subjectTopic','d2','d3');
    populateTeachers();
}

function validateAddForm() {

     if(trim(document.getElementById('labelId').value)==""){
        messageBox("<?php echo SELECT_TIMETABLE;?>");  
        document.getElementById('labelId').focus();
        return false;
    }    
    
    if(trim(document.getElementById('employeeId').value)==""){
        messageBox("<?php echo SELECT_TEACHER;?>");  
        document.getElementById('employeeId').focus();
        return false;
    }    

    if(trim(document.getElementById('classId').value)==""){
        messageBox("<?php echo SELECT_CLASS;?>");  
        document.getElementById('classId').focus();
        return false;
    }    

    if(trim(document.getElementById('subject').value)==""){
        messageBox("<?php echo SELECT_SUBJECT;?>");  
        document.getElementById('subject').focus();
        return false;
    }    
    
    if(trim(document.getElementById('group').value)==""){
        messageBox("<?php echo SELECT_GROUP;?>");  
        document.getElementById('group').focus();
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
    
    if(trim(document.getElementById('subjectTopic').value)==""){
        messageBox("<?php echo SELECT_TOPICS_TAUGHT; ?>");  
        //document.getElementById('subjectTopic').focus();
        popupMultiSelectDiv('subjectTopic','d1','containerDiv','d3');
        return false;
    }  
    
    closeTargetDiv('d1','containerDiv');
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false);
    
    document.getElementById('saveDiv').style.display='';
    document.getElementById('showTitle').style.display='';
    document.getElementById('showData').style.display=''; 

    //sendReq(listURL,divResultName,'listForm','');     
    return false;
}
//---------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "states/city" select box depending upon which country/state is selected
//
//Author : Rajeev Aggarwal
// Created on : (17.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------
//id:id 
//type:states/city
//target:taget dropdown box
//function autoPopulate(){

function autoPopulate(){
    
   var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxGetTopics.php';
   document.listForm.subjectTopic.length = null;

   if(document.getElementById('classId').value=='') {
        //messageBox("<?php echo SELECT_SUBJECT;?>");
       return false;
   }

   if(document.getElementById('subject').value=='') {
        //messageBox("<?php echo SELECT_SUBJECT;?>");
       return false;
   }
   
   classId = document.getElementById('classId').value;
   subjectId = document.getElementById('subject').value;
   
   new Ajax.Request(url,
   {
       method:'post',
       parameters: { classId: classId,
                     subject: subjectId},
       onCreate: function() {
           showWaitDialog(); 
       },
       onSuccess: function(transport){
         hideWaitDialog();
          var j = trim(transport.responseText).evalJSON();   
          var len=j.topicArr.length;
          for(i=0;i<len;i++) { 
              addOption(document.listForm.subjectTopic, j.topicArr[i].subjectTopicId, j.topicArr[i].topic);
          }
     },
    onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }     
   }); 
}


function populateTeachers(){
    
    document.listForm.classId.length = null;
    addOption(document.listForm.classId, '', 'Select');
    
    document.listForm.subject.length = null;
    addOption(document.listForm.subject, '', 'Select');
    
    document.listForm.group.length = null;
    addOption(document.listForm.group, '', 'Select');
    
    document.listForm.employeeId.length = null;
    addOption(document.listForm.employeeId, '', 'Select');
    
    document.listForm.subjectTopic.length = null;
    //addOption(document.listForm.subjectTopic, '', 'Select');
   
    if(document.getElementById('labelId').value=='') {
       document.getElementById('labelId').focus();
       return false; 
    }  
    
    var url ='<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxGetEmployee.php';
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
                      var objOption = new Option(j[c].employeeName1,j[c].employeeId);
                      document.listForm.employeeId.options.add(objOption);
                    }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

function populateClass(){
    
    document.listForm.classId.length = null;
    addOption(document.listForm.classId, '', 'Select');
    
    document.listForm.subject.length = null;
    addOption(document.listForm.subject, '', 'Select');
    
    document.listForm.group.length = null;
    addOption(document.listForm.group, '', 'Select');
    
    document.listForm.subjectTopic.length = null;
    //to make it show "Click to show...."
    totalSelected('subjectTopic','d3');
    closeTargetDiv('d1','containerDiv');
    
    if(document.getElementById('employeeId').value=='') {
       document.getElementById('employeeId').focus();
       return false; 
    }
    
    employeeId = document.getElementById('employeeId').value;
     
    var url ='<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxGetClasses.php';

        
    new Ajax.Request(url,
    {
             method:'post',
             asynchronous:false,
             parameters: {
                 timeTabelId: document.getElementById('labelId').value,
                 employeeId: employeeId
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+transport.responseText+')'); 
                    for(var c=0;c<j.length;c++){
                      var objOption = new Option(j[c].className,j[c].classId);
                      document.listForm.classId.options.add(objOption);
                    }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

function populateSubjects(classId){
    
    
    document.listForm.subject.length = null;
    addOption(document.listForm.subject, '', 'Select');
    
    document.listForm.group.length = null;
    addOption(document.listForm.group, '', 'Select');
    
    document.listForm.subjectTopic.length = null;
    //to make it show "Click to show...."
    totalSelected('subjectTopic','d3');
    closeTargetDiv('d1','containerDiv');
    
    if(document.getElementById('classId').value=='') {
       document.getElementById('classId').focus();
       return false; 
    }
    
    var url ='<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxGetSubject.php';
    new Ajax.Request(url,
    {
             method:'post',
             asynchronous:false,
             parameters: {
                 timeTabelId: document.getElementById('labelId').value,  
                 employeeId:  document.getElementById('employeeId').value,
                 classId: document.getElementById('classId').value
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+transport.responseText+')'); 

                    for(var c=0;c<j.length;c++){
                       if(j[c].hasAttendance==1) {
                         var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                         document.listForm.subject.options.add(objOption);
                       }
                    }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}


//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate topic drop-down upon selection of  subject(Teacher Module)
//Author : Jaineesh 
// Created on : (12.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function populateGroups() {
    
        document.listForm.group.length = null;
        addOption(document.listForm.group, '', 'Select');
        var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxGetGroups.php';
        if(document.getElementById('subject').value=='') {
           //messageBox("<?php echo SELECT_SUBJECT;?>");
           return false;
        }
   
        new Ajax.Request(url,
        {
             method:'post',
             parameters: { 
                           employeeId: document.getElementById('employeeId').value, 
                           classId: document.getElementById('classId').value,
                           subject: document.getElementById('subject').value
                         },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                hideWaitDialog();
                var j = trim(transport.responseText).evalJSON();   
                var len=j.groupArr.length;
                if (len > 0) {
                    document.listForm.group.length = null;           
                    addOption(document.listForm.group, 'all', 'All');
                }
                for(i=0;i<len;i++) { 
                    addOption(document.listForm.group, j.groupArr[i].groupId, j.groupArr[i].groupName);
                }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
           
     autoPopulate();
}




function printReport() {
    
    if(trim(document.getElementById('employeeId').value)==""){
        messageBox("<?php echo "Select Teacher"; ?>");  
        document.getElementById('employeeId').focus();
        return false;
    }    
    
    if(trim(document.getElementById('classId').value)==""){
        messageBox("<?php echo SELECT_CLASS;?>");  
        document.getElementById('classId').focus();
        return false;
    }    

    if(trim(document.getElementById('subject').value)==""){
        messageBox("<?php echo SELECT_SUBJECT;?>");  
        document.getElementById('subject').focus();
        return false;
    }    
    
    if(trim(document.getElementById('group').value)==""){
        messageBox("<?php echo SELECT_GROUP;?>");  
        document.getElementById('group').focus();
        return false;
    }    
   
    if(!isEmpty(document.getElementById('startDate').value)) {
       if(isEmpty(document.getElementById('endDate').value)) {
        messageBox("<?php echo EMPTY_TO_DATE;?>");  
        document.getElementById('endDate').focus();
        return false;
       }  
    }    
   
    if(!isEmpty(document.getElementById('endDate').value)) {
       if(isEmpty(document.getElementById('startDate').value)) {
        messageBox("<?php echo EMPTY_FROM_DATE;?>");  
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
    
    if(trim(document.getElementById('subjectTopic').value)==""){
        messageBox("<?php echo SELECT_TOPICS_TAUGHT; ?>");  
        //document.getElementById('subjectTopic').focus();
        popupMultiSelectDiv('subjectTopic','d1','containerDiv','d3');
        return false;
    }  

    var queryString = generateQueryString('listForm');
    queryString += '&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    path='<?php echo UI_HTTP_PATH;?>/teacherTopicCoveredReportPrint.php?lst=1&'+queryString;
    window.open(path,"SubjectwiseTopicTaughtReportPrint","status=1,menubar=1,scrollbars=1, width=900, height=500, top=100,left=50");
}

/* function to print all csv*/
function printReportCSV() {

    if(trim(document.getElementById('employeeId').value)==""){
        messageBox("<?php echo "Select Teacher"; ?>");  
        document.getElementById('employeeId').focus();
        return false;
    }    

    if(trim(document.getElementById('classId').value)==""){
        messageBox("<?php echo SELECT_CLASS;?>");  
        document.getElementById('classId').focus();
        return false;
    }    

    if(trim(document.getElementById('subject').value)==""){
        messageBox("<?php echo SELECT_SUBJECT;?>");  
        document.getElementById('subject').focus();
        return false;
    }    
    
    if(trim(document.getElementById('group').value)==""){
        messageBox("<?php echo SELECT_GROUP;?>");  
        document.getElementById('group').focus();
        return false;
    }    
   
    if(!isEmpty(document.getElementById('startDate').value)) {
       if(isEmpty(document.getElementById('endDate').value)) {
        messageBox("<?php echo EMPTY_TO_DATE;?>");  
        document.getElementById('endDate').focus();
        return false;
       }  
    }    
   
    if(!isEmpty(document.getElementById('endDate').value)) {
       if(isEmpty(document.getElementById('startDate').value)) {
        messageBox("<?php echo EMPTY_FROM_DATE;?>");  
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
    
    if(trim(document.getElementById('subjectTopic').value)==""){
        messageBox("<?php echo SELECT_TOPICS_TAUGHT; ?>");  
        //document.getElementById('subjectTopic').focus();
        popupMultiSelectDiv('subjectTopic','d1','containerDiv','d3');
        return false;
    }  

    var queryString = generateQueryString('listForm');
    queryString += '&sortOrderBy='+sortOrderBy+'&sortField='+sortField;

    path='<?php echo UI_HTTP_PATH;?>/teacherTopicCoveredReportCSV.php?lst=1&'+queryString; 
    window.location=path;
}

</script>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/EmployeeReports/listTopicCoveredContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php
// $History: teacherTopicCoveredReport.php $
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 8  *****************
//User: Parveen      Date: 2/11/10    Time: 7:14p
//Updated in $/LeapCC/Interface
//validation format updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 2/11/10    Time: 4:45p
//Updated in $/LeapCC/Interface
//timetable label check added
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 5/01/10    Time: 15:38
//Updated in $/LeapCC/Interface
//Made UI changes
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/24/09   Time: 5:03p
//Updated in $/LeapCC/Interface
//parseOutput function added 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/24/09   Time: 3:44p
//Updated in $/LeapCC/Interface
//alignment & format updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/22/09   Time: 6:20p
//Updated in $/LeapCC/Interface
//date format & alignement updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/22/09   Time: 5:16p
//Updated in $/LeapCC/Interface
//format & validation format updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/03/09   Time: 3:21p
//Created in $/LeapCC/Interface
//initial checkin
//
//*****************  Version 7  *****************
//User: Parveen      Date: 11/20/09   Time: 6:04p
//Updated in $/LeapCC/Interface/Teacher
//subjectTopic checks updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 10/24/09   Time: 4:17p
//Updated in $/LeapCC/Interface/Teacher
//sorting order updated (dates)
//
//*****************  Version 5  *****************
//User: Parveen      Date: 10/15/09   Time: 11:49a
//Updated in $/LeapCC/Interface/Teacher
//print & CSV format checks updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 10/09/09   Time: 6:09p
//Updated in $/LeapCC/Interface/Teacher
//date format updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/09/09   Time: 5:18p
//Updated in $/LeapCC/Interface/Teacher
//date checks & group query updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 10/06/09   Time: 2:51p
//Updated in $/LeapCC/Interface/Teacher
//class added, look & feel formating 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/03/09    Time: 12:23p
//Created in $/LeapCC/Interface/Teacher
//initial checkin
//


?>
