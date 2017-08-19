<?php
//-------------------------------------------------------
// Purpose: To delete hostel detail
//
// Author : DB
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','PhotoGallery');   
    define('ACCESS','delete');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/PhotoManager.inc.php");
    $photoManager = PhotoManager::getInstance();
        
    
    $id=add_slashes(trim($REQUEST_DATA['photoGalleryId']));   
    $photoType =add_slashes(trim($REQUEST_DATA['photoType'])); 
    
    if($id=='') {
      $id=0;   
    }
    
    if($photoType=='OneByOne') {
        $foundArray = $photoManager->getPhotoName($id); 
        if(is_array($foundArray) && count($foundArray)>0 ) {   
          $photoName = $foundArray[0]['photoName']; 
          $photoGalleryId = $foundArray[0]['photoGalleryId']; 
        }
        if($photoGalleryId=='') {
          $photoGalleryId=0;  
        }
    }      
    
    if(SystemDatabaseManager::getInstance()->startTransaction()) {
        if($photoType=='OneByOne') {
	       $deletePhoto = $photoManager->deletePhoto($id,'photo_gallery_detail','photoGalleryDetailId');
		   if($deletePhoto === false) {
		      echo FAILURE;
		      die;
		   }
        }
        else if($photoType=='all') { 
           $deletePhoto = $photoManager->deletePhoto($id,'photo_gallery_detail','photoGalleryId');
           if($deletePhoto === false) {
              echo FAILURE;
              die;
           }  
           $photoGalleryId = $id;
        }
        
        $foundArray = $photoManager->getPhotoName($photoGalleryId);    
        if(is_array($foundArray) && count($foundArray)==0 ) {
          $deletePhoto = $photoManager->deletePhoto($photoGalleryId,'photo_gallery_role','photoGalleryId'); 
          if($deletePhoto === false) {
             echo FAILURE;
             die;
          }
          $deletePhoto = $photoManager->deletePhoto($photoGalleryId,'photo_gallery_master','photoGalleryId');    
          if($deletePhoto === false) {
             echo FAILURE;
             die;
          }
        }
        
        if(SystemDatabaseManager::getInstance()->commitTransaction()){
		  echo DELETE;
          if($photoName!='') {
            @unlink(IMG_PATH.'/EventPhotoGallery/'.$photoName);
          }
		}
		else{
		  echo FAILURE;
		  die;
		}
    }
    
// $History: $    
//
?>
