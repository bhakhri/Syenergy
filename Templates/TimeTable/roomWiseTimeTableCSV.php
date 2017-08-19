<?php
//--------------------------------------------------------  
//It contains the time table
//
// Author :Parveen Sharma
// Created on : 07-08-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','DisplayRoomTimeTable');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(); 

    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timeTableManager = TimeTableManager::getInstance();
    require_once($FE . "/Library/HtmlFunctions.inc.php"); 
    $htmlFunctionsManager = HtmlFunctions::getInstance();  

    global $sessionHandler;
    $timetableFormat = $sessionHandler->getSessionVariable('TIMETABLE_FORMAT');

    $orderBy =($timetableFormat == 1) ? " ORDER BY tt.daysOfWeek, LENGTH(p.periodNumber)+0,p.periodNumber" : " ORDER BY LENGTH(p.periodNumber)+0,p.periodNumber, tt.daysOfWeek";
    
    //Get the time table date according to class selected
    $conditions=($REQUEST_DATA['roomId']!=0 ? " AND r.roomId=".$REQUEST_DATA['roomId'] : "");
    $conditions .=($REQUEST_DATA['labelId']!='' ? " AND tt.timeTableLabelId=".$REQUEST_DATA['labelId'] : "");

    $studentRecordArray = $timeTableManager->getRoomTimeTable($conditions,$orderBy);
    $periodArray = $timeTableManager->getTimeTablePeriodList(' tt.timeTableLabelId = '.$REQUEST_DATA['labelId']);  
    
    $roomName = $REQUEST_DATA['roomName'];
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
    
    $csvData = "For ".$roomName." As On $formattedDate ";     
    $csvData .= "\n";
    
    $recordCount = count($studentRecordArray);
    if($recordCount >0 && is_array($studentRecordArray)) {     
       if($timetableFormat=='1') {
           $csvData .= $htmlFunctionsManager->showTimeTablePeriodsColumnsCSV($studentRecordArray,$periodArray);
           $csvData .= " \n\n\n ";
       }
       else 
       if($timetableFormat=='2') {
         $csvData .= $htmlFunctionsManager->showTimeTablePeriodsRowsCSV($studentRecordArray,$periodArray);
         $csvData .= " \n\n\n ";
       }
    }

ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;filename="TimeTableReport.csv"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;
die;      

//$History : $
?>
