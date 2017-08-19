 <?php 
//This file is used as printing version for display attendance report in parent module.
//
// Author :Jaineesh
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentManager = StudentInformationManager::getInstance();
    
    $fromDate=$REQUEST_DATA['startDate'];    
    $toDate=$REQUEST_DATA['toDate'];
	
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
	$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
		
	$orderBy = " $sortField $sortOrderBy";
	
	$studentId = $sessionHandler->getSessionVariable('StudentId');

	$classId = $REQUEST_DATA['studyPeriodId'];
	  
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance(); 

    if($fromDate) {
			$where = " AND fromDate BETWEEN '$fromDate' AND '$toDate'";
	}
	if($toDate) {
			$where .= " AND toDate BETWEEN '$fromDate' AND '$toDate'";
	}

	
	if ($where != "") {
       $where .= " AND su.hasAttendance = 1 ";
       if($REQUEST_DATA['consolidatedView']==0){ //if consolidated display is required 
        $studentAttendanceArray = CommonQueryManager::getInstance()->getConsolidatedStudentAttendance($studentId,$classId,$limit,$where,"order by $orderBy");
       }
       else{
           $studentAttendanceArray = CommonQueryManager::getInstance()->getStudentAttendance($studentId,$classId,$limit,$where,"$orderBy");
       }
    }
    else {      
       $where .= " AND su.hasAttendance = 1 ";
       if($REQUEST_DATA['consolidatedView']==0){ //if consolidated display is required  
        $studentAttendanceArray = CommonQueryManager::getInstance()->getConsolidatedStudentAttendance($studentId,$classId,$limit,"","order by $orderBy");           
       }
       else{
           $studentAttendanceArray = CommonQueryManager::getInstance()->getStudentAttendance($studentId,$classId,$limit,"","$orderBy");           
       }
    }   
     
    $cnt=count($studentAttendanceArray);
    $valueArray = array();
    
    for($i=0;$i<$cnt;$i++) {
		
		if ($studentAttendanceArray[$i]['studentName'] != '-1') {
			$studentAttendanceArray[$i]['Percentage'] = "0.00";
		}
		else {
			$studentAttendanceArray[$i]['Percentage'] = NOT_APPLICABLE_STRING;
		}


		if($studentAttendanceArray[$i]['studentName'] != '-1') {
			$studentAttendanceArray[$i]['fromDate'] = UtilityManager::formatDate($studentAttendanceArray[$i]['fromDate']);	
			$studentAttendanceArray[$i]['toDate'] = UtilityManager::formatDate($studentAttendanceArray[$i]['toDate']);
		}

        if( $studentAttendanceArray[$i]['delivered'] > 0 ) {
			if ($studentAttendanceArray[$i]['dutyLeave'] != '') {
				$studentAttendanceArray[$i]['attended1'] = "".$studentAttendanceArray[$i]['attended'] + $studentAttendanceArray[$i]['dutyLeave']."";
				$studentAttendanceArray[$i]['Percentage']="".ROUND((($studentAttendanceArray[$i]['attended1'] / $studentAttendanceArray[$i]['delivered'])*100),2)."";
			}
			else {
				$studentAttendanceArray[$i]['Percentage']="".ROUND((($studentAttendanceArray[$i]['attended'] / $studentAttendanceArray[$i]['delivered'])*100),2)."";
			}
		}

		if ($studentAttendanceArray[$i]['dutyLeave'] == 'null' || $studentAttendanceArray[$i]['dutyLeave'] == '') {
			$studentAttendanceArray[$i]['dutyLeave'] = NOT_APPLICABLE_STRING;
		}

        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$studentAttendanceArray[$i]);
   }

	$className=$studentManager -> getClassName();
	$rollNo = $className[0]['rollNo'];
	$className2=str_replace('-'," ",$className[0]['className']) ;

	$name = $studentAttendanceArray[0]['studentName'];

	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Student Attendance Report ');
	if ($name != '' && $rollNo != '' && $className2 != '') {
		$reportManager->setReportInformation("$name <br> $rollNo, $className2 ");
	}
	

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align 
    $reportTableHead['srNo']                =    array('#',                  'width="4%" align="left"', "align='left' ");
    if($REQUEST_DATA['consolidatedView']==0){ //if consolidated display is required
	    $reportTableHead['className']           =    array('Class',              'width="10%" align="left"', "align='left' ");
	    $reportTableHead['subject']		        =    array('Subject',            ' width=14% align="left" ','align="left" ');
	    $reportTableHead['periodName']			=    array('Study Period',            ' width=12% align="left" ','align="left" ');
	    $reportTableHead['fromDate']			=    array('From',      ' width=8% align="center" ','align="center" ');
	    $reportTableHead['toDate']				=	 array('To',      ' width=8% align="center" ','align="center" ');
        $reportTableHead['delivered']			=    array('Del.',        ' width="8%" align="right" ','align="right"');
        $reportTableHead['attended']            =    array('Att.',        'width="9%" align="right"','align="right"');
        $reportTableHead['dutyLeave']         =    array('Leaves Taken',            'width="10%" align="right"','align="right"');
		$reportTableHead['Percentage']          =    array('Percentage',            'width="10%" align="right"','align="right"');
		
    }
   else{
        $reportTableHead['className']           =    array('Class',              'width="10%" align="left"', "align='left' ");
        $reportTableHead['subject']             =    array('Subject',            ' width=12% align="left" ','align="left" ');
        $reportTableHead['groupName']           =    array('Group Name',            ' width=10% align="left" ','align="left" ');
        $reportTableHead['periodName']          =    array('Study Period',            ' width=12% align="left" ','align="left" ');
        $reportTableHead['employeeName']        =    array('Teacher Name',      ' width=14% align="left" ','align="left" ');
        $reportTableHead['fromDate']            =    array('From',      ' width=8% align="center" ','align="center" ');
        $reportTableHead['toDate']              =     array('To',      ' width=8% align="center" ','align="center" ');
        $reportTableHead['delivered']           =    array('Del.',        ' width="6%" align="right" ','align="right"');
        $reportTableHead['attended']            =    array('Att.',        'width="6%" align="right"','align="right"');
		$reportTableHead['dutyLeave']           =    array('DL',        'width="6%" align="right"','align="right"');
        $reportTableHead['Percentage']          =    array('Percentage',            'width="10%" align="right"','align="right"');
   } 
    


    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();

//$History : $
?>