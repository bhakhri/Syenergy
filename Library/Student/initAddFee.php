<?php
//-------------------------------------------------------
// Purpose: To store the records of fees receipt
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
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

	$errorMessage ='';
    //print_r($REQUEST_DATA);
	if (!isset($REQUEST_DATA['studentRoll']) || trim($REQUEST_DATA['studentRoll']) == '') {
        $errorMessage .= 'Please enter student roll number <br/>';
    }
    if (!isset($REQUEST_DATA['feeCycle']) || trim($REQUEST_DATA['feeCycle']) == '') {
        $errorMessage .= 'Please select fee cycle <br/>';
    }
	if (!isset($REQUEST_DATA['feeStudyPeriod']) || trim($REQUEST_DATA['feeStudyPeriod']) == '') {
        $errorMessage .= 'Please select fee study period <br/>';
    }
 
	$sessionHandler->setSessionVariable('FEE_RECEIPT_DATE',$REQUEST_DATA['receiptDate']);

    if (trim($errorMessage) == '') {

		require_once(MODEL_PATH . "/StudentManager.inc.php");
		$studentManager = StudentManager::getInstance();
		
		$studentRoll    = $REQUEST_DATA['studentRoll'];
		$feeCycle	    = $REQUEST_DATA['feeCycle'];
		$feeStudyPeriod	= $REQUEST_DATA['feeStudyPeriod'];
		$studentId	    = $REQUEST_DATA['studentId'];
		
		$foundArray = StudentManager::getInstance()->getFeeReceiptNo(' WHERE feeCycleId='.trim($REQUEST_DATA['feeCycle']).' AND  receiptNo="'.add_slashes(trim($REQUEST_DATA['receiptNumber'])).'" ');
        if(trim($foundArray[0]['receiptNo'])=='') {  //DUPLICATE CHECK
            
			$returnStatus = $studentManager->insertStudentFees();
			if($returnStatus) {

				echo SUCCESS.'~'.$returnStatus;  
			}
			else {

				echo FAILURE.'~0';  
			}
        }
        else {
             
                echo RECEIPT_ALREADY_EXIST.'~0';
                die;
              
        } 
		
    
	}
	else {
		echo FAILURE;  
    }

// $History: initAddFee.php $
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Library/Student
//updated with all the fees enhancements
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 09-11-21   Time: 3:52p
//Updated in $/LeapCC/Library/Student
//Added Student search,receipt no manual and fee type functionality in
//collect fees
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
//*****************  Version 3  *****************
//User: Rajeev       Date: 9/17/08    Time: 11:36a
//Updated in $/Leap/Source/Library/Student
//updated the formatting and added comments
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/31/08    Time: 4:32p
//Updated in $/Leap/Source/Library/Student
//reviewed the file
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/24/08    Time: 12:36p
//Created in $/Leap/Source/Library/Student
//intial checkin
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/10/08    Time: 5:53p
//Updated in $/Leap/Source/Library/Student
//made the student admit module ajax based
?>