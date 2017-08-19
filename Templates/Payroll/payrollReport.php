<?php 
//it contain the template of employee salary report 
//
// Author :Abhiraj Malhotra
// Created on : 22-04-2010
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


<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>    
                    <td valign="top">Report &nbsp;&raquo;&nbsp; Payroll &nbsp;&raquo;&nbsp;  Payroll Report</td>
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
                                <form name="heads" onSubmit="showList();return false;" >
                                  <table align="center" border="0" cellpadding="0">
                                    <tr>
                                      <td  class="contenttab_internal_rows"><nobr><b>Employee Name: </b></nobr></td>
                                      <td >
                                      <input type="text" style="width:182px" name="searchfield" id="searchfield" class="inputBox" onChange="populateDropDown();" />&nbsp;<img title="Populate Month & Year" src="<?php echo IMG_HTTP_PATH;?>/synch.gif" onclick="populateDropDown();" style="position:relative; top:4px;" /> 
                                      </td>
									  <td  style="padding-left:15px"><strong>Month:</strong></td>
									  <td  style="padding-left:0px"><label>
									  <?php
									      require_once(MODEL_PATH . "/PayrollManager.inc.php");
										  require_once(BL_PATH.'/HtmlFunctions.inc.php');
										  $monthArray=PayrollManager::getInstance()->getMonth();
										  $monthCount=count($monthArray);
										  $yearArray=PayrollManager::getInstance()->getYear();
										  $yearCount=count($yearArray);
										  ?>
									  <span id="monthDD">
									  <select name="month" id="month">
									  <?php
									  echo HtmlFunctions::getInstance()->makeSelectBox($monthArray,'month','month');
									  ?>
								      </select>
									  </span>
									  </label></td>
									  <td  style="padding-left:15px"><strong>Year:</strong></td>
									  <td  style="padding-left:0px">
									  <span id="yearDD">
									  <select name="year" id="year">
									  <?php
									  echo HtmlFunctions::getInstance()->makeSelectBox($yearArray,'year','year');
									  ?>
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
                            
							<tr id='overallSummary' style='display:none;'>
                              <td valign="top"  class="contenttab_border2" style="padding:5px">
							  <table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td class="contenttab_internal_rows">
								<fieldset class="fieldset">
							  <legend>Overall Summary</legend>
							  <b>
							  <span style="padding:7px; text-align:center" id="summary"></span></b>
							  
							  </fieldset>
								
								</td>
							  </tr>
							  
							</table>
							  </td>
                            </tr>
							<tr>
							  <td height="10px">
							  </td>
						  </tr>
							<tr id='nameRow1' style='display:none;'>
							<td class="contenttab_border" height="20">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
								<tr>
									<td class="content_title"><span id='listTitle'></span></td>
								</tr>
								</table>
							</td>
						 </tr>
							<tr id='nameRow2' style='display:none;'>
                              <td valign="top" class="contenttab_border2" style="padding-top:0px">
							  <div id="resultsDiv">
							  </div>
							  </td>
                            </tr>
                        </table>
                        <!-- form table ends -->
                </td>
             </tr>
          </table>
</table>
<?php floatingDiv_Start('ViewSalarySlip','Salary Slip'); ?>

