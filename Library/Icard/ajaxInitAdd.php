<?php
//It contains the time table according store bus pass details    
//
// Author :Parveen Sharma
// Created on : 12-Jun-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   global $FE;
   require_once($FE . "/Library/common.inc.php");
   require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
   require_once(BL_PATH . "/UtilityManager.inc.php");
   require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
   require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    
   $commonQueryManager = CommonQueryManager::getInstance();    
   $studentManager = StudentReportsManager::getInstance();
  
   define('MODULE','COMMON');
   define('ACCESS','add');
   UtilityManager::ifNotLoggedIn(true);
   UtilityManager::headerNoCache();
    
   $errorMessage ='';
   
   $studentId = $REQUEST_DATA['busStudentId'];
   $classId = $REQUEST_DATA['busClassId'];
   $busStop = $REQUEST_DATA['busStop'];
   $busRoute = $REQUEST_DATA['busRoute']; 
   $receiptNo = $REQUEST_DATA['receiptNo'];
   $validUpto = $REQUEST_DATA['validUpto'];
   $busNo = $REQUEST_DATA['busNo'];   
   
  
   $tableInfo .= "<div class='report'>For following student(s) Receipt No. already exists.</div><br>"; 
   $tableInfo .= "<table align='center' width='100%' border='0' class='anyid'>
                   <tr class='rowheading'> 
                     <td valign='top' width='3%'>#</td>  
                     <td valign='top' width='18%'>Name</td>
                     <td valign='top' width='19%'>Class</td>
                     <td valign='top' width='18%'>Bus Route</td>
                     <td valign='top' width='18%'>Bus Stoppage</td>
                     <td valign='top' width='12%'>Receipt No.</td>
                     <td valign='top' width='12%' align='center'>Valid Upto</td>
                  </tr>";
       
   $k=0;    
   for($i=0; $i<count($studentId); $i++) {        
      $conditions = " AND bpass.receiptNo = '".trim($receiptNo[$i])."' AND a.studentId != '".trim($studentId[$i])."'";
      $foundArray = $studentManager->getReceiptNo($conditions);
      if(trim($foundArray[0]['receiptNo'])!='') {  //DUPLICATE CHECK
          $bg = $bg =='row0' ? 'row1' : 'row0'; 
          $tableInfo .= "<tr class=".$bg."> 
                           <td valign='top'>".($k+1)."</td>
                           <td valign='top'>".$foundArray[0]['studentName']."</td>
                           <td valign='top'>".$foundArray[0]['className']."</td>
                           <td valign='top'>".$foundArray[0]['routeCode']."</td>
                           <td valign='top'>".$foundArray[0]['stopName']."</td>
                           <td valign='top'>".$foundArray[0]['receiptNo']."</td>
                           <td valign='top' align='center'>".UtilityManager::formatDate($foundArray[0]['validUpto'])."</td>
                        </tr>"; 
          $k++;
      }
   }
   
   if($k!=0) {
      $tableInfo .= "</table>"; 
      echo $tableInfo;
      die; 
   }
   
   
   if(SystemDatabaseManager::getInstance()->startTransaction()) { 
           for($i=0; $i<count($studentId); $i++) {    
             
              $dateArray=explode("-",$validUpto[$i]);
              $year="20".$dateArray[2];
              $month=$dateArray[1];
              $day=$dateArray[0];
              $validUpto[$i] = $year."-".$month."-".$day;    
              
              $strValue = " VALUES ('".$busNo[$i]."','".$studentId[$i]."','".$classId[$i]."','".$busStop[$i]."','".$busRoute[$i]."','".$sessionHandler->getSessionVariable('UserId')."','".$receiptNo[$i]."','".$validUpto[$i]."','".date('Y-m-d')."','1')";
            
              if(trim($busStop[$i])!='') { 
                  $returnStatus = $studentManager->addBusPass($strValue);
                  if($returnStatus===false) {
                     echo FAILURE;
                     die; 
                  }
                  
                  // Findout Session 
                  $classNameArray = $studentManager->getSingleField('class', 'isActive', "WHERE classId  = ".$classId[$i]);
                  $isActive = $classNameArray[0]['isActive'];
                  if($isActive==1) {
                    $condition = " WHERE studentId = '".$studentId[$i] ."' AND classId = '".$classId[$i] ."'";
                    $returnStatus = $studentManager->addStudentBusPass($busStop[$i],$busRoute[$i],$condition);
                    if($returnStatus===false) {
                      echo FAILURE;
                      die; 
                    }
                  }
              }
           }
           if(SystemDatabaseManager::getInstance()->commitTransaction()) {
               echo SUCCESS;
           }
           else {
               echo FAILURE;
           }  
   }
   

