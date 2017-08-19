<?php
//-------------------------------------------------------
// Purpose: To generate student list
// functionality 
//
// Author : Dipanjan Bhattacharjee
// Created on : (14.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SearchStudentDisplay');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
if(isset($_REQUEST['id']) and $_REQUEST['id'] > 0){
 require_once(BL_PATH . "/Teacher/StudentActivity/initList.php"); 
} 
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Detail</title>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
//require_once(CSS_PATH .'/tab-view.css'); 
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("ajax.js");  
echo UtilityManager::includeJS("tab-view.js"); 


//pareses input and returns 0 if the input is blank
//Author: Dipanjan Bhatacharjee
//Date:14.7.2008
function parseInput($input) {
    return ( (trim($input)!="" ? $input : 0 ) );
}

//pareses input and returns "-" if the input is blank
//Author: Dipanjan Bhatacharjee
//Date:14.7.2008
function parseOutput($data){
    
     return ( (trim($data)!="" ? $data : "---" ) );  
    
}
?> 

<script language="javascript">
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

/****************************************************************/
//Overriding tabClick() function of tab.js
//Dipanjan Bhattacharjee
//Date:14.02.2009
/****************************************************************/
var tabNumber=0;  //Determines the current tab index
function tabClick()
    {
        var idArray = this.id.split('_');
        showTab(this.parentNode.parentNode.id,idArray[idArray.length-1].replace(/[^0-9]/gi,''));
        tabNumber=(idArray[idArray.length-1].replace(/[^0-9]/gi,''));
        
        //refresshes data for this tab
        refreshStudentData(document.getElementById('studyPeriod').value,tabNumber);
    }


//Global variables for classId countres for different tabs
var ccId=-1;
var tcId=-1;
var mcId=-1;
var coId=-1;
var reId=-1;


//--------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO GET STUDENT ATTENDANCE BASED UPON DATES SELECTED
//
//Author : Dipanjan Bhattacharjee
// Created on : (15.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------
function getAttendance(studentId,startDate,endDate) {
 
    if(trim(startDate)=="" || trim(endDate)==""){
        messageBox("Date fields can not be empty");
        return false;
    }
   
   if(!dateDifference(startDate,endDate,"-")) 
   {
      messageBox("To Date can not be smaller than From Date");   
      return false;
   }
   
   var d=new Date();
   var cdate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));
   
   if(!dateDifference(endDate,cdate,"-")) 
   {
     messageBox("To Date Can Not be Greater Than Current Date");
      return false;
   }
    
   var attendanceData=refreshAttendanceData(document.getElementById('studyPeriod').value);  
}


