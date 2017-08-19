<?php
//This file creates Html Form output in Subjects Module 
//
// Author :Abhay Kant
// Created on : 22-June-2011
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>			

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td valign="top" class="title">
        <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
                <td valign="top" class="title">
                    <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td valign="top">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
                <td valign="top" class="content" align="center">
                    <!-- form table starts -->
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" >
                        <tr>
                            <td valign="top" class="contenttab_row1">
                                  <table align="center" border="0" width="100%" cellspacing="0px" cellpadding="0px">
                                        <tr>             
                                            <td width="2%" class="contenttab_internal_rows"><nobr><b>&nbsp;Roll No.<?php echo REQUIRED_FIELD ?></B></nobr></td>
                                            <td width="2%" class="contenttab_internal_rows"><nobr><b>:&nbsp;</b></nobr></td>
                                            <td width="96%" class="contenttab_internal_rows">
                                                  <nobr><textarea cols="90" rows="3" class="inputbox1" id="rollNo" name="rollNo" ></textarea></nobr>
                                               </td>    
                                            </tr>
                                            <tr>  
                                               <td class="contenttab_internal_rows" ><nobr><b>&nbsp;Message<?php echo REQUIRED_FIELD ?>&nbsp;</b></nobr></td>
                                               <td class="contenttab_internal_rows" ><nobr><b>:&nbsp;</b></nobr></td>
                                               <td class="contenttab_internal_rows" >
                                                <nobr>
                                                <textarea cols="90" rows="3" class="inputbox1" id="message" name="message" maxlength="500" onkeyup="return ismaxlength(this)"></textarea> 
                                                </td>
                                            </tr>
                                            <tr><td></td><td></td>
                                            <td class="contenttab_internal_rows" >
                                               <input type="checkbox" id="mail_check" name="mail_check" ><FONT COLOR="#FF0000">Send Mail to parents of students regarding this message.</FONT>
                                            </td>
                                            </tr>
                                            <tr>
                                              <td class="padding" align="center" colspan="8">
                                               <nobr>
                                                <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/submit.gif" onClick="validateAddForm(); return false;" />
                                                       </nobr>  
                                               </td> 
                                            </tr>    
                                    </table>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr id='nameRow' >
                            <td class="" height="20">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                    <tr>
                                        <td width="95%" class="content_title" style="text-align:left">Block Student Details :</td>
                                        <td width="5%" align="right" style="text-align:right">
                                           <span style="text-align:right">
                                             <?php  require_once(TEMPLATES_PATH . "/searchForm.php"); ?>  
                                           </span> 
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr id='resultRow'>
                            <td colspan='1' class='contenttab_row'>
                                <div id = 'results'></div>
                            </td>
                        </tr>
                        <tr id='nameRow2'>
                            <td class="" height="20">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                    <tr>
                                        <td colspan="2" class="content_title" align="right">
                                            <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/unblock_selected.gif" onClick="unBlock();" >&nbsp;
                                            <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                                            <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <!-- form table ends -->
                </td>
            </tr>
         </table>
       </td>
     </tr>
  </table>

