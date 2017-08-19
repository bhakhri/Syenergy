<?php

//This file calls Listing Function and creates Global Array in "Display Marks in Parent " Module 
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

  
    require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
    $parentManager = ParentManager::getInstance();
   
   // Creates Array for subjects of a student
   
    $studentSubjectArray = $parentManager->getDisplayMarksSubjectClass();
   
	
    
?>

<?php 

//$History: initDisplayMarks.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Parent
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/31/08    Time: 6:30p
//Updated in $/Leap/Source/Library/Parent
//changed the path of ParentManager file
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/17/08    Time: 12:08p
//Created in $/Leap/Source/Library/Parent
//Initial checkin


?>
