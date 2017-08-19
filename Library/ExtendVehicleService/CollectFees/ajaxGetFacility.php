<?php

//The file contains data base functions for marks
//
// Author :Rajeev Aggarwal
// Created on : 21.11.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','CollectFees');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/CollectStudentFeeManager.inc.php");   
    $collectStudentFeeManager = CollectStudentFeeManager::getInstance(); 
    
    $rollNo = add_slashes(trim($REQUEST_DATA['rollNo']));
    $id  = add_slashes(trim($REQUEST_DATA['id']));
    $feeClassId = add_slashes(trim($REQUEST_DATA['feeClassId']));
    
    
    $sessionHandler->setSessionVariable('IdToFindFeeRollNo',$rollNo);
    $sessionHandler->setSessionVariable('IdToFindFeeClassId',$feeClassId); 
    
    // $id = 1  == > Transport Facility
    // $id = 2  == > Hostel Facility
    // $id = 3  == > Prev Dues
    if($rollNo=='' || $id =='') {
       echo 'Required parameter missing';  
       die; 
    }
    
    $condition = " AND (s.rollNo = '$rollNo' OR s.regNo = '$rollNo'  OR s.universityRollNo = '$rollNo' ) ";
    $foundArray = $collectStudentFeeManager->getStudentClassDetail($condition);
    if(is_array($foundArray) && count($foundArray)>0 ) {    
       $classCondition = " AND c.batchId  = '".$foundArray[0]['batchId']."' AND c.degreeId = '".$foundArray[0]['degreeId']."'  
                           AND c.branchId = '".$foundArray[0]['branchId']."'";
    }
    else {
       echo 0;
       die; 
    }
    
    $studentId = $foundArray[0]['studentId'];     
    $studentName = $foundArray[0]['studentName'];
    $fatherName = $foundArray[0]['fatherName'];  
    $rollNo = $foundArray[0]['rollNo'];
    $regNo = $foundArray[0]['regNo'];
    $univRollNo = $foundArray[0]['universityRollNo'];
    $studentName = $foundArray[0]['studentName'];
    $isLeet = $foundArray[0]['isLeet'];
    $isMigration = $foundArray[0]['isMigration'];  
    $migrationClassId = $foundArray[0]['migrationClassId']; 
   
    
    if($isMigration=='1') {
      $classCondition .= " AND c.classId >=$migrationClassId ";  
    }
    else if($isLeet=='1') {
      $classCondition .= " AND sp.periodValue >=3 "; 
    }
 
    if($id=='1' || $id=='2') {
      $classArray = $collectStudentFeeManager->getFeeReceiptClasses($classCondition,' c.branchId, c.studyPeriodId',$studentId,$id);  
    }
    else {
      $classArray = $collectStudentFeeManager->getFeePendingDuesClasses($classCondition,' c.branchId, c.studyPeriodId',$studentId);    
    }
    
    for($i=0;$i<count($classArray);$i++) {
        
        $classId = $classArray[$i]['classId'];
        $str = $studentId."~".$id."~".$classId;
        
        $tCharges = $classArray[$i]['charges']; 
        $tConcession = $classArray[$i]['concession']; 
        $tComments = $classArray[$i]['comments']; 
        
        $amt = '<input type="hidden" name="facilityClassId[]" id="facilityClassId.'.$i.'" value="'.$classId.'" class="inputbox" />
                <input type="hidden" name="facilityTypeId[]" id="facilityTypeId.'.$i.'" value="'.$id.'" class="inputbox" />
                <input type="text"   name="facilityAmt[]" id="facilityAmt.'.$i.'" value="'.$tCharges.'" class="inputbox" maxlength="9" style="width:100px" />';
        $concession = '<input type="text" name="facilityConcession[]" id="facilityConcession.'.$i.'" value="'.$tConcession.'" class="inputbox" maxlength="9" style="width:100px" />'; 
        $comments = '<input type="text" name="facilityComments[]" id="facilityComments.'.$i.'" value="'.$tComments.'" class="inputbox" maxlength="500" style="width:250px" />'; 
        
        //$checkall = '<input type="checkbox"  name="facility[]" id="facility.'.$i.'" value="'.$str.'">';  
        $valueArray = array_merge(array('srNo' => ($i+1),
                                        //'checkAll' => $checkall,
                                        'className' =>$classArray[$i]['className'],
                                        'facilityAmount' => $amt,
                                        'facilityConcession' => $concession,
                                        'facilityComments' => $comments 
                                        ));   
        if(trim($json_val)=='') {
          $json_val = json_encode($valueArray);
        }
        else {
          $json_val .= ','.json_encode($valueArray);           
        } 
    }
    
    
    echo '{"studentName":"'.$studentName.'","fatherName":"'.$fatherName.'","studentId":"'.$studentId.'",  
           "rollNo":"'.$rollNo.'","regNo":"'.$regNo.'","univRollNo":"'.$univRollNo.'",  
           "info" : ['.$json_val.']}';
    die;
?> 