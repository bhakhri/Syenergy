<?php
//Edited-Madhav Bhasin--------------------
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	define('MODULE','COMMON');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  
	
    require_once(MODEL_PATH . "/StudentPendingDuesManager.inc.php");
    $studentPendingDuesManager = StudentPendingDuesManager::getInstance();

        $page    = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
        $records = ($page-1)* 15000;
        $limit   = ' LIMIT '.$records.',15000';
        
        $feeClassId = trim(add_slashes($REQUEST_DATA['feeClassId']));
        $studentName = trim(add_slashes($REQUEST_DATA['studentName']));
        $rollNo = trim(add_slashes($REQUEST_DATA['rollNo']));
        $leftOutStudent=$REQUEST_DATA['leftOutStudent'];
        
        $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
        $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
        
        if($sortOrderBy=='undefined') {
          $sortOrderBy = 'ASC';
        }
        
        if($sortField=='undefined') {
          $sortField = 'studentName'; 
        }
        
        $orderBy = " $sortField $sortOrderBy";     
        
        $studentCondition = '';
        $condition = '';		
        if($rollNo!='') {
          $condition .= " AND (stu.rollNo LIKE '$rollNo' OR stu.regNo LIKE '$rollNo'  OR stu.universityRollNo LIKE '$rollNo') ";		   
        }
        
        if($studentName!='') {
          $condition .= " AND (CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) LIKE '$studentName') ";
        }
        
        if($feeClassId=='') {
          $feeClassId=0;  
        }
		
        $listClassArray = explode(',',$feeClassId);
       
        $classArray = $studentPendingDuesManager->getClassList($feeClassId);
        $classIds =0;
        for($i=0;$i<count($classArray);$i++) {
          $classIds .= ",".$classArray[$i]['classId'];  
        }
		
		
        if($leftOutStudent!=''){
			if($leftOutStudent==1){
				$studentCondition1=1;
			}
			else {
				$studentCondition1=0;	
			}
		}
		
        $recCount=0;
        $studentCondition = " stu.classId IN (".$classIds.") ".$condition;
		
        $studentArray = $studentPendingDuesManager->getClassStudentList($studentCondition,$orderBy,$studentCondition1);
        for($ss=0;$ss<count($studentArray);$ss++) { 
           for($j=0;$j<count($listClassArray);$j++) {
              $classId = $listClassArray[$j]; 
              $studentArray[$ss]['academicDues']=0;
              $studentArray[$ss]['transportDues']=0;
              $studentArray[$ss]['hostelDues']=0;
              $studentArray[$ss]['prevDues']=0;
              $studentArray[$ss]['total']=0;
              $studentArray[$ss]['feeClassName']='';


                $span1 = "<span class='redColor'>";
              $span2 = "</span>";
              $chk='';
              if($studentArray[$ss]['deleteType']=='0' && $studentArray[$ss]['studentStatus'] =='1') {
                $span1 = "";
                $span2 = "";
              }
              
              $stuStatus ="";
              if($studentArray[$ss]['deleteType']=='1') {
                $stuStatus = " (Deleted)";  
              }
              else if($studentArray[$ss]['studentStatus']=='0') {
                $stuStatus = " (Inactive)";  
              }


              $studentArray[$ss]['studentName']=$span1.$studentArray[$ss]['studentName'].$stuStatus.$span2;
              $studentArray[$ss]['regNo']=$span1.$studentArray[$ss]['regNo'].$span2;
              $studentArray[$ss]['rollNo']=$span1.$studentArray[$ss]['rollNo'].$span2;
              $studentArray[$ss]['fatherName']=$span1.$studentArray[$ss]['fatherName'].$span2;
              $studentArray[$ss]['universityRollNo']=$span1.$studentArray[$ss]['universityRollNo'].$span2;
              $studentArray[$ss]['studentMobileNo']=$span1.$studentArray[$ss]['studentMobileNo'].$span2;
           
            
              $valueArray = getFeeList($studentArray[$ss],$classId);
              
	          if($valueArray!=0) {
	            $finalPindingArray[] = $valueArray;
                    $valueArray['srNo']=$span1.$valueArray['srNo'].$span2; 
                    $valueArray['feeClassName']=$span1.$valueArray['feeClassName'].$span2; 
	          }
	       }
	    }
		
		$formattedDate = date('d-M-y');
	    $reportManager->setReportWidth(800);
	    $reportManager->setReportHeading('Pending Dues Report');
	    $reportManager->setReportInformation("As on $formattedDate");
	    
	    $reportTableHead                     =    array();
	                    //associated key                  col.label,        col. width,      data align        
	    $reportTableHead['srNo']             =    array('#',                'width="2%" align="left"', "align='left'"); 
	    $reportTableHead['studentName']      =    array('Student Name',     'width=15% align="left" ','align="left" ');
	    $reportTableHead['rollNo']           =    array(COLUMN_ROLL_NO,     'width="8%" align="left" ','align="left"');
	    $reportTableHead['universityRollNo'] =    array(COLUMN_UNIV_ROLL_NO,'width="12%" align="left" ','align="left"');  
	    $reportTableHead['feeClassName']     =    array('Class ',           'width=18% align="left" ','align="left" ');
	    $reportTableHead['studentMobileNo']  =    array('Mobile',           'width="10%" align="left" ','align="left"');
	    $reportTableHead['imgSrc']           =    array('Photo',            'width="10%" align="left" ','align="center"');  
	    $reportTableHead['academicDues']     =    array('Academic',         'width="5%" align="left" ','align="right"');  
	    $reportTableHead['transportDues']    =    array('Transport',        'width="5%" align="left" ','align="right"');  
	    $reportTableHead['hostelDues']       =    array('Hostel',           'width="5%" align="left" ','align="right"');  
	    $reportTableHead['prevDues']         =    array('Prev. Dues',       'width="5%" align="left" ','align="right"');  
	    $reportTableHead['total']            =    array('Total',            'width="5%" align="left" ','align="right"');  
	    
	  
	    $reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	    $reportManager->setReportData($reportTableHead, $finalPindingArray);
	    $reportManager->showReport(); 
	die;   
    
    