//this function fetches records corresponding to resource uploaded
function refreshResourceData(value){
 var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/StudentActivity/ajaxCourseResourceList.php'; 
 var tableColumns = new Array(
                            new Array('srNo','#','width="2%" valign="middle"',false), 
                            new Array('subject','Subject','width="10%" valign="middle"',true) , 
                            new Array('description','Description','width="15%" valign="middle"',false), 
                            new Array('resourceName','Type','width="10%" valign="middle"',true), 
                            new Array('postedDate','Date','width="8%"  align="center"',true),
                            new Array('resourceLink','Link','width="8%" valign="middle"',false),
                            new Array('attachmentLink','Attachment','width="7%" valign="middle" align="center"',false),
                            new Array('employeeName','Creator','width="9%" align="left" valign="middle"',true)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj4 = new initPage(url,recordsPerPage,linksPerPage,1,'','subject','ASC','resourceResultsDiv','','',true,'listObj4',tableColumns,'','','&rClassId='+value+'&searchbox='+document.getElementById('searchbox').value);
 sendRequest(url, listObj4, '')

}

//this function fetches records corresponding to student groups
function refreshGroupData(value){
 var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/StudentActivity/ajaxGroupList.php'; 
  var tableColumns = new Array(
                            new Array('srNo','#','width="2%" valign="middle"',false), 
                            new Array('studyPeriod','Study Period','width="10%" valign="middle"',true) , 
                            new Array('groupName','Group','width="10%" valign="middle"',true) , 
                            new Array('groupTypeCode','Type','width="15%" valign="middle"',true) 
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj5 = new initPage(url,recordsPerPage,linksPerPage,1,'','studePeriod','ASC','groupResultsDiv','','',true,'listObj5',tableColumns,'','','&rClassId='+value);
 sendRequest(url, listObj5, '')

}


//this function fetches records corresponding to student grades/marks
function refreshMarksData(value){
  url = '<?php echo HTTP_LIB_PATH;?>/Teacher/StudentActivity/ajaxMarksList.php';
  var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
                        new Array('subjectName','Subject','width="12%" align="left"',true),
                        new Array('testTypeName','Test Type','width="10% align="left"',true), 
                        new Array('testDate','Test Date','width="8%" align="center"',true),
                        new Array('studyPeriod','Study Period','width="10%" align="center"',true),
                        new Array('employeeName','Teacher','width="12%" align="left"',true),
                        new Array('testName','Test Name','width="8%" align="left"',true),
                        new Array('totalMarks','Max. Marks','width="10%" align="right"',true),
                        new Array('obtainedMarks','Marks Scored','width="10%" align="right"',true)
                       );
 
 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj2 = new initPage(url,recordsPerPage,linksPerPage,1,'','subject','ASC','marksResultsDiv','','',true,'listObj2',tableColumns,'','','&rClassId='+value);
 sendRequest(url, listObj2, '')
 
}

//this variable is used to determine whether group wise or 
//consolidated attendance view is required
//Modified By : Dipanjan Bhattacharjee
//Date: 06.10.2009
var attendanceConsolidatedView=1;
var viewType=0;

//this function fetches records corresponding to student attendance
function refreshAttendanceData(value){
  var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/StudentActivity/ajaxAttendanceList.php';
  //if consolidated view is not required
 if(attendanceConsolidatedView==1){
     var tableColumns = new Array(
                            new Array('srNo','#','width="2%" align="left"',false), 
                            new Array('subjectName','Subject','width="15%" align="left"',true),
                            new Array('studyPeriod','Study Period','width="12% align="left"',true), 
                            new Array('groupName','Group','width="12% align="left"',true), 
                            new Array('employeeName','Teacher','width="15%" align="left"',true),
                            new Array('fromDate','From','width="8%" align="center"',true),
                            new Array('toDate','To','width="8%" align="center"',true),
                            new Array('delivered','Delivered','width="10%" align="right"',true),
                            new Array('attended','Attended','width="10%" align="right"',true),
                            new Array('percentage','Percentage','width="15%" align="right"',false)
                           );
     
     //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
     listObj3 = new initPage(url,recordsPerPage,linksPerPage,1,'','subject','ASC','attendanceResultsDiv','','',true,'listObj3',tableColumns,'','','&rClassId='+value+'&startDate='+document.getElementById('startDate').value+'&endDate='+document.getElementById('endDate').value+'&consolidatedView='+attendanceConsolidatedView);
     sendRequest(url, listObj3, '');
 }
 else{
     var tableColumns = new Array(
                            new Array('srNo','#','width="2%" align="left"',false), 
                            new Array('subjectName','Subject','width="15%" align="left"',true),
                            new Array('studyPeriod','Study Period','width="12% align="left"',true), 
                            new Array('fromDate','From','width="8%" align="center"',true),
                            new Array('toDate','To','width="8%" align="center"',true),
                            new Array('delivered','Delivered','width="10%" align="right"',true),
                            new Array('attended','Attended','width="10%" align="right"',true),
                            new Array('percentage','Percentage','width="8%" align="right"',false)
                           );
     
     //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
     listObj3 = new initPage(url,recordsPerPage,linksPerPage,1,'','subject','ASC','attendanceResultsDiv','','',true,'listObj3',tableColumns,'','','&rClassId='+value+'&startDate='+document.getElementById('startDate').value+'&endDate='+document.getElementById('endDate').value+'&consolidatedView='+attendanceConsolidatedView);
     sendRequest(url, listObj3, '');
 }
}

function toggleAttendanceDataFormat(value){
 var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/StudentActivity/ajaxAttendanceList.php';
 
 //if consolidated view is not required
 if(viewType==1){ 
  var tableColumns = new Array(
                            new Array('srNo','#','width="2%" align="left"',false), 
                            new Array('subjectName','Subject','width="15%" align="left"',true),
                            new Array('studyPeriod','Study Period','width="12% align="left"',true), 
                            new Array('groupName','Group','width="12% align="left"',true), 
                            new Array('employeeName','Teacher','width="15%" align="left"',true),
                            new Array('fromDate','From','width="8%" align="center"',true),
                            new Array('toDate','To','width="8%" align="center"',true),
                            new Array('delivered','Delivered','width="10%" align="right"',true),
                            new Array('attended','Attended','width="10%" align="right"',true),
                            new Array('percentage','Percentage','width="15%" align="right"',false)
                           );
     
     //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
     listObj3 = new initPage(url,recordsPerPage,linksPerPage,1,'','subject','ASC','attendanceResultsDiv','','',true,'listObj3',tableColumns,'','','&rClassId='+value+'&startDate='+document.getElementById('startDate').value+'&endDate='+document.getElementById('endDate').value+'&consolidatedView='+viewType);
     sendRequest(url, listObj3, '');
 }
else{
  var tableColumns = new Array(
                            new Array('srNo','#','width="2%" align="left"',false), 
                            new Array('subjectName','Subject','width="15%" align="left"',true),
                            new Array('studyPeriod','Study Period','width="12% align="left"',true), 
                            new Array('fromDate','From','width="8%" align="center"',true),
                            new Array('toDate','To','width="8%" align="center"',true),
                            new Array('delivered','Delivered','width="10%" align="right"',true),
                            new Array('attended','Attended','width="10%" align="right"',true),
                            new Array('percentage','Percentage','width="8%" align="right"',false)
                           );
     
     //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
     listObj3 = new initPage(url,recordsPerPage,linksPerPage,1,'','subject','ASC','attendanceResultsDiv','','',true,'listObj3',tableColumns,'','','&rClassId='+value+'&startDate='+document.getElementById('startDate').value+'&endDate='+document.getElementById('endDate').value+'&consolidatedView='+viewType);
     sendRequest(url, listObj3, ''); 
}

 attendanceConsolidatedView=viewType;
 if(viewType==1){
    viewType=0;
    //document.getElementById('consolidatedDiv').innerHTML='Consolidated View';
    document.getElementById('consolidatedDiv').innerHTML='<input type="image" name="imageField" src="'+globalCurrentThemePath+'/consolidated.gif" />';
    document.getElementById('consolidatedDiv').title='Consolidated View';    
 }
 else{
    viewType=1;
    //document.getElementById('consolidatedDiv').innerHTML='Detailed View';
    document.getElementById('consolidatedDiv').innerHTML='<input type="image" name="imageField" src="'+globalCurrentThemePath+'/detailed.gif" />';
    document.getElementById('consolidatedDiv').title='Detailed View';
 }

}
 

// Student Offense/Achv Detail

//this function fetches records corresponding to resource uploaded
//this function fetches records corresponding to student offence
function refreshOffenseAchvData(){
    
    var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxStudentOffence.php';
    
    var tableColumns = new Array(
                            new Array('srNo','#','width="2%" valign="middle"',false), 
                            new Array('offenseName','Offense/Achv','width="15%" valign="middle"',true),
                            new Array('offenseDate','Date','width="10%" align="center" valign="middle"',true) , 
                            new Array('periodName','Study Period','width="13%" valign="middle"',true) , 
                            new Array('reportedBy','Reported By','width="15%" valign="middle"',true) , 
                            new Array('remarks','Remarks','width="50%" valign="middle" align="left"',true) 
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj9 = new initPage(url,recordsPerPage,linksPerPage,1,'','offenseName','ASC','offenceResultsDiv','','',true,'listObj9',tableColumns,'','','studentId='+"<?php echo $REQUEST_DATA['id']; ?>");
 sendRequest(url, listObj9, '')
} 


function showMessageDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 400, 200)
    populateMessageValues(id);
}

    //-------------------------------------------------------
    //THIS FUNCTION IS USED TO POPULATE "divNotice" DIV
    //
    //Author : Parveen Sharma
    // Created on : (27.11.2008)
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    function populateMessageValues(id) {
             url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetOffenseDetails.php';
             new Ajax.Request(url,
               {
                 method:'post',
                 parameters: {disciplineId: id},
                 onCreate: function() {
                     showWaitDialog(true);
                 },
                 onSuccess: function(transport){
                         hideWaitDialog(true);
                        if(trim(transport.responseText)==0) {
                            hiddenFloatingDiv('divMessage');
                            messageBox("This Offense/Achievements Record Does Not Exists");
                            //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                          //return false;
                       }
                        j = trim(transport.responseText);
                        document.getElementById('message').innerHTML= j;
                 },
                 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
               });
    }

    var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
    //---------------------------------------------------------------------------------
    //THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
    //
    //Author : Dipanjan Bhattacharjee
    // Created on : (14.7.2008)
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //
    //-----------------------------------------------------------------------------------
    function getData(){
      document.getElementById("nameRow").style.display='';
      document.getElementById("nameRow2").style.display='';
      document.getElementById("resultRow").style.display='';
      // url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
      refreshOffenseAchvData();
       return false;  
    }

    function hideResults() {
        document.getElementById("resultRow").style.display='none';
        document.getElementById('nameRow').style.display='none';
        document.getElementById('nameRow2').style.display='none';
    }


    function printReport() {                              

        path='<?php echo UI_HTTP_PATH;?>/Teacher/listAchievementsOffencePrint.php?classId='+document.getElementById('classId').value+'&subjectId='+document.getElementById('subject').value+'&groupId='+document.getElementById('group').value+'&studentRollNo='+trim(document.getElementById('rollNo').value)+'&sortOrderBy='+listObj6.sortOrderBy+'&sortField='+listObj6.sortField+str;
        window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
    }

    /* function to print all fee collection to csv*/
    function printReportCSV() {

        path='<?php echo UI_HTTP_PATH;?>/Teacher/listAchievementsOffencePrintCSV.php?classId='+document.getElementById('classId').value+'&subjectId='+document.getElementById('subject').value+'&groupId='+document.getElementById('group').value+'&studentRollNo='+trim(document.getElementById('rollNo').value)+'&sortOrderBy='+listObj6.sortOrderBy+'&sortField='+listObj6.sortField+str;
        window.location=path;
    }

 
