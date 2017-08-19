<?php 

//-------------------------------------------------------
//  This File contains the template file and data base file
//
// Author :Jaineesh
// Created on : 23.07.08
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentDisplayAttendance');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();
require_once(BL_PATH . "/Student/initAttendance.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Attendance Details </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
sortField = 'subjectName1';
sortOrderBy = 'ASC';


//this variable is used to determine whether group wise or 
//consolidated attendance view is required
//Modified By : Dipanjan Bhattacharjee
//Date: 06.10.2009
var attendanceConsolidatedView=1;
var viewType=0;

//this function shows duty leave details when we click on them
function showDutyLeaveDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 400, 200)
    populateDutyLeaveValues(id);
}

//this function shows medical leave details when we click on them
function showMedicalLeaveDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 400, 200)
    populateMedicalLeaveValues(id);
}


function getAttendance(value) {
 
   //var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentAttendance.php';
   var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentAttendanceList.php'; 

   var classId = document.getElementById('semesterDetail').value;
   
   //if consolidated view is not required
   if(attendanceConsolidatedView==1){
	    var tableColumns2 =new Array(
                                new Array('srNo','#','width="2%" align="left"',false), 
                                new Array('subjectName1','Subject','width="25%" align="left"',true),
                                new Array('periodName','Study Period','width="14% align="left"',true), 
                                new Array('groupName','Group','width="8%" align="left"',true),
                                new Array('employeeName','Teacher','width="15%" align="left"',true),
                                new Array('fromDate','From','width="8%" align="center"',true),
                                new Array('toDate','To','width="8%" align="center"',true),
                                 new Array('attended','Attended','width="10%" align="right"',false),
                                new Array('leaveTaken','Duty Leaves','width="10%" align="right"',false),
                                new Array('delivered','Delivered','width="10%" align="right"',false),
                                new Array('per','%age','width="10%" align="right"',false)
                               );
    }
    else{
        var tableColumns2 =new Array(
                                new Array('srNo','#','width="2%" align="left"',false), 
                                new Array('subjectName1','Subject','width="25%" align="left"',true),
                                new Array('periodName','Study Period','width="12% align="left"',true), 
                                new Array('employeeName','Teacher','width="15%" align="left"',true), 
                                new Array('fromDate','From','width="8%" align="center"',true),
                                new Array('toDate','To','width="8%" align="center"',true),
                                new Array('attended','Attended','width="10%" align="right"',false),
                                new Array('leaveTaken','Duty Leaves','width="10%" align="right"',false),
                                new Array('medicalLeaveTaken','Medical Leaves','width="10%" align="right"',false),
                                new Array('delivered','Delivered','width="10%" align="right"',false),
                                new Array('per','%age','width="10%" align="right"',false)
                               );
    }
 
    //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
    listObj3 = new initPage(url,recordsPerPage,linksPerPage,1,'','subjectName1','ASC','attendanceResultDiv','','',true,'listObj3',tableColumns2,'','','&rClassId='+classId+'&startDate2='+document.attendance.startDate2.value+'&consolidatedView='+attendanceConsolidatedView);
    sendRequest(url, listObj3, '',true );
    document.getElementById('printDiv2').style.display='';
}


