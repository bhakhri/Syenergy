<?php 

//-------------------------------------------------------
//  This File contains the template file and data base file
//
//
// Author :Parveen Sharma
// Created on : 22-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifParentNotLoggedIn();   
require_once(BL_PATH . "/ScParent/scStudentMarks.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Marks </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="5%"',''), 
                               new Array('subject','Subject','width="20%"','',true), 
                               new Array('sectionName','Section','width="8%"','',true),
                               new Array('periodName', 'Study Period', 'width="13%"','',true),
                               new Array('employeeName','Teacher Name','width="20%"','',true), 
                               new Array('fromDate','From','width="10%"','align="center"'), 
                               new Array('toDate','To','width="8%"','align="center"'),
                               new Array('delivered','Delivered','width="8%"','align="right"'), 
                               new Array('attended','Attended','width="10%"','align="right"'), 
                               new Array('Percentage','Percentage','width="10%"','align="right"'));

recordsPerPage = '<?php echo RECORDS_PER_PAGE;?>';
linksPerPage = '<?php echo LINKS_PER_PAGE;?>';
listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxDisplayMarks.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = '';   
editFormName   = '';
winLayerWidth  = 400; //  add/edit form width
winLayerHeight = 400; // add/edit form height
deleteFunction = '';
divResultName  = 'results';
page=1; //default page
sortField = 'subject';
sortOrderBy = 'ASC';

function getStudentMarks(value) {
 
    url = '<?php echo HTTP_LIB_PATH;?>/ScParent/scAjaxInitStudentMarks.php';
	
    var tbHeadArray =	new Array(new Array('srNo','#','width="2%" valign="top"',false), 
						new Array('subject','Subject','width="12%" valign="top"',true), 
						new Array('examType','Test Type','width="10%" valign="top"',true),
						new Array ('testDate','Test Date','width="9%" valign="top"',true), 
						new Array('periodName','Study Period','width="12%" valign="top"',true), 
						new Array('employeeName','Teacher Name','width="13%" valign="top"',true), 
						new Array('testName','Test Name','width="10%" valign="top"',true), 
						new Array('totalMarks','Max. Marks','width="10%" valign="top" align="right"',true), 
						new Array('obtained','Marks scored','width="12%" valign="top" align="right"',true), 
						new Array('percentage','Percentage','width="10%" valign="top" align="right"',false));
                        
    listObj1 = new initPage(url,recordsPerPage,linksPerPage,1,'','subject','ASC','results','','',true,'listObj1',tbHeadArray,'','','&studyPeriodId='+value);
	sendRequest(url, listObj1, '')
}

window.onload = function(){
	getStudentMarks(document.getElementById('semesterDetail').value);	
}

function printReport() {
	path='<?php echo UI_HTTP_PATH;?>/Parent/scStudentMarksReport.php?studyPeriodId='+document.getElementById('semesterDetail').value;
    window.open(path,"DisplayAttendanceReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
	
	path='<?php echo UI_HTTP_PATH;?>/Parent/scStudentMarksCSV.php?studyPeriodId='+document.getElementById('semesterDetail').value;    
    window.location = path;
	//document.getElementById('generateCSV').href='<?php echo UI_HTTP_PATH;?>/Student/scStudentMarksCSV.php?studyPeriodId='+document.getElementById('semesterDetail').value;    
	//document.getElementById('generateCSV1').href='<?php echo UI_HTTP_PATH;?>/Student/scStudentMarksCSV.php?studyPeriodId='+document.getElementById('semesterDetail').value;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/ScParent/scStudentMarksContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>


<?php 
//$History: scMarks.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/18/09    Time: 4:44p
//Created in $/LeapCC/Interface/Parent
//file added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/26/09    Time: 6:47p
//Updated in $/Leap/Source/Interface/Parent
//format, condition, validation updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/26/09    Time: 6:16p
//Updated in $/Leap/Source/Interface/Parent
//code, formatting updated
//

?>