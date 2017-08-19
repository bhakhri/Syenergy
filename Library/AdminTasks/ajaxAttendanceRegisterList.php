<?php
//--------------------------------------------------------
//This file fetch teacher attendance register details
//
// Author :Parveen Sharma
// Created on : 15-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
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
  
  
    //paging
    $page = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records  = ($page-1)* RECORDS_PER_PAGE;
    $limit   = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
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
                          AND (att.toDate <= '$fromDate') "; 
    $groupCondition   = "  AND att.groupId = $groupId ";                                               
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
    
    $rowspan="rowspan='4'";
    echo getTableHeading($rowspan,$colspan,$attendanceDateList);
    
    for($i=0;$i<$cnt;$i++) {
       $srNo = ($i+1);
       $bg = $bg =='trow0' ? 'trow1' : 'trow0';   
       
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
       
       $studentId = $studentArray[$i]['studentId'];

     
       // Findout Old Student Attendance
       $attended = "";
       $delivered = "";
       /* $jj=-1;
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
       $result = "<tr class='$bg'>
                       <td valign='top' class='padding_top' align='left'>".$srNo."</td>  
                       <td valign='top' class='padding_top' align='left'>".$studentArray[$i]['rollNo']."</td>
                       <td valign='top' class='padding_top' align='left'>".$studentArray[$i]['universityRollNo']."</td>
                       <td valign='top' class='padding_top' align='left'>".$studentArray[$i]['studentName']."</td>";
                       
       $find=0;
       //$dtCount =0; 
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
                 			$result .= "<td valign='top' class='padding_top' align='center'>$dutyLeavePrefix</td>";
				            $attended=$attended+1;
				            $groupAttend[$ii]['groupAttend'] = $groupAttend[$ii]['groupAttend']+1; 
                 		}
                 		else {
                      		$result .= "<td valign='top' class='padding_top' align='center'>$absentPrefix</td>";  
                    	}
                 	}
                 	//if duty leave is NOT checked and medical is checked
                 	if($dutyLeave!=1 && $medicalLeave==1)
                 	{
                 		//consolidated is checked
                 		if($consolidatedId==1 && $medicalLeaveTaken==1  ){
		             			$result .= "<td valign='top' class='padding_top' align='center'>$medicalLeavePrefix</td>";
						        $attended=$attended+1;
						        $groupAttend[$ii]['groupAttend'] = $groupAttend[$ii]['groupAttend']+1; 
				        }
		             	else {
		                  		$result .= "<td valign='top' class='padding_top' align='center'>$absentPrefix</td>";  
		                }
                 	}
                 	//if both duty leave and medical leave is checked
                 	if($dutyLeave==1 && $medicalLeave==1)
                 	{
                 		if($leaveTaken==1) {
                 			$result .= "<td valign='top' class='padding_top' align='center'>$dutyLeavePrefix</td>";
				            $attended=$attended+1;
				            $groupAttend[$ii]['groupAttend'] = $groupAttend[$ii]['groupAttend']+1; 
                 		}
                 		if($consolidatedId==1 && $medicalLeaveTaken==1 ) {
		             			$result .= "<td valign='top' class='padding_top' align='center'>$medicalLeavePrefix</td>";
						        $attended=$attended+1;
						        $groupAttend[$ii]['groupAttend'] = $groupAttend[$ii]['groupAttend']+1; 
	             		}
	             		//if both the above conditions are false then
	             		else if($leaveTaken!=1 && ($consolidatedId==1 && $medicalLeaveTaken==1)==0){
	             			$result .= "<td valign='top' class='padding_top' align='center'>$absentPrefix</td>";
	             		}
	             		
                 	}
                 	//if neither duty leave nor medical leave is checked
                 	if($dutyLeave!=1 && $medicalLeave!=1){
                 		$result .= "<td valign='top' class='padding_top' align='center'>$absentPrefix</td>"; 
                 	}
                 }
                 else {
                   //$result .= "<td valign='top' class='padding_top' align='center'>$attended</td>";
                   $result .= "<td valign='top' class='padding_top' align='center'>$temp</td>";
                 }
                 $jj++;
              }
              else if($tgroupId == $compGroupId && $studentId == $compStudentId && $periodId == $compPeriodId && $fromDate==$compFromDate &&  $toDate == $compToDate && $isMemberOfClass == -1 ) { 
                 $result .= "<td valign='top' class='padding_top' align='center'>$notMemberPrefix</td>"; 
                 $jj++;
              }
              else {
                 $result .= "<td valign='top' class='padding_top' align='center'>$notMemberPrefix</td>"; 
              }
              $find=1;
           } 
       }
       
           
       if($find==0) {
         if($dtCount==0) {
            for($k=0;$k<$nosCol;$k++) {    
               $result .= "<td valign='top' class='padding_top' align='center'></td>"; 
            }
         }
         else {
            for($k=0;$k<$dtCount;$k++) {
              $result .= "<td valign='top' class='padding_top' align='center'>".$notMemberPrefix."</td>";   
            } 
         }
       }
       for($kk=0;$kk<$dif;$kk++) {
         $result .= "<td valign='top' class='padding_top' align='center'></td>";
       } 
       
       if($dtCount!=0) {
           if($consolidatedId==1 && $gt==1) {
              $aa = $groupAttend[2]['groupAttend']; 
              $dd = $groupAttend[2]['groupDelivered']; 
              $result .= "<td valign='top' class='padding_top' align='center'>".$aa."/".$dd."</td>";
             
              $aa = $groupAttend[0]['groupAttend']; 
              $dd = $groupAttend[0]['groupDelivered']; 
              $result .= "<td valign='top' class='padding_top' align='center'>".$aa."/".$dd."</td>";
           }
       
           if($attended=='' && $delivered=='')  {
              $result .= "<td valign='top' class='padding_top' align='center'>".NOT_APPLICABLE_STRING."</td>
                          <td valign='top' class='padding_top' align='center'>".NOT_APPLICABLE_STRING."</td>
                         </tr>";    
           }              
           else {
              if($delivered==0) {
                $per="0.00";   
              } 
              else {
                $per = round(($attended/$delivered)*100,2);
              }
              $result .= "<td valign='top' class='padding_top' align='center'>".$attended."/".$delivered."</td>
                          <td valign='top' class='padding_top' align='center'>".$per."</td>
                         </tr>";    
           }   
       }
       else {
           $result .= "<td valign='top' class='padding_top' align='center'></td>
                       <td valign='top' class='padding_top' align='center'></td>
                       </tr>";  
       }
       
       echo $result;
    }
    die;
    
    
    function getTableHeading($rowspan,$colspan,$attendanceDateList) {
        
        global $nosCol; 
        global $groupAttend;
        global $consolidatedId;
        global $gt;
        
        $tableHeading = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                          <tr class='rowheading'>
                             <td width='2%'  valign='middle' $rowspan class='searchhead_text' ><b>#</b></td>
                             <td width='5%'  valign='middle' $rowspan class='searchhead_text' align='left'><strong>Roll No.</strong></td>
                             <td width='5%'  valign='middle' $rowspan class='searchhead_text' align='left'><strong>URoll No.</strong></td>
                             <td width='10%' valign='middle' $rowspan class='searchhead_text' align='left'><strong>Student Name</strong></td>";
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
         
         $gt=0;
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
              $dateHeading .= " <td width='10%' valign='middle' class='searchhead_text' align='center'><strong>".$val."</strong></td>";
              $periodNumberHeading .= " <td width='10%' valign='middle' class='searchhead_text' align='center'><strong>".$periodNumber."</strong></td>";
              $groupTypeHeading .= " <td width='10%' valign='middle' class='searchhead_text' align='center'><strong>".$gType."</strong></td>";
            }
            else {
              $fromArr = explode('-',$fromDate);
              $toArr = explode('-',$toDate);
              $val  = $fromArr[2].'/'.$fromArr[1];     
              $val1 = $toArr[2].'/'.$toArr[1];     
              $dateHeading .= " <td width='10%' rowspan='2' valign='middle' class='searchhead_text' align='center'><strong>$val<br>To<br>$val1</strong></td>";  
              //$periodNumberHeading .= "<td width='10%' valign='middle' class='searchhead_text' align='center'><strong></strong></td>";
              $groupTypeHeading .= " <td width='10%' valign='middle' class='searchhead_text' align='center'><strong>".$gType."</strong></td>";
            }
            
            $tableHeading .= " <td width='10%' valign='middle' class='searchhead_text' align='center'><strong>".($j+1)."</strong></td>";
         }
         for($kk=0;$kk<$dif;$kk++) {
            $tableHeading .= " <td width='10%' valign='middle' class='searchhead_text' align='center'><strong>".($j+1)."</strong></td>";
            $dateHeading .= " <td width='10%' valign='middle' class='searchhead_text' align='center'><strong></strong></td>";  
            $periodNumberHeading .= "<td width='10%' valign='middle' class='searchhead_text' align='center'><strong></strong></td>";  
            $groupTypeHeading .= " <td width='10%' valign='middle' class='searchhead_text' align='center'><strong></strong></td>"; 
            $j++;   
         }    
         
         if($consolidatedId==1 && $gt==1) {
           $groupTypeHeading .= " <td width='10%' valign='middle' class='searchhead_text' align='center'><strong>L</strong></td>"; 
           $groupTypeHeading .= " <td width='10%' valign='middle' class='searchhead_text' align='center'><strong>T</strong></td>"; 
           
           $tableHeading .= " <td width='10%' valign='middle' rowspan='3' class='searchhead_text' align='center'><strong>Total</strong></td>";    
           $tableHeading .= " <td width='10%' valign='middle' rowspan='3' class='searchhead_text' align='center'><strong>Total</strong></td>";
         }
         
         $tableHeading .= " <td width='10%' valign='middle' $rowspan class='searchhead_text' align='center'><strong>Total</strong></td>  
                            <td width='10%' valign='middle' $rowspan class='searchhead_text' align='center'><strong>%age</strong></td>  
                            </tr>";
                            
         $tableHeading .="<tr class='rowheading'>".$dateHeading."</tr>";
         $tableHeading .="<tr class='rowheading'>".$periodNumberHeading."</tr>";
         $tableHeading .="<tr class='rowheading'>".$groupTypeHeading."</tr>";
                            
        return $tableHeading;                    
    }
    
?>

