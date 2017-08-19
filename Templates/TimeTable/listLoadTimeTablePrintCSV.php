<?php
//This file is used as printing CSV version for teacher time table load.
//
// Author :Parveen Sharma
// Created on : 19-01-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timetableManager  = TimeTableManager::getInstance();

    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance(); 

    //--------------------------------------------------------
    //Purpose:To escape any newline or comma present in data
    //--------------------------------------------------------
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
            return '"'.$comments.'"';
         }
         else {
            return $comments;
         }
    }

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

    $reportHead .= "Time Table:,".parseCSVComments($timeTableName)."\n"; 
    if($REQUEST_DATA['branchId']=='') {
       $reportHead .= "Branch:,All";
    }
    else {
       // Findout Branch Name
       $branchArray = $studentReportsManager->getSingleField('branch', 'branchName', "WHERE branchId  = ".add_slashes($REQUEST_DATA['branchId']));
       $branchName = $branchArray[0]['branchName'];
       $reportHead .= "Branch:,".$branchName;

       $conditions .= " AND emp.branchId=".add_slashes($REQUEST_DATA['branchId']);
    }

    if($REQUEST_DATA['teacherId']=='') {
       $reportHead .= ", Teacher:,All";
    }
    else {
       // Findout Employee Name
       $employeeArray = $studentReportsManager->getSingleField('employee', 'employeeName, employeeCode', "WHERE employeeId  = ".add_slashes($REQUEST_DATA['teacherId']));
       $employeeName = $employeeArray[0]['employeeName']+' ('+$employeeArray[0]['employeeCode']+')';
       $reportHead .= ", Teacher:,".$employeeName;

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


    $record = $timetableManager->getTeacherLoadTimeTable($conditions,$orderBy,'',$filter1,$filter3);
    $cnt = count($record);

    $csvData  = '';
    $csvData .= $reportHead;
    $csvData .= "\n";
    $csvData .= "Sr. No., Name, Subjects ";
      $cnt1 = count($lectureGroupTypeArr);
      for($i=0; $i<$cnt1; $i++) {
        $csvData .= ", ".$lectureGroupTypeNameArr[$i];
      }
    $csvData .= ", Total Load \n";

    $cnt = count($record);
    for($i=0;$i<$cnt;$i++) {
        $csvData .= ($i+1).','.parseCSVComments($record[$i]['Name']).','.parseCSVComments($record[$i]['Subjects']);
        $cnt1 = count($lectureGroupTypeArr);
        for($j=0; $j<$cnt1; $j++) {
          $id = trim("ss".trim($lectureGroupTypeArr[$j]));
          $csvData .= ','.parseCSVComments($record[$i][$id]);
        }
        $csvData .= ', '.parseCSVComments($record[$i]['TotalLoad']);
        $csvData .= "\n";
    }

ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="teacherloadreport.csv"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;
die;
?>

<?php
// $History: listLoadTimeTablePrintCSV.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 10/26/09   Time: 1:09p
//Updated in $/LeapCC/Templates/TimeTable
//report format updated (dynmically grouptype created)
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/08/09    Time: 6:03p
//Updated in $/LeapCC/Templates/TimeTable
//load time table query & formatting update
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/26/09    Time: 11:38a
//Updated in $/LeapCC/Templates/TimeTable
//workload add
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
