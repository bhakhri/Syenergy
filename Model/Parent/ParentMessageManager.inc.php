<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "parent_teacher_communication" TABLE
//
//
// Author :Parveen Sharma
// Created on : (04.02.2009)
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class ParentTeacherManager {
	
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
	// THIS FUNCTION IS USED TO FETCH TOTAL OF Parent TEACHER MESSAGE DATA
	//
	// Author :Rajeev Aggarwal
	// Created on : (28.01.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------  	
	public function getTotalParentTeacherMessage($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        COUNT(*) AS totalRecords  
				  FROM 
                        `student_teacher_communication` stc, `employee` emp         
				  WHERE 
                        stc.senderId = emp.userId 
                        $conditions AND
				        stc.receiverId = '".$sessionHandler->getSessionVariable('UserId')."' AND stc.instituteId = $instituteId";

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
	public function getParentTeacherMessageList($conditions='', $limit = '', $orderBy=' messageDate') {

		global $sessionHandler;
      $instituteId = $sessionHandler->getSessionVariable('InstituteId');
     
        $query = "SELECT  
		                emp.employeeName,emp.employeeCode,emp.employeeAbbreviation,messageId,
                        senderId,receiverId,messageSubject,message,attachmentFile,readStatus,messageDate,
                        r.roleName, r.roleId  
				  FROM 
                        `student_teacher_communication` stc, `employee` emp, `user` usr, role r         
				  WHERE 
                        stc.senderId = emp.userId $conditions AND
                        usr.userId = stc.senderId AND
                        r.roleId = usr.roleId AND
                        stc.instituteId = $instituteId AND
				        stc.receiverId = '".$sessionHandler->getSessionVariable('UserId')."' 
				  ORDER BY 
                        $orderBy $limit";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO FETCH TOTAL OF parent TEACHER MESSAGE DATA
	//
	// Author :Rajeev Aggarwal
	// Created on : (28.01.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------  	
	public function getTotalSentItemMessage($conditions='') {
        
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        COUNT(*) AS totalRecords  
				  FROM 
                        `student_teacher_communication` stc, `employee` emp         
				  WHERE 
                        stc.receiverId = emp.userId 
				        $conditions AND
				        stc.senderId = '".$sessionHandler->getSessionVariable('UserId')."' AND stc.instituteId = $instituteId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO FETCH LIST OF Parent TEACHER MESSAGE DATA
	//
	// Author :Rajeev Aggarwal
	// Created on : (28.01.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------  	
	public function getSentItemMessageList($conditions='', $limit = '', $orderBy=' messageDate') {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
     
        $query = "SELECT  
				            emp.employeeName,emp.employeeCode,emp.employeeAbbreviation,
                            messageId,senderId,receiverId,messageSubject,message,attachmentFile,readStatus,messageDate 
				  FROM 
                            `student_teacher_communication` stc, `employee` emp         
				  WHERE 
                            stc.receiverId = emp.userId $conditions AND
                            stc.senderId = '".$sessionHandler->getSessionVariable('UserId')."'
									 AND stc.instituteId = $instituteId
				  ORDER BY 
                            $orderBy $limit";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  


	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO ADD parent TEACHER MESSAGE DATA
	//
	// Author :Rajeev Aggarwal
	// Created on : (28.01.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------  	
	public function addParentTeacherMessage() {

		global $REQUEST_DATA;
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $roleId=$REQUEST_DATA['roleId'];
        if($roleId=='') {
          $roleId = $sessionHandler->getSessionVariable('RoleId');  
        }

		$todayDate = date('Y-m-d H:i:s');
		SystemDatabaseManager::getInstance()->runAutoInsert('student_teacher_communication', 
            array('senderId','receiverId','messageSubject','message','messageDate','instituteId','roleId'), 
            array($sessionHandler->getSessionVariable('UserId'),$REQUEST_DATA['teacherId'],$REQUEST_DATA['messageSubject'],$REQUEST_DATA['messageText'],$todayDate,$instituteId,$roleId));
	  	$noticeId=SystemDatabaseManager::getInstance()->lastInsertId();
       
        // set session for fileuploading name
		$sessionHandler->setSessionVariable('IdToFileUpload',SystemDatabaseManager::getInstance()->lastInsertId());
		
	}

	 public function updateAttachmentFilenameInMessage($id, $fileName) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        return SystemDatabaseManager::getInstance()->runAutoUpdate('student_teacher_communication', 
        array('attachmentFile'), 
        array($fileName), "messageId=$id AND instituteId = $instituteId" );
    }

	 public function checkInParentMessage($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        if ($conditions == '') {
			  $conditions .= ' WHERE ';
        }
		  else {
			  $conditions .= ' AND ';
		  }
		  $conditions .= " instituteId = $instituteId ";
     
        $query = "SELECT COUNT(*) as totalRecords
        FROM `student_teacher_communication`
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
	//-------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR DELETING A Leave Type
	//
	//$LeaveTypeId :LeaveTypeId of the feedback label
	// Author :Rajeev Aggarwal 
	// Created on : (25.11.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------------------------------------------------------      
    public function deleteParentMessage($messageId) {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

		$queryAttachment="SELECT 
						 attachmentFile 
				         
						 FROM 
						 `student_teacher_communication` 
				         
						 WHERE 
						 messageId=$messageId AND instituteId = $instituteId";
		$a=SystemDatabaseManager::getInstance()->executeQuery($queryAttachment,"Query: $queryAttachment");

		$query="DELETE 
	        FROM `student_teacher_communication` 
	        WHERE messageId=$messageId AND instituteId = $instituteId";
			if(SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query")){
		
				if($a[0]['attachmentFile']!=''){
						$File = STORAGE_PATH."/Images/StudentMessage/".$a[0]['attachmentFile'];
						if(file_exists($File)){
						Unlink($File);
					}
				}
				return true;
			}
			else{
				return false;
			}
      
    }
	//-------------------------------------------------------
	// THIS FUNCTION IS USED FOR GETTING TimeTable Label LIST
	//
	//$conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (25.11.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------         
    public function getParentMessage($conditions='', $limit = '', $orderBy=' messageDate') {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
		$query = "SELECT  
				        employeeName,messageId,senderId,receiverId,messageSubject,
                        message,attachmentFile,readStatus,messageDate,stc.roleId 
				  FROM 
                        `student_teacher_communication` stc, `employee` emp, `user` usr
				  WHERE 
				        stc.receiverId = emp.userId  AND
				        usr.userId = emp.userId AND
				        stc.senderId = '".$sessionHandler->getSessionVariable('UserId')."' AND
						stc.instituteId = $instituteId 
                  $conditions
				  ORDER BY 
                        $orderBy $limit";
     
        /*$query = "SELECT messageId ,senderId, receiverId, messageSubject, message, attachmentFile, readStatus, messageDate 
        
		FROM `student_teacher_communication` 
        $conditions";*/
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
	//-------------------------------------------------------
	// THIS FUNCTION IS USED FOR GETTING TimeTable Label LIST
	//
	//$conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (25.11.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------         
    public function getParentMessage1($conditions='', $limit = '', $orderBy=' messageDate') {

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "SELECT  
				        employeeName,messageId,senderId,receiverId,messageSubject, 
                        message,attachmentFile,readStatus,messageDate,roleId  
				  FROM 
                        `student_teacher_communication` stc, `employee` emp, `user` usr      
				  WHERE 
				        stc.receiverId = emp.userId  AND
				        usr.userId = emp.userId  AND
						stc.instituteId = $instituteId 
                        $conditions
				  ORDER BY 
                        $orderBy $limit";
     
        /*$query = "SELECT messageId ,senderId, receiverId, messageSubject, message, attachmentFile, readStatus, messageDate 
		FROM `student_teacher_communication` 
        $conditions";*/
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	//-------------------------------------------------------
	// THIS FUNCTION IS USED FOR GETTING TimeTable Label LIST
	//
	//$conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (25.11.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------         
    public function getParentMessageAndReply($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
     
        $query = "SELECT    
                            a.readStatus,a.receiverId ,a.messageSubject,a.message,
                            a.attachmentFile,b.messageSubject as replySubject,
                            b.message as replyMessage,b.messageDate  as replyDate,b.attachmentFile replyFile 
                  FROM 
                            `student_teacher_communication` a,`student_teacher_communication` b 
                  WHERE 
                            a.messageId = b.replyMessageId
									 AND a.instituteId = $instituteId
									 AND b.instituteId = $instituteId
                  $conditions ";
                  
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
    public function editParentMessage($id) {
        global $REQUEST_DATA;
        global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
		$todayDate = date('Y-m-d H:i:s');
		SystemDatabaseManager::getInstance()->runAutoInsert('student_teacher_communication', 
          array('senderId','receiverId','messageSubject','message','messageDate','replyMessageId','instituteId','roleId'), 
          array($REQUEST_DATA['receiverId'],$REQUEST_DATA['senderId'],$REQUEST_DATA['messageSubject'],$REQUEST_DATA['messageText'],$todayDate,$REQUEST_DATA['messageId'], $instituteId,$REQUEST_DATA['roleId']));
	  	$noticeId=SystemDatabaseManager::getInstance()->lastInsertId();
   
		// set session for fileuploading name
		$sessionHandler->setSessionVariable('IdToFileUpload',SystemDatabaseManager::getInstance()->lastInsertId());
    } 
	
	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO FETCH TOTAL OF Parent TEACHER MESSAGE DATA
	//
	// Author :Rajeev Aggarwal
	// Created on : (28.01.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------  	
	public function getTotalTeacherParentMessage($conditions='') {
        global $sessionHandler;
		  $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        COUNT(*) AS totalRecords  
				  FROM 
                        `student_teacher_communication` stc LEFT JOIN `student` scs  ON 
                        (stc.senderId = scs.userId OR stc.senderId = scs.fatherUserId OR stc.senderId = scs.motherUserId OR stc.senderId = scs.guardianUserId)
				  WHERE 
                        stc.instituteId = $instituteId
				        $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO FETCH LIST OF Parent TEACHER MESSAGE DATA
	//
	// Author :Rajeev Aggarwal
	// Created on : (28.01.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------  	
	public function getTeacherParentMessageList($conditions='', $limit = '', $orderBy=' messageDate') {

		global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
     
        $query = "SELECT  
				        CONCAT(IFNULL(scs.firstName,''),IFNULL(scs.lastName,''),' (',scs.rollNo,')') studentName,
                        IF(stc.senderId = scs.fatherUserId,scs.fatherName,IF(stc.senderId = scs.motherUserId,scs.motherName,
                        IF(stc.senderId = scs.guardianUserId,scs.guardianName,'".NOT_APPLICABLE_STRING."'))) AS parentName,
                        messageId, senderId,receiverId,messageSubject,message,attachmentFile,readStatus,messageDate,roleId,
                        emp.employeeName 
                  FROM 
                        `user` usr, `student_teacher_communication` stc 
                        LEFT JOIN `student` scs  ON (stc.senderId = scs.userId OR stc.senderId = scs.fatherUserId OR 
                                                     stc.senderId = scs.motherUserId OR stc.senderId = scs.guardianUserId)
                        LEFT JOIN employee emp ON stc.receiverId = emp.userId 
                  WHERE 
                        usr.userId = stc.senderId AND
                        stc.instituteId = $instituteId      
                  $conditions 
				  ORDER BY 
                          $orderBy $limit";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 

	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO FETCH TOTAL OF Parent TEACHER MESSAGE DATA
	//
	// Author :Rajeev Aggarwal
	// Created on : (28.01.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------  	
	public function getTotalTeacherSentItemMessage($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        COUNT(*) AS totalRecords  
				  FROM 
                        `student_teacher_communication` stc, `sc_student` scs         
				  WHERE 
                        stc.receiverId = scs.userId 
                        $conditions  AND
				        stc.senderId = '".$sessionHandler->getSessionVariable('UserId')."' AND stc.instituteId = $instituteId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO FETCH LIST OF Parent TEACHER MESSAGE DATA
	//
	// Author :Rajeev Aggarwal
	// Created on : (28.01.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------  	
	public function getTeacherSentItemList($conditions='', $limit = '', $orderBy=' messageDate') {

		global $sessionHandler;
      $instituteId = $sessionHandler->getSessionVariable('InstituteId');
     
        $query = "SELECT  
				          CONCAT(scs.firstName,scs.lastName) firstName,messageId,
                          senderId,receiverId,messageSubject,message,attachmentFile,readStatus,messageDate,roleId 
				  FROM 
                          `student_teacher_communication` stc, `sc_student` scs, `user` usr      
				  WHERE 
				          stc.receiverId = scs.userId  AND
				          usr.userId = scs.userId  
				          $conditions AND
				          stc.senderId = '".$sessionHandler->getSessionVariable('UserId')."' 
							 AND stc.instituteId = $instituteId
				  ORDER BY 
                          $orderBy $limit";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
	

	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO FETCH LIST OF Parent TEACHER MESSAGE DATA
	//
	// Author :Rajeev Aggarwal
	// Created on : (28.01.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------  	
	public function getParentMessageDetail1($conditions='', $limit = '', $orderBy=' messageDate') {

		global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
     
        $query = "SELECT  
				         CONCAT(scs.firstName,scs.lastName) firstName,messageId,senderId,
                         receiverId,messageSubject,message,attachmentFile,readStatus,messageDate,roleId 
				  FROM 
                         `student_teacher_communication` stc, `sc_student` scs, `user` usr      
				  WHERE 
				         stc.senderId = scs.userId  AND
				         usr.userId = scs.userId  
				         $conditions AND
				         stc.receiverId = '".$sessionHandler->getSessionVariable('UserId')."' 
							AND stc.instituteId = $instituteId
				  ORDER BY 
                         $orderBy $limit";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 

	public function getParentMessageDetail($conditions='', $limit = '', $orderBy=' messageDate') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
     
        $query = "SELECT  
				          CONCAT(scs.firstName,scs.lastName,'-',scs.rollNo) firstName,messageId,
                          senderId,receiverId,messageSubject,message,attachmentFile,readStatus,messageDate,roleId 
				  FROM 
                          `student_teacher_communication` stc, `sc_student` scs, `user` usr      
				  WHERE 
                  		  stc.receiverId = scs.userId  AND
				          usr.userId = scs.userId  
				          $conditions AND
				          stc.senderId = '".$sessionHandler->getSessionVariable('UserId')."' 
							 AND stc.instituteId = $instituteId
				  ORDER BY 
                          $orderBy $limit";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 

	//--------------------------------------------------------------------------------
	// THIS FUNCTION IS USED TO ADD Parent TEACHER MESSAGE DATA
	//
	// Author :Rajeev Aggarwal
	// Created on : (28.01.2009)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//-------------------------------------------------------------------------------  	
	public function addTeacherMessageParent() {

		global $REQUEST_DATA;
		global $sessionHandler;
      $instituteId = $sessionHandler->getSessionVariable('InstituteId');


		 //$REQUEST_DATA['messageId']
		 $query = "SELECT 
                        COUNT(*) AS totalRecords  
				  FROM 
                        `student_teacher_communication` stc         
				  WHERE 
                        replyMessageId = '".$REQUEST_DATA['messageId']."' AND stc.instituteId = $instituteId";
		
		 $founArray = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		
		//if($founArray[0]['totalRecords']){
		
		//}
		//else{

			$todayDate = date('Y-m-d H:i:s');
			SystemDatabaseManager::getInstance()->runAutoInsert('student_teacher_communication', array('senderId','receiverId','messageSubject','message','messageDate','replyMessageId','instituteId'), array($REQUEST_DATA['receiverId'],$REQUEST_DATA['senderId'],$REQUEST_DATA['messageSubject'],$REQUEST_DATA['messageText'],$todayDate,$REQUEST_DATA['messageId'], $instituteId));
	  		$noticeId=SystemDatabaseManager::getInstance()->lastInsertId();
       
			// set session for fileuploading name
			$sessionHandler->setSessionVariable('IdToFileUpload',SystemDatabaseManager::getInstance()->lastInsertId());
		//}
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
    
}
// $History: ParentMessageManager.inc.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 4/09/10    Time: 4:35p
//Updated in $/Leap/Source/Model/Parent
//role permission and validation updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 4/05/10    Time: 12:56p
//Updated in $/Leap/Source/Model/Parent
//getParentMessage1 function updated
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 2/15/10    Time: 12:23p
//Updated in $/Leap/Source/Model/Parent
//done changes to implement multi-institute in SC
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/09/09   Time: 11:43a
//Updated in $/Leap/Source/Model/Parent
//all query format updated 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/17/09    Time: 7:02p
//Updated in $/Leap/Source/Model/Parent
//deleteTeacherAttachementOnFailedUpload function updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/04/09    Time: 4:26p
//Created in $/Leap/Source/Model/Parent
//initial checkin 
//

?>