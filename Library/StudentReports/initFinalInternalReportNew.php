<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Ajinder Singh
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    ini_set('MEMORY_LIMIT','200M');
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
   
    global $sessionHandler;
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
      UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();
    

    $labelId = $REQUEST_DATA['timetable']; //1
    $classId = $REQUEST_DATA['degree']; //1
    $subjectId = $REQUEST_DATA['subjectId']; //8
    $groupId = $REQUEST_DATA['groupId']; //3
    $showGraceMarks = $REQUEST_DATA['showGraceMarks'];
    $showTestMarks = $REQUEST_DATA['showMarks'];
    $showExternalMarks = $REQUEST_DATA['showExternalMarks'];
    $showUnivRollNo = $REQUEST_DATA['showUnivRollNo'];  
    $roundMethod  = $REQUEST_DATA['roundMethod'];  
    
    global $sessionHandler;
    if($roleId==2) {
       $employeeId = $sessionHandler->getSessionVariable('EmployeeId');       
    }
    else {
       $employeeId = trim($REQUEST_DATA['employeeId']);    
       if($employeeId!='all' && $employeeId!='') {
          $condition .= " AND tt.employeeId = '$employeeId' ";  
       }  
    }
    
    
    // Weighted Marks Internal (Internal+Attendance) and External
    $externalTotalMarks = "";
    $internalTotalMarks = "";
    $internalExternalArray = $studentReportsManager->getInternalExternalSubjectMarks($classId,$subjectId);
    if(count($internalExternalArray)>0) {
      $externalTotalMarks = $internalExternalArray[0]['externalTotalMarks'];
      $internalTotalMarks = $internalExternalArray[0]['internalTotalMarks'];
      $showExternalTotalMarks = $externalTotalMarks;
      $showInternalTotalMarks = $internalTotalMarks;
    }
    
    $totalGracemarks = 0;
    $allDetailsArray = array();
    //fetch class students
    $conditions = '';
    if ($groupId != 'all') {
        $conditions = "";
        $groupCodeArray = $studentReportsManager->getSingleField('`group`', 'groupShort', "WHERE groupId  = '$groupId' ");
        $groupCode = $groupCodeArray[0]['groupShort'];
    }

    //fetch distinct types of test taken on this class

    $groupStr = '';
    $groupStr2 = '';
    $testGroup = '';
    if ($groupId != 'all') {
        $groupStr  .= " AND sg.groupId = '$groupId' ";
        $groupStr2 .= " AND groupId = '$groupId' ";
        $testGroup  = " AND groupId = '$groupId' ";
    }
    $testTypeArray = $studentReportsManager->getClassInternalTestTypes($classId, $subjectId);
    
  
    $mmSubjectArray = $studentReportsManager->checkSubjectMM($classId, $subjectId);
    $mmSubjectCount = $mmSubjectArray[0]['cnt'];

    $tableName = 'student_groups';
    if ($mmSubjectCount > 0) {
        $tableName = 'student_optional_subject';
    }
    $uniqueTTCategoryArray = array();
    $uniqueTTCategoryId = array();

   
    foreach($testTypeArray as $testTypeRecord) {
        $testTypeId = $testTypeRecord['testTypeId'];
        $testTypeCategoryId = $testTypeRecord['testTypeCategoryId'];
        if (in_array($testTypeCategoryId, $uniqueTTCategoryId)) {
            continue;
        }
        $uniqueTTCategoryArray[] = $testTypeRecord;
        $uniqueTTCategoryId[] = $testTypeCategoryId;
    }
    $testTypeArray = $uniqueTTCategoryArray; 
    
  
    $sortBy = '';

    $sorting = $REQUEST_DATA['sorting'];
    if ($sorting == 'cRollNo') {
        $sortBy = ' length(rollNo)+0,rollNo ';
    }
    elseif ($sorting == 'uRollNo') {
        $sortBy = ' length(universityRollNo)+0,universityRollNo ';
    }
    elseif ($sorting == 'name') {
        $sortBy = ' studentName ';
    }
    $sortBy .= $REQUEST_DATA['ordering'];

    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

    
    $finalCondition = " AND c.classId LIKE '$classId' AND tt.subjectId = '$subjectId' ";      
    if($groupId!='all') {
      $finalCondition .= " AND tt.groupId = '$groupId' "; 
    }
    if ($roleId == 1) {
       $employeeId = trim($REQUEST_DATA['employeeId']);    
       if($employeeId!='all' && $employeeId!='') {
          $finalCondition .= " AND tt.employeeId = '$employeeId' ";  
       }  
    }
    elseif($roleId == 2) {
       $employeeId = $sessionHandler->getSessionVariable('EmployeeId');
       $finalCondition .= " AND tt.employeeId = '$employeeId' ";  
    }
    else {
       $employeeId = trim($REQUEST_DATA['employeeId']);    
       if($employeeId!='all' && $employeeId!='') {
         $finalCondition .= " AND tt.employeeId = '$employeeId' ";  
       }  
    }
    
    //$studentArray = $studentReportsManager->getStudents($classId, $subjectId, $tableName, $groupId, $sortBy, $limit); 
    $studentArray = $studentReportsManager->getFinalTransferredStudent($finalCondition, $sortBy, $limit); 
    $studentIdList = UtilityManager::makeCSList($studentArray, 'studentId');
    if(empty($studentIdList)) {
      $studentIdList = 0;
    }
    $studentArray2 = $studentReportsManager->getFinalTransferredStudent($finalCondition, $sortBy); 
    //$studentArray2 = $studentReportsManager->getStudents($classId, $subjectId, $tableName, $groupId, $sortBy);
    $studentIdList2 = UtilityManager::makeCSList($studentArray2, 'studentId');
    if(empty($studentIdList2)) {
       $studentIdList2 = 0;
    }
    
    
    $attCondition = " AND att.classId ='$classId' AND att.subjectId ='$subjectId' "; 
    $attendanceDetailsArray = $commonQueryManager->getFinalAttendance($attCondition,'','1','','',$classId,$subjectId);
    /*  $lectureDeliveredArray = $studentReportsManager->getLectureDeliveredSum($studentIdList, $classId, $subjectId);
        $lectureDeliveredNewArray = array();
        foreach($lectureDeliveredArray as $record) {
            $lectureDeliveredNewArray[$record['studentId']] = $record['lectureDelivered'];
        }
        $lectureDeliveredArray = null;

        $lectureAttendedArray = $studentReportsManager->getLectureAttendedSum($studentIdList, $classId, $subjectId);
        $lectureAttendedNewArray = array();
        foreach($lectureAttendedArray as $record) {
            $lectureAttendedNewArray[$record['studentId']] = $record['lectureAttended'];
        }
        $lectureAttendedArray = null;

        $dutyLeaveArray = $studentReportsManager->getDutyLeaveSum($studentIdList, $classId, $subjectId);
        $dutyLeaveNewArray = array();
        foreach($dutyLeaveArray as $record) {
            $dutyLeaveNewArray[$record['studentId']] = $record['dutyLeave'];
        }
        $dutyLeaveArray = null;
    */


    $queryPart = '';
    $testTypeList = '';
    $i = 0;
    foreach($testTypeArray as $testTypeRecord) {
        $testTypeId = $testTypeRecord['testTypeId'];
        $testTypeCategoryId = $testTypeRecord['testTypeCategoryId'];
        $testTypeName = $testTypeRecord['testTypeName'];
        $isAttendanceCategory = $testTypeRecord['isAttendanceCategory'];
        $testTypeMaxMarks = $testTypeRecord['maxMarks'];
        $totalMaxMarks += $testTypeMaxMarks;
        if ($isAttendanceCategory == 1) {
            $attendanceArray = $studentReportsManager->getAttendanceResultMarks($studentIdList, $classId, $subjectId, $testTypeId);
            $attendanceNewArray = array();
            foreach($attendanceArray as $record) {
                $attendanceNewArray[$record['studentId']] = $record['ms_attendance'];
            }
            $attendanceArray = null;
        }
        else {
            $testTransferredMarksArray[$testTypeCategoryId] = $studentReportsManager->getTestTransferredResultMarks($studentIdList, $classId, $subjectId, $testTypeId);
          

            if ($showTestMarks == 1) {
                $testTypeMaxMarks = " b.maxMarks ";
                $testTypeMarksArray = $studentReportsManager->getTestMarks($studentIdList, $classId, $subjectId, $testTypeCategoryId, $testTypeMaxMarks);
                foreach($testTypeMarksArray as $record) {
                   $testTypeArray[$i]['testMaxMarks'] = $record['maxMarks'];
                }
            }
            else {
                $testTypeArray[$i]['testMaxMarks'] = $testTypeMaxMarks;
            }
            $testTypeMaxMarks = $testTypeArray[$i]['testMaxMarks'];
            $testMarksArray[] = $studentReportsManager->getTestMarks($studentIdList, $classId, $subjectId, $testTypeCategoryId, $testTypeMaxMarks);
            $testIndexArray = $studentReportsManager->getDistinctTests($testTypeCategoryId, $classId, $subjectId, $testGroup);
            $allDetailsArray[$testTypeCategoryId] = $testIndexArray;
            $allDetailsArray['testTypeCategory'][$testTypeCategoryId] = $testIndexArray; 
        }
        $i++;
    }
    $allDetailsArray['testTypes'] = $testTypeArray;
    
  
    $testTransferredMarksNewArray = array();
    foreach($testTransferredMarksArray as $testTypeCategoryId => $recordArray) {
        foreach($recordArray as $record) {
            $studentId = $record['studentId'];
            $marksScored = $record['marksScored'];
            $ttMaxMarks = $record['maxMarks'];
            $testTransferredMarksNewArray[$studentId]['ms_'.$testTypeCategoryId] = $marksScored;
            $testTransferredMarksNewArray[$studentId]['msmm_'.$testTypeCategoryId] = $ttMaxMarks;
        }
    }
    $testTransferredMarksArray = null;

    // External Marks
    $externalArray = $studentReportsManager->getStudentExteralMarks($studentIdList, $classId, $subjectId);
    
    $externalMarksArray = array();
    foreach($externalArray as $record) {
        $studentId = $record['studentId'];
       
        $externalMarksArray[$studentId]['maxMarks'] =  $record['maxMarks'] ;   
        $externalMarksArray[$studentId]['marksScored'] = $record['marksScored'];  
       
        if($showTestMarks=='1') {
          $showExternalTotalMarks = $externalMarksArray[$studentId]['maxMarks']; 
        }
        
        $exmm = $externalMarksArray[$studentId]['maxMarks'];
        $exms = $externalMarksArray[$studentId]['marksScored'];
        
        if($exms=='') {
          $exms = NOT_APPLICABLE_STRING;  
        }
   
        // Show Weighted Marks
        if($showTestMarks !='1') {
           if($showExternalTotalMarks=='') {
             $showExternalTotalMarks='0';  
           }
           if($exms!='A' || $exms != 'D' || $exms != NOT_APPLICABLE_STRING) {
              if($showExternalTotalMarks != $exmm) {
                if($showExternalTotalMarks=='0') {
                  $exms = '0';  
                }  
                else {
                  $exms = ($exms/$exmm)*$showExternalTotalMarks;
                }
              }
           }
        }   
       
        $externalMarksArray[$studentId]['maxMarks'] =  $exmm;   
        $externalMarksArray[$studentId]['marksScored'] = $exms;  
    }
   
   
    $graceMarksArray = $studentReportsManager->getGraceMarks($studentIdList, $classId, $subjectId);
    $graceMarksNewArray = array();
    foreach($graceMarksArray as $record) {
        $studentId = $record['studentId'];
        $marksScored = $record['graceMarks'];
        $graceMarksNewArray[$studentId]['grace'] =  $record['graceMarks'];   
        $graceMarksNewArray[$studentId]['Int'] =  $record['internalGraceMarks'];   
        $graceMarksNewArray[$studentId]['Ext'] =  $record['externalGraceMarks'];   
        $graceMarksNewArray[$studentId]['Tot'] =  $record['totalGraceMarks'];   
    }
    $graceMarksArray = null;

    $totalTransferredMarksArray = $studentReportsManager->getTotalTransferredResultMarksNew($studentIdList, $classId, $subjectId);
    $totalTransferredMarksNewArray = array();
    foreach($totalTransferredMarksArray as $record) {
        $studentId = $record['studentId'];
        $marksScored = $record['marksScored'];
        $totalTransferredMarksNewArray[$studentId] = $marksScored;
    }
    $totalTransferredMarksArray = null;

    $studentTestArray = array();
    $studentTestArrayActualMM = array();
    $studentTestArrayActualMS = array();
    
    foreach($testMarksArray as $testRecordArray) {
        foreach($testRecordArray as $record) {
            $studentId = $record['studentId'];
            $testName = $record['testName'];
            $marksScored = $record['marksScored'];
            $maxMarks = $record['maxMarks'];
            $actualMaxMarks = $record['actualMaxMarks'];
            $actualMarksScored = $record['actualMarksScored'];
            
            $studentTestArray[$studentId][$testName] = $marksScored;
            $studentMaxMarksArray[$studentId][$testName] = $maxMarks; 
            
            $studentTestArrayActualMM[$studentId][$testName] = $actualMaxMarks;
            $studentTestArrayActualMS[$studentId][$testName] = $actualMarksScored;
        }
    }
    $testMarksArray = null;

    $lectureDeliveredNewArray = array();
    foreach($attendanceDetailsArray as $record) {
       $tStudentId = $record['studentId'];  
       $lectureAttended = $record['lectureAttended'];
       $lectureDelivered = $record['lectureDelivered'];
       $dutyLeave = $record['dutyLeave'];
       $medicalLeave = $record['medicalLeave'];
       
       $lectureDeliveredNewArray[$tStudentId]['lectureAttended'] = $lectureAttended; 
       $lectureDeliveredNewArray[$tStudentId]['lectureDelivered'] = $lectureDelivered;
       $lectureDeliveredNewArray[$tStudentId]['dutyLeave'] = $dutyLeave;
       $lectureDeliveredNewArray[$tStudentId]['medicalLeave'] = $medicalLeave;
       $lectureDeliveredNewArray[$tStudentId]['totalAttended'] = $lectureAttended+$dutyLeave+$medicalLeave;
    }    
    
    $i = 0;
    foreach($studentArray as $record) {
        $studentId = $record['studentId'];
        $studentArray[$i]['lectureDelivered'] = $lectureDeliveredNewArray[$studentId]['lectureDelivered'];
        $studentArray[$i]['lectureAttended'] = $lectureDeliveredNewArray[$studentId]['lectureAttended'];
        $studentArray[$i]['dutyLeave'] = $lectureDeliveredNewArray[$studentId]['dutyLeave'];  
        $studentArray[$i]['medicalLeave'] = $lectureDeliveredNewArray[$studentId]['medicalLeave'];  
        $studentArray[$i]['totalAttended'] = $lectureDeliveredNewArray[$studentId]['totalAttended'];  
        $studentArray[$i]['ms_attendance'] = $attendanceNewArray[$studentId];
        
        if (array_key_exists($studentId, $studentTestArrayActualMS)) {
            foreach($studentTestArrayActualMS[$studentId] as $testName => $marksScored) {
               $ttName = $testName."ac_ms";
               $studentArray[$i][$ttName] = $marksScored;
            }                                                   
            foreach($studentTestArrayActualMM[$studentId] as $testName => $maxMarks) {
                $ttName = $testName."ac_mm";
                $studentArray[$i][$ttName] = round($maxMarks,1);
            }
        }
        
        if (array_key_exists($studentId, $studentTestArray)) {
            foreach($studentTestArray[$studentId] as $testName => $marksScored) {
               $studentArray[$i][$testName] = $marksScored;
            }
            foreach($studentMaxMarksArray[$studentId] as $testName => $maxMarks) {
                $studentTestMaxMarksArray[$i][$testName]['maxMarks'] = round($maxMarks,1);
            }
        }
        if (array_key_exists($studentId, $testTransferredMarksNewArray)) {
            foreach($testTransferredMarksNewArray[$studentId] as $testName => $marksScored) {
                $studentArray[$i][$testName] = $marksScored;
            }
        }
        
        if($graceMarksNewArray[$studentId]['Int']!='') { 
           $studentArray[$i]['ms_grace'] = $graceMarksNewArray[$studentId]['grace']; 
           $studentArray[$i]['ms_int_grace'] = $graceMarksNewArray[$studentId]['Int'];
           $studentArray[$i]['ms_ext_grace'] = $graceMarksNewArray[$studentId]['Ext'];
           $studentArray[$i]['ms_tot_grace'] = $graceMarksNewArray[$studentId]['Tot'];
        }
        else {
           $studentArray[$i]['ms_grace'] = '0';  
           $studentArray[$i]['ms_int_grace'] = '0';
           $studentArray[$i]['ms_ext_grace'] = '0';
           $studentArray[$i]['ms_tot_grace'] = '0';  
        }
        
        if($externalMarksArray[$studentId]['marksScored']!='') {
          $studentArray[$i]['ext_maxMarks'] = $externalMarksArray[$studentId]['maxMarks'];
          $studentArray[$i]['ext_scored'] = $externalMarksArray[$studentId]['marksScored'];
        }
        else {
          $studentArray[$i]['ext_maxMarks'] = NOT_APPLICABLE_STRING;
          $studentArray[$i]['ext_scored'] = NOT_APPLICABLE_STRING;  
        }
        
        $studentArray[$i]['ms_total'] = $totalTransferredMarksNewArray[$studentId];
        
        $i++;
    }
    $studentTestArray = null;
    $testTransferredMarksNewArray = null;
    $graceMarksNewArray = null;
    $totalTransferredMarksNewArray = null;


    $totalRecordArray = $studentReportsManager->countStudents($classId, $subjectId, $tableName, $groupId);
    $cnt1 = $totalRecordArray[0]['cnt'];



    $resultDataArray = $studentArray;//$studentReportsManager->getFinalReportData($classId, $groupStr, $queryPart, $sortBy, $limit, $tableName);
    $studentArray = null;
    $cnt = count($resultDataArray);

    $allDetailsArray['totalRecords'] = $cnt1;


    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$resultDataArray[$i]);
    }

     $totalMarksArray = $studentReportsManager->getSubjectTotalMarks($studentIdList2, $classId, $subjectId);
     if ($showGraceMarks == '1' or $showGraceMarks == 1) {
         $graceMarksArray = $studentReportsManager->getTotalGraceMarks($studentIdList2, $classId, $subjectId);
         $totalGracemarks = $graceMarksArray[0]['marksScored'];
     }
     $totalMarksScored = $totalMarksArray[0]['marksScored'] + $totalGracemarks;
     $maxMarks = $totalMarksArray[0]['maxMarks'];
     $average = round(($totalMarksScored*100) / $maxMarks,2);
     $average = "$average";

    $str = '';
    if ($groupId != 'all') {
        $str = " AND a.groupId = $groupId ";
    }
    else {
        $str = " AND a.groupId IN (select groupId from `group` where classId = $classId)";
    }
    $teacherArray = $studentReportsManager->getSubjectTeachers($subjectId, $str);
    $teacherName = UtilityManager::makeCSList($teacherArray,'employeeName');
    $subjectNameArray = $studentReportsManager->getSingleField('subject','subjectName'," WHERE subjectId = $subjectId");
    $subjectName = $subjectNameArray[0]['subjectName'];

    
    $allDetailsArray['average'] = $average;
    $allDetailsArray['teachers'] = $teacherName;
    $allDetailsArray['subjectName'] = $subjectName;
    $allDetailsArray['resultData'] = $valueArray;  
    $valueData = reportGenerate($allDetailsArray);
    $allDetailsArray['resultData'] = $valueData;  
    //$allDetailsArray['maxMarks'] = $studentTestMaxMarksArray;
    
    
    
    echo json_encode($allDetailsArray);


