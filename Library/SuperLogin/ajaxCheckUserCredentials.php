<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SuperLogin');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['val'] ) != '') {
    $userId=trim($REQUEST_DATA['val']);
    $searchString=trim($REQUEST_DATA['searchString']);
    require_once(MODEL_PATH . "/LoginManager.inc.php");
    $loginManager = LoginManager::getInstance();
    
    /*
    if($sessionHandler->getSessionVariable('RoleId')!=1){
        echo ACCESS_DENIED;
        die;
    }
    */
    
    //get user credentials
    $returnStatus=$loginManager->getUserCredentials($userId);
    if($returnStatus[0]['roleId']!=3 and $returnStatus[0]['roleId']!=4){
        echo 'You cannot login into other roles except parent and student';
        die;
    }
    $roleId=$returnStatus[0]['roleId'];
    
    //fetch user details    
    $studentRet = $loginManager->getStudentDetail($userId);
    if(!is_array($studentRet) or count($studentRet)==0 ){
        echo 'No student/parent found associated with this username.';  //? need to check the logic
        die;
    }
    
    //***********store super user id**********************
    $sessionHandler->setSessionVariable('SuperUserId',$sessionHandler->getSessionVariable('UserId'));
    $sessionHandler->setSessionVariable('SuperLoginSearchString',$searchString);
    //***********store super user id**********************
    
    //now change session variables
	$sessionHandler->setSessionVariable('staticfooter','on');   //session variable for the still footer
    $sessionHandler->setSessionVariable('UserId',$returnStatus[0]['userId']);
    $sessionHandler->setSessionVariable('RoleId',$returnStatus[0]['roleId']);
    $sessionHandler->setSessionVariable('UserName',$returnStatus[0]['userName']);
    $sessionHandler->setSessionVariable('UserThemeId',$returnStatus[0]['themeId']);
    $sessionHandler->setSessionVariable('UserExpandCollapseGrouping',$returnStatus[0]['grouping']);
    $sessionHandler->setSessionVariable('RoleName',strip_slashes($returnStatus[0]['roleName']));
    $sessionHandler->setSessionVariable('RemoteIp',$_SERVER['REMOTE_ADDR']);
    $sessionHandler->setSessionVariable('ApplicationPath',HTTP_PATH);
    
    //update access rights
    //FETCH VALUES FROM CONFIG TABLE AND STORE INTO SESSION
    $configArray = $loginManager->getConfigSettings();
    if (is_array($configArray) && count($configArray)) {
        foreach($configArray as $configRecord) {
            $sessionHandler->setSessionVariable($configRecord['param'],$configRecord['value']);
        }
    }

    $accessArray = $loginManager->getAccessArray();
    foreach($accessArray as $accessRecord) {
        $sessionHandler->setSessionVariable($accessRecord['moduleName'], 
            Array (
            'view'    =>    $accessRecord['viewPermission'],
            'add'    =>    $accessRecord['addPermission'], 
            'edit'    =>    $accessRecord['editPermission'], 
            'delete'=>    $accessRecord['deletePermission']
            )
        );
    }
    
    $dashboardAccessArray = $loginManager->getDashboardAccessArray();
    foreach($dashboardAccessArray as $dashboardAccessRecord) {
        $sessionHandler->setSessionVariable($dashboardAccessRecord['frameName'],$dashboardAccessRecord['frameId'] );
    }

    $sessionHandler->setSessionVariable('StudentMobileNo',$studentRet[0]['studentMobileNo']);
    $sessionHandler->setSessionVariable('FatherMobileNo',$studentRet[0]['fatherMobileNo']);
    $sessionHandler->setSessionVariable('MotherMobileNo',$studentRet[0]['motherMobileNo']);
    $sessionHandler->setSessionVariable('GuardianMobileNo',$studentRet[0]['guardianMobileNo']);
    $sessionHandler->setSessionVariable('CorrPhoneNo',$studentRet[0]['corrPhone']);
    $sessionHandler->setSessionVariable('PermPhoneNo',$studentRet[0]['permPhone']);
    $sessionHandler->setSessionVariable('StudentId',$studentRet[0]['studentId']);
    $sessionHandler->setSessionVariable('StudentName',$studentRet[0]['studentName']);
    $sessionHandler->setSessionVariable('FatherName',$studentRet[0]['fatherName']);
    $sessionHandler->setSessionVariable('MotherName',$studentRet[0]['motherName']);
    $sessionHandler->setSessionVariable('GuardianName',$studentRet[0]['guardianName']);
    $sessionHandler->setSessionVariable('RegNo',$studentRet[0]['regNo']); 
    $sessionHandler->setSessionVariable('RollNo',$studentRet[0]['rollNo']);
    $sessionHandler->setSessionVariable('UniversityRollNo',$studentRet[0]['universityRollNo']);
    $sessionHandler->setSessionVariable('DateOfAdmission',$studentRet[0]['dateOfAdmission']);
    $sessionHandler->setSessionVariable('DateOfBirth',$studentRet[0]['dateOfBirth']);
    $sessionHandler->setSessionVariable('ClassName',$studentRet[0]['className']);
    $sessionHandler->setSessionVariable('StudentAllClass',$studentRet[0]['sAllClass']);
    $sessionHandler->setSessionVariable('StudentMigrationStudyPeriod',$studentRet[0]['migrationStudyPeriod']); 
    
    $sessionHandler->setSessionVariable('ClassBatchId',$studentRet[0]['batchId']);
    $sessionHandler->setSessionVariable('ClassDegreeId',$studentRet[0]['degreeId']);
    $sessionHandler->setSessionVariable('ClassBranchId',$studentRet[0]['branchId']);
    
    
    
    $sessionHandler->setSessionVariable('ClassId',$studentRet[0]['classId']);
    $sessionHandler->setSessionVariable('LoggedName',$studentRet[0]['studentName']);
    
    //we will not update login time so that student/parent do not complain that 
    //somebody else is poking into their account
    
    if($roleId==3){
        $sessionHandler->setSessionVariable('ParentId',$returnStatus[0]['userId']);
        $parentName=$loginManager->getParentName($returnStatus[0]['userId']);
        $sessionHandler->setSessionVariable('ParentName',$parentName[0]['name']);
        $sessionHandler->setSessionVariable('StudentArray',$studentRet);
    }
    else{
        $sessionHandler->setSessionVariable('userPhoto',"Student/".$studentRet[0]['studentPhoto']);
    }
    
    echo $roleId;
    die;
    
}
else{
    echo 'Required Parameters Missing';
    die;
}
// $History: ajaxCheckUserCredentials.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/04/10    Time: 19:10
//Created in $/Leap/Source/Library/SuperLogin
//Created module "Super Login"
?>