<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
// Author : Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','StudentAdhocConcessionNew');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

    require_once(MODEL_PATH . "/Fee/GenerateFeeManager.inc.php");
    $generateFeeManager = GenerateFeeManager::getInstance(); 

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

    require_once(MODEL_PATH . "/Fee/FeeHeadManager.inc.php");
    $feeHeadManager = FeeHeadManager::getInstance();
    
    require_once(MODEL_PATH . "/Fee/CollectFeesManager.inc.php");   
    $CollectFeesManager = CollectFeesManager::getInstance(); 
    
    require_once(MODEL_PATH . "/Fee/StudentFeeManager.inc.php");
    $studentFeeManager = StudentFeeManager::getInstance();
    
    global $sessionHandler;
    

    require_once(MODEL_PATH . "/Fee/StudentAdhocConcessionManager.inc.php");
    $studentAdhocConcessionManager = StudentAdhocConcessionManager::getInstance();

    $classId = trim(add_slashes($REQUEST_DATA['classId']));
    $rollNo = trim(add_slashes($REQUEST_DATA['rollNo']));
    
    if(trim($REQUEST_DATA['classId'])==''){
       echo SELECT_FEE_CLASS;
       die;
    }
    
    if(trim($REQUEST_DATA['rollNo'])==''){
       echo ENTER_NAME_ROLLNO;
       die;
    }
  
    $condition = '';
    if($rollNo=='') {
      echo STUDENT_NOT_EXIST;  
      die; 
    }
    
    if($classId=='') {
      echo STUDENT_NOT_EXIST;  
      die; 
    }
      
    // Fetch Student Detail
    $studentId='';
    $condition = " (stu.rollNo LIKE '$rollNo' OR stu.regNo LIKE '$rollNo'  OR stu.universityRollNo LIKE '$rollNo') ";   
    $studentFeesArray = $studentAdhocConcessionManager->getCheckStudentId($condition,$classId);
    if(count($studentFeesArray)==1) {
      $studentId = $studentFeesArray[0]['studentId'];
    }
    
    if($studentId=='') {
      echo STUDENT_NOT_EXIST;  
      die; 
        }
        
        $adhocCondition =" AND acm.feeClassId = '$classId' AND acm.studentId = '$studentId' "; 
        $adhocArray=$studentAdhocConcessionManager->getStudentAdhocConcession($adhocCondition);
        
        
        $feeResultMessage = getGenerateFee($classId,$studentId);
        if($feeResultMessage!=SUCCESS) {
          echo FEE_HEAD_NOT_DEFINE;  
          die;  
        }
        
        $feeCondition =" classId = '$classId' AND studentId = '$studentId' "; 
        $feeArray=$studentAdhocConcessionManager->getCheckFeeHead($feeCondition);
        if(count($feeArray)==0) {
          echo FEE_HEAD_NOT_DEFINE;  
          die;   
        }
        
      
       	$adhocAmount =0;
        $adhocAmount = $adhocArray[0]['adhocAmount'];
        $comments = $adhocArray[0]['description'];
      	$totalFee = 0;
        for($i=0; $i<count($feeArray); $i++) {
          $feeHeadAmt = $feeArray[$i]['feeHeadAmt'];
          $totalFee +=$feeArray[$i]['feeHeadAmt']; 
          $valueArray = array_merge(array('srNo'=>($i+1)
                                          ), $feeArray[$i]); 
          if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
          }
          else {
            $json_val .= ','.json_encode($valueArray);           
          }  
        }
       
     	$json_student = json_encode($studentFeesArray[0]);        
       	$totalFee = number_format($totalFee, 2, '.', '');
       	$adhocAmount = number_format($adhocAmount, 2, '.', '');
        echo '{"comments":"'.$comments.'","concessionAmount":"'.$adhocAmount.'","totalFee":"'.$totalFee.'",
               "studentinfo" : ['.$json_student.'],"info" : ['.$json_val.'], "currentClassId" :"'.$currentClassId .'"}';  
        
    die;  

    function getGenerateFee($feeClassId,$studentId) {
         
         global $generateFeeManager;
         global $commonQueryManager;
         global $feeHeadManager;
         global $sessionHandler;
         
         if($feeClassId=='') {
           $feeClassId='0';
         }
         
         if($studentId=='') {
           $studentId='0';
         }
         
         $ttFeeClassId=  $feeClassId;
         
         $userId = $sessionHandler->getSessionVariable('UserId');
         $errorMessage =''; 
         
         
         $feeCycleCondition = " classId = '$feeClassId' ";
         $feeCycleArray = $generateFeeManager->checkStudentFeeCycle($feeCycleCondition);
         if(count($feeCycleArray) > 0){
            $feeCycleId = $feeCycleArray[0]['feeCycleId'];
         }
    
         // to fetch Current class of student
         $classArray = $generateFeeManager->getClass($feeClassId);
         if(count($classArray) == 0){
           return  "Class Not Found";
        }
        
        // Fetch the all Classes 
        $classes = '';
        foreach($classArray as $key => $value){
          if($classes == ''){
            $classes = $value['classId'];
          }
          else{
            $classes .= ",".$value['classId'];
          }
        } 
        $feeStudyPeriodId = $classArray[0]['feeStudyPeriodId'];
    
    
        if(SystemDatabaseManager::getInstance()->startTransaction()){
            
                // To Delete old fee heads
                $oldFeeHeadDelete = $generateFeeManager->checkStudentFeeHeadDelete($studentId,$ttFeeClassId);
                if($oldFeeHeadDelete===false) {
                   echo FAILURE;
                   die;
                }
            
     // Fetch Migration Fee  Start
                $migrationArray = $generateFeeManager->getCheckStudentMigration($studentId);
                if(count($migrationArray) > 0 && is_array($migrationArray)) {
                  $ttIsMigrationId=$migrationArray[0]['migrationStudyPeriod'];
                }
                if($ttIsMigrationId=='') {
                  $ttIsMigrationId='0';  
                }
                
                $ttPeriodValue='-1';  
                if($ttIsMigrationId>0) {
                   $migrationPeriodArray = $generateFeeManager->getMigrationStudyPeriod($feeClassId);
                   $ttPeriodValue = $migrationPeriodArray[0]['periodValue'];
                   if($ttPeriodValue=='') {
                     $ttPeriodValue='-1';  
                   }
                }
                if($ttIsMigrationId==$ttPeriodValue) {
                  $ttIsMigrationId=1; 
                }
                else {
                  $ttIsMigrationId=0;   
                }
            // Migration Fee END  

            // to Get Student Details
            $condition1 = " AND studentId = '$studentId' ";
            $condition2 = " AND stu.studentId = '$studentId' ";
            $studentDataArray = $generateFeeManager->getStudentDetailsNew($classes,$condition1,$condition2);
            if(count($studentDataArray) == 0 || !is_array($studentDataArray)) {
               return "Students Not Found";  
            }

            $j=1;
            foreach($studentDataArray as $key =>$studentArr) {
                $currentClass = $studentArr['classId'];  
                $instituteId =  $studentArr['instituteId'];  
                $instituteAbbr =  $studentArr['instituteAbbr'];  
                
                $concession = '';
                $hostelFees='';
                $transportFees='';
                $busRouteId   = '';
                $busStopId = '';
                $feeReceiptId = '';
                $totalAcademicFee =0;
                $hostelSecurity = 0;
             // to get Student Concession                   
                $adhocCondition =" acm.feeClassId = '".$feeClassId."' AND acm.studentId = '".$studentArr['studentId']."'"; 
                $adhocArray=$generateFeeManager->getStudentAdhocConcessionNew($adhocCondition);
                $concession = $adhocArray[0]['adhocAmount']; // concession Amount
                $isMigration ='-1';
                if($studentArr['isMigration'] == 1  && $ttIsMigrationId == 1){
                  $isMigration = 3;
                }
                 // to get Student Fee Heads
                 $foundArray = $generateFeeManager->getStudentFeeHeadDetail($feeClassId,$studentArr['quotaId'],$studentArr['isLeet'],$studentArr['studentId'],$isMigration);
                 if(count($foundArray) == 0){
                    return FEE_HEAD_NOT_DEFINE;
                 }
                 
                 $feeArray = array();
                 $applicableHeadId = array();
                 $index = array();
                
                 // code to find only applicable Head Value 
                 foreach($foundArray as $key =>$subArray) {
                    if(!in_array($subArray['feeHeadId'],$applicableHeadId)){
                           $flag1 = true; // used for filtering purpose
                       }  
                    $flag= true;
                    foreach($foundArray as $key1 =>$subArray1){
                           if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $subArray['isLeet'] == 3)&& (($subArray['quotaId'] == $studentArr['quotaId']) && $subArray['isLeet'] == $isMigration)){
                               $flag = true;
                               foreach($applicableHeadId as $key2 => $value){
                                   if($value == $subArray['feeHeadId']){
                                       $applicableHeadId[$key2] = $subArray['feeHeadId'];
                                       $index[$key2] = $key;
                                       $flag= false;
                                   }    
                               }
                               if($flag){
                                   $applicableHeadId[] = $subArray['feeHeadId'];
                                   $index[] = $key;
                               }
                               break;
                           }
                           else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $subArray['isLeet'] == 1) && (($subArray['quotaId'] == $studentArr['quotaId']) && ($subArray['isLeet'] == $studentArr['isLeet']))){ 
                               $flag = true;
                               foreach($applicableHeadId as $key2 => $value){
                                   if($value == $subArray['feeHeadId']){
                                       $applicableHeadId[$key2] = $subArray['feeHeadId'];
                                       $index[$key2] = $key;
                                       $flag= false;
                                   }    
                               }
                               if($flag){
                                   $applicableHeadId[] = $subArray['feeHeadId'];
                                   $index[] = $key;
                               }
                               break;
                           }
                           else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $flag1 == true) && (($subArray['quotaId'] == 0) && $subArray['isLeet'] == $isMigration)){ 
                               $flag = true;
                               foreach($applicableHeadId as $key2 => $value){
                                   if($value == $subArray['feeHeadId']){
                                       $applicableHeadId[$key2] = $subArray['feeHeadId'];
                                       $index[$key2] = $key;
                                       $flag= false;
                                   }    
                               }
                               if($flag){
                                   $applicableHeadId[] = $subArray['feeHeadId'];
                                   $index[] = $key;
                               }
                               break;
                           }
                           else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && !in_array($subArray['feeHeadId'],$applicableHeadId)) && (($subArray['quotaId'] == $studentArr['quotaId']) || (($subArray['isLeet'] == $studentArr['isLeet']) || $subArray['isLeet'] == $isMigration))){ 
                               $applicableHeadId[] = $subArray['feeHeadId'];
                               $index[] = $key;
                               break;
                           
                           }
                    }              
                  }
          
                  $applicableHeadId = array_unique($applicableHeadId); 

                  // to put other heads 
                  foreach($foundArray as $key =>$subArray){
                    if(!in_array($subArray['feeHeadId'],$applicableHeadId)){
                        $feeArray[$key] = $foundArray[$key];
                    }
                  }
                  // to insert aplicable head at there place
                  $index = array_unique($index);
                  foreach($index as $key =>$value){
                    $feeArray[$value] = $foundArray[$value];
                  }
                  // this is done to mantain the order of fee it stores the key
                  $indexArr = array();
                  foreach($feeArray as $key =>$value){
                    $indexArr[] = $key;
                  }
                  sort($indexArr); // to sort the index
            
                   $studentId = $studentArr['studentId'];
                $feeReceiptId = '';
                $feeReceiptArray= $generateFeeManager->getFeeMasterId($studentId,$feeClassId);
                if(count($feeReceiptArray) > 0 ) {
                  $feeReceiptId = $feeReceiptArray[0]['feeReceiptId'];
                }
                $status = $generateFeeManager->insertIntoFeeMaster($studentId,$currentClass,$feeClassId,$feeCycleId,$concession);
                if($status === FALSE){
                    return FALIURE;
                }
                if($feeReceiptId=='') {
                  $feeReceiptId=SystemDatabaseManager::getInstance()->lastInsertId();
                }
                
                $cnt = count($indexArr);
                $instrumentValues = '';
                for($i=0;$i<$cnt; $i++){
                    //feeReceiptInstrumentId,feeReceiptId,studentId,classId,feeHeadId,feeHeadName,amount,feeStatus
                    if($feeArray[$indexArr[$i]]['feeHeadAmt'] > 0) {
                        if($instrumentValues != ''){
                            $instrumentValues .=", ";
                        }
                        $instrumentValues .="('','$feeReceiptId','$studentId','$feeClassId','".$feeArray[$indexArr[$i]]['feeHeadId']."','".ucwords($feeArray[$indexArr[$i]]['headName'])."','".$feeArray[$indexArr[$i]]['feeHeadAmt']."',0)";
                        $totalAcademicFee += floatval($feeArray[$indexArr[$i]]['feeHeadAmt']);
                        $totalAcademicFee = " ".$totalAcademicFee;
                    }
                }
        
                $status1 = $generateFeeManager->insertIntoReceiptInstrument($instrumentValues);
                if($status1 === FALSE){
                    return FALIURE;
                }
                $j++;
            }
            if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                $msg = SUCCESS; 
            }
            else {
               $msg = FAILURE;
            }
        }
        else {
          $msg = FAILURE;
        }
        return $msg; 
    }    
    
