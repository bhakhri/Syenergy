<?php 
    //This file is used as printing version for student ICard.
    //
    // Author :Parveen Sharma
    // Created on : 26-12-2008
    // Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    require_once(MODEL_PATH . "/InstituteManager.inc.php");
    require_once(BL_PATH . '/ReportManager.inc.php');
    require_once(TEMPLATES_PATH . "/Icard/busPassEmployeeTemplate.php");  
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    
    define('MODULE','EmployeeIcardReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
 
    global $sessionHandler;    

    $employeeManager = EmployeeManager::getInstance();
    $commonQueryManager = CommonQueryManager::getInstance();
    $reportManager = ReportManager::getInstance();
    $instituteManager = InstituteManager::getInstance(); 
    
    $cardView = $REQUEST_DATA['cardView'];          
    
    foreach($REQUEST_DATA as $key => $values) {
      $$key = $values;
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
    $orderBy = "$sortField $sortOrderBy";
    
    $employee = $REQUEST_DATA['employee'];
    if($employee=='') {
      $employee=0;  
    }
    
    $conditions1 = " AND empBus.status = 1"; 
    $conditions .= " WHERE emp.isActive = 1 AND emp.employeeId IN ($employee) AND empBus.status=1"; 
    $recordArray = $employeeManager->getEmployeeBusPassList($conditions,'',$orderBy,$conditions1); 
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
        <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center">Employee Bus Pass Report</th></tr>
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
        $icardData1 = str_replace("<employeeName>",$recordArray[$i]['employeeName'],$icardData1);
        $icardData1 = str_replace("<employeeCode>",$recordArray[$i]['employeeCode'],$icardData1);
        $icardData1 = str_replace("<department>",$recordArray[$i]['departmentAbbr'],$icardData1);
       
        $busPassLogo = IMG_PATH."/BusPass/".$sessionHandler->getSessionVariable('BUS_PASS_LOGO');    
        if(file_exists($busPassLogo)) {
            $fileName = IMG_HTTP_PATH."/BusPass/".$sessionHandler->getSessionVariable('BUS_PASS_LOGO'); 
            $insLogo = "<img  src=\"".$fileName." \"height=\"45px\" \"width=\"135px\" valign=\"top\" >";
        }
        else {
            $insAddress  = $studentRecordArray[0]['instituteAddress1'].' '.$studentRecordArray[0]['instituteAddress2'].' '.$studentRecordArray[0]['pin'].'<br>'.$studentRecordArray[0]['insPhone'];
            $insWebSite  = $studentRecordArray[0]['instituteWebsite'];
            $insEmail   = $studentRecordArray[0]['instituteEmail'];   
            $fileName = IMG_PATH."/Institutes/".$studentRecordArray[0]['instituteLogo']; 
            $insLogo='';
            if($instRecordArray[0]['instituteLogo']=='') {
               $insLogo = "<img class='bborder' src=\"".IMG_HTTP_PATH."/notfound.jpg \"height=\"45px\" width=\"135px\" \"valign=\"top\" >";  
            }
            else 
            if(file_exists($fileName)) {
               $insLogo = "<img  src=\"".IMG_HTTP_PATH."/Institutes/".$studentRecordArray[0]['instituteLogo']." \"height=\"45px\" \"width=\"135px\" valign=\"top\" >";
            }
            else {
               $insLogo = "<img class='bborder' src=\"".IMG_HTTP_PATH."/notfound.jpg \"height=\"450px\" width=\"1355px\" \"valign=\"top\" >";  
            }
        }  
       
        $icardData1 = str_replace("<INSTLOGO>",$insLogo,$icardData1); 
        
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
        
        
        if($recordArray[$i]['routeCode']=='') {
          $routeCode= NOT_APPLICABLE_STRING;  
        }
        else {
          $routeCode =$recordArray[$i]['routeCode'];
        }
        
        if($recordArray[$i]['busNo']!='') {
          $routeCode .= " (".$recordArray[$i]['busNo'].")" ;  
        }
        
        if($recordArray[$i]['stopName']=='') {
          $stopName= NOT_APPLICABLE_STRING;  
        }
        else {
          $stopName=$recordArray[$i]['stopName'];  
        }
        
        $valid = NOT_APPLICABLE_STRING;
        if($recordArray[$i]['validUpto']!='0000-00-00') {
          $valid = UtilityManager::formatDate($recordArray[$i]['validUpto']);
        }
        
        $busDetail = '<table border="0" cellpadding="1px" cellspacing="1px" width="75%" align="center">
                        <tr>
                            <td valign="top" width="37%" class="icardContent" align="left"><b>Route</b></td>
                            <td valign="top" width="3%" class="icardContent" align="left"><b>:&nbsp;</b></td>
                            <td valign="top" width="60%" class="icardData" align="left" >'.$routeCode.'</td>
                        </tr>
                        <tr>
                            <td valign="top" class="icardContent" align="left"><b>Stoppage</b></td>
                            <td valign="top" width="3%" class="icardContent" align="left"><b>:&nbsp;</b></td>
                            <td valign="top" class="icardData" align="left" >'.$stopName.'</td>
                        </tr>
                        <tr>
                            <td valign="top" class="icardContent" align="left"><b>Valid Upto</b></td>
                            <td valign="top" width="3%" class="icardContent" align="left"><b>:&nbsp;</b></td>
                            <td valign="top" class="icardData" align="left" >'.$valid.'</td>
                        </tr>
                     </table>';
        
        $icardData1 = str_replace("<BusPassDetail>",$busDetail,$icardData1);
        
        
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
// $History: busPassReportPrint.php $
//
//*****************  Version 13  *****************
//User: Parveen      Date: 2/01/10    Time: 2:19p
//Updated in $/LeapCC/Templates/Icard
//look & feel updated
//
//*****************  Version 12  *****************
//User: Parveen      Date: 12/24/09   Time: 5:20p
//Updated in $/LeapCC/Templates/Icard
//look & feel updated 
//
//*****************  Version 11  *****************
//User: Parveen      Date: 12/19/09   Time: 2:24p
//Updated in $/LeapCC/Templates/Icard
//date format setting
//
//*****************  Version 10  *****************
//User: Parveen      Date: 8/31/09    Time: 2:24p
//Updated in $/LeapCC/Templates/Icard
//busPassLogo format updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 7/06/09    Time: 2:40p
//Updated in $/LeapCC/Templates/Icard
//format & new enhancement updated (blood group added) 
//
//*****************  Version 8  *****************
//User: Parveen      Date: 7/06/09    Time: 10:31a
//Updated in $/LeapCC/Templates/Icard
//busPass bloodgroup & config base setting updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 6/23/09    Time: 3:12p
//Updated in $/LeapCC/Templates/Icard
//format update
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/22/09    Time: 4:13p
//Updated in $/LeapCC/Templates/Icard
//issue fix Format, validation added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/22/09    Time: 2:29p
//Updated in $/LeapCC/Templates/Icard
//formating, validations, messages, conditions  changes 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/16/09    Time: 6:23p
//Updated in $/LeapCC/Templates/Icard
//condition update routeCode
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/16/09    Time: 3:10p
//Updated in $/LeapCC/Templates/Icard
//date format & class name formatting
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/15/09    Time: 12:34p
//Updated in $/LeapCC/Templates/Icard
//validation, conditions & formatting updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/15/09    Time: 11:21a
//Created in $/LeapCC/Templates/Icard
//file added
//

?>