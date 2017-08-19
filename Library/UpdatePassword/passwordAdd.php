<?php
//-------------------------------------------------------
// Purpose: to design the layout for add subject to class
//
// Author : Jaineesh
// Created on : (30.05.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UpdatePasswordReport');
define('ACCESS','add');

UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/StudentManager.inc.php");
$scStudentUpdateManager = StudentManager::getInstance();

// CSV data field Comments added 
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return $comments; 
         }
    }      

        
   
        $chb  = $REQUEST_DATA['chb'];
        $cnt = count($chb);
        $onePassword = $REQUEST_DATA['onePassword'];
        $studentIds = add_slashes($REQUEST_DATA['studentCheckIds']); 
        $value = "";
        for ($i=0;$i<$cnt;$i++) {
            $querySeprator = '';
            if($value!='') {
                    $querySeprator = ",";
                }
            $value .= "$querySeprator ".$chb[$i]."";
        }
        $conditions = " AND a.studentId IN (".$studentIds.")";
        
        
    
        // Sorting Order
       $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
       $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
       $orderBy=" $sortField $sortOrderBy"; 
   

   // END: search filter 
    //$foundArray = $scStudentUpdateManager->getStudentRecord($value);
    $foundArray = $scStudentUpdateManager->getStudentList($conditions,'',$orderBy); 
    $count = count($foundArray);


//$foundArray1 = $scStudentUpdateManager->getUserRecord($value);
//****************************************************************************************************************    
//***********************************************STRAT TRANSCATION************************************************
//****************************************************************************************************************

$userNameFormat = $REQUEST_DATA['userNameFormat'];     
$changeUserName= ($REQUEST_DATA['changeUserName']);   
$classId = ($REQUEST_DATA['classId']);

if($changeUserName=='') {
  $changeUserName=0;  
}

if($classId=='') {
   $classId=0;
}

$classNameArray = $scStudentUpdateManager->getSingleField('class', 'className', "WHERE classId  = $classId");
$className = $classNameArray[0]['className'];

$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);   

$csvHead = "";   
$csvHead .= "For,".parseCSVComments($className);
$csvHead .= "\n";
$csvHead .= "As On,".parseCSVComments($formattedDate);
$csvHead .= "\n";
$csvHead .= "#,Reg. No.,Univ. Roll No.,Roll No.,Name,Email,Old Username,New Username,Password";
$csvHead .= "\n";

$csvData =  $csvHead;

