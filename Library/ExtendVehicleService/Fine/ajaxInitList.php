<?php
//-----------------------------------------------------------------------------------------------
// Purpose: To display fine categories along with add,edit,delete,sorting and paging facility 
// Author : Dipanjan Bbhattacharjee
// Created on : (02.07.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FineCategoryMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineCategoryManager = FineManager::getInstance();

    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE ( fineCategoryName LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR fineCategoryAbbr LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fineCategoryName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray                = $fineCategoryManager->getTotalFineCategories($filter);
    $fineCategoriesRecordArray = $fineCategoryManager->getFineCategoriesList($filter,$limit,$orderBy);
    $cnt = count($fineCategoriesRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $valueArray = array_merge(array('action' => $fineCategoriesRecordArray[$i]['fineCategoryId'] , 'srNo' => ($records+$i+1) ),$fineCategoriesRecordArray[$i]);

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
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/07/09    Time: 16:46
//Updated in $/LeapCC/Library/Fine
//Changes html and model files names in "Fine  Category" module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/07/09    Time: 15:30
//Created in $/LeapCC/Library/Fine
//Added files for "fine_category" module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/07/09    Time: 16:07
//Created in $/LeapCC/Library/FineCategory
//Created "Fine Category Master" module
?>