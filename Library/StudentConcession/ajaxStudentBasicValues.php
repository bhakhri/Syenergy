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
    define('MODULE','CollectFees');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
	global $sessionHandler;
	/* START: function to fetch student details along with class */
	$studentFeesArray = $studentManager->getStudentDetailClass(' AND stu.studentId='.$studentId.' AND cls.classId='.$classId);
	
		$studentHeadFeesArray = array();
		$studentBusFeesArray = array();	 
		$studentHostelFeesArray = array();	 
		/* START: function to fetch student head details */
		
		global $feeTypeArr;

		if(($REQUEST_DATA['feeTypeId']==1) || ($REQUEST_DATA['feeTypeId']==4)){
		
			$studentHeadFeesArray = $studentManager->getStudentFeeHeadDetailClass($REQUEST_DATA['feeCycleId'],$REQUEST_DATA['studyPeriodId'],$studentFeesArray[0]['instituteId'],$studentFeesArray[0]['universityId'],$studentFeesArray[0]['batchId'],$studentFeesArray[0]['degreeId'],$studentFeesArray[0]['branchId'],$studentFeesArray[0]['quotaId'],$studentFeesArray[0]['isLeet']);
		}
		
		if(($REQUEST_DATA['feeTypeId']==2) || ($REQUEST_DATA['feeTypeId']==4)){
		
			$busCondition = "";
			//echo "---".$studentFeesArray[0]['busStopId'];
			if($studentFeesArray[0]['busStopId']!=0){
			
				$busCondition = " and bus.busStopId = ".$busStopId;
			} 
			$studentBusFeesArray = $studentManager->getStudentBusDetailClass($busCondition,$transportFacility);
		}

		if(($REQUEST_DATA['feeTypeId']==3) || ($REQUEST_DATA['feeTypeId']==4)){
			$hostelCondition = "";
			if($studentFeesArray[0]['hostelRoomId']!=0){
			
				$hostelCondition = "  and hosroom.hostelRoomId = ".$hostelRoomId;
			}
			$studentHostelFeesArray = $studentManager->getStudentHostelDetailClass($hostelCondition,$hostelFacility);
		} 
		$studentFeesArray = array_merge ($studentHeadFeesArray,$studentBusFeesArray,$studentHostelFeesArray);
		/* END: function to fetch student head details details */
	
	$cnt = count($studentFeesArray);
	$totalFees = "";
    for($i=0;$i<$cnt;$i++) {

		$showlink ="";
		$tabIndex = 4;
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

		if($headAmt>0)
		{
			$showlink ="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$headAmt."<input type='hidden' name='chb[]' class='inputbox3' size='8' value='".$headAmt."' 'READONLY'>";
		}
		else
		{
			if($studentFeesArray[$i]['isConsessionable'])
			{
				if($concessionType==1){
				
					$totalCon = ($feeHeadAmt1*$concessionValue)/100;
				}
				else{
				
					$totalCon = $concessionValue;
				}
				//$abc = $feeHeadAmt1*$concessionValue;
				$tabIndex = $tabIndex+1;
				$showlink ="<input type='text' name='chb[]' class='inputbox3' size='6' value='".$totalCon."' maxlength='8' onkeyup='calculateConcession()' tabindex='4'><input type='checkbox' name='feeHeadId[]' value='".$studentFeesArray[$i]['feeHeadId']."' CHECKED style='display:none'>";
			}
			else
				$showlink ="0.00<input type='hidden' name='chb[]' class='inputbox3' size='6' value='".$headAmt."' 'READONLY'><input type='checkbox' name='feeHeadId[]' value='".$studentFeesArray[$i]['feeHeadId']."' CHECKED style='display:none'>";
		}
		$valueArray = array_merge(array('feeHeadAmt' =>  $feeHeadAmt,'concession' =>  $showlink, 'srNo' => ($records+$i+1) ),$studentFeesArray[$i]);
		
		$totalFees += $studentFeesArray[$i]['feeHeadAmount'];
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
	
	
    echo '{"info" : ['.$json_val.']}'; 
 

?>