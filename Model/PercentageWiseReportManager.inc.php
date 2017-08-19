<?php

//-------------------------------------------------------
//  This File contains Result query for "Percentage Wise Attendance Report Module"
//
//
// Author :Aditi Miglani
// Created on : 07-Nov-2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
class PercentageWiseReportManager {
	private static $instance = null;

	private function __construct() {
	}

	public static function getInstance() {
		if (self::$instance === null) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}
	
	public function getUserRole() {
        require_once(LIB_PATH . "/Library/common.inc.php"); //for UserId
        global $sessionHandler;

        $systemDatabaseManager = SystemDatabaseManager::getInstance();

        $query = "SELECT roleName
                  FROM role,user
                  WHERE role.roleId=user.roleId AND user.userId=".$sessionHandler->getSessionVariable('UserId');
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    public function getFinalAttendanceCount($condition='',$consolidated='',$holdStudentClassId='')  {

        global $REQUEST_DATA;
        global $sessionHandler;

	$isHoldCondition = "";
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        if($roleId==3 || $roleId==4){
          $isHoldCondition = " AND c.holdAttendance = '0' "; 
        }
	if($holdStudentClassId!='') {
           $classCond .= " AND c.classId NOT IN (".$holdStudentClassId.")";
        }

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $groupBy='';
        if($consolidated=='') {
          $groupBy = " ,att.groupId";
        }
        

        $query = "SELECT
                       COUNT(*) AS cnt 
                  FROM
                      (SELECT
                            DISTINCT sp.periodName, sp.periodValue,  c.className,
                            CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                            IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                            IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                            sub.subjectName, sub.subjectCode, 
                            CONCAT(sub.subjectName,' (',sub.subjectCode,')') AS subjectNameCode,  
                            att.studentId, att.classId,  att.subjectId, 
                            MIN(fromDate) AS fromDate, MAX(toDate) AS toDate  $groupBy
                      FROM
                            `subject` sub, class c, `group` grp, student s, study_period sp,
                            ".ATTENDANCE_TABLE." att  LEFT JOIN attendance_code ac ON ac.attendanceCodeId = att.attendanceCodeId 
                      WHERE       
                            sp.studyPeriodId = c.studyPeriodId AND  
                            s.studentId = att.studentId AND
                            sub.subjectId = att.subjectId AND
                            grp.groupId = att.groupId AND
                            c.classId = att.classId AND
                            c.isActive IN (1,3) AND
                            c.instituteId = $instituteId   AND
                            c.sessionId = $sessionId 
                            $condition
			    $isHoldCondition
                       GROUP BY
                            att.studentId, att.classId, att.subjectId $groupBy) AS t ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getFinalAttendance($condition='',$orderBy='',$limit='',$consolidated='',$percentCondition='',$holdStudentClassId='')  {

        global $REQUEST_DATA;
        global $sessionHandler;



        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $lowerMedicalLimit=$sessionHandler->getSessionVariable('MEDICAL_LEAVE_CALCULATION_LIMIT');
        $higherMedicalLimit=$sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');
        
        $groupByField1 ='';
        $groupBy='';
        $groupBy2 ='';
        
        $ttGroupBy='';
        if($consolidated=='') {
          $groupByField1 = " ,grp.groupId, grp.groupName";
          $groupBy  = "  ,grp.groupId";
          $groupBy2 = " ,att.groupId";
          
          $ttGroupBy = " ,t.groupId";
        }
        
        if($orderBy=='') {
          $orderBy = "subjectName";
        }
        

	$isHoldCondition = "";
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        if($roleId==3 || $roleId==4){
          $isHoldCondition = " AND c.holdAttendance = '0' "; 
        }
 	if($holdStudentClassId!='') {
           $isHoldCondition .= " AND c.classId NOT IN (".$holdStudentClassId.")";
        }

        $query = "SELECT
                        DISTINCT sp.periodName, sp.periodValue,  c.className,
                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        sub.subjectName, sub.subjectCode, 
                        CONCAT(sub.subjectName,' (',sub.subjectCode,')') AS subjectNameCode,  
                        att.studentId, att.classId,  att.subjectId, 
                        GROUP_CONCAT(DISTINCT CONCAT(e.employeeName,' (',e.employeeCode,')') ORDER BY e.employeeName ASC SEPARATOR ', ') AS employeeName, 
                        MIN(att.fromDate) AS fromDate, MAX(att.toDate) AS toDate  $groupByField1
                  FROM
                        `employee` e, `subject` sub, class c, `group` grp, student s, study_period sp,
                        ".ATTENDANCE_TABLE." att  LEFT JOIN attendance_code ac ON ac.attendanceCodeId = att.attendanceCodeId 
                  WHERE 
                        e.employeeId = att.employeeId AND      
                        sp.studyPeriodId = c.studyPeriodId AND  
                        s.studentId = att.studentId AND
                        sub.subjectId = att.subjectId AND
                        grp.groupId = att.groupId AND
                        c.classId = att.classId AND
                        c.isActive IN (1,3) AND
                        c.instituteId = $instituteId   AND
                        c.sessionId = $sessionId 
                        $condition
			$isHoldCondition
                   GROUP BY
                        att.studentId, att.classId, att.subjectId $groupBy2
                   ORDER BY     
                        $orderBy
                   $limit ";
	
        $studentSubjectArray = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
       
       
        $resultArray = array();  
        
        $studentIdList = "0";
        for($i=0;$i<count($studentSubjectArray);$i++)  {
            $studentId =  $studentSubjectArray[$i]['studentId'];   
            $classId   =  $studentSubjectArray[$i]['classId'];  
            $subjectId =  $studentSubjectArray[$i]['subjectId'];
            if($consolidated=='') { 
              $groupId =  $studentSubjectArray[$i]['groupId'];  
            }
            
            $medicalCondition = " AND att.studentId = '$studentId' AND att.classId = '$classId' AND att.subjectId = '$subjectId' ";
            
            $attCondition = " AND att.studentId = '$studentId' AND att.classId = '$classId' AND att.subjectId = '$subjectId' ";
            if($consolidated=='') { 
               $attCondition .= " AND att.groupId = '$groupId' "; 
            }
        
        
            // Fetch Student Attendance List ============ START ===========================
            $query = "SELECT 
                        att.classId, att.studentId, att.subjectId,
                        ROUND(SUM(IF( att.isMemberOfClass =0, 0,
                        IF(att.attendanceType =2,(ac.attendanceCodePercentage /100), att.lectureAttended ) ) ),0) AS lectureAttended ,
                        SUM(IF(isMemberOfClass=0,0, lectureDelivered))  as lectureDelivered,
                        MIN(att.fromDate) AS fromDate, MAX(att.toDate) AS toDate 
                        $groupByField1
                 FROM  
                        employee e, `group` grp,  ".ATTENDANCE_TABLE." att  
                        LEFT JOIN attendance_code ac ON ac.attendanceCodeId = att.attendanceCodeId 
                 WHERE 
                        e.employeeId = att.employeeId AND
                        grp.classId = att.classId AND
                        grp.groupId = att.groupId 
                        $attCondition       
                 GROUP BY 
                        att.classId, att.studentId, att.subjectId $groupBy2
                 ORDER BY
                        att.studentId";
            $attendanceResultArray =  SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
            
            
            
            
            $attendanceArray = array();
            foreach($attendanceResultArray as $record) {
                $classId = $record['classId'];
                $studentId = $record['studentId'];
                $subjectId = $record['subjectId'];
                if($consolidated=='') {  
                  $groupId = $record['groupId'];   
                }
                $lectureAttended = $record['lectureAttended'];
                $lectureDelivered = $record['lectureDelivered'];
                if($consolidated=='') {  
                  $attendanceArray[$studentId][$classId][$subjectId][$groupId]['lectureAttended']= $lectureAttended;
                  $attendanceArray[$studentId][$classId][$subjectId][$groupId]['lectureDelivered']= $lectureDelivered;
                  $attendanceArray[$studentId][$classId][$subjectId][$groupId]['fromDate']= $record['fromDate']; 
                  $attendanceArray[$studentId][$classId][$subjectId][$groupId]['toDate']= $record['toDate']; 
                }
                else {
                  $attendanceArray[$studentId][$classId][$subjectId]['lectureAttended']= $lectureAttended;
                  $attendanceArray[$studentId][$classId][$subjectId]['lectureDelivered']= $lectureDelivered; 
                  $attendanceArray[$studentId][$classId][$subjectId]['fromDate']= $record['fromDate']; 
                  $attendanceArray[$studentId][$classId][$subjectId]['toDate']= $record['toDate'];  
                }
            }
            // Fetch Student Attendance List ============ END ===========================
            
            
            // Fetch Student Duty Leave List ============ START =========================== 
            $query = "SELECT
                           SUM(t.leavesTaken) AS leavesTaken, t.studentId, t.classId, t.subjectId  $ttGroupBy   
                      FROM  
                          (SELECT
                              DISTINCT a.classId, a.studentId, a.subjectId, a.groupId, a.periodId,  a.dutyDate, 
                              IF(IFNULL(ml.medicalLeaveId,'')='',IF(att.isMemberOfClass=0,0,IF(att.attendanceType=2,1,0)),0) leavesTaken
                           FROM
                               ".ATTENDANCE_TABLE." att LEFT JOIN  attendance_code ac1 ON ac1.attendanceCodeId = att.attendanceCodeId, 
                               ".DUTY_LEAVE_TABLE." a LEFT JOIN ".MEDICAL_LEAVE_TABLE."  ml ON a.studentId = ml.studentId AND
                                                            a.classId   = ml.classId   AND
                                                            a.subjectId = ml.subjectId AND
                                                            a.groupId   = ml.groupId   AND
                                                            a.periodId  = ml.periodId  AND
                                                            a.dutyDate = ml.medicalLeaveDate AND
                                                            ml.approvedStatus  = ".MEDICAL_LEAVE_APPROVE."  
                           WHERE
                              att.classId = a.classId AND att.studentId = a.studentId AND att.subjectId = a.subjectId AND
                              att.periodId = a.periodId   AND att.groupId=a.groupId AND
                              (a.dutyDate = att.fromDate  AND a.dutyDate = att.toDate) AND
                              a.rejected= ".DUTY_LEAVE_APPROVE."
                              $attCondition) AS t
                       GROUP BY 
                            t.studentId, t.classId, t.subjectId $ttGroupBy
                       ORDER BY
                            t.studentId, t.classId, t.subjectId $ttGroupBy";
            $dutyLeaveResultArray =  SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");                        

            
            $dutyLeaveArray = array();
            foreach($dutyLeaveResultArray as $record) {
                $classId = $record['classId'];
                $studentId = $record['studentId'];
                $subjectId = $record['subjectId'];
                if($consolidated=='') {  
                  $groupId = $record['groupId'];   
                }
                $leavesTaken = $record['leavesTaken'];
                
                if($consolidated=='') {  
                  $dutyLeaveArray[$studentId][$classId][$subjectId][$groupId]['leavesTaken']= $leavesTaken;
                }
                else {
                  $dutyLeaveArray[$studentId][$classId][$subjectId]['leavesTaken']= $leavesTaken;
                }
            }
            // Fetch Student Duty Leave List ============ END =========================== 
            
            
            // Fetch Student Medical Leave List ============ START =========================== 
            $medicalLeaveArray = array(); 
            if($consolidated!='') {  
                $query = "SELECT
                               SUM(t.leavesTaken) AS leavesTaken, t.studentId, t.classId, t.subjectId     
                          FROM 
                              (SELECT
                                  DISTINCT a.classId, a.studentId, a.subjectId,  
                                  IF(IFNULL(dl.dutyLeaveId,'')='',IF(att.isMemberOfClass=0,0,IF(att.attendanceType=2,1,0)),0) leavesTaken
                               FROM
                                  ".ATTENDANCE_TABLE." att LEFT JOIN  attendance_code ac1 ON ac1.attendanceCodeId = att.attendanceCodeId ,
                                   ".MEDICAL_LEAVE_TABLE."  a LEFT JOIN  ".DUTY_LEAVE_TABLE."  dl ON dl.studentId = a.studentId AND
                                                             dl.classId   = a.classId   AND
                                                             dl.subjectId = a.subjectId AND
                                                             dl.groupId   = a.groupId   AND
                                                             dl.periodId  = a.periodId  AND
                                                             dl.dutyDate = a.medicalLeaveDate AND
                                                             dl.rejected  = ".DUTY_LEAVE_APPROVE."   
                               WHERE
                                  att.classId = a.classId AND att.studentId = a.studentId AND att.subjectId = a.subjectId AND
                                  att.periodId = a.periodId   AND att.groupId=a.groupId AND
                                  (a.medicalLeaveDate = att.fromDate AND a.medicalLeaveDate = att.toDate) AND
                                  a.approvedStatus  = ".MEDICAL_LEAVE_APPROVE." 
                                  $medicalCondition) AS t
                          GROUP BY 
                                t.studentId, t.classId, t.subjectId 
                          ORDER BY
                                t.studentId, t.classId, t.subjectId";
               $medicalLeaveResultArray =  SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");                        
            
              
               foreach($medicalLeaveResultArray as $record) {
                  $classId = $record['classId'];
                  $studentId = $record['studentId'];
                  $subjectId = $record['subjectId'];
                  $leavesTaken = $record['leavesTaken'];
                  
                  $medicalLeaveArray[$studentId][$classId][$subjectId]['leavesTaken']= $leavesTaken;  
               }
            }
            // Fetch Student Medical Leave List ============ END ===========================  
      
            
          
            // Student List
            $resultArray[$i]['studentId'] = $studentSubjectArray[$i]['studentId'];
            $resultArray[$i]['classId'] = $studentSubjectArray[$i]['classId'];
            $resultArray[$i]['subjectId']  = $studentSubjectArray[$i]['subjectId'];    
            $studentId = $studentSubjectArray[$i]['studentId'];
            $classId = $studentSubjectArray[$i]['classId'];
            $subjectId  = $studentSubjectArray[$i]['subjectId'];
            if($consolidated=='') { 
              $resultArray[$i]['groupId']  = $studentSubjectArray[$i]['groupId'];  
              $resultArray[$i]['groupName']  = $studentSubjectArray[$i]['groupName']; 
              $groupId  = $studentSubjectArray[$i]['groupId'];   
            }
            
            $resultArray[$i]['rollNo'] = $studentSubjectArray[$i]['rollNo'];
            $resultArray[$i]['universityRollNo'] = $studentSubjectArray[$i]['universityRollNo'];
            $resultArray[$i]['studentName'] = $studentSubjectArray[$i]['studentName'];
            $resultArray[$i]['subjectName1']  = $studentSubjectArray[$i]['subjectNameCode'];
            $resultArray[$i]['periodName']  = $studentSubjectArray[$i]['periodName'];
            $resultArray[$i]['employeeName'] = $studentSubjectArray[$i]['employeeName']; 
             
            $resultArray[$i]['fromDate']   = NOT_APPLICABLE_STRING; 
            $resultArray[$i]['toDate']   = NOT_APPLICABLE_STRING; 
            $resultArray[$i]['attended']   = NOT_APPLICABLE_STRING; 
            $resultArray[$i]['delivered'] = NOT_APPLICABLE_STRING; 
            $resultArray[$i]['leaveTaken']   = NOT_APPLICABLE_STRING; 
            $resultArray[$i]['medicalLeaveTaken']  = NOT_APPLICABLE_STRING; 
            $resultArray[$i]['per']  = NOT_APPLICABLE_STRING; 
           
            // Fetch Stduent Attendance List
            if($consolidated=='') {
                if(count($attendanceArray[$studentId][$classId][$subjectId][$groupId]) > 0 ) {
                     $attended = $attendanceArray[$studentId][$classId][$subjectId][$groupId]['lectureAttended'];   
                     $delivered = $attendanceArray[$studentId][$classId][$subjectId][$groupId]['lectureDelivered'];
                     
                     if($delivered > 0) {   
                       // Fetch Stduent Duty Leave List
                       $dutyLeaveTaken='0';  
                       if(count($dutyLeaveArray[$studentId][$classId][$subjectId][$groupId]['leavesTaken']) >0)  {
                         $dutyLeaveTaken = $dutyLeaveArray[$studentId][$classId][$subjectId][$groupId]['leavesTaken'];  
                       }
                       if($dutyLeaveTaken=='') {
                         $dutyLeaveTaken='0';  
                       }
                       $attended = $attended + $dutyLeaveTaken; 
                       $per = ceil($attended / $delivered * 100); 
                       
                       $attended = $attendanceArray[$studentId][$classId][$subjectId][$groupId]['lectureAttended']; 
                     }      
                     else {
                        $attended=0; 
                        $delivered=0;
                        $dutyLeaveTaken=0;
                        $medicalLeaveTaken=0; 
                        $per=0; 
                     } 
                     $resultArray[$i]['fromDate']   = $attendanceArray[$studentId][$classId][$subjectId][$groupId]['fromDate']; 
                     $resultArray[$i]['toDate']   = $attendanceArray[$studentId][$classId][$subjectId][$groupId]['toDate']; 
                     $resultArray[$i]['attended']   = $attended; 
                     $resultArray[$i]['delivered'] = $delivered; 
                     $resultArray[$i]['leaveTaken']   = $dutyLeaveTaken; 
                     $resultArray[$i]['per']  = $per; 
               }
            }
            else { 
                if(count($attendanceArray[$studentId][$classId][$subjectId]) > 0 ) {
                     $attended = $attendanceArray[$studentId][$classId][$subjectId]['lectureAttended'];   
                     $delivered = $attendanceArray[$studentId][$classId][$subjectId]['lectureDelivered'];
                     
                     if($delivered > 0) {   
                       // Fetch Stduent Duty Leave List
                       $dutyLeaveTaken='0';  
                       if(count($dutyLeaveArray[$studentId][$classId][$subjectId]['leavesTaken']) >0)  {
                         $dutyLeaveTaken = $dutyLeaveArray[$studentId][$classId][$subjectId]['leavesTaken'];  
                       }
                       if($dutyLeaveTaken=='') {
                         $dutyLeaveTaken='0';  
                       }
                       $attended = $attended + $dutyLeaveTaken; 
                       $per = ceil($attended / $delivered * 100); 
                       
                       
                       // Fetch Stduent Medical Leave List 
                       $medicalLeaveTaken=0; 
                       if($consolidated!='') { 
                          if($per >= $lowerMedicalLimit && $per <= $higherMedicalLimit) { 
                              if(count($medicalLeaveArray[$studentId][$classId][$subjectId]['leavesTaken']) >0) {
                                 $medicalLeaveTaken = $medicalLeaveArray[$studentId][$classId][$subjectId]['leavesTaken'];  
                              }
                              if($medicalLeaveTaken=='') {
                                $medicalLeaveTaken=0; 
                              }
                              if($medicalLeaveTaken > 0 ) {
                                  $dif = $higherMedicalLimit - $per;    
                                  
                                  $medicalCnt = intval($dif*$delivered/100);  
                                  if($medicalLeaveTaken > $medicalCnt) {
                                    $per = 75;   
                                    $medicalLeaveTaken = $medicalCnt;
                                  }
                                  else {
                                     $attended = $attended + $medicalLeaveTaken;
                                     $per = ceil($attended / $delivered * 100); 
                                  }
                              }
                          }
                       }
                       $attended = $attendanceArray[$studentId][$classId][$subjectId]['lectureAttended']; 
                     }      
                     else {
                        $attended=0; 
                        $delivered=0;
                        $dutyLeaveTaken=0;
                        $medicalLeaveTaken=0; 
                        $per=0; 
                     } 
                     $resultArray[$i]['fromDate']   = $attendanceArray[$studentId][$classId][$subjectId]['fromDate']; 
                     $resultArray[$i]['toDate']   = $attendanceArray[$studentId][$classId][$subjectId]['toDate']; 
                     $resultArray[$i]['attended']   = $attended; 
                     $resultArray[$i]['delivered'] = $delivered; 
                     $resultArray[$i]['leaveTaken']   = $dutyLeaveTaken; 
                     $resultArray[$i]['medicalLeaveTaken']  = $medicalLeaveTaken; 
                     $resultArray[$i]['per']  = $per; 
               }
            }
       }
      
       return $resultArray;
    }
    
    
    
    
	public function getStudentAttendanceReport($condition='',$orderBy='',$consolidated='',$limit='',$percentCondition='')  {

        global $REQUEST_DATA;
        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
		
		$lowerMedicalLimit=$sessionHandler->getSessionVariable('MEDICAL_LEAVE_CALCULATION_LIMIT');
		$higherMedicalLimit=$sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');
		
        if($orderBy=='') {
          $orderBy = "subjectName";
        }

        $groupByField1 ='';
        $groupByField2 ='';
        $groupBy='';
        if($consolidated=='') {
          $groupByField1 = " ,tt.groupId, tt.groupName";
          $groupByField2 = " ,t.groupId, t.groupName";
          $groupBy = " ,tt.groupId";
        }

        $query = "SELECT
                        t.studentId, t.classId, t.subjectId, t.subjectCode, t.subjectName, t.className, t.studentName,
                        IFNULL(t.employeeName,'".NOT_APPLICABLE_STRING."') AS employeeName,
                        t.subjectTypeId, t.subjectTypeName, t.rollNo, t.universityRollNo,
                        CONCAT(t.subjectName,' (',t.subjectCode,')') AS subjectName1, t.periodName,
                        t.fromDate, t.toDate, t.lectureAttended AS attended, t.lectureDelivered AS delivered,
                        t.leaveTaken, t.medicalLeaveTaken,
                        IF(t.lectureDelivered=0,0,((t.lectureAttended+t.leaveTaken)/t.lectureDelivered)*100) AS per,
                        IF(t.lectureDelivered=0,0,(t.lectureAttended/t.lectureDelivered)*100) AS per1
                        $groupByField2
                  FROM
                     (SELECT
                             tt.studentId, tt.classId, tt.subjectId, tt.subjectCode, tt.subjectName, tt.className, tt.studentName,
                             tt.rollNo, tt.universityRollNo, MIN(tt.fromDate) AS fromDate, MAX(tt.toDate) AS toDate,  tt.periodName,
                             ''  AS employeeName,
                             tt.subjectTypeId, tt.subjectTypeName,
                             IFNULL(SUM(tt.lectureAttended),0) AS lectureAttended, IFNULL(SUM(tt.lectureDelivered),0) AS lectureDelivered,
                             IFNULL(SUM(tt.leaveTaken),0) AS leaveTaken ,
                             IFNULL(SUM(tt.medicalleaveTaken),0) AS medicalleaveTaken 
                             $groupByField1
                      FROM
                         (SELECT
                                att.classId, att.subjectId, att.groupId, att.studentId, su.subjectCode, su.subjectName, c.className,
                                CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                                IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                                IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                                st.subjectTypeId, st.subjectTypeName,
                                IF(IFNULL(att.periodId,'')='','-1',att.periodId) AS periodId, gt.groupTypeId, grp.groupName,
                                att.fromDate, att.toDate, IF(IFNULL(p.periodNumber,'')='','',p.periodNumber) AS periodNumber,
                                IF(att.isMemberOfClass=0, -1, 1) AS isMemberOfClass,
                                IF(att.isMemberOfClass=0, '', IF(att.attendanceType =2,(ac.attendanceCodePercentage/100),att.lectureAttended)) AS lectureAttended,
                                IF(att.isMemberOfClass=0, '', att.lectureDelivered) AS lectureDelivered, sp.periodName,
                                '' AS employeeName,
                                IFNULL(IF(att.isMemberOfClass=0, '', IF(att.attendanceType =2, IF((ac.attendanceCodePercentage/100)=0,
                                        (SELECT
                                                   DISTINCT IF(IFNULL(CONCAT(ml.studentId, ml.classId, ml.subjectId, ml.groupId,
                                        		ml.periodId, ml.medicalLeaveDate, ml.approvedStatus),'')='',IF(IFNULL(CONCAT(dl.studentId,
                                        		dl.classId,dl.subjectId,dl.groupId,dl.periodId,dl.dutyDate,dl.rejected),'')='','',1),'')
                                            FROM
                                                    ".DUTY_LEAVE_TABLE."  dl LEFT JOIN  ".MEDICAL_LEAVE_TABLE."  ml ON 
								                   dl.studentId = ml.studentId AND
								                   dl.classId   = ml.classId   AND
								                   dl.subjectId = ml.subjectId AND
								                   dl.groupId   = ml.groupId   AND
								                   dl.periodId  = ml.periodId  AND
								                   dl.dutyDate = ml.medicalLeaveDate AND
								                   ml.approvedStatus  = ".MEDICAL_LEAVE_APPROVE."  
                                            WHERE                                   
                                                   dl.studentId = att.studentId AND
                                                   dl.classId   = att.classId   AND
                                                   dl.subjectId = att.subjectId AND
                                                   dl.groupId   = att.groupId   AND
                                                   dl.periodId  = att.periodId  AND
                                                   att.fromDate = dl.dutyDate   AND
                                                   att.toDate   = dl.dutyDate   AND
                                                   dl.rejected  = ".DUTY_LEAVE_APPROVE."),''),'')),'') AS leaveTaken,
                                                   
                         		IFNULL(IF(att.isMemberOfClass=0, '', IF(att.attendanceType =2, IF((ac.attendanceCodePercentage/100)=0,
                                        (SELECT
                                                   DISTINCT IF(IFNULL(CONCAT(dl.studentId,dl.classId,dl.subjectId,dl.groupId,dl.periodId,
                                        		dl.dutyDate,dl.rejected),'')='',IF(IFNULL(CONCAT(ml.studentId, ml.classId,ml.subjectId,
                                        		ml.groupId, ml.periodId,ml.medicalLeaveDate,ml.approvedStatus),'')='','',1),'')
                                            FROM

                                                    ".MEDICAL_LEAVE_TABLE."  ml LEFT JOIN  ".DUTY_LEAVE_TABLE."  dl ON 
								                                   dl.studentId = ml.studentId AND
								                                   dl.classId   = ml.classId   AND
								                                   dl.subjectId = ml.subjectId AND
								                                   dl.groupId   = ml.groupId   AND
								                                   dl.periodId  = ml.periodId  AND
								                                   dl.dutyDate = ml.medicalLeaveDate AND
								                                   dl.rejected  = ".DUTY_LEAVE_APPROVE."   
                                            WHERE
                                                   ml.studentId = att.studentId AND
                                                   ml.classId   = att.classId   AND
                                                   ml.subjectId = att.subjectId AND
                                                   ml.groupId   = att.groupId   AND
                                                   ml.periodId  = att.periodId  AND
                                                   att.fromDate = ml.medicalLeaveDate  AND
                                                   att.toDate   = ml.medicalLeaveDate  AND
                                                   ml.approvedStatus  = ".MEDICAL_LEAVE_APPROVE."),''),'')),'') AS medicalLeaveTaken                         
                                                   
                          FROM
                                group_type gt, `group` grp, class c, study_period sp, subject_type st, `subject` su,
                                student s INNER JOIN ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
                                LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId  AND ac.instituteId = $instituteId)
                                LEFT JOIN period p ON att.periodId = p.periodId
                          WHERE
                                sp.studyPeriodId = c.studyPeriodId AND
                                gt.groupTypeId = grp.groupTypeId  AND
                                att.groupId   = grp.groupId       AND
                                att.subjectId = su.subjectId      AND
                                st.subjectTypeId = su.subjectTypeId AND
                                att.classId   = c.classId
                          $condition) AS tt
                       GROUP BY
                          tt.studentId, tt.classId, tt.subjectId $groupBy) AS t
                  $percentCondition
                  ORDER BY
                        $orderBy $limit";
	
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  
    }
 
 
}
?>
