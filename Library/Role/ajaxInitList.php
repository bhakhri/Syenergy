<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','RoleMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/RoleManager.inc.php");
    $roleManager = UserRoleManager::getInstance();

    /////////////////////////


    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (rl.roleName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'roleName';

    $orderBy = " rl.$sortField $sortOrderBy";

    ////////////

    $totalArray = $roleManager->getTotalRole($filter);
    $roleRecordArray = $roleManager->getRoleList($filter,$limit,$orderBy);
    $cnt = count($roleRecordArray);

    for($i=0;$i<$cnt;$i++) {

       if($roleRecordArray[$i]['userCount']!=0){
        $viewUserString       = '<span title="View (Total : '.$roleRecordArray[$i]['userCount'].') users of '.$roleRecordArray[$i]['roleName'].' role"><img src="'.IMG_HTTP_PATH.'/user_view.gif" border="0" style="margin-bottom:-3px" alt="'.$roleRecordArray[$i]['roleName'].' ( Total : '.$roleRecordArray[$i]['userCount'].' )" onclick="viewUsers('.$roleRecordArray[$i]['roleId'].',this.alt);return false;">('.$roleRecordArray[$i]['userCount'].')</span>';
       }
       else{
         $viewUserString     =  '<span>('.$roleRecordArray[$i]['userCount'].')</span>';
       }
      if($roleRecordArray[$i]['roleId']!=1){
        $permissionString     = '<span title="View permissions of '.$roleRecordArray[$i]['roleName'].' role"><img src="'.IMG_HTTP_PATH.'/view_permissions.gif" border="0" alt="'.$roleRecordArray[$i]['roleName'].'" style="margin-bottom:-3px" onclick="viewPermissions('.$roleRecordArray[$i]['roleId'].',this.alt);return false;"></span>';

        if($roleRecordArray[$i]['roleId']==2){//for teachers
            $path='teacherPermission.php';
        }
        else if($roleRecordArray[$i]['roleId']==3){//for parents
            $path='parentPermission.php';
        }
        else if($roleRecordArray[$i]['roleId']==4){//for students
            $path='studentPermission.php';
        }
        else if($roleRecordArray[$i]['roleId']==5){//for management
            $path='managementPermission.php';
        }
        else{//for other roles(except admin)
            $path='rolePermission.php?roleId='.$roleRecordArray[$i]['roleId'];
        }
        $editPremissionString = '<a href="'.$path.'" target="_blank" title="Edit Permissions"><img src="'.IMG_HTTP_PATH.'/edit_permission.gif" border="0" alt="Edit Permissions" ></a>';
		  $copyPermissionString = '<span title="Copy Permissions"><img src="'.IMG_HTTP_PATH.'/copy.jpg" border="0" style="margin-bottom:-3px" alt="Copy Permissions" onclick="copyPermissions('.$roleRecordArray[$i]['roleId'].',this.alt);return false;"></span>';
      }
      else{
         $permissionString     = 'All Permissions';
         $editPremissionString = 'All Permissions';
		   $copyPermissionString = 'All Permissions';
      }

       $valueArray = array_merge(
                                  array('action' => $roleRecordArray[$i]['roleId'],
                                  'srNo' => ($records+$i+1),
                                  'userList'=>$viewUserString,
                                  'permissionList'=>$permissionString,
                                  'editPermissionList'=>$editPremissionString,
                                  'copyPermissionList'=>$copyPermissionString
                                ),
                                 $roleRecordArray[$i]
                                );

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }

    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}';

// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 15/12/09   Time: 18:46
//Updated in $/LeapCC/Library/Role
//Made UI changes in Role Master module
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/20/09    Time: 2:00p
//Updated in $/LeapCC/Library/Role
//added role permission module for user other than admin
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Role
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/06/08   Time: 10:32a
//Updated in $/Leap/Source/Library/Role
//Added access rules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/10/08    Time: 5:22p
//Updated in $/Leap/Source/Library/Role
//Created Role(Role Master) Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/10/08    Time: 2:58p
//Created in $/Leap/Source/Library/Role
//Initial checkin
?>
