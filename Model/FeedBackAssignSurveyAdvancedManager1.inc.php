<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
//
//
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FeedBackAssignSurveyAdvancedManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "CityManager" CLASS
//
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "CityManager" CLASS
//
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
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


    public function getExistingMappingId($labelId,$catId,$questionSetId,$roleId,$classId='',$subjectIds=''){
         $classConditions='';
        $subjectConditions='';
        if($classId!=''){
            $classConditions=" AND classId=$classId";
        }
        if($subjectIds!=''){
            $subjectConditions=" AND feedbackMappingId IN (SELECT feedbackMappingId FROM feedbackadv_to_class_subjects WHERE subjectId IN (".$subjectIds.") )";
        }

        $query="SELECT
                       DISTINCT feedbackMappingId
                FROM
                       feedbackadv_survey_mapping
                WHERE
                    feedbackSurveyId='$labelId'
                    AND feedbackCategoryId='$catId'
                    AND feedbackQuestionSetId='$questionSetId'
                    AND roleId='$roleId'
                    $classConditions
                    $subjectConditions
                ";
        return SystemDatabaseManager::getInstance()->executeQuery($query);
    }

//----------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR inserting data into feedbackadv_survey_mapping table
// Author :Dipanjan Bhattacharjee
// Created on : (18.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------
    public function insertMappingData($labelId,$catId,$questionSetId,$classId,$roleId,$visibleFrom,$visibleTo) {

       $query="INSERT INTO
                          feedbackadv_survey_mapping
                SET
                    feedbackSurveyId='$labelId',
                    feedbackCategoryId='$catId',
                    feedbackQuestionSetId='$questionSetId',
                    classId=$classId,
                    roleId='$roleId',
                    visibleFrom='$visibleFrom',
                    visibleTo='$visibleTo'
                ";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

    public function deleteMappingData($labelId,$catId,$questionSetId,$roleId,$classId='',$subjectIds='') {
        $classConditions='';
        $subjectConditions='';
        if($classId!=''){
            $classConditions=" AND classId=$classId";
        }
        if($subjectIds!=''){
            $subjectConditions=" AND feedbackMappingId IN (SELECT feedbackMappingId FROM feedbackadv_to_class_subjects WHERE subjectId IN (".$subjectIds.") )";
        }
        $query="set foreign_key_checks=0";
        if(SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query)==false){
			return false;
		}
        $query="DELETE FROM
                          feedbackadv_survey_mapping
                WHERE
                    feedbackSurveyId='$labelId'
                    AND feedbackCategoryId='$catId'
                    AND feedbackQuestionSetId='$questionSetId'
                    AND roleId='$roleId'
                    $classConditions
                    $subjectConditions
                ";
        if(SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query)==false){
			return false;
		}
		$query="set foreign_key_checks=1";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function insertSurveyVisibleToUsersData($insertQuery) {

        $query="INSERT INTO
                           feedbackadv_survey_visible_to_users
                ( feedbackMappingId,userId,roleId ) VALUES $insertQuery
                ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function deleteAssignedUsersData($allStudents,$labelId,$catId,$questionSetId,$roleId,$excludedUsers,$classId='',$subjectIds='') {
        $classConditions='';
        $subjectConditions='';
        if($classId!=''){
            $classConditions=" AND classId=$classId";
        }
        if($subjectIds!=''){
            $subjectConditions=" AND feedbackMappingId IN (SELECT feedbackMappingId FROM feedbackadv_to_class_subjects WHERE subjectId IN (".$subjectIds.") )";
        }

  
        $query="DELETE FROM
                          feedbackadv_survey_visible_to_users
                WHERE
                    userId NOT IN ($excludedUsers)
                    AND userId IN ($allStudents)
                    AND feedbackMappingId IN
                                            (
                                              SELECT
                                                    feedbackMappingId
                                              FROM
                                                    feedbackadv_survey_mapping
                                              WHERE
                                                    feedbackSurveyId='$labelId'
                                                    AND feedbackCategoryId='$catId'
                                                    AND feedbackQuestionSetId='$questionSetId'
                                                    AND roleId=$roleId
                                                    $classConditions
                                                    $subjectConditions
                                              )
                ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }


    public function deleteClassSubjectMapping($mappingId) {

        $query="DELETE FROM
                          feedbackadv_to_class_subjects
                WHERE
                    feedbackMappingId='$mappingId'
                ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function doClassSubjectMapping($subjectInsertString) {

        $query="INSERT INTO
                           feedbackadv_to_class_subjects
                ( feedbackMappingId,subjectId ) VALUES $subjectInsertString
                ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }


   public function getGivenFeedbackQuestions($labelId,$catId,$questionSetId,$roleId,$classId='',$subjectIds='') {
        $classConditions='';
        $subjectConditions='';
        if($classId!=''){
            $classConditions=" AND fm.classId=$classId";
        }
        if($subjectIds!=''){
            $subjectConditions=" AND fm.feedbackMappingId IN (SELECT feedbackMappingId FROM feedbackadv_to_class_subjects WHERE subjectId IN (".$subjectIds.") )";
        }
        $query = "SELECT
                        fa.userId,
                        fm.feedbackMappingId
                  FROM
                        feedbackadv_survey_mapping fm,
                        feedbackadv_survey_answer fa
                  WHERE
                        fm.feedbackMappingId=fa.feedbackMappingId
                        AND fm.feedbackSurveyId=$labelId
                        AND fm.feedbackCategoryId=$catId
                        AND fm.feedbackQuestionSetId=$questionSetId
                        AND fm.roleId=$roleId
                        $classConditions
                        $subjectConditions
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function getOldMappingId($labelId,$catId,$questionSetId,$classId,$roleId,$subjectIds='') {

        if($subjectIds!=''){
           $subjectConditions=" AND feedbackMappingId IN (SELECT feedbackMappingId FROM feedbackadv_to_class_subjects WHERE subjectId IN (".$subjectIds.") )";
        }
        $query = "SELECT
                        DISTINCT feedbackMappingId
                  FROM
                        feedbackadv_survey_mapping
                  WHERE
                        feedbackSurveyId=$labelId
                        AND feedbackCategoryId=$catId
                        AND feedbackQuestionSetId=$questionSetId
                        AND classId=$classId
                        AND roleId=$roleId
                        $subjectConditions
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Parent Category
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (09.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getSurveyLabel($conditions='') {
        global $sessionHandler;
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        fs.feedbackSurveyId,
                        fs.feedbackSurveyLabel
                  FROM
                        feedbackadv_survey fs,time_table_labels ttl
                  WHERE
                        fs.timeTableLabelId=ttl.timeTableLabelId
                        AND ttl.instituteId=$instituteId
                        AND ttl.sessionId=$sessionId
                        AND fs.isActive=1
                        $conditions
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function getMappedSurveyCategories($conditions='') {
        global $sessionHandler;
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        fc.feedbackCategoryId,
                        fc.feedbackCategoryName,
                        fs.roleId,
                        fs.roleId AS applicableTo
                  FROM
                        feedbackadv_survey fs,time_table_labels ttl,
                        feedbackadv_category fc,feedbackadv_to_question  fq
                  WHERE
                        fc.feedbackCategoryId=fq.feedbackCategoryId
                        AND fq.feedbackSurveyId=fs.feedbackSurveyId
                        AND fs.timeTableLabelId=ttl.timeTableLabelId
                        AND ttl.instituteId=$instituteId
                        AND ttl.sessionId=$sessionId
                        AND fs.isActive=1
                        $conditions
                  GROUP BY fc.feedbackCategoryId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

     public function getMappedQuestionSet($conditions='') {
        global $sessionHandler;
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                        fqs.feedbackQuestionSetId,
                        fqs.feedbackQuestionSetName
                  FROM
                        feedbackadv_survey fs,time_table_labels ttl,
                        feedbackadv_category fc,feedbackadv_to_question  fq,
                        feedbackadv_question_set fqs
                  WHERE
                        fqs.feedbackQuestionSetId=fq.feedbackQuestionSetId
                        AND fc.feedbackCategoryId=fq.feedbackCategoryId
                        AND fq.feedbackSurveyId=fs.feedbackSurveyId
                        AND fs.timeTableLabelId=ttl.timeTableLabelId
                        AND ttl.instituteId=$instituteId
                        AND ttl.sessionId=$sessionId
                        AND fqs.instituteId=$instituteId
                        AND fs.isActive=1
                        $conditions
                  GROUP BY fqs.feedbackQuestionSetId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getCategoryRelation($conditions='') {

        $query = "SELECT
                        fc.feedbackType,
                        IF(st.subjectTypeName IS NULL,'', CONCAT('(',st.subjectTypeName,')')) AS subjectTypeName,
                        IF(st.subjectTypeId IS NULL,-1,st.subjectTypeId ) AS subjectTypeId
                  FROM
                        feedbackadv_category fc
                        LEFT JOIN subject_type st ON st.subjectTypeId=fc.subjectTypeId
                        $conditions
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getSubjectTypeClasses($conditions='') {
       /*
       $query = "SELECT
                        c.classId,
                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
                  FROM
                        `class` c,time_table_classes ttc,
                         subject_to_class stc,`subject` s,
                         student st
                  WHERE
                         ttc.classId=c.classId
                         AND c.classId=stc.classId
                         AND stc.subjectId=s.subjectId
                         AND c.isActive=1
                         AND c.classId=st.classId
                        $conditions
                  GROUP BY c.classId
                 ";
        */
        $query = "SELECT
                        c.classId,
                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
                  FROM
                        `class` c,feedbackadv_teacher_mapping ftm,
                         `subject` s
                  WHERE
                         ftm.classId=c.classId
                         AND ftm.subjectId=s.subjectId
                         AND c.isActive=1
                        $conditions
                  GROUP BY c.classId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

   public function getSubjectTypeSubjects($conditions='') {
       /*
        $query = "SELECT
                        s.subjectId,
                        s.subjectName,
                        s.subjectCode
                  FROM
                        `class` c,time_table_classes ttc,
                         subject_to_class stc,`subject` s
                  WHERE
                         ttc.classId=c.classId
                         AND c.classId=stc.classId
                         AND stc.subjectId=s.subjectId
                         AND c.isActive=1
                        $conditions
                  GROUP BY s.subjectId
                 ";
        */
        $query = "SELECT
                        s.subjectId,
                        s.subjectName,
                        s.subjectCode
                  FROM
                        `class` c,feedbackadv_teacher_mapping ftm,
                         `subject` s
                  WHERE
                         ftm.classId=c.classId
                         AND ftm.subjectId=s.subjectId
                         AND c.isActive=1
                        $conditions
                  GROUP BY s.subjectId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

     public function getAllSubjectTypeClasses($conditions='') {
       
         $query = "SELECT
                        DISTINCT c.classId,
                        SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3) AS className
                  FROM
                        `class` c,time_table_classes ttc,
                         subject_to_class stc,`subject` s,
                         student st
                  WHERE
                         ttc.classId=c.classId
                         AND c.classId=stc.classId
                         AND stc.subjectId=s.subjectId
                         AND c.isActive=1
                         AND c.classId=st.classId
                        $conditions
                  GROUP BY c.classId
                 ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getAllSubjectTypeSubjects($conditions='') {

        $query = "SELECT
                        s.subjectId,
                        s.subjectName,
                        s.subjectCode
                  FROM
                        `class` c,time_table_classes ttc,
                         subject_to_class stc,`subject` s
                  WHERE
                         ttc.classId=c.classId
                         AND c.classId=stc.classId
                         AND stc.subjectId=s.subjectId
                         AND c.isActive=1
                        $conditions
                  GROUP BY s.subjectId ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Category Informations
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (09.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------
    public function getFeedbackCategory($conditions='') {

        $query = "SELECT
                        *
                  FROM
                        feedbackadv_category
                  $conditions
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

public function getLabelApplicableTo($timeTableId,$labelId){
   global $sessionHandler;
   $sessionId   = $sessionHandler->getSessionVariable('SessionId');
   $instituteId = $sessionHandler->getSessionVariable('InstituteId');

   $query="
           SELECT
                  f.feedbackSurveyId,
                  f.roleId
           FROM
                  feedbackadv_survey f,time_table_labels ttl
           WHERE
                  f.timeTableLabelId=ttl.timeTableLabelId
                  AND ttl.instituteId=$instituteId
                  AND ttl.sessionId=$sessionId
                  AND f.isActive=1
                  AND f.feedbackSurveyId=$labelId
                  AND f.timeTableLabelId=$timeTableId
         ";
   return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

}

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Adv. Category List
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Dipanjan Bhattacharjee
// Created on : (18.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getEmployeeList($conditions='', $limit = '', $orderBy=' e.employeeName',$lableId=-1,$catId=-1,$questionSetId=-1) {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

       $query="
                 SELECT
                        e.employeeId,
                        TRIM(e.employeeCode) AS employeeCode,
                        TRIM(e.employeeName) AS employeeName,
                        u.userId,
                        IF(fsv.userId IS NULL,-1,1) AS empAssigned,
                        IF(d.designationName IS NULL OR d.designationName='','".NOT_APPLICABLE_STRING."',d.designationName) AS designationName
                 FROM
                        employee e
                        LEFT JOIN designation d ON d.designationId=e.designationId
                        INNER JOIN `user` u ON ( u.userId=e.userId AND u.instituteId=$instituteId )
                        LEFT  JOIN feedbackadv_survey_visible_to_users fsv ON ( fsv.userId=u.userId AND fsv.feedbackMappingId IN ( SELECT fsm.feedbackMappingId FROM feedbackadv_survey_mapping fsm WHERE fsm.feedbackSurveyId=$lableId AND fsm.feedbackCategoryId=$catId AND fsm.feedbackQuestionSetId=$questionSetId ) )
                 WHERE
                        1
                        $conditions
                 ORDER BY $orderBy
                 $limit
              ";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function getTotalEmployee($conditions='',$lableId=-1,$catId=-1,$questionSetId=-1) {
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

        $query="
                 SELECT
                        e.userId,
                        IF(fsv.userId IS NULL,-1,1) AS empAssigned
                 FROM
                        employee e
                        LEFT JOIN designation d ON d.designationId=e.designationId
                        INNER JOIN `user` u ON ( u.userId=e.userId AND u.instituteId=$instituteId )
                        LEFT  JOIN feedbackadv_survey_visible_to_users fsv ON ( fsv.userId=u.userId AND fsv.feedbackMappingId IN ( SELECT fsm.feedbackMappingId FROM feedbackadv_survey_mapping fsm WHERE fsm.feedbackSurveyId=$lableId AND fsm.feedbackCategoryId=$catId AND fsm.feedbackQuestionSetId=$questionSetId ) )
                 WHERE
                        1
                        $conditions
              ";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function getStudentList($conditions='', $limit = '', $orderBy=' studentName',$lableId=-1,$catId=-1,$questionSetId=-1,$classId=-1,$subjectId=-1) {

       global $sessionHandler;
       global $REQUEST_DATA;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');
       $classCondition='';
       $subjectCondition='';
       if($classId!=-1 and $subjectId!=-1){
           $classCondition=' AND fsm.classId='.$classId;
           $subjectCondition='AND fsm.feedbackMappingId IN (
                                SELECT
                                    feedbackMappingId
                                FROM
                                    feedbackadv_to_class_subjects
                                WHERE
                                    subjectId IN ('.$subjectId.')
                             )';
       }

       $query = "SELECT
                        s.studentId,
                        CONCAT(s.firstName,' ',s.lastName) AS studentName,
                        IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        u.userId,
                        IF(fsv.userId IS NULL,-1,1) AS studentAssigned,
                        deg.degreeAbbr,
                        br.branchCode,
                        stp.periodName
                  FROM
                     student s
                     INNER JOIN `user` u ON ( u.userId=s.userId AND u.instituteId=$instituteId )
                     LEFT  JOIN feedbackadv_survey_visible_to_users fsv ON (
                                     fsv.userId=u.userId
                                     AND fsv.feedbackMappingId IN (
                                          SELECT
                                                fsm.feedbackMappingId
                                          FROM
                                                feedbackadv_survey_mapping fsm
                                          WHERE
                                               fsm.feedbackSurveyId=$lableId
                                               AND fsm.feedbackCategoryId=$catId
                                               AND fsm.feedbackQuestionSetId=$questionSetId
                                               $classCondition
                                               $subjectCondition
                                          )
                                     )
                     INNER JOIN class cl   ON (cl.classId=s.classId)
                     INNER JOIN degree deg ON cl.degreeId=deg.degreeId
                     INNER JOIN branch br  ON cl.branchId=br.branchId
                     INNER JOIN study_period stp ON cl.studyPeriodId=stp.studyPeriodId
                  WHERE
                    cl.instituteId=".$instituteId."
                    AND cl.sessionId=".$sessionId."
                    $conditions
                    GROUP BY s.studentId
                    ORDER BY $orderBy
                    $limit
                ";

       //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getTotalStudent($conditions='', $lableId=-1,$catId=-1,$questionSetId=-1,$classId=-1,$subjectId=-1) {

       global $sessionHandler;
       global $REQUEST_DATA;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');
       $classCondition='';
       $subjectCondition='';
       if($classId!=-1 and $subjectId!=-1){
           $classCondition=' AND fsm.classId='.$classId;
           $subjectCondition='AND fsm.feedbackMappingId IN (
                                SELECT
                                    feedbackMappingId
                                FROM
                                    feedbackadv_to_class_subjects
                                WHERE
                                    subjectId IN ('.$subjectId.')
                             )';
       }

      $query = "SELECT
                        u.userId,
                        IF(fsv.userId IS NULL,-1,1) AS studentAssigned
                  FROM
                     student s
                     INNER JOIN `user` u ON ( u.userId=s.userId AND u.instituteId=$instituteId )
                     LEFT  JOIN feedbackadv_survey_visible_to_users fsv ON (
                                     fsv.userId=u.userId
                                     AND fsv.feedbackMappingId IN (
                                          SELECT
                                                fsm.feedbackMappingId
                                          FROM
                                                feedbackadv_survey_mapping fsm
                                          WHERE
                                               fsm.feedbackSurveyId=$lableId
                                               AND fsm.feedbackCategoryId=$catId
                                               AND fsm.feedbackQuestionSetId=$questionSetId
                                               $classCondition
                                               $subjectCondition
                                          )
                                     )
                     INNER JOIN class cl   ON (cl.classId=s.classId)
                     INNER JOIN degree deg ON cl.degreeId=deg.degreeId
                     INNER JOIN branch br  ON cl.branchId=br.branchId
                     INNER JOIN study_period stp ON cl.studyPeriodId=stp.studyPeriodId
                  WHERE
                    cl.instituteId=".$instituteId."
                    AND cl.sessionId=".$sessionId."
                    $conditions
                    GROUP BY s.studentId
                ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Adv. Category List
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Dipanjan Bhattacharjee
// Created on : (09.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    public function getFeedbackCategoryList($conditions='', $limit = '', $orderBy=' fc.feedbackCategoryName') {
        global $sessionHandler;
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        /*
        $query = "SELECT
                         fc.feedbackCategoryId,
                         fc.feedbackCategoryName,
                         IF(fc1.feedbackCategoryName IS NULL,'',fc1.feedbackCategoryName) AS parentCategoryName,
                         fc.parentFeedbackCategoryId,
                         fc.feedbackType,
                         fc.printOrder,
                         st.subjectTypeName,
                         fs.feedbackSurveyLabel
                 FROM
                         time_table_labels ttl,
                         feedbackadv_survey fs,
                         feedbackadv_category fc
                         LEFT JOIN subject_type st ON fc.subjectTypeId=st.subjectTypeId
                         LEFT JOIN feedbackadv_category fc1 ON fc.parentFeedbackCategoryId=fc1.feedbackCategoryId
                 WHERE
                         fc.feedbackSurveyId=fs.feedbackSurveyId
                         AND fs.timeTableLabelId=ttl.timeTableLabelId
                         AND ttl.instituteId=$instituteId
                         AND ttl.sessionId=$sessionId
                 $conditions
                 ORDER BY $orderBy
                 $limit";
        */
        $query = "SELECT
                         fc.feedbackCategoryId,
                         fc.feedbackCategoryName,
                         IF(fc1.feedbackCategoryName IS NULL,'',fc1.feedbackCategoryName) AS parentCategoryName,
                         fc.parentFeedbackCategoryId,
                         fc.feedbackType,
                         fc.printOrder,
                         st.subjectTypeName
                 FROM
                         feedbackadv_category fc
                         LEFT JOIN subject_type st ON fc.subjectTypeId=st.subjectTypeId
                         LEFT JOIN feedbackadv_category fc1 ON fc.parentFeedbackCategoryId=fc1.feedbackCategoryId
                 $conditions
                 ORDER BY $orderBy
                 $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Total Adv. Category Count
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (09.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------
    public function getTotalFeedbackCategory($conditions='') {
        global $sessionHandler;
        $sessionId   = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        /*
        $query = "SELECT
                         fc.feedbackCategoryId,
                         IF(fc1.feedbackCategoryName IS NULL,'',fc1.feedbackCategoryName) AS parentCategoryName
                  FROM
                         time_table_labels ttl,
                         feedbackadv_survey fs,
                         feedbackadv_category fc
                         LEFT JOIN subject_type st ON fc.subjectTypeId=st.subjectTypeId
                         LEFT JOIN feedbackadv_category fc1 ON fc.parentFeedbackCategoryId=fc1.feedbackCategoryId
                  WHERE
                         fc.feedbackSurveyId=fs.feedbackSurveyId
                         AND fs.timeTableLabelId=ttl.timeTableLabelId
                         AND ttl.instituteId=$instituteId
                         AND ttl.sessionId=$sessionId
                  $conditions ";
        */

        $query = "SELECT
                         fc.feedbackCategoryId,
                         IF(fc1.feedbackCategoryName IS NULL,'',fc1.feedbackCategoryName) AS parentCategoryName
                  FROM
                         feedbackadv_category fc
                         LEFT JOIN subject_type st ON fc.subjectTypeId=st.subjectTypeId
                         LEFT JOIN feedbackadv_category fc1 ON fc.parentFeedbackCategoryId=fc1.feedbackCategoryId
                  $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    public function getFeedbackStudentStatus($conditions='') {
        $query = "
                   SELECT
                        DISTINCT userId
                   FROM
                        feedbackadv_student_status
                        $conditions
                  ";

       return SystemDatabaseManager::getInstance()->executeQuery($query, "Query: $query");
    }

  public function insertFeedbackStudentStatus($insertString) {
        $query = "
                   INSERT INTO
                              feedbackadv_student_status
                               ( userId, status )
                   VALUES
                           $insertString
                  ";

       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }



/*These functions are needed for displaying repors*/
public function displayStudentFeedbackStatusList($conditions='',$limit,$orderBy) {
   global $sessionHandler;
   global $REQUEST_DATA;
   $instituteId=$sessionHandler->getSessionVariable('InstituteId');
   $sessionId=$sessionHandler->getSessionVariable('SessionId');

    $query = "SELECT
                        s.studentId,
                        CONCAT(s.firstName,' ',s.lastName) AS studentName,
                        IF(s.rollNo IS NULL OR s.rollNo='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(s.universityRollNo IS NULL OR s.universityRollNo='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        u.userId,
                        IF(fsv.userId IS NULL,-1,1) AS studentAssigned,
                        cl.className,
                        fss.status
                  FROM
                     student s
                     INNER JOIN `user` u ON ( u.userId=s.userId AND u.instituteId=$instituteId )
                     INNER JOIN feedbackadv_survey_visible_to_users fsv ON ( fsv.userId=s.userId )
                     INNER JOIN feedbackadv_student_status fss ON ( fss.userId=s.userId )
                     INNER JOIN class cl  ON (cl.classId=s.classId)
                  WHERE
                    cl.instituteId=".$instituteId."
                    AND cl.sessionId=".$sessionId."
                    $conditions
                    GROUP BY s.userId
                    ORDER BY $orderBy
                    $limit
                ";

       //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function displayStudentFeedbackStatusTotal($conditions='') {
       global $sessionHandler;
       global $REQUEST_DATA;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');

      $query = "SELECT
                        COUNT(*) AS totalRecords
                  FROM
                     student s
                     INNER JOIN `user` u ON ( u.userId=s.userId AND u.instituteId=$instituteId )
                     INNER JOIN feedbackadv_survey_visible_to_users fsv ON ( fsv.userId=s.userId )
                     INNER JOIN feedbackadv_student_status fss ON ( fss.userId=s.userId )
                     INNER JOIN class cl  ON (cl.classId=s.classId)
                  WHERE
                    cl.instituteId=".$instituteId."
                    AND cl.sessionId=".$sessionId."
                    $conditions
                    GROUP BY s.userId
                ";

       //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function getStudentInformation($conditions='') {
    $query="
            SELECT
                   CONCAT(IF(firstName IS NULL OR firstName='','',firstName),' ',IF(lastName IS NULL OR lastName='','',lastName)) AS studentName,
                   userId
            FROM
                  student
                  $conditions
           ";
   return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}



  public function changeStudentStatus($userId,$status) {
        $query = "
                   UPDATE
                         feedbackadv_student_status
                   SET
                         status=$status
                   WHERE
                         userId=$userId
                  ";
		
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

 public function changeStudentStatus1($condition,$status) {
        $query = "
                   UPDATE
                         feedback_student_status
                   SET
                         status=$status
                   WHERE
                         $condition
                  ";
		
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }


  public function doStatusLogEntry1($userId,$reason,$logStatus,$doneByUserId) {

       $query = "UPDATE
                        feedback_student_status
                  SET          
                        message='$reason'
                  WHERE
                        studentId=$userId ";

       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
  }

   public function changeStudentStatusInBlock1($condition='',$status) {
       
        $query = "UPDATE
                       feedback_student_status
                  SET
                       status=$status
                  WHERE
                      $condition";

       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

   public function changeStudentStatusInBlock($userIds,$status) {
        $query = "
                   UPDATE
                         feedbackadv_student_status
                   SET
                         status=$status
                   WHERE
                         userId IN ( $userIds )
                  ";

       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

  public function doStatusLogEntry($userId,$reason,$logStatus,$doneByUserId) {
        $query = "
                   INSERT INTO
                              feedbackadv_student_status_log
                               (userId,logDescription,logStatus,logDate,doneByUserId)
                              VALUES
                               ($userId,'".$reason."',$logStatus,CURDATE(),$doneByUserId)
                  ";

       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function doStatusLogEntryInBulk($insertString) {
        $query = "
                   INSERT INTO
                              feedbackadv_student_status_log
                               (userId,logDescription,logStatus,logDate,doneByUserId)
                              VALUES
                               $insertString
                  ";

       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }




public function totalAnsweresForMappedQuestionsForStudents($userId,$roleId) {

    $query ="SELECT
                   COUNT(*) as cnt
             FROM
                   feedbackadv_survey_answer
             WHERE
                   userId=$userId
                   AND feedbackMappingId IN
                                          (
                                            SELECT
                                                  DISTINCT feedbackMappingId
                                            FROM
                                                  feedbackadv_survey_visible_to_users
                                            WHERE
                                                  userId=$userId
                                                  AND roleId=$roleId
                                                  AND feedbackMappingId IN
                                                                         (
                                                                           SELECT
                                                                                  DISTINCT feedbackMappingId
                                                                           FROM
                                                                                  feedbackadv_survey_mapping
                                                                           WHERE
                                                                                  roleId=$roleId
                                                                         )
                                          )
                 AND answerSetOptionId!=-1
             ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



/*These functions are needed for displaying repors*/
public function displayStudentFeedbackLabelWiseList($conditions='',$userIds,$labelId,$limit='',$orderBy='studentName') {
   global $sessionHandler;
   $instituteId=$sessionHandler->getSessionVariable('InstituteId');
   $sessionId=$sessionHandler->getSessionVariable('SessionId');
   
   if($orderBy=='') {
     $orderBy='studentName';  
   }

   $query =  "SELECT
                     t.feedbackSurveyLabel, t.studentName, t.rollNo, t.userId,
                     t.universityRollNo,t.className,
                     (IFNULL(t.totalQuestions1,0)+IFNULL(t.totalQuestions2,0)) AS totalQuestions,
                     t.questionsAnswered,
                     IF(t.questionsAnswered = 0, 'No', if(t.questionsAnswered = IFNULL(t.totalQuestions1,0)+IFNULL(t.totalQuestions2,0),'Yes','Partial')) AS isCompleted,
                     IF(t.questionsAnswered = 0, 0, if(t.questionsAnswered = IFNULL(t.totalQuestions1,0)+IFNULL(t.totalQuestions2,0),1,2)) AS isCompletedFlag,
                     IF(t.extendTo>CURRENT_DATE(),'No','Yes') AS dateOver,
                     IF(t.extendTo>CURRENT_DATE(),0,1) AS dateOverFlag,
                     t.status AS currentStatus,
                     t.userId
              FROM (
                SELECT
                     fs.feedbackSurveyLabel,
                     fs.extendTo,
                     fss.status,
                     CONCAT(s.firstName,' ',s.lastName) AS studentName,
                     IF(s.rollNo IS NULL OR s.rollNo='','---',s.rollNo) AS rollNo,
                     IF(s.universityRollNo IS NULL OR s.universityRollNo='','---',s.universityRollNo) AS universityRollNo,
                     cl.className,
                     s.userId,
                            (
                                SELECT sum( t2.cnt )
                                FROM (
                                    SELECT COUNT( * ) * t.teacherCnt AS cnt
                                    FROM (
                                        SELECT
                                               DISTINCT COUNT(*) AS teacherCnt, ftm.subjectId
                                        FROM
                                               feedbackadv_teacher_mapping ftm
                                        WHERE CONCAT_WS( '~', ftm.feedbackSurveyId, ftm.classId )
                                         IN (
                                             SELECT
                                                   CONCAT_WS( '~', $labelId, sg.classId ) AS c
                                             FROM
                                                   student_groups sg, student ss
                                             WHERE
                                                    ss.classId = sg.classId
                                                    AND ss.studentId = sg.studentId
                                                    AND ss.userId IN ($userIds)
                                             )
                                             GROUP BY ftm.feedbackSurveyId, ftm.subjectId, ftm.classId,ftm.groupId
                                         ) AS t,       
                                         feedbackadv_to_question ftq,
                                         feedbackadv_survey_visible_to_users fsvu,
                                         feedbackadv_questions fq, feedbackadv_survey_mapping fsm
                                         LEFT JOIN feedbackadv_to_class_subjects ftcs ON ( ftcs.feedbackMappingId = fsm.feedbackMappingId )
                                         WHERE fsvu.feedbackMappingId = fsm.feedbackMappingId
                                         AND fsm.feedbackSurveyId = ftq.feedbackSurveyId
                                         AND fsm.feedbackCategoryId = ftq.feedbackCategoryId
                                         AND fsm.feedbackQuestionSetId = ftq.feedbackQuestionSetId
                                         AND ftq.feedbackQuestionId = fq.feedbackQuestionId
                                         AND fsm.feedbackSurveyId =$labelId
                                         AND fsvu.userId IN ($userIds)
                                         AND fsvu.roleId =4
                                         AND ftcs.subjectId IS NOT NULL
                                         AND ftcs.subjectId = t.subjectId
                                         GROUP BY ftcs.subjectId
                                        ) AS t2
                                    ) AS totalQuestions1,
                                    (SELECT
                                          COUNT(*) AS cnt
                                    FROM
                                            feedbackadv_to_question  ftq,
                                            feedbackadv_survey_visible_to_users fsvu,
                                            feedbackadv_questions fq,
                                            feedbackadv_survey_mapping fsm
                                            LEFT JOIN  feedbackadv_to_class_subjects ftcs ON (ftcs.feedbackMappingId=fsm.feedbackMappingId)
                                       WHERE
                                            fsvu.feedbackMappingId=fsm.feedbackMappingId
                                            AND fsm.feedbackSurveyId=ftq.feedbackSurveyId
                                            AND fsm.feedbackCategoryId=ftq.feedbackCategoryId
                                            AND fsm.feedbackQuestionSetId=ftq.feedbackQuestionSetId
                                            AND ftq.feedbackQuestionId=fq.feedbackQuestionId
                                            AND fsm.feedbackSurveyId=$labelId
                                            AND fsvu.userId IN ($userIds)
                                            AND fsvu.roleId=4
                                            and ftcs.subjectId IS NULL
                                     ) AS  totalQuestions2,
                     (
                       IFNULL((SELECT
                               COUNT(*) as cnt
                         FROM
                               feedbackadv_survey_answer
                         WHERE
                               userId=s.userId
                               AND feedbackMappingId IN
                                    (
                                        SELECT
                                               DISTINCT feedbackMappingId
                                        FROM
                                               feedbackadv_survey_visible_to_users
                                        WHERE
                                               userId=s.userId
                                               AND roleId=4
                                               AND feedbackMappingId IN
                                                                 (
                                                                   SELECT
                                                                          DISTINCT feedbackMappingId
                                                                   FROM
                                                                          feedbackadv_survey_mapping
                                                                   WHERE
                                                                          feedbackSurveyId=$labelId
                                                                          AND roleId=4
                                                                 )
                                  )
                               AND subjectId IN (SELECT 
                                                             DISTINCT subjectId 
                                                        FROM 
                                                             feedbackadv_to_class_subjects 
                                                        WHERE 
                                                             feedbackMappingId IN 
                                                                           (SELECT 
                                                                                 DISTINCT feedbackMappingId 
                                                                            FROM 
                                                                                 feedbackadv_survey_mapping
                                                                            WHERE 
                                                                                 feedbackSurveyId IN ($labelId) AND roleId=4))    
                         AND answerSetOptionId!=-1),0) +
                      IFNULL((SELECT  
                                       COUNT(*) AS cnt         
                                  FROM 
                                        feedbackadv_survey_answer
                                  WHERE
                                        userId=s.userId
                                        AND answerSetOptionId!=-1  
                                        AND
                                        feedbackMappingId IN 
                                                             (SELECT 
                                                                      DISTINCT fsm.feedbackMappingId
                                                              FROM 
                                                                       feedbackadv_to_question  ftq,
                                                                       feedbackadv_survey_visible_to_users fsvu,
                                                                       feedbackadv_questions fq,
                                                                       feedbackadv_survey_mapping fsm
                                                                       LEFT JOIN  feedbackadv_to_class_subjects ftcs ON (ftcs.feedbackMappingId=fsm.feedbackMappingId  )
                                                              WHERE
                                                                       fsvu.feedbackMappingId=fsm.feedbackMappingId
                                                                       AND fsm.feedbackSurveyId=ftq.feedbackSurveyId
                                                                       AND fsm.feedbackCategoryId=ftq.feedbackCategoryId
                                                                       AND fsm.feedbackQuestionSetId=ftq.feedbackQuestionSetId
                                                                       AND ftq.feedbackQuestionId=fq.feedbackQuestionId
                                                                       AND fsm.feedbackSurveyId IN ($labelId)
                                                                       AND fsvu.userId=s.userId
                                                                       AND fsvu.roleId=4
                                                                       AND ftcs.subjectId IS NULL)),0))
                      AS questionsAnswered
               FROM
                     student s
                     INNER JOIN `user` u ON ( u.userId=s.userId AND u.instituteId=$instituteId )
                     INNER JOIN feedbackadv_survey_visible_to_users fsv ON ( fsv.userId=s.userId )
                     INNER JOIN feedbackadv_survey_mapping fsm ON (fsm.feedbackMappingId=fsv.feedbackMappingId)
                     INNER JOIN feedbackadv_survey fs ON (fs.feedbackSurveyId=fsm.feedbackSurveyId)
                     INNER JOIN feedbackadv_student_status fss ON ( fss.userId=s.userId )
                     INNER JOIN class cl  ON (cl.classId=s.classId)
               WHERE
                    cl.instituteId=$instituteId
                    AND cl.sessionId=$sessionId
                    AND s.userId IN ($userIds)
                    $conditions
                GROUP BY s.userId,fs.feedbackSurveyId
               ) AS t
                    ORDER BY  $orderBy
                    $limit
                ";
       //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function displayStudentFeedbackLabelWiseTotal($conditions='',$userIds) {
       global $sessionHandler;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');

      $query = "SELECT
                     COUNT(*) AS totalRecords
                FROM
                     student s
                     INNER JOIN `user` u ON ( u.userId=s.userId AND u.instituteId=$instituteId )
                     INNER JOIN feedbackadv_survey_visible_to_users fsv ON ( fsv.userId=s.userId )
                     INNER JOIN feedbackadv_survey_mapping fsm ON (fsm.feedbackMappingId=fsv.feedbackMappingId)
                     INNER JOIN feedbackadv_survey fs ON (fs.feedbackSurveyId=fsm.feedbackSurveyId)
                     INNER JOIN feedbackadv_student_status fss ON ( fss.userId=s.userId )
                     INNER JOIN class cl  ON (cl.classId=s.classId)
               WHERE
                    cl.instituteId=$instituteId
                    AND cl.sessionId=$sessionId
                    AND s.userId IN ($userIds)
                    $conditions
                    GROUP BY s.userId,fs.feedbackSurveyId
                ";

       //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


public function fetchStudentFeedbackUserList($conditions='',$orderBy='studentName',$limit='') {
   global $sessionHandler;
   $instituteId=$sessionHandler->getSessionVariable('InstituteId');
   $sessionId=$sessionHandler->getSessionVariable('SessionId');

   $query =   "SELECT
                     DISTINCT s.userId, 
                              CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                              s.rollNo, s.universityRollNo, cl.className
               FROM
                     student s
                     INNER JOIN `user` u ON ( u.userId=s.userId AND u.instituteId=$instituteId )
                     INNER JOIN feedbackadv_survey_visible_to_users fsv ON ( fsv.userId=s.userId )
                     INNER JOIN feedbackadv_survey_mapping fsm ON (fsm.feedbackMappingId=fsv.feedbackMappingId)
                     INNER JOIN feedbackadv_survey fs ON (fs.feedbackSurveyId=fsm.feedbackSurveyId)
                     INNER JOIN feedbackadv_student_status fss ON ( fss.userId=s.userId )
                     INNER JOIN class cl  ON (cl.classId=s.classId)
               WHERE
                    cl.instituteId=$instituteId
                    AND cl.sessionId=$sessionId
                    $conditions
                GROUP BY s.userId,fs.feedbackSurveyId
                ORDER BY $orderBy 
                $limit ";

       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}




public function displayEmployeeFeedbackLabelWiseList($conditions='',$labelId,$limit,$orderBy) {
   global $sessionHandler;
   $instituteId=$sessionHandler->getSessionVariable('InstituteId');
   $sessionId=$sessionHandler->getSessionVariable('SessionId');

   $query =   "SELECT
                     t.feedbackSurveyLabel, t.employeeName, t.employeeCode, t.userId, t.totalQuestions, t.questionsAnswered,
                     IF(t.questionsAnswered = 0, 'No', if(t.questionsAnswered = t.totalQuestions,'Yes','Partial')) AS isCompleted
               FROM (
                        SELECT
                              fs.feedbackSurveyLabel,
                              e.employeeName,
                              e.employeeCode,
                              e.userId,
                               (
                                 SELECT
                                       COUNT(*) AS cnt
                                 FROM
                                       feedbackadv_to_question  ftq,
                                       feedbackadv_survey_visible_to_users fsvu,
                                       feedbackadv_survey_mapping fsm,
                                       feedbackadv_questions fq
                                 WHERE
                                       fsvu.feedbackMappingId=fsm.feedbackMappingId
                                       AND fsm.feedbackSurveyId=ftq.feedbackSurveyId
                                       AND fsm.feedbackCategoryId=ftq.feedbackCategoryId
                                       AND fsm.feedbackQuestionSetId=ftq.feedbackQuestionSetId
                                       AND ftq.feedbackQuestionId=fq.feedbackQuestionId
                                       AND fsm.feedbackSurveyId=$labelId
                                       AND fsvu.userId=e.userId
                                       AND fsvu.roleId=2
                               ) as totalQuestions,
                               (
                                 SELECT
                                       COUNT(*) as cnt
                                 FROM
                                       feedbackadv_survey_answer
                                 WHERE
                                       userId=e.userId
                                       AND feedbackMappingId IN
                                            (
                                                SELECT
                                                       DISTINCT feedbackMappingId
                                                FROM
                                                       feedbackadv_survey_visible_to_users
                                                WHERE
                                                       userId=e.userId
                                                       AND roleId=2
                                                       AND feedbackMappingId IN
                                                                         (
                                                                           SELECT
                                                                                  DISTINCT feedbackMappingId
                                                                           FROM
                                                                                  feedbackadv_survey_mapping
                                                                           WHERE
                                                                                  feedbackSurveyId=$labelId
                                                                                  AND roleId=2
                                                                         )
                                          )
                                 AND answerSetOptionId!=-1
                              ) AS questionsAnswered
               FROM
                     employee e
                     INNER JOIN `user` u ON ( u.userId=e.userId AND u.instituteId=1 )
                     INNER JOIN feedbackadv_survey_visible_to_users fsv ON ( fsv.userId=e.userId )
                     INNER JOIN feedbackadv_survey_mapping fsm ON (fsm.feedbackMappingId=fsv.feedbackMappingId)
                     INNER JOIN feedbackadv_survey fs ON (fs.feedbackSurveyId=fsm.feedbackSurveyId)

               WHERE
                    1=1
                    $conditions
               ) AS t
               ORDER BY  $orderBy
               $limit
               ";

       //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function displayEmployeFeedbackLabelWiseTotal($conditions='') {
       global $sessionHandler;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');

      $query = "SELECT
                     COUNT(*) AS totalRecords
                FROM
                     employee e
                     INNER JOIN `user` u ON ( u.userId=e.userId AND u.instituteId=$instituteId )
                     INNER JOIN feedbackadv_survey_visible_to_users fsv ON ( fsv.userId=e.userId )
                     INNER JOIN feedbackadv_survey_mapping fsm ON (fsm.feedbackMappingId=fsv.feedbackMappingId)
                     INNER JOIN feedbackadv_survey fs ON (fs.feedbackSurveyId=fsm.feedbackSurveyId)  
               WHERE
                    1=1
                    $conditions
                    GROUP BY e.userId,fs.feedbackSurveyId
                ";

       //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}
/*These functions are needed for displaying repors*/


    public function getFetchTimeTableEmployee($conditions='') {
     
        global $sessionHandler;
     
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');

        $query ="SELECT
                       DISTINCT tt.timeTableLabelId, tt.classId, tt.groupId, tt.subjectId, tt.employeeId, 
                                 IFNULL(ftm.teacherMappingId,'') AS teacherMappingId
                 FROM
                       class c, time_table tt 
                       LEFT JOIN feedbackadv_teacher_mapping ftm ON tt.timeTableLabelId = ftm.timeTableLabelId AND 
                       tt.classId = ftm.classId AND tt.groupId = ftm.groupId AND tt.subjectId = ftm.subjectId AND 
                       tt.employeeId = ftm.employeeId
                 WHERE
                       tt.classId = c.classId AND
                       c.isActive IN (1) AND
                       tt.instituteId = $instituteId AND
                       tt.toDate IS NULL AND 
                       IFNULL(ftm.teacherMappingId,'') = '' 
                 $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getCheckTeacherMapping($conditions='') {
     
        global $sessionHandler;
     
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT
                        DISTINCT timeTableLabelId, classId, groupId, subjectId, employeeId 
                  FROM
                        feedbackadv_teacher_mapping 
                  WHERE
                        $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
     
     
     //Getting data weather student has completed the survey or not
    public function getStudents($conditions='',$orderBy='',$limits='') {

        $query = "SELECT 
                		   DISTINCT 
                                    fss.surveyId, fss.classId, fss.studentId, 
			                        fss.status AS currentStatus,
		 		                    IF( fss.isStatus >0, 'Completed', 'Partial' ) AS isCompleted, 
		  		                    IF( extendTo > CURRENT_DATE( ) , 'No', 'Yes' ) AS dateOver, 
		  		                    IF( extendTo > CURRENT_DATE( ) , 0, 1 ) AS dateOverFlag, 
		       		                CONCAT( IFNULL( s.firstName, '' ) , ' ', IFNULL( s.lastName, '' ) ) AS studentName, 
		  		                    c.className, s.userId, s.rollNo, s.universityRollNo
		           FROM 
      				    `feedbackadv_survey` fs, student s 
     				    LEFT JOIN `feedback_student_status` fss ON  fss.studentId = s.studentId 
     				    LEFT JOIN class c ON fss.classId = c.classId
		           WHERE
    				    fss.surveyId = fs.feedbackSurveyId 
   				        $conditions 
                   $orderBy $limits";
		
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    //Getting total number of students 
       public function getTotalStudents($conditions='') {
     
		$query="SELECT 
				s.userId
			FROM 
				student s, feedback_student_status fss
			WHERE 
				fss.studentId = s.studentId
				AND fss.SurveyId =$conditions";
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getStudentsFeedbackStatus($conditions='',$labelId='',$orderBy='studentName',$limit='',$timeTableId='',$typeOf='') {
	
	   global $sessionHandler;
       
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');
       
       if($labelId=='') {
         $labelId=0;  
       }
       
       if($orderBy=='') {
         $orderBy='studentName';  
       }
       
	   if($typeOf!=''){
         $typeOf = "  AND t.isCompleted IN ('$typeOf') ";
       }
       
	   $query="SELECT
    			  t.studentId, t.studentName, t.rollNo, t.universityRollNo, t.feedbackStudentId,
    			  t.userId, t.studentAssigned, t.degreeAbbr, t.branchCode, 
    			  t.periodName, t.className, t.feedbackSurveyId, t.classId, 
                  t.timeTableLabelId, t.currentStatus, t.isCompleted, t.dateOver, 
    			  t.dateOverFlag, t.isCheck, DATE_FORMAT(t.visibleFrom,'%d-%b-%y') AS visibleFrom,
                  DATE_FORMAT(t.visibleTo,'%d-%b-%y') AS visibleTo, DATE_FORMAT(t.extendTo,'%d-%b-%y') AS extendTo
		FROM	
   			(SELECT
					DISTINCT s.studentId, IFNULL(fs.feedbackStudentId,-1) AS feedbackStudentId, 
					CONCAT(s.firstName,' ',s.lastName) AS studentName, 
					IF(s.rollNo IS NULL OR s.rollNo='','---',s.rollNo) AS rollNo,
					IF(s.universityRollNo IS NULL OR s.universityRollNo='','---',s.universityRollNo) AS universityRollNo,
					u.userId, fs.status,
					IF(fsv.userId IS NULL,-1,1) AS studentAssigned,
					deg.degreeAbbr, br.branchCode, stp.periodName, cl.className,
                    fss.feedbackSurveyId, fss.timeTableLabelId, s.classId, 
                    fss.visibleFrom, fss.visibleTo, fss.extendTo,
                    IFNULL(fs.status,'') AS currentStatus, 
                    IF(IFNULL(fs.isStatus,'')='','Pending',
                    IF(IFNULL(fs.isStatus,'')='2','Pending',
                       IF(IFNULL(fs.isStatus,'')=1,'Completed',
                          IF(IFNULL(fs.isStatus,'')=0,'Partial','Pending')))) AS isCompleted,
                    IF(fss.extendTo > CURRENT_DATE( ) , 'No', 'Yes' ) AS dateOver, 
                    IF(fss.extendTo > CURRENT_DATE( ) , 0, 1 ) AS dateOverFlag,
                    IFNULL(fs.feedbackStudentId,'-1') AS isCheck
			  FROM
	    			 feedbackadv_survey fss, student s
					 INNER JOIN `user` u ON ( u.userId=s.userId AND u.instituteId=$instituteId )
					 LEFT  JOIN feedbackadv_survey_visible_to_users fsv ON (
							     fsv.userId=u.userId
							     AND fsv.feedbackMappingId IN (
								  SELECT
									fsm.feedbackMappingId
								  FROM
									feedbackadv_survey_mapping fsm
								  WHERE
								       fsm.feedbackSurveyId=$labelId
							     ))
				     INNER JOIN class cl   ON (cl.classId=s.classId)
				     INNER JOIN degree deg ON cl.degreeId=deg.degreeId
				     INNER JOIN branch br  ON cl.branchId=br.branchId
				     INNER JOIN study_period stp ON cl.studyPeriodId=stp.studyPeriodId
                     LEFT JOIN feedback_student_status fs ON s.studentId = fs.studentId AND fs.surveyId=$labelId
				WHERE
				    cl.instituteId=$instituteId
				    AND cl.sessionId=$sessionId
                    AND fss.feedbackSurveyId=$labelId AND fss.timeTableLabelId= $timeTableId
                    AND IFNULL(fss.feedbackSurveyId,'') != '' AND IFNULL(fss.timeTableLabelId,'') != ''                  
                    $conditions
		         GROUP BY 
				     s.studentId) AS t   
        WHERE
             t.studentAssigned = 1 $typeOf              
		ORDER BY  
		     $orderBy $limit";
	
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	
	//This function is used to insert the data for pending student  for blocking
	public function addPendingStudents($strField='') {
     
        $query = "INSERT INTO feedback_student_status
        		  (surveyId,classId,studentId,isStatus,status)
        	      VALUES 
                  $strField";
          
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }    
    
}
// $History: FeedBackAssignSurveyAdvancedManager.inc.php $
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 22/02/10   Time: 12:20
//Updated in $/LeapCC/Model
//Done modifications :
//1.Showing Yes/No/Partial status for student feedback status in report.
//2.Highlight tabs and questions when NA is selected.
//3.Changed status message when partial feedback is given
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/02/10    Time: 15:55
//Updated in $/LeapCC/Model
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/02/10    Time: 18:06
//Updated in $/LeapCC/Model
//Created the repoort for showing student status for feedbacks
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/02/10    Time: 18:21
//Updated in $/LeapCC/Model
//Made modifications in Feedback modules---Added block/unblock feature
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 3/02/10    Time: 15:28
//Updated in $/LeapCC/Model
//Done modification in Adv. Feedback modules and added the options of
//choosing teacher during subject wise feedback
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 19/01/10   Time: 13:04
//Created in $/LeapCC/Model
//Created "Assign Survey (Adv)" module
?>
