<?php
//-------------------------------------------------------
// Purpose: to design the layout for add subject to class
//
// Author : Jaineesh
// Created on : (30.05.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0);  
    
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','CreateParentLogin');
    define('ACCESS','add');

    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    $chb  = add_slashes($REQUEST_DATA['chb']);                                              
    $cnt = count($chb);
    $onePassword = add_slashes($REQUEST_DATA['onePassword']);
    $studentIds = add_slashes($REQUEST_DATA['studentCheckIds']);

    if($studentIds=='') {
      $studentIds=0;  
    }
     
    $conditions .= " AND a.studentId IN (".$studentIds.")"; 
    
   // Sorting Order
   $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
   $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'firstName';
   $orderBy=" $sortField $sortOrderBy"; 
   

   // END: search filter 
   $foundArray = $studentManager->getStudentList($conditions,'',$orderBy);
   $count = count($foundArray);

   
//****************************************************************************************************************    
//***********************************************STRAT TRANSCATION************************************************
//****************************************************************************************************************

   $fcheck = add_slashes($REQUEST_DATA['fcheckbox']);
   $mcheck = add_slashes($REQUEST_DATA['mcheckbox']);
   $gcheck = add_slashes($REQUEST_DATA['gcheckbox']);
   $overwrite = add_slashes($REQUEST_DATA['overwrite']);
   $onePassword = add_slashes($REQUEST_DATA['onePassword']);
   $newPassword = add_slashes($REQUEST_DATA['newPassword']);   

   $userIdArr = "";                  // generate a password then Store the userId
   $studentIdNotPassword = "";       
   
   $parentUserLoginList = "";       
   
   $parentfoundArray1 = Array();
   $parentfoundArray2 = Array();
   $parentfoundArray3 = Array();
   
   $p1=0;
   $m1=0;
   $g1=0;
   $json_val1="";
   $json_val2="";
   $json_val3="";

