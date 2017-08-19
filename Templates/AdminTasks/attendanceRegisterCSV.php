<?php 
//This file is used as csv version for for Attendance Register CSV
//
// Author :Parveen Sharma
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------- 
?>
<script>
    //alert(location.search);
    var str=unescape(location.search);
    var strArray=str.split('heading=');
    var len=strArray.length;
    var heading=strArray[1];
</script>
<?php    
    ini_set('MEMORY_LIMIT','10000M'); 
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
    
    $conditionEmployee = '';     
    if($roleId==2) {    
      $employeeId=$sessionHandler->getSessionVariable('EmployeeId');
      $conditionEmployee = " AND tt.employeeId = '$employeeId' ";
    }

    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportManager = StudentReportsManager::getInstance();
     
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance(); 
    
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();
    
    require_once(MODEL_PATH . "/AttendanceRegisterManager.inc.php");
    $attendanceRegisterManager = AttendanceRegisterManager::getInstance();
    
    global $sessionHandler;
    $sessionId = $sessionHandler->getSessionVariable('SessionId');
    $instituteId = $sessionHandler->getSessionVariable('InstituteId'); 
  
  
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
    
    $dutyLeave = add_slashes($REQUEST_DATA['dutyLeave']);  
    $medicalLeave = add_slashes($REQUEST_DATA['medicalLeave']);       
    $timeTableLabelId = add_slashes($REQUEST_DATA['timeTable']);  
    $fromDate = add_slashes($REQUEST_DATA['fromDate']);
    //$toDate= add_slashes($REQUEST_DATA['toDate']);
    $degree = add_slashes($REQUEST_DATA['degree']);
    $subjectId = add_slashes($REQUEST_DATA['subjectId']);  
    $groupId = add_slashes($REQUEST_DATA['groupId']);  
    $nosCol= add_slashes($REQUEST_DATA['nosCol']); 
    $consolidatedId = add_slashes($REQUEST_DATA['consolidatedId']);
    $gt =0;
 
    $absentPrefix= trim($REQUEST_DATA['absentPrefix']);
    $dutyLeavePrefix = trim($REQUEST_DATA['dutyLeavePrefix']);
    $medicalLeavePrefix = trim($REQUEST_DATA['medicalLeavePrefix']);
    $notMemberPrefix= trim($REQUEST_DATA['notMemberPrefix']);
    
    $groupAttend = array();     
    
    
    if($timeTableLabelId=='') {
      $timeTableLabelId = 0;  
    }
   
    if($degree=='') {
      $degree = 0;  
    }
   
    if($subjectId=='') {
      $subjectId = 0;  
    }
    
    if($groupId=='') {
      $groupId = 0;  
    }
   
    if($nosCol=='') {
      $nosCol=20;  
    }
    
    if($dutyLeave=='') {
      $dutyLeave=0; 
    }
    
    if($medicalLeave=='') {
      $medicalLeave=0; 
    }
    
    if($consolidatedId=='') {
      $consolidatedId=0; 
    }
  
  
     // Fetch Employee Name
    $employeeName='';
    $tableName = " employee e, `group` g,  ".TIME_TABLE_TABLE."  tt ";
    $fieldsName ="GROUP_CONCAT(DISTINCT employeeName,' (',employeeCode,')' SEPARATOR ', ') AS employeeName";
    $empCondition = " WHERE 
                            e.employeeId=tt.employeeId AND   
                            tt.groupId=g.groupId AND  
                            tt.toDate IS NULL AND  
                            g.classId=$degree  AND tt.subjectId = $subjectId AND tt.timeTableLabelId=$timeTableLabelId AND
                            tt.sessionId=$sessionId AND tt.instituteId=$instituteId AND tt.groupId = $groupId
                      GROUP BY 
                            g.classId, tt.subjectId";  
    $employeeArray = $studentManager->getSingleField($tableName, $fieldsName, $empCondition);
    $employeeName = $employeeArray[0]['employeeName'];
    if($employeeName=='') {
      $employeeName = NOT_APPLICABLE_STRING;  
    }
    
    
    // Findout Time Table Name
    $timeNameArray = $studentManager->getSingleField('time_table_labels', 'labelName', "WHERE timeTableLabelId  = $timeTableLabelId");
    $timeTableName = $timeNameArray[0]['labelName'];
    if($timeTableName=='') {
      $timeTableName = NOT_APPLICABLE_STRING;  
    }
   
    // Findout Class Name
    if($degree != '') {   
      $classNameArray = $studentManager->getSingleField('class', 'className', "WHERE classId  = $degree");
      $className = $classNameArray[0]['className'];
      $className2 = str_replace("-",' ',$className);
    }
    
    if($subjectId != '') {   
      $classNameArray = $studentManager->getSingleField('subject', "CONCAT(subjectName,' (',subjectCode,')') AS subjectName", "WHERE subjectId  = $subjectId");
      $className = $classNameArray[0]['subjectName'];
      $subjectName = $className; 
    }
    
    if($groupId != '') {   
      $classNameArray = $studentManager->getSingleField('`group`', 'groupName', "WHERE groupId  = $groupId");
      $className = $classNameArray[0]['groupName'];
      $groupName = $className;
    }
    
    $heading = add_slashes($REQUEST_DATA['heading']); 
    $heading = urldecode($heading);
  
    $search ='';
    $search1 ='';
    $search2 ='';                             
    $search3 ='';
    
    $search1  = "Time Table,".parseCSVComments($timeTableName)."\n";
    $search1 .= "Degree,".parseCSVComments($className2).",Group,".parseCSVComments($groupName)."\n";
    $search1 .= "Subject,".parseCSVComments($subjectName)."\nTeacher,".parseCSVComments($employeeName)."\n"; 
    $search2 .= "Attendance Upto,".parseCSVComments(UtilityManager::formatDate($fromDate))."\n";
    //,To,".parseCSVComments(UtilityManager::formatDate($toDate))."\n";
    $search3 .= parseCSVComments($heading);
    $search3 .= "\n";
    $search = $search1.$search2.$search3; 

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
  
    
    // Findout Student List
    $studentCondition = " AND c.classId = $degree AND tt.subjectId = $subjectId AND tt.groupId = $groupId 
                          AND tt.timeTableLabelId='".$timeTableLabelId."' ".$conditionEmployee;
    $studentArray =  $studentReportManager->getClasswiseStudent($studentCondition,$orderBy);  
    $cnt = count($studentArray);
    $ffStudentId = 0;    
    for($i=0; $i<$cnt; $i++) {
      $ffStudentId .= ",".$studentArray[$i]['studentId'];  
    }
 
 
    // Findout Group Type List
    $groupTypeName = "";
    $groupTypeId="";
    $groupTypeArray = $studentManager->getSingleField('`group`', 'groupTypeId', "WHERE groupId  = $groupId");
    $groupTypeId = $groupTypeArray[0]['groupTypeId'];


    // Fetch Student Attendance 
    $studentCondition = " AND att.classId = $degree AND att.subjectId = $subjectId  AND att.studentId IN ($ffStudentId)
                          AND (att.toDate <=  '$fromDate')"; 
    $groupCondition   = " AND att.groupId = $groupId ";                                               
    if($consolidatedId==1) {
       if($groupTypeId==1  || $groupTypeId==3) {
         $groupCondition = " AND gt.groupTypeId IN (1,3) ";
       }  
    }
    $studentCondition .= $groupCondition;
    $attendanceDateList   =  $attendanceRegisterManager->getStudentPercentageAttendanceReport($studentCondition,'','','',1);                           
    $studentAttendanceList =  $attendanceRegisterManager->getStudentPercentageAttendanceReport($studentCondition);
    
 
 /* 
    // Fetch Student Old Attendance 
    $studentCondition     = " AND att.classId = $degree AND att.subjectId = $subjectId     
                              AND (att.fromDate < '$fromDate') AND att.studentId IN ($ffStudentId) ".$groupCondition; 
    $studentOldAttendanceList =  $commonQueryManager->getStudentOldAttendanceReport($studentCondition);
 */   
    
    $csvData  = '';
    $csvData .= $search;
    $csvData .= getTableHeading($rowspan,$colspan,$attendanceDateList);
    
    
    $result ='';
    for($i=0;$i<$cnt;$i++) {
       $srNo = ($i+1);
       //$bg = $bg =='trow0' ? 'trow1' : 'trow0';    
       $studentId = $studentArray[$i]['studentId'];

       $groupAttend = array();
       
       $groupAttend[0]['groupTypeId']=1;
       $groupAttend[0]['groupAttend']=0;
       $groupAttend[0]['groupDelivered']=0; 
       
       $groupAttend[1]['groupTypeId']=2;
       $groupAttend[1]['groupAttend']=0;
       $groupAttend[1]['groupDelivered']=0; 
       
       $groupAttend[2]['groupTypeId']=3;
       $groupAttend[2]['groupAttend']=0;
       $groupAttend[2]['groupDelivered']=0; 
       
       $groupAttend[3]['groupTypeId']=4;
       $groupAttend[3]['groupAttend']=0;
       $groupAttend[3]['groupDelivered']=0; 
       
       $groupAttend[4]['groupTypeId']=5;
       $groupAttend[4]['groupAttend']=0;
       $groupAttend[4]['groupDelivered']=0; 
       
       
       $attended = "";
       $delivered = "";
       // Findout Old Student Attendance
      /* 
       $jj=-1;
       for($k=0;$k<count($studentOldAttendanceList);$k++) { 
         $compStudentId= $studentOldAttendanceList[$k]['studentId'];  
         if($studentId == $compStudentId) {
             $attended=$studentOldAttendanceList[$k]['lectureAttended'];
             if($dutyLeave==1) {
               $attended=$attended+$studentOldAttendanceList[$k]['leaveTaken'];  
             }
             $delivered=$studentOldAttendanceList[$k]['lectureDelivered'];
             break;  
         }
       }
      */ 
      
       // Findout Student Attendance
       $jj=-1;
       for($k=0;$k<count($studentAttendanceList);$k++) {  
         if($studentAttendanceList[$k]['studentId']==$studentId) {
            $jj=$k;  
            break;  
         }
       }
       
       $total = "0";
       $per   = "0";
       $csvData .= parseCSVComments($srNo).",".parseCSVComments($studentArray[$i]['rollNo']).",";  
       $csvData .= parseCSVComments($studentArray[$i]['universityRollNo']).",".parseCSVComments($studentArray[$i]['studentName']);  
      
       $find=0; 
       if($jj!=-1) {
           $dif=0;
           $dtCount = count($attendanceDateList); 
           if(count($attendanceDateList)>$nosCol) {
              $dtCount=$nosCol; 
              $dif=0;
           }
           else {
              $dif = $nosCol - count($attendanceDateList);
           }
           for($k=0;$k<$dtCount;$k++) {
              $periodId= $attendanceDateList[$k]['periodId'];
              $fromDate= $attendanceDateList[$k]['fromDate'];
              $toDate= $attendanceDateList[$k]['toDate'];
              $tgroupId= $attendanceDateList[$k]['groupId'];
              $tgroupTypeId= $attendanceDateList[$k]['groupTypeId'];
              
              $compStudentId= $studentAttendanceList[$jj]['studentId']; 
              $compPeriodId= $studentAttendanceList[$jj]['periodId'];
              $compFromDate= $studentAttendanceList[$jj]['fromDate'];
              $compToDate= $studentAttendanceList[$jj]['toDate'];
              $isMemberOfClass= $studentAttendanceList[$jj]['isMemberOfClass']; 
              $compGroupId= $studentAttendanceList[$jj]['groupId']; 
                                                                                      
              if($tgroupId == $compGroupId && $studentId == $compStudentId && $periodId == $compPeriodId && $fromDate==$compFromDate &&  $toDate == $compToDate && $isMemberOfClass == 1) {
                 $attended=$attended+$studentAttendanceList[$jj]['lectureAttended'];
                 $delivered=$delivered+$studentAttendanceList[$jj]['lectureDelivered'];
                 
                 for($ii=0;$ii<count($groupAttend);$ii++) {
                    if($groupAttend[$ii]['groupTypeId']==$tgroupTypeId) {
                       $groupAttend[$ii]['groupAttend'] = $groupAttend[$ii]['groupAttend']+$studentAttendanceList[$jj]['lectureAttended']; 
                       $groupAttend[$ii]['groupDelivered'] = $groupAttend[$ii]['groupDelivered']+$studentAttendanceList[$jj]['lectureDelivered'];
                       $temp = $groupAttend[$ii]['groupAttend'];
                       break;  
                    } 
                 }
                 
                 if($studentAttendanceList[$jj]['lectureAttended']==0) {
                   $leaveTaken=$studentAttendanceList[$jj]['leaveTaken'];
                   $medicalLeaveTaken=$studentAttendanceList[$jj]['medicalLeaveTaken'];
                 
                 //if duty leave is checked and medical is not
                 	if($dutyLeave==1 && $medicalLeave!=1)
                 	{
                 		if($leaveTaken==1){
                 			$csvData .= ",".parseCSVComments($dutyLeavePrefix);  
                      		$attended=$attended+1;
                      		$groupAttend[$ii]['groupAttend'] = $groupAttend[$ii]['groupAttend']+1; 
                 		}
                 		else {
                      		$csvData .= ",".parseCSVComments($absentPrefix); 
                    	}
                 	}
                 	//if duty leave is NOT checked and medical is checked
                 	if($dutyLeave!=1 && $medicalLeave==1)
                 	{
                 		//consolidated is checked
                 		if($consolidatedId==1 && $medicalLeaveTaken==1  ){
		             		$csvData .= ",".parseCSVComments($medicalLeavePrefix);  
                      		$attended=$attended+1;
                      		$groupAttend[$ii]['groupAttend'] = $groupAttend[$ii]['groupAttend']+1;  
				        }
		             	else {
		                  		$csvData .= ",".parseCSVComments($absentPrefix);
		                }
                 	}
                 	//if both duty leave and medical leave is checked
                 	if($dutyLeave==1 && $medicalLeave==1)
                 	{
                 		if($leaveTaken==1) {
                 			$csvData .= ",".parseCSVComments($dutyLeavePrefix);  
                      		$attended=$attended+1;
                      		$groupAttend[$ii]['groupAttend'] = $groupAttend[$ii]['groupAttend']+1; 
                 		}
                 		if($consolidatedId==1 && $medicalLeaveTaken==1 ) {
		             		$csvData .= ",".parseCSVComments($medicalLeavePrefix);  
                      		$attended=$attended+1;
                      		$groupAttend[$ii]['groupAttend'] = $groupAttend[$ii]['groupAttend']+1;  
	             		}
	             		//if both the above conditions are false then
	             		else if($leaveTaken!=1 && ($consolidatedId==1 && $medicalLeaveTaken==1)==0){
	             			$csvData .= ",".parseCSVComments($absentPrefix);
	             		}
	             		
                 	}
                 	//if neither duty leave nor medical leave is checked
                 	if($dutyLeave!=1 && $medicalLeave!=1){
                 		$csvData .= ",".parseCSVComments($absentPrefix);
                 	}
                 }
                 else {
                   $csvData .= ",".parseCSVComments($temp);
                 }
                 $jj++;
              }
              else if($tgroupId == $compGroupId && $studentId == $compStudentId && $periodId == $compPeriodId && $fromDate==$compFromDate &&  $toDate == $compToDate && $isMemberOfClass == -1 ) { 
                 $csvData .= ",".parseCSVComments($notMemberPrefix);
                 $jj++;
              }
              else {
                 $csvData .= ",".parseCSVComments($notMemberPrefix);
              }
              $find=1;
           } 
       }
               
       if($find==0) {
         for($k=0;$k<$dtCount;$k++) {
           $csvData .= ",".parseCSVComments($notMemberPrefix);
         } 
       }
       for($kk=0;$kk<$dif;$kk++) {
         $csvData .= ",".parseCSVComments("");
       } 
       
       if($dtCount!=0) {  
           if($consolidatedId==1 && $gt==1) {
              $aa = $groupAttend[2]['groupAttend']; 
              $dd = $groupAttend[2]['groupDelivered']; 
              $csvData .= ",".parseCSVComments($aa."/".$dd);
             
              $aa = $groupAttend[0]['groupAttend']; 
              $dd = $groupAttend[0]['groupDelivered']; 
              $csvData .= ",".parseCSVComments($aa."/".$dd);
           }
           
           if($attended=='' && $delivered=='') {
              $csvData .= ",".parseCSVComments(NOT_APPLICABLE_STRING).",".parseCSVComments(NOT_APPLICABLE_STRING);
              $csvData .= "\n";                     
           }              
           else {
              if($delivered==0) {
                $per="0.00";   
              } 
              else {
                $per = round(($attended/$delivered)*100,2);
              }
              $csvData .= ",".parseCSVComments($attended."/".$delivered).",".parseCSVComments($per);
              $csvData .= "\n";
           }   
       }
       else {
          $csvData .= "\n";
       }
       
    }
    
    UtilityManager::makeCSV($csvData,'AttendanceRegisterReport.csv');
    
    die;
    
    
    function getTableHeading($rowspan,$colspan,$attendanceDateList) {
        
        global $nosCol; 
        global $groupAttend;
        global $consolidatedId;
        global $gt;
  
         $csvData1 = "#,Roll No.,URoll No.,Student Name";  
         $dateHeading ="";                    
         $periodNumberHeading ="";
         $groupTypeHeading =""; 
         
         $dif=0;
         $dtCount = count($attendanceDateList); 
         if(count($attendanceDateList)>$nosCol) {
            $dtCount=$nosCol; 
            $dif=0;
         }
         else {
            $dif = $nosCol - count($attendanceDateList);
         }
         
         for($j=0;$j<$dtCount;$j++) {
            $fromDate = $attendanceDateList[$j]['fromDate']; 
            $toDate = $attendanceDateList[$j]['toDate']; 
            $periodNumber = $attendanceDateList[$j]['periodNumber']; 
            $groupTypeId = $attendanceDateList[$j]['groupTypeId'];
            $gType =""; 
            if($groupTypeId=='1') {
                 $gType ="T"; 
                 $gt=1;
            }
            else if($groupTypeId=='2') {
                  $gType ="P"; 
            }
            else if($groupTypeId=='3') {
                 $gType ="L";
                 $gt=1;
            }
            else if($groupTypeId=='4') {
                 $gType ="TR";                  
            }
            else if($groupTypeId=='5') {
                 $gType ="U";                  
            }
            
            if($fromDate==$toDate) {
              $fromArr = explode('-',$fromDate);
              $val = $fromArr[2].'/'.$fromArr[1];   
              $dateHeading .= ",".parseCSVComments($val);
              $periodNumberHeading .= ",".parseCSVComments($periodNumber);
              $groupTypeHeading .= ",".parseCSVComments($gType); 
            }
            else {
              $fromArr = explode('-',$fromDate);
              $toArr = explode('-',$toDate);
              $val  = $fromArr[2].'/'.$fromArr[1];     
              $val1 = $toArr[2].'/'.$toArr[1];     
              $dateHeading .= ",".parseCSVComments($val." to ".$val1);
              $periodNumberHeading .= ",".parseCSVComments(NOT_APPLICABLE_STRING);
              $groupTypeHeading .= ",".parseCSVComments($gType);     
            }
            
            $csvData1 .= ",".parseCSVComments(($j+1));
         }
         for($kk=0;$kk<$dif;$kk++) {
            $csvData1 .= ",".parseCSVComments(($j+1));
            $dateHeading .= ",".parseCSVComments("");
            $periodNumberHeading .= ",".parseCSVComments("");
            $groupTypeHeading .= ",".parseCSVComments("");   
            $j++;   
         }         
         
         if($consolidatedId==1 && $gt==1) {
           $groupTypeHeading .= ",L,T"; 
           
           $csvData1 .= ",Total,Total";
         }
         
         $csvData1 .= ",Total,%age";
         $csvData1 .= "\n";
         
         $csvData1 .= "Date,,,".$dateHeading."\n";
         $csvData1 .= "Period No.,,,".$periodNumberHeading."\n";
         $csvData1 .= "Group Type,,,".$groupTypeHeading."\n";
         
         return $csvData1;                    
    }

?>
