<?php
  //-------------------------------------------------------
// Purpose: to design the layout for Hostel.
//
// Author : Gurkeerat Sidhu
// Created on : (15-09-2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//---
?>
   
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Inventory&nbsp;&raquo;&nbsp;Items Reorder Report</td>
                <td valign="bottom" align="right">
                                <form action="" method="" name="searchForm" onSubmit="sendReq(listURL,divResultName,searchFormName,'');return false;">
                    <input type="text" name="searchbox_h" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                    <input type="hidden" name="searchbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" /> &nbsp;
                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/search.gif" align="absbottom" style="margin-right: 5px;" onClick="document.searchForm.searchbox.value=document.searchForm.searchbox_h.value; sendReq(listURL,divResultName,searchFormName,''); return false;"/>
                </form>

                  </td>
            </tr>
            </table>
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
                        <td class="content_title">Items Reorder Detail : </td>
                        <td class="content_title" title="Add"></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" ><div id="results"></div></td>
             </tr>
             <tr><td height="10px"></td></tr>
         
            <tr>
           <td align="right">
             <input type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0" onClick="printReport()">
             &nbsp;<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV()"/>
          </td></tr>
          </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
   
   
