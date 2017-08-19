<?php
//-------------------------------------------------------
// Purpose: To sent messages list
// functionality
//
// Author : Parveen Sharma
// Created on : (04.02.2009 )
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','add');
    UtilityManager::ifParentNotLoggedIn(true);       
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/Parent/ParentMessageManager.inc.php");
    $parentTeacherManager = ParentTeacherManager::getInstance();

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND ( message LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR message LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'messageDate';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray           = $parentTeacherManager->getTotalSentItemMessage($filter);
    $parentTeacherRecordArray = $parentTeacherManager->getSentItemMessageList($filter,$limit,$orderBy);
    $cnt = count($parentTeacherRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
		if($parentTeacherRecordArray[$i]['attachmentFile']){
		   $parentTeacherRecordArray[$i]['attachmentFile'] ='<img src="'.IMG_HTTP_PATH.'/download.gif" title="'.$parentTeacherRecordArray[$i]['messageSubject'].'" onclick="javascript:download(\''.$parentTeacherRecordArray[$i]['attachmentFile'].'\')" />';  
		}
		else{
		   $parentTeacherRecordArray[$i]['attachmentFile'] = NOT_APPLICABLE_STRING;
		}
		
		if(strlen($parentTeacherRecordArray[$i]['messageSubject'])>20){
          $parentTeacherRecordArray[$i]['messageSubject'] =  substr($parentTeacherRecordArray[$i]['messageSubject'],0,20)."...";
        }
        if(strlen($parentTeacherRecordArray[$i]['message'])>70){
          $parentTeacherRecordArray[$i]['message'] =  substr($parentTeacherRecordArray[$i]['message'],0,70)."..."; 
        }
		
		if($parentTeacherRecordArray[$i]['readStatus']==1){
            $parentTeacherRecordArray[$i]['messageDate'] = "<strong>".UtilityManager::formatDate($parentTeacherRecordArray[$i]['messageDate'],true)."</strong>";
			$parentTeacherRecordArray[$i]['employeeName'] = "<strong>".$parentTeacherRecordArray[$i]['employeeName']."</strong>";
            $parentTeacherRecordArray[$i]['messageSubject'] =  "<strong>".$parentTeacherRecordArray[$i]['messageSubject']."</strong>";
            $parentTeacherRecordArray[$i]['message'] =  "<strong>".$parentTeacherRecordArray[$i]['message']."</strong>";
		}
		else{
			$parentTeacherRecordArray[$i]['messageDate'] = UtilityManager::formatDate($parentTeacherRecordArray[$i]['messageDate'],true);
		}
		
		//if($parentTeacherRecordArray[$i]['readStatus'] == 1){
		
        $actionStr1='<a href="#" title="View"><img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="View" onclick="showSentData('.$parentTeacherRecordArray[$i]['messageId'].',\'SentDataActionDiv\');return false;"></a>';
        $actionStr2='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete1.gif" border="0" alt="Delete" onclick="deleteParentTeacher('.$parentTeacherRecordArray[$i]['messageId'].');return false;"></a>';
		

        $actionStr = $actionStr1."&nbsp;&nbsp;".$actionStr2;
		//}
		//else{

		//	$actionStr=NOT_APPLICABLE_STRING;

		//}
        $valueArray = array_merge(array('action1' => $actionStr,'srNo' => ($records+$i+1) ),$parentTeacherRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitSentItemList.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/20/09    Time: 7:17p
//Updated in $/Leap/Source/Library/ScParent
//issue fix 13, 15, 10, 4 1129, 1118, 1134, 555, 224, 1177, 1176, 1175
//formating conditions updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/19/09    Time: 6:55p
//Updated in $/Leap/Source/Library/ScParent
//formating & validation updated
//1132, 1130, 54, 1045, 1044, 500, 1042, 1043 issue resolve
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/10/09    Time: 5:28p
//Updated in $/Leap/Source/Library/ScParent
//formating, validation updated
//issue fix 994, 9943, 992, 991, 989, 987, 
//986, 985, 981, 914, 913, 911
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/07/09    Time: 7:21p
//Updated in $/Leap/Source/Library/ScParent
//validation, features, conditions, formatting updated (bug Nos.
//331, 323, 334, 338,339, 348, 350, 351,352, 354, 380, 381,342, 349, 427,
//428, 429,430, 431, 432, 433, 434,435, 436,437, 432, 479, 480, 481,482,
//493, 494, 495, 498,501, 502,478, 477, 934, 924, 925)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/04/09    Time: 4:27p
//Created in $/Leap/Source/Library/ScParent
//initial checkin 


?>