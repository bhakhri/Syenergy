 <?php
//This file is used to save student marks in csv format.
//
// Author :Jaineesh
// Created on : 24-09-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
    $studentId = $sessionHandler->getSessionVariable('StudentId');

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
			$bg = $bg =='row0' ? 'row1' : 'row0';
			$marksObtained =  ($studentSubjectArray[$i]['obtained']/$studentSubjectArray[$i]['totalMarks']);

			//$subjectName1 = $studentSubjectArray[$i]['subjectName'].' ('.$studentSubjectArray[$i]['subjectCode'].')';
			$j=$i+1;
			$marksObtained=$marksObtained*100;

			/*if($subjectName == $subjectName1)
			{
				$subjectName1 = "";

				 $k++;
				$j="";
			}
		   else{
			   $marksPrintArray[$i]['subjectName']=$subjectName1;
			  $j=$i-$k+1;
			}*/
			 //if($examType == $studentSubjectArray[$i]['examType'] )
			 //{
			   //   $studentSubjectArray[$i]['examType']="";

			 //}
			 if ($studentSubjectArray[$i]['obtained']=='Not MOC'){
				$marksObtained="0.00";

			}
			 if ($studentSubjectArray[$i]['obtained']=='A'){
				$marksObtained="0.00";

			}


			 $marksObtained = number_format($marksObtained, 2, '.', '');
			 $marksPrintArray[$i]['srNo']   =$j;
			 $marksPrintArray[$i]['subject']=$studentSubjectArray[$i]['subject'];
			 $marksPrintArray[$i]['groupName']=$studentSubjectArray[$i]['groupName'];
			 $marksPrintArray[$i]['examType']=$studentSubjectArray[$i]['examType'];
			 $marksPrintArray[$i]['testDate']=$studentSubjectArray[$i]['testDate'];
			 $marksPrintArray[$i]['periodName']=$studentSubjectArray[$i]['periodName'];
			 $marksPrintArray[$i]['employeeName']=$studentSubjectArray[$i]['employeeName'];
			 $marksPrintArray[$i]['testName']=$studentSubjectArray[$i]['testName'];
			 $marksPrintArray[$i]['totalMarks']=$studentSubjectArray[$i]['totalMarks'];
			 $marksPrintArray[$i]['obtained']=$studentSubjectArray[$i]['obtained'];
			 $marksPrintArray[$i]['Percentage']=$marksObtained;

			if($subjectName1 != "")
				$subjectName = $subjectName1;

			 if($studentSubjectArray[$i]['examType'] != "" )
			 {
				  $examType=$studentSubjectArray[$i]['examType'];
			 }
			 $valueArray[] = array_merge(array('srNo' => $i+1),$marksPrintArray[$i]);
		}
	}

		$csvData = '';
		$flag=0;
		$csvData .= "#, Subject, Group, Test Type, Test Date, Study Period, Teacher Name, Test, M.M, M.S,%age  \n";
			foreach($valueArray as $record) {
			$csvData .= $record['srNo'].','.$record['subject'].','.$record['groupName'].','.$record['examType'].','.$record['testDate'].','.$record['periodName'].','.$record['employeeName'].','.$record['testName'].','.$record['totalMarks'].','.$record['obtained'].','.$record['Percentage'];
			$csvData .= "\n";
			$flag=1;

	}
	if($falg==0){
		$csvData .= "No Data Found";
	}

ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="StudentMarksReport.csv"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;
die;

   /* $className=$studentManager -> getClassName();
	$rollNo = $className[0]['rollNo'];
    $className2=str_replace('-'," ",$className[0]['className']) ;
	$studentArray = $commonQueryManager->getScAttendance("WHERE scs.studentId='$studentId' ");
	$name = $studentArray[0]['studentName'];

    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Student Marks Report ');
    $reportManager->setReportInformation("$name <br> $rollNo, $className2");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align
    $reportTableHead['srNo']                =    array('#',                'width="4%" align="center"', "align='center' ");
    $reportTableHead['subjectName']         =    array('Subject ',         ' width=15% align="center" ','align="left" ');
    $reportTableHead['examType']			=    array('Test Type',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['testDate']			=    array('Test Date',        ' width="15%" align="center" ','align="center"');
	$reportTableHead['employeeName']		=    array('Teacher Name',     ' width="15%" align="center" ','align="center"');
    $reportTableHead['testName']            =    array('Test Name',        'width="12%" align="center"','align="center"');


    $reportTableHead['totalMarks']          =    array('Max. Marks',            'width="10%" align="right"','align="right"');
    $reportTableHead['obtained']            =    array('Marks Scored',         'width="12%" align="right"','align="right"');
    $reportTableHead['Percentage']          =    array('Percentage',            'width="10%" align="right"','align="right"');


    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $marksPrintArray);
    $reportManager->showReport(); */

//$History : $
?>