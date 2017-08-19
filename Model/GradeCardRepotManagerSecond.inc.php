<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "Document" table
// Author :Jaineesh 
// Created on : (28.02.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class GradeCardRepotManagerSecond {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "TestTypeManager" CLASS
//
// Author :Jaineesh
// Created on : (28.02.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "DocumentManager" CLASS
//
// Author :Jaineesh 
// Created on : (28.02.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
    public static function getInstance() {
        if (self::$instance === null) {
            $class = __CLASS__;
            return self::$instance = new $class;
        }
        return self::$instance;
    }
    
    
    //----------------------------------------------------------------------
    //  THIS FUNCTION IS USED FOR fetching  Trimester
    //
    // Author :Parveen Sharma
    // Created on : (05.03.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    public function getStudentPeriodData($conditions='') {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                         DISTINCT sp.periodName, sp.studyPeriodId  
                  FROM
                         ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm, class c, study_period sp
                  WHERE
                         sp.studyPeriodId = c.studyPeriodId AND
                         c.classId = ttm.classId AND
                         c.instituteId='".$instituteId."' AND
                         ttm.holdResult = 0
                         $conditions
                  ORDER BY sp.studyPeriodId ";


        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }
    
    
     //----------------------------------------------------------------------------------------------------
    //function created for fetching students matching conditions
    // Author :Parveen Sharma
    // Created on : 23-Sep-2008
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
    public function getAllDetailsStudentList($conditions='', $order=' rollNo', $limit='') {
        global $sessionHandler;
        $query = "SELECT
                            DISTINCT  CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) as studentName,
                            IF(a.rollNo='','".NOT_APPLICABLE_STRING."',a.rollNo) AS rollNo,
                            CONCAT(c.universityCode,'-',d.degreeCode,'-',e.branchCode) as programme,
                            f.periodName,
                            a.classId as class_id,
                            a.studentId,
                            IF(a.studentEmail='','".NOT_APPLICABLE_STRING."',a.studentEmail) AS studentEmail,
                            universityRollNo,
                            IF(a.corrCityId Is NULL,'".NOT_APPLICABLE_STRING."',(SELECT cityName FROM city WHERE cityId = a.corrCityId)) AS corrCityId,
                            IF(a.classId Is NULL,'".NOT_APPLICABLE_STRING."',(SELECT periodName FROM study_period sp, class cls WHERE sp.studyPeriodId = cls.studyPeriodId and cls.classId = a.classId)) AS studyPeriod,
                            IF(a.studentMobileNo='','".NOT_APPLICABLE_STRING."',a.studentMobileNo) AS studentMobileNo ,
                            IF(IFNULL(corrAddress1,'')='','', CONCAT(corrAddress1,' ',(SELECT cityName from city where city.cityId=a.corrCityId),' ',(SELECT stateName from states where states.stateId=a.corrStateId),' ',(SELECT countryName from countries where countries.countryId=a.corrCountryId),IF(IFNULL(a.corrPinCode,'')='','',CONCAT('-',a.corrPinCode)))) AS corrAddress,
                            IF(IFNULL(permAddress1,'')='','', CONCAT(permAddress1,' ',IFNULL(permAddress2,''),' ',(SELECT cityName from city where city.cityId=a.permCityId),' ',(SELECT stateName from states where states.stateId=a.permStateId),' ',(SELECT countryName from countries where countries.countryId=a.permCountryId),IF(IFNULL(a.permPinCode,'')='','',CONCAT('-',a.permPinCode)))) AS permAddress,
                            fatherName AS fatherName, dateOfBirth AS DOB, studentPhoto AS Photo,
                            SUBSTRING_INDEX(b.classname,'".CLASS_SEPRATOR."',-4)  AS className, bch.endDate
                  FROM
                            student_groups ss, student a, class b,
                            university c, degree d,  branch e, study_period f, batch bch
                  WHERE
                            ss.studentId = a.studentId
                            AND ss.classId = b.classId
                            AND bch.batchId = b.batchId
                            AND bch.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                            AND b.universityId = c.universityId
                            AND b.degreeId = d.degreeId
                            AND b.branchId = e.branchId
                            AND b.studyPeriodId = f.studyPeriodId
                            AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                  $conditions
                  ORDER BY  $order 
                  $limit";

        // AND b.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."'

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    //----------------------------------------------------------------------------------------------------
    //function created for counting students matching conditions

    // Author :Ajinder Singh
    // Created on : 13-Sep-2008
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------
    public function countRecords($conditions='') {

        global $sessionHandler;

        $query = "SELECT
                            COUNT(DISTINCT ss.studentId) AS cnt
                  FROM
                            student_groups ss, student a, class b,
                            university c, degree d,  branch e, study_period f
                  WHERE
                            ss.studentId = a.studentId
                            AND ss.classId = b.classId
                            AND b.universityId = c.universityId
                            AND b.degreeId = d.degreeId
                            AND b.branchId = e.branchId
                            AND b.studyPeriodId = f.studyPeriodId
                            AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
                  $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    //----------------------------------------------------------------------------
    //  THIS FUNCTION IS USED FOR fetching Student Study period wise information
    //
    // Author :Parveen Sharma
    // Created on : (05.03.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-----------------------------------------------------------------------------
    public function getStudentStudyPeriodWiseInfo($conditions='') {
        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                          DISTINCT     
                                cls.classId,  ttm.studentId,
                                sp.periodName AS studyPeriod1,
                                CONCAT(sp.periodValue,' ',p.periodicityName) AS studyPeriod,
                                CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) as studentName,
                                IF(stu.rollNo='','".NOT_APPLICABLE_STRING."',stu.rollNo) AS rollNo,
                                IF(stu.regNo='','".NOT_APPLICABLE_STRING."',stu.regNo) AS regNo,
                                fatherName AS fatherName, d.degreeName, ii.instituteName, ii.instituteLogo, b.batchName,
                                ii.instituteEmail, ii.instituteWebsite, SUBSTR(b.startDate,1,4) AS batchStart, SUBSTR(b.endDate,3,2) AS batchEnd,
                                CONCAT(c.universityCode,'-',d.degreeCode,'-',e.branchCode) as programme,
                                CONCAT(SUBSTRING_INDEX(b.startDate,'-',1),'-',SUBSTRING_INDEX(b.endDate,'-',1)) AS timeDuration,
                                IF(IFNULL(corrAddress1,'')='','', CONCAT(corrAddress1,' ',IFNULL(corrAddress2,''),' ',(SELECT cityName from city where city.cityId=stu.corrCityId),' ',(SELECT stateName from states where states.stateId=stu.corrStateId),' ',(SELECT countryName from countries where countries.countryId=stu.corrCountryId),IF(IFNULL(stu.corrPinCode,'')='','',CONCAT('-',stu.corrPinCode)))) AS corrAddress,
                                IF(IFNULL(permAddress1,'')='','', CONCAT(permAddress1,' ',IFNULL(permAddress2,''),' ',(SELECT cityName from city where city.cityId=stu.permCityId),' ',(SELECT stateName from states where states.stateId=stu.permStateId),' ',(SELECT countryName from countries where countries.countryId=stu.permCountryId),IF(IFNULL(stu.permPinCode,'')='','',CONCAT('-',stu.permPinCode)))) AS permAddress,
                                IF(IFNULL(permAddress1,'')='','', CONCAT(permAddress1,'<br>',IFNULL(permAddress2,''),' ',(SELECT cityName from city where city.cityId=stu.permCityId),' ',(SELECT stateName from states where states.stateId=stu.permStateId),' ',(SELECT countryName from countries where countries.countryId=stu.permCountryId),IF(IFNULL(stu.permPinCode,'')='','',CONCAT('-',stu.permPinCode)))) AS permAdd,
                                sp.studyPeriodId, ses.sessionName AS sessionName, sp.periodValue AS periodValue,
                                p.periodicityName AS periodicityName, p.periodicityCode AS periodicityCode,
                                p.periodicityFrequency AS periodicityFrequency, IFNULL(e.branchName,'') AS branchName, stu.studentPhoto,
                                ttl.startDate, ttl.endDate, ttl.timeTableLabelId, ttl.labelName AS timeTableLabelName,
                                c.universityName,  IFNULL(c.universityLogo,'') AS universityLogo,
                                IF(IFNULL(c.universityAddress1,'')='','', CONCAT(c.universityAddress1,' ',IFNULL(c.universityAddress2,''),' ',(SELECT cityName from city where city.cityId=c.cityId),' ',(SELECT stateName from states where states.stateId=c.stateId),' ',(SELECT countryName from countries where countries.countryId=c.countryId),IF(IFNULL(c.pin,'')='','',CONCAT('-',c.pin)))) AS univAddress,
                                IFNULL((SELECT cityName from city where city.cityId=c.cityId),'') AS univCityName,
                                (SELECT branchName FROM branch br, class cr, student sr 
                                 WHERE br.branchId = cr.branchId AND cr.classId = sr.classId AND sr.studentId = ttm.studentId) AS currBranchName 
                                
                FROM
                        time_table_classes ttc, time_table_labels ttl,
                        institute ii, periodicity p, student stu, study_period sp, class cls,
                        university c, degree d,  branch e, batch b, `session` ses,
                        ".TOTAL_TRANSFERRED_MARKS_TABLE." ttm
                WHERE
                        ttc.timeTableLabelId = ttl.timeTableLabelId AND
                        ttc.classId = cls.classId               AND
                        ii.instituteId = cls.instituteId        AND
                        p.periodicityId = sp.periodicityId      AND
                        ses.sessionId = cls.sessionId           AND
                        cls.batchId = b.batchId                 AND
                        ttm.classId = cls.classId               AND
                        sp.studyPeriodId = cls.studyPeriodId    AND
                        cls.universityId = c.universityId       AND
                        cls.degreeId = d.degreeId               AND
                        cls.branchId = e.branchId               AND
                        cls.studyPeriodId = sp.studyPeriodId    AND
                        cls.instituteId='".$instituteId."'      AND
                        ttm.holdResult = 0                      AND
                        ttm.studentId = stu.studentId
                        $conditions
                GROUP BY
                       ttm.studentId, ttm.classId, ttm.subjectId
                ORDER BY
                    ttm.studentId, ttm.classId ";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }
    
    
    //----------------------------------------------------------------------
    //  THIS FUNCTION IS USED FOR fetching Student Grade Card Information
    //
    // Author :Parveen Sharma
    // Created on : (05.03.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    public function getStudentGradeCardInfo($conditions) {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                        ttm.classId, ttm.studentId, ttm.subjectId, ttm.gradeId,
                        IF(gr.gradeLabel IS NULL, 'I', gr.gradeLabel) as gradeLabel,
                        sub.subjectCode, sub.subjectName,
                        sp.periodName AS studyPeriod,
                        f.credits, gr.gradePoints,
                        IFNULL(cate.categoryName,'".NOT_APPLICABLE_STRING."') AS categoryName,
                        CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) as studentName,
                        IFNULL(stu.rollNo,'".NOT_APPLICABLE_STRING."') AS rollNo,
                        fatherName AS fatherName,
                        CONCAT(c.universityCode,'-',d.degreeCode,'-',e.branchCode) AS programme,
                        CONCAT(SUBSTRING_INDEX(b.startDate,'-',1),'-',SUBSTRING_INDEX(b.endDate,'-',1)) AS timeDuration,
                        IF(corrAddress1 IS NULL,'', CONCAT(corrAddress1,' ',(SELECT cityName from city where city.cityId=stu.corrCityId),' ',(SELECT stateName from states where states.stateId=stu.corrStateId),' ',(SELECT countryName from countries where countries.countryId=stu.corrCountryId),IF(stu.corrPinCode IS NULL OR stu.corrPinCode='','',CONCAT('-',stu.corrPinCode)))) AS corrAddress,
                        IF(permAddress1 IS NULL,'', CONCAT(permAddress1,' ',(SELECT cityName from city where city.cityId=stu.permCityId),' ',(SELECT stateName from states where states.stateId=stu.permStateId),' ',(SELECT countryName from countries where countries.countryId=stu.permCountryId),IF(stu.permPinCode IS NULL OR stu.permPinCode='','',CONCAT('-',stu.permPinCode)))) AS permAddress,
                        sp.studyPeriodId, '-1' AS examType
                  FROM
                        student stu, study_period sp, class cls, university c, degree d,  branch e, batch b,
                        subject_to_class f,".TOTAL_TRANSFERRED_MARKS_TABLE." ttm LEFT JOIN grades gr ON (ttm.gradeId = gr.gradeId AND gr.instituteId = $instituteId),
                        `subject` sub LEFT JOIN subject_category cate ON (sub.subjectCategoryId = cate.subjectCategoryId )
                  WHERE
                        f.hasParentCategory = 0 AND  
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
                        cls.instituteId='".$instituteId."'      
                        $conditions
                  GROUP BY
                        ttm.studentId, ttm.classId, ttm.subjectId
                  UNION
                  SELECT
                        ttm.classId, ttm.studentId, ttm.subjectId, ttm.gradeId,
                        IF(gr.gradeLabel IS NULL, 'I', gr.gradeLabel) as gradeLabel,
                        sub.subjectCode, sub.subjectName,
                        sp.periodName AS studyPeriod,
                        f1.credits AS credits, gr.gradePoints,
                        IFNULL(cate.categoryName,'".NOT_APPLICABLE_STRING."') AS categoryName,
                        CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) as studentName,
                        IFNULL(stu.rollNo,'".NOT_APPLICABLE_STRING."') AS rollNo,
                        fatherName AS fatherName,
                        CONCAT(c.universityCode,'-',d.degreeCode,'-',e.branchCode) AS programme,
                        CONCAT(SUBSTRING_INDEX(b.startDate,'-',1),'-',SUBSTRING_INDEX(b.endDate,'-',1)) AS timeDuration,
                        IF(corrAddress1 IS NULL,'', CONCAT(corrAddress1,' ',(SELECT cityName from city where city.cityId=stu.corrCityId),' ',(SELECT stateName from states where states.stateId=stu.corrStateId),' ',(SELECT countryName from countries where countries.countryId=stu.corrCountryId),IF(stu.corrPinCode IS NULL OR stu.corrPinCode='','',CONCAT('-',stu.corrPinCode)))) AS corrAddress,
                        IF(permAddress1 IS NULL,'', CONCAT(permAddress1,' ',(SELECT cityName from city where city.cityId=stu.permCityId),' ',(SELECT stateName from states where states.stateId=stu.permStateId),' ',(SELECT countryName from countries where countries.countryId=stu.permCountryId),IF(stu.permPinCode IS NULL OR stu.permPinCode='','',CONCAT('-',stu.permPinCode)))) AS permAddress,
                        sp.studyPeriodId, '-1' AS examType
                  FROM
                        student stu, study_period sp, class cls, university c, degree d,  branch e, batch b, subject_to_class f1, optional_subject_to_class os,
                        student_optional_subject f,".TOTAL_TRANSFERRED_MARKS_TABLE." ttm LEFT JOIN grades gr ON (ttm.gradeId = gr.gradeId AND gr.instituteId = $instituteId),
                        `subject` sub LEFT JOIN subject_category cate ON (sub.subjectCategoryId = cate.subjectCategoryId )
                  WHERE
                        f1.hasParentCategory = 1 AND
                        f1.subjectId = os.parentOfSubjectId     AND
                        os.subjectId = f.subjectId              AND
                        os.classId   = f.classId                AND
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
                        cls.instituteId='".$instituteId."'      
                        $conditions
                  GROUP BY
                        ttm.studentId, ttm.classId, ttm.subjectId       
                  ORDER BY                                
                        studentId, classId, subjectCode ";

        return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }
    
    
    //----------------------------------------------------------------------------------------------------------
    // THIS FUNCTION IS USED FOR getting Total classwise GPA
    //
    // Author :Parveen Sharma
    // Created on : (22.12.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------------
      public function getStudentClasswiseGPA($condition='') {

            if($condition == '') {
               $cond = " WHERE  s1.studentId = s.studentId ";
            }
            else {
               $cond = " WHERE s1.studentId = s.studentId AND ".$condition;
            }
            $query = "SELECT
                            s.classId, className,
                            IF(s.credits=0,0,(s.gradeIntoCredits/s.credits)) AS gpa, FORMAT(credits,0) AS credits
                      FROM
                            `student` s1, `student_cgpa` s  LEFT JOIN `class` c ON s.classId = c.classId
                      $cond ";

            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }  
      
      
      public function getInstituteList($conditions='', $limit = '', $orderBy=' ins.instituteName') {
     
        $query = "SELECT 
                        ins.instituteId,ins.instituteCode,ins.instituteName,ins.instituteAbbr,ins.instituteLogo,
                        ins.instituteAddress1,ins.instituteAddress2,ct.cityName,
                        st.stateName,ins.pin,cn.countryName,ins.employeeId,ins.employeePhone,
                        ins.instituteEmail,ins.instituteWebsite
                  FROM 
                        institute ins, states st, city ct,countries cn 
                  WHERE 
                        ins.stateId=st.stateId AND ins.cityId=ct.cityId  AND ins.countryId=cn.countryId
                        $conditions 
                  ORDER BY 
                        $orderBy $limit" ;
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    public function getUniversityList($conditions='', $limit = '', $orderBy=' un.universityName') {   
     
        $query = "SELECT 
                        un.universityId,un.universityCode,un.universityName,un.universityAbbr,un.universityLogo,
                        un.universityAddress1,un.universityAddress2,ct.cityName,
                        st.stateName,un.pin,cn.countryName,un.contactPerson,un.contactNumber,
                        un.universityEmail,un.universityWebsite
                   FROM 
                        university un, states st,city ct,countries cn
                   WHERE 
                        un.stateId=st.stateId AND un.cityId=ct.cityId  AND un.countryId=cn.countryId
                        $conditions 
                   ORDER BY 
                        $orderBy $limit" ;
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    public function getStudentClasswiseCGPA($condition='') {
        
        $query = "SELECT
                       sg.classId, c.className, sg.studentId, 
                       sg.previousCredit, sg.previousGradePoint, sg.previousCreditEarned,
                       sg.lessCredit, sg.lessGradePoint, sg.lessCreditEarned,  
                       sg.currentCredit, sg.currentGradePoint, sg.currentCreditEarned,
                       sg.netCredit, sg.netGradePoint, sg.netCreditEarned,
                       sg.gradeIntoCredits, sg.credits, sg.cgpa, sg.gpa
                 FROM
                      `student` s, `student_cgpa` sg , class c 
                 WHERE
                       s.studentId = sg.studentId AND
                       sg.classId = c.classId
                 $condition
                 ORDER BY
                       sg.studentId, sg.classId ";
                 

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
}

?>
