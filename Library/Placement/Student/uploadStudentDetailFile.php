<?php
//-------------------------------------------------------------------
// THIS FILE IS USED TO upload student photo from student login 
// Author : Dipanjan Bhattacharjee
// Created on : (14.06.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/HtmlFunctions.inc.php");
require_once(BL_PATH . "/FileUploadManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(MODEL_PATH . '/Placement/StudentUploadManager.inc.php');
define('MODULE','PlacementUploadStudentDetail');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);

$sessionHandler->setSessionVariable('Placement_UploadStudentDetails','');

$placementDriveId=trim($REQUEST_DATA['placementDriveId']);
if($placementDriveId==''){
   echo '<script language="javascript">parent.fileUploadError("'.SELECT_PLACEMENT_DRIVE.'")</script>';
   die; 
}

//check of max. file size
if($_FILES['studentFile']['name']!='' and $_FILES['studentFile']['tmp_name']==''){
    echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
    die;
}

$userId=$sessionHandler->getSessionVariable('UserId');
$instituteId=$sessionHandler->getSessionVariable('InstituteId');
$sessionId=$sessionHandler->getSessionVariable('SessionId');

$marksIds=$REQUEST_DATA['marksIds'];
if($marksIds==1) {
  $cutOffName = "BE/B.Tech";
}
else {
  $cutOffName = "Graduation";
}

$studentMgr=StudentUploadManager::getInstance();

$fileObj = FileUploadManager::getInstance('studentFile');
$fileName = $fileObj->tmp;
if($fileName==''){
    echo '<script language="javascript">parent.fileUploadError("'.SELECT_FILE_FOR_UPLOAD.'")</script>';
    die;
}

//check for whether there are data corresponding to selected placement drive
$foundArray=$studentMgr->getPlacementDrivesUsage($placementDriveId);
if($foundArray[0]['cnt']!=0){
    echo '<script language="javascript">parent.fileUploadError("'.DEPENDENCY_CONSTRAINT_EDIT.'")</script>';
    die;
}

$serverDate=explode('-',date('Y-m-d'));
$server_date=gregoriantojd($serverDate[1], $serverDate[2], $serverDate[0]);


