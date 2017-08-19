<?php 

//This file calls Delete Function and Listing Function and creates Global Array in Country Module 
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------



    //Paging code goes here
    require_once(MODEL_PATH . "/SubjectTypeManager.inc.php");
    $subjectTypeManager = SubjectTypeManager::getInstance();
    
   
        
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (sub.subjectTypeName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR sub.subjectTypeCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
    $totalArray = $subjectTypeManager->getTotalSubjectType($filter);    
    $subjectTypeRecordArray = $subjectTypeManager->getSubjectTypeList($filter,$limit);   
    


//$History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/SubjectType
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/26/08    Time: 5:18p
//Updated in $/Leap/Source/Library/SubjectType
//removed the function which perform the delete operation
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/14/08    Time: 6:25p
//Created in $/Leap/Source/Library/SubjectType
//new files added
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/13/08    Time: 12:04p
//Updated in $/Leap/Source/Library/Country
//Make $history a comment
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:19p
//Created in $/Leap/Source/Library/Country
//New Files Added in Country Folder

?>
