<?php
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	require_once(MODEL_PATH . "/CleaningRoomManager.inc.php");
	$cleaningRoomManager = CleaningRoomManager::getInstance();


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'et.tempEmployeeName';
    
     $orderBy = "$sortField $sortOrderBy"; 

	$tempEmployeeId = $REQUEST_DATA['tempEmployee'];
	$hostelId = $REQUEST_DATA['hostel'];
	$startDate = $REQUEST_DATA['startDate'];
	$toDate = $REQUEST_DATA['toDate'];
	  function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return chr(160).$comments; 
         }
    }

	if ($hostelId == '' && $tempEmployeeId == '') {
		$conditions = "AND Dated BETWEEN '$startDate' AND '$toDate'";
	//	$totalArray = $cleaningRoomManager->getTotalCleaningHistoryDetail($conditions);
		$cleaningRoomRecordArray = $cleaningRoomManager->getCleaningHistoryList($conditions,$orderBy);
		$cnt = count($cleaningRoomRecordArray);	
	}
	if ($tempEmployeeId != '' && $hostelId == '' ) {
		$conditions = "	AND Dated BETWEEN '$startDate' AND '$toDate'
						AND hcr.tempEmployeeId IN($tempEmployeeId)";
		//$totalArray = $cleaningRoomManager->getTotalCleaningHistoryDetail($conditions);
		$cleaningRoomRecordArray = $cleaningRoomManager->getCleaningHistoryList($conditions,$orderBy);
		$cnt = count($cleaningRoomRecordArray);	
	}

	if ($tempEmployeeId != '' && $hostelId != '' ) {
		$conditions = "	AND Dated BETWEEN '$startDate' AND '$toDate'
						AND hcr.tempEmployeeId IN($tempEmployeeId)
						AND hcr.hostelId IN($hostelId)";
		//$totalArray = $cleaningRoomManager->getTotalCleaningHistoryDetail($conditions);
		$cleaningRoomRecordArray = $cleaningRoomManager->getCleaningHistoryList($conditions,$orderBy);
		$cnt = count($cleaningRoomRecordArray);	
	}

	if ($tempEmployeeId == '' && $hostelId != '' ) {
		$conditions = "	AND Dated BETWEEN '$startDate' AND '$toDate'
						AND hcr.hostelId IN($hostelId)";
		$totalArray = $cleaningRoomManager->getTotalCleaningHistoryDetail($conditions);
		$cleaningRoomRecordArray = $cleaningRoomManager->getCleaningHistoryList($conditions,$orderBy);
		$cnt = count($cleaningRoomRecordArray);	
	}
	$valueArray = array();
 $reportHead  = "<b>Safaiwala</b>:&nbsp;".$REQUEST_DATA['employeeText'];
    $reportHead .= "&nbsp;&nbsp;<b>Hostel</b>:&nbsp;".$REQUEST_DATA['hostelText']."<br>";  
    $reportHead .= "<b>Date </b>BETWEEN:&nbsp;".UtilityManager::formatDate($REQUEST_DATA['startDate'])."&nbsp;&nbsp;AND&nbsp;&nbsp;".UtilityManager::formatDate($REQUEST_DATA['toDate']);  

    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
//        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$reportRecordArray[$i]);
		$cleaningRoomRecordArray[$i]['Dated'] = UtilityManager::FormatDate($cleaningRoomRecordArray[$i]['Dated']);
        $valueArray[] = array(	'srNo' => $i+1 ,
								'tempEmployeeName' => $cleaningRoomRecordArray[$i]['tempEmployeeName'],
								'hostelName' => $cleaningRoomRecordArray[$i]['hostelName'],
								'Dated' => $cleaningRoomRecordArray[$i]['Dated'],
								'toiletsCleaned' => $cleaningRoomRecordArray[$i]['toiletsCleaned'],
								'noOfRoomsCleaned' => $cleaningRoomRecordArray[$i]['noOfRoomsCleaned'],
								'attachedRoomToiletsCleaned' => $cleaningRoomRecordArray[$i]['attachedRoomToiletsCleaned'],
								'dryMoppingInSqMeter' => $cleaningRoomRecordArray[$i]['dryMoppingInSqMeter'],
								'wetMoppingInSqMeter' => $cleaningRoomRecordArray[$i]['wetMoppingInSqMeter'],
								'roadCleanedInSqMeter' => $cleaningRoomRecordArray[$i]['roadCleanedInSqMeter'],
								'noOfGarbageBinsDisposal' => $cleaningRoomRecordArray[$i]['noOfGarbageBinsDisposal'],
								'noOfHoursWorked' => $cleaningRoomRecordArray[$i]['noOfHoursWorked']
							);
   }
   $csvData = '';
    $reportHead  = "Safaiwala,".$REQUEST_DATA['employeeText'];
    $reportHead .= ",Hostel,".$REQUEST_DATA['hostelText']."\n";
    $reportHead .= "Date:, BETWEEN,".UtilityManager::formatDate($REQUEST_DATA['startDate']).",AND,".UtilityManager::formatDate($REQUEST_DATA['toDate'])."\n";
    $csvData .= $reportHead;
    $csvData .= "#, Employee Name, Hostel Name, Date, Toilet(s) Cleaned, Room(s) Cleaned, Attached Room Toilet(s) Cleaned , Dry Mopping, Wet Mopping,Road cleaned,Garbage Disposal,No. of hours \n";
	$find=0;
    foreach($valueArray as $record) {
		$find=1;
        $csvData .= $record['srNo'].','.$record['tempEmployeeName'].','.$record['hostelName'].','.$record['Dated'].','.$record['toiletsCleaned'].','.  
        $record['noOfRoomsCleaned'].','.$record['attachedRoomToiletsCleaned'].','.$record['dryMoppingInSqMeter'].','.$record['wetMoppingInSqMeter'].',
		'.$record['roadCleanedInSqMeter'].','.$record['noOfGarbageBinsDisposal'].'.'.$record['noOfHoursWorked'];
        $csvData .= "\n";
    }
	   if($find==0) {
		$csvData .= ",,,No Data Found";
	}
   UtilityManager::makeCSV($csvData,'CleaningHistoryReport.csv');
    
    die;
 