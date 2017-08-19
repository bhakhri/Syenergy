<?php 
    //This file is used as printing version for Student Attendance Short Report Print
    //
    // Author :Parveen Sharma
    // Created on : 26-12-2008
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/FeeHeadReportManager.inc.php");   
   
    define('MODULE','FeeHeadReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    global $sessionHandler;          
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
   

   //used to parse csv data
   function parseCSVComments($comments,$param='') {
     $comments = str_replace('"', '""', $comments);
     $comments = str_ireplace('<br/>', "\n", $comments);
      if(eregi(",", $comments) or eregi("\n", $comments)) {
         return '"'.$comments.'"'; 
      } 
      else {   
         if($param=='') {    
           return $comments;
         }
         else {
           return $comments.chr(160);
         }
      }
    }

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
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    $orderBy = " $sortField $sortOrderBy";
    
    
    if($reportFormat=='') {
      $reportFormat=1;  
    }
    
    
    
    // Find Fee Head List
    if($reportFormat!=3)
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
     
     if($reportFormat==1 || $reportFormat==3) {    
         // Fetch Student List
         $studentListArray = $feeHeadReportManager->getStudentList($condition,$orderBy,'',$consolidatedId,$studentStatus);  
         
         // Fetch Fee Head List
         //$studentFeeArray = $feeHeadReportManager->getStudentWiseFeeHeadCollection($condition1);
         $studentFeeArray = $feeHeadReportManager->getStudentWiseFeeHeadCollection($condition1,$consolidatedId,$studentStatus); 
     }
         
     $csvData = "Search By"; 
     $csvData .= "\n";
     if($reportFormat==1 || $reportFormat==3) {  
       $csvData .= "#,Roll No.,Univ. Roll No.,Student Name,Father's Name,Class";
       if($consolidatedId==0) { 
          $csvData .= ",Receipt No.,Receipt Dt.";  
       }
       
       for($i=0;$i<count($reportFeeHeadArray);$i++) {
           $feeHeadName = $reportFeeHeadArray[$i]['feeHeadName'];
           $feeId = $reportFeeHeadArray[$i]['feeIds'];
           $csvData .= ",".parseCSVComments($feeHeadName); 
       }
       $csvData .= ",Cash,Cheque/DD";    
       
       $dtCheck='';    
       $dateWiseFeeTotal = array();
       for($i=0; $i<count($studentListArray); $i++) {
           displayFeeHead($i); 
       }
       
       $cc=count($valueArray);
       if($cc>0) {
         $csvData .= "\n";
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
         
         if($consolidatedId==0) {   
           $csvData .=",,,,,,,Grand Total";
         }
         else {
           $csvData .=",,,,,Grand Total";  
         }
         for($j=0;$j<count($reportFeeHeadArray);$j++) {
           $feeId = $reportFeeHeadArray[$j]['feeIds']; 
           $csvData .=",".$valueArray[$cc][$feeId]; 
         }
         $csvData .=",".$valueArray[$cc]['cashAmount'];
         $csvData .=",".$valueArray[$cc]['ddAmount'];
       }
       
         
       if($i==0) {
          $csvData .=",,,No Data Found";  
       }
     }
     if($reportFormat==2) {
          $csvData .= "#,Head Name,Amount\n";        
          $srNo=0;
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
               $headCondition = $condition." AND fc.feeHeadId = ".$feeHeadArray[$i]['feeHeadId'];   
               $headCondition .= " AND fr.receiptStatus NOT IN (3,4) "; 
               $studentFeeArray = $feeHeadReportManager->getFeeHeadWiseCollection($headCondition,$studentStatus); 
               $feeHeadName = $feeHeadArray[$i]['headName'];
               $csvData .= ($srNo+1).",".parseCSVComments($feeHeadName).",";   
               if(count($studentFeeArray)>0) {
                 $csvData .= parseCSVComments($studentFeeArray[0]['feeHeadAmount']);
               }
               else {
                 $csvData .= parseCSVComments("0");
               }
               $csvData .= "\n";
               $srNo++;
            }
          }
          
          if($feeAllowArray[0]=='1') {
             $csvData .= headWiseDetails($srNo,'Transport',$applTransport);
             $srNo++;
          }
          if($feeAllowArray[1]=='1') {
            $csvData .= headWiseDetails($srNo,'Hostel',$applHostel);    
            $srNo++;
          }
          if($feeAllowArray[2]=='1') {
            $csvData .= headWiseDetails($srNo,'Fee Fine',$studentFine);                  
            $srNo++;
          }
          if($feeAllowArray[3]=='1') {
            $csvData .=  headWiseDetails($srNo,'Transport Fine',$applTransportFine);                                
            $srNo++;
          }
          if($feeAllowArray[4]=='1') {
            $csvData .= headWiseDetails($srNo,'Hostel Fine',$applHostelFine);                                
            $srNo++;
          }
          if($feeAllowArray[5]=='1') {
            $csvData .=  headWiseDetails($srNo,'Advance Fee',$feeAdvance);                                
            $srNo++;
          }
          if($feeAllowArray[6]=='1') {
            $csvData .=  headWiseDetails($srNo,'Advance Transport',$transportAdvance);                                
            $srNo++;
          }
          if($feeAllowArray[7]=='1') {
            $csvData .= headWiseDetails($srNo,'Advance Hostel',$hostelAdvance);                                
          }
     }
         
    UtilityManager::makeCSV($csvData,'FeeHeadWiseReport.csv');
