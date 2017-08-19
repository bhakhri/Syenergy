<?php
//-------------------------------------------------------
// Purpose: to create time table coursewise.
// Author : PArveen Sharma
// Created on : 27.01.09
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<form action="" method="post" name="allDetailsForm" id="allDetailsForm" onsubmit="return false;">
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
                       <table border="0" cellspacing="5px" cellpadding="5px" width="20%" align="center">
                            <tr>    
                                <td  class="contenttab_internal_rows" width="2%"><nobr><b>Fee Cycle<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                                <td  class="contenttab_internal_rows" width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                <td  class="contenttab_internal_rows" width="2%">  
                                    <select name="feeCycleId" id="feeCycleId" style="width:280px" class="selectfield" >
                                        <option value="">Select</option>
                                        <?php
                                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                           echo HtmlFunctions::getInstance()->getFeeCycleData();
                                        ?>
                                    </select>
                                </td>
                                <td class="contenttab_internal_rows"  style="padding-left:15px">  
                                 <input type="image" name="feeClasses" value="feeClasses" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return refreshData();  return false;" />  
                                <td>
                              </tr>
                         </table>    
                         <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr id='nameRow' style='display:none;'>
                                    <td class="" height="20">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                            <tr>
                                                <td colspan="1" class="content_title">Map Fee Cycle To Classes Detail :</td>
                                                <td colspan="2" class="content_title" align="right">
                                                    <input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateAddForm(this.form);return false;" /> 
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr id='resultRow' style='display:none;'>
                                    <td colspan='1' class='contenttab_row'>
                                        <div id="scroll2" style="overflow:auto; height:420px; vertical-align:top;">
                                           <div id="resultsDiv" style="width:98%; vertical-align:top;"></div>
                                        </div>
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