<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR feed back grades
// Author :Dipanjan Bhattacharjee
// Created on : (12.1.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(BL_PATH.'/HtmlFunctions.inc.php');
require_once(MODEL_PATH . "/EmployeeLeaveAuthorizerManager.inc.php");
$instituteId=$sessionHandler->getSessionVariable('InstituteId');

global $sessionHandler;
$leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');

if(trim($leaveAuthorizersId)=='') {
  $leaveAuthorizersId=1;
}

//Get Employee Leave Authorizer List
$empLeaveAuthorizerArray= EmployeeLeaveAuthorizerManager::getInstance()->getLeaveAuthorizerEmployee();
$empLeaveAuthorizerString='';
$empLeaveAuthorizerString ="<option value=''>Select</option> ";
if(count($empLeaveAuthorizerArray)>0 and is_array($empLeaveAuthorizerArray)) {
   foreach($empLeaveAuthorizerArray as $emp) {
      $empLeaveAuthorizerString .='<option value="'.$emp['employeeId'].'">'.$emp['employeeName'].'</option>';
   }
}



//get all emp-leave type mapping
$empLeaveTypeArray= EmployeeLeaveAuthorizerManager::getInstance()->getLeaveTypesForEmployees();
$empLeaveTypeString='';
$empLeaveTypeString ="<option value=''>Select</option> ";
if(count($empLeaveTypeArray)>0 and is_array($empLeaveTypeArray)){
   foreach($empLeaveTypeArray as $emp){
     $empLeaveTypeString .='<option value="'.$emp['employeeId'].'~'.$emp['leaveTypeId'].'">'.$emp['leaveTypeName'].'</option>';
   }
}
$employeeListString = "<option value=''>Select</option> ";
$employeeListString = HtmlFunctions::getInstance()->getEmployeeFullName('',' AND instituteId='.$instituteId);

?>


<input type="hidden" readonly="readonly" id="hiddenLeaveAuthorizersId" name="hiddenLeaveAuthorizersId" value="<?php echo $leaveAuthorizersId; ?>" >
<select name="hiddenAuthorizedEmployeeId" id="hiddenAuthorizedEmployeeId" style="display:none;">
    <?php
        echo $empLeaveAuthorizerString;
    ?>
</select>

<select name="hiddenEmpId" id="hiddenEmpId" style="display:none;">
    <?php
      echo $employeeListString;
    ?>
</select>

<select name="hiddenLeaveTypeId" id="hiddenLeaveTypeId" style="display:none;">
    <?php
        echo $empLeaveTypeString;
    ?>
</select>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
        </td>
    </tr>

    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <form name="allDetailsForm" id="allDetailsForm" action="" method="post" onSubmit="return false;">

                 <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
                    <td class="contenttab_border" height="20">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                            <td class="content_title">Employee Leave Authorizer Detail : </td>
                            <td class="content_title"></td>
                        </tr>
                        </table>
                    </td>
                 </tr>
                 <tr>
                    <td class="contenttab_row" valign="top" >
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td class="padding"><a href="javascript:addOneRow();" title="Add One Record"><b>+ Add One Row</b></a></td>
                     </tr>
                 <tr>
                  <td>
                   <div id="containerDiv" style="">
                        <table id="resourceDetailTable" border="0" cellpadding="0" cellspacing="1" style="width:100%;">
                         <tbody id="resourceDetailTableBody">
                          <tr  class="rowheading">
                           <th align="left" width="3%">#</th>
                           <th width="20%" align="left" style="padding-left:3px;">Employee</th>
                           <th width="20%" align="left" style="padding-left:3px;">First Authorizer</th>
                           <?php
                              if($leaveAuthorizersId==2) {
                                echo '<th width="20%" align="left" style="padding-left:3px;">Second Authorizer</th>';
                              }
                           ?>
                           <th width="20%" align="left" style="padding-left:3px;">Leave Type</th>
                           <th align="right" width="2%" style="padding-right:3px;" class="searchhead_text"></th>
                          </tr>
                         </tbody>
                        </table>
                   </div>
                   </td>
                   </tr>
                   <tr>
                     <td class="padding"><a href="javascript:addOneRow();" title="Add One Record"><b>+ Add One Row</b></a></td>
                   </tr>
                   </table>
                 </td>
              </tr>
              <tr><td height="5px"></td></tr>
              <tr>
               <td align="center" id="saveTrId" style="display:none">
                 <INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/save.gif" border="0" onClick="return employeeLeaveAuthorizer();">
              </td></tr>
              <tr>
               <td align="right">
                 <!--<INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0" onClick="printReport()">&nbsp;<INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0" onClick="javascript:printCSV();">-->
              </td></tr>
              </table>


            </form>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>

<?php
// $History: listFeedbackAdvOptionsContents.php $
?>