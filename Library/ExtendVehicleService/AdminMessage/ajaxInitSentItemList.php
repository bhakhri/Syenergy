<?php
//-------------------------------------------------------
// Purpose: To store the records of salary head in array from the database, pagination and search, delete 
// functionality
//
// Author : Rajeev Aggarwal
// Created on : (25.11.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MANAGEMENT_ACCESS',1); 
    define('MODULE','COMMON');
    define('ACCESS','view');
    if($sessionHandler->getSessionVariable('RoleId')==2) {     
        UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else {
        UtilityManager::ifNotLoggedIn(true);   
    }
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/AdminMessageManager.inc.php");   
    $adminMessageManager = AdminMessageManager::getInstance();

    /////////////////////////
    
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
    global $sessionHandler;
    $userId = $sessionHandler->getSessionVariable('UserId');
    
    $filter .= " AND stc.senderId = ".$userId;
    
    $totalArray           = $adminMessageManager->getTotalParentSentItemMessage($filter);
    $salaryHeadRecordArray = $adminMessageManager->getParentSentItemList($filter,$limit,$orderBy);
    $cnt = count($salaryHeadRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
		if($salaryHeadRecordArray[$i]['attachmentFile']){
			$salaryHeadRecordArray[$i]['attachmentFile'] ='<img src="'.IMG_HTTP_PATH.'/download.gif" title="'.$salaryHeadRecordArray[$i]['messageSubject'].'" onclick="download(\''.$salaryHeadRecordArray[$i]['attachmentFile'].'\')" />';  
		}
		else{
			$salaryHeadRecordArray[$i]['attachmentFile'] = '--';
		}
		
		$readMore = '';
		if(strlen($salaryHeadRecordArray[$i]['message'])>125){
			$readMore = " ...";
		}
		$salaryHeadRecordArray[$i]['message'] = substr($salaryHeadRecordArray[$i]['message'],0,125).$readMore;

		$readMore1 = '';
		if(strlen($salaryHeadRecordArray[$i]['messageSubject'])>20){

			$readMore1 = " ...";
		}
		$salaryHeadRecordArray[$i]['messageSubject'] = substr($salaryHeadRecordArray[$i]['messageSubject'],0,20).$readMore1;
		
		if($salaryHeadRecordArray[$i]['readStatus']==1){
			$salaryHeadRecordArray[$i]['messageDate'] = "<strong>".UtilityManager::formatDate($salaryHeadRecordArray[$i]['messageDate'],true)."</strong>";
			$salaryHeadRecordArray[$i]['messageSubject'] = "<strong>".$salaryHeadRecordArray[$i]['messageSubject']."</strong>";
            $salaryHeadRecordArray[$i]['studentName'] = "<strong>".$salaryHeadRecordArray[$i]['studentName']."</strong>";
            $salaryHeadRecordArray[$i]['parentName'] = "<strong>".$salaryHeadRecordArray[$i]['parentName']."</strong>";
			$salaryHeadRecordArray[$i]['firstName'] = "<strong>".$salaryHeadRecordArray[$i]['firstName']."</strong>";
            $salaryHeadRecordArray[$i]['employeeName'] = "<strong>".$salaryHeadRecordArray[$i]['employeeName']."</strong>";
			$salaryHeadRecordArray[$i]['message'] = "<strong>". substr($salaryHeadRecordArray[$i]['message'],0,125).$readMore."</strong>";
		}
		else{
		
			$salaryHeadRecordArray[$i]['messageDate'] = UtilityManager::formatDate($salaryHeadRecordArray[$i]['messageDate'],true);
		}
		
		//if($salaryHeadRecordArray[$i]['readStatus'] == 1){
           $actionStr1='<a href="#" title="View"><img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="View" onclick="showSentData('.$salaryHeadRecordArray[$i]['messageId'].',\'SentDataActionDiv\');return false;"></a>';
           $actionStr2='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete1.gif" border="0" alt="Delete" onclick="deleteParentTeacher('.$salaryHeadRecordArray[$i]['messageId'].');return false;"></a>';
           $actionStr = $actionStr1."&nbsp;&nbsp;".$actionStr2;
			
		//}
		//else{

		//	$actionStr=NOT_APPLICABLE_STRING;

		//}
        $valueArray = array_merge(array('action1' => $actionStr,'srNo' => ($records+$i+1) ),$salaryHeadRecordArray[$i]);

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
//*****************  Version 4  *****************
//User: Rajeev       Date: 09-09-01   Time: 1:15p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Updated with Session check
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 2/11/09    Time: 1:34p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Updated validations and fixed bugs
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/04/09    Time: 6:32p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//inital checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 2/02/09    Time: 4:25p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Intial checkin
?>