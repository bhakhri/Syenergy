 <?php 
//This file is used as printing version for Attendance Deduct
//
// Author :Prveen Sharma
// Created on : 13-Oct-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/AssignFinalGradeManager.inc.php");    
    define('MODULE','AssignFinalGrade');    
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    $assignFinalGrade = AssignFinalGradeManager::getInstance();    
    
    $foundArray =  $assignFinalGrade->getAssignFinalGradeList();  
    $valueArray = array();
    for($i=0; $i<count($foundArray); $i++ ) {
      $valueArray[] = array_merge(array('srNo' => ($i+1) ),$foundArray[$i]);
    }
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);                   
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Assign Final Grade Report');
    $reportManager->setReportInformation("AS On ".$formattedDate);
                    
    $reportTableHead                 =    array();
                    //associated key                  col.label,         col. width,      data align        
    $reportTableHead['srNo']         =    array('#',            'width="4%" align="left"', "align='left'"); 
    $reportTableHead['minval']       =    array('Grade Point From ',  'width=20% align="right" ','align="right" ');
    $reportTableHead['maxval']       =    array('Grade Point To',    'width="20%" align="right" ','align="right"');
    $reportTableHead['grade']        =    array('Grade',        'width="20%" align="center" ','align="center"');
    $reportTableHead['point']        =    array('Grade Points',        'width="20%" align="right" ','align="right"');  
    
    
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 
?> 
