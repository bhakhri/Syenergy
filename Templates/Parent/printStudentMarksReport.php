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

    $studentSubjectArray = $studentManager->getStudentMarks($studentId,$classId,"",$orderBy); 
    
			$recordCount = count($studentSubjectArray);
			$j=0;
			$k=0;
			$marksPrintArray[] =  Array();
			if($recordCount >0 && is_array($studentSubjectArray) ) { 
					$subjectName = ""; 
					$examType="";    
					for($i=0; $i<$recordCount; $i++ ) {
						
						$bg = $bg =='row0' ? 'row1' : 'row0';
						$marksObtained =  ($studentSubjectArray[$i]['obtained']/$studentSubjectArray[$i]['totalMarks']);
					   
						$j=$i+1;
						$marksObtained=$marksObtained*100;
						
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
				}
			}
                           
    $className=$studentManager -> getClassName();
	$rollNo = $className[0]['rollNo'];
    $className2=str_replace('-'," ",$className[0]['className']) ;
	$studentArray = $commonQueryManager->getAttendance("WHERE s.studentId='$studentId' ");     
	$name = $studentArray[0]['studentName'];
	    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Student Marks Report ');
    $reportManager->setReportInformation("$name <br> $rollNo, $className2");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']                =    array('#',                'width="3%" align="center"', "align='center' ");
    $reportTableHead['subject']				=    array('Subject ',         ' width=14% align="center" ','align="left" ');
	$reportTableHead['groupName']			=    array('Group Name ',      ' width=12% align="center" ','align="left" ');
    $reportTableHead['examType']			=    array('Test Type',        ' width="12%" align="left" ','align="left"');
	$reportTableHead['testDate']			=    array('Test Date',        ' width="8%" align="center" ','align="center"');
	$reportTableHead['periodName']			=    array('Study Period',     ' width="10%" align="center" ','align="center"');
	$reportTableHead['employeeName']		=    array('Teacher Name',     ' width="14%" align="left" ','align="left"');
    $reportTableHead['testName']            =    array('Test Name',        'width="6%" align="center"','align="center"');
    $reportTableHead['totalMarks']          =    array('Max. Marks',       'width="8%" align="right"','align="right"');
    $reportTableHead['obtained']            =    array('Marks Scored',     'width="8%" align="right"','align="right"'); 
    $reportTableHead['Percentage']          =    array('%age',            'width="8%" align="right"','align="right"');  


    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $marksPrintArray);
    $reportManager->showReport(); 

//$History : $
?>