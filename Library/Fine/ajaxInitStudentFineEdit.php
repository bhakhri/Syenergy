<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A Fine Category
// Author : Rajeev Aggarwal
// Created on : (03.07.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineStudentMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    
    /*if (!isset($REQUEST_DATA['categoryName']) || trim($REQUEST_DATA['categoryName']) == '') {
        $errorMessage .= ENTER_FINE_CATEGORY_NAME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['categoryAbbr']) || trim($REQUEST_DATA['categoryAbbr']) == '')) {
        $errorMessage .= ENTER_FINE_CATEGORY_ABBR."\n"; 
    }*/

    if (trim($errorMessage) == '') {

        require_once(MODEL_PATH . "/FineManager.inc.php");
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');

		$foundArray1 = FineManager::getInstance()->getFineStudent(' AND fineStudentId='.$REQUEST_DATA['fineStudentId']);
		if(trim($foundArray1[0]['paid'])=='0') {
                                                      
			$foundArray = FineManager::getInstance()->getFineStudent(' AND stu.studentId="'.add_slashes(trim($REQUEST_DATA['studentId'])).'" AND fc.fineCategoryId="'.add_slashes(trim($REQUEST_DATA['fineCategoryId'])).'"  AND fs.fineDate="'.add_slashes(trim($REQUEST_DATA['fineDate1'])).'"  AND fs.userId="'.add_slashes(trim($userId)).'" AND fineStudentId!='.$REQUEST_DATA['fineStudentId']);

			if(trim($foundArray[0]['fineCategoryName'])=='') {  //DUPLICATE CHECK

				$returnStatus = FineManager::getInstance()->editFineStudent($REQUEST_DATA['fineStudentId']);
				if($returnStatus === false) {

					echo FAILURE;
					die;
				}
				else {

					if(trim($REQUEST_DATA['dueStatus'])==1){
			
						if(trim($REQUEST_DATA['dueStatus'])==trim($REQUEST_DATA['oldDueStatus'])){
						
							$amount = $REQUEST_DATA['amount'];
							$oldDueAmount = $REQUEST_DATA['oldDueAmount'];
							if($amount>$oldDueAmount){

								$foundFineArray = FineManager::getInstance()->getNoDueFineStudent(' AND classId = "'.add_slashes(trim($REQUEST_DATA['classId'])).'" AND studentId="'.add_slashes(trim($REQUEST_DATA['studentId'])).'"');
								$extraAmount = $amount-$oldDueAmount;
								if($foundFineArray[0]['amountDue']){
								
									$totalAmount = $foundFineArray[0]['amountDue']+$extraAmount;
									$returnNoDueStatus = FineManager::getInstance()->updateNoDueFineStudent($totalAmount,$REQUEST_DATA['studentId'],$REQUEST_DATA['classId']);	
								}
								else{
								
									$returnNoDueStatus = FineManager::getInstance()->insertNoDueFineStudent();	
								}
							}
							else{

								$foundFineArray = FineManager::getInstance()->getNoDueFineStudent(' AND classId = "'.add_slashes(trim($REQUEST_DATA['classId'])).'"  AND studentId="'.add_slashes(trim($REQUEST_DATA['studentId'])).'"');
								$lessAmount = $oldDueAmount-$amount;
								if($foundFineArray[0]['amountDue']){
								
									$totalAmount = $foundFineArray[0]['amountDue']-$lessAmount;
									$returnNoDueStatus = FineManager::getInstance()->updateNoDueFineStudent($totalAmount,$REQUEST_DATA['studentId'],$REQUEST_DATA['classId']);	
								}
								else{
								
									$returnNoDueStatus = FineManager::getInstance()->insertNoDueFineStudent();	
								}
							
								
							}
						}
						else{
						
							$amount = $REQUEST_DATA['amount'];
							$oldDueAmount = $REQUEST_DATA['oldDueAmount'];
							$foundFineArray = FineManager::getInstance()->getNoDueFineStudent(' AND classId = "'.add_slashes(trim($REQUEST_DATA['classId'])).'"  AND studentId="'.add_slashes(trim($REQUEST_DATA['studentId'])).'"');
								 
							if($foundFineArray[0]['amountDue']){
							
								$totalAmount = $foundFineArray[0]['amountDue']+$REQUEST_DATA['amount'];
								$returnNoDueStatus = FineManager::getInstance()->updateNoDueFineStudent($totalAmount,$REQUEST_DATA['studentId'],$REQUEST_DATA['classId']);	
							}
							else{
							
								$returnNoDueStatus = FineManager::getInstance()->insertNoDueFineStudent();	
							}
						}
					}
					else{
					
						$amount = $REQUEST_DATA['amount'];
						$oldDueAmount = $REQUEST_DATA['oldDueAmount'];

						if(trim($REQUEST_DATA['dueStatus'])!=trim($REQUEST_DATA['oldDueStatus'])){
						//if($amount==$oldDueAmount){

							$foundFineArray = FineManager::getInstance()->getNoDueFineStudent(' AND classId = "'.add_slashes(trim($REQUEST_DATA['classId'])).'"  AND studentId="'.add_slashes(trim($REQUEST_DATA['studentId'])).'"');
							 
							if($foundFineArray[0]['amountDue']){
							
								$totalAmount = $foundFineArray[0]['amountDue']-$oldDueAmount;
								$returnNoDueStatus = FineManager::getInstance()->updateNoDueFineStudent($totalAmount,$REQUEST_DATA['studentId'],$REQUEST_DATA['classId']);	
							}
						//}
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
		else{
			echo FINE_ALREADY_PAID;
            die;
		}
	}
	
    else {
        echo $errorMessage;
    }

// $History: ajaxInitStudentFineEdit.php $
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/29/09    Time: 4:53p
//Updated in $/LeapCC/Library/Fine
//fixed bugs 703,704,705,706,707,708,709,733,742,743,744,745,750,
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/09/09    Time: 10:47a
//Updated in $/LeapCC/Library/Fine
//Updated module with dependency constraint
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/03/09    Time: 4:30p
//Created in $/LeapCC/Library/Fine
//Intial checkin for fine student
?>