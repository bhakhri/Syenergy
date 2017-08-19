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
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifParentNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
    $parentManager = ParentManager::getInstance();

    // to limit records per page

    $studentId = $sessionHandler->getSessionVariable('StudentId');
    $classId  = $REQUEST_DATA['rClassId'];


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studyPeriod';


    $orderBy = " $sortField $sortOrderBy";

    $className=$parentManager -> getClassName();
    $className2=str_replace(CLASS_SEPRATOR," ",$className[0]['className']) ;

    $studentName = $sessionHandler->getSessionVariable('StudentName');

    $current="Current Class&nbsp;:&nbsp;";
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $search = "$studentName<br>$current$className2<br>As On $formattedDate";

    //$sectionCondtion = " AND sct.studentId = $studentId";
	if($sessionHandler->getSessionVariable('MARKS') == 1){
		$studentSectionArray = $studentManager->getStudentMarks($studentId,$classId,$orderBy,'');
		$cnt = count($studentSectionArray);
	}

      $valueArray = array();
	  for($i=0;$i<$cnt;$i++) {
       $studentSectionArray[$i]['testDate'] = UtilityManager::formatDate($studentSectionArray[$i]['testDate']);
       $tot=number_format($studentSectionArray[$i]['totalMarks'], 0, '.', '');
       $obt=number_format($studentSectionArray[$i]['obtainedMarks'], 0, '.', '');
       $valueArray[] = array_merge(array('srNo' => ($i+1),
                                         'totalMarks' =>  $tot,
                                         'obtainedMarks' => $obt
                                        ),
                                         $studentSectionArray[$i]);
    }



    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Student Marks Report ');
    $reportManager->setReportInformation($search);

    $reportTableHead                        =    array();
     //associated key                           col.label,              col. width,      data align
     $reportTableHead['srNo']               = array('#',                'width="2%"      align="left"',      'align="left"');
     $reportTableHead['studyPeriod']        = array('Study Period ',    'width="10%"     align="left"',      'align="left"');
     $reportTableHead['subjectName']        = array('Subject',          'width="15%"     align="left"',      'align="left"');
     $reportTableHead['testTypeName']       = array('Test',             'width="8%"      align="left"',      'align="left"');
     $reportTableHead['testDate']           = array('Date',             'width="8%"      align="center"',    'align="center"');
     $reportTableHead['employeeName']       = array('Teacher',          'width="15% "    align="left"',      'align="left"');
     $reportTableHead['testName']           = array('Test Name',        'width="10%"      align="left"',      'align="left"');
     $reportTableHead['totalMarks']         = array('Max. Marks',       'width="8%"      align="right"',     'align="right"');
     $reportTableHead['obtainedMarks']      = array('Scored',           'width="5%"      align="right"',     'align="right"');

     $reportManager->setRecordsPerPage(30);
     $reportManager->setReportData($reportTableHead, $valueArray);
     $reportManager->showReport();

//$History : $
//

?>
