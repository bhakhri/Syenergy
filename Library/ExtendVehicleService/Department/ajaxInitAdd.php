<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD DEPARMENT
//
//
// Author : Jaineesh
// Created on : (20.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DepartmentMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['departmentName']) || trim($REQUEST_DATA['departmentName']) == '') {
        $errorMessage .= ENTER_DEPARTMENT_NAME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['abbr']) || trim($REQUEST_DATA['abbr']) == '')) {
        $errorMessage .= ENTER_ABBREVIATION."\n"; 
    }
    

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/DepartmentManager.inc.php");
        $foundArray = DepartmentManager::getInstance()->getDepartment('WHERE UCASE(abbr)="'.add_slashes(trim(strtoupper($REQUEST_DATA['abbr']))).'"');
		if(trim($foundArray[0]['abbr'])=='') {
		$foundArray1 = DepartmentManager::getInstance()->getDepartment('WHERE LCASE(departmentName)="'.add_slashes(trim(strtolower($REQUEST_DATA['departmentName']))).'"');	
        if(trim($foundArray1[0]['departmentName'])=='') {  //DUPLICATE CHECK
            $returnStatus = DepartmentManager::getInstance()->addDepartment();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
		else {
			echo DEPARTMENT_NAME_EXIST ;
		}
		}
        else {
            echo DEPARTMENT_ABBR_EXIST ;
        }
    }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitAdd.php $
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
//used for add data in department table
//
?>