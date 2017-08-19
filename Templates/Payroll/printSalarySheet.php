 <?php 
//This file is used as printing version for salary sheet.
//
// Author :Abhiraj
// Created on : 04-May-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
     global $FE;
     require_once($FE . "/Library/common.inc.php");
     require_once(BL_PATH . "/UtilityManager.inc.php");
     define('MODULE','Payroll');
     define('ACCESS','view');
     UtilityManager::ifNotLoggedIn(true);
     UtilityManager::headerNoCache();
     require_once(BL_PATH . '/ReportManager.inc.php');
     $reportManager = ReportManager::getInstance();  

     require_once(MODEL_PATH . "/PayrollManager.inc.php");
     $payrollManager = PayrollManager::getInstance();

	 //$search = $REQUEST_DATA['searchbox'];
     //$conditions = ''; 
     $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
     $records    = ($page-1)* RECORDS_PER_PAGE;
     $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
     
	 $month=trim($REQUEST_DATA['month']);
     $year=trim($REQUEST_DATA['year']);   
     global $sessionHandler;
     $headsArray=$sessionHandler->getSessionVariable('selectedHeads');
     $valueArray=$sessionHandler->getSessionVariable('dataArray');
     $valueArray[]=$sessionHandler->getSessionVariable('total');             
     $reportManager->setReportWidth(970);
	 $reportManager->setReportHeading('Salary Sheet For The Month Of '.$REQUEST_DATA['month'].' '.$REQUEST_DATA['year'].'<br>
     <img src='.IMG_HTTP_PATH.'/plus_mini.gif style="position:relative; top:2px; margin-top:2px;"> : Earnings  &nbsp; <img src='.IMG_HTTP_PATH.'/minus_mini.gif style="position:relative; top:2px; margin-top:2px;"> : Deductions
     ');

     $reportTableHead                        =    array();  
     $reportTableHead['srNo']				=    array('#','width="3%" align="left"', "align='left'");
     $reportTableHead['empName']			    =    array('Name ','align="left" ','align="left" ');
     $reportTableHead['empCode']             =    array('Code','align="left" ','align="left"');
     $reportTableHead['empDepartment']       =    array('Department','align="left" ','align="left"');
     $reportTableHead['empDesignation']        =    array('Designation','align="left" ','align="left"');
     for($i=0;$i<count($headsArray);$i++)
     {
         $headAbbr=PayrollManager::getInstance()->getHeadAbbr("where headName like'".$headsArray[$i]."'");
         $headType=PayrollManager::getInstance()->getHeadType("where headName like'".$headsArray[$i]."'");
         if($headType[0]['headType']==0)
         {
            $reportTableHead[$i]        =    array($headAbbr[0]['headAbbr'].'<img src='.IMG_HTTP_PATH.'/plus_mini.gif title="Earning">','align="right" ','align="right"');
         }
         else
         {
            $reportTableHead[$i]        =    array($headAbbr[0]['headAbbr'].'<img src='.IMG_HTTP_PATH.'/minus_mini.gif title="Deduction">','align="right" style="color:red" ','align="right"'); 
         }
         
     }
     $reportTableHead[$i]        =    array('Net','align="right" ','align="right"');
     $reportManager->setRecordsPerPage(40);
     $reportManager->setReportData($reportTableHead, $valueArray);
     $reportManager->showReport(); 

?>