<?php
//-------------------------------------------------------
// Purpose: To delete employee thumb image
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
define('MODULE','EmployeeMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['employeeId']) || trim($REQUEST_DATA['employeeId']) == '') {
        $errorMessage = INVALID_EMPLOYEE;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/EmployeeManager.inc.php");
        $employeeManager =  EmployeeManager::getInstance();
		//to get the information of uploaded file
        $fileInfoArr=$employeeManager->getEmployeeThumbImageDetail(' WHERE employeeId='.$REQUEST_DATA['employeeId']);
        
            if ($REQUEST_DATA['employeeId']!="") {
				if(SystemDatabaseManager::getInstance()->startTransaction()) {
					$ret1 = $employeeManager->deleteEmployeeThumbImage($REQUEST_DATA['employeeId']);
					if($ret1===false){
					 echo FAILURE;
					 die;  
				    }
					//echo DELETE;
					//}
					if(SystemDatabaseManager::getInstance()->commitTransaction()) {
					//delete the image associated with this tracker id
					  if(UtilityManager::notEmpty($fileInfoArr[0]['thumbImage'])) {
						if(file_exists(IMG_PATH.'/Employee/ThumbImage/'.$fileInfoArr[0]['thumbImage'])) {
							@unlink(IMG_PATH.'/Employee/ThumbImage/'.$fileInfoArr[0]['thumbImage']);
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
   
    
// $History: ajaxDeleteEmployeeThumbImage.php $    
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/29/10    Time: 3:32p
//Created in $/LeapCC/Library/Employee
//new files for employee experience, education & gap analysis
//
//
?>