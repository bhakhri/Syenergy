<?php
//--------------------------------------------------------
//This file returns the array of of Test Time Period
// Author :Parveen Sharma
// Created on : 04-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    
    set_time_limit(0);  
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    
    require_once(MODEL_PATH . "/ConsolidatedManager.inc.php");
    $consolidatedReportManager = ConsolidatedManager::getInstance();
    
  
    $str = $REQUEST_DATA['str'];
    $viewType = $REQUEST_DATA['viewType'];
  
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
  
    //echo $page." ".$records." ".$limit;
    
    if($viewType==1) {
        $filedName = $consolidatedReportManager->$str('','','1');
        $cnt = count($filedName);

        echo "<table width='100%' border='0' cellspacing='1px' cellpadding='0' class=''>
                        <tr class='rowheading'>  
                            <td width='2%' class='searchhead_text'><b>#</b></td>";
       
        for($i=0; $i<$cnt; $i++) {
           echo "<td class='searchhead_text' align='left'><strong>".$filedName[$i]."</strong></td>";
        }        
        echo "</tr>";
     
        $reportResult = $consolidatedReportManager->$str('');
        $cnt1 = count($reportResult);
        if($cnt1 > 0 ) {
            for($i=0; $i<$cnt1; $i++) {
              $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
              echo "<tr class='$bg'>
                    <td class='padding_top' align='left'>".($i+1)."</td>"; 
                for($j=0; $j<$cnt; $j++) { 
                  $filedName1 = $filedName[$j];  
                  echo "<td class='padding_top' align='left'>".$reportResult[$i][$filedName1]."</td>";    
                }  
              echo "</tr>";
            }
        }
        else {
          echo "<tr>
                    <td colspan='".$cnt."'><div align='center'>".NOT_APPLICABLE_STRING."</div></td>
                </tr>";
        }
        echo "</table>";
    }  
    
    $reportHead = "";
    if($viewType==2) { 
        $filedName = $consolidatedReportManager->$str('','','1');
        $cnt = count($filedName);

        echo "<table width='100%' border='0' cellspacing='1px' cellpadding='0' class=''>
                        <tr class='rowheading'>  
                            <td width='2%' class='searchhead_text'><b>#</b></td>";
        
        $reportHead ="";
        for($i=0; $i<2; $i++) {
          if($i==0) {  
            $reportHead .= $filedName[$i];         
          }
          else {
            $reportHead .= " - ".$filedName[$i];           
          }
        } 
        $reportHead .="&nbsp;:&nbsp;";                                                                               
        
        for($i=2; $i<$cnt; $i++) {
           echo "<td nowrap class='searchhead_text' align='left'><strong>".$filedName[$i]."</strong></td>";
        }        
        echo "</tr>";
     
        $reportResult = $consolidatedReportManager->$str('');
        $cnt1 = count($reportResult);
        if($cnt1 > 0 ) {
            
            $value1 = $reportResult[0][$filedName[0]];
            $value2 = $reportResult[0][$filedName[1]];
            $temp="1";
            $bg = $bg =='trow0' ? 'trow1' : 'trow0';             
            for($i=0; $i<$cnt1; $i++) {
              // echo  $reportResult[$i][$filedName[0]]." == ".$value1." == ".$reportResult[$i][$filedName[1]]." == ".$value2;
               if($reportResult[$i][$filedName[0]]==$value1 && $reportResult[$i][$filedName[1]]==$value2 && $temp=="1") {
                  echo "<tr class='trow1'>
                           <td nowrap colspan='".$cnt."' class='padding_top' align='left'><b>".$reportHead."</b>".$value1."-".$value2."</td>
                        </tr>"; 
                  $temp="0";      
               }
               $bg = $bg =='trow0' ? 'trow1' : 'trow0';             
               echo "<tr class='$bg'>
                        <td class='padding_top' align='left'>".($i+1)."</td>"; 
                  for($j=2; $j<$cnt; $j++) { 
                     $filedName1 = $filedName[$j];  
                     echo "<td nowrap class='padding_top' align='left'>".$reportResult[$i][$filedName1]."</td>";    
                  }  
               echo "</tr>";
               if(($i+1) <= $cnt1) {
                 // echo  $reportResult[($i+1)][$filedName[0]]." == ".$value1." == ".$reportResult[$i][$filedName[1]]." == ".$value2;  
                 if($reportResult[($i+1)][$filedName[0]]==$value1 && $reportResult[($i+1)][$filedName[1]]!=$value2 && $temp=="0") {   
                    $value1 = $reportResult[($i+1)][$filedName[0]];
                    $value2 = $reportResult[($i+1)][$filedName[1]];
                    $temp = "1";
                 }
                 else
                 if($reportResult[$i+1][$filedName[0]]!=$value1 && $reportResult[$i+1][$filedName[1]]==$value2 && $temp=="0") {   
                    $value1 = $reportResult[($i+1)][$filedName[0]];
                    $value2 = $reportResult[($i+1)][$filedName[1]];
                    $temp = "1";
                 }
                 else
                 if($reportResult[($i+1)][$filedName[0]]!=$value1 && $reportResult[($i+1)][$filedName[1]]!=$value2 && $temp=="0") {   
                    $value1 = $reportResult[($i+1)][$filedName[0]];
                    $value2 = $reportResult[($i+1)][$filedName[1]];
                    $temp = "1";
                 }
               }
            }
        }
        else {
          echo "<tr>
                    <td class='padding_top' colspan='".$cnt."'><div align='center'>".NOT_APPLICABLE_STRING."</div></td>
                </tr>";
        }
        echo "</table>";
     }
            
     if($viewType==3) { 
        $filedName = $consolidatedReportManager->$str('','','1');
        $cnt = count($filedName);

        $reportHead ="";
        for($i=0; $i<1; $i++) {
          if($i==0) {  
            $reportHead .= $filedName[$i];         
          }
          else {
            $reportHead .= " - ".$filedName[$i];           
          }
        } 
         $reportHead .="&nbsp;:&nbsp;";    
        
        echo "<table width='100%' border='0' cellspacing='1px' cellpadding='0' class=''>
                        <tr class='rowheading'>  
                            <td width='1%' class='searchhead_text'><b>#</b></td>
                            <td nowrap class='searchhead_text'><b>".$filedName[0]."</b></td>";
        for($i=1; $i<$cnt; $i++) {
           echo "<td nowrap class='searchhead_text' align='left'><strong>".$filedName[$i]."</strong></td>";
        }        
        echo "</tr>";
     
        $reportResult = $consolidatedReportManager->$str('');
        $cnt1 = count($reportResult);
        if($cnt1 > 0 ) {
            $value1 = $reportResult[0][$filedName[0]];
            $temp="1";
            $bg = $bg =='trow0' ? 'trow1' : 'trow0'; 
            $jj=0; 
            for($i=0; $i<$cnt1; $i++) {
              // echo  $reportResult[$i][$filedName[0]]." == ".$value1." == ".$reportResult[$i][$filedName[1]]." == ".$value2;
               if($reportResult[$i][$filedName[0]]==$value1 && $temp=="1") {
                  //echo "<tr class='trow1'>
                  //         <td colspan='".$cnt."' class='padding_top' align='left'><b>".$reportHead."</b>".$value1."</td>  
                  //      </tr>"; 
                  $instituteName = "<b>$value1</b>";
                  $temp="0";      
                  $jj=0; 
               }
               $bg = $bg =='trow0' ? 'trow1' : 'trow0';             
               echo "<tr class='$bg'>
                        <td class='padding_top' align='left'>".($i+1)."</td>
                        <td nowrap class='padding_top' align='left'>".$instituteName."</td>"; 
                        $jj++;
                        $instituteName='';
                  for($j=1; $j<$cnt; $j++) { 
                     $filedName1 = $filedName[$j];  
                     echo "<td nowrap class='padding_top' align='left'>".$reportResult[$i][$filedName1]."</td>";    
                  }  
               echo "</tr>";
               if(($i+1) <= $cnt1) {
                 // echo  $reportResult[($i+1)][$filedName[0]]." == ".$value1." == ".$reportResult[$i][$filedName[1]]." == ".$value2;  
                 if($reportResult[($i+1)][$filedName[0]]!=$value1 && $temp=="0") {   
                    $value1 = $reportResult[($i+1)][$filedName[0]];
                    $temp = "1";
                 }
                 else
                 if($reportResult[($i+1)][$filedName[0]]!=$value1 && $temp=="0") {   
                    $value1 = $reportResult[($i+1)][$filedName[0]];
                    $temp = "1";
                 }
               }
            }
        }
        else {
          echo "<tr>
                    <td class='padding_top' colspan='".$cnt."'><div align='center'>".NOT_APPLICABLE_STRING."</div></td>
                </tr>";
        }
        echo "</table>";
     }
                 
// $History: ajaxInitList.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/Consolidated
//added access defines for management login
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/04/09   Time: 3:11p
//Updated in $/LeapCC/Library/Consolidated
//getDuplicateAttendance link added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 10/26/09   Time: 3:29p
//Updated in $/LeapCC/Library/Consolidated
//query formatting updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 10/15/09   Time: 2:18p
//Created in $/LeapCC/Library/Consolidated
//initial checkin
//
//*****************  Version 6  *****************
//User: Parveen      Date: 10/14/09   Time: 12:14p
//Updated in $/LeapCC/Library/StudentReports
//CSV & Query Format updated 
//
//*****************  Version 5  *****************
//User: Parveen      Date: 10/13/09   Time: 6:04p
//Updated in $/LeapCC/Library/StudentReports
//table colspan setting updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 10/13/09   Time: 2:44p
//Updated in $/LeapCC/Library/StudentReports
//consolidated & details report print
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/03/09   Time: 4:09p
//Updated in $/LeapCC/Library/StudentReports
//It checks the value of hasAttendance, hasMarks field for every subject
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/08/08   Time: 11:45a
//Created in $/LeapCC/Library/StudentReports
//student percentagewise report files added
//

?>
