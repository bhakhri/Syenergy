<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To delete attendance records
// Author : Dipanjan Bbhattacharjee
// Created on : (14.04.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','DeleteAttendance');
    define('ACCESS','delete');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
    $adminTasksManager = AdminTasksManager::getInstance();
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    
   if(SystemDatabaseManager::getInstance()->startTransaction()) {   //start the transaction
    
        //Checking purity of input data  
        if($REQUEST_DATA['strId']==''){
            echo FAILURE;
            die;
        }   
        $reqData=explode(',',$REQUEST_DATA['strId']);
        $len=count($reqData);
        for($i=0;$i<$len;$i++){
          $req=explode('~',$reqData[$i]);
          if(count($req)!= 8){
              echo FAILURE;
              die;
          }
            //insert data into quarantine table
            $ret1=$adminTasksManager->qurantineAttendance($reqData[$i]);
            if($ret1===false){
               echo FAILURE;
               die; 
            }

            //delete data from main attendance table
            $ret2=$adminTasksManager->deleteAttendance($reqData[$i]);
            if($ret2===false){
               echo FAILURE;
               die; 
            }
            
            $attType=$req[6];
            if($attType==2){//for daily attendance ,update duty leave records also
             $classId=$req[1];
             $subjectId=$req[2];
             $groupId=$req[3];
             $dutyDate=$req[4];
             $periodId=$req[7];
             $sessionId=$sessionHandler->getSessionVariable('SessionId');
             $instituteId=$sessionHandler->getSessionVariable('InstituteId');
             
             $ret3=$adminTasksManager->updateDutyLeave($classId,$subjectId,$groupId,$dutyDate,$periodId,$sessionId,$instituteId);
             if($ret3==false){
               die(FAILURE);
             }
            }
        }
        
       if(SystemDatabaseManager::getInstance()->commitTransaction()){ //commit transaction
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
// for VSS
// $History: ajaxDeleteAttendance.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/AdminTasks
//Added Role Permission Variables
//
//*****************  Version 2  *****************
//User: Administrator Date: 5/06/09    Time: 15:12
//Updated in $/LeapCC/Library/AdminTasks
//Corrected attendance deletion module's logic
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 14/04/09   Time: 17:21
//Created in $/LeapCC/Library/AdminTasks
//Created Attendance Delete Module
?>