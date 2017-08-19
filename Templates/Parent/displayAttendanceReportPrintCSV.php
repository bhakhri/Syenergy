 <?php
//This file is used as printing version for display attendance report in parent module.
//
// Author :Arvind Singh Rawat
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifParentNotLoggedIn();
    UtilityManager::headerNoCache();


   require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
	 require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subject';

    $orderBy = " $sortField $sortOrderBy";

    $studentId=$sessionHandler->getSessionVariable('StudentId');
    if($studentId=='') {
      $studentId=0;
    }

    $studentName = $REQUEST_DATA['studentName'];
    $studentLName= $REQUEST_DATA['studentLName'];
    $fromDate = $REQUEST_DATA['startDate2'];
    $toDate = $REQUEST_DATA['endDate2'];

    $classId = $REQUEST_DATA['classId'];

    if($fromDate) {
        $where = " AND fromDate BETWEEN '$fromDate' AND '$toDate'";
    }
    if($toDate) {
        $where .= " AND toDate BETWEEN '$fromDate' AND '$toDate'";
    }


    if($where != "") {
      $where .= " AND su.hasAttendance = 1 ";
      if($REQUEST_DATA['consolidatedView']==0){ //if consolidated display is required
         $totalRecordArray = CommonQueryManager::getInstance()->countConsolidatedStudentAttendance($studentId,$classId,$where);
         $totalRecord = count($totalRecordArray);
         $recordArray = CommonQueryManager::getInstance()->getConsolidatedStudentAttendance($studentId,$classId,$limit,$where,"ORDER BY $orderBy");
      }
     else{ //if group wise display is required
         $totalRecordArray = CommonQueryManager::getInstance()->countStudentAttendance($studentId,$classId,$where);
         $totalRecord = count($totalRecordArray);
         $recordArray = CommonQueryManager::getInstance()->getStudentAttendance($studentId,$classId,$limit,$where,"$orderBy");
     }
    }
    else {
      $where .= " AND su.hasAttendance = 1 ";
     if($REQUEST_DATA['consolidatedView']==0){ //if consolidated display is required
         $totalRecordArray = CommonQueryManager::getInstance()->countConsolidatedStudentAttendance($studentId,$classId,$where);
         $totalRecord = count($totalRecordArray);
         $recordArray = CommonQueryManager::getInstance()->getConsolidatedStudentAttendance($studentId,$classId,$limit,$where,"ORDER BY $orderBy");
     }
     else{//if group wise display is required
         $totalRecordArray = CommonQueryManager::getInstance()->countStudentAttendance($studentId,$classId,$where);
         $totalRecord = count($totalRecordArray);
         $recordArray = CommonQueryManager::getInstance()->getStudentAttendance($studentId,$classId,$limit,$where,"$orderBy");
     }
    }

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $recordCount = count($recordArray);

    $valueArray = array();

    $studentName = $sessionHandler->getSessionVariable('StudentName');

    $csvData ='';
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $csvData = "For, $studentName,$className,As On, $formattedDate";
    $csvData .="\n";

    if($REQUEST_DATA['consolidatedView']==1){
        $csvData .="#,Subject,Study Period,Group,Teacher,From,To,Del.,Att.,DL,Percentage ";
    }
    else {
        $csvData .="#,Subject,Study Period,From,To,Delivered,Attended,Leaves Taken,Percentage ";
    }
    $csvData .="\n";

    for($i=0;$i<$recordCount;$i++) {
        if ($recordArray[$i]['studentName'] != '-1') {
            $marksObtained = "0.00";
        }
        else {
            $marksObtained = NOT_APPLICABLE_STRING;
        }


        if($recordArray[$i]['delivered'] > 0 ) {
            if ($recordArray[$i]['dutyLeave'] != '') {
                $recordArray[$i]['attended1'] = "".$recordArray[$i]['attended'] + $recordArray[$i]['dutyLeave']."";
                $marksObtained =  ($recordArray[$i]['attended1']/$recordArray[$i]['delivered'])*100;
                $marksObtained = number_format($marksObtained, 2, '.', ' ');
            }
            else {
                $marksObtained =  ($recordArray[$i]['attended']/$recordArray[$i]['delivered'])*100;
                $marksObtained = number_format($marksObtained, 2, '.', ' ');
            }
        }

        if ($recordArray[$i]['dutyLeave'] == 'null' || $recordArray[$i]['dutyLeave'] == '') {
            $recordArray[$i]['dutyLeave'] = NOT_APPLICABLE_STRING;
        }

        if($recordArray[$i]['studentName'] != '-1') {
            $recordArray[$i]['fromDate'] = UtilityManager::formatDate($recordArray[$i]['fromDate']);
            $recordArray[$i]['toDate'] = UtilityManager::formatDate($recordArray[$i]['toDate']);
        }
        if($REQUEST_DATA['consolidatedView']==1){
             $csvData .= ($i+1).",";
             $csvData .= $recordArray[$i]['subject'].",";
             $csvData .= $recordArray[$i]['periodName'].",";
             $csvData .= $recordArray[$i]['groupName'].",";
             $csvData .= $recordArray[$i]['employeeName'].",";
             $csvData .= $recordArray[$i]['fromDate'].",";
             $csvData .= $recordArray[$i]['toDate'].",";
             $csvData .= $recordArray[$i]['delivered'].",";
             $csvData .= $recordArray[$i]['attended'].",";
             $csvData .= $recordArray[$i]['dutyLeave'].",";
             $csvData .= $marksObtained.",";
             $csvData .= "\n";
        }
        else {
            $csvData .= ($i+1).",";
            $csvData .= $recordArray[$i]['subject'].",";
            $csvData .= $recordArray[$i]['periodName'].",";
            $csvData .= $recordArray[$i]['fromDate'].",";
            $csvData .= $recordArray[$i]['toDate'].",";
            $csvData .= $recordArray[$i]['delivered'].",";
            $csvData .= $recordArray[$i]['attended'].",";
            $csvData .= $recordArray[$i]['dutyLeave'].",";
            $csvData .= $marksObtained.",";
            $csvData .= "\n";
        }
  }

 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'StudentAttendanceReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;
die;
//$History : $
?>
