<?php
/*
PURPOSE : REMOVE SPECIAL CHARACTERS FROM ROLL NO. AND USERNAME
AUTHOR: AJINDER SINGH
DATE: 27-DEC-2010
*/
set_time_limit(0);
require_once("dbConfig.inc.php");

$con = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect');
mysql_select_db(DB_NAME, $con) or die("database not selected");



$allowedArray = range(97,122); // a-z
foreach ($allowedArray as $allowedValue) {
	$allowedAsciiArray[] = $allowedValue;
}
$allowedArray = range(48,57); //0-9
foreach ($allowedArray as $allowedValue) {
	$allowedAsciiArray[] = $allowedValue;
}
$allowedArray = range(65,90); //A-Z
foreach ($allowedArray as $allowedValue) {
	$allowedAsciiArray[] = $allowedValue;
}

$allowedAsciiArray[] = 40;// (
$allowedAsciiArray[] = 41;// )
$allowedAsciiArray[] = 45;// -
$allowedAsciiArray[] = 46;// .
$allowedAsciiArray[] = 47; // /
$allowedAsciiArray[] = 92;// \
$allowedAsciiArray[] = 95;// _

$query = "select studentId, rollNo from student";
$result = mysql_query($query,$con) or die('error while executing query '.$query);

while ($row = mysql_fetch_object($result)) {
	$studentId = $row->studentId;
	$rollNo = $row->rollNo;
	$start = 0;
	$total = strlen($rollNo);
	$newRollNo = '';
	while ($start <= $total) {
		$char = substr($rollNo, $start, 1);
		$ascii = ord($char);
		if (in_array($ascii, $allowedAsciiArray)) {
			$newRollNo .= $char;
		}
		$start++;
	}
	$query2 = "update student set rollNo = '$newRollNo' where studentId = $studentId";
	mysql_query($query2,$con) or die('error while executing query '.$query2.'<br>'.mysql_error($con));
}




$query = "select userId, userName from user";
$result = mysql_query($query,$con) or die('error while executing query '.$query);

while ($row = mysql_fetch_object($result)) {
	$userId = $row->userId;
	$userName = $row->userName;
	$start = 0;
	$total = strlen($userName);
	$newUserName = '';
	while ($start <= $total) {
		$char = substr($userName, $start, 1);
		$ascii = ord($char);
		if (in_array($ascii, $allowedAsciiArray)) {
			$newUserName .= $char;
		}
		$start++;
	}
	$query2 = "update user set userName = '$newUserName' where userId = $userId";
	mysql_query($query2,$con) or die('error while executing query '.$query2.'<br>'.mysql_error($con));
}



echo 'done';