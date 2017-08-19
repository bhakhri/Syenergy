<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A Fine Category
// Author : Rajeev Aggarwal
// Created on : (03.07.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FineStudentMaster');
    define('ACCESS','add');
 global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();
   $errorMessage ='';

    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineStudentManager = FineManager::getInstance();
    
    
    global $sessionHandler;
    $roleId = $sessionHandler->getSessionVariable('RoleId');
    $userId = $sessionHandler->getSessionVariable('UserId');
	
    $condition = ' AND cls.classId ="'.$REQUEST_DATA['classId'].'" AND stu.studentId="'.add_slashes(trim($REQUEST_DATA['studentId'])).'" 
                   AND fc.fineCategoryId="'.add_slashes(trim($REQUEST_DATA['fineCategoryId'])).'"  
                   AND fs.fineDate="'.add_slashes(trim($REQUEST_DATA['fineDate1'])).'"  AND fs.userId="'.add_slashes(trim($userId)).'"';    

    $foundArray = $fineStudentManager->getFineStudent($condition);
    if(trim($foundArray[0]['fineStudentId'])=='') {  //DUPLICATE CHECK
        if(SystemDatabaseManager::getInstance()->startTransaction()) {  
           $returnStatus = $fineStudentManager->addFineStudent(); 
           if($returnStatus === false) {
             echo FAILURE;
             die;
           }  
           if(SystemDatabaseManager::getInstance()->commitTransaction()) { 
             echo SUCCESS;     
           }   
        }
    }
    else {
    	echo FINE_ALREADY_EXIST ;
        die;
    }
?>    