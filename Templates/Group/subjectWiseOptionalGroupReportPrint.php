<?php
//This file is used as printing version for SMS
//
// Author :Parveen Sharma
// Created on : 26-11-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php        
    set_time_limit(0);         
    require_once(BL_PATH . "/UtilityManager.inc.php");                    
    define('MODULE','COMMON');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1); 
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once($FE . "/Library/HtmlFunctions.inc.php");
    $htmlManager  = HtmlFunctions::getInstance();
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

  
    require_once(MODEL_PATH . "/OptionalSubjectGroupManager.inc.php");
    $groupManager = OptionalSubjectGroupManager::getInstance();

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'groupName';
    
    $orderBy = " $sortField $sortOrderBy";         

  
    $foundArray = $groupManager->getRegistrationClassList();

    $k=0;             
    $className = '';                                 
    for($i=0;$i<count($foundArray);$i++) {
         
         $groupId = $foundArray[$i]['groupId'];
         
         $checkStatus ="N";
         if($groupId!=-1) {
           $checkStatus="Y";  
         }
          
         if($className!=$foundArray[$i]['className']) {
           $tclassName = strip_slashes($foundArray[$i]['className']);
           $k=0;
         }  
         else {
           $tclassName = '';  
         }
          
         $valueArray[$i]['srNo']            = ($k+1);
         $valueArray[$i]['className']       = $tclassName;
         $valueArray[$i]['subjectName']     = strip_slashes($foundArray[$i]['subjectName']);
         $valueArray[$i]['careerStudent']   = strip_slashes($foundArray[$i]['careerStudent']);
         $valueArray[$i]['electiveStudent'] = strip_slashes($foundArray[$i]['electiveStudent']);
         $valueArray[$i]['totalStudent']    = strip_slashes($foundArray[$i]['totalStudent']);
         $valueArray[$i]['subjectCode']     = strip_slashes($foundArray[$i]['subjectCode']);
         $valueArray[$i]['groupStatus']     = $checkStatus;
        
         $className = strip_slashes($foundArray[$i]['className']);          
         $k++;           
    }
    
    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);    
    $reportHead = "As On ".$formattedDate;
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Subject Wise Optional Group Report');
    $reportManager->setReportInformation($reportHead);    
    
    $reportTableHead                        =    array();
                //associated key                    col.label,             col. width,      data align
    $reportTableHead['srNo']                = array('#',                'width="2%"  align="left"',  'align="left"');
    $reportTableHead['className']           = array('Class Name',       'width="25%" align="left"', 'align="left"');
    $reportTableHead['subjectName']         = array('Subject Name',     'width="25%" align="left"', 'align="left"');
    $reportTableHead['careerStudent']       = array('Career',           'width="6%" align="right"', 'align="right"');
    $reportTableHead['electiveStudent']     = array('Elective',         'width="7%" align="right"', 'align="right"');
    $reportTableHead['totalStudent']        = array('Total Students',   'width="12%" align="right"', 'align="right"');
    $reportTableHead['subjectCode']         = array('Group Name',       'width="10%" align="left"', 'align="left"');
    $reportTableHead['groupStatus']         = array('Create Group?',    'width="15%" align="center"',  'align="center"');
   

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();
    
?>