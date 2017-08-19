<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload Photo Upload
//
// Author : Parveen Sharma
// Created on : (02.10.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','PhotoGallery');
    define('ACCESS','add');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/PhotoManager.inc.php");
    $photoManager = PhotoManager::getInstance();

    require_once(BL_PATH . "/FileUploadManager.inc.php");   
    
    require_once(MODEL_PATH . "/CropImagesManager.inc.php");    
    $cropImagesManager = CropImagesManager::getInstance();    
    
    global $sessionHandler;
       
    $sessionId = $sessionHandler->getSessionVariable('SessionId');
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $userId = $sessionHandler->getSessionVariable('UserId');
    
    $roleIdArray = $REQUEST_DATA['roleId'];
    $visibleFrom = $REQUEST_DATA['visibleFrom']; 
    $visibleTo = $REQUEST_DATA['visibleTo'];  

    $eventName  =  htmlentities(add_slashes(trim($REQUEST_DATA['eventName']))); 
    $eventDescription  =  htmlentities(add_slashes(trim($REQUEST_DATA['eventDescription']))); 
    $commentsArr = $REQUEST_DATA['comments']; 
    $hiddenIdArr = $REQUEST_DATA['hiddenId']; 
    $photoHiddenArr = $REQUEST_DATA['photoHiddenId']; 
    $mainPhotoGalleryId = $REQUEST_DATA['mainPhotoGalleryId']; 
    
    
    if(trim(add_slashes($eventName))=="") {
       echo '<script language="javascript"> parent.globalFL=1; </script>';  
       echo "Upload Error: Event Name missing.";
       die; 
    }
    
    if(SystemDatabaseManager::getInstance()->startTransaction()) {      
        
            $invalidSizeFound = "";
            
            // new event added 
            $dateOfEntry = date('Y-m-d');
            
            if($mainPhotoGalleryId=='') {  
             $mainPhotoGalleryId=0;
		$foundArray = $photoManager->getPhotoName($mainPhotoGalleryId);    
		if(is_array($foundArray) && count($foundArray)==0 ) {
		  $deletePhoto = $photoManager->deletePhoto($mainPhotoGalleryId,'photo_gallery_role','photoGalleryId'); 
		  if($deletePhoto === false) {
		     echo FAILURE;
		     die;
		  }
		  $deletePhoto = $photoManager->deletePhoto($mainPhotoGalleryId,'photo_gallery_master','photoGalleryId');    
		  if($deletePhoto === false) {
		     echo FAILURE;
		     die;
		  }
                }
                $str = "'$dateOfEntry','$eventName',$instituteId,$sessionId,$userId,'$visibleFrom','$visibleTo','$eventDescription'";
                $returnStatus = $photoManager->addEvent($str);
                if($returnStatus === false) { 
                  echo '<script language="javascript"> parent.globalFL=1; </script>';  
                  echo 'The file could not be uploaded.1';
                  die;                           
                }
                $id=SystemDatabaseManager::getInstance()->lastInsertId();    
            }
            else {
               $returnStatus = $photoManager->editEvent($dateOfEntry,$eventName,$userId,$visibleFrom,$visibleTo,$eventDescription,$mainPhotoGalleryId);
               if($returnStatus === false) { 
                  echo '<script language="javascript"> parent.globalFL=1; </script>';  
                  echo 'The file could not be uploaded.1';
                  die;                           
                } 
                $id=$mainPhotoGalleryId;  
            }
            
            $deletePhoto = $photoManager->deletePhoto($mainPhotoGalleryId,'photo_gallery_role','photoGalleryId'); 
            if($deletePhoto === false) {
              echo FAILURE;
              die;
            }
            $str ="";
            for($k=0;$k<count($roleIdArray);$k++) {
               $roleId = $roleIdArray[$k]; 
               if($str!='') {
                 $str .=",";  
               }
               $str .= "($id,$roleId)"; 
            }
            
            if($str!='') {
              $returnStatus = $photoManager->addEventRole($str);  
              if($returnStatus === false) { 
                 echo '<script language="javascript"> parent.globalFL=1; </script>';  
                 echo 'The file could not be uploaded.1';
                 die;                           
              }  
            }
            
            $i=0;     
            foreach ($_FILES["uploadPhoto"]["error"] as $key => $error) {
                if ($error == 0) {
                    $tmp_name = $_FILES["uploadPhoto"]["tmp_name"][$key];
                    $name = strtolower($_FILES["uploadPhoto"]["name"][$key]);
                    $extArr= explode('.',$name);
                    $ext = strtolower($extArr[count($extArr)-1]);
                    $size = $_FILES["fileId"]["size"][$key];
                    $comments =  htmlentities(add_slashes(trim($commentsArr[$i])));
                    
                    
                    // photo name upated
                    $returnStatus = $photoManager->addEventPhoto($id);
                    if($returnStatus === false) { 
                      echo '<script language="javascript"> parent.globalFL=1; </script>';  
                      echo 'The file could not be uploaded.';
                      die;                           
                    }
                    $photoIds=SystemDatabaseManager::getInstance()->lastInsertId(); 
                    
                    // Photo upload folder
                    $filename = $id.'_'.$photoIds.'.'.$ext;  
                    $returnStatus = upload($filename);
                    if($returnStatus === false) { 
                       echo '<script language="javascript"> parent.globalFL=1; </script>';  
                       echo 'The file could not be uploaded.';
                       die;                             
                    }
                    
                    $returnStatus = $photoManager->updateEventPhoto($filename,$comments,$photoIds);
                    if($returnStatus === false) { 
                      echo '<script language="javascript"> parent.globalFL=1; </script>';  
                      echo 'The file could not be uploaded.';
                      die;                           
                    }
                }
                else if ($error == 1) {
                  $invalidSizeFound .=  $eventName." Maximum upload size is ".ceil(MAXIMUM_FILE_SIZE/1024)." kb ";   
                  logError("The file has not been uploaded....StudentId: ".$studentArr[$i]);      
                  
                }
                $i++;  
             }

            $foundArray = $photoManager->getPhotoName($id);    
	    if(is_array($foundArray) && count($foundArray)==0 ) {
		  $deletePhoto = $photoManager->deletePhoto($mainPhotoGalleryId,'photo_gallery_role','photoGalleryId'); 
		  if($deletePhoto === false) {
		     echo FAILURE;
		     die;
		  }
		  $deletePhoto = $photoManager->deletePhoto($mainPhotoGalleryId,'photo_gallery_master','photoGalleryId');    
		  if($deletePhoto === false) {
		     echo FAILURE;
		     die;
		  }
	   }			
           if(SystemDatabaseManager::getInstance()->commitTransaction()) {  
             $errMsg=SUCCESS;
          }
     }
     echo '<script language="javascript"> parent.globalFL=1; </script>';
     echo '<script language="javascript"> parent.fileUploadError("'.$errMsg.'");</script>';
    
