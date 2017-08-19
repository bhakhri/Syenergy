 <?php
//This file is used as printing version for test type category.
//
// Author :Jaineesh
// Created on : 25.07.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineManager = FineManager::getInstance();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    
    define('MODULE','COMMON');
    define('ACCESS','view');
    global $sessionHandler; 
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
    UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
    UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();  

	/// Search filter /////
	$fineManager = FineManager::getInstance();
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
	$orderBy = " $sortField $sortOrderBy";

    $condition = '';

    $timeTableLabelId  = trim($REQUEST_DATA['timeTable']);
    $classId   = trim($REQUEST_DATA['classId']);
	$status	= $REQUEST_DATA['status'];
	$rollNo	= $REQUEST_DATA['rollNo'];
	$startDate	= $REQUEST_DATA['startDate'];
	$toDate	= $REQUEST_DATA['toDate'];
	$fineCategoryId	= $REQUEST_DATA['fineCategory'];

    if($timeTableLabelId=='') {
       $timeTableLabelId=0;
    }

    if($classId=='') {
      $classId=0;
    }

    if($classId!='all') {
       $condition .= " AND c.classId=".$classId;
    }


    $condition .=" AND ttc.timeTableLabelId = $timeTableLabelId AND fs.status= $status";

	if ($rollNo != "") {
		$condition .= " AND st.rollNo='".$rollNo."'";
	}

	if ($fineCategoryId != "") {
		$condition .= " AND fs.fineCategoryId=".$fineCategoryId."";
	}

    $reportHead ='';
	if ($startDate != "" && $toDate != "") {
		$condition .= " AND fs.fineDate BETWEEN '".$startDate."' AND '".$toDate."'";
        $reportHead = "Date:&nbsp;".UtilityManager::formatDate($startDate)."&nbsp;To&nbsp;&nbsp;".UtilityManager::formatDate($toDate);
	}

    $search ='';

    // Findout Class Name
    if($timeTableLabelId!='') {
       $classNameArray = $studentManager->getSingleField("time_table_labels", "labelName AS labelName", "WHERE timeTableLabelId  = $timeTableLabelId");
       $labelName = $classNameArray[0]['labelName'];
       $search .= "Time Table: ".$labelName."<br>";
    }

    if($classId!='all') {
       $classNameArray = $studentManager->getSingleField("class", "SUBSTRING_INDEX(className,'-',-3) AS className", "WHERE classId  = $classId");
       $className = $classNameArray[0]['className'];
       $className2 = str_replace("-",' ',$className);
       $search .= "Class: ".$className2."<br>";
    }

    if($rollNo!='') {
       $search .= "Roll No.: ".$rollNo."<br>";
    }

    if($fineCategoryId!='') {
       $classNameArray = $studentManager->getSingleField("fine_category", "fineCategoryAbbr AS abbr", "WHERE fineCategoryId  = $fineCategoryId");
       $abbr = $classNameArray[0]['abbr'];
       $search .= "Fine Category: ".$abbr."<br>";
    }

    if($reportHead!='') {
     $search .= $reportHead."<br>";
    }

    global $statusCategoryArr;
    $search .= "Fine Status: ".$statusCategoryArr[$status]."<br>";




	$studentFineArray = $fineManager->getStudentFineList($condition,$orderBy);
    $recordCount = count($studentFineArray);

    //$designationPrintArray[] =  Array();
	if($recordCount >0 && is_array($studentFineArray) ) {

		for($i=0; $i<$recordCount; $i++ ) {

            if ($studentFineArray[$i]['employeeName'] == NOT_APPLICABLE_STRING) {
               $studentFineArray[$i]['employeeName'] = 'Admin';
            }

			$bg = $bg =='row0' ? 'row1' : 'row0';
		    $studentFineArray[$i]['fineDate'] = UtilityManager::formatDate($studentFineArray[$i]['fineDate']);
			$valueArray[] = array_merge(array('srNo' => ($i+1) ),$studentFineArray[$i]);

		}
	}

    //$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
    //$search .= "As On ".$formattedDate;

    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Student Fine Approval Report');
	$reportManager->setReportInformation($search);

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align
    $reportTableHead['srNo']				=    array('#',                'width="4%" align="left"', "align='left'");
    $reportTableHead['rollNo']				=    array('Roll No.',         ' width=10% align="left" ','align="left" ');
    $reportTableHead['className']				=    array('Class',         ' width=10% align="left" ','align="left" ');
    $reportTableHead['studentName']			=    array('Name',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['fineCategoryName']	=    array('Fine Category',        'width="15%" align="left" ','align="left"');
	$reportTableHead['fineDate']			=    array('Date',        ' width="10%" align="center" ','align="center"');
	$reportTableHead['employeeName']		=    array('Fined By',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['reason']				=    array('Reason',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['statusReason']		=    array('Status Reason',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['amount']				=    array('Amount',        ' width="10%" align="right" ','align="right"');


    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();

//$History : $
?>
