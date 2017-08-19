<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Photo Gallery" Module
//
// Author :Aanchal Arora
// Created on : 31-Aug-2011
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class PhotoManager {
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
    
    public function addEvent($fieldValue='') {
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "INSERT INTO `photo_gallery_master` 
                  (`dateOfEntry`,`eventName`,`instituteId`,`sessionId`,userId,visibleFrom,visibleTo,eventDescription)
                  VALUES 
                  ($fieldValue)";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    
    public function editEvent($dateOfEntry,$eventName,$userId,$visibleFrom,$visibleTo,$eventDescription,$photoGalleryId) {
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "UPDATE `photo_gallery_master` 
                  SET
                      dateOfEntry = '$dateOfEntry',
                      eventName='$eventName',
                      instituteId='$instituteId',
                      sessionId='$sessionId',
                      userId='$userId',
                      visibleFrom='$visibleFrom',
                      visibleTo='$visibleTo',
                      eventDescription='$eventDescription'
                  WHERE
                       photoGalleryId = '$photoGalleryId' ";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function addEventRole($fieldValue='') {
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "INSERT INTO `photo_gallery_role` 
                  (`photoGalleryId`,`roleId`)
                  VALUES 
                  $fieldValue";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function addEventPhoto($id='') {
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "INSERT INTO `photo_gallery_detail` 
                  (`photoGalleryId`)
                  VALUES 
                  ('$id')";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function updateEventPhoto($fileName='',$comments='',$id='') {
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "UPDATE 
                        `photo_gallery_detail` 
                  SET      
                        photoName = '$fileName',
                        comments = '$comments'
                  WHERE
                        photoGalleryDetailId = $id";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    
    public function getTotalEventsPhoto($condition='') {
       
          global $sessionHandler;
          $instituteId=$sessionHandler->getSessionVariable('InstituteId');
          $sessionId=$sessionHandler->getSessionVariable('SessionId');
         
           $query = "SELECT
                         COUNT(*) AS totalRecords 
                    FROM
                         photo_gallery_master
                    WHERE
                         instituteId = $instituteId  AND     
                         sessionId = $sessionId 
                    $condition";
                          
          return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }
     
     public function getEventsPhotoList($condition='',$orderBy=' visibleFrom DESC',$limit='') {
       
          global $sessionHandler;
          $instituteId=$sessionHandler->getSessionVariable('InstituteId');
          $sessionId=$sessionHandler->getSessionVariable('SessionId');
         
          $query = "SELECT
                        t.photoGalleryId, t.dateOfEntry, t.eventName, t.totalPhotographs,
                        t.instituteId, t.sessionId, t.userId, t.visibleFrom, t.visibleTo, t.roleVisibleTo,t.eventDescription
                    FROM
                         (SELECT
                               pm.photoGalleryId, pm.dateOfEntry, pm.eventName, 
                               pm.instituteId, pm.sessionId, pm.userId, pm.visibleFrom, pm.visibleTo,
                               GROUP_CONCAT(DISTINCT roleName ORDER BY roleName SEPARATOR ', ') AS roleVisibleTo,
                               IFNULL(pm.eventDescription,'".NOT_APPLICABLE_STRING."') AS eventDescription,
                               COUNT(DISTINCT photoGalleryDetailId) AS totalPhotographs
                         FROM
                               photo_gallery_master pm 
                               LEFT JOIN photo_gallery_role pr ON pm.photoGalleryId = pr.photoGalleryId 
                               LEFT JOIN role r ON r.roleId = pr.roleId
                               LEFT JOIN photo_gallery_detail pd ON pd.photoGalleryId = pm.photoGalleryId
                         WHERE
                               pm.instituteId = $instituteId  AND     
                               pm.sessionId = $sessionId 
                         GROUP BY
                               pm.photoGalleryId) AS t      
                     $condition                               
                     ORDER BY 
                            $orderBy $limit";
                          
          return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }
    
      public function getPhoto($condition='',$orderBy=' photoGalleryId ASC') {
       
          global $sessionHandler;
          $instituteId=$sessionHandler->getSessionVariable('InstituteId');
          $sessionId=$sessionHandler->getSessionVariable('SessionId');
         
          $query = "SELECT
                          DISTINCT t.photoGalleryDetailId, t.photoGalleryId, t.dateOfEntry, t.eventName, 
                          t.instituteId, t.sessionId, t.userId, t.visibleFrom, t.visibleTo,
                          t.roleVisibleTo,t.eventDescription, t.photoName, t.comments  
                    FROM 
                        (SELECT
                               DISTINCT pd.photoGalleryDetailId, pd.photoGalleryId, pm.dateOfEntry, pm.eventName, 
                               pm.instituteId, pm.sessionId, pm.userId, pm.visibleFrom, pm.visibleTo,
                               GROUP_CONCAT(DISTINCT r.roleId) AS roleVisibleTo,pm.eventDescription,
                               pd.photoName, pd.comments
                        FROM
                               photo_gallery_master pm 
                               LEFT JOIN photo_gallery_role pr ON pm.photoGalleryId = pr.photoGalleryId 
                               LEFT JOIN role r ON r.roleId = pr.roleId
                               LEFT JOIN photo_gallery_detail pd ON pd.photoGalleryId = pm.photoGalleryId
                        WHERE
                               pm.instituteId = $instituteId  AND     
                               pm.sessionId = $sessionId 
                               $condition  
                         GROUP BY
                               pd.photoGalleryDetailId) AS t
                     ORDER BY 
                            $orderBy";
                          
          return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }
 
     public function deletePhoto($id,$tableName='',$filedName='') {
         global $sessionHandler;
             
         $query = "DELETE FROM $tableName WHERE $filedName IN ($id)";
            
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
     } 
     
     
     public function getRoleEventsPhotoList($condition='',$orderBy=' visibleFrom DESC',$limit='') {
       
          global $sessionHandler;
          $instituteId=$sessionHandler->getSessionVariable('InstituteId');
          $sessionId=$sessionHandler->getSessionVariable('SessionId');
          $roleId=$sessionHandler->getSessionVariable('RoleId');
          
          $currentDate = date('Y-m-d');
         
          $query = "SELECT
                        t.photoGalleryId, t.dateOfEntry, t.eventName, t.totalPhotographs, t.photoName,
                        t.instituteId, t.sessionId, t.userId, t.visibleFrom, t.visibleTo, t.roleVisibleTo,t.eventDescription
                    FROM
                         (SELECT
                               pm.photoGalleryId, pm.dateOfEntry, pm.eventName, pd.photoName, 
                               pm.instituteId, pm.sessionId, pm.userId, pm.visibleFrom, pm.visibleTo,
                               GROUP_CONCAT(DISTINCT roleName ORDER BY roleName SEPARATOR ', ') AS roleVisibleTo,pm.eventDescription,
                               COUNT(DISTINCT photoGalleryDetailId) AS totalPhotographs
                         FROM
                               photo_gallery_master pm 
                               LEFT JOIN photo_gallery_role pr ON pm.photoGalleryId = pr.photoGalleryId 
                               LEFT  JOIN role r ON r.roleId = pr.roleId AND r.roleId = $roleId
                               LEFT JOIN photo_gallery_detail pd ON pd.photoGalleryId = pm.photoGalleryId
                         WHERE
                               pm.instituteId = $instituteId  AND     
                               pm.sessionId = $sessionId  AND
                               pm.visibleFrom <= '$currentDate' AND pm.visibleTo >= '$currentDate'
                         GROUP BY
                               pm.photoGalleryId) AS t      
                     $condition                               
                     ORDER BY 
                            $orderBy $limit";
                          
          return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }
	
     public function getPhotoName($id) {
  
        $query = "SELECT photoName, photoGalleryId FROM photo_gallery_detail WHERE photoGalleryId IN ($id) ";
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
     } 
     
     public function getPhotos($condition='',$orderBy='photoGalleryDetailId') {
         
         global $sessionHandler;
         $instituteId=$sessionHandler->getSessionVariable('InstituteId');
         $sessionId=$sessionHandler->getSessionVariable('SessionId');
         
         // This function linked in two modules 
         // a. ListPhotoGallery Modoule = Image are getting upload and can be viewed by all roles
         // b. Display Photo Gallery Module = Only for viewing selected roles.
         
         $query = "SELECT
                       DISTINCT pd.photoGalleryDetailId, pd.photoName, pm.eventName,pm.eventDescription
                 FROM
                       photo_gallery_detail pd, photo_gallery_master pm 
                       LEFT JOIN photo_gallery_role pr ON pm.photoGalleryId = pr.photoGalleryId    
                 WHERE
                       pd.photoGalleryId = pm.photoGalleryId AND
                       pm.photoGalleryId = pr.photoGalleryId
                       $condition 
                 ORDER BY
                        $orderBy";
            
         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");   
   }

 
}
?>
