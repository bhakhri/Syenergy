<?php
//-------------------------------------------------------
// Purpose: To delete fine category  detail
// Author : Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineStudentMaster');
define('ACCESS','delete');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['fineStudentId']) || trim($REQUEST_DATA['fineStudentId']) == '') {
        $errorMessage = 'Invalid Student Fine';
        die;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FineManager.inc.php");
        $fineManager =  FineManager::getInstance();
        $recordArray = $fineManager->getFineStudent(' AND fineStudentId="'.add_slashes(trim($REQUEST_DATA['fineStudentId'])).'"'); //temp. not checking for dependency
        if($recordArray[0]['paid']==0) {

           if($fineManager->deleteFineStudent($REQUEST_DATA['fineStudentId']) ) {

				if($recordArray[0]['noDues']){
				
					//echo ($recordArray[0]['amount']);
					$foundFineArray = FineManager::getInstance()->getNoDueFineStudent(' AND studentId="'.add_slashes(trim($recordArray[0]['studentId'])).'"');
							 
					if($foundFineArray[0]['amountDue']){
					
						$totalAmount = $foundFineArray[0]['amountDue']-$recordArray[0]['amount'];
						$returnNoDueStatus = FineManager::getInstance()->updateNoDueFineStudent($totalAmount,$recordArray[0]['studentId']);	
					}
				}
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
   
    
// $History: ajaxInitStudentFineDelete.php $    
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/29/09    Time: 4:53p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//fixed bugs 703,704,705,706,707,708,709,733,742,743,744,745,750,
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/09/09    Time: 10:47a
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Updated module with dependency constraint
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