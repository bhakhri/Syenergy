<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Fee Slip </title>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
<style type="text/css" media="print">
@page port {size: portrait;}
@page land {size: landscape;}
.portrait {page: port;}
.landscape {page: land;}

BR.page { page-break-after: always }
</style>

<script type="text/javascript">

function printout()
{
	document.getElementById('hidePrint').style.display='none';
	window.print();
	document.getElementById('hidePrint').style.display='';
}
</script>
</head>
<body>
<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    
	echo UtilityManager::includeCSS("css.css");
	
    require_once(MODEL_PATH . "/Fee/StudentFeeManager.inc.php");
    $studentFeeManager = StudentFeeManager::getInstance();
        
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
    require_once(MODEL_PATH . "/Fee/GenerateFeeManager.inc.php");
    $generateFeeManager = GenerateFeeManager::getInstance(); 

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

    require_once(MODEL_PATH . "/Fee/FeeHeadManager.inc.php");
    $feeHeadManager = FeeHeadManager::getInstance();
    
    require_once(BL_PATH . '/NumToWord.class.php'); 

    global $sessionHandler; 
    
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==4){
      UtilityManager::ifStudentNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache(); 
    
    
    $slipCopyNameArray = array(1=>'Student Copy',2=>'Bank Copy',3=>'College Copy');
    
    $hostelDescId='';
    $transportDescId='';
  
    $instituteAbbr = '';
    $instituteAbbArray = $sessionHandler->getSessionVariable('InstituteAbbArray');
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $instituteAbbr =  $instituteAbbArray[$instituteId];  
    $userId = $sessionHandler->getSessionVariable('UserId');

    $payFeeOf = "all";
    if(($userId==''|| $payFeeOf == '') || $REQUEST_DATA['classId'] =='' ||  $REQUEST_DATA['studentId'] ==''){
       echo 'Required Parameters Missing';
       die;
    }
    
    $feeClassId = $REQUEST_DATA['classId'];
    $currentClassId = $feeClassId;
    $classId = $feeClassId;
    $studentId = $REQUEST_DATA['studentId']; 
    $comments = html_entity_decode(strip_slashes($REQUEST_DATA['comments'])); 
	 $paymentMode = $REQUEST_DATA['paymentMode']; 
    $ddNo = $REQUEST_DATA['ddNo']; 
    $ddDate = $REQUEST_DATA['ddDate']; 
    $ddBankName = $REQUEST_DATA['ddBankName']; 
	
	if($ddNo==''){		
		$ddNo="..........................";		
	}
	if($ddDate==''){		
		$ddDate="............................";		
	}
	if($ddBankName==''){		
		$ddBankName="..................................................";		
	}
	
    $feesClassArray = $studentFeeManager->getFeeClass($feeClassId); 
    $feeClassId = $feesClassArray[0]['feeClass'];    
    $feeStudyPeriodId = $feesClassArray[0]['studyPeriodId'];
    $batchId = $feesClassArray[0]['batchId'];
    
    // $condition = " AND stu.userId = '$userId'";
    $totalFeePaid =0;
    if($payFeeOf=='academic'){
      $feeType=1;
    }
    
    if($payFeeOf=='transport'){
       $feeType=2;
     }

    if($payFeeOf=='hostel'){
      $feeType=3;
    }
     
    if($payFeeOf =='all'){
      $feeType=4;    
    }


  
    if($payFeeOf=='academic' || $payFeeOf =='all') {
         
           if($feeAcdSearch=='0') {
             $feeResultMessage = getGenerateFee($feeClassId,$studentId);
             if($feeResultMessage!=SUCCESS) {
               echo $feeResultMessage;
               die;  
             }
           }
        }
    
          $receiptNo = '';
          $feeDataArray = $studentFeeManager->getStudentFeeDetails($studentId,$currentClassId,$feeClassId);
        if(count($feeDataArray) == 0){
              echo "No Data Found.";
              die;
          }
          $feeCycleId = $feeDataArray[0]['feeCycleId'];
          $regNo = $feeDataArray[0]['regNo'];      
        
         
        
        
       $hostelDesc = " <u><b>Hostel Detail</b></u>
                       <table width='100%' border='1px' cellpadding='0px' cellspacing='0px' >
                        <tr>
                           <td class='dataFont' align='left' ><b>Hostel</b></td>
                           <td class='dataFont' align='center' ><b>Check In</b></td>
                           <td class='dataFont' align='center' ><b>Check Out</b></td>
                        </tr>   
                        <tr>
                           <td class='dataFont' align='left' >".$feeDataArray[0]['hostelName']." (".$feeDataArray[0]['roomName'].")</td>
                           <td align='center' class='dataFont'>".UtilityManager::formatDate($feeDataArray[0]['dateOfCheckIn'])."</td>
                           <td align='center' class='dataFont'>".UtilityManager::formatDate($feeDataArray[0]['dateOfCheckOut'])."</td>
                        </tr>
                       </table>";
        
      $transportDesc = " <u><b>Transport Detail</b></u>
                       <table width='100%' border='1px' cellpadding='0px' cellspacing='0px' >
                        <tr>
                           <td class='dataFont' align='left' ><b>Route</b></td>
                           <td class='dataFont' align='center' ><b>Valid From</b></td>
                           <td class='dataFont' align='center' ><b>Valid To</b></td>
                        </tr>   
                        <tr>
                           <td class='dataFont' align='left' >".$feeDataArray[0]['routeName']." (".$feeDataArray[0]['cityName'].")</td>
                           <td align='center' class='dataFont'>".UtilityManager::formatDate($feeDataArray[0]['validFrom'])."</td>
                           <td align='center' class='dataFont'>".UtilityManager::formatDate($feeDataArray[0]['validTo'])."</td>
                        </tr>
                       </table>";
         $t1 = $feeDataArray[0]['routeName']." (".$feeDataArray[0]['cityName'].")";
         $h1 = $feeDataArray[0]['hostelName']." (".$feeDataArray[0]['roomName'].")";              
          
          // to append with Fee Receipt No to know which fee is paid by student
          switch($payFeeOf){
              case "all" :
              $receiptNo .="/All";
              break;
              
              case "academic" :
              $receiptNo .="/Acd";
              break;
              
              case "transport" :
              $receiptNo .="/Tra";
              break;
              
              case "hostel" :
              $receiptNo .="/Hstl";
              break;
              
              default :
              $receiptNo .="$payFeeOf";
              break;
          }
    
        $paddingLeft = "style='padding-left:45px'";
    
 $showPrevPayment = "<tr>
                        <td class='dataFont' $paddingLeft ><b>Prev. Payment</b></td>
                        <td class='dataFont'><b>&nbsp;:&nbsp;</b></td>
                        <td class='dataFont' align='right' ><PrevPayment></td>
                     </tr>";
                     
$showBalanceAdvance = "<tr>
                        <td class='dataFont' $paddingLeft ><b><BalanceAdvanceText></b></td>
                        <td class='dataFont'><b>&nbsp;:&nbsp;</b></td>
                        <td class='dataFont' align='right' ><BalanceAdvance></td>
                     </tr>";                     

$showPrevFine = "<tr>
                        <td class='dataFont' $paddingLeft ><b>Prev. Fine</b></td>
                        <td class='dataFont'><b>&nbsp;:&nbsp;</b></td>
                        <td class='dataFont' align='right' ><PrevFine></td>
                     </tr>";



  $feeContent = '';
  $totalAmount =0;
  $cnt = 1;
  $feeHeadIdArray = $REQUEST_DATA['chb'];
  $feeAmountArray = $REQUEST_DATA['feeAmount'];
  $feeSlipDetail = '';	
  for($i=0;$i<count($feeHeadIdArray);$i++) {
  	   $headNameArray = explode('!~!~!',$feeHeadIdArray[$i]);   
  	   $headName =$headNameArray[0];
  	   $cnnt =$headNameArray[1];  	
	   for($j=0;$j<count($feeAmountArray);$j++) {
	   	 if($cnnt == ($j+1))	{	   			
		   $amount = $feeAmountArray[$j];
	     } 	   		
	   }	   
 		$feeContent .="<tr>
 					<td class='dataFont'  style='padding:4px 0px 0px 4px' ><strong>".$cnt."</strong></td> 
                    <td class='dataFont'  style='padding:4px 0px 0px 4px' ><strong>$headName</strong></td> 
                    <td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$amount, 2, '.', '')."</td>
                   </tr>";       
           $totalAmount +=$amount;
           $cnt++;	           
       if($headName!=''){	   
         if($feeSlipDetail!='')  {
           $feeSlipDetail.= "~~" ;          
         }         
   		 $feeSlipDetail .= $headName."!~!!~!".$amount;
       }
   }

  
    $result = $studentFeeManager->insertFeeSlipGenerate($studentId,$feeClassId,$feeSlipDetail,$comments,$paymentMode,$ddNo,$ddDate,$ddBankName);
    if($result===false){
      echo "Some Thing Went Wrong !!!";
      die;
    }  		
		
    $balance= 0;
    $totalFeePaid =0;
    $caption  = "Total ";
    if(($feeDataArray[0]['concession'] == 0) && ($payFeeOf == 'all' || $payFeeOf == 'academic')){
       $caption = "Payable Amount";
    }
 
    $feeContent .=" <tr>
                      <td class='dataFont' colspan = 2  style='padding:4px 0px 0px 4px' ><strong>$caption</strong></td> 
                      <td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$totalAmount, 2, '.', '')."</td>
                   </tr>";

    
    $condition = " AND frm.studentId = '$studentId'  AND frm.feeClassId = '$feeClassId' AND frd.feeType = '$feeType' ";
    
    
    $prevFeeDetailArray = $studentFeeManager->getStudentPreviousFeeDetails($condition);
    for($i=0;$i<count($prevFeeDetailArray);$i++) {
       $totalFeePaid += ($prevFeeDetailArray[$i]['DDAmount'] + $prevFeeDetailArray[$i]['checkAmount'] + $prevFeeDetailArray[$i]['cashAmount']);
    }
    
    if($totalFeePaid>0 && $totalAmount!=0) {
         $feeContent .=" <tr>
                      <td class='dataFont' colspan = 2  style='padding:4px 0px 0px 4px' ><strong>Prev. Paid Amount</strong></td> 
                      <td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$totalFeePaid, 2, '.', '')."</td>
                   </tr>";
    
        $balance = $totalAmount - $totalFeePaid;
        $totalAmount = $balance;
        $feeContent .=" <tr>
                          <td class='dataFont' colspan = 2  style='padding:4px 0px 0px 4px' ><strong>Balance</strong></td> 
                          <td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$totalAmount, 2, '.', '')."</td>
                       </tr>";
    } 
                   
                           
    $logoPath = '';
    $logo = $_SESSION['InstituteLogo'];

    if($logo != ''){
        $logoPath =IMG_HTTP_PATH."/Institutes/".$logo."?yy=".rand(0,1000);
    }
    else{
        $logoPath = IMG_HTTP_PATH."/logo.gif";
    }
                        

    $num = new NumberToWord($totalAmount);
    $num1 = trim(ucwords(strtolower($num->word)));
    if($num1!='') {
      $num1 .=" Only";  
    }
     if($feeDataArray[0]['bankAddress'] ==''){
         $feeDataArray[0]['bankAddress'] ='---';
     }
     $receiptData="<table width='100%' border='0px' cellpadding='0px' cellspacing='0px' >
                 <tr class='dataFont'>
                    <td class='dataFont' colspan=2 style='padding-top:4px'><b>Date&nbsp;:</b>&nbsp;".date('d-m-y')." 
                        <span style='display:none;float:right'><b>Receipt / Scroll No</b>.......................</span>
                        <span style='float:right;'>
                        <b>&nbsp;<SlipCopyName></b></span>
                    </td>                
                 </tr>
                  <tr class='dataFont'>
                    <td class='dataFont' colspan=2 style='padding-top:4px'>
                        <b>Bank Name&nbsp;:&nbsp;</b>".$feeDataArray[0]['bankAbbr']."<span style='float:right'>
                        <b>A/C No.</b>&nbsp;".$feeDataArray[0]['instituteBankAccountNo']."</span></td> 
                    </tr>
                  
                   <tr class='dataFont' style='display:none'>
                    <td class='dataFont' colspan=2 style='padding-top:4px'>
                        <table width='100%' border=0 cellspacing=0 cellpading=0>
                           <tr>
                             <td valign='top' width='70px'><b>Bank Addr.&nbsp;:&nbsp;</b></td><td valign='top' >".$feeDataArray[0]['bankAddress']."</td>
                           </tr>
                        </table>
                    </td> 
                   </tr>
                   

                <tr class='dataFont'>
                     <td align='left' colspan='2' style='padding-top:10px'>
                        <table width='100%' border='0px' cellpadding='0px' cellspacing='0px' >
                          <tr>
                             <td align='left'>
                               <img src='$logoPath' width='200' height='60' border=0>       
                             </td> 
                          </tr>  
                        </table>
                     </td>
                 </tr> 
                 <tr class='dataFont'>
                     <td colspan='2' align='center' style='padding-top:10px'><b>FEE RECEIPT</b></td>
                 </tr>
                 <tr>
                    <td class='dataFont' style='padding-top:4px;width:35%' valign='top' nowrap> 
                       <b>Student Name</b>
                    </td>
                    <td class='dataFont' style='padding-top:4px;width:65%' nowrap valign='top'><b>:</b>&nbsp;".$feeDataArray[0]['studentName']."</td>
                 </tr>
                 <tr>
                     <td class='dataFont' style='padding-top:4px' valign='top'> 
                         <b>Father's Name</b>
                     </td>
                     <td class='dataFont' style='padding-top:4px' valign='top'><b>:&nbsp;</b>".$feeDataArray[0]['fatherName']."</td>
                 </tr>
                 <tr>
                    <td class='dataFont' align='left' style='padding-top:4px' valign='top'>
                        <b>Class Name</b></td>
                     <td class='dataFont' nowrap style='padding-top:4px' valign='top'><b>:</b>&nbsp;".$feeDataArray[0]['className']."</td>
                 </tr>
                 <tr>
                    <td class='dataFont' style='padding-top:4px' nowrap align='left'>
                      <b>Reg No.</b>
                    </td>
                    <td class='dataFont' style='padding-top:4px' nowrap><b>:</b>&nbsp;".$feeDataArray[0]['regNo']."</td>
                 </tr>
                 <tr>
                    <td class='dataFont' style='padding-top:4px' nowrap align='left'>
                      <b>Roll No.</b>
                    </td>
                    <td class='dataFont' style='padding-top:4px' nowrap><b>:</b>&nbsp;".$feeDataArray[0]['rollNo']."</td>
                 </tr>";
                 
                 if($hostelDescId=='1') {
                    $receiptData .="<tr>
                                    <td class='dataFont' style='padding-top:4px' nowrap align='left'>
                                      <b>Hostel</b>
                                    </td>
                                    <td class='dataFont' style='padding-top:4px' colspan='2' nowrap><b>:</b>&nbsp;".$h1."</td>
                                 </tr>";
                 }
                 
                 if($transportDescId=='1') {
                        $receiptData .="<tr>
                                    <td class='dataFont' style='padding-top:4px' nowrap align='left'>
                                      <b>Route</b>
                                    </td>
                                    <td class='dataFont' style='padding-top:4px' colspan='2' nowrap><b>:</b>&nbsp;".$t1."</td>
                                 </tr>";
                 }          
                 
                 
       $receiptData .="<tr>
                        <td colspan='2' style='padding-top:8px'>   
                          <table width='100%' border='1px' cellpadding='1px' cellspacing='0px'> 
                           <tr>
                               <td class='dataFont' align='center' width='5%'><strong>#</strong></td>
                               <td class='dataFont'  width='60%'><strong>Particulars</strong></td>
                               <td class='dataFont' align='right' width='35%'><strong>Amount</strong></td>
                           </tr> 
                            ".$feeContent." 
                         
                        </table>
                     </td>
                 </tr>"; 

    

    $receiptData .="<tr>    
                <td class='dataFont'  nowrap align='left'>
                                      <b>Amount (in words)</b>
                                    </td>
                               <td class='dataFont' align='left' width='5%' nowrap colspan='2'><strong> :".$num1."</strong></td>
                          
                     </tr>"; 
                 /*                
                 if($hostelDescId=='1') {
                    $receiptData .="<tr><td class='dataFont' colspan='2' height='16px'></td></tr>
                                    <tr><td class='dataFont' colspan='2' valign='top'>$hostelDesc</td></tr>";
                 }
                 
                 if($transportDescId=='1') {
                     $receiptData .="<tr><td class='dataFont' colspan='2' height='16px'></td></tr> 
                                     <tr><td class='dataFont' colspan='2' valign='top'>$transportDesc</td></tr>";
                 }
                 */
                 $receiptData .="<tr><td class='dataFont' colspan='2' height='6px'></td></tr>
                 <tr><td class='dataFont' colspan='2' align='left'><b><u></u></b></td></tr>
                 <tr><td class='dataFont' colspan='2' height='6px'></td></tr>   
                 
                 <tr>
                    <td class='dataFont'><b>Cash&nbsp;/&nbsp;DD &nbsp;&nbsp;</b></td>
                    <td class='dataFont' colspan='2'>".$paymentMode."&nbsp;</td>
                 </tr>
                  <tr>
                    <td class='dataFont' style='padding-top:5px'><nobr><b>&nbsp;DD No.&nbsp;&nbsp;</b>".$ddNo."&nbsp;</nobr></td>
                    <td class='dataFont' colspan='2'><b>&nbsp;Dated&nbsp;&nbsp;</b>".$ddDate."</td>
                 </tr>
                  <tr>
                    <td class='dataFont' style='padding-top:5px' ><b>Bank Name&nbsp;&nbsp;</b></td>
                    <td class='dataFont' colspan='2'>".$ddBankName."</td>
                 </tr>
                 <tr>
                    <td class='dataFont' colspan='3' style='padding-top:5px'></td>
                 </tr>  
                 <tr>
                   <td  valign='bottom' class='dataFont' colspan=3 style='padding-top:40px'><b>Depositor's Singnature</b> <span  style='float:right'>  <b>Authorised Signatory</b></span></td> 
                 </tr>                       
                 <tr>
                    <td  valign='bottom' class='dataFont' colspan=3 style='padding-top:10px;font-weight: normal; font-size: 9px; FONT-FAMILY: Arial, Helvetica, sans-serif; '>
                     <b><i>Computerized Generate Slip so please pay on same date</i></b>
                    </td>
                 </tr>
              </table>";

  
                  
    echo $paymentReceiptPrint = "<table width='98%' border='0px' cellpadding='0px' cellspacing='0px' align='center'>
               <tr>
                 <td width='30%'>".str_replace('<SlipCopyName>',$slipCopyNameArray[1],$receiptData)."</td>
                 <td width='5%' align='center'><img src=".STORAGE_HTTP_PATH."/Images/cut.png alt= ''></td>
                 <td width='30%'>".str_replace('<SlipCopyName>',$slipCopyNameArray[2],$receiptData)."</td>
                <td width='5%' align='center'><img src=".STORAGE_HTTP_PATH."/Images/cut.png alt ='' ></td>
                 <td width='30%'>".str_replace('<SlipCopyName>',$slipCopyNameArray[3],$receiptData)."</td>
               </tr>
               <tr>
                   <td height=20></td>
               </tr>
               <tr>
               <td colspan=5 align='right'><span id='hidePrint'><input type='image'  src=".IMG_HTTP_PATH.'/print.gif'." onClick=printout(); title=Print></span></td>
               </tr>
               </table>";

  function getGenerateFee($feeClassId,$studentId) {
         
         global $generateFeeManager;
         global $commonQueryManager;
         global $feeHeadManager;
         global $sessionHandler;
         
         if($feeClassId=='') {
           $feeClassId='0';
         }
         
         if($studentId=='') {
           $studentId='0';
         }
         
         $userId = $sessionHandler->getSessionVariable('UserId');
         $errorMessage =''; 
         
         
         $feeCycleCondition = " classId = '$feeClassId' ";
         $feeCycleArray = $generateFeeManager->checkStudentFeeCycle($feeCycleCondition);
         if(count($feeCycleArray) > 0){
            $feeCycleId = $feeCycleArray[0]['feeCycleId'];
         }
    
         // to fetch Current class of student
         $classArray = $generateFeeManager->getClass($feeClassId);
         if(count($classArray) == 0){
           return  "Class Not Found";
        }
        
        // Fetch the all Classes 
        $classes = '';
        foreach($classArray as $key => $value){
          if($classes == ''){
            $classes = $value['classId'];
          }
          else{
            $classes .= ",".$value['classId'];
          }
        } 
        $feeStudyPeriodId = $classArray[0]['feeStudyPeriodId'];
    
    
        if(SystemDatabaseManager::getInstance()->startTransaction()){

            // To Delete old fee heads
            $oldFeeHeadDelete = $generateFeeManager->checkStudentFeeHeadDelete($studentId,$feeClassId);
            if($oldFeeHeadDelete===false) {
               echo FAILURE;
               die;
            }
            
            // Fetch Migration Fee  Start
                $migrationArray = $generateFeeManager->getCheckStudentMigration($studentId);
                if(count($migrationArray) > 0 && is_array($migrationArray)) {
                  $ttIsMigrationId=$migrationArray[0]['migrationStudyPeriod'];
                }
                if($ttIsMigrationId=='') {
                  $ttIsMigrationId='0';  
                }
                
                $ttPeriodValue='-1';  
                if($ttIsMigrationId>0) {
                   $migrationPeriodArray = $generateFeeManager->getMigrationStudyPeriod($feeClassId);
                   $ttPeriodValue = $migrationPeriodArray[0]['periodValue'];
                   if($ttPeriodValue=='') {
                     $ttPeriodValue='-1';  
                   }
                }
                if($ttIsMigrationId==$ttPeriodValue) {
                  $ttIsMigrationId=1; 
                }
                else {
                  $ttIsMigrationId=0;   
                }
            // Migration Fee END    
                

            // to Get Student Details
            $condition1 = " AND studentId = '$studentId' ";
            $condition2 = " AND stu.studentId = '$studentId' ";
            $studentDataArray = $generateFeeManager->getStudentDetailsNew($classes,$condition1,$condition2);
            if(count($studentDataArray) == 0 || !is_array($studentDataArray)) {
               return "Students Not Found";  
            }

            $j=1;
            foreach($studentDataArray as $key =>$studentArr) {
                $currentClass = $studentArr['classId'];  
                $instituteId =  $studentArr['instituteId'];  
                $instituteAbbr =  $studentArr['instituteAbbr'];  
                
                $concession = '';
                $hostelFees='';
                $transportFees='';
                $busRouteId   = '';
                $busStopId = '';
                $feeReceiptId = '';
                $totalAcademicFee =0;
                $hostelSecurity = 0;
             // to get Student Concession                   
             
                
                $adhocCondition =" acm.feeClassId = '".$feeClassId."' AND acm.studentId = '".$studentArr['studentId']."'"; 
                $adhocArray=$generateFeeManager->getStudentAdhocConcessionNew($adhocCondition);
                $concession = $adhocArray[0]['adhocAmount']; // concession Amount
                $isMigration ='-1';
                if($studentArr['isMigration'] == 1 && $ttIsMigrationId == 1){
                  $isMigration = 3;
                }
                 // to get Student Fee Heads
                 $foundArray = $generateFeeManager->getStudentFeeHeadDetail($feeClassId,$studentArr['quotaId'],$studentArr['isLeet'],$studentArr['studentId'],$isMigration);
                 if(count($foundArray) == 0){
                    return FEE_HEAD_NOT_DEFINE;
                 }
                 
                 $feeArray = array();
                 $applicableHeadId = array();
                 $index = array();
                
                 // code to find only applicable Head Value 
                 foreach($foundArray as $key =>$subArray) {
                    if(!in_array($subArray['feeHeadId'],$applicableHeadId)){
                           $flag1 = true; // used for filtering purpose
                       }  
                    $flag= true;
                    foreach($foundArray as $key1 =>$subArray1){
                           if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $subArray['isLeet'] == 3)&& (($subArray['quotaId'] == $studentArr['quotaId']) && $subArray['isLeet'] == $isMigration)){
                               $flag = true;
                               foreach($applicableHeadId as $key2 => $value){
                                   if($value == $subArray['feeHeadId']){
                                       $applicableHeadId[$key2] = $subArray['feeHeadId'];
                                       $index[$key2] = $key;
                                       $flag= false;
                                   }    
                               }
                               if($flag){
                                   $applicableHeadId[] = $subArray['feeHeadId'];
                                   $index[] = $key;
                               }
                               break;
                           }
                           else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $subArray['isLeet'] == 1) && (($subArray['quotaId'] == $studentArr['quotaId']) && ($subArray['isLeet'] == $studentArr['isLeet']))){ 
                               $flag = true;
                               foreach($applicableHeadId as $key2 => $value){
                                   if($value == $subArray['feeHeadId']){
                                       $applicableHeadId[$key2] = $subArray['feeHeadId'];
                                       $index[$key2] = $key;
                                       $flag= false;
                                   }    
                               }
                               if($flag){
                                   $applicableHeadId[] = $subArray['feeHeadId'];
                                   $index[] = $key;
                               }
                               break;
                           }
                           else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $flag1 == true) && (($subArray['quotaId'] == 0) && $subArray['isLeet'] == $isMigration)){ 
                               $flag = true;
                               foreach($applicableHeadId as $key2 => $value){
                                   if($value == $subArray['feeHeadId']){
                                       $applicableHeadId[$key2] = $subArray['feeHeadId'];
                                       $index[$key2] = $key;
                                       $flag= false;
                                   }    
                               }
                               if($flag){
                                   $applicableHeadId[] = $subArray['feeHeadId'];
                                   $index[] = $key;
                               }
                               break;
                           }
                           else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && !in_array($subArray['feeHeadId'],$applicableHeadId)) && (($subArray['quotaId'] == $studentArr['quotaId']) || (($subArray['isLeet'] == $studentArr['isLeet']) || $subArray['isLeet'] == $isMigration))){ 
                               $applicableHeadId[] = $subArray['feeHeadId'];
                               $index[] = $key;
                               break;
                           
                           }
                    }              
                  }
          
                  $applicableHeadId = array_unique($applicableHeadId); 

                  // to put other heads 
                  foreach($foundArray as $key =>$subArray){
                    if(!in_array($subArray['feeHeadId'],$applicableHeadId)){
                        $feeArray[$key] = $foundArray[$key];
                    }
                  }
                  // to insert aplicable head at there place
                  $index = array_unique($index);
                  foreach($index as $key =>$value){
                    $feeArray[$value] = $foundArray[$value];
                  }
                  // this is done to mantain the order of fee it stores the key
                  $indexArr = array();
                  foreach($feeArray as $key =>$value){
                    $indexArr[] = $key;
                  }
                  sort($indexArr); // to sort the index
            
                   $studentId = $studentArr['studentId'];
                $feeReceiptId = '';
                $feeReceiptArray= $generateFeeManager->getFeeMasterId($studentId,$feeClassId);
                if(count($feeReceiptArray) > 0 ) {
                  $feeReceiptId = $feeReceiptArray[0]['feeReceiptId'];
                }
                $status = $generateFeeManager->insertIntoFeeMaster($studentId,$currentClass,$feeClassId,$feeCycleId,$concession);
                if($status === FALSE){
                    return FALIURE;
                }
                if($feeReceiptId=='') {
                  $feeReceiptId=SystemDatabaseManager::getInstance()->lastInsertId();
                }
                
                $cnt = count($indexArr);
                $instrumentValues = '';
                for($i=0;$i<$cnt; $i++){
                    //feeReceiptInstrumentId,feeReceiptId,studentId,classId,feeHeadId,feeHeadName,amount,feeStatus
                    if($feeArray[$indexArr[$i]]['feeHeadAmt'] > 0) {     
                        if($instrumentValues != ''){
                            $instrumentValues .=", ";
                        }
                        $instrumentValues .="('','$feeReceiptId','$studentId','$feeClassId','".$feeArray[$indexArr[$i]]['feeHeadId']."','".ucwords($feeArray[$indexArr[$i]]['headName'])."','".$feeArray[$indexArr[$i]]['feeHeadAmt']."',0)";
                        $totalAcademicFee += floatval($feeArray[$indexArr[$i]]['feeHeadAmt']);
                        $totalAcademicFee = " ".$totalAcademicFee;
                    }
                }
        
                $status1 = $generateFeeManager->insertIntoReceiptInstrument($instrumentValues);
                if($status1 === FALSE){
                    return FALIURE;
                }
                $j++;
               }
            if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                $msg = SUCCESS; 
            }
            else {
               $msg = FAILURE;
            }
        }
        else {
          $msg = FAILURE;
        }
        return $msg; 
    }
?>             
        
     </body>
        </html>

