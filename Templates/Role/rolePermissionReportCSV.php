<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

//to parse csv values    
function parseCSVComments($comments) {
 $comments = str_replace('"', '""', $comments);
 $comments = str_ireplace('<br/>', "\n", $comments);
 if(eregi(",", $comments) or eregi("\n", $comments)) {
   return '"'.$comments.'"'; 
 } 
 else {
 return $comments; 
 }
 
}

    require_once(MODEL_PATH . "/RoleManager.inc.php");
    $roleManager = UserRoleManager::getInstance();
    
    $roleId=trim($REQUEST_DATA['roleId']);
    
    //if roleId is empty or of administrator's
    if($roleId=='' or $roleId==1){
      echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":0","page":"'.$page.'","info" : ['.$json_val.']}';   
      die;
    }
    
    
    $filter =' AND rp.roleId='.$roleId;
    
    /////////////////////////
    
    //***********INCLUDE MENU FILES BASED ON ROLE SELECTED****************
    if($roleId==2){//for teacher
        require_once(BL_PATH . "/teacherMenuItems.php");
        $globalMenuStructure=$allTeacherMenus;
    }
    elseif($roleId==3){//for parents
        require_once(BL_PATH . "/parentMenuItems.php");
        $globalMenuStructure=$allParentMenus;
    }
    elseif($roleId==4){//for students
        require_once(BL_PATH . "/studentMenuItems.php");
        $globalMenuStructure=$allStudentMenus;
    }
    elseif($roleId==5){//for management
        require_once(BL_PATH . "/managementMenuItems.php");
        $globalMenuStructure=$allManagementMenus;
    }
    else{//for other roles (except admin )
        require_once(BL_PATH . "/menuItems.php");
        $globalMenuStructure=$allMenus;
    }
    
   /*BUILD MENU NAMES*/
   $menuNameArray=array();
   $menuActArray=array();
   if(count($globalMenuStructure)>0){
    foreach($globalMenuStructure as $independentMenu) {
     foreach($independentMenu as $menuItemArray) {
        if ($menuItemArray[0] == SET_MENU_HEADING) {
        }
        elseif($menuItemArray[0] == MAKE_SINGLE_MENU) {
             $moduleName  = trim($menuItemArray[2][0]);
             $moduleLabel = trim($menuItemArray[2][1]);
             $menuActArray[]="'".$moduleName."'";
             $menuNameArray[$moduleName]=$moduleLabel;
        }
        elseif($menuItemArray[0] == MAKE_MENU) {
            $moduleHeadLabel = $menuItemArray[1];
            foreach($menuItemArray[2] as $moduleMenuItem) {
                $moduleName  = trim($moduleMenuItem[0]);
                $moduleLabel = trim($moduleMenuItem[1]);
                $menuActArray[]="'".$moduleName."'";
                $menuNameArray[$moduleName]=$moduleLabel; 
            }
         }
        elseif($menuItemArray[0] == MAKE_HEADING_MENU) {
            $moduleArray = $menuItemArray[1];
            list($moduleName, $menuLabel,$menuLink) = explode(',',$moduleArray);
            $moduleName  = trim($moduleName);
            $moduleLabel = trim($menuLabel);
            $menuActArray[]="'".$moduleName."'";
            $menuNameArray[$moduleName]=$moduleLabel;
        } 
       }
     }
     $filter .=" AND m.moduleName IN ('0',".implode(',',$menuActArray).")";
   }

    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'moduleName';
    $orderBy = " $sortField $sortOrderBy";
    
    
    
    ////////////
    
     $roleRecordArray = $roleManager->getRolePermissionList($filter,' ',$orderBy);
     $cnt = count($roleRecordArray);
     $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $valueArray = array();
    for($i=0;$i<$cnt;$i++){
       //extract module name
       $moduleName     = trim($roleRecordArray[$i]['moduleName']);
       $tempModuleName = trim($menuNameArray[$moduleName]);
       
       if($tempModuleName=='' and $roleRecordArray[$i]['moduleName']==''){
           $roleRecordArray[$i]['moduleName'] = NOT_APPLICABLE_STRING;
       }
       else if($tempModuleName==''){
           $roleRecordArray[$i]['moduleName'] = $roleRecordArray[$i]['moduleName'];
       }
       else{
           $roleRecordArray[$i]['moduleName'] = $tempModuleName;
       }
       
       //role permissions
       $roleRecordArray[$i]['viewPermission']   = $roleRecordArray[$i]['viewPermission']==1?'Yes':'No';
       $roleRecordArray[$i]['addPermission']    = $roleRecordArray[$i]['addPermission']==1?'Yes':'No';
       $roleRecordArray[$i]['editPermission']   = $roleRecordArray[$i]['editPermission']==1?'Yes':'No';
       $roleRecordArray[$i]['deletePermission'] = $roleRecordArray[$i]['deletePermission']==1?'Yes':'No';
   
       $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$roleRecordArray[$i]);
   }

	$csvData = '';
    $csvData .= "#, Module, View, Add, Edit, Delete \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['moduleName']).', '.$record['viewPermission'].', '.$record['addPermission'].','.$record['editPermission'].','.$record['deletePermission'];
		$csvData .= "\n";
    }
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="rolePermissionReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    

// $History: rolePermissionReportCSV.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 15/12/09   Time: 18:49
//Created in $/LeapCC/Templates/Role
//Made UI changes in Role Master module
?>