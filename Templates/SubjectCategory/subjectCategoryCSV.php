<?php
//This file is used as printing version for TestType.
//
// Author :Parveen sharma
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','SubjectCategory');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);

    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/SubjectCategoryManager.inc.php");
    $subjectCategoryManager =  SubjectCategoryManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();


    //to parse csv values
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
            return '"'.$comments.'"';
         }
         else {
            return $comments.chr(160);
         }
    }

    /// Search filter /////
     if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
        $filter = ' WHERE (categoryName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                          abbr LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                          subjectCount LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'")';
        
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'categoryName';

     $orderBy = " ORDER BY $sortField $sortOrderBy";

    ////////////
    $record = $subjectCategoryManager->getSubjectCategoryList($filter,'',$orderBy);
    $cnt = count($record);

    $search = $REQUEST_DATA['searchbox'];

    $csvData = '';
    $csvData .= "SearchBy,".parseCSVComments($search).",\n";
    $csvData .= "#, Category Name, Abbr., No. of Students,Parent Category \n";
    for($i=0;$i<$cnt;$i++) {
       // add stateId in actionId to populate edit/delete icons in User Interface
       $csvData .= ($i+1).",".parseCSVComments($record[$i]['categoryName']).",".parseCSVComments($record[$i]['abbr']).",".parseCSVComments($record[$i]['subjectCount']);
       $csvData .= ",".parseCSVComments($record[$i]['parentCategoryName'])." \n";
    }

    if($i==0) {
       $csvData .= ",,No Data Found";
    }

	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a CSV
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	// It will be called testType.csv
	header('Content-Disposition: attachment;  filename="SubjectCategoryReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;


// $History: subjectCategoryCSV.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/16/09    Time: 5:17p
//Created in $/LeapCC/Templates/SubjectCategory
//initial checkin
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/08/09    Time: 5:30p
//Updated in $/LeapCC/Templates/AttendanceCode
//bug fix 505, 504, 503, 968, 961, 960, 959, 958, 957, 956, 955, 954,
//953, 952,
//951, 723, 722, 797, 798, 799, 916, 935, 936, 937, 938, 939, 940, 944
//(alignment, condition & formatting updated)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/08/09    Time: 4:58p
//Updated in $/LeapCC/Templates/AttendanceCode
//condition remove (showInLeavetype  extra)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/08/09    Time: 4:44p
//Updated in $/LeapCC/Templates/AttendanceCode
//search condition updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/06/09    Time: 4:31p
//Created in $/LeapCC/Templates/AttendanceCode
//initial checkin
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/27/09    Time: 6:23p
//Updated in $/LeapCC/Templates/Subject
//bug fix (csvData print format bracket updated)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/20/09    Time: 1:55p
//Updated in $/LeapCC/Templates/Subject
//new enhancement categoryId (link subject_category table) new field
//added
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:58
//Created in $/LeapCC/Templates/Subject
//Added "Print" and "Export to excell" in subject and subjectType modules
?>