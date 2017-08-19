<?php
//used for showing teacher dashboard
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH.'/HtmlFunctions.inc.php');
UtilityManager::ifTeacherNotLoggedIn();
define('MODULE','TeacherDashBoard');
//for displaying notices,events etc
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Home </title>
<?php
 require_once(TEMPLATES_PATH .'/jsCssHeader.php');
 require_once(BL_PATH . "/Teacher/TeacherActivity/dashBoardList.php");
// echo UtilityManager::includeJS("tiny_mce/tiny_mce.js");
 echo UtilityManager::includeCSS2();
?>
<script language="javascript">

var topPos = 0;
var leftPos = 0;


var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
<?php
//echo UtilityManager::javaScriptFile2();
?>
function dateDivShow()
{
  if(document.getElementById('dashBoardCheck').checked){
      document.getElementById('dateDiv').style.display='block';
      document.getElementById('startDate').focus();
  }
 else{
     document.getElementById('dateDiv').style.display='none';
 }
}

function smsDivShow() {
  if(document.getElementById('smsCheck').checked){
      document.getElementById('smsDiv').style.display='block';
  }
 else{
     document.getElementById('smsDiv').style.display='none';
 }
}


//---------------------------------------------------------------------------------
//purspose:to show subject options when msgmedium is email
//Author: Dipanjan Bhattacharjee
//Date: 21.07.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------
function subjectDivShow()
{
 /*
  if(document.getElementById('emailCheck').checked){
      document.getElementById('subjectDiv').style.display='block';
      document.getElementById('msgSubject').focus();
  }
 else{
     document.getElementById('subjectDiv').style.display='none';
 }
 */
}
function smsCalculation(value,limit,target){

 var temp1=value;
 var nos=1;    //no of sms limit://length of a sms
 if(tinyMCE.get('elm1').getContent()!=""){
  document.getElementById('sms_char').value=(parseInt(temp1.length)+1-3);
 }
 else{
  document.getElementById('sms_char').value=0;
 }
 while(temp1.length > (limit+2)){
     temp1=temp1.substr(limit);
     nos=nos+1;
 }
document.getElementById(target).value=nos;
}

