<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Training ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (04.03.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TaskMaster');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();
//require_once(BL_PATH . "/Discipline/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Task Manager </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 250; // add/edit form height

// ajax search results ---end ///

function getTask(){
  url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitTaskList.php';
  var value=document.searchBox1.searchbox.value;
    
 var tableColumns = new Array(
                        new Array('srNo','#','width="3%" align="left"',false), 
                        new Array('title','Title','width="10%" align="left"',true),
						new Array('shortDesc','Short Description','width="15%" align="left"',true),
						new Array('dueDate','Date','width="10%" align="left"',true),
						new Array('daysPrior','Priority Days','width="10%" align="left"',true),
						new Array('Result','Prior Date','width="10%" align="left"',true),
						new Array('status','Status','width="10%" align="left"',true)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','title','ASC','TaskResultDiv','TaskActionDiv','',true,'listObj',tableColumns,'editWindow','deleteTask','&searchbox='+trim(value));
 sendRequest(url, listObj, '')
}
// ajax search results ---end ///

window.onload=function(){
        //loads the data
        getTask();    
}

</script>

</head>
<body>
	<?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/Student/listStudentTaskContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
	
</body>
</html>
<?php 
// $History: listStudentTask.php $ 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/20/09    Time: 6:42p
//Created in $/LeapCC/Interface/Student
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/24/09    Time: 4:33p
//Updated in $/SnS/Interface/Student
//modified in task for parent & student
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/20/09    Time: 6:11p
//Created in $/SnS/Interface/Student
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 3/19/09    Time: 4:41p
//Updated in $/SnS/Interface
//add new room if hostel room is different
//new task module in student
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/19/09    Time: 2:53p
//Updated in $/SnS/Interface
//check prior days should be in integer
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/18/09    Time: 6:23p
//Created in $/SnS/Interface
//new file for task
//
?>