if(SystemDatabaseManager::getInstance()->startTransaction()) {
  for($j=0;$j<$count;$j++) {
    
    $studentId = $foundArray[$j]['studentId']; 
      
    $firstName = $foundArray[$j]['firstName'];
    
    $fatherName = trim($foundArray[$j]['fatherName']);
    $motherName = trim($foundArray[$j]['motherName']);
    $guardianName = trim($foundArray[$j]['guardianName']);
    
    $dateOfBirth = $foundArray[$j]['DOB'];
    
    $fUserId = $foundArray[$j]['fatherUserId'];
    $mUserId = $foundArray[$j]['motherUserId'];
    $gUserId = $foundArray[$j]['guardianUserId'];
    
    if($fUserId==0) {
      $fUserId = "";  
    }
    
    if($mUserId==0) {
      $mUserId = "";  
    }
    
    if($gUserId==0) {
      $gUserId = "";  
    }
    
    $fatherUserName = $foundArray[$j]['fatherUserName'];
    $motherUserName = $foundArray[$j]['motherUserName'];
    $guardianUserName = $foundArray[$j]['guardianUserName'];
               
               
    if ($onePassword == 1) {    // The passwords would be first name of parent followed by the year(yy) of birth of student. (i.e. ram90, seema87)   
        if($overwrite==1 && $fUserId=="" && $fcheck==1)  {   //  Do not change the password for existing users. 
           // Father's User Id Create
           $rollNo = $foundArray[$j]['rollNo']; 
           if($fcheck==1 && $dateOfBirth != '0000-00-00' && $fatherName != NOT_APPLICABLE_STRING && $rollNo != NOT_APPLICABLE_STRING ) {
              $rollNo = trim(strtolower($rollNo));
              $yr = trim(substr($dateOfBirth,2,2));
              
              $userName = "f".trim($rollNo);

              $userPass1 = substr($fatherName,0,stripos($fatherName," "));
              if($userPass1!="") {
                 $fatherName = $userPass1;  
              }
              $userPass = strtolower(trim($fatherName).trim($yr));
              
              $userUpdate = $studentManager->insertParentUserData($userName,$userPass);
              if($userUpdate===false) {
                 echo FAILURE;
                 die;
              }
              $userId=SystemDatabaseManager::getInstance()->lastInsertId();
              if($userId===false) {
                echo FAILURE;
                die;
              }
              
              $cond = " `student` SET fatherUserId = '".$userId."' WHERE studentId = '".$studentId."'";
              $returnStatus = $studentManager->updateParentPassword($cond);
              if($returnStatus===false) {
                echo FAILURE;
                die;
              }
              
             // Start   --   Check user role
             $foundUserRole = $studentManager->getUserRoleList(" AND ur.userId = ".$userId);
             if(count($foundUserRole) == 0 ) {
                // Insert User Role 
                $returnStatus = $studentManager->insertUserRole($userId,3); 
                if($returnStatus===false) {
                   echo FAILURE; 
                   die;
                }
             }
             // End    --   Check user role
            
   /*           $parentfoundArray1[$p1]['fatherUserId']=$userName;
              $parentfoundArray1[$p1]['fatherPass']=$userPass;
              $parentfoundArray1[$p1]['rollNo']=$foundArray[$j]['rollNo'];
              $parentfoundArray1[$p1]['studentName']=$foundArray[$j]['firstName'];
              $parentfoundArray1[$p1]['className']=$foundArray[$j]['className'];
              $parentfoundArray1[$p1]['fatherName']=$foundArray[$j]['fatherName'];
              $parentfoundArray1[$p1]['check']="father";
              if(trim($json_val1)=='') {
                $json_val1 = json_encode($parentfoundArray1[$p1]);
              }
              else {
                 $json_val1 .= ','.json_encode($parentfoundArray1[$p1]);           
              }
              $p1++;  
*/              
              if(trim($json_val1)=='') {
                $json_val1 = $studentId;
              }
              else {
                 $json_val1 .= ','.$studentId;
              }
           }
           else {
              if($studentIdNotPassword=='') {
                 $studentIdNotPassword=$foundArray[$j]['studentId'];
              }
              else {
                 $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
              }
           }
        }                       
        else if($overwrite==0 && $fUserId!="" && $fcheck==1)  {   //  change the password for existing users.       
            // Father's User Id Create
           $rollNo = $foundArray[$j]['rollNo']; 
           if($fcheck==1 && $dateOfBirth != '0000-00-00' && $fatherName != NOT_APPLICABLE_STRING && $rollNo != NOT_APPLICABLE_STRING ) {
              $rollNo = trim(strtolower($rollNo));
              $yr = trim(substr($dateOfBirth,2,2));
              
              $userPass1 = substr($fatherName,0,stripos($fatherName," "));
              if($userPass1!="") {
                 $fatherName = $userPass1;  
              }
              $userPass = strtolower(trim($fatherName).trim($yr));
              
              $cond = " `user` SET userPassword = md5('".trim($userPass)."') WHERE userId = '".$fUserId."'";
              $returnStatus = $studentManager->updateParentPassword($cond);
              if($returnStatus===false) {
                echo FAILURE;
                die;
              }
              
             // Start   --   Check user role
             $foundUserRole = $studentManager->getUserRoleList(" AND ur.userId = ".$fUserId);
             if(count($foundUserRole) == 0 ) {
                // Insert User Role 
                $returnStatus = $studentManager->insertUserRole($fUserId,3); 
                if($returnStatus===false) {
                   echo FAILURE; 
                   die;
                }
             }
             // End    --   Check user role

/*              $parentfoundArray1[$p1]['fatherUserId']=$foundArray[$j]['fatherUserName'];   
              $parentfoundArray1[$p1]['fatherPass']=$userPass;
              $parentfoundArray1[$p1]['rollNo']=$foundArray[$j]['rollNo'];
              $parentfoundArray1[$p1]['studentName']=$foundArray[$j]['firstName'];
              $parentfoundArray1[$p1]['className']=$foundArray[$j]['className'];
              $parentfoundArray1[$p1]['fatherName']=$foundArray[$j]['fatherName'];
              $parentfoundArray1[$p1]['check']="father";
              
              if(trim($json_val1)=='') {
                $json_val1 = json_encode($parentfoundArray1[$p1]);
              }
              else {
                 $json_val1 .= ','.json_encode($parentfoundArray1[$p1]);           
              }
              $p1++;  
*/
              if(trim($json_val1)=='') {
                $json_val1 = $studentId;
              }
              else {
                 $json_val1 .= ','.$studentId;
              }
           }
           else {
              if($studentIdNotPassword=='') {
                 $studentIdNotPassword=$foundArray[$j]['studentId'];
              }
              else {
                 $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
              }
           }
        }
/*        else if(($overwrite==0 || $overwrite==1) && $fcheck==1)  {
           if($studentIdNotPassword=='') {
             $studentIdNotPassword=$foundArray[$j]['studentId'];
           }
           else {
             $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
           }
        }
*/        
          
        if($overwrite==1 && $mUserId=="" && $mcheck==1)  {   //  Do not change the password for existing users.    
           // Mother's User Id Create
           $rollNo = $foundArray[$j]['rollNo']; 
           if($mcheck==1 && $dateOfBirth != '0000-00-00' && $motherName != NOT_APPLICABLE_STRING && $rollNo != NOT_APPLICABLE_STRING ) {
              $rollNo = trim(strtolower($rollNo));
              $yr = trim(substr($foundArray[$j]['dateOfBirth'],2,2));
              
              $userName = "m".trim($rollNo);
 
              $userPass1 = substr($motherName,0,stripos($motherName," "));
              if($userPass1!="") {
                 $motherName = $userPass1;  
              }
              $userPass = strtolower(trim($motherName).trim($yr));
 
              $userUpdate = $studentManager->insertParentUserData($userName,$userPass);
              if($userUpdate===false) {
                 echo FAILURE;
                 die;
              }
              $userId=SystemDatabaseManager::getInstance()->lastInsertId();
              if($userId===false) {
                echo FAILURE;
                die;
              }
              
              $cond = " `student` SET motherUserId = '".$userId."' WHERE studentId = '".$studentId."'";   
              $returnStatus = $studentManager->updateParentPassword($cond);
              if($returnStatus===false) {
                echo FAILURE;
                die;
              }
              
             // Start   --   Check user role
             $foundUserRole = $studentManager->getUserRoleList(" AND ur.userId = ".$userId);
             if(count($foundUserRole) == 0 ) {
                // Insert User Role 
                $returnStatus = $studentManager->insertUserRole($userId,3); 
                if($returnStatus===false) {
                   echo FAILURE; 
                   die;
                }
             }
             // End    --   Check user role
              
              
/*              $parentfoundArray2[$m1]['motherUserId']=$userName;
              $parentfoundArray2[$m1]['motherPass']=$userPass;
              $parentfoundArray2[$m1]['rollNo']=$foundArray[$j]['rollNo'];
              $parentfoundArray2[$m1]['studentName']=$foundArray[$j]['firstName'];
              $parentfoundArray2[$m1]['className']=$foundArray[$j]['className'];
              $parentfoundArray2[$m1]['motherName']=$foundArray[$j]['motherName'];
              $parentfoundArray2[$m1]['check']="mother";
              
              if(trim($json_val2)=='') {
                $json_val2 = json_encode($parentfoundArray2[$m1]);
              }
              else {
                 $json_val2 .= ','.json_encode($parentfoundArray2[$m1]);           
              }
              $m1++;
*/
              if(trim($json_val2)=='') {
                $json_val2 = $studentId;
              }
              else {
                 $json_val2 .= ','.$studentId;           
              }
           }
           else {
              if($studentIdNotPassword=='') {
                 $studentIdNotPassword=$foundArray[$j]['studentId'];
              }
              else {
                 $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
              }
           }
        }
        else if($overwrite==0 && $mUserId!="" && $mcheck==1)  {   //  change the password for existing users.    
            // Mother's User Id Create
           $rollNo = $foundArray[$j]['rollNo']; 
           if($mcheck==1 && $dateOfBirth != '0000-00-00' && $motherName != NOT_APPLICABLE_STRING && $rollNo != NOT_APPLICABLE_STRING ) {
              $rollNo = trim(strtolower($rollNo));
              $yr = trim(substr($dateOfBirth,2,2));

              $userPass1 = substr($motherName,0,stripos($motherName," "));
              if($userPass1!="") {
                 $motherName = $userPass1;  
              }
              $userPass = strtolower(trim($motherName).trim($yr));

            
              $cond = " `user` SET userPassword = md5('".trim($userPass)."')  WHERE userId = '".$mUserId."'";
              $returnStatus = $studentManager->updateParentPassword($cond);
              if($returnStatus===false) {
                echo FAILURE;
                die;
              }
              
              // Start   --   Check user role
             $foundUserRole = $studentManager->getUserRoleList(" AND ur.userId = ".$mUserId);
             if(count($foundUserRole) == 0 ) {
                // Insert User Role 
                $returnStatus = $studentManager->insertUserRole($mUserId,3); 
                if($returnStatus===false) {
                   echo FAILURE; 
                   die;
                }
             }
             // End    --   Check user role
              
/*            $parentfoundArray2[$m1]['motherUserId']=$foundArray[$j]['motherUserName'];
              $parentfoundArray2[$m1]['motherPass']=$userPass;
              $parentfoundArray2[$m1]['rollNo']=$foundArray[$j]['rollNo'];
              $parentfoundArray2[$m1]['studentName']=$foundArray[$j]['firstName'];
              $parentfoundArray2[$m1]['className']=$foundArray[$j]['className'];
              $parentfoundArray2[$m1]['motherName']=$foundArray[$j]['motherName'];
              $parentfoundArray2[$m1]['check']="mother";
              
              if(trim($json_val2)=='') {
                $json_val2 = json_encode($parentfoundArray2[$m1]);
              }
              else {
                 $json_val2 .= ','.json_encode($parentfoundArray2[$m1]);           
              }
              $m1++;
*/
              if(trim($json_val2)=='') {
                $json_val2 = $studentId;
              }
              else {
                 $json_val2 .= ','.$studentId;
              }              
           }
           else {
              if($studentIdNotPassword=='') {
                 $studentIdNotPassword=$foundArray[$j]['studentId'];
              }
              else {
                 $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
              }
           }
        }
/*        else if(($overwrite==0 || $overwrite==1) && $mcheck==1)  {
           if($studentIdNotPassword=='') {
             $studentIdNotPassword=$foundArray[$j]['studentId'];
           }
           else {
             $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
           }
        }
*/        
        
        
        if($overwrite==1 && $gUserId==""  && $gcheck==1)  {   //  Do not change the password for existing users.    
           // Guardian's User Id Create
           $rollNo = $foundArray[$j]['rollNo']; 
           if($gcheck==1 && $dateOfBirth != '0000-00-00' && $guardianName != NOT_APPLICABLE_STRING && $rollNo != NOT_APPLICABLE_STRING ) {
              $rollNo = trim(strtolower($rollNo));
              $yr = trim(substr($foundArray[$j]['dateOfBirth'],2,2));
              
              $userName = "g".trim($rollNo);
              
              $userPass1 = substr($guardianName,0,stripos($guardianName," "));
              if($userPass1!="") {
                 $guardianName = $userPass1;  
              }
              $userPass = strtolower(trim($guardianName).trim($yr));

              $userUpdate = $studentManager->insertParentUserData($userName,$userPass);
              if($userUpdate===false) {
                 echo FAILURE;
                 die;
              }
              $userId=SystemDatabaseManager::getInstance()->lastInsertId();
              if($userId===false) {
                echo FAILURE;
                die;
              }
              
              $cond = " `student` SET guardianUserId = '".$userId."' WHERE studentId = '".$studentId."'";   
              $returnStatus = $studentManager->updateParentPassword($cond);
              if($returnStatus===false) {
                echo FAILURE;
                die;
              }
              
             // Start   --   Check user role
             $foundUserRole = $studentManager->getUserRoleList(" AND ur.userId = ".$userId);
             if(count($foundUserRole) == 0 ) {
                // Insert User Role 
                $returnStatus = $studentManager->insertUserRole($userId,3); 
                if($returnStatus===false) {
                   echo FAILURE; 
                   die;
                }
             }
             // End    --   Check user role

/*             $parentfoundArray3[$g1]['guardianUserId']=$userName;
             $parentfoundArray3[$g1]['guardianPass']=$userPass;
             $parentfoundArray3[$g1]['rollNo']=$foundArray[$j]['rollNo'];
             $parentfoundArray3[$g1]['studentName']=$foundArray[$j]['firstName'];
             $parentfoundArray3[$g1]['className']=$foundArray[$j]['className'];
             $parentfoundArray3[$g1]['guardianName']=$foundArray[$j]['guardianName'];
             $parentfoundArray3[$g1]['check']="guardian";
             
             if(trim($json_val3)=='') {
                $json_val3 = json_encode($parentfoundArray3[$g1]);
              }
              else {
                 $json_val3 .= ','.json_encode($parentfoundArray3[$g1]);           
              }
              $g1++;
*/
             if(trim($json_val3)=='') {
                $json_val3 = $studentId;
              }
              else {
                 $json_val3 .= ','.$studentId;
              }
           }
           else {
              if($studentIdNotPassword=='') {
                 $studentIdNotPassword=$foundArray[$j]['studentId'];
              }
              else {
                 $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
              }
           }
        }
        else if($overwrite==0 && $gUserId!="" && $gcheck==1)  {   //  change the password for existing users.    
            // Guardian's User Id Create
           $rollNo = $foundArray[$j]['rollNo']; 
           if($gcheck==1 && $dateOfBirth != '0000-00-00' && $guardianName != NOT_APPLICABLE_STRING && $rollNo != NOT_APPLICABLE_STRING ) {
              $rollNo = trim(strtolower($rollNo));
              $yr = trim(substr($dateOfBirth,2,2));

              $userPass1 = substr($guardianName,0,stripos($guardianName," "));
              if($userPass1!="") {
                 $guardianName = $userPass1;  
              }
              $userPass = strtolower(trim($guardianName).trim($yr));
              
              $cond = " `user` SET userPassword = md5('".trim($userPass)."')  WHERE userId = '".$gUserId."'";
              $returnStatus = $studentManager->updateParentPassword($cond);
              if($returnStatus===false) {
                echo FAILURE;
                die;
              }
              
              // Start   --   Check user role
             $foundUserRole = $studentManager->getUserRoleList(" AND ur.userId = ".$gUserId);
             if(count($foundUserRole) == 0 ) {
                // Insert User Role 
                $returnStatus = $studentManager->insertUserRole($gUserId,3); 
                if($returnStatus===false) {
                   echo FAILURE; 
                   die;
                }
             }
             // End    --   Check user role

/*                 $parentfoundArray3[$g1]['guardianUserId']=$foundArray[$j]['guardianUserName'];
                 $parentfoundArray3[$g1]['guardianPass']=$userPass;
                 $parentfoundArray3[$g1]['rollNo']=$foundArray[$j]['rollNo'];
                 $parentfoundArray3[$g1]['studentName']=$foundArray[$j]['firstName'];
                 $parentfoundArray3[$g1]['className']=$foundArray[$j]['className'];
                 $parentfoundArray3[$g1]['guardianName']=$foundArray[$j]['guardianName'];
                 $parentfoundArray3[$g1]['check']="guardian";
                 
                 if(trim($json_val3)=='') {
                   $json_val3 = json_encode($parentfoundArray3[$g1]);
                 }
                 else {
                   $json_val3 .= ','.json_encode($parentfoundArray3[$g1]);           
                 }     
                 $g1++;
*/
             if(trim($json_val3)=='') {
                $json_val3 = $studentId;
              }
              else {
                 $json_val3 .= ','.$studentId;
              }
           }
           else {
              if($studentIdNotPassword=='') {
                 $studentIdNotPassword=$foundArray[$j]['studentId'];
              }
              else {
                 $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
              }
           }
        }
/*        else if(($overwrite==0 || $overwrite==1) && gfcheck==1)  {
           if($studentIdNotPassword=='') {
             $studentIdNotPassword=$foundArray[$j]['studentId'];
           }
           else {
             $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
           }
        }
*/        
        
/*     // guardian Lgoin
        if($overwrite==0 && $fUserId=="" && $fcheck==1)  { 
           if($studentIdNotPassword=='') {
              $studentIdNotPassword=$foundArray[$j]['studentId'];
           }
           else {
              $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
           } 
        }
        if($overwrite==0 && $mUserId=="" && $mcheck==1)  { 
           if($studentIdNotPassword=='') {
              $studentIdNotPassword=$foundArray[$j]['studentId'];
           }
           else {
              $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
           } 
        }
        if($overwrite==0 && $gUserId=="" && $gcheck==1)  { 
           if($studentIdNotPassword=='') {
              $studentIdNotPassword=$foundArray[$j]['studentId'];
           }
           else {
              $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
           } 
        } */        
    }
    
   if ($onePassword == 2) {
        if($overwrite==1 && $fUserId=="" && $fcheck==1)  {   //  Do not change the password for existing users. 
           // Father's User Id Create
           $rollNo = $foundArray[$j]['rollNo']; 
           
           if($fcheck==1 && $fatherName != NOT_APPLICABLE_STRING && $rollNo != NOT_APPLICABLE_STRING ) {
              $rollNo = trim(strtolower($rollNo));
              $userName = "f".trim($rollNo);
              $userPass = trim($newPassword);
              
              $userUpdate = $studentManager->insertParentUserData($userName,$userPass);
              if($userUpdate===false) {
                 echo FAILURE;
                 die;
              }
              $userId=SystemDatabaseManager::getInstance()->lastInsertId();
              if($userId===false) {
                echo FAILURE;
                die;
              }

              $cond = " `student` SET fatherUserId = '".$userId."' WHERE studentId = '".$studentId."'";   
              $returnStatus = $studentManager->updateParentPassword($cond);
              if($returnStatus===false) {
                echo FAILURE;
                die;
              }
              
             // Start   --   Check user role
             $foundUserRole = $studentManager->getUserRoleList(" AND ur.userId = ".$userId);
             if(count($foundUserRole) == 0 ) {
                // Insert User Role 
                $returnStatus = $studentManager->insertUserRole($userId,3); 
                if($returnStatus===false) {
                   echo FAILURE; 
                   die;
                }
             }
             // End    --   Check user role
              
/*             $parentfoundArray1[$p1]['fatherUserId']=$userName;
             $parentfoundArray1[$p1]['fatherPass']=$userPass;
             $parentfoundArray1[$p1]['rollNo']=$foundArray[$j]['rollNo'];
             $parentfoundArray1[$p1]['studentName']=$foundArray[$j]['firstName'];
             $parentfoundArray1[$p1]['className']=$foundArray[$j]['className'];
             $parentfoundArray1[$p1]['fatherName']=$foundArray[$j]['fatherName'];
             $parentfoundArray1[$p1]['check']="father";
             
             if(trim($json_val1)=='') {
                $json_val1 = json_encode($parentfoundArray1[$p1]);
              }
              else {
                 $json_val1 .= ','.json_encode($parentfoundArray1[$p1]);           
              }
              $p1++;
*/
             if(trim($json_val1)=='') {
                $json_val1 = $studentId;
              }
              else {
                 $json_val1 .= ','.$studentId;
              }
           }
           else {
              if($studentIdNotPassword=='') {
                 $studentIdNotPassword=$foundArray[$j]['studentId'];
              }
              else {
                 $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
              }
           }
        }
        else if($overwrite==0 && $fUserId!="" && $fcheck==1)  {   //  change the password for existing users.    
            // Father's User Id Create
           $rollNo = $foundArray[$j]['rollNo']; 
           
           if($fcheck==1 && $fatherName != NOT_APPLICABLE_STRING && $rollNo != NOT_APPLICABLE_STRING ) {

              $userPass = trim($newPassword);
               
              $cond = " `user` SET userPassword = md5('".trim($userPass)."') WHERE userId = '".$fUserId."'";
              $returnStatus = $studentManager->updateParentPassword($cond);
              if($returnStatus===false) {
                echo FAILURE;
                die;
              }
              
             // Start   --   Check user role
             $foundUserRole = $studentManager->getUserRoleList(" AND ur.userId = ".$fUserId);
             if(count($foundUserRole) == 0 ) {
                // Insert User Role 
                $returnStatus = $studentManager->insertUserRole($fUserId,3); 
                if($returnStatus===false) {
                   echo FAILURE; 
                   die;
                }
             }
             // End    --   Check user role
              
              
/*             $parentfoundArray1[$p1]['fatherUserId']=$foundArray[$j]['fatherUserName'];
             $parentfoundArray1[$p1]['fatherPass']=$userPass;
             $parentfoundArray1[$p1]['rollNo']=$foundArray[$j]['rollNo'];
             $parentfoundArray1[$p1]['studentName']=$foundArray[$j]['firstName'];
             $parentfoundArray1[$p1]['className']=$foundArray[$j]['className'];
             $parentfoundArray1[$p1]['fatherName']=$foundArray[$j]['fatherName'];
             $parentfoundArray1[$p1]['check']="father";
             
              if(trim($json_val1)=='') {
                $json_val1 = json_encode($parentfoundArray1[$p1]);
              }
              else {
                 $json_val1 .= ','.json_encode($parentfoundArray1[$p1]);           
              }
              $p1++;
*/
             if(trim($json_val1)=='') {
                $json_val1 = $studentId;
              }
              else {
                 $json_val1 .= ','.$studentId;
              }
           }
           else {
              if($studentIdNotPassword=='') {
                 $studentIdNotPassword=$foundArray[$j]['studentId'];
              }
              else {
                 $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
              }
           }
        }
/*        else if(($overwrite==0 || $overwrite==1) && $fcheck==1)  {
           if($studentIdNotPassword=='') {
             $studentIdNotPassword=$foundArray[$j]['studentId'];
           }
           else {
             $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
           }
       }
*/        
        
        
        if($overwrite==1 && $mUserId=="" && $mcheck==1)  {   //  Do not change the password for existing users.    
           // Mother's User Id Create
           $rollNo = $foundArray[$j]['rollNo']; 
           if($mcheck==1 && $motherName != NOT_APPLICABLE_STRING && $rollNo != NOT_APPLICABLE_STRING ) {
              $rollNo = trim(strtolower($rollNo));
              
              $userName = "m".trim($rollNo);
              $userPass = trim($newPassword);
              
              $userUpdate = $studentManager->insertParentUserData($userName,$userPass);
              if($userUpdate===false) {
                 echo FAILURE;
                 die;
              }
              $userId=SystemDatabaseManager::getInstance()->lastInsertId();
              if($userId===false) {
                echo FAILURE;
                die;
              }

              $cond = " `student` SET motherUserId = '".$userId."' WHERE studentId = '".$studentId."'";   
              $returnStatus = $studentManager->updateParentPassword($cond);
              if($returnStatus===false) {
                echo FAILURE;
                die;
              }
              
             // Start   --   Check user role
             $foundUserRole = $studentManager->getUserRoleList(" AND ur.userId = ".$userId);
             if(count($foundUserRole) == 0 ) {
                // Insert User Role 
                $returnStatus = $studentManager->insertUserRole($userId,3); 
                if($returnStatus===false) {
                   echo FAILURE; 
                   die;
                }
             }
             // End    --   Check user role
              
              
              // Parent Login Create: UserId
/*             $parentfoundArray2[$m1]['motherUserId']=$userName;
             $parentfoundArray2[$m1]['motherPass']=$userPass;
             $parentfoundArray2[$m1]['rollNo']=$foundArray[$j]['rollNo'];
             $parentfoundArray2[$m1]['studentName']=$foundArray[$j]['firstName'];
             $parentfoundArray2[$m1]['className']=$foundArray[$j]['className'];
             $parentfoundArray2[$m1]['motherName']=$foundArray[$j]['motherName'];
             $parentfoundArray2[$m1]['check']="mother";
             
             if(trim($json_val2)=='') {
                $json_val2 = json_encode($parentfoundArray2[$m1]);
              }
              else {
                 $json_val2 .= ','.json_encode($parentfoundArray2[$m1]);           
              }
              $m1++;
*/
             if(trim($json_val2)=='') {
                $json_val2 = $studentId;
              }
              else {
                 $json_val2 .= ','.$studentId;
              }
              
           }
           else {
              if($studentIdNotPassword=='') {
                 $studentIdNotPassword=$foundArray[$j]['studentId'];
              }
              else {
                 $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
              }
           }
        }
        else if($overwrite==0 && $mUserId!="" && $mcheck==1)  {   //  change the password for existing users.    
            // Mother's User Id Create
           $rollNo = $foundArray[$j]['rollNo']; 
           if($mcheck==1 && $motherName != NOT_APPLICABLE_STRING && $rollNo != NOT_APPLICABLE_STRING ) {
              
              $userPass = trim($newPassword);
              
              $cond = " `user` SET userPassword = md5('".trim($userPass)."')  WHERE userId = '".$mUserId."'";
              $returnStatus = $studentManager->updateParentPassword($cond);
              if($returnStatus===false) {
                echo FAILURE;
                die;
              }
              
             // Start   --   Check user role
             $foundUserRole = $studentManager->getUserRoleList(" AND ur.userId = ".$mUserId);
             if(count($foundUserRole) == 0 ) {
                // Insert User Role 
                $returnStatus = $studentManager->insertUserRole($mUserId,3); 
                if($returnStatus===false) {
                   echo FAILURE; 
                   die;
                }
             }
             // End    --   Check user role
              
/*              // Parent Login Create: UserId
             $parentfoundArray2[$m1]['motherUserId']=$foundArray[$j]['motherUserName'];
             $parentfoundArray2[$m1]['motherPass']=$userPass;
             $parentfoundArray2[$m1]['rollNo']=$foundArray[$j]['rollNo'];
             $parentfoundArray2[$m1]['studentName']=$foundArray[$j]['firstName'];
             $parentfoundArray2[$m1]['className']=$foundArray[$j]['className'];
             $parentfoundArray2[$m1]['motherName']=$foundArray[$j]['motherName'];
             $parentfoundArray2[$m1]['check']="mother";
             if(trim($json_val2)=='') {
                $json_val2 = json_encode($parentfoundArray2[$m1]);
              }
              else {
                 $json_val2 .= ','.json_encode($parentfoundArray2[$m1]);           
              }
              $m1++;
*/
              if(trim($json_val2)=='') {
                $json_val2 = $studentId;
              }
              else {
                 $json_val2 .= ','.$studentId;
              }
           }
           else {
              if($studentIdNotPassword=='') {
                 $studentIdNotPassword=$foundArray[$j]['studentId'];
              }
              else {
                 $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
              }
           }
        }
/*        else if(($overwrite==0 || $overwrite==1) && $mcheck==1)  {
           if($studentIdNotPassword=='') {
             $studentIdNotPassword=$foundArray[$j]['studentId'];
           }
           else {
             $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
           }
       }
*/        
      
       if($overwrite==1 && $gUserId=="" && $gcheck==1)  {   //  Do not change the password for existing users.    
           // Guardian's User Id Create
           $rollNo = $foundArray[$j]['rollNo']; 
           if($gcheck==1 && $guardianName != NOT_APPLICABLE_STRING && $rollNo != NOT_APPLICABLE_STRING ) {
              $rollNo = trim(strtolower($rollNo));
              
              $userName = "g".trim($rollNo);
              $userPass = trim($newPassword);
              
              $userUpdate = $studentManager->insertParentUserData($userName,$userPass);
              if($userUpdate===false) {
                 echo FAILURE;
                 die;
              }
              $userId=SystemDatabaseManager::getInstance()->lastInsertId();
              if($userId===false) {
                echo FAILURE;
                die;
              }
             
              $cond = " `student` SET guardianUserId = '".$userId."' WHERE studentId = '".$studentId."'";   
              $returnStatus = $studentManager->updateParentPassword($cond);
              if($returnStatus===false) {
                echo FAILURE;
                die;
              }
              
              // Start   --   Check user role
             $foundUserRole = $studentManager->getUserRoleList(" AND ur.userId = ".$userId);
             if(count($foundUserRole) == 0 ) {
                // Insert User Role 
                $returnStatus = $studentManager->insertUserRole($userId,3); 
                if($returnStatus===false) {
                   echo FAILURE; 
                   die;
                }
             }
             // End    --   Check user role
              
              // Parent Login Create: UserId
/*             $parentfoundArray3[$g1]['guardianUserId']=$userName;
             $parentfoundArray3[$g1]['guardianPass']=$userPass;
             $parentfoundArray3[$g1]['rollNo']=$foundArray[$j]['rollNo'];
             $parentfoundArray3[$g1]['studentName']=$foundArray[$j]['firstName'];
             $parentfoundArray3[$g1]['className']=$foundArray[$j]['className'];
             $parentfoundArray3[$g1]['guardianName']=$foundArray[$j]['guardianName'];
             $parentfoundArray3[$g1]['check']="guardian";
             
              if(trim($json_val3)=='') {
                $json_val3 = json_encode($parentfoundArray3[$g1]);
              }
              else {
                 $json_val3 .= ','.json_encode($parentfoundArray3[$g1]);           
              }
              $g1++;
*/
             if(trim($json_val3)=='') {
                $json_val3 = $studentId;
              }
              else {
                 $json_val3 .= ','.$studentId;
              }
           }
           else {
              if($studentIdNotPassword=='') {
                 $studentIdNotPassword=$foundArray[$j]['studentId'];
              }
              else {
                 $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
              }
           }
        }
        else if($overwrite==0 && $gUserId!="" && $gcheck==1)  {   //  change the password for existing users.    
            // Guardian's User Id Create
           $rollNo = $foundArray[$j]['rollNo']; 
           if($gcheck==1 && $guardianName != NOT_APPLICABLE_STRING && $rollNo != NOT_APPLICABLE_STRING ) {

              $userPass = trim($newPassword);
              
              $cond = " `user` SET userPassword = md5('".$userPass."') WHERE userId = '".$gUserId."'";
              $returnStatus = $studentManager->updateParentPassword($cond);
              if($returnStatus===false) {
                echo FAILURE;
                die;
              }
              
               // Start   --   Check user role
             $foundUserRole = $studentManager->getUserRoleList(" AND ur.userId = ".$gUserId);
             if(count($foundUserRole) == 0 ) {
                // Insert User Role 
                $returnStatus = $studentManager->insertUserRole($gUserId,3); 
                if($returnStatus===false) {
                   echo FAILURE; 
                   die;
                }
             }
              
              // Parent Login Create: UserId
/*             $parentfoundArray3[$g1]['guardianUserId']=$foundArray[$j]['guardianUserName'];
             $parentfoundArray3[$g1]['guardianPass']=$userPass;
             $parentfoundArray3[$g1]['rollNo']=$foundArray[$j]['rollNo'];
             $parentfoundArray3[$g1]['studentName']=$foundArray[$j]['firstName'];
             $parentfoundArray3[$g1]['className']=$foundArray[$j]['className'];
             $parentfoundArray3[$g1]['guardianName']=$foundArray[$j]['guardianName'];
             $parentfoundArray3[$g1]['check']="guardian";
             if(trim($json_val3)=='') {
                $json_val3= json_encode($parentfoundArray3[$g1]);
              }
              else {
                 $json_val3.= ','.json_encode($parentfoundArray3[$g1]);           
              }
             $g1++;
*/
             if(trim($json_val3)=='') {
                $json_val3 = $studentId;
              }
              else {
                 $json_val3 .= ','.$studentId;
              }
           }
           else {
              if($studentIdNotPassword=='') {
                 $studentIdNotPassword=$foundArray[$j]['studentId'];
              }
              else {
                 $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
              }
            }
          }
/*          else if(($overwrite==0 || $overwrite==1) && $gcheck==1)  {
           if($studentIdNotPassword=='') {
             $studentIdNotPassword=$foundArray[$j]['studentId'];
           }
           else {
             $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
           }
         }
*/         
      }  
/*        // guardian Lgoin
        if($overwrite==0 && $fUserId=="" && $fcheck==1)  { 
           if($studentIdNotPassword=='') {
              $studentIdNotPassword=$foundArray[$j]['studentId'];
           }
           else {
              $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
           } 
        }
        if($overwrite==0 && $mUserId=="" && $mcheck==1)  { 
           if($studentIdNotPassword=='') {
              $studentIdNotPassword=$foundArray[$j]['studentId'];
           }
           else {
              $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
           } 
        }
        if($overwrite==0 && $gUserId=="" && $gcheck==1)  { 
           if($studentIdNotPassword=='') {
              $studentIdNotPassword=$foundArray[$j]['studentId'];
           }
           else {
              $studentIdNotPassword .=", ".$foundArray[$j]['studentId'];  
           } 
        } */        
   }
   if(SystemDatabaseManager::getInstance()->commitTransaction()) {
	 $errorMessage=SUCCESS;
   }
   else {
     $errorMessage=FAILURE;
     die;
   }
   /*
   if($studentIdNotPassword!="") {
        $conditions = " AND a.studentId IN (".$studentIdNotPassword.")";
        $foundArray = $studentManager->getStudentList($conditions,'',$orderBy);
                
        //$tableInfo .= "<div class='report'>For following list(s) login not created.</div><br>"; 
        $tableInfo .= "<table align='center' width='100%' border='0' class='anyid'>
                   <tr class='rowheading'> 
                     <td valign='top'>#</td>  
                     <td valign='top'>Student Name</td>
                     <td valign='top'>Class</td>
                     <td valign='top'>Roll No.</td>
                     <td valign='top'>Date of Birth</td>
                     <td valign='top'>Father's Name</td>
                     <td valign='top'>Mother's Name</td>
                     <td valign='top'>Guardian's Name</td>
                  </tr>";
         for($i=0; $i<count($foundArray); $i++) {   
              if($foundArray[$i]['rollNo']=="") {
                 $rollNo = NOT_APPLICABLE_STRING;
              }
              else {
                 $rollNo = $foundArray[$i]['rollNo'];
              }                     
              
              if($foundArray[$i]['DOB']=='0000-00-00') {
                 $dob = NOT_APPLICABLE_STRING; 
              }
              else {
                 $dob =  UtilityManager::formatDate($foundArray[$i]['DOB']);
              }                     
              
              if($foundArray[$i]['fatherName']==NOT_APPLICABLE_STRING) {
                 $fatherName = NOT_APPLICABLE_STRING; 
              }
              else {
                 $fatherName = $foundArray[$i]['fatherName'];
              }                     
              
              if($foundArray[$i]['motherName']==NOT_APPLICABLE_STRING) {
                 $motherName= NOT_APPLICABLE_STRING; 
              }
              else {
                 $motherName = $foundArray[$i]['motherName'];
              }                     
              
              if($foundArray[$i]['guardianName']==NOT_APPLICABLE_STRING) {
                 $guardianName =NOT_APPLICABLE_STRING; 
              }
              else {
                 $guardianName = $foundArray[$i]['guardianName'];
              }                     
              $bg = $bg =='row0' ? 'row1' : 'row0'; 
              $tableInfo .= "<tr class=".$bg."> 
                               <td valign='top'>".($i+1)."</td>
                               <td valign='top'>".$foundArray[$i]['firstName']."</td>
                               <td valign='top'>".$foundArray[$i]['className']."</td>
                               <td valign='top'>".$rollNo."</td>
                               <td valign='top'>".$dob."</td>
                               <td valign='top'>".$fatherName."</td>
                               <td valign='top'>".$motherName."</td>
                               <td valign='top'>".$guardianName."</td>
                            </tr>"; 
         }
         
         if($i!=0) {
           $tableInfo .= "</table>"; 
           //die; 
         }    
   }
   */

   //echo $errorMessage.'!~!~!'.$tableInfo.'!~!~!'.'{"$parentfoundArray1":"'.$json_val1.'","$parentfoundArray2":"'.$json_val2.'","$parentfoundArray3" : ['.$json_val3.']}'; 
   echo $errorMessage.'!~!~!'.$studentIdNotPassword.'!~!~!'.$json_val1.'!~!~!'.$json_val2.'!~!~!'.$json_val3;  
}
else {
  echo FAILURE;
  die;
}


//$History: ajaxCreatePassword.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 1/29/10    Time: 11:48a
//Updated in $/LeapCC/Library/CreateParentLogin
//set_time_limit added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/25/10    Time: 3:16p
//Updated in $/LeapCC/Library/CreateParentLogin
//validation format update (father/Mother/guardian userId '0' check
//updated)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/28/09    Time: 4:11p
//Created in $/LeapCC/Library/CreateParentLogin
//initial checkin
//

?>