/* 
function refreshStudentData(value){
     refreshGroupData(value) ;  
     refreshMarksData(value); 
     refreshAttendanceData(value);                             
     refreshResourceData(value);    
}
*/
//this function is uded to refresh tab data based uplon selection of study periods
function refreshStudentData(value,tabIndex){
    
    //get the data of course based upon selected study period
    if(tabIndex==2 && value!=ccId){
    //get the data of course based upon selected study period
     var courseData=refreshGroupData(value);
     ccId=value;
    }
    
   //get the data of course based upon selected study period
    if(tabIndex==3 && value!=tcId){
        //get the data of grade based upon selected study period
        var gradeData=refreshMarksData(value);
        tcId=value
        return;
    }
    
   //get the data of course based upon selected study period
    if(tabIndex==4 && value!=mcId){ 
        //get the data of attendance based upon selected study period
        var attendanceData=refreshAttendanceData(value);
        mcId=value;
        return;
    }
 
    //get the data of course based upon selected study period
    if(tabIndex==5 && value!=reId){ 
        
        var resourceData=refreshResourceData(value);
        reId=value;
        return;
    }
}
function  download(str){    
  str = escape(str);
  var address="<?php echo IMG_HTTP_PATH;?>/CourseResource/"+str;
  window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}

function goToURL(path){
    window.location=path;
}

