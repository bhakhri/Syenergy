<?php 
//it contain the template of employee salary report 
//
// Author :Abhiraj Malhotra
// Created on : 01-05-2010
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<style type="text/css">
<!--
.style1 {color: #0033CC}
.style2 {color: #FFFFFF}
-->
</style>
<!--<div id="statusDiv" style="position:absolute; overflow: auto; top:0px; width:400px; height:100px; display:none; font-family:Arial, Helvetica, sans-serif; font-size:12px"><span id="msg" style="text-align:center; background-color:#fff1a8; width:400px; padding:12px 8px 8px 8px"></span></div>-->
<form name="generateSalary" onsubmit='generateSalaryConfirm(this.month.value,this.year.value);return false;' id="heads">	
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                    <td valign="top">Setup &nbsp;&raquo;&nbsp; Payroll &nbsp;&raquo;&nbsp; Generate Salary </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
           <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
              <tr>
                 <td align="center" valign="top" class="content">
                     <!-- form table starts -->
                     <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                        <tr>
                            <td valign="top" class="contenttab_row1"><table align="center" border="0" cellpadding="0">
                                    <tr>
                                      <td style="padding-left:10px"><strong>Month:</strong></td>
									  <td style="padding-left:0px"><label>
									  <!--<input name="month" type="text" style="width:60px" id="month" value="<?php echo date('M');?>" readonly="readonly" />-->
                                      <?php
                                      $newMonth=date('M',strtotime('-1 Month',strtotime(date('M'))));
                                      ?>
									  <select name="month" id="month" onChange="showDateWiseRecords();">
                                       <option value="<?php echo date('M');?>" selected><?php echo date('M');?></option>
									   <option value="<?php echo $newMonth;?>"><?php echo $newMonth;?></option>
								      </select>
									  </label></td>
									  <td style="padding-left:10px"><strong>Year:</strong></td>
									  <td style="padding-left:0px">
									  <input name="year" type="text" style="width:40px" value="<?php echo date('Y');?>" readonly="readonly" id="year" />
									  </td>
                                      <td>
									  &nbsp;<input type="image" src="<?php  echo IMG_HTTP_PATH;?>/generate.gif" />
									  </td>   
                                    </tr>
                                  </table>
						  </td>
                       </tr>
                   </table>
				     <table width="100%" border="0" cellspacing="2" cellpadding="3" style="margin-top:10px">

							<tr id='nameRow1' style="display:none" >
							<td height="20" valign="top" class="content_title"><span id='listTitle'></span></td>
						 </tr>
							<tr id='nameRow2'>
                              <td valign="top" class="contenttab_border2" style="padding-top:0px">
							  <div id="results" class="contenttab_internal_rows">
							  </div>
							  </td>
                            </tr>
							<tr id='nameRow3' style="display:none">
							  <td align="right" valign="top" style="padding-top:0px"><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printSalarySheet(document.getElementById('month').value,document.getElementById('year').value)" title="Print" />&nbsp;<input type="image" name="printGroupSubmit" id='generateCSV' onClick="downloadSalarySheet(document.getElementById('month').value,document.getElementById('year').value);return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/></td>
						  </tr>
                        </table>
                        <!-- form table ends -->
                </td>
             </tr>
          </table>
</table>
 </form>