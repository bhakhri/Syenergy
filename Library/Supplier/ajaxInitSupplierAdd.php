<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A SUPPLIER
//
//
// Author : Gurkeerat Sidhu
// Created on : (06.05.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SupplierMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['companyName']) || trim($REQUEST_DATA['companyName']) == '')) {
        $errorMessage .= ENTER_COMPANY_NAME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['supplierCode']) || trim($REQUEST_DATA['supplierCode']) == '')) {
        $errorMessage .= ENTER_SUPPLIER_CODE."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['address']) || trim($REQUEST_DATA['address']) == '')) {
        $errorMessage .= ENTER_ADDRESS1."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['countryId']) || trim($REQUEST_DATA['countryId']) == '')) {
        $errorMessage .= SELECT_COUNTRY."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['stateId']) || trim($REQUEST_DATA['stateId']) == '')) {
        $errorMessage .= SELECT_STATE."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['cityId']) || trim($REQUEST_DATA['cityId']) == '')) {
        $errorMessage .= SELECT_CITY."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['contactPerson']) || trim($REQUEST_DATA['contactPerson']) == '')) {
        $errorMessage .= ENTER_CONTACT_PERSON_NAME."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['contactPersonPhone']) || trim($REQUEST_DATA['contactPersonPhone']) == '')) {
        $errorMessage .= ENTER_CONTACT_PERSON_PHONE."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['companyPhone']) || trim($REQUEST_DATA['companyPhone']) == '')) {
        $errorMessage .= ENTER_COMPANY_PHONE."\n";    
    }
    
   if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/SupplierManager.inc.php");
                 $foundArray = SupplierManager::getInstance()->getSupplier(' AND UCASE(companyName)= "'.add_slashes(trim(strtoupper($REQUEST_DATA['companyName']))).'" OR UCASE(supplierCode)="'.add_slashes(strtoupper($REQUEST_DATA['supplierCode'])).'"');
                 if(trim($foundArray[0]['supplierCode'])=='') {  //DUPLICATE CHECK  
                   $returnStatus = SupplierManager::getInstance()->addSupplier();
                        if($returnStatus === false) {
                            echo FAILURE;
                        }
                        else {
                            echo SUCCESS;           
                        }
                    }
                    else {
                       if(trim(strtoupper($foundArray[0]['supplierCode']))==trim(strtoupper($REQUEST_DATA['supplierCode']))){ 
                           echo SUPPLIER_CODE_EXIST;
                         die;
                       }
                       elseif($foundArray[0]['companyName']==trim($REQUEST_DATA['companyName'])){ 
                           echo COMPANY_EXIST;
                           die;
                       }
                    }
    }
    else {
        echo $errorMessage;
    }

 
?>