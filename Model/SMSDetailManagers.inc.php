<?php
//-------------------------------------------------------
//  This File contains all functions for SMSDetails for Student/Employee
// Author :Parveen Sharma
// Created on : 26-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class SMSDetailManagers
 {
	
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
// THIS FUNCTION IS USED TO GET TOTAL MESSAGE RECORD
//
// Author :Vritee Dhall
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
       
   
	/*public function getMessageDetailTotal($conditions=''){

		global $sessionHandler;   

		$query="
			SELECT 
					COUNT(*) AS totalRecords
				FROM    
	 				`admin_messages`
       			  $conditions
		";
 
  		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
	} */


//--------------------------------------------------------------------------------
// THIS FUNCTION GIVES THE MESSAGE DETAIL ON THE BASIS OF CONDITIONS AND ORDER OF SORTING
//
// Author :Vritee Dhall
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


    public function getMessageDetail($conditions='', $limit = '', $orderBy=' Dated'){ 
     
        global $sessionHandler;

        $query="SELECT 
			        DISTINCT 
                            dated, messageType, message, messageId,
			                 IF(u.roleId =1, 'Administrator', 
			                    IF(u.roleId =4, (SELECT 
						                 DISTINCT CONCAT( IFNULL( firstName, '' ) , ' ', IFNULL( lastName, '' ) ) AS studentName
					                     FROM 
						                 `student` s
					                     WHERE s.userId = u.userId ), 
			                     IF( u.roleId =3, 'Parent', (SELECT 
							                     DISTINCT employeeName
			 				                 FROM 
							                     `employee` e
				                WHERE e.userId = u.userId
				                ) ) )
				                ) AS userName
		        FROM 
			        `admin_messages` a, `user` u, `role` r,`class` c, student s, student_groups sg
		        WHERE 
			        a.senderId = u.userId
			        AND a.receiverType = 'student'
			        AND u.roleId=r.roleId 
			        AND sg.classId = c.classId
			        AND sg.studentId = s.studentId 
		        $conditions
		        ORDER BY 
			        $orderBy $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
     } 


//--------------------------------------------------------------------------------
// THIS FUNCTION GIVES THE MESSAGE DETAILS
//
// Author :Vritee Dhall
// Created on : (05.08.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getMessageValue($conditions=''){

        global $sessionHandler;   

        $query="SELECT dated, messageType, message, messageId, IF( u.roleId =1, 'Admin', IF( u.roleId =4, (
		SELECT DISTINCT CONCAT( IFNULL( firstName, '' ) , ' ', IFNULL( lastName, '' ) ) AS studentName
			FROM student s
				WHERE s.userId = u.userId ) , IF( u.roleId =3, 'Parent', (
					SELECT DISTINCT employeeName
						FROM employee e
					WHERE e.userId = u.userId
					) ) )
				) AS userName
		FROM `admin_messages` a, `user` u, role r
			WHERE a.senderId = u.userId
			AND receiverType = 'student'
			AND u.roleId = r.roleId
	                AND  messageId= $conditions";


          return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");     
     } 
    //--------------------------------------------------------------------------------
    // Purpose: get all message
    // Author :Parveen Sharma
    // Created on : (26.11.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-------------------------------------------------------------------------------           
     public function getTotalSMSDetailList($condition='') {
		
         global $sessionHandler;   
         $query = "SELECT count(*) as totalRecords
                   FROM     `admin_messages` a, `user` u
                   WHERE    a.senderId=u.userId AND 
                            a.instituteId=".$sessionHandler->getSessionVariable('InstituteId')." 
                   AND      u.instituteId=".$sessionHandler->getSessionVariable('InstituteId')." 
                   AND      a.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                            $condition";
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
     }     
     
	//--------------------------------------------------------------------------------
    // Purpose: get all total message by sender
    // Author :Parveen Sharma
    // Created on : (26.11.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-------------------------------------------------------------------------------           
     public function getSMSDetailList($condition='',$orderBy,$limit) {
		
         global $sessionHandler;   
         $query = "SELECT dated, messageType, message, messageId, IF( u.roleId =1, 'Admin', IF( u.roleId =4, (
			SELECT DISTINCT CONCAT( IFNULL( firstName, '' ) , ' ', IFNULL( lastName, '' ) ) AS studentName
								FROM student s
									WHERE s.userId = u.userId ) , IF( u.roleId =3, 'Parent', (
											SELECT DISTINCT employeeName
												FROM employee e
													WHERE e.userId = u.userId
													) ) )
													) AS userName
											FROM `admin_messages` a, `user` u, role r
										WHERE a.senderId = u.userId
									AND u.roleId = r.roleId
								ORDER BY 
			$orderBy $limit";
			
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
     }     
       
   
    //--------------------------------------------------------------------------------
    // Purpose: get all message sum by Sender
    // Author :Parveen Sharma
    // Created on : (27.11.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-------------------------------------------------------------------------------           
     public function getTotalSMSFullDetailList($condition='') {
        
         global $sessionHandler;   
         $query = "SELECT    COUNT(*) AS totalRecords
                   FROM     
                            `admin_messages` a, `user` u
                    WHERE   
                            a.senderId=u.userId AND 
                            a.instituteId=".$sessionHandler->getSessionVariable('InstituteId')." AND      
                            a.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    $condition 
                   GROUP BY 
                            senderId,messageType";
                            
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
     }  
   
    //--------------------------------------------------------------------------------
    // Purpose: get all total message by sender
    // Author :Parveen Sharma
    // Created on : (27.11.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-------------------------------------------------------------------------------           
    
     public function getSMSFullDetailList($condition='',$orderBy,$limit) {
         global $sessionHandler;   
         $query = "SELECT messageType, MIN(DATE_FORMAT(dated,'%Y-%m-%d')) AS dated, messageId
                   FROM     `admin_messages` a, `user` u
                   WHERE    a.senderId=u.userId AND 
                            a.instituteId=".$sessionHandler->getSessionVariable('InstituteId')." AND
                            a.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                   $condition 
                   GROUP BY senderId, userName, messageType, receiverType 
                   $orderBy $limit";
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
     }
	     //--------------------------------------------------------------------------------
    // Purpose: get all send messsages receverIds
    // Created on : (30.1.2011)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-------------------------------------------------------------------------------  
	 public function sendMessagesIds($messageId){
		 $query = "SELECT	receiverIds
					FROM	`admin_messages`
					WHERE	messageId = $messageId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	 }
	  //--------------------------------------------------------------------------------
    // Purpose: get all undelivered messagesIds
    // Created on : (30.1.2011)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-------------------------------------------------------------------------------  
	 public function getUndeliveredMessagesIds($messageId){
		 $query = "SELECT	receiverIds
					FROM	`admin_messages_failed`
					WHERE	messageId=$messageId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	 }
	 	  //--------------------------------------------------------------------------------
    // Purpose: get all delivered  messages details
    // Created on : (30.1.2011)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-------------------------------------------------------------------------------  
	 public function getdeliveredMessages($messageId,$dlvrMessagesIdsList,$orderBy,$limit){
		 $condtion ='';
		 $condition1='';
		 if($dlvrMessagesIdsList !=''){
		 $condition  = "AND b.studentId IN($dlvrMessagesIdsList) ";
		 $condition1 ="AND b.employeeId IN($dlvrMessagesIdsList)";
		 }

		 $query="	SELECT				c.userName AS userName, 
										a.dated,
										CONCAT(b.fatherName,'(',b.rollNo,')') AS name,
										d.roleName AS role
					FROM				admin_messages a, 
										student b, 
										`user` c, 
										role d
					WHERE				a.messageId =$messageId
					AND					a.receiverType = 'Father'
					AND					b.fatherUserId = c.userId
					AND					c.roleId = d.roleId
					$condition
					UNION 
					SELECT				c.userName AS userName, 
										a.dated,
										CONCAT(b.fatherName,'(',b.rollNo,')') AS name, 
										d.roleName AS role
					FROM				`admin_messages` a, 
										`student` b, 
										`user` c, 
										`role` d
					WHERE				a.messageId =$messageId
					AND					a.receiverType = 'Mother'
					AND					b.motherUserId = c.userId
					AND					c.roleId = d.roleId
					$condition
					UNION 
					SELECT				c.userName AS userName, 
										a.dated,
										CONCAT(b.guardianUserId,'(',b.rollNo,')') AS name, 
										d.roleName AS role
					FROM 				`admin_messages` a, 
										`student` b, 
										`user` c, 
										`role` d
					WHERE				a.messageId =$messageId
					AND					a.receiverType = 'Guardian'
					AND					FIND_IN_SET( b.userId, a.receiverIds )
					AND					b.guardianUserId = c.userId
					AND					c.roleId = d.roleId
					$condition
					UNION 
					SELECT				c.userName AS userName, 
										a.dated,
										CONCAT( b.firstName, '', b.lastName,'(',b.rollNo,')' ) AS name, 
										d.roleName AS role
					FROM				admin_messages a, 
										`student` b, 
										`user` c, 
										`role` d
					WHERE				a.messageId =$messageId
					AND					a.receiverType = 'Student'
					AND					b.userId = c.userId
					AND					c.roleId = d.roleId
					$condition
					UNION
					SELECT 				c.username AS userName , 
										a.dated,
										CONCAT(b.employeeName,'(',b.employeeCode,')') AS name,
										d.roleName AS role
					FROM 				`admin_messages` a, 
										`employee` b, 
										`user` c, 										
										`role` d
					WHERE 				a.messageId =$messageId
					AND 				a.receiverType = 'Employee'
					AND 				b.userId = c.userId
					AND 				c.roleId = d.roleId
					$condition1
					$orderBy $limit
				";
				//echo $query; die;
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	 }
	 	  //--------------------------------------------------------------------------------
    // Purpose: get all undelivered messages details
    // Created on : (30.1.2011)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-------------------------------------------------------------------------------  
	 public function getUndeliveredMessages($messageId,$orderBy,$limit){
		 $query="
				SELECT				c.userName AS userName,
									a.dated,
									CONCAT(b.fatherName,'(',b.rollNo,')') AS name,
									d.roleName AS role
				FROM				admin_messages_failed a, 
									student b, 
									`user` c, 
									role d
				WHERE				a.messageId =$messageId
				AND					a.receiverType = 'Father'
				AND					FIND_IN_SET( b.studentId, a.receiverIds )
				AND					b.fatherUserId = c.userId
				AND					c.roleId = d.roleId
				UNION 
				SELECT				c.userName AS userName, 
									a.dated,
									CONCAT(b.fatherName,'(',b.rollNo,')') AS name, 
									d.roleName AS role
				FROM				`admin_messages_failed` a, 
									`student` b, 
									`user` c, 
									`role` d
				WHERE				a.messageId =$messageId
				AND					a.receiverType = 'Mother'
				AND					FIND_IN_SET( b.studentId, a.receiverIds )
				AND					b.motherUserId = c.userId
				AND					c.roleId = d.roleId
				UNION 
				SELECT				c.userName AS userName,
									a.dated,
									CONCAT(b.guardianUserId,'(',b.rollNo,')') AS name, 
									d.roleName AS role
				FROM 				`admin_messages_failed` a, 
									`student` b, 
									`user` c, 
									`role` d
				WHERE				a.messageId =$messageId
				AND					a.receiverType = 'Guardian'
				AND					FIND_IN_SET( b.studentId, a.receiverIds )
				AND					b.guardianUserId = c.userId
				AND					c.roleId = d.roleId
				UNION 
				SELECT				c.userName AS userName,
									a.dated,
									CONCAT( b.firstName, '', b.lastName,'(',b.rollNo,')' ) AS name, 
									d.roleName AS role
				FROM				admin_messages_failed a, 
									`student` b, 
									`user` c, 
									`role` d
				WHERE				a.messageId =$messageId
				AND					a.receiverType = 'Student'
				AND					FIND_IN_SET( b.studentId, a.receiverIds )
				AND					b.userId = c.userId
				AND					c.roleId = d.roleId
				UNION
				SELECT 				c.username AS userName , 
									a.dated,
									CONCAT(b.employeeName,'(',b.employeeCode,')') AS name,
									d.roleName AS role
				FROM 				`admin_messages_failed` a, 
									`employee` b, 
									`user` c, 										
									`role` d
				WHERE 				a.messageId =$messageId
				AND 				a.receiverType = 'Employee'
				AND 				FIND_IN_SET( b.employeeId, a.receiverIds )
				AND 				b.userId = c.userId
				AND 				c.roleId = d.roleId
				$orderBy $limit
				";
				
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	 }
	 	  //--------------------------------------------------------------------------------
    // Purpose: get   message details
    // Created on : (30.1.2011)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //------------------------------------------------------------------------------- 
	 public function getMessageDetails($messageId){
					$query = "
							SELECT		dated,
										message,
										messageType
							FROM		`admin_messages`
							WHERE		messageId=$messageId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
	 }





