<?php
//-------------------------------------------------------
// THIS FILE Contains All The DataBase Queries Of The Registration Form
// Author : Ankur Aggarwal
// Created on : 25-July-2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); 

class StudentRegistration {
	private static $instance = null;

	private function __construct() {
	}
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

	public function insertStudentToRegistration() {

		global $REQUEST_DATA;
        global $sessionHandler;   
        
        $name		= $REQUEST_DATA['name'];
        $fatherName	= $REQUEST_DATA['fatherName'];
        $id	        = $REQUEST_DATA['universityRollNo'];
        $studentEmail	= $REQUEST_DATA['studentEmail'];
        $parentsNumber	= $REQUEST_DATA['parentsNumber'];
        $studentNumber   = $REQUEST_DATA['studentNumber'];
        $bloodGroup      =$REQUEST_DATA['bloodGroup'];
        $allergy        = $REQUEST_DATA['allergy'];
        $address	= $REQUEST_DATA['address'];
        $mentor	        = $REQUEST_DATA['mentor'];
        $cgpa           = $REQUEST_DATA['cgpa'];
        $scholarType  = $REQUEST_DATA['status'];                         //  // This will check the status of the status (whether Hosteler or Day Scholar)
        $travellingStatus=$REQUEST_DATA['travel'];
        $studentId        = $REQUEST_DATA['studentId'];
        $classId        = $REQUEST_DATA['currentClassId'];       
        $parentEmail = $REQUEST_DATA['parentEmail'];
        $hostelName = $REQUEST_DATA['hostelName'];
        $roomNo = $REQUEST_DATA['roomNo'];
        $routeNo = $REQUEST_DATA['routeNo'];
        $pickUp = $REQUEST_DATA['pickUp'];
        $vehicleType = $REQUEST_DATA['vehicleType'];
        $travellingPt = $REQUEST_DATA['travellingPt'];
        $vehicleRegistration = $REQUEST_DATA['vehicleRegistration'];
        $landlineNo=$REQUEST_DATA['landlineNo'];
        
        $pgOwner=htmlentities(add_slashes($REQUEST_DATA['pgOwner']));
        $pgContact=htmlentities(add_slashes($REQUEST_DATA['pgContact']));
        $pgAddress=htmlentities(add_slashes($REQUEST_DATA['pgAddress']));
       
        if($REQUEST_DATA['travel']==3) {
          $pgOwner=htmlentities(add_slashes($REQUEST_DATA['PgName']));
          $pgContact=htmlentities(add_slashes($REQUEST_DATA['pgContact1']));
          $pgAddress=htmlentities(add_slashes($REQUEST_DATA['address1']));
        }
        
        
        $aieeeRollNo=$REQUEST_DATA['aieeeRollNo'];
        $aieeeRank=$REQUEST_DATA['aieeeRank'];
        $cityNative=$REQUEST_DATA['cityNative'];
        $stateNative=$REQUEST_DATA['stateNative'];
        $dateOfBirth=$REQUEST_DATA['dateOfBirth'];
        
        $companyName= htmlentities(add_slashes($REQUEST_DATA['companyName']));
        $companyCity=htmlentities(add_slashes($REQUEST_DATA['companyCity']));
        $companyHR=htmlentities(add_slashes($REQUEST_DATA['companyHR']));
        $companyEmailId=htmlentities(add_slashes($REQUEST_DATA['companyEmailId']));
        $companyContactNo=htmlentities(add_slashes($REQUEST_DATA['companyContactNo']));
        $companyProjectName=htmlentities(add_slashes($REQUEST_DATA['companyProjectName']));
        $companyAddress=htmlentities(add_slashes($REQUEST_DATA['companyAddress']));
        
		$dt = date('Y-m-d');
        
        $hostelArray = explode('!~~!!~~!',$hostelName);
        $hostelId = $hostelArray[0];
        $hostelName = $hostelArray[1];
        $wardenName= $hostelArray[2]; 
        $wardenContact = $hostelArray[3]; 
      
      
        $query = "INSERT INTO 
                    `sc_student_registration` 
                  SET
                    `companyName`='$companyName' ,
                    `companyCity`='$companyCity',
                    `companyHR`='$companyHR' ,
                    `companyEmail`='$companyEmailId',
                    `companyContactNo`='$companyContactNo' ,
                    `companyProjectName`='$companyProjectName',
                    `companyAddress`='$companyAddress' ,
                    `hostelId` = '$hostelId' ,
                    `universityRollNo`='$id' ,
                    `studentId`='$studentId' ,
                    `classId`='$classId' ,
                    `studentName`='$name' ,
                    `studentMobileNo`='$studentNumber' ,
                    `studentEmail`='$studentEmail' ,
                    `fatherName`='$fatherName' ,
                    `fatherMobile`='$parentsNumber' ,
                    `landlineNo`='$landlineNo' ,
                    `parentEmail`='$parentEmail' ,
                    `permAddress1`='$address' ,
                    `bloodGroup`='$bloodGroup',
                    `allergy`='$allergy',
                    `mentor`='$mentor' ,
                    `cgpa`='$cgpa' ,
                    `registrationDate`='$dt',
                    `scholarType`='$scholarType' ,
                    `hostelName`='$hostelName' ,
                    `roomNo`='$roomNo' ,
                    `wardenName`='$wardenName',
                    `wardenContact`='$wardenContact' ,
                    `travellingStatus`='$travellingStatus' ,
                    `routeNo`='$routeNo' ,
                    `pickUp`='$pickUp' ,
                    `travellingPt`='$travellingPt' ,
                    `vehicleType`='$vehicleType' ,
                    `vehicleRegistrationNumber`='$vehicleRegistration',
                    `pgOwner`='$pgOwner' ,
                    `pgAddress`='$pgAddress' ,
                    `pgContact`='$pgContact' ,
                   
                    aieeeRollNo = '$aieeeRollNo',
                    aieeeRank = '$aieeeRank',
                    cityNative = '$cityNative',
                    stateNativeId = '$stateNative',
                    dateOfBirth = '$dateOfBirth' ";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query); 
	}

