 <?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/VehicleRouteAllocationManager.inc.php");
    $vehicleRouteAllocationManager = VehicleRouteAllocationManager::getInstance();
    
    require_once(MODEL_PATH . "/RoomAllocationManager.inc.php");
    $roomAllocationManager = RoomAllocationManager::getInstance();

    global $bloodResults; 
     
    $type = trim($REQUEST_DATA['type']);
    $ids = trim($REQUEST_DATA['ids']);   
    
    $idsArray = explode(',',$ids);

    require_once(TEMPLATES_PATH."/GeneratePass/busPassTemplate.php");     
    $studentContent = $icardData; 	    
   
    require_once(TEMPLATES_PATH."/GeneratePass/busPassEmployeeTemplate.php");     
    $employeeContent = $icardData;

    require_once(TEMPLATES_PATH."/GeneratePass/hostelPassTemplate.php");     
    $hostelContent = $icardData;  
   
	
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $orderBy = " $sortField $sortOrderBy";     
 
    $val ='';
    for($i=0;$i<count($idsArray);$i++) {
       if($val!='') {
         $val .=",";  
       }  
       $val .= "'".$idsArray[$i]."'";
    }
 
    if($val!='') {
      if($type=='B') {
	  // Bus Pass	
         $condition = " AND CONCAT(frd.studentId,'~',frd.classId) IN ($val) "; 
         $foundArray = $vehicleRouteAllocationManager->getStudentAllocationList($condition,$orderBy);  
      }
      else {
	  // Hostel Pass
         $condition = " AND CONCAT(frd.studentId,'~',frd.classId) IN ($val) "; 
         $foundArray = $roomAllocationManager->getRoomAllocationList($condition,'',$orderBy);  
      } 
    }
    else {
       echo getNoData();
       die;   
    }
   
   
    $issueDate = date('Y-m-d');  
    
	$logoFile = IMG_HTTP_PATH."/logo.gif";
	$imgLogo = "<img src='".$logoFile."' height='35px' width='95px' >";

    for($i=0;$i<count($foundArray);$i++) {
	    if($type=='B') {	
          // isAllocation check 1 for student  
	      if($foundArray[$i]['isAllocation']=='1') { 
             $dataContent = $studentContent;
             
             $comments  = "UID:".$foundArray[$i]['userId'];
             $comments .= "IssueDate:".$foundArray[$i]['issueDate'];
             $comments .= "PassStatus:".$foundArray[$i]['isPassStatus']."~";
              
             $passCheckStatus=''; 
             $passSrNo ='';
             if($foundArray[$i]['isPassStatus']>0 && $foundArray[$i]['passIssueDate'] == $issueDate) {  
                $passCheckStatus='1';  
             }           
             else {
                // Generate Sr. No. for Bus Pass
                $returnArray = $vehicleRouteAllocationManager->getPassSrNo();
                $srNo = doubleval($returnArray[0]['passSrNo'])+1;
                if(strlen($srNo)=='1') {
                  $passSrNo = "0000".$srNo; 
                }
                else if(strlen($srNo)=='2') {
                  $passSrNo = "000".$srNo; 
                }
                else if(strlen($srNo)=='3') {
                  $passSrNo = "00".$srNo; 
                }
                else if(strlen($srNo)=='4') {
                  $passSrNo = "0".$srNo; 
                }
                else {
                  $passSrNo = $srNo; 
                }
             }     
              
             // check Status Blank Table record update   
		     if($passCheckStatus=='') { 
               if(SystemDatabaseManager::getInstance()->startTransaction()) {  
                 
                  $condition = " busRouteStudentMappingId = '".$foundArray[$i]['busRouteStudentMappingId']."'"; 
                  $returnArray = $vehicleRouteAllocationManager->updateBusStatus($condition,$comments,$passSrNo);   
                  if($returnArray===false) {
                    die;  
                  }
                  if(SystemDatabaseManager::getInstance()->commitTransaction()) { 
                  }
               }
             }
	      }	
	      else {
		    $dataContent = $employeeContent;
	      }
	    }
        else {
	       $dataContent = $hostelContent;
           
           $comments  = "UID:".$foundArray[$i]['userId'];
           $comments .= "IssueDate:".$foundArray[$i]['issueDate'];
           $comments .= "PassStatus:".$foundArray[$i]['isPassStatus']."~";
              
           $passCheckStatus=''; 
           $passSrNo ='';
           if($foundArray[$i]['isPassStatus']>0 && $foundArray[$i]['passIssueDate'] == $issueDate) {  
                $passCheckStatus='1';  
           }           
           else {
                // Generate Sr. No. for Hostel Passs
                $returnArray = $roomAllocationManager->getPassSrNo();
                $srNo = doubleval($returnArray[0]['passSrNo'])+1;
                if(strlen($srNo)=='1') {
                  $passSrNo = "0000".$srNo; 
                }
                else if(strlen($srNo)=='2') {
                  $passSrNo = "000".$srNo; 
                }
                else if(strlen($srNo)=='3') {
                  $passSrNo = "00".$srNo; 
                }
                else if(strlen($srNo)=='4') {
                  $passSrNo = "0".$srNo; 
                }
                else {
                  $passSrNo = $srNo; 
                }
             }     
              
             // check Status Blank Table record update   
             if($passCheckStatus=='') { 
               if(SystemDatabaseManager::getInstance()->startTransaction()) {  
                 
                  $condition = " hostelStudentId = '".$foundArray[$i]['hostelStudentId']."'"; 
                  $returnArray = $roomAllocationManager->updateBusStatus($condition,$comments,$passSrNo);   
                  if($returnArray===false) {
                    die;  
                  }
                  if(SystemDatabaseManager::getInstance()->commitTransaction()) { 
                  }
               }
             }
           
           
        }
      
	      $fileName = IMG_PATH."/Student/".$foundArray[$i]['Photo']; 
	      if(file_exists($fileName)) {
	      	$img = "<img class='bborder' src=\"".STUDENT_PHOTO_PATH."/".$foundArray[$i]['Photo']."\"height=\"55px\" width=\"65px\"  valign=\"middle\" >"; 
	      }
	      else {
		    $img = "<img class='bborder' src=\"".IMG_HTTP_PATH."/notfound.jpg \"height=\"55px\" width=\"65px\" \"valign=\"middle\" >";                          
	      }

	      $fileName = IMG_PATH."/Employee/".$foundArray[$i]['employeePhoto']; 
	      if(file_exists($fileName)) {
		    $imgEmp = "<img class='bborder' src=\"".HTTP_PATH."/".$foundArray[$i]['Photo']."\"height=\"55px\" width=\"65px\"  valign=\"middle\" >"; 
	      }
	      else {
		    $imgEmp = "<img class='bborder' src=\"".IMG_HTTP_PATH."/notfound.jpg \"height=\"55px\" width=\"65px\" \"valign=\"middle\" >";                          
	      }

	      $validTo =  UtilityManager::formatDate($foundArray[$i]['validTo']);
	      $bloodGroup  = $bloodResults[$foundArray[$i]['studentBloodGroup']];
	      if($bloodGroup=='') {
		    $bloodGroup = NOT_APPLICABLE_STRING;    
	      }
	      $dob =  UtilityManager::formatDate($foundArray[$i]['DOB']);
	      
		   
          if($passSrNo=='') {
            $passSrNo = $foundArray[$i]['passSrNo'];  
          } 
          $dataContent = str_replace('<StudentId>',$passSrNo,$dataContent);                                                           
          
	      $dataContent = str_replace('<ISSUE>',date('d-M-y'),$dataContent); 
	      $dataContent = str_replace('<INSTLOGO>',$imgLogo,$dataContent); 
	      $dataContent = str_replace('<StudentPhoto>',$img,$dataContent); 
	      $dataContent = str_replace('<StudentRollNoHead>',"Roll No.",$dataContent);  
	      $dataContent = str_replace('<StudentRollNo>',$foundArray[$i]['rollNo'],$dataContent);  
	      $dataContent = str_replace('<StudentName>',$foundArray[$i]['studentName'],$dataContent);  
	      $dataContent = str_replace('<Course>',$foundArray[$i]['branchCode'],$dataContent);  
	      $dataContent = str_replace('<ROUTENO>',$foundArray[$i]['routeCode'],$dataContent);  
	      $dataContent = str_replace('<RECEIPTNO>',$foundArray[$i]['receiptNo'],$dataContent);  
	      $dataContent = str_replace('<STOPPAGE>',$foundArray[$i]['stopName'],$dataContent);  
	      $dataContent = str_replace('<VALIDITY>',$validTo,$dataContent);  
	      $dataContent = str_replace('<StudentAddress>',$foundArray[$i]['corrAddress'],$dataContent);  
	      $dataContent = str_replace('<StudentContact>',$foundArray[$i]['studentMobileNo'],$dataContent);  
	      $dataContent = str_replace('<StudentDOB>',$dob,$dataContent);  
	      $dataContent = str_replace('<StudentBloodGroup>',$bloodGroup,$dataContent);  
      
	      /*$dataContent = str_replace('<employeePhoto>',$imgEmp,$dataContent); 
	      $dataContent = str_replace('<employeeName>',$foundArray[$i]['employeeName'],$dataContent);  
	      $dataContent = str_replace('<designationName>'$foundArray[$i]['designationName'],$dataContent);  
	      $dataContent = str_replace('<BusPassDetail>',$foundArray[$i]['rollNo'],$dataContent); 
	      $dataContent = str_replace('<employeeCode>',$foundArray[$i]['rollNo'],$dataContent);  
	      $dataContent = str_replace('<department>',$foundArray[$i]['rollNo'],$dataContent); 
	      $dataContent = str_replace('<DOJ>',$foundArray[$i]['rollNo'],$dataContent); 
	      $dataContent = str_replace('<BloodGroup>',$foundArray[$i]['rollNo'],$dataContent);  
	      $dataContent = str_replace('<address>',$foundArray[$i]['rollNo'],$dataContent); 
	      $dataContent = str_replace('<contactNo>',$foundArray[$i]['rollNo'],$dataContent);
*/
	      $dataContent = str_replace('<HostelName>',$foundArray[$i]['hostelName'],$dataContent); 
	      $dataContent = str_replace('<RoomNo>',$foundArray[$i]['RoomName'],$dataContent);  
	      $dataContent = str_replace('<CheckOut>',$foundArray[$i]['checkOutDate'],$dataContent);  


     	      echo $dataContent."<br>";
       }
    
    
    
    
    function getNoData() {
    ?>    
      <table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
            <tr>
                <td align="left" colspan="1" width="25%" class=""><?php echo $reportManager->showHeader();?></td>
                <th align="center" colspan="1" width="50%" <?php echo $reportManager->getReportTitleStyle();?>><?php echo $reportManager->getInstituteName(); ?></th>
                <td align="right" colspan="1" width="25%" class="">
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("d-M-y");?></td>
                        </tr>
                        <tr>
                            <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right">Time :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("h:i:s A");?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center"><?php echo $reportManager->reportHeading; ?></th></tr>
            <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center">Student Bus Pass Report</th></tr>
            </table> <br> 
            <table border='0' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
            <tr>
                <td valign="top">
                    <div class="dataFont" align="center"><b>No record found</b></div>
                </td>
            </tr>
           </table><br><br>     
           <table border='0' cellspacing='0' cellpadding='0' width="90%" align="center">
           <tr>
            <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
        </tr>
        </table>    
    <?php
    }
    ?>
