<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE period names 
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (4.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(MODEL_PATH."/CommonQueryManager.inc.php");
define('MODULE','MannualExternalMarks');
define('ACCESS','delete');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();


    $testId = trim($REQUEST_DATA['testId'] );
    if($testId=='') {
      $testId = '0';        
    }


    
   /*CHECK FOR FROZEN CLASS*/
     $classIdArray=CommonQueryManager::getInstance()->getTestClass($testId);
     $classId=$classIdArray[0]['classId'];
     
     if($classId=='') {
       $classId='0';  
     }
     
     $isFrozenArray=CommonQueryManager::getInstance()->checkFrozenClass($classId);
     if($isFrozenArray[0]['isFrozen']==1){
         echo FROZEN_CLASS_RESTRICTION.$isFrozenArray[0]['className'];
         die;
     }
   /*CHECK FOR FROZEN CLASS*/
   
    
 //****************************************************************************************************************    
//***********************************************STRAT TRANSCATION************************************************
//****************************************************************************************************************
    if(SystemDatabaseManager::getInstance()->startTransaction()) {    
        require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
        
        //delete both marks and test
        $ret1=TeacherManager::getInstance()->deleteTestMarks($testId);
        if($ret1===false){
            echo FAILURE;
            die;
        }
        
        $ret2=TeacherManager::getInstance()->deleteTest($testId);
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
// $History: ajaxDeleteTestData.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 17/12/09   Time: 15:47
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added the code for "Freezed" class
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
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