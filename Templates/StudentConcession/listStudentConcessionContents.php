<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR student and message LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
              <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" >
             <tr>
                <td class="contenttab_border" height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Student Concession : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             
             <tr>
              <td colspan="2" style="padding:5px" valign="top" class="contenttab_row" >
               <!--Add Student Filter-->
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" >
                            <tr>
                                <td valign="top" class="contenttab_row1" align="center">
                                    <form name="allDetailsForm" action="" method="post" onSubmit="return false;">
                                        <table border='0' width='100%' cellspacing='0'>
                                            <?php echo $htmlFunctions->makeStudentDefaultSearch(); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeStudentAcademicSearch(false); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeStudentAddressSearch(); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeStudentMiscSearch(); ?>
                                            <tr>
                                                <td class="contenttab_internal_rows" colspan="15">
                                                  <table border="0" cellspacing="0px" cellpadding="2px" >
                                                    <tr>
                                                   <!-- <td class="contenttab_internal_rows"><b>Fee Cycle</b>&nbsp;<?php echo REQUIRED_FIELD ?></td>
                                                        <td width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                                        <td class="contenttab_internal_rows">
                                                            <select size="1" name="feeCycle" id="feeCycle" onchange="getFeeCylceClasses(); return false;"style="width:320px" class="selectfield" >
                                                                <option value="">Select</option>
                                                                <?php
                                                                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                                   echo HtmlFunctions::getInstance()->getFeeCycleListData();
                                                                ?>
                                                            </select>
                                                        </td>  
                                                    -->    
                                                        <td class="contenttab_internal_rows"><b>Fee Class</b>&nbsp;<?php echo REQUIRED_FIELD ?></td>
                                                        <td width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                                        <td class="contenttab_internal_rows">
                                                            <select size="1" name="feeClassId" id="feeClassId" style="width:320px" class="selectfield" >
                                                                <option value="">Select</option>
                                                                <?php
                                                                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                                   echo HtmlFunctions::getInstance()->getAllFeeClass();
                                                                ?>
                                                            </select>
                                                          </td>  
                                                          <td class="contenttab_internal_rows"><b>Fee Head</b>&nbsp;<?php echo REQUIRED_FIELD ?></td>
                                                          <td width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                                          <td class="contenttab_internal_rows" >
                                                             <select size="1"  name="feeHead" id="feeHead" style="width:320px;" class="selectfield"  >
                                                                <option value="">Select</option>
                                                                <?php
                                                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                                  echo HtmlFunctions::getInstance()->getFeeHeadData('headName',' AND isVariable=0');
                                                                 ?>
                                                               </select> 
                                                          </td>  
                                                      </tr>
                                                      <tr>  
                                                        <td class="contenttab_internal_rows"><b>Quota</b>&nbsp;</td>
                                                        <td width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                                        <td class="contenttab_internal_rows">
                                                            <select name="quota" id="quota" style="width:320px;" class="selectfield"  >
                                                                <option value="all">All</option>
                                                                 <?php
                                                                     require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                                     echo HtmlFunctions::getInstance()->getCurrentCategories($configsRecordArray[$i]['value'],' WHERE parentQuotaId=0 ',$showParentCat='1');
                                                                 ?>
                                                            </select>  
                                                        </td> 
                                                        <td class="contenttab_internal_rows"><b>Applicable To</b>&nbsp;<?php echo REQUIRED_FIELD ?></td>
                                                        <td width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                                        <td class="contenttab_internal_rows" >
                                                            <select name="leet" id="leet" style="width:320px;" class="selectfield"  >
                                                                <option value="">Select</option>    
                                                                <option value="1">Leet</option>
                                                                <option value="2">Non Leet</option>
                                                                <option value="3">Leet & Non Leet</option>
                                                            </select>  
                                                        </td>    
                                                        <td class='' align='left' valign="middle" style="padding-left:25px" >
                                                            <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(1);return false;" />
                                                        </td>
                                                     <tr>
                                                 </table>     
                                               </td>  
                                            </tr>
                                        </table>
                                    </form>
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="" valign="top" >&nbsp;
                                <div id="showList" style="display:none">
                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr id="printTrId2" style="display:none">
                                 <td class="contenttab_border">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                                   <tr>
                                    <td class="content_title">Student List : </td>
                                    <td align="right" valign="middle">
                                       <!--
                                        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport()"/>&nbsp;
                                        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printStudentCSV()"/>
                                       --> 
                                    </td>
                                   </tr>
                                  </table>
                                </td>
                                </tr>
                                <tr>
                                 <td>
                                <form name="listFrm" id="listFrm">
                                <!--Do not delete-->
                                 <input type="hidden" name="students" id="students" />
                                 <input type="hidden" name="students" id="students" />  
                                 <!--Do not delete-->
                                 <div id="scroll2" style="overflow:auto; height:420px; vertical-align:top;">
                                    <div id="results" style="width:98%; vertical-align:top;"></div>
                                 </div> 
                                </form>           
                                </td>
                               </tr>
                               <tr><td height="5px"></td></tr>
                               <tr> 
                                <td align="center">
                                <div id="divButton" style="display:none">
                                  <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateForm();return false;" />
                                </div> 
                                 </td>
                               </tr>
                               <tr><td height="5px"></td></tr>
                              </table> 
                              </div>
                             </td>
                             </tr>
                        </table>
               <!--Add Student Filter-->
             </td>
             </tr>
             <tr><td height="5px"></td></tr>
             <tr id="printTrId" style="display:none;">
             <td>
              <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
               <tr>
                <td class="content_title">&nbsp;</td>
                <td align="right" valign="middle">
                 <!--
                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport()"/>&nbsp;
                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printStudentCSV()"/> 
                 -->   
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
<!--Start Add Bank Branch Div-->
<?php floatingDiv_Start('EditStudentConcession','Edit Student Consession'); ?>
<form name="addBankBranch" id="addBankBranch" action="" method="post">
    <table width="400px" border="0" cellspacing="0" cellpadding="0" class="border">
	<tr>
		<td height="5px" colspan="3"></td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows" width="30%"><B>Student Name</B></td>
		<td class="contenttab_internal_rows"><B>:</B></td>
		<td class="contenttab_internal_rows" width="85%"><span id="studentFull">--</span></td>
    </tr>
	<tr>
		<td height="5px" colspan="3"></td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><B>Class</B></td>
		<td class="contenttab_internal_rows"><B>:</B></td>
		<td class="contenttab_internal_rows"><span id="studentClass">--</span></td>
    </tr>
	<tr>
		<td height="5px" colspan="3"></td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><B>University Roll No.</B></td>
		<td class="contenttab_internal_rows"><B>:</B></td>
		<td class="contenttab_internal_rows"><span id="studentUniv">--</span></td>
    </tr>
	<tr>
		<td height="5px" colspan="3"></td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><B>College Roll No.</B></td>
		<td class="contenttab_internal_rows"><B>:</B></td>
		<td class="contenttab_internal_rows"><span id="studentRoll">--</span></td>
    </tr>
	<tr>
		<td height="5px" colspan="3"></td>
	</tr>
	<tr>
		<td colspan="3"><div id="resultConcession"></div></td>
    </tr>
	<tr>
		<td height="5px" colspan="3"></td>
	</tr>
	<tr>
		<td height="5px" colspan="3" style="padding-right:5px;text-align:right" class="contenttab_internal_rows"><B>Total Fees</B>: <input type="text" name="actualValue" id="actualValue" class="inputbox3" style="width:60px;" readonly/></td>
	</tr>
	<tr>
		<td height="5px" colspan="3"></td>
	</tr>
	<tr>
		<td height="5px" colspan="3" align="right" style="padding-right:5px;text-align:right" class="contenttab_internal_rows"><B>Total Concession</B>: <input type="text" name="totalValue" id="totalValue" class="inputbox3" style="width:60px;" readonly/>
		<input type="hidden" name="classId" id="classId"/>
		<input type="hidden" name="studentId" id="studentId"/>
		<input type="hidden" name="feeCycleId" id="feeCycleId"/>
		</td>
	</tr>
	<tr>
		<td height="5px" colspan="3"></td>
	</tr>
	<tr>
		<td height="5px" colspan="3" style="padding-right:5px;text-align:right" class="contenttab_internal_rows"><B>Total Fees to be paid</B>: <input type="text" name="paidValue" id="paidValue" class="inputbox3" style="width:60px;" readonly/></td>
	</tr>
	<tr>
		<td height="5px" colspan="3"></td>
	</tr>
	<tr>
		<td class="contenttab_internal_rows"><B>Reason:</B></td>
    </tr>
	<tr>
		<td colspan="3"><input type="text" class="inputbox1" name="concessionReason" id="concessionReason" maxlength="240" style="width:400px;"></td>
	</tr>
	<tr>
		<td height="5px" colspan="3"></td>
	</tr>
	<tr>
		<td align="center" style="padding-right:10px" colspan="3">
		<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm1(this.form,'Add');return false;" />
		<input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditStudentConcession');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
		</td>
	</tr>
	<tr>
		<td height="5px" colspan="3"></td>
	</tr>
    </table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->
<?php
// $History: listAdminStudentMessageContents.php $
?>