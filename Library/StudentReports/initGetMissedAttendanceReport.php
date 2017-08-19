<?php
//--------------------------------------------------------
//This file returns the array of attendance missed records
//
// Author :Ajinder Singh
// Created on : 15-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$reportManager = StudentReportsManager::getInstance();
	 
	
	$classId = $REQUEST_DATA['degree'];
	$subjectId = $REQUEST_DATA['subjectId'];
	$tillDate = $REQUEST_DATA['tillDate'];
//	$tillDate = date('Y-m-d',strtotime($tillDate));
	$sortOrderBy = $REQUEST_DATA['sortOrderBy'];
	$sortField = $REQUEST_DATA['sortField'];
	$labelId = $REQUEST_DATA['labelId'];


	$recordArray = array();

    // $condition: It checks the value of hasAttendance field for every subject
    $condition = " AND c.hasAttendance =1 ";
    
	if ($classId == 'all') {
	   $recordArray = $reportManager->getAllClassMissedAttendanceReport($labelId, $tillDate, $sortField, $sortOrderBy, $condition );
	}
	else {
		if ($subjectId == 'all') {
		   //fetch data for all subjects of selected class
		   $recordArray = $reportManager->getAllSubjectMissedAttendanceReport($classId, $tillDate, $sortField, $sortOrderBy, $condition );
		}
		else {
		   //fetch data for selected subject of selected class
		   $recordArray = $reportManager->getOneSubjectMissedAttendanceReport($classId, $subjectId, $tillDate, $sortField, $sortOrderBy, $condition );
		}
	}



	$cnt = count($recordArray);


    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('srNo' => ($records+$i+1)),
                                        $recordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

// $History: initGetMissedAttendanceReport.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 10/03/09   Time: 4:09p
//Updated in $/LeapCC/Library/StudentReports
//It checks the value of hasAttendance, hasMarks field for every subject
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/31/09    Time: 4:57p
//Updated in $/LeapCC/Library/StudentReports
//added time table label.
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 5/28/09    Time: 5:17p
//Updated in $/LeapCC/Library/StudentReports
//changed 'class' variable to 'degree' as this was causing problems in
//IE6
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/StudentReports
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/08/08    Time: 7:33p
//Updated in $/Leap/Source/Library/StudentReports
//corrected report
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/29/08    Time: 1:05p
//Created in $/Leap/Source/Library/StudentReports
//file added for "attendance not entered report"
?>
