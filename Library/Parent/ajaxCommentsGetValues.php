
<?php 

////  This File  Get the Record Data Form Table in "displaycomments" module
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH.'/HtmlFunctions.inc.php');
define('MODULE','ParentTeacherComments');
define('ACCESS','view');
UtilityManager::ifParentNotLoggedIn(true);
UtilityManager::headerNoCache();
 
 //Function gets data from notice table
function trim_output($str,$maxlength,$mode=1,$rep='...'){
   $ret=($mode==2?chunk_split($str,30):$str);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep; 
   }
  return $ret;  
}
if(trim($REQUEST_DATA['commentId'] ) != '') {
    require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
    $foundArray = ParentManager::getInstance()->getComments('  tc.commentId ='.$REQUEST_DATA['commentId']);
    //$foundArray[0]['comments']=trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($foundArray[0]['comments'])),strlen($foundArray[0]['comments']));
    if(is_array($foundArray) && count($foundArray)>0 ) {  
      //$subject  =  str_replace(array('<p>','</p>'),'',html_entity_decode($foundArray[0]['subject']));
      //$subject   =  HtmlFunctions::getInstance()->removePHPJS($subject);
      $foundArray[0]['subject']=html_entity_decode(strip_slashes($foundArray[0]['subject']));
      $foundArray[0]['comments']=html_entity_decode(strip_slashes($foundArray[0]['comments']));
      //$comments  =  str_replace(array('<p>','</p>'),'',html_entity_decode($foundArray[]['comments']));
      //$comments   =  HtmlFunctions::getInstance()->removePHPJS($comments);
      echo json_encode($foundArray[0]);
    }
    else {
      echo 0;
   }
}


//$History: ajaxCommentsGetValues.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 10/15/09   Time: 5:48p
//Updated in $/LeapCC/Library/Parent
//added access rights
//
//*****************  Version 2  *****************
//User: Parveen      Date: 9/04/09    Time: 3:01p
//Updated in $/LeapCC/Library/Parent
//div base berif information formating updated 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/18/09    Time: 5:46p
//Created in $/LeapCC/Library/Parent
//file added
//
//*****************  Version 10  *****************
//User: Parveen      Date: 8/14/09    Time: 6:40p
//Updated in $/Leap/Source/Library/Parent
//issue fix 1070, 1003, 346, 344, 1076, 1075, 1073,
//1072, 1071, 1069, 1068, 1067, 1064, 
//1063, 1061, 1060, 438 1001, 1004 
//alignment & formating, validation updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 8/11/09    Time: 11:46a
//Updated in $/Leap/Source/Library/Parent
//1002 bug fix (validation & formating updated)
//
//*****************  Version 8  *****************
//User: Parveen      Date: 8/07/09    Time: 7:21p
//Updated in $/Leap/Source/Library/Parent
//validation, features, conditions, formatting updated (bug Nos.
//331, 323, 334, 338,339, 348, 350, 351,352, 354, 380, 381,342, 349, 427,
//428, 429,430, 431, 432, 433, 434,435, 436,437, 432, 479, 480, 481,482,
//493, 494, 495, 498,501, 502,478, 477, 934, 924, 925)
//
//*****************  Version 7  *****************
//User: Arvind       Date: 9/20/08    Time: 5:41p
//Updated in $/Leap/Source/Library/Parent
//corrected error
//
//*****************  Version 6  *****************
//User: Arvind       Date: 9/12/08    Time: 3:40p
//Updated in $/Leap/Source/Library/Parent
//added removePHPJS()  function
//
//*****************  Version 5  *****************
//User: Arvind       Date: 9/11/08    Time: 6:24p
//Updated in $/Leap/Source/Library/Parent
//added facility for display div
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/31/08    Time: 6:30p
//Updated in $/Leap/Source/Library/Parent
//changed the path of ParentManager file
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/17/08    Time: 3:38p
//Updated in $/Leap/Source/Library/Parent
//changed the "ifNotLoggedIn()" function to "ifParentNotLoggedIn()"
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/16/08    Time: 10:55a
//Updated in $/Leap/Source/Library/Parent
//added comments
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/14/08    Time: 6:03p
//Created in $/Leap/Source/Library/Parent
//added new files for Parent Module


?>
