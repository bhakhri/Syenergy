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
   require_once(MODEL_PATH . "/EmployeeManager.inc.php");
   require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    
   $commonQueryManager = CommonQueryManager::getInstance();    
   $employeeManager = EmployeeManager::getInstance();
  
   define('MODULE','EmployeeBusPass');
   define('ACCESS','add');
   UtilityManager::ifNotLoggedIn();
   UtilityManager::headerNoCache();
    
   $errorMessage ='';

   $employeeId = $REQUEST_DATA['busEmployeeId'];
   $busStop = $REQUEST_DATA['busStop'];
   $busRoute = $REQUEST_DATA['busRoute']; 
   $validUpto = $REQUEST_DATA['validUpto'];
   $busNo = $REQUEST_DATA['busNo'];
   
   /*
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
       for($i=0; $i<count($employeeId); $i++) {        
          $conditions = " AND bpass.receiptNo = '".trim($receiptNo[$i])."' AND a.studentId != '".trim($employeeId[$i])."'";
          $foundArray = $employeeManager->getReceiptNo($conditions);
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
   */
    
   $userId = $sessionHandler->getSessionVariable('UserId');
   $instituteId = $sessionHandler->getSessionVariable('InstituteId');
   
   if(SystemDatabaseManager::getInstance()->startTransaction()) { 
           for($i=0; $i<count($employeeId); $i++) {    
              $dateArray=explode("-",$validUpto[$i]);
              $year="20".$dateArray[2];
              $month=$dateArray[1];
              $day=$dateArray[0];
              $validUpto[$i] = $year."-".$month."-".$day;    
              
              $strValue = " VALUES ('".$busNo[$i]."','".$employeeId[$i]."','".$busStop[$i]."','".$busRoute[$i]."','".$userId."','".$validUpto[$i]."','".date('Y-m-d')."','1',$instituteId)";
              if(trim($busStop[$i])!='') { 
                  $returnStatus = $employeeManager->addBusPass($strValue);
                  if($returnStatus===false) {
                     echo FAILURE;
                     die; 
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

