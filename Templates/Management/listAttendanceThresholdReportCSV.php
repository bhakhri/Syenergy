<?php
//This file is used as printing version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifManagementNotLoggedIn(true);
    UtilityManager::headerNoCache();

    global $sessionHandler;

    require_once(MODEL_PATH . "/Management/DashboardManager.inc.php");
    $managementManager = DashBoardManager::getInstance();


    //to parse csv values
    function parseCSVComments($comments) {
       $comments = str_replace('"', '""', $comments);
       $comments = str_ireplace('<br/>', "\n", $comments);
       if(eregi(",", $comments) or eregi("\n", $comments)) {
          return '"'.$comments.'"';
       }
       else {
          return $comments.chr(160);
       }
    }

    $degreeId =  add_slashes($REQUEST_DATA['degreeId']);
    $teacherId =  add_slashes($REQUEST_DATA['teacherId']);
    $subjectId =  add_slashes($REQUEST_DATA['subjectId']);
    $groupId =  add_slashes($REQUEST_DATA['groupId']);
    $rollNo =  add_slashes(trim($REQUEST_DATA['sRollNo']));
    $studentName =  add_slashes(trim($REQUEST_DATA['sStudentName']));

    $filterCond =  add_slashes(trim($REQUEST_DATA['filterCond']));

    $mode = add_slashes(trim($REQUEST_DATA['mode']));
    $classId = add_slashes(trim($REQUEST_DATA['val']));

    if($filterCond=='') {
      $filterCond = 'Y';
    }

    if($mode=='') {
      $mode='a';
    }

    $condition = '';
    $condition1 = '';
    if($filterCond=='N') {
      if($classId != '0') {
        $condition = " AND d.classId IN ($classId) ";
      }
    }
    else if($filterCond=='Y') {
          if ($mode == 't') {
                if($degreeId != '') {
                   $condition .= " AND c.classId IN ($degreeId)" ;
                   $condition1 .= " AND att.classId IN ($degreeId)" ;
                }

                if($teacherId != '') {
                   $condition .= " AND a.employeeId  IN ($teacherId)" ;
                   $condition1 .= " AND att.employeeId  IN ($teacherId)" ;
                }

                if($subjectId != '') {
                   $condition .= " AND a.subjectId IN ($subjectId)" ;
                   $condition1 .= " AND att.subjectId IN ($subjectId)" ;
                }

                if($groupId != '') {
                   $condition .= " AND a.groupId IN ($groupId)" ;
                   $condition1 .= " AND f.groupId IN ($groupId)" ;
                }

                if($rollNo != '') {
                   $condition1 .= " AND s.rollNo LIKE '$rollNo%' " ;
                }

                if($studentName != '') {
                   $condition1 .= " AND CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) LIKE '$studentName%' " ;
                }
          }
          else {
              if($degreeId != '') {
                   $condition .= " AND c.classId IN ($degreeId)" ;
                   $condition1 .= " AND att.classId IN ($degreeId)" ;
                }

                if($teacherId != '') {
                   $condition .= " AND a.employeeId  IN ($teacherId)" ;
                   $condition1 .= " AND att.employeeId  IN ($teacherId)" ;
                }

                if($subjectId != '') {
                   $condition .= " AND a.subjectId IN ($subjectId)" ;
                   $condition1 .= " AND att.subjectId IN ($subjectId)" ;
                }

                if($groupId != '') {
                   $condition .= " AND a.groupId IN ($groupId)" ;
                   $condition1 .= " AND att.groupId IN ($groupId)" ;
                }

                if($rollNo != '') {
                   $condition1 .= " AND s.rollNo LIKE '$rollNo%' " ;
                }

                if($studentName != '') {
                   $condition1 .= " AND CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) LIKE '$studentName%' " ;
                }
          }
    }
    //if ($mode != 'attendanceThreshold' and $mode != 'toppers' and $mode != 'belowAvg' and $mode != 'aboveAvg') {
    //    echo INVALID_DETAILS_FOUND;
    //    die;
    //}

    // to limit records per page


    $sortOrderBy1 = 'ASC';
    $sortField1  = 'percentage';

    $toppers = $sessionHandler->getSessionVariable('TOPPERS_RECORD_LIMIT');
    if($mode=='a') {
      $limitRecords = RECORDS_PER_PAGE;
    }
    else if ($mode == 't') {
      if($toppers=='') {
        $toppers = 5;
      }
      $limitRecords = $toppers;
      $sortOrderBy1 = 'DESC';
      $sortField1  = 'percentage';
    }
    else if ($mode == 'b') {
      $limitRecords = RECORDS_PER_PAGE;
    }
    else if ($mode == 'av') {
      $limitRecords = RECORDS_PER_PAGE;
    }
    else {
      $limitRecords = RECORDS_PER_PAGE;
    }

   $str = $mode;

   if($str == 'a') {
     $searchBy = "List of Students having attendance below Attendance Threshold (".$sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD')."%)";
   }
   else if($str == 't'){
      $searchBy = "List of Top ".$sessionHandler->getSessionVariable('TOPPERS_RECORD_LIMIT')." students";
      $toppers = $sessionHandler->getSessionVariable('TOPPERS_RECORD_LIMIT');
      if($toppers=='') {
        $toppers = 5;
      }
      $searchBy = "List of Top ".$toppers." students";
   }
   else if($str == 'b'){
      $searchBy = "List of Students having marks below average (below ".$sessionHandler->getSessionVariable('BELOW_AVERAGE_PERCENTAGE')."%)";
   }
   else if($str == 'av'){
      $searchBy = "List of Students having marks above average (more than ".$sessionHandler->getSessionVariable('ABOVE_AVERAGE_PERCENTAGE')."%)";
   }

    // Search filter /////
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : $sortOrderBy1;
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : $sortField1;
    $orderBy = " $sortField $sortOrderBy";


    $concatStr = "'0#0'";

    $activeTimeTableLabelArray = $managementManager->getActiveTimeTable();
    $activeTimeTableLabelId = $activeTimeTableLabelArray[0]['timeTableLabelId'];

    $teacherSubjectsArray = $managementManager->getTeacherSubjects($activeTimeTableLabelId,$condition);
    $concatStrSubArray = array();
    foreach($teacherSubjectsArray as $teacherSubjectRecord) {
        $subjectId = $teacherSubjectRecord['subjectId'];
        $subjectCode = $teacherSubjectRecord['subjectCode'];
        $classId = $teacherSubjectRecord['classId'];
        $employeeId = $teacherSubjectRecord['employeeId'];
        $className = $teacherSubjectRecord['className'];
        $employeeName = $teacherSubjectRecord['employeeName'];
        if ($concatStr != '') {
            $concatStr .= ',';
        }
        $concatStr .= "'$subjectId#$classId'";
    }

    if ($mode == 'a') {
       $recordsArray = $managementManager->getAttendanceThresholdRecords($activeTimeTableLabelId, $concatStr,$condition1,$orderBy);
       $cnt1 = count($recordsArray);
    }
    else if ($mode == 't') {
       $k=0;
       $concatStrArr = explode(',',$concatStr);
       $cnt = 0;
       for($i=0;$i<count($concatStrArr);$i++) {
         $totalRecordArray = $managementManager->countTopperRecords($concatStrArr[$i],$condition1);
         if($totalRecordArray[0]['cnt']>0) {
            $cnt = $cnt + $totalRecordArray[0]['cnt'];
            $recordsArray1 = $managementManager->getTopperRecords($concatStrArr[$i],$condition1);
            for($j=0;$j<count($recordsArray1);$j++) {
               $recordsArray[$k]['rollNo'] = $recordsArray1[$j]['rollNo'];
               $recordsArray[$k]['studentName'] = $recordsArray1[$j]['studentName'];
               $recordsArray[$k]['className'] = $recordsArray1[$j]['className'];
               $recordsArray[$k]['subjectCode'] = $recordsArray1[$j]['subjectCode'];
               $recordsArray[$k]['groupShort'] = $recordsArray1[$j]['groupShort'];
               $recordsArray[$k]['employeeName'] = $recordsArray1[$j]['employeeName'];
               $recordsArray[$k]['percentage'] = $recordsArray1[$j]['percentage'];
               $recordsArray[$k]['studentPhoto'] = $recordsArray1[$j]['studentPhoto'];
               $k=$k+1;
            }
         }
       }
       if($k>0) {
         if($k > $toppers) {
           $cnt1 = $toppers;
         }
         else {
           $cnt1 = $k;
         }
       }
    }
    else if ($mode == 'b') {
        $recordsArray = $managementManager->getBelowAvgRecords($concatStr,$condition1,$orderBy);
        $cnt1 = count($recordsArray);
        //$recordsArray = $managementManager->getBelowAvgRecords($concatStrSub);
    }
    else if ($mode == 'av') {
       $recordsArray = $managementManager->getAboveAvgRecords($concatStr,$condition1,$orderBy);
       $cnt1 = count($recordsArray);
       //$recordsArray = $managementManager->getAboveAvgRecords($concatStrSub);
    }


    $search = parseCSVComments($searchBy);
    $csvData = '';
    $csvData .= "Search By:, ".parseCSVComments($search)."\n";
    $csvData .= "Sr. No.,Roll No.,Student Name,Class,Subject,Group,Teacher,Percent \n";
    for($i=0;$i<$cnt1;$i++) {
        if ($mode == 't') {
          $val=$records+$i;
        }
        else {
          $val=$i;
        }
        if($recordsArray[$val]['studentPhoto'] != ''){
            $File = STORAGE_PATH."/Images/Student/".$recordsArray[$val]['studentPhoto'];
            if(file_exists($File)){
               $imgSrc= IMG_HTTP_PATH.'/Student/'.$recordsArray[$val]['studentPhoto'].'?x='.rand(0,150)*rand(0,150);
            }
            else{
               $imgSrc= IMG_HTTP_PATH.'/notfound.jpg';
            }
        }
        else{
          $imgSrc= IMG_HTTP_PATH.'/notfound.jpg';
        }

        $imgSrc = "<img src='".$imgSrc."' width='20' height='20' id='studentImageId' class='imgLinkRemove' />";
        $recordsArray[$records+$i]['imgSrc'] =  $imgSrc;

        $csvData .= ($i+1).",".parseCSVComments($recordsArray[$i]['rollNo']).",".parseCSVComments($recordsArray[$i]['studentName']);
        $csvData .= ",".parseCSVComments($recordsArray[$i]['className']);
        $csvData .= ",".parseCSVComments($recordsArray[$i]['subjectCode']).",".parseCSVComments($recordsArray[$i]['groupShort']);
        $csvData .= ",".parseCSVComments($recordsArray[$i]['employeeName']).",".parseCSVComments($recordsArray[$i]['percentage'])."\n";
        //$valueArray[] = array_merge(array('srNo' => ($records+$i+1)), $recordsArray[$val]);
    }

	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a CSV
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	// It will be called testType.csv
	header('Content-Disposition: attachment;  filename="AttendanceThresholdReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;

// $History: listAttendanceThresholdReportCSV.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/10/10    Time: 4:10p
//Created in $/LeapCC/Templates/Management
//initial checkin
//

?>