die;

function headWiseDetails($srNo='',$head='',$amt='') {
    
    if($amt=='') {
     $amt=0;  
    }
    
    $result = ($srNo+1).",".parseCSVComments($head).",".parseCSVComments($amt)."\n";  
   
    return $result;
}  

function displayFeeHead($i) {
		global $reportFormat;
        global $studentListArray; 
        global $studentFeeArray;
        global $feeHeadArray;
        global $feeAllowArray;
        global $valueArray;
        global $csvData;
        global $consolidatedId;  
        global $feeHeadReportManager;  
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
        
        
         if($consolidatedId==0) {  
          if($dtCheck=='') {
             $dtCheck=$studentListArray[$i]['receiptDate'];
          }
          else if($dtCheck!=$studentListArray[$i]['receiptDate']) {
              $dateTotalId = str_replace("-","",$dtCheck); 
              $csvData .= "\n"; 
              
              
              if($consolidatedId==0) {   
                 $csvData .=",,,,,,,Total";
              }
              else {
                $csvData .=",,,,,Total";  
              }
              for($j=0;$j<count($reportFeeHeadArray);$j++) {
               $feeId = $reportFeeHeadArray[$j]['feeIds']; 
               $csvData .=",".$dateWiseFeeTotal[$dateTotalId][$feeId]; 
              }
              $csvData .=",".$dateWiseFeeTotal[$dateTotalId]['cashAmount'];
              $csvData .=",".$dateWiseFeeTotal[$dateTotalId]['ddAmount'];
          }
        }
        
        
        $valueArray[$i]['srNo']=($i+1);
        $valueArray[$i]['rollNo']=$studentListArray[$i]['rollNo'];
        $valueArray[$i]['universityRollNo']=$studentListArray[$i]['universityRollNo'];
        $valueArray[$i]['studentName']=$studentListArray[$i]['studentName'];
        $valueArray[$i]['fatherName']=$studentListArray[$i]['fatherName']; 
        $valueArray[$i]['className']=$studentListArray[$i]['className'];
        
        $csvData .= "\n";   
        $csvData .= ($i+1).",".parseCSVComments($studentListArray[$i]['rollNo'],"1");
        $csvData .= ",".parseCSVComments($studentListArray[$i]['universityRollNo'],"1");
        $csvData .= ",".parseCSVComments($studentListArray[$i]['studentName'],"1");
        $csvData .= ",".parseCSVComments($studentListArray[$i]['fatherName'],"1");
        $csvData .= ",".parseCSVComments($studentListArray[$i]['className'],"1");
        
        $ttCash = "";
        $ttDD = "";
        
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
           
           $csvData .= ",".parseCSVComments($studentListArray[$i]['receiptNo'],"1");
           $csvData .= ",".parseCSVComments(UtilityManager::formatDate($studentListArray[$i]['receiptDate']),"1");
           
           $dateWiseFeeTotal[$dateTotalId]['cashAmount'] += $ttCash;
           $dateWiseFeeTotal[$dateTotalId]['ddAmount'] +=  $ttDD;
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
                       $csvData .= ",".parseCSVComments($studentFeeArray[$k]['feeHeadAmount']);      
                       $valueArray[$i][$feeIds]=$studentFeeArray[$k]['feeHeadAmount']; 
                       if($consolidatedId==0) {      
                         $dateWiseFeeTotal[$dateTotalId][$feeIds]+=$studentFeeArray[$k]['feeHeadAmount']; 
                       }
                       $find=1;
                       break;
                     }
                     else if($ttReceiptNo == $studentFeeArray[$k]['receiptNo'] && $ttReceiptDate = $studentFeeArray[$k]['receiptDate'] && $sFeeHeadId==$feeHeadId && $sStudentId==$studentId && $sFeeClassId==$feeClassId && $sFeeHeadType==1 && $consolidatedId==0) {
                       $csvData .= ",".parseCSVComments($studentFeeArray[$k]['feeHeadAmount']);      
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
              $csvData .= ",".parseCSVComments(NOT_APPLICABLE_STRING); 
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
                  if($sStudentId==$studentId && $sFeeClassId==$feeClassId && $sFeeHeadType==3  && $ttReceiptNo == $studentFeeArray[$k]['receiptNo'] && $ttReceiptDate = $studentFeeArray[$k]['receiptDate']) {
                    $feeAdvance += $studentFeeArray[$k]['feeHeadAmount']; 
                  }  
               }
            }
        }
        if($reportFormat!=3) {
        if($feeAllowArray[0]=='1') {
          $feeIds = "fee_".$fId;  
          $csvData .= ",".parseCSVComments($applTransport); 
          $valueArray[$i][$feeIds]=$applTransport;
          if($consolidatedId==0) {      
             $dateWiseFeeTotal[$dateTotalId][$feeIds]+=$applTransport; 
          }
          $fId++; 
        }
        if($feeAllowArray[1]=='1') {
          $feeIds = "fee_".$fId;  
          $csvData .= ",".parseCSVComments($applHostel); 
          $valueArray[$i][$feeIds]=$applHostel;
          if($consolidatedId==0) {      
             $dateWiseFeeTotal[$dateTotalId][$feeIds]+=$applHostel; 
          }
          $fId++;  
        }
        if($feeAllowArray[2]=='1') {
          $feeIds = "fee_".$fId;  
          $csvData .= ",".parseCSVComments($studentFine); 
          $valueArray[$i][$feeIds]=$studentFine;
          if($consolidatedId==0) {      
             $dateWiseFeeTotal[$dateTotalId][$feeIds]+=$studentFine; 
          }
          $fId++;  
        }
        if($feeAllowArray[3]=='1') {
          $feeIds = "fee_".$fId;  
          $csvData .= ",".parseCSVComments($applTransportFine); 
          $valueArray[$i][$feeIds]=$applTransportFine;
          if($consolidatedId==0) {      
             $dateWiseFeeTotal[$dateTotalId][$feeIds]+=$applTransportFine; 
          }
          $fId++; 
        }
        if($feeAllowArray[4]=='1') {
          $feeIds = "fee_".$fId;  
          $csvData .= ",".parseCSVComments($applHostelFine); 
          $valueArray[$i][$feeIds]=$applHostelFine;
          if($consolidatedId==0) {      
             $dateWiseFeeTotal[$dateTotalId][$feeIds]+=$applHostelFine; 
          }
          $fId++; 
        }
        if($feeAllowArray[5]=='1') {
          $feeIds = "fee_".$fId;  
          $csvData .= ",".parseCSVComments($feeAdvance); 
          $valueArray[$i][$feeIds]=$feeAdvance; 
          if($consolidatedId==0) {      
             $dateWiseFeeTotal[$dateTotalId][$feeIds]+=$feeAdvance; 
          }
          $fId++; 
        }
        if($feeAllowArray[6]=='1') {
          $feeIds = "fee_".$fId;  
          $csvData .= ",".parseCSVComments($transportAdvance); 
          $valueArray[$i][$feeIds]=$transportAdvance; 
          if($consolidatedId==0) {      
             $dateWiseFeeTotal[$dateTotalId][$feeIds]+=$transportAdvance; 
          }
          $fId++;  
        }
        if($feeAllowArray[7]=='1') {
          $feeIds = "fee_".$fId;  
          $csvData .= ",".parseCSVComments($hostelAdvance); 
          $valueArray[$i][$feeIds]=$hostelAdvance; 
          if($consolidatedId==0) {      
             $dateWiseFeeTotal[$dateTotalId][$feeIds]+=$hostelAdvance; 
          }
          $fId++;  
        } 
        }
        $csvData .= ",".parseCSVComments($ttCash);
        $csvData .= ",".parseCSVComments($ttDD);
        
       if($consolidatedId==0) {  
         if($i==(count($studentListArray)-1)) {
           $dateTotalId = str_replace("-","",$dtCheck); 
           $csvData .= "\n"; 
           if($consolidatedId==0) {   
                 $csvData .=",,,,,,,Total";
           }
           else {
                $csvData .=",,,,,Total";  
           }
           for($j=0;$j<count($reportFeeHeadArray);$j++) {
              $feeId = $reportFeeHeadArray[$j]['feeIds']; 
              $csvData .=",".$dateWiseFeeTotal[$dateTotalId][$feeId]; 
           }
           $csvData .=",".$dateWiseFeeTotal[$dateTotalId]['cashAmount'];
           $csvData .=",".$dateWiseFeeTotal[$dateTotalId]['ddAmount'];
         }
       }
        
}     
?>
