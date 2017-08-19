<?php 
//This file is used as Excel Version for testwise marks report (Backup).
//
// Author :Ankur Aggarwal
// Created on : 18-Aug-2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
        ini_set('MEMORY_LIMIT','5000M'); 
    	set_time_limit(0);  
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
  	define('MODULE','COMMON');
   	define('ACCESS','view');
   	define('MANAGEMENT_ACCESS',1);
	UtilityManager::ifNotLoggedIn(true);
   	UtilityManager::headerNoCache();
        require_once(TEMPLATES_PATH . "/StudentReports/PHPExcel.php");
        require_once(TEMPLATES_PATH . "/StudentReports/PHPExcel/IOFactory.php");
  	//require_once 'PHPExcel.php';
    //    require_once 'PHPExcel/IOFactory.php';
        $objPHPExcel = new PHPExcel();
	

 	global $sessionHandler;
	$userName = $sessionHandler->getSessionVariable('UserName');

	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	
	$labelId = trim($REQUEST_DATA['timeTable']);
	$classId = trim($REQUEST_DATA['degree']); 

	$subjectId = trim($REQUEST_DATA['subjectId']);

	$internal = $REQUEST_DATA['internal'];
	$attendance = $REQUEST_DATA['attendance'];
	$external = $REQUEST_DATA['external'];
	$total = $REQUEST_DATA['total'];


	if (empty($labelId) or empty($classId) or empty($subjectId)) {
		echo 'Required parameters missing1';
		die;
	}
	if (empty($internal) and empty($attendance) and empty($external) and empty($total)) {
		echo 'Required parameters missing2';
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
        $cnt = count($studentRecordArray);
	$studentIdList = UtilityManager::makeCSList($studentArray, 'studentId');
	if (empty($studentIdList)) {
		$studentIdList = 0;
	}

	$subjectArray = $studentReportsManager->getSingleField("subject", "subjectId, subjectCode", "where subjectId in ($subjectId) order by subjectCode");


	$maxMarksArray = array();

	//$subjectArray = $studentReportsManager->getSingleField("subject", "subjectId, subjectCode", " where subjectId in ($subjectId)");
	$subjectCodeArray = array();
	foreach($subjectArray as $record) {
		$subjectCodeArray[$record['subjectId']] = $record['subjectCode'];
	}


	$dataArray = array();
	$studentStatusArray = array();
	if ($internal == 'on') {
		$subMaxMarksArray = $studentReportsManager->getInternalMaxMarks($classId, $subjectId);
		foreach($subMaxMarksArray as $record) {
			$maxMarksArray[$record['subjectId']]['I'] = round($record['maxMarks'],1);
		}
		$internalArray = $studentReportsManager->getInternalMarks($studentIdList, $classId, $subjectId);
		foreach($internalArray as $record) {
			$studentId = $record['studentId'];
			$subjectIdNew = $record['subjectId'];
			$dataArray[$studentId][$subjectIdNew]['I'] = round($record['marksScored'],1);
			if ($maxMarksArray[$subjectId]['I'] > 0) {
				$percent = (($dataArray[$studentId][$subjectIdNew]['I'] * 100) / $maxMarksArray[$subjectIdNew]['I']);
				if ($percent < 40) {
					$studentStatusArray[$studentId][$subjectIdNew] = 'RTI ' . $subjectCodeArray[$subjectIdNew];
				}
			}
		}
	}
	if ($attendance == 'on') {
		$internalArray = $studentReportsManager->getAttendance($studentIdList, $classId, $subjectId);
		foreach($internalArray as $record) {
			$dataArray[$record['studentId']][$record['subjectId']]['A'] = '<u>'.$record['lectureAttended'] .'</u><br>' . $record['lectureDelivered'];
		}
	}
	if ($external == 'on') {
		$subMaxMarksArray = $studentReportsManager->getExternalMaxMarks($classId, $subjectId);
		foreach($subMaxMarksArray as $record) {
			$maxMarksArray[$record['subjectId']]['E'] = round($record['maxMarks'],1);
		}
		$internalArray = $studentReportsManager->getExternalMarks($studentIdList, $classId, $subjectId);
		foreach($internalArray as $record) {
			$studentId = $record['studentId'];
			$subjectIdNew = $record['subjectId'];
			$dataArray[$studentId][$subjectIdNew]['E'] = $record['marksScored'] == '' ? 0 : round($record['marksScored'],1);
			if ($maxMarksArray[$subjectIdNew]['I'] > 0) {
				$percent = (($dataArray[$studentId][$subjectIdNew]['E'] * 100) / $maxMarksArray[$subjectIdNew]['E']);
				if ($percent < 40) {
					$studentStatusArray[$studentId][$subjectIdNew] = 'RP '. $subjectCodeArray[$subjectIdNew];
				}
			}
		}
	}
	if ($total == 'on') {
		$subMaxMarksArray = $studentReportsManager->getTotalMaxMarks($classId, $subjectId);
		foreach($subMaxMarksArray as $record) {
			$maxMarksArray[$record['subjectId']]['T'] = round($record['maxMarks'],1);
		}
		$internalArray = $studentReportsManager->getTotalStudentMarks($studentIdList, $classId, $subjectId);
		foreach($internalArray as $record) {
			$studentId = $record['studentId'];
			$subjectIdNew = $record['subjectId'];
			$dataArray[$studentId][$subjectIdNew]['T'] = round($record['marksScored'],1);
			$percent = (($dataArray[$studentId][$subjectIdNew]['T'] * 100) / $maxMarksArray[$subjectIdNew]['T']);
			if ($percent < 40) {
				$studentStatusArray[$studentId][$subjectIdNew] = 'RT '. $subjectCodeArray[$subjectIdNew];
			}
		}
	}

	$finalMaxMarksArray = $studentReportsManager->getFinalMaxMarks($classId, $subjectId);
	

	$studentFinalMarksArray = array();
	$finalMarksScoredArray = $studentReportsManager->getFinalMarksScored($studentIdList, $classId, $subjectId);
	$i = 0;
	foreach($finalMarksScoredArray as $record) {
		$studentId = $record['studentId'];
		$subjectIdNew = $record['subjectId'];
		$studentFinalMarksArray[$studentId] = round($record['marksScored'],1);
		if (isset($studentStatusArray[$studentId])) {
			$status = implode('<br>',$studentStatusArray[$studentId]);
			$finalMarksScoredArray[$i]['marksScored'] = $status;//$studentStatusArray[$studentId][$subjectIdNew];
			$studentFinalMarksArray[$studentId] = $status;
		}
		$i++;
	}


	$mainStudentArray = array();
	$i = 0;
	foreach($studentArray as $record) {
		$studentId = $record['studentId'];
		foreach($subjectArray as $subjectRecord) {
			$subjectIdNew = $subjectRecord['subjectId'];
			if ($internal != '') {
				$studentArray[$i][$subjectIdNew]['I'] = $dataArray[$studentId][$subjectIdNew]['I'] == '' ? 0 : $dataArray[$studentId][$subjectIdNew]['I'];
			}
			if ($attendance != '') {
				$studentArray[$i][$subjectIdNew]['A'] = $dataArray[$studentId][$subjectIdNew]['A']== '' ? '-' : $dataArray[$studentId][$subjectIdNew]['A'];
			}
			if ($external != '') {
				$studentArray[$i][$subjectIdNew]['E'] = $dataArray[$studentId][$subjectIdNew]['E'] == '' ? 0 : $dataArray[$studentId][$subjectIdNew]['E'];
			}
			if ($total != '') {
				$studentArray[$i][$subjectIdNew]['T'] = $dataArray[$studentId][$subjectIdNew]['T'] == '' ? 0 : $dataArray[$studentId][$subjectIdNew]['T'];
			}
			$studentArray[$i]['totalMarks'] = $studentFinalMarksArray[$studentId];
		}
		$i++;
	}

	$classNameArray = $studentReportsManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "WHERE classId  = $classId");
	$className = $classNameArray[0]['className'];

	$className2 = str_replace("-",' ',$className);

	$subCode = 'All';
	if ($subjectId != 'all') {
		$subCodeArray = $studentReportsManager->getSingleField('subject', 'subjectCode', "WHERE subjectId  In (".$REQUEST_DATA['subjectId'].")");
		foreach($subCodeArray as $record) {
			$subCodesArray[] = $record['subjectCode'];



	$allDetailsArray = array('userName'=>$userName, 'className'=>$className, 'subjectArray'=>$subjectArray, 'recordArray'=>$studentArray, 'totalStudents'=>$totalStudents, 					'maxMarks'=>$maxMarksArray, 'finalMaxMarksArray' => $finalMaxMarksArray);



	$ct=count($subjectArray);	
   
// Building Up The Sheet Using PHPExecl API

	for($j=0;$j<$ct;$j++)
	{
        	$subId=$subjectArray[$j]['subjectId'];
		$objPHPExcel->setActiveSheetIndex($j);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', "Class : ".$allDetailsArray['className']);
		$objPHPExcel->getActiveSheet()->setCellValue('A3', "Subject Code : ".$allDetailsArray['subjectArray'][$j]['subjectCode']);
		$objPHPExcel->getActiveSheet()->setCellValue('A5', "#");
		$objPHPExcel->getActiveSheet()->setCellValue('B5', "Roll No");
		$objPHPExcel->getActiveSheet()->setCellValue('C5', "Name");
		$objPHPExcel->getActiveSheet()->setCellValue('D5', "Father Name");
		$objPHPExcel->getActiveSheet()->setCellValue('E5', "Internal");
		$objPHPExcel->getActiveSheet()->setCellValue('F5', "Attendance");
		$objPHPExcel->getActiveSheet()->setCellValue('G5', "External");
		$objPHPExcel->getActiveSheet()->setCellValue('H5', "Total Marks");
		$objPHPExcel->getActiveSheet()->setCellValue('I5', "Reappear");

    		for($i=0;$i<$totalStudents;$i++) {
                	$objPHPExcel->getActiveSheet()->setCellValue('A' . ($i+6),($i+1));
			$objPHPExcel->getActiveSheet()->setCellValue('B' . ($i+6), strip_tags($allDetailsArray['recordArray'][$i]['universityRollNo']));
			$objPHPExcel->getActiveSheet()->setCellValue('C' . ($i+6), strip_tags($allDetailsArray['recordArray'][$i]['studentName']));
			$objPHPExcel->getActiveSheet()->setCellValue('D' . ($i+6), strip_tags($allDetailsArray['recordArray'][$i]['fatherName']));
			$objPHPExcel->getActiveSheet()->setCellValue('E' . ($i+6), strip_tags($allDetailsArray['recordArray'][$i][$subId]['I']));
			$objPHPExcel->getActiveSheet()->setCellValue('F' . ($i+6), strip_tags(str_replace("<br>","/",$allDetailsArray['recordArray'][$i][$subId]['A'])));
			$objPHPExcel->getActiveSheet()->setCellValue('G' . ($i+6), strip_tags($allDetailsArray['recordArray'][$i][$subId]['E']));
			$objPHPExcel->getActiveSheet()->setCellValue('H' . ($i+6), strip_tags($allDetailsArray['recordArray'][$i][$subId]['T']));
 			if(is_numeric($allDetailsArray['recordArray'][$i]['totalMarks'])){
			$objPHPExcel->getActiveSheet()->setCellValue('I' . ($i+6),"");
			}
			else{
			$reappear=explode("<br>",$allDetailsArray['recordArray'][$i]['totalMarks']);
                	         $key=array_search("RT ".$allDetailsArray['subjectArray'][$j]['subjectCode'], $reappear);
                	         if (strlen($key)>0)
				$objPHPExcel->getActiveSheet()->setCellValue('I' . ($i+6), strip_tags($reappear[$key]));
				else
				$objPHPExcel->getActiveSheet()->setCellValue('I' . ($i+6),"");
			}
		}
	    $objPHPExcel->getActiveSheet()->setCellValue('A' . ($i+8),"Backup By : ".$allDetailsArray['userName']);
            $objPHPExcel->getActiveSheet()->setCellValue('A' . ($i+9),"Date : ".date("d/m/y"));
            $objPHPExcel->getActiveSheet()->setTitle($allDetailsArray['subjectArray'][$j]['subjectCode']); 
            $objPHPExcel->createSheet();
       }
 

     ob_end_clean();  //cleaning the output buffer
     header('Content-Type: application/vnd.ms-excel');
     header('Content-Disposition: attachment;filename="backup.csv"');
     header('Cache-Control: max-age=0');
     $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
     ob_end_clean();
     $objWriter->save('php://output');  //output the file on the client server
     die();
   }

 }


?>