function reportGenerate($allDetailsArray) {
       
        global $showUnivRollNo;
        global $showGraceMarks;
        global $showTestMarks;  
        global $showExternalMarks;
        global $showExternalTotalMarks;
        global $showInternalTotalMarks;
        global $roundMethod;
        
        $totalTests = count($allDetailsArray['testTypes']);
           
        if ($totalTests == 0 || count($allDetailsArray['resultData'])== 0) {
            $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
             $tableData = '<table border="0" cellpadding="1" cellspacing="1" width="100%">';
            $tableData .= '<tr class="rowheading"><td width="2%" class="searchhead_text">#</td>';
            if($showUnivRollNo == '1') {
              $tableData .= '<td width="8%" class="searchhead_text">U.Roll No.</td>';
            }
            $tableData .= '<td width="8%" class="searchhead_text">Roll No.</td>';
            $tableData .= '<td width="20%" class="searchhead_text">Student Name</td>';
            $tableData .= '<td width=62% class="searchhead_text">&nbsp;</td></tr>';
            $tableData .= '<tr '.$bg.'>';
            $tableData .= '<td class="padding_top" align=center colspan=5>No details found</td></tr>';
        }
        else {
            $tableData = '<table border="0" cellpadding="1" cellspacing="1" width="100%">';
            $tableData .= '<tr class="rowheading"><td rowspan="3" width="2%" class="searchhead_text">#</td>';
            if($showUnivRollNo == '1') {
              $tableData .= '<td rowspan="3" width="8%" class="searchhead_text">U.Roll No.</td>';
            }
            $tableData .= '<td rowspan="3" width="8%" class="searchhead_text">Roll No.</td>';
            $tableData .= '<td width="20%" rowspan="3" class="searchhead_text">Student Name</td>';


            $allTests = 0;
            for($i=0;$i<$totalTests;$i++) {
                $testTypeId = $allDetailsArray['testTypes'][$i]['testTypeId'];
                $testTypeCategoryId = $allDetailsArray['testTypes'][$i]['testTypeCategoryId'];
                $isAttendanceCategory = $allDetailsArray['testTypes'][$i]['isAttendanceCategory'];
                $testTypeName = $allDetailsArray['testTypes'][$i]['testTypeName'];
                
                if($isAttendanceCategory == 1 || $isAttendanceCategory == "1") {
                   $allTests += 5;//for attendance
                }
                else {
                   $testTypeCategoryIdTests = count($allDetailsArray[$testTypeCategoryId]);
                   $allTests += $testTypeCategoryIdTests;
                   $allTests++;//for evaluated marks
                }
            }
            
            $perTestSpace = intval(62/$allTests).'%';
            //$tableData .= '<tr class="rowheading">';
            for($i=0;$i<$totalTests;$i++) {
                $testTypeId = $allDetailsArray['testTypes'][$i]['testTypeId'];
                $testTypeCategoryId = $allDetailsArray['testTypes'][$i]['testTypeCategoryId'];
                $isAttendanceCategory = $allDetailsArray['testTypes'][$i]['isAttendanceCategory'];
                $testTypeName = $allDetailsArray['testTypes'][$i]['testTypeName'];
                if($isAttendanceCategory == 1 || $isAttendanceCategory == "1") {
                  $tableData .= '<td align="center" class=" searchhead_text" colspan = "7">Attendance&nbsp;</td>';
                }
                else {
                    $testTypeCategoryIdTests = count($allDetailsArray[$testTypeCategoryId]);  
                    $thisColSpan = $testTypeCategoryIdTests;   
                    if($showTestMarks=='1') {
                      $thisColSpan = $thisColSpan*2;  
                    }
                    $thisColSpan = $thisColSpan+1;
                    $tableData .= '<td align="center" class=" searchhead_text" colspan = "'.$thisColSpan.'">'.$testTypeName.'&nbsp;</td>';
                    $allTests .= $testTypeCategoryIdTests;
                    $allTests++;//for evaluated marks
                }
            }
            //showGraceMarksValue = form.showGraceMarks.value;
            $showGraceMarksValue = $showGraceMarks;
            //if (showGraceMarksValue == 'yes') {
            if ($showGraceMarksValue == '1') {
              if($showExternalMarks=='1') {     
                $tableData .= '<td align="center" class=" searchhead_text" colspan="3" colspan = "1">Grace</td>';
              }
              else {
                $tableData .= '<td align="center" class=" searchhead_text" colspan="2" colspan = "1">Grace</td>';  
              }
            } $exms = ($exms/$exmm)*$showExternalTotalMarks;
            $tableData .= '<td align="center" class=" searchhead_text" colspan = "1">Internal</td>';  
            
            if($showExternalMarks=='1') {    
              $tableData .= '<td align="center" class=" searchhead_text" colspan = "1">External</td>';
            }
            
            $tableData .= '<td align="right" class=" searchhead_text" rowspan="3" colspan = "1">G.Total</td>';
            $tableData .= '</tr>';
            $tableData .= '<tr class="rowheading">';
            
            $totalTestTypeMaxMarks = 0;
            for($i=0;$i<$totalTests;$i++) {
                $testTypeId = $allDetailsArray['testTypes'][$i]['testTypeId'];
                $testTypeCategoryId = $allDetailsArray['testTypes'][$i]['testTypeCategoryId'];
                $isAttendanceCategory = $allDetailsArray['testTypes'][$i]['isAttendanceCategory'];
                $testTypeName = $allDetailsArray['testTypes'][$i]['testTypeName'];
                
                $testTypeMaxMarks = $allDetailsArray['testTypes'][$i]['maxMarks'];
                $testTypeAbbr = $allDetailsArray['testTypes'][$i]['testTypeAbbr'];
                $totalTestTypeMaxMarks += $testTypeMaxMarks;
                
                if ($isAttendanceCategory == 1 || $isAttendanceCategory == "1") {
                    $tableData .= '<td align="right" rowspan="2"  class="searchhead_text" colspan = "1">Held</td>';
                    $tableData .= '<td align="right" rowspan="2" class="searchhead_text" colspan = "1">Attended</td>';
                    $tableData .= '<td align="right" rowspan="2" class="searchhead_text" colspan = "1">DL</td>';
                    $tableData .= '<td align="right" rowspan="2" class="searchhead_text" colspan = "1">ML</td>';
                    $tableData .= '<td align="right" rowspan="2" class="searchhead_text" colspan = "1">Total</td>';
                    $tableData .= '<td align="right" rowspan="2" class="searchhead_text" colspan = "1">%</td>';
                    $tableData .= '<td align="right" rowspan="2" class="searchhead_text" colspan = "1">M.M.<br> '.(round($testTypeMaxMarks*10)/10).'</td>';
                }
                else {
                    $testTypeCategoryIdTests = count($allDetailsArray[$testTypeCategoryId]);     
                    for($m=0; $m<$testTypeCategoryIdTests; $m++) {
                        $testCode = $testTypeAbbr.''.$allDetailsArray[$testTypeCategoryId][$m]['testIndex'];
                        if($showTestMarks=='1') {  
                          $tableData .= '<td align="center" class="searchhead_text" colspan="2">'.$testCode.'&nbsp;</td>';  
                        }
                        else {
                          $tableData .= '<td align="center" class="searchhead_text" rowspan="1">'.$testCode.'&nbsp;</td>';
                        }
                    }
                    $tableData .= '<td align="right" class="searchhead_text" rowspan="2" colspan = "1">M.M.<br> '.(round($testTypeMaxMarks*10)/10).'</td>';
                }
            }
                        
            
            if ($showGraceMarksValue == '1') {    
              $tableData .= '<td align="right" class="searchhead_text" rowspan="2"  colspan = "1">Internal</td>';
              if($showExternalMarks=='1') { 
                $tableData .= '<td align="right" class="searchhead_text" rowspan="2"  colspan = "1">External</td>';
              }
              $tableData .= '<td align="right" class="searchhead_text" rowspan="2"  colspan = "1">Total</td>';
            }
            
            $intMaxMks = $totalTestTypeMaxMarks;       
            /*
	        if($showTestMarks == '0') {
              $intMksWeightage = number_format($showInternalTotalMarks,2);
            }
            else {
              $intMksWeightage = $totalTestTypeMaxMarks;
            } 
            */ 	
            $intMksWeightage = $totalTestTypeMaxMarks;

            $tableData .= '<td align="right" class="searchhead_text" rowspan="2" colspan = "1">'.$intMksWeightage.'</td>';

            // External Marks
            if($showExternalMarks=='1') {
              $tableData .= '<td align="right" class="searchhead_text" rowspan="2" colspan = "1">'.(number_format($showExternalTotalMarks,2)).'</td>';  
            }
            
            
            
            $tableData .= '</tr>';    
            $tableData .= '<tr class="rowheading">';
            for($i=0;$i<$totalTests;$i++) {
                $testTypeId = $allDetailsArray['testTypes'][$i]['testTypeId'];
                $testTypeCategoryId = $allDetailsArray['testTypes'][$i]['testTypeCategoryId'];
                $isAttendanceCategory = $allDetailsArray['testTypes'][$i]['isAttendanceCategory'];
                $testTypeName = $allDetailsArray['testTypes'][$i]['testTypeName'];
                
                $testTypeMaxMarks = $allDetailsArray['testTypes'][$i]['maxMarks'];
                $testTypeAbbr = $allDetailsArray['testTypes'][$i]['testTypeAbbr'];
                
                $indiTestMaxMarks = $allDetailsArray['testTypes'][$i]['testMaxMarks'];
                if ($isAttendanceCategory == 1 || $isAttendanceCategory == "1") {
                    continue;

                }
                else {
                    $testTypeCategoryIdTests = count($allDetailsArray[$testTypeCategoryId]);  
                    
                    for ($m = 0; $m < $testTypeCategoryIdTests; $m++) {
                        $testCode = $testTypeAbbr.''.$allDetailsArray[$testTypeCategoryId][$m]['testIndex'];
                        //testMaxMarks = j[testTypeCategoryId][m]['maxMarks'];
                        if($showTestMarks=='1') {
                          $tableData .= '<td align="right" class="searchhead_text">MO&nbsp;</td>
                                         <td align="right" class="searchhead_text">MM&nbsp;</td>'; 
                        }
                        else {
                          $tableData .= '<td align="right" class="searchhead_text">'.(round($indiTestMaxMarks*10)/10).'&nbsp;</td>';
                        }
                    }
                } 
            }
            $tableData .= '</tr>';


            $resultDataLength = count($allDetailsArray['resultData']);

            for($x = 0; $x < $resultDataLength; $x++) {
                $studentTotalMarks = 0;
                $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
                $tableData .= '<tr '.$bg.'>';
                $tableData .= '<td >'.$allDetailsArray['resultData'][$x]['srNo'].'&nbsp;</td>';
                if ($allDetailsArray['resultData'][$x]['universityRollNo'] == "undefined") {
                   $allDetailsArray['resultData'][$x]['universityRollNo'] = NOT_APPLICABLE_STRING;
                }
                if ($showUnivRollNo == '1') {
                   $tableData .= '<td align="center">'.$allDetailsArray['resultData'][$x]['universityRollNo'].'&nbsp;</td>';
                }

                //$tableData .= '<td >'+j['resultData'][x]['rollNo']+'&nbsp;</td>';
                /*if (j['resultData'][x]['universityRollNo'] == "undefined" || typeof j['resultData'][x]['universityRollNo'] == "undefined") {
                    j['resultData'][x]['universityRollNo'] = '---';
                }
                if (showUnivRollNo == '1') {
                    $tableData .= '<td align="center">'+j['resultData'][x]['universityRollNo']+'&nbsp;</td>';
                }*/
                
                $tableData .= '<td >'.$allDetailsArray['resultData'][$x]['rollNo'].'&nbsp;</td>';
                $tableData .= '<td >'.$allDetailsArray['resultData'][$x]['studentName'].'&nbsp;</td>';
                
                $graceMarks = $allDetailsArray['resultData'][$x]['ms_grace'];
                $graceMarksInt = $allDetailsArray['resultData'][$x]['ms_int_grace'];   
                $graceMarksExt = $allDetailsArray['resultData'][$x]['ms_ext_grace'];   
                $graceMarksTot = $allDetailsArray['resultData'][$x]['ms_tot_grace'];   
                
              
                $externalMaxMarks = $allDetailsArray['resultData'][$x]['ext_maxMarks'];   
                $externalMarks = $allDetailsArray['resultData'][$x]['ext_scored'];   
                
                
                $studentFinalTotalMarks = $allDetailsArray['resultData'][$x]['ms_total'];
                
                $ttTotalTestMaxMarks='0';
                for($i=0;$i<$totalTests;$i++) {
                    $testTypeId = $allDetailsArray['testTypes'][$i]['testTypeId'];
                    $testTypeCategoryId = $allDetailsArray['testTypes'][$i]['testTypeCategoryId'];
                    $isAttendanceCategory = $allDetailsArray['testTypes'][$i]['isAttendanceCategory'];
                    $testTypeName = $allDetailsArray['testTypes'][$i]['testTypeName'];
                    
                    $testTypeMaxMarks = $allDetailsArray['testTypes'][$i]['maxMarks'];
                    $testTypeAbbr = $allDetailsArray['testTypes'][$i]['testTypeAbbr'];
                    
                    $ttTotalTestMaxMarks += $testTypeMaxMarks;
                    
                    if ($isAttendanceCategory == 1 || $isAttendanceCategory == "1") {
                        $lectureDelivered = $allDetailsArray['resultData'][$x]['lectureDelivered'];
                        if ($lectureDelivered == null || $lectureDelivered == "null") {
                            $lectureDelivered = '---';
                        }
                        $lectureAttended = $allDetailsArray['resultData'][$x]['lectureAttended'];
                        if ($lectureAttended == null || $lectureAttended == "null") {
                            $lectureAttended = '---';
                        }
                        $totalAttended = $allDetailsArray['resultData'][$x]['totalAttended'];
                        if ($totalAttended == null || $totalAttended == "null") {
                            $totalAttended = '---';
                        }
                        $dutyLeave = $allDetailsArray['resultData'][$x]['dutyLeave'];
                        $align = "right";
                        if ($dutyLeave == null || $dutyLeave == "null" || $dutyLeave == "0.00") {
                            $dutyLeave = '---';
                            $align = "center";
                        }
                        $medicalLeave = $allDetailsArray['resultData'][$x]['medicalLeave'];
                        $align = "right";
                        if ($medicalLeave == null || $medicalLeave == "null" || $medicalLeave == "0.00") {
                            $medicalLeave = '---';
                            $align = "center";
                        }
                        $tableData .= '<td align="right"  colspan = "1">'.$lectureDelivered.'</td>';
                        $tableData .= '<td align="right"   colspan = "1">'.$lectureAttended.'</td>';
                        $tableData .= '<td align="'.$align.'"   colspan = "1">'.$dutyLeave.'</td>';
                        $tableData .= '<td align="'.$align.'"   colspan = "1">'.$medicalLeave.'</td>';
                        $tableData .= '<td align="right"   colspan = "1">'.$totalAttended.'</td>';
                        $percentAttended = 0;
                        if ($allDetailsArray['resultData'][$x]['lectureDelivered'] != null && $allDetailsArray['resultData'][$x]['lectureDelivered'] != "null") {
                            $percentAttended = (ceil($allDetailsArray['resultData'][$x]['totalAttended'] * 100 / $allDetailsArray['resultData'][$x]['lectureDelivered']));
                        }
                        $tableData .= '<td align="right"   colspan = "1">'.$percentAttended.'</td>';
                        $ms_attendance = (round($allDetailsArray['resultData'][$x]['ms_attendance']*10)/10);
                        if ($ms_attendance == null || $ms_attendance == "null") {
                            $ms_attendance = '---';
                        }
                        else {
                            $studentTotalMarks += $ms_attendance;
                        }
                        $tableData .= '<td align="right"  colspan = "1"><B>'.$ms_attendance.'</B></td>';
                    }
                    else {
                        $testTypeCategoryIdTests = count($allDetailsArray[$testTypeCategoryId]);    
                        $mm=0;
                        $ttMks =0;
                        
                        $actMks =0;
                        $actMax =0;
                        
                        for ($m = 0; $m < $testTypeCategoryIdTests; $m++) {                         
                            $testMaxMarks += $allDetailsArray[$testTypeCategoryId][$m]['testIndex']['maxMarks'];
                            $testIndex = $allDetailsArray[$testTypeCategoryId][$m]['testIndex'];
                            $testName = 'ms_'.$testTypeCategoryId.'_'.$testIndex;
                            
                            $testMarksName = 'ms_'.$testTypeCategoryId;
                            $studentMarks = $allDetailsArray['resultData'][$x][$testName];
                            $testMarksNameMM = 'msmm_'.$testTypeCategoryId; 
                              
                                            
                            $mm += $allDetailsArray['resultData'][$x][$testMarksNameMM];  
                            
                         
                            $ttName = $testName."ac_mm";
                            $actualMaxMarks = $allDetailsArray['resultData'][$x][$ttName];
                            
                            $ttName = $testName."ac_ms";
                            $actualMarksScored = $allDetailsArray['resultData'][$x][$ttName];
                            
                            //studentMarks = $allDetailsArray['resultData'][$x][testName];
                            $align = "right";
                            $studentTestMaxMarks = '';
                      
                            
                            if($showTestMarks=='1') {
                                if ($actualMarksScored == null || $actualMarksScored == "null" || $actualMarksScored == "N/A") {
                                    $actualMarksScored = NOT_APPLICABLE_STRING;
                                    $actualMaxMarks = NOT_APPLICABLE_STRING;
                                    $align = "center";
                                }
                                else if ($studentMarks == "A") {
                                    $align = "center";
                                    $actualMarksScored = "<u>".$studentMarks."</u>";
                                }
                                if($actualMarksScored!=NOT_APPLICABLE_STRING) {
                                  $actMks +=  $actualMarksScored;
                                  $actMax += $actualMaxMarks;
                                }
                                $tableData .= '<td align="'.$align.'" nowrap>'.$actualMarksScored.'</td>';  
                                $tableData .= '<td align="'.$align.'" nowrap>'.$actualMaxMarks.'</td>';  
                            }
                            else {
                                if ($studentMarks == null || $studentMarks == "null" || $studentMarks == "N/A") {
                                    $studentMarks = NOT_APPLICABLE_STRING;
                                    $align = "center";
                                }
                                else if ($studentMarks == "A") {
                                    $align = "center";
                                    $studentMarks = "<u>".$studentMarks."</u>";
                                    $ttMks += number_format($studentMarks,2);
                                }
                                else {
                                    $studentMarks = number_format($studentMarks,2);
                                    $ttMks += $studentMarks;
                                }  
                                $tableData .= '<td align="'.$align.'" nowrap>'.$studentMarks.'</td>';
                            }
                        }   
                        $studentMarks = $allDetailsArray['resultData'][$x][$testMarksName];  
                        /*  $studentMarks = number_format(($actMks/$actMax)*$testTypeMaxMarks,2);
                            if($showTestMarks == '0') {
                               $studentMarks = number_format(($ttMks/$mm)*$testTypeMaxMarks,2);
			                }
                        */  
                          
                        $tableData .= '<td align="right"><B>'.number_format($studentMarks,2).'</B>&nbsp;</td>';  
                        
                        $studentTotalMarks += doubleval($studentMarks);
                    }
                }
                
                if ($graceMarks == null || $graceMarks == "null") {
                    $graceMarks = 0;
                }
                
                //if($showTestMarks=='0') {     
                   //$studentTotalMarks = ceil($studentTotalMarks);     
                   //$studentTotalMarks = number_format(($studentTotalMarks/$ttTotalTestMaxMarks)*$showInternalTotalMarks,2);
                //}
                
                if($roundMethod=='ceil') { 
                  $studentTotalMarks = number_format(ceil($studentTotalMarks),2);
                }
                else if($roundMethod=='round') {
                  $studentTotalMarks = number_format(round($studentTotalMarks,0),2);   
                }
                else {
                  $studentTotalMarks = number_format($studentTotalMarks,2);  
                }
                
                //if (showGraceMarksValue == 'yes') {
                if ($showGraceMarksValue == '1') {
                    //$tableData .= '<td align="right">'+graceMarks+'&nbsp;</td>';
                    $tableData .= '<td align="right">'.$graceMarksInt.'&nbsp;</td>'; 
                    if($showExternalMarks=='1') { 
                      $tableData .= '<td align="right">'.$graceMarksExt.'&nbsp;</td>';
                    }
                    $tableData .= '<td align="right">'.$graceMarksTot.'&nbsp;</td>';

                    $grandTotal = doubleval($studentTotalMarks) + doubleval($graceMarksInt) + doubleval($graceMarksExt) + doubleval($graceMarksTot);
                }
                else {
                    $grandTotal = $studentTotalMarks;
                }                               
                
                
                $tableData .= '<td align="right"><B>'.$studentTotalMarks.'</B>&nbsp;</td>'; 
                
                if($showExternalMarks=='1') {
                  $tableData .= '<td align="right"><B>'.number_format($externalMarks,2).'&nbsp;</B></td>';
                  if($externalMarks!=NOT_APPLICABLE_STRING || $externalMarks!='A' || $externalMarks!='D') {
                    $grandTotal = doubleval($grandTotal) + doubleval($externalMarks);    
                  }
                }
                
                
                if($grandTotal >= 0) {
                 $grandTotal = number_format($grandTotal,2);
                }
                $tableData .= '<td align="right"><B>'.$grandTotal.'</B>&nbsp;</td>';
                $tableData .= '</tr>';
            }
        }
        $tableData .= "</table>";
        
        return $tableData;
}


