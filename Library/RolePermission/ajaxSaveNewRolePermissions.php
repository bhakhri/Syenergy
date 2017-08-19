<?php
//-------------------------------------------------------
//  This File saves role menu permissions
//
//
// Author :Ajinder Singh
// Created on : 06-Nov-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . '/menuItems.php');
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RolePermissions');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/LoginManager.inc.php");
$loginManager = LoginManager::getInstance();


global $sessionHandler;
$sessionRoleId = $sessionHandler->getSessionVariable('RoleId');
$roleId = $REQUEST_DATA['roleId'];



$instituteAllArray = $loginManager->getAllInstitue();
$instituteIds='';
for($i=0;$i<count($instituteAllArray);$i++) {
   if($instituteIds!='') {
     $instituteIds .=",";  
   } 
   $instituteIds .= $instituteAllArray[$i]['instituteId'];
}


$dashboardArray = array();
if ($sessionRoleId != 1) {
    $allMenuArray = Array();
    foreach($allMenus as $independentMenu) {
        foreach($independentMenu as $menuItemArray) {
            if($menuItemArray[0] == MAKE_SINGLE_MENU) {
                $moduleName = $menuItemArray[2][0];
                $allMenuArray[] = $moduleName . '_viewPermission';
                $allMenuArray[] = $moduleName . '_addPermission';
                $allMenuArray[] = $moduleName . '_editPermission';
                $allMenuArray[] = $moduleName . '_deletePermission';
                //$allMenuArray[] = $moduleName . '_allInstitutePermission'; 
            }
            elseif($menuItemArray[0] == MAKE_MENU) {
                foreach($menuItemArray[2] as $moduleMenuItem) {
                    $moduleName = $moduleMenuItem[0];
                    $allMenuArray[] = $moduleName . '_viewPermission';
                    $allMenuArray[] = $moduleName . '_addPermission';
                    $allMenuArray[] = $moduleName . '_editPermission';
                    $allMenuArray[] = $moduleName . '_deletePermission';
                    //$allMenuArray[] = $moduleName . '_allInstitutePermission'; 
                }
            }
            elseif($menuItemArray[0] == MAKE_HEADING_MENU) {
                $moduleArray = $menuItemArray[1];
                list($moduleName, $menuLabel,$menuLink) = explode(',',$moduleArray);
                $allMenuArray[] = $moduleName . '_viewPermission';
                $allMenuArray[] = $moduleName . '_addPermission';
                $allMenuArray[] = $moduleName . '_editPermission';
                $allMenuArray[] = $moduleName . '_deletePermission';
                //$allMenuArray[] = $moduleName . '_allInstitutePermission'; 
            }
        }
    }
/*  OLD CODE: This code that they only have view privileges. All edit/save privileges have been lost.
    foreach($allMenus as $independentMenu) {
        foreach($independentMenu as $menuItemArray) {
            if($menuItemArray[0] == MAKE_SINGLE_MENU) {
                $moduleName = $menuItemArray[2][0];
                //echo "\n--".$moduleName;
                if (!isset($_SESSION[$moduleName])) {
                    continue;
                }
                elseif ($_SESSION[$moduleName]['view'] == 1) {
                    $allMenuArray[] = $moduleName . '_viewPermission';
                }
                elseif ($_SESSION[$moduleName]['add'] == 1) {
                    $allMenuArray[] = $moduleName . '_addPermission';
                }
                elseif ($_SESSION[$moduleName]['edit'] == 1) {
                    $allMenuArray[] = $moduleName . '_editPermission';
                }
                elseif ($_SESSION[$moduleName]['delete'] == 1) {
                    $allMenuArray[] = $moduleName . '_deletePermission';
                }
            }
            elseif($menuItemArray[0] == MAKE_MENU) {
                foreach($menuItemArray[2] as $moduleMenuItem) {
                    $moduleName = $moduleMenuItem[0];
                    if (!isset($_SESSION[$moduleName])) {
                        continue;
                    }
                    if ($_SESSION[$moduleName]['view'] == 1) {
                        $allMenuArray[] = $moduleName . '_viewPermission';
                    }
                    if ($_SESSION[$moduleName]['add'] == 1) {
                        $allMenuArray[] = $moduleName . '_addPermission';
                    }
                    if ($_SESSION[$moduleName]['edit'] == 1) {
                        $allMenuArray[] = $moduleName . '_editPermission';
                    }
                    if ($_SESSION[$moduleName]['delete'] == 1) {
                        $allMenuArray[] = $moduleName . '_deletePermission';
                    }
                }
            }
            elseif($menuItemArray[0] == MAKE_HEADING_MENU) {
                $moduleArray = $menuItemArray[1];
                list($moduleName, $menuLabel,$menuLink) = explode(',',$moduleArray);
                //echo "\n***".$moduleName;
                if (!isset($_SESSION[$moduleName])) {
                    continue;
                }
                if ($_SESSION[$moduleName]['view'] == 1) {
                    $allMenuArray[] = $moduleName . '_viewPermission';
                }
                if ($_SESSION[$moduleName]['add'] == 1) {
                    $allMenuArray[] = $moduleName . '_addPermission';
                }
                if ($_SESSION[$moduleName]['edit'] == 1) {
                    $allMenuArray[] = $moduleName . '_editPermission';
                }
                if ($_SESSION[$moduleName]['delete'] == 1) {
                    $allMenuArray[] = $moduleName . '_deletePermission';
                }
            }
        }
    }
*/    
}
else {
    $allMenuArray = Array();
    foreach($allMenus as $independentMenu) {
        foreach($independentMenu as $menuItemArray) {
            if($menuItemArray[0] == MAKE_SINGLE_MENU) {
                $moduleName = $menuItemArray[2][0];
                $allMenuArray[] = $moduleName . '_viewPermission';
                $allMenuArray[] = $moduleName . '_addPermission';
                $allMenuArray[] = $moduleName . '_editPermission';
                $allMenuArray[] = $moduleName . '_deletePermission';
                //$allMenuArray[] = $moduleName . '_allInstitutePermission';  
            }
            elseif($menuItemArray[0] == MAKE_MENU) {
                foreach($menuItemArray[2] as $moduleMenuItem) {
                    $moduleName = $moduleMenuItem[0];
                    $allMenuArray[] = $moduleName . '_viewPermission';
                    $allMenuArray[] = $moduleName . '_addPermission';
                    $allMenuArray[] = $moduleName . '_editPermission';
                    $allMenuArray[] = $moduleName . '_deletePermission';
                    //$allMenuArray[] = $moduleName . '_allInstitutePermission';  
                }
            }
            elseif($menuItemArray[0] == MAKE_HEADING_MENU) {
                $moduleArray = $menuItemArray[1];
                list($moduleName, $menuLabel,$menuLink) = explode(',',$moduleArray);
                $allMenuArray[] = $moduleName . '_viewPermission';
                $allMenuArray[] = $moduleName . '_addPermission';
                $allMenuArray[] = $moduleName . '_editPermission';
                $allMenuArray[] = $moduleName . '_deletePermission';
                //$allMenuArray[] = $moduleName . '_allInstitutePermission';  
            }
        }
    }
}

