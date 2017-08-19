<?php
//-------------------------------------------------------
// Purpose: To show student fine list
//
// Author : Saurabh Thukral
// Created on : (13.08.2012 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
    global $sessionHandler; 
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
      UtilityManager::ifTeacherNotLoggedIn(true);
      $rollNo= trim($REQUEST_DATA['id']);
    }
    else if($roleId==3){
      UtilityManager::ifParentNotLoggedIn(true);
      $rollNo= $sessionHandler->getSessionVariable('StudentId'); 
    }
    else if($roleId==4){
      UtilityManager::ifStudentNotLoggedIn(true);
      $rollNo= $sessionHandler->getSessionVariable('StudentId'); 
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
      $rollNo= trim($REQUEST_DATA['id']);                        
    }
    UtilityManager::headerNoCache();
    
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/CollectStudentFineManager.inc.php");
    $fineManager = CollectStudentFineManager::getInstance();
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fineDate';
	$orderBy = " $sortField $sortOrderBy";
	

    function trim_output($str,$maxlength,$mode=1,$rep='.......'){
        $ret=($mode==2?chunk_split($str,30):$str);

        if(strlen($ret) > $maxlength){
            $ret=substr($ret,0,$maxlength).$rep;
        }
        return $ret;
    }

    $condition = '';
    if(trim($rollNo)== "") {
      $rollNo = '0';  
    }
    
    if($rollNo != "") {
      $condition  = " AND s.studentId='".$rollNo."'";
   	  $condition1 = " fs.studentId='".$rollNo."'";
      $rollNo1=$rollNo;
	}

	$studentFineArray = $fineManager->getStudentFineList($condition,$orderBy);
	$finePaymentArray = $fineManager->getFinePaymentDetails($condition,$orderBy);
	
	$finalArr=array_merge($studentFineArray,$finePaymentArray);


	$count=count($finalArr);
    for($i=0;$i<$count;$i++){
		$studentFineArray1[] = array_merge(array('srNo' => ($i+1),
	                                       'studentName' => $finalArr[$i]['studentName'],
	                                       'fineCategoryName' => $finalArr[$i]['fineCategoryName'],
	                                       'fineDate' => UtilityManager::formatDate($finalArr[$i]['fineDate']),  
	                                       'reason' => $finalArr[$i]['reason'],  
	                                       'amount' => $finalArr[$i]['amount'],
	                                       'paidAmount' => $finalArr[$i]['paidAmount'],
	                                       'balance' => $finalArr[$i]['balance']   
	                                       ));
	}

	$json_val = json_encode($studentFineArray1);	
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$count.'","page":"'.$page.'","info" : '.$json_val.'}'; 
?>