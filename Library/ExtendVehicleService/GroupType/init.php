<?php
//duplicate check has to be coded

$errorMessage = '';
if($REQUEST_DATA['groupTypeSubmit'] == 'Add' ) {

    if (!isset($REQUEST_DATA['groupTypeName']) || trim($REQUEST_DATA['groupTypeName']) == '') {
        $errorMessage = 'Enter Group Type Name';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['groupTypeCode']) || trim($REQUEST_DATA['groupTypeCode']) == '')) {
        $errorMessage = 'Enter Group Type Code';
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/GroupTypeManager.inc.php");
        $returnStatus = GroupTypeManager::getInstance()->addGroupType();
        
        if($returnStatus === false) {
            $errorMessage = 'Data couldn\'t be saved. Please try again!';
        }
        else {
            redirectBrowser(UtilityManager::buildTargetURL("addGroupType.php", array('status' => 1 ) ));            
        }
    }
}
if( $REQUEST_DATA['groupTypeSubmit'] == 'Edit' ) {

    if (!isset($REQUEST_DATA['groupTypeName']) || trim($REQUEST_DATA['groupTypeName']) == '') {
        $errorMessage = 'Enter Group Type Name';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['groupTypeCode']) || trim($REQUEST_DATA['groupTypeCode']) == '')) {
        $errorMessage = 'Enter Group Type Code';
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/GroupTypeManager.inc.php");
        $returnStatus = GroupTypeManager::getInstance()->editGroupType($REQUEST_DATA['groupTypeId']);
        
        if($returnStatus === false) {
            $errorMessage = 'Data couldn\'t be saved. Please try again!';
        }
        else {
            redirectBrowser(UtilityManager::buildTargetURL("listGroupType.php", array('status' => 1 )));            
        }
    }
}
if(UtilityManager::notEmpty($REQUEST_DATA['groupTypeId']) ) {
        require_once(MODEL_PATH . "/GroupTypeManager.inc.php");
        $groupTypeRecordArray   = GroupTypeManager::getInstance()->getGroupType(' WHERE groupTypeId='.$REQUEST_DATA['groupTypeId']);   
} 
?>