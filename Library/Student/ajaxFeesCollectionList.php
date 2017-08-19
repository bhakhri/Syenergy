<?php
//-------------------------------------------------------
// Purpose: To store the records of Fee Collection in array from the database 
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FeeCollection');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	require_once(BL_PATH.'/HtmlFunctions.inc.php');
	$htmlManager = HtmlFunctions::getInstance();
    
	// to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
	$showTitle = "";
	$showData  = "";
	$showPrint = "";
	 
	if(UtilityManager::notEmpty($REQUEST_DATA['univId'])) {
	    
	   $filter .= ' AND (cls.universityId IN ('.$REQUEST_DATA['univId'].'))';         
	}
	
	if(UtilityManager::notEmpty($REQUEST_DATA['instsId'])) {
	    
	   $filter .= ' AND (cls.instituteId IN ('.$REQUEST_DATA['instsId'].'))';         
	}
	
	if(UtilityManager::notEmpty($REQUEST_DATA['degsId'])) {
	    
	   $filter .= ' AND (cls.degreeId IN ('.$REQUEST_DATA['degsId'].'))';         
	}
	
	if(UtilityManager::notEmpty($REQUEST_DATA['bransId'])) {
	    
	   $filter .= ' AND (cls.branchId IN ('.$REQUEST_DATA['bransId'].'))';         
	}
	
	if(UtilityManager::notEmpty($REQUEST_DATA['semsId'])) {
	    
	   $filter .= ' AND (cls.studyPeriodId IN ('.$REQUEST_DATA['semsId'].'))';         
	}
	
	if(UtilityManager::notEmpty($REQUEST_DATA['feesId'])) {
	   
	   $feeCycleArr = explode(",", $REQUEST_DATA['feesId']);
	   //$filter .= ' AND (fr.feeCycleId IN ('.$REQUEST_DATA['feesId'].'))';         
	}
	
	if(UtilityManager::notEmpty($REQUEST_DATA['fromDate'])) {
	   $filter .= " AND (fr.receiptDate >='".add_slashes($REQUEST_DATA['fromDate'])."')";         
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['toDate'])) {
	   $filter .= " AND (fr.receiptDate <='".add_slashes($REQUEST_DATA['toDate'])."')";         
	}
	
	//$totalArray = $studentManager->getTotalFeesStudent($filter);

	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'cycleName';
    
    $orderBy = " ORDER BY $sortField $sortOrderBy";  
   
    $cnt = count($feeCycleArr);
    
	$feeCycle = "";
	$totalAmt1= "";
	$totalAmt = "";
	$filterPrev = $filter; 
	for($i=0;$i<$cnt;$i++){
	   $filter1 = '';	
	   $filter1 = " AND fr.feeCycleId =".$feeCycleArr[$i]." AND fr.isNew = 1 AND fr.receiptStatus NOT IN (3,4) ";
	   $filter  = $filterPrev.' '.$filter1;

	   $studentRecordArray = $studentManager->getFeeCollectionList($filter,'',$orderBy);
       $cont = count($studentRecordArray);
       
       $filter1 = '';
	   $totalCash = '';
	   $totalCheque = '';
	   $totalDraft = '';
	   $feeCycle = '';
	   for($j=0;$j<$cont;$j++) {
          $feeReceiptId = $studentRecordArray[$j]['feeReceiptId'];
          $totalCash +=$studentRecordArray[$j]['cashAmount'];    
              
          $conditions = " WHERE feeReceiptId = $feeReceiptId AND paymentInstrument =2";
          $resultArray = $studentManager->getSingleField('fee_payment_detail',' SUM(instrumentAmount) AS totalAmountPaid',$conditions);  
          $totalCheque +=$resultArray[0]['totalAmountPaid'];  
          
          $conditions = " WHERE feeReceiptId = $feeReceiptId AND paymentInstrument =3";
          $resultArray = $studentManager->getSingleField('fee_payment_detail',' SUM(instrumentAmount) AS totalAmountPaid',$conditions); 
          $totalDraft +=$resultArray[0]['totalAmountPaid'];   
           
          if($studentRecordArray[$j]['paymentInstrument']=="1")
		  {
			 $totalCash +=$studentRecordArray[$j]['totalAmountPaid'];
		  }
		  if($studentRecordArray[$j]['paymentInstrument']=="2")
		  {
			 $totalCheque +=$studentRecordArray[$j]['totalAmountPaid'];
		  }
		  if($studentRecordArray[$j]['paymentInstrument']=="3")
		  {
			 $totalDraft +=$studentRecordArray[$j]['totalAmountPaid'];
		  }
		  $feeCycle = $studentRecordArray[$j]['feeCycleId'];
	   }

	   $feeArr = $studentManager->getFieldValue("fee_cycle","cycleName",$feeCycleArr[$i],"feeCycleId");
	   $feeCycle=	$feeArr[0]['cycleName'];
	   
	   $totalCash = ($totalCash!='') ? $totalCash : "-- ";
	   $totalCash =  number_format($totalCash,'2','.','');
	   
	   $totalCheque = ($totalCheque!='') ? $totalCheque : "-- ";
	   $totalCheque =  number_format($totalCheque,'2','.','');
	   
	   $totalDraft = ($totalDraft!='') ? $totalDraft : "-- ";
	   $totalDraft =  number_format($totalDraft,'2','.','');

	   $totalAmount = $totalCash+$totalCheque+$totalDraft;
	   $totalAmount =  number_format($totalAmount,'2','.','');

	   $grandAmount = $grandAmount+$totalAmount;
	   $grandAmount =  number_format($grandAmount,'2','.','');
	   
	   $valueArray = array_merge(array('totalAmount' =>$totalAmount,'cycleName' =>$feeCycle,
                                       'cashPayment' =>$totalCash,'chequePayment' =>$totalCheque,
                                       'draftPayment' =>$totalDraft,'srNo' => ($records+$i+1) ));
	   
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
       
			$json_val .= ','.json_encode($valueArray);           
       }
    }
	
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxFeesCollectionList.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/Student
//added access defines for management login
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/21/09    Time: 4:38p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: Updated access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 9/16/08    Time: 1:43p
//Updated in $/Leap/Source/Library/Student
//updated the query
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/16/08    Time: 11:29a
//Created in $/Leap/Source/Library/Student
//intial checkin 
?>