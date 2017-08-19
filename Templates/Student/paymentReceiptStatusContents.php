<?php
//-------------------------------------------------------
// Purpose: to design the layout for payment status.
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<form action="" method="post" name="listForm" id="listForm" onsubmit="return false;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td valign="top" class="title">
                <table border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td height="10"></td>
                    </tr>
                    <tr>
                       <td valign="top">
                       <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>
                       </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
           <td valign="top">
              <table width="100%" border="0" cellspacing="0" cellpadding="5" height="405">
                <tr>
                  <td valign="top" class="content" align="center">
                 <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
                    <td class="contenttab_border2" align="center">
                      <table border="0" cellspacing="2px" cellpadding="2px" width="100%" align="center">
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Fee Cycle</b></nobr></td>
                            <td class="contenttab_internal_rows"><B>:</B></td>
                            <td class="padding_top"> 
                            <select size="1" name="feeCycle" id="feeCycle"  onChange="populateValues()" class="inputbox1" style="width:220px">
                              <option value="">All</option>
                              <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getFeeCycleData();
                              ?>
                            </select></td>  
                            <td class="contenttab_internal_rows"><nobr><b>Study Period</b></nobr></td>
                            <td class="contenttab_internal_rows"><B>:</B></td>
                            <td class="padding_top">
                            <select size="1" class="inputbox1" name="studyperiod" id="studyperiod" style="width:140px">
                            <option value="">All</option>
                              <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getStudyPeriodData($REQUEST_DATA['periodName']==''? $REQUEST_DATA['studyperiod'] : $REQUEST_DATA['periodName'] );
                              ?>
                            </select>
                            </td>  
                            <td class="contenttab_internal_rows" width="11%"><nobr><b>Fee Class</b></nobr></td>
                            <td class="contenttab_internal_rows"><B>:</B></td>
                            <td class="padding_top" width="30%">
                                <select size="1" class="inputbox1" name="degree" id="degree" style="width:300px">
                                    <option value="">All</option>
                                    <?php
                                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                       echo HtmlFunctions::getInstance()->getAllFeeClass();
                                    ?>
                                    </select>
                            </td>
                          </tr>
                          <tr>  
                            <td class="contenttab_internal_rows" width="11%"><nobr><b>Batch</b></nobr></td>
                            <td class="contenttab_internal_rows"><B>:</B></td>
                            <td class="padding_top" width="20%">
                                <select size="1" class="inputbox1" name="batch" id="batch" style="width:220px">
                                <option value="">All</option>
                                  <?php
                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                      echo HtmlFunctions::getInstance()->getBatchData($REQUEST_DATA['batchName']==''? $REQUEST_DATA['batch'] : $REQUEST_DATA['batchName'] );
                                  ?>
                                </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><B>Instrument Status</B></nobr></td>
                            <td class="contenttab_internal_rows"><B>:</B></td>
                            <td class="padding_top">
                               <select size="1" name="paymentStatus" id="paymentStatus" class="inputbox1" style="width:140px">
                              <option value="">All</option>
                              <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getFeeReceiptPaymentStatus();
                              ?>
                            </select>
                            </td> 
                            <td class="contenttab_internal_rows"><B>From Date</B> </td>
                            <td class="contenttab_internal_rows"><B>:</B></td>
                            <td class="contenttab_internal_rows" style="text-align:left"><?php
                                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                   echo HtmlFunctions::getInstance()->datePicker('fromDate','');
                            ?><span style="padding-left:15px"><B>To Date&nbsp;:&nbsp;&nbsp;</B><?php
                                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                   echo HtmlFunctions::getInstance()->datePicker('toDate','');
                            ?>
                                </span>
                            </td>
                        </tr> 
                        <tr>
                        <td class="contenttab_internal_rows"><nobr><B>Receipt Status</B></nobr></td>
                            <td class="contenttab_internal_rows"><B>:</B></td>
                            <td class="padding_top">
                              <select size="1" name="receiptStatus" id="receiptStatus" class="inputbox1" style="width:220px">
                                  <option value="">All</option>
                                  <?php
                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                      echo HtmlFunctions::getInstance()->getFeeReceiptStatus('',4);
                                  ?>
                              </select>
                        </td>
                        <td class="contenttab_internal_rows"><B>Receipt No.</B></td>
                        <td class="contenttab_internal_rows"><B>:</B></td>
                        <td class="contenttab_internal_rows" style="text-align:left">
                            <input type="text" name="receiptNo" id="receiptNo" class="inputbox" style="width:135px"></nobr>  
                        </td>
                        <td class="contenttab_internal_rows"><nobr><b>Student Name</b></nobr></td>
                        <td class="contenttab_internal_rows"><B>:</B></td>
                        <td class="padding_top"><nobr>
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">   
                          <tr>
                            <td class="contenttab_internal_rows"><nobr><input type="text" name="studentName" class="inputbox" style="width:100px"></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>Roll No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><B>:&nbsp;</B></td>
                            <td class="padding_top" align="left"><nobr>
                                    <input type="text" name="studentRoll" id="studentRoll" class="inputbox"  style="width:100px">
                                    </nobr>
                            </td>
                            <td align="left">
                                <input type="hidden" name="listStudent" value="1">&nbsp;&nbsp;
                                <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateStatus();document.getElementById('saveDiv').style.display='';document.getElementById('showTitle').style.display='';document.getElementById('showData').style.display='';return false;"/>
                            </td>
                           </tr>
                           </table>
                        </td>
                    </tr>
                  </table>    
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr id='showTitle' style='display:none;'>
                            <td class="" height="20">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                    <tr>
                                        <td colspan="1" class="content_title">Fee Receipt Status :</td>
                                        <td colspan="2" class="content_title" align="right">
                                            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
                                            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printFeeStatusCSV();return false;"/>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr id='showData' style='display:none;'>
                            <td colspan='1' class='contenttab_row' width="100%">
                                <div id="scroll2" style="overflow:auto; height:420px; vertical-align:top;">
                                   <div id="results" style="width:98%; vertical-align:top;"></div>
                                </div>
                            </td>
                        </tr>
                        <tr id='nameRow2' style='display:none;'>
                            <td class="" height="20">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                    <tr>
                                        <td colspan="2" class="content_title" align="right">
                                           <div id = 'saveDiv' style="display:none">
                                              <input type="hidden" name="listSubject" value="1">
                                              <input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validatetStatus();return false;" />&nbsp;
                                              <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
                                              <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printFeeStatusCSV();return false;"/>
                                           </div>
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
   </td>
 </tr>
</table>  
</form>