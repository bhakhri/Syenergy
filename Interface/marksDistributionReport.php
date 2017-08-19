<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in StudentLabels Form
//
//
// Author :Ajinder Singh
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','MarksDistribution');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Marks Distribution Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="5%" align=left','align=left',false), 
                               new Array('conductingAuthority2','Conducting Authority','width=18% align=left','align=left',true), 
                               new Array('subjectCode','Subject','width="15%" align=left','align=left',true), 
                               new Array('testTypeName','Test Type','width="18%" align="left"','align="left"',false), 
                               new Array('weightageAmount','Weightage Amt.','width="18%" align="right"','align="right"',false), 
                               new Array('weightagePercentage','Weightage %','width="18%" align="right"','align="right"',false)); 

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initMarksDistributionReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'marksDistributionForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'conductingAuthority2';
sortOrderBy    = 'ASC';


function validateAddForm(frm) {
    var fieldsArray = new Array(new Array("degree","<?php echo SELECT_DEGREE;?>"),new Array("subjectTypeId","<?php echo SELECT_SUBJECT_TYPE;?>"), new Array("subjectId","<?php echo SELECT_SUBJECT;?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
	//openStudentLists(frm.name,'rollNo','Asc');    
	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
}

function resetStudyPeriod() {
	document.marksDistributionForm.subjectTypeId.selectedIndex = 0;
}

function printReport() {
	form = document.marksDistributionForm;
	path='<?php echo UI_HTTP_PATH;?>/marksDistributionReportPrint.php?degree='+form.degree.value+'&subjectTypeId='+form.subjectTypeId.value+'&subjectId='+form.subjectId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.open(path,"MarksDistributionReport","status=1,menubar=1,scrollbars=1, width=900");
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

function cleanSubjects() {
	document.marksDistributionForm.subjectId.length = null;
	addOption(document.marksDistributionForm.subjectId, '', 'Select');
	return false;
}

function cleanSubjectType() {
	document.marksDistributionForm.subjectTypeId.value = '';
}

function getSubjects() {
	form = document.marksDistributionForm;
	var degree = form.degree.value;
	var subjectTypeId = form.subjectTypeId.value;
	if (degree == '' || subjectTypeId == '') {
		document.marksDistributionForm.subjectId.length = null;
		addOption(document.marksDistributionForm.subjectId, '', 'Select');
		return false;
	}
	

	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/marksDistributionGetSubjects.php';
	var pars = 'degree='+degree+'&subjectTypeId='+subjectTypeId;
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
               hideWaitDialog(true);
				var j = eval('(' + transport.responseText + ')');
				len = j.length;
				document.marksDistributionForm.subjectId.length = null;
				addOption(document.marksDistributionForm.subjectId, '', 'Select');
				if (len > 0) {
					addOption(document.marksDistributionForm.subjectId, 'all', 'All');
				}
				for(i=0;i<len;i++) { 
                  if(j[i].hasMarks==1 || j[i].hasAttendance==1 ) {            
					addOption(document.marksDistributionForm.subjectId, j[i].subjectId, j[i].subjectName);
                  }
				}
				// now select the value
				document.marksDistributionForm.subjectId.value = j[0].subjectId;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function getTimeTableClasses() {
	form = document.marksDistributionForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetClasses.php';
	var pars = 'labelId='+form.labelId.value;

	if (form.labelId.value=='') {
		form.degree.length = null;
		addOption(form.degree, '', 'Select');
		return false;
	}
	
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.degree.length = null;
			for(i=0;i<len;i++) {
				addOption(form.degree, j[i].classId, j[i].className);
			}
			// now select the value
			form.degree.value = j[0].classId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

window.onload=function(){
	document.getElementById('labelId').focus();
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listMarksDistributionReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

////$History: marksDistributionReport.php $
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 11/25/09   Time: 10:40a
//Updated in $/LeapCC/Interface
//fixed bug: 2109, 2111, 2119
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 11/23/09   Time: 6:03p
//Updated in $/LeapCC/Interface
//fixed bug no.2108
//
//*****************  Version 5  *****************
//User: Rahul.nagpal Date: 11/19/09   Time: 12:50p
//Updated in $/LeapCC/Interface
//issue #2051 resolved.
//
//*****************  Version 4  *****************
//User: Parveen      Date: 10/03/09   Time: 4:09p
//Updated in $/LeapCC/Interface
//It checks the value of hasAttendance, hasMarks field for every subject
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/26/09    Time: 10:22a
//Updated in $/LeapCC/Interface
//fixed bug nos.0001235, 0001233, 0001230, 0001234 and put time table in
//reports
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
//*****************  Version 7  *****************
//User: Ajinder      Date: 11/18/08   Time: 4:50p
//Updated in $/Leap/Source/Interface
//added access level defines
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 11/03/08   Time: 4:37p
//Updated in $/Leap/Source/Interface
//changed default sorting field
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 11/03/08   Time: 11:50a
//Updated in $/Leap/Source/Interface
//Added "MANAGEMENT_ACCESS" variable as these files are used in admin as
//well as in management role
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/26/08    Time: 2:29p
//Updated in $/Leap/Source/Interface
//done the common messaging
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/25/08    Time: 6:32p
//Updated in $/Leap/Source/Interface
//code applied for reducing unnecessary server trip
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/18/08    Time: 7:09p
//Updated in $/Leap/Source/Interface
//improved page design, applied new buttons
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/12/08    Time: 3:59p
//Created in $/Leap/Source/Interface
//file added for making marks distribution report - view part
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 8/09/08    Time: 10:47a
//Updated in $/Leap/Source/Interface
//updated the code of ajax request, and changed messages
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/08/08    Time: 7:05p
//Updated in $/Leap/Source/Interface
//done minor changes
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/07/08    Time: 6:23p
//Updated in $/Leap/Source/Interface
//removed field "lectures missed" as told
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/06/08    Time: 2:10p
//Updated in $/Leap/Source/Interface
//removed unused code
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/06/08    Time: 2:04p
//Updated in $/Leap/Source/Interface
//file changed for making it as per new format
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/02/08    Time: 5:19p
//Updated in $/Leap/Source/Interface
//done cosmetic changes
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 7/29/08    Time: 11:08a
//Updated in $/Leap/Source/Interface
//made the changes as per new query
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 7/25/08    Time: 2:38p
//Updated in $/Leap/Source/Interface
//file updated and made working with new attendance table
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 7/18/08    Time: 4:33p
//Updated in $/Leap/Source/Interface
//done the coding for report printing part
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 7/17/08    Time: 7:26p
//Updated in $/Leap/Source/Interface
//done the coding for studentAttendanceReport completion
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/17/08    Time: 10:30a
//Created in $/Leap/Source/Interface
//File made for : StudentAttendanceReport
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/15/08    Time: 12:39p
//Created in $/Leap/Source/Interface
//Added one new file for StudentLabels Report Module


?>
