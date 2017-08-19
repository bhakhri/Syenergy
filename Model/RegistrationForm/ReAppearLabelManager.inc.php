<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Reappear / Re-Exam" Module
//
// Author :Parveen Sharma   
// Created on : 19-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ReAppearLabelManager {
    private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "SessionsManager" CLASS
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "SessionsManager" CLASS
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
    public static function getInstance() {
        if (self::$instance === null) {
            $class = __CLASS__;
            return self::$instance = new $class;
        }
        return self::$instance;
    }
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Session
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       

    public function addReAppearLabel() {
        
        global $REQUEST_DATA;
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');  
        
        return SystemDatabaseManager::getInstance()->runAutoInsert('reappear_label', 
            array('labelName', 'visibleFrom', 'visibleTo', 'batches', 'maxCourseReExam', 'gradeWeight','isActive','instituteId'),
            array(trim($REQUEST_DATA['labelName']),$REQUEST_DATA['visibleFrom'],$REQUEST_DATA['visibleTo'],
				  $REQUEST_DATA['batches'],$REQUEST_DATA['maxCourse'],$REQUEST_DATA['gradeWeight'],$REQUEST_DATA['Active'],$instituteId));
    }
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Session
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------           
    
    
    public function editReAppearLabel($id) {
        
        global $REQUEST_DATA;
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');       
        
        return SystemDatabaseManager::getInstance()->runAutoUpdate('reappear_label', 
            array('labelName', 'visibleFrom', 'visibleTo', 'batches', 'maxCourseReExam', 'gradeWeight','isActive','instituteId'), 
            array(trim($REQUEST_DATA['labelName']),$REQUEST_DATA['visibleFrom'],$REQUEST_DATA['visibleTo'],
				  $REQUEST_DATA['batches'],$REQUEST_DATA['maxCourse'],$REQUEST_DATA['gradeWeight'],$REQUEST_DATA['Active'],$instituteId), "labelId=$id");
    }
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Session Name
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
     
   public function getBatchYear($conditions='') {
       global $sessionHandler;
        
       $instituteId = $sessionHandler->getSessionVariable('InstituteId');
       $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        
       $query = "SELECT
                     DISTINCT b.batchYear
                 FROM 
                     reappear_label r LEFT JOIN batch b ON INSTR(r.batches,CONCAT_WS(',',b.batchYear)) > 0 AND b.instituteId = $instituteId 
                 WHERE
                     r.instituteId = $instituteId      
                 $conditions";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }    
     
     
    
    public function getReAppearlabelName($conditions='') {
       global $sessionHandler;
        
       $instituteId = $sessionHandler->getSessionVariable('InstituteId');
       $sessionId   = $sessionHandler->getSessionVariable('SessionId');
       
       $query = "SELECT
						tt.labelId, tt.labelName, tt.visibleFrom, tt.visibleTo, tt.batchId, 
						tt.maxCourseReExam, tt.gradeWeight, tt.isActive, tt.batches
				  FROM 	
					   (SELECT  
							r.labelId, r.labelName, r.visibleFrom, r.visibleTo, r.batches AS batchId, 
							r.maxCourseReExam, r.gradeWeight, r.isActive, GROUP_CONCAT(b.batchYear) AS batches
						FROM 
							 reappear_label r LEFT JOIN batch b ON INSTR(r.batches,CONCAT_WS(',',b.batchYear)) > 0 AND b.instituteId = $instituteId 
                        WHERE
                            r.instituteId = $instituteId       
						$conditions
						GROUP BY
							r.labelId) AS tt";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
   

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A "Session" RECORD
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function deleteReAppearLabel($labelId) {
      
       global $sessionHandler;
        
       $instituteId = $sessionHandler->getSessionVariable('InstituteId');
       $sessionId   = $sessionHandler->getSessionVariable('SessionId');
      
       $query = "DELETE FROM `reappear_label` WHERE labelId=$labelId AND instituteId = $instituteId ";
                 
       return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Session LIST 
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
    public function getLabel($conditions='') {
       global $sessionHandler;
        
       $instituteId = $sessionHandler->getSessionVariable('InstituteId');
       $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        
       $query = "SELECT
					`labelId`, `labelName`, `visibleFrom`, `visibleTo`, `batches`, `maxCourseReExam`, `gradeWeight`, `isActive`
				 FROM 
					`reappear_label`
                 WHERE
                     instituteId = $instituteId      
                 $conditions";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Session LIST 
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
    
    public function getReAppearList($conditions='', $limit = '', $orderBy=' sessionYear') {
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        
        
		 $query = "SELECT
						tt.labelId, tt.labelName, tt.visibleFrom, tt.visibleTo, 
						tt.maxCourseReExam, tt.gradeWeight, tt.active, tt.batches
				  FROM 	
					   (SELECT  
							r.labelId, r.labelName, r.visibleFrom, r.visibleTo, r.batches, 
							r.maxCourseReExam, r.gradeWeight, IF(isActive=1,'Yes','No') AS active
						FROM 
							reappear_label r  
                        WHERE
                            r.instituteId = $instituteId     
						$conditions) AS tt
						ORDER BY 
		                  $orderBy $limit";
		
		          
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
    

    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR Making All Session Labels Inactive
//----------------------------------------------------------------------------------------      
    public function makeAllLabelInActive($conditions='') {
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "UPDATE 
                        reappear_label r
                  SET 
                        isActive=0
                  WHERE 
                        r.isActive=1 AND r.instituteId = $instituteId   
                  $conditions ";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }


//--------------------------------------------------------------
//  THIS FUNCTION IS Fetch Fee Cycle Classes
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function getReAppearLabelClasses($labelId='',$condition='', $orderBy='className', $limits='') {
       
        global $sessionHandler; 
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');  
        
        if($orderBy=='') {
          $orderBy=" classStatus, SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3), studyPeriodId";  
        }
                                     
        $query ="SELECT
                      DISTINCT tt.classId, tt.className, tt.reappearClassId, tt.classStatus
                 FROM
                     (SELECT
						    DISTINCT c.classId, c.className, IFNULL(reappearClassId,'') AS reappearClassId,
						    IF(c.isActive=1,'Active',IF(c.isActive=3,'Past','Unused')) AS classStatus
                      FROM 
					    reappear_label r,  class c 
                        LEFT JOIN reappear_classes rc ON c.classId = rc.classId AND rc.labelId = '$labelId' AND rc.instituteId=$instituteId
                        INNER JOIN batch b ON c.batchId = b.batchId AND b.instituteId = $instituteId 
				      WHERE
					    r.labelId = '$labelId' AND   
					    INSTR(r.batches,CONCAT_WS(',',b.batchYear)) > 0 AND
                        c.instituteId=$instituteId AND   
                        r.instituteId=$instituteId AND    
                        c.instituteId = r.instituteId AND
                        c.isActive IN (1,3) 
                        $condition 
                      GROUP BY
                        c.classId) AS tt 
                      ORDER BY 
					       $orderBy 
                      $limits";

         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");   
    }
    
    //-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Session" TABLE
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
    public function getTotalReAppear($conditions='') {

        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT
                        COUNT(*) AS totalRecords
                  FROM     
                       (SELECT  
                            r.labelId, r.labelName, r.visibleFrom, r.visibleTo, r.batches, 
                            r.maxCourseReExam, r.gradeWeight, IF(isActive=1,'Yes','No') AS active 
                        FROM 
                            reappear_label r 
                        WHERE
                            r.instituteId = $instituteId      
                        $conditions) AS tt";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

public function addReAppearClasses($str) {
        
   $query = "INSERT INTO reappear_classes (labelId,classId,instituteId) VALUES $str ";

   return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query); 
}

public function deleteReAppearClasses($condition='') {
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "DELETE FROM reappear_classes WHERE instituteId = $instituteId $condition ";
        
         return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query); 
    }

	
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A "Leave Session" RECORD
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

    public function checkClassMapping($condition) {
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                       reappearClassId, labelId, classId
                  FROM 
                       reappear_classes
                  WHERE
                       instituteId = $instituteId
                  $condition";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A "Leave Session" RECORD
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

    public function getStudentReappearSubjectList($classId='',$condition='') {
        
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
						DISTINCT c.classId, r.gradeWeight, r.maxCourseReExam, r.labelId     
				  FROM 
						class c, `reappear_classes` rc, `reappear_label` r
				  WHERE 
                        c.instituteId = $instituteId AND
                        rc.instituteId = $instituteId AND
                        r.instituteId = $instituteId AND
						c.isActive IN (1,3) AND
						CONCAT_WS(',',c.branchId, c.batchId, c.degreeId) IN 
						(SELECT DISTINCT CONCAT_WS(',',cc.branchId, cc.batchId, cc.degreeId)
						 FROM class cc WHERE cc.isActive IN (1,3) AND cc.instituteId = $instituteId AND cc.classId = '$classId') AND
						 c.classId = rc.classId AND 
						 r.labelId = rc.labelId AND r.isActive=1 
                         $condition";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	public function getScStudentGradeDetails($condition='',$labelCondition='',$orderBy='studentId, classId, subjectCode') {
	  
	    global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');        

        if($orderBy=='') {
          $orderBy = "studentId, classId, subjectCode ";  
        }
        $totalTransferred= 'total_transferred_marks'.$instituteId;
        $totalUpdated='total_updated_marks'.$instituteId;
        
        $query = " SELECT
                            ttm.classId, ttm.studentId, ttm.subjectId, ttm.gradeId,
                            IF(gr.gradeLabel IS NULL, 'I', gr.gradeLabel) as gradeLabel,
                            sub.subjectCode, sub.subjectName,
                            sp.periodName AS studyPeriod,
                            sp.periodName,
                            f.credits, gr.gradePoints,
                            IFNULL(cate.category,'".NOT_APPLICABLE_STRING."') AS categoryName,
                            CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) as studentName,
                            IFNULL(stu.rollNo,'".NOT_APPLICABLE_STRING."') AS rollNo,
                            fatherName AS fatherName,
                            CONCAT(c.universityCode,'-',d.degreeCode,'-',e.branchCode) AS programme,
                            CONCAT(SUBSTRING_INDEX(b.startDate,'-',1),'-',SUBSTRING_INDEX(b.endDate,'-',1)) AS timeDuration,
                            IF(corrAddress1 IS NULL,'', CONCAT(corrAddress1,' ',(SELECT cityName from city where city.cityId=stu.corrCityId),' ',(SELECT stateName from states where states.stateId=stu.corrStateId),' ',(SELECT countryName from countries where countries.countryId=stu.corrCountryId),IF(stu.corrPinCode IS NULL OR stu.corrPinCode='','',CONCAT('-',stu.corrPinCode)))) AS corrAddress,
                            IF(permAddress1 IS NULL,'', CONCAT(permAddress1,' ',(SELECT cityName from city where city.cityId=stu.permCityId),' ',(SELECT stateName from states where states.stateId=stu.permStateId),' ',(SELECT countryName from countries where countries.countryId=stu.permCountryId),IF(stu.permPinCode IS NULL OR stu.permPinCode='','',CONCAT('-',stu.permPinCode)))) AS permAddress,
                            sp.studyPeriodId, '-1' AS examType,  
                            COUNT(rs.reappearSubjectId) AS reappearSubjectId,
                            IFNULL(rs.labelId,'') AS labelId
                        FROM
                            sc_student stu, study_period sp, class cls, university c, degree d,  branch e, batch b,  
                            subject_to_class f,".$totalTransferred." ttm 
                            LEFT JOIN grades gr ON (ttm.gradeId = gr.gradeId AND gr.instituteId = $instituteId)
                            LEFT JOIN reappear_subject_student rs ON (ttm.classId = rs.classId AND ttm.subjectId = rs.subjectId AND 
                                ttm.studentId = rs.studentId AND rs.instituteId = $instituteId  $labelCondition),
                            `subject` sub LEFT JOIN course_category cate ON (sub.courseCategoryId = cate.courseCategoryId AND cate.instituteId = $instituteId )
                           
                        WHERE
                            cls.batchId = b.batchId                 AND
                            ttm.studentId = stu.studentId           AND
                            ttm.subjectId = sub.subjectId           AND
                            ttm.classId = cls.classId               AND
                            sp.studyPeriodId = cls.studyPeriodId    AND
                            ttm.classId = f.classId                 AND
                            ttm.subjectId = f.subjectId             AND
                            ttm.holdResult = 0                      AND
                            cls.universityId = c.universityId       AND
                            cls.degreeId = d.degreeId               AND
                            cls.branchId = e.branchId               AND
                            cls.studyPeriodId = sp.studyPeriodId    AND
                            cls.instituteId='".$instituteId."'  AND
                            ttm.isActive = 1
                        $condition
                        GROUP BY
                            ttm.studentId, ttm.classId, ttm.subjectId
                        UNION
                        SELECT
                            ttm.classId, ttm.studentId, ttm.subjectId, ttm.gradeId,
                            IF(gr.gradeLabel IS NULL, 'I', gr.gradeLabel) as gradeLabel,
                            sub.subjectCode, sub.subjectName,
                            sp.periodName AS studyPeriod,
                            sp.periodName, 
                            f.credits, gr.gradePoints,
                            IFNULL(cate.category,'".NOT_APPLICABLE_STRING."') AS categoryName,
                            CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) as studentName,
                            IFNULL(stu.rollNo,'".NOT_APPLICABLE_STRING."') AS rollNo,
                            fatherName AS fatherName,
                            CONCAT(c.universityCode,'-',d.degreeCode,'-',e.branchCode) AS programme,
                            CONCAT(SUBSTRING_INDEX(b.startDate,'-',1),'-',SUBSTRING_INDEX(b.endDate,'-',1)) AS timeDuration,
                            IF(corrAddress1 IS NULL,'', CONCAT(corrAddress1,' ',(SELECT cityName from city where city.cityId=stu.corrCityId),' ',(SELECT stateName from states where states.stateId=stu.corrStateId),' ',(SELECT countryName from countries where countries.countryId=stu.corrCountryId),IF(stu.corrPinCode IS NULL OR stu.corrPinCode='','',CONCAT('-',stu.corrPinCode)))) AS corrAddress,
                            IF(permAddress1 IS NULL,'', CONCAT(permAddress1,' ',(SELECT cityName from city where city.cityId=stu.permCityId),' ',(SELECT stateName from states where states.stateId=stu.permStateId),' ',(SELECT countryName from countries where countries.countryId=stu.permCountryId),IF(stu.permPinCode IS NULL OR stu.permPinCode='','',CONCAT('-',stu.permPinCode)))) AS permAddress,
                            sp.studyPeriodId, '1' AS examType,  
                            COUNT(rs.reappearSubjectId) AS reappearSubjectId,
                            IFNULL(rs.labelId,'') AS labelId
                        FROM
                            sc_student stu, study_period sp, class cls, university c, degree d,  branch e, batch b, 
                            subject_to_class f,".$totalUpdated." ttm
                            LEFT JOIN grades gr ON (ttm.gradeId = gr.gradeId AND gr.instituteId = $instituteId)
                            LEFT JOIN reappear_subject_student rs ON (ttm.classId = rs.classId AND ttm.subjectId = rs.subjectId AND 
                                ttm.studentId = rs.studentId AND rs.instituteId = $instituteId  $labelCondition),
                            `subject` sub LEFT JOIN course_category cate ON (sub.courseCategoryId = cate.courseCategoryId AND cate.instituteId = $instituteId )
                        WHERE
                            cls.batchId = b.batchId                 AND
                            ttm.studentId = stu.studentId           AND
                            ttm.subjectId = sub.subjectId           AND
                            ttm.classId = cls.classId               AND
                            sp.studyPeriodId = cls.studyPeriodId    AND
                            ttm.classId = f.classId                 AND
                            ttm.subjectId = f.subjectId             AND
                            cls.universityId = c.universityId       AND
                            cls.degreeId = d.degreeId               AND
                            cls.branchId = e.branchId               AND
                            cls.studyPeriodId = sp.studyPeriodId    AND
                            cls.instituteId='".$instituteId."'  AND
                            ttm.isActive = 1
                        $condition
                        GROUP BY
                            ttm.studentId, ttm.classId, ttm.subjectId
                        ORDER BY
                            $orderBy ";
                            
	     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

      public function getSingleField($table, $field, $conditions='') {
            $query = "SELECT $field FROM $table $conditions";
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }
        
      public function addReappearSubjectStudent($str) {
        
        $query = "INSERT INTO `reappear_subject_student` 
                  (labelId, classId, studentId, subjectId, gradeId, instituteId, dateOfRegistration) 
                  VALUES 
                  $str ";

        return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query); 
      }

      public function deleteReappearSubjectStudent($condition='') {
        
          global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "DELETE FROM `reappear_subject_student`  WHERE instituteId = $instituteId $condition ";
        
         return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query); 
      }
      
     public function getReappearSubjectStudentList($condition='',$orderBy='reappearSubjectId') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        DISTINCT rs.reappearSubjectId, rs.labelId, rs.classId, rs.studentId, rs.subjectId, 
                                 rs.gradeId, rs.instituteId, rs.dateOfRegistration, 
                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        c.className, sub.subjectCode, sub.subjectName, IFNULL(b.gradeLabel,'I') as gradeLabel, 
                        IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                        IF(IFNULL(s.studentMobileNo,'')='','".NOT_APPLICABLE_STRING."',s.studentMobileNo) AS studentMobileNo,
                        IF(IFNULL(s.studentEmail,'')='','".NOT_APPLICABLE_STRING."',s.studentEmail) AS studentEmail,
                        sp.periodName, studentPhoto
                  FROM 
                        student s, subject sub, class c, study_period sp,
                        `reappear_subject_student` rs LEFT JOIN grades b ON (b.gradeId = rs.gradeId AND b.instituteId = $instituteId)
                  WHERE 
                        rs.instituteId = $instituteId AND
                        c.instituteId = $instituteId AND
                        rs.studentId = s.studentId AND
                        rs.classId = c.classId AND
                        c.studyPeriodId = sp.studyPeriodId AND
                        rs.subjectId = sub.subjectId
                  $condition
                  ORDER BY $orderBy";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getReappearSubjectList($condition='',$orderBy='subjectCode') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        DISTINCT rs.subjectId, sub.subjectName, sub.subjectCode, CONCAT(sub.subjectCode, '(',sub.subjectName,')') AS subjectCodeName
                  FROM 
                       `reappear_subject_student` rs, subject sub
                  WHERE 
                        sub.subjectId = rs.subjectId AND
                        rs.instituteId = $instituteId
                  $condition
                  ORDER BY $orderBy";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getReappearClassList($condition='',$orderBy='className') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        DISTINCT rs.classId, c.className, sp.periodName
                  FROM 
                       `reappear_subject_student` rs, class c, study_period sp
                  WHERE 
                        c.classId = rs.classId AND
                        sp.studyPeriodId = c.studyPeriodId AND
                        rs.instituteId = $instituteId
                  $condition
                  ORDER BY $orderBy";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    public function getReappearFormList($condition='',$orderBy='className',$limit='') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        rs.labelId, rs.classId, rs.studentId, rs.subjectId, rs.gradeId, rs.instituteId, rs.dateOfRegistration,
                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        c.className, sub.subjectCode, sub.subjectName,
                        CONCAT(sub.subjectCode, '(',sub.subjectName,')') AS subjectCodeName,
                        IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                        IF(IFNULL(s.studentMobileNo,'')='','".NOT_APPLICABLE_STRING."',s.studentMobileNo) AS studentMobileNo,
                        IF(IFNULL(s.studentEmail,'')='','".NOT_APPLICABLE_STRING."',s.studentEmail) AS studentEmail,
                        sp.periodName
                  FROM 
                       `reappear_subject_student` rs, sc_student s, class c, study_period sp, subject sub   
                  WHERE 
                        c.classId = rs.classId AND
                        s.studentId = rs.studentId AND
                        sp.studyPeriodId = c.studyPeriodId AND
                        sub.subjectId = rs.subjectId AND  
                        rs.instituteId = $instituteId
                  $condition
                  ORDER BY $orderBy
                  $limit";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    public function getReappearFormCount($condition='') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                       COUNT(*) AS cnt 
                  FROM 
                       `reappear_subject_student` rs, sc_student s, class c, study_period sp, subject sub   
                  WHERE 
                        c.classId = rs.classId AND
                        s.studentId = rs.studentId AND
                        sp.studyPeriodId = c.studyPeriodId AND
                        sub.subjectId = rs.subjectId AND  
                        rs.instituteId = $instituteId
                  $condition";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    
    public function getReappearFormConslidatedList($condition='',$orderBy='className',$limit='') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                        tt.labelId, tt.classId, tt.studentId, tt.subjectId, tt.studentName, tt.className, 
                        tt.subjectCode, tt.rollNo, tt.fatherName,
                        tt.studentMobileNo, tt.studentEmail, tt.periodName, tt.dateOfRegistration 
                  FROM  
                      (SELECT 
                            rs.labelId, rs.classId, rs.studentId, rs.subjectId, rs.dateOfRegistration,
                            CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                            c.className, GROUP_CONCAT(DISTINCT sub.subjectCode ORDER BY subjectCode SEPARATOR ', ') AS subjectCode,
                            IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                            IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                            IF(IFNULL(s.studentMobileNo,'')='','".NOT_APPLICABLE_STRING."',s.studentMobileNo) AS studentMobileNo,
                            IF(IFNULL(s.studentEmail,'')='','".NOT_APPLICABLE_STRING."',s.studentEmail) AS studentEmail,
                            sp.periodName
                      FROM 
                           `reappear_subject_student` rs, sc_student s, class c, study_period sp, subject sub   
                      WHERE 
                            c.classId = rs.classId AND
                            s.studentId = rs.studentId AND
                            sp.studyPeriodId = c.studyPeriodId AND
                            sub.subjectId = rs.subjectId AND  
                            rs.instituteId = $instituteId
                      $condition
                      GROUP BY 
                            rs.studentId) AS tt
                  ORDER BY $orderBy
                  $limit";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
     public function getReappearFormConslidatedCount($condition='') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                       COUNT(*) AS cnt
                  FROM  
                      (SELECT 
                            rs.labelId, rs.classId, rs.studentId, rs.subjectId, rs.dateOfRegistration,
                            CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                            c.className, GROUP_CONCAT(DISTINCT sub.subjectCode ORDER BY subjectCode SEPARATOR ', ') AS subjectCode,
                            IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                            IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                            IF(IFNULL(s.studentMobileNo,'')='','".NOT_APPLICABLE_STRING."',s.studentMobileNo) AS studentMobileNo,
                            IF(IFNULL(s.studentEmail,'')='','".NOT_APPLICABLE_STRING."',s.studentEmail) AS studentEmail,
                            sp.periodName
                      FROM 
                           `reappear_subject_student` rs, sc_student s, class c, study_period sp, subject sub   
                      WHERE 
                            c.classId = rs.classId AND
                            s.studentId = rs.studentId AND
                            sp.studyPeriodId = c.studyPeriodId AND
                            sub.subjectId = rs.subjectId AND  
                            rs.instituteId = $instituteId
                      $condition
                      GROUP BY 
                            rs.studentId) AS tt";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    public function checkLabel($conditions='') {
       global $sessionHandler;
        
       $instituteId = $sessionHandler->getSessionVariable('InstituteId');
       $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        
       $query = "SELECT
                       COUNT(*) AS cnt
                 FROM 
                    `reappear_classes`
                 WHERE
                     instituteId = $instituteId      
                 $conditions";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    public function getUserData($userId) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
     
        $query = "SELECT userName 
        FROM user
        WHERE userId=$userId AND instituteId = $instituteId";
        
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//---------
public function getFieldValue($tableName, $whereFieldName, $whereFieldValue, $fromFieldName) {
    
        $query = "SELECT $whereFieldName
        FROM `$tableName` 
        WHERE $fromFieldName =  $whereFieldValue";

		return SystemDatabaseManager::getInstance()->runSingleQuery($query,"Query: $query");
    }    
	public function getStudentData($studentId) {
     
        $query = "SELECT * 
        FROM  student
        WHERE studentId=$studentId ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

public function getStudentClass($classId) {
        $query = "SELECT 
						c.classId,c.className,c.studyPeriodId,c.isActive,
						b.branchName, b.branchCode, d.degreeName, d.degreeCode, d.degreeAbbr
				  FROM 
						class c, degree d, branch b
				  WHERE 
						d.degreeId = c.degreeId AND
						b.branchId = c.branchId AND
						c.classId = '$classId'";
        
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
}
?>