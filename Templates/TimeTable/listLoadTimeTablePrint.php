<?php
//This file is used as printing version for teacher time table load.
//
// Author :Parveen Sharma
// Created on : 19-01-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance(); 

    define('MODULE','DisplayLoadTeacherTimeTable');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timetableManager  = TimeTableManager::getInstance();


     $timeArray = $studentManager->getSingleField('time_table_labels', 'labelName, timeTableType', "WHERE timeTableLabelId='".$REQUEST_DATA['labelId']."'");
    
    $timeTableType=1;
    $timeDistinct = "";
    if(is_array($timeArray) && count($timeArray)>0 ) { 
      $timeTableType=$timeArray[0]['timeArray'];  
      $timeTableName=$timeArray[0]['labelName'];  
      if($timeTableType=='2') {
        $timeDistinct = " DISTINCT ";
      }
    }
    
    
    $conditions = '';
    $reportHead = '';

    $conditions = ($REQUEST_DATA['labelId']!='' ? " AND tt.timeTableLabelId=".$REQUEST_DATA['labelId'] : "");


    $reportHead .= "Time Table:&nbsp;$timeTableName<br>";   
    if($REQUEST_DATA['branchId']=='') {
      $reportHead .= "Branch:&nbsp;All&nbsp;&nbsp;";
    }
    else {
       // Findout Branch Name
       $branchArray = $studentReportsManager->getSingleField('branch', 'branchName', "WHERE branchId  = ".add_slashes($REQUEST_DATA['branchId']));
       $branchName = $branchArray[0]['branchName'];
       $reportHead .= "Branch:&nbsp;".$branchName;

       $conditions .= " AND emp.branchId=".add_slashes($REQUEST_DATA['branchId']);
    }

    if($REQUEST_DATA['teacherId']=='') {
       $reportHead .= ", Teacher:&nbsp;All";
    }
    else {
       // Findout Employee Name
       $employeeArray = $studentReportsManager->getSingleField('employee', 'employeeName, employeeCode', "WHERE employeeId  = ".add_slashes($REQUEST_DATA['teacherId']));
       $employeeName = $employeeArray[0]['employeeName']+' ('+$employeeArray[0]['employeeCode']+')';
       $reportHead .= ", Teacher:&nbsp;".$employeeName;

       $conditions .= " AND tt.employeeId=".add_slashes($REQUEST_DATA['teacherId']);
    }

    $lectureGroupType = add_slashes($REQUEST_DATA['lectureGroupType']);
    $lectureGroupTypeArr = explode(',',$lectureGroupType);

    $lectureGroupTypeName = add_slashes($REQUEST_DATA['lectureGroupTypeName']);
    $lectureGroupTypeNameArr = explode(',',$lectureGroupTypeName);

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
                   tt.Subjects
                   FROM ( ";
                   
            
    // Search filter /////
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'Name';

    $orderBy = " , gr.groupTypeId
				) AS tt
				GROUP BY
				 tt.employeeId
                   ORDER BY $sortField $sortOrderBy ";

    $teacherLoadRecordArray = $timetableManager->getTeacherLoadTimeTable($conditions,$orderBy,'',$filter1,$filter3);
    $cnt = count($teacherLoadRecordArray);
    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array_merge(array('srNo' => ($i+1)),$teacherLoadRecordArray[$i]);
    }

    $reportManager->setReportWidth(750);
    $reportManager->setReportHeading('Teacher Load Report');
    $reportManager->setReportInformation($reportHead);

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align
    $reportTableHead['srNo']                =    array('#',           'width="2%"', "align='left' ");
    $reportTableHead['Name']                =    array('Name',        'width=12% align="left"', 'align="left"');
    $reportTableHead['Subjects']            =    array('Subjects',    'width=20% align="left"', 'align="left"');

    for($i=0; $i<$cntRecord; $i++) {
       $id = trim("ss".trim($lectureGroupTypeArr[$i]));
       $reportTableHead[$id]                =    array($lectureGroupTypeNameArr[$i],  'width="5%" align="right" ','align="right" valign="top" ');
    }
    $reportTableHead['TotalLoad']           =    array('Total',  'width="5%" align="right"','align="right"  valign="top" ');

    $reportManager->setRecordsPerPage(30);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();
?>

<?php
// $History: listLoadTimeTablePrint.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 10/26/09   Time: 1:09p
//Updated in $/LeapCC/Templates/TimeTable
//report format updated (dynmically grouptype created)
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/08/09    Time: 6:03p
//Updated in $/LeapCC/Templates/TimeTable
//load time table query & formatting update
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/25/09    Time: 11:56a
//Updated in $/LeapCC/Templates/TimeTable
//branchId update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/25/09    Time: 10:34a
//Updated in $/LeapCC/Templates/TimeTable
//code update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/19/09    Time: 6:29p
//Created in $/LeapCC/Templates/TimeTable
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/19/09    Time: 4:01p
//Created in $/Leap/Source/Templates/ScTimeTable
//inital checkin
//


?>