die;    
    
        // file record 
    
     
       
    
    
    function upload($filename='') {
             
             $targetPath = IMG_PATH.'/EventPhotoGallery/';  
             global $tmp_name;
             global $ext;
             global $size;
             global $eventName;
             global $errorMsg;
             global $cropImagesManager;
             
            $errorMsg = "";
            logError("File Upload starts....EventName: ".$eventName." Size: ".$size." File Name: ".$filename);   
            if($size > MAXIMUM_FILE_SIZE) {
               $errorMsg = " Maximum upload size is ".ceil(MAXIMUM_FILE_SIZE/1024)." kb \n"; 
               return false;
            }
            if(!is_writable($targetPath) ) {
               @chmod($targetPath,777);
            }
            
            
            if($filename!='') {
              logError("File Upload starts....EventName: ".$eventName." Size: ".$size." File Name: ".$filename);     
              if(file_exists($targetPath.$filename)) {
                @unlink($targetPath.$filename);
              }  
            }
              
            $cropImagesManager->load($tmp_name);
            $cropImagesManager->resize(250,240);
            $cropImagesManager->save($targetPath.$filename); 
            
            logError("The file has been uploaded successfully....EventName: ".$eventName." Size: ".$size." File Name: ".$filename);      
            return true;   
            
           
        }

  
 ?>
 
