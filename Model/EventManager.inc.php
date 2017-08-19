<?php

//-------------------------------------------------------
//  This File contains Bussiness Logic of the "notice" Module
//
//
// Author :Arvind Singh Rawat
// Created on : 5-july-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');



class EventManager {
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

	public function addEvent($str) {
		global $REQUEST_DATA;
		global $sessionHandler;
		
        $query = "INSERT INTO `user_wishes_events_master` 
                  (eventWishDate,isStatus,`comments`,`abbr`)
                  VALUES 
                  $str";
		
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}
    
    public function editEvent($eventWishDate,$isStatus,$comments,$abbr,$id) {
        global $REQUEST_DATA;
        global $sessionHandler;
        
        $query = "UPDATE 
                      `user_wishes_events_master` 
                  SET
                      eventWishDate='$eventWishDate',
                      isStatus='$isStatus',
                      `comments`='$comments',
                      `abbr`='$abbr'
                  WHERE
                       userWishEventId=$id ";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function editEventCheck($isStatus,$condition='') {
        global $REQUEST_DATA;
        global $sessionHandler;
        
        $query = "UPDATE 
                      `user_wishes_events_master`
                  SET
                       isStatus='$isStatus'
                  $condition";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    
    public function addRole($str) {
        global $REQUEST_DATA;
        global $sessionHandler;
        
        $query = "INSERT INTO `user_wishes_events_detail` 
                  (userWishEventId,roleId)
                  VALUES 
                  $str";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    
    public function updateAttachmentFilenameInEvent($id, $fileName) {
        global $sessionHandler;   
        
       $query = "UPDATE 
                        `user_wishes_events_master` 
                  SET 
                        eventPhoto = '$fileName'
                  WHERE
                        userWishEventId=$id ";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function deleteEventRole($id) {      
        global $sessionHandler;  
         
        $query = "DELETE FROM user_wishes_events_detail WHERE userWishEventId=$id ";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

    // Failed Upload (delete Events)
    public function deleteEventFailedUpload($eventId) {
        global $sessionHandler;

        //First Delete the records into user_wishes_events_detail table
        $query = "DELETE FROM user_wishes_events_detail WHERE userWishEventId=$eventId ";
        $ret=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($ret===false){
           return false;
        }
        //Then delete records from user_wishes_events_master table
        $query = "DELETE FROM user_wishes_events_master WHERE userWishEventId=$eventId ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }
    
    
    
//This function is used to get the roleIds
	public function getRoleIds($lastInsertNotice){
		$query = "SELECT	roleId,instituteId
					FROM	`notice_visible_to_role`
					WHERE	noticeId = $lastInsertNotice";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	//getUserIds is used to get the user IDs correspornding to role Ids
	public function getUserIds($roleIds,$insituteIdList){
		$query = "SELECT	userId
					FROM	`user`
					WHERE	roleId IN($roleIds)
					AND		instituteId IN($insituteIdList)";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
// this function is used to get the mobile number corresponding to user Ids
	public function getMobileNumber($userId){
		$query = "SELECT	studentMobileNo AS mobileNumber
					FROM	`student`
					WHERE	userId IN($userId)

					UNION

					SELECT	mobileNumber
					FROM	`employee`
					WHERE	userId IN($userId)";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
    //Edit Notice

   

	//FUNCTION TO GET A LIST of Notices

    public function getEvent($conditions='') {
        
        global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        um.userWishEventId, um.eventWishDate, um.eventPhoto, 
                        um.isStatus, um.comments, um.abbr, ud.roleId
                  FROM 
                        user_wishes_events_master um LEFT JOIN user_wishes_events_detail ud ON um.userWishEventId = ud.userWishEventId
		          WHERE
   		                $conditions ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");                    
	}
    

	public function getUserBranch($userId){
		$query="SELECT branchId FROM employee WHERE userId=$userId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

   //Gets the notice table fields
    public function getEventList($conditions='', $limit = '', $orderBy='abbr') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
     
        $query = "SELECT 
                       t.userWishEventId, t.abbr, t.comments, t.eventWishDate,
                       t.isStatus, t.eventPhoto, t.eventRoleName, t.eventStatus   
                  FROM
                       (SELECT
                             DISTINCT u.userWishEventId,
                             u.abbr, u.comments, u.eventWishDate, u.isStatus,
                             IF(u.isStatus='0','No','Yes') AS eventStatus,
                             u.eventPhoto, GROUP_CONCAT(DISTINCT r.roleName SEPARATOR ', ') AS eventRoleName
                        FROM
                             user_wishes_events_master u 
                             LEFT JOIN  user_wishes_events_detail nr ON u.userWishEventId = nr.userWishEventId 
                             LEFT JOIN  role r ON r.roleId = nr.roleId
                        GROUP BY
                              u.userWishEventId    
                       ) AS t    
                   $conditions    
                   ORDER BY
                        $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getTotalEvent($condition ='') {
     
        $query="SELECT 
                     COUNT(*) AS cnt
                FROM
                    `user_wishes_events_master` 
                $condition";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   }  


    // Deletes the Event
     public function deleteEvent($userWishEventId) {
        global $sessionHandler;

        
        

        $query = "DELETE FROM  user_wishes_events_detail WHERE userWishEventId=$userWishEventId ";
		if(SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query")){
			//to get notice attachment name
			$queryAttachment="SELECT
                                      eventPhoto
                               FROM
                                      user_wishes_events_master
	                           WHERE
                                      userWishEventId=$userWishEventId ";
			$a=SystemDatabaseManager::getInstance()->executeQuery($queryAttachment,"Query: $queryAttachment");

			$query="DELETE FROM user_wishes_events_master WHERE userWishEventId=$userWishEventId ";
			if(SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query")){
				if($a[0]['eventPhoto']!=''){
						$File = STORAGE_PATH."/Images/Event/".$a[0]['eventPhoto'];
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
		else{
			return false;
		}
    }
    
     public function updateAttachmentFilenameInNotice($id, $fileName) {
       // echo $id;
        return SystemDatabaseManager::getInstance()->runAutoUpdate('user_wishes_events_master',
        array('eventPhoto'),
        array($fileName), "userWishEventId=$id" );
    }

        //--------------------------------------------------------------------------------------------------------------
    // THIS FUNCTION IS USED FOR displaying resource details for a particular resource
    //
    //$conditions :db clauses
    // Author :Dipanjan Bhattacharjee
    // Created on : (05.11.2008)
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //
    //---------------------------------------------------------------------------------------------------------------
    public function checkEventExists($eventId){

        $query="SELECT eventPhoto
                FROM user_wishes_events_master
                WHERE userWishEventId=".$eventId;

          return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	
	//--------------------------------------------------------------
	// Purpose : This Function fetches list of all the notifications
	// Author :Kavish Manjkhola
	// Created on : 05.04.2011
	// Copyright 2010-2011: syenergy Technologies Pvt. Ltd.
	//-----------------------------------------------------
	public function getNotificationsList($filter, $orderBy, $limit) {
			$query = "
					  SELECT
								msgId, message, publishDateTime, viewDateTime
					  FROM
								notifications
					  $filter
					  ORDER BY  $orderBy
					  $limit
					 ";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	
    //--------------------------------------------------------------
    // Purpose : This Function fetches list of all the notifications
    // Author :Kavish Manjkhola
    // Created on : 05.04.2011
    // Copyright 2010-2011: syenergy Technologies Pvt. Ltd.
    //-----------------------------------------------------
    public function getEvenetCheck($condition='') {
            
        $query = "SELECT
                      COUNT(*) AS cnt
                  FROM
                      user_wishes_events_master
                  WHERE    
                      $condition";
                      
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
    
}


?>

<?php
//$History: EventManager.inc.php $
//

?>
