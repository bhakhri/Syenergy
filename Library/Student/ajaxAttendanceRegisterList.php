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
    else if($roleId==3){
      UtilityManager::ifParentNotLoggedIn(true);
    }
    else if($roleId==4){
      UtilityManager::ifStudentNotLoggedIn(true); 
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
    
    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentInformationManager = StudentInformationManager::getInstance();
    
    if($roleId==3 || $roleId==4) { 
      $studentId = $sessionHandler->getSessionVariable('StudentId');
    }
    $classId = $REQUEST_DATA['classId'];  
    
  
    // first Condition student subject Group fetch  class Wise 
    
    
    $orderBy="subjectCode";
    $finalSubject = $studentInformationManager->getStudentFinalSubject($studentId,$classId,$orderBy); 
    
    $className='';
  
    $attendanceRegisterArray = array();  
    for($yy=0;$yy<count($finalSubject);$yy++) {
    
        $ttClassName = $finalSubject[$yy]['className'];
        $ttSubjectName = $finalSubject[$yy]['subjectName'];
        $ttSubjectCode = $finalSubject[$yy]['subjectCode'];
        
        $degree = $finalSubject[$yy]['classId'];
        $subjectId = $finalSubject[$yy]['subjectId'];
        $groupId = $finalSubject[$yy]['groupId'];
        $fromDate = date('Y-m-d');
    
        $dutyLeave = '1';  
        $medicalLeave = '1'; 
        
        if($roleId==3) { 
          $medicalLeave = '0';   
        }
        if($roleId==4) {
          $medicalLeave = '0';     
        }
             

        $nosCol= ''; 
        $consolidatedId = '1';
        $gt =0;
        $absentPrefix= 'X';
        $dutyLeavePrefix = 'DL';
        $medicalLeavePrefix = 'ML';
        $notMemberPrefix= 'N';
    
        $groupAttend = array();
        
        
        if($degree=='') {
          $degree = 0;  
        }
       
        if($subjectId=='') {
          $subjectId = 0;  
        }
        
        if($groupId=='') {
          $groupId = 0;  
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
  
  
        $orderBy = " rollNo ASC";    
        
        // Findout Student List
        $studentCondition = " AND s.studentId = $studentId AND c.classId = $degree AND tt.subjectId = $subjectId 
                              AND tt.groupId = $groupId ".$conditionEmployee; 
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
        
        $nosCol=count($attendanceDateList);  
        
        /* 
            // Fetch Student Old Attendance   
            $studentCondition     = " AND att.classId = $degree AND att.subjectId = $subjectId     
                                      AND (att.fromDate < '$fromDate') AND att.studentId IN ($ffStudentId) ".$groupCondition; 
            $studentOldAttendanceList =  $commonQueryManager->getStudentOldAttendanceReport($studentCondition);
        */  
        
        $rowspan="rowspan='4'";
        
        if(count($attendanceDateList) > 0 ) {
          $attendanceRegisterArray[] = getTableHeading($rowspan,$colspan,$attendanceDateList,$ttClassName,$ttSubjectName,$ttSubjectCode);  
        
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
               $result = "<tr class='$bg'>";
                               //<td valign='top' class='padding_top' align='left'></td>";
                               //<td valign='top' class='padding_top' align='left'>".$ttSubjectCode."</td>";
                               
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
                 if(count($dtCount)==0) {
                    for($k=0;$k<$nosCol;$k++) {    
                       $result .= "<td valign='top' class='padding_top' align='center'></td>"; 
                    }
                 }
                 else {
                    for($k=0;$k<count($dtCount);$k++) {
                      $result .= "<td valign='top' class='padding_top' align='center'>".($k+1)."</td>";   
                    } 
                 }
               }
               for($kk=0;$kk<$dif;$kk++) {
                 $result .= "<td valign='top' class='padding_top' align='center'></td>";
               } 
               
               if(count($dtCount)!=0) {
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
               $result .= "</table>";
               
               $attendanceRegisterArray[] = $result;
               $attendanceRegisterArray[] = "<br>";  
            }
        }
    }
    
    if(count($attendanceRegisterArray) > 0) {
        for($i=0;$i<count($attendanceRegisterArray);$i++) {
           echo $attendanceRegisterArray[$i]; 
        }
    }
    else {
      $tableHeading = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                          <tr><td height='15px'></td></tr>  
                          <tr>
                            <td width='10%' colspan='10' class='searchhead_text' align='center'>
                                <strong>No data found of Attendance Registered</strong>
                             </td>
                          </tr>
                        </table>";
       echo $tableHeading;                 
    }
    
    die;
    
    function setClassName($className='',$subjectName='',$subjectCode='') {
        $tableHeading = "<table width='100%' border='0' cellspacing='0' cellpadding='0'>
                          <tr><td height='15px'></td></tr>   
                          <tr>
                            <td width='10%' class='searchhead_text' align='left'>
                                <strong>$className</strong>
                             </td>
                          </tr>
                          <tr>
                            <td width='10%' class='searchhead_text' align='left'>
                                <strong>$subjectName ($subjectCode)</strong>
                             </td>
                          </tr>
                         </table>";
                        
       echo $tableHeading;                 
    }      
    
    
    function getTableHeading($rowspan,$colspan,$attendanceDateList,$className='',$subjectName='',$subjectCode='') { 
        
        global $nosCol; 
        global $groupAttend;
        global $consolidatedId;
        global $gt;
        
        $tableHeading = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                          <tr class='rowheading'>
                            <td width='1%' valign='middle' colspan='250' class='searchhead_text' align='left'>
                              <b>$className &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $subjectName ($subjectCode) </b>
                            </td>
                          </tr>
                          <tr class='rowheading'>";
                             //<td width='1%' valign='middle' $rowspan class='searchhead_text' align='left'></td>";
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