$notFound='';
if(SystemDatabaseManager::getInstance()->startTransaction()) {
  for($j=0;$j<$count;$j++) {
      $oldUserName =  $foundArray[$j]['userName'];      
      $regNo = $foundArray[$j]['regNo'];
      $universityRollNo = $foundArray[$j]['universityRollNo'];
      $rollNo = $foundArray[$j]['rollNo']; 
      $email1 = explode('@',$foundArray[$j]['studentEmail']);
      $email = $email1[0];
      $studentName = $foundArray[$j]['studentName']; 
      $studentNameBatch = $foundArray[$j]['studentNameBatch']; 
      
      if($userNameFormat==1) {
          $userName = $regNo;
      }
      else if($userNameFormat==2) {
          $userName = $universityRollNo;
      }
      else if($userNameFormat==3) {
          $userName = $rollNo;      
      }
      else if($userNameFormat==4) {
          $userName = $email;      
      }
      else if($userNameFormat==5) {
          $userName = $studentNameBatch;      
      }
      
      $userName = strtolower($userName);
      
      if ($onePassword == 1) {
            $studentId = $foundArray[$j]['studentId'];          
            $userId = $foundArray[$j]['userId'];          
            $firstName = $foundArray[$j]['firstName1'];
            $dateOfBirth = $foundArray[$j]['DOB'];
            if($changeUserName==1) {
               if($userId!='') {
                 $userName = $oldUserName;
                 $dupUserName = $userName;
               }
            }
            
            if($changeUserName==0) {                              
              $resultArray = $scStudentUpdateManager->getSingleField('`user`','username'," WHERE upper(userName)=upper('$userName') ");
              if(is_array($resultArray) && count($resultArray)>0) {
                $dupUserName = $userName;  
                $userName=NOT_APPLICABLE_STRING; 
              }
            }
            
            if($userName==NOT_APPLICABLE_STRING) {
               if($notFound =='') {
                 $notFound = $studentId;
               }
               else {
                 $notFound .=",".$studentId;               
               }   
               $userName=NOT_APPLICABLE_STRING;
               $pass=''; 
            }
            else {
                $dateOfBirth = explode('-',$dateOfBirth);
                $pass = trim($firstName).trim($dateOfBirth[0]);
                if($userId=='' && $userName != NOT_APPLICABLE_STRING) {
                     // Duplicate User Check Start
                      $resultArray = $scStudentUpdateManager->getSingleField('`user`','username'," WHERE upper(userName)=upper('$userName') ");
                      if(is_array($resultArray) && count($resultArray)>0) {
                         $dupUserName = $userName;   
                         $userName=NOT_APPLICABLE_STRING; 
                      }
                      if($userName==NOT_APPLICABLE_STRING) {
                           if($notFound =='') {
                             $notFound = $studentId;
                           }
                           else {
                             $notFound .=",".$studentId;               
                           }   
                           $userName=NOT_APPLICABLE_STRING;
                           $pass=''; 
                      }  
                      
                      // Duplicate User Check End
                      if($userName!=NOT_APPLICABLE_STRING) {
                          
                          $userUpdate = $scStudentUpdateManager->insertUserData($userName,md5($pass));
                          if($userUpdate===false) {
                            echo FAILURE.'!~!~!'.'';
                            die;
                          }
                          $userId=SystemDatabaseManager::getInstance()->lastInsertId();
                          if($userId===false) {
                            echo FAILURE.'!~!~!'.'';
                            die;
                          }
                          $returnStatus = $scStudentUpdateManager->updateStudentRecord($userId,$rollNo);
                          if($returnStatus===false) {
                            echo FAILURE.'!~!~!'.'';
                            die;
                          }
                          // Start   --   Check user role
                          $foundUserRole = $scStudentUpdateManager->getUserRoleList(" AND ur.userId = ".$userId);
                          if(count($foundUserRole) == 0 ) {
                            // Insert User Role 
                            $returnStatus = $scStudentUpdateManager->insertUserRole($userId,4); 
                            if($returnStatus===false) {
                                echo FAILURE.'!~!~!'.''; 
                               die;
                            }
                          }
                      }
                      // End    --   Check user role
                }
                else {
                  if($userName != NOT_APPLICABLE_STRING) {
                     $userUpdate = $scStudentUpdateManager->updateUserData(md5($pass),$userId,$userName);    
                     if($userUpdate===false) {
                         echo FAILURE.'!~!~!'.''; 
                        die;
                     }
                     // Start   --   Check user role
                     $foundUserRole = $scStudentUpdateManager->getUserRoleList(" AND ur.userId = ".$userId);
                     if(count($foundUserRole) == 0 ) {
                        // Insert User Role 
                        $returnStatus = $scStudentUpdateManager->insertUserRole($userId,4); 
                        if($returnStatus===false) {
                           echo FAILURE.'!~!~!'.''; 
                           die;
                        }
                     }
                     // End    --   Check user role
                  }
                }
                if($userUpdate===false) {
                   echo FAILURE.'!~!~!'.'';  
                   die;
                }
            }
        }

        if ($onePassword == 2) {
            $studentId = $foundArray[$j]['studentId'];          
            $userId = $foundArray[$j]['userId'];          
            $firstName = $foundArray[$j]['firstName1'];
            $dateOfBirth = $foundArray[$j]['DOB'];
            
            $pass = trim($REQUEST_DATA['newPassword']);

            if($changeUserName==1) {
               if($userId!='') {
                 $dupUserName = $userName;  
                 $userName = $oldUserName;
               }
            }
           
            if($changeUserName==0) {
              $resultArray = $scStudentUpdateManager->getSingleField('`user`','username'," WHERE upper(userName)=upper('$userName') ");
              if(is_array($resultArray) && count($resultArray)>0) {
                $dupUserName = $userName;  
                $userName=NOT_APPLICABLE_STRING; 
              }
            }
            
            if($userName==NOT_APPLICABLE_STRING) {
               if($notFound =='') {
                 $notFound = $studentId;
               }
               else {
                 $notFound .=",".$studentId;               
               }  
               $userName=NOT_APPLICABLE_STRING;
               $pass=''; 
            }
            else {
                if($userId=='') {  
                     // Duplicate User Check Start
                      $resultArray = $scStudentUpdateManager->getSingleField('`user`','username'," WHERE upper(userName)=upper('$userName') ");
                      if(is_array($resultArray) && count($resultArray)>0) {
                         $dupUserName = $userName;  
                         $userName=NOT_APPLICABLE_STRING; 
                      }
                      if($userName==NOT_APPLICABLE_STRING) {
                           if($notFound =='') {
                             $notFound = $studentId;
                           }
                           else {
                             $notFound .=",".$studentId;               
                           }   
                           $userName=NOT_APPLICABLE_STRING;
                           $pass=''; 
                      }  
                      
                      // Duplicate User Check End
                      if($userName!=NOT_APPLICABLE_STRING) {
                            $userUpdate = $scStudentUpdateManager->insertUserData($userName,md5($pass));
                            if($userUpdate===false) {
                                echo FAILURE.'!~!~!'.'';
                                die;
                            }    
                            $userId=SystemDatabaseManager::getInstance()->lastInsertId();
                            if($userId===false) {
                                echo FAILURE.'!~!~!'.'';
                                die;
                            }
                            $returnStatus = $scStudentUpdateManager->updateStudentRecord($userId,$rollNo); 
                            if($returnStatus===false) {
                                echo FAILURE.'!~!~!'.'';
                                die;
                            }
                            // Start   --   Check user role
                             $foundUserRole = $scStudentUpdateManager->getUserRoleList(" AND ur.userId = ".$userId);
                             if(count($foundUserRole) == 0 ) {
                                // Insert User Role 
                                $returnStatus = $scStudentUpdateManager->insertUserRole($userId,4); 
                                if($returnStatus===false) {
                                   echo FAILURE.'!~!~!'.''; 
                                   die;
                                }
                             }
                             // End    --   Check user role
                      }
                }
                else{
                   $userUpdate = $scStudentUpdateManager->updateUserData(md5($pass),$userId,$userName);    
                   if($userUpdate===false) {
                      echo FAILURE.'!~!~!'.'';
                      die;
                   }
                   // Start   --   Check user role
                     $foundUserRole = $scStudentUpdateManager->getUserRoleList(" AND ur.userId = ".$userId);
                     if(count($foundUserRole) == 0 ) {
                        // Insert User Role 
                        $returnStatus = $scStudentUpdateManager->insertUserRole($userId,4); 
                        if($returnStatus===false) {
                           echo FAILURE.'!~!~!'.''; 
                           die;
                        }
                     }
                     // End    --   Check user role
                }
                if($userUpdate===false) {
                    echo FAILURE.'!~!~!'.'';
                    die;
                }
            }
          }
            
          if($onePassword == 3) {
            $pass = substr('c'.date('YmdHis').microtime(),10,20);
            $studentId = $foundArray[$j]['studentId'];          
            $userId = $foundArray[$j]['userId'];          
            $firstName = $foundArray[$j]['firstName1'];
            $dateOfBirth = $foundArray[$j]['DOB'];
            
            $dupUserName = "";
            if($changeUserName==1) {
               if($userId!='') {
                 $userName = $oldUserName;
                 $dupUserName = $userName;
               }
            }
            
            if($changeUserName==0) {
              $resultArray = $scStudentUpdateManager->getSingleField('`user`','username'," WHERE upper(userName)=upper('$userName') ");
              if(is_array($resultArray) && count($resultArray)>0) {
                $dupUserName = $userName;  
                $userName=NOT_APPLICABLE_STRING; 
              }
            }
            
            if($userName==NOT_APPLICABLE_STRING) {
               if($notFound =='') {
                 $notFound = $studentId;
               }
               else {
                 $notFound .=",".$studentId;               
               }  
               $userName=NOT_APPLICABLE_STRING;
               $pass='';  
            }
            else {
                if($userId==''){
                      // Duplicate User Check Start
                      $resultArray = $scStudentUpdateManager->getSingleField('`user`','username'," WHERE upper(userName)=upper('$userName') ");
                      if(is_array($resultArray) && count($resultArray)>0) {
                         $dupUserName = $userName;   
                         $userName=NOT_APPLICABLE_STRING; 
                      }
                      if($userName==NOT_APPLICABLE_STRING) {
                           if($notFound =='') {
                             $notFound = $studentId;
                           }
                           else {
                             $notFound .=",".$studentId;               
                           }   
                           $userName=NOT_APPLICABLE_STRING;
                           $pass=''; 
                      }  
                      
                      // Duplicate User Check End
                      if($userName!=NOT_APPLICABLE_STRING) {
                            
                            $userUpdate = $scStudentUpdateManager->insertUserData($userName,md5($pass));
                            if($userUpdate===false) {
                              echo FAILURE.'!~!~!'.'';
                              die;
                            }   
                            $userId=SystemDatabaseManager::getInstance()->lastInsertId();
                            if($userId===false) {
                                echo FAILURE.'!~!~!'.'';
                                die;
                            }
                           $returnStatus = $scStudentUpdateManager->updateStudentRecord($userId,$rollNo); 
                            if($returnStatus===false) {
                                echo FAILURE.'!~!~!'.'';
                                die;
                            }
                             // Start   --   Check user role
                             $foundUserRole = $scStudentUpdateManager->getUserRoleList(" AND ur.userId = ".$userId);
                             if(count($foundUserRole) == 0 ) {
                                // Insert User Role 
                                $returnStatus = $scStudentUpdateManager->insertUserRole($userId,4); 
                                if($returnStatus===false) {
                                   echo FAILURE.'!~!~!'.''; 
                                   die;
                                }
                             }
                             // End    --   Check user role
                      }
                }
                else{
                   $userUpdate = $scStudentUpdateManager->updateUserData(md5($pass),$userId,$userName);    
                   if($userUpdate===false) {
                     echo FAILURE.'!~!~!'.'';
                     die;
                   }
                    // Start   --   Check user role
                     $foundUserRole = $scStudentUpdateManager->getUserRoleList(" AND ur.userId = ".$userId);
                     if(count($foundUserRole) == 0 ) {
                        // Insert User Role 
                        $returnStatus = $scStudentUpdateManager->insertUserRole($userId,4); 
                        if($returnStatus===false) {
                           echo FAILURE.'!~!~!'.''; 
                           die;
                        }
                     }
                     // End    --   Check user role
                }
                if($userUpdate===false) {
                    echo FAILURE.'!~!~!'.'';
                    die;
                }
            }
          }  
          
          $csvData .= ($j+1).','.parseCSVComments(trim($regNo)).','. parseCSVComments(trim($universityRollNo)).'';
          $csvData .= ','.parseCSVComments(trim($rollNo)).','.parseCSVComments(trim($studentName));
          $csvData .= ','.parseCSVComments(trim($email)).','.parseCSVComments(trim($oldUserName));
          if($userName==NOT_APPLICABLE_STRING) {
            $csvData .= ",User name $dupUserName already exist";
          }
          else {
            $csvData .= ','.trim($userName).','.trim($pass);  
          }
          $csvData .="\n";
          $dupUserName = "";
    }
    
    if(SystemDatabaseManager::getInstance()->commitTransaction()) {
      $errorMessage = SUCCESS;
    }
    else {
     $csvData = $csvHead;   
     $csvData .=  FAILURE; 
     echo FAILURE.'!~!~!'.'';  
   }
   echo $errorMessage.'!~!~!'.$csvData;  
   die;
}
else {
  $csvData = $csvHead;   
  $csvData .=  FAILURE; 
  $errorMessage  =  FAILURE;
  echo $errorMessage.'!~!~!'.$csvData;  
  die;
}



