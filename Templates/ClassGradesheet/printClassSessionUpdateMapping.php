<?php
//-------------------------------------------------------
// THIS FILE IS USED TO Reappear/ Re-exam Flow
// Author : Parveen Sharma
// Created on : (12.06.2011 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/ClassSessionUpdateManager.inc.php");
define('MODULE','ClassSessionUpdate');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

	require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    $classUpdateManager = ClassUpdateManager::getInstance(); 
    

    	$batchId = trim($REQUEST_DATA['batchId']);  
	$degreeId = trim($REQUEST_DATA['degreeId']);
	$branchId = trim($REQUEST_DATA['branchId']); 
     	
   
   
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    $orderBy = " $sortField $sortOrderBy";
    
    // Findout Time Table Name
    $labelArray = $classUpdateManager->getSingleField('class', "WHERE batchId  = $batchId AND degreeId=$degreeId AND branchId=$branchId");
   
   
       
    //$foundArray = $classUpdateManager->getReAppearLabelClasses($labelId,$condition,$orderBy); 
    $cnt = count($labelArray);

 
    for($i=0;$i<$cnt;$i++) {
       $valueArray[] = array_merge(array('className' => $labelArray[$i]['className'],
                                         'srNo' => ($records+$i+1) ,'titleName'=>$labelArray[$i]['sessionTitleName']),$labelArray[$i]);
	}
    
    $search ="Label Name&nbsp;:&nbsp;".$labelName;
    
    if($batchId!='') {   
      $batches =  str_replace(",",", ",$batchId); 
      $search .="<br>";
      $search .="Batch Year&nbsp;:&nbsp;".$batches;
    }

	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Class Session Report ');
   // $reportManager->setReportInformation($search);
    $reportTableHead                    =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']			=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['className']		=    array('Class Name ',' width=15% align="left" ','align="left" ');
    $reportTableHead['titleName']		=    array('Title',' width="15%" align="center" ','align="center"');
    $reportTableHead['displayOrder']    =    array('Display Order',' width="15%" align="center" ','align="center"');
   
	
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 
?>    
