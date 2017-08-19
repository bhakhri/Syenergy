<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','CollectFees');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
	global $sessionHandler;

	$feeCycleId = $REQUEST_DATA['feeCycleId'];
	$studentId  = $REQUEST_DATA['studentId'];
	$classId    = $REQUEST_DATA['classId'];

	/* START: function to fetch student details along with class */
	//$condition = " AND rollNo='".$REQUEST_DATA['rollNo']."'";
	$condition = " AND studentId='".$REQUEST_DATA['studentId']."'";

    $studentFeesArray = $studentManager->getStudentDetailClass($condition);
	if(is_array($studentFeesArray) && count($studentFeesArray)>0 ) {  
        $json_student =  json_encode($studentFeesArray[0]);
    }
    else {
        $json_student =  "exit"; // no record found
		exit;
    }
	$studentId = $studentFeesArray[0]['studentId'];
	
	$condition = "WHERE feeCycleId=".$REQUEST_DATA['feeCycleId']." AND studentId=".$studentFeesArray[0]['studentId'];
	$studentConcessionArray = $studentManager->getConcessionDetail($condition);
	$cnt = count($studentConcessionArray);
    for($i=0;$i<$cnt;$i++) {

	  $concessionArr[$studentConcessionArray[$i]['feeHeadId']]=$studentConcessionArray[$i]['concessionValue'].'~'.$studentConcessionArray[$i]['concessionType'].'~'.$studentConcessionArray[$i]['reason'];
	}
	//print_r($concessionArr);
	/* END: function to fetch student details along with class */
	
	$hostelFacility = $studentFeesArray[0]['hostelFacility'];
	$transportFacility = $studentFeesArray[0]['transportFacility'];
	/* END: function to fetch student previous details*/

	$studentHeadFeesArray = array();
	$studentBusFeesArray = array();	 
	$studentHostelFeesArray = array();	 
	/* START: function to fetch student head details */
	
	global $feeTypeArr;
	$studentHeadFeesArray = $studentManager->getStudentFeeHeadDetailClass($REQUEST_DATA['feeCycleId'],$REQUEST_DATA['studyPeriodId'],$studentFeesArray[0]['instituteId'],$studentFeesArray[0]['universityId'],$studentFeesArray[0]['batchId'],$studentFeesArray[0]['degreeId'],$studentFeesArray[0]['branchId'],$studentFeesArray[0]['quotaId'],$studentFeesArray[0]['isLeet']);

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
	$studentFeesArray = array_merge ($studentHeadFeesArray,$studentBusFeesArray,$studentHostelFeesArray);
	/* END: function to fetch student head details details */

	$cnt = count($studentFeesArray);
	$totalFees = "";
	$discValue = 0; 
    for($i=0;$i<$cnt;$i++) {

		$showlink ="";
		$headAmt = number_format($studentFeesArray[$i]['discountedAmount'],"2",".","");;
		$feeHeadAmt1 = number_format($studentFeesArray[$i]['feeHeadAmount'],'2','.','');
		$discountAmt += $studentFeesArray[$i]['discountedAmount'];

		if($studentFeesArray[$i]['hostelHead']){
		
			$feeHeadAmt ="<input type='text' name='feeHeadAmt[]' class='inputbox3' size='4' value='".$feeHeadAmt1."' maxlength='10' onkeyup='calculateTotal()'>";
		}
		 
		else if($studentFeesArray[$i]['transportHead']){
		
			$feeHeadAmt ="<input type='text' name='feeHeadAmt[]' class='inputbox3' size='4' value='".$feeHeadAmt1."' maxlength='10' onkeyup='calculateTotal()'>";
		}
		else if($studentFeesArray[$i]['miscHead']){
		
			$feeHeadAmt ="<input type='text' name='feeHeadAmt[]' class='inputbox3' size='4' value='".$feeHeadAmt1."' maxlength='10' onkeyup='calculateTotal()'>";
		}
		else{
			
			$feeHeadAmt =$feeHeadAmt1."<input type='hidden' name='feeHeadAmt[]' class='inputbox3' size='10' value='".$feeHeadAmt1."' maxlength='10' 'READONLY'>";
		}

		$ConcessionArr = explode('~',$concessionArr[$studentFeesArray[$i]['feeHeadId']]);	 
		if($ConcessionArr[1]==1){
		
			$totalCon = number_format((($feeHeadAmt1*$ConcessionArr[0])/100),"2",".","");
		}
		else{
			$totalCon = number_format($ConcessionArr[0],"2",".","");;
		}
		if($ConcessionArr[2]=='')
			$reason="--";
		else{
		
			$reason=$ConcessionArr[2];
		}

		$showlink ="<input type='text' name='chb[]' class='inputbox3' size='6' value='".$totalCon."'  onkeyup='calculateTotal()'><input type='hidden' name='chb1[]' value='".$feeHeadAmt1."' ><input type='checkbox' name='feeHeadId[]' value='".$studentFeesArray[$i]['feeHeadId']."' CHECKED style='display:none'>";
		$valueArray = array_merge(array('reason'=>$reason,'feeHeadAmt' =>  $feeHeadAmt,'concession' =>  $showlink, 'srNo' => ($records+$i+1) ),$studentFeesArray[$i]);
		
        if($studentFeesArray[$i]['discountedAmount']){
	   
			$discValue += $studentFeesArray[$i]['discountedAmount'];
	    }
	   $totalFees += $studentFeesArray[$i]['feeHeadAmount'];

	   $totalConcession +=$totalCon;
	   
       if(trim($json_val)=='') {

            $json_val = json_encode($valueArray);
       }
       else {

            $json_val .= ','.json_encode($valueArray);           
       }
    }
	$totalConcession =number_format($totalConcession,"2",".","");
	$totalFees =number_format($totalFees,"2",".","");
	 
    echo '{"classId":"'.$classId.'","studentId":"'.$studentId.'","feeCycleId":"'.$feeCycleId.'","totalFees":"'.$totalFees.'","totalConcession":"'.$totalConcession.'","studentinfo" : ['.$json_student.'],"info" : ['.$json_val.']}'; 
?>