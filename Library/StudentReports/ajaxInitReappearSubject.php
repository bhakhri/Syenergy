<?php
//-------------------------------------------------------
// THIS FILE IS USED TO fetch student internal re-appear subject Details
// Author : PArveen Sharma
// Created on : (23.12.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();

 require_once(MODEL_PATH . "/StudentManager.inc.php");      

   
    global $sessionHandler;  
    $studentManager = StudentManager::getInstance();  
    
    
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $reapperClassId = add_slashes($REQUEST_DATA['reappearClassId']);
  
    if($reapperClassId=='') {
      $reapperClassId = 0;  
    }
    
    $where = " WHERE sr.reapperClassId = c.classId AND sub.subjectId = sr.subjectId AND 
                     sr.reapperClassId IN ($reapperClassId) AND sr.instituteId = '$instituteId' ORDER BY className, subjectCode ";
    $filedName = "DISTINCT sub.subjectId, sub.subjectName, sub.subjectCode AS subjectCode1, c.className, 
                           CONCAT(sub.subjectCode,' (',SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',-2),')') AS subjectCode ";
    $tableName = "`subject` sub, student_reappear sr, class c ";
    $returnStatus = $studentManager->getSingleField($tableName, $filedName, $where);
    
    echo json_encode($returnStatus);
    
    
// $History: ajaxInitReappearSubject.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/18/10    Time: 4:13p
//Created in $/LeapCC/Library/StudentReports
//initial checkin
//

?>