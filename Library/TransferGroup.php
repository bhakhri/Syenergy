<?php 

   // die("Stopped for time being as told by Ajinder Sir");
    
	global $FE;
    require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/GroupManager.inc.php");
    $groupManager = GroupManager::getInstance();

	$oldClassId = 2;
	$newClassId = 61;
    
    //get the instituteId and sessionId of new class
    $classArray=$groupManager->getClassInfo($newClassId);
    
    
    if(!is_array($classArray) or count($classArray)==0){
        echo 'Institute and Session of new class is missing';
        die;
    }
    $newSessionId=$classArray[0]['sessionId'];
    $newInstituteId=$classArray[0]['instituteId'];
    
    //get the groups for coping
    $groupArray=$groupManager->getGroupsForTransfer($oldClassId);
    if(is_array($groupArray) and count($groupArray)>0){
        
        $cnt=count($groupArray);
        //creating groups parent-child array structure
        $gArray=array();
        for($i=0;$i<$cnt;$i++){
         $sArray=array();
          for($j=0;$j<$cnt;$j++){
              if($groupArray[$i]['groupId']==$groupArray[$j]['parentId']){
                  if(count($sArray)==0){
                    $sArray[]=$groupArray[$i]['groupId'];
                  }
                  $sArray[]=$groupArray[$j]['groupId'];
              }
          }

        if(count($sArray)!=0){
          $gArray[]=$sArray;
         }
        }
        
        //done the coping
        $pId=0;
        $qArray1=array();
        $qArray2=array();

         //starting transaction
         if(SystemDatabaseManager::getInstance()->startTransaction()) {
            
            foreach($gArray as $val){
                 $pId=0;
                 if(array_search($val[0],$qArray1)===false){
                  //copy top level group   
                  $r1=$groupManager->doGroupTransfer($newClassId,$pId,$val[0]);
                  if($r1===false){
                   echo FAILURE;
                   die;   
                  }
                  $pId=SystemDatabaseManager::getInstance()->lastInsertId();
                  //now update student_groups table
                  $r2=$groupManager->doStudentGroupUpdation($oldClassId,$val[0],$newClassId,$pId,$newInstituteId,$newSessionId);
                  if($r2===false){
                   echo FAILURE;
                   die;   
                  }
                }
                else{
                 $pId=$qArray2[array_search($val[0],$qArray1)];
                 }

                 $cnt=count($val);
                 for($i=1;$i<$cnt;$i++){
                  //copy child groups
                  $r3=$groupManager->doGroupTransfer($newClassId,$pId,$val[$i]);
                  if($r3===false){
                   echo FAILURE;
                   die;   
                  }
                  $lastId=SystemDatabaseManager::getInstance()->lastInsertId();;
                  $qArray1[]=$val[$i];
                  $qArray2[]=$lastId;
                  //now update student_groups table
                  $r4=$groupManager->doStudentGroupUpdation($oldClassId,$val[$i],$newClassId,$lastId,$newInstituteId,$newSessionId);
                  if($r4===false){
                   echo FAILURE;
                   die;   
                  }
              }
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
        else {
         echo FAILURE;
         die;
        }


 }
 else{
    echo 'No Groups Found';
    die;
 }
?>