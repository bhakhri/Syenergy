<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload Notcie Attachment
//
// Author : Parveen Sharma
// Created on : (02.10.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
//require_once(BL_PATH . "/FileUploadManager.inc.php");
require_once(MODEL_PATH . "/StudentManager.inc.php");     
require_once(MODEL_PATH . "/EmployeeManager.inc.php");

define('MODULE','COMMON');
define('ACCESS','add');


 //UtilityManager::ifNotLoggedIn(true);
 if(trim(add_slashes($_REQUEST['reportFormat']))!="1" && trim(add_slashes($_REQUEST['reportFormat']))!="2") {
    echo '<script language="javascript"> 
                parent.globalFL=1; 
                parent.empGlobalFL=1; 
          </script>';  
    echo "Upload Error: ID missing.";
    die; 
 }

 if(trim(add_slashes($_REQUEST['reportFormat']))=="1") { 
        
    
         if(trim(add_slashes($_REQUEST['studentIds']))=="") {
            echo '<script language="javascript"> parent.globalFL=1; </script>';  
            echo "Upload Error: Student ID missing.";
            die; 
         }
     

   /*     $errMsg1=NOT_WRITEABLE_FOLDER; 
        
        $xmlFilePath = TEMPLATES_PATH."/Xml/initDownloadImages.php";
        if(!is_writable($xmlFilePath) ) {    
            logError("unable to open user activity data xml file...");
            echo '<script language="javascript"> parent.globalFL=1; </script>';
            echo '<script language="javascript"> parent.fileUploadError("'.$errMsg1.'");</script>';
            die;
        }   
   */     
        /**
         * Function to upload image
         * @param $targetPath: target path where to upload image
         * @param $filename : name of the file 
         * @return bool
        */
      
      
        $studentArr = explode(',',add_slashes($_REQUEST['studentIds']));
        $studentNameArr = explode(',',add_slashes($_REQUEST['studentNames1']));   
        
        $findArray = array();
        function upload($targetPath, $filename='', $oldStudentName='') {
             global $tmp_name;
             global $name;
             global $ext;
             global $size;
             global $studentName;
             global $studentId;
             global $errorMsg;
             
            $errorMsg = "";
            logError("File Upload starts....StudentId: ".$studentId." Size: ".$size." File Name: ".$filename);   
            //logError('--'.$size.'kb--'.$filename.'--'.$studentName);              
            if($size > MAXIMUM_FILE_SIZE) {
               $errorMsg = $studentName." Maximum upload size is ".ceil(MAXIMUM_FILE_SIZE/1024)." kb \n"; 
               return false;
            }
            /*  if(!in_array($this->fileExtension,$this->allowedExtensions)) {
                   $invalidSizeFound .= $studentName." Invalid file extenstion. Try only 'gif','jpg','jpeg','png','bmp' extenstions.\n";  
                   return false;
                }
            */
            if(trim($filename)=='') {
               $filename = $name;
            }               
            if(!is_writable($targetPath) ) {
               @chmod($targetPath,777);
            }
            
            if($oldStudentName!='') {
              logError("File Upload starts.1...StudentId: ".$studentId." Size: ".$size." File Name: ".$filename." Old File Name: ".$oldStudentName);     
              if(file_exists(IMG_PATH.'/Student/'.$oldStudentName)) {
                @unlink(IMG_PATH.'/Student/'.$oldStudentName);
              }  
            }
            if($filename!='') {
              logError("File Upload starts.2...StudentId: ".$studentId." Size: ".$size." File Name: ".$filename." Old File Name: ".$oldStudentName);     
              if(file_exists(IMG_PATH.'/Student/'.$filename)) {
                @unlink(IMG_PATH.'/Student/'.$filename);
              }  
            }
            if(!move_uploaded_file($tmp_name, $targetPath.$filename)) {
               $errorMsg = "The file could not be uploaded."; 
               return false;                     
            }
            else {
               logError("The file has been uploaded successfully....StudentId: ".$studentId." Size: ".$size." File Name: ".$filename." Old File Name: ".$oldStudentName);      
               return true;
            }
            return true;
        }

        $invalidSizeFound = "";
        $i=0; 
        foreach ($_FILES["fileId"]["error"] as $key => $error) {
            $studentId = $studentArr[$i];
            $oldStudentName = $studentNameArr[$i];  
            if ($error == 0) {
                $tmp_name = $_FILES["fileId"]["tmp_name"][$key];
                $name = $_FILES["fileId"]["name"][$key];
                $extArr= explode('.',$name);
                 //$ext = $_FILES["fileId"]["type"][$key];
                $ext = strtolower($extArr[count($extArr)-1]);
                $size = $_FILES["fileId"]["size"][$key];
                
                $filename = $studentId.'.'.$ext;  
                $returnStatus = upload(IMG_PATH.'/Student/',$filename,$oldStudentName);
                if($returnStatus === true) { 
                  //update logo image name in student table
                  StudentManager::getInstance()->updatePhotoFilenameInStudent($studentId,$filename);   
                  $findArray[] = $studentId;  
                }
                else {
                  $invalidSizeFound .= $errorMsg;   
                }
                $i++;
            }
            else if ($error == 1) {
              $invalidSizeFound .=  $studentNameArr[$i]." Maximum upload size is ".ceil(MAXIMUM_FILE_SIZE/1024)." kb ";   
              logError("The file has not been uploaded....StudentId: ".$studentArr[$i]);      
              $i++;
            }
        }

         $name ="";
         if($invalidSizeFound!="") {  
            $k=0; 
            for($i=0; $i< count($studentArr); $i++) {
              if($studentArr[$i]==$findArray[$k]) {
                $k++;  
              }
              else {
                 if($name=='') {
                   $name .= ",".$studentNameArr[$i];
                 }
                 else {
                   $name .= ",".$studentNameArr[$i]; 
                 }
              }
            }
            $errMsg = urlencode($name.$invalidSizeFound);
         }
         else {
            $errMsg=SUCCESS;
         }
     
         echo '<script language="javascript"> parent.globalFL=1; </script>';
         echo '<script language="javascript"> parent.fileUploadError("'.$errMsg.'");</script>';
         
 }
 
 else if(trim(add_slashes($_REQUEST['reportFormat']))=="2") {

      //UtilityManager::ifNotLoggedIn(true);
     if(trim(add_slashes($_REQUEST['employeeIds']))=="") {
        echo '<script language="javascript"> parent.empGlobalFL=1; </script>';  
        echo "Upload Error: Employee ID missing.";
        die; 
     }
 

 /* $errMsg1=NOT_WRITEABLE_FOLDER; 
    
    $xmlFilePath = TEMPLATES_PATH."/Xml/initDownloadImages.php";
    if(!is_writable($xmlFilePath) ) {    
        logError("unable to open user activity data xml file...");
        echo '<script language="javascript"> parent.empGlobalFL=1; </script>';
        echo '<script language="javascript"> parent.empFileUploadError("'.$errMsg1.'");</script>';
        die;
    }   
 */
 
    /**
     * Function to upload image
     * @param $targetPath: target path where to upload image
     * @param $filename : name of the file 
     * @return bool
    */
  
  
    $studentArr = explode(',',add_slashes($_REQUEST['employeeIds']));
    $studentNameArr = explode(',',add_slashes($_REQUEST['employeeNames1']));   
    
    $findArray = array();
    function upload($targetPath, $filename='', $oldStudentName='') {
         global $tmp_name;
         global $name;
         global $ext;
         global $size;
         global $studentName;
         global $studentId;
         global $errorMsg;
         
        $errorMsg = "";
        logError("File Upload starts....EmployeeId: ".$studentId." Size: ".$size." File Name: ".$filename);   
        //logError('--'.$size.'kb--'.$filename.'--'.$studentName);              
        if($size > MAXIMUM_FILE_SIZE) {
           $errorMsg = $studentName." Maximum upload size is ".ceil(MAXIMUM_FILE_SIZE/1024)." kb \n"; 
           return false;
        }
        /*  if(!in_array($this->fileExtension,$this->allowedExtensions)) {
               $invalidSizeFound .= $studentName." Invalid file extenstion. Try only 'gif','jpg','jpeg','png','bmp' extenstions.\n";  
               return false;
            }
        */
        if(trim($filename)=='') {
           $filename = $name;
        }               
        if(!is_writable($targetPath) ) {
           @chmod($targetPath,777);
        }
        
        if($oldStudentName!='') {
          logError("File Upload starts.1...EmployeeId: ".$studentId." Size: ".$size." File Name: ".$filename." Old File Name: ".$oldStudentName);     
          if(file_exists(IMG_PATH.'/Employee/'.$oldStudentName)) {
            @unlink(IMG_PATH.'/Employee/'.$oldStudentName);
          }  
        }
        if($filename!='') {
          logError("File Upload starts.2...EmployeeId: ".$studentId." Size: ".$size." File Name: ".$filename." Old File Name: ".$oldStudentName);     
          if(file_exists(IMG_PATH.'/Employee/'.$filename)) {
            @unlink(IMG_PATH.'/Employee/'.$filename);
          }  
        }
        if(!move_uploaded_file($tmp_name, $targetPath.$filename)) {
           $errorMsg = "The file could not be uploaded."; 
           return false;                     
        }
        else {
           logError("The file has been uploaded successfully....EmployeeId: ".$studentId." Size: ".$size." File Name: ".$filename." Old File Name: ".$oldStudentName);      
           return true;
        }
        return true;
    }

   
    $invalidSizeFound = "";
    $i=0; 
    foreach ($_FILES["empFileId"]["error"] as $key => $error) {
        $studentId = $studentArr[$i];
        $oldStudentName = $studentNameArr[$i];  
        
        if ($error == 0) {
            $tmp_name = $_FILES["empFileId"]["tmp_name"][$key];
            $name = $_FILES["empFileId"]["name"][$key];
            $extArr= explode('.',$name);
             //$ext = $_FILES["empFileId"]["type"][$key];
            $ext = strtolower($extArr[count($extArr)-1]);
            $size = $_FILES["empFileId"]["size"][$key];
            
            $filename = $studentId.'.'.$ext;  
            $returnStatus = upload(IMG_PATH.'/Employee/',$filename,$oldStudentName);
            if($returnStatus === true) { 
              //update logo image name in student table
              EmployeeManager::getInstance()->updateEmployeeImage($studentId,$filename);   
              $findArray[] = $studentId;  
            }
            else {
              $invalidSizeFound .= $errorMsg;   
            }
            $i++;
        }
        else if ($error == 1) {
          $invalidSizeFound .=  $studentNameArr[$i]." Maximum upload size is ".ceil(MAXIMUM_FILE_SIZE/1024)." kb ";   
          logError("The file has not been uploaded....EmployeeId: ".$studentArr[$i]);      
          $i++;
        }
    }

     $name ="";
     if($invalidSizeFound!="") {  
        $k=0; 
        for($i=0; $i< count($studentArr); $i++) {
          if($studentArr[$i]==$findArray[$k]) {
            $k++;  
          }
          else {
             if($name=='') {
               $name .= ",".$studentNameArr[$i];
             }
             else {
               $name .= ",".$studentNameArr[$i]; 
             }
          }
        }
        $errMsg = urlencode($name.$invalidSizeFound);
     }
     else {
        $errMsg=SUCCESS;
     }
 
     echo '<script language="javascript"> parent.empGlobalFL=1; </script>';
     echo '<script language="javascript"> parent.empFileUploadError("'.$errMsg.'");</script>';
 }
 die;   

// $History: fileUpload.php $
//
//*****************  Version 8  *****************
//User: Parveen      Date: 1/23/10    Time: 4:41p
//Updated in $/LeapCC/Library/AdminTasks
//is_writable check remove
//
//*****************  Version 7  *****************
//User: Parveen      Date: 1/06/10    Time: 3:13p
//Updated in $/LeapCC/Library/AdminTasks
//employee photos upload and download functionality added 
//
//*****************  Version 6  *****************
//User: Parveen      Date: 12/23/09   Time: 6:53p
//Updated in $/LeapCC/Library/AdminTasks
//is_writeable function added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/23/09   Time: 6:40p
//Updated in $/LeapCC/Library/AdminTasks
//is_writeable check added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/21/09   Time: 11:07a
//Updated in $/LeapCC/Library/AdminTasks
//validation format updatd 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/18/09   Time: 3:33p
//Updated in $/LeapCC/Library/AdminTasks
//code updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/18/09   Time: 3:02p
//Updated in $/LeapCC/Library/AdminTasks
//code updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/10/09   Time: 1:18p
//Created in $/LeapCC/Library/AdminTasks
//initial checkin
//

?>