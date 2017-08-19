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

	$condition = " AND rollNo='".$REQUEST_DATA['rollNo']."'";
    
	if($REQUEST_DATA['deleteStudent']=='1'){
		
		$studentFeesArray = $studentManager->getQuarantineStudentDetailClass($condition);
	}
	else{

		$studentFeesArray = $studentManager->getStudentDetailClass($condition);
	}
	if(count($studentFeesArray)>0){
	
	}
	else{

		$condition = " AND studentId='".$REQUEST_DATA['studentId']."'";
		if($REQUEST_DATA['deleteStudent']){
		
			$studentFeesArray = $studentManager->getQuarantineStudentDetailClass($condition);
		}else{
		
			$studentFeesArray = $studentManager->getStudentDetailClass($condition);
		}
	}
	
	if(is_array($studentFeesArray) && count($studentFeesArray)>0 ) {  
		$json_student =  json_encode($studentFeesArray[0]);
	}
	else {
		$json_student =  "exit"; // no record found
		exit;
	}
	$studentId = $studentFeesArray[0]['studentId'];
	//die();
	$condition = "WHERE feeCycleId=".$REQUEST_DATA['feeCycleId']." AND studentId=".$studentFeesArray[0]['studentId'];
	$studentConcessionArray = $studentManager->getConcessionDetail($condition);
	$cnt = count($studentConcessionArray);
    for($i=0;$i<$cnt;$i++) {

	  $concessionArr[$studentConcessionArray[$i]['feeHeadId']]=$studentConcessionArray[$i]['concessionValue'].'~'.$studentConcessionArray[$i]['concessionType'].'~'.$studentConcessionArray[$i]['reason'];
	}
	/* END: function to fetch student details along with class */
	
	/* START: function to fetch student fee cycle fine*/
    $studentFeeCycleArray = $studentManager->getStudentCycleFineClass($REQUEST_DATA['receiptDate']);
	$studentFeeCycleFine  = $studentFeeCycleArray[0]['fineAmount'];
	/* END: function to fetch student fee cycle fine*/

	/* START: function to fetch student installment*/
	$studentInstallmentFeeArr = $studentManager->getStudentInstallment($studentFeesArray[0]['studentId'],$REQUEST_DATA['feeCycleId'],$REQUEST_DATA['studyPeriodId'],$REQUEST_DATA['feeTypeId']);
	$studentInstallment    = $studentInstallmentFeeArr[0]['toalInstallment'];
	/* END: function to fetch student installment*/

	/* START: function to fetch student previous details*/
	$studentIPreviousFeeArr = $studentManager->getStudentPrevious($studentFeesArray[0]['studentId'],$REQUEST_DATA['feeCycleId'],$REQUEST_DATA['studyPeriodId'],$REQUEST_DATA['feeTypeId']);
	$previousDues         = $studentIPreviousFeeArr[0]['previousDues'];
    $previousOverPayment  = $studentIPreviousFeeArr[0]['previousOverPayment'];
	/*if($studentInstallment){
	
		$studentInstallmentFeeArrAmt = $studentManager->getStudentInstallmentAmt($studentFeesArray[0]['studentId'],$REQUEST_DATA['feeCycleId'],$REQUEST_DATA['studyPeriodId'],$REQUEST_DATA['feeTypeId']);	
		$discountedFeePayable = $studentInstallmentFeeArrAmt[0]['toalInstallmentAmt'];
	}
	else{
		*/
		$discountedFeePayable = $studentIPreviousFeeArr[0]['discountedFeePayable'];
	//}
	$totalAmountPaid      = $studentIPreviousFeeArr[0]['totalAmountPaid'];
	$feeStudyPeriodId     = $studentIPreviousFeeArr[0]['feeStudyPeriodId'];
	
	$hostelFacility = $studentFeesArray[0]['hostelFacility'];
	$transportFacility = $studentFeesArray[0]['transportFacility'];
	/* END: function to fetch student previous details*/

	if($studentInstallment)
	{

		//if(($REQUEST_DATA['feeTypeId']==1) || ($REQUEST_DATA['feeTypeId']==4)){

			$studentFeesArray = $studentManager->getStudentHeadDetailClass($studentId,$REQUEST_DATA['feeCycleId'],$REQUEST_DATA['feeTypeId'],$REQUEST_DATA['studyPeriodId']);

			/* START: function to fetch student fine*/
			$studentFineArr = $studentManager->getStudentFine($studentId,$REQUEST_DATA['feeCycleId'],$REQUEST_DATA['studyPeriodId']);
			$studentFeeCycleFine    = $studentFineArr[0]['fine'];
			/* END: function to fetch student fine*/
			//$studentFeeCycleFine = $studentFineAmount;
		//}
		/*if(($REQUEST_DATA['feeTypeId']==2)){

			$studentFeesArray = $studentManager->getStudentHeadDetailClass($studentId,$REQUEST_DATA['feeCycleId'],$REQUEST_DATA['feeTypeId']);
		}
		if(($REQUEST_DATA['feeTypeId']==3)){

			$studentFeesArray = $studentManager->getStudentHeadDetailClass($studentId,$REQUEST_DATA['feeCycleId'],$REQUEST_DATA['feeTypeId']);
		}*/
	}
	else
	{
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
			if($studentFeesArray[0]['busStopId']!=0){
			
				$busCondition = " and bus.busStopId = ".$studentFeesArray[0]['busStopId'];
			} 
			$studentBusFeesArray = $studentManager->getStudentBusDetailClass($busCondition,$transportFacility);
		}

		if(($REQUEST_DATA['feeTypeId']==3) || ($REQUEST_DATA['feeTypeId']==4)){
			$hostelCondition = "";
			if($studentFeesArray[0]['hostelRoomId']!=0){
			
				$hostelCondition = "  and hosroom.hostelRoomId = ".$studentFeesArray[0]['hostelRoomId'];
			}
			$studentHostelFeesArray = $studentManager->getStudentHostelDetailClass($hostelCondition,$hostelFacility);
		} 
		$studentFeesArray = array_merge ($studentHeadFeesArray,$studentBusFeesArray,$studentHostelFeesArray);
		/* END: function to fetch student head details details */
	}
	/*echo "<pre>";
	print_r($studentFeesArray);

	echo "<pre>";
	print_r($studentBusFeesArray);

	echo "<pre>";
	print_r($studentHostelFeesArray);*/
	//echo "-------".$studentFeesArray[$i]['hostelHead'];
	$cnt = count($studentFeesArray);
	$totalFees = "";
	$grandFees = 0;
	$discValue = 0; 
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
				$ConcessionArr = explode('~',$concessionArr[$studentFeesArray[$i]['feeHeadId']]);	 
				if($ConcessionArr[1]==1){
				
					$totalCon = number_format((($feeHeadAmt1*$ConcessionArr[0])/100),"2",".","");
				}
				else{
					$totalCon = number_format($ConcessionArr[0],"2",".","");;
				}
				//$abc = $feeHeadAmt1*$concessionValue;
				$tabIndex = $tabIndex+1;
				$showlink ="<input type='text' name='chb[]' class='inputbox3' size='6' value='".$totalCon."' maxlength='8' onkeyup='calculateConcession()'><input type='checkbox' name='feeHeadId[]' value='".$studentFeesArray[$i]['feeHeadId']."' CHECKED style='display:none'>";
			}
			else
				$showlink ="0.00<input type='hidden' name='chb[]' class='inputbox3' size='6' value='".$headAmt."' 'READONLY'><input type='checkbox' name='feeHeadId[]' value='".$studentFeesArray[$i]['feeHeadId']."' CHECKED style='display:none'>";
		}
		$valueArray = array_merge(array('feeHeadAmt' =>  $feeHeadAmt,'concession' =>  $showlink, 'srNo' => ($records+$i+1) ),$studentFeesArray[$i]);
		
       if($studentFeesArray[$i]['discountedAmount']){
	   
			$discValue += $studentFeesArray[$i]['discountedAmount'];
	   }
	   $totalFees += $studentFeesArray[$i]['feeHeadAmount'];
	   $grandFees += $studentFeesArray[$i]['feeHeadAmount'];
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }

	   $totalCon1=$totalCon1+$totalCon;
    }
	$totalFees = $totalFees-$discValue;
	 
	$totalFees -= $totalCon1;
	$previousDues = number_format($previousDues,"2",".","");
	$previousOverPayment = number_format($previousOverPayment,"2",".","");
	$discountedFeePayable = number_format($discountedFeePayable,"2",".","");
	$totalAmountPaid = number_format($totalAmountPaid,"2",".","");

	$previousPaid = $totalFees - $previousDues + $previousOverPayment;
	$previousPaid = number_format($previousPaid,"2",".","");
	if($feeStudyPeriodId!=$REQUEST_DATA['studyPeriodId'])
	{
		$previousPaid = "0.00";
		if($previousDues>0)
			$previousPaid = "-".$previousDues;
		
		if($previousOverPayment>0)
			$previousPaid = $previousOverPayment;
		//$previousPaid = $discountedFeePayable;
	}

	//$totalFees = $totalFees + $previousDues - $previousOverPayment;
	$totalFees = number_format($totalFees,"2",".","");

	$studentTotalPaidFee = number_format($studentTotalPaidFee,"2",".","");
	$grandFees = number_format($grandFees,"2",".","");
	$studentFeeCycleFine = number_format($studentFeeCycleFine,"2",".","");
	$discountAmt = number_format($discountAmt,"2",".","");
	$discountAmt = "0.00";
	$studentInstallmentCount = "Installment 1 <br>" ;
	for($count=2;$count<=$studentInstallment + 1;$count++)
		$studentInstallmentCount .= "Installment ".$count."<br>";
	//$studentInstallmentCount = $studentInstallment + 1;
    echo '{"previousDues":"'.$previousDues.'","previousPaid":"'.$previousPaid.'","discountAmt":"'.$discountAmt.'","studentInstallmentCount":"'.$studentInstallmentCount.'","studentFeeCycleFine":"'.$studentFeeCycleFine.'","paidFee":"'.$studentTotalPaidFee.'","totalAmount":"'.$grandFees.'","discAmount":"'.$totalFees.'","studentinfo" : ['.$json_student.'],"info" : ['.$json_val.']}'; 
 
