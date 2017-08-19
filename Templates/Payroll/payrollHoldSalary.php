<?php 
//it contain the template of employee salary report 
//
// Author :Abhiraj Malhotra
// Created on : 22-04-2010
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<style type="text/css">
<!--
.style1 {color: #0033CC}
.style2 {color: #FFFFFF}
-->
</style>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                    <td valign="top">Setup &nbsp;&raquo;&nbsp;Payroll &nbsp;&raquo;&nbsp;Hold Salary  </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
           <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
              <tr>
                 <td valign="top" class="content">
                     <!-- form table starts -->
                     <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                        <tr>
                            <td valign="top" class="contenttab_row1">
                                <form name="holdSalary" onSubmit="showList();return false;" >
                                  <table align="center" border="0" cellpadding="0">
                                    <tr>
                                      <td  class="contenttab_internal_rows"><nobr><b>Employee Name: </b></nobr></td>
                                      <td >
                                      <input type="text" style="width:182px" name="searchfield" id="searchfield" class="inputBox"/> 
                                      </td>
									  <td  style="padding-left:15px"><strong>Month:</strong></td>
									  <td  style="padding-left:0px"><label>
									  <span id="monthDD">
									  <select name="month" id="month">
									  <?php
									  $month=date('m',strtotime('-1 Month', strtotime(date('Y-m-d'))));
									  //$diff=12-(int)$month;
									  for($i=(int)$month;$i<=12;$i++)
									  {
									  ?>
									  <option value="<?php echo date('M', mktime(0,0,0,$i,01,date('Y')))?>"><?php echo date('M', mktime(0,0,0,$i,01,date('Y')))?></option>
									  <?php
									  }
									  ?>
								      </select>
									  </span>
									  </label></td>
									  <td  style="padding-left:15px"><strong>Year:</strong></td>
									  <td  style="padding-left:0px">
									  <span id="yearDD">
									  <select name="year" id="year">
									  <option value="<?php echo date('Y');?>"><?php echo date('Y');?></option>
									  </select>
									  </span>
									  </td>
                                         <td  colspan="4" align="center" style="padding-left:15px" >
                                          
<input type="image" name="headsMappingSubmit" value="headsMappingSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />
                                      </td>
                                    </tr>
                                  </table>
                              </form>
                          </td>
                       </tr>
                   </table>
					    <br />
						
                        <table width="100%" border="0" cellspacing="2" cellpadding="3">
                            
							
							<tr>
							  <td height="10px">
							  </td>
						  </tr>
							<tr id='nameRow2' style="display:none">
                              <td valign="top" class="contenttab_border2" style="padding-top:0px">
							  <div id="results" class="contenttab_internal_rows">
							  </div>
							  </td>
                            </tr>
                        </table>
                        <!-- form table ends -->
                </td>
             </tr>
          </table>
</table>
<?php floatingDiv_Start('viewHoldHistory','Salary Hold/Unhold History'); ?>
<table width="500px" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">
	<div style="width:500px; height:350px;overflow:auto; ">
    <span id="historyDiv">
    </span>
	</div>
    </td>
  </tr>
</table>
<?php floatingDiv_End(); ?>
<?php floatingDiv_Start('holdUnhold','Hold/Unhold Salary'); ?>
<form name="holdUnholdSalary" action="" method="post">
<table width="400px" border="0" cellspacing="0" cellpadding="0">
   <tr>
            <td width="50" valign="top" class="contenttab_internal_rows" align="center"><strong>&nbsp;&nbsp;Reason
</strong></td>
            <td width="5" valign="top"><strong>:&nbsp;</strong> </td>
          <td width="305" valign="top" class="padding"><textarea id="holdReason" name="holdReason" style="width:242px;" rows=3 /></textarea>
          <input type="hidden" name="employeeId" /><input type="hidden" name="month" /><input type="hidden" name="year" /><br>
          <span style="font-family:verdana;font-size:10px; text-align:center; font-weight:normal">Should not exceed 60 chars</span></td>
    </tr>
   <tr>
     <td colspan="3" align="center" valign="top" class="contenttab_internal_rows"><div align="center">
       <input type="image" name="holdSalary" value="holdSalary" id="submitBtn" onclick="holdEmpSalary(document.holdUnholdSalary.employeeId.value,document.holdUnholdSalary.month.value,document.holdUnholdSalary.year.value); return false;" align="middle"/>
     </div></td>
    </tr>
</table>
</form>
<?php floatingDiv_End(); ?>
