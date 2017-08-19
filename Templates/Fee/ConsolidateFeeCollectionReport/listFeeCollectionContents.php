<?php
//-------------------------------------------------------
// Purpose: to Show Contents Of List Fee Collection Report Contents
// Author : Nishu bindal
// Created on : 16-April-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form action="" method="post" name="allDetailsForm" id="allDetailsForm" onsubmit="return false;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td valign="top" class="title">
              <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");?>    
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
                              <div style="height:15px"></div>  
                              <?php require_once(TEMPLATES_PATH . "/listFeeReportContents.php");?>   
                           </td><a id="lk1" class="set_default_values">Set Default Values for Report Parameters</a>
                         </tr> 
                         <tr>
                            <td colspan='1' class='contenttab_row' id='nameRow' style='display:none;'>
                                   <div >
                                       <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20" class="contenttab_border" >
                                      <tr>
                                        <td class="content_title" align="left">Consolidated Fee Collection Report :</td>
                                      </tr>
                                    </table>
                                </div>                  
                                <div id="resultsDiv"></div>
                             </td>
                          </tr>
                          <tr id='cancelDiv1' style='display:none;'>
                            <td height='15px' align='right' style='padding-top:15px;'>
                                <!--<input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/close_big.gif"  onclick="javascript:resetResult('all');return false;" />-->
                                <input name="imageField" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onclick="printReport();return false;" type="image">&nbsp;
                                <input name="imageField" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onclick="printReportCSV();return false;" type="image">
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
</td>
</tr>
</table>  