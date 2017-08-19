<script>
    //alert(location.search);
    var str=unescape(location.search);
    var strArray=str.split('icardTitle~12=');
    var len=strArray.length;
    var icardTitle=strArray[1];
</script>
<?php 
    //This file is used as printing version for student ICard.
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
    define('MODULE','EmployeeIcardReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
 

    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    $employeeManager = EmployeeManager::getInstance();
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

	 $joiningDateCard = $REQUEST_DATA['joiningDateCard'];
	 $emailCard = $REQUEST_DATA['emailCard'];
	 $issueDateCard = $REQUEST_DATA['issueDateCard'];

    require_once(TEMPLATES_PATH . "/Icard/icardEmployeeTemplate.php");      
    
    global $sessionHandler;    
    
    
    //print_r($REQUEST_DATA);
   
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
    $orderBy = "$sortField $sortOrderBy";         
    
    $employeeId = add_slashes($REQUEST_DATA['employeeId']);
    
    if($employeeId=='') {
       $employeeId = 0; 
    }
    
    $conditions =  " WHERE emp.employeeId IN ($employeeId) ";          

    $setIssueDate = $REQUEST_DATA['setIssueDate'];
    
    // Update Issue Date 
    $recordArray = $employeeManager->editIcardIssueDate($setIssueDate,$employeeId);  
    if($returnStatus === false) {
      echo FAILURE;
      die;
    }
     
    $recordArray = $employeeManager->getIcardEmployeeList($conditions,$limit,$orderBy);  
    $cnt = count($recordArray);
    
    if($cnt==0)  { 
?>    <table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
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
        <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center">Employee I-Card Report</th></tr>
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
       die;
    }
    else  {
     for($i=0;$i<$cnt;$i++) {
	    $icardData1 = $icardData; 
        $valid =  UtilityManager::formatDate($recordArray[$i]['endDate']);

/*
        $sp=0;
        $strIcardTitle = $REQUEST_DATA['icardTitle'];
        for($s=0; $s<strlen($strIcardTitle); $s++) {
           if(substr($strIcardTitle,$s,1)==' ') {
             $sp=1; 
             break;
           }
        }
        if($sp==0) {
          $temp = chunk_split($strIcardTitle,22);
        }
        else {
           $temp = $strIcardTitle;  
        }
*/

        if($recordArray[$i]['issueDate'] == '0000-00-00') {
          $issueDate  = NOT_APPLICABLE_STRING;
        }
        else {                                
          $issueDate = UtilityManager::formatDate($recordArray[$i]['issueDate']);
        }
        
        $icardData1 = str_replace("<reportHeading>","<script>unescape(document.write(icardTitle));</script>",$icardData1);
        $icardData1 = str_replace("<employeeName>",$recordArray[$i]['employeeName'],$icardData1);
        $icardData1 = str_replace("<employeeCode>",$recordArray[$i]['employeeCode'],$icardData1);
        $icardData1 = str_replace("<department>",$recordArray[$i]['departmentAbbr'],$icardData1);
        $icardData1 = str_replace("<emailAddress>",$recordArray[$i]['emailAddress'],$icardData1); 
        $icardData1 = str_replace("<issueDate>",$issueDate,$icardData1); 
       
        global $bloodResults; 
        $bloodGroup =NOT_APPLICABLE_STRING;
        if($recordArray[$i]['bloodGroup']!=NOT_APPLICABLE_STRING) {
          $id = $recordArray[$i]['bloodGroup'];  
          $bloodGroup = $bloodResults[$id];
        }
        $icardData1 = str_replace("<BloodGroup>",$bloodGroup,$icardData1);
        
        if($recordArray[$i]['dateOfJoining']=='0000-00-00') {
          $icardData1 = str_replace("<DOJ>",NOT_APPLICABLE_STRING,$icardData1);
        }
        else {
          $icardData1 = str_replace("<DOJ>",UtilityManager::formatDate($recordArray[$i]['dateOfJoining']),$icardData1);            
        }
        $icardData1 = str_replace("<designationName>",$recordArray[$i]['designationName'],$icardData1);
        if($recordArray[$i]['contactNumber']=='') {
           $icardData1 = str_replace("<contactNo>",NOT_APPLICABLE_STRING,$icardData1);
        }
        else {
           $icardData1 = str_replace("<contactNo>",$recordArray[$i]['contactNumber'],$icardData1);
        }
        if($recordArray[$i]['permAddress']=='') {
           $icardData1 = str_replace("<address>",NOT_APPLICABLE_STRING,$icardData1);            
        }
        else {
           $icardData1 = str_replace("<address>",$recordArray[$i]['permAddress'],$icardData1);
        }
        
        if($recordArray[$i]['employeeImage']=='') {
           $img = "<img class='bborder' src=".IMG_HTTP_PATH."/notfound.jpg  height='135px' width='110px' valign='middle' />";   
        }
        else {
            $img = "<img class='bborder' src=".IMG_HTTP_PATH."/Employee/".$recordArray[$i]['employeeImage']." height='135px' width='110px' valign='middle' />"; 
        } 
        $icardData1 = str_replace("<employeePhoto>",$img,$icardData1);

        echo $icardData1;
        if(($i+1)%2==0) {
          echo '<br class="page">';
        }
      }    // end for loop 
    }                  

?>                 

<?php    
// $History: icardEmployeeReportPrint.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/22/09   Time: 6:20p
//Updated in $/LeapCC/Templates/Icard
//date format & alignement updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/21/09   Time: 6:59p
//Updated in $/LeapCC/Templates/Icard
//urlencode function added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/15/09   Time: 1:20p
//Updated in $/LeapCC/Templates/Icard
//validation & look & feel update (Employee Filter)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/01/09   Time: 3:26p
//Updated in $/LeapCC/Templates/Icard
//icard title added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/10/09    Time: 2:09p
//Created in $/LeapCC/Templates/Icard
//initial checkin
//
//*****************  Version 7  *****************
//User: Parveen      Date: 5/18/09    Time: 5:31p
//Updated in $/Leap/Source/Templates/ScICard
//valid upto field add
//
//*****************  Version 6  *****************
//User: Parveen      Date: 1/13/09    Time: 3:43p
//Updated in $/Leap/Source/Templates/ScICard
//icard template css settings
//
//*****************  Version 5  *****************
//User: Parveen      Date: 1/13/09    Time: 2:33p
//Updated in $/Leap/Source/Templates/ScICard
//
//*****************  Version 4  *****************
//User: Parveen      Date: 1/07/09    Time: 4:45p
//Updated in $/Leap/Source/Templates/ScICard
//template base code settings
//
//*****************  Version 3  *****************
//User: Parveen      Date: 1/05/09    Time: 5:48p
//Updated in $/Leap/Source/Templates/ScICard
//template base settings
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/27/08   Time: 4:26p
//Updated in $/Leap/Source/Templates/ScICard
//checkbox added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/26/08   Time: 4:29p
//Created in $/Leap/Source/Templates/ScICard
//initial checkin
//
//

?>