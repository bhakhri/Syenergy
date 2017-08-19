<?php
//duplicate check has to be coded

$errorMessage = '';
if($REQUEST_DATA['designationSubmit'] == 'Add' ) {

    if (!isset($REQUEST_DATA['designationName']) || trim($REQUEST_DATA['designationName']) == '') {
        $errorMessage = 'Enter Designation Name';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['designationCode']) || trim($REQUEST_DATA['designationCode']) == '')) {
        $errorMessage = 'Enter Designation Code';
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/DesignationManager.inc.php");
        $returnStatus = DesignationManager::getInstance()->addDesignation();
        
        if($returnStatus === false) {
            $errorMessage = 'Data couldn\'t be saved. Please try again!';
        }
        else {
            redirectBrowser(UtilityManager::buildTargetURL("addDesignation.php", array('status' => 1 ) ));            
        }
    }
}
if( $REQUEST_DATA['designationSubmit'] == 'Edit' ) {

    if (!isset($REQUEST_DATA['designationName']) || trim($REQUEST_DATA['designationName']) == '') {
        $errorMessage = 'Enter Designation Name';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['designationCode']) || trim($REQUEST_DATA['designationCode']) == '')) {
        $errorMessage = 'Enter Designation Code';
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/DesignationManager.inc.php");
        $returnStatus = DesignationManager::getInstance()->editDesignation($REQUEST_DATA['designationId']);
        
        if($returnStatus === false) {
            $errorMessage = 'Data couldn\'t be saved. Please try again!';
        }
        else {
            redirectBrowser(UtilityManager::buildTargetURL("listDesignation.php", array('status' => 1 )));            
        }
    }
}
if(UtilityManager::notEmpty($REQUEST_DATA['designationId']) ) {
        require_once(MODEL_PATH . "/DesignationManager.inc.php");
        $designationRecordArray   = DesignationManager::getInstance()->getDesignation(' WHERE designationId='.$REQUEST_DATA['designationId']);   
} 
?>