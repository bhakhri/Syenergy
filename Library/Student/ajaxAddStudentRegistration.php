<?php
//-------------------------------------------------------
// Author : Ankur Aggarwal
// Created on : 25-July-2011
// Copyright 2011-2012: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();


require_once(MODEL_PATH . "/StudentRegistration.inc.php");
$studentRegistration = StudentRegistration::getInstance();

// call to functions for the retrival of values

$classId= $studentRegistration->getClassId($REQUEST_DATA['studentId']);  //ClassId from Database
$checkStudentId= $studentRegistration->checkStudentId($REQUEST_DATA['studentId']); //StudentId from Database
$enableClasses=$studentRegistration->getEnableClasses(); // get the registration enable classes from the config table
$enableClasses=explode(",",$enableClasses[0]['value']);  
$flag=0;

           for($i=0;$i<sizeof($enableClasses);$i++) {
             	if ($enableClasses[$i]==$sessionHandler->getSessionVariable('ClassId')) {
	 		if($checkStudentId[0]['count(studentId)']>0) {
    		  		if ($REQUEST_DATA['currentClassId']!=$classId[0]['classId']) {
                                        $flag=1;
					$studentRegistration->updateStudentRegistration();
            	       			echo SUCCESS;
      	 			 }
     				 else {  
					   $flag=1;
      	 				   echo "Registration Already Exists";
    	 			 }
     			 }
      			else {  
				$flag=1;
		 		$studentRegistration->insertStudentToRegistration();
	        		echo SUCCESS;
			}
              }
         }

	 if($flag==0){
  		 echo "Registration For Your Class Has Not Been Opened Yet";
 	  }



?>
  
