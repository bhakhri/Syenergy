<?php 
//  This File calls addFunction used in adding Country Records
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PublicationMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['publicationName']) || trim($REQUEST_DATA['publicationName']) == '') {
        $errorMessage .= ENTER_PUBLICATION_NAME."\n";
    }
	$publicationId = $REQUEST_DATA['publicationId'];
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/PublicationManager.inc.php");
         $foundArray = PublicationManager::getInstance()->getPublication(' WHERE UCASE(publicationName)="'.add_slashes($REQUEST_DATA['publicationName']).'"');
        if(trim($foundArray[0]['publicationName'])=='') {  //DUPLICATE CHECK
			        $returnStatus = PublicationManager::getInstance()->editPublication($publicationId);
                    if($returnStatus === false) {
                        $errorMessage = FAILURE;
                    }
                    else {
                        echo SUCCESS;           
                    }
              }
              else {
                echo PUBLICATION_NAME_ALREADY_EXISTS;
            }
        }
    else {
        echo $errorMessage;
    }
	?>