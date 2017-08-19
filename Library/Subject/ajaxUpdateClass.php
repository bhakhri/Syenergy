<?php
//-------------------------------------------------------
// Purpose: To store the records of Subject in array from the database, pagination and search, delete 
// functionality
//
// Author : Arvind Singh Rawat
// Created on : (30.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','Subject');
    define('ACCESS','add');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    

    require_once(MODEL_PATH . "/SubjectManager.inc.php");
    $subjectManager = SubjectManager::getInstance();

    $studentRecordArray = array();
    
    $isNotUpdate='';
    $isUpdate='';
    $studentRecordArray = $subjectManager->getStudentClassList();
    for($i=0;$i<count($studentRecordArray);$i++) {
       $ttClassId  = $studentRecordArray[$i]['classId'];   
       $ttDegreeId = $studentRecordArray[$i]['degreeId'];
       $ttBranchId = $studentRecordArray[$i]['branchId'];
       $ttBatchId  = $studentRecordArray[$i]['batchId'];
       
       $condition = " AND CONCAT_WS(',',c.degreeId, c.branchId, c.batchId) IN ('$ttDegreeId,$ttBranchId,$ttBatchId') ";
       $studentClassArray = $subjectManager->getClassList($condition);
       $ssClassId = '';
       for($j=0;$j<count($studentClassArray);$j++) {
          if($ssClassId!='') {
            $ssClassId .= "~";  
          }
          $ssClassId .= $studentClassArray[$j]['classId'];
       }
       if($ssClassId!='') {
           $str = "UPDATE student SET 
                     sAllClass = '~$ssClassId~' 
                   WHERE 
                     classId = '$ttClassId' ";
           if($ttClassId!='') {
             if(SystemDatabaseManager::getInstance()->startTransaction()) {   
                 $returnStatus = $subjectManager->updateStudentClass($str); 
                 if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                    if($isUpdate!='') {
                      $isUpdate .=",";  
                    } 
                    $isUpdate .= "$ttClassId";
                 }
                 else {
                    if($isNotUpdate!='') {
                      $isNotUpdate .=",";  
                    } 
                    $isNotUpdate .= "$ttClassId"; 
                 }
             }
           }
       }
    } 
    echo $isUpdate."!~!!~!".$isNotUpdate;
?>
