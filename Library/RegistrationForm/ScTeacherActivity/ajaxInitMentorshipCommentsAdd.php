<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','add');
global $sessionHandler;
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();
$errorMessage ='';

if ($errorMessage == '' && (!isset($REQUEST_DATA['comments']) || trim($REQUEST_DATA['comments']) == '')) {
	$errorMessage .= ENTER_MENTORSHIP_COMMENTS."\n";    
}

$mentorshipId =  $REQUEST_DATA['mentorshipId'];
$comments = add_slashes($REQUEST_DATA['comments']);
if (trim($errorMessage) == '') {
	
require_once(MODEL_PATH . "/RegistrationForm/MenteesManager.inc.php");
$teacherManager = MenteesManager::getInstance();

	
    $currentUserId = $sessionHandler->getSessionVariable('UserId');
    
    $mentorEmail = $sessionHandler->getSessionVariable('MENTOR_COMMENT_EMAIL');

    $studentIdArray = $teacherManager->getMentorshipStudentId($mentorshipId);
	$studentId = $studentIdArray[0]['studentId'];
    $classId = $studentIdArray[0]['classId'];
    $rollNo = $studentIdArray[0]['rollNo'];
    $studentName = $studentIdArray[0]['studentName'];
    $employeeName = $studentIdArray[0]['employeeName']; 
    $className = $studentIdArray[0]['className'];
    $currentDate = UtilityManager::formatDate(date('Y-m-d'));
	$headers = 'From: info@chitkarauniversity.edu.in';
	

	$returnStatus = $teacherManager->addComments($studentId, $comments, $currentUserId, $classId);
	if($returnStatus === false) {
		echo FAILURE;
	}
	else {
       if($mentorEmail!='') {
		 $str=$rollNo." Has been given comments by mentor which are: ".$comments;
		 $headers = 'From: info@chitkarauniversity.edu.in';
		 $regMenotrEmailId=$sessionHandler->getSessionVariable('MENTOR_COMMENTS_EMAIL');
		 
   	     @mail($mentorEmail,$employeeName."Added Comments for ".$studentName." having roll no.".$rollNo."of class ".$className,$str,$headers);
		 
		if($regMenotrEmailId!=''){
			 @mail($regMenotrEmailId,$employeeName."Added Comments for ".$studentName." having roll no.".$rollNo."of class ".$className,$str,$headers);
										
			} 
   	   }
	   echo SUCCESS; 
	}
}
else {
	echo $errorMessage;
}
?>