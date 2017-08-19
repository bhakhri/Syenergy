<?php 
//-------------------------------------------------------
//  This File contains list of Exam Statistics Report
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
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifManagementNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Exam Statistics Report</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
 
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%" align="left"','align="left"',false), 
                             new Array('employeeName','Teacher','width=12%','',true),     
                             new Array('className','Class','width=12%','',true), 
                             new Array('subjectCode','Subject','width="10%"','',true),
                             new Array('groupShort','Group','width="10%"','',true), 
                             new Array('testName','Test','width="12%"','',true), 
                             new Array('testDate','Test Date','width="10%" align="center"','align="center"',true), 
                             new Array('maxMarks','Max.<br>Marks','width="6%" align="right"','align="right"',false), 
                             new Array('maxMarksScored','Max.<br>Scored','width="6%" align="right"','align="right"',false), 
                             new Array('minMarksScored','Min.<br>Scored','width="6%" align="right"','align="right"',false), 
                             new Array('avgMarks','Avg.','width="6%" align="right"','align="right"',false), 
                             new Array('presentCount','Present','width="6%" align="right"','align="right"',false), 
                             new Array('absentCount','Absent','width="6%" align="right"','align="right"',false)
                             ); 

var listURL='<?php echo HTTP_LIB_PATH;?>/Management/ajaxGetTeacherTests.php';
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
sortField = 'employeeName';
sortOrderBy    = 'ASC';
queryString ='';

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      

function hideResults() {
    
    
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function validateAddForm() {

   
   queryString = generateQueryString('examForm');
   
   hideResults();
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false); 
   document.getElementById("resultRow").style.display='';
   document.getElementById('nameRow').style.display='';
   document.getElementById('nameRow2').style.display='';
   
   return true;
}

function printReport()
{
    path='<?php echo UI_HTTP_PATH;?>/Management/listExamStatisticsPrint.php?'+queryString;
    window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=800, height=550, top=100,left=50");
}

function printReportCSV() {
    path='<?php echo UI_HTTP_PATH;?>/Management/listExamStatisticsPrintCSV.php?'+queryString; 
    window.location = path;
}

</script>
</head>
<body>
<?php
	require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Management/listExamStatisticsContents.php");
	require_once(TEMPLATES_PATH . "/footer.php");
?>
<SCRIPT LANGUAGE="JavaScript">
    <!--
       document.getElementById("resultRow").style.display='';
       document.getElementById('nameRow').style.display='';
       document.getElementById('nameRow2').style.display='';
       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //-->
</SCRIPT> 
</body>
</html>
<?php 
// $History: listManagementExamStatistics.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/10/10    Time: 4:09p
//Created in $/LeapCC/Interface/Management
//initial checkin
//

?>