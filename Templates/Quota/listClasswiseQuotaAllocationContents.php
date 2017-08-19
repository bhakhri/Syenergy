<?php
//-------------------------------------------------------
// Purpose: to create time table coursewise.
// Author : PArveen Sharma
// Created on : 27.01.09
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");   
?>
<form action="" method="post" name="attendancePercentFrm" id="attendancePercentFrm" onsubmit="return false;">    
    <input type="hidden" readonly="readonly" name="classAllocationId" id="classAllocationId" value="">
    <select name="roundIds" id="roundIds" style="width:200px;display:none" class="selectfield"  >
        <option value="">Select</option>
         <?php
             require_once(BL_PATH.'/HtmlFunctions.inc.php');
             echo HtmlFunctions::getInstance()->getCounsellingRoundsData();
         ?>

    <tr>
        <td valign="top" colspan=2>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
             <tr>
                <td class="content" valign="top" >
                <table width="100%" border="0" cellspacing="0" cellpadding="5" height="405">
                <tr>
                 <td valign="top" class="content" align="center">
                 <table width="100%" border="0" cellspacing="0" cellpadding="5">
                 <tr>
                    <td class="contenttab_border2" align="center">
                    <table border="0" cellspacing="0" cellpadding="0px" width="20%">
                    <tr>    
                        <td  class="contenttab_internal_rows" nowrap><nobr><b>Date</b></nobr></td>
                        <td  class="contenttab_internal_rows" nowrap><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                        <td  class="padding"><nobr>
                            <?php 
                               require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
                               echo HtmlFunctions::getInstance()->datePicker('allocationDate',date('Y-m-d'),'',"onBlur=\"getShowRound();  return false;\""); 
                            ?></nobr>
                        </td>
                        <td  class="contenttab_internal_rows" nowrap><nobr><b>&nbsp;&nbsp;Class<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                        <td  class="contenttab_internal_rows" nowrap><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                        <td  class="padding" nowrap>  
                            <select name="classId" id="classId" style="width:280px" class="selectfield" onchange="getShowRound();  return false;">
                                <option value="">Select</option>
                                <?php
                                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                   echo HtmlFunctions::getInstance()->getCounsellingClassData();
                                ?>
                            </select>
                        </td>
                        <td  class="contenttab_internal_rows" nowrap><nobr><b>&nbsp;&nbsp;Round<?php echo REQUIRED_FIELD; ?></b></nobr></td>
                        <td  class="contenttab_internal_rows" nowrap><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                        <td  class="padding" nowrap>  
                            <select name="roundId" id="roundId" style="width:100px" class="selectfield" >
                                <option value="">Select</option>
                                <?php
                                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                   echo HtmlFunctions::getInstance()->getCounsellingRoundsData();
                                ?>
                            </select>
                        </td>
                        <td class="padding" nowrap>  
                        &nbsp;&nbsp;&nbsp;
                        <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return refreshQuotaData();  return false;" />  
                        <td>
                    </tr>
            </table>
            </td>
            </tr>                   
            <div id="results" style="display:none" >      
                <tr id="results11" style="display:none"> 
                    <td class="contenttab_row" valign="top" >   
                       <div id="resultsDiv"></div>                      
                    </td>
                </tr>
                <tr id="trAttendance" style="display:none">
                   <td  align="right" style="padding-right:5px" colspan="9">
                      <input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateAddForm(this.form);return false;" />
                  </td>
                </tr>
            </div>                  
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

<?php 
// $History: listClasswiseQuotaAllocationContents.php $
//
?>