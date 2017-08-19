<?php 
//This file is used as printing version for testwise marks report.
//
// Author :Ipta Thakur
// Created on : 02-12-2011
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php

	ini_set('MEMORY_LIMIT','200M');
	set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
        define('MODULE','COMMON');
        define('ACCESS','view');
        define('MANAGEMENT_ACCESS',1);
        UtilityManager::ifNotLoggedIn(true);
        UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();

	require_once(BL_PATH . '/ReportFooterPrintManager.inc.php');
	$reportFooterManager = ReportFooterManager::getInstance();

	
	$labelId = trim($REQUEST_DATA['timeTable']);
	$classId = trim($REQUEST_DATA['degree']); 
	$subjectId = trim($REQUEST_DATA['subjectId']);


       if (empty($labelId) or empty($classId) or empty($subjectId)) {
		echo 'Required parameters missing1';
		die;
	}

      $allDetailsArray = array();
	$sortBy = '';

	$sorting = $REQUEST_DATA['sorting'];
	if ($sorting == 'cRollNo') {
		$sortBy = ' length(rollNo)+0,rollNo ';
	}
	elseif ($sorting == 'uRollNo') {
		$sortBy = ' length(universityRollNo)+0,universityRollNo ';
	}
	elseif ($sorting == 'name') {
		$sortBy = ' studentName ';
	}
	$sortBy .= $REQUEST_DATA['ordering'];

	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
	$records    = ($page-1)* RECORDS_PER_PAGE;
	$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	$studentArray2 = $studentReportsManager->countClassStudents($classId);
	$totalStudents = $studentArray2[0]['cnt'];

	$studentArray = $studentReportsManager->getClassStudents($classId, $sortBy, '');
	$studentIdList = UtilityManager::makeCSList($studentArray, 'studentId');
	if (empty($studentIdList)) {
		$studentIdList = 0;
	}

        $tableFooter="<table border='0' cellspacing='20' cellpadding='0' >
                    <tr>
                        <td >
                            Internal Examiner
                        </td>
                        <td  >
                            External Examiner 
                        </td>
                        <td >
                            HOD
                        </td>
                    </tr>
                    <tr>
                        <td  >
                           Name
                        </td>
                        <td  >
                           Name 
                        </td>
                        <td  >
                            Name
                        </td>
                    </tr>
                    <tr>
                        <td  >
                            Signature
                        </td>
                        <td >
                            Signature 
                        </td>
                        <td >
                            Signature
                        </td>
                    </tr>
                  </table>";
    
          $tableExFooter="<table border='0' cellspacing='20' cellpadding='0' >
                    <tr>
                        <td >
                          
                        </td>

                        <td  valign='top' align='center'>
                            Total no. of sheets=1 
                        </td>
                        <td >
                            
                             
                        </td>
                    </tr>
                     <tr>
                         <td>
                             Internal Examiner
                         </td>
                         <td>
                             External Examiner
                         </td>
                         <td>
                             Hod
                         </td>
                         <td>
                             Dean
                         </td>
                    <tr>
                        <td  >
                           Name________
                        </td>
                        <td  >
                           Name________ 
                        </td>
                        <td  >
                            Name________
                        </td>
                        <td>
                            Name________
                        </td>
                    </tr>
                    <tr>
                      <td>
                          Email Id And Phone_____________
                      </td>
                      <td>
                          Email Id And Phone_____________
                      </td>
                    </tr>
                    <tr>
                        <td  >
                            Signature
                        </td>
                        <td >
                            Signature 
                        </td>
                        <td >
                            Signature
                        </td>
                        <td>
                            Signature
                        </td> 
                    </tr>
                  </table>";
    
    
	$ReportFooterPrintManager->setReportWidth(800);
	$ReportFooterPrintManager->setReportHeading('Gazette Report');


 
					//associated key				  col.label,			col. width,	  data align		
	$reportTableHead['srNo']	=	array('#','width="2%" align="left" rowspan="2"', "align='left' ");
	$reportTableHead['rollNo']	=	array('Roll No.<br>Name<br>Father\'s Name','width="10%" align="left" rowspan="2"', "align='left' ");
        $allDetailsArray = array('recordArray'=>$studentArray, 'totalStudents'=>$totalStudents);


	$ReportFooterPrintManager->setRecordsPerPage(25);
	$ReportFooterPrintManager->setReportData($reportTableHead, $allDetailsArray,$tableFooter,'N',$tableExFooter);
	$ReportFooterPrintManager->showGazetteReport();	
       



?>
     
