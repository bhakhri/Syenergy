<?php
//-------------------------------------------------------
// Purpose: To delete employee image
//
// Author : Parveen Sharma
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

require_once(MODEL_PATH . "/EmployeeManager.inc.php");
$employeeManager = EmployeeManager::getInstance(); 

    
    $xmlFilePath = TEMPLATES_PATH."/Xml/initDownloadImages.php";
    if(!is_writable($xmlFilePath) ) {    
       logError("unable to open user activity data xml file...");
       echo NOT_WRITEABLE_FOLDER;
       die;
    }    

    if (!isset($REQUEST_DATA['employeeId']) || trim($REQUEST_DATA['employeeId']) == '') {
        echo "Invalid Employee Id";
        die;
    }
    
    $fieldName  = " DISTINCT employeeId, IFNULL(employeeImage,'') AS employeeImage ";
    $condition  = " WHERE employeeId = ".add_slashes($REQUEST_DATA['employeeId']);
    $tableName  = " employee "; 
   
    if (trim($errorMessage) == '') {

        if ($REQUEST_DATA['employeeId']!="") {

               $fileInfoArr=$studentManager->getSingleField($tableName, $fieldName, $condition); 
        
				if(SystemDatabaseManager::getInstance()->startTransaction()) {
					$ret1 = $employeeManager->deleteEmployeeImage(add_slashes($REQUEST_DATA['employeeId']));
					if($ret1===false){
					 echo FAILURE;
					 die;  
				    }
					//echo DELETE;
					//}
					if(SystemDatabaseManager::getInstance()->commitTransaction()) {
					//delete the image associated with this tracker id
					  if(UtilityManager::notEmpty($fileInfoArr[0]['employeeImage'])) {
						if(file_exists(IMG_PATH.'/Employee/'.$fileInfoArr[0]['employeeImage'])) {
							@unlink(IMG_PATH.'/Employee/'.$fileInfoArr[0]['employeeImage']);
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
   
    
// $History: ajaxDeleteEmployeeImage.php $    
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/06/10    Time: 5:28p
//Created in $/LeapCC/Library/AdminTasks
//initial checkin
//

?>