<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To store the records of students in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (8.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/SendMessageManager.inc.php");
    $sendMessageManager = SendMessageManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    /////search functionility not needed   
    
    //splited to get classId and groupId
    $arr=split("~",$REQUEST_DATA['group']);
    
    $filter = ' WHERE classId="'.$arr[1].'" AND (thGroupId="'.$arr[0].'" OR prGroupId="'.$arr[0].'")'; 
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $sendMessageManager->getTotalStudent($filter);
    $studentRecordArray = $sendMessageManager->getStudentList($filter,$limit,$orderBy);
    $cnt = count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array("students" => "<input type=\"checkbox\" name=\"students\" id=\"students\" value=\"".$studentRecordArray[$i]['studentId'] ."\">" ,"parents" => "<input type=\"checkbox\" name=\"parents\" id=\"parents\" value=\"".$studentRecordArray[$i]['studentId'] ."\">"), $studentRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/SendMessage
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/15/08    Time: 4:15p
//Updated in $/Leap/Source/Library/SendMessage
//Modified database query form
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/08/08    Time: 7:29p
//Updated in $/Leap/Source/Library/SendMessage
//Added comments
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/08/08    Time: 5:49p
//Updated in $/Leap/Source/Library/SendMessage
//Created sendMessage module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/05/08    Time: 6:16p
//Created in $/Leap/Source/Library/SendMessage
//Initial checkin
?>
