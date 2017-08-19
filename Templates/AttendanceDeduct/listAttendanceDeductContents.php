<?php
//-------------------------------------------------------
// Purpose: to create time table coursewise.
// Author : PArveen Sharma
// Created on : 27.01.09
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
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
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
                    <td class="" align="center" >
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20" class="contenttab_border" >
                        <tr>
                            <td class="content_title" align="left">Attendance Deduct Details :</td>
                        </tr>
                    </table>
            <tr>
                <td class="contenttab_border2" valign="top" >   
                   <!-- <div class="searchhead_text" align="left">Add Rows:&nbsp;&nbsp;
                        <a href="javascript:addOneRow(1);" title="Add One Row"><b>+</b></a>
                    </div>   --> 
                    <input type="hidden" name="attendanceDeductId" id="attendanceDeductId" value="" />                        
                     <table class="padding" width="100%" border="0"  id="anyid">
                            <tbody id="anyidBody">
                              <tr class="rowheading">
                                <td class="searchhead_text" width="6%"  align="left"><nobr><b>#</b></nobr></td>
                                <td class="searchhead_text" width="15%" align="left"><nobr><b>Attendance From</b></nobr></td>
                                <td class="searchhead_text" width="15%" align="left"><nobr><b>Attendance To</b></nobr></td>
                                <td class="searchhead_text" width="15%" align="left"><nobr><b>Grade Cut Points</b></nobr></td>
                                <td class="searchhead_text" width="6%" align="center"><nobr><b>Action</b></nobr></td>
                              </tr>
                            </tbody>
                        </div>
                     </table>       
                     <table width="100%" border="0">     
                        <tr>
                            <td class="padding" >        
                                <div class="searchhead_text1" align="left">Add Rows&nbsp;:&nbsp;&nbsp;
                                     <a href="javascript:addOneRow(1);" title="Add One Row"><b>+</b></a>
                                </div>    
                            </td>
                         </tr>
                      </table>          
                 </td>
            </tr>
            <tr id="trAttendance" style="display:none">
                 <td  align="right" style="padding-right:5px" colspan="9">
                    <input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateAddForm(this.form);return false;" />&nbsp;
                    <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                    <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >&nbsp;
                </td>
           </tr>
           </tr><td style="height:5px"></td></tr>
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
