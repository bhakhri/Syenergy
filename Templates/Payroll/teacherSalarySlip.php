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
                    <td valign="top">Report &nbsp;&raquo;&nbsp; Payroll&nbsp;&raquo;&nbsp; Salary Slip </td>
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
							<?php global $sessionHandler;
							global $FE;
							require_once($FE . "/Library/common.inc.php");
							require_once(BL_PATH.'/HtmlFunctions.inc.php');
							require_once(BL_PATH.'/UtilityManager.inc.php');
							require_once(MODEL_PATH.'/PayrollManager.inc.php'); 
							$userId=PayrollManager::getInstance()->getEmployeeId($sessionHandler->getSessionVariable('UserId'));
							logError("xxxxxxxxxx".$sessionHandler->getSessionVariable('UserId'));
							
							?>
                                <form name="heads" onSubmit="showSalaryDetail(<?php echo $userId[0]['employeeId'];?>,this.month.value,this.year.value);return false;" >						<?php
								if(count($userId)>0)
								{
									$monthArray=PayrollManager::getInstance()->getMonth('where generated=1 and employeeId='.$userId[0]['employeeId']);
									$yearArray=PayrollManager::getInstance()->getYear('where generated=1 and employeeId='.$userId[0]['employeeId']);	
									if(count($monthArray)>0 && count($yearArray)>0)
									{
									?>
                                  <table align="center" border="0" cellpadding="0">
                                    <tr>
                                      <td style="padding-left:10px"><strong>Month:</strong></td>
									  <td style="padding-left:0px"><label>
									  <select name="month" id="month">
									  <?php 
                  					  echo HtmlFunctions::getInstance()->makeSelectBox($monthArray,'month','month');
									  ?>
								      </select>
									  </label></td>
									  <td style="padding-left:10px"><strong>Year:</strong></td>
									  <td style="padding-left:0px">
									  <select name="year" id="year">
									  <?php
									  echo HtmlFunctions::getInstance()->makeSelectBox($yearArray,'year','year');
								      ?>
									  </select>									  </td>
                                         <td align="center" colspan="4" style="padding-left:15px" >
                                          
<input type="image" name="headsMappingSubmit" value="headsMappingSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" />                                      </td>
                                    </tr>
                                  </table>
								  <?php
								  }
								  else
								  {
								  echo "<div align=center style=padding:7px><b>No Salary Slip Generated Yet</b></div><br>";
								  }
								  }
								  else
								  {
								  echo "<div align=center style=padding:7px><b>No Salary Slip Generated Yet</b></div><br>";
								  }
								  ?>
								  
                              </form>
                          </td>
                       </tr>
                   </table>
					    <br />
						
                        <table width="100%" border="0" cellspacing="2" cellpadding="3">
							<tr id='holdSalary1' style="display:none" >
							<td class="contenttab_border2" align="center"><div class="contenttab_internal_rows" align="center" style="text-align:center">
							<b>Your Salary Has Been Held For The Selected Period<br /><br />Reason<br /><span id="holdReason"></span></b>
							</div>
							</td>
							</tr>
							<tr id='nameRow1' style='display:none;'>
							<td class="contenttab_border" height="20">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
								<tr>
									<td class="content_title"><span id='listTitle'></span></td>
								</tr>
								</table>							</td>
						 </tr>
							<tr id='nameRow2' style='display:none;'>
                              <td valign="top" class="contenttab_border2" style="padding-top:0px">
							  <table width="778px" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center">
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
                <td width="25%" style="padding:8px"><span class="style1">Name</span></td>
                <td colspan="2" style="padding:8px">:&nbsp;&nbsp;<span class="style1" id="empName">--</span></td>
              </tr>
              <tr>
                <td colspan="2" valign="top" style="padding:4px 8px 4px 8px; border-top:1px solid #000; border-right:1PX SOLID #000"><table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="48%" valign="top" style="padding:4px 8px 4px 8px; "><span class="style1">Employee Designation </span></td>
                    <td width="52%" valign="top" style="padding:4px 8px 4px 8px;">:&nbsp;&nbsp;<span class="style1" id="empDesignation">--</span></td>
                  </tr>
                  <tr>
                    <td valign="top" style="padding:4px 8px 4px 8px; "><span class="style1">Employee Department </span></td>
                    <td valign="top" style="padding:4px 8px 4px 8px; ">:&nbsp;&nbsp;<span class="style1" id="empDept">--</span></td>
                  </tr>
                  <tr>
                    <td valign="top" style="padding:4px 8px 4px 8px; "><span class="style1">Employee Code </span></td>
                    <td valign="top" style="padding:4px 8px 4px 8px; ">:&nbsp;&nbsp;<span class="style1" id="empCode">--</span></td>
                  </tr>
                  
                </table></td>
                <td width="50%" valign="top" style="padding:8px; border-top:1px solid #000;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="42%" style="padding:2px"><span class="style1">Pay for the month of </span></td>
                      <td width="3%"  ><span class="style1">:</span></td>
                      <td width="55%"  style="padding:2px" ><span class="style1" id="salMonth">--</span></td>
                    </tr>
                    <tr>
                      <td><span class="style1" style="padding:2px">Year</span></td>
                      <td><span class="style1">:</span></td>
                      <td style="padding:2px"><span class="style1" id="salYear">--</span></td>
                    </tr>
					 <tr>
                      <td class="style1" style="padding:2px">PF A/C Number </td>
                      <td class="style1">:</td>
                      <td class="style1" style="padding:2px"><span class="style1" id="pf">--</span></td>
                    </tr>
                    <tr>
                      <td class="style1" style="padding:2px">ESI Number </td>
                      <td class="style1">:</td>
                      <td class="style1" style="padding:2px"><span class="style1" id="esi">--</span></td>
                    </tr>
                    <tr>
                      <td class="style1" style="padding:2px">PAN</td>
                      <td class="style1">:</td>
                      <td class="style1" style="padding:2px"><span class="style1" id="pan">--</span></td>
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
    <br /></td>
  </tr>
</table>							  </td>
                            </tr>
							<tr id='nameRow2' style='display:none;'>
							  <td valign="top" class="contenttab_border2" style="padding-top:0px"><table width="778" border="0" cellspacing="0" cellpadding="0" align="center">
                                <tr>
                                  <td>&nbsp;</td>
                                </tr>
                              </table></td>
						  </tr>
                        </table>
                        <!-- form table ends -->
                </td>
             </tr>
          </table>
</table>
