<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
if($sessionHandler->getSessionVariable('OverrideAppraisalModuleAccess')==1){
 define('MODULE','EmployeeAppraisal');
}
else{
 define('MODULE','AppraisalForm');
}
define('ACCESS','edit');
$roleId=$sessionHandler->getSessionVariable('RoleId');
if(SHOW_EMPLOYEE_APPRAISAL_FORM!=1){
  die(ACCESS_DENIED);
}
if($roleId==2){
 UtilityManager::ifTeacherNotLoggedIn();
}
else if($roleId==5){
 UtilityManager::ifManagementNotLoggedIn();
}
else if($roleId!=1 and $roleId>5){
  UtilityManager::ifNotLoggedIn();
}
else{
  die(ACCESS_DENIED);
}

if($sessionHandler->getSessionVariable('OverrideAppraisalModuleAccess')==1){
 $employeeId=$sessionHandler->getSessionVariable('EmployeeToBeAppraised');
 $superiorEmployeeId=$sessionHandler->getSessionVariable('EmployeeId');
 $genEmployeeId=$sessionHandler->getSessionVariable('EmployeeId'); //for special forms where HOD has access
}
else{
  $employeeId=$sessionHandler->getSessionVariable('EmployeeId');
  $superiorEmployeeId=0;
  $genEmployeeId=$employeeId;
}


UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/Appraisal/AppraisalDataManager.inc.php");
$appDataManager = AppraisalDataManager::getInstance();


$sessionId=$sessionHandler->getSessionVariable('SessionId');

$proofId=trim($REQUEST_DATA['proofId']);
$appraisalId=trim($REQUEST_DATA['appraisalId']);

if($proofId=='' or $appraisalId==''){
    die('Required Parameters Missing');
}

if($proofId < 1 or $proofId > 34){
   die("This Form Is Not Available");
}

if($proofId!=14 and $proofId!=1){ //where hod does not have access
  if($sessionHandler->getSessionVariable('OverrideAppraisalModuleAccess')==1){
      die(ACCESS_DENIED);
  }
}

function doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,$hodEvalueation,$sessionId){
   global $appDataManager;

  $foundArray=$appDataManager->checkMainAppraisal($appraisalId,$employeeId,$sessionId);
  if($foundArray[0]['cnt']==0){
       //insert fresh data
       $ret=$appDataManager->insertMainAppraisal($employeeId,$appraisalId,$selfEvaluation,$hodEvalueation,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
  }
  else{
       //update data
       $ret=$appDataManager->updateMainAppraisal($employeeId,$appraisalId,$selfEvaluation,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
   }
}

function doMainAppraisalHOD($employeeId,$appraisalId,$selfEvaluation,$hodEvaluation,$sessionId,$superiorEmployeeId){
   global $appDataManager,$sessionHandler;

  $foundArray=$appDataManager->checkMainAppraisal($appraisalId,$employeeId,$sessionId);
  if($foundArray[0]['cnt']==0){
       //insert fresh data
       $ret=$appDataManager->insertMainAppraisal($employeeId,$appraisalId,$selfEvaluation,$hodEvaluation,$sessionId,$superiorEmployeeId);
       if($ret==false){
           die(FAILURE);
       }
  }
  else{
       //update data
       $ret=$appDataManager->updateMainAppraisalHOD($employeeId,$appraisalId,$hodEvaluation,$sessionId,$superiorEmployeeId);
       if($ret==false){
           die(FAILURE);
       }
   }
}

if(SystemDatabaseManager::getInstance()->startTransaction()) {

   if($proofId==2){
       $cert_process=add_slashes(trim($REQUEST_DATA['cert_process']));
       $devoted=add_slashes(trim($REQUEST_DATA['devoted']));
       $supervision=add_slashes(trim($REQUEST_DATA['supervision']));

       $certification=add_slashes(trim($REQUEST_DATA['certification']));
       $assistance=add_slashes(trim($REQUEST_DATA['assistance']));
       $superValue=add_slashes(trim($REQUEST_DATA['superValue']));

       if($cert_process=='' or $devoted=='' or $supervision=='' or $certification=='' or $assistance=='' or $superValue==''){
          die('Required Parameters Missing');
       }

       if(!is_numeric($certification)){
           die('Enter numeric value');
       }
       if($certification > 10){
           die("Marks can not be greater than 10");
       }
       if(!is_numeric($assistance)){
           die('Enter numeric value');
       }
       if($assistance > 20){
           die("Marks can not be greater than 20");
       }
       if(!is_numeric($superValue)){
           die('Enter numeric value');
       }
       if($superValue > 20){
           die("Marks can not be greater than 20");
       }
       //delete prev records
       $ret=$appDataManager->deleteProofData1($employeeId,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
       //insert new records
       $ret=$appDataManager->insertProofData1($employeeId,$sessionId,$cert_process,$devoted,$supervision,$certification,$assistance,$superValue);
       if($ret==false){
           die(FAILURE);
       }

       //now update main appraisal table
       $selfEvaluation= ($certification+$assistance+$superValue);
       doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);

   }

   if($proofId==3){
       $central=add_slashes(trim($REQUEST_DATA['central']));
       $facilities=add_slashes(trim($REQUEST_DATA['facilities']));
       if($central=='' or $facilities==''){
          die('Required Parameters Missing');
       }
       if(!is_numeric($facilities)){
           die('Enter numeric value');
       }
       if($facilities > 50){
           die("Marks can not be greater than 50");
       }

       //delete prev records
       $ret=$appDataManager->deleteProofData3($employeeId,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
       //insert new records
       $ret=$appDataManager->insertProofData3($employeeId,$sessionId,$central,$facilities);
       if($ret==false){
           die(FAILURE);
       }

       //now update main appraisal table
       $selfEvaluation= ($facilities);
       doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
   }

   if($proofId==4){
       $k=0;
       $serverDate=explode('-',date('Y-m-d'));
       $serverDate=gregoriantojd($serverDate[1], $serverDate[2], $serverDate[0]);

       $act1_marks=add_slashes(trim($REQUEST_DATA['act1_marks']));
       $act2_marks=add_slashes(trim($REQUEST_DATA['act2_marks']));
       $act3_marks=add_slashes(trim($REQUEST_DATA['act3_marks']));

       if($act1_marks =='' or $act2_marks=='' or $act3_marks==''){
           die('Required Parameters Missing');
       }

       if(!is_numeric($act1_marks)){
           die('Enter numeric value');
       }
       if($act1_marks >5){
           die("Marks can not be greater than 10");
       }

       if(!is_numeric($act2_marks)){
           die('Enter numeric value');
       }
       if($act2_marks > 25){
           die("Marks can not be greater than 10");
       }

       if(!is_numeric($act3_marks)){
           die('Enter numeric value');
       }
       if($act3_marks > 20){
           die("Marks can not be greater than 10");
       }

       $insertString='';
       for($i=1;$i<11;$i++){
           if(trim($REQUEST_DATA['act'.$i])!=''){

             if(trim($REQUEST_DATA['testinput'.($i+$k)])==''){
                 die('Select held from date');
             }

             if(trim($REQUEST_DATA['testinput'.($i+$k+1)])==''){
                 die('Select held to date');
             }

             $start_date=explode('-',trim($REQUEST_DATA['testinput'.($i+$k)]));
             $end_date=explode('-',trim($REQUEST_DATA['testinput'.($i+$k+1)]));

             $start_date=gregoriantojd($start_date[1], $start_date[2], $start_date[0]);
             $end_date=gregoriantojd($end_date[1], $end_date[2], $end_date[0]);
             if(($start_date-$end_date)>0){
                 die('Held to date can not be less than held from date');
             }
           }

           if(trim($REQUEST_DATA['testinput'.($i+$k)])!=''){

             $start_date=explode('-',trim($REQUEST_DATA['testinput'.($i+$k)]));
             $start_date=gregoriantojd($start_date[1], $start_date[2], $start_date[0]);
             if(($serverDate-$start_date)<0){
                die('Held from date can not be greater than current date');
             }
             if(trim($REQUEST_DATA['act'.$i])==''){
                 die('Please enter value');
             }

             if(trim($REQUEST_DATA['testinput'.($i+$k+1)])==''){
                 die('Select held to date');
             }

             $end_date=explode('-',trim($REQUEST_DATA['testinput'.($i+$k+1)]));
             $end_date=gregoriantojd($end_date[1], $end_date[2], $end_date[0]);
             if(($start_date-$end_date)>0){
                 die('Held to date can not be less than held from date');
             }
           }

           if(trim($REQUEST_DATA['testinput'.($i+$k+1)])!=''){

             $end_date=explode('-',trim($REQUEST_DATA['testinput'.($i+$k+1)]));
             $end_date=gregoriantojd($end_date[1], $end_date[2], $end_date[0]);
             if(($serverDate-$end_date)<0){
                die('Held to date can not be greater than current date');
             }
             if(trim($REQUEST_DATA['act'.$i])==''){
                 die('Please enter value');
             }

             if(trim($REQUEST_DATA['testinput'.($i+$k)])==''){
                 die('Select held from date');
             }

             $start_date=explode('-',trim($REQUEST_DATA['testinput'.($i+$k)]));
             $start_date=gregoriantojd($start_date[1], $start_date[2], $start_date[0]);
             if(($start_date-$end_date)>0){
                 die('Held to date can not be less than held from date');
             }
           }
           if($insertString!=''){
               $insertString .=',';
           }
           $insertString .= '"'.add_slashes(trim($REQUEST_DATA['act'.$i])).'","'.add_slashes(trim($REQUEST_DATA['testinput'.($i+$k)])).'","'.add_slashes(trim($REQUEST_DATA['testinput'.($i+$k+1)])).'"';
           $k++;
       }

       if($insertString!=''){
           $insertString .=','.$act1_marks.','.$act2_marks.','.$act3_marks;
       }

       //delete prev records
       $ret=$appDataManager->deleteProofData4($employeeId,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
       //insert new records
       $ret=$appDataManager->insertProofData4($employeeId,$sessionId,$insertString);
       if($ret==false){
           die(FAILURE);
       }

       //now update main appraisal table
       $selfEvaluation= ($act1_marks+$act2_marks+$act3_marks);
       doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);

   }

   if($proofId==5){
       $k=0;
       $serverDate=explode('-',date('Y-m-d'));
       $serverDate=gregoriantojd($serverDate[1], $serverDate[2], $serverDate[0]);

       $act1_marks=add_slashes(trim($REQUEST_DATA['act1_marks']));
       $act2_marks=add_slashes(trim($REQUEST_DATA['act2_marks']));

       if($act1_marks =='' or $act2_marks==''){
           die('Required Parameters Missing');
       }

       if(!is_numeric($act1_marks)){
           die('Enter numeric value');
       }
       if($act1_marks > 30){
           die("Marks can not be greater than 30");
       }

       if(!is_numeric($act2_marks)){
           die('Enter numeric value');
       }
       if($act2_marks > 20){
           die("Marks can not be greater than 20");
       }

       $insertString='';
       for($i=1;$i<8;$i++){
           if(trim($REQUEST_DATA['act'.$i])!=''){

             if(trim($REQUEST_DATA['testinput'.($i+$k)])==''){
                 die('Select held from date');
             }

             if(trim($REQUEST_DATA['testinput'.($i+$k+1)])==''){
                 die('Select held to date');
             }

             $start_date=explode('-',trim($REQUEST_DATA['testinput'.($i+$k)]));
             $end_date=explode('-',trim($REQUEST_DATA['testinput'.($i+$k+1)]));

             $start_date=gregoriantojd($start_date[1], $start_date[2], $start_date[0]);
             $end_date=gregoriantojd($end_date[1], $end_date[2], $end_date[0]);
             if(($start_date-$end_date)>0){
                 die('Held to date can not be less than held from date');
             }
           }

           if(trim($REQUEST_DATA['testinput'.($i+$k)])!=''){

             $start_date=explode('-',trim($REQUEST_DATA['testinput'.($i+$k)]));
             $start_date=gregoriantojd($start_date[1], $start_date[2], $start_date[0]);
             if(($serverDate-$start_date)<0){
                die('Held from date can not be greater than current date');
             }
             if(trim($REQUEST_DATA['act'.$i])==''){
                 die('Please enter value');
             }

             if(trim($REQUEST_DATA['testinput'.($i+$k+1)])==''){
                 die('Select held to date');
             }

             $end_date=explode('-',trim($REQUEST_DATA['testinput'.($i+$k+1)]));
             $end_date=gregoriantojd($end_date[1], $end_date[2], $end_date[0]);
             if(($start_date-$end_date)>0){
                 die('Held to date can not be less than held from date');
             }
           }

           if(trim($REQUEST_DATA['testinput'.($i+$k+1)])!=''){

             $end_date=explode('-',trim($REQUEST_DATA['testinput'.($i+$k+1)]));
             $end_date=gregoriantojd($end_date[1], $end_date[2], $end_date[0]);
             if(($serverDate-$end_date)<0){
                die('Held to date can not be greater than current date');
             }
             if(trim($REQUEST_DATA['act'.$i])==''){
                 die('Please enter value');
             }

             if(trim($REQUEST_DATA['testinput'.($i+$k)])==''){
                 die('Select held from date');
             }

             $start_date=explode('-',trim($REQUEST_DATA['testinput'.($i+$k)]));
             $start_date=gregoriantojd($start_date[1], $start_date[2], $start_date[0]);
             if(($start_date-$end_date)>0){
                 die('Held to date can not be less than held from date');
             }
           }
           if($insertString!=''){
               $insertString .=',';
           }
           $insertString .= '"'.add_slashes(trim($REQUEST_DATA['act'.$i])).'","'.add_slashes(trim($REQUEST_DATA['testinput'.($i+$k)])).'","'.add_slashes(trim($REQUEST_DATA['testinput'.($i+$k+1)])).'"';
           $k++;
       }

       if($insertString!=''){
           $insertString .=','.$act1_marks.','.$act2_marks;
       }

       //delete prev records
       $ret=$appDataManager->deleteProofData5($employeeId,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
       //insert new records
       $ret=$appDataManager->insertProofData5($employeeId,$sessionId,$insertString);
       if($ret==false){
           die(FAILURE);
       }

       //now update main appraisal table
       $selfEvaluation= ($act1_marks+$act2_marks);
       doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);

   }

   if($proofId==6){
       $k=0;
       $serverDate=explode('-',date('Y-m-d'));
       $serverDate=gregoriantojd($serverDate[1], $serverDate[2], $serverDate[0]);

       $act1_marks=add_slashes(trim($REQUEST_DATA['act1_marks']));
       $act2_marks=add_slashes(trim($REQUEST_DATA['act2_marks']));
       $act3_marks=add_slashes(trim($REQUEST_DATA['act3_marks']));

       if($act1_marks =='' or $act2_marks=='' or $act3_marks==''){
           die('Required Parameters Missing');
       }

       if(!is_numeric($act1_marks)){
           die('Enter numeric value');
       }
       if($act1_marks > 80){
           die("Marks can not be greater than 80");
       }

       if(!is_numeric($act2_marks)){
           die('Enter numeric value');
       }
       if($act2_marks > 40){
           die("Marks can not be greater than 40");
       }

       if(!is_numeric($act3_marks)){
           die('Enter numeric value');
       }
       if($act3_marks > 30){
           die("Marks can not be greater than 30");
       }

       $insertString='';
       for($i=1;$i<15;$i++){
           if(trim($REQUEST_DATA['act'.$i])!=''){

             if(trim($REQUEST_DATA['testinput'.($i+$k)])==''){
                 die('Select held from date');
             }

             if(trim($REQUEST_DATA['testinput'.($i+$k+1)])==''){
                 die('Select held to date');
             }

             $start_date=explode('-',trim($REQUEST_DATA['testinput'.($i+$k)]));
             $end_date=explode('-',trim($REQUEST_DATA['testinput'.($i+$k+1)]));

             $start_date=gregoriantojd($start_date[1], $start_date[2], $start_date[0]);
             $end_date=gregoriantojd($end_date[1], $end_date[2], $end_date[0]);
             if(($start_date-$end_date)>0){
                 die('Held to date can not be less than held from date');
             }
           }

           if(trim($REQUEST_DATA['testinput'.($i+$k)])!=''){

             $start_date=explode('-',trim($REQUEST_DATA['testinput'.($i+$k)]));
             $start_date=gregoriantojd($start_date[1], $start_date[2], $start_date[0]);
             if(($serverDate-$start_date)<0){
                die('Held from date can not be greater than current date');
             }
             if(trim($REQUEST_DATA['act'.$i])==''){
                 die('Please enter value***');
             }

             if(trim($REQUEST_DATA['testinput'.($i+$k+1)])==''){
                 die('Select held to date');
             }

             $end_date=explode('-',trim($REQUEST_DATA['testinput'.($i+$k+1)]));
             $end_date=gregoriantojd($end_date[1], $end_date[2], $end_date[0]);
             if(($start_date-$end_date)>0){
                 die('Held to date can not be less than held from date');
             }
           }

           if(trim($REQUEST_DATA['testinput'.($i+$k+1)])!=''){

             $end_date=explode('-',trim($REQUEST_DATA['testinput'.($i+$k+1)]));
             $end_date=gregoriantojd($end_date[1], $end_date[2], $end_date[0]);
             if(($serverDate-$end_date)<0){
                die('Held to date can not be greater than current date');
             }
             if(trim($REQUEST_DATA['act'.$i])==''){
                 die('Please enter value');
             }

             if(trim($REQUEST_DATA['testinput'.($i+$k)])==''){
                 die('Select held from date');
             }

             $start_date=explode('-',trim($REQUEST_DATA['testinput'.($i+$k)]));
             $start_date=gregoriantojd($start_date[1], $start_date[2], $start_date[0]);
             if(($start_date-$end_date)>0){
                 die('Held to date can not be less than held from date');
             }
           }
           if($insertString!=''){
               $insertString .=',';
           }
           $insertString .= '"'.add_slashes(trim($REQUEST_DATA['act'.$i])).'","'.add_slashes(trim($REQUEST_DATA['testinput'.($i+$k)])).'","'.add_slashes(trim($REQUEST_DATA['testinput'.($i+$k+1)])).'"';
           $k++;
       }

       if($insertString!=''){
           $insertString .=','.$act1_marks.','.$act2_marks.','.$act3_marks;
       }

       //delete prev records
       $ret=$appDataManager->deleteProofData6($employeeId,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
       //insert new records
       $ret=$appDataManager->insertProofData6($employeeId,$sessionId,$insertString);
       if($ret==false){
           die(FAILURE);
       }

       //now update main appraisal table
       $selfEvaluation= ($act1_marks+$act2_marks+$act3_marks);
       doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);

   }

  if($proofId==7){
       $newlab=add_slashes(trim($REQUEST_DATA['newlab']));
       $labname=add_slashes(trim($REQUEST_DATA['labname']));
       $act1_marks=add_slashes(trim($REQUEST_DATA['act1_marks']));

       $major_equip=add_slashes(trim($REQUEST_DATA['major_equip']));
       $major_equip2=add_slashes(trim($REQUEST_DATA['major_equip2']));
       $act2_marks=add_slashes(trim($REQUEST_DATA['act2_marks']));

       $maint_equip=add_slashes(trim($REQUEST_DATA['maint_equip']));
       $act3_marks=add_slashes(trim($REQUEST_DATA['act3_marks']));

       $testing_meas=add_slashes(trim($REQUEST_DATA['testing_meas']));
       $testing_meas2=add_slashes(trim($REQUEST_DATA['testing_meas2']));
       $act4_marks=add_slashes(trim($REQUEST_DATA['act4_marks']));

       if($newlab!=''){
           if($labname==''){
               die("Enter lab names");
           }
       }

       if($labname!=''){
           if($newlab==''){
               die("Enter no. of labs");
           }
       }

       if($major_equip!='0'){
           if($major_equip2==''){
               die("Enter details");
           }
       }

       if($major_equip2!=''){
           if($major_equip=='0'){
               die("Select amount");
           }
       }

       if($maint_equip!=''){
           if($act3_marks==''){
               die("Enter marks");
           }
       }

       if($act3_marks!='' and $act3_marks!=0){
           if($maint_equip==''){
               die("This field can not be left blank");
           }

           if(!is_numeric($act3_marks)){
               die('Enter numeric value');
           }

           if($act3_marks>20){
               die('Marks can not be greater than 20');
           }
       }

       if($testing_meas!='0'){
           if($testing_meas2==''){
               die("Enter details");
           }
       }

       if($testing_meas2!=''){
           if($testing_meas=='0'){
               die("Select amount");
           }
       }

       //die('dipu');

       //delete prev records
       $ret=$appDataManager->deleteProofData7($employeeId,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
       //insert new records
       $ret=$appDataManager->insertProofData7($employeeId,$sessionId,$newlab,$labname,$act1_marks,$major_equip,$major_equip2,$act2_marks,$maint_equip,$act3_marks,$testing_meas,$testing_meas2,$act4_marks);
       if($ret==false){
           die(FAILURE);
       }

       //now update main appraisal table
       $selfEvaluation= ($act1_marks+$act2_marks+$act3_marks+$act4_marks);
       doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
   }

  if($proofId==8){
       $qty_lab=add_slashes(trim($REQUEST_DATA['qty_lab']));
       $new_manual=add_slashes(trim($REQUEST_DATA['new_manual']));
       $act1_marks=add_slashes(trim($REQUEST_DATA['act1_marks']));

       if($qty_lab!=''){
           if(!is_numeric($qty_lab)){
               die('Enter numeric value');
           }
           if($new_manual==''){
               die('This field can not bel left blank');
           }
           if($act1_marks==''){
               die('Enter marks');
           }
           if(!is_numeric($act1_marks)){
               die('Enter numeric value');
           }
           if($act1_marks>20){
               die('Marks can not be greater than 20');
           }
       }

       if($new_manual!=''){
           if(!is_numeric($qty_lab)){
               die('Enter numeric value');
           }
           /*if($qty_lab==0){
               die('Enter values greater than zero');
           }*/
           if($act1_marks==''){
               die('Enter marks');
           }
           if(!is_numeric($act1_marks)){
               die('Enter numeric value');
           }
           if($act1_marks>20){
               die('Marks can not be greater than 20');
           }
       }

       if($act1_marks!='' and $act1_marks!=0){
           if(!is_numeric($qty_lab)){
               die('Enter numeric value');
           }
         /*  if($qty_lab==0){
               die('Enter values greater than zero');
           }*/
           if($new_manual==''){
               die('This field can not bel left blank');
           }
           if(!is_numeric($act1_marks)){
               die('Enter numeric value');
           }
           if($act1_marks>20){
               die('Marks can not be greater than 20');
           }
       }

       $existing_manual=add_slashes(trim($REQUEST_DATA['existing_manual']));
       $act2_marks=add_slashes(trim($REQUEST_DATA['act2_marks']));

       if($existing_manual!=''){
           if($act2_marks==''){
               die('Enter marks');
           }
           if(!is_numeric($act2_marks)){
               die('Enter numeric values');
           }
           if($act2_marks>10){
               die('Marks can not be greater than 10');
           }
       }

       if($act2_marks!='' and $act2_marks!=0){
           if($existing_manual==''){
               die('This field can not be left blank');
           }
           if(!is_numeric($act2_marks)){
               die('Enter numeric values');
           }
           if($act2_marks>10){
               die('Marks can not be greater than 10');
           }
       }

       //delete prev records
       $ret=$appDataManager->deleteProofData8($employeeId,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
       //insert new records
       $ret=$appDataManager->insertProofData8($employeeId,$sessionId,$qty_lab,$new_manual,$act1_marks,$existing_manual,$act2_marks);
       if($ret==false){
           die(FAILURE);
       }

       //now update main appraisal table
       $selfEvaluation= ($act1_marks+$act2_marks);
       doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
   }

   if($proofId==9){
       $act1_marks=add_slashes(trim($REQUEST_DATA['act1_marks']));
       $strength_indus=add_slashes(trim($REQUEST_DATA['strength_indus']));
       $location_indus=add_slashes(trim($REQUEST_DATA['location_indus']));
       $indus_datefrom=add_slashes(trim($REQUEST_DATA['indus_datefrom']));
       $indus_dateto=add_slashes(trim($REQUEST_DATA['indus_dateto']));

       $act2_marks=add_slashes(trim($REQUEST_DATA['act2_marks']));
       $strength_indus2=add_slashes(trim($REQUEST_DATA['strength_indus2']));
       $location_indus2=add_slashes(trim($REQUEST_DATA['location_indus2']));
       $indus_datefrom2=add_slashes(trim($REQUEST_DATA['indus_datefrom2']));
       $indus_dateto2=add_slashes(trim($REQUEST_DATA['indus_dateto2']));

       $reportSubmitted=add_slashes(trim($REQUEST_DATA['reportSubmitted']));
       $act3_marks=add_slashes(trim($REQUEST_DATA['act3_marks']));

       $act4_marks=add_slashes(trim($REQUEST_DATA['act4_marks']));
       $strength_trips=add_slashes(trim($REQUEST_DATA['strength_trips']));
       $location_trips=add_slashes(trim($REQUEST_DATA['location_trips']));
       $trips_datefrom=add_slashes(trim($REQUEST_DATA['trips_datefrom']));
       $trips_dateto=add_slashes(trim($REQUEST_DATA['trips_datefrom']));

       $act5_marks=add_slashes(trim($REQUEST_DATA['act5_marks']));
       $strength_trips2=add_slashes(trim($REQUEST_DATA['strength_trips2']));
       $location_trips2=add_slashes(trim($REQUEST_DATA['location_trips2']));
       $trips_datefrom2=add_slashes(trim($REQUEST_DATA['trips_datefrom2']));
       $trips_dateto2=add_slashes(trim($REQUEST_DATA['trips_datefrom2']));


       //delete prev records
       $ret=$appDataManager->deleteProofData9($employeeId,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
       //insert new records
       $ret=$appDataManager->insertProofData9($employeeId,$sessionId,$act1_marks,$strength_indus,$location_indus,$indus_datefrom,$indus_dateto,$act2_marks,$strength_indus2,$location_indus2,$indus_datefrom2,$indus_dateto2,$reportSubmitted,$act3_marks,$act4_marks,$strength_trips,$location_trips,$trips_datefrom,$trips_dateto,$act5_marks,$strength_trips2,$location_trips2,$trips_datefrom2,$trips_dateto2);
       if($ret==false){
           die(FAILURE);
       }

       //now update main appraisal table
	   $selfEvaluation= ($act1_marks+$act2_marks+$act3_marks+$act4_marks+$act5_marks);
       doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
   }

   if($proofId==10){
       $eventname_org=add_slashes(trim($REQUEST_DATA['eventname_org']));
       $org_strength=add_slashes(trim($REQUEST_DATA['org_strength']));
       $org_budget=add_slashes(trim($REQUEST_DATA['org_budget']));
       $org_heldfrom=add_slashes(trim($REQUEST_DATA['org_heldfrom']));
       $org_heldto=add_slashes(trim($REQUEST_DATA['org_heldto']));

       $eventname_org2=add_slashes(trim($REQUEST_DATA['eventname_org2']));
       $org_strength2=add_slashes(trim($REQUEST_DATA['org_strength2']));
       $org_budget2=add_slashes(trim($REQUEST_DATA['org_budget2']));
       $org_heldfrom2=add_slashes(trim($REQUEST_DATA['org_heldfrom2']));
       $org_heldto2=add_slashes(trim($REQUEST_DATA['org_heldto2']));

       $eventname_assisted=add_slashes(trim($REQUEST_DATA['eventname_assisted']));
       $assisted_strength=add_slashes(trim($REQUEST_DATA['assisted_strength']));
       $assisted_budget=add_slashes(trim($REQUEST_DATA['assisted_budget']));
       $assisted_heldfrom=add_slashes(trim($REQUEST_DATA['assisted_heldfrom']));
       $assisted_heldto=add_slashes(trim($REQUEST_DATA['assisted_heldto']));

       $eventname_assisted2=add_slashes(trim($REQUEST_DATA['eventname_assisted2']));
       $assisted_strength2=add_slashes(trim($REQUEST_DATA['assisted_strength2']));
       $assisted_budget2=add_slashes(trim($REQUEST_DATA['assisted_budget2']));
       $assisted_heldfrom2=add_slashes(trim($REQUEST_DATA['assisted_heldfrom2']));
       $assisted_heldto2=add_slashes(trim($REQUEST_DATA['assisted_heldto2']));

       $act1_marks=add_slashes(trim($REQUEST_DATA['act1_marks']));
       $act2_marks=add_slashes(trim($REQUEST_DATA['act2_marks']));
       $act3_marks=add_slashes(trim($REQUEST_DATA['act3_marks']));
       $act4_marks=add_slashes(trim($REQUEST_DATA['act4_marks']));


       //delete prev records
       $ret=$appDataManager->deleteProofData10($employeeId,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
       //insert new records
       $ret=$appDataManager->insertProofData10($employeeId,$sessionId,$eventname_org,$org_strength,$org_budget,$org_heldfrom,$org_heldto,$act1_marks,$eventname_org2,$org_strength2,$org_budget2,$org_heldfrom2,$org_heldto2,$act2_marks,$eventname_assisted,$assisted_strength,$assisted_budget,$assisted_heldfrom,$assisted_heldto,$act3_marks,$eventname_assisted2,$assisted_strength2,$assisted_budget2,$assisted_heldfrom2,$assisted_heldto2,$act4_marks);
       if($ret==false){
           die(FAILURE);
       }

       //now update main appraisal table
       $selfEvaluation= ($act1_marks+$act2_marks+$act3_marks+$act4_marks);
       doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
   }

   if($proofId==11){
       $even_incharge=add_slashes(trim($REQUEST_DATA['even_incharge']));
       $even_cases=add_slashes(trim($REQUEST_DATA['even_cases']));
       $even_checked=add_slashes(trim($REQUEST_DATA['even_checked']));
       $even_cases_count=add_slashes(trim($REQUEST_DATA['even_cases_count']));
       $even_achieve1=add_slashes(trim($REQUEST_DATA['even_achieve1']));
       $even_desc1=add_slashes(trim($REQUEST_DATA['even_desc1']));
       $even_role1=add_slashes(trim($REQUEST_DATA['even_role1']));
       $even_achieve2=add_slashes(trim($REQUEST_DATA['even_achieve2']));
       $even_desc2=add_slashes(trim($REQUEST_DATA['even_desc2']));
       $even_role2=add_slashes(trim($REQUEST_DATA['even_role2']));

       $odd_incharge=add_slashes(trim($REQUEST_DATA['odd_incharge']));
       $odd_cases=add_slashes(trim($REQUEST_DATA['odd_cases']));
       $odd_checked=add_slashes(trim($REQUEST_DATA['odd_checked']));
       $odd_cases_count=add_slashes(trim($REQUEST_DATA['odd_cases_count']));
       $odd_achieve1=add_slashes(trim($REQUEST_DATA['odd_achieve1']));
       $odd_desc1=add_slashes(trim($REQUEST_DATA['odd_desc1']));
       $odd_role1=add_slashes(trim($REQUEST_DATA['odd_role1']));
       $odd_achieve2=add_slashes(trim($REQUEST_DATA['odd_achieve2']));
       $odd_desc2=add_slashes(trim($REQUEST_DATA['odd_desc2']));
       $odd_role2=add_slashes(trim($REQUEST_DATA['odd_role2']));

       $act1_marks=add_slashes(trim($REQUEST_DATA['act1_marks']));
       $act2_marks=add_slashes(trim($REQUEST_DATA['act2_marks']));
       $act3_marks=add_slashes(trim($REQUEST_DATA['act3_marks']));
       $act4_marks=add_slashes(trim($REQUEST_DATA['act4_marks']));
       $act5_marks=add_slashes(trim($REQUEST_DATA['act5_marks']));
       $act6_marks=add_slashes(trim($REQUEST_DATA['act6_marks']));

       //delete prev records
       $ret=$appDataManager->deleteProofData11($employeeId,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
       if($act1_marks != '' or $act2_marks != '' or $act3_marks != '' or $act4_marks !='' or $act5_marks !='' or $act6_marks !=''){
			 //insert new records
			 $ret=$appDataManager->insertProofData11($employeeId,$sessionId,$even_incharge,$even_cases,$even_achieve1,$even_desc1,$even_role1,$even_achieve2,$even_desc2,$even_role2,$odd_incharge,$odd_cases,$odd_achieve1,$odd_desc1,$odd_role1,$odd_achieve2,$odd_desc2,$odd_role2,$act1_marks,$act2_marks,$act3_marks,$act4_marks,$act5_marks,$act6_marks,$even_checked,$even_cases_count,$odd_checked,$odd_cases_count);
			 if($ret==false){
				  die(FAILURE);
			 }
       }

       //now update main appraisal table
       $selfEvaluation= ceil((($act1_marks+$act2_marks+$act3_marks+$act4_marks+$act5_marks+$act6_marks)/2));
       doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
   }

   if($proofId==12){
       $track1=add_slashes(trim($REQUEST_DATA['track1']));
       $track2=add_slashes(trim($REQUEST_DATA['track2']));
       $track3=add_slashes(trim($REQUEST_DATA['track3']));
       $track4=add_slashes(trim($REQUEST_DATA['track4']));

       $act1_marks=add_slashes(trim($REQUEST_DATA['act1_marks']));
       $act2_marks=add_slashes(trim($REQUEST_DATA['act2_marks']));
       $act3_marks=add_slashes(trim($REQUEST_DATA['act3_marks']));
       $act4_marks=add_slashes(trim($REQUEST_DATA['act4_marks']));

       if($act1_marks=='' or $act2_marks=='' or $act3_marks=='' or $act4_marks==''){
           die('Required Parameters Missing');
       }

       //check for co-ordinator
       $empArray=$appDataManager->checkCoordinator($employeeId);
       $isCoordinator=$empArray[0]['isCoordinator'];

       //get appropriate proof data
       $proofArray=$appDataManager->gerProofData($proofId,$employeeId,$sessionId);

       if($isCoordinator==1){
         $a1=$act1_marks;
         $a2=($proofArray[0]['act2_marks']==''?0:$proofArray[0]['act2_marks']);
         $a3=$act2_marks;
         $a4=($proofArray[0]['act4_marks']==''?0:$proofArray[0]['act4_marks']);
         $a5=$act3_marks;
         $a6=($proofArray[0]['act6_marks']==''?0:$proofArray[0]['act6_marks']);
         $a7=$act4_marks;
         $a8=($proofArray[0]['act8_marks']==''?0:$proofArray[0]['act8_marks']);
       }
       else{
         $a1=($proofArray[0]['act1_marks']==''?0:$proofArray[0]['act1_marks']);
         $a2=$act1_marks;
         $a3=($proofArray[0]['act3_marks']==''?0:$proofArray[0]['act3_marks']);
         $a4=$act2_marks;
         $a5=($proofArray[0]['act5_marks']==''?0:$proofArray[0]['act5_marks']);
         $a6=$act3_marks;
         $a7=($proofArray[0]['act7_marks']==''?0:$proofArray[0]['act7_marks']);
         $a8=$act4_marks;
       }

       //delete prev records
       $ret=$appDataManager->deleteProofData12($employeeId,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
       //insert new records
       $ret=$appDataManager->insertProofData12($employeeId,$sessionId,$track1,$track2,$track3,$track4,$a1,$a2,$a3,$a4,$a5,$a6,$a7,$a8);
       if($ret==false){
           die(FAILURE);
       }

       //now update main appraisal table
       $selfEvaluation= ($act1_marks+$act2_marks+$act3_marks+$act4_marks);
       doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
   }

   if($proofId==13){
       $feed_even=add_slashes(trim($REQUEST_DATA['feed_even']));
       $feed_odd=add_slashes(trim($REQUEST_DATA['feed_odd']));

       if($feed_even==''){
          die('Please enter value');
       }
       if(!is_numeric($feed_even)){
         die('Please enter numeric value');
       }
       if($feed_even>50){
           die('Value can not be greater than 50');
       }

       if($feed_odd==''){
          die('Please enter value');
       }
       if(!is_numeric($feed_odd)){
         die('Please enter numeric value');
       }
       if($feed_odd>50){
           die('Value can not be greater than 50');
       }

       //delete prev records
       $ret=$appDataManager->deleteProofData13($employeeId,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
       //insert new records
       $ret=$appDataManager->insertProofData13($employeeId,$sessionId,$feed_even,$feed_odd);
       if($ret==false){
           die(FAILURE);
       }

       //now update main appraisal table
       $selfEvaluation= ceil(($feed_even+$feed_odd)/2);
       doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);

   }

   if($proofId==15){
       $even_prob_design=add_slashes(trim($REQUEST_DATA['even_prob_design']));
       $even_avg_marks=add_slashes(trim($REQUEST_DATA['even_avg_marks']));
       $even_eminent=add_slashes(trim($REQUEST_DATA['even_eminent']));
       $odd_prob_design=add_slashes(trim($REQUEST_DATA['odd_prob_design']));
       $odd_avg_marks=add_slashes(trim($REQUEST_DATA['odd_avg_marks']));
       $odd_eminent=add_slashes(trim($REQUEST_DATA['odd_eminent']));

       $act1_marks=add_slashes(trim($REQUEST_DATA['act1_marks']));
       $act2_marks=add_slashes(trim($REQUEST_DATA['act2_marks']));
       $act3_marks=add_slashes(trim($REQUEST_DATA['act3_marks']));
       $act4_marks=add_slashes(trim($REQUEST_DATA['act4_marks']));
       $act5_marks=add_slashes(trim($REQUEST_DATA['act5_marks']));
       $act6_marks=add_slashes(trim($REQUEST_DATA['act6_marks']));

       $times1=add_slashes(trim($REQUEST_DATA['times1']));
       $times2=add_slashes(trim($REQUEST_DATA['times2']));
       $times3=add_slashes(trim($REQUEST_DATA['times3']));
       $times4=add_slashes(trim($REQUEST_DATA['times4']));
       $times5=add_slashes(trim($REQUEST_DATA['times5']));
       $times6=add_slashes(trim($REQUEST_DATA['times6']));

       /* if($times1=='' or $times2=='' or $times3=='' or $times4=='' or $times5=='' or $times6==''){
           die('Required Parameters Missing');
       }

       if($act1_marks=='' or $act2_marks=='' or $act3_marks=='' or $act4_marks=='' or $act5_marks=='' or $act6_marks==''){
           die('Required Parameters Missing');
       } */

       //delete prev records
       $ret=$appDataManager->deleteProofData15($employeeId,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
       //insert new records
	   if($times1 != '' or $times2 !='' or $times3 != '' or $times4 != '' or $times5 != '' or $times6 != '' or $act1_marks != '' or $act2_marks != '' or $act3_marks != '' or $act4_marks != '' or $act5_marks != '' or $act6_marks != '') {
			$ret = $appDataManager->insertProofData15($employeeId, $sessionId, $even_prob_design, $even_avg_marks, $even_eminent, $odd_prob_design, $odd_avg_marks, $odd_eminent, $times1,$times2, $times3, $times4, $times5, $times6, $act1_marks, $act2_marks, $act3_marks, $act4_marks, $act5_marks, $act6_marks);
			if($ret==false){
				die(FAILURE);
			}
       }

       //now update main appraisal table
       $selfEvaluation= ceil((($act1_marks+$act2_marks+$act3_marks+$act4_marks+$act5_marks+$act6_marks)/2));
       doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
   }

   if($proofId==16){
       $even_advisor=add_slashes(trim($REQUEST_DATA['even_advisor']));
       $even_avg_gfs=add_slashes(trim($REQUEST_DATA['even_avg_gfs']));
       $even_adv_mt=add_slashes(trim($REQUEST_DATA['even_adv_mt']));
       $even_indis=add_slashes(trim($REQUEST_DATA['even_indis']));
       $odd_advisor=add_slashes(trim($REQUEST_DATA['odd_advisor']));
       $odd_avg_gfs=add_slashes(trim($REQUEST_DATA['odd_avg_gfs']));
       $odd_adv_mt=add_slashes(trim($REQUEST_DATA['odd_adv_mt']));
       $odd_indis=add_slashes(trim($REQUEST_DATA['odd_indis']));

       $act1_marks=add_slashes(trim($REQUEST_DATA['act1_marks']));
       $act2_marks=add_slashes(trim($REQUEST_DATA['act2_marks']));
       $act3_marks=add_slashes(trim($REQUEST_DATA['act3_marks']));
       $act4_marks=add_slashes(trim($REQUEST_DATA['act4_marks']));
       $act5_marks=add_slashes(trim($REQUEST_DATA['act5_marks']));
       $act6_marks=add_slashes(trim($REQUEST_DATA['act6_marks']));

       $times1=add_slashes(trim($REQUEST_DATA['times1']));
       $times2=add_slashes(trim($REQUEST_DATA['times2']));
       $times3=add_slashes(trim($REQUEST_DATA['times3']));
       $times4=add_slashes(trim($REQUEST_DATA['times4']));
       $times5=add_slashes(trim($REQUEST_DATA['times5']));
       $times6=add_slashes(trim($REQUEST_DATA['times6']));

       if($times1=='' or $times2=='' or $times3=='' or $times4=='' or $times5=='' or $times6==''){
           die('Required Parameters Missing');
       }

       if($act1_marks=='' or $act2_marks=='' or $act3_marks=='' or $act4_marks=='' or $act5_marks=='' or $act6_marks==''){
           die('Required Parameters Missing');
       }

       //delete prev records
       $ret=$appDataManager->deleteProofData16($employeeId,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
       //insert new records
       $ret=$appDataManager->insertProofData16($employeeId,$sessionId,$even_advisor,$even_avg_gfs,$even_adv_mt,$even_indis,$odd_advisor,$odd_avg_gfs,$odd_adv_mt,$odd_indis,$times1,$times2,$times3,$times4,$times5,$times6,$act1_marks,$act2_marks,$act3_marks,$act4_marks,$act5_marks,$act6_marks);
       if($ret==false){
           die(FAILURE);
       }

       //now update main appraisal table
       $selfEvaluation= ceil((($act1_marks+$act2_marks+$act3_marks+$act4_marks+$act5_marks+$act6_marks)/2));
       doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
   }

   if($proofId==17){
       $act1=add_slashes(trim($REQUEST_DATA['act1']));
       $act2=add_slashes(trim($REQUEST_DATA['act2']));
       $act3=add_slashes(trim($REQUEST_DATA['act3']));
       $act4=add_slashes(trim($REQUEST_DATA['act4']));
       $budget1=add_slashes(trim($REQUEST_DATA['budget1']));
       $budget2=add_slashes(trim($REQUEST_DATA['budget2']));
       $budget3=add_slashes(trim($REQUEST_DATA['budget3']));
       $budget4=add_slashes(trim($REQUEST_DATA['budget4']));
       $org_duties=add_slashes(trim($REQUEST_DATA['org_duties']));
       $imp_duties=add_slashes(trim($REQUEST_DATA['imp_duties']));

       $act1_marks=add_slashes(trim($REQUEST_DATA['act1_marks']));
       $act2_marks=add_slashes(trim($REQUEST_DATA['act2_marks']));
       $act3_marks=add_slashes(trim($REQUEST_DATA['act3_marks']));
       $act4_marks=add_slashes(trim($REQUEST_DATA['act4_marks']));
       $act5_marks=add_slashes(trim($REQUEST_DATA['act5_marks']));
       $act6_marks=add_slashes(trim($REQUEST_DATA['act6_marks']));

       $duties1=add_slashes(trim($REQUEST_DATA['duties1']));
       $duties2=add_slashes(trim($REQUEST_DATA['duties2']));

       if(trim($act1)!=''){
           if(trim($budget1)=='0'){
               die('Please select budget(with details)');
           }
       }
       if(trim($budget1)!='0'){
           if(trim($act1)==''){
               die('Please enter value');
           }
       }

       if(trim($act2)!=''){
           if(trim($budget2)=='0'){
               die('Please select budget(with details)');
           }
       }
       if(trim($budget2)!='0'){
           if(trim($act2)==''){
               die('Please enter value');
           }
       }

       if(trim($act3)!=''){
           if(trim($budget3)=='0'){
               die('Please select budget(with details)');
           }
       }
       if(trim($budget3)!='0'){
           if(trim($act3)==''){
               die('Please enter value');
           }
       }

       if(trim($act4)!=''){
           if(trim($budget4)=='0'){
               die('Please select budget(with details)');
           }
       }
       if(trim($budget4)!='0'){
           if(trim($act4)==''){
               die('Please enter value');
           }
       }

       if(!is_numeric($duties1)){
           die('Enter numeric value');
       }
       if(!is_numeric($duties1)){
           die('Enter numeric value');
       }

       if($duties1>5){
           die('Value can not be greater than 5');
       }

       if($duties2>10){
           die('Value can not be greater than 10');
       }


       //delete prev records
       $ret=$appDataManager->deleteProofData17($employeeId,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
       //insert new records
       $ret=$appDataManager->insertProofData17($employeeId,$sessionId,$act1,$act2,$act3,$act4,$budget1,$budget2,$budget3,$budget4,$org_duties,$imp_duties,$act1_marks,$act2_marks,$act3_marks,$act4_marks,$duties1,$act5_marks,$duties2,$act6_marks);
       if($ret==false){
           die(FAILURE);
       }
       //die('dip');
       //now update main appraisal table
       $selfEvaluation= ($act1_marks+$act2_marks+$act3_marks+$act4_marks+$act5_marks+$act6_marks);
       doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
   }

 if($proofId==14){
       $oddsem=add_slashes(trim($REQUEST_DATA['oddsem']));
       $evensem=add_slashes(trim($REQUEST_DATA['evensem']));
       $score_gained=add_slashes(trim($REQUEST_DATA['score_gained']));

       if($oddsem=='' or $evensem=='' or $score_gained==''){
          die('Required Parameters Missing');
       }

       if(!is_numeric($oddsem)){
           die('Enter numeric value');
       }
       if(!is_numeric($evensem)){
           die('Enter numeric value');
       }
       if(!is_numeric($score_gained)){
           die('Enter numeric value');
       }

       //delete prev records
       $ret=$appDataManager->deleteProofData14($employeeId,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
       //insert new records
       $ret=$appDataManager->insertProofData14($employeeId,$sessionId,$oddsem,$evensem,$score_gained);
       if($ret==false){
           die(FAILURE);
       }

       //now update main appraisal table
       $hodEvaluation= ($score_gained);
       doMainAppraisalHOD($employeeId,$appraisalId,0,$hodEvaluation,$sessionId,$superiorEmployeeId);

   }

if($proofId==1){

       $internal=add_slashes(trim($REQUEST_DATA['internal']));
       $external=add_slashes(trim($REQUEST_DATA['external']));
       $copies_checked=add_slashes(trim($REQUEST_DATA['copies_checked']));
       $superValue=add_slashes(trim($REQUEST_DATA['superValue']));
       $weekends=add_slashes(trim($REQUEST_DATA['weekends']));
       $score_gained=add_slashes(trim($REQUEST_DATA['score_gained']));

       if($internal=='' or $external=='' or $copies_checked=='' or $superValue=='' or $weekends=='' or $score_gained==''){
          die('Required Parameters Missing');
       }

       if(!is_numeric($internal)){
           die('Enter numeric value');
       }
       if(!is_numeric($external)){
           die('Enter numeric value');
       }
       if(!is_numeric($copies_checked)){
           die('Enter numeric value');
       }
       if(!is_numeric($superValue)){
           die('Enter numeric value');
       }
       if(!is_numeric($weekends)){
           die('Enter numeric value');
       }
       if(!is_numeric($score_gained)){
           die('Enter numeric value');
       }

       //delete prev records
       $ret=$appDataManager->deleteProofData1_1($employeeId,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
       //insert new records
       $ret=$appDataManager->insertProofData1_1($employeeId,$sessionId,$internal,$external,$copies_checked,$superValue,$weekends,$score_gained);
       if($ret==false){
           die(FAILURE);
       }

       //now update main appraisal table
       $hodEvaluation= ($score_gained);
       doMainAppraisalHOD($employeeId,$appraisalId,0,$hodEvaluation,$sessionId,$superiorEmployeeId);

   }


  if(SystemDatabaseManager::getInstance()->commitTransaction()) {
   echo SUCCESS;
   die;
  }
  else {
   echo FAILURE;
   die;
  }
 }
 else {
  echo FAILURE;
  die;
 }
// $History: ajaxGetValues.php $
?>