<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeAppraisal');
define('ACCESS','view');
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
 UtilityManager::ifTeacherNotLoggedIn(true);
}
else if($roleId==5){
 UtilityManager::ifManagementNotLoggedIn(true);
}
else{
 UtilityManager::ifNotLoggedIn(true);   
}
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['empId']) != ''){
    $sessionHandler->setSessionVariable('EmployeeToBeAppraised',trim($REQUEST_DATA['empId']));
    $sessionHandler->setSessionVariable('OverrideAppraisalModuleAccess',1);
    require_once(MODEL_PATH . "/Appraisal/AppraisalDataManager.inc.php");
    $appDataManager = AppraisalDataManager::getInstance();
    $empArray=$appDataManager->getEmployeeInfo(trim($REQUEST_DATA['empId']));
    if(is_array($empArray) and count($empArray)>0){
       $empCode=$empArray[0]['employeeCode']; 
       $empName=$empArray[0]['employeeName'];
    }
    if($empCode==''){
        $empCode=NOT_APPLICABLE_STRING;
        $empName=NOT_APPLICABLE_STRING;
    }
    $sessionHandler->setSessionVariable('EmployeeCodeToBeAppraised',$empCode); 
    $sessionHandler->setSessionVariable('EmployeeNameToBeAppraised',$empName); 
    //fetch employee code and put it in session
    die(SUCCESS);
}
else{
    $sessionHandler->setSessionVariable('EmployeeToBeAppraised','');
    $sessionHandler->setSessionVariable('OverrideAppraisalModuleAccess',0);
    $sessionHandler->setSessionVariable('EmployeeCodeToBeAppraised',''); 
    $sessionHandler->setSessionVariable('EmployeeNameToBeAppraised',''); 
    die(EMPLOYEE_INFO_MISSING);
}
?>