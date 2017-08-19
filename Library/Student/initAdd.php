<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','Admit');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

global $sessionHandler;
$errorMessage ='';
$totalLength = $sessionHandler->getSessionVariable('INSTITUTE_REGISTRATION_NO_LENGTH');


    //  Photo Upload 
    $sessionHandler->setSessionVariable('OperationMode','');
    //Stores file upload info
    $sessionHandler->setSessionVariable('HiddenFile','');
    //Stores the studentId
    $sessionHandler->setSessionVariable('studentIdToFileUpload','');
    //RegNo
    $sessionHandler->setSessionVariable('studentRegNo','');  
    //ErrorMsg
    $sessionHandler->setSessionVariable('ErrorMsg','');
    
    
    // if (trim($errorMessage) == '') {
		require_once(MODEL_PATH . "/StudentManager.inc.php");
		$studentManager = StudentManager::getInstance();
	    //print_r($REQUEST_DATA);
	    //die('line'.__LINE__);
    
        function clearSpecialChar($text) {
           if($text!="") {
             $text=strtolower($text);
             $code_entities_match = array(' ');
             $code_entities_replace = array('');
             $text = str_replace($code_entities_match, $code_entities_replace, $text);
           }
           return $text;
        } 

		$classId		= urldecode($REQUEST_DATA['degree']);
		$rollNo			= urldecode($REQUEST_DATA['rollNo']);
		$session		= urldecode($REQUEST_DATA['session']);
		$institute		= urldecode($REQUEST_DATA['institute']);
		$board			= urldecode($REQUEST_DATA['board']);
		$marks			= urldecode($REQUEST_DATA['marks']);
		$maxMarks		= urldecode($REQUEST_DATA['maxMarks']);
        $educationStream        = urldecode($REQUEST_DATA['educationStream']);
		$percentage		= urldecode($REQUEST_DATA['percentage']);
		$previousClass	        = urldecode($REQUEST_DATA['previousClass']);
		$regularAilment	        = urldecode($REQUEST_DATA['regularAilment']);
		$familyAilment	        = urldecode($REQUEST_DATA['familyAilment']);
		$migratedStudyPeriod	= urldecode($REQUEST_DATA['migratedStudyPeriod']);
		//$regularAilmentNo	= $REQUEST_DATA['regularAilmentNo'];

		$cnt = count($familyAilment);
		if($cnt > 0 AND is_array($familyAilment)) { 
		 $familyAilmentList = implode(",",$familyAilment);
		}

		if($regularAilment == 1) {
			//$regularAilment = $REQUEST_DATA['regularAilmentYes'];
			$natureAilment = add_slashes(urldecode($REQUEST_DATA['natureAilment']));
			$familyAilment = $familyAilmentList;
			$otherAilment = add_slashes(urldecode($REQUEST_DATA['otherAilment']));
		
		}
		else {
			$natureAilment = '';
			$familyAilment = '';
			$otherAilment = '';
		}
		
        
         /* START: to check rollno*/
        if(add_slashes(trim(clearSpecialChar(urldecode($REQUEST_DATA[studentClassRole]))))!='') { 
            $duplicateCondition = " rollNo LIKE '".add_slashes(trim(clearSpecialChar(urldecode($REQUEST_DATA[studentClassRole]))))."'"; 
            $userRollArr = $studentManager->checkStudentAll($duplicateCondition);    
            if($userRollArr[0]['cnt'] > 0) {
               echo "Student roll number already exists";
               die;
            }
            /* END: to check rollno*/

            
            /* START: to check rollno in quarantine table*/
            $duplicateCondition = " rollNo LIKE '".add_slashes(trim(clearSpecialChar(urldecode($REQUEST_DATA[studentClassRole]))))."'"; 
            $userRollArr = $studentManager->checkStudentQuarantineAll($duplicateCondition);    
            if($userRollArr[0]['cnt'] > 0) {
                echo "Student roll number already exists in deleted Records";
                die;
            }
            /* END: to check rollno in quarantine table*/
        }
        
        
        if(add_slashes(trim(clearSpecialChar(urldecode($REQUEST_DATA[collegeRegNo]))))!='') { 
        
            /* START: to check reg no*/
            $duplicateCondition = " regNo LIKE '".add_slashes(trim(clearSpecialChar(urldecode($REQUEST_DATA[collegeRegNo]))))."'"; 
            $userRollArr = $studentManager->checkStudentAll($duplicateCondition);    
            if($userRollArr[0]['cnt'] > 0) {
                echo "College reg no. already exists";
                die;
            }
            /* END: to check univ no*/

            /* START: to check reg no quarantine table*/
            $duplicateCondition = " regNo LIKE '".add_slashes(trim(clearSpecialChar(urldecode($REQUEST_DATA[collegeRegNo]))))."'"; 
            $userRollArr = $studentManager->checkStudentQuarantineAll($duplicateCondition);    
            if($userRollArr[0]['cnt'] > 0) {
                echo "College reg no. already exists in deleted records";
                die;
            }
            /* END: to check univ no quarantine table*/
        }    
        
        
        if(add_slashes(trim(clearSpecialChar(urldecode($REQUEST_DATA[studentUniversityRole]))))!='') {           
            /* START: to check univ no*/
            $duplicateCondition = " universityRollNo LIKE '".add_slashes(trim(clearSpecialChar(urldecode($REQUEST_DATA[studentUniversityRole]))))."'"; 
            $userRollArr = $studentManager->checkStudentAll($duplicateCondition);    
            if($userRollArr[0]['cnt'] > 0) {
                echo "University no. already exists";
                die;
            }
            /* END: to check univ no*/
            
            
            /* START: to check univ no quarantine table*/
            $duplicateCondition = " universityRollNo LIKE '".add_slashes(trim(clearSpecialChar(urldecode($REQUEST_DATA[studentUniversityRole]))))."'"; 
            $userRollArr = $studentManager->checkStudentQuarantineAll($duplicateCondition);    
            if($userRollArr[0]['cnt'] > 0) {
                echo "University no. already exists in deleted records";     
                die;
            }
            /* END: to check univ no quarantine table*/
        }
        
        
        if(add_slashes(trim(clearSpecialChar(urldecode($REQUEST_DATA[feeReceiptNo]))))!='') {           
            /* START: to check fee receipt no*/
            $duplicateCondition = " feeReceiptNo LIKE '".add_slashes(trim(clearSpecialChar(urldecode($REQUEST_DATA[feeReceiptNo]))))."'"; 
            $userRollArr = $studentManager->checkStudentAll($duplicateCondition);    
            if($userRollArr[0]['cnt'] > 0) {
                echo "Fee receipt no. already exists";
                die;
            }
            /* END: to check fee receipt no*/

            
            /* START: to check fee receipt no quarantine table*/
            $duplicateCondition = " feeReceiptNo LIKE '".add_slashes(trim(clearSpecialChar(urldecode($REQUEST_DATA[feeReceiptNo]))))."'"; 
            $userRollArr = $studentManager->checkStudentQuarantineAll($duplicateCondition);    
            if($userRollArr[0]['cnt'] > 0) {
                echo "Fee receipt no. already exists in deleted records";
                die;
            }
        }
        
        /* START: to check email*/
        if(add_slashes(trim(clearSpecialChar(urldecode($REQUEST_DATA[studentEmail]))))!='') { 
            $duplicateCondition = " studentEmail LIKE '".add_slashes(trim(clearSpecialChar(urldecode($REQUEST_DATA[studentEmail]))))."'"; 
            $userRollArr = $studentManager->checkStudentAll($duplicateCondition);    
            if($userRollArr[0]['cnt'] > 0) {
               echo "Student email already exists";
               die;
            }
            /* END: to check rollno*/

            
            /* START: to check rollno in quarantine table*/
            $duplicateCondition = " studentEmail LIKE '".add_slashes(trim(clearSpecialChar(urldecode($REQUEST_DATA[studentEmail]))))."'"; 
            $userRollArr = $studentManager->checkStudentQuarantineAll($duplicateCondition);    
            if($userRollArr[0]['cnt'] > 0) {
                echo "Student email already exists in deleted Records";
                die;
            }
            /* END: to check rollno in quarantine table*/
        }



		//*********************************************************    
		//******************************STRAT TRANSCATION*********************************
		//*****************************************************************************************
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			
			//ADD SaLLCLASS IN STUDENT TABLE
			 $studentClassStatus = $studentManager->updateStudentAllClasses($classId);
			 if($studentClassStatus===false){
               $sessionHandler->setSessionVariable('ErrorMsg',FAILURE);  
               echo FAILURE;
            } 
			 $allClass ='';
			 for($i=0;$i<count($studentClassStatus);$i++){			 
			 if($allClass !=''){
			 	$allClass .='~';				
			 }	
				$allClass .=$studentClassStatus[$i]['classId'];	
			 }
            $sAllClass ="~".$allClass."~";	
				
		  //started insert and update operations
		  
			$returnStatus   = $studentManager->insertStudentaToClass($classId,$sAllClass);
			
			if($returnStatus) {
  				$returnStatus = SystemDatabaseManager::getInstance()->lastInsertId();
             
              
                if($REQUEST_DATA['admitOptionalField']==1) {
                   // To Optional Field Added 
                   $optionalFieldStatus = $studentManager->insertOptionalFieldStudent($returnStatus);
                   if($optionalFieldStatus===false){
                      $sessionHandler->setSessionVariable('ErrorMsg',FAILURE);  
                      echo FAILURE;
                      die;
                   }
                }
                
                
				$autoGenArr  = $studentManager->getConfigLabelValue(" AND param='AUTO_GENERATED_REG_NO'");
				if($autoGenArr[0]['value']){
				
					$registrationArr = $studentManager->getRegistrationNumber(" WHERE studentId = $returnStatus");

					if($registrationArr[0]['regNo']){
					
						$genratedRegNo = '';
						$prefixArr        = $studentManager->getConfigLabelValue(" AND param='INSTITUTE_REGISTRATION_NO_PREFIX'"); 
						$prefix		      = $prefixArr[0]['value'];

						$prefixLengthArr  = $studentManager->getConfigLabelValue(" AND param='INSTITUTE_REGISTRATION_NO_PREFIX'"); 
						$prefixLength	  = strlen($prefixLengthArr[0]['value']);

						$totalLengthArr  = $studentManager->getConfigLabelValue(" AND param='INSTITUTE_REGISTRATION_NO_LENGTH'"); 
						$totalLength	  = $totalLengthArr[0]['value'];
						/*$prefix = $sessionHandler->getSessionVariable('INSTITUTE_REGISTRATION_NO_PREFIX');
						$prefixLength = strlen($sessionHandler->getSessionVariable('INSTITUTE_REGISTRATION_NO_PREFIX'));
						$totalLength = $sessionHandler->getSessionVariable('INSTITUTE_REGISTRATION_NO_LENGTH');
						*/
						$lastNo = abs(substr($registrationArr[0]['regNo'], $prefixLength)); 
						
						$newRegistrationNumber = $lastNo+1; 
						$newRegistrationNumberLength = strlen($newRegistrationNumber);
						$regLength = $totalLength - $prefixLength-$newRegistrationNumberLength;
						
						//$newRegistrationNumberLength = strlen($newRegistrationNumber); 
						for($i=0;$i<$regLength;$i++){
						
							$count.= "0";
						}
						$genratedRegNo = $prefix.$count.$newRegistrationNumber;
					}
				}
				$academicStatus = $studentManager->insertStudentAcademics($rollNo,$session,$institute,$board,$marks,$maxMarks,$percentage,$educationStream,$previousClass,$returnStatus);

				if($academicStatus===false){
				    $sessionHandler->setSessionVariable('ErrorMsg',FAILURE);  
					echo FAILURE;
				}
				
                $ailmentStatus = $studentManager->insertStudentAilment($returnStatus,$regularAilment,$natureAilment,$familyAilment,$otherAilment);
                if($ailmentStatus===false){
                   $sessionHandler->setSessionVariable('ErrorMsg',FAILURE);  
                   echo FAILURE;
                }
				
				else {
					if(SystemDatabaseManager::getInstance()->commitTransaction()) {
	                    //  Photo Upload 
                        $sessionHandler->setSessionVariable('OperationMode',1);
                        //Stores file upload info
                        $sessionHandler->setSessionVariable('HiddenFile',urldecode($REQUEST_DATA['hiddenFile']));
                        //Stores the studentId
                        $sessionHandler->setSessionVariable('studentIdToFileUpload',$returnStatus);
                        //RegNo
                        $sessionHandler->setSessionVariable('studentRegNo',$genratedRegNo);  
                        
                        $sessionHandler->setSessionVariable('ErrorMsg',SUCCESS);
                        echo SUCCESS."~".$genratedRegNo;                            
                        
						die;
					 }
					 else {
                        $sessionHandler->setSessionVariable('ErrorMsg',FAILURE);
						echo FAILURE;
					 }
					 
                     //  Photo Upload 
                     $sessionHandler->setSessionVariable('OperationMode',1);
                     //Stores file upload info
                     $sessionHandler->setSessionVariable('HiddenFile',urldecode($REQUEST_DATA['hiddenFile']));
                     //Stores the studentId
                     $sessionHandler->setSessionVariable('studentIdToFileUpload',$returnStatus);  
                     //regNo
                     $sessionHandler->setSessionVariable('studentRegNo',$genratedRegNo);  
                     
                     $sessionHandler->setSessionVariable('ErrorMsg',SUCCESS);
                     
                     echo SUCCESS."~".$genratedRegNo;
				}
				//*****************************COMMIT TRANSACTION************************* 
			}
			else {
				$sessionHandler->setSessionVariable('ErrorMsg',FAILURE);  
				echo FAILURE;
			}
		}
		else{
              $sessionHandler->setSessionVariable('ErrorMsg',FAILURE); 
			  echo FAILURE;
			  die;
		}
?>
