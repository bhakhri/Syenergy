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
	define('MODULE','FeeReceiptStatus');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	require_once(BL_PATH.'/HtmlFunctions.inc.php');
	$htmlManager = HtmlFunctions::getInstance();
    
    $receiptId = $REQUEST_DATA['recieptId'];
    
    if($receiptId=='') {
      echo "Fee Receipt Status cannot be selected";
      die;
    }
    
    $find=0;
    $id = explode(",",$receiptId);
    for($i=0;$i<count($id);$i++) {
      $ids = explode("~",$id[$i]);
      $feeReceiptId = $ids[0]; 
      $receiptStatus = $ids[1]; 
      $instrumentStatus = $ids[2];
       
      SystemDatabaseManager::getInstance()->runAutoUpdate('fee_receipt', array('receiptStatus','instrumentStatus'), array($receiptStatus,$instrumentStatus), " feeReceiptId = $feeReceiptId" );
      $find=1;
    }
    
    //echo $receiptStatus;
	if($find==1){
	  echo SUCCESS;
	}
    else {
      echo "Fee Receipt Status cannot be updated";
    }
 
// for VSS
// $History: ajaxUpdateStatus.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/23/08   Time: 12:57p
//Updated in $/LeapCC/Library/Student
//updated as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 9/05/08    Time: 6:04p
//Updated in $/Leap/Source/Library/Student
//initial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/28/08    Time: 1:10p
//Created in $/Leap/Source/Library/Student
//intial checkin
?>
