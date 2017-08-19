<?php
//--------------------------------------------------------
//This file returns the array of of Percentage Wise attendance report
// Author :Aditi Miglani
// Created on : 8-Nov-2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
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
    
    require_once(MODEL_PATH . "/PercentageWiseReportManager.inc.php");
    $percentageWiseReportManager = PercentageWiseReportManager::getInstance();
    

    //paging
    $page = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records  = ($page-1)* RECORDS_PER_PAGE;
    $limit   = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

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
    $incMedicalLeave = add_slashes($REQUEST_DATA['incMedicalLeave']);
    $lowerMedicalLimit=$sessionHandler->getSessionVariable('MEDICAL_LEAVE_CALCULATION_LIMIT');
	$higherMedicalLimit=$sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');

    if($incDutyLeave=='') {
       $incDutyLeave=0;
    }
    
    if($incMedicalLeave=='') {
       $incMedicalLeave=0;
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
    $tableData = tableHeadingList($degreeId,$showSubjectG,$showGroupG,$consolidated);



    // Findout Student Attendance
    $where .= " AND att.studentId IN ($ffStudentId) ".$conditionEmployee;
    $studentAttendanceList = $percentageWiseReportManager->getStudentAttendanceReport($where,$orderBySubject,$consolidated);

    $subjectBlankList = '';
    for($j=0;$j<count($subjectArray);$j++) {
       $subjectBlankList .= "<td valign='top' class='padding_top' align='center'>".NOT_APPLICABLE_STRING."</td>";
    }

    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
       $srNo = ($i+1);
       $bg = $bg =='trow0' ? 'trow1' : 'trow0';

       $studentId = $studentArray[$i]['studentId'];
       $result = "<tr class='$bg'>
                        <td valign='top' class='padding_top' align='left'>".$srNo."</td>
                        <td valign='top' class='padding_top' align='left'>".$studentArray[$i]['rollNo']."</td>
                        <td valign='top' class='padding_top' align='left'>".$studentArray[$i]['universityRollNo']."</td>
                        <td valign='top' class='padding_top' align='left'>".$studentArray[$i]['studentName']."</td>";

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
                       $medicalLeaveTaken = number_format($studentAttendanceList[$k]['medicalLeaveTaken'],0);
                       $perDuty = round($studentAttendanceList[$k]['per'],2);  //With Duty Leave Percentage Calculate
                       $perAtt = round($studentAttendanceList[$k]['per1'],2); //With out Duty Leave Percentage Calculate
					   $perMed=0;
					   
					   //if duty leave is checked and medical leave is not
                       if($incDutyLeave==1 && $incMedicalLeave!=1) {
                       	$per = $perDuty;
                       }
//echo $perDuty."  ".$medicalLeaveTaken."  ".$attendance."  ".$leaveTaken."  ".$m."  ".$delivered; die;                       
                       //if duty leave is not checked and medical leave is checked
                       if($incDutyLeave!=1 && $incMedicalLeave==1) { 
                       	if($groupId == 'all') {
	                    		if($perDuty >= $lowerMedicalLimit && $perDuty <= $higherMedicalLimit && $medicalLeaveTaken>0 ){ 
	                    			for($m=1;$m<=$medicalLeaveTaken;$m++){
	                    			  if($delivered>0) {	
									    $perMed=(($attendance+$leaveTaken+$m)/$delivered)*100;
									    if($perMed>=$higherMedicalLimit){
										  break;
									    } 
									  }
	                    			}
	                    			$medicalLeaveTaken=$m;
	                    			$per=$perMed;
	                    		}
	                    		else{
	                    			$medicalLeaveTaken=0;
	                    			$per=$perAtt;
	                    		}
	                       }
	                       else{
	                       	$medicalLeaveTaken=0;
	                       	$per=$perAtt;
                		  }
                       }	
                       //if both duty and medical leave are checked
                       if($incDutyLeave==1 && $incMedicalLeave==1){
                       	if($groupId == 'all') {
	                    		if($perDuty >= $lowerMedicalLimit && $perDuty <= $higherMedicalLimit && $medicalLeaveTaken>0){ 
	                    			for($m=1;$m<=$medicalLeaveTaken;$m++){ 
	                    			  if($delivered>0) {	
									    $perMed=(($attendance+$leaveTaken+$m)/$delivered)*100; 
									    if($perMed>=$higherMedicalLimit){
										  break;
									    }
									  }
	                    			} 
	                    			$medicalLeaveTaken=$m;
	                    			$per=$perMed;
	                    		}
	                    		else{
	                    			$medicalLeaveTaken=0;
	                    			$per=$perDuty;
	                    		}
	                       }
	                       else{
	                       	$medicalLeaveTaken=0;
	                       	$per=$perDuty;
                		   }
                       }
                       //if neither duty nor medical leave is checked
                       if($incDutyLeave!=1 && $incMedicalLeave!=1){
                       	$per = $perAtt;
                       }
		               
                       if($reportType==1) {
                          $msg = $per;
                       }
                       else if($reportType==2) { 
                          if($delivered==0) {
                            $msg = NOT_APPLICABLE_STRING; 
                          }
                          
                          //if duty leave is checked and medical leave is not
		                   if($incDutyLeave==1 && $incMedicalLeave!=1) {
		                   	$msg = "$attendance/$delivered<br>DL- $leaveTaken<br>$per";
		                   }
		                   //if duty leave is not checked and medical leave is checked
		                   if($incDutyLeave!=1 && $incMedicalLeave==1) {
		                   	if($groupId == 'all') {
                             	$msg = "$attendance/$delivered<br>DL- $leaveTaken<br>ML- $medicalLeaveTaken<br>$per";
                             }
                             else{
		                   		$msg = "$attendance/$delivered<br>$per";
		                   	}
		                   }
		                   //if both duty and medical leave are checked
		                   if($incDutyLeave==1 && $incMedicalLeave==1){
		                   	if($groupId == 'all') {
                             	$msg = "$attendance/$delivered<br>DL- $leaveTaken<br>ML- $medicalLeaveTaken<br>$per";
                             }
                             else{
		                   		$msg = "$attendance/$delivered<br>DL- $leaveTaken<br>$per";
		                   	}
		                   }
		                   //if neither duty nor medical leave is checked
		                   if($incDutyLeave!=1 && $incMedicalLeave!=1){
		                   		$msg = "$attendance/$delivered<br>$per";
		                   }
		                   
                       }
                       if($average == 1 && $per > $percentage) {
                          $att = $msg;
                       }
                       else if($average == 2 && $per < $percentage) {
						  $att = "<b><font color = red><u>".$msg."</u></font></b>";
                       }
                       else if($average == 3 && $per == $percentage) {
                           $att = $msg;
                       }
                       else {
                          if($incAll == 0) {
                            $att = NOT_APPLICABLE_STRING; 
                          }
                          else if($incAll == 1){
							 $att = $msg;
							 
                          }
                       }
	                  //if duty leave is checked and medical leave is not
	                   if($incDutyLeave==1 && $incMedicalLeave!=1) {
	                   	$attend += $attendance + $leaveTaken;
	                   	$dlvr += $delivered;
	                   }
	                   //if duty leave is not checked and medical leave is checked
	                   if($incDutyLeave!=1 && $incMedicalLeave==1) {
	                   	$attend += $attendance + $leaveTaken + $medicalLeaveTaken;
	                   	$dlvr += $delivered;
	                   }
	                   //if both duty and medical leave are checked
	                   if($incDutyLeave==1 && $incMedicalLeave==1){
	                   	$attend += $attendance + $leaveTaken + $medicalLeaveTaken;
	                   	$dlvr += $delivered;
	                   }
	                   //if neither duty nor medical leave is checked
	                   if($incDutyLeave!=1 && $incMedicalLeave!=1){
	                   	$attend += $attendance;
					   	$dlvr += $delivered;
                   		}
                        $k++;  
                  }
                  $result .= "<td valign='top' class='padding_top' align='center'>".$att."</td>";
             }
       }
	   if($dlvr==0 || $dlvr=='') {
 	     $aggregatePercentage = 0;
	   }
	   else {
	     $aggregatePercentage = ($attend/$dlvr) * 100;
	   }
	   $aggregatePercentage = round($aggregatePercentage,2);
	   if($aggregatePercentage == ''){
		   $aggregatePercentage = NOT_APPLICABLE_STRING; 
	   }
	   $result .= "<td valign='top' class='padding_top' align='center' valign='middle'>".$aggregatePercentage."</td>";
       $result .= "</tr>";
	   $valueArray[] = $result;
    }//close i for loop


    if(is_array($valueArray) && count($valueArray)>0 ) {
      $tableData .= join($valueArray);
    }
    else {
      $tableData .= "<tr><td align='center' colspan='50'>No Data Found</tr>";
    }

    echo $employeeList1."!~~!~~!".$tableData;
