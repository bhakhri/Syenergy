<?php
//This file is used as printing version for Country Listing
//
// Author :Arvind Singh Rawat
// Created on : 13-Oct-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FeeHeadValues');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance(); 
   
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/FeeHeadValuesManager.inc.php");
    $feeHeadValuesManager = FeeHeadValuesManager::getInstance();

    
    $classId = add_slashes(trim($REQUEST_DATA['classId']));
    
    if($classId=='') {
      $classId=0;  
    }
    
    
    $classNameArray = $studentManager->getSingleField('class', 'className', "WHERE classId  = $classId");
    $className = $classNameArray[0]['className'];
    
    $condition = " fh.classId=$classId  AND ff.isVariable=0 ";
    $feeHeadArray = $feeHeadValuesManager->getFeeHeadList($condition);
    
    for($i=0; $i<count($feeHeadArray); $i++ ) {
       $valueArray[] = array_merge(array('srNo' => ($i+1) ),$feeHeadArray[$i]);
    }
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Fee Head Values Report');
    $reportManager->setReportInformation("<b>Class:&nbsp;</b>$className");

    $reportTableHead                        =    array();
                     //associated key                  col.label,         col. width,                      data align
    $reportTableHead['srNo']                =    array('#',              ' width="2%"  align="left"',     'align="left" ');
    $reportTableHead['feeHeadName']         =    array('Fee Head',       ' width="30%" align="left" ',    'align="left" ');
    $reportTableHead['quotaName']           =    array('Quota ',         ' width="30%" align="left" ',    'align="left" ');
    $reportTableHead['isLeetName']          =    array('Applicable To',  ' width="10%" align="left" ',    'align="left" ');
    $reportTableHead['feeHeadAmount']       =    array('Amount',         ' width="15%" align="right" ',   'align="right" ');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();

    
    
/*    
     /// Search filter /////
     if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       if(add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'yes' || add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'y' || add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'ye') 
          $str = ' OR fh.isConsessionable = "1" OR fh.transportHead = "1" OR fh.hostelHead = "1" OR fh.miscHead = "1"  OR fhv.isLeet = "1"  ';
       else if(add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'no'  || add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'n') 
          $str = ' OR fh.isConsessionable = "0" OR fh.transportHead = "0" OR fh.hostelHead = "0" OR fh.miscHead = "0"  OR fhv.isLeet = "0"  ';  
       else if(add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'both'  || add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'bo'  || add_slashes(strtolower($REQUEST_DATA['searchbox'])) == 'bot') 
          $str = '  OR fhv.isLeet = "2" OR fhv.isLeet IS NULL ';   
        
       if(strtoupper($REQUEST_DATA['searchbox'])=='ALL') {
          $filter = ' AND (bat.batchName IS NULL  OR b.branchCode IS NULL OR std.periodName IS NULL OR 
                           d.degreeAbbr IS NULL OR univ.universityAbbr IS NULL '.$str.')'; 
       }
       else {
          $filter = ' AND (fc.cycleName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           fh.headAbbr LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           ffa.allocationEntity LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           fhv.feeHeadAmount LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           bat.batchName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           b.branchCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           std.periodName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           d.degreeAbbr LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           univ.universityAbbr LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%"'.$str.')';         
       }
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'headAbbr';
    $search = $REQUEST_DATA['searchbox'];
    $orderBy = " $sortField $sortOrderBy";

    //$totalArray = $sectionManager->getTotalSection();
    //$totalArray = $branchManager->getTotalBatch($filter);
    $feeHeadValuesRecordArray = $feeHeadValuesManager->getFeeHeadValuesList($filter,'',$orderBy);
    $cnt = count($feeHeadValuesRecordArray);
    for($i=0;$i<$cnt;$i++) {
        $feeHeadValuesRecordArray[$i]['srNo']=($i+1);
        $feeHeadValuesRecordArray[$i]['batchName']=changeNull($feeHeadValuesRecordArray[$i]['batchName']);
        $feeHeadValuesRecordArray[$i]['branchCode']=changeNull($feeHeadValuesRecordArray[$i]['branchCode']);
        $feeHeadValuesRecordArray[$i]['universityAbbr']=changeNull($feeHeadValuesRecordArray[$i]['universityAbbr']);
        $feeHeadValuesRecordArray[$i]['periodName']=changeNull($feeHeadValuesRecordArray[$i]['periodName']);
        $feeHeadValuesRecordArray[$i]['degreeAbbr']=changeNull($feeHeadValuesRecordArray[$i]['degreeAbbr']);

        if($feeHeadValuesRecordArray[$i]['isLeet']=="1"){
            $feeHeadValuesRecordArray[$i]['isLeet']="Yes";
        }
        elseif($feeHeadValuesRecordArray[$i]['isLeet']=="0"){
           $feeHeadValuesRecordArray[$i]['isLeet']="No";
        }
        else {        
           $feeHeadValuesRecordArray[$i]['isLeet']="Both";
        }

        //$feeHeadValuesRecordArray[$i]['isLeet']=changeNull($feeHeadValuesRecordArray[$i]['isLeet']);
        // add feeHeadValueId in actionId to populate edit/delete icons in User Interface   
       $feeHeadValuesRecordArray[$i]['feeHeadAmount']=number_format($feeHeadValuesRecordArray[$i]['feeHeadAmount'],2,'.','');
    }

    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Fee Head Values Report');
    $reportManager->setReportInformation("Search By: $search");

    $reportTableHead                        =    array();
                     //associated key                  col.label,              col. width,                         data align
    $reportTableHead['srNo']                =    array('#',                    ' width="2%"  align="left"',      'align="left" ');
    $reportTableHead['headAbbr']            =    array('Fee Head',             ' width="13%" align="left" ',     'align="left" ');
    $reportTableHead['cycleName']           =    array('Fee Cycle  ',          ' width="12%" align="left" ',     'align="left" ');
    $reportTableHead['allocationEntity']    =    array('Fee Fund Allocation',  ' width="14%" align="left" ',     'align="left" ');
    $reportTableHead['universityAbbr']      =    array('University',           ' width="9%" align="left" ',     'align="left" ');
    $reportTableHead['branchCode']          =    array('Branch',               ' width="9%" align="left" ',     'align="left" ');
    $reportTableHead['periodName']          =    array('Study Period',         ' width="10%" align="left" ',     'align="left" ');
    $reportTableHead['degreeAbbr']          =    array('Degree',               ' width="9%" align="left" ',     'align="left" ');
    $reportTableHead['batchName']           =    array('Batch',                ' width="9%" align="left" ',     'align="left" ');
    $reportTableHead['isLeet']				  =    array('Is Leet',                ' width="9%" align="left" ',     'align="left" ');
    $reportTableHead['feeHeadAmount']       =    array('Fee Head Amount',      ' width="15%" align="right" ',   'align="right" ');

    $reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
    $reportManager->setReportData($reportTableHead, $feeHeadValuesRecordArray);
    $reportManager->showReport();
*/
//$History : $
?>