	public function updateStudentRegistration() {
		
        global $REQUEST_DATA;
        global $sessionHandler;   
        
        $name		= $REQUEST_DATA['name'];
        $fatherName	= $REQUEST_DATA['fatherName'];
        $id	        = $REQUEST_DATA['universityRollNo'];
        $studentEmail	= $REQUEST_DATA['studentEmail'];
        $parentsNumber	= $REQUEST_DATA['parentsNumber'];
        $bloodGroup      =$REQUEST_DATA['bloodGroup'];
        $allergy        = $REQUEST_DATA['allergy'];
        $studentNumber   = $REQUEST_DATA['studentNumber'];
        $address	= $REQUEST_DATA['address'];
        $mentor	        = $REQUEST_DATA['mentor'];
        $cgpa           = $REQUEST_DATA['cgpa'];
        $scholarType  = $REQUEST_DATA['status'];                 // This will check the status of the status (whether Hosteler or Day Scholar)
        $travellingStatus=$REQUEST_DATA['travel'];
        $classId        = $REQUEST_DATA['currentClassId'];
        $studentId        = $REQUEST_DATA['studentId']; 
        $parentEmail = $REQUEST_DATA['parentEmail'];
        $hostelName = $REQUEST_DATA['hostelName'];
        $roomNo = $REQUEST_DATA['roomNo'];
        
        $routeNo = $REQUEST_DATA['routeNo'];
        $pickUp = $REQUEST_DATA['pickUp'];
        $vehicleType = $REQUEST_DATA['vehicleType'];
        $travellingPt = $REQUEST_DATA['travellingPt'];
        $vehicleRegistration = $REQUEST_DATA['vehicleRegistration'];
        $landlineNo=$REQUEST_DATA['landlineNo'];

        $dt = date('Y-m-d');

        $aieeeRollNo=$REQUEST_DATA['aieeeRollNo'];
        $aieeeRank=$REQUEST_DATA['aieeeRank'];
        $cityNative=$REQUEST_DATA['cityNative'];
        $stateNative=$REQUEST_DATA['stateNative'];
        $dateOfBirth=$REQUEST_DATA['dateOfBirth'];
        
        $hostelArray = explode('!~~!!~~!',$hostelName);
        $hostelId = $hostelArray[0];
        $hostelName = $hostelArray[1];
        $wardenName= $hostelArray[2]; 
        $wardenContact = $hostelArray[3]; 
        
        $pgOwner=htmlentities(add_slashes($REQUEST_DATA['pgOwner']));
        $pgContact=htmlentities(add_slashes($REQUEST_DATA['pgContact']));
        $pgAddress=htmlentities(add_slashes($REQUEST_DATA['pgAddress']));
        
        if($REQUEST_DATA['travel']==3) {
          $pgOwner=htmlentities(add_slashes($REQUEST_DATA['PgName']));
          $pgContact=htmlentities(add_slashes($REQUEST_DATA['pgContact1']));
          $pgAddress=htmlentities(add_slashes($REQUEST_DATA['address1']));
        }
        
        $companyName= htmlentities(add_slashes($REQUEST_DATA['companyName']));
        $companyCity=htmlentities(add_slashes($REQUEST_DATA['companyCity']));
        $companyHR=htmlentities(add_slashes($REQUEST_DATA['companyHR']));
        $companyEmailId=htmlentities(add_slashes($REQUEST_DATA['companyEmailId']));
        $companyContactNo=htmlentities(add_slashes($REQUEST_DATA['companyContactNo']));
        $companyProjectName=htmlentities(add_slashes($REQUEST_DATA['companyProjectName']));
        $companyAddress=htmlentities(add_slashes($REQUEST_DATA['companyAddress']));
        
		$query = "UPDATE 
                        `sc_student_registration` 
                  SET
                        `companyName`='$companyName' ,
                        `companyCity`='$companyCity',
                        `companyHR`='$companyHR' ,
                        `companyEmail`='$companyEmailId',
                        `companyContactNo`='$companyContactNo' ,
                        `companyProjectName`='$companyProjectName',
                        `companyAddress`='$companyAddress' ,
                        `hostelId` = '$hostelId',
                        `universityRollNo`='$id' ,
                        `classId`='$classId' ,
                        `studentName`='$name' ,
                        `studentMobileNo`='$studentNumber' ,
                        `studentEmail`='$studentEmail' ,
                        `fatherName`='$fatherName' ,
                        `fatherMobile`='$parentsNumber' ,
                        `landlineNo`='$landlineNo' ,
                        `parentEmail`='$parentEmail' ,
                        `permAddress1`='$address' ,
                        `bloodGroup`='$bloodGroup',
                        `allergy`='$allergy',
                        `mentor`='$mentor' ,
                        `cgpa`='$cgpa' ,
                        `registrationDate`='$dt',
                        `scholarType`='$scholarType' ,
                        `hostelName`='$hostelName' ,
                        `roomNo`='$roomNo' ,
                        `wardenName`='$wardenName',
                        `wardenContact`='$wardenContact' ,
                        `travellingStatus`='$travellingStatus' ,
                        `routeNo`='$routeNo' ,
                        `pickUp`='$pickUp' ,
                        `travellingPt`='$travellingPt' ,
                        `vehicleType`='$vehicleType' ,
                        `vehicleRegistrationNumber`='$vehicleRegistration',
                        `pgOwner`='$pgOwner' ,
                        `pgAddress`='$pgAddress' ,
                        `pgContact`='$pgContact' ,
                        
                        
                        aieeeRollNo = '$aieeeRollNo',
                        aieeeRank = '$aieeeRank',
                        cityNative = '$cityNative',
                        stateNativeId = '$stateNative',
                        dateOfBirth = '$dateOfBirth' 
                  WHERE 
                        `studentId`='$studentId'";


		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query); 
	}

    
    public function getStudentAcademicList($condition,$orderBy=''){

        global $REQUEST_DATA;
        global $sessionHandler;

        $query="SELECT 
                     sa.previousClassId, sa.previousRollNo, 
                     sa.previousSession, sa.previousInstitute, sa.previousBoard, sa.previousMarks, 
                     sa.previousMaxMarks, sa.previousPercentage,sa.previousEducationStream
                FROM
                    `student_academic_registration` sa
                    $condition
                ORDER BY 
                    $orderBy ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function insertStudentAcademics($studentId) {
       
        global $REQUEST_DATA; 
        global $sessionHandler;               
        
        $studentId = $sessionHandler->getSessionVariable('StudentId'); 
        
        $session    = $REQUEST_DATA['session'];
        $board        = $REQUEST_DATA['board'];
        $marks        = $REQUEST_DATA['marks'];
        $maxMarks    = $REQUEST_DATA['maxMarks'];
        $educationStream    = $REQUEST_DATA['educationStream'];
        $percentage    = $REQUEST_DATA['percentage'];
        $previousClass    = $REQUEST_DATA['previousClass'];
        
   
        $query = "DELETE  FROM `student_academic_registration` WHERE studentId = '$studentId' ";
        $ret=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");

        if($ret===false){
          return false;
        }
        $insertValue = "";
        $cnt = count($previousClass);
        
        for($i=0;$i<$cnt; $i++) {
            $querySeprator = '';
            if(trim($board[$i])!='' || trim($session[$i]) !='' || trim($marks[$i]) !=''  || trim($maxMarks[$i]) !='' || trim($educationStream[$i]) !='') {
                if($insertValue!=''){
                 $querySeprator = ",";
                }
                $insertValue .= "$querySeprator ('".add_slashes($previousClass[$i])."','".
                                                    add_slashes($studentId)."','".
                                                    add_slashes($session[$i])."','".
                                                    add_slashes($board[$i])."','".
                                                    add_slashes($marks[$i])."','".
                                                    add_slashes($maxMarks[$i])."','".
                                                    add_slashes($percentage[$i])."','".
                                                    add_slashes(trim($educationStream[$i]))."')";
            }
        }
      
        if($insertValue!=''){
            $query = "INSERT INTO `student_academic_registration`
                      (previousClassId,studentId,previousSession,previousBoard,previousMarks,
                       previousMaxMarks,previousPercentage,previousEducationStream)
                       VALUES 
                       ".$insertValue;
             return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);            
        }
        
    }

	public function getClassId($studentId) {
        global $sessionHandler;   
        $query="select classId from sc_student_registration where studentId='$studentId'";  // will fetch the ClassId from the Table
       return SystemDatabaseManager::getInstance()->executeQuery($query);
   }

   public function getCGPA($studentId) {
       global $sessionHandler;   
       $query="SELECT	ROUND(SUM(gradeIntoCredits)/sum(credits),3) as cgpa from student_cgpa where studentId = '$studentId'"; //Will Return the CGPA for studentId
       return SystemDatabaseManager::getInstance()->executeQuery($query);

   }

        public function checkStudentId($studentId) {
            global $sessionHandler;   
        $query="select count(studentId) from sc_student_registration where studentId='$studentId'";
       return SystemDatabaseManager::getInstance()->executeQuery($query); //Will Return True If StudentId Exist Else False

     }

     public function countClassId($studentId,$classId) {
            global $sessionHandler;   
        $query="SELECT count(classId) from sc_student_registration where classId='$classId' and studentId='$studentId'";
       return SystemDatabaseManager::getInstance()->executeQuery($query); //Will Return True If StudentId Exist Else False

     }
     
     public function getMentorAllow($studentId,$classId) {
        
        global $sessionHandler;   
        $query="SELECT 
                    isAllowRegistration 
                FROM 
                    student_teacher_mentorship 
                WHERE classId='$classId' and studentId='$studentId'";
      
        return SystemDatabaseManager::getInstance()->executeQuery($query); //Will Return True If StudentId Exist Else False
     }
     

     public function getStudentInfo($studentId){
       global $sessionHandler;   
       $query="select * from sc_student_registration where studentId='$studentId'";
       return SystemDatabaseManager::getInstance()->executeQuery($query);       
    }
       public function getEnableClasses(){
           global $sessionHandler; 
            $instituteId=$sessionHandler->getSessionVariable('InstituteId');  
	$query="select value from config where param='ENABLE_REGISTRATION' AND instituteId = '$instituteId'";

	return SystemDatabaseManager::getInstance()->executeQuery($query);
    }

    public function getMentorName($studentId,$classId){
        global $sessionHandler;   
        $query="SELECT 
                     IFNULL(employeeName,'') AS mentorUserName 
                FROM 
                     student_teacher_mentorship ss 
                     LEFT JOIN  employee e ON e.userId = ss.userId
                WHERE
                     ss.studentId = '$studentId' AND ss.classId='$classId' "; // Will get the Mentor Name Corressponding To The University Roll No
                     
	    return SystemDatabaseManager::getInstance()->executeQuery($query);
    }
    
    public function getScholarType($studentId,$classId){
        global $sessionHandler;   
      $query="select dayScholar from student_hostel_bus_status where studentId='$studentId' and classId='$classId'"; 
      // will return whether student is day scholar or hosteler
     
      return SystemDatabaseManager::getInstance()->executeQuery($query);
    }
    public function getMentorEmail($studentId,$classId){
        global $sessionHandler;   
         
       $query="SELECT 
                    emailAddress 
              FROM 
                    employee 
              WHERE 
                    userId IN (SELECT DISTINCT userId FROM student_teacher_mentorship WHERE studentId='$studentId' and classId='$classId')";
       return SystemDatabaseManager::getInstance()->executeQuery($query);
    }

    public function getCSVInfo($condition='',$limit=''){
        
        global $sessionHandler;   
        
        $query="SELECT 
                        * 
                FROM 
                        sc_student_registration sr LEFT JOIN states ss ON sr.stateNativeId = ss.stateId $condition
                ORDER BY 
                        registrationDate DESC
                $limit";  
        
        return SystemDatabaseManager::getInstance()->executeQuery($query);
    }
   
     //This function willget the Allowed IP values
     public function getAllowedIp($allowedIp=''){
         
      global $sessionHandler;
      $query="  SELECT 
      			count(allowIPNo) as cnt
      		 FROM
      		 	`allow_ip_address`
      		 WHERE 	
      		         allowIPNo='$allowedIp'";
      		        
      return SystemDatabaseManager::getInstance()->executeQuery($query);
    }
    
     public function getAcademicInfo($condition=''){
        
         global $sessionHandler;   
        
        $query="SELECT 
                      * 
                FROM 
                      student_academic_registration sa, sc_student_registration sr 
                WHERE
                     sa.studentId = sr.studentId
                $condition";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query);
    }
      public function getTotalStudentCourseResource($studentId,$classId='',$conditions=''){
     global $REQUEST_DATA;
     global $sessionHandler;
     //$studentId=( trim($REQUEST_DATA['id'])=="" ? 0 : trim($REQUEST_DATA['id']) );

     if($classId!='' and $classId!=0){
      $classCondition=" AND sc_student_section_subject.classId=".add_slashes($classId);
     }

//AND  course_resources.timeTableLabelId = ttl.timeTableLabelId
//AND time_table_classes.instituteId = $instituteId

     $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     $sessionId=$sessionHandler->getSessionVariable('SessionId');

     $query="SELECT COUNT(*) AS totalRecords

             FROM
               course_resources,resource_category,subject,employee, time_table_labels ttl
             WHERE
                  course_resources.resourceTypeId=resource_category.resourceTypeId
                  AND
                  course_resources.subjectId=subject.subjectId
                  AND
                  course_resources.employeeId=employee.employeeId
                  AND
                  course_resources.instituteId=$instituteId
                  AND
                  course_resources.sessionId=$sessionId
				  
				  AND
                  course_resources.subjectId
                   IN
                    (
						SELECT DISTINCT sc_student_section_subject.subjectId
						FROM sc_student_section_subject, time_table_classes
						WHERE sc_student_section_subject.studentId=$studentId
						AND sc_student_section_subject.classId = time_table_classes.classId
						AND timeTableLabelId = course_resources.timeTableLabelId
						AND sc_student_section_subject.sessionId=$sessionId
						AND sc_student_section_subject.instituteId=$instituteId
                      $classCondition
                    )
                  $conditions
                  " ;
     //echo $query;
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
      public function getStudentCourseResourceList($studentId,$classId='',$conditions='', $orderBy=' subject',$limit=''){
     global $REQUEST_DATA;
     global $sessionHandler;
     //$studentId=( trim($REQUEST_DATA['id'])=="" ? 0 : trim($REQUEST_DATA['id']) );

     if($classId!='' and $classId!=0){
      $classCondition=" AND sc_student_section_subject.classId=".add_slashes($classId);
     }

     $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     $sessionId=$sessionHandler->getSessionVariable('SessionId');

     $query="SELECT
                    courseResourceId,resourceName,description,subjectCode AS subject,
                    IF(resourceUrl IS NULL,-1,resourceUrl) AS resourceUrl,
                    IF(attachmentFile IS NULL,-1,attachmentFile) AS attachmentFile,
                    employeeName, postedDate, subjectCode, subjectName
             FROM
               course_resources,resource_category,subject,employee
             WHERE
                  course_resources.resourceTypeId=resource_category.resourceTypeId
                  AND
                  course_resources.subjectId=subject.subjectId
                  AND
                  course_resources.employeeId=employee.employeeId
                  AND
                  course_resources.instituteId=$instituteId
                  AND
                  course_resources.sessionId=$sessionId
                  AND
                  course_resources.subjectId
                   IN
                    (
						SELECT DISTINCT sc_student_section_subject.subjectId
						FROM sc_student_section_subject, time_table_classes
						WHERE sc_student_section_subject.studentId=$studentId
						AND sc_student_section_subject.classId = time_table_classes.classId
						AND timeTableLabelId = course_resources.timeTableLabelId
						AND sc_student_section_subject.sessionId=$sessionId
						AND sc_student_section_subject.instituteId=$instituteId
                      $classCondition
                    )
                  $conditions
                  ORDER BY $orderBy
                  $limit " ;
     //echo $query;
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    public function getTotalStudentSection($studentId,$classId,$orderBy) {
		if ($classId != "" and $classId != "0") {
			$classCond =" AND sct.classId =".add_slashes($classId);
		   }
		global $sessionHandler;

      $query = "	SELECT
								sectionName
					FROM		`sc_student_section_subject` sct,
								`sc_section` sc,
								`class` cls ,
								`subject` sub
					WHERE		sc.SectionId = sct.sectionId
					AND			cls.classId = sct.classId
					AND			sub.subjectId = sct.subjectId
					AND			cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
					AND			cls.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
					AND			sct.studentId=$studentId
								$classCond ORDER BY  $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getStudentSection($studentId,$classId,$limit,$orderBy) {

		if ($classId != "" and $classId != "0") {
			$classCond =" AND sct.classId =".add_slashes($classId);
		   }

		global $sessionHandler;

	$query = "	SELECT
								sectionName,
								subjectName,
								sc.sectionType,
								subjectCode,
								SUBSTRING_INDEX(cls.className,'".CLASS_SEPRATOR."',-1) AS periodName
					FROM		`sc_student_section_subject` sct,
								`sc_section` sc,
								`class` cls ,
								`subject` sub
					WHERE		sc.SectionId = sct.sectionId
					AND			cls.classId = sct.classId
					AND			sub.subjectId = sct.subjectId
					AND			cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
					AND			cls.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
					 AND		sct.studentId=$studentId
								$classCond ORDER BY  $orderBy $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   public function getMentorCommentsCount($studentId='') {
      
        $query = "SELECT 
                      COUNT(*) AS totalRecords
                  FROM 
                      `sc_teacher_mentorship_comments` stmc, 
                      class cls, `employee` e, study_period sp 
                   WHERE 
                      e.userId = stmc.userId AND
                      cls.classId = stmc.classId AND
                      cls.studyPeriodId = sp.studyPeriodId AND
                      stmc.studentId = '$studentId' ";
  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
   	public function getMentorCommentsList($studentId='',$orderBy='',$limit='') {
      
        if($orderBy=='') {
          $orderBy = "commentDate DESC";  
        }
        
        $query = "SELECT 
                      DISTINCT stmc.mentorshipCommentId, stmc.userId, stmc.studentId, stmc.classId, 
                      stmc.comments, stmc.commentDate, e.employeeName, employeeCode, cls.className, sp.periodName
                  FROM 
                      `sc_teacher_mentorship_comments` stmc, 
                      class cls, `employee` e, study_period sp
                   WHERE 
			           e.userId = stmc.userId AND
                       cls.classId = stmc.classId AND
                       cls.studyPeriodId = sp.studyPeriodId AND
                       stmc.studentId = '$studentId' 
                   ORDER BY
                       $orderBy $limit";
  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
   	
 public function getStudentFeeDetails($studentId='',$classId='') {
		$query = "
           SELECT 
                DISTINCT frd.studentId, frd.classId,SUM(frd.amount) AS paidAmount 
            FROM   
                fee_receipt_details frd
			WHERE
				frd.studentId ='$studentId' AND
				frd.classId='$classId' AND
               frd.isDelete = 0 AND
               frd.feeType IN(1,4)
			GROUP BY
                 frd.studentId, frd.classId, frd.receiptNo  ";
          
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");   

	}  
	  public function getUpdateMentorAllow($ttStudentId='',$ttClassId='') {
        global $sessionHandler;
       
        
        $query = "UPDATE 
                      `student_teacher_mentorship` 
                  SET 
                      isAllowRegistration = '1'
                  WHERE
                      studentId = '$ttStudentId' AND
                      classId = '$ttClassId'";
        
        return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }
        
	
}

?>
