 <?php
//This file is used as printing version for display attendance report in parent module.
//
//--------------------------------------------------------


    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifParentNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
    $parentManager = ParentManager::getInstance();


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studyPeriod';

    $studentId = $sessionHandler->getSessionVariable('StudentId');
    $classId  = $REQUEST_DATA['rClassId'];

    $studentName = $sessionHandler->getSessionVariable('StudentName');

    $orderBy = " $sortField $sortOrderBy";
	if($sessionHandler->getSessionVariable('MARKS') == 1){
		$parentSubjectAttendanceArray = $studentManager->getStudentMarks($studentId,$classId,$orderBy,'');
		$cnt = count($parentSubjectAttendanceArray);
	}



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

    $className=$parentManager -> getClassName();
    $className2=str_replace(CLASS_SEPRATOR," ",$className[0]['className']) ;

    $studentName = $sessionHandler->getSessionVariable('StudentName');

    $current="Current Class,";
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $search = parseCSVComments($studentName)."\n$current,".parseCSVComments($className2)."\nAs On, ".parseCSVComments($formattedDate)."\n";

    $csvData .=$search;
    $csvData .="Sr No.,Study Period,Subject,Type,Date,Teacher,Test Name,Max. Marks,Scored";
    $csvData .="\n";

    for($i=0;$i<$cnt;$i++) {
       $parentSubjectAttendanceArray[$i]['testDate'] = UtilityManager::formatDate($parentSubjectAttendanceArray[$i]['testDate']);
       $csvData .= ($i+1).",";
       $csvData .= parseCSVComments($parentSubjectAttendanceArray[$i]['studyPeriod']).",";
       $csvData .= parseCSVComments($parentSubjectAttendanceArray[$i]['subjectName']).",";
       $csvData .= parseCSVComments($parentSubjectAttendanceArray[$i]['testTypeName'])." ,";
       $csvData .= parseCSVComments($parentSubjectAttendanceArray[$i]['testDate']).",";
       $csvData .= parseCSVComments($parentSubjectAttendanceArray[$i]['employeeName']).",";
       $csvData .= parseCSVComments($parentSubjectAttendanceArray[$i]['testName']).",";
       $csvData .= parseCSVComments($parentSubjectAttendanceArray[$i]['totalMarks']).",";
       $csvData .= parseCSVComments($parentSubjectAttendanceArray[$i]['obtainedMarks']).",";
       $csvData .= "\n";
   }
   if($cnt == 0){
	   $csvData .="No Data Found";
   }

//print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'studentMarksReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;
die;
//$History : $
?>
