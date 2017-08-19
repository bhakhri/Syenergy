 <?php 
//This file is used as printing version for display attendance report in parent module.
//
// Author :Jaineesh
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
    global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentManager = StudentInformationManager::getInstance();
    
    $fromDate=$REQUEST_DATA['startDate'];    
    $toDate=$REQUEST_DATA['toDate'];

	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
	$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
		
	$orderBy = " $sortField $sortOrderBy";
	  
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance(); 

	$studentId = $sessionHandler->getSessionVariable('StudentId'); 

	$classId = $REQUEST_DATA['studyPeriodId'];

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
		if($studentAttendanceArray[$i]['delivered'] > 0 ) {
			if ($studentAttendanceArray[$i]['dutyLeave'] != '') {
				$studentAttendanceArray[$i]['attended1'] = "".$studentAttendanceArray[$i]['attended'] + $studentAttendanceArray[$i]['dutyLeave']."";				$studentAttendanceArray[$i]['Percentage']="".ROUND((($studentAttendanceArray[$i]['attended1'] / $studentAttendanceArray[$i]['delivered'])*100),2)."";
			}
			else {
				$studentAttendanceArray[$i]['Percentage']="".ROUND((($studentAttendanceArray[$i]['attended'] / $studentAttendanceArray[$i]['delivered'])*100),2)."";
			}
		}

		if ($studentAttendanceArray[$i]['dutyLeave'] == 'null' || $studentAttendanceArray[$i]['dutyLeave'] == '') {
			$studentAttendanceArray[$i]['dutyLeave'] = NOT_APPLICABLE_STRING;
		}
		
		$valueArray[] = array_merge(array('srNo' => $i+1),$studentAttendanceArray[$i]);
    }

	$csvData = '';
    
    if($REQUEST_DATA['consolidatedView']==0){ //if consolidated display is required
	 $csvData .= "Sr No., Class, Subject, Study Period, From, To, Delivered, Attended, Leaves Taken, Percentage \n";
	 foreach($valueArray as $record) {
		$csvData .= $record['srNo'].','.$record['className'].','.$record['subject'].','.$record['periodName'].','.$record['fromDate'].','.$record['toDate'].','.$record['delivered'].','.$record['attended'].','.$record['dutyLeave'].','.$record['Percentage'];
		$csvData .= "\n";
	 }
    }
    else{
       $csvData .= "Sr No., Class, Subject, GroupName, Study Period, Teacher Name, From, To, Del., Att., Duty Leave, Percentage \n";
       foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.$record['className'].','.$record['subject'].','.$record['groupName'].','.$record['periodName'].','.$record['employeeName'].','.$record['fromDate'].','.$record['toDate'].','.$record['delivered'].','.$record['attended'].','.$record['dutyLeave'].','.$record['Percentage'];
        $csvData .= "\n";
      }
    }
	
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="studentReport.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;
	
//$History : $
?>
