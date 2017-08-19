<?php
//-------------------------------------------------------
// Purpose: To display the records of student fees from the database, pagination and search
// functionality
//
// Author : Parveen Sharma
// Created on : 16-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifParentNotLoggedIn(true);  
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
    $parentManager = ParentManager::getInstance();

    
	function trim_output($str,$maxlength='250',$rep='...'){
		
		$ret=chunk_split($str,60);

		if(strlen($ret) > $maxlength){

			$ret=substr($ret,0,$maxlength).$rep; 
		}
		return $ret;  
	}
 
   
    $studentName  = add_slashes($REQUEST_DATA['studentName']);
    $studentId  =  $sessionHandler->getSessionVariable('StudentId');    
    $classId  = add_slashes($REQUEST_DATA['rClassId']);
    
   

       
    if($classId=='0') {
       $condition = "WHERE studentId = ".$studentId;
    }
    else {
       $condition = "WHERE studentId = ".$studentId." AND feeStudyPeriodId IN (SELECT studyPeriodId FROM class WHERE classId = '".$classId."')";
    }
 
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
 
 
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'receiptNo';
    $orderBy = " $sortField $sortOrderBy";           
    
    $totalArray          = $parentManager->getStudentTotalFeesClass($condition);
    $resourceRecordArray = $parentManager->getStudentFeesClass($condition,$orderBy,$limit);
    $cnt = count($resourceRecordArray);

    for($i=0;$i<$cnt;$i++) {
 		// add stateId in actionId to populate edit/delete icons in User Interface
	   $paymentInstrument1 = $modeArr[$resourceRecordArray[$i]['paymentInstrument']];
	   $receiptStatus1 = $receiptArr[$resourceRecordArray[$i]['receiptStatus']];
	   $instrumentStatus1 = $receiptPaymentArr[$resourceRecordArray[$i]['instrumentStatus']];

       $valueArray = array_merge(array('paymentInstrument1'=>$paymentInstrument1,'receiptStatus1'=>$receiptStatus1,'instrumentStatus1'=>$instrumentStatus1,'srNo' => ($records+$i+1)),$resourceRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// $History: ajaxStudentFees.php $
//
//*****************  Version 8  *****************
//User: Gurkeerat    Date: 10/15/09   Time: 5:48p
//Updated in $/LeapCC/Library/Parent
//added access rights
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 10/14/09   Time: 5:53p
//Updated in $/LeapCC/Library/Parent
//updated access rights
//
//*****************  Version 6  *****************
//User: Parveen      Date: 9/24/09    Time: 12:29p
//Updated in $/LeapCC/Library/Parent
//study period added (student fee)
//
//*****************  Version 5  *****************
//User: Parveen      Date: 9/24/09    Time: 10:57a
//Updated in $/LeapCC/Library/Parent
//alignment & condition format updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 9/03/09    Time: 5:48p
//Updated in $/LeapCC/Library/Parent
//condition & formating updated issue fix (1426, 1384, 1263, 1074)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/23/08   Time: 1:55p
//Updated in $/LeapCC/Library/Parent
//file updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/16/08   Time: 10:44a
//Updated in $/LeapCC/Library/Parent
//Intial Checkin 
//


?>