<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE period names 
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (4.08.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','TestMarks');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();

    
if(trim($REQUEST_DATA['testId'] ) != '')  {
 //****************************************************************************************************************    
//***********************************************STRAT TRANSCATION************************************************
//****************************************************************************************************************
    if(SystemDatabaseManager::getInstance()->startTransaction()) {    
        require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
        
        //delete both marks and test
        $ret1=AdminTasksManager::getInstance()->deleteTestMarks($REQUEST_DATA['testId']);
        if($ret1===false){
            echo FAILURE;
            die;
        }
        
        $ret2=AdminTasksManager::getInstance()->deleteTest($REQUEST_DATA['testId']);
        if($ret2===false){
            echo FAILURE;
            die;
        }

      //*****************************COMMIT TRANSACTION************************* 
         if(SystemDatabaseManager::getInstance()->commitTransaction()) {
            echo   2;
            die;
         }
         else {
            echo FAILURE;
            die;
         }
      
    }
    else{
          echo FAILURE;
          die;
    }   
}
// $History: ajaxDeleteTestData.php $
//
//*****************  Version 1  *****************
//User: Administrator Date: 10/06/09   Time: 11:18
//Created in $/LeapCC/Library/AdminTasks
//Created "Test Marks" module in admin section
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 19/05/09   Time: 16:36
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added "SystemDatabase Manager" class's reference
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 16/05/09   Time: 15:23
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added "Transaction Support" For Attendance and Marks Modules in
//Leap,LeapCC ans SNS
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/09/08   Time: 3:09p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Corrected Marks modules
?>