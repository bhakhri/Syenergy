<?php
//------------------------------------------------------------------------
// THIS FILE IS USED TO SHOW MANAGMENT DASHBOARD
//
//
// Author : Rajeev Aggarwal
// Created on : (08.10.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH.'/HtmlFunctions.inc.php');
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifManagementNotLoggedIn();
//for displaying notices,events etc
require_once(BL_PATH . "/Management/dashBoardList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Home </title>
<?php require_once(TEMPLATES_PATH .'/jsCssHeader.php'); ?>

<script language="javascript">
var topPos = 0;
var leftPos = 0;
checkStatus = 0;
perVal = 0;

function showNoticeDetails(id,dv,w,h) {
    //displayWindow('divNotice',600,600);
	displayFloatingDiv(dv,'', w, h, 200, 180)
    populateNoticeValues(id);
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divNotice" DIV
//
//Author : Rajeev Aggarwal
// Created on : (15.10.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateNoticeValues(id) {
         //url = '<?php echo HTTP_LIB_PATH;?>/Management/ajaxGetNoticeDetails.php';
         url = '<?php echo HTTP_LIB_PATH;?>/Index/ajaxGetNoticeDetails.php';  
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {noticeId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('divNotice');
                        messageBox("This Notice Record Doen Not Exists");
                        //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        //return false;
                   }
                    j = eval('('+trim(transport.responseText)+')');
                  document.getElementById('noticeSubject').innerHTML = trim(j.noticeSubject);
                  document.getElementById('noticeDepartment').innerHTML = trim(j.departmentName+' ('+j.abbr+')');
                  document.getElementById('noticeText').innerHTML = trim(j.noticeText);
                  document.getElementById('visibleToDate').innerHTML=customParseDate(j.visibleToDate,"-");
                  document.getElementById('visibleFromDate').innerHTML=customParseDate(j.visibleFromDate,"-");

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY Event Div
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
/*function showEventDetails(id) {
    displayWindow('divEvent',300,200);
    populateEventValues(id);
}*/

function showEventDetails(id,dv,w,h) {

	displayFloatingDiv(dv,'', w, h, 200, 180)
    //displayWindow('divEvent',300,200);
    populateEventValues(id);
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divAttendance" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateEventValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Management/ajaxGetEventDetails.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {eventId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('divEvent');
                        messageBox("This Event Record Doen Not Exists");
                        //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        //return false;
                   }
                    j = eval('('+trim(transport.responseText)+')');

                   document.getElementById('eventTitle').innerHTML = trim(j.eventTitle);
				   document.getElementById('shortDescription').innerHTML = trim(j.shortDescription);
				   document.getElementById('longDescription').innerHTML = trim(j.longDescription);
				   document.getElementById('startDate').innerHTML = customParseDate(j.startDate,"-");
				   document.getElementById('endDate').innerHTML = customParseDate(j.endDate,"-");

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function showMessageDetails(id,dv,w,h) {


	displayFloatingDiv(dv,'', w, h, 200, 180)
    //displayWindow('divEvent',300,200);
    populateMessageValues(id);
}

function populateMessageValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Management/scAjaxGetMessageDetails.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {messageId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('divMessage');
                        messageBox("This Message Doen Not Exists");
                        //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        //return false;
                   }
                   j = eval('('+trim(transport.responseText)+')');


				   document.getElementById('subject').innerHTML = trim(j.subject);
                   document.getElementById('message').innerHTML = trim(j.message);
                   var dt=j.dated.split(' ');
                   if(dt.length >1 ){
                    document.getElementById('dated').innerHTML = customParseDate(dt[0],"-")+" "+dt[1];
                   }
                   else{
					   document.getElementById('dated').innerHTML = customParseDate(dt[0],"-");

                   }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


var topPos = 0;
var leftPos = 0;



var tableHeadArray = new Array(new Array('srNo','#','width="3%"','valign="top"',false),
new Array('userName','Sender','width="100"','valign="top"',true) ,
new Array('subject','Subject','width="200"','valign="top"',true),
new Array('message','Synopsis','width="400"','valign="top"',true) ,
new Array('dated','Date','width="100"','align="right" style="padding-right:5px" valign="top"',true));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxAdminMessageList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
/*
addFormName    = 'AddCity';
editFormName   = 'EditCity';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteCity';
*/
divResultName  = 'results';
page=1; //default page
sortField = 'userName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whe

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY Attendance Div
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function showAttendanceDetails(id) {
    displayWindow('divAttendance',300,200);
    populateAttendanceValues(id);
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divAttendance" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateAttendanceValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAttendanceLastTakenDetails.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {attendanceId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('divAttendance');
                        messageBox("This Attendance Record Doen Not Exists");
                        //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        //return false;
                   }
                    j = eval('('+trim(transport.responseText)+')');

                   document.AttendanceForm.subject.value = j.subjectName;
                   document.AttendanceForm.tclass.value =trim(j.className);
                   document.AttendanceForm.date.value = customParseDate(document.getElementById("toDate"+id).value,"-");

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function  download(str){
//location.href = "<?php echo HTTP_LIB_PATH.'/';?>/Notice/noticeDownload.php?path="+str;
//location.href="<?php echo IMG_HTTP_PATH.'/';?>Notice/"+str;
var address="<?php echo IMG_HTTP_PATH;?>/Notice/"+str;
window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}

function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');
      return false;
    }
    //document.getElementById('divHelpInfo').innerHTML=title;
    document.getElementById('helpInfo').innerHTML= msg;
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);

    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}

