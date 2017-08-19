<?php
  global $FE;
  require_once($FE . "/Library/common.inc.php");
  require_once(BL_PATH . "/UtilityManager.inc.php");
  define('MODULE','UploadCandidateDetails');
  define('ACCESS','add');
  UtilityManager::ifNotLoggedIn();
  ob_end_clean();
  header("Content-Type: application/force-download");
  header("Content-Type: text/html");
  header("Content-Type: application/download");
  header('Content-Disposition: attachment; filename="StudentEnquiryUploadInstructions.txt"');
  readfile(BL_PATH . '/StudentEnquiryUploadInstructions.txt');
?>
