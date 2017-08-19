<?php
//-------------------------------------------------
//This file returns the load of teacher
//
// Author :PArveen Sharma
// Created on : 19-01-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','DisplayLoadTeacherTimeTable');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timetableManager  = TimeTableManager::getInstance();
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance(); 

    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    $timeArray = $studentManager->getSingleField('time_table_labels', 'timeTableType', "WHERE timeTableLabelId='".$REQUEST_DATA['labelId']."'");
    
    $timeTableType=1;
    $timeDistinct = "";
    if(is_array($timeArray) && count($timeArray)>0 ) { 
      $timeTableType=$timeArray[0]['timeArray'];  
      if($timeTableType=='2') {
        $timeDistinct = " DISTINCT ";
      }
    }
    
    
    /// Search filter /////
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'Name';

    $orderBy = " , gr.groupTypeId
				) AS tt
				GROUP BY
				 tt.employeeId
                   ORDER BY $sortField $sortOrderBy ";

    $conditions = '';

    $conditions = ($REQUEST_DATA['labelId']!='' ? " AND tt.timeTableLabelId=".$REQUEST_DATA['labelId'] : "");
    $conditions .= ($REQUEST_DATA['branchId']!='' ? " AND emp.branchId=".$REQUEST_DATA['branchId'] : "");
    $conditions .= ($REQUEST_DATA['teacherId']!='' ? " AND tt.employeeId=".$REQUEST_DATA['teacherId'] : "");

    $lectureGroupType = add_slashes($REQUEST_DATA['groupType']);
    $lectureGroupTypeArr = explode(',',$lectureGroupType);

    $filter1 = '';
    $filter2 = '';
	$filter3 = '';
    
    $filter3="SELECT
                    tt.employeeId, tt.Name, tt.timeTableType ";

    $cntRecord = count($lectureGroupTypeArr);
    if($cntRecord > 0 )  {
        for($i=0; $i<$cntRecord; $i++) {
            $id=($i+1);
            $filter1 .= ", IF(gr.groupTypeId=".trim($lectureGroupTypeArr[$i]).",
                            COUNT($timeDistinct CONCAT(tt.subjectId,tt.daysOfWeek,tt.periodId)),0) AS ss".trim($lectureGroupTypeArr[$i]);
                            
            $filter3 .=", SUM(tt.ss$id) AS ss$id";                
        }
        $filter2 .= ", COUNT($timeDistinct CONCAT(tt.subjectId,tt.daysOfWeek,tt.periodId)) ";
        $filter1 .= $filter2." AS totalLoad ";
    }
	$filter3 .= ", SUM(tt.totalLoad) AS TotalLoad,
				   GROUP_CONCAT(tt.Subjects) AS Subjects
			       FROM ( ";
                   
    $totalArray = $timetableManager->getTeacherCountLoadTimeTable($conditions);
    $teacherLoadRecordArray = $timetableManager->getTeacherLoadTimeTable($conditions,$orderBy,$limit,$filter1,$filter3);

    $cnt = count($teacherLoadRecordArray);
     for($i=0;$i<$cnt;$i++) {
        $employeeName = $teacherLoadRecordArray[$i]['Name'];
        $employeeId = $teacherLoadRecordArray[$i]['employeeId'];
        $timeTableType = $teacherLoadRecordArray[$i]['timeTableType'];

        $actionStr ='<img name="teacherListSubmit" value="teacherListSubmit" src="'.IMG_HTTP_PATH.'/zoom.gif" onClick="return showDetails('.$employeeId.',\''.$employeeName.'\','.$timeTableType.',\'Load\'); return false;" /></img>';
        $valueArray = array_merge(array('srNo' => ($records+$i+1),'Details' => $actionStr),$teacherLoadRecordArray[$i]);
        if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
        }
        else{
            $json_val .= ','.json_encode($valueArray);
        }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['cnt'].'","page":"'.$page.'","info" : ['.$json_val.']}';


// $History: initTeacherLoadTimeTableReport.php $/Leap/Source/Library/ScStudentReports
//
//*****************  Version 5  *****************
//User: Parveen      Date: 10/26/09   Time: 1:09p
//Updated in $/LeapCC/Library/TimeTable
//report format updated (dynmically grouptype created)
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/03/09    Time: 11:47a
//Updated in $/LeapCC/Library/TimeTable
//Gurkeerat: resolved issue 1412
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/19/09    Time: 10:30a
//Updated in $/LeapCC/Library/TimeTable
//details link add show the teacher time table
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/25/09    Time: 11:48a
//Updated in $/LeapCC/Library/TimeTable
//branchId update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/19/09    Time: 6:29p
//Created in $/LeapCC/Library/TimeTable
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/19/09    Time: 3:05p
//Created in $/Leap/Source/Library/ScTimeTable
//teacher time table load file added
//added code to fix issue in report.
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/03/08   Time: 1:45p
//Updated in $/Leap/Source/Library/ScStudentReports
//define('MODULE','MarksNotEntered');
//define('ACCESS','view');
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 10/30/08   Time: 10:47a
//Updated in $/Leap/Source/Library/ScStudentReports
//added code to fix 'duplicate values' bug found during self testing.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/24/08   Time: 12:28p
//Updated in $/Leap/Source/Library/ScStudentReports
//added code for paging.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/19/08    Time: 7:54p
//Created in $/Leap/Source/Library/ScStudentReports
//file added for "marks not entered report"
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 9/10/08    Time: 2:52p
//Updated in $/Leap/Source/Library/StudentReports
//fixed "no records found" bug, and fixed IE related issue
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 9/09/08    Time: 1:45p
//Updated in $/Leap/Source/Library/StudentReports
//changed the function call from
//fetching classes from time table
//to
//fetching classes from test
//for "marks not entered report"
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/05/08    Time: 1:20p
//Updated in $/Leap/Source/Library/StudentReports
//removed unwanted code
//
?>
