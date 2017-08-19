 <?php
//This file is used as printing version for display Bank.
//
// Author :Jaineesh
// Created on : 06.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    require_once(MODEL_PATH . "/BankManager.inc.php");
    $bankManager = BankManager::getInstance();

 //to parse csv values    
function parseCSVComments($comments) {
 $comments = str_replace('"', '""', $comments);
 $comments = str_ireplace('<br/>', "\n", $comments);
 if(eregi(",", $comments) or eregi("\n", $comments)) {
   return '"'.$comments.'"'; 
 } 
 else {
 return $comments; 
 }
 
}
 
   $search = $REQUEST_DATA['searchbox'];
    $conditions = '';
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $search=trim($REQUEST_DATA['searchbox']);
       $filter = '  WHERE bankName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bankAbbr LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'bankName';

     $orderBy = " $sortField $sortOrderBy";

	$bankRecordArray = $bankManager->getBankList($filter,'',$orderBy);

	$recordCount = count($bankRecordArray);

    $valueArray = array();

    $csvData ="Search By : ".$search."\n";
    $csvData .="#,Bank Name,Abbr.,Address";
    $csvData .="\n";

    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= parseCSVComments($bankRecordArray[$i]['bankName']).",";
		  $csvData .= parseCSVComments($bankRecordArray[$i]['bankAbbr']).",";
          $csvData .= parseCSVComments($bankRecordArray[$i]['bankAddress']).",";
		  $csvData .= "\n";
    }

    if($recordCount==0){
        $csvData .=",".NO_DATA_FOUND;
    }

 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'BankReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;
die;
//$History : $
?>