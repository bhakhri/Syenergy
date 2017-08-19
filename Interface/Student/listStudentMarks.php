<?php

//-------------------------------------------------------
//  This File contains the template file and data base file
//
//
// Author :Jaineesh
// Created on : 22-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentDisplayMarks');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();
require_once(BL_PATH . "/Student/initStudentMarks.php");
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

url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitStudentMarks.php';


	 var tbHeadArray =	new Array(new Array('srNo','#','width="3%" ',false),
						new Array('subject','Subject','width="14%"',true),
						new Array('groupName','Group','width="7%"',true),
						new Array('examType','Test Type','width="12%"',true),
						new Array ('testDate','Test Date','width="9%" align="center"' ,true),
						new Array('periodName','Study Period','width="10%"  align="left"',true),
						new Array('employeeName','Teacher Name','width="12%"',true),
						new Array('testName','Test','width="6%" ',true),
						new Array('totalMarks','M.M ','width="5%"  align="right"',true),
						new Array('obtained','M.S','width="5%" align="right"',true),
						new Array('percentage','%age','width="5%" align="right"',false));

     //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
		  listObj1 = new initPage(url,recordsPerPage,linksPerPage,1,'','testDate','ASC','results','','',true,'listObj1',tbHeadArray,'','','&studyPeriodId='+value);
		 sendRequest(url, listObj1, '')
}

window.onload = function(){
	var classId = "<?php echo $REQUEST_DATA['classId']; ?>";

	if (classId != '') {
		document.getElementById('semesterDetail').value = classId;
	}
		getStudentMarks(document.getElementById('semesterDetail').value);

}

function printReport() {
	form = document.studentMarks;
	sortField = listObj1.sortField;
	sortOrderBy = listObj1.sortOrderBy;
	var qstr = "&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/Student/displayStudentMarksReport.php?studyPeriodId='+document.getElementById('semesterDetail').value+qstr;
    window.open(path,"DisplayAttendanceReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
	form = document.studentMarks;
	sortField = listObj1.sortField;
	sortOrderBy = listObj1.sortOrderBy;
	var qstr = "&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/Student/studentMarksCSV.php?studyPeriodId='+document.getElementById('semesterDetail').value+qstr;
	window.location = path;
	//document.getElementById('generateCSV1').href='<?php echo UI_HTTP_PATH;?>/Student/studentMarksCSV.php?studyPeriodId='+document.getElementById('semesterDetail').value;
}

</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/studentMarksContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>


<?php
//History: $


?>