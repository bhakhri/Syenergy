<?php
//-------------------------------------------------------
// Purpose: To store the records of BankBranch in array from the database, pagination and search, delete 
// functionality
//
// Author : Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BankMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/BankBranchManager.inc.php");
    $bankBranchManager = BankBranchManager::getInstance();
    
    require_once(MODEL_PATH . "/BankManager.inc.php");
    $bankManager = BankManager::getInstance();

       /////////////////////////                           
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    //if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  AND (a.bankId = "'.add_slashes($REQUEST_DATA['id']).'")';
    //}
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'bankName';
    
     $orderBy = " $sortField $sortOrderBy";

    
    $totalArray = $bankBranchManager->getTotalBankBranch($filter);
    $bankBranchRecordArray = $bankBranchManager->getBankBranchList($filter,$limit,$orderBy);
    $save = '<a href="#" title="Save"><img src="<?php echo IMG_HTTP_PATH;?>/save.gif" border="0" alt="Save" onClick="editBranch();return false;"></a>';
    $cnt = count($bankBranchRecordArray);
    $check = '<input type="checkbox" id="checkbox2" name="checkbox2" onclick="doAll();">';
            
            
    $tableArray ="<div class='anyid' align='left'><b>Bank Name&nbsp;:</b>&nbsp;".$bankName."</div>
                  <table border='0px' width='100%' cellpadding='0px' cellspacing='2px' align='center' class='anyid' >
                   <tr class='rowheading'>  
                        <td align='left' width='2%'><b>#</b></td>
                        <td align='left' width='3%'><b>".$check."</b></td>
                        <td align='left' width='20%'><b>Branch</b></td>
                        <td align='left' width='15%'><b>Abbr.</b></td>
                        <td align='left' width='15%'><b>Account Type</b></td>
                        <td align='left' width='15%'><b>Account Number</b></td>
                        <td align='left' width='15%'><b>Operator</b></td>
                        <td align='center' width='8%'><b>Action</b></td>
                   </tr>
                    <tr>
                    <td align='right'>".$save."</td>
                    </tr>"; 
                    
                    
                    
    for($i=0;$i<$cnt;$i++) {
      // add bankId in actionId to populate edit/delete icons in User Interface
     
        
       $bankName = $bankBranchRecordArray[$i]['bankName'];
       $save = '<a href="#" title="Save"><img src="'.IMG_HTTP_PATH.'/save.gif" border="0" alt="Save" onClick="editBranch();return false;"></a>'; 
       if($i==0) {
         $tableArray ="<div class='anyid'><b>Bank Name&nbsp;:</b>&nbsp;".$bankName."</div>
                  <table border='0px' width='100%' cellpadding='0px' cellspacing='2px' align='center' class='anyid' >
                   <tr class='rowheading'>  
                        <td align='left' width='2%'><b>#</b></td>
                        <td align='left' width='3%'><b>".$check."</b></td>
                        <td align='left' width='20%'><b>Branch</b></td>
                        <td align='left' width='15%'><b>Abbr.</b></td>
                        <td align='left' width='15%'><b>Account Type</b></td>
                        <td align='left' width='15%'><b>Account Number</b></td>
                        <td align='left' width='15%'><b>Operator</b></td>
                        <td align='center' width='8%'><b>Action</b></td>
                   </tr> ";
                     
       }
       

      $id = strip_slashes($bankBranchRecordArray[$i]['bankBranchId']);
      $actionStr='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" onClick="deleteBankBranch('.$bankBranchRecordArray[$i]['bankBranchId'].');return false;"></a>';
      $checkall = "<input type='checkbox' name='chb[]' id='chb".$id."' onClick='checkDisable(".$id.")' value='".$id."'>"; 
      $branchName = "<input type='text' maxlength='100' id='branchName".$id."' name='branchName[]' value='".$bankBranchRecordArray[$i]['branchName']."'  style='width:142px' disabled />";
      $branchAbbr = "<input type='text' maxlength='10' id='branchAbbr".$id."' name='branchAbbr[]' value='".strtoupper($bankBranchRecordArray[$i]['branchAbbr'])."'  style='width:142px' disabled />";
      $accountType = "<input type='text' maxlength='20' id='accountType".$id."' name='accountType[]' value='".$bankBranchRecordArray[$i]['accountType']."'  style='width:142px' disabled />";
      $accountNumber = "<input type='text' maxlength='30' id='accountNumber".$id."' name='accountNumber[]' value='".$bankBranchRecordArray[$i]['accountNumber']."'  style='width:142px' disabled />";
      $operator = "<input type='text' maxlength='30' id='operator".$id."' name='operator[]' value='".$bankBranchRecordArray[$i]['operator']."'  style='width:142px' disabled />";
      $status = "<input type='hidden' id='status".$id."' name='status[]' value='N'  style='width:142px' />";
      $tbankBranchId = "<input type='hidden' id='tbankBranchId".$id."' name='tbankBranchId[]' value='".$id."' style='width:142px' />";
      
      $bg = $bg =='row0' ? 'row1' : 'row0'; 
      $tableArray .="<tr class=".$bg."> 
                        <td>".($i+1)."</td>
                        <td align='center'>".$checkall."</td>
                        <td>".$branchName."</td>
                        <td>".$branchAbbr."</td>
                        <td>".$accountType."</td>
                        <td>".$accountNumber."</td>
                        <td>".$operator."</td>
                        <td align='center'>".$actionStr."".$tbankBranchId."".$status."</td>
                     </tr>"; 
             
    }
    if($cnt==0) {
        $bankN=$REQUEST_DATA['id'];   
        $bankNameArray = $bankManager->getBankName1($bankN);
        $bankName = $bankNameArray[0]['bankName']. " (".$bankNameArray[0]['bankAbbr'].")";
         $tableArray ="<div class='anyid'><b>Bank Name&nbsp;:</b>&nbsp;".$bankName."</div>
                  <table border='0px' width='100%' cellpadding='0px' cellspacing='2px' align='center' class='anyid' >
                   <tr class='rowheading'>  
                        <td align='left' width='2%'><b>#</b></td>
                        <td align='left' width='3%'><b>".$check."</b></td>
                        <td align='left' width='20%'><b>Branch</b></td>
                        <td align='left' width='15%'><b>Abbr.</b></td>
                        <td align='left' width='15%'><b>Account Type</b></td>
                        <td align='left' width='15%'><b>Account Number</b></td>
                        <td align='left' width='15%'><b>Operator</b></td>
                        <td align='center' width='8%'><b>Action</b></td>
                   </tr> 
                   <tr>
                     <td colspan='8' align='center'><b>No Data Found</b> </td>
                   </tr>
                      </table>";  
    } 
    else {
        $tableArray .=" <tr>
            <td height='5px' colspan='8'></td></tr>
            <tr>
                    <tr>
                    <td align='right' colspan='8'>".$save."</td>
                    </tr>
                    </table>";
    }
    
    echo $tableArray;
    
    //echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

// $History: ajaxInitBankBranchList.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 12/29/09   Time: 12:58p
//Updated in $/LeapCC/Library/Bank
//Merged Bank & BankBranch module in single module
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/30/09    Time: 10:31a
//Created in $/LeapCC/Library/Bank
//file added
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/BankBranch
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/10/08   Time: 11:58a
//Updated in $/Leap/Source/Library/BankBranch
//add define access in module
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/23/08    Time: 5:49p
//Created in $/Leap/Source/Library/BankBranch
//File added for bank branch master

?>
