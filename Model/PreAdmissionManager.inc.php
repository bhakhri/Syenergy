<?php 
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class PreAdmissionManager {
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
    
    public function getCampList($condition='',$orderBy='campAbbr') {  
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        if($orderBy=='') {
          $orderBy='campAbbr'; 
        }
        $query = "SELECT
                        campId AS id, campName AS name, campAbbr As abbr
                  FROM
                       `preadmission_camp`
                  $condition     
                  ORDER BY 
                        $orderBy";
                 
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    public function getSchoolList($condition='',$orderBy='schoolAbbr') {  
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        if($orderBy=='') {
          $orderBy='schoolAbbr'; 
        }
        $query = "SELECT
                        schoolId AS id, schoolName AS name, schoolAbbr As abbr
                  FROM
                       `preadmission_school`
                  $condition     
                  ORDER BY 
                        $orderBy";
                 
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    public function getCourseList($condition='',$orderBy='courseAbbr') {  
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        if($orderBy=='') {
          $orderBy='courseAbbr'; 
        }
        $query = "SELECT
                        courseId AS id, courseName as name, courseAbbr AS abbr
                  FROM
                       `preadmission_course`
                  $condition     
                  ORDER BY 
                        $orderBy";
                 
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    public function getPreadmissionPreferance($condition='',$orderBy='preferanceAbbr') {  
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        if($orderBy=='') {
          $orderBy='preferanceAbbr'; 
        }
        
        $query = "SELECT
                       ppc.preferanceCourseId AS id, pp.preferanceName AS name, pp.preferanceAbbr AS abbr
                  FROM
                       preadmission_preferance_course ppc, `preadmission_preferance` pp
                  WHERE
                      ppc.preferanceId = pp.preferanceId     
                  $condition     
                  ORDER BY 
                        $orderBy";
                 
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    public function getPreferanceCourseList($condition='',$orderBy='preferanceAbbr') {  
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        if($orderBy=='') {
          $orderBy='preferanceAbbr'; 
        }
        
        $query = "SELECT
                       pc.preferanceId, pc.preferanceName, pc.preferanceAbbr,
                       pc.courseId, c.courseName, c.courseAbbr, pc.preferanceCourseId 
                  FROM
                       preadmission_preferance_course pc, `preadmission_course`  c,  `preadmission_preferance` p
                  WHERE
                       pc.preferanceId= p.preferanceId AND
                       pc.courseId = c.courseId
                  $condition     
                  ORDER BY 
                        $orderBy";
                 
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    public function getPreadmissionReligion($condition='',$orderBy='religionName') {  
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        if($orderBy=='') {
          $orderBy='religionName'; 
        }
        
        $query = "SELECT
                       religionId AS id, religionName AS name
                  FROM
                       `preadmission_religion`
                  $condition     
                  ORDER BY 
                        $orderBy";
                 
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    public function getPreadmissionCategory($condition='',$orderBy='categoryName') {  
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        if($orderBy=='') {
          $orderBy='categoryName'; 
        }
        
        $query = "SELECT
                       categoryId AS id, categoryName AS name
                  FROM
                       `preadmission_category`
                  $condition     
                  ORDER BY 
                        $orderBy";
                 
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    public function getPreadmissionDomicile($condition='',$orderBy='domicileName') {  
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        if($orderBy=='') {
          $orderBy='domicileName'; 
        }
        
        $query = "SELECT
                       domicileId AS id, domicileName AS name
                  FROM
                       `preadmission_domicile`
                  $condition     
                  ORDER BY 
                        $orderBy";
                 
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    public function getPreadmissionExamTest($condition='',$orderBy='examName') {  
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        if($orderBy=='') {
          $orderBy='examName'; 
        }
        
        $query = "SELECT
                       examId AS id, examName AS name
                  FROM
                       `preadmission_exam_test`
                  $condition     
                  ORDER BY 
                        $orderBy";
                 
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    
    public function getTotalPreAdmission($condition='') { 
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        $query="SELECT
                    COUNT(*) AS totalRecords
                FROM
                    student_preadmission sp, preadmission_course pc, preadmission_school ps,
                    preadmission_camp c  
                WHERE
                    sp.campId = c.campId AND
                    sp.courseId = pc.courseId AND
                    sp.schoolId = ps.schoolId 
                $condition";    
       
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
    
    public function getPreAdmissionList($condition='',$orderBy='admissionStatus',$limit='') { 
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        $query="SELECT
                    sp.studentId, sp.studentName, sp.admissionStatus, sp.admissionNumber,
                    pc.courseAbbr, ps.schoolAbbr, c.campAbbr
                    
                FROM
                    student_preadmission sp, preadmission_course pc, preadmission_school ps,
                    preadmission_camp c
                WHERE
                    sp.campId = c.campId AND
                    sp.courseId = pc.courseId AND
                    sp.schoolId = ps.schoolId 
                $condition
                ORDER BY
                    $orderBy
                $limit";    
       
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }
   
    public function deleteStudent($id='') {
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        global $REQUEST_DATA;    
        
        $query = "DELETE FROM student_preadmission_academic WHERE studentId = '$id' ";
        $returnStatus = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($returnStatus===false) {
           return false;
        }    
        
        $query = "DELETE FROM student_preadmission_exam_test WHERE studentId = '$id' ";
        $returnStatus = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($returnStatus===false) {
           return false;
        }    
        
        $query = "DELETE FROM student_preadmission_course_preference WHERE studentId = '$id' ";
        $returnStatus = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($returnStatus===false) {
           return false;
        }    
        
        $query = "DELETE FROM student_preadmission WHERE studentId = '$id' ";
        $returnStatus = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($returnStatus===false) {
           return false;
        } 
        
        return true;    
    } 
    
    
    public function intAddEdit($queryFormat='', $id='') {   
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        global $REQUEST_DATA;         
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
       /*
          echo "<pre>";
            print_r($REQUEST_DATA);
          die;
	   */
        $conditions = "";
        if(strtoupper(trim($queryFormat))=='UPDATE') {
          $conditions = " WHERE studentId = '$id' ";  
        }
       
        $camp = htmlentities(add_slashes(trim($REQUEST_DATA['camp'])));
        $campId = explode('~',$camp);
       
        $query = "$queryFormat student_preadmission   
                  SET
                    `admissionStatus`='".htmlentities(add_slashes(trim($REQUEST_DATA['status'])))."',
                    `campId`='".$campId[0]."',
		            `srNumber`='".htmlentities(add_slashes(trim($REQUEST_DATA['srNumber'])))."',
                     admissionSession = '2012',
                    `admissionNumber`='".htmlentities(add_slashes(trim($REQUEST_DATA['admissionNumber'])))."',
                     schoolId =  '".htmlentities(add_slashes(trim($REQUEST_DATA['school'])))."',   
                     courseId =  '".htmlentities(add_slashes(trim($REQUEST_DATA['courses'])))."',   
                    `studentName`='".htmlentities(add_slashes(trim($REQUEST_DATA['studentName'])))."',
                    `dateofBirth`='".htmlentities(add_slashes(trim($REQUEST_DATA['dateofBirth'])))."',
                    `gender`='".htmlentities(add_slashes(trim($REQUEST_DATA['gender'])))."',
                    `bloodGroup`='".htmlentities(add_slashes(trim($REQUEST_DATA['blood_group'])))."',
                    `religionId`='".htmlentities(add_slashes(trim($REQUEST_DATA['religion'])))."',
                    `categoryId`='".htmlentities(add_slashes(trim($REQUEST_DATA['category'])))."',
                    `identificationMark`='".htmlentities(add_slashes(trim($REQUEST_DATA['identiMark'])))."',
                    `domicileId`='".htmlentities(add_slashes(trim($REQUEST_DATA['stateDomicile'])))."',    
                    
                    `mobileNumber`='".htmlentities(add_slashes(trim($REQUEST_DATA['mobileNumber'])))."',    
                    
                    `corr_address1`='".htmlentities(add_slashes(trim($REQUEST_DATA['correspondeceAddress1'])))."',
                    `corr_address2`='".htmlentities(add_slashes(trim($REQUEST_DATA['correspondeceAddress1'])))."',
                    `corr_pincode`= '".htmlentities(add_slashes(trim($REQUEST_DATA['correspondecePincode'])))."',  
                    `corr_country`= '".htmlentities(add_slashes(trim($REQUEST_DATA['correspondenceCountry'])))."', 
                    `corr_state`='".htmlentities(add_slashes(trim($REQUEST_DATA['correspondenceStates'])))."', 
                    `corr_city`= '".htmlentities(add_slashes(trim($REQUEST_DATA['correspondenceCity'])))."',
                    `corr_contactno`= '".htmlentities(add_slashes(trim($REQUEST_DATA['correspondecePhone'])))."',
                    
                    `perm_address1`='".htmlentities(add_slashes(trim($REQUEST_DATA['permanentAddress1'])))."',
                    `perm_address2`='".htmlentities(add_slashes(trim($REQUEST_DATA['permanentAddress2'])))."',
                    `perm_pincode`= '".htmlentities(add_slashes(trim($REQUEST_DATA['permanentPincode'])))."',  
                    `perm_country`= '".htmlentities(add_slashes(trim($REQUEST_DATA['permanentCountry'])))."', 
                    `perm_state`='".htmlentities(add_slashes(trim($REQUEST_DATA['permanentStates'])))."', 
                    `perm_city`= '".htmlentities(add_slashes(trim($REQUEST_DATA['permanentCity'])))."',
                    `perm_contactno`= '".htmlentities(add_slashes(trim($REQUEST_DATA['permanentPhone'])))."',  
                    
                    `hostelFacility`='".htmlentities(add_slashes(trim($REQUEST_DATA['hostel_acc'])))."',
                    
                    `fatherName`='".htmlentities(add_slashes(trim($REQUEST_DATA['fatherName'])))."',
                    `fatherQual`='".htmlentities(add_slashes(trim($REQUEST_DATA['fatherQualification'])))."',
                    `fatherProf`='".htmlentities(add_slashes(trim($REQUEST_DATA['fatherProfession'])))."',
                    `fatherDesig`='".htmlentities(add_slashes(trim($REQUEST_DATA['fatherDesignation'])))."',
                    `father_mobile`='".htmlentities(add_slashes(trim($REQUEST_DATA['fatherMobile'])))."', 
                    `father_off_address`='".htmlentities(add_slashes(trim($REQUEST_DATA['fatherAddress'])))."',
                    `father_off_country`='".htmlentities(add_slashes(trim($REQUEST_DATA['fatherCountry'])))."',
                    `father_off_state`='".htmlentities(add_slashes(trim($REQUEST_DATA['fatherStates'])))."',
                    `father_off_city`='".htmlentities(add_slashes(trim($REQUEST_DATA['fatherCity'])))."',
                    `father_off_pincode`='".htmlentities(add_slashes(trim($REQUEST_DATA['fatherPincode'])))."',
                    `father_contactNo`='".htmlentities(add_slashes(trim($REQUEST_DATA['fatherContact'])))."',
                    `father_email`='".htmlentities(add_slashes(trim($REQUEST_DATA['fatherEmail'])))."',
                   
                    `motherName`='".htmlentities(add_slashes(trim($REQUEST_DATA['motherName'])))."',
                    `motherQual`='".htmlentities(add_slashes(trim($REQUEST_DATA['motherQualification'])))."',
                    `motherProf`='".htmlentities(add_slashes(trim($REQUEST_DATA['motherProfession'])))."',
                    `motherDesig`='".htmlentities(add_slashes(trim($REQUEST_DATA['motherDesignation'])))."',
                    `mother_landlineNo`='".htmlentities(add_slashes(trim($REQUEST_DATA['motherMobile'])))."', 
                    `mother_off_address`='".htmlentities(add_slashes(trim($REQUEST_DATA['motherAddress'])))."',
                    `mother_off_country`='".htmlentities(add_slashes(trim($REQUEST_DATA['motherCountry'])))."',
                    `mother_off_state`='".htmlentities(add_slashes(trim($REQUEST_DATA['motherStates'])))."',
                    `mother_off_city`='".htmlentities(add_slashes(trim($REQUEST_DATA['motherCity'])))."',
                    `mother_off_pincode`='".htmlentities(add_slashes(trim($REQUEST_DATA['motherPincode'])))."',
                    `mother_contactNo`='".htmlentities(add_slashes(trim($REQUEST_DATA['motherContact'])))."',
                    `mother_email`='".htmlentities(add_slashes(trim($REQUEST_DATA['motherEmail'])))."',
                    
                    `guardianName`='".htmlentities(add_slashes(trim($REQUEST_DATA['guardianName'])))."',
                    `guardianQual`='".htmlentities(add_slashes(trim($REQUEST_DATA['guardianQualification'])))."',
                    `guardianProf`='".htmlentities(add_slashes(trim($REQUEST_DATA['guardianProfession'])))."',
                    `guardianDesig`='".htmlentities(add_slashes(trim($REQUEST_DATA['guardianDesignation'])))."',
                    `guardian_landlineNo`='".htmlentities(add_slashes(trim($REQUEST_DATA['guardianMobile'])))."', 
                    `guardian_off_address`='".htmlentities(add_slashes(trim($REQUEST_DATA['guardianAddress'])))."',
                    `guardian_off_country`='".htmlentities(add_slashes(trim($REQUEST_DATA['guardianCountry'])))."',
                    `guardian_off_state`='".htmlentities(add_slashes(trim($REQUEST_DATA['guardianStates'])))."',
                    `guardian_off_city`='".htmlentities(add_slashes(trim($REQUEST_DATA['guardianCity'])))."',
                    `guardian_off_pincode`='".htmlentities(add_slashes(trim($REQUEST_DATA['guardianPincode'])))."',
                    `guardian_contactNo`='".htmlentities(add_slashes(trim($REQUEST_DATA['guardianContact'])))."',
                    `guardian_email`='".htmlentities(add_slashes(trim($REQUEST_DATA['guardianEmail'])))."',
                    
                    `annualIncome`='".htmlentities(add_slashes(trim($REQUEST_DATA['annualIncome'])))."',
                    
                    `siblingName`='".htmlentities(add_slashes(trim($REQUEST_DATA['siblingName'])))."',
                    `siblingYear`='".htmlentities(add_slashes(trim($REQUEST_DATA['siblingYear'])))."',
                    `siblingCourse`='".htmlentities(add_slashes(trim($REQUEST_DATA['siblingCourse'])))."',                    
                    `siblingRollno`='".htmlentities(add_slashes(trim($REQUEST_DATA['siblingRollno'])))."'
                  $conditions ";
                  
        $returnStatus = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($returnStatus===false) {
           return false;
        }
        if(strtoupper(trim($queryFormat))=='INSERT') {
          $id=SystemDatabaseManager::getInstance()->lastInsertId();
        }
        
        $query = "DELETE FROM student_preadmission_academic WHERE studentId = '$id' ";
        $returnStatus = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($returnStatus===false) {
           return false;
        }          

        // To Store Pre Admission Academic Detail
        $examArray = array("1"=>"10th","2"=>"10+2","3"=>"Graduation","4"=>"Diploma","5"=>"Any Other");               
        $insertQuery='';
        for($i=1;$i<=count($examArray);$i++) {
           $str  = trim($REQUEST_DATA['board'.$i]).trim($REQUEST_DATA['year'.$i]).trim($REQUEST_DATA['marksObtained'.$i]);
           $str .= trim($REQUEST_DATA['maxMarks'.$i]).trim($REQUEST_DATA['subject'.$i]).trim($REQUEST_DATA['percentage'.$i]);
           
           if(trim($str)!='') { 
               if($insertQuery!='') {
                 $insertQuery .=",";  
               }
               $insertQuery .="($id,'".htmlentities(add_slashes(trim($examArray[$i])))."','".
                                 htmlentities(add_slashes(trim($REQUEST_DATA['board'.$i])))."','".
                                 htmlentities(add_slashes(trim($REQUEST_DATA['year'.$i])))."','".
                                 htmlentities(add_slashes(trim($REQUEST_DATA['marksObtained'.$i])))."','".
                                 htmlentities(add_slashes(trim($REQUEST_DATA['maxMarks'.$i])))."','".
                                 htmlentities(add_slashes(trim($REQUEST_DATA['subject'.$i])))."','".
                                 htmlentities(add_slashes(trim($REQUEST_DATA['percentage'.$i])))."')";
           }
        }
        if($insertQuery!='') {
           $query = "INSERT INTO student_preadmission_academic
                  (studentId, classId, previousBoard, previousYear,  previousMarks, previousMaxMarks, subjects, previousPercentage) 
                  VALUES
                  $insertQuery"; 
           $returnStatus = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
           if($returnStatus===false) {
             return false;
           }                 
        }
        
        // To Store Pre Admission Exam Test Detail (AIEEE, NATA,..)
        $query = "DELETE FROM student_preadmission_exam_test WHERE studentId = '$id' ";
        $returnStatus = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($returnStatus===false) {
           return false;
        } 
        $insertQuery='';
        $exmaType = $REQUEST_DATA['examType'];
        for($i=0;$i<count($exmaType);$i++) {
           if($insertQuery!='') {
             $insertQuery .=",";  
           }
           $insertQuery .="($id,$exmaType[$i])";
        }
        if($insertQuery!='') {
           $query = "INSERT INTO student_preadmission_exam_test
                  (studentId, testId) 
                  VALUES
                  $insertQuery"; 
           $returnStatus = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
           if($returnStatus===false) {
             return false;
           }                 
        }
        
        
        // To Store Student Course Preference
        $query = "DELETE FROM student_preadmission_course_preference WHERE studentId = '$id' ";
        $returnStatus = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($returnStatus===false) {
           return false;
        } 
        $insertQuery='';
        
        $preferenceLength = $REQUEST_DATA['totalPref'];
        if($preferenceLength=='') {
          $preferenceLength='0';  
        }
        for($i=0;$i<$preferenceLength;$i++) {
          $sortId = $REQUEST_DATA['coursePreference'.($i+1)] ;
          $coursePreferenceId = $REQUEST_DATA['coursePreferenceId'.($i+1)] ;
          if($sortId!='') {
             if($insertQuery!='') {
               $insertQuery .=",";  
             }
             $insertQuery .="($id,$coursePreferenceId,$sortId)";
          }
        }
        if($insertQuery!='') {
           $query = "INSERT INTO student_preadmission_course_preference
                  (studentId, preferanceCourseId, sortId) 
                  VALUES
                  $insertQuery"; 
           $returnStatus = SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
           if($returnStatus===false) {
             return false;
           }                 
        }
        return true;
    }
    
    public function getPopulateList($id='') {   
        
        global $sessionHandler;
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        
        global $REQUEST_DATA;         
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query="SELECT
                        sp.studentId, sp.admissionStatus, sp.srNumber, sp.campId, sp.admissionSession, sp.admissionNumber
                        , sp.schoolId, sp.courseId, sp.studentName, sp.dateofBirth, sp.gender, sp.bloodGroup 
                        , IFNULL(sp.categoryId,'') AS categoryId, sp.identificationMark, sp.corr_address1, sp.corr_address2
                        , sp.corr_city, sp.corr_state, IFNULL(sp.religionId,'') AS religionId
                        , sp.corr_country, sp.corr_pincode, sp.corr_contactno, sp.perm_address1, sp.perm_address2, sp.perm_city
                        , sp.perm_state, sp.perm_country, sp.perm_pincode, sp.perm_contactno, sp.domicileId, sp.hostelFacility
                        , sp.fatherName, sp.fatherQual, sp.fatherProf, sp.fatherDesig, sp.father_off_address, sp.father_off_city
                        , sp.father_off_state, sp.father_off_country, sp.father_off_pincode, sp.father_contactNo
                        , sp.father_mobile, sp.father_email, sp.motherName, sp.motherQual, sp.motherProf, sp.motherDesig
                        , sp.mother_off_address, sp.mother_off_city, sp.mother_off_state, sp.mother_off_country
                        , sp.mother_off_pincode, sp.mother_contactNo, sp.mother_landlineNo, sp.mother_email
                        , sp.guardianName, sp.guardianQual, sp.guardianProf, sp.guardianDesig, sp.guardian_off_address
                        , sp.guardian_off_city, sp.guardian_off_state, sp.guardian_off_country, sp.guardian_off_pincode
                        , sp.guardian_contactNo, sp.guardian_landlineNo, sp.guardian_email, sp.annualIncome, sp.siblingName
                        , sp.siblingYear, sp.siblingCourse, sp.siblingRollno, pr.religionName, pc.campName, pc.campAbbr
                        , ps.schoolName, ps.schoolAbbr, c.courseName, c.courseAbbr, d.domicileName, cat.categoryName
                        , sp.mobileNumber
                 FROM
                        student_preadmission sp
                        LEFT JOIN preadmission_camp pc
                            ON (sp.campId = pc.campId)
                        LEFT JOIN preadmission_category cat
                            ON (sp.categoryId = cat.categoryId)
                        LEFT JOIN preadmission_course c
                            ON (sp.courseId = c.courseId)
                        LEFT JOIN preadmission_domicile d
                            ON (sp.domicileId = d.domicileId)
                        LEFT JOIN preadmission_religion pr
                            ON (sp.religionId = pr.religionId)
                        LEFT JOIN preadmission_school ps
                            ON (sp.schoolId = ps.schoolId)
                WHERE
                        sp.studentId = '$id' ";    
        $studentListArray = $systemDatabaseManager->executeQuery($query,"Query: $query");
        
        $query="SELECT   
                        ts.studentId, ts.testId, ex.examName
                FROM
                    student_preadmission_exam_test ts
                    INNER JOIN preadmission_exam_test ex
                    ON (ex.examId = ts.testId) 
                WHERE
                    ts.studentId = '$id' ";     
        $examArray = $systemDatabaseManager->executeQuery($query,"Query: $query"); 
        
        $query="SELECT   
                    studentId, classId, previousBoard, previousYear, subjects, previousMarks, previousMaxMarks, previousPercentage
                FROM
                    student_preadmission_academic
                WHERE
                    studentId = '$id' ";     
        $academicArray = $systemDatabaseManager->executeQuery($query,"Query: $query"); 

        
        $query="SELECT
                    spp.studentId, spp.preferanceCourseId, ppc.courseId, ppc.preferanceId, spp.sortId
                FROM
                    student_preadmission_course_preference spp 
                    INNER JOIN preadmission_preferance_course ppc
                    ON (spp.preferanceCourseId = ppc.preferanceCourseId)
                WHERE
                    spp.studentId = '$id'";     
        $preferenceArray = $systemDatabaseManager->executeQuery($query,"Query: $query"); 
        
        
       $resultArray = '{"studentinfo" : '.json_encode($studentListArray).',"examinfo" : '.json_encode($examArray).', 
                        "academicinfo" : '.json_encode($academicArray).',"preferenceinfo" : '.json_encode($preferenceArray).' 
                       }';  
        
       return $resultArray;  
    }   
    
}
?>
