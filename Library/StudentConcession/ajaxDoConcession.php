<?php
//-------------------------------------------------------
// THIS FILE IS USED TO DO STUDENT CONCESSION ENTRIES
// Author : Dipanjan Bhattacharjee
// Created on : (07.05.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentConcession');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/StudentConcessionManager.inc.php");
require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentConcessionManager = StudentConcessionManager::getInstance();
$studentManager = StudentManager::getInstance();
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

$feeCycleId=trim($REQUEST_DATA['feeCycle']);
$feeHeadId=trim($REQUEST_DATA['feeHead']);
$studentString=trim($REQUEST_DATA['studentString']);
$userId=$sessionHandler->getSessionVariable('UserId');

if($feeCycleId=='' or $studentString==''){
    echo 'Required Parameters Missing';
    die;
}

//first check total fees and percentage wise restrictions
$studentInfoArray=explode('!@!^!@!',$studentString);
$studentInfoCount=count($studentInfoArray);
if($studentInfoCount==0){
    echo NO_DATA_FOUND;
    die;
}

//this function is used to fetch total fees of a student
function getTotalFees($studentId,$classId,$feeCycleId,$feeHeadId){
    global $studentManager;
    //now fetch total fees of this student
    $studentFeesArray = $studentManager->getStudentDetailClass(' AND stu.studentId='.$studentId.' AND cls.classId='.$classId);
    $totalFees = 0;
	$hostelFacility = 0;
    $transportFacility = 0;
    if(is_array($studentFeesArray) and count($studentFeesArray)>0){
       
	   $studentHeadFeesArray = $studentManager->getStudentFeeHeadClass($feeCycleId,$studentFeesArray[0]['studyPeriodId'],$studentFeesArray[0]['instituteId'],$studentFeesArray[0]['universityId'],$studentFeesArray[0]['batchId'],$studentFeesArray[0]['degreeId'],$studentFeesArray[0]['branchId'],$studentFeesArray[0]['quotaId'],$studentFeesArray[0]['isLeet'],$feeHeadId);
       $feeCnt = count($studentHeadFeesArray);
        for($lm=0;$lm<$feeCnt;$lm++) {
             $totalFees += $studentHeadFeesArray[$lm]['feeHeadAmount'];
        }

		$hostelFacility = $studentFeesArray[0]['hostelFacility'];
	    $transportFacility = $studentFeesArray[0]['transportFacility'];
		
		$busCondition = "";
		if($studentFeesArray[0]['busStopId']!=0){
		
			$busCondition = " and bus.busStopId = ".$studentFeesArray[0]['busStopId'];
		} 
		$studentBusFeesArray = $studentManager->getStudentBusDetailClass($busCondition,$transportFacility);

		$hostelCondition = "";
		if($studentFeesArray[0]['hostelRoomId']!=0){
		
			$hostelCondition = "  and hosroom.hostelRoomId = ".$studentFeesArray[0]['hostelRoomId'];
		} 
		$studentHostelFeesArray = $studentManager->getStudentHostelDetailClass($hostelCondition,$hostelFacility);

		$totalFees+=$studentBusFeesArray[0]['feeHeadAmount'];
		$totalFees+=$studentHostelFeesArray[0]['feeHeadAmount'];

     }
    return $totalFees;
}

$deleteString='';
$insertString='';
$dated=date('Y-m-d h:i:s');

 if(SystemDatabaseManager::getInstance()->startTransaction()) {

  for($i=0;$i<$studentInfoCount;$i++){

    $studentTempArray=explode('!_$^!@^@',$studentInfoArray[$i]);
	//echo "<pre>";
	//print_r($studentTempArray);
	
    $reason=trim(add_slashes($studentTempArray[1]));
    $studentArray=explode('_',$studentTempArray[0]);
    $studentId=trim($studentArray[0]);
    $classId=trim($studentArray[1]);
    $concessionType=trim($studentArray[2]);
    $concessionValue=trim($studentArray[3]);
	$discValue=trim($studentArray[4]);
	//die();
    if($studentId==''){
        echo 'Student Information Missing';
        die;
    }
    if($classId==''){
        echo 'Class Information Missing';
        die;
    }
    if($concessionType==''){
        echo 'Concession type Information Missing';
        die;
    }
    if($concessionValue==''){
        echo 'Concession value Information Missing';
        die;
    }
    if(!is_numeric($concessionValue)){
        echo 'Enter numeric value';
        die;
    }
    if($concessionValue<0){
        echo "Please enter values greater than or equal to zero";
        die;
    }    
    if($concessionValue>0){
        if($reason==''){
            echo ENTER_CONCESSION_REASON;
            die;
        }
    }
	
	$totalFees=getTotalFees($studentId,$classId,$feeCycleId,$feeHeadId);
    
	if($concessionType==1){
        if($concessionValue>100){ //for percentage wise concession,concession value cannot be greter than 100
            echo PERCENTAGE_WISE_MAX_VALUE_CHECK;
            die;
        }
    }
    else if($concessionType==2){ //for value wise concession,,concession value cannot be greter than total fees
       //$totalFees=getTotalFees($studentId,$classId,$feeCycleId,$feeHeadId);
	   
       if($concessionValue>$totalFees){
           echo TOTAL_FEES_WISE_MAX_VALUE_CHECK;
           die;
       }
    }
    else{
      echo 'Invalid Concession Type';
      die;
    }
   
    //building delete part
    if($deleteString!=''){
        $deleteString .=',';
    }
    $deleteString .="'".$feeCycleId.'_'.$studentId.'_'.$classId.'_'.$feeHeadId."'";
    if($discValue==''){
		
		$discValue = "0.00";
	}
    //building insert part
    if($insertString!=''){
        $insertString .=',';
    }
    $insertString .= "($feeCycleId,$feeHeadId, $studentId,$classId,$totalFees,$concessionType,$concessionValue,$discValue,'".$reason."',$userId,'".$dated."' )";
    
    if(($i % 20)==0){ //so that queries does not excede max. character limit
     //then delete previous records
     $ret=$studentConcessionManager->deleteStudentConcession($deleteString);
     if($ret==false){
         echo FAILURE;
         die;
     }
     //now make fresh insert
     $ret=$studentConcessionManager->insertStudentConcession($insertString);
     if($ret==false){
         echo FAILURE;
         die;
     }
      $insertString='';
      $deleteString='';
    }
    
 } //end ot for loop
 
  //code for remaining data
 if($deleteString!=''){
     $ret=$studentConcessionManager->deleteStudentConcession($deleteString);
     if($ret==false){
         echo FAILURE;
         die;
     }
 }
 if($insertString!=''){
     //now make fresh insert
     $ret=$studentConcessionManager->insertStudentConcession($insertString);
     if($ret==false){
         echo FAILURE;
         die;
     }
 }
  //commit transaction
  if(SystemDatabaseManager::getInstance()->commitTransaction()) {
   echo SUCCESS;
   die;
  }
  else {
   echo FAILURE;
   die;
  }
 }
 else {
  echo FAILURE;
  die;
 } 


?>