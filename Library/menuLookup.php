<?php
//-------------------------------------------------------
// THIS FILE IS USED for populating menu lookup on all
// pages according to the user input
// Author : Abhiraj
// Created on : (08.03.2011)
// Copyright 2008-2001: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
        
$resultSet = $sessionHandler->getSessionVariable('allModuleLabelArray');

$numOfRows=count($resultSet);
$str='';
$i=0;
foreach ($resultSet as $key=>$value)
{
    $menuItemsArray[$value['menuLabel']]=$value['menuLink'];
} 
 
$indexArray=0;
$tempArray=array();
foreach($menuItemsArray as $key=>$value)
{
    if($key)
    {
        if(stripos($key,add_slashes($REQUEST_DATA['txt']))===0 || stripos($key,add_slashes($REQUEST_DATA['txt']))>0)
        {
            $tempArray[$indexArray]['data']= ucfirst($key);
            $tempArray[$indexArray]['link']= $value;
            $indexArray++;
        }
    }  
}
if(is_array($tempArray) && count($tempArray)>0)
{
    echo json_encode($tempArray);
}
else
{
    echo "";
}    
    

//$History: ajaxConsultingGetValues.php $
?>