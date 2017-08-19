<?php
//-------------------------------------------------------
// Purpose: to create time table coursewise.
// Author : PArveen Sharma
// Created on : 27.01.09
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<form action="" method="post" name="studentAttendanceForm" id="studentAttendanceForm" onsubmit="return false;">
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
                    <td class="contenttab_border2" align="center" style="padding-left:10px">
                       <table border="0" cellspacing="0px" cellpadding="0px" width="100%" align="left">
                            <tr><td colspan="10" height="5px"></td></tr>
                            <tr>    
                                <td style="width:5px" class="contenttab_internal_rows"><nobr><b>Report Format</b></nobr></td>
                                <td class="contenttab_internal_rows"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                <td class="contenttab_internal_rows"><nobr>  
                                  <table border="0" cellspacing="0px" cellpadding="0px" width="10%" align="left">
                                   <tr>
                                    <td class="contenttab_internal_rows"><nobr> 
                                        <select name="reportFormat" id="reportFormat" style="width:120px" class="selectfield" onchange="getConsolidated(); return false;">
                                            <option value="1">Student Wise</option>
                                            <option value="2">Head Wise</option>
                                            <option value="3">Daily Cash Report</option>
                                        </select>
                                        </nobr>
                                    </td>    
                                    <td class="contenttab_internal_rows" style="padding-left:10px">
                                       <nobr><input type="checkbox" id="consolidatedId" name="consolidatedId"></nobr>
                                    </td>    
                                    <td class="contenttab_internal_rows"><nobr>Consolidated</nobr></td>
                                   </tr>
                                   </table> 
                                </td>
                                <td class="contenttab_internal_rows" valign="top"><nobr><b>Fee Head&nbsp;:</b></nobr></td>
                                <td valign="bottom" rowspan="7" align="right"><nobr>
                                   <input type="image" name="listShow" value="listShow" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();  return false;" />&nbsp;&nbsp;
                                </nobr></td>
                              </tr>
                              <tr>    
                                <td class="contenttab_internal_rows" ><nobr><b>Fee Cycle</b></nobr></td>
                                <td class="contenttab_internal_rows" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                <td class="contenttab_internal_rows" ><nobr>  
                                    <select name="feeCycleId" id="feeCycleId" style="width:280px" class="selectfield" >
                                        <option value="">Select</option>
                                        <?php
                                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                           echo HtmlFunctions::getInstance()->getFeeCycleData();
                                        ?>
                                    </select></nobr>
                                </td>
								<a id="lk1"  class="set_default_values">Set Default Values for Report Parameters</a>
                                <td class="contenttab_internal_rows" rowspan="5" valign="top"><nobr>
                                    <select multiple name='feeHead[]' id='feeHead' size='7' class='htmlElement2' style='width:400px'>
                                      <?php
                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                        echo HtmlFunctions::getInstance()->getFeeHeadData('headName');
                                      ?>
                                      <option value='T'>Transport</option>
                                      <option value='H'>Hostel</option>
                                      <option value='FF'>Fee Fine</option>
                                      <option value='TF'>Transport Fine</option>
                                      <option value='HF'>Hostel Fine</option>
                                      <option value='AF'>Advance Fee</option>
                                      <option value='AT'>Advance Transport</option>
                                      <option value='AH'>Advance Hostel</option>
                                      <!--<option value='D'>Dues</option>--> 
                                      
                                    </select><br>Select &nbsp;
                                    <a class="allReportLink" href="javascript:makeSelection('feeHead[]','All','studentAttendanceForm');">All</a> / 
                                    <a class="allReportLink" href="javascript:makeSelection('feeHead[]','None','studentAttendanceForm');">None</a>
                                </nobr></td>                               
                              </tr>  
                              <tr>    
                                <td class="contenttab_internal_rows" ><nobr><b>Class</b></nobr></td>
                                <td class="contenttab_internal_rows" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                <td class="contenttab_internal_rows" ><nobr>  
                                    <select name="feeClassId" id="feeClassId" style="width:280px" class="selectfield" >
                                        <option value="">Select</option>
                                        <?php
                                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                           echo HtmlFunctions::getInstance()->getCollectFeeClassData();
                                        ?>
                                    </select></nobr>
                                </td>
                             </tr>
                            <tr>
                                <td class="contenttab_internal_rows"><B>Receipt Date</B> </td>
                                <td class="contenttab_internal_rows" ><B>&nbsp;:&nbsp;</B></td>
                                <td class="contenttab_internal_rows"><nbor>
                                    From
                                    <?php
                                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                       echo HtmlFunctions::getInstance()->datePicker('fromDate','');
                                     ?><span style="padding-left:10px">To&nbsp;&nbsp;<?php
                                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                       echo HtmlFunctions::getInstance()->datePicker('toDate','');
                                ?></span></nobr>
                                </td>
                             </tr>     
                             <tr>   
                                <td class="contenttab_internal_rows" ><nobr><b>Receipt No.</b></nobr></td>    
                                <td class="contenttab_internal_rows" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                <td class="contenttab_internal_rows" ><nobr>  
                                     <input type="text" maxlength="20" id="receiptNo" style="width:140px" name="receiptNo" class="inputbox"  /></nobr>
                                 </td>
                             </tr>
                             <tr>    
                                  <td class="contenttab_internal_rows" ><nobr><b>Reg./ Univ./ Roll No.</b></nobr></td>
                                  <td class="contenttab_internal_rows" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                  <td class="contenttab_internal_rows" ><nobr>  
                                     <input type="text" maxlength="20" id="rollNo" name="rollNo" style="width:140px" class="inputbox"  />
                                     </nobr>
                                  </td>
                             </tr> 
                             <tr> 
                               <td class="contenttab_internal_rows" ><nobr><b>Student Status</b></nobr></td>
                               <td class="contenttab_internal_rows" ><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                               <td class="contenttab_internal_rows" ><nobr>  
                                 <input name="studentStatus" value="1" checked="checked" type="radio">Active &nbsp;
                                 <input name="studentStatus" value="2" type="radio">Deleted &nbsp;
                                 <input name="studentStatus" value="3" type="radio">Both &nbsp;
                               </td>
                             </tr>    
                         </table>    
                         <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr id='nameRow' style='display:none;'>
                                    <td class="" height="20">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                            <tr>
                                                <td colspan="1" class="content_title"> Fee Head Wise Report Detail :</td>
                                                <td colspan="2" class="content_title" align="right">
                                                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
                                                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();return false;"/> 
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr id='resultRow' style='display:none;'>
                                    <td colspan='1' class='contenttab_row'>
                                        <div id="scroll2" style="overflow:auto; width:980px;  height:420px; vertical-align:top;">
                                           <div id="resultsDiv" style="width:98%; vertical-align:top;"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id='pageRow' style='display:none;'>    
                                  <td valign='top' colspan='1'  class=''>
                                      <table width="98%" valign='top' border="0" class='' cellspacing="0" cellpadding="0" >
                                        <tr>
                                          <td valign='top' colspan='1'  class='' align='left'>    
                                            <span id = 'pagingDiv1' class='contenttab_row1' align='left'></span>
                                          </td>
                                          <td valign='top' colspan='1'  class='' align='right'>   
                                            <span id = 'pagingDiv' align='right'></span> 
                                          </td>
                                        </tr>
                                      </table>      
                                  </td>
                                </tr>
                                <tr id='nameRow2' style='display:none;'>
                                    <td class="" height="20">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                            <tr>
                                                <td colspan="2" class="content_title" align="right">
                                                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
                                                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();return false;"/> 
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