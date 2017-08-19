<?php
//This file is used as CSV for student Percentage wise Attendance Reports
//
// Author :Parveen Sharma
// Created on : 05-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
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

    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"';
         }
         else {
           return chr(160).$comments;
         }
    }


    $conditionEmployee = '';
    $roleEmployeeId='';
    if($roleId==2) {
      $employeeId=$sessionHandler->getSessionVariable('EmployeeId');
      $conditionEmployee = " AND att.employeeId = '$employeeId' ";
      $roleEmployeeId = " AND tt.employeeId = '$employeeId' ";
    }

    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportManager = StudentReportsManager::getInstance();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();


    $subjectArray = array();
    $timeTableLabelId= add_slashes($REQUEST_DATA['timeTableLabelId']);
    $degreeId = add_slashes($REQUEST_DATA['degreeId']);
    $average = add_slashes($REQUEST_DATA['average']);
    $subjectId = add_slashes($REQUEST_DATA['subjectId']);
    $groupId = add_slashes($REQUEST_DATA['groupId']);
    $percentage = add_slashes($REQUEST_DATA['percentage']);
    $startDate = add_slashes($REQUEST_DATA['startDate']);
    $endDate = add_slashes($REQUEST_DATA['endDate']);
    $consolidatedId = add_slashes($REQUEST_DATA['consolidatedId']);
    $reportType = add_slashes($REQUEST_DATA['reportType']);
    $incAll = add_slashes($REQUEST_DATA['incAll']);
    $attendanceConsolidatedView =  add_slashes($REQUEST_DATA['consolidatedView']);
    $incDutyLeave = add_slashes($REQUEST_DATA['incDutyLeave']);

    if($incDutyLeave=='') {
       $incDutyLeave=0;
    }

    if($timeTableLabelId=='') {
      $timeTableLabelId=0;
    }

    if($degreeId=='') {
      $degreeId=0;
    }

    if($subjectId=='') {
      $subjectId=0;
    }

    if($groupId=='') {
      $groupId=0;
    }

    if($consolidatedId=='') {
      $consolidatedId=0;
    }

    if($percentage=='') {
      $percentage=0;
    }

    if($average=='') {
      $average=1;
    }


    // Findout Time Table Name
    $timeNameArray = $studentReportManager->getSingleField('time_table_labels', 'labelName', "WHERE timeTableLabelId  = $timeTableLabelId");
    $timeTableName = $timeNameArray[0]['labelName'];
    if($timeTableName=='') {
      $timeTableName = NOT_APPLICABLE_STRING;
    }

    // Findout Class Name
    $classNameArray = $studentReportManager->getSingleField('class', 'className', "WHERE classId  = $degreeId");
    $className = $classNameArray[0]['className'];
    $className2 = str_replace("-",' ',$className);

    // Findout Subject
    $subName = '';
    $groupName = '';
    $groupName1 = '';
    if ($subjectId != 'all') {
       $subCodeArray = $studentReportManager->getSingleField('subject', 'subjectCode, subjectName', "WHERE subjectId  = $subjectId");
       $subName = $subCodeArray[0]['subjectName'];
       $subCode = "&nbsp;(".$subCodeArray[0]['subjectCode'].")";
    }

    // Findout Group
    if ($groupId != 'all') {
       $subCodeArray = $studentReportManager->getSingleField('`group`', 'groupName, groupShort', "WHERE groupId  = $groupId");
       $groupName1 = $subCodeArray[0]['groupName'];
    }

    $attformat='';
    if($reportType != 2) {
        if($average== 1) {
           $attformat = "%age attendance above,".parseCSVComments($percentage);
        }
        else if($average == 2) {
           $attformat = "%age attendance below,".parseCSVComments($percentage);
        }
        else if($average == 3) {
           $attformat = "%age attendance equal,".parseCSVComments($percentage);
        }
    }

    if($endDate!="") {
      $fromDate1 = UtilityManager::formatDate($endDate);
    }

    $search = "Time Table,".parseCSVComments($timeTableName)."\nDegree,".parseCSVComments($className2)."\n";
    if($subName!='') {
      $search .= "Subject,".parseCSVComments($subName." (".$subCode.")")."\n";
    }
    if($groupName1!='') {
      $search .= "Group,".parseCSVComments($groupName1)."\n";
    }
    
    $fromDate = UtilityManager::formatDate($startDate);
    $toDate = UtilityManager::formatDate($endDate);
    
    $search .= "Attendance From,".parseCSVComments($fromDate).",To,".parseCSVComments($toDate)."\n".$attformat;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';

    if ($sortField == 'studentName') {
        $sortField1 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",studentId, studentName)';
    }
    else if ($sortField == 'rollNo') {
        $sortField1 = 'IF(IFNULL(rollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",studentId, rollNo)';
    }
    else if ($sortField == 'universityRollNo') {
        $sortField1 = 'IF(IFNULL(universityRollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",studentId, universityRollNo)';
    }
    else {
       $sortField == 'studentName';
       $sortField1 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",studentId, studentName)';
    }
    $orderBy = " $sortField1 $sortOrderBy";


    $studentCondition = " AND c.classId = '$degreeId' AND tt.timeTableLabelId='$timeTableLabelId' ".$roleEmployeeId;
    $where = " AND att.classId = '$degreeId' ";
    if($startDate!='' && $endDate!='') {
       $where .= " AND att.fromDate >= '$startDate' AND att.toDate <= '$endDate' ";
    }

    $showSubjectG ='';
    if($subjectId!='all') {
       $where .= " AND att.subjectId IN ($subjectId) ";
       $showSubjectG = " AND su.subjectId IN ($subjectId) ";
       $studentCondition .= " AND  tt.subjectId IN ($subjectId) ";
    }
    else {
       $filterType  = " DISTINCT su.subjectTypeId, su.subjectId, su.subjectName, su.subjectCode, st.subjectTypeName, c.classId ";
       $orderByType = " classId, subjectTypeId, subjectCode ";
       $groupByType ='';
       $cond = " AND c.classId = '$degreeId' AND su.hasAttendance = 1 ".$showSubjectG." ".$showGroupG." ".$roleEmployeeId;         
       $tSubjectArray =  $studentReportManager->getAllSubjectAndSubjectTypes($cond, $filterType, $groupByType,  $orderByType);
       $ttSubjectId=0;
       for($i=0;$i<count($tSubjectArray); $i++) {
         $ttSubjectId .=",".$tSubjectArray[$i]['subjectId'];
       }
       $where .= " AND att.subjectId IN ($ttSubjectId) ";
       $showSubjectG = " AND su.subjectId IN ($ttSubjectId) ";
       $studentCondition .= " AND  tt.subjectId IN ($ttSubjectId) ";
    }

    $showGroupG ='';
    if($groupId!='all') {
       $where .= " AND att.groupId IN ($groupId) ";
       $showGroupG = " AND g.groupId IN ($groupId) ";
       $studentCondition .= " AND  tt.groupId IN ($groupId) ";
    }

    $consolidated='1';
    $orderBySubject = " classId, studentId, subjectTypeId, subjectCode  ";
    /*
    if($consolidatedId=='0') {
      $consolidated='';
      $orderBySubject = " , groupId ";
    }
    */


    // Findout Student List
    $studentArray =  $studentReportManager->getClasswiseStudent($studentCondition,$orderBy);
    $cnt = count($studentArray);
    $ffStudentId = 0;
    for($i=0; $i<$cnt; $i++) {
      $ffStudentId .= ",".$studentArray[$i]['studentId'];
    }


    // Fetch Employee Teaching Subject List
    $employeeList1 = employeeSubjectList($degreeId,$timeTableLabelId);


    // Fetch Table Heading
    $tableHead = tableHeadingList($degreeId,$showSubjectG,$showGroupG,$consolidated);

    $csvData = $search."\n".$tableHead;

    // Findout Student Attendance
    $where .= " AND att.studentId IN ($ffStudentId) ".$conditionEmployee;
    $studentAttendanceList = CommonQueryManager::getInstance()->getStudentAttendanceReport($where,$orderBySubject,$consolidated);



    $subjectBlankList = '';
    for($j=0;$j<count($subjectArray);$j++) {
       $subjectBlankList .= ",";
    }


    for($i=0;$i<$cnt;$i++) {
       $srNo = ($i+1);

       $studentId = $studentArray[$i]['studentId'];
       $csvData .= parseCSVComments($srNo).",".parseCSVComments($studentArray[$i]['rollNo']);
       $csvData .= ",".parseCSVComments($studentArray[$i]['universityRollNo']).",".parseCSVComments($studentArray[$i]['studentName']);

       // Findout Student Attendance
       $find=-1;
       for($k=0;$k<count($studentAttendanceList);$k++) {
         if($studentAttendanceList[$k]['studentId']==$studentId) {
            $find=$k;
            break;
         }
       }

       if($find==-1) {
          $result .= $subjectBlankList;
       }
       else {
		   $attend =0;
		   $dlvr =0;
           for($j=0;$j<count($subjectArray);$j++) {
                  $ttSubjectId = $subjectArray[$j]['subjectId'];
                  $att = NOT_APPLICABLE_STRING;
                  if($studentAttendanceList[$k]['studentId'] == $studentId &&  $studentAttendanceList[$k]['subjectId'] == $ttSubjectId) {
                       $attendance = number_format($studentAttendanceList[$k]['attended'],0);
                       $delivered = number_format($studentAttendanceList[$k]['delivered'],0);
                       $leaveTaken = number_format($studentAttendanceList[$k]['leaveTaken'],0);
                       $per1 = round($studentAttendanceList[$k]['per'],2);  //With Duty Leave Percentage Calculate
                       $per2 = round($studentAttendanceList[$k]['per1'],2); //With out Duty Leave Percentage Calculate
                       if($incDutyLeave==1) {
                         $per = $per1;
                       }
                       else {
                         $per = $per2;
                       }

                       if($reportType==1) {
                          $msg = $per;
                       }
                       else if($reportType==2) {
                          if($delivered=='0') {
                            $msg = NOT_APPLICABLE_STRING;
                          }
                          if($incDutyLeave==1) {
                             $msg = "($attendance/$delivered) ($leaveTaken)  ($per)";
                          }
                          else {
                             $msg = "($attendance/$delivered) ($per)";
                          }
                       }

                       if($average == 1 && $per > $percentage) {
                          $att = $msg;
                       }
                       else if($average == 2 && $per < $percentage) {
                          $att = $msg;
                       }
                       else if($average == 3 && $per == $percentage) {
                           $att = $msg;
                       }
                       else {
                          if($incAll == 0) {
                            $att = NOT_APPLICABLE_STRING;
                          }
                          else {
                             $att = $msg;
                          }
                       }
                       $k++;
					   $attend += $attendance + $leaveTaken ;
					   $dlvr += $delivered;
                  }
                  $csvData .= ",".parseCSVComments($att);
             }
       }
	   if($dlvr==0 || $dlvr=='') {
 	     $agrigatePercentage = 0;
	   }
	   else {
	     $agrigatePercentage = ($attend/$dlvr) * 100;
	   }
	   //$agrigatePercentage = ($attend/$dlvr) * 100;
	   $agrigatePercentage = round($agrigatePercentage,2);
	   if($agrigatePercentage == ''){
		   $agrigatePercentage = NOT_APPLICABLE_STRING;
	   }
	   $csvData .= ",".parseCSVComments($agrigatePercentage);
       $csvData .= "\n";
    }


    if($cnt==0 ) {
      $result .= ",,,No Data Found";
    }

    UtilityManager::makeCSV($csvData,'AttendancePercentageWiseReport.csv');

