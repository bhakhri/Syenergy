<?php
//-------------------------------------------------------
// Purpose: To make time table for a teacher
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Modified by : Pushpender Kumar
// Modified on : (19.09.2008 )
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/FeeHeadReportManager.inc.php");   
define('MODULE','FeeHeadReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


$feeHeadReportManager = FeeHeadReportManager::getInstance(); 
  
    global $sessionHandler;
    
    $valueArray = array(); 
    
    $reportFormat   =   $REQUEST_DATA['reportFormat'];
    $feeCycleId     =   $REQUEST_DATA['feeCycleId'];
    $feeHead        =   $REQUEST_DATA['feeHead'];
    $feeClassId     =   $REQUEST_DATA['feeClassId'];
    $rollNo         =   trim($REQUEST_DATA['rollNo']);
    $receiptNo      =   trim($REQUEST_DATA['receiptNo']);
    $fromDate       =   $REQUEST_DATA['fromDate'];
    $toDate         =   $REQUEST_DATA['toDate'];
    $consolidatedId =   $REQUEST_DATA['consolidatedId']; 
    $studentStatus  =   $REQUEST_DATA['studentStatus']; 
    
    if($studentStatus=='') {
      $studentStatus=3;  
    }
    
    if($consolidatedId=='') {
       $consolidatedId=0; 
    }
    
    $feeAllowArray = array();
    for($i=0;$i<8;$i++) {
       $feeAllowArray[$i]=0;
    }

    
    // Set Fee Head Values
    $feeHeadIds = '';   
    if($feeHead!='') {
       $feeHeadIdArray = explode(',',$feeHead); 
       for($i=0;$i<count($feeHeadIdArray);$i++) {
          if($feeHeadIdArray[$i]=='T') {
            $feeAllowArray[0]=1;  
          }
          else if($feeHeadIdArray[$i]=='H') {
            $feeAllowArray[1]=1;  
          }
          else if($feeHeadIdArray[$i]=='FF') {
            $feeAllowArray[2]=1;  
          }
          else if($feeHeadIdArray[$i]=='TF') {
            $feeAllowArray[3]=1;  
          }
          else if($feeHeadIdArray[$i]=='HF') {
            $feeAllowArray[4]=1;  
          }
          else if($feeHeadIdArray[$i]=='AF') {
            $feeAllowArray[5]=1;  
          }
          else if($feeHeadIdArray[$i]=='AT') {
            $feeAllowArray[6]=1;  
          }
          else if($feeHeadIdArray[$i]=='AH') {
            $feeAllowArray[7]=1;  
          }
          else if($feeHeadIdArray[$i]=='D') {
            $feeAllowArray[8]=1;  
          }
          else {
            if($feeHeadIds!='') {
              $feeHeadIds .=",";  
            } 
            $feeHeadIds .=$feeHeadIdArray[$i];  
          }
       }  
    }
    else {
      for($i=0;$i<8;$i++) {
        $feeAllowArray[$i]=1;
      }  
    }
    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    $orderBy = " $sortField $sortOrderBy";
    
    
    if($reportFormat=='') {
      $reportFormat=1;  
    }
    
    
    if($reportFormat==1) { 
       $tableHead = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                       <tr class='rowheading'>
                         <td width='2%'  class='searchhead_text' align='left'><b><nobr>#</nobr></b></td>
                         <td width='5%'  class='searchhead_text' align='left'><strong><nobr>Roll No.</nobr></strong></td>
                         <td width='5%'  class='searchhead_text' align='left'><strong><nobr>URoll No.</nobr></strong></td>
                         <td width='10%' class='searchhead_text' align='left'><strong><nobr>Student Name</nobr></strong></td>
                         <td width='10%' class='searchhead_text' align='left'><strong><nobr>Fahter's Name</nobr></strong></td>
                         <td width='10%' class='searchhead_text' align='left'><strong><nobr>Class</nobr></strong></td>"; 
       if($consolidatedId==0) {
          $tableHead .= "<td width='10%' class='searchhead_text' align='left'><strong><nobr>Receipt No.</nobr></strong></td>
                         <td width='10%' class='searchhead_text' align='center'><strong><nobr>Receipt Dt.</nobr></strong></td>"; 
       }                   
    }
    if($reportFormat==2) {
       $tableHead = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                       <tr class='rowheading'>
                         <td width='2%'  class='searchhead_text' align='left'><b><nobr>#</nobr></b></td>
                         <td width='50%'  class='searchhead_text' align='left'><strong><nobr>Head Name</nobr></strong></td>
                         <td width='5%'  class='searchhead_text' align='right'><strong><nobr>Amount</nobr></strong></td>";  
    }
     if($reportFormat==3) {
     		$tableHead = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                       <tr class='rowheading'>
                         <td width='2%'  class='searchhead_text' align='left'><b><nobr>#</nobr></b></td>
                         <td width='5%'  class='searchhead_text' align='left'><strong><nobr>Roll No.</nobr></strong></td>
                         <td width='5%'  class='searchhead_text' align='left'><strong><nobr>URoll No.</nobr></strong></td>
                         <td width='10%' class='searchhead_text' align='left'><strong><nobr>Student Name</nobr></strong></td>
                         <td width='10%' class='searchhead_text' align='left'><strong><nobr>Fahter's Name</nobr></strong></td>
                         <td width='10%' class='searchhead_text' align='left'><strong><nobr>Class</nobr></strong></td>
           				 <td width='10%' class='searchhead_text' align='left'><strong><nobr>Receipt No.</nobr></strong></td>
                         <td width='10%' class='searchhead_text' align='center'><strong><nobr>Receipt Dt.</nobr></strong></td>
                         <td width='5%' class='searchhead_text' align='center'><strong>Cash</strong></td>
                         <td width='5%' class='searchhead_text' align='center'><strong>Cheque/DD</strong></td>";
     	}
    
    
    // Find Fee Head List
    if($reportFormat==1 || $reportFormat==2)
    {
    $condition='';
    if($feeCycleId!='') {
      $condition .= " AND fc.feeCycleId = $feeCycleId";
    }
    
    if($feeClassId!='') {
      $condition .= " AND fc.feeClassId = $feeClassId";
    }
    
    if($feeHead!='') {   
      if($feeHeadIds!='') {
        $condition .= " AND fc.feeHeadId IN ($feeHeadIds)";
        $feeHeadArray = $feeHeadReportManager->getFeeHeadList($condition);  
      }   
    }
    else {
      $feeHeadArray = $feeHeadReportManager->getFeeHeadList($condition);
    }           
    
    
   
      $reportFeeHeadArray = array();
      $fId=0;
      for($i=0;$i<count($feeHeadArray);$i++) {
        $feeHeadType = $feeHeadArray[$i]['feeHeadType'];
        if($feeHeadType==1) {
          $feeHeadName = $feeHeadArray[$i]['headName'];
          $reportFeeHeadArray[$fId]['feeHeadName']=$feeHeadName;
          $reportFeeHeadArray[$fId]['feeIds']="fee_".$fId;
          $fId++;
        }
      }
    
      if($feeAllowArray[0]=='1') {
        $reportFeeHeadArray[$fId]['feeHeadName']="Transport";
        $reportFeeHeadArray[$fId]['feeIds']="fee_".$fId;
        $fId++;
      }
      if($feeAllowArray[1]=='1') {
        $reportFeeHeadArray[$fId]['feeHeadName']="Hostel";
        $reportFeeHeadArray[$fId]['feeIds']="fee_".$fId;
        $fId++;
      }
      if($feeAllowArray[2]=='1') {
        $reportFeeHeadArray[$fId]['feeHeadName']="Fee Fine";
        $reportFeeHeadArray[$fId]['feeIds']="fee_".$fId;
        $fId++;
      }
      if($feeAllowArray[3]=='1') {
        $reportFeeHeadArray[$fId]['feeHeadName']="Transport Fine";
        $reportFeeHeadArray[$fId]['feeIds']="fee_".$fId;
        $fId++;
      }
      if($feeAllowArray[4]=='1') {
        $reportFeeHeadArray[$fId]['feeHeadName']="Hostel Fine";
        $reportFeeHeadArray[$fId]['feeIds']="fee_".$fId;
        $fId++;
      }
      if($feeAllowArray[5]=='1') {
        $reportFeeHeadArray[$fId]['feeHeadName']="Advance Fee";
        $reportFeeHeadArray[$fId]['feeIds']="fee_".$fId;
        $fId++;
      }
      if($feeAllowArray[6]=='1') {
        $reportFeeHeadArray[$fId]['feeHeadName']="Advance Transport";
        $reportFeeHeadArray[$fId]['feeIds']="fee_".$fId;
        $fId++;  
      }
      if($feeAllowArray[7]=='1') {
        $reportFeeHeadArray[$fId]['feeHeadName']="Advance Hostel";
        $reportFeeHeadArray[$fId]['feeIds']="fee_".$fId;
        $fId++;  
      }
      if($feeAllowArray[8]=='1') {
        $reportFeeHeadArray[$fId]['feeHeadName']="Dues";
        $reportFeeHeadArray[$fId]['feeIds']="fee_".$fId;
        $fId++;  
      }
    
    }
    if($reportFormat==1) { 
          for($i=0;$i<count($feeHeadArray);$i++) {
             $feeHeadType = $feeHeadArray[$i]['feeHeadType'];
             if($feeHeadType==1) {
                $feeHeadName = $feeHeadArray[$i]['headName'];
                $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong>".$feeHeadName."</strong></td>";   
             }
          }
          
          if($feeAllowArray[0]=='1') {
            $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong>Transport</strong></td>";
          }
          if($feeAllowArray[1]=='1') {
            $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong>Hostel</strong></td>";
          }
          if($feeAllowArray[2]=='1') {
            $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong>Fee Fine</strong></td>";
          }
          if($feeAllowArray[3]=='1') {
            $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong>Transport Fine</strong></td>";
          }
          if($feeAllowArray[4]=='1') {
            $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong>Hostel Fine</strong></td>";
          }
          if($feeAllowArray[5]=='1') {
            $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong>Advance Fee</strong></td>";
          }
          if($feeAllowArray[6]=='1') {
            $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong>Advance Transport</strong></td>";
          }
          if($feeAllowArray[7]=='1') {
            $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong>Advance Hostel</strong></td>";
          }
          if($feeAllowArray[8]=='1') {
            $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong>Dues</strong></td>";
          }
          $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong>Cash</strong></td>
                         <td width='5%' class='searchhead_text' align='center'><strong>Cheque/DD</strong></td>";
         $tableHead .= "</tr>";
    }
             
   
     $condition ='';
     $condition1 ='';
     if($feeCycleId!='') {
        $condition .= " AND fr.feeCycleId = $feeCycleId";
     }
    
     if($feeClassId!='') {
         $condition .= " AND fr.feeClassId = $feeClassId";
     }
     
     if($receiptNo!='') {
        $receiptNo = add_slashes($receiptNo); 
        $condition .= " AND (fr.receiptNo LIKE '$receiptNo') "; 
     }
     if($fromDate != '' && $toDate != '') {
        $condition .= " AND (fr.receiptDate BETWEEN '$fromDate%' AND '$toDate') ";    
     }
     $condition .= " AND fr.receiptStatus NOT IN (3,4) ";  
     $condition1 = $condition;
     
     if($rollNo!='') {
        $rollNo = add_slashes($rollNo); 
        $condition .= " AND (s.rollNo LIKE '$rollNo%' OR s.regNo LIKE '$rollNo%' OR s.universityRollNo LIKE '$rollNo%') "; 
     }
     
     if($reportFormat==3) {  
         // Fetch Student List Count
         $studentListCount = $feeHeadReportManager->getStudentList($condition,'','',$consolidatedId,$studentStatus);
         $totalStudent =  count($studentListCount);
        
         // Fetch Student List
         $studentListArray = $feeHeadReportManager->getStudentList($condition,$orderBy,$limit,$consolidatedId,$studentStatus);
         
         // Fetch Fee Head List
         $studentFeeArray = $feeHeadReportManager->getStudentWiseFeeHeadCollection($condition1,$consolidatedId,$studentStatus);
     
   
         if(count($studentListArray)==0) {   
            $totalStudent=0;
            $tableHead .= "<tr><td colspan='50'><center>No Record Found</center></td></tr></table>";
            echo $tableHead.'!~~!'.$totalStudent;  
            die;  
         }
      
         $dtCheck='';
         $dateWiseFeeTotal = array();
         for($i=0; $i<count($studentListArray); $i++) {
            $tableHead .= displayFeeHead($i); 
         }
         
         $cc=count($valueArray);
         if($cc>0) {
             $valueArray[$cc]['cashAmount'] =0;
             $valueArray[$cc]['ddAmount'] =0;
             for($j=0;$j<count($reportFeeHeadArray);$j++) {
                $feeId = $reportFeeHeadArray[$j]['feeIds'];
                $valueArray[$cc][$feeId] = 0;
             }
             
             for($i=0;$i<$cc;$i++) {
                if($valueArray[$i]['cashAmount']!='' && $valueArray[$i]['cashAmount']!=NOT_APPLICABLE_STRING) { 
                   $valueArray[$cc]['cashAmount'] = doubleval($valueArray[$cc]['cashAmount'])+doubleval($valueArray[$i]['cashAmount']) ;
                }
                if($valueArray[$i]['ddAmount']!='' && $valueArray[$i]['ddAmount']!=NOT_APPLICABLE_STRING) { 
                  $valueArray[$cc]['ddAmount'] = doubleval($valueArray[$cc]['ddAmount'])+doubleval($valueArray[$i]['ddAmount']) ;
                }
                for($j=0;$j<count($reportFeeHeadArray);$j++) {
                  $feeId = $reportFeeHeadArray[$j]['feeIds'];
                  if($valueArray[$i][$feeId]!='' && $valueArray[$i][$feeId]!=NOT_APPLICABLE_STRING) {
                    $valueArray[$cc][$feeId] = doubleval($valueArray[$cc][$feeId])+doubleval($valueArray[$i][$feeId]) ;  
                  }
                }
             }
             if($consolidatedId==1) { 
                $tableHead .= "<tr>
                                   <td colspan='6' align='right'><b>Grand Total&nbsp;&nbsp;</td>"; 
             }
             else {
                $tableHead .= "<tr>
                                   <td colspan='8' align='right'><b>Grand Total&nbsp;&nbsp;</b></td>";   
             }
             $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong>".$valueArray[$cc]['cashAmount']."</strong></td>
                            <td width='5%' class='searchhead_text' align='center'><strong>".$valueArray[$cc]['ddAmount']."</strong></td>";
         }
     }
     ///////////////////////////////////////madhav\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
     if($reportFormat==1) {  
         // Fetch Student List Count
         $studentListCount = $feeHeadReportManager->getStudentList($condition,'','',$consolidatedId,$studentStatus);
         $totalStudent =  count($studentListCount);
        
         // Fetch Student List
         $studentListArray = $feeHeadReportManager->getStudentList($condition,$orderBy,$limit,$consolidatedId,$studentStatus);
         
         // Fetch Fee Head List
         $studentFeeArray = $feeHeadReportManager->getStudentWiseFeeHeadCollection($condition1,$consolidatedId,$studentStatus);
     
   
         if(count($studentListArray)==0) {   
            $totalStudent=0;
            $tableHead .= "<tr><td colspan='50'><center>No Record Found</center></td></tr></table>";
            echo $tableHead.'!~~!'.$totalStudent;  
            die;  
         }
      
         $dtCheck='';
         $dateWiseFeeTotal = array();
         for($i=0; $i<count($studentListArray); $i++) {
            $tableHead .= displayFeeHead($i); 
         }
         
         $cc=count($valueArray);
         if($cc>0) {
             $valueArray[$cc]['cashAmount'] =0;
             $valueArray[$cc]['ddAmount'] =0;
             for($j=0;$j<count($reportFeeHeadArray);$j++) {
                $feeId = $reportFeeHeadArray[$j]['feeIds'];
                $valueArray[$cc][$feeId] = 0;
             }
             
             for($i=0;$i<$cc;$i++) {
                if($valueArray[$i]['cashAmount']!='' && $valueArray[$i]['cashAmount']!=NOT_APPLICABLE_STRING) { 
                   $valueArray[$cc]['cashAmount'] = doubleval($valueArray[$cc]['cashAmount'])+doubleval($valueArray[$i]['cashAmount']) ;
                }
                if($valueArray[$i]['ddAmount']!='' && $valueArray[$i]['ddAmount']!=NOT_APPLICABLE_STRING) { 
                  $valueArray[$cc]['ddAmount'] = doubleval($valueArray[$cc]['ddAmount'])+doubleval($valueArray[$i]['ddAmount']) ;
                }
                for($j=0;$j<count($reportFeeHeadArray);$j++) {
                  $feeId = $reportFeeHeadArray[$j]['feeIds'];
                  if($valueArray[$i][$feeId]!='' && $valueArray[$i][$feeId]!=NOT_APPLICABLE_STRING) {
                    $valueArray[$cc][$feeId] = doubleval($valueArray[$cc][$feeId])+doubleval($valueArray[$i][$feeId]) ;  
                  }
                }
             }
             if($consolidatedId==1) { 
                $tableHead .= "<tr>
                                   <td colspan='6' align='right'><b>Grand Total&nbsp;&nbsp;</td>"; 
             }
             else {
                $tableHead .= "<tr>
                                   <td colspan='8' align='right'><b>Grand Total&nbsp;&nbsp;</b></td>";   
             }
             for($j=0;$j<count($reportFeeHeadArray);$j++) {
               $feeId = $reportFeeHeadArray[$j]['feeIds']; 
               $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong>".$valueArray[$cc][$feeId]."</strong></td>"; 
             }
             $tableHead .= "<td width='5%' class='searchhead_text' align='center'><strong>".$valueArray[$cc]['cashAmount']."</strong></td>
                            <td width='5%' class='searchhead_text' align='center'><strong>".$valueArray[$cc]['ddAmount']."</strong></td>";
         }
     }
     if($reportFormat==2){
          $srNo=1;
          
          $studentFine = 0;
          $feeAdvance = 0; 
          $feePaid =0;
          $applTransport  = 0;   
          $applTransportFine  = 0;   
          $transportAdvance = 0;   
          $applHostel  = 0;   
          $applHostelFine  = 0;   
          $hostelAdvance  = 0;   
             
             
          // Fetch Student List
          $studentListArray = $feeHeadReportManager->getFeeHeadWiseAdavanceList($condition,'','',$studentStatus);
          
          // Fetch Fee Head List
          for($i=0; $i<count($studentListArray);$i++) {
              $feePaid += $studentListArray[$i]['feePaid'];  
              $applTransport     += $studentListArray[$i]['applTransport'];
              $applTransportFine += $studentListArray[$i]['applTransportFine'];  
              $transportAdvance  += $studentListArray[$i]['transportPaid'] - ($applTransport+$applTransportFine);
                
              $applHostel += $studentListArray[$i]['applHostel'];
              $applHostelFine += $studentListArray[$i]['applHostelFine'];
              $hostelAdvance  += $studentListArray[$i]['hostelPaid'] - ($applHostel+$applHostelFine);
              
              $studentFine += $studentFeeArray[$k]['feeHeadAmount'];
              $feeAdvance += $studentFeeArray[$k]['feeHeadAmount'];   
          }   
          
          
          for($i=0;$i<count($feeHeadArray);$i++) {   
            $feeHeadType = $feeHeadArray[$i]['feeHeadType'];
            if($feeHeadType==1) {
               $bg = $bg =='trow0' ? 'trow1' : 'trow0';   
               
               $headCondition = $condition." AND fc.feeHeadId = ".$feeHeadArray[$i]['feeHeadId'];   
               $headCondition .= " AND fr.receiptStatus NOT IN (3,4) "; 
               $studentFeeArray = $feeHeadReportManager->getFeeHeadWiseCollection($headCondition,$studentStatus); 
                
               $feeHeadName = $feeHeadArray[$i]['headName'];
               $tableHead .= "<tr class='$bg'>
                                 <td class='padding_top' align='left'>".$srNo."</td>
                                 <td class='padding_top' align='left'>".$feeHeadName."</td>";
                                    
              
               if(count($studentFeeArray)>0) {
                 $tableHead .= "<td class='padding_top' width='400px' align='right'>".$studentFeeArray[0]['feeHeadAmount']."</td>";
               }
               else {
                 $tableHead .= "<td class='padding_top' align='right'>0</td>";  
               }
               $tableHead .= "</tr>";
               $srNo++;
            }
          }
          if($feeAllowArray[0]=='1') {
             $feeIds = "fee_".$fId;    
             $valueArray[$i][$feeIds]=$applTransport;
             $fId++; 
             $tableHead .=  headWiseDetails($srNo,'Transport',$applTransport);
             $srNo++;
          }
          if($feeAllowArray[1]=='1') {
            $tableHead .=  headWiseDetails($srNo,'Hostel',$applHostel);    
            $srNo++;
          }
          if($feeAllowArray[2]=='1') {
            $tableHead .=  headWiseDetails($srNo,'Fee Fine',$studentFine);                  
            $srNo++;
          }
          if($feeAllowArray[3]=='1') {
            $tableHead .=  headWiseDetails($srNo,'Transport Fine',$applTransportFine);                                
            $srNo++;
          }
          if($feeAllowArray[4]=='1') {
            $tableHead .=  headWiseDetails($srNo,'Hostel Fine',$applHostelFine);                                
            $srNo++;
          }
          if($feeAllowArray[5]=='1') {
            $tableHead .=  headWiseDetails($srNo,'Advance Fee',$feeAdvance);                                
            $srNo++;
          }
          if($feeAllowArray[6]=='1') {
            $tableHead .=  headWiseDetails($srNo,'Advance Transport',$transportAdvance);                                
            $srNo++;
          }
          if($feeAllowArray[7]=='1') {
            $tableHead .=  headWiseDetails($srNo,'Advance Hostel',$hostelAdvance);                                
          }
          if($feeAllowArray[8]=='1') {
            $tableHead .=  headWiseDetails($srNo,'Dues','');                                
          }
          if($srNo==1) {
            $tableHead .= "<tr><td colspan='5' align='center'>No Data Found</tr>";  
          }
          $tableHead .= "</table>";
     }
     
echo $tableHead.'!~~!'.$totalStudent;     
die;
     
     
function headWiseDetails($srNo='',$head='',$amt='') {
   global $bg; 
   if($amt=='') {
     $amt=0;  
   }
   $bg = $bg =='trow0' ? 'trow1' : 'trow0';
   $result = "<tr class='$bg'>
                  <td class='padding_top' align='left'>".$srNo."</td>
                  <td class='padding_top' align='left'>".$head."</td>
                  <td class='padding_top' align='right'>".$amt."</td>
             </tr>";
                       
   return $result;                    
}
     
     
function displayFeeHead($i) {
        	
        global $reportFormat;
        global $studentListArray; 
        global $studentFeeArray;
        global $feeHeadArray;
        global $feeAllowArray;
        global $consolidatedId;
        global $feeHeadReportManager;
        global $valueArray; 
        global $studentStatus;
        global $dateWiseFeeTotal;
        global $dtCheck;
        global $reportFeeHeadArray;
        
        
        $studentId = $studentListArray[$i]['studentId'];
        $feeClassId = $studentListArray[$i]['feeClassId'];  
        
        $feePaid = $studentListArray[$i]['feePaid'];  
        
        $applTransport     = $studentListArray[$i]['applTransport'];
        $applTransportFine = $studentListArray[$i]['applTransportFine'];  
        $transportAdvance  = $studentListArray[$i]['transportPaid'] - ($applTransport+$applTransportFine);
        
        $applHostel = $studentListArray[$i]['applHostel'];
        $applHostelFine = $studentListArray[$i]['applHostelFine'];
        $hostelAdvance  = $studentListArray[$i]['hostelPaid'] - ($applHostel+$applHostelFine);
        
        $ttCash = "";
        $ttDD = "";
        $result ="";
        
        $valueArray[$i]['srNo']=($i+1);
        $valueArray[$i]['rollNo']=$studentListArray[$i]['rollNo'];
        $valueArray[$i]['universityRollNo']=$studentListArray[$i]['universityRollNo'];
        $valueArray[$i]['studentName']=$studentListArray[$i]['studentName'];
        $valueArray[$i]['className']=$studentListArray[$i]['className'];
        
      
        if($consolidatedId==0) {  
          if($dtCheck=='') {
             $dtCheck=$studentListArray[$i]['receiptDate'];
          }
          else if($dtCheck!=$studentListArray[$i]['receiptDate']) {
              $dateTotalId = str_replace("-","",$dtCheck); 
              if($consolidatedId==1) { 
                    $result .= "<tr>
                                   <td colspan='6'></td>"; 
              }
              else {
                    $result .= "<tr>
                                   <td colspan='8' align='right'><B>Total&nbsp;&nbsp;</b></td>";   
              }
              for($j=0;$j<count($reportFeeHeadArray);$j++) {
                   $feeId = $reportFeeHeadArray[$j]['feeIds'];  
                   $result .= "<td width='5%' class='searchhead_text' align='center'><strong>".$dateWiseFeeTotal[$dateTotalId][$feeId]."</strong></td>"; 
              }
              $result .= "<td width='5%' class='searchhead_text' align='center'><strong>".$dateWiseFeeTotal[$dateTotalId]['cashAmount']."</strong></td>
                          <td width='5%' class='searchhead_text' align='center'><strong>".$dateWiseFeeTotal[$dateTotalId]['ddAmount']."</strong></td>
                         </tr>";
          }
        }
        
        $result .= "<tr>
                      <td class='padding_top' align='center'>".($i+1)."</td>
                      <td class='padding_top' align='left'>".$studentListArray[$i]['rollNo']."</td>
                      <td class='padding_top' align='left'>".$studentListArray[$i]['universityRollNo']."</td>
                      <td class='padding_top' align='left'>".$studentListArray[$i]['studentName']."</td>
                      <td class='padding_top' align='left'>".$studentListArray[$i]['fatherName']."</td>   
                      <td class='padding_top' align='left'>".$studentListArray[$i]['className']."</td>";
                      //  receiptNo, receiptDate
                                            
        if($consolidatedId==0) {
           $ttReceiptNo = $studentListArray[$i]['receiptNo'];
           $ttReceiptDate = $studentListArray[$i]['receiptDate']; 
           $feeReceiptId  = $studentListArray[$i]['feeReceiptId'];
           
           $paymentCondition = " AND fr.feeReceiptId = '$feeReceiptId' "; 
           $feePaymentArray = $feeHeadReportManager->getFeeCashDetail($paymentCondition,$studentStatus);  
           $ttCash = $feePaymentArray[0]['totalAmountPaid'];                
           
           $feePaymentArray = $feeHeadReportManager->getFeePaymentDetail($paymentCondition,$studentStatus);  
           $ttDD = $feePaymentArray[0]['totalAmountPaid'];
           
           $dtCheck=$studentListArray[$i]['receiptDate'];
           $dateTotalId = str_replace("-","",$dtCheck); 
           
           
           $valueArray[$i]['receiptNo']=$studentListArray[$i]['receiptNo'];
           $valueArray[$i]['receiptDate']=UtilityManager::formatDate($studentListArray[$i]['receiptDate']);
           
           
           $dateWiseFeeTotal[$dateTotalId]['cashAmount'] += $ttCash;
           $dateWiseFeeTotal[$dateTotalId]['ddAmount'] +=  $ttDD;
           
           
           $result .= "<td class='padding_top' align='left'>".$studentListArray[$i]['receiptNo']."</td>
                       <td class='padding_top' align='center'>".UtilityManager::formatDate($studentListArray[$i]['receiptDate'])."</td>";
        }            
        else {
           $paymentCondition = " AND fr.studentId = '$studentId' AND fr.feeClassId = '$feeClassId' "; 
           $feePaymentArray = $feeHeadReportManager->getFeeCashDetail($paymentCondition,$studentStatus);  
           $ttCash = $feePaymentArray[0]['totalAmountPaid'];                
           
           $feePaymentArray = $feeHeadReportManager->getFeePaymentDetail($paymentCondition,$studentStatus);  
           $ttDD = $feePaymentArray[0]['totalAmountPaid'];
        }
        $valueArray[$i]['cashAmount']= $ttCash;
        $valueArray[$i]['ddAmount']=  $ttDD;
        
         
       
        $tt=-1;
        for($k=0; $k<count($studentFeeArray); $k++) {
           $sStudentId   = $studentFeeArray[$k]['studentId'];
           $sFeeClassId  = $studentFeeArray[$k]['feeClassId']; 
           if($sStudentId==$studentId && $sFeeClassId==$feeClassId) {
              $tt=$k;
              break; 
           }  
        }
        
        $fId=0; 
        for($j=0; $j<count($feeHeadArray); $j++) {
            $find=='';
            $feeHeadId = $feeHeadArray[$j]['feeHeadId'];
            $feeIds = "fee_".$fId; 
            if($tt!=-1) {
               for($k=$tt; $k<count($studentFeeArray); $k++) { 
                  $find='';
                  $sFeeHeadId   = $studentFeeArray[$k]['feeHeadId'];
                  $sStudentId   = $studentFeeArray[$k]['studentId'];
                  $sFeeHeadType = $studentFeeArray[$k]['feeHeadType'];
                  $sFeeClassId  = $studentFeeArray[$k]['feeClassId'];
                  if($sStudentId==$studentId) { 
                     if($sFeeHeadId==$feeHeadId && $sStudentId==$studentId && $sFeeClassId==$feeClassId && $sFeeHeadType==1 && $consolidatedId==1) {
                       $result .= "<td class='padding_top' align='center'>".$studentFeeArray[$k]['feeHeadAmount']."</td>"; 
                       $valueArray[$i][$feeIds]=$studentFeeArray[$k]['feeHeadAmount']; 
                       if($consolidatedId==0) {      
                         $dateWiseFeeTotal[$dateTotalId][$feeIds]+=$studentFeeArray[$k]['feeHeadAmount']; 
                       }
                       $find=1;
                       break;
                     }
                     else if($ttReceiptNo == $studentFeeArray[$k]['receiptNo'] && $ttReceiptDate = $studentFeeArray[$k]['receiptDate'] && $sFeeHeadId==$feeHeadId && $sStudentId==$studentId && $sFeeClassId==$feeClassId && $sFeeHeadType==1 && $consolidatedId==0) {
                       $result .= "<td class='padding_top' align='center'>".$studentFeeArray[$k]['feeHeadAmount']."</td>"; 
                       $valueArray[$i][$feeIds]=$studentFeeArray[$k]['feeHeadAmount']; 
                       if($consolidatedId==0) {      
                          $dateWiseFeeTotal[$dateTotalId][$feeIds]+=$studentFeeArray[$k]['feeHeadAmount']; 
                       }
                       $find=1;
                       break;
                     }
                  }
                  else {
                     break; 
                  }
               }
            }
            if($find=='') {
              $result .= "<td class='padding_top' align='center'>".NOT_APPLICABLE_STRING."</td>"; 
               $valueArray[$i][$feeIds]=NOT_APPLICABLE_STRING; 
            }
            $fId++;  
        } 
        
        $studentFine = 0;
        $feeAdvance = 0;    
        if($tt!=-1) { 
            for($k=$tt; $k<count($studentFeeArray); $k++) {   
               $sStudentId   = $studentFeeArray[$k]['studentId']; 
               $sFeeClassId  = $studentFeeArray[$k]['feeClassId'];  
               $sFeeHeadType = $studentFeeArray[$k]['feeHeadType'];    
               if($consolidatedId==1) { 
                  if($sStudentId==$studentId && $sFeeClassId==$feeClassId && $sFeeHeadType==2) {
                     $studentFine += $studentFeeArray[$k]['feeHeadAmount']; 
                  }
                  if($sStudentId==$studentId && $sFeeClassId==$feeClassId && $sFeeHeadType==3) {
                     $feeAdvance += $studentFeeArray[$k]['feeHeadAmount']; 
                  }
               }
               else if($consolidatedId==0){
                  if($sStudentId==$studentId && $sFeeClassId==$feeClassId && $sFeeHeadType==2  && $ttReceiptNo == $studentFeeArray[$k]['receiptNo'] && $ttReceiptDate = $studentFeeArray[$k]['receiptDate']) {
                     $studentFine += $studentFeeArray[$k]['feeHeadAmount']; 
                  }
                  if($sStudentId==$studentId && $sFeeClassId==$feeClassId && $sFeeHeadType==3 && $ttReceiptNo == $studentFeeArray[$k]['receiptNo'] && $ttReceiptDate = $studentFeeArray[$k]['receiptDate']) {
                     $feeAdvance += $studentFeeArray[$k]['feeHeadAmount']; 
                  } 
               }
            }
        }

        if($reportFormat!=3) {
	        if($feeAllowArray[0]=='1') {
	          $feeIds = "fee_".$fId;  
	          $valueArray[$i][$feeIds]=$applTransport;
	          if($consolidatedId==0) {      
	             $dateWiseFeeTotal[$dateTotalId][$feeIds]+=$applTransport; 
	          }
	          $fId++; 
	          $result .= "<td class='padding_top' align='center'>".$applTransport."</td>"; 
	        }
	        if($feeAllowArray[1]=='1') {
	          $result .= "<td class='padding_top' align='center'>".$applHostel."</td>";
	          $feeIds = "fee_".$fId;  
	          if($consolidatedId==0) {      
	             $dateWiseFeeTotal[$dateTotalId][$feeIds]+=$applHostel; 
	          }
	          $valueArray[$i][$feeIds]=$applHostel;
	          $fId++; 
	        }
	        if($feeAllowArray[2]=='1') {
	          $result .= "<td class='padding_top' align='center'>".$studentFine."</td>";
	          $feeIds = "fee_".$fId;  
	          if($consolidatedId==0) {      
	             $dateWiseFeeTotal[$dateTotalId][$feeIds]+=$studentFine; 
	          }
	          $valueArray[$i][$feeIds]=$studentFine;
	          $fId++;
	        }
	        if($feeAllowArray[3]=='1') {
	          $result .= "<td class='padding_top' align='center'>".$applTransportFine."</td>";
	          $feeIds = "fee_".$fId;  
	          if($consolidatedId==0) {      
	             $dateWiseFeeTotal[$dateTotalId][$feeIds]+=$applTransportFine; 
	          }
	          $valueArray[$i][$feeIds]=$applTransportFine;
	          $fId++;
	        }
	        if($feeAllowArray[4]=='1') {
	          $result .= "<td class='padding_top' align='center'>".$applHostelFine."</td>";
	          $feeIds = "fee_".$fId;  
	          if($consolidatedId==0) {      
	             $dateWiseFeeTotal[$dateTotalId][$feeIds]+=$applHostelFine; 
	          }
	          $valueArray[$i][$feeIds]=$applHostelFine;
	          $fId++;
	        }
	        if($feeAllowArray[5]=='1') {
	          $result .= "<td class='padding_top' align='center'>".$feeAdvance."</td>";
	          $feeIds = "fee_".$fId;  
	          if($consolidatedId==0) {      
	             $dateWiseFeeTotal[$dateTotalId][$feeIds]+=$feeAdvance; 
	          }
	          $valueArray[$i][$feeIds]=$feeAdvance;
	          $fId++;
	        }
	        if($feeAllowArray[6]=='1') {
	          $result .= "<td class='padding_top' align='center'>".$transportAdvance."</td>";
	          $feeIds = "fee_".$fId;  
	          if($consolidatedId==0) {      
	             $dateWiseFeeTotal[$dateTotalId][$feeIds]+=$transportAdvance; 
	          }
	          $valueArray[$i][$feeIds]=$transportAdvance;
	          $fId++;
	        }
	        if($feeAllowArray[7]=='1') {
	          $result .= "<td class='padding_top' align='center'>".$hostelAdvance."</td>";
	          $feeIds = "fee_".$fId;  
	          if($consolidatedId==0) {      
	             $dateWiseFeeTotal[$dateTotalId][$feeIds]+=$hostelAdvance; 
	          }
	          $valueArray[$i][$feeIds]=$hostelAdvance;
	          $fId++;
	        }      
	        if($feeAllowArray[8]=='1') {
	          $result .= "<td class='padding_top' align='center'></td>";
	          $feeIds = "fee_".$fId;  
	          if($consolidatedId==0) {      
	             $dateWiseFeeTotal[$dateTotalId][$feeIds]+=0; 
	          }
	          $valueArray[$i][$feeIds]=0;
	          $fId++;
	        }
        }
        $result .= "<td class='padding_top' align='center'>".$ttCash."</td>
                    <td class='padding_top' align='center'>".$ttDD."</td>";  
        
        $result .= "</tr>";  
        
        if($consolidatedId==0) {  
          if($i==(count($studentListArray)-1)) {
              $dateTotalId = str_replace("-","",$dtCheck); 
              if($consolidatedId==1) { 
                    $result .= "<tr>
                                   <td colspan='6'></td>"; 
              }
              else {
                    $result .= "<tr>
                                   <td colspan='8' align='right'><B>Total&nbsp;&nbsp;</b></td>";   
              }
              for($j=0;$j<count($reportFeeHeadArray);$j++) {
                   $feeId = $reportFeeHeadArray[$j]['feeIds'];  
                   $result .= "<td width='5%' class='searchhead_text' align='center'><strong>".$dateWiseFeeTotal[$dateTotalId][$feeId]."</strong></td>"; 
              }
              $result .= "<td width='5%' class='searchhead_text' align='center'><strong>".$dateWiseFeeTotal[$dateTotalId]['cashAmount']."</strong></td>
                          <td width='5%' class='searchhead_text' align='center'><strong>".$dateWiseFeeTotal[$dateTotalId]['ddAmount']."</strong></td>
                         </tr>";
          }
        }
        
        return $result;
}     

 ?>