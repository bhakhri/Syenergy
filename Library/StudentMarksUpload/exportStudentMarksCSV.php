 <?php 
//This file is used as CSV version of student external marks.
//
// Author :Jaineesh
// Created on : 06.02.10
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
	set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();
    
    define('MODULE','COMMON');
    define('ACCESS','view');
    global $sessionHandler;   
    $roleId = $sessionHandler->getSessionVariable('RoleId');
    if($roleId=='2') {      // Teacher Login
      UtilityManager::ifTeacherNotLoggedIn(true); 
    }
    else {
      UtilityManager::ifNotLoggedIn(true); 
    }
    UtilityManager::headerNoCache();
   
	//to parse csv values    
	function parseCSVComments($comments) {
	   //$comments = str_replace('"', '""', $comments);
	   //$comments = str_ireplace('<br/>', "\n", $comments);
	   if(eregi(",", $comments) or eregi("\n", $comments)) {
	     return '"'.$comments.'"'; 
	   }
	   else {
	     return $comments; 
	   }
	}

    $classId = trim($REQUEST_DATA['degree']);
	$studentListRollNo = trim($REQUEST_DATA['studentListRollNo']);
	
	if ($classId == '') {
		echo ('<script type="text/javascript">alert("Please select class");</script>');
		echo ('<script type="text/javascript">document."'.$classId.'"focus();;</script>');
		die;
	}

    if($classId=='') {
      $classId='0';  
    }

	$conditions = " AND sg.classId='".$classId."'";
	//$studentRecordArray = $studentManager->getStudentListExternal($conditions);
    $studentRecordArray = $studentManager->getStudentListExternalNew($conditions);
	$recordCount = count($studentRecordArray);

	$valueArray = array();

	$csvData ='';
	$csvData="Sr.No.,University Roll No.,Student Name, [Max. Marks]";
	$csvData .="\n";

	for($i=0;$i<$recordCount;$i++) {
	   $csvData .= ($i+1).",";
	   //$csvData .= trim($studentRecordArray[$i]['rollNo']).',';
	   $csvData .= parseCSVComments(trim($studentRecordArray[$i]['universityRollNo'])).",";
	   $csvData .= parseCSVComments(trim($studentRecordArray[$i]['studentName'])).",";
	   $csvData .= "\n ";
	}
    
    if($i==0) {
      $csvData .= ",,No Data Found ";  
    }
 
    UtilityManager::makeCSV($csvData,'StudentMarksReport.csv');
die;
//$History : $
?>