//$History: passwordAdd.php $
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 11/13/09   Time: 2:34p
//Updated in $/LeapCC/Library/UpdatePassword
//added 'parseCSVComments' on roll no. field
//
//*****************  Version 8  *****************
//User: Gurkeerat    Date: 11/12/09   Time: 5:01p
//Updated in $/LeapCC/Library/UpdatePassword
//username checks updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 11/12/09   Time: 12:31p
//Updated in $/LeapCC/Library/UpdatePassword
//sorting order updated
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 11/12/09   Time: 12:12p
//Updated in $/LeapCC/Library/UpdatePassword
//updated code to resolve issues
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 11/11/09   Time: 6:29p
//Updated in $/LeapCC/Library/UpdatePassword
//resolved issues: 1967, 1968, 1969, 1971, 1972, 1980, 1981
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 11/06/09   Time: 1:46p
//Updated in $/LeapCC/Library/UpdatePassword
//Updated code to modify 'Generate student login'
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/09/09    Time: 2:07p
//Updated in $/LeapCC/Library/UpdatePassword
//replicate of bug Nos.3,4 in cc
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/01/09    Time: 3:26p
//Updated in $/LeapCC/Library/UpdatePassword
//changes as per leap cc
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/01/09    Time: 1:14p
//Created in $/LeapCC/Library/UpdatePassword
//copy files from sc
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/30/09    Time: 6:20p
//Updated in $/Leap/Source/Library/ScUpdatePassword
//optimization in code
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/30/09    Time: 3:37p
//Created in $/Leap/Source/Library/ScUpdatePassword
//new ajax file to show student list & save student user name, password
//

?>