//--------------------------------------------------------------------------------
// Purpose: Change SMS delivery status
// Author :Abhiraj
// Created on : (24.03.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------            
    
     public function updateDeliveryStatus() {
         
         $query="Select smsStatus,msgId from sms_messages where updateBit=0 and smsStatus>0";
         $result=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
         if(count($result)>0)
         {
             for($i=0;$i<count($result);$i++)
            {
                $msgId=$result[$i]['msgId'];
               $ch = curl_init();
               curl_setopt($ch, CURLOPT_URL, "http://chalkpad.noesisinfoway.com/rep.php"); //set the url
               curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //return as a variable
               curl_setopt($ch, CURLOPT_POST, 1); //set POST method
               $postVars = SMS_GATEWAY_USER_VARIABLE.'='.SMS_GATEWAY_USERNAME.'&'.SMS_GATEWAY_PASS_VARIABLE.'='.SMS_GATEWAY_PASSWORD.'&msgid='.$msgId;
               curl_setopt($ch, CURLOPT_POSTFIELDS, $postVars); //set the POST variables
               $response = curl_exec($ch); //run the whole process and return the response
               curl_close($ch);
               $responseArray=explode(" ",trim($response));
               if(trim($responseArray[1])=='DELIVRD')
               {
                   $smsStatus=2; 
               }
			   elseif(preg_match("/stat:DND/i",$response))
               {
                    $smsStatus=4;
               }
               elseif(trim($responseArray[1])=='UNDELIVRD')
               {
                   $smsStatus=3; 
               }
               else
               {
                    $smsStatus=1;
               }
               if($smsStatus==1)
               {
                   $query = "update sms_messages set smsStatus='$smsStatus' where msgId='$msgId'";
               }
               else
               {
                   $query = "update sms_messages set smsStatus='$smsStatus',updateBit=1 where msgId='$msgId'";
               }
                
                SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");     
            } 
         }                                         
        
            return;    
     }

	     //--------------------------------------------------------------------------------
    // Purpose: get all message sms_messages table for delivery reports
    // Author :Abhiraj
    // Created on : (24.03.2011)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //-------------------------------------------------------------------------------           
     public function getTotalSMSStatusDetailList($condition='') {   
         $query = "SELECT count(*) as totalRecords
                   FROM     `sms_messages`
                            $condition";
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
     }


