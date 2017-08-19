<?php

//This file calls Listing Function and creates Global Array in "Display Notices in Parent " Module 
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
    $parentManager = ParentManager::getInstance();
    
          
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (comments LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'")';         
    }
   
    $totalArray = $parentManager->getTotalComments($filter);
    $parentRecordArray = $parentManager->getCommentsList($filter,$limit);  
	echo '<pre>';
	print_r($parentRecordArray);
    
?>

<?php 

//$History: initDisplayComments.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/21/09    Time: 1:30p
//Updated in $/LeapCC/Library/Parent
//put the task files in parent module
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Parent
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/31/08    Time: 6:30p
//Updated in $/Leap/Source/Library/Parent
//changed the path of ParentManager file
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/16/08    Time: 10:55a
//Updated in $/Leap/Source/Library/Parent
//added comments
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/14/08    Time: 6:03p
//Created in $/Leap/Source/Library/Parent
//added new files for Parent Module
//
//*****************  Version 3  *****************
//User: Arvind       Date: 6/16/08    Time: 11:11a
//Updated in $/Leap/Source/Library/Country
//modification
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
