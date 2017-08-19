<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','StudentInfoDetail');
define('ACCESS','edit');
UtilityManager::ifStudentNotLoggedIn();

require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentInformationManager = StudentInformationManager::getInstance();
$studentId=$sessionHandler->getSessionVariable('StudentId');

$docId=trim($REQUEST_DATA['docId']);
if($docId==''){
    die('Required Parameters Missing');
}

/*************GET STUDENT's DOCUMENTS INFORMATIONS************/
 $studentDocumentsArray=$studentInformationManager->getStudentAttachedDocuments($studentId,' AND documentId='.$docId);
 $documentFile=$studentDocumentsArray[0]['documentFileName'];
 $ret=StudentManager::getInstance()->deleteDocumentFile($studentId,$docId);
 if($ret==true){
  if($documentFile!=''){
     @unlink(STORAGE_PATH.'/Student/Documents/'.$documentFile);
  }
  die(DELETE);
 }
 else{
   die(FAILURE);  
 }
?>