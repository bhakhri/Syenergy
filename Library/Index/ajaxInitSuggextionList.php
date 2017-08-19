<?php
//-------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of institute notices  and show it in dashboard
//
// Author : Rajeev Aggarwal
// Created on : (13.08.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::headerNoCache();
	global $suggestionArr;
    require_once(MODEL_PATH . "/DashBoardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();

	// to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
   
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'suggestionOn';
    
     $orderBy = " $sortField $sortOrderBy";         
    ////////////
    
    $totalArray           = $dashboardManager->getTotalSuggestion($filter);
    $suggestionRecordArray = $dashboardManager->getSuggestionList($filter,$orderBy,$limit);
    $cnt = count($suggestionRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   

		if($suggestionRecordArray[$i]['repliedOn']=='-1'){
			
			$readMore = '';
			if(strlen($suggestionRecordArray[$i]['suggestionText'])>125){

				$readMore = " ...";
			}
			$suggestionRecordArray[$i]['suggestionText'] = "<strong>".substr($suggestionRecordArray[$i]['suggestionText'],0,125).$readMore."</strong>";

			$suggestionRecordArray[$i]['userName'] = "<strong>".$suggestionRecordArray[$i]['userName']."</strong>";
			$suggestionRecordArray[$i]['repliedOn'] = "<strong>--</strong>";

			$suggestionRecordArray[$i]['suggestionSubjectId'] = "<strong>".$suggestionArr[$suggestionRecordArray[$i]['suggestionSubjectId']]."</strong>";

			
			$suggestionRecordArray[$i]['suggestionOn'] = "<strong>".UtilityManager::formatDate($suggestionRecordArray[$i]['suggestionOn'])."</strong>";
		}
		else{

			$suggestionRecordArray[$i]['suggestionSubjectId'] = $suggestionArr[$suggestionRecordArray[$i]['suggestionSubjectId']];
			
			$suggestionRecordArray[$i]['suggestionOn'] = UtilityManager::formatDate($suggestionRecordArray[$i]['suggestionOn']);

			$suggestionRecordArray[$i]['repliedOn'] = UtilityManager::formatDate($suggestionRecordArray[$i]['repliedOn']);

			$readMore = '';
			if(strlen($suggestionRecordArray[$i]['suggestionText'])>125){

				$readMore = " ...";
			}
			$suggestionRecordArray[$i]['suggestionText'] = substr($suggestionRecordArray[$i]['suggestionText'],0,125).$readMore;

		
		}
		$actionStr='<a href="#" title="Detail"><img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="Edit" onclick="editWindow('.$suggestionRecordArray[$i]['suggestionId'].',\'StudentTeacherActionDiv\');return false;"></a>';

        $valueArray = array_merge(array('action1' => $actionStr,'srNo' => ($records+$i+1) ),$suggestionRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
	
	
// $History: ajaxInitSuggextionList.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/15/09    Time: 11:44a
//Created in $/LeapCC/Library/Index
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/02/09    Time: 1:02p
//Created in $/SnS/Library/Index
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 2/11/09    Time: 2:54p
//Created in $/Leap/Source/Library/ScIndex
//Intial checkin
?>