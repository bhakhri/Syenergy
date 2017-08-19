<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<?php  
  global $sessionHandler;
  $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');  
  if($leaveAuthorizersId=='') {
     $leaveAuthorizersId=1;  
  }
  
  $pendingStatus = "";
  if($leaveAuthorizersId==2) {
    $pendingStatus = 'onclick="togglePendingStatus(this.value);"';  
  }
?>
    <input type="hidden" readonly="readonly" name="hiddenLeaveAuthorizersId" id="hiddenLeaveAuthorizersId" value=<?php echo $leaveAuthorizersId;?> >
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Employee Leaves Taken Report : </td>
                        <td class="content_title"></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <form name="searchForm" method="post" action="" onsubmit="return false;">
                  <table border="0" cellpadding="0" cellspacing="0">
                   <tr>
                     <td class="contenttab_internal_rows"><b>Leave Session</b></td>
                     <td class="padding">:</td>
                     <td class="padding">
                      <select name="leaveSessionId" id="leaveSessionId" class="inputbox" onchange="vanishData();" >
                       <option value="">Select</option>
                       <?php
                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           echo HtmlFunctions::getInstance()->getLeaveSessionData();
                        // echo HtmlFunctions::getInstance()->getSessionYearData();
                       ?>
                      </select>
                     </td>
                     <td class="contenttab_internal_rows"><b>Employee</b></td>
                     <td class="padding">:</td>
                     <td class="padding" colspan="5">
                      <select name="employeeDD" id="employeeDD" class="inputbox" onchange="vanishData();" >
                       <option value="-1">All</option>
                       <?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         echo HtmlFunctions::getInstance()->getTeacher('' ,1);
                       ?>
                      </select>
                     </td>
                   </tr>  
                   <tr>
                   <td class="contenttab_internal_rows"><b>Leave Status</b></td>
                   <td class="padding">:</td>
                   <td class="contenttab_internal_rows">
                    <input type="radio" name="leaveStatus" value="1" checked="checked" <?php echo $pendingStatus; ?> >Approved&nbsp;
                    <input type="radio" name="leaveStatus" value="0"  <?php echo $pendingStatus; ?> >Pending
                   </td>
                   <?php if($leaveAuthorizersId==2) { ?>
                               <td class="contenttab_internal_rows"><b>Pending Status</b></td>
                               <td class="padding">:</td>
                               <td class="padding">
                               <select name="pendingStatus" id="pendingStatus" class="inputbox" disabled="disabled" onchange="vanishData();">
                                <option value="-1">All</option>
                                <?php
                                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                   echo HtmlFunctions::getInstance()->getPendingLeaveStatusData(-1);
                                ?>
                               </select> 
                               </td>
                   <?php } ?>            
                   <td class="contenttab_internal_rows">&nbsp;&nbsp;<b>From</b></td>
                   <td class="padding">:</td> 
                   <td class="padding">
                     <?php
                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                       echo HtmlFunctions::getInstance()->datePicker('fromDate',''); 
                     ?> 
                   &nbsp;
                   To : 
                   <?php
                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                       echo HtmlFunctions::getInstance()->datePicker('toDate',''); 
                   ?>  
                   </td> 
                   <td>
                       <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/showlist.gif" onClick="generateReport();" >&nbsp;
                   </td>
                  </tr> 
                  </table>
                  </form>
                   <div id="results">  </div>           
                </td>
             </tr>
             <tr id="printTrId" style="display:none;">
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                            <td class="content_title" valign="middle" align="right" width="20%">
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
                            </td>  
                        </tr>
                    </table>
                </td>
             </tr>
          </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
    <!--Start Add Div-->

<?php
// $History: listCityContents.php $
?>