/* $condition = " AND (stu.rollNo LIKE '$rollNo' OR stu.regNo LIKE '$rollNo'  OR stu.universityRollNo LIKE '$rollNo') ";
        $studentFeesArray = $studentAdhocConcessionManager->getStudentDetailClass($condition,$classId);  
        if(is_array($studentFeesArray) && count($studentFeesArray)>0 ) {
           if($studentFeesArray[0]['feeClassId']==-1) {
              echo FEE_HEAD_NOT_DEFINE;
              die;
           }
        }  
        else {
           echo STUDENT_NOT_EXIST;  
           die; 
        }
      
        $studentId = $studentFeesArray[0]['studentId'];  
        $quotaId = $studentFeesArray[0]['quotaId'];  
        $isLeet = $studentFeesArray[0]['isLeet']; 
        $currentClassId = $studentFeesArray[0]['classId']; // currentClassId  
        
        $isMigration = '';
        if($studentFeesArray[0]['isMigration'] == 1){
            $isMigration = 3;
        }
     
        $migrationClassId = $studentFeesArray[0]['migrationClassId'];  
         
        $universityId = $studentFeesArray[0]['universityId'];
        $batchId = $studentFeesArray[0]['batchId'];
        $degreeId = $studentFeesArray[0]['degreeId'];
        $branchId = $studentFeesArray[0]['branchId'];
        $studyPeriodId = $studentFeesArray[0]['studyPeriodId'];
        $feeStudyPeriodId = $studentFeesArray[0]['feeStudyPeriodId'];
        
        $adhocCondition =" AND acm.feeClassId = $classId AND acm.studentId = $studentId "; 
        $adhocArray=$studentAdhocConcessionManager->getStudentAdhocConcession($adhocCondition);
                           
        //================================FEE HEAD DETAILS (Start)=======================================
        $foundArray = $studentAdhocConcessionManager->getStudentFeeHeadDetail($classId,$quotaId,$isLeet,$studentId,$isMigration);
        if(count($foundArray)==0){
          echo FEE_HEAD_NOT_DEFINE;
          die;
        }
    
    
         $feeArray = array();
         $applicableHeadId = array();
         $index = array();
          // code to find only applicable Head Value
           foreach($foundArray as $key =>$subArray){
                   if(!in_array($subArray['feeHeadId'],$applicableHeadId)){
                       $flag1 = true; // used for filtering purpose
                   } 
                   $flag= true;
                   foreach($foundArray as $key1 =>$subArray1){
                       if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $subArray['isLeet'] == 3) && (($subArray['quotaId'] == $quotaId) && ($subArray['isLeet'] == $isMigration))){ 
                           $flag = true;
                           $flag1= false;
                           foreach($applicableHeadId as $key2 => $value){
                               if($value == $subArray['feeHeadId']){
                                   $applicableHeadId[$key2] = $subArray['feeHeadId'];
                                   $index[$key2] = $key;
                                   $flag= false;
                               }    
                           }
                           if($flag){
                               $applicableHeadId[] = $subArray['feeHeadId'];
                               $index[] = $key;
                           }
                           break;
                       }
                       else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $subArray['isLeet'] == 1) && (($subArray['quotaId'] == $quotaId) && ($subArray['isLeet'] == $isLeet))){ 
                           $flag = true;
                           foreach($applicableHeadId as $key2 => $value){
                               if($value == $subArray['feeHeadId']){
                                   $applicableHeadId[$key2] = $subArray['feeHeadId'];
                                   $index[$key2] = $key;
                                   $flag= false;
                               }    
                           }
                           if($flag){
                               $applicableHeadId[] = $subArray['feeHeadId'];
                               $index[] = $key;
                           }
                           break;
                       }
                       else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $flag1 == true) && (($subArray['quotaId'] == 0) && $subArray['isLeet'] == $isMigration)){ 
                           $flag = true;
                           foreach($applicableHeadId as $key2 => $value){
                               if($value == $subArray['feeHeadId']){
                                   $applicableHeadId[$key2] = $subArray['feeHeadId'];
                                   $index[$key2] = $key;
                                   $flag= false;
                               }    
                           }
                           if($flag){
                               $applicableHeadId[] = $subArray['feeHeadId'];
                               $index[] = $key;
                           }
                           break;
                       }
                       else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && !in_array($subArray['feeHeadId'],$applicableHeadId)) && (($subArray['quotaId'] == $quotaId) || ($subArray['isLeet'] == $isLeet || $subArray['isLeet'] == $isMigration))){ 
                           $applicableHeadId[] = $subArray['feeHeadId'];
                           $index[] = $key;
                           break;
                       }
                   }              
          }
         
          $applicableHeadId = array_unique($applicableHeadId); 
        // to put other heads 
          foreach($foundArray as $key =>$subArray){
                  if(!in_array($subArray['feeHeadId'],$applicableHeadId)){
                      $feeArray[$key] = $foundArray[$key];
                  }
          }
        // to insert aplicable head at there place
          $index = array_unique($index);
          foreach($index as $key =>$value){
              $feeArray[$value] = $foundArray[$value];
          }
         
      // this is done to mantain the order of fee it stores the key
         $indexArr = array();
         foreach($feeArray as $key =>$value){
             $indexArr[] = $key;
         }
        sort($indexArr); // to sort the index 
     */   

/*
for($i=0; $i<count($indexArr); $i++){
   $feeHeadAmt = $feeArray[$indexArr[$i]]['feeHeadAmt'];
  
   $totalFee +=$feeArray[$indexArr[$i]]['feeHeadAmt'];
   $valueArray = array_merge(array('srNo' => ($i+1)
                                  ), $feeArray[$indexArr[$i]]); 
    if(trim($json_val)=='') {
     $json_val = json_encode($valueArray);
   }
   else {
     $json_val .= ','.json_encode($valueArray);           
   }  
} 
*/  
      
?>    