function showExamStatistics() {

    var tableColumns = new Array(new Array('srNo','#','width="2%" align="left"',false),
                                 new Array('employeeName','Teacher','width=15%',true),
                                 new Array('className','Class','width=15%',true),
                                 new Array('subjectCode','Subject','width="5%"',true),
                                 new Array('groupShort','Group','width="5%"',true),
                                 new Array('testName','Test','width="12%"',true),
                                 new Array('testDate','Test Date','width="12%" align="center"',true),
                                 new Array('maxMarks','M.Marks','width="5%" align="right"',true),
                                 new Array('maxMarksScored','Max.Scored','width="5%" align="right"',true),
                                 new Array('minMarksScored','Min.Scored','width="5%" align="right"',true),
                                 new Array('avgMarks','Avg.','width="5%" align="right"',true),
                                 new Array('presentCount','Pre.','width="5%" align="right"',true),
                                 new Array('absentCount','Ab.','width="5%" align="right"',true));

    var listURL='<?php echo HTTP_LIB_PATH;?>/Management/ajaxGetTeacherTests.php';
    var divResultName = 'examStatisticsDiv';
    var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window

    recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
    linksPerPage = <?php echo LINKS_PER_PAGE;?>;

    searchFormName = 'AttendanceForm'; // name of the form which will be used for search
    addFormName    = 'AddState';
    editFormName   = 'EditState';
    winLayerWidth  = 300; //  add/edit form width
    winLayerHeight = 250; // add/edit form height
    deleteFunction = 'return deleteState';
    page=1; //default page
    sortField = 'className';
    sortOrderBy    = 'Asc';

    document.getElementById('examStatisticsTable').innerHTML = '';
    displayFloatingDiv('examStatisticsDiv',450,550);
     //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
    listObj = new initPage(listURL,recordsPerPage,linksPerPage,1,'','employeeName','ASC','examStatisticsTable','LeaveTypeResultDiv','',true,'listObj',tableColumns,'editWindow','deleteLeaveType','');
    sendRequest(listURL, listObj, '');
}


function chkObject(id){
  obj = document.smsSendingForm.elements[id];
  if(obj.length > 0) {
      return true;
  }
  else{
    return false;;
  }
}

function  selectStudents(){

    //state:checked/not checked
    var state=document.getElementById('studentList').checked;
    if(!chkObject('students')){
     document.smsSendingForm.students.checked =state;
     return true;
    }
    formx = document.smsSendingForm;
    var l=formx.students.length;
    for(var i=0 ;i < l ; i++){
        formx.students[ i ].checked=state;
    }
}


function validateForm() {
    if(document.smsSendingForm.students.length == 0){
       messageBox("<?php echo NO_DATA_SUBMIT; ?>");
       return false;
    }
    else if(!(checkStudents())){  //checkes whether any student/parent checkboxes selected or not
        messageBox("<?php echo SELECT_STUDENT_MSG; ?>");
        document.getElementById('studentList').focus();
        return false;
    }
    else if(!(document.getElementById('smsCheck').checked) && !(document.getElementById('emailCheck').checked) && !(document.getElementById('dashBoardCheck').checked)) {
       alert("<?php echo SELECT_MSG_MEDIUM; ?>");
       document.getElementById('dashBoardCheck').focus();
       return false;
    }

    if(trim(document.getElementById('msgSubject').value)=="") {
        messageBox("<?php echo EMPTY_SUBJECT; ?>");
        document.getElementById('msgSubject').focus();
        return false;
    }
    else if(isEmpty(tinyMCE.get('elm1').getContent())) {
        messageBox("<?php echo EMPTY_MSG_BODY; ?>");
        tinyMCE.execInstanceCommand("elm1", "mceFocus");
        return false;
    }
    else if(document.getElementById('dashBoardCheck').checked && !dateDifference(document.getElementById('startDate').value,document.getElementById('endDate').value,"-")){
        messageBox("<?php echo STUDENT_MSG_DATE_VALIDATION; ?>");
        document.getElementById('startDate').focus();
        return false;
     }
    else{
         sendMessage(); //sends the message
         return false;
      }
}


