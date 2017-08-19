<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "student_teacher_communication" TABLE
//
//
// Author :Rajeev Aggarwal 
// Created on : (28.01.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class StudentAssignmentManager {
    
    private static $instance = null;
    private function __construct(){

    }
    
    public static function getInstance(){

        if (self::$instance === null){

            $class = __CLASS__;
            return self::$instance = new $class;
        }
        return self::$instance;
    }
    
    
     

    //--------------------------------------------------------------------------------
    // THIS FUNCTION IS USED TO FETCH STUDENT/FAHTER/MOTHER/Guardian userId
    //
    // Author :Parveen Sharma
    // Created on : (18.03.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-------------------------------------------------------------------------------      
    public function getUserId($filter='',$conditions='') {

        global $sessionHandler;
     
        $query = "SELECT  
                        $filter
                  FROM 
                        `sc_student`        
                  $conditions ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    //--------------------------------------------------------------------------------
    // THIS FUNCTION IS USED TO FETCH TOTAL OF STUDENT TEACHER MESSAGE DATA
    // Author :Rajeev Aggarwal
    // Created on : (28.01.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //-------------------------------------------------------------------------------      
    public function getTotalStudentTeacherAssignment($conditions='') {
    
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $query = "SELECT 
                         COUNT(*) AS totalRecords  
                  FROM 
                         `assignment_detail` ad, `assignment` aa
                  WHERE 
                         ad.assignmentId = aa.assignmentId
                         AND aa.isVisible=1
                         $conditions
                         AND ad.studentId =".$sessionHandler->getSessionVariable('StudentId')." AND
                         aa.instituteId =".$instituteId." AND
                         aa.sessionId =".$sessionId."
                         AND aa.assignedOn <=curdate()";
                         
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    //--------------------------------------------------------------------------------
    // THIS FUNCTION IS USED TO FETCH LIST OF STUDENT TEACHER MESSAGE DATA
    // Author :Rajeev Aggarwal
    // Created on : (28.01.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //-------------------------------------------------------------------------------      
    public function getStudentTeacherAssignmentList($conditions='', $limit = '', $orderBy=' assignedOn') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $query = "SELECT  
                         *,
                         IF(ad.submittedOn IS NULL,'-1',ad.submittedOn) as submitDate
                  FROM 
                         `assignment_detail` ad, `assignment` aa         
                  WHERE 
                         ad.assignmentId = aa.assignmentId
                         AND aa.isVisible=1
                         $conditions
                         AND  ad.studentId =".$sessionHandler->getSessionVariable('StudentId')." AND
                         aa.instituteId =".$instituteId." AND
                         aa.sessionId =".$sessionId." 
                         AND aa.assignedOn <=curdate()
                  ORDER BY $orderBy 
                  $limit";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
        
    //-------------------------------------------------------
    // THIS FUNCTION IS USED FOR GETTING TimeTable Label LIST
    // $conditions :db clauses
    // Author :Rajeev Aggarwal 
    // Created on : (25.11.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //--------------------------------------------------------         
    public function getStudentAssignment($conditions='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT  
                        *
                  FROM 
                        `assignment_detail` ad, `assignment` aa,
                        `employee` emp     
                  WHERE 
                        aa.employeeId = emp.employeeId  AND
                        aa.assignmentId = ad.assignmentId
                        $conditions AND
                        ad.studentId =".$sessionHandler->getSessionVariable('StudentId')." AND
                        aa.instituteId =".$instituteId." AND
                        aa.sessionId =".$sessionId;
         
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
 
    //-------------------------------------------------------
    // THIS FUNCTION IS USED FOR GETTING TimeTable Label LIST
    // $conditions :db clauses
    // Author :Rajeev Aggarwal 
    // Created on : (25.11.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //--------------------------------------------------------         
    public function editStudentAssignment($fileDetailId) {
    
         global $REQUEST_DATA;
        global $sessionHandler;
        $todayDate = date('Y-m-d');

        $fileDetailArr = explode('_',$fileDetailId);
        $sessionHandler->setSessionVariable('IdToFileUpload',$fileDetailId);

        return SystemDatabaseManager::getInstance()->runAutoUpdate('assignment_detail', array('submittedOn','studentRemarks','addedOn'), array($todayDate,$REQUEST_DATA['messageText'],$todayDate), "assignmentDetailId=$fileDetailArr[1]" );
    }

    //----------------------------------------------------------------------------------------------------
    // THIS FUNCTION IS USED FOR inserting comment attachment  in teacher_comment table
    //
    //$id :id of the notice
    //$fileName: name of the file
    // Author :Dipanjan Bhattacharjee 
    // Created on : (04.11.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //----------------------------------------------------------------------------------------------------       
        public function updateTeacherAssignmentFile($id, $fileName) {

            return SystemDatabaseManager::getInstance()->runAutoUpdate('assignment_detail', 
            array('replyAttachmentFile'), 
            array($fileName), "assignmentDetailId=$id" );
        } 
}
// $History: StudentMessageManager.inc.php $
//
//*****************  Version 9  *****************
//User: Parveen      Date: 4/09/10    Time: 4:35p
//Updated in $/Leap/Source/Model/Student
//role permission and validation updated
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 2/15/10    Time: 12:23p
//Updated in $/Leap/Source/Model/Student
//done changes to implement multi-institute in SC
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 3/23/09    Time: 10:23a
//Updated in $/Leap/Source/Model/Student
//Fixed bugs
//
//*****************  Version 6  *****************
//User: Parveen      Date: 3/18/09    Time: 4:36p
//Updated in $/Leap/Source/Model/Student
//query update
//
//*****************  Version 5  *****************
//User: Parveen      Date: 3/16/09    Time: 6:32p
//Updated in $/Leap/Source/Model/Student
//query update
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 2/09/09    Time: 5:40p
//Updated in $/Leap/Source/Model/Student
//Fixed bugs and alignment
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 2/06/09    Time: 6:41p
//Updated in $/Leap/Source/Model/Student
//added assignment functions
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 2/02/09    Time: 4:25p
//Created in $/Leap/Source/Model/Student
//Intial checkin
?>