die;


    function tableHeadingList($degreeId,$showSubjectG,$showGroupG,$consolidated) {

       global $studentReportManager;
       global $subjectArray;
       global $roleEmployeeId;
       global $reportManager;

/*     $filterType= " DISTINCT c.classId,su.subjectTypeId,st.subjectTypeName AS subjectTypeName,
                            COUNT(DISTINCT st.subjectTypeName) AS cnt, COUNT(DISTINCT su.subjectName) AS cnt1";
       $groupByType = "GROUP BY c.classId, su.subjectTypeId";
       $orderByType = " classId, subjectTypeId";
       $cond = " AND c.classId = '$degreeId' AND su.hasAttendance = 1 ".$showSubjectG." ".$showGroupG." ".$roleEmployeeId;
       $recordArrayType1 =  $studentReportManager->getAllSubjectAndSubjectTypes($cond, $filterType, $groupByType, $orderByType);
*/       
       $filterType= " DISTINCT c.classId,su.subjectTypeId,st.subjectTypeName AS subjectTypeName";
       $orderByType = " classId, subjectTypeId ";
       $groupByType = "";  
       $cond = " AND c.classId = '$degreeId' AND su.hasAttendance = 1 ".$showSubjectG." ".$showGroupG." ".$roleEmployeeId;         
       $recordArrayType1 =  $studentReportManager->getAllSubjectAndSubjectTypes($cond, $filterType, $groupByType,  $orderByType);
       
       
       $filterType  = " DISTINCT su.subjectTypeId, su.subjectId, su.subjectName, su.subjectCode, st.subjectTypeName, c.classId ";
       $orderByType = " classId, subjectTypeId, subjectCode ";
       $groupByType ='';
       $cond = " AND c.classId = '$degreeId' AND su.hasAttendance = 1 ".$showSubjectG." ".$showGroupG." ".$roleEmployeeId;         
       $subjectArray =  $studentReportManager->getAllSubjectAndSubjectTypes($cond, $filterType, $groupByType,  $orderByType);
       
       for($i=0;$i<count($recordArrayType1);$i++) {    
         $recordArrayType1[$i]['cnt']  = 0;  
         $recordArrayType1[$i]['cnt1'] = 0;  
         for($j=0;$j<count($subjectArray);$j++) {  
           if($subjectArray[$j]['subjectTypeId']==$recordArrayType1[$i]['subjectTypeId']) {
              $recordArrayType1[$i]['cnt'] = 1;  
              $recordArrayType1[$i]['cnt1']++; 
           }     
         }
       }


       $result = "#,Roll No.,Univ. Roll No.,Student Name";

       for($i=0;$i<count($recordArrayType1);$i++) {
          $colspan='';
          $subjectTypeName = $recordArrayType1[$i]['subjectTypeName'];
          $val = $recordArrayType1[$i]['cnt1'];
          $result .=",".parseCSVComments($subjectTypeName);
          if($recordArrayType1[$i]['cnt1']>=2) {
            for($col=1;$col<$val;$col++) {
              $result .=",";
            }
          }
       }
	   	$result .=','."Total %age";
		$result .= "\n";

  /*   $filterType  = " DISTINCT su.subjectTypeId, su.subjectId, su.subjectName, su.subjectCode, st.subjectTypeName, c.classId ";
       $orderByType = " classId, subjectTypeId, subjectCode ";
       $groupByType ='';
       $cond = " AND c.classId = '$degreeId' AND su.hasAttendance = 1 ".$showSubjectG." ".$showGroupG." ".$roleEmployeeId;
       $subjectArray =  $studentReportManager->getAllSubjectAndSubjectTypes($cond, $filterType, $groupByType,  $orderByType);
*/       
	   $count = count($subjectArray);
       $result .= ",,,";
         for($i=0;$i<$count;$i++) {
           $subjectCode = $subjectArray[$i]['subjectCode'];
           $result .=",".parseCSVComments($subjectCode);
         }
        $result .= "\n";

       return $result;
    }


    function employeeSubjectList($classId,$timeTableLabelId) {

       global $commonQueryManager;
       global $reportManager;

       $empCondition = " AND g.classId = $classId  AND tt.timeTableLabelId=$timeTableLabelId ";
       $employeeArray = $commonQueryManager->getEmployeeTeachSubjectList($empCondition);
       $result = "<table width='100%' border='1' class='reportTableBorder'>
                                  <tr>
                                     <td width='2%' class='dataFont'><b>#</b></td>
                                     <td width='10%' class='dataFont'><b>Subject Code</b></td>
                                     <td width='25%' class='dataFont'><b>Subject Name</b></td>
                                     <td width='65%' class='dataFont'><b>Teacher</b></td>
                                  </tr>";
       if(count($employeeArray)>0) {
          for($i=0;$i<count($employeeArray);$i++) {
              $result .= "<tr>
                                    <td class='dataFont'>".($i+1)."</nobr></td>
                                    <td class='dataFont'>".$employeeArray[$i]['subjectCode']."</td>
                                    <td class='dataFont'>".$employeeArray[$i]['subjectName']."</td>
                                    <td width='98%' class='dataFont'>".$employeeArray[$i]['employeeName']."</td>
                                 </tr>";
          }
       }
       else {
          $result .= "<tr><td class='dataFont' align='center' colspan='4'>No Data Found</td></tr>";
       }
       $result .= "</table>";

       return $result;
    }

?>