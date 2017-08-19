<?php

//-------------------------------------------------------
//  This File contains Bussiness Logic of the "notice" Module
//
//
// Author :Arvind Singh Rawat
// Created on : 5-july-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');



class NoticeManager {
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

	public function addNotice() {
		global $REQUEST_DATA;
		global $sessionHandler;
		$branchId = $REQUEST_DATA['branchId'];
		if($branchId == '') {
		  $branchId = "NULL";
		}
		
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
		SystemDatabaseManager::getInstance()->runAutoInsert('notice', 
        array('departmentId','noticeSubject','noticeText','visibleFromDate','visibleToDate', 
              'sendSms','smsText','instituteId','visibleMode','noticePublishTo'), 
        array($REQUEST_DATA['departmentId'],htmlentities(trim($REQUEST_DATA['noticeSubject'])),
        htmlentities($REQUEST_DATA['noticeText']),trim($REQUEST_DATA['visibleFromDate']),trim($REQUEST_DATA['visibleToDate']),
        trim($REQUEST_DATA['smsStatus']),trim($REQUEST_DATA['sms']),$instituteId,
        $REQUEST_DATA['visibleMode'],$REQUEST_DATA['noticePublishTo']));

	  	$noticeId=SystemDatabaseManager::getInstance()->lastInsertId();
		$sessionHandler->setSessionVariable('IdToFileUpload',$noticeId);
        
        if($REQUEST_DATA['noticePublishTo']==1) {
            
            $isClass='0';
            $check1=explode("~",$REQUEST_DATA['noticeClassId']);
            for($i=0;$i<sizeof($check1);$i++) {
                $query="INSERT INTO notice_visible_to_class (noticeId,classId) VALUES ('$noticeId','$check1[$i]')";
                $return = SystemDatabaseManager::getInstance()->executeUpdate($query);
                $isClass='1';
                if($return == false) {
                  return FAILURE;
                  break;
               }
            }
            
            $check=explode("~",$REQUEST_DATA['roleId']);
		    for($i=0;$i<sizeof($check);$i++) {
		        $query="INSERT INTO notice_visible_to_role 
                    (noticeId,roleId,instituteId,sessionId,universityId,degreeId,branchId,isClass) VALUES 
                    ('$noticeId','$check[$i]','".$sessionHandler->getSessionVariable('InstituteId')."',
                    '".$sessionHandler->getSessionVariable('SessionId')."',".$REQUEST_DATA['universityId'].",
                    ".$REQUEST_DATA['degreeId'].",".$branchId.",'$isClass')";
                $return = SystemDatabaseManager::getInstance()->executeUpdate($query);
                if($return == false) {
                  return FAILURE;
                  break;
                }
            }
            
            
        }
        else {
            $check1=explode("~",$REQUEST_DATA['noticeInstitute']);
            for($i=0;$i<sizeof($check1);$i++) {
                $query="INSERT INTO notice_visible_to_institute (noticeId,noticeInstituteId) VALUES ('$noticeId','$check1[$i]')";
                $return = SystemDatabaseManager::getInstance()->executeUpdate($query);
                if($return == false) {
                  return FAILURE;
                  break;
               }
            }
        }
        
	    return SUCCESS;
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

    public function editNotice($id) {
       
        global $REQUEST_DATA;
        global $sessionHandler;
        $branchId = $REQUEST_DATA['branchId'];
        if($branchId == '') {
          $branchId = "NULL";
        }
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        SystemDatabaseManager::getInstance()->runAutoUpdate('notice', 
        array('departmentId','noticeSubject','noticeText','visibleFromDate','visibleToDate', 
              'sendSms','smsText','instituteId','visibleMode','noticePublishTo'), 
        array($REQUEST_DATA['departmentId'],htmlentities(trim($REQUEST_DATA['noticeSubject'])),
        htmlentities($REQUEST_DATA['noticeText']),trim($REQUEST_DATA['visibleFromDate']),trim($REQUEST_DATA['visibleToDate']),
        trim($REQUEST_DATA['smsStatus']),trim($REQUEST_DATA['sms']),$instituteId,
        $REQUEST_DATA['visibleMode'],$REQUEST_DATA['noticePublishTo']),"noticeId=$id");

        $noticeId=$id;
       
        $query="DELETE FROM notice_visible_to_role WHERE noticeId='".$id."'";
        SystemDatabaseManager::getInstance()->executeUpdate($query);
       
        $query="DELETE FROM notice_visible_to_class WHERE noticeId='".$id."'";
        SystemDatabaseManager::getInstance()->executeUpdate($query);
         
        $query="DELETE FROM notice_visible_to_institute WHERE noticeId='".$id."'";
        SystemDatabaseManager::getInstance()->executeUpdate($query);
        
        if($REQUEST_DATA['noticePublishTo']==1) {
            
            $isClass='0';
            $check1=explode("~",$REQUEST_DATA['noticeClassId']);
            for($i=0;$i<sizeof($check1);$i++) {
              $query="INSERT INTO notice_visible_to_class (noticeId,classId) VALUES ('$noticeId','$check1[$i]')";
              $return = SystemDatabaseManager::getInstance()->executeUpdate($query);
              $isClass='1';
              if($return == false) {
                return FAILURE;
                break;
              }
            }
            
            $check=explode("~",$REQUEST_DATA['roleId']);
            for($i=0;$i<sizeof($check);$i++) {
                $query="INSERT INTO notice_visible_to_role 
                    (noticeId,roleId,instituteId,sessionId,universityId,degreeId,branchId,isClass) VALUES 
                    ('$noticeId','$check[$i]','".$sessionHandler->getSessionVariable('InstituteId')."',
                    '".$sessionHandler->getSessionVariable('SessionId')."',".$REQUEST_DATA['universityId'].",
                    ".$REQUEST_DATA['degreeId'].",".$branchId.",'$isClass')";
                $return = SystemDatabaseManager::getInstance()->executeUpdate($query);
                if($return == false) {
                  return FAILURE;
                  break;
                }
            }
            
            
        }
        else {
            $check1=explode("~",$REQUEST_DATA['noticeInstitute']);
            for($i=0;$i<sizeof($check1);$i++) {
                $query="INSERT INTO notice_visible_to_institute (noticeId,noticeInstituteId) VALUES ('$noticeId','$check1[$i]')";
                $return = SystemDatabaseManager::getInstance()->executeUpdate($query);
                if($return == false) {
                  return FAILURE;
                  break;
               }
            }
        }
        
        return SUCCESS;
    }

	//FUNCTION TO GET A LIST of Notices

    public function getNotice($conditions='') {
        
        global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                        n.departmentId,n.noticeId,n.noticeSubject,n.noticeText,n.visibleFromDate,
                        n.noticeAttachment, n.visibleToDate,n.sendSms,n.smsText, nr.roleId,nr.instituteId,
                        nr.sessionId,nr.universityId,nr.degreeId,nr.branchId,
                        n.visibleMode, n.noticePublishTo
                   FROM 
                        department d, 
                        notice n LEFT JOIN notice_visible_to_role nr ON n.noticeId=nr.noticeId
		           WHERE
		                n.instituteId = $instituteId
                        AND d.departmentId=n.departmentId
   		           $conditions 
                   LIMIT 1";
		$result= SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    	
        
        $roleQuery = "SELECT nr.roleId FROM notice n, notice_visible_to_role nr WHERE n.noticeId=nr.noticeId
		              $conditions";
        $resultRoles=SystemDatabaseManager::getInstance()->executeQuery($roleQuery,"Query: $rolequery");
        $count=count($resultRoles);
        $roleId="";
        for($i=0;$i<$count;$i++)
        {
          if($roleId=="") {
            $roleId=$resultRoles[$i]['roleId'];
          }
          else {
            $roleId.="~".$resultRoles[$i]['roleId'];
          }
        }
        $result[0]['roleId']=$roleId;
        
        
        $roleQuery = "SELECT nr.noticeInstituteId FROM notice n, notice_visible_to_institute nr WHERE n.noticeId=nr.noticeId
                      $conditions";
        $resultRoles=SystemDatabaseManager::getInstance()->executeQuery($roleQuery,"Query: $rolequery");
        $count=count($resultRoles);
        
        $noticeInstituteId="";
        for($i=0;$i<$count;$i++)
        {
          if($noticeInstituteId=="") {
            $noticeInstituteId=$resultRoles[$i]['noticeInstituteId'];
          }
          else {
            $noticeInstituteId.="~".$resultRoles[$i]['noticeInstituteId'];
          }
        }
        $result[0]['noticeInstituteId']=$noticeInstituteId;
        
        
        $roleQuery = "SELECT nr.classId FROM notice n, notice_visible_to_class nr WHERE n.noticeId=nr.noticeId
                      $conditions";
        $resultRoles=SystemDatabaseManager::getInstance()->executeQuery($roleQuery,"Query: $rolequery");
        $count=count($resultRoles);
        $noticeClassId="";
        for($i=0;$i<$count;$i++)
        {
          if($noticeClassId=="") {
            $noticeClassId=$resultRoles[$i]['classId'];
          }
          else {
            $noticeClassId.="~".$resultRoles[$i]['classId'];
          }
        }
        $result[0]['noticeClassId']=$noticeClassId;
        
        
        return $result;
	}

	public function getUserBranch($userId){
		$query="SELECT branchId FROM employee WHERE userId=$userId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
    
    
    public function getNoticeRole($conditions='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        $userId=$sessionHandler->getSessionVariable('UserId');
        
        $query = "SELECT
                        DISTINCT n.roleId, n.noticeId, r.roleName
                  FROM
                        notice_visible_to_role n, role r
                  WHERE
                        r.roleId = n.roleId
                  $conditions
                  ORDER BY
                       roleName ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getNoticeInstitute($conditions='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        $userId=$sessionHandler->getSessionVariable('UserId');
        
        $query = "SELECT
                        DISTINCT  n.noticeId, i.instituteName, i.instituteCode
                  FROM
                        notice_visible_to_institute n, institute i
                  WHERE
                        n.noticeInstituteId = i.instituteId
                        $conditions
                  ORDER BY
                        instituteCode ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
     public function getNoticeClass($conditions='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        $userId=$sessionHandler->getSessionVariable('UserId');
        
        $query = "SELECT
                        DISTINCT c.className, c.classId
                  FROM
                        notice_visible_to_class n, class c
                  WHERE
                        n.classId = c.classId
                  $conditions
                  ORDER BY
                        className ";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    
    public function getNoticeListNew($conditions='', $limit = '', $orderBy=' noticeSubject') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        $userId=$sessionHandler->getSessionVariable('UserId');

        
        $query = "SELECT
                        DISTINCT 
                        n.noticeId, IFNULL(dept.departmentName,'".NOT_APPLICABLE_STRING."') AS departmentName,
                        n.noticeSubject, n.noticeText, n.visibleFromDate, n.visibleToDate, n.noticeAttachment, 
                        IFNULL(IF(n.visibleMode=1,'New',IF(n.visibleMode=2,'Important',IF(n.visibleMode=3,'Urgent','New'))),'New') AS visibleMode,
                        IF(n.noticePublishTo=1,'Role','Institute') AS noticePublishTo
                  FROM
                        notice n
                        LEFT JOIN department dept ON dept.departmentId = n.departmentId
                  WHERE
                        n.instituteId = '".$instituteId."'
                        $conditions
                  ORDER BY
                        $orderBy $limit";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getTotalNoticeNew($conditions='', $limit = '', $orderBy=' noticeSubject') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        $userId=$sessionHandler->getSessionVariable('UserId');

        
        $query = "SELECT
                        COUNT(*) AS totalRecords
                  FROM      
                    (SELECT
                            DISTINCT 
                            n.noticeId, IFNULL(dept.departmentName,'".NOT_APPLICABLE_STRING."') AS departmentName,
                            n.noticeSubject, n.noticeText, n.visibleFromDate, n.visibleToDate, n.noticeAttachment, 
                            IFNULL(IF(n.visibleMode=1,'New',IF(n.visibleMode=2,'Important',IF(n.visibleMode=3,'Urgent','New'))),'New') AS visibleMode,
                            IF(n.noticePublishTo=1,'Role','Institute') AS noticePublishTo
                      FROM
                            notice n LEFT JOIN department dept ON dept.departmentId = n.departmentId
                      WHERE
                            n.instituteId = '".$instituteId."'
                            $conditions) AS tt";
                        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

   //Gets the notice table fields
    public function getNoticeList($conditions='', $limit = '', $orderBy=' noticeSubject') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        $userId=$sessionHandler->getSessionVariable('UserId');

        $extraCondition='';
        if($roleId!=1 and roleId!=5){ //1 : admin,5:management
         //check if this user has branchId
         $branchArray=$this->getUserBranch($userId);
         if($branchArray[0]['branchId']!=''){
          $extraCondition=' AND ( nr.branchId='.$branchArray[0]['branchId'].' OR nr.branchId IS NULL )';
         }
         $extraCondition .=' AND nr.roleId='.$roleId;
        }

        if($conditions=='') {
           $conditions = " WHERE n.instituteid= '".$instituteId."'";
        }
        else {
           $conditions .= " AND n.instituteid= '".$instituteId."'";
        }

        if($roleId!=1) {
           $conditions .= " AND nr.roleId='".$roleId."'";
        }
      
        $query = "SELECT
                        DISTINCT nr.noticeId,
                        GROUP_CONCAT(DISTINCT r.roleName SEPARATOR ', ') AS roleName,
                        IFNULL(dept.departmentName,'".NOT_APPLICABLE_STRING."') AS departmentName,
                        n.noticeSubject, n.noticeText, n.visibleFromDate, n.visibleToDate,
                        n.noticeAttachment,nr.universityId,nr.degreeId,nr.branchId, 
                        IFNULL(IF(n.visibleMode=1,'New',IF(n.visibleMode=2,'Important',IF(n.visibleMode=3,'Urgent','New'))),'New') AS visibleMode
                  FROM
                        notice n
                        LEFT JOIN notice_visible_to_role nr ON
                        ( n.noticeId = nr.noticeId AND nr.instituteid= '".$instituteId."' AND nr.sessionId= '".$sessionId."' $extraCondition )
                        LEFT JOIN department dept ON dept.departmentId = n.departmentId
                        INNER JOIN role r ON r.roleId = nr.roleId
                  $conditions
                  GROUP BY
                         nr.noticeId
                  ORDER BY
                        $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getNoticeStatus($noticeId) {
        $query = "SELECT IF (visibleToDate < curdate(), 'expired','active') AS noticeStatus FROM notice WHERE noticeId = $noticeId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



    public function getTotalNotice($conditions='') {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        $userId=$sessionHandler->getSessionVariable('UserId');

        $extraCondition='';
        if($roleId!=1 and roleId!=5){ //1 : admin,5:management
         //check if this user has branchId
         $branchArray=$this->getUserBranch($userId);
         if($branchArray[0]['branchId']!=''){
          $extraCondition=' AND ( nr.branchId='.$branchArray[0]['branchId'].' OR nr.branchId IS NULL )';
         }
         $extraCondition .=' AND nr.roleId='.$roleId;
        }

        if($conditions=='') {
           $conditions = " WHERE n.instituteid= '".$instituteId."'";
        }
        else {
           $conditions .= " AND n.instituteid= '".$instituteId."'";
        }

        if($roleId!=1) {
           $conditions .= " AND nr.roleId='".$roleId."'";
        }

        $query = "SELECT
                        COUNT(*) AS totalRecords
                  FROM
                        (SELECT
                                DISTINCT nr.noticeId,
                                GROUP_CONCAT(DISTINCT r.roleName SEPARATOR ', ') AS roleName,
                                IFNULL(dept.departmentName,'".NOT_APPLICABLE_STRING."') AS departmentName,
                                n.noticeSubject, n.noticeText, n.visibleFromDate, n.visibleToDate,
                                n.noticeAttachment,nr.universityId,nr.degreeId,nr.branchId
                         FROM
                                notice n
                                LEFT JOIN notice_visible_to_role nr ON
                                ( n.noticeId = nr.noticeId AND nr.instituteid= '".$instituteId."' AND nr.sessionId= '".$sessionId."' $extraCondition )
                                LEFT JOIN department dept ON dept.departmentId = n.departmentId
                                INNER JOIN role r ON r.roleId = nr.roleId
                         $conditions
                         GROUP BY
                                 nr.noticeId) AS t";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    // Deletes the notice
     public function deleteNotice($noticeId) {
        global $sessionHandler;

        //checking for past events less than current day
        $chkStr="SELECT visibleToDate FROM notice WHERE noticeId=$noticeId AND visibleToDate <CURRENT_DATE";
        $chk=SystemDatabaseManager::getInstance()->executeQuery($chkStr,"Query: $chkStr");
        if($chk[0]['visibleToDate']!=''){
            return OLD_NOTICE;
        }

        $query = "DELETE FROM notice_visible_to_role WHERE noticeId=$noticeId ";
		if(SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query")){
			//to get notice attachment name
			$queryAttachment="SELECT
                                      noticeAttachment
                               FROM
                                      notice
	                           WHERE
                                      noticeId=$noticeId ";
			$a=SystemDatabaseManager::getInstance()->executeQuery($queryAttachment,"Query: $queryAttachment");

			$query="DELETE FROM notice WHERE noticeId=$noticeId ";
			if(SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query")){
				if($a[0]['noticeAttachment']!=''){
						$File = STORAGE_PATH."/Images/Notice/".$a[0]['noticeAttachment'];
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

    /*
    @@ purpose: To update filename(for logo image) in 'university' table
    @@ author: Dipanjan Bhattacharjee
    @@ Params: Id (University ID), filename (name of the file)
    @@ created On: 23.06.2008
    @@ returns: boolean value
    */
    public function updateAttachmentFilenameInNotice($id, $fileName) {
       // echo $id;
        return SystemDatabaseManager::getInstance()->runAutoUpdate('notice',
        array('noticeAttachment'),
        array($fileName), "noticeId=$id" );
    }

    // Failed Upload (delete Notices)
    public function deleteNoticeFailedUpload($noticeId) {
        global $sessionHandler;

        //First Delete the records into notice_visible_to_role table
        $query = "DELETE FROM notice_visible_to_role WHERE noticeId=$noticeId ";
        $ret=SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
        if($ret===false){
           return false;
        }
        //Then delete records from notice table
        $query = "DELETE FROM notice WHERE noticeId=$noticeId ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }
        //--------------------------------------------------------------------------------------------------------------
    // THIS FUNCTION IS USED FOR displaying resource details for a particular resource
    //
    //$conditions :db clauses
    // Author :Dipanjan Bhattacharjee
    // Created on : (05.11.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //---------------------------------------------------------------------------------------------------------------
    public function checkNoticeExists($noticeId){

        $query="SELECT noticeAttachment
                FROM notice
                WHERE noticeId=".$noticeId;

          return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//----------------------------------------------------------------
	// Purpose : This Function counts the list of all the notifications
	// Author :Kavish Manjkhola
	// Created on : 05.04.2011
	// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
	//-----------------------------------------------------
	public function getNotificationsCount() {
			$query = "
					  SELECT
								count(*) as totalRecords
					  FROM
								notifications
					 ";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//--------------------------------------------------------------
	// Purpose : This Function fetches list of all the notifications
	// Author :Kavish Manjkhola
	// Created on : 05.04.2011
	// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
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
	//-------------------------------------------------------------------------------
	//getInsuranceDueDateList() is used to vehicle insurance due date list
	//Author : Kavish Manjkhola
	//Created on : 06.04.11
	//Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
	//-------------------------------------------------------------------------------  
    public function deleteInsuranceNoticePassTime($timeLimit) {
     
        $query = "	DELETE 
					FROM 
							notifications
					WHERE 
							DATEDIFF(now(),viewDateTime) > $timeLimit
				 ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

	//------------------------------------------------------------------------
	//updateNotificationViewDateTime() is used to update notification viewTime
	//Author : Kavish Manjkhola
	//Created on : 06.04.11
	//Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
	//------------------------------------------------------------------------
    public function updateNotificationViewDateTime() {
     
        $query = "	UPDATE
							notifications
					SET
							viewDateTime = now()
					WHERE	
							viewStatus = 0;
				 ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }
    
    
    public function getAllClasses($condition='',$fieldName='',$orderBy=' branchCode'){
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "SELECT 
                      DISTINCT $fieldName
                  FROM 
                      class a, branch b, university u, degree d 
                  WHERE 
                      a.instituteId = $instituteId AND a.sessionId = $sessionId 
                      AND u.universityId = a.universityId  AND d.degreeId = a.degreeId 
                      AND b.branchId = a.branchId
                      AND a.isActive IN (1,3)
                  $condition  
                  ORDER BY 
                      $orderBy";
                            
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

    }
    

public function getAllInstituteNoticesCount($conditions='') {
  
    global $sessionHandler;
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $curDate=date('Y')."-".date('m')."-".date('d');

    $extraCondition='';
    if($roleId==4 or $roleId==3){
        if($roleId==4){
         $classId=$sessionHandler->getSessionVariable('ClassId');
        }
        else{
          $classArray=$this->getStudentClass($sessionHandler->getSessionVariable('StudentId'));
          $classId=$classArray[0]['classId'];
        }
        //get university,degree and branchId of this class
        $classArray=$this->getClassMiscInfo($classId);
        if(is_array($classArray)>0 and count($classArray)>0){
            $extraCondition=' AND (
                                    (nvr.universityId IS NULL OR nvr.universityId='.$classArray[0]['universityId'].')
                                     AND
                                    (nvr.degreeId IS NULL OR nvr.degreeId='.$classArray[0]['degreeId'].')
                                     AND
                                    (nvr.branchId IS NULL OR nvr.branchId='.$classArray[0]['branchId'].')
                                   )';
        }
    }

    $query="SELECT
                    COUNT(*) AS totalRecords
            FROM        
                    (SELECT 
                            DISTINCT n.noticeId, 
                            n.noticeText,
                            n.noticeSubject,
                            n.visibleFromDate,
                            n.visibleToDate,
                            n.noticeAttachment,
                            n.downloadCount,
                            d.abbr,
                            d.departmentName ,
                            n.visibleMode
                    FROM    
                            department d, notice n INNER JOIN notice_visible_to_role nvr ON  ( n.noticeId=nvr.noticeId $extraCondition ) 
                            AND isClass = CASE WHEN '1' THEN (SELECT 
                                                        DISTINCT 1 FROM notice_visible_to_class c 
                                                  WHERE 
                                                        n.noticeId=c.noticeId AND c.classId='$classId' LIMIT 0,1)  ELSE '0' END
                    WHERE    
                            nvr.roleId=$roleId          
                            AND nvr.instituteId=$instituteId 
                            AND n.instituteId=$instituteId 
                            AND nvr.sessionId=$sessionId 
                            AND n.departmentId = d.departmentId 
                            $conditions 
                    GROUP BY 
                            n.noticeId
                    UNION  
                    SELECT 
                            DISTINCT  n.noticeId, 
                            n.noticeText,
                            n.noticeSubject,
                            n.visibleFromDate,
                            n.visibleToDate,
                            n.noticeAttachment,
                            n.downloadCount,
                            d.abbr,
                            d.departmentName,
                            n.visibleMode
                      FROM  
                            department d, notice n INNER JOIN notice_visible_to_institute nvr ON (n.noticeId=nvr.noticeId) 
                      WHERE        
                            nvr.noticeInstituteId=$instituteId 
                            AND n.departmentId = d.departmentId 
                            $conditions 
                      GROUP BY 
                            n.noticeId ) AS tt " ;              
           
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getAllInstituteNotices($conditions='',$limit='',$orderBy='') {
    global $sessionHandler;
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $curDate=date('Y')."-".date('m')."-".date('d');

    $extraCondition='';
    if($roleId==4 or $roleId==3){
        if($roleId==4){
         $classId=$sessionHandler->getSessionVariable('ClassId');
        }
        else{
          $classArray=$this->getStudentClass($sessionHandler->getSessionVariable('StudentId'));
          $classId=$classArray[0]['classId'];
        }
        //get university,degree and branchId of this class
        $classArray=$this->getClassMiscInfo($classId);
        if(is_array($classArray)>0 and count($classArray)>0){
            $extraCondition=' AND (
                                    (nvr.universityId IS NULL OR nvr.universityId='.$classArray[0]['universityId'].')
                                     AND
                                    (nvr.degreeId IS NULL OR nvr.degreeId='.$classArray[0]['degreeId'].')
                                     AND
                                    (nvr.branchId IS NULL OR nvr.branchId='.$classArray[0]['branchId'].')
                                   )
                                ';
        }
    }

    $query="SELECT 
                    DISTINCT n.noticeId, 
                    n.noticeText,
                    n.noticeSubject,
                    n.visibleFromDate,
                    n.visibleToDate,
                    n.noticeAttachment,
                    n.downloadCount,
                    d.abbr,
                    d.departmentName ,
                    n.visibleMode
            FROM    
                    department d, notice n INNER JOIN notice_visible_to_role nvr ON  (n.noticeId=nvr.noticeId $extraCondition) 
                    AND isClass = CASE WHEN '1' THEN (SELECT 
                                                        DISTINCT 1 FROM notice_visible_to_class c 
                                                  WHERE 
                                                        n.noticeId=c.noticeId AND c.classId='$classId' LIMIT 0,1)  ELSE '0' END
            WHERE    
                    nvr.roleId=$roleId          
                    AND nvr.instituteId=$instituteId 
                    AND n.instituteId=$instituteId 
                    AND nvr.sessionId=$sessionId 
                    AND n.departmentId = d.departmentId 
                    $conditions 
            GROUP BY 
                    n.noticeId
            UNION  
            SELECT 
                    DISTINCT  n.noticeId, 
                    n.noticeText,
                    n.noticeSubject,
                    n.visibleFromDate,
                    n.visibleToDate,
                    n.noticeAttachment,
                    n.downloadCount,
                    d.abbr,
                    d.departmentName,
                    n.visibleMode
              FROM  
                    department d, notice n INNER JOIN notice_visible_to_institute nvr ON (n.noticeId=nvr.noticeId) 
              WHERE        
                    nvr.noticeInstituteId=$instituteId 
                    AND n.departmentId = d.departmentId 
                    $conditions 
              GROUP BY 
                    n.noticeId
              ORDER BY 
                    $orderBy $limit " ;              
           
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}
?>

