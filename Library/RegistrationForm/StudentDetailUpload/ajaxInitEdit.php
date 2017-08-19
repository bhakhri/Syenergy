<?php 
//-------------------------------------------------------
// Purpose: To design the Student Fee Concession Mapping     
//
// Author : Parveen Sharma
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    define('MODULE','UpdateStudentStatus');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
	
    require_once(MODEL_PATH . "/RegistrationForm/ScStudentStatusUpload.inc.php");
    $studentStatusManager=StudentStatusUpload::getInstance();


    $classId = implode(',',$REQUEST_DATA['classId']);
    $cnt=count($REQUEST_DATA['chb1']);
    $cnt2=count($REQUEST_DATA['chb2']);
    $cnt3=count($REQUEST_DATA['chb3']);  

   if ($cnt==0 && $cnt2==0 && $cnt3==0){
	 $errorMessage='No Value Selected';
   }
   if($errorMessage=='') {
         // updating change in day scholar status if any
        for($i=0;$i<$cnt;$i++) {
   	       $studentStatusManager->updateDayScholarStatus($REQUEST_DATA['chb1'][$i],$classId);
	    }

        //updating change in hostler status if any
        for($i=0;$i<$cnt2;$i++) {
	      $studentStatusManager->updateHostlerStatus($REQUEST_DATA['chb2'][$i],$classId);
	    }
    
        for($i=0;$i<$cnt3;$i++) {
           $studentStatusManager->updateOtherStatus($REQUEST_DATA['chb3'][$i],$classId);
        }
        echo SUCCESS;
   }
   else {
	 echo $errorMessage;
  }
  


?>

