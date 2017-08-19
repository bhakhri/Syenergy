<?php
//-----------------------------------------------------------------------------------------------------------------
// Purpose: To get values of assigned and unassigned courses information according to time table label selected
// Author : Dipanjan Bhattacharjee
// Created on : 17.06.2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php'); //for transaction support
    define('MODULE','AssignFinetoRoles');
    define('ACCESS',trim($REQUEST_DATA['action']));
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineManager = FineManager::getInstance();
    
    
    $errStr='';
    //to check for invalid user input
    function checkEmpty($item,$key){
        global $errStr;
        if(empty($item)){
           if($errStr==''){ 
            $errStr =($key+1);
           }
           else{
               $errStr .='~'.($key+1);
           }
        }
    }
    
    //to add single quote
    function addSingleQuote(&$item,$key){
        $item="'".add_slashes($item)."'";
    }

    //data sanitize
    function sanitizeData(&$item,$key){
        $item=preg_replace("/[\n\r]/","",strval(trim($item)));
    }

    function makeString($array) {
        $newArray = Array();
        foreach ($array as $key => $value) {
            $newArray[] = strval(strtoupper(trim($value)));
        }
        return $newArray;
    }

    
    if(trim($REQUEST_DATA['roleId']) != '' && trim($REQUEST_DATA['fineIds']) != '' && 
        trim($REQUEST_DATA['userIds']) != '' && trim($REQUEST_DATA['instituteIds']) != '' &&
          trim($REQUEST_DATA['fineClassId']) != '' ) { 
    
    
    //*****************INPUT DATA VALIDATION********************//
    
    $userIds=explode(',',trim($REQUEST_DATA['userIds']));
    array_walk($userIds,'sanitizeData');
    array_walk($userIds,'checkEmpty');
    if($errStr!=''){
       echo INVALID_USER_NAME_INPUT;
       die;
    }
    $ex1=$userIds;
    //add single quote
    array_walk($ex1,'addSingleQuote');
    $ex2=implode(",",$ex1); 
    
    //check for USER NAME input
    $ret=$fineManager->checkUserNames(' WHERE u.userName IN ('.$ex2.')');
    $userIdArray=UtilityManager::makeCSList($ret,'userId');
    $userInstituteArray=explode(',',UtilityManager::makeCSList($ret,'instituteId'));
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $wrongUserSting='';

    //to check whethe the given user names contain any student/parent usernames(3:Parent,4:Student)    
    $roleIdArray=explode(',',UtilityManager::makeCSList($ret,'roleId'));
    if(in_array(3,$roleIdArray) or in_array(4,$roleIdArray)){
      echo STUDENT_PARENT_USER_CANNOT_APPROVE;
      die;
    }

    
    $fl=1;
    $r=count($ret);
    $retArray='';
    for($i=0;$i<$r;$i++){
       if($retArray==''){
           $retArray=trim($ret[$i]['userName']);
       }
       else{
           $retArray .=' ,'.trim($ret[$i]['userName']);
       }
    }
   
   $retArray=explode(',',$retArray);
   $userIds = makeString($userIds);
   $retArray = makeString($retArray);
   $errStr = array_diff($userIds,$retArray);
   if(count($errStr)>0 and is_array($errStr)){
      echo INCORRECT_USER_NAME_INPUT;
      die; 
   }

   
   //*****************INPUT DATA VALIDATION(ends)********************// 
  
   
   if(SystemDatabaseManager::getInstance()->startTransaction()) {
     
       //delete previous data
       $r1=$fineManager->deletePreviouslyRoletoFineMapping($REQUEST_DATA['roleId']);
       if($r1===false){
         echo FAILURE;
         die;  
       }
       //add to role_fine table
       $r2=$fineManager->addRoletoFineMapping($REQUEST_DATA['roleId']);
       if($r2=='' OR $r2===false){
         echo FAILURE;
         die;
       }
       //get the generated role fine id
       $roleFineId=SystemDatabaseManager::getInstance()->lastInsertId();
       
       //add to role_fine_category table       
       $fineCategories=explode(',',$REQUEST_DATA['fineIds']);
	   
       $cnt=count($fineCategories);
       $str='';
       for($i=0;$i<$cnt;$i++){
           if($str!=''){
               $str .=',';
           }
           $str .= " ( $roleFineId,$fineCategories[$i] ) "; 
       }
       if($str!=''){
         $r3=$fineManager->addRoletoFineCategoryMapping($str);   
       }
       else{
         echo FAILURE;
         die;  
       }

	   
	   //add to role_fine_institute table       
       $fineInstitutes=explode(',',$REQUEST_DATA['instituteIds']);
       $cnt=count($fineInstitutes);
       $str='';
       for($i=0;$i<$cnt;$i++){
           if($str!=''){
               $str .=',';
           }
           $str .= " ( $roleFineId,$fineInstitutes[$i] ) "; 
       }
       if($str!=''){
         $r3=$fineManager->addInstitute($str);   
       }
       else{
         echo FAILURE;
         die;  
       }
       
       
       //add to role_fine_class table       
       $fineClassId=explode(',',$REQUEST_DATA['fineClassId']);
       $cnt=count($fineClassId);
       $str='';
       for($i=0;$i<$cnt;$i++){
           if($str!=''){
               $str .=',';
           }
           $str .= " ( $roleFineId,$fineClassId[$i] ) "; 
       }
       if($str!=''){
         $r3=$fineManager->addFineClass($str);   
       }
       else{
         echo FAILURE;
         die;  
       }
	   
	        
       //add to role_fine_approve table
       $userIdArray=explode(',',$userIdArray);
       $cnt=count($userIdArray);
       $str='';
       for($i=0;$i<$cnt;$i++){
           if($str!=''){
               $str .=',';
           }
           $str .= " ( $roleFineId,$userIdArray[$i] ) "; 
       }
       if($str!=''){
         $r4=$fineManager->addRoletoApproveMapping($str);   
       }
       else{
         echo FAILURE;
         die;  
       }
       if(SystemDatabaseManager::getInstance()->commitTransaction()) {
          echo SUCCESS;
          die;
       }
       else {
         echo FAILURE;
         die;
       }
   }
   else{
       echo FAILURE;
       die;
   }
    
}
else{
    echo 'Invalid Selection of Role,Fines and Approver';
    die;
}
?>