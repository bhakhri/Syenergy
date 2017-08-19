<?php
//-------------------------------------------------------
// Purpose: To delete degree detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DepartmentMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['departmentId']) || trim($REQUEST_DATA['departmentId']) == '') {
        $errorMessage = INVALID_DEPARTMENT;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/DepartmentManager.inc.php");
        $departmentManager =  DepartmentManager::getInstance();
        $recordArray = $departmentManager->checkDepartmentInNotice(' WHERE departmentId='.$REQUEST_DATA['departmentId']);
        if($recordArray[0]['found']==0) { 
            if($departmentManager->deleteDepartment($REQUEST_DATA['departmentId']) ) {
                echo DELETE;
                die;
            }
           else {
                echo DEPENDENCY_CONSTRAINT;
                die;
            }
        }
      else{
          echo DEPENDENCY_CONSTRAINT;
          die;
      }  
    }
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/08/09    Time: 11:36
//Updated in $/LeapCC/Library/Department
//Done bug fixing.
//bug ids---
//0000971 to 0000976,0000979
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Department
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Department
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/20/08   Time: 5:49p
//Created in $/Leap/Source/Library/Department
//used for delete data in department table
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:27p
//Updated in $/Leap/Source/Library/Degree
//Added access rules
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:31p
//Updated in $/Leap/Source/Library/Degree
//Added dependency constraint check
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/25/08    Time: 2:22p
//Updated in $/Leap/Source/Library/Degree
//Adding AjaxEnabled Delete functionality
//*********Solved the problem :**********
//Open 2 browsers opening Degree Masters page. On one page, delete a
//Degree. On the second page, the deleted degree is still visible since
//editing was done on first page. Now, click on the Edit button
//corresponding to the deleted Degree in the second page which was left
//untouched. Provide the new Degree Code and click Submit button.A blank
//popup is displayed. It should rather display "The Degree you are trying
//to edit no longer exists".
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/25/08    Time: 12:55p
//Created in $/Leap/Source/Library/Degree
//Initial Checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/25/08    Time: 12:53p
//Updated in $/Leap/Source/Library/Quota
//Added AjaxEnabled Delete Functionality
//Added Input Data Validation using Javascript
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/25/08    Time: 12:09p
//Created in $/Leap/Source/Library/Quota
//Initial checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/25/08    Time: 11:36a
//Updated in $/Leap/Source/Library/City
//Added AjaxEnabled Delete Functionality
?>

