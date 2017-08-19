<?php
//-------------------------------------------------------
// Purpose: To delete fine category  detail
// Author : Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineCategoryMaster');
define('ACCESS','delete');
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();

    if (!isset($REQUEST_DATA['fineCategoryId']) || trim($REQUEST_DATA['fineCategoryId']) == '') {
        $errorMessage = 'Invalid Fine Category';
        die;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FineManager.inc.php");
        $fineCategoryManager =  FineManager::getInstance();
     //  echo $REQUEST_DATA['fineCategoryId'];
		$recordArray = $fineCategoryManager->countFineType($REQUEST_DATA['fineCategoryId']); // 
		$cnt = COUNT($recordArray);
		if($cnt > 0 ) {
            if($fineCategoryManager->deleteFineCategory($REQUEST_DATA['fineCategoryId']) ) {
                echo DELETE;
                die;
            }
           else {
                echo DEPENDENCY_CONSTRAINT;
                die;
            }
 
        }
        else {
            echo DEPENDENCY_CONSTRAINT;
            die;
        }
        
    }
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/07/09    Time: 16:46
//Updated in $/LeapCC/Library/Fine
//Changes html and model files names in "Fine  Category" module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/07/09    Time: 15:30
//Created in $/LeapCC/Library/Fine
//Added files for "fine_category" module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/07/09    Time: 16:07
//Created in $/LeapCC/Library/FineCategory
//Created "Fine Category Master" module
?>