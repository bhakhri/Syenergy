<?php    
//-------------------------------------------------------
// Purpose: To store the records of time table report in array from the database for subject centric
//
// Author : Rajeev Aggarwal
// Created on : (31.10.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentRegistrationReport');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    //UtilityManager::ifNotLoggedIn();
   // UtilityManager::headerNoCache();
   
    require_once(MODEL_PATH . "/Mentees/RegistrationManager.inc.php");
    $studentRegistrationDetailManager  = StudentRegistrationManager::getInstance();

   
$mentorName  =  $REQUEST_DATA['mentorName'];
$rollNo	     =  $REQUEST_DATA['rollNo'];
$sortField   =	$REQUEST_DATA['sortField'];
$sortOrderBy =  $REQUEST_DATA['sortOrderBy'];

$conditions='';
 if($mentorName!=''){
   $conditions=$mentorName;
     } 
  if($rollNo!=''){
      $conditions=$rollno;;
      }


   $studentRegistrationDetailArray=$studentRegistrationDetailManager->getstudentRegistrationDetails($conditions);

    
    $tableHead = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                   <tr class='rowheading'>
                     <td width='2%'  class='searchhead_text' $rowspan align='left'><b><nobr>Sr No.</nobr></b></td>
                     <td width='5%'  class='searchhead_text' $rowspan align='left'><strong><nobr>Date of Registration</nobr></strong></td>
                     <td width='5%'  class='searchhead_text' $rowspan align='left'><strong><nobr>Roll No.</nobr></strong></td>
                     <td width='5%'  class='searchhead_text' $rowspan align='left'><strong><nobr>Student Name</nobr></strong></td>
                     <td width='5%'  class='searchhead_text' $rowspan align='left'><strong><nobr>Father's Name</nobr></strong></td>
                      <td width='5%'  class='searchhead_text' $rowspan align='left'><strong><nobr>Mobile No.</nobr></strong></td>
                     <td width='10%' class='searchhead_text' $rowspan align='left'><strong><nobr>Mentor Name</nobr></strong></td>"; 
       

    echo $tableHead;
    die;
    
    
    

?>





