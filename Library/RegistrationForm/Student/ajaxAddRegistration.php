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
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','COMMON');
define('ACCESS','add');
UtilityManager::ifStudentNotLoggedIn();  
UtilityManager::headerNoCache();


require_once(MODEL_PATH . "/RegistrationForm/ScStudentRegistration.inc.php");
$studentRegistration = StudentRegistration::getInstance();

global $REQUEST_DATA;





// call to functions for the retrival of values
$classId= $studentRegistration->getClassId($REQUEST_DATA['studentId']);  //ClassId from Database
$checkStudentId= $studentRegistration->checkStudentId($REQUEST_DATA['studentId']); //StudentId from Database
$mentorEmail=$studentRegistration->getMentorEmail($REQUEST_DATA['studentId'],$sessionHandler->getSessionVariable('ClassId'));
$enableClasses=$studentRegistration->getEnableClasses(); // get the registration enable classes from the config table
$enableClasses=explode(",",$enableClasses[0]['value']);  
$flag=0;


$studentId = $sessionHandler->getSessionVariable('StudentId');

if($studentId=='') {
$studentId='0';  
} 

    //This checks whether to allow registration or insert/update the record.
    for($i=0;$i<sizeof($enableClasses);$i++) {
        if ($enableClasses[$i]==$sessionHandler->getSessionVariable('ClassId')) {
            if($checkStudentId[0]['count(studentId)']>0) {  
                if ($REQUEST_DATA['currentClassId']!=$classId[0]['classId']) {
                    if(SystemDatabaseManager::getInstance()->startTransaction()) {    
                        $returnArray = $studentRegistration->updateStudentRegistration();
                        if($returnArray===false) {
                           echo FAILURE; 
                           die;
                        }
                        $returnArray = $studentRegistration->insertStudentAcademics($studentId);
                        if(SystemDatabaseManager::getInstance()->commitTransaction()) {  
                            if($returnArray===false) {
                               echo FAILURE; 
                               die;
                            }   
                            else {
                                $flag=1;        
                                $str=$REQUEST_DATA['universityRollNo']." Has Submitted His/Her Registration Form Successfully ";
                                $headers = 'From: info@chitkarauniversity.edu.in';
                                @mail($mentorEmail[0]['emailAddress'],"Registration Successful :".$REQUEST_DATA['universityRollNo'],$str);
								
								$regEmailId=$sessionHandler->getSessionVariable('STUDENT_REGISTRATION_EMAIL');
							if($regEmailId!=''){
								@mail($regEmailId,"Registration Successful :".$REQUEST_DATA['universityRollNo'],$str);
																	
								}
                                echo SUCCESS; 
								        
                            }
                         } 
                    }  // Commit Transaction   
                }
                else {
                     $flag=1;
                     echo "Registration Already Exists";
                }
            }
            else {
                if(SystemDatabaseManager::getInstance()->startTransaction()) {      
                    $returnArray  = $studentRegistration->insertStudentToRegistration();
                    if($returnArray===false) {
                       echo FAILURE; 
                       die;
                    }
                    $returnArray = $studentRegistration->insertStudentAcademics($session,$board,$marks,$maxMarks,$percentage,$educationStream,$previousClass,$studentId);
                    if(SystemDatabaseManager::getInstance()->commitTransaction()) {  
                        if($returnArray===false) {
                           echo FAILURE; 
                           die;
                        }   
                        else {
                            $flag=1;
                            $str=$REQUEST_DATA['universityRollNo']." Has Submitted His/Her Registration Form Successfully ";
                            $headers = 'From: info@chitkarauniversity.edu.in';
                            @mail($mentorEmail[0]['emailAddress'],"Registration Successful :".$REQUEST_DATA['universityRollNo'],$str);
							$regEmailId=$sessionHandler->getSessionVariable('STUDENT_REGISTRATION_EMAIL');
							if($regEmailId!=''){
								@mail($regEmailId,"Registration Successful :".$REQUEST_DATA['universityRollNo'],$str);
																	
								}
                            echo SUCCESS;
                        }
                    }
                } // Commit Transaction
            }
       }
   }
   if($flag==0){
     echo "Registration For Your Class Has Not Been Opened Yet";
  }

?>
  
