<?php
//-------------------------------------------------------
// Purpose: This file is used to initialise second phase or initialize data for ajax calls
//
// Author       : Vimal Sharma
// Created on   : (15.04.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    require_once(MODEL_PATH . "/StudentEnquiryManager.inc.php");
    $studentManager = StudentEnquiryManager::getInstance();
    
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache(); 

    

    $programRecordArray = $studentManager->getProgramAllotmentStatusList();
    $cnt = count($programRecordArray);
    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* $cnt;
    $limit      = ' LIMIT 0,'.$cnt;

    
    for($i=0;$i<$cnt;$i++) {
       $valueArray = array_merge(array('srNo' => ($records+$i+1)),
                                       $programRecordArray[$i]);
       if(trim($json_val)=='') {
         $json_val = json_encode($valueArray);
       }
       else {
         $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>

<?php 
// $History: ajaxInitProgramStatusList.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 3/23/10    Time: 6:34p
//Updated in $/LeapCC/Library/StudentEnquiry
//query & condition format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/18/10    Time: 12:45p
//Updated in $/LeapCC/Library/StudentEnquiry
//validation & condition updated
//

?>