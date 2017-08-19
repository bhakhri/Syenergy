 <?php
//This file is used as printing version for display attendance report in parent module.
//
// Author :Jaineesh
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentManager = StudentInformationManager::getInstance();

	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
	$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subject';
	$orderBy = " $sortField $sortOrderBy";

    $studentId = $sessionHandler->getSessionVariable('StudentId');

	$classId = $REQUEST_DATA['studyPeriodId'];
	if($sessionHandler->getSessionVariable('MARKS') == 1){
		$studentSubjectArray = $studentManager->getStudentMarks($studentId,$classId,"",$orderBy);
		$recordCount = count($studentSubjectArray);
	}
	$j=0;
	$k=0;
	$marksPrintArray[] =  Array();
	if($recordCount >0 && is_array($studentSubjectArray) ) {
			$subjectName = "";
			$examType="";

			for($i=0; $i<$recordCount; $i++ ) {
				$studentSubjectArray[$i]['testDate'] = UtilityManager::formatDate($studentSubjectArray[$i]['testDate']);

				$studentSubjectArray[$i]['marksObtained'] = "0.00";
				$bg = $bg =='row0' ? 'row1' : 'row0';
				if ($studentSubjectArray[$i]['obtained'] >0 && $studentSubjectArray[$i]['totalMarks'] >0) {
				  $studentSubjectArray[$i]['marksObtained'] = "".ROUND((($studentSubjectArray[$i]['obtained']/$studentSubjectArray[$i]['totalMarks'])*100),2)."";
				}

				//echo($marksObtained);

				$j=$i+1;


			   /* if($subjectName == $subjectName1)
				{
					$subjectName1 = "";

					 $k++;
					$j="";
				}
			   else{
				   $marksPrintArray[$i]['subjectName']=$subjectName1;
				  $j=$i-$k+1;
				}*/

				 if ($studentSubjectArray[$i]['obtained']=='Not MOC'){
					$studentSubjectArray[$i]['marksObtained'] = NOT_APPLICABLE_STRING;

				}
				 if ($studentSubjectArray[$i]['obtained']=='A'){
					$studentSubjectArray[$i]['marksObtained'] = NOT_APPLICABLE_STRING;

				}
				//echo($marksObtained);
			 //$marksObtained = number_format($marksObtained, 2, '.', '');
			 $studentSubjectArray[$i]['Percentage']=$studentSubjectArray[$i]['marksObtained'];
			 $valueArray[] = array_merge(array('srNo' => ($i+1) ),$studentSubjectArray[$i]);

			 /*$marksPrintArray[$i]['srNo']   =$j;
			 $marksPrintArray[$i]['subject']=$studentSubjectArray[$i]['subject'];
			 $marksPrintArray[$i]['groupName']=$studentSubjectArray[$i]['groupName'];
			 $marksPrintArray[$i]['examType']=$studentSubjectArray[$i]['examType'];
			 $marksPrintArray[$i]['testDate']=$studentSubjectArray[$i]['testDate'];
			 $marksPrintArray[$i]['periodName']=$studentSubjectArray[$i]['periodName'];
			 $marksPrintArray[$i]['employeeName']=$studentSubjectArray[$i]['employeeName'];
			 $marksPrintArray[$i]['testName']=$studentSubjectArray[$i]['testName'];
			 $marksPrintArray[$i]['totalMarks']=$studentSubjectArray[$i]['totalMarks'];
			 $marksPrintArray[$i]['obtained']=$studentSubjectArray[$i]['obtained']; */


			if($subjectName1 != "")
				$subjectName = $subjectName1;

			 if($studentSubjectArray[$i]['examType'] != "" )
			 {
				  $examType=$studentSubjectArray[$i]['examType'];
			 }
		}
	}

    $className=$studentManager -> getClassName();
	$rollNo = $className[0]['rollNo'];
    $className2=str_replace('-'," ",$className[0]['className']) ;
	$studentArray = $commonQueryManager->getAttendance("WHERE s.studentId='$studentId' ");
	$name = $studentArray[0]['studentName'];

    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Student Marks Report ');
	if ($name != '' && $rollNo != '' && $className2 != '') {
		$reportManager->setReportInformation("$name <br> $rollNo, $className2");
	}

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align
    $reportTableHead['srNo']                =    array('#',                'width="3%" align="center"', "align='center' ");
    $reportTableHead['subject']				=    array('Subject ',         ' width=14% align="center" ','align="left" ');
	$reportTableHead['groupName']			=    array('Group ',      ' width=12% align="center" ','align="left" ');
    $reportTableHead['examType']			=    array('Test Type',        ' width="12%" align="left" ','align="left"');
	$reportTableHead['testDate']			=    array('Test Date',        ' width="10%" align="center" ','align="center"');
	$reportTableHead['periodName']			=    array('Study Period',     ' width="10%" align="left" ','align="left"');
	$reportTableHead['employeeName']		=    array('Teacher Name',     ' width="14%" align="left" ','align="left"');
    $reportTableHead['testName']            =    array('Test',        'width="6%" align="left"','align="left"');
    $reportTableHead['totalMarks']          =    array('M.M',       'width="8%" align="right"','align="right"');
    $reportTableHead['obtained']            =    array('M.S',     'width="8%" align="right"','align="right"');
    $reportTableHead['Percentage']          =    array('%age',            'width="8%" align="right"','align="right"');


    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();

//$History : $
?>