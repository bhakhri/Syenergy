<?php

//-------------------------------------------------------
//  This File contains Result query for "Attendance Register"
//
//
// Author :Aditi Miglani
// Created on : 07-Nov-2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
class AttendanceRegisterManager{
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
	
	 public function getStudentPercentageAttendanceReport($condition='',$groupBy='',$orderBy='',$limit='',$dateFields='')  {

        global $REQUEST_DATA;
        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

		 
        $isHoldCondition = "";
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        if($roleId==3 || $roleId==4){
          $isHoldCondition = " AND c.holdAttendance = '0' "; 
        }



        if($dateFields=='') {
           $fieldName = "tt.classId, tt.subjectId, tt.groupId, tt.studentId, tt.subjectName, tt.subjectCode,
                         tt.periodId, tt.groupTypeId, tt.fromDate, tt.toDate, tt.isMemberOfClass,
                         tt.periodNumber, tt.lectureAttended, tt.lectureDelivered,
                         tt.leaveTaken,tt.medicalLeaveTaken";
           if($orderBy=='') {
              $orderBy = " tt.studentId, tt.classId, tt.subjectId, tt.fromDate, tt.toDate, tt.periodNumber, tt.periodId, tt.groupId  ";
           }
        }
        else {
           $fieldName = " tt.classId, tt.subjectId, tt.groupId, tt.groupTypeId, tt.fromDate, tt.toDate,
                          tt.periodNumber, tt.periodId, tt.subjectName, tt.subjectCode ";
           if($orderBy=='') {
              $orderBy = " tt.classId, tt.subjectId, tt.fromDate, tt.toDate, tt.periodNumber, tt.periodId, tt.groupId,
                           tt.subjectName, tt.subjectCode ";
           }
        }

        $query = "SELECT
                         DISTINCT $fieldName
                  FROM
                     (SELECT

                            su.subjectName, su.subjectCode,
                            att.classId, att.subjectId, att.groupId, att.studentId,
                            IF(IFNULL(att.periodId,'')='','-1',att.periodId) AS periodId, gt.groupTypeId,
                            att.fromDate, att.toDate, IF(IFNULL(p.periodNumber,'')='','',p.periodNumber) AS periodNumber,
                            IF(att.isMemberOfClass=0, -1, 1) AS isMemberOfClass,
                            IF(att.isMemberOfClass=0, '', IF(att.attendanceType =2,(ac.attendanceCodePercentage/100),att.lectureAttended)) AS lectureAttended,
                            IF(att.isMemberOfClass=0, '', att.lectureDelivered) AS lectureDelivered,
                            IFNULL(IF(att.isMemberOfClass=0, '', IF(att.attendanceType =2, IF((ac.attendanceCodePercentage/100)=0,
                                    (SELECT
                                                   DISTINCT IF(IFNULL(ml.medicalLeaveId,'')='',IF(IFNULL(dl.dutyLeaveId,'')='','',1),'')
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
                                                   DISTINCT IF(IFNULL(dl.dutyLeaveId,'')='',IF(IFNULL(ml.medicalLeaveId,'')='','',1),'')
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
                            group_type gt, `group` grp, class c, `subject` su,
                            student s INNER JOIN ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
                            LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId  AND ac.instituteId = $instituteId)
                            LEFT JOIN period p ON att.periodId = p.periodId
                      WHERE
                            gt.groupTypeId = grp.groupTypeId  AND
                            att.groupId   = grp.groupId       AND
                            att.subjectId = su.subjectId      AND
                            att.classId   = c.classId
			    $condition
			    $isHoldCondition
                      $groupBy) AS tt
                  ORDER BY
                        $orderBy $limit";

        $resultArray= SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        if($resultArray===false) {
  			return false;
		}
		else{
			if($consolidated!='') { 
				for($i=0;$i<count($resultArray);$i++) {
          			if($resultArray[$i]['per'] >= $lowerMedicalLimit && $resultArray[$i]['per'] <= $higherMedicalLimit) { 
          				$medicalLeaveTaken = $resultArray[$i]['medicalLeaveTaken'];
          				
          				for($j=1;$j<=$medicalLeaveTaken;$j++){
          				    $attend = $resultArray[$i]['attended'];
          				    $leaveTaken = $resultArray[$i]['leaveTaken'];
          				    $delivered = $resultArray[$i]['delivered'];
          				    if($delivered>0) {	
							  $resultArray[$i]['per']=(($attend+$leaveTaken+$j)/$delivered)*100;
							  if($resultArray[$i]['per']>=$higherMedicalLimit){
								break;
							  }
							}
          				}
          			}
          		}
          		return $resultArray; 
			}
			else{
				return $resultArray;
			}
		}
    }
}
?>
