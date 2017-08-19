<?php
//-------------------------------------------------------
// Purpose: To delete discipline category detail
//
// Author : Gurkeerat Sidhu
// Created on : (28.04.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisciplineCategory');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['disciplineCategoryId']) || trim($REQUEST_DATA['disciplineCategoryId']) == '') {
        $errorMessage = 'Invalid Category';
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/HostelDisciplineCatManager.inc.php");
        
			$disciplineManager =  DisciplineManager::getInstance();
			if($disciplineManager->deleteDisciplineCategory($REQUEST_DATA['disciplineCategoryId'])) {
				echo DELETE;
			}
    }
    else {
        echo $errorMessage;
    }
   
    

?>

