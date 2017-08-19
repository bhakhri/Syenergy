<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A Fine Category
// Author : Rajeev Aggarwal
// Created on : (03.07.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineStudentMaster');
define('ACCESS','add');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    
   /* if (!isset($REQUEST_DATA['categoryName']) || trim($REQUEST_DATA['categoryName']) == '') {
        $errorMessage .= ENTER_FINE_CATEGORY_NAME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['categoryAbbr']) || trim($REQUEST_DATA['categoryAbbr']) == '')) {
        $errorMessage .= ENTER_FINE_CATEGORY_ABBR."\n"; 
    }
    */

    if (trim($errorMessage) == '') {

        require_once(MODEL_PATH . "/FineManager.inc.php");
		
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
			
        $classId = $REQUEST_DATA['classId'];    
       
        $foundArray = FineManager::getInstance()->getFineStudent(' AND cls.classId ="'. $REQUEST_DATA['classId'] .'" AND stu.studentId="'.add_slashes(trim($REQUEST_DATA['studentId'])).'" AND fc.fineCategoryId="'.add_slashes(trim($REQUEST_DATA['fineCategoryId'])).'"  AND fs.fineDate="'.add_slashes(trim($REQUEST_DATA['fineDate1'])).'"  AND fs.userId="'.add_slashes(trim($userId)).'"');
        if(trim($foundArray[0]['fineStudentId'])=='') {  //DUPLICATE CHECK

            $returnStatus = FineManager::getInstance()->addFineStudent();
            if($returnStatus === false) {
                echo FAILURE;
                die;
            }
            else {
                
                if(trim($REQUEST_DATA['dueStatus'])==1){
                    
                    $foundFineArray = FineManager::getInstance()->getNoDueFineStudent(' AND classId ="'. $REQUEST_DATA['classId'] .'" AND studentId="'.add_slashes(trim($REQUEST_DATA['studentId'])).'"');
                    
                    if($foundFineArray[0]['amountDue']){
                    
                        $totalAmount = $foundFineArray[0]['amountDue']+$REQUEST_DATA['amount'];
                        $returnNoDueStatus = FineManager::getInstance()->updateNoDueFineStudent($totalAmount,$REQUEST_DATA['studentId'],$REQUEST_DATA['classId']);    
                    }
                    else{
                    
                        $returnNoDueStatus = FineManager::getInstance()->insertNoDueFineStudent();    
                    }
                }
                echo SUCCESS;
                die;
            }
        }
        else {
          
             echo FINE_ALREADY_EXIST ;
             die;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitStudentFineAdd.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/29/09    Time: 4:53p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//fixed bugs 703,704,705,706,707,708,709,733,742,743,744,745,750,
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/08/09    Time: 7:21p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/03/09    Time: 4:30p
//Created in $/LeapCC/Library/Fine
//Intial checkin for fine student
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/07/09    Time: 16:07
//Created in $/LeapCC/Library/FineCategory
//Created "Fine Category Master" module
?>