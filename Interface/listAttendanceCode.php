<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Country Form
//
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AttendanceCodesMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/AttendanceCode/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Attendance Code Master </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(
                new Array('srNo','#','width="2%"','',false), 
                new Array('attendanceCodeName','Attendance Name','width="30%"','',true) , 
                new Array('attendanceCode ','Attendance Code','width="12%"','align="left"',true), 
                new Array('attendanceCodePercentage','Percentage ','width="12%" align="right"','align="right"',true) ,  
                new Array('showInLeaveType','Show in Leave Type ','width="20%" align="center"','align="center"',true) ,  
                new Array('action','Action','width="5%"','align="center"',false)
        );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/AttendanceCode/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddAttendanceCode'; 
editFormName   = 'EditAttendanceCode';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteAttendanceCode';
divResultName  = 'results';
page=1; //default page
sortField = 'attendanceCodeName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      
//This function Displays Div Window

function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);
}

//This function Validates Form 


function validateAddForm(frm, act) {

   //new Array("attendanceCodeAction","Enter Attendance Action")     
    var fieldsArray = new Array(new Array("attendanceCodeName","<?php echo ENTER_ATTENDANCE_NAME;?>"),
                                new Array("attendanceCode","<?php echo ENTER_ATTENDANCE_CODE;?>"),
                                new Array("attendanceCodePercentage","<?php echo ENTER_ATTENDANCE_PERCENTAGE;?>"));
    
    //new Array("attendanceCodeDescription","<?php echo ENTER_ATTENDANCE_DESCRIPTION;?>"), 
                                
    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winmessageBox (fieldsArray[i][1],fieldsArray[i][0]);
            messageBox (fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else if(fieldsArray[i][0]=="attendanceCodePercentage" && (!isInteger(eval("frm."+(fieldsArray[i][0])+".value")))){
            messageBox("Enter numeric value ");
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        if(fieldsArray[i][0]=="attendanceCodePercentage" && ( eval("frm."+(fieldsArray[i][0])+".value")>100 ||  eval("frm."+(fieldsArray[i][0])+".value")<0)){
            messageBox("<?php echo INVALID_PERCENTAGE;?> ");
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
     }

     if(act=='Add') {
        addAttendanceCode();
        return false;
    }
    else if(act=='Edit') {
        editAttendanceCode();
        return false;
    }
}

//This function adds form through ajax 
function addAttendanceCode() {  ;
         url = '<?php echo HTTP_LIB_PATH;?>/AttendanceCode/ajaxInitAdd.php';
		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 attendanceCodeName: trim(document.addAttendanceCode.attendanceCodeName.value), 
                 attendanceCode: trim(document.addAttendanceCode.attendanceCode.value), 
                 attendanceCodeDescription: trim(document.addAttendanceCode.attendanceCodeDescription.value),
                 attendanceCodePercentage:trim (document.addAttendanceCode.attendanceCodePercentage.value),
                 showInLeaveType: document.addAttendanceCode.showInLeave[0].checked?1:0
             },
             onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('AddAttendanceCode');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
},
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function blankValues() {
   /*
   document.addAttendanceCode.attendanceCode.value = '';
   document.addAttendanceCode.attendanceCodeName.value = '';
   document.addAttendanceCode.attendanceCodeDescription.value = '';
   document.addAttendanceCode.attendanceCodePercentage.value = '';
   */
   document.addAttendanceCode.reset();
   document.addAttendanceCode.attendanceCodeName.focus();
}
//This function edit form through ajax
function editAttendanceCode() {
         url = '<?php echo HTTP_LIB_PATH;?>/AttendanceCode/ajaxInitEdit.php';
		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 attendanceCodeId : trim(document.editAttendanceCode.attendanceCodeId.value), 
                 attendanceCodeName:trim(document.editAttendanceCode.attendanceCodeName.value), 
                 attendanceCode:trim(document.editAttendanceCode.attendanceCode.value),
                 attendanceCodeDescription:trim(document.editAttendanceCode.attendanceCodeDescription.value),
                 attendanceCodePercentage: trim(document.editAttendanceCode.attendanceCodePercentage.value),
                 showInLeaveType: document.editAttendanceCode.showInLeave[0].checked?1:0
             },
			 onCreate: function() {
			 	showWaitDialog(true);
			 }  ,
			onSuccess: function(transport){
					hideWaitDialog(true);
                     //messageBox(trim(transport.responseText));
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditAttendanceCode');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                      else {
                         messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
// THis function deletes a record through AJax
function deleteAttendanceCode(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {
         url = '<?php echo HTTP_LIB_PATH;?>/AttendanceCode/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {attendanceCodeId: id},
			 onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
                       hideWaitDialog(true);
                  //   messageBox(trim(transport.responseText));
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
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

//This function populates values in edit form through ajax 

function populateValues(id) {       
         url = '<?php echo HTTP_LIB_PATH;?>/AttendanceCode/ajaxGetValues.php';
         document.editAttendanceCode.reset();
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {attendanceCodeId: id},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
             
                    hideWaitDialog(true);
                    j = eval('('+transport.responseText+')');
                   
				   document.editAttendanceCode.attendanceCode.value = j.attendanceCode;
                   document.editAttendanceCode.attendanceCodeDescription.value = j.attendanceCodeDescription;
                   document.editAttendanceCode.attendanceCodePercentage.value = j.attendanceCodePercentage;
                   document.editAttendanceCode.attendanceCodeName.value = j.attendanceCodeName;
                   if(j.showInLeaveType==1){
                     document.editAttendanceCode.showInLeave[0].checked=true;   
                   }
                   else if(j.showInLeaveType===0){
                       document.editAttendanceCode.showInLeave[1].checked=true;
                   }
                   document.editAttendanceCode.attendanceCodeId.value  =j.attendanceCodeId;
			  },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print Subject report*/
function printReport() {
    var qstr="searchbox="+document.searchForm.searchbox.value;
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/attendaceCodeReportPrint.php?'+qstr;
    window.open(path,"SubjectReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+document.searchForm.searchbox.value;
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    
    path='<?php echo UI_HTTP_PATH;?>/attendaceCodeReportCSV.php?'+qstr;
    window.location = path;  
}


</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/AttendanceCode/listAttendanceCodeContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
<script language="javascript">
      sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
</html>
<?php
//$History: listAttendanceCode.php $
//
//*****************  Version 8  *****************
//User: Parveen      Date: 8/10/09    Time: 5:28p
//Updated in $/LeapCC/Interface
//formating, validation updated
//issue fix 994, 9943, 992, 991, 989, 987, 
//986, 985, 981, 914, 913, 911
//
//*****************  Version 7  *****************
//User: Parveen      Date: 8/08/09    Time: 5:30p
//Updated in $/LeapCC/Interface
//bug fix 505, 504, 503, 968, 961, 960, 959, 958, 957, 956, 955, 954,
//953, 952,
//951, 723, 722, 797, 798, 799, 916, 935, 936, 937, 938, 939, 940, 944
//(alignment, condition & formatting updated)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Interface
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/11/09    Time: 5:23p
//Updated in $/LeapCC/Interface
//conditions, validation & formatting updated
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 20/04/09   Time: 15:27
//Updated in $/LeapCC/Interface
//modified codes
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 20  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Interface
//Define Module, Access  Added
//
//*****************  Version 19  *****************
//User: Arvind       Date: 9/10/08    Time: 5:55p
//Updated in $/Leap/Source/Interface
//modify
//
//*****************  Version 18  *****************
//User: Arvind       Date: 9/06/08    Time: 3:23p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 17  *****************
//User: Arvind       Date: 8/27/08    Time: 3:14p
//Updated in $/Leap/Source/Interface
//modify
//
//*****************  Version 16  *****************
//User: Arvind       Date: 8/20/08    Time: 1:33p
//Updated in $/Leap/Source/Interface
//replaced validation message by common message
//
//*****************  Version 15  *****************
//User: Arvind       Date: 8/07/08    Time: 3:25p
//Updated in $/Leap/Source/Interface
//modified the display message
//
//*****************  Version 14  *****************
//User: Arvind       Date: 8/01/08    Time: 6:56p
//Updated in $/Leap/Source/Interface
//no change
//
//*****************  Version 13  *****************
//User: Arvind       Date: 8/01/08    Time: 6:53p
//Updated in $/Leap/Source/Interface
//added validation
//
//*****************  Version 12  *****************
//User: Arvind       Date: 8/01/08    Time: 6:51p
//Updated in $/Leap/Source/Interface
//added validation for percentage
//
//*****************  Version 11  *****************
//User: Arvind       Date: 8/01/08    Time: 11:20a
//Updated in $/Leap/Source/Interface
//modified
//
//*****************  Version 10  *****************
//User: Arvind       Date: 7/31/08    Time: 12:42p
//Updated in $/Leap/Source/Interface
//modified the ajax listing function
//
//*****************  Version 9  *****************
//User: Arvind       Date: 7/21/08    Time: 4:07p
//Updated in $/Leap/Source/Interface
//removed a field attendanceCodeAction
//
//*****************  Version 8  *****************
//User: Arvind       Date: 7/18/08    Time: 2:22p
//Updated in $/Leap/Source/Interface
//added messageBox in place of alert 
//
//*****************  Version 7  *****************
//User: Arvind       Date: 7/10/08    Time: 1:13p
//Updated in $/Leap/Source/Interface
//corrected the fieldname in ajaxedit function and added a validation for
//percentage
//
//*****************  Version 6  *****************
//User: Arvind       Date: 6/30/08    Time: 4:22p
//Updated in $/Leap/Source/Interface
//1) Added a new javascript function which calls table listing through
//ajax and pagination function 
//2) Added a delete funciton which call ajax file to delete
//3) Modifies add and edit funnction.
//    Data saved successfullyand
//   DO you want to add more ?
//  messageBox  is displayed in one messageBox  box
//
//*****************  Version 5  *****************
//User: Arvind       Date: 6/26/08    Time: 5:13p
//Updated in $/Leap/Source/Interface
//not changed anything
//
//*****************  Version 3  *****************
//User: Arvind       Date: 6/17/08    Time: 4:13p
//Updated in $/Leap/Source/Interface
//added new fields
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/14/08    Time: 6:23p
//Updated in $/Leap/Source/Interface
//file updated
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:17p
//Created in $/Leap/Source/Interface
//New Files Checkin

?>
