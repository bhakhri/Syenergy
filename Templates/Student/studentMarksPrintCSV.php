 <?php 
//This file is used to save student marks in csv format.
//
// Author :Jaineesh
// Created on : 24-09-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance(); 
    $studentId = $sessionHandler->getSessionVariable('StudentId');

	$studentId    = $REQUEST_DATA['studentId'];
	$studentName  = $REQUEST_DATA['studentName'];
	$studentLName = $REQUEST_DATA['studentLName'];
	$classId	  = $REQUEST_DATA['classId'];
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectName';
	$className	  = $REQUEST_DATA['className'];


    $orderBy = " $sortField $sortOrderBy";

    $studentSubjectArray = $studentManager->getStudentMarks($studentId,$classId,$orderBy);
                           
	$recordCount = count($studentSubjectArray);
	$j=0;
	$k=0;
	$marksPrintArray[] =  Array();
	if($recordCount >0 && is_array($studentSubjectArray) ) { 
		$subjectName = ""; 
		for($i=0; $i<$recordCount; $i++ ) {
			 $studentSubjectArray[$i]['testDate'] = UtilityManager::formatDate($studentSubjectArray[$i]['testDate']);
			
			 $marksPrintArray[$i]['srNo']   =$i+1;
			 $marksPrintArray[$i]['subjectName']=$studentSubjectArray[$i]['subjectName'];
			 $marksPrintArray[$i]['groupName']=$studentSubjectArray[$i]['testTypeName'];
			 $marksPrintArray[$i]['examType']=$studentSubjectArray[$i]['examType'];  
			 $marksPrintArray[$i]['testDate']=$studentSubjectArray[$i]['testDate'];  
			 $marksPrintArray[$i]['employeeName']=$studentSubjectArray[$i]['employeeName'];
			 $marksPrintArray[$i]['studyPeriod']=$studentSubjectArray[$i]['studyPeriod'];
			 $marksPrintArray[$i]['testName']=$studentSubjectArray[$i]['testName'];  
			 $marksPrintArray[$i]['totalMarks']=$studentSubjectArray[$i]['totalMarks'];  
			 $marksPrintArray[$i]['obtainedMarks']=$studentSubjectArray[$i]['obtainedMarks']; 
			 
			/*if($subjectName1 != "")
				$subjectName = $subjectName1;
			
			 if($studentSubjectArray[$i]['examType'] != "" )
			 {
				  $examType=$studentSubjectArray[$i]['examType'];
			 }*/
			 $valueArray[] = array_merge(array('srNo' => $i+1),$marksPrintArray[$i]);
		}
	}

		$csvData = '';
		$csvData .= "#, Subject, Type, Date, Teacher, Study Period, Test Name, Max. Marks, Marks Scored\n";
		foreach($valueArray as $record) {
		 $csvData .= $record['srNo'].','.$record['subjectName'].','.$record['groupName'].','.$record['examType'].','.$record['testDate'].','.$record['employeeName'].','.$record['studyPeriod'].','.$record['testName'].','.$record['totalMarks'].','.$record['obtainedMarks'];
		 $csvData .= "\n";
	    }
        
        if(count($valueArray)==0){
            $csvData .= " ,".NO_DATA_FOUND."\n";
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