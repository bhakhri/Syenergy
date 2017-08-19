<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/FeeCycleClassesManager.inc.php");
    define('MODULE','FeeCycleClasses');
    define('ACCESS','add');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    $feeCycleClassesManager = FeeCycleClassesManager::getInstance(); 

  
    global $sessionHandler;

    $sessionId = $sessionHandler->getSessionVariable('SessionId');
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
	 
    $saveClassId = $REQUEST_DATA['saveClassId']; 
    $cancelClassId = $REQUEST_DATA['cancelClassId']; 
    $feeCycleId = $REQUEST_DATA['feeCycleId']; 
    
    $errorMessage = '';
    
    if($feeCycleId=='') {
       echo FEE_CYCLE;
       die;
    }
    
    $id='';   
    if($cancelClassId!='') {
        $condition = " feeClassId IN ($cancelClassId) AND feeCycleId IN ($feeCycleId) ";
        $foundArray = $feeCycleClassesManager->getCheckFeeReceipt($condition); 
        $cnt = count($foundArray);
        for($i = 0; $i < $cnt; $i++) {     
           $classId = $foundArray[$i]['classId'];
           if($id=='') {
             $id =$classId;
           }
           else {
             $id .="~~".$classId;
           }
        }
    }
    
    
    if($saveClassId!='') {
        $condition = " AND classId IN ($saveClassId) AND feeCycleId != $feeCycleId";
        $foundArray = $feeCycleClassesManager->getCheckFeeCycleClasses($condition); 
        $cnt = count($foundArray);
        for($i = 0; $i < $cnt; $i++) {     
           $classId = $foundArray[$i]['classId'];
           if($id=='') {
             $id =$classId;
           }
           else {
             $id .="~~".$classId;
           }
        }
    }
    
    if($id!='') {
      echo $id;
      die;  
    }
    
    $condition ='';
    if (trim($errorMessage) == '') {
          //****************************************************************************************************************    
          //***********************************************STRAT TRANSCATION************************************************
          //****************************************************************************************************************
          if(SystemDatabaseManager::getInstance()->startTransaction()) {
              
              if($saveClassId!='') {
                  $classArray = explode(',',$saveClassId);     
                  if(count($classArray)>0) {
                     //`instituteId`,`sessionId`, `feeCycleId`,`classId` 
                     for($i = 0; $i < count($classArray); $i++) { 
                        $classId = $classArray[$i]; 
                        if($str=='') {
                          $str = "($instituteId,$sessionId,$feeCycleId,$classId)";
                        } 
                        else {
                          $str .= ",($instituteId,$sessionId,$feeCycleId,$classId)";  
                        }
                     }
                  }
              }
          
              $classId='';
              if($cancelClassId!='') {
                  $classArray = explode(',',$cancelClassId);     
                  if(count($classArray)>0) {  
                     $classId='-1'; 
                     for($i = 0; $i < count($classArray); $i++) { 
                        $classId .= ",".$classArray[$i];
                     }
                  }
              }
              
              if($str!='') {
                  $returnStatus = $feeCycleClassesManager->addFeeCylceClasses($str);
                  if($returnStatus === false) {
                    $errorMessage = FAILURE; 
                    die;
                  }
                  $recordStatus=1;
              }
              
              if($classId!='') { 
                  $condition = " AND classId IN ($classId) AND feeCycleId = $feeCycleId";
                  $returnStatus =  $feeCycleClassesManager->deleteFeeCylceClasses($condition);  
                  if($returnStatus === false) {
                    $errorMessage = FAILURE; 
                    die;
                  }
                  $recordStatus=2;
              }
                
              //*****************************COMMIT TRANSACTION************************* 
              if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                 $errorMessage = SUCCESS;
                 if($recordStatus==1) {
                   $errorMessage = FEE_CYCLE_CLASS_ADDED_SUCCESSFULLY;  
                 }
                 else if($recordStatus==2) { 
                   $errorMessage = FEE_CYCLE_CLASS_UPDATE_SUCCESSFULLY;
                 }
                 else if($recordStatus==3) {
                   $errorMessage = FEE_CYCLE_CLASS_DELETE_SUCCESSFULLY;  
                 }
              }
              else {
                 $errorMessage = $id;
              }    
        }
    }
    echo $errorMessage;
?>