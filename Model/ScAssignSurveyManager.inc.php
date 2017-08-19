<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "student and teacher_comment" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId

class ScAssignSurveyManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "SendMessageManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "SendMessageManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
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
    
//---------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR fetching student email and mobileno
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------
public function getStudentInfo($studentId){
    
    $query="SELECT studentMobileNo,studentEmail FROM student WHERE studentId IN ($studentId)";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
}


//---------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR fetching parent email and mobileno
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------
public function getParentInfo($studentId){
    
    $query="SELECT fatherMobileNo,fatherEmail,motherMobileNo,motherEmail,guardianMobileNo,guardianEmail 
            FROM student WHERE studentId IN ($studentId)";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
}




//----------------------------------------------------------------
// THIS FUNCTION IS USED FOR sending message students/+parents
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------

public function sendMessage($query){
    return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");  
}  



//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING group list
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------      
public function getGroup($conditions=''){
    global $sessionHandler;
    
    $query="SELECT groupId,groupName,class.classId 
            FROM `group`, class
            WHERE  group.classId=class.classId AND instituteId=".$sessionHandler->getSessionVariable('InstituteId') . " AND sessionId=".$sessionHandler->getSessionVariable('SessionId') .
            " $conditions $conditions";
    
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  

}  


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING student LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getStudentList($conditions='', $limit = '', $orderBy=' studentName') {

       global $sessionHandler;
       global $REQUEST_DATA;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');
       
       $query = "SELECT 
                        s.studentId,IF(su.svuId IS NULL,'No','Yes') AS studentAssigned, 
                        rollNo, universityRollNo, concat(firstName,' ',lastName) AS studentName,
                        deg.degreeAbbr,br.branchCode,stp.periodName
                  FROM 
                     student s
                     LEFT JOIN survey_visible_to_users su ON s.studentId = su.targetIds
                     AND su.userType = 'S'  AND su.feedbackSurveyId=".$REQUEST_DATA['surveyId']."
                     
                     INNER JOIN class cl   
                      ON s.classId=cl.classId
                     INNER JOIN degree deg
                      ON cl.degreeId=deg.degreeId
                     INNER JOIN branch br
                      ON cl.branchId=br.branchId 
                     INNER JOIN study_period stp
                      ON cl.studyPeriodId=stp.studyPeriodId
                     INNER JOIN university uni
                      ON cl.universityId=uni.universityId
                  WHERE 
                    cl.instituteId=".$instituteId." 
                    AND cl.sessionId=".$sessionId." 
                    $conditions 
                    ORDER BY $orderBy 
                    $limit
                ";
        
       //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF students
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalStudent($conditions='') {
        global $sessionHandler;
        global $REQUEST_DATA;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
       
        $query = "SELECT 
                        s.studentId,IF(su.svuId IS NULL,'No','Yes') AS studentAssigned 
                 FROM 
                     student s
                     LEFT JOIN survey_visible_to_users su ON s.studentId = su.targetIds
                     AND su.userType = 'S'  AND su.feedbackSurveyId=".$REQUEST_DATA['surveyId']."
                     
                     INNER JOIN class cl   
                      ON s.classId=cl.classId
                     INNER JOIN degree deg
                      ON cl.degreeId=deg.degreeId
                     INNER JOIN branch br
                      ON cl.branchId=br.branchId 
                     INNER JOIN study_period stp
                      ON cl.studyPeriodId=stp.studyPeriodId
                     INNER JOIN university uni
                      ON cl.universityId=uni.universityId
                  WHERE 
                    cl.instituteId=".$instituteId." 
                    AND cl.sessionId=".$sessionId." 
                    $conditions 
                ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING student LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getParentList($conditions='', $limit = '', $orderBy=' studentName') {

       global $sessionHandler;
       global $REQUEST_DATA;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');
       
        $query ="SELECT 
                       s.studentId,IF(su.svuId IS NULL,'No','Yes') AS parentAssigned,
                       rollNo, universityRollNo, concat(firstName,' ',lastName) AS studentName,
                       IF(s.fatherName IS NULL OR s.fatherName='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                       IF(s.motherName IS NULL OR s.motherName='','".NOT_APPLICABLE_STRING."',s.motherName) AS motherName,
                       IF(s.guardianName IS NULL OR s.guardianName='','".NOT_APPLICABLE_STRING."',s.guardianName) AS guardianName
                 FROM 
                     student s
                     LEFT JOIN survey_visible_to_users su ON s.studentId = su.targetIds
                     AND su.userType = 'P'  AND su.feedbackSurveyId=".$REQUEST_DATA['surveyId']."
                     
                     INNER JOIN class cl   
                      ON s.classId=cl.classId
                     INNER JOIN degree deg
                      ON cl.degreeId=deg.degreeId
                     INNER JOIN branch br
                      ON cl.branchId=br.branchId 
                     INNER JOIN study_period stp
                      ON cl.studyPeriodId=stp.studyPeriodId
                     INNER JOIN university uni
                      ON cl.universityId=uni.universityId
                 WHERE 
                    cl.instituteId=".$instituteId." 
                    AND cl.sessionId=".$sessionId." 
                    $conditions 
                ORDER BY $orderBy 
                $limit";
        
       //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF students
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalParent($conditions='') {
        global $sessionHandler;
        global $REQUEST_DATA;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');
       
        $query = "SELECT 
                      s.studentId,IF(su.svuId IS NULL,'No','Yes') AS parentAssigned 
                 FROM 
                     student s
                     LEFT JOIN survey_visible_to_users su ON s.studentId = su.targetIds
                     AND su.userType = 'P'  AND su.feedbackSurveyId=".$REQUEST_DATA['surveyId']."
                     
                     INNER JOIN class cl   
                      ON s.classId=cl.classId
                     INNER JOIN degree deg
                      ON cl.degreeId=deg.degreeId
                     INNER JOIN branch br
                      ON cl.branchId=br.branchId 
                     INNER JOIN study_period stp
                      ON cl.studyPeriodId=stp.studyPeriodId
                     INNER JOIN university uni
                      ON cl.universityId=uni.universityId
                 WHERE 
                    cl.instituteId=".$instituteId." 
                    AND cl.sessionId=".$sessionId." 
                    $conditions 
                ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }     
    

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING employee LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getEmployeeList($conditions='', $limit = '', $orderBy=' e.employeeName') {
       global $REQUEST_DATA;
       global $sessionHandler;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       
       $query = "SELECT 
                      e.employeeId,IF(su.svuId IS NULL,'No','Yes') AS empAssigned, 
                      e.employeeName,e.employeeCode,e.employeeAbbreviation,
                      IF(e.isTeaching=1,'YES','NO') AS isTeaching,e.qualification,
                      IF(e.dateOfJoining='0000-00-00','---',DATE_FORMAT(e.dateOfJoining,'%d-%b-%y')) AS  dateOfJoining,
                      d.designationName,br.branchCode,r.roleName
                FROM 
                    employee e
                    LEFT JOIN survey_visible_to_users su ON e.employeeId = su.targetIds
                    AND su.userType = 'E' AND su.feedbackSurveyId=".$REQUEST_DATA['surveyId']."
                    
                    INNER JOIN designation d
                    ON e.designationId=d.designationId
                    INNER JOIN `user` u
                    ON e.userId=u.userId
                    INNER JOIN `role` r
                    ON u.roleId=r.roleId
                    INNER JOIN branch br
                    ON e.branchId=br.branchId 
                    WHERE 
                    e.instituteId=".$instituteId."
                    $conditions 
                    ORDER BY $orderBy 
                    $limit";
                
                //echo  $query;
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF students
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalEmployee($conditions='') {
        global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId=$sessionHandler->getSessionVariable('InstituteId');
        
        $query ="SELECT 
                       e.employeeId,IF(su.svuId IS NULL,'No','Yes') AS empAssigned
                 FROM 
                    employee e
                    LEFT JOIN survey_visible_to_users su ON e.employeeId = su.targetIds
                    AND su.userType = 'E' AND su.feedbackSurveyId=".$REQUEST_DATA['surveyId']."
                    
                    INNER JOIN designation d
                    ON e.designationId=d.designationId
                    INNER JOIN `user` u
                    ON e.userId=u.userId
                    INNER JOIN `role` r
                    ON u.roleId=r.roleId
                    INNER JOIN branch br
                    ON e.branchId=br.branchId
                    WHERE
                    e.instituteId=".$instituteId."
                    $conditions ";
        
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    
//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR deleting assigned survey records
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (19.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------         
    public function deleteAssignedSurvey($surveyId,$userType,$condition) {
        /*First delete related records*/
		$query="DELETE FROM survey_visible_to_users WHERE feedbackSurveyId=$surveyId AND userType='".$userType."' $condition";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query"); 
    }

       
//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR inserting assign survey records
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (19.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------         
    public function assignSurvey($insQuery) {
        return SystemDatabaseManager::getInstance()->executeUpdate($insQuery); 
    }     

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO CHECK EXISTENCE IN FEEDBACK SURVEY
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (19.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------         
    public function checkStudentExistence($condition) {
        /*First delete related records*/
     $query="		SELECT	distinct fsa.userId, scs.studentId, svu.targetIds 
					FROM	feedback_survey_answer fsa,
							survey_visible_to_users svu,
							student scs $condition";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
    }
   
//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO CHECK EXISTENCE IN FEEDBACK SURVEY
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (19.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------         
    public function checkParentExistence($condition) {
        /*First delete related records*/
    $query="	SELECT	distinct fsa.userId, scs.fatherUserId, scs.motherUserId, scs.guardianUserId, svu.targetIds,scs.studentId 
					FROM	feedback_survey_answer fsa,
							survey_visible_to_users svu,
							student scs $condition";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
    }
  //--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO CHECK EXISTENCE IN FEEDBACK SURVEY
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (19.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------         
    public function checkEmployeeExistence($condition) {
        /*First delete related records*/
     $query="		SELECT	distinct fsa.userId, emp.employeeId, svu.targetIds 
					FROM	feedback_survey_answer fsa,
							survey_visible_to_users svu,
							employee emp $condition";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
    }
}
?>
<?php
// $History: ScAssignSurveyManager.inc.php $
//
//*****************  Version 1  *****************
//User: Administrator Date: 21/05/09   Time: 14:42
//Created in $/LeapCC/Model
//Copied "Assign Survey" module from Leap to LeapCC
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/24/09    Time: 11:59a
//Updated in $/Leap/Source/Model
//fixed bugs in survey assign for student, parent, employee can not
//delete the record if exist in another table
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 13/01/09   Time: 12:29
//Updated in $/Leap/Source/Model
//Fixed bugs related to "Assign Survey" module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/01/09    Time: 13:26
//Updated in $/Leap/Source/Model
//Crrected pagination related problem
//[maintained checkbox state in pagination]
?>