function toggleAttendanceDataFormat(value){
  //var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentAttendance.php';
    var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentAttendanceList.php';  
    
    var classId = document.getElementById('semesterDetail').value;  
    //if consolidated view is not required
    if(viewType==1){ 
         var tableColumns2 =new Array(
                                    new Array('srNo','#','width="2%" align="left"',false), 
                                    new Array('subjectName1','Subject','width="25%" align="left"',true),
                                    new Array('periodName','Study Period','width="14% align="left"',true), 
                                    new Array('groupName','Group','width="8%" align="left"',true),
                                    new Array('employeeName','Teacher','width="15%" align="left"',true),
                                    new Array('fromDate','From','width="8%" align="center"',true),
                                    new Array('toDate','To','width="8%" align="center"',true),
                                     new Array('attended','Attended','width="10%" align="right"',false),
                                    new Array('leaveTaken','Duty Leaves','width="10%" align="right"',false),
                                    new Array('delivered','Delivered','width="10%" align="right"',false),
                                    new Array('per','%age','width="10%" align="right"',false)
                                   );
        }
        else{
            var tableColumns2 =new Array(
                                    new Array('srNo','#','width="2%" align="left"',false), 
                                    new Array('subjectName1','Subject','width="25%" align="left"',true),
                                    new Array('periodName','Study Period','width="12% align="left"',true), 
                                    new Array('employeeName','Teacher','width="15%" align="left"',true), 
                                    new Array('fromDate','From','width="8%" align="center"',true),
                                    new Array('toDate','To','width="8%" align="center"',true),
                                    new Array('attended','Attended','width="10%" align="right"',false),
                                    new Array('leaveTaken','Duty Leaves','width="10%" align="right"',false),
                                    new Array('medicalLeaveTaken','Medical Leaves','width="10%" align="right"',false),
                                    new Array('delivered','Delivered','width="10%" align="right"',false),
                                    new Array('per','%age','width="10%" align="right"',false)
                                   );
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
    
    //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
    listObj3 = new initPage(url,recordsPerPage,linksPerPage,1,'','subjectName1','ASC','attendanceResultDiv','','',true,'listObj3',tableColumns2,'','','&rClassId='+classId+'&startDate2='+document.attendance.startDate2.value+'&consolidatedView='+attendanceConsolidatedView);
    sendRequest(url, listObj3, '',true );
    document.getElementById('printDiv2').style.display='';
 
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "div_dutyLeave" DIV
//
//Author : Aditi Miglani
// Created on : (04.11.2011)
// Copyright 2011-2012 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateDutyLeaveValues(id) {
    url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxGetDutyLeaveValue.php';   
    document.getElementById('div_dutyLeave').innerHTML ='';
    new Ajax.Request(url,
    {      
		method:'post',
		parameters: {id: id },
		onCreate: function() {
			showWaitDialog();
		},
		onSuccess: function(transport){
		    hideWaitDialog(true); 
		    var ret=trim(transport.responseText); 
		    var retArray=ret.split('!~!');  
		    document.getElementById('div_dutyLeave').innerHTML = trim(retArray[0]);
		             //displayWindow('divMessage',200,200); 
		},
	onFailure: function(){ alert('Something went wrong...') }
   });
}


function populateMedicalLeaveValues(id) {
    
    url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxGetMedicalLeaveValue.php';   
    
    document.getElementById('div_medicalLeave').innerHTML ='';
    new Ajax.Request(url,
    {      
		method:'post',
		parameters: {id: id },
		onCreate: function() {
			showWaitDialog();
		},
		onSuccess: function(transport){
		    hideWaitDialog(true); 
		    var ret=trim(transport.responseText);  
		    var retArray=ret.split('!~!');  
		    document.getElementById('div_medicalLeave').innerHTML = trim(retArray[0]);
		             //displayWindow('divMessage',200,200); 
		},
	onFailure: function(){ alert('Something went wrong...') }
   });
}

function printReport() {

/*  form = document.attendance;
	sortField = listObj3.sortField;
	sortOrderBy = listObj3.sortOrderBy;
	var qstr = "&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/Student/displayStudentAttendanceReport.php?studyPeriodId='+form.semesterDetail.value+'&toDate='+form.startDate2.value+'&consolidatedView='+attendanceConsolidatedView;
    window.open(path,"DisplayAttendanceReport","status=1,menubar=1,scrollbars=1, width=900");
*/
	form = document.attendance;
	path='<?php echo UI_HTTP_PATH;?>/studentAttendanceReportPrint.php?&startDate2='+form.startDate2.value+'&classId='+document.getElementById('semesterDetail').value+'&sortOrderBy='+listObj3.sortOrderBy+'&sortField='+listObj3.sortField+'&consolidatedView='+attendanceConsolidatedView;
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=400, top=150,left=150");
	return false;
}

function printCSV() {
	
/*	form = document.attendance;
	sortField = listObj3.sortField;
	sortOrderBy = listObj3.sortOrderBy;
	var qstr = '&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	path='<?php echo UI_HTTP_PATH;?>/Student/studentAttendanceReportCSV.php?studyPeriodId='+form.semesterDetail.value+'&toDate='+form.startDate2.value+'&consolidatedView='+attendanceConsolidatedView+qstr;
	window.location = path;
*/
	form = document.attendance;
	path='<?php echo UI_HTTP_PATH;?>/studentAttendanceReportPrintCSV.php?&startDate2='+form.startDate2.value+'&classId='+document.getElementById('semesterDetail').value+'&sortOrderBy='+listObj3.sortOrderBy+'&sortField='+listObj3.sortField+'&consolidatedView='+attendanceConsolidatedView;
	window.location = path;
}
window.onload = function(){
  getAttendance(document.getElementById('semesterDetail').value);
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/attendanceContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>


<?php 

////$History: listAttendance.php $
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 11/23/09   Time: 3:24p
//Updated in $/LeapCC/Interface/Student
//Show duty in attendance during student login
//
//*****************  Version 8  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 12:27p
//Updated in $/LeapCC/Interface/Student
//added access defines
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 7/10/09    Time: 15:52
//Updated in $/LeapCC/Interface/Student
//Added Detailed(group wise) and Consolidated view(irrespective of groups
//of a subject) of attendance records in student tabs .Now user can
//choose whether to view complete or just consolidated attendance of a
//student.This is also done in print & export to excel version of these
//reports as applicable.
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/28/09    Time: 10:37a
//Updated in $/LeapCC/Interface/Student
//fixed bugs 
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/31/09    Time: 1:25p
//Updated in $/LeapCC/Interface/Student
//fixed the bugs during self testing
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/13/09    Time: 6:28p
//Updated in $/LeapCC/Interface/Student
//modified for left alignment and giving cell padding, cell spacing 1
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/25/08   Time: 4:37p
//Updated in $/LeapCC/Interface/Student
//modified in query for attendance threshold
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:15p
//Updated in $/LeapCC/Interface/Student
//modification code for cc student attendance
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Student
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:10p
//Updated in $/Leap/Source/Interface/Student
//remove the spaces
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 9/15/08    Time: 6:36p
//Updated in $/Leap/Source/Interface/Student
//modification for student attendance
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 9/13/08    Time: 12:07p
//Updated in $/Leap/Source/Interface/Student
//modify for date
//
//*****************  Version 6  *****************
//User: Administrator Date: 9/05/08    Time: 7:28p
//Updated in $/Leap/Source/Interface/Student
//bugs fixation
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/29/08    Time: 11:01a
//Updated in $/Leap/Source/Interface/Student
//modification in template
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/18/08    Time: 5:33p
//Updated in $/Leap/Source/Interface/Student
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/14/08    Time: 3:50p
//Updated in $/Leap/Source/Interface/Student
//modified for print
//
//*****************  Version 2  *****************
//User: Administrator Date: 7/28/08    Time: 7:03p
//Updated in $/Leap/Source/Interface/Student
//modified for attendance 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/25/08    Time: 1:04p
//Created in $/Leap/Source/Interface/Student
//contain the student attendance data base function & template files
//

?>
