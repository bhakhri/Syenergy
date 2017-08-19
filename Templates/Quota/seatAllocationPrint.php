<?php
//This file is used as printing version for SMS
//
// Author :Parveen Sharma
// Created on : 26-11-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php                
    require_once(BL_PATH . "/UtilityManager.inc.php");                    
    define('MODULE','SeatAllocationReport');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once($FE . "/Library/HtmlFunctions.inc.php");
    $htmlManager  = HtmlFunctions::getInstance();
    
    require_once(MODEL_PATH . "/QuotaManager.inc.php");
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

   
    // Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'branchName';
    $orderBy = " $sortField $sortOrderBy";
    
    
    global $sessionHandler; 
    
    $allocationDate = $sessionHandler->getSessionVariable('AllocationDate');
    $classId = $sessionHandler->getSessionVariable('AllocationClassId');
    $quotaId = $sessionHandler->getSessionVariable('AllocationQuotaId'); 
    $roundId = $sessionHandler->getSessionVariable('AllocationRoundId'); 
    
    if($classId=='') {
      $classId = -1; 
    }
    
    if($quotaId=='') {
      $quotaId = -1; 
    }
    
    if($roundId=='') {
      $roundId = -1; 
    }
    
    $alotSeat= trim($REQUEST_DATA['alotSeat']);
    $rptSeat = trim($REQUEST_DATA['rptSeat']);
    $vcnSeat = trim($REQUEST_DATA['vcnSeat']);
   
    if($alotSeat=='') {
      $alotSeat=0;  
    }
    
    if($rptSeat=='') {
      $rptSeat=0;  
    }
    
    if($vcnSeat=='') {
      $vcnSeat=0;  
    }
    
    $conditionDate = " AND cc1.allocationDate <= '$allocationDate' ";
    
    $condition = '';
    $conditionMain = '';
    
    if($classId!='-1') {
      $condition .= " AND cc1.classId IN ($classId) ";
      $conditionMain .= " AND qs.classId IN ($classId) ";
    }
    
    if($quotaId!='-1') {
      $condition .= " AND cc2.quotaId IN ($quotaId) ";
      $conditionMain .= " AND qs.quotaId IN ($quotaId) ";  
    }
    
    if($roundId!='-1') {
       $condition .= " AND cc1.roundId IN ($roundId) ";
       $conditionMain .= " AND cc1.roundId IN ($roundId) ";  
    }

    //$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);       
    $search = "As On ".UtilityManager::formatDate($allocationDate);
    
    $foundArray = QuotaManager::getInstance()->getClassQuotaAllocationList($condition,$conditionDate,$allocationDate,$conditionMain,$orderBy); 
    $cnt = count($foundArray);
   
    $recordLimit = 30;
    $totalPages = ceil($cnt/$recordLimit);    
    $tableData ='';
    if($totalPages>0) {
      $pageCounter=1;
    }
   
    
    $tableHead = "<table border='1' cellpadding='0' cellspacing='0' width='100%' class='reportTableBorder'  align='center'>
                   <tr>
                      <td width='2%'   style='padding-left:2px'  ".$reportManager->getReportDataStyle()." ><b>#</b></td>
                      <td width='20%'  style='padding-left:2px'  ".$reportManager->getReportDataStyle()." align='left'><strong>Course Name</strong></td>
                      <td width='25%'  style='padding-left:2px'  ".$reportManager->getReportDataStyle()." align='left'><strong>Seat Type</strong></td>
                      <td width='10%'  style='padding-right:2px' ".$reportManager->getReportDataStyle()." align='right'><strong>Total Seats</strong></td>";
                   
    if($alotSeat!='0') {
      $tableHead .= "<td width='12%'  style='padding-right:2px' ".$reportManager->getReportDataStyle()." align='right'><strong>Allotted Seats</strong></td>";
    }
    
    if($rptSeat!='0') {
      $tableHead .= "<td width='14%'  style='padding-right:2px' ".$reportManager->getReportDataStyle()." align='right'><strong>Reported Seats</strong></td>";
    }
    
    if($vcnSeat!='0') {
       $tableHead .= "<td width='12%'  style='padding-right:2px' ".$reportManager->getReportDataStyle()." align='right'><strong>Vacant Seats</strong></td>";
    }                                  
    $tableHead .= "</tr>"; 
    
    $j=0;
    $find=0;
    $tableData = "";
    $branchName='';
    for($i=0;$i<$cnt;$i++) {
      if($tableData == "") {
        if($i!=0) {
          $tableData = "<br class='page'>";
        }
        $tableData = $tableHead;  
      }
      $tbranchName = $foundArray[$i]['branchName'];
      if($foundArray[$i]['branchName']==$branchName) {
        $tbranchName = ""; 
      }
      else {
         $j=0;
         if($i!=0) {
           $tableData .= getTotalSeatsValue($totalSeat);   
         }
         $totalSeat['totalSeats']=0;
         $totalSeat['allotedSeats']=0;
         $totalSeat['reportedSeats']=0;
         $totalSeat['vacantSeats'] =0;
      }
      
      $tableData .= getRecordValue($tbranchName,$foundArray[$i],$j); 
       
      $find=1;   
      $branchName = $foundArray[$i]['branchName'];          
      $j++;
       
      if(($i+1)%$recordLimit==0) {
        $tableData .= "</table>"; 
        reportGenerate($tableData,$search);
        $tableData='';  
        $branchName='';
      } 
    }
      
    if($tableData != "") {
      $tableData .= getTotalSeatsValue($totalSeat);
      $tableData .= "</table>"; 
      reportGenerate($tableData,$search);     
    }  
    
    if($find==0) {
      $tableHead .= "<tr><td ".$reportManager->getReportDataStyle()." colspan='8' align='center'>No Data Found</td></tr></table>"; 
      reportGenerate($tableHead,$search); 
    }
    
    die;
    
     // Report generate
    function reportGenerate($value,$heading) {
        
        global $reportManager;
        global $pageCounter;
        global $totalPages;
      
                          
        $reportManager = ReportManager::getInstance();
        $reportManager->setReportWidth(780);
        $reportManager->setReportHeading('Seat Allocation Report');
        $reportManager->setReportInformation($heading);      
        ?>
        <div>
            <table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
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
            <tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>  align="center"><?php echo $reportManager->getReportInformation(); ?></th></tr>
            </table> <br>
            <table border='0' cellspacing='0' width="100%" class="reportTableBorder"  align="center">
                <tr>
                    <td valign="top">
                        <?php echo $value; ?>        
                    </td>
                </tr> 
            </table>       
            <br>
            <?php
              if($totalPages!=0 ) {
            ?>
                <table border='0' cellspacing='0' cellpadding='0' width="100%">
                    <tr>
                        <td valign='' align="left"  <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
                        <td valign='' align="right" <?php echo $reportManager->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                    </tr>
                </table>
                <br class='page'>
                <?php
                 $pageCounter++;
              }
              else {
            ?>
            <table border='0' cellspacing='0' cellpadding='0' width="100%" align="center">
            <tr>
            <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
            </tr>
            </table>
            <?php
              }
            ?>
        </div>
<?php        
    }


  function getRecordValue($tbranchName,$foundArray,$j) {
       global $reportManager;  
       global $totalSeat;  
       global $alotSeat;
       global $rptSeat;
       global $vcnSeat;
       
       $vacantSeats = $foundArray['totalSeats']-$foundArray['reportedSeats'];   
       $result = "<tr>
                         <td width='2%'   style='padding-left:2px'  ".$reportManager->getReportDataStyle()." >".($j+1)."</td>
                         <td width='20%'  style='padding-left:2px'  ".$reportManager->getReportDataStyle()." align='left'>".strip_slashes($tbranchName)."</td>
                         <td width='25%'  style='padding-left:2px'  ".$reportManager->getReportDataStyle()." align='left'>".strip_slashes($foundArray['quotaName'])."</td>
                         <td width='10%'  style='padding-right:2px' ".$reportManager->getReportDataStyle()." align='right'>".strip_slashes($foundArray['totalSeats'])."</td>";

        if($alotSeat!='0') {
          $result .= "<td width='12%'  style='padding-right:2px' ".$reportManager->getReportDataStyle()."  align='right'>".strip_slashes($foundArray['allotedSeats'])."</td>";
        }
        if($rptSeat!='0') {
          $result .= "<td width='14%'  style='padding-right:2px' ".$reportManager->getReportDataStyle()."  align='right'>".strip_slashes($foundArray['reportedSeats'])."</td>";
        }
        if($vcnSeat!='0') {
          $result .= "<td width='12%'  style='padding-right:2px' ".$reportManager->getReportDataStyle()." align='right'>".strip_slashes($vacantSeats)."</td> ";
        }  
        $result .= "</tr>";             
        
        $totalSeat['totalSeats'] += $foundArray['totalSeats'];
        $totalSeat['allotedSeats'] += $foundArray['allotedSeats'];
        $totalSeat['reportedSeats'] += $foundArray['reportedSeats'];
        $totalSeat['vacantSeats']  += $vacantSeats;    
          
        return $result;              
    }
    
    
    function getTotalSeatsValue($totalSeat) {
       global $reportManager;  
       global $alotSeat;
       global $rptSeat;
       global $vcnSeat;  
       
       $result = "<tr>
                         <td colspan='3'  style='padding-right:2px'  ".$reportManager->getReportDataStyle()." align='right'><b>Total</b></td>
                         <td width='10%'  style='padding-right:2px' ".$reportManager->getReportDataStyle()." align='right'>".strip_slashes($totalSeat['totalSeats'])."</td>";
                         
       if($alotSeat!='0') {
         $result .= "<td width='12%'  style='padding-right:2px' ".$reportManager->getReportDataStyle()." align='right'>".strip_slashes($totalSeat['allotedSeats'])."</td>";
       }
       if($rptSeat!='0') {
         $result .= "<td width='14%'  style='padding-right:2px' ".$reportManager->getReportDataStyle()." align='right'>".strip_slashes($totalSeat['reportedSeats'])."</td>";
       }
       if($vcnSeat!='0') {
         $result .= "<td width='12%'  style='padding-right:2px' ".$reportManager->getReportDataStyle()." align='right'>".strip_slashes($totalSeat['vacantSeats'])."</td> ";
       }   
       $result .= "</tr>";                
       
       return $result;              
    }
    