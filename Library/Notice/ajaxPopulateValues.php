<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/NoticeManager.inc.php");  
    $noticeManager = NoticeManager::getInstance();
    
    $universityId = trim($REQUEST_DATA['universityId']); 
    $branchId = trim($REQUEST_DATA['branchId']); 
    $degreeId = trim($REQUEST_DATA['degreeId']); 
    $val = trim($REQUEST_DATA['val']);
    
    // D Value
    
    // Degree List 
    $condition='';
    $valAll='';
    if($val=='U') {
      if($universityId!="NULL") {  
        $condition = " AND a.universityId = '$universityId' "; 
      }
      $valAll='D';
    }
    $fieldName = " d.degreeId, d.degreeName, d.degreeCode ";
    $orderBy = " d.degreeCode";
    $degreeArray = $noticeManager->getAllClasses($condition,$fieldName,$orderBy); 
    
   
    
    // Branch List 
    $condition ="";
    if($val=='D' || $valAll=='D') {
      if($universityId!="NULL") {  
        $condition .= " AND a.universityId = '$universityId' "; 
      }
      if($degreeId!="NULL") {  
        $condition .= " AND a.degreeId = '$degreeId' "; 
      }
      $valAll='B';
    }
    
    $fieldName = " b.branchId, b.branchName, b.branchCode";
    $orderBy = " b.branchCode";
    $branchArray = $noticeManager->getAllClasses($condition,$fieldName,$orderBy); 
    
    
    // Class List 
    $condition ="";
    if($universityId!="NULL") {  
      $condition .= " AND a.universityId = '$universityId' "; 
    }  
    if($degreeId!="NULL") {  
      $condition .= " AND a.degreeId = '$degreeId' "; 
    }  
    if($branchId!="NULL") {  
      $condition .= " AND a.branchId = '$branchId' "; 
    }  
    $condition .= " AND a.isActive IN (1,3) "; 
    $fieldName = " a.className, a.classId ";
    $orderBy = " a.className";
    $classArray = $noticeManager->getAllClasses($condition,$fieldName,$orderBy); 
    
    
    echo json_encode($universityArray)."!~~!".json_encode($degreeArray)."!~~!".json_encode($branchArray)."!~~!".json_encode($classArray);    
?>