die;


    function tableHeadingList($degreeId,$showSubjectG,$showGroupG,$consolidated) {

       global $studentReportManager;
       global $subjectArray;
       global $roleEmployeeId;

      /* $filterType= " DISTINCT c.classId,su.subjectTypeId,st.subjectTypeName AS subjectTypeName,
                            COUNT(DISTINCT st.subjectTypeName) AS cnt, COUNT(DISTINCT su.subjectName) AS cnt1";
       $groupByType = "GROUP BY c.classId, su.subjectTypeId";
       $orderByType = " classId, subjectTypeId";
       $cond = "AND c.classId = '$degreeId' AND su.hasAttendance = 1 ".$showSubjectG." ".$showGroupG." ".$roleEmployeeId;
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
       
       $result = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                   <tr class='rowheading'>
                     <td width='2%'  valign='middle' rowspan='2' class='searchhead_text' ><b>#</b></td>
                     <td width='8%'  valign='middle' rowspan='2' class='searchhead_text' align='left'><strong>Roll No.</strong></td>
                     <td width='8%'  valign='middle' rowspan='2' class='searchhead_text' align='left'><strong>Univ. Roll No.</strong></td>
                     <td width='10%' valign='middle' rowspan='2' class='searchhead_text' align='left'><strong>Student Name</strong></td>";

       for($i=0;$i<count($recordArrayType1);$i++) {
          $colspan='';
          $subjectTypeName = $recordArrayType1[$i]['subjectTypeName'];
          $val = $recordArrayType1[$i]['cnt1'];
          if($recordArrayType1[$i]['cnt1']>=2) {
            $colspan = "colspan='$val'";
          }
          $result .= "<td width='10%' $colspan valign='middle' class='searchhead_text' align='center'><strong>$subjectTypeName</strong></td>";
       }
		 $result .="<td width='10%' valign='middle' rowspan='2' class='searchhead_text' align='center'><strong>Total %age </strong></td>";
         $result .= "</tr>";

       
       
       $result .= " <tr class='rowheading'> ";
         for($i=0;$i<count($subjectArray);$i++) {
           $subjectCode = $subjectArray[$i]['subjectCode'];
           $result .= "<td width='5%' valign='middle' class='searchhead_text' align='center'><strong>$subjectCode</strong></td>";
         }
       $result .= "</tr>";

       return $result;
    }


    function employeeSubjectList($classId,$timeTableLabelId) {

       global $commonQueryManager;

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
