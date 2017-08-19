<?php

//This file calls Listing Function and creates Global Array in "Display Fees in Parent " Module 
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
    $parentManager = ParentManager::getInstance();
      
    
    $feeRecordArray = $parentManager->getFeeDetail();   
    
?>

<?php 

//$History: initDisplayFees.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Parent
//
//*****************  Version 4  *****************
//User: Arvind       Date: 8/09/08    Time: 4:23p
//Updated in $/Leap/Source/Library/Parent
//added new function 
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/31/08    Time: 6:30p
//Updated in $/Leap/Source/Library/Parent
//changed the path of ParentManager file
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/31/08    Time: 1:34p
//Updated in $/Leap/Source/Library/Parent
//added functions


?>