$accessModulesArray = array();

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
if(SystemDatabaseManager::getInstance()->startTransaction()) {
    $return = $loginManager->unsetAllPermissionsInTransaction($roleId);
    if ($return == false) {
        echo FAILURE;
        die;
    }
    
   foreach($REQUEST_DATA as $key => $value) {
        $moduleId = 0;
        if ($value == 'on') {
            $accessModulesArray[] = $key;
            if (in_array($key, $allMenuArray)) {
                $temp=explode('_', $key);
                $tempCount=count($temp);
                $moduleName='';
                if($tempCount>2){
                    for($i=0;$i<$tempCount-1;$i++){
                        if($moduleName!=''){
                            $moduleName .='_';
                        }
                        $moduleName .=$temp[$i];
                    }
                    $modulePermission=$temp[$tempCount-1];
                }
                else{
                    $moduleName=$temp[0];
                    $modulePermission=$temp[1];
                }
                
               
                
                // Set Module Permission i.e. Attendance Main Menu(isActive or Not)
                //list($moduleName,$modulePermission) = explode('_', $key);
                $moduleName = trim($moduleName);
                $moduleArray = $loginManager->checkModuleExists($moduleName);
                if (is_array($moduleArray) and count($moduleArray)) {
                    $moduleId = $moduleArray[0]['moduleId'];
                }
                else {
                    $moduleId = $loginManager->addModuleInTransaction($moduleName);
                    if ($moduleId == false) {
                        echo FAILURE;
                        die;
                    }
                }
                
                $accessArray = $loginManager->checkModuleAccess($roleId, $moduleId);
                if (!is_array($accessArray) or count($accessArray) == 0) {
                    $return = $loginManager->addRolePermissionInTransaction($roleId,$moduleId);
                    if ($return == false) {
                        echo FAILURE;
                        die;
                    }
                }
                $return = $loginManager->updatePermissionsInTransaction($roleId,$moduleId, $modulePermission);
                if ($return == false) {
                    echo FAILURE;
                    die;
                }
                
                // Update all Institute Permission START
                if($REQUEST_DATA[$moduleName.'_allInstitutePermission']=='on') {
                    for($jj=0;$jj<count($instituteAllArray);$jj++) {
                        $ttInstituteId = $instituteAllArray[$jj]['instituteId'];
                        $accessArray = $loginManager->checkInstituteModuleAccess($roleId, $moduleId,$ttInstituteId);
                        if (!is_array($accessArray) or count($accessArray) == 0) {
                            $return = $loginManager->addRoleInstituePermissionInTransaction($roleId,$moduleId,$ttInstituteId);
                            if ($return == false) {
                                echo FAILURE;
                                die;
                            }
                        }
                        $return = $loginManager->updateInstitutePermissionsInTransaction($roleId,$moduleId, $modulePermission,$ttInstituteId );
                        if ($return == false) {
                            echo FAILURE;
                            die;
                        }
                   }
                }
                // Update all Institute Permission END
            }
        }
       
        $moduleNameChb = substr($key,0,3);
        if($moduleNameChb == "chb") {
            $moduleName = substr($key,3);
            $dashboardArray[] = $moduleName;
        }
    }

    $return = $loginManager->deleteNewRolePermissionInTransaction($roleId);
    if ($return == false) {
        echo FAILURE;
        die;
    }
    if (count($dashboardArray) > 0) {
        $moduleString ='';
        foreach($dashboardArray as $val) {
           if($moduleString != "") {
              $moduleString .= ",";
            }
            $str = $val;
            $str = str_replace("_viewPermission","",$str);
            $str = str_replace("_addPermission","",$str);
            $str = str_replace("_editPermission","",$str);
            $str = str_replace("_deletePermission","",$str);
            $moduleString .= "'$str'";
        }
        
        $moduleIdArray = $loginManager->getFrameId($moduleString);
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        //print_r($moduleIdArray); die();
        $strAllView = "";
        $strAllAdd = "";
        $strAllEdit = "";
        $strAllDelete = "";
        
        $strAllFrameId = "";
        
        $strView = "";
        $strAdd = "";
        $strEdit = "";
        $strDelete = "";
        $val = "";

        for($i=0;$i<count($moduleIdArray); $i++) {            
           $frameId = $moduleIdArray[$i]['frameId'];
           
           // Save the role permission (add/edit/delete/view)
           $dashboardPermissionVal = "chb".$moduleIdArray[$i]['frameName']."_allInstitutePermission";
           $dashboardPermissionId = $REQUEST_DATA[$dashboardPermissionVal];
         
           
           $val = "chb".$moduleIdArray[$i]['frameName']."_viewPermission";
           $id = $REQUEST_DATA[$val];
           if($id == 'on') {
                 if($strView != '') {
                 $strView .=",";
              }
                 $strView .= "($frameId,$roleId,$instituteId,'view')";
              
              if($dashboardPermissionId=='on') {
                 if($strAllFrameId!='') {
                   $strAllFrameId .= ",";  
                 }
                 $strAllFrameId .= "'".$frameId."!~~!view'";
                  
                 for($jj=0;$jj<count($instituteAllArray);$jj++) {
                   if($strAllView != '') {
                     $strAllView .=",";
                   }   
                   $ttInstituteId = $instituteAllArray[$jj]['instituteId'];
                   $strAllView .= "($frameId,$roleId,$ttInstituteId,'view')";       
                 }
              }
           }
           
           $val = "chb".$moduleIdArray[$i]['frameName']."_addPermission";
           $id = $REQUEST_DATA[$val];
           if($id == 'on') {
                 if($strAdd != '') {
                 $strAdd .=",";
              }
                 $strAdd .= "($frameId,$roleId,$instituteId,'add')";
              
              if($dashboardPermissionId=='on') {
                 if($strAllFrameId!='') {
                   $strAllFrameId .= ",";  
                 }
                 $strAllFrameId .= "'".$frameId."!~~!add'";
                 
                 for($jj=0;$jj<count($instituteAllArray);$jj++) {
                   if($strAllAdd != '') {
                     $strAllAdd .=",";
                   }   
                   $ttInstituteId = $instituteAllArray[$jj]['instituteId'];
                   $strAllAdd .= "($frameId,$roleId,$ttInstituteId,'add')";       
                 }
              }
           }
           
           $val = "chb".$moduleIdArray[$i]['frameName']."_editPermission";
           $id = $REQUEST_DATA[$val];
           if($id == 'on') {
                 if($strEdit != '') {
                 $strEdit .=",";
              }
              $strEdit .= "($frameId,$roleId,$instituteId,'edit')";
              
              if($dashboardPermissionId=='on') {
                 if($strAllFrameId!='') {
                   $strAllFrameId .= ","; 
                 }
                 $strAllFrameId .= "'".$frameId."!~~!edit'";
                 
                 for($jj=0;$jj<count($instituteAllArray);$jj++) {
                   if($strAllEdit != '') {
                     $strAllEdit .=",";
                   } 
                   $ttInstituteId = $instituteAllArray[$jj]['instituteId'];
                   $strAllEdit .= "($frameId,$roleId,$ttInstituteId,'edit')";       
                 }
              }
              
           }
           
           $val = "chb".$moduleIdArray[$i]['frameName']."_deletePermission";
           $id = $REQUEST_DATA[$val];
           if($id == 'on') {
                 if($strDelete != '') {
                $strDelete .=",";
              }
                 $strDelete .= "($frameId,$roleId,$instituteId,'delete')";
              
              if($dashboardPermissionId=='on') {
                 if($strAllFrameId!='') {
                   $strAllFrameId .= ","; 
                 }
                 $strAllFrameId .= "'".$frameId."!~~!delete'";
                 
                 for($jj=0;$jj<count($instituteAllArray);$jj++) {
                   if($strAllDelete != '') {
                     $strAllDelete .=",";
                   }   
                   $ttInstituteId = $instituteAllArray[$jj]['instituteId'];
                   $strAllDelete .= "($frameId,$roleId,$ttInstituteId,'delete')";       
                 }
              }
              
           }
        }
        if($strAdd!='') {
          $return = $loginManager->insertNewRolePermissionInTransactionNew($strAdd);
           if ($return == false) {
            echo FAILURE;
            die;
          }
        }
        if($strEdit!='') {
          $return = $loginManager->insertNewRolePermissionInTransactionNew($strEdit);
           if ($return == false) {
            echo FAILURE;
            die;
          }
        }
        
        if($strView!='') {
          $return = $loginManager->insertNewRolePermissionInTransactionNew($strView);
           if ($return == false) {
            echo FAILURE;
            die;
          }
        }
        
        if($strDelete!='') {
          $return = $loginManager->insertNewRolePermissionInTransactionNew($strDelete);
           if ($return == false) {
            echo FAILURE;
            die;
          }
        }
        
        // All Institute Permission
        if($strAllFrameId!='') {
          $return = $loginManager->deleteAllNewRolePermissionInTransaction($roleId,$strAllFrameId,$instituteIds);
          if ($return == false) {
            echo FAILURE;
            die;
          }
        }
       
        if($strAllAdd!='') {
          $return = $loginManager->insertNewRolePermissionInTransactionNew($strAllAdd);
           if ($return == false) {
            echo FAILURE;
            die;
          }
        }
        if($strAllEdit!='') {
          $return = $loginManager->insertNewRolePermissionInTransactionNew($strAllEdit);
           if ($return == false) {
            echo FAILURE;
            die;
          }
        }
        
        if($strAllView!='') {
          $return = $loginManager->insertNewRolePermissionInTransactionNew($strAllView);
           if ($return == false) {
            echo FAILURE;
            die;
          }
        }
        
        if($strAllDelete!='') {
          $return = $loginManager->insertNewRolePermissionInTransactionNew($strAllDelete);
           if ($return == false) {
            echo FAILURE;
            die;
          }
        }
        
    }
    else {

    }

    if(SystemDatabaseManager::getInstance()->commitTransaction()) {
        echo SUCCESS;
    }
    else {
        echo FAILURE;
    }
}
else {
    echo FAILURE;
}
?>
