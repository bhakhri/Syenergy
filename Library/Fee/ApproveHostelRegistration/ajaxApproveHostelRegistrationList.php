<?php
//-------------------------------------------------------
// Purpose: To show online payment records
// Author :harpreet kaur
// Created on : 13-may-2013
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0); 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    global $sessionHandler;      
    $roleId = $sessionHandler->getSessionVariable('RoleId');     
   
      UtilityManager::ifNotLoggedIn(true);
      UtilityManager::headerNoCache();
 
    require_once(MODEL_PATH . "/Fee/ApproveHostelRegistrationManager.inc.php");   
    $approveHostelRegistrationManager = ApproveHostelRegistrationManager::getInstance();
    
        $fromDate  = trim($REQUEST_DATA['fromDate']); 
        $toDate  = trim($REQUEST_DATA['toDate']); 
        $searchRegistrationStatus =trim($REQUEST_DATA['searchRegistrationStatus']);
        $condition = "";
       
	   if($searchRegistrationStatus!=''){
	   	
		 $condition .= " AND registrationStatus ='$searchRegistrationStatus' ";
	   }
        if($fromDate!='' && $toDate!='') {
          $condition .= " AND (DATE_FORMAT(hr.dateOfEntry,'%Y-%m-%d') BETWEEN '$fromDate' and '$toDate') ";
        }
     


    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////

               
    //$totalArray = $studentManager->getFeesHistoryListNew($filter);
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'dateOfEntry';
    
    if($sortField=='undefined') {
      $sortField='receiptDate';  
    }
    
    if($sortOrderBy=='undefined') {
      $sortOrderBy='ASC';  
    }
    
    $sortField1 = $sortField;
    if($sortField=='receiptDate') {
      $sortField1 = 'hr.dateOfEntry';  
    }
    else if($sortField=='receiptNo') {
      $sortField1 = 'LENGTH(hr.dateOfEntry)+0,hr.dateOfEntry';  
    }
    $orderBy = "$sortField1 $sortOrderBy";   
    
  	
	 //fetch complete information 
     $hostelRecordArray = $approveHostelRegistrationManager->getApproveHostelRegistration($condition,$onlineFeeCondition,$limit,$sortOrderBy,$sortField);
	 $cnt = count($hostelRecordArray);	 
	 
	
     for($i=0;$i<$cnt;$i++) { 
        
		$hostelRecordArray[$i]['dateOfEntry'] = UtilityManager::formatDate($hostelRecordArray[$i]['dateOfEntry']);
		 if($hostelRecordArray[$i]['registrationStatus']=='0'){		 	
			$registerStatus = "Applied";			
			 }
		  if($hostelRecordArray[$i]['registrationStatus']=='1'){		 	
			$registerStatus = "Cancelled";			
			 }
		   if($hostelRecordArray[$i]['registrationStatus']=='2'){		 	
			$registerStatus = "Approved";			
			 }
		   if($hostelRecordArray[$i]['registrationStatus']=='3'){		 	
			$registerStatus = "Rejected";			
			 }
		   if($hostelRecordArray[$i]['registrationStatus']=='4'){		 	
			$registerStatus = "Pending";			
			 }
		    if($hostelRecordArray[$i]['roomTypeId']!=''){		 	
				$ret=explode(",",$hostelRecordArray[$i]['roomTypeId']);
				
				$valueRoomType = explode("~",$ret[0]);				
				$ttRoomType =$valueRoomType[1];
				
				
				$valueRoomType1 = explode("~",$ret[1]);
				$ttRoomType1 =$valueRoomType1[1];
				
				$valueRoomType2 = explode("~",$ret[2]);
				$ttRoomType2 =$valueRoomType2[1];
				
				$xxRoomType =$ttRoomType.','.$ttRoomType1.','.$ttRoomType2;
			  $hostelRoomTypeArray = $approveHostelRegistrationManager->getHostelRoomType($xxRoomType);
			  $ttroomTypeArray='';
			  for($xx=0;$xx<=count($hostelRoomTypeArray);$xx++){
			  	if($ttroomTypeArray!=''){
					$ttroomTypeArray.=",<br>";	
				}				  	
				$ttroomTypeArray.=$hostelRoomTypeArray[$xx]['roomType'];
							
			  }
			 
		 }
		   
		   $studentId =$hostelRecordArray[$i]['studentId'];
		    $classId =$hostelRecordArray[$i]['classId'];
			$selectId = "commonRegistrationStatus".$i;   	
			if($hostelRecordArray[$i]['registrationStatus']=='2'){	//Approvedd	 	
				$showStatusAction ='<select name="commonRegistrationStatus[]" id="$selectId" class="inputbox" style="width:100px;" >
		   				<option value="0">Select</option>
		   				<option value="2" selected ="selected">Approve</option>
		   				<option value="3">Reject</option>
		   				<option value="4">Pending</option>
		   				</select>';						  		
			 }else if($hostelRecordArray[$i]['registrationStatus']=='3'){//Rejected	 	
			 	$showStatusAction ='<select name="commonRegistrationStatus[]" id="$selectId" class="inputbox" style="width:100px;" >
		   				<option value="0">Select</option>
		   				<option value="2" >Approve</option>
		   				<option value="3" selected ="selected">Reject</option>
		   				<option value="4">Pending</option>
		   				</select>';		
			 }else if($hostelRecordArray[$i]['registrationStatus']=='4'){
			 	$showStatusAction ='<select name="commonRegistrationStatus[]" id="$selectId" class="inputbox" style="width:100px;" >
		   				<option value="0">Select</option>
		   				<option value="2" >Approve</option>
		   				<option value="3" >Reject</option>
		   				<option value="4" selected ="selected">Pending</option>
		   				</select>';		
			 }else{			 	  
		     $showStatusAction ='<select name="commonRegistrationStatus[]" id="$selectId" class="inputbox" style="width:100px;" >
		   				<option value="0">Select</option>
		   				<option value="2">Approve</option>
		   				<option value="3">Reject</option>
		   				<option value="4">Pending</option>
		   				</select>';				
			 }
		    
		   
		   if($hostelRecordArray[$i]['wardenComments']!=''){
		   	$valueStatus = $hostelRecordArray[$i]['wardenComments'];
			
			}else{							
			$valueStatus = "";	
		 }
		$commentId ="reason".$i;     
		$comments ='<textarea class="inputbox" name="reason[]" id="$commentId" size="5" >'.$valueStatus.'</textarea>';
		
		$chkId = "chb".$i;       		
		$checkall = "<input type='checkbox' name='chb[]' id='$chkId' value='$studentId~$classId' >";
        
       
        $valueArray = array_merge(array('registerStatus' => $registerStatus,
        								'showAction' => $showStatusAction,
        								'commentsStatus' => $comments,
        								'checkall' => $checkall,
        								'roomTypeArray' => $ttroomTypeArray,
        								 'srNos' => ($records+$i+1)),
                                        $hostelRecordArray[$i]);
        
        if(trim($json_val)=='') {
          $json_val = json_encode($valueArray);
        }
        else {
          $json_val .= ','.json_encode($valueArray);           
        } 

    }

	 	$cntTotal=0;    	
       
         $cntTotal = count($hostelRecordArray)+1;
    	
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cntTotal.'","page":"'.$page.'","info" : ['.$json_val.']}';    
?>