/*  if ($errorMessage == '' && (!isset($REQUEST_DATA['busStopId']) || trim($REQUEST_DATA['busStopId']) == '')) {
        $errorMessage .= BUSPASS_STOPPAGE."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['busRouteId']) || trim($REQUEST_DATA['busRouteId']) == '')) {
        $errorMessage .= BUSPASS_ROUTE."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['receiptNo']) || trim($REQUEST_DATA['receiptNo']) == '')) {
        $errorMessage .= ENTER_BUSPASS_RECEIPT."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['busPassStatus']) || trim($REQUEST_DATA['busPassStatus']) == '')) {
        $errorMessage .= BUSPASS_STATUS."\n";
    }
    
    if (trim($errorMessage) == '') {  
        $conditions = " AND (bpass.receiptNo = '".trim($REQUEST_DATA['receiptNo'])."' AND a.studentId != '".trim($REQUEST_DATA['studentId'])."') ";
        $foundArray = $studentManager->getStudentICardDetails($conditions,'studentName');
        if(trim($foundArray[0]['busPassId'])=='') {  //DUPLICATE CHECK
            $conditions = " AND a.studentId = '".trim($REQUEST_DATA['studentId'])."' AND bpass.busPassStatus =1 ";
            $foundArray = $studentManager->getStudentICardDetails($conditions,'studentName');
            if(trim($foundArray[0]['busPassId'])=='') {  //DUPLICATE CHECK
                if(SystemDatabaseManager::getInstance()->startTransaction()) {      
                  $returnStatus = $studentManager->addBusPass();
                  if($returnStatus===false) {
                     echo FAILURE;
                     die; 
                  }
                  $returnStatus = $studentManager->addStudentBusPass();
                  if($returnStatus===false) {
                     echo FAILURE;
                     die; 
                  }
                  if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                       echo SUCCESS;
                  }
                  else {
                       echo FAILURE;
                  }  
            }
            else{
                 echo FAILURE;
                 die;
             }
        }
       else {
               echo STUDENT_BUSPASS_ALREADY; 
            } 
    }
    else {
            echo RECEIPT_BUSPASS_ALREADY;
        }
    }
    else {
        echo $errorMessage;
    }  
*/    

//$History: ajaxInitAdd.php $
//
//*****************  Version 10  *****************
//User: Parveen      Date: 2/03/10    Time: 10:46a
//Updated in $/LeapCC/Library/Icard
//validation format updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 6/29/09    Time: 12:18p
//Updated in $/LeapCC/Library/Icard
//getReceiptNo function update (duplicate receipt No checks)
//
//*****************  Version 8  *****************
//User: Parveen      Date: 6/23/09    Time: 3:29p
//Updated in $/LeapCC/Library/Icard
//date formatting updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 6/23/09    Time: 10:29a
//Updated in $/LeapCC/Library/Icard
//date formatting settings
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/22/09    Time: 2:29p
//Updated in $/LeapCC/Library/Icard
//formating, validations, messages, conditions  changes 
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/18/09    Time: 10:37a
//Updated in $/LeapCC/Library/Icard
//messaged settings
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/16/09    Time: 12:41p
//Updated in $/LeapCC/Library/Icard
//busPass Status add disabled / edit enabled  
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/15/09    Time: 6:15p
//Updated in $/LeapCC/Library/Icard
//validation messages update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/15/09    Time: 12:34p
//Updated in $/LeapCC/Library/Icard
//validation, conditions & formatting updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/13/09    Time: 2:53p
//Created in $/LeapCC/Library/Icard
//initial checkin
//

?>

