 <?php
//This file is used as printing version for display group.
//
// Author :Jaineesh
// Created on : 03.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php
	require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/GroupManager.inc.php");
	$groupManager = GroupManager::getInstance();

	//to parse csv values
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

    $search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (c.groupName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR c.groupShort LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR gt.groupTypeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR cl.className LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'groupName';

     $orderBy = " $sortField $sortOrderBy";

	$groupRecordArray = $groupManager->getGroupList($filter,'',$orderBy);

	$recordCount = count($groupRecordArray);

    $valueArray = array();

    $csvData ='';
    $csvData="#,Group Name,Short Name,Parent Group,Group Type,Class";
    $csvData .="\n";

    for($i=0;$i<$recordCount;$i++) {
          if($groupRecordArray[$i]['parentGroup']==''){
            $groupRecordArray[$i]['parentGroup']=NOT_APPLICABLE_STRING;
          }
		  $csvData .= ($i+1).',';
		  $csvData .= parseCSVComments($groupRecordArray[$i]['groupName']).',';
		  $csvData .= parseCSVComments($groupRecordArray[$i]['groupShort']).',';
		  $csvData .= parseCSVComments($groupRecordArray[$i]['parentGroup']).',';
		  $csvData .= parseCSVComments($groupRecordArray[$i]['groupTypeName']).',';
		  $csvData .= parseCSVComments($groupRecordArray[$i]['className']);
		  $csvData .= "\n ";
  }

 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'GroupReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;
die;
//$History : $
?>