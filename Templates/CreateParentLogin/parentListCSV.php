 <?php 
//This file is used as printing version for display parent login list.
//
// Author :Parveen Sharma
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    set_time_limit(0);  
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','CreateParentLogin');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    $fIds = add_slashes($REQUEST_DATA['fIds']);
    $mIds = add_slashes($REQUEST_DATA['mIds']);
    $gIds = add_slashes($REQUEST_DATA['gIds']);
    
    $checkValue=add_slashes($REQUEST_DATA['checkValue']);                                            
    $check=add_slashes($REQUEST_DATA['check1']);

    $fcheck = add_slashes($REQUEST_DATA['fcheckbox']);
    $mcheck = add_slashes($REQUEST_DATA['mcheckbox']);
    $gcheck = add_slashes($REQUEST_DATA['gcheckbox']);
    
   // $studentIdNotPassword = add_slashes($REQUEST_DATA['studentNotIds']);  
   // $authorizedName = add_slashes($REQUEST_DATA['authorizedName']);
   // $designation = add_slashes($REQUEST_DATA['designation']);
 
 
 
 // Sorting Order
   $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy1'])) ? $REQUEST_DATA['sortOrderBy1'] : 'ASC';
   $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField1'])) ? $REQUEST_DATA['sortField1'] : 'firstName';
   
   if($sortField=='undefined') {
       $sortField='firstName';
    }
    
    if($sortOrderBy=='undefined') {
       $sortOrderBy='ASC'; 
    }
   
   $orderBy=" $sortField $sortOrderBy"; 
    
    $csvData = '';
    if($fcheck==1) {
       if($fIds!='') { 
           $conditions = " AND a.studentId IN (".$fIds.")";
           $foundArray = $studentManager->getStudentList($conditions,'');

           if(count($foundArray) > 0) {
	         $csvData .= "#, Father's User Name, Password, RollNo, Student Name, Class, Father's Name \n";
             for($i=0; $i<count($foundArray); $i++) {
               if($checkValue==1) {
                  $f = $foundArray[$i]['fatherName']; 
                  $yr = trim(substr($foundArray[$i]['DOB'],2,2)); 
                  $userPass1 = substr($f,0,stripos($f," "));
                  if($userPass1!="") {
                     $f = $userPass1;  
                  }
                  $pass = strtolower(trim($f).trim($yr));
               }
               else {
                 $pass = $check;  
               }  
               $csvData .= ($i+1).',=clean("'.$foundArray[$i]['fatherUserName'].'"),=clean("'.$pass.'"),=clean("'.$foundArray[$i]['rollNo'].'"),=clean("'.$foundArray[$i]['firstName'].'"),=clean("'.$foundArray[$i]['className'].'"),=clean("'.$foundArray[$i]['fatherName'].'")';
               $csvData .= "\n";
             }
           }
       }
    }
    
    if($mcheck==1) {
       if($mIds!='') {  
           $conditions = " AND a.studentId IN (".$mIds.")";
           $foundArray = $studentManager->getStudentList($conditions,'');
           if(count($foundArray) > 0) {
             $csvData .= "#, Mother's User Name, Password, RollNo, Student Name, Class, Mother's Name \n";
             for($i=0; $i<count($foundArray); $i++) {
               if($checkValue==1) {
                  $m = $foundArray[$i]['motherName']; 
                  $yr = trim(substr($foundArray[$i]['DOB'],2,2)); 
                  $userPass1 = substr($m,0,stripos($m," "));
                  if($userPass1!="") {
                     $m = $userPass1;  
                  }
                  $pass = strtolower(trim($m).trim($yr));
               }
               else {
                 $pass = $check;  
               }      
               $csvData .= ($i+1).',=clean("'.$foundArray[$i]['motherUserName'].'"),=clean("'.$pass.'"),=clean("'.$foundArray[$i]['rollNo'].'"),=clean("'.$foundArray[$i]['firstName'].'"),=clean("'.$foundArray[$i]['className'].'"),=clean("'.$foundArray[$i]['motherName'].'")';  
               $csvData .= "\n";
             }
           }
       }
    }
    
    if($gcheck==1) {
       if($gIds!='') { 
           $conditions = " AND a.studentId IN (".$gIds.")";  
           $foundArray = $studentManager->getStudentList($conditions,'');
           if(count($foundArray) > 0) {
             $csvData .= "#, Guardian's User Name, Password, RollNo, Student Name, Class, Guardian's Name \n";
             for($i=0; $i<count($foundArray); $i++) {
               if($checkValue==1) {
                  $g = $foundArray[$i]['guardianName']; 
                  $yr = trim(substr($foundArray[$i]['DOB'],2,2)); 
                  $userPass1 = substr($g,0,stripos($g," "));
                  if($userPass1!="") {
                     $g = $userPass1;  
                  }
                  $pass = strtolower(trim($g).trim($yr));
               }
               else {
                 $pass = $check;  
               }      
               $csvData .= ($i+1).',=clean("'.$foundArray[$i]['guardianUserName'].'"),=clean("'.$pass.'"),=clean("'.$foundArray[$i]['rollNo'].'"),=clean("'.$foundArray[$i]['firstName'].'"),=clean("'.$foundArray[$i]['className'].'"),=clean("'.$foundArray[$i]['guardianName'].'")';  
               $csvData .= "\n";
             }
           }
       }
    }
    
    
if($csvData!="") {
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="studentReport.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
}
else {
  echo "List is Empty";  
}
die;
	
//$History : $parentListCSV.php $
//
?>