<?php 

//-------------------------------------------------------
//  This File contains the template file and data base file
//
//
// Author :Parveen Sharma
// Created on : 22-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifParentNotLoggedIn();    
//require_once(BL_PATH . "/Parent/ajaxInitStudentMarks.php");
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

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

function getStudentMarks(value) {
 
url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxInitStudentMarks.php';

	
			 var tbHeadArray =	new Array(new Array('srNo','#','width="3%" valign="top"',false), 
								new Array('subject','Subject','width="14%" valign="top"',true), 
								new Array('groupName','Group Name','width="11%" valign="top"',true), 
								new Array('examType','Test Type','width="12%" valign="top"',true),
								new Array ('testDate','Test Date','width="9%" valign="top"',true), 
								new Array('periodName','Study Period','width="7%" valign="top" align="left"',true), 
								new Array('employeeName','Teacher Name','width="12%" valign="top"',true), 
								new Array('testName','Test Name','width="6%" valign="top"',true), 
								new Array('totalMarks','Max. Marks ','width="5%" valign="top" align="right"',true), 
								new Array('obtained','Marks scored','width="5%" valign="top" align="right"',true), 
								new Array('percentage','%age','width="5%" valign="top" align="right"',false));
			
     //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
		  listObj1 = new initPage(url,recordsPerPage,linksPerPage,1,'','subject','ASC','results','','',true,'listObj1',tbHeadArray,'','','&studyPeriodId='+value);
		 sendRequest(url, listObj1, '')
}

window.onload = function(){
   getStudentMarks(document.getElementById('semesterDetail').value);	
}

function printReport() {
    
	path='<?php echo UI_HTTP_PATH;?>/Parent/displayStudentMarksReport.php?studyPeriodId='+document.getElementById('semesterDetail').value;
    window.open(path,"DisplayAttendanceReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
	document.getElementById('generateCSV').href='<?php echo UI_HTTP_PATH;?>/Parent/studentMarksCSV.php?studyPeriodId='+document.getElementById('semesterDetail').value;	
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Parent/studentMarksContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>


<?php 
//History: $


?>