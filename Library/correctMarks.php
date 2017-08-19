<?php
//Purpose: To find the inconsistemcy in b/w test_marks_table1 and total_marks_table
//Author: Kavish Manjkhola
//Date: 16-March-2011
//Chalkpad Technologies 2010-2011

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
$insertArray = array();
$query="SELECT 
					a.studentId, a.classId, a.subjectId, sum(a.marksScored) as totalMarksScored, sum(a.maxMarks) as totalMaxMarks, b.conductingAuthority
		FROM 	
					test_transferred_marks1 a, test_type b 
		WHERE 
					a.testTypeId = b.testTypeId 
		AND 		(a.classId, a.subjectId, a.studentId, b.conductingAuthority) 
					NOT IN  (SELECT 
									classId, subjectId, studentId, conductingAuthority 
							 FROM	total_transferred_marks1)
		GROUP BY	a.studentId, a.classId, a.subjectId,b.conductingAuthority
		ORDER BY	a.studentId,a.classId,a.subjectId,b.conductingAuthority";
$result = mysql_query($query);
if ($result == false) {
	echo FAILURE;
	die;
}
$num_rows = mysql_num_rows($result);
if($num_rows == 0) {
	echo NO_INCONSISTENCY_FOUND;
	die;
}
while($data=mysql_fetch_array($result)) {
	$studentId = $data['studentId'];
	$classId = $data['classId'];
	$subjectId = $data['subjectId'];
	$totalMarksScored = $data['totalMarksScored'];
	$totalMaxMarks = $data['totalMaxMarks'];
	$holdResult = 0;
	$marksScoredStatus = "'Marks'";
	$isActive = 1;
	$conductingAuthority = $data['conductingAuthority'];
	$insertArray[] = "($conductingAuthority, $studentId, $classId, $subjectId, $totalMaxMarks, $totalMarksScored, $holdResult, $marksScoredStatus,$isActive)";
}
if (count($insertArray)) {
	$insertStr = implode(',', $insertArray);
	$query = "INSERT INTO total_transferred_marks1(conductingAuthority, studentId, classId, subjectId, maxMarks, marksScored, holdResult, marksScoredStatus, isActive) VALUES $insertStr";
	$result = mysql_query($query);
	if($result == false) {
		echo FAILURE;
		die;
	}
	else {
		echo SUCCESS;
	}
}
?>