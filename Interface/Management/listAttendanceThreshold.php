<?php
//-------------------------------------------------------
//  This File contains list of Attendance Threshold Report (toppers/below/above/average)
//
//
// Author :Parveen Sharma
// Created on : 15-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH.'/HtmlFunctions.inc.php');
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifManagementNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Attendance Threshold Report</title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
                               new Array('universityRollNo','URoll No.','width="7%"','',true),
                               new Array('rollNo','Roll No.','width="7%"','',true),
                               new Array('studentName','Student Name','width="10%"','',true),
                               new Array('className','Class','width=12%','',true),
                               new Array('subjectCode','Subject','width="8%"','',true),
                               new Array('groupShort','Group','width="6%"','',true),
                               new Array('employeeName','Teacher','width=12%','',true),
                               new Array('percentage','Percent','width="6%" align="right"','align="right"',true),
                               new Array('imgSrc','Photo','width="5%" align="center"','align="center"',false));

var listURL='<?php echo HTTP_LIB_PATH;?>/Management/showDashBoardList.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'examForm'; // name of the form which will be used for search
addFormName    = 'AddState';
editFormName   = 'EditState';
winLayerWidth  = 300; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'percentage';
sortOrderBy    = 'ASC';
queryString ='';

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

function hideResults() {


    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function validateAddForm() {

   queryString = '';
   page=1;
   document.getElementById("filterCond").value="Y";

   if(document.getElementById("mode").value=='t') {
      sortOrderBy = 'DESC';
      sortField = 'percentage';
   }

   if(document.getElementById("mode").value == '') {
     document.getElementById("mode").value = 'a';
   }

   str = document.getElementById("mode").value;

   if (str == 'a') {
        document.getElementById('classSubjectDiv').innerHTML = '<b>List of Students having attendance below Attendance Threshold ('+<?php echo $sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');?>+' %) </b>' ;
   }
   else if (str == 't'){
      <?php
         $toppers = $sessionHandler->getSessionVariable('TOPPERS_RECORD_LIMIT');
         if($toppers=='') {
           $toppers = 5;
         }
      ?>
      document.getElementById('classSubjectDiv').innerHTML = '<b>List of Top '+<?php echo $toppers; ?>+' students</b>';
      recordsPerPage = <?php echo $toppers; ?>;
   }
   else if (str == 'b'){
        document.getElementById('classSubjectDiv').innerHTML = '<b>List of Students having marks below average (below '+<?php echo $sessionHandler->getSessionVariable('BELOW_AVERAGE_PERCENTAGE');?>+'%)</b>';
   }
   else if (str == 'av'){
        document.getElementById('classSubjectDiv').innerHTML = '<b>List of Students having marks above average (more than '+ <?php echo $sessionHandler->getSessionVariable('ABOVE_AVERAGE_PERCENTAGE');?>+ '%)</b>';
   }
   queryString = generateQueryString('examForm');
   hideResults();
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false);
   document.getElementById("resultRow").style.display='';
   document.getElementById('nameRow').style.display='';
   document.getElementById('nameRow2').style.display='';

   return false;
}

function printReport()
{
    path='<?php echo UI_HTTP_PATH;?>/Management/listAttendanceThresholdPrint.php?'+queryString;
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=800, height=550, top=100,left=50");
}

function printReportCSV() {
    path='<?php echo UI_HTTP_PATH;?>/Management/listAttendanceThresholdCSV.php?'+queryString;
    window.location = path;
}

</script>
</head>
<body>
<?php
	require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Management/listAttendanceThresholdContents.php");
	require_once(TEMPLATES_PATH . "/footer.php");
?>
<SCRIPT LANGUAGE="JavaScript">
   hideResults();

   page=1;

   if(document.getElementById("mode").value == '') {
     document.getElementById("mode").value = 'a';
   }

   str = document.getElementById("mode").value;

   if(str == 'a') {
      document.getElementById('classSubjectDiv').innerHTML = '<b>List of Students having attendance below Attendance Threshold ('+<?php echo $sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');?>+' %) </b>' ;
   }
   else if(str == 't'){
      document.getElementById('classSubjectDiv').innerHTML = '<b>List of Top '+<?php echo $sessionHandler->getSessionVariable('TOPPERS_RECORD_LIMIT'); ?>+' students</b>';
      sortOrderBy = 'DESC';
      sortField = 'percentage';
      <?php
         $toppers = $sessionHandler->getSessionVariable('TOPPERS_RECORD_LIMIT');
         if($toppers=='') {
           $toppers = 5;
         }
      ?>
      document.getElementById('classSubjectDiv').innerHTML = '<b>List of Top '+<?php echo $toppers; ?>+' students</b>';
      recordsPerPage = <?php echo $toppers; ?>;
   }
   else if(str == 'b'){
      document.getElementById('classSubjectDiv').innerHTML = '<b>List of Students having marks below average (below '+<?php echo $sessionHandler->getSessionVariable('BELOW_AVERAGE_PERCENTAGE');?>+'%)</b>';
   }
   else if(str == 'av'){
      document.getElementById('classSubjectDiv').innerHTML = '<b>List of Students having marks above average (more than '+ <?php echo $sessionHandler->getSessionVariable('ABOVE_AVERAGE_PERCENTAGE');?>+ '%)</b>';
   }
   queryString = generateQueryString('examForm');
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false);
   document.getElementById("resultRow").style.display='';
   document.getElementById('nameRow').style.display='';
   document.getElementById('nameRow2').style.display='';

</SCRIPT>
</body>
</html>
<?php
// $History: listAttendanceThreshold.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/10/10    Time: 4:09p
//Created in $/LeapCC/Interface/Management
//initial checkin
//

?>