function sendMessage() {
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxSendStudentMessage.php';


         //determines which student and parents are selected and their studentIds
         formx = document.smsSendingForm;
         var student="";  //get studentIds when student checkboxes are selected

        if((document.smsSendingForm.students.length)<=1){
           student=(document.smsSendingForm.students[0].checked ? document.smsSendingForm.students[0].value : "0" );
         }
        else{
         var m=formx.students.length;
         for(var k=0 ; k < m ; k++){
            if(formx.students[ k ].checked==true){
                if(student==""){
                    student= formx.students[ k ].value;
                }
               else{
                    student+="," + formx.students[ k ].value;
               }
            }
         }
        }
         //determines message medium
         var msgMedium=((document.getElementById('smsCheck').checked) ? document.getElementById('smsCheck').value: 0)+","+((document.getElementById('emailCheck').checked) ? document.getElementById('emailCheck').value: 0)+","+((document.getElementById('dashBoardCheck').checked) ? document.getElementById('dashBoardCheck').value: 0) ;

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {msgBody: (tinyMCE.get('elm1').getContent()),
             student: (student),
             msgMedium: (msgMedium),
             msgSubject:(trim(document.getElementById('msgSubject').value)),
             visibleFrom:(document.getElementById('startDate').value),
             visibleTo:(document.getElementById('endDate').value),
             nos:(trim(document.getElementById('sms_no').value))
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     var ret=trim(transport.responseText).split('!~!~!');
                     var eStr='';
                     var fl=0;
                     if("<?php echo SUCCESS;?>" == ret[0]) {
                       flag = true;
                       if(ret[1]!=''){
                         eStr ='\nSMS not sent to these students :\n'+ret[1];
                         fl=1;
                       }
                       else {
                          ret[1]=-1;
                       }
                       if(fl==1){
                         if(confirm("<?php echo MESSAGE_NOT_SEND; ?>")){
                           window.location = "<?php echo UI_HTTP_PATH ?>/Teacher/detailsTeacherMessageDocument.php?type=s&emailStudentIds="+ret[1];
                         }
                       }
                       else {
                         messageBox("<?php echo MSG_SENT_OK; ?>"+eStr);
                         hiddenFloatingDiv('messageSendingDiv');
                       }
                     }
                     else {
                        messageBox(ret[0]);
                     }
                     resetForm();
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


function checkStudents(){

    var fl=0;
    if(!chkObject('students')){
     if(document.smsSendingForm.students.checked==true){
         fl=1;
     }
     return fl;
   }
    formx = document.smsSendingForm;
    var l=formx.students.length;

    for(var i=0 ;i < l ; i++){
        if(formx.students[ i ].checked==true){
            fl=1;
            break;
        }
    }

    return (fl);

}



//-------------------------------------------------------
//THIS FUNCTION IS USED TO hide "showList" div
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function hide_div(id,mode){

    if(mode==2){
     document.getElementById(id).style.display='none';
    }
    else{
        document.getElementById(id).style.display='block';
    }
}

function showMessageSending(str, str2, str3) {
    document.smsSendingForm.reset();
    tinyMCE.get('elm1').setContent('');

    document.getElementById('classSubjectDiv').innerHTML = str3;
    subjectCodeClassNameArray = str3.split('#');
    subjectCode = subjectCodeClassNameArray[0];
    className = subjectCodeClassNameArray[1];
    employeeName = subjectCodeClassNameArray[2];

    if (str == 'attendanceThreshold') {
        document.getElementById('classSubjectDiv').innerHTML = '<b>List of Students having attendance below Attendance Threshold ('+<?php echo $sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');?>+' %) </b>' ;
    }
    else if (str == 'toppers'){
        document.getElementById('classSubjectDiv').innerHTML = '<b>List of Top '+<?php echo $sessionHandler->getSessionVariable('TOPPERS_RECORD_LIMIT'); ?>+' students</b>';
    }
    else if (str == 'belowAvg'){
        document.getElementById('classSubjectDiv').innerHTML = '<b>List of Students having marks below average (below '+<?php echo $sessionHandler->getSessionVariable('BELOW_AVERAGE_PERCENTAGE');?>+'%)</b>';
    }
    else if (str == 'aboveAvg'){
        document.getElementById('classSubjectDiv').innerHTML = '<b>List of Students having marks above average (more than '+ <?php echo $sessionHandler->getSessionVariable('ABOVE_AVERAGE_PERCENTAGE');?>+ '%)</b>';
    }
    else {
        return false;
    }
    if (str2 == '') {
        return false;
    }

    document.getElementById('classSubjectDiv').innerHTML += '<b> for Class: '+className+', Teacher: '+employeeName+', Subject: '+subjectCode+'</b>';


    var tableColumns2 = new Array(new Array('srNo','#','width="2%"','',false),
                                  new Array('students','<input type=\"checkbox\" id=\"studentList\" name=\"studentList\" onclick=\"selectStudents();\">','width="3%"','align=\"left\"',false),
                                  new Array('rollNo','Roll No.','width="5%"','',true),
                                  new Array('studentName','Student Name','width="5%"','',true),
                                  new Array('imgSrc','Photo','width="5%"','',true),
                                  new Array('percentage','Percent','width="5%" align="right"','',true));

    var listURL='<?php echo HTTP_LIB_PATH;?>/Management/showDashBoardList.php';
    var divResultName = 'examStatisticsDiv';
    var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window

    recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
    linksPerPage = <?php echo LINKS_PER_PAGE;?>;

    searchFormName = 'AttendanceForm'; // name of the form which will be used for search
    addFormName    = 'AddState';
    editFormName   = 'EditState';
    winLayerWidth  = 900; //  add/edit form width
    winLayerHeight = 250; // add/edit form height
    deleteFunction = 'return deleteState';
    page=1; //default page
    sortField = 'className';
    sortOrderBy    = 'Asc';
     //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
    displayFloatingDiv('messageSendingDiv',900,200);
    listObj = new initPage(listURL,500000,500000,1,'','className','ASC','messageSendingTable','LeaveTypeResultDiv','',false,'listObj',tableColumns2,'editWindow','deleteLeaveType','');
    sendRequest(listURL, listObj, 'mode='+str+'&val='+escape(str2));

}

</script>
</head>
<body>
<?php
function trim_output($str,$maxlength,$mode=1,$rep='...'){
   $ret=($mode==2?chunk_split($str,30):$str);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep;
   }
  return $ret;
}