var SMSML=<?php echo SMS_MAX_LENGTH; ?>;
/*
tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        plugins : "paste",
        theme_advanced_buttons3_add : "pastetext,pasteword,selectall",
        paste_auto_cleanup_on_paste : true,
        paste_preprocess : function(pl, o) {
            // Content string containing the HTML from the clipboard
            //alert(o.content);
        },
        paste_postprocess : function(pl, o) {
            // Content DOM node containing the DOM structure of the clipboard
            //alert(o.node.innerHTML);
        },
        setup : function(ed) {
        ed.onKeyUp.add(function(ed, e) {
          smsCalculation("'"+removeHTMLTags(tinyMCE.get('suggestionText').getContent())+"'",SMSML,'sms_no');
         }
        );
      },
      //auto_focus : "elm1",

       // Theme options
       theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|formatselect,fontselect,fontsizeselect",
       theme_advanced_buttons2 : "bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",

    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left"
    //theme_advanced_statusbar_location : "bottom",
    //theme_advanced_resizing : true
});
*/


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

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY Notice Div
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
function showNoticeDetails(id) {
    displayWindow('divNotice',300,200);
    populateNoticeValues(id);
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divNotice" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateNoticeValues(id) {
         //var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetNoticeDetails.php';
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
                   var j = eval('('+trim(transport.responseText)+')');
                   document.getElementById('noticeSubject').innerHTML = trim(j.noticeSubject);
                   document.getElementById('noticeDepartment').innerHTML = trim(j.departmentName+' ('+j.abbr+')');
                   document.getElementById('noticeText').innerHTML = trim(j.noticeText);
                   document.getElementById('visibleToDateNotice').innerHTML = customParseDate(j.visibleToDate,"-");
                   document.getElementById('visibleFromDateNotice').innerHTML = customParseDate(j.visibleFromDate,"-");

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
function showEventDetails(id) {
    displayWindow('divEvent',300,200);
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
         var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetEventDetails.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {eventId: id},

              onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                  hideWaitDialog();
                 j= trim(transport.responseText).evalJSON();
                  document.getElementById("innerNotice").innerHTML = j.eventTitle;
                  document.getElementById("innerDescription").innerHTML = j.shortDescription;
                  document.getElementById("visibleFromDate").innerHTML = customParseDate(j.startDate,"-");
                  document.getElementById("visibleToDate").innerHTML = customParseDate(j.endDate,"-");
                  document.getElementById("longDescription").innerHTML = j.longDescription;
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
function showMessageDetails(id) {
    displayWindow('divMessage',300,200);
    populateMessageValues(id);
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divAttendance" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateMessageValues(id) {
         var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetMessageDetails.php';
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
                   document.getElementById("sub").innerHTML = trim(j.subject);
             //      document.MessageForm.message.value = trim(j.message);

                   document.getElementById("message").innerHTML = trim(j.message);

                   var dt=j.dated.split(' ');
                   if(dt.length >1 ){
                    document.getElementById("dated").innerHTML = customParseDate(dt[0],"-")+" "+dt[1];
                   }
                   else{
                      document.getElementById("dated").innerHTML = customParseDate(dt[0],"-");
                   }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO check time table cookie
// Author : Dipanjan Bhattacharjee
// Created on : (04.09.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function checkTimeTableAlert() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/ajaxCheckTimeTableAlertCookie.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {1: 1},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    if(trim(transport.responseText)==1) {
                        window.location="listTimeTable.php";
                        return false;
                    }
                    return false;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
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


function  download(str){
 str = escape(str);
 var address="<?php echo IMG_HTTP_PATH;?>/Notice/"+str;
 window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}

function showExamStatistics() {
	 tableColumns = new Array(new Array('srNo','#','width="2%"','',false),
		 new Array('className','Class','width=15%',true),
		 new Array('subjectCode','Subject','width="5%"',true),
		 new Array('groupShort','Group','width="5%"',true),
		 new Array('testName','Test','width="12%"',true),
		 new Array('testDate','Test Date','width="12%" align="center"','align="center"',true),
		 new Array('maxMarks','M.Marks','width="5%" align="right"',true),
		 new Array('maxMarksScored','Max.Scored','width="5%" align="right"',true),
		 new Array('minMarksScored','Min.Scored','width="5%" align="right"',true),
		 new Array('avgMarks','Avg.','width="5%" align="right"',true),
		 new Array('presentCount','Pre.','width="5%" align="right"',true),
		 new Array('absentCount','Ab.','width="5%" align="right"',true));

	var listURL='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetTeacherTests.php';
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
	 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
	 document.getElementById('examStatisticsTable').innerHTML = '';
	displayFloatingDiv('examStatisticsDiv',300,200);
	listObj = new initPage(listURL,500000,500000,1,'','className','ASC','examStatisticsTable','LeaveTypeResultDiv','',false,'listObj',tableColumns,'editWindow','deleteLeaveType','');
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
		try {
			tinyMCE.execInstanceCommand("elm1", "mceFocus");
		}
		catch (e) {
		}
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
	//tinyMCE.get('elm1').setContent('');

	document.getElementById('classSubjectDiv').innerHTML = str3;
	subjectCodeClassNameArray = str3.split('#');
	subjectCode = subjectCodeClassNameArray[0];
	className = subjectCodeClassNameArray[1];

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

	document.getElementById('classSubjectDiv').innerHTML += '<b> for Class: '+className+', Subject: '+subjectCode+'</b>';


	var tableColumns2 = new Array(new Array('srNo','#','width="2%"','',false),
		new Array('students','<input type=\"checkbox\" id=\"studentList\" name=\"studentList\" onclick=\"selectStudents();\">','width="3%"','align=\"left\"',false),
		new Array('rollNo','Roll No.','width="5%"','',true),
		new Array('universityRollNo','Univ. Roll No.','width="5%"','',true),
		new Array('studentName','Student Name','width="5%"','',true),
		new Array('imgSrc','Photo','width="5%"','',true),
		new Array('percentage','Percent','width="5%" align="right"','',true));

	var listURL='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/showDashBoardList.php';
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
function examStatisticsPrintReport() {
     var nam= document.getElementById('employeeId1').value;
	//alert(nam);
    str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value;
    path='<?php echo UI_HTTP_PATH;?>/Teacher/examStatisticsPrint.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj.sortOrderBy+'&sortField='+listObj.sortField+str;
    //alert(path);
    window.open(path,"ExamStatisticsReport","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

function examStatisticsPrintReportCSV() {
    var nam= document.getElementById('employeeId1').value;
	//alert(nam);
    str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value;
    path='<?php echo UI_HTTP_PATH;?>/Teacher/examStatisticsPrintCSV.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj.sortOrderBy+'&sortField='+listObj.sortField+str;
    //window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
    window.location = path;
}

function showAvailableWidgets(){
    /*
    if(document.getElementById('availabeWidgetsId').style.display==''){
      document.getElementById('availabeWidgetsId').style.display='none';
    }
    else{
      document.getElementById('availabeWidgetsId').style.display='';
    }
    */
    displayFloatingDiv('divWidgets',415,250);
}


function toggleWidgets(id,value,checked){
    var widgId=id.split('_')[0];
    //var action=id.split('_')[1];
    var action=checked;
    if(action==false){
      /*
       if(!confirm('This widget will be removed, are you sure?')){
           return false;
       }
      */
       $ = $j;
       if($('#'+widgId).remove()){
         iNettuts.savePreferences();
         //document.getElementById(id).checked=false;
         //document.getElementById(id).disabled=true;
         //document.getElementById(widgId+'_1').disabled=false;
       }
    }
   else{
       var valArray=value.split('_');
       var col=trim(valArray[0]);
       var title=trim(valArray[1]);
       //document.getElementById(id).checked=false;
       //document.getElementById(id).disabled=true;
       //document.getElementById(widgId+'_2').disabled=false;
	   iNettuts.addWidget(col, {
            id: trim(widgId),
            color: "color-white",
            title: title
       })
   }
}

</script>
</head>
<body>
<?php
//---------------------------------------------------------------------------------------------------------------
//purpose: to trim a string and output str.. etc
//Author:Dipanjan Bhattcharjee
//Date:26.07.2008
//$str=input string,$maxlenght:no of characters to show,$rep:what to display in place of truncated string
//$mode=1 : no split after 30 chars,mode=2:split after 30 characters
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
function trim_output($str,$maxlength,$mode=1,$rep='...'){
   $ret=($mode==2?chunk_split($str,30):$str);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep;
   }
  return $ret;
}

    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/index.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>

<script language="javascript">
window.onload=function(){
    enableTooltips();
    document.widgetsForm.reset();
     //alert(document.getElementById('tAttendance').style.height);
} 
//For autosuggest
function changeDefaultTextOnClick()
{
    if(document.getElementById('menuLookup').value=="Menu Lookup..")
    {
        document.getElementById('menuLookup').value="";
        document.getElementById('menuLookup').className="text_class";
    }
}
function changeDefaultTextOnBlur()
{
    if(document.getElementById('menuLookup').value=="")
    {
        document.getElementById('menuLookup').className="fadeMenuText"; 
        document.getElementById('menuLookup').value="Menu Lookup..";
    }
}
//This script throws a ajax request to populate autosuggest menu
function getMenuLookup()
{
    document.getElementById('menuLookupContainer').style.display="none";
    if(document.getElementById('menuLookup').value.length>1)
    {
        url = '<?php echo HTTP_LIB_PATH;?>/menuLookup.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {txt: document.getElementById('menuLookup').value},
             onCreate: function() {
                 
                // showWaitDialog(true);
             },
             onSuccess: function(transport){
                    // hideWaitDialog(true);
                    if((transport.responseText)!="") {
                        var display="<ul style='list-style:none'>";
                        var obj=transport.responseText.evalJSON() 
                        if(obj)
                        {
                            var objSize=10;
                            if(obj.length<10)
                            {
                                objSize=obj.length;
                            }
                            
                            for(var arrayIndex=0;arrayIndex<objSize;arrayIndex++)
                            {
                                display+="<li style='padding:3px'><a href='"+obj[arrayIndex]['link']+"'>"+obj[arrayIndex]['data']+"</a></li>";
                            }       
                        }
                        display+="</ul>";
                        document.getElementById('menuLookupContainer').style.display="";
                        document.getElementById('menuLookupContainer').style.display="block";
                        document.getElementById('menuLookupContainer').innerHTML=display;
                        return false;
                    }
             },
             onFailure: function(){ 
                 //messageBox("<?php echo TECHNICAL_PROBLEM;?>") 
             }
           });  
     }
}
// Autosuggest ends
</script>
 	



</body>
</html>
<?php
echo UtilityManager::includeJS("jquery-1.2.6.min.js");
echo UtilityManager::includeJS("jquery-ui-personalized-1.6rc2.min.js");
echo UtilityManager::includeJS("inettuts.js");
echo UtilityManager::includeJS("xhttpr.js");
?>