function getFeeList($resultArray,$classId) {    
    
      global $studentPendingDuesManager;
      global $sessionHandler; 
      global $recCount;
      
      
      $valueArray = array();
      
      $studentId = $resultArray['studentId']; 
      $quotaId = $resultArray['quotaId']; 
      $isLeet = $resultArray['isLeet'];  
      $tIsLeet=2; 
      if($isLeet==1) {
        $tIsLeet=1;  
      } 
      
      if($resultArray['studentPhoto'] != ''){ 
            $File = STORAGE_PATH."/Images/Student/".$resultArray['studentPhoto'];
            if(file_exists($File)){
               $imgSrc= IMG_HTTP_PATH.'/Student/'.$resultArray['studentPhoto'];
            }
            else{
               $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
            }
      }
      else{
          $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
      }
      $imgSrc = "<img src='".$imgSrc."' width='20' height='20' id='studentImageId' class='imgLinkRemove' />";
      $resultArray['imgSrc'] = $imgSrc;

      $conessionFormatId =  $sessionHandler->getSessionVariable('CONCESSION_FORMAT'); 
      if($conessionFormatId=='') {
        $conessionFormatId=1;  
      }
      $conessionFormatId=3;  
      
      
      // Findout Student Details
      $condition = " AND stu.studentId='".$studentId."'";
	  
      $studentFeesArray = $studentPendingDuesManager->getStudentDetailClass($condition,$classId);     
      if(is_array($studentFeesArray) && count($studentFeesArray)>0 ) {
         if($studentFeesArray[0]['feeClassId']==-1) {
            return 0;
         }
      }  
      else {
         return 0;
      }
      $resultArray['feeClassName']=$studentFeesArray[0]['feeClassName'];
      
      // Check Adhoc Concession 
      $adhocConcession=0; 
      $adhocCondition = " feeClassId = '$classId' AND studentId = '$studentId' "; 
      $adhocConcessionArray = $studentPendingDuesManager->getCheckStudentConcession($adhocCondition); 
      if(is_array($adhocConcessionArray) && count($adhocConcessionArray)>0) {
         $adhocConcession = 1; 
         $conessionFormatId = 4;
      }  
      
      
      // ======== Prev Dues START ===========
            $resultArray['prevDues']=0;
            $showTDuesAmt=0;
            $prevCondition = " AND fsf.studentId = '$studentId' AND fsf.classId <= '$classId' ";  
            $prevClassFeeArray = $studentPendingDuesManager->getPendingDuesList($prevCondition);  
            for($i=0; $i<count($prevClassFeeArray); $i++) {
               if($prevClassFeeArray[$i]['dues'] != $prevClassFeeArray[$i]['paid']) {
                  $srNo=$rSrNo;  
                  $duesClassId = $prevClassFeeArray[$i]['classId'];
                  $duesAmt = $prevClassFeeArray[$i]['dues'];
                  if($duesAmt=='') {
                    $duesAmt=0;  
                  }
                  $showTDuesAmt += doubleval($duesAmt);
               }
            }
            $resultArray['prevDues']=$showTDuesAmt;
      // ======== Prev Dues END ===========
      
      
      // ======== Acadmeic  START ===========
            $resultArray['academicDues']=0;
            $showTFeeAmt=0;
            $feeId = "-1";
            $havingConditon = " COUNT(fhv.feeHeadId) = 1 "; 
            $foundArray = $studentPendingDuesManager->getCountFeeHead($classId,$quotaId,$tIsLeet,$havingConditon);
            for($i=0; $i<count($foundArray); $i++) {
              $feeId .=",".$foundArray[$i]['feeId'];  
            }        
            
            $havingConditon = " COUNT(fhv.feeHeadId) >= 2"; 
            $isLeetCheck = "1,2,3";
            $foundArray = $studentPendingDuesManager->getCountFeeHead($classId,$quotaId,$tIsLeet,$havingConditon,'',0,$isLeetCheck); 
            for($i=0; $i<count($foundArray); $i++) {
               $tFeeHeadId = $foundArray[$i]['feeHeadId']; 
               if($quotaId!='') {
                  $feeHeadCondition = " AND fhv.quotaId = $quotaId AND fhv.feeHeadId = $tFeeHeadId";  
                  $quotaFoundArray = $studentPendingDuesManager->getCountFeeHead($classId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
                  if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                    $feeId .=",".$quotaFoundArray[0]['feeId'];  
                  }
                  else {
                    $feeHeadCondition = " AND IFNULL(fhv.quotaId,'')='' AND fhv.feeHeadId = $tFeeHeadId";  
                    $quotaFoundArray = $studentPendingDuesManager->getCountFeeHead($classId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
                    if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                      $feeId .=",".$quotaFoundArray[0]['feeId'];  
                    }
                    else {
                       $feeHeadCondition = " AND IFNULL(fhv.quotaId,'')='' AND fhv.feeHeadId = $tFeeHeadId";  
                       $quotaFoundArray = $studentPendingDuesManager->getCountFeeHeadNew($feeClassId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
                       if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                         $feeId .=",".$quotaFoundArray[0]['feeId'];  
                       }
                    }
                  }
               }
               else {
                 $feeHeadCondition = " AND IFNULL(fhv.quotaId,'')='' AND fhv.feeHeadId = $tFeeHeadId";  
                 $quotaFoundArray = $studentPendingDuesManager->getCountFeeHead($classId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
                 if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                   $feeId .=",".$quotaFoundArray[0]['feeId'];  
                 } 
               }
            }        
            if($feeId=='') {
              $feeId = "-1"; 
            }

            //================================FEE HEAD DETAILS (Start)=======================================
            $foundArray = $studentPendingDuesManager->getStudentFeeHeadDetail($classId,$feeId,$studentId);
            $feeHeadIds = "-1";
            for($i=0;$i<count($foundArray);$i++) {
              $feeHeadIds .= ",".$foundArray[$i]['feeHeadId'];   
            }
         
            // Student Concession Findout is Leet & Non Leet Base 
            $concessionArray = $studentPendingDuesManager->getStudentConcession($classId,$studentId,$feeHeadIds,$tIsLeet,$condition='');
            $concessionFeeHeadIds = "-1"; 
            for($i=0;$i<count($concessionArray);$i++) {
              $concessionFeeHeadIds .= ",".$concessionArray[$i]['feeHeadId'];   
            }
            
            $concessionCondition = " AND fcv.feeHeadId NOT IN ($concessionFeeHeadIds)";
            $concessionFinalArray = $studentPendingDuesManager->getStudentFinalConcession($classId,$studentId,$feeHeadIds,$tIsLeet,$concessionCondition);
             
            $i=0;
            $concession=0;    
            for($i=0; $i<count($foundArray); $i++) {
               $feeHeadDetailFind=1;
               $foundArray[$i]['concession']=0;
               $feeId = $foundArray[$i]['isVariable'].'_'.$foundArray[$i]['feeId'];
               $totalFees +=$foundArray[$i]['feeHeadAmt'];
               $salFeeHeadId =  $foundArray[$i]['feeHeadId'];
               
               $concession =0;     
               // Categories wise Concession 
               if($adhocConcession==1) {
                  for($jj=0;$jj<count($adhocConcessionArray);$jj++) {
                     if($adhocConcessionArray[$jj]['feeHeadId']==$salFeeHeadId) {  
                       $concession = $adhocConcessionArray[$jj]['concessionAmount']; 
                     }
                  }
               }
               else if($adhocConcession==0) {
                   $maxConcession = 0;
                   $minConcession = 0;
                   $reducingConcession = 0;   
                   $chk=0;               
                   for($jj=0;$jj<count($concessionFinalArray);$jj++) {
                      if($concessionFinalArray[$jj]['feeHeadId']==$salFeeHeadId) {
                          if($concessionFinalArray[$jj]['concessionType']=='2') {
                            $concessionAmt = doubleval($foundArray[$i]['feeHeadAmt']) - doubleval($concessionFinalArray[$jj]['concessionAmount']);
                            if($chk==1) {
                              $reducingConcession = doubleval($reducingConcession) - doubleval($concessionFinalArray[$jj]['concessionAmount']);
                            }
                          }
                          if($concessionFinalArray[$jj]['concessionType']=='1') {
                            $concessionAmt = doubleval($foundArray[$i]['feeHeadAmt']) - (doubleval($foundArray[$i]['feeHeadAmt']) * doubleval($concessionFinalArray[$jj]['concessionAmount'])/100.0);
                            if($chk==1) {
                              $reducingConcession = doubleval($reducingConcession) - (doubleval($reducingConcession) * doubleval($concessionFinalArray[$jj]['concessionAmount'])/100.0);
                            }
                          }
                         
                          if($chk==0) {
                             $maxConcession = $concessionAmt;
                             $minConcession = $concessionAmt; 
                             $reducingConcession = $concessionAmt;
                          }
                          if($concessionAmt < $maxConcession) {
                            $maxConcession = $concessionAmt;  
                          }
                          if($concessionAmt > $minConcession) {
                            $minConcession = $concessionAmt;  
                          }
                          $chk=1;        
                      }
                   }
                   
                   if($conessionFormatId==1) {
                     $concession = $maxConcession; 
                   }
                   if($conessionFormatId==2) {
                     $concession = $minConcession; 
                   }
                   if($conessionFormatId==3) {
                     $concession = $reducingConcession; 
                   }
               }
               
               if($concession==0 || $concession=='') {
                 $conn = 0;    
               }
               else {
                  if($adhocConcession==0) { 
                    $conn = doubleval($foundArray[$i]['feeHeadAmt'])-doubleval($concession);
                  }
                  else {
                    $conn = doubleval($concession);  
                  }
               }
               $foundArray[$i]['concession'] = $conn;
               $totalConcession += doubleval($foundArray[$i]['concession']);  
               $feesAmt = doubleval($foundArray[$i]['feeHeadAmt']) - doubleval($foundArray[$i]['concession']);
               $showTFeeAmt +=$feesAmt;
            }
            $resultArray['academicDues']=$showTFeeAmt;
      // ======== Acadmeic END ===========
      
      
     // ======== Transport  START ===========    
         $resultArray['transportDues']=0;
         $showTransportAmt=0;
         $condition  = " fsf.studentId = $studentId AND fsf.classId = '$classId' AND IFNULL(fsf.facilityType,'') = 1 ";    
         $facilityArrayCheck = $studentPendingDuesManager->getFacility($condition);   
         if(is_array($facilityArrayCheck) && count($facilityArrayCheck)>0 ) {   
            $trCharges = $facilityArrayCheck[0]['charges'];
            $trConcession = $facilityArrayCheck[0]['concession'];
            $showTransportAmt = doubleval($trCharges) - doubleval($trConcession);
         }
         $resultArray['transportDues']=$showTransportAmt;
     // ======== Transport  END ===========     
      
      
     // ======== Hostel  START ===========    
         $resultArray['hostelDues']=0;
         $showHostelAmt=0;
         $condition  = " fsf.studentId = $studentId AND fsf.classId = '$classId' AND IFNULL(fsf.facilityType,'') = 2 ";                    
         $facilityArrayCheck = $studentPendingDuesManager->getFacility($condition);  
         if(is_array($facilityArrayCheck) && count($facilityArrayCheck)>0 ) {   
            $trCharges = $facilityArrayCheck[0]['charges'];
            $trConcession = $facilityArrayCheck[0]['concession'];
            $showHostelAmt = doubleval($trCharges) - doubleval($trConcession);
         }
         $resultArray['hostelDues']=$showHostelAmt;
     // ======== Hostel  END ===========     
     
     
       //$condition  = " fsf.studentId = $studentId AND fsf.classId = '$classId' AND IFNULL(fsf.facilityType,'') = 2 ";                    
       //$prevFeeArray= $studentPendingDuesManager->getPreviousFeePaymentDetail(
       
       $condition  = " AND f.studentId = $studentId AND f.feeClassId = '$classId'"; 
       $paidArray = $studentPendingDuesManager->getPreviousFeePaymentDetail($condition); 
       
       $hostelPaid=0;
       $transportPaid=0;
       $feePaid=0;
       $duesPaid=0;
       for($i=0;$i<count($paidArray);$i++) {
          $showTFeeAmt += doubleval($paidArray[$i]['prevFeeFine']);
          $showTransportAmt += doubleval($paidArray[$i]['prevTransportFine']); 
          $showHostelAmt += doubleval($paidArray[$i]['prevHostelFine']); 
          
          $hostelPaid += doubleval($paidArray[$i]['prevHostelPaid']);  
          $transportPaid+= doubleval($paidArray[$i]['prevTransportPaid']);  
          $feePaid += doubleval($paidArray[$i]['prevFeePaid']);  
          $duesPaid += doubleval($paidArray[$i]['prevDuesPaid']);  
       }
       
       $resultArray['prevDues']=doubleval($showTDuesAmt)-doubleval($duesPaid);
       $resultArray['academicDues']=doubleval($showTFeeAmt)-doubleval($feePaid);
       $resultArray['transportDues']=doubleval($showTransportAmt)-doubleval($transportPaid );
       $resultArray['hostelDues']=doubleval($showHostelAmt)-doubleval($hostelPaid);
       
       $total = doubleval($showTDuesAmt)+doubleval($showTFeeAmt)+doubleval($showTransportAmt)+doubleval($showHostelAmt);
       $paid = doubleval($duesPaid)+doubleval($feePaid)+doubleval($transportPaid)+doubleval($hostelPaid);
       
       $net = doubleval($total) -  doubleval($paid); 
       
       $resultArray['total'] = doubleval($resultArray['prevDues'])+doubleval($resultArray['academicDues'])+doubleval($resultArray['transportDues'])+doubleval($resultArray['hostelDues']);
      
       if(doubleval($net)!=0) {      
         $valueArray = array_merge(array('srNo' => ($recCount+1)), $resultArray);
         $recCount++;
         return $valueArray;
       } 
       return 0;
}
?>    
