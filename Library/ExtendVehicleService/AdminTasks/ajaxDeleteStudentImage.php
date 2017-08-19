<?php
//-------------------------------------------------------
// Purpose: To delete employee image
//
// Author : Jaineesh
// Created on : (31.08.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','COMMON');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager =  StudentManager::getInstance();

require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
$studentReport = StudentReportsManager::getInstance();    

    
    $xmlFilePath = TEMPLATES_PATH."/Xml/initDownloadImages.php";
    if(!is_writable($xmlFilePath) ) {    
       logError("unable to open user activity data xml file...");
       echo NOT_WRITEABLE_FOLDER;
       die;
    }    

    if (!isset($REQUEST_DATA['studentId']) || trim($REQUEST_DATA['studentId']) == '') {
        echo "Invalid Student Id";
        die;
    }
    
    $fieldName  = " DISTINCT studentId, IFNULL(studentPhoto,'') AS studentPhoto ";
    $condition  = " WHERE studentId = ".add_slashes($REQUEST_DATA['studentId']);
    $tableName  = " student "; 
   
    if (trim($errorMessage) == '') {

        if ($REQUEST_DATA['studentId']!="") {

               $fileInfoArr=$studentReport->getSingleField($tableName, $fieldName, $condition); 
        
				if(SystemDatabaseManager::getInstance()->startTransaction()) {
					$ret1 = $studentManager->deleteStudentImage(add_slashes($REQUEST_DATA['studentId']));
					if($ret1===false){
					 echo FAILURE;
					 die;  
				    }
					//echo DELETE;
					//}
					if(SystemDatabaseManager::getInstance()->commitTransaction()) {
					//delete the image associated with this tracker id
					  if(UtilityManager::notEmpty($fileInfoArr[0]['studentPhoto'])) {
						if(file_exists(IMG_PATH.'/Student/'.$fileInfoArr[0]['studentPhoto'])) {
							@unlink(IMG_PATH.'/Student/'.$fileInfoArr[0]['studentPhoto']);
						  }
					  } 
					  echo DELETE;
					  die;
				   }
				   else {
					  echo FAILURE;
					  die;
				   }
				}
		else {
            echo FAILURE;
            die;
      }
    }
	else {
            echo FAILURE;
            die;
      }
	}
    else {
        echo $errorMessage;
        die;
    }
   
    
// $History: ajaxDeleteStudentImage.php $    
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/23/09   Time: 6:39p
//Updated in $/LeapCC/Library/AdminTasks
//role permission check & format updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/05/09   Time: 5:37p
//Created in $/LeapCC/Library/AdminTasks
//initial checkin
//

?>