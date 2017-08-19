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
    define('MODULE','CollectFine');
    define('ACCESS','view');
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineStudentManager = FineManager::getInstance();


	/* START: function to fetch student details along with class */
    $studentFeesArray = $fineStudentManager->getStudentDetail("  AND st.rollNo='".trim(add_slashes($REQUEST_DATA['rollNo']))."'");
    $ttStudentId = $studentFeesArray[0]['studentId'];
    if(is_array($studentFeesArray) && count($studentFeesArray)>0 ) {
        $json_student =  json_encode($studentFeesArray[0]);
    }
    else {
        $json_student =  "exit"; // no record found
		exit;
    }
	/* END: function to fetch student details along with class */
	
	/* START: function to fetch student Serial Number */
	global $sessionHandler;

	$studentFineSerialArr = $fineStudentManager->getStudentFineSerial();
	$studentFineSerialNo  = $studentFineSerialArr[0]['maxSerialNo'];

	if($studentFineSerialNo)
		$studentFineSerialNo  = $studentFineSerialArr[0]['maxSerialNo']+1;
	else
		$studentFineSerialNo  = $sessionHandler->getSessionVariable('STUDENT_FINE_START_FROM');
	/* END: function to fetch student Serial Number */

	
	$studentFeesArray = $fineStudentManager->getFineStudentList(" AND fs.studentId = '".$ttStudentId."'");
	
	$finePaymentArray = $fineStudentManager->getFinePaymentDetails(" AND fs.studentId = '".$ttStudentId."'");
	$finalArr=array_merge($studentFeesArray,$finePaymentArray);

    $span1 = "<span class='redColor'>";
    $span2 = "</span>";
    $cnt = count($finalArr);
	$totalFees = "";
    $totalFine = "0";
	$totalPaidFine = "0";
    for($i=0;$i<$cnt;$i++) {
      
	   if($finalArr[$i]['paidType']=='0') { 
	     $totalFine += $finalArr[$i]['amount'];
	   }

  	   if($finalArr[$i]['paidType']=='1') { 
  	     $totalPaidFine += $finalArr[$i]['paidAmount'];
	   }
        
       $checkall = '<input type="checkbox" name="chb1[]"  id="chb1'.$i.'" value="'.$finalArr[$i]['fineStudentId'].'">
                     <input type="hidden" name="chb1Value['.$finalArr[$i][fineStudentId].']" id="chb1Value'.$i.'" value="'.$finalArr[$i]['amount'].'">';
                     
	   $finalArr[$i]['fineDate'] = UtilityManager::formatDate($finalArr[$i]['fineDate']);

	    $reasonLength = strlen($finalArr[$i]['reason']);
	    if($reasonLength>25){
		  $extendString='...';
	    }
		else{
		  $extendString='';
		}
	
		if($finalArr[$i]['paidAmount']==='---'){
		  $finalArr[$i]['reason'] = '<a title="View Details" onClick="editWindow('.$finalArr[$i]['fineStudentId'].',\'ViewReason\',400,400); return false;"  style="cursor:pointer">'.substr($finalArr[$i]['reason'],0,25).'&nbsp;'.$extendString.'</a>'; 
		}
        
        $srNo = ($records+$i+1);
        if($finalArr[$i]['paidType']=='1') {
          $srNo =  $span1.$srNo.$span2; 
          $finalArr[$i]['fineDate'] =  $span1.$finalArr[$i]['fineDate'].$span2; 
          $finalArr[$i]['fineCategoryAbbr'] =  $span1.$finalArr[$i]['fineCategoryAbbr'].$span2;
          $finalArr[$i]['receiptNo'] =  $span1.$finalArr[$i]['receiptNo'].$span2;
          $finalArr[$i]['reason'] =  $span1.$finalArr[$i]['reason'].$span2;
          $finalArr[$i]['amount'] =  $span1.$finalArr[$i]['amount'].$span2;
          $finalArr[$i]['paidAmount'] =  $span1.$finalArr[$i]['paidAmount'].$span2;
        }
        
		$valueArray = array_merge(array('checkAll' => $checkall,
                                        'srNo' => $srNo
                                        ),
                                        $finalArr[$i]);
		
	    if(trim($json_val)=='') {
          $json_val = json_encode($valueArray);
        }
        else {
          $json_val .= ','.json_encode($valueArray);           
        }
    }
	$balance=$totalFine-$totalPaidFine;
    echo '{"serialNo":"'.$studentFineSerialNo.'","totalAmount":"'.$balance.'","studentinfo" : ['.$json_student.'],"info" : ['.$json_val.']}'; 
?>