<?php
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	UtilityManager::ifTeacherNotLoggedIn(true);
	UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
	if (!isset($REQUEST_DATA['mode'])) {
		echo INVALID_DETAILS_FOUND;
		die;
	}
	if (!isset($REQUEST_DATA['val'])) {
		echo INVALID_DETAILS_FOUND;
		die;
	}
	$mode = add_slashes(trim($REQUEST_DATA['mode']));
	$val = add_slashes(trim($REQUEST_DATA['val']));
	if ($mode != 'attendanceThreshold' and $mode != 'toppers' and $mode != 'belowAvg' and $mode != 'aboveAvg') {
		echo INVALID_DETAILS_FOUND;
		die;
	}

    $teacherManager = TeacherManager::getInstance();
    $teacherId = $sessionHandler->getSessionVariable('EmployeeId');

	$activeTimeTableLabelArray = $teacherManager->getActiveTimeTable();
	$activeTimeTableLabelId = $activeTimeTableLabelArray[0]['timeTableLabelId'];

	$teacherSubjectsArray = $teacherManager->getTeacherSubjects($activeTimeTableLabelId);
	$concatStrSubArray = array();
	foreach($teacherSubjectsArray as $teacherSubjectRecord) {
		$subjectId = $teacherSubjectRecord['subjectId'];
		$subjectCode = $teacherSubjectRecord['subjectCode'];
		$classId = $teacherSubjectRecord['classId'];
		$className = $teacherSubjectRecord['className'];
		$concatStrSubArray[] = "$subjectId#$classId";
	}

	if (!count($concatStrSubArray)) {
		echo INVALID_DETAILS_FOUND;
		die;
	}

	if (!in_array($val, $concatStrSubArray)) {
		echo INVALID_DETAILS_FOUND;
		die;
	}

	$concatStrSub = "'$val'";

	if ($mode == 'attendanceThreshold') {
		$recordsArray = $teacherManager->getAttendanceThresholdRecords($activeTimeTableLabelId, $concatStrSub);
	}
	else if ($mode == 'toppers') {
		$recordsArray = $teacherManager->getTopperRecords($concatStrSub);
	}
	else if ($mode == 'belowAvg') {
		$recordsArray = $teacherManager->getBelowAvgRecords($concatStrSub);
	}
	else if ($mode == 'aboveAvg') {
		$recordsArray = $teacherManager->getAboveAvgRecords($concatStrSub);
	}

	$cnt = count($recordsArray);

	for($i=0;$i<$cnt;$i++) {

        if($recordsArray[$i]['studentPhoto'] != ''){ 
            $File = STORAGE_PATH."/Images/Student/".$recordsArray[$i]['studentPhoto'];
            if(file_exists($File)){
               $imgSrc= IMG_HTTP_PATH.'/Student/'.$recordsArray[$i]['studentPhoto'];
            }
            else{
               $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
            }
        }
        else{
          $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
        }
        
        $imgSrc = "<img src='".$imgSrc."' width='20' height='20' id='studentImageId' class='imgLinkRemove' />";
        $recordsArray[$i]['imgSrc'] =  $imgSrc;



        $valueArray = array_merge(array('srNo' => ($i+1),"students" => "<input type=\"checkbox\" name=\"students\" id=\"students\" value=\"".$recordsArray[$i]['studentId'] ."\">"),$recordsArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$cnt.'","page":"1","info" : ['.$json_val.']}'; 

// $History: showDashBoardList.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 1/08/10    Time: 3:07p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//file added for teacher dashboard enhancements
//



?>