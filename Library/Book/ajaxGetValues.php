
<?php 

////  This File checks  whether record exists in Book Form Table
//
// Author :Nancy Puri
// Created on : 04-Oct-2010
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BookMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
 
 //Function gets data from books_master table
 
if(trim($REQUEST_DATA['bookId'] ) != '') {
    require_once(MODEL_PATH . "/BookManager.inc.php");
    $foundArray = BookManager::getInstance()->getBook(' WHERE bookId="'.$REQUEST_DATA['bookId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
