<?php
//-------------------------------------------------------
// This File is used for Update Student Pending Fee
// Author :Nishu Bindal
// Created on : 6-Feb-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
    
    require_once(MODEL_PATH . "/Fee/FeeHeadValuesManager.inc.php");
    $feeHeadValueManager = FeeHeadValuesManager::getInstance();
    
    require_once(MODEL_PATH . "/Fee/GenerateFeeManager.inc.php");
    $generateFeeManager = GenerateFeeManager::getInstance(); 

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

    require_once(MODEL_PATH . "/Fee/FeeHeadManager.inc.php");
    $feeHeadManager = FeeHeadManager::getInstance();
    
    require_once(MODEL_PATH . "/Fee/StudentFeeManager.inc.php");
    $studentFeeManager = StudentFeeManager::getInstance();
    
    $generateClassId= $_REQUEST['generateClassId'];
    // Fetch the all academic class
    $pendingClassArray = $feeHeadValueManager->fetchPendingFeeClases($generateClassId); 
	//print_r($pendingClassArray);die; 
    for($ff=0;$ff<count($pendingClassArray);$ff++) {
           $periodValue = $pendingClassArray[$ff]['periodValue']; 
           $className = $pendingClassArray[$ff]['className'] ;
           $feeClassId =   $pendingClassArray[$ff]['classId']; 
    
           // Fetch the all student Leet and non leet
           $isLeetCondition = '';
           if($periodValue<='2') {
             $isLeetCondition = " AND s.isLeet = 0 ";  
           }
      
           $studentPendingDataArray = $generateFeeManager->checkPendingStudentAcademicList($feeClassId,$isLeetCondition);   
           for($cc=0;$cc<count($studentPendingDataArray);$cc++) {
               $studentId = $studentPendingDataArray[$cc]['studentId'];
               
               // Fetch the student Fee         
               $feeAcdSearch='0';
               $feeCycleArray = $generateFeeManager->checkStudentInstrument($studentId,$feeClassId);
               if(count($feeCycleArray)> 0){
                 $feeAcdSearch='1';
               }
               
               if($feeAcdSearch=='0') {
                 $feeCycleCondition = " AND studentId = '$studentId' AND classId = '$feeClassId' "; 
                 $feeCycleArray = $generateFeeManager->checkStudentFeeDetail($feeCycleCondition);
                 if(count($feeCycleArray)> 0){
                   $feeAcdSearch='1';
                 }
               }
               
               if($feeAcdSearch=='0') {
                 $feeResultMessage = getGenerateFee($feeClassId,$studentId);
                 if($feeResultMessage!=SUCCESS) {
                   echo $feeResultMessage." ".$className;
                   die;  
                 }
               }
           }
    }
    echo "Pending fee generated";
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
            $studentDataArray = $generateFeeManager->getStudentDetails($classes,$condition1,$condition2);
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
    
?>