?>
<?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Management/index.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>

<script language="javascript">
window.onload=function(){
   enableTooltips();
   getShowDetail(1);
};
//document.getElementById('div_Alerts').style.width="420px";

function showData(id)
{
	path='<?php echo UI_HTTP_PATH;?>/Management/branchWiseStudentPrint.php?branchId='+id;
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
}

function loginShowData(id){

    path='<?php echo UI_HTTP_PATH;?>/Management/userWisePrint.php?dateSelected='+id;
    window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
}

function getShowDetail(val) {

   document.getElementById("answer1").style.display='none';
   document.getElementById('answer2').style.display='none';
   document.getElementById('answer3').style.display='none';
   document.getElementById('answer4').style.display='none';

   document.getElementById("question1").src = "<?php echo IMG_HTTP_PATH;?>/arrow-up.gif"
   document.getElementById('question2').src = "<?php echo IMG_HTTP_PATH;?>/arrow-up.gif"
   document.getElementById('question3').src = "<?php echo IMG_HTTP_PATH;?>/arrow-up.gif"
   document.getElementById('question4').src = "<?php echo IMG_HTTP_PATH;?>/arrow-up.gif"

   if(val==1 && (checkStatus==0 || perVal!=val) ) {
     document.getElementById("answer1").style.display='';
     document.getElementById("question1").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif"
   }
   else if(val==2 && (checkStatus==0 || perVal!=val)) {
     document.getElementById("answer2").style.display='';
     document.getElementById("question2").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif"
   }
   else if(val==3 && (checkStatus==0 || perVal!=val)) {
     document.getElementById("answer3").style.display='';
     document.getElementById("question3").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif"
   }
   else if(val==4 && (checkStatus==0 || perVal!=val)) {
     document.getElementById("answer4").style.display='';
     document.getElementById("question4").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif"
   }

   if(checkStatus==1) {
     checkStatus=0;
   }
   else {
     checkStatus=1;
   }
   perVal = val;
}


</script>



</body>
</html>
<?php
// $History: index.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/11/10    Time: 2:30p
//Updated in $/LeapCC/Interface/Management
//query & validation format updated (topper, below, average, i.e. added)
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:01p
//Created in $/LeapCC/Interface/Management
//Inital checkin
?>