function sendKeysForResource(eleName, e) {
 var ev = e||window.event;
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13') {
   refreshResourceData(document.getElementById('studyPeriod').value);  
   document.getElementById('searchbox').focus(); 
   return false;
 }
}

window.onload=function(){
    //refreshStudentData(document.getElementById('studyPeriod').value);
    //get the data of resource 
    //var resourceData=refreshResourceData(<?php echo $studentDataArr[0]['classId']; ?>);
    refreshOffenseAchvData();
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");

   //this will fetch the Latest Registration Details from the student_registration table
   require_once(MODEL_PATH . "/StudentRegistration.inc.php");
   $studentRegistration = StudentRegistration::getInstance();
   $getStudentRegistrationInfo=$studentRegistration->getStudentInfo($_REQUEST['id']);

    require_once(TEMPLATES_PATH . "/Teacher/StudentActivity/studentDetailContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: studentDetail.php $
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 14/10/09   Time: 12:11
//Updated in $/LeapCC/Interface/Teacher
//Corrected alignment of offense date column
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 8/10/09    Time: 14:19
//Updated in $/LeapCC/Interface/Teacher
//Done bug fixing.
//Bug ids---
//00001621,00001644,00001645,00001646,
//00001647,00001711
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 7/10/09    Time: 16:41
//Updated in $/LeapCC/Interface/Teacher
//Done bug fixing.
//Bug ids---
//00001726,
//00001714,
//00001713
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 7/10/09    Time: 15:52
//Updated in $/LeapCC/Interface/Teacher
//Added Detailed(group wise) and Consolidated view(irrespective of groups
//of a subject) of attendance records in student tabs .Now user can
//choose whether to view complete or just consolidated attendance of a
//student.This is also done in print & export to excel version of these
//reports as applicable.
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Interface/Teacher
//Corrected look and feel of teacher module logins
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Interface/Teacher
//Added Role Permission Variables
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 13/07/09   Time: 11:59
//Updated in $/LeapCC/Interface/Teacher
//Added "Class" column in student display and corrected session changing
//problem
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 26/06/09   Time: 10:37
//Updated in $/LeapCC/Interface/Teacher
//Done GNIMT enhancements as on 26.06.2009
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/30/09    Time: 7:15p
//Updated in $/LeapCC/Interface/Teacher
//condition & formatting update
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/05/08   Time: 1:37p
//Updated in $/LeapCC/Interface/Teacher
//Corrected Student Tabs
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Teacher
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/22/08    Time: 3:27p
//Updated in $/Leap/Source/Interface/Teacher
//Added Standard Messages
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/16/08    Time: 4:10p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 7/31/08    Time: 7:26p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 7/31/08    Time: 3:04p
//Updated in $/Leap/Source/Interface/Teacher
//Added onCreate() function in ajax code
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 7/26/08    Time: 10:37a
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/24/08    Time: 7:57p
//Updated in $/Leap/Source/Interface/Teacher
//Changed header.php and footer.php paths to the original paths
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/17/08    Time: 5:17p
//Updated in $/Leap/Source/Interface/Teacher
//ifTeacherNotLoggedIn
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/15/08    Time: 5:36p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:18p
//Created in $/Leap/Source/Interface/Teacher
//Initial checkin
?>
