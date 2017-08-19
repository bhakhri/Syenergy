<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A HOSTEL VISITOR
//
//
// Author : Gurkeerat Sidhu
// Created on : (20.04.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelVisitor');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
   if (!isset($REQUEST_DATA['visitorName']) || trim($REQUEST_DATA['visitorName']) == '') {
        $errorMessage .= ENTER_VISITOR_NAME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['address']) || trim($REQUEST_DATA['address']) == '')) {
        $errorMessage .= ENTER_ADDRESS."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['timeOfVisit']) || trim($REQUEST_DATA['timeOfVisit']) == '')) {
        $errorMessage .= ENTER_TIME."\n";    
    }
     if ($errorMessage == '' && (!isset($REQUEST_DATA['toVisit']) || trim($REQUEST_DATA['toVisit']) == '')) {
        $errorMessage .= ENTER_TO_VISIT."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['purpose']) || trim($REQUEST_DATA['purpose']) == '')) {
        $errorMessage .= ENTER_PURPOSE ."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['contactNo']) || trim($REQUEST_DATA['contactNo']) == '')) {
        $errorMessage .= ENTER_CONTACT ."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['relation']) || trim($REQUEST_DATA['relation']) == '')) {
        $errorMessage .= SELECT_RELATION ."\n";    
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/HostelVisitorManager.inc.php");
        $d = $REQUEST_DATA['dateOfVisit'];
        $cdate = date('Y-m-d');
        $dArray = explode('-',$d);
        $cdateArray = explode('-',$cdate);
        $dVal = $dArray[0].$dArray[1].$dArray[2];
        $cdateVal = $cdateArray[0].$cdateArray[1].$cdateArray[2];
		if($cdateVal<$dVal){
			  echo FUTURE_DATE_VALIDATION;
			  exit();
			}
		$visitTime = $REQUEST_DATA['timeOfVisit'];
		$visitTime = intval(substr($visitTime,0,2));

		if($visitTime > 23 or $visitTime < 0) {
			echo HOURS_LIMIT;
			exit(0);
		}
	        
        $returnStatus = HostelVisitorManager::getInstance()->editHostelVisitor($REQUEST_DATA['visitorId']);
            if($returnStatus === false) {
                echo FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
    else {
        echo $errorMessage;
    }

?>
