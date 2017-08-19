<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class AdminMessageManager {
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
    
    
    //--------------------------------------------------------------------------------
    // THIS FUNCTION IS USED TO ADD STUDENT TEACHER MESSAGE DATA
    //
    // Author :Rajeev Aggarwal
    // Created on : (28.01.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-------------------------------------------------------------------------------      
    public function addAdminMessageStudent() {

        global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $roleId = $sessionHandler->getSessionVariable('RoleId');

        //$REQUEST_DATA['messageId']
        $query = "SELECT COUNT(*) AS totalRecords  
                  FROM `student_teacher_communication` stc         
                  WHERE replyMessageId =".$REQUEST_DATA['messageId'] . " AND instituteId = $instituteId";
        $founArray = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        //if($founArray[0]['totalRecords']){
        //}
        //else{
        $todayDate = date('Y-m-d H:i:s');
        SystemDatabaseManager::getInstance()->runAutoInsert('student_teacher_communication', 
        array('senderId','receiverId','messageSubject','message','messageDate','replyMessageId','instituteId','roleId'), 
        array($REQUEST_DATA['receiverId'],$REQUEST_DATA['senderId'],$REQUEST_DATA['messageSubject'],$REQUEST_DATA['messageText'],$todayDate,$REQUEST_DATA['messageId'], $instituteId, $roleId));
        
        $noticeId=SystemDatabaseManager::getInstance()->lastInsertId();
     
        // set session for fileuploading name
        $sessionHandler->setSessionVariable('IdToFileUpload',SystemDatabaseManager::getInstance()->lastInsertId());
        //}
    }
    
    //--------------------------------------------------------------------------------------------------------------
    // THIS FUNCTION IS USED FOR attachement added when message sent
    //$fileName: name of the file
    // Author :Parveen Sharma
    // Created on : (17.08.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //---------------------------------------------------------------------------------------------------------------    
    public function updateAttachmentFilenameInMessage($id, $fileName) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        return SystemDatabaseManager::getInstance()->runAutoUpdate('student_teacher_communication', 
        array('attachmentFile'), 
        array($fileName), "messageId=$id AND instituteId = $instituteId" );
    }
    
    
    //--------------------------------------------------------------------------------------------------------------
    // THIS FUNCTION IS USED FOR delete addTeacherMessageParent when message is not sent
    //$id :id of the notice
    //$fileName: name of the file
    // Author :Parveen Sharma
    // Created on : (17.08.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //---------------------------------------------------------------------------------------------------------------       
    public function deleteTeacherAttachementOnFailedUpload($id) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
  
        $query="DELETE FROM student_teacher_communication WHERE messageId = '".$id."' AND instituteId = $instituteId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }    
    
    
    //--------------------------------------------------------------------------------
    // THIS FUNCTION IS USED TO FETCH TOTAL OF Parent TEACHER MESSAGE DATA
    //
    // Author :Rajeev Aggarwal
    // Created on : (28.01.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-------------------------------------------------------------------------------      
    public function getTotalParentAdminMessage($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $userId = $sessionHandler->getSessionVariable('UserId');
        
        $query = "SELECT 
                        COUNT(*) AS totalRecords  
                  FROM 
                      (SELECT  
                                CONCAT(IFNULL(scs.firstName,''),IFNULL(scs.lastName,''),' (',scs.rollNo,')') studentName,
                                        IF(stc.senderId = scs.fatherUserId,scs.fatherName,IF(stc.senderId = scs.motherUserId,scs.motherName,
                                        IF(stc.senderId = scs.guardianUserId,scs.guardianName,'".NOT_APPLICABLE_STRING."'))) AS parentName,
                                        messageId, senderId,receiverId,messageSubject,message,attachmentFile,readStatus,messageDate,
                                        usr.roleId, emp.employeeName 
                          FROM 
                                `user` usr, `student_teacher_communication` stc 
                                LEFT JOIN `student` scs  ON (stc.senderId = scs.userId OR stc.senderId = scs.fatherUserId OR 
                                                             stc.senderId = scs.motherUserId OR stc.senderId = scs.guardianUserId)
                                LEFT JOIN employee emp ON stc.receiverId = emp.userId         
                          WHERE 
                                stc.receiverId = emp.userId  AND
                                stc.receiverId = usr.userId  AND
                                stc.instituteId = $instituteId
                          $conditions) AS t ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    //--------------------------------------------------------------------------------
    // THIS FUNCTION IS USED TO FETCH LIST OF parent TEACHER MESSAGE DATA
    //
    // Author :Rajeev Aggarwal
    // Created on : (28.01.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-------------------------------------------------------------------------------      
    public function getParentTeacherAdminList($conditions='', $limit = '', $orderBy=' messageDate') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
     
        $query = "SELECT  
                        CONCAT(IFNULL(scs.firstName,''),IFNULL(scs.lastName,''),' (',scs.rollNo,')') studentName,
                                IF(stc.senderId = scs.fatherUserId,scs.fatherName,IF(stc.senderId = scs.motherUserId,scs.motherName,
                                IF(stc.senderId = scs.guardianUserId,scs.guardianName,'".NOT_APPLICABLE_STRING."'))) AS parentName,
                                messageId, senderId,receiverId,messageSubject,message,readStatus,messageDate,usr.roleId, emp.employeeName,emp.visibleToParent,
                                IF(IFNULL(attachmentFile,'')='','',attachmentFile) AS attachmentFile
                  FROM 
                        `user` usr, `student_teacher_communication` stc 
                        LEFT JOIN `student` scs  ON (stc.senderId = scs.userId OR stc.senderId = scs.fatherUserId OR 
                                                     stc.senderId = scs.motherUserId OR stc.senderId = scs.guardianUserId)
                        LEFT JOIN employee emp ON stc.receiverId = emp.userId         
                  WHERE 
                        stc.receiverId = emp.userId  AND
                        stc.receiverId = usr.userId  AND
                        stc.instituteId = $instituteId
                  $conditions      
                  ORDER BY 
                        $orderBy $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
    
    //--------------------------------------------------------------------
    // THIS FUNCTION IS USED FOR EDITING A Leave Type
    //
    //$id:busRouteId
    // Author :Rajeev Aggarwal 
    // Created on : (25.11.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------------        
    public function changeMessageStatus($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
         
        return SystemDatabaseManager::getInstance()->runAutoUpdate('student_teacher_communication', 
        array('readStatus','instituteId'), 
        array(2,$instituteId) ,
              "messageId=$id AND instituteId = $instituteId"
            );
    } 
    
    
    //--------------------------------------------------------------------------------
    // THIS FUNCTION IS USED TO FETCH LIST OF STUDENT TEACHER MESSAGE DATA
    //
    // Author :Rajeev Aggarwal
    // Created on : (28.01.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-------------------------------------------------------------------------------      
    public function getParentMessageDetail($conditions='', $limit = '', $orderBy=' messageDate') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
     
        $query = "SELECT  
                          (SELECT 
                            UPPER(IF(senderId=scs.fatherUserId,CONCAT(IFNULL(scs.fatherName,''),' (',scs.rollNo,')'),
                                  IF(stc.senderId=scs.motherUserId,CONCAT(IFNULL(scs.motherName,''),' (',scs.rollNo,')'),
                                  IF(senderId=scs.guardianUserId,CONCAT(IFNULL(scs.guardianName,''),' (',scs.rollNo,')'),
                                  IF(senderId=scs.userId,CONCAT(IFNULL(scs.firstName,''),' ',IFNULL(scs.lastName,''),' (',scs.rollNo,')'),''))))) 
                            FROM 
                                   student scs 
                            WHERE 
                                   fatherUserId=stc.senderId OR motherUserId=stc.senderId OR 
                                   guardianUserId=stc.senderId OR userId=stc.senderId) AS firstName,
                            emp.employeeName, messageId,senderId,receiverId,messageSubject,message,
                            attachmentFile,readStatus,messageDate, usr.roleId
                    FROM 
                            employee emp, `student_teacher_communication` stc, `user` usr
                    WHERE    
                            stc.receiverId = emp.userId AND
                            usr.userId = emp.userId AND
                            stc.instituteId = $instituteId
                            $conditions
                  ORDER BY $orderBy $limit";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    
    //--------------------------------------------------------------------------------
    // THIS FUNCTION IS USED TO FETCH TOTAL OF STUDENT TEACHER MESSAGE DATA
    //
    // Author :Rajeev Aggarwal
    // Created on : (28.01.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-------------------------------------------------------------------------------      
    public function getTotalParentSentItemMessage($conditions='') {
        global $sessionHandler;
          $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                         COUNT(*) AS totalRecords  
                  FROM
                        (SELECT  
                                IFNULL(CONCAT(IFNULL(scs.firstName,''),IFNULL(scs.lastName,''),' (',scs.rollNo,')'),'".NOT_APPLICABLE_STRING."') studentName,
                                IF(stc.receiverId = scs.fatherUserId,scs.fatherName,IF(stc.receiverId = scs.motherUserId,scs.motherName,
                                IF(stc.receiverId = scs.guardianUserId,scs.guardianName,'".NOT_APPLICABLE_STRING."'))) AS parentName,
                                messageId, senderId,receiverId,messageSubject,message,attachmentFile,readStatus,messageDate,usr.roleId,
                                emp.employeeName 
                          FROM 
                                `user` usr, `student_teacher_communication` stc 
                                LEFT JOIN `student` scs  ON (stc.receiverId = scs.userId OR stc.receiverId = scs.fatherUserId OR 
                                                             stc.receiverId = scs.motherUserId OR stc.receiverId = scs.guardianUserId)
                                LEFT JOIN employee emp ON stc.senderId = emp.userId 
                          WHERE 
                                stc.senderId = emp.userId AND
                                stc.senderId = usr.userId AND 
                                stc.instituteId = $instituteId
                                $conditions) AS t";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    //--------------------------------------------------------------------------------
    // THIS FUNCTION IS USED TO FETCH LIST OF STUDENT TEACHER MESSAGE DATA
    //
    // Author :Rajeev Aggarwal
    // Created on : (28.01.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-------------------------------------------------------------------------------      
    public function getParentSentItemList($conditions='', $limit = '', $orderBy=' messageDate') {

       global $sessionHandler;
       $instituteId = $sessionHandler->getSessionVariable('InstituteId');
     
       $query = "SELECT  
                        IFNULL(CONCAT(IFNULL(scs.firstName,''),IFNULL(scs.lastName,''),' (',scs.rollNo,')'),'".NOT_APPLICABLE_STRING."') studentName,
                        IF(stc.receiverId = scs.fatherUserId,scs.fatherName,IF(stc.receiverId = scs.motherUserId,scs.motherName,
                        IF(stc.receiverId = scs.guardianUserId,scs.guardianName,'".NOT_APPLICABLE_STRING."'))) AS parentName,
                        messageId, senderId,receiverId,messageSubject,message,attachmentFile,readStatus,messageDate,usr.roleId,
                        emp.employeeName 
                  FROM 
                        `user` usr, `student_teacher_communication` stc 
                        LEFT JOIN `student` scs  ON (stc.receiverId = scs.userId OR stc.receiverId = scs.fatherUserId OR 
                                                     stc.receiverId = scs.motherUserId OR stc.receiverId = scs.guardianUserId)
                        LEFT JOIN employee emp ON stc.senderId = emp.userId 
                  WHERE 
                        stc.senderId = emp.userId AND
                        stc.senderId = usr.userId AND 
                        stc.instituteId = $instituteId
                        $conditions
                  ORDER BY $orderBy $limit";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
}

