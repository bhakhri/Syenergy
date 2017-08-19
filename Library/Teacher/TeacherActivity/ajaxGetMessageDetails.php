<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE message div
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (18.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH.'/HtmlFunctions.inc.php');
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['messageId'] ) != '') {
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $foundArray = TeacherManager::getInstance()->getAdminMessageList(" AND adm.messageId=".$REQUEST_DATA['messageId']);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        //echo json_encode($foundArray[0]);
        if($foundArray[0]['messageFile']!=''){
            if(file_exists(IMG_PATH.'/AdminMessage/'.$foundArray[0]['messageFile'])){
              $foundArray[0]['messageFile']='<A href="javascript:void(0);" name="'.trim($foundArray[0]['messageFile']).'" onClick="download(this.name);" title="Download Attached File">Download Attached File</a><img style="margin-bottom:-4px;" src="'.IMG_HTTP_PATH.'/download.gif" name="'.trim($foundArray[0]['messageFile']).'" onClick="download(this.name);" title="Download Attached File" />';  
            }
            else{
               $foundArray[0]['messageFile']=NOT_APPLICABLE_STRING;  
            }
        }
        else{
           $foundArray[0]['messageFile']=NOT_APPLICABLE_STRING; 
        }
        //echo   '{"messageId":"'.$foundArray[0]['messageId'].'","userName":"'.$foundArray[0]['userName'].'","subject":"'.$foundArray[0]['subject'].'","message":"'.nl2br(add_slashes(HtmlFunctions::getInstance()->removePHPJS(str_replace("\n",'',$foundArray[0]['message'])))).'","dated":"'.$foundArray[0]['dated'].'"}';
        $foundArray[0]['message']=html_entity_decode($foundArray[0]['message']);
        $foundArray[0]['dated']=UtilityManager::formatDate($foundArray[0]['dated'],true);
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetMessageDetails.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 25/08/09   Time: 17:29
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Corrected msg display in teacher dashboard
//and discipline module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/09/08    Time: 1:58p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/18/08    Time: 4:12p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity

?>