//--------------------------------------------------------------------------------
// Purpose: Show SMS delivery report
// Author :Abhiraj
// Created on : (24.03.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------            
    
     public function getSMSStatusDetailList($filter,$orderBy,$limit) {
         // To get the status
         $query = "select 
					
							s.smsTo,s.sentDate,s.smsText,(case s.smsStatus 
							when '0' then 'Failed'	
							when '1' then 'Pending'
							when '2' then 'Delivered'
							when '3' then 'Undelivered'
							when '4' then 'Undelivered (DND)'     
							when '' then '---'end) as smsStatus,
							if(roleId='1','Admin', e.employeeName) AS smsFrom 
					from sms_messages s INNER JOIN employee e on e.employeeId=s.smsFrom
					$filter $orderBy $limit";
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
     }
}
// $History: SMSDetailManager.inc.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/16/09   Time: 3:55p
//Updated in $/LeapCC/Model
//date format check updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/21/09    Time: 1:15p
//Updated in $/LeapCC/Model
//getMessageDetail function instituteId check added
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/20/09    Time: 3:47p
//Updated in $/LeapCC/Model
//Gurkeerat: resolved issue 1161
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/20/09    Time: 10:23a
//Updated in $/LeapCC/Model
//fixed bug: 1160
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 7  *****************
//User: Parveen      Date: 11/28/08   Time: 5:36p
//Updated in $/Leap/Source/Model
//list and report formatting
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/28/08   Time: 11:30a
//Updated in $/Leap/Source/Model
//change list formatting
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/28/08   Time: 10:45a
//Updated in $/Leap/Source/Model
//changed lists view format
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/27/08   Time: 5:22p
//Updated in $/Leap/Source/Model
//add fields messages
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/27/08   Time: 1:08p
//Updated in $/Leap/Source/Model
//sms details message search
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/26/08   Time: 5:06p
//Updated in $/Leap/Source/Model
//sms details report added
//



?>