<table width="700px" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><div style="overflow:auto; width:700px; height:400px">
      <table width="650px" border="0" cellspacing="2" cellpadding="2" align="center" style="border: 0px solid #000">
        <tr>
          <td align="center" class="contenttab_internal_rows style1" style="padding:15px"><div align="center">
              <?php
		global $sessionHandler;
		echo $sessionHandler->getSessionVariable("InstituteName");
		?>
              <br />
            <br />
            Salary slip for <span id="yearMonth"></span></div></td>
        </tr>
        <tr>
          <td style="padding:0px" class="contenttab_internal_rows"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #000">
              <tr>
                <td style="padding:8px 0px 8px 8px"><span class="style1">Name</span><span style="padding-left:128px">:&nbsp;&nbsp;</span><span class="style1" style="padding-left:0px" id="empName">--</span></td>
                
              </tr>
              <tr>
                <td colspan="2" valign="top" style="padding:4px 8px 4px 8px; border-top:1px solid #000; border-right:1px solid #000;">
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="48%" valign="top" style="padding:4px 8px 4px 0px; "><span class="style1">Employee Designation </span></td>
                    <td width="52%" valign="top" style="padding:4px 8px 4px 8px;">:&nbsp;&nbsp;<span class="style1" id="empDesignation">--</span></td>
                  </tr>
                  <tr>
                    <td valign="top" style="padding:4px 8px 4px 0px; "><span class="style1">Employee Department </span></td>
                    <td valign="top" style="padding:4px 8px 4px 8px; ">:&nbsp;&nbsp;<span class="style1" id="empDept">--</span></td>
                  </tr>
                  <tr>
                    <td valign="top" style="padding:4px 8px 4px 0px; "><span class="style1">Employee Code </span></td>
                    <td valign="top" style="padding:4px 8px 4px 8px; ">:&nbsp;&nbsp;<span class="style1" id="empCode">--</span></td>
                  </tr>
                  
                </table>
				</td>
                <td width="50%" valign="top" style="padding:8px; border-top:1px solid #000"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="42%" style="padding:3px 3px 3px 0px"><span class="style1">Pay for the month of </span></td>
                      <td width="3%"><span class="style1">:</span></td>
                      <td width="55%" style="padding:3px"><span class="style1" id="salMonth">--</span></td>
                    </tr>
                    <tr>
                      <td><span class="style1" style="padding:3px 3px 3px 0px">Year</span></td>
                      <td><span class="style1">:</span></td>
                      <td style="padding:3px"><span class="style1" id="salYear">--</span></td>
                    </tr>
					 <tr>
                      <td class="style1" style="padding:3px 3px 3px 0px">PF A/C Number </td>
                      <td class="style1">:</td>
                      <td class="style1" style="padding:3px"><span class="style1" id="pf">--</span></td>
                    </tr>
                    <tr>
                      <td class="style1" style="padding:3px 3px 3px 0px">ESI Number </td>
                      <td class="style1">:</td>
                      <td class="style1" style="padding:3px"><span class="style1" id="esi">--</span></td>
                    </tr>
                    <tr>
                      <td class="style1" style="padding:3px 3px 3px 0px">PAN Number</td>
                      <td class="style1">:</td>
                      <td class="style1" style="padding:3px"><span class="style1" id="pan">--</span></td>
                    </tr>
                </table></td>
              </tr>
              
          </table></td>
        </tr>
        <tr>
          <td style="padding:0px" class="contenttab_internal_rows">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top" class="contenttab_internal_rows" style="padding:0px"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #000">
              <tr>
                <td width="50%" style="border-right:1px solid #000;border-bottom:1px solid #000; padding:8px"><span class="style1">Earnings</span></td>
                <td style="padding:8px;border-bottom:1px solid #000;"><span class="style1">Deductions</span></td>
              </tr>
              <tr>
                <td style="border-right:1px solid #000; padding:0px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td colspan="2" class="style1" style="padding:0px"><span id="earningHeads">--</span></td>
                    </tr>
                    <tr>
                      <td width="65%" class="style1" style="border-right:1px solid #000;border-top:1px solid #000; padding:5px">Total</td>
                      <td width="35%" align="right" class="style1" style="border-top:1px solid #000; padding:5px"><span id="totEarnings">--</span> INR</td>
                    </tr>
                </table></td>
                <td style="padding:0px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td colspan="2" class="style1" style="padding:0px"><span id="deductionHeads">--</span></td>
                    </tr>
                    <tr>
                      <td width="65%" class="style1" style="border-right:1px solid #000;border-top:1px solid #000; padding:5px">Total</td>
                      <td width="35%" align="right" class="style1" style="border-top:1px solid #000; padding:5px"><span id="totDeductions">--</span> INR</td>
                    </tr>
                </table></td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td style="padding:0px" class="contenttab_internal_rows">&nbsp;</td>
        </tr>
        <tr>
          <td style="padding:0px" class="contenttab_internal_rows" align="right"><table width="300" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #000" align="right">
              <tr>
                <td width="50%" style="padding:5px"><span class="style1">Net Pay </span></td>
                <td align="right" style="padding:5px"><span class="style1"><span id="net">--</span> INR</span></td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td style="padding:0px" class="contenttab_internal_rows" align="right">&nbsp;</td>
        </tr>
        <tr>
          <td style="padding:0px" class="contenttab_internal_rows" align="right"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>&nbsp;</td>
              </tr>
          </table></td>
        </tr>
      </table>
    </div>
    <br /></td>
  </tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="40px" style="border-top:1px dashed #333"><table width="650px" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td align="right" style="padding-top:10px"><span id="downloadButtons">
		</span>
		</td>
      </tr>
    </table></td>
  </tr>
</table>


<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('holdReason','Salary Hold Reason'); ?>
<table width="400px" height="150" border="0" cellspacing="0" cellpadding="0">
   <tr>
            <td width="86" valign="top" class="contenttab_internal_rows" style="padding-top:7px;"><strong>&nbsp;&nbsp;Reason
</strong></td>
            <td width="10" valign="top" style="padding-top:7px;"><strong>:&nbsp;</strong> </td>
            <td width="305" valign="top" class="padding" style="padding-top:7px;"><span id="reason" class="contenttab_internal_rows" style="font-weight:normal"></span></td>
   </tr>
   
</table>

<?php floatingDiv_End(); ?>