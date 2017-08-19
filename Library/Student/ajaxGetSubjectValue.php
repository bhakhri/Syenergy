<?php




global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');


$studentId=$REQUEST_DATA['studentId'];
$previousClassId = $REQUEST_DATA['previousClassId'];

    
if($studentId  != '') {
    require_once(MODEL_PATH . "/GetSubjectManager.inc.php");  
    $foundArray =SubjectManager::getInstance()->getSubjectValues("WHERE studentId='$studentId' AND previousClassId='$previousClassId'");
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}

?>
