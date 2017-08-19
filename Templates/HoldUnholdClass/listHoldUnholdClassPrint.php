<?php
//-------------------------------------------------------
// THIS FILE IS USED TO Reappear/ Re-exam Flow
// Author : Parveen Sharma
// Created on : (12.06.2011 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");

define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

	require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  
require_once(MODEL_PATH . "/ClassSessionUpdateManager.inc.php");
   $classUpdateManager = ClassUpdateManager::getInstance(); 

 require_once(MODEL_PATH . "/HoldUnholdClassManager.inc.php");   
      $HoldUnholdClassManager = HoldUnholdClassManager::getInstance(); 

    	$batchId = trim($REQUEST_DATA['batchId']);  
	$degreeId = trim($REQUEST_DATA['degreeId']);
	$branchId = trim($REQUEST_DATA['branchId']); 
     	
   
   
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    $orderBy = " $sortField $sortOrderBy";
    
    // Findout Time Table Name
     
    $foundArray = $HoldUnholdClassManager->getSessionClasses($batchId,$branchId,$degreeId,$condition,$orderBy);
    $cnt = count($foundArray);
    
    for($i=0;$i<$cnt;$i++) {
        $classId =  $foundArray[$i]['classId'];
        $studyPeroid=  $foundArray[$i]['studyPeriodId'];
        
         $attendance='';
        $marks='';
        $finalResult='';
        $grades='';
        if($foundArray[$i]['holdAttendance']=='1') {
          $attendance='HELD';
        }
        if($foundArray[$i]['holdTestMarks']=='1') {
          $marks='HELD'; 
        }
        if($foundArray[$i]['holdFinalResult']=='1') {
          $finalResult='HELD';  
        }
        if($foundArray[$i]['holdGrades']=='1') {
          $grades='HELD'; 
        }  
 
 
       $valueArray[] = array_merge(array('attendance' => $attendance,
                                        'marks' => $marks,
					 'finalResult' => $finalResult,
					'grades' => $grades,
                                        'srNo' => ($i+1),
                                        'studyPeriodName'=>$studyPeroid),
                                        $foundArray[$i]);
     }
    
    $search ="Label Name&nbsp;:&nbsp;".$labelName;
    
    if($batchId!='') {   
      $batches =  str_replace(",",", ",$batchId); 
      $search .="<br>";
      $search .="Batch Year&nbsp;:&nbsp;".$batches;
    }

	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Hold/Unhold Classes Report ');
   // $reportManager->setReportInformation($search);
    $reportTableHead                    =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']			=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['className']		=    array('Class Name ',' width=20% align="left" ','align="left" ');
    $reportTableHead['attendance']		=    array('Attendance',' width="15%" align="center" ','align="center"');
    $reportTableHead['marks']    =    array('Test Marks',' width="15%" align="center" ','align="center"');
     $reportTableHead['finalResult']    =    array('Final Result',' width="15%" align="center" ','align="center"');
    $reportTableHead['grades']    =    array('Grades',' width="15%" align="center" ','align="center"');
   
	
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 
?>    
