<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
   
        require_once(MODEL_PATH . "/StudentAdhocConcessionManager.inc.php");
        $studentAdhocConcessionManager = StudentAdhocConcessionManager::getInstance();

        $feeClassId = trim(add_slashes($REQUEST_DATA['feeClassId']));
        $studentName = trim(add_slashes($REQUEST_DATA['studentName']));
        $rollNo = trim(add_slashes($REQUEST_DATA['rollNo']));
        
        if(trim($REQUEST_DATA['feeClassId'])==''){
           echo SELECT_FEE_CLASS;
           die;
        }
        
        if(trim($REQUEST_DATA['studentName'])=='' && trim($REQUEST_DATA['rollNo'])==''){
           echo ENTER_NAME_ROLLNO;
           die;
        }
    
   
        $condition = '';
        if($rollNo!='') {
          $condition .= " AND (stu.rollNo LIKE '$rollNo' OR stu.regNo LIKE '$rollNo'  OR stu.universityRollNo LIKE '$rollNo') ";   
        }
        
        if($studentName!='') {
          $condition .= " AND (CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) LIKE '$studentName') ";   
        }
        
        $studentFeesArray = $studentAdhocConcessionManager->getStudentDetailClass($condition,$feeClassId);     
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
        $concessionCondition = " studentId = '$studentId' AND classId = '$feeClassId' ";
        $concessionCheckArray = $studentAdhocConcessionManager->getCheckStudentConcession($concessionCondition);    
        if(is_array($concessionCheckArray) && count($concessionCheckArray)>0 ) {
           if($concessionCheckArray[0]['cnt'] > 0) {
              echo STUDENT_CONCESSION_CATEGORY; 
              die;     
           } 
        }
        
        
        
        $studentId = $studentFeesArray[0]['studentId'];  
        $quotaId = $studentFeesArray[0]['quotaId'];  
        $isLeet = $studentFeesArray[0]['isLeet'];  
        
        $isMigration = $studentFeesArray[0]['isMigration'];  
        $migrationClassId = $studentFeesArray[0]['migrationClassId'];  
         
        $universityId = $studentFeesArray[0]['universityId'];
        $batchId = $studentFeesArray[0]['batchId'];
        $degreeId = $studentFeesArray[0]['degreeId'];
        $branchId = $studentFeesArray[0]['branchId'];
        $studyPeriodId = $studentFeesArray[0]['studyPeriodId'];
        $feeStudyPeriodId = $studentFeesArray[0]['feeStudyPeriodId'];
        
        $tIsLeet=2; 
        if($isLeet==1) {
          $tIsLeet=1;  
        }
    
    
        $adhocCondition =" AND acm.feeClassId = $feeClassId AND acm.studentId = $studentId "; 
        $adhocArray=$studentAdhocConcessionManager->getStudentAdhocConcession($adhocCondition);
    
    
        $feeId = "-1";
        $havingConditon = " COUNT(fhv.feeHeadId) = 1 "; 
        $foundArray = $studentAdhocConcessionManager->getCountFeeHead($feeClassId,$quotaId,$tIsLeet,$havingConditon);
        for($i=0; $i<count($foundArray); $i++) {
          $feeId .=",".$foundArray[$i]['feeId'];  
        }        
            
        $havingConditon = " COUNT(fhv.feeHeadId) >= 2"; 
        $isLeetCheck = "1,2,3";
        $foundArray = $studentAdhocConcessionManager->getCountFeeHead($feeClassId,$quotaId,$tIsLeet,$havingConditon,'',0,$isLeetCheck); 
        for($i=0; $i<count($foundArray); $i++) {
           $tFeeHeadId = $foundArray[$i]['feeHeadId']; 
           if($quotaId!='') {
              $feeHeadCondition = " AND fhv.quotaId = $quotaId AND fhv.feeHeadId = $tFeeHeadId";  
              $quotaFoundArray = $studentAdhocConcessionManager->getCountFeeHead($feeClassId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
              if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                $feeId .=",".$quotaFoundArray[0]['feeId'];  
              }
              else {
                $feeHeadCondition = " AND IFNULL(fhv.quotaId,'')='' AND fhv.feeHeadId = $tFeeHeadId";  
                $quotaFoundArray = $studentAdhocConcessionManager->getCountFeeHead($feeClassId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
                if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                  $feeId .=",".$quotaFoundArray[0]['feeId'];  
                }
                else {
                    $feeHeadCondition = " AND IFNULL(fhv.quotaId,'')='' AND fhv.feeHeadId = $tFeeHeadId";  
                    $quotaFoundArray = $studentAdhocConcessionManager->getCountFeeHeadNew($feeClassId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
                    if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                           $feeId .=",".$quotaFoundArray[0]['feeId'];  
                    }
                }

              }
           }
           else {
             $feeHeadCondition = " AND IFNULL(fhv.quotaId,'')='' AND fhv.feeHeadId = $tFeeHeadId";  
             $quotaFoundArray = $studentAdhocConcessionManager->getCountFeeHead($feeClassId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
             if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
               $feeId .=",".$quotaFoundArray[0]['feeId'];  
             } 
           }
        }        
        if($feeId=='') {
          $feeId = "-1"; 
        }
                          
        //================================FEE HEAD DETAILS (Start)=======================================
        $foundArray = $studentAdhocConcessionManager->getStudentFeeHeadDetail($feeClassId,$feeId,$studentId);
        $feeHeadIds = "-1";
        for($i=0;$i<count($foundArray);$i++) {
          $feeHeadIds .= ",".$foundArray[$i]['feeHeadId'];   
        }
        
        
        $comments = "";    
        for($i=0; $i<count($foundArray); $i++) {
           $feeId = $studentId.'_'.$foundArray[$i]['classId'].'_'.$foundArray[$i]['isVariable'].'_'.$foundArray[$i]['feeHeadId'];
           $feeHeadAmt = $foundArray[$i]['feeHeadAmt'];
           
           $adhocAmount='';
           for($j=0; $j<count($adhocArray); $j++) {
              if($adhocArray[$j]['feeHeadId']==$foundArray[$i]['feeHeadId'] && $adhocArray[$j]['studentId']==$studentId && $adhocArray[$j]['feeClassId']==$foundArray[$i]['classId']) {
                 $adhocAmount = $adhocArray[$j]['concessionAmount'];
                 $comments = $adhocArray[$j]['description'];   
                 break;  
              }  
           }
           $concession = "<input type='hidden'  readonly='readonly' id='feeHeadId$i' name='feeHeadId[]' value='".$feeId."'>
                          <input type='hidden'  readonly='readonly' id='feeAmount$i' name='feeAmount[]' value='".$feeHeadAmt."'>
                          <input type='hidden'  readonly='readonly' id='idNos' name='idNos[]' value='".$i."'> 
                          <input type='text' name='totalAmount[]' id='totalAmount$i' value='".$adhocAmount."' maxlength='12' style='width:150px' class='inputbox2'>";
            
           $valueArray = array_merge(array('srNo' => ($i+1),
                                           'concession' => $concession
                                          ), $foundArray[$i]);   
           
           if(trim($json_val)=='') {
             $json_val = json_encode($valueArray);
           }
           else {
             $json_val .= ','.json_encode($valueArray);           
           } 
        }
        
        $json_student = json_encode($studentFeesArray[0]);        
        
        echo '{"comments":"'.$comments.'","studentinfo" : ['.$json_student.'],"info" : ['.$json_val.']}';  
        
    die;  
?>    