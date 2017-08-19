<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE user_role table
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(MODEL_PATH . '/UserRoleManager.inc.php');

echo "<title>Run This File Only Once.......ONCE...</title>";

echo  "<h1>Run This File Only Once.......ONCE...</h1>";

//die(ACCESS_DENIED);


if(SystemDatabaseManager::getInstance()->startTransaction()) {

    $userMgr=UserRoleManager::getInstance();

   //*****************EMPLOYEE PART*********************
   //get employees from employee table
   $empArray=$userMgr->getEmployees();
   if(is_array($empArray) and count($empArray)>0){
     foreach($empArray as $emp){
         $empId=$emp['employeeId'];
         $userId=$emp['userId'];
         $roleId=$emp['roleId'];
         $empInstituteId=$emp['instituteId'];

         //finding emp records from emp_can_teache_in
         $empCanTeacheInArray=$userMgr->getEmployeeCanTeachIn($empId);
         if(is_array($empCanTeacheInArray) and count($empCanTeacheInArray)>0){
             foreach($empCanTeacheInArray as $empCan){
                 $instituteId=$empCan['instituteId'];
                 //check in user_role #1
                 $userRoleArray=$userMgr->getEmpUserRoles($userId);
                 if(is_array($userRoleArray) and count($userRoleArray)>0){
                     foreach($userRoleArray as $userRole){
                         $genRoleId=$userRole['roleId'];
                         $genInstituteId=$userRole['instituteId'];
                         if($genInstituteId==-1){
                             //update query
                             $upRet=$userMgr->updateUserRole($userId,$genRoleId,$instituteId);
                             if($upRet==false){
                               echo FAILURE;
                               die;
                             }
                         }
                         else{
                              $insRet=$userMgr->insertUserRole($userId,$genRoleId,$instituteId);
                              if($insRet==false){
                                echo FAILURE;
                                die;
                              }
                         }
                     }
                 }
                 //check in user_role #2
                 $userRoleArray2=$userMgr->getEmpUserRoles($userId,$roleId,$instituteId);
                 if(count($userRoleArray2)==0){
                     //insert query
                     $insRet=$userMgr->insertUserRole($userId,$roleId,$instituteId);
                     if($insRet==false){
                         echo FAILURE;
                         die;
                     }
                 }
             }
         }
         else{ //if record is not in employee_can_teach_in
             //check in user_role #1
             $userRoleArray=$userMgr->getEmpUserRoles($userId);
             if(is_array($userRoleArray) and count($userRoleArray)>0){
                 foreach($userRoleArray as $userRole){
                     $genRoleId=$userRole['roleId'];
                     $genInstituteId=$userRole['instituteId'];
                     if($genInstituteId==-1){
                         //update query
                         $upRet=$userMgr->updateUserRole($userId,$genRoleId,$empInstituteId);
                         if($upRet==false){
                             echo FAILURE;
                             die;
                         }
                     }
                     else{
                         $insRet=$userMgr->insertUserRole($userId,$genRoleId,$empInstituteId);
                         if($insRet==false){
                          echo FAILURE;
                          die;
                         }
                     }
                 }
             }
             //check in user_role #2
             $userRoleArray2=$userMgr->getEmpUserRoles($userId,$roleId,$empInstituteId);
             if(count($userRoleArray2)==0){
                 //insert query
                 $insRet=$userMgr->insertUserRole($userId,$roleId,$empInstituteId);
                 if($insRet==false){
                     echo FAILURE;
                     die;
                 }
             }

         }
      }
   }

   //for users that are not in employee table
   $userArray=$userMgr->getUsersWhoAreNotEmployees();
   foreach($userArray as $users){
       //check in user_role
       $chkArray=$userMgr->checkUserRoles(' WHERE userId='.$users['userId']);
       if($chkArray[0]['cnt']==0){
         //insert into user_role
         $insRet=$userMgr->insertUserRole($users['userId'],$users['roleId'],$users['instituteId']);
         if($insRet==false){
             echo FAILURE;
             die;
         }
       }
   }

   //*****************STUDENT PART*********************
   $studentArray=$userMgr->getStudentInfo();
   foreach($studentArray as $studentInfo){
     //insert query
     $insRet=$userMgr->insertUserRole($studentInfo['userId'],4,$studentInfo['instituteId']);
     if($insRet==false){
         echo FAILURE;
         die;
     }
   }

   //*****************PARENT PART*********************
   $parentArray=$userMgr->getParentInfo('Father');
   foreach($parentArray as $parentInfo){
     //insert query
     $insRet=$userMgr->insertUserRole($parentInfo['userId'],3,$parentInfo['instituteId']);
     if($insRet==false){
         echo FAILURE;
         die;
     }
   }

   $parentArray=$userMgr->getParentInfo('Mother');
   foreach($parentArray as $parentInfo){
     //insert query
     $insRet=$userMgr->insertUserRole($parentInfo['userId'],3,$parentInfo['instituteId']);
     if($insRet==false){
         echo FAILURE;
         die;
     }
   }

   $parentArray=$userMgr->getParentInfo('Guardian');
   foreach($parentArray as $parentInfo){
     //insert query
     $insRet=$userMgr->insertUserRole($parentInfo['userId'],3,$parentInfo['instituteId']);
     if($insRet==false){
         echo FAILURE;
         die;
     }
   }

// die('dipanjan');

  if(SystemDatabaseManager::getInstance()->commitTransaction()) {
   echo SUCCESS;
   die;
  }
  else {
   echo FAILURE;
   die;
  }
 }
 else {
  echo FAILURE;
  die;
 }

?>