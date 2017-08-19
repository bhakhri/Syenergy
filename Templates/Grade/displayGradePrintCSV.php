
<?php
    require_once(MODEL_PATH . "/GradeManager.inc.php");
    $gradeManager = GradeManager::getInstance();
    
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
	  if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  WHERE gradeSetName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR gradeLabel LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR gradePoints LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%"';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'gradeSetName';
    
    $orderBy = " $sortField $sortOrderBy";
	 
	$gradeRecordArray = $gradeManager->getGradeList($filter,'',$orderBy);
    $cnt = count($gradeRecordArray);
    
    $search = add_slashes(trim($REQUEST_DATA['searchbox']));    
    
    $csvData ='';
    $csvData ="Search By,".parseCSVComments($search)."\n";
    $csvData.="Sr No.,Grade Set Name,Grade Name,Grade Points";
    $csvData .="\n";
    
    for($i=0;$i<$cnt;$i++) {  
		  $csvData .= ($i+1).",";
		  $csvData .= parseCSVComments($gradeRecordArray[$i]['gradeSetName']).",";
		  $csvData .= parseCSVComments($gradeRecordArray[$i]['gradeLabel']).",";
		  $csvData .= parseCSVComments($gradeRecordArray[$i]['gradePoints'])."\n";
    }
    
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'GradeReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//Modify By satinder
?>