// for VSS
// $History: ajaxStudentBasicValues.php $
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Library/Student
//updated with all the fees enhancements
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 10-03-04   Time: 12:32p
//Updated in $/LeapCC/Library/Student
//condition format updated in student details
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 09-11-21   Time: 3:52p
//Updated in $/LeapCC/Library/Student
//Added Student search,receipt no manual and fee type functionality in
//collect fees
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 09-09-02   Time: 3:03p
//Updated in $/LeapCC/Library/Student
//Updated with config parameter which has been removed from
//common.inc.php
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 9/17/08    Time: 11:36a
//Updated in $/Leap/Source/Library/Student
//updated the formatting and added comments
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 9/10/08    Time: 12:17p
//Updated in $/Leap/Source/Library/Student
//updated tab order
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 8/27/08    Time: 2:13p
//Updated in $/Leap/Source/Library/Student
//updated fee module
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 8/13/08    Time: 6:45p
//Updated in $/Leap/Source/Library/Student
//updated fee receipt modifications
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/05/08    Time: 6:30p
//Updated in $/Leap/Source/Library/Student
//updated feehead spacing
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/31/08    Time: 4:32p
//Updated in $/Leap/Source/Library/Student
//reviewed the file
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/24/08    Time: 6:37p
//Updated in $/Leap/Source/Library/Student
//updated the validations
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/24/08    Time: 12:36p
//Updated in $/Leap/Source/Library/Student
//completed the fee receipt functionality
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/19/08    Time: 7:10p
//Created in $/Leap/Source/Library/Student
//intial checkin
?>