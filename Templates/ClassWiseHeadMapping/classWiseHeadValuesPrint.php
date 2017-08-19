<?php
//This file is used as printing version for Country Listing
//
// Author :Arvind Singh Rawat
// Created on : 13-Oct-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ClassWiseHeadMapping');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance(); 
   
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/ClassWiseHeadMappingManager.inc.php");
    $classWiseHeadValuesManager = ClassWiseHeadValuesManager::getInstance();

    
    $classId = add_slashes(trim($REQUEST_DATA['classId']));
    
    if($classId=='') {
      $classId=0;  
    }
    
    
    $classNameArray = $studentManager->getSingleField('class', 'className', "WHERE classId  = $classId");
    $className = $classNameArray[0]['className'];
    
    $condition = " fcc.classId=$classId  AND ff.isConsessionable=1 ";
    $classWiseHeadArray = $classWiseHeadValuesManager->getClassWiseHeadList($condition);
    
    for($i=0; $i<count($classWiseHeadArray); $i++ ) {
       if($classWiseHeadArray[$i]['concessionType']==1) {
        $cType = "%age";
       }  
       if($classWiseHeadArray[$i]['concessionType']==2) { 
        $cType = "Fixed";
       }  
       $valueArray[] = array_merge(array('srNo' => ($i+1),
                                         'cType' => $cType),
                                         $classWiseHeadArray[$i]);
    }
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Define Concession Value Report');
    $reportManager->setReportInformation("<b>Class:&nbsp;</b>$className");

    $reportTableHead                         =    array();
                     //associated key                  col.label,         col. width,                      data align
    $reportTableHead['srNo']                 =    array('#',' width="2%"  align="left"','align="left" ');
    $reportTableHead['categoryName']         =    array('Fee Concession Category',' width="30%" align="left" ', 'align="left" ');
    $reportTableHead['HeadName']             =    array('Fee Head ',        ' width="30%" align="left" ', 'align="left" ');
    $reportTableHead['isLeetName']           =    array('Applicable To',    ' width="10%" align="left" ', 'align="left" ');
    $reportTableHead['concessionAmount']     =    array('Concession Value', ' width="15%" align="right" ','align="right" ');
    $reportTableHead['cType']                =    array('Concession Type',  ' width="15%" align="left" ','align="left" ');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();
?>