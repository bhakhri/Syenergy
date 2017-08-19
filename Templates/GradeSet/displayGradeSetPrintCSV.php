
<?php
    require_once(MODEL_PATH . "/GradeSetManager.inc.php");
    $gradeSetManager = GradeSetManager::getInstance();
    
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
	
	$search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE ( gradeSetName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR isActive LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'gradeSetName';
    
    $orderBy = " $sortField $sortOrderBy";
	 
	$gradeSetArray = $gradeSetManager->getGradeSetDetail($filter,'',$orderBy);

    
	$recordCount = count($gradeSetArray);

    $gradeTypeArray = array( 0=>'No',1=>'Yes');
	  for($i=0;$i<$cnt;$i++) {
	   $gradeType = $gradeSetArray[$i]['isActive'];
	   
	   /*
	   $gradeArray = explode(',', $gradeType);
	   $str = '';
	   foreach ($gradeArray as $rec) {
		   if (!empty($str)) {
			   $str .= ',';
		   }
		   $str .= $gradeSetArray[$rec];
	   }
	   */

	  }

    $valueArray = array();

    $csvData ='';
    $csvData="Sr No.,Grade Set Name,isActive.";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
		  $csvData .= $gradeSetArray[$i]['gradeSetName'].",";
		  $csvData .= parseCSVComments($gradeTypeArray[$gradeSetArray[$i]['isActive']]).",";
		  $csvData .= "\n";
  }
    
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'GradeSetReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//Modify By satinder
?>