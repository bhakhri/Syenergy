<?php
//-------------------------------------------------------
// Purpose: conatins logic of group copying
//
// Author : Ajinder Singh
// Created on : (28-jan-2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
if (!isset($callGroupCopyCodeFile) or empty($callGroupCopyCodeFile) or $callGroupCopyCodeFile == false) {
		echo INVALID_DETAILS_FOUND;
		die;
	}
    //checking of input data
    if($oldClassId==''){
        echo SOURCE_CLASS_MISSING;
        die;
    }
    if($newClassId==''){
        echo TARGET_CLASS_MISSING;
        die;
    }
    //source & target cannot be same
    if($oldClassId==$newClassId){
        echo SAME_CLASS_RESTRICTION;
        die;
    }
    //check for parent class    
    $parentClassArray = StudentManager::getInstance()->getPrevClass($newClassId);

	//target class must have a parent class
    if(count($parentClassArray)==0){
        echo NO_PARENT_CLASS_FOUND;
        die;
    }
    //target class's parent class must be equal to user supplied source class
    if($parentClassArray[0]['classId']!=$oldClassId){
        echo INVALID_PARENT_CLASS_RESTRICTION;
        die;
    }
        
    //get the instituteId and sessionId of new class
    $classArray=$groupManager->getClassInfo($newClassId);
    if(!is_array($classArray) or count($classArray)==0){
        echo INSTITUTE_SESSION_INFO_MISSING_FOR_TARGET_CLASS;
        die;
    }
    $newSessionId=$classArray[0]['sessionId'];
    $newInstituteId=$classArray[0]['instituteId'];
    
    //target class must not have any group allocation
    //checking with group table
    $newGroupArray=$groupManager->checkTargetClassGroupAllocation($newClassId);
    if(count($newGroupArray)>0 and is_array($newGroupArray)){
        echo GROUP_ALREADY_ALLOCATED_TO_TARGET_CLASS;
        die;
    }
    
    //checking with student_groups table
    $newGroupArray=$groupManager->checkTargetClassGroupAllocationDetailed($newClassId);
    if(count($newGroupArray)>0 and is_array($newGroupArray)){
        echo GROUP_ALREADY_ALLOCATED_TO_TARGET_CLASS;
        die;
    }
    
    //get the groups for coping
    $groupArray=$groupManager->getGroupsForTransfer($oldClassId);
    
    //source class must have groups
    if(count($groupArray)<=0 or !is_array($groupArray)){
        echo SOURCE_CLASS_WITH_NO_GROUPS;
        die;
    }
    
    
	if(is_array($groupArray) and count($groupArray)>0) {
		$cnt=count($groupArray);
		//creating groups parent-child array structure
		$gArray=array();
		for($i=0;$i<$cnt;$i++){
			$sArray=array();
			for($j=0;$j<$cnt;$j++) {
				if($groupArray[$i]['groupId']==$groupArray[$j]['parentId']) {
					$treeFound = true;
					if(count($sArray)==0) {
						$sArray[]=$groupArray[$i]['groupId'];
					}
					$sArray[]=$groupArray[$j]['groupId'];
				}
			}
			if(count($sArray)!=0) {
				$gArray[]=$sArray;
			}
		}
		//fetching parent groups which does not have any children
		$condition = "AND a.classId=$oldClassId and a.parentGroupId = 0";
		$noChildGroupsArray = CommonQueryManager::getInstance()->getLastLevelGroups('groupName', $condition);


		//done the coping
		$pId=0;
		$qArray1=array();
		$qArray2=array();


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

		$singleCnt=count($noChildGroupsArray);
		for($i=0;$i<$singleCnt;$i++){
			//copy top level group 
			$r1=$groupManager->doGroupTransfer($newClassId,0,$noChildGroupsArray[$i]['groupId']);
			if($r1===false){
				echo FAILURE;
				die;   
			}
			$lastId=SystemDatabaseManager::getInstance()->lastInsertId();
			//now update student_groups table
			$r2=$groupManager->doStudentGroupUpdation($oldClassId,$noChildGroupsArray[$i]['groupId'],$newClassId,$lastId,$newInstituteId,$newSessionId);
			if($r2===false){
				echo FAILURE;
				die;   
			} 
		}

	}
	else{
		echo 'No Groups Found';
		die;
	}

// $History: ajaxCopyGroupsCode.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 1/29/10    Time: 3:42p
//Created in $/LeapCC/Library/Group
//file added for new interface of session end activities
//



?>