if($fileObj->fileExtension=='xls') {
   require_once(BL_PATH . "/reader.php");
   $data = new Spreadsheet_Excel_Reader();
   $data->setOutputEncoding('CP1251');
   $data->read($fileName); 
   
   require_once(MODEL_PATH . '/DutyLeaveManager.inc.php');
   $dlMgr=DutyLeaveManager::getInstance();
   
   $m=0;
   $sheetNameArray = array();
   $inconsistenciesArray = array();
   while(isset($data->boundsheets[$m]['name'])) {
     $sheetNameArray[] =  $data->boundsheets[$m]['name'];
     $m++;
   }
  
   
   $studentMasterArray=array();
   $lc=0;
   if(SystemDatabaseManager::getInstance()->startTransaction()) {
       
       //at first delete all data corresponding to selected placement drive
       $ret=$studentMgr->deleteUploadedStudentData($placementDriveId);
       if($ret==false){
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'")</script>';
           die;
       }
       
    foreach($sheetNameArray as $sheetIndex=>$value) {
        for ($i = 1; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {
            if ($data->sheets[$sheetIndex]['cells'][1][1] != "Sr.No") {
                $inconsistenciesArray[] = "Data has not been entered in given format";
                continue;
            }
        }
        if(count($inconsistenciesArray)!=0){
            break;
        }
        for ($i = 2; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {
                 
                 $srNo = $data->sheets[$sheetIndex]['cells'][$i][1];
                 $studentTitle = trim($data->sheets[$sheetIndex]['cells'][$i][2]);
                 $studentName = trim($data->sheets[$sheetIndex]['cells'][$i][3]);
                 $fatherName = add_slashes(trim($data->sheets[$sheetIndex]['cells'][$i][4]));
                 $dob = add_slashes(trim($data->sheets[$sheetIndex]['cells'][$i][5]));
                 $corrAddress = add_slashes(trim($data->sheets[$sheetIndex]['cells'][$i][6]));
                 $permAddress = add_slashes(trim($data->sheets[$sheetIndex]['cells'][$i][7]));
                 $homeTown = add_slashes(trim($data->sheets[$sheetIndex]['cells'][$i][8]));
                 $landline = add_slashes(trim($data->sheets[$sheetIndex]['cells'][$i][9]));
                 $mobile = add_slashes(trim($data->sheets[$sheetIndex]['cells'][$i][10]));
                 $emailId = add_slashes(trim($data->sheets[$sheetIndex]['cells'][$i][11]));
                 $gender = add_slashes(trim($data->sheets[$sheetIndex]['cells'][$i][12]));
                 $course = add_slashes(trim($data->sheets[$sheetIndex]['cells'][$i][13]));
                 $discipline = add_slashes(trim($data->sheets[$sheetIndex]['cells'][$i][14]));
                 $cutOff1 = add_slashes(trim($data->sheets[$sheetIndex]['cells'][$i][15]));
                 $cutOff2 = add_slashes(trim($data->sheets[$sheetIndex]['cells'][$i][16]));
                 $cutOff3 = add_slashes(trim($data->sheets[$sheetIndex]['cells'][$i][17]));
                 //$cutOff4 = add_slashes(trim($data->sheets[$sheetIndex]['cells'][$i][18]));
                 $college = add_slashes(trim($data->sheets[$sheetIndex]['cells'][$i][18]));
                 $university = add_slashes(trim($data->sheets[$sheetIndex]['cells'][$i][19]));
                 
                 $studentTitle=preg_replace('/\s+/','',$studentTitle);
                 $studentName=preg_replace('/\s+/','',$studentName);
                 $fatherName=preg_replace('/\s+/','',$fatherName);
                 $dob=preg_replace('/\s+/','',$dob);
                 $corrAddress=preg_replace('/\s+/','',$corrAddress);
                 $permAddress=preg_replace('/\s+/','',$permAddress);
                 $homeTown=preg_replace('/\s+/','',$homeTown);
                 $landline=preg_replace('/\s+/','',$landline);
                 $mobile=preg_replace('/\s+/','',$mobile);
                 $emailId=preg_replace('/\s+/','',$emailId);
                 $gender=preg_replace('/\s+/','',$gender);
                 $course=preg_replace('/\s+/','',$course);
                 $discipline=preg_replace('/\s+/','',$discipline);
                 $cutOff1=preg_replace('/\s+/','',$cutOff1);
                 $cutOff2=preg_replace('/\s+/','',$cutOff2);
                 $cutOff3=preg_replace('/\s+/','',$cutOff3);
                 //$cutOff4=preg_replace('/\s+/','',$cutOff4);
                 $college=preg_replace('/\s+/','',$college);
                 $university=preg_replace('/\s+/','',$university);
                 
                 if($studentTitle==''){
                     $inconsistenciesArray[] = "Title can not be blank at Sr.No.'$srNo'";
                     continue;
                 }
                 
                 if($studentName==''){
                     $inconsistenciesArray[] = "Candidate name can not be blank at Sr.No.'$srNo'";
                     continue;
                 }
                 
                 if($fatherName==''){
                     $inconsistenciesArray[] = "Father's name can not be blank at Sr.No.'$srNo'";
                     continue;
                 }
                 
                 if($dob==''){
                     $inconsistenciesArray[] = "DOB can not be blank at Sr.No.'$srNo'";
                     continue;
                 }
                 
                 if($landline=='' and $mobile=='' and $emailId==''){
                     $inconsistenciesArray[] = "Any one of landline,mobile and email must not be blank at Sr.No.'$srNo'";
                     continue;
                 }
                 
                 /*******date input checking*******/
                 $date = explode('.',$dob);
                 $count = count($date);
                 $dobDate='';
                 if($count == 3) {
                  $day = $date[0];
                  $month = $date[1];
                  $year = $date[2];
                  $a = checkdate($month, $day, $year);
                  if($a) {
                    $dobDate = trim($year).'-'.trim($month).'-'.trim($day);
                    $sDate1=explode('-',$dobDate);
                    $dob_date  =gregoriantojd($sDate1[1], $sDate1[2], $sDate1[0]);
                    if(($server_date-$dob_date)<0){
                      $inconsistenciesArray[] = "DOB can not be greater than current date at Sr.No.'$srNo'";
                      continue;
                    }
                  }
                  else {
                    $inconsistenciesArray[] = "Invalid Date at Sr.No.'$srNo'";
                    continue;
                  }
                }
                else {
                    $inconsistenciesArray[] = "Invalid Date at Sr.No.'$srNo'";
                    continue;
                }
                /*******date input checking*******/
                
                //check for mobile
                if($mobile!=''){
                    if(!is_numeric($mobile)){
                      $inconsistenciesArray[] = "Invalid mobile no. at Sr.No.'$srNo'";
                      continue;  
                    }
                    
                    if(strlen($mobile)<10){
                      $inconsistenciesArray[] = "Invalid mobile no. at Sr.No.'$srNo'";
                      continue;  
                    }
                }
                 
                //check for email id
                if($emailId!=''){
                    if(HtmlFunctions::getInstance()->isEmail($emailId)==0){
                      $inconsistenciesArray[] = "Invalid email id at Sr.No.'$srNo'";
                      continue;  
                    }
                }
                
                if($gender==''){
                     $inconsistenciesArray[] = "Gender can not be blank at Sr.No.'$srNo'";
                     continue;
                }
                
                if($gender!='Male' and $gender!='Female'){
                     $inconsistenciesArray[] = "Gender can be either Male or Female at Sr.No.'$srNo'";
                     continue;
                }
                
                if($course==''){
                     $inconsistenciesArray[] = "Course can not be blank at Sr.No.'$srNo'";
                     continue;
                }
                
                if($discipline==''){
                     $inconsistenciesArray[] = "Discipline can not be blank at Sr.No.'$srNo'";
                     continue;
                }
                
                if($cutOff1==''){
                     $inconsistenciesArray[] = "Percentage in 10th can not be blank at Sr.No.'$srNo'";
                     continue;
                }
                
                if($cutOff2==''){
                     $inconsistenciesArray[] = "Percentage in 12th can not be blank at Sr.No.'$srNo'";
                     continue;
                }

				if($cutOff3==''){
                    $inconsistenciesArray[] = "Enter value for percentage in $cutOffName at Sr.No.'$srNo'";
                    continue;
                }  
                
				
                if(!is_numeric($cutOff1)){
                    $inconsistenciesArray[] = "Enter decimal values for percentage for 10th at Sr.No.'$srNo'";
                    continue;
                }
                
                if(!is_numeric($cutOff2)){
                    $inconsistenciesArray[] = "Enter decimal values for percentage for 12th at Sr.No.'$srNo'";
                    continue;
                }
               
			   if(trim($cutOff3)!=''){
                 if(!is_numeric($cutOff3)){
                    $inconsistenciesArray[] = "Enter decimal values for percentage in $cutOffName at Sr.No.'$srNo'";
                    continue;
                 }
                }
                
                if($college==''){
                     $inconsistenciesArray[] = "College name can not be blank at Sr.No.'$srNo'";
                     continue;
                }
                
                if($university==''){
                     $inconsistenciesArray[] = "University name can not be blank at Sr.No.'$srNo'";
                     continue;
                }
                
                $uniqueString=$studentName.'_'.$fatherName.'_'.$dob.'_'.$landline.'_'.$mobile.'_'.$emailId;
                
                if(in_array($uniqueString,$studentMasterArray)){
                    $inconsistenciesArray[] = "Duplicate data found at Sr.No.'$srNo'";
                    continue;
                }
                else{
                   $studentMasterArray[]=$uniqueString; 
                }
                
                //now build the insert query
                if(($lc % 20)==0){ //this is to prevent building too long insert query
                   if($insertString!=''){
                    $ret=$studentMgr->insertStudentDetails($insertString);
                    if($ret==false){
                      echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'")</script>';
                      die;  
                    }
                    $insertString='';
                   }
                    
                }
                
                if($insertString!=''){
                    $insertString .=',';
                }
                if($cutOff3==''){
                   $cutOff3='Null'; 
                }
                $insertString .=" ( '".$placementDriveId."','".$studentTitle."','".$studentName."','".$fatherName."','".$dobDate."','".$corrAddress."','".$permAddress."','".$homeTown."','".$landline."','".$mobile."','".$emailId."','".$gender."','".$course."','".$discipline."','".$cutOff1."','".$cutOff2."',".$cutOff3.",'".$college."','".$university."' )";
                
                $lc++;
            }
        }
        
        if($insertString!=''){
           $ret=$studentMgr->insertStudentDetails($insertString,$marksIds);
           if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'")</script>';
             die;  
           } 
        }
    }
  else {
       echo FAILURE;
       die;
  }
 
 /* 
  echo '<pre> ***';
  print_r($studentMasterArray);
  echo '****<br/>';
  print_r($inconsistenciesArray);
  die;
*/
 
 
 
   if (count($inconsistenciesArray) == 0) {
        if(SystemDatabaseManager::getInstance()->commitTransaction()) {
            $successString = "Data saved successfully for $lc student(s)";
            $sessionHandler->setSessionVariable('Placement_UploadStudentDetails',$successString);
            echo '<script language="javascript">parent.fileUploadError("1")</script>';
            die;
        }
    }
    else {
        $csvData = '';
        $i = 1;
        foreach($inconsistenciesArray as $key=>$record) {
            $csvData .= "$i $record\r\n";
            $i++;
        }
        $csvData = trim($csvData);
        $sessionHandler->setSessionVariable('Placement_UploadStudentDetails',$csvData);
        echo '<script language="javascript">parent.fileUploadError("0")</script>';
        die;
        
    }
    
}
else {
    echo '<script language="javascript">parent.fileUploadError("'.INCORRECT_FILE_EXTENSION.'")</script>';
    die;
}

// $History: fileUpload.php $
?>