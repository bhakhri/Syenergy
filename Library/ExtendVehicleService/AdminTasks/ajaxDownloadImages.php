<?php 
//-------------------------------------------------------
//  This File contains Download Images
//
// Author :Parveen Sharma
// Created on : 03-01-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);

require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
$studentReport = StudentReportsManager::getInstance();    

    $xmlFilePath = TEMPLATES_PATH."/Xml/initDownloadImages.php";
    if(!is_writable($xmlFilePath) ) {    
        logError("unable to open user activity data xml file...");
        echo NOT_WRITEABLE_FOLDER;
        die;
    }    

        
    //UtilityManager::ifNotLoggedIn(true);
    if(trim(add_slashes($_REQUEST['reportDownload']))!="1" && trim(add_slashes($_REQUEST['reportDownload']))!="2") {
      echo "Dowload Error: ID missing.";
      die; 
    }

 
 if(trim(add_slashes($_REQUEST['reportDownload']))=="1") { 
    
    $studentId = add_slashes($REQUEST_DATA['studentId']);  

    if($studentId!='') {
      $cond = ", ".$studentId;
    }
    
    $tableName = " student s ";
    $fieldName = " DISTINCT 
                            s.studentId, 
                            IFNULL(s.rollNo,'".NOT_APPLICABLE_STRING."') AS rollNo, 
                            CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                            IFNULL(studentPhoto,'') AS studentPhoto ";
    $condition .= " WHERE s.studentStatus = 1 AND s.studentId IN (0 $cond)";
    
    $studentRecordArray = $studentReport->getSingleField($tableName, $fieldName, $condition);
    $cnt = count($studentRecordArray);

    $zipFileArray  = Array();  
    $zipFileArray1 = Array();  
    
/*    for($i=0;$i<$cnt;$i++) {
       $File = STORAGE_PATH."/Images/Student/".$studentRecordArray[$i]['studentPhoto']; 
       $Des = STORAGE_PATH."/Templates/Xml/temp/".$studentRecordArray[$i]['studentPhoto'];     
      
       if(file_exists($File)){
          copy($File,"C:/wamp/www/LeapCC/Templates/Xml/temp/".$studentRecordArray[$i]['studentPhoto']);
       }
    }
*/  

    for($i=0;$i<$cnt;$i++) {
       $File = STORAGE_PATH."/Images/Student/".$studentRecordArray[$i]['studentPhoto']; 
       if(file_exists($File)){
          $zipFileArray[] = $File;
          $ext = explode('.',$studentRecordArray[$i]['studentPhoto']);
          $newFileName = "";
          if(trim($studentRecordArray[$i]['rollNo'])==NOT_APPLICABLE_STRING || trim($studentRecordArray[$i]['rollNo']) == '') {
             $newFileName = trim($studentRecordArray[$i]['studentName']);
             $newFileName = str_replace(" ","",$newFileName)."-$i.".$ext[count($ext)-1];
          }
          else {
            $newFileName = trim($studentRecordArray[$i]['rollNo']); 
            $newFileName = str_replace(" ","",$newFileName).".".$ext[count($ext)-1];
          }
          $zipFileArray1[] = $newFileName;
       }
    }
    
    $zipOutputFilename =  "./StudentPhoto.zip";
    if(file_exists($zipOutputFilename)) {
       @unlink($zipOutputFilename);
    }
    $zipFile = new ZipArchive();
    if ($zipFile->open($zipOutputFilename, ZIPARCHIVE::CREATE)) {
        $i=0;
        foreach($zipFileArray as $zipFileName) {
            $zipFile->addFile($zipFileName,$zipFileArray1[$i]);
            $i++;
        }
        $zipFile->close();
    }
    else {
        echo FAILURE;
        die;
    }
    echo SUCCESS.'#'.  HTTP_PATH . "/Templates/Xml/StudentPhoto.zip";    
 }
 else   
 if(trim(add_slashes($_REQUEST['reportDownload']))=="2") { 
    
    $employeeId = add_slashes($REQUEST_DATA['employeeId']);  

    if($employeeId!='') {
      $cond = ", ".$employeeId;
    }
    
    $tableName = " employee e ";
    $fieldName = " DISTINCT 
                            e.employeeId, 
                            IF(IFNULL(employeeName,'')='','".NOT_APPLICABLE_STRING."',employeeName) AS employeeName,
                            IF(IFNULL(e.designationId,'')='','".NOT_APPLICABLE_STRING."', 
                                (SELECT designationName FROM designation desg WHERE desg.designationId=e.designationId)) AS designationName, 
                            e.employeeImage ";
    $condition .= " WHERE e.isActive = 1 AND e.employeeId IN (0 $cond)";
    
    $employeeRecordArray = $studentReport->getSingleField($tableName, $fieldName, $condition);
    $cnt = count($employeeRecordArray);

    $zipFileArray  = Array();  
    $zipFileArray1 = Array();  
    

    for($i=0;$i<$cnt;$i++) {
       $File = STORAGE_PATH."/Images/Employee/".$employeeRecordArray[$i]['employeeImage']; 
       if(file_exists($File)){
          $zipFileArray[] = $File;
          $ext = explode('.',$employeeRecordArray[$i]['employeeImage']);
          $newFileName = "";
          if(trim($employeeRecordArray[$i]['employeeName'])!=NOT_APPLICABLE_STRING ) {
             $newFileName = trim($employeeRecordArray[$i]['employeeName']);
             $newFileName = str_replace(" ","",$newFileName)."-$i.".$ext[count($ext)-1];
          }
          else {
            $newFileName = "emp".trim($studentRecordArray[$i]['employeeId']); 
            $newFileName = str_replace(" ","",$newFileName).".".$ext[count($ext)-1];
          }
          $zipFileArray1[] = $newFileName;
       }
    }
    
    $zipOutputFilename =  "./EmployeePhoto.zip";
    if(file_exists($zipOutputFilename)) {
       @unlink($zipOutputFilename);
    }
    $zipFile = new ZipArchive();
    if ($zipFile->open($zipOutputFilename, ZIPARCHIVE::CREATE)) {
        $i=0;
        foreach($zipFileArray as $zipFileName) {
            $zipFile->addFile($zipFileName,$zipFileArray1[$i]);
            $i++;
        }
        $zipFile->close();
    }
    else {
        echo FAILURE;
        die;
    }
    echo SUCCESS.'#'.  HTTP_PATH . "/Templates/Xml/EmployeePhoto.zip";    
 }

?>