<?php 
//It contains the fee of student 
//
// Author :Jaineesh
// Created on : 29.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
    <tr>
        <td valign="top" colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr height="30">
                                <td class="contenttab_border" height="20" style="border-right:0px;">
                                    <?php // require_once(TEMPLATES_PATH . "/searchForm.php"); ?> 
                                    <table>
                                      <tr>
                                         <td class="content_title"><nobr>Fee Details:</nobr></td>
                                      </tr>
                                    </table>    
                                </td>
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;">
                                    <!-- <img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddSubject',315,250);blankValues();return false;" />&nbsp; -->
                                </td>
                            </tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
                            </tr>
             <tr>
                                <td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                                            <td class="content_title" valign="middle" align="right" width="20%">
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
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
<?php 
//$History : $

?>
