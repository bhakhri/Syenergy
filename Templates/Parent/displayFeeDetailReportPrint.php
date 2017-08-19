 <?php 
//This file is used as printing version for display attendance report in parent module.
//
// Author :Arvind Singh Rawat
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifParentNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

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
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'receiptNo';
    $orderBy = " $sortField $sortOrderBy";           
       
         
    if($classId=='0') {
       $condition = "WHERE studentId = ".$studentId;
    }
    else {
       $condition = "WHERE studentId = ".$studentId." AND feeStudyPeriodId IN (SELECT studyPeriodId FROM class WHERE classId = '".$classId."')";
    }
    
    $resourceRecordArray = $parentManager->getStudentFeesClass($condition,$orderBy,$limit);
    $cnt = count($resourceRecordArray);
    

    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
       $paymentInstrument1 = $modeArr[$resourceRecordArray[$i]['paymentInstrument']];
       $receiptStatus1 = $receiptArr[$resourceRecordArray[$i]['receiptStatus']];
       $instrumentStatus1 = $receiptPaymentArr[$resourceRecordArray[$i]['instrumentStatus']];
       $valueArray[] = array_merge(array('paymentInstrument1'=>$paymentInstrument1,'receiptStatus1'=>$receiptStatus1,'instrumentStatus1'=>$instrumentStatus1,'srNo' => ($records+$i+1)),$resourceRecordArray[$i]);
    }

    $formattedDate = date('d-M-y'); 
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Student Fee Detail Report');
    $reportManager->setReportInformation("For ".$studentName." As On $formattedDate");

    $reportTableHead                         =    array();
                    //associated key                  col.label,             col. width,      data align        
    $reportTableHead['srNo']                 =    array('#',                 'width="2%" align="left"', "align='left' ");
    $reportTableHead['receiptNo']            =    array('Receipt No.',       'width=11% align="left" ','align="left" ');
    $reportTableHead['receiptDate']          =    array('Receipt Date',      'width="12%" align="center" ','align="center"');
    $reportTableHead['periodName']          =    array('Study Period',       'width="12%" align="center" ','align="center"'); 
    $reportTableHead['totalFeePayable']      =    array('Total Fee(Rs.)',   'width="13%" align="right"','align="right"');
    $reportTableHead['discountedFeePayable'] =    array('Payable(Rs.)',     'width="12%" align="right"','align="right"');
    $reportTableHead['totalAmountPaid']      =    array('Paid(Rs.)',        'width="11%" align="right"','align="right"');
    $reportTableHead['paymentInstrument1']   =    array('Payment<br>Instrument','width="10%" align="left"','align="left"');
    $reportTableHead['receiptStatus1']       =    array('Receipt<br>Status',    'width="9%" align="left"','align="left"');
    $reportTableHead['instrumentStatus1']    =    array('Instrument<br>Status', 'width="15%" align="left"